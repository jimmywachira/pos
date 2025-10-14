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

    // 🟢 Finalize, Hold, Resume, Cancel (we already prepared in Checkout.php earlier)
    // 👉 Here, you’d move that finalizeSale, holdSale, resumeSale, cancelSale logic.

    public function render()
    {
        return view('livewire.point-of-sale', [
            'heldSales' => Sale::where('status', 'pending')->latest()->get(),
        ]);
    }
}
