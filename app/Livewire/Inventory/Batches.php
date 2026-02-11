<?php

namespace App\Livewire\Inventory;

use App\Models\Branch;
use App\Models\Stock;
use App\Models\StockAdjustment;
use App\Models\StockMovement;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class Batches extends Component
{
    use WithPagination;

    // Table properties
    public $search = '';
    public $branchId = 'all';
    public $expiryFilter = 'all';
    public $sortBy = 'expiry_date';
    public $sortDirection = 'asc';

    // Modal properties
    public $showStockModal = false;
    public $editingStockId;
    public $editingStockVariantName;
    public $currentQuantity;

    public $showTransferModal = false;
    public $transferStockId;
    public $transferStock;
    public $transferStockVariantName;
    public $transferAvailableQuantity;
    public $transferBranchId;
    public $transferQuantity;

    // Form fields for modal
    public $adjustmentType = 'add';
    public $adjustmentQuantity;
    public $adjustmentReason;

    protected $rules = [
        'adjustmentType' => 'required|in:add,set,remove',
        'adjustmentQuantity' => 'required|integer|min:0',
        'adjustmentReason' => 'nullable|string|max:255',
        'transferBranchId' => 'required|integer',
        'transferQuantity' => 'required|integer|min:1',
    ];
  
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    public function editStock(Stock $stock)
    {
        $this->reset(['adjustmentType', 'adjustmentQuantity', 'adjustmentReason']);
        $this->editingStockId = $stock->id;
        $this->editingStockVariantName = $stock->productVariant->product->name . ' - ' . $stock->productVariant->label;
        $this->currentQuantity = $stock->quantity;
        $this->showStockModal = true;
    }

    public function saveStockAdjustment()
    {
        $this->validate();

        $stock = Stock::findOrFail($this->editingStockId);
        $previousQuantity = $stock->quantity;
        $movementType = null;
        $movementDirection = null;
        $movementQuantity = 0;

        switch ($this->adjustmentType) {
            case 'add':
                $stock->increment('quantity', $this->adjustmentQuantity);
                $movementType = 'stock_in';
                $movementDirection = 'in';
                $movementQuantity = $this->adjustmentQuantity;
                break;
            case 'remove':
                $stock->decrement('quantity', $this->adjustmentQuantity);
                $movementType = 'stock_out';
                $movementDirection = 'out';
                $movementQuantity = $this->adjustmentQuantity;
                break;
            case 'set':
                $stock->update(['quantity' => $this->adjustmentQuantity]);
                $delta = $this->adjustmentQuantity - $previousQuantity;
                if ($delta !== 0) {
                    $movementType = 'adjustment';
                    $movementDirection = $delta > 0 ? 'in' : 'out';
                    $movementQuantity = abs($delta);
                }
                break;
        }

        if ($movementType && $movementQuantity > 0) {
            StockMovement::create([
                'product_variant_id' => $stock->product_variant_id,
                'branch_id' => $stock->branch_id,
                'user_id' => Auth::id(),
                'transaction_type' => $movementType,
                'direction' => $movementDirection,
                'quantity' => $movementQuantity,
                'reference_type' => 'stock_adjustment',
                'reference_id' => $stock->id,
                'notes' => $this->adjustmentReason ?: 'Manual adjustment',
            ]);
        }

        session()->flash('success', 'Stock quantity updated successfully.');
        $this->closeModal();
    }

    public function writeOffExpired($stockId)
    {
        $stock = Stock::with('productVariant')->findOrFail($stockId);

        if (! $stock->expiry_date || Carbon::parse($stock->expiry_date)->isFuture()) {
            $this->dispatch('flash-message', message: 'This stock batch is not expired.', type: 'error');
            return;
        }

        if ($stock->quantity <= 0) {
            $this->dispatch('flash-message', message: 'No quantity to write off.', type: 'warning');
            return;
        }

        DB::transaction(function () use ($stock) {
            $quantity = $stock->quantity;
            $unitCost = $stock->productVariant->cost_price ?? 0;
            $totalCost = $quantity * $unitCost;

            $stock->update(['quantity' => 0]);

            StockAdjustment::create([
                'stock_id' => $stock->id,
                'product_variant_id' => $stock->product_variant_id,
                'branch_id' => $stock->branch_id,
                'user_id' => Auth::id(),
                'type' => 'loss',
                'quantity' => $quantity,
                'reason' => 'expired',
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
            ]);

            StockMovement::create([
                'product_variant_id' => $stock->product_variant_id,
                'branch_id' => $stock->branch_id,
                'user_id' => Auth::id(),
                'transaction_type' => 'loss',
                'direction' => 'out',
                'quantity' => $quantity,
                'reference_type' => 'stock_adjustment',
                'reference_id' => $stock->id,
                'notes' => 'Expired stock write-off',
            ]);
        });

        $this->dispatch('flash-message', message: 'Expired stock removed and logged as loss.', type: 'success');
    }

    public function render()
    {
        $stocks = Stock::with(['productVariant.product', 'branch'])
            ->when($this->search, function ($query) {
                $query->whereHas('productVariant.product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->branchId !== 'all', fn ($q) => $q->where('branch_id', $this->branchId))
            ->when($this->expiryFilter !== 'all', function ($query) {
                if ($this->expiryFilter === 'expired') {
                    $query->whereNotNull('expiry_date')->where('expiry_date', '<', now());
                } elseif ($this->expiryFilter === 'expiring_soon') {
                    $query->whereNotNull('expiry_date')->whereBetween('expiry_date', [now(), now()->addDays(30)]);
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        // Get all branches excluding the currently selected stock's branch
        $excludeBranchId = null;
        if ($this->transferStockId) {
            $transferStock = Stock::find($this->transferStockId);
            if ($transferStock) {
                $excludeBranchId = $transferStock->branch_id;
            }
        }
        
        $branches = $excludeBranchId 
            ? Branch::where('id', '!=', $excludeBranchId)->get()
            : Branch::all();

        return view('livewire.inventory.batches', [
            'stocks' => $stocks,
            'branches' => $branches,
        ]);
    }

    public function closeModal()
    {
        $this->showStockModal = false;
    }

    public function openTransferModal(Stock $stock)
    {
        $this->reset(['transferBranchId', 'transferQuantity']);
        $this->transferStockId = $stock->id;
        $this->transferStockVariantName = $stock->productVariant->product->name . ' - ' . $stock->productVariant->label;
        $this->transferAvailableQuantity = $stock->quantity;
        $this->showTransferModal = true;
        
        $this->transferStock = $stock;
        
    }

    public function transferStock()
    {
        // Validate only transfer-related fields
        $this->validate([
            'transferBranchId' => 'required|integer',
            'transferQuantity' => 'required|integer|min:1',
        ]);

        $stock = Stock::with('productVariant.product', 'branch')->findOrFail($this->transferStockId);
        $destinationBranch = Branch::find($this->transferBranchId);

        if (! $destinationBranch) {
            $this->addError('transferBranchId', 'Please select a valid destination branch.');
            return;
        }

        if ((int) $destinationBranch->id === (int) $stock->branch_id) {
            $this->addError('transferBranchId', 'Destination branch must be different from the source.');
            return;
        }

        $quantity = (int) $this->transferQuantity;
        if ($quantity <= 0 || $quantity > $stock->quantity) {
            $this->addError('transferQuantity', 'Transfer quantity must be between 1 and the available stock.');
            return;
        }

        DB::transaction(function () use ($stock, $destinationBranch, $quantity) {
            $stock->decrement('quantity', $quantity);

            $destinationStock = Stock::firstOrCreate(
                [
                    'branch_id' => $destinationBranch->id,
                    'product_variant_id' => $stock->product_variant_id,
                ],
                ['quantity' => 0]
            );

            $destinationStock->increment('quantity', $quantity);

            StockMovement::create([
                'product_variant_id' => $stock->product_variant_id,
                'branch_id' => $stock->branch_id,
                'user_id' => Auth::id(),
                'transaction_type' => 'transfer_out',
                'direction' => 'out',
                'quantity' => $quantity,
                'reference_type' => 'stock_transfer',
                'notes' => 'Transfer to ' . $destinationBranch->name,
            ]);

            StockMovement::create([
                'product_variant_id' => $stock->product_variant_id,
                'branch_id' => $destinationBranch->id,
                'user_id' => Auth::id(),
                'transaction_type' => 'transfer_in',
                'direction' => 'in',
                'quantity' => $quantity,
                'reference_type' => 'stock_transfer',
                'notes' => 'Transfer from ' . $stock->branch->name,
            ]);
        });

        session()->flash('success', 'Stock transferred successfully.');
        $this->closeTransferModal();
    }

    public function closeTransferModal()
    {
        $this->showTransferModal = false;
    }
}
