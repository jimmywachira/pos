<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{ProductVariant, Sale, SaleItem, Customer, Branch, User, Stock};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PointOfSale extends Component
{
    public $search = '';
    public $results = [];
    public $cart = [];
    public $customerId;
    public $branchId = 1;
    public $paymentMethod = 'cash';

    public $discount = 0;
    public $resumingSaleId = null;

    // 🔎 Search products
    public function updatedSearch()
    {
        $this->results = ProductVariant::where('label', 'like', "%{$this->search}%")
            ->orWhere('barcode', 'like', "%{$this->search}%")
            ->with('product')
            ->limit(5)
            ->get();
    }

    // ➕ Add to cart
    public function addToCart($variantId)
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        $this->cart[$variantId] = [
            'id' => $variant->id,
            'name' => $variant->product->name . ' - ' . $variant->label,
            'unit_price' => $variant->retail_price,
            'quantity' => ($this->cart[$variantId]['quantity'] ?? 0) + 1,
        ];
    }

    // ➖ Remove from cart
    public function removeFromCart($variantId)
    {
        unset($this->cart[$variantId]);
    }

    public function getSubtotalProperty() { return collect($this->cart)->sum(fn($i) => $i['unit_price'] * $i['quantity']); }
    public function getTaxProperty() { return $this->subtotal * 0.16; }
    public function getGrandTotalProperty() { return max(0, $this->subtotal + $this->tax - $this->discount); }

    public function incrementQuantity($variantId)
    {
        if (isset($this->cart[$variantId])) {
            $this->cart[$variantId]['quantity']++;
        }
    }

    public function decrementQuantity($variantId)
    {
        if (isset($this->cart[$variantId])) {
            if ($this->cart[$variantId]['quantity'] > 1) {
                $this->cart[$variantId]['quantity']--;
            } else {
                $this->removeFromCart($variantId);
            }
        }
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->discount = 0;
        $this->resumingSaleId = null;
    }

    // 🟢 Finalize Sale
    public function finalizeSale()
    {
        if (empty($this->cart)) {
            return;
        }

        $sale = DB::transaction(function () {
            $sale = Sale::create([
                'invoice_no' => 'INV-' . time(),
                'branch_id' => $this->branchId,
                'user_id' => Auth::id(),
                'customer_id' => $this->customerId,
                'total' => $this->grandTotal,
                'tax' => $this->tax,
                'discount' => $this->discount,
                'paid' => $this->grandTotal, // Assuming full payment
                'payment_method' => $this->paymentMethod,
                'status' => 'completed',
            ]);

            foreach ($this->cart as $item) {
                $sale->items()->create([
                    'product_variant_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['unit_price'] * $item['quantity'],
                ]);

                // Decrement stock (assuming stock management is in place)
                Stock::where('branch_id', $this->branchId)
                    ->where('product_variant_id', $item['id'])
                    ->decrement('quantity', $item['quantity']);
            }

            return $sale;
        });

        session()->flash('message', 'Sale completed successfully!');
        $this->clearCart();
        // In a real app, you might redirect to a receipt page.
        // return redirect()->route('receipt.show', $sale->id);
    }

    // ✅ Hold Sale
    public function holdSale()
    {
        // This would be similar to finalizeSale but with 'pending' status
        // For brevity, this is a simplified version.
        session()->flash('message', 'Sale held!');
        $this->clearCart();
    }

    // ↩️ Resume Sale
    public function resumeSale($saleId)
    {
        // Logic to load a held sale into the cart
        session()->flash('message', 'Sale resumed!');
    }

    // ❌ Cancel Sale
    public function cancelSale($saleId)
    {
        Sale::where('status', 'pending')->find($saleId)?->delete();
        session()->flash('message', 'Held sale cancelled!');
    }

    public function render()
    {
        return view('livewire.point-of-sale', [
            'heldSales' => Sale::where('status', 'pending')->latest()->take(5)->get(),
        ])->layout('layouts.app');
    }
}
