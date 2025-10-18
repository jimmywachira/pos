<?php

namespace App\Livewire\Inventory;

use App\Models\Branch;
use App\Models\Stock;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

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

    // Form fields for modal
    public $adjustmentType = 'add';
    public $adjustmentQuantity;
    public $adjustmentReason;

    protected $rules = [
        'adjustmentType' => 'required|in:add,set,remove',
        'adjustmentQuantity' => 'required|integer|min:0',
        'adjustmentReason' => 'nullable|string|max:255',
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

        switch ($this->adjustmentType) {
            case 'add':
                $stock->increment('quantity', $this->adjustmentQuantity);
                break;
            case 'remove':
                $stock->decrement('quantity', $this->adjustmentQuantity);
                break;
            case 'set':
                $stock->update(['quantity' => $this->adjustmentQuantity]);
                break;
        }

        // Here you would typically log the stock movement for auditing purposes
        // e.g., StockMovement::log($stock, $this->adjustmentType, $this->adjustmentQuantity, $this->adjustmentReason);

        session()->flash('success', 'Stock quantity updated successfully.');
        $this->closeModal();
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

        return view('livewire.inventory.batches', [
            'stocks' => $stocks,
            'branches' => Branch::all(),
        ])->layout('layouts.app');
    }

    public function closeModal()
    {
        $this->showStockModal = false;
    }
}
