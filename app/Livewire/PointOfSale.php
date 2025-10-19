<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{ProductVariant, Sale, SaleItem, Customer, Branch, User, Stock};
use Illuminate\Support\Facades\DB;
use Safaricom\Mpesa\Mpesa;
use Illuminate\Support\Facades\Auth;

class PointOfSale extends Component
{
    use WithPagination;

    public $search = '';
    public $cart = [];
    public $customerId;
    public $branchId = 1;
    public $amountPaid = 0;
    public $paymentMethod = 'cash';
    public $discount = 0;
    public $mpesaPhone;
    public $resumingSaleId = null;

    // State properties
    public $isProcessingMpesa = false;

    protected $paginationTheme = 'tailwind';

    // 🧠 Computed properties
    protected function getListeners()
    {
        return ['mpesaPaymentSuccess'];
    }

    public function getProductsProperty()
    {
        $query = ProductVariant::with('product')
            ->when($this->search, function ($q) {
                $q->where('label', 'like', "%{$this->search}%")
                    ->orWhere('barcode', 'like', "%{$this->search}%")
                    ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$this->search}%"));
            });

        return $query->paginate(12); // Paginate product grid
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(fn($i) => $i['unit_price'] * $i['quantity']);
    }

    public function getTaxProperty()
    {
        return $this->subtotal * 0.16;
    }

    public function getGrandTotalProperty()
    {
        return max(0, $this->subtotal + $this->tax - $this->discount);
    }

    public function getChangeProperty()
    {
        $paid = floatval($this->amountPaid);

        if ($this->paymentMethod === 'cash' && $paid > 0) {
            return $paid - $this->grandTotal;
        }
        return 0;
    }

    // 🛒 Add to cart
    public function addToCart($variantId)
    {
        $stock = Stock::where('product_variant_id', $variantId)
                      ->where('branch_id', $this->branchId)
                      ->first();

        if (!$stock || $stock->quantity <= 0) {
            $this->dispatch('flash-message', message: 'Product is out of stock.', type: 'error');
            return;
        }

        if (isset($this->cart[$variantId])) {
            // Item is already in cart, increment quantity
            if ($this->cart[$variantId]['quantity'] < $stock->quantity) {
                $this->cart[$variantId]['quantity']++;
            } else {
                $this->dispatch('flash-message', message: 'No more stock available for this product.', type: 'error');
            }
        } else {
            // Add new item to cart
            $variant = ProductVariant::with('product')->findOrFail($variantId);
            $this->cart[$variantId] = [
                'id' => $variant->id,
                'name' => "{$variant->product->name} - {$variant->label}",
                'unit_price' => $variant->retail_price,
                'quantity' => 1,
            ];
        }
    }

    // ➖ Remove / update
    public function removeFromCart($variantId)
    {
        unset($this->cart[$variantId]);
    }

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
        $this->isProcessingMpesa = false;
        $this->discount = 0;
        $this->amountPaid = 0;
        $this->resumingSaleId = null;
    }

    // ✅ Finalize Sale
    public function finalizeSale()
    {
        if (empty($this->cart)) {
            $this->dispatch('flash-message', message: 'Cart is empty!', type: 'warning');
            return;
        }

        if ($this->paymentMethod === 'mpesa') {
            $this->initiateMpesaPayment();
        } else { // Cash payment
            if (floatval($this->amountPaid) < $this->grandTotal) {
                $this->dispatch('flash-message', message: 'Amount paid is less than the total.', type: 'error');
                return;
            }

            $sale = DB::transaction(function () {
                $sale = Sale::create([
                    'invoice_no' => 'INV-' . now()->format('YmdHis'),
                    'branch_id' => $this->branchId,
                    'user_id' => Auth::id(),
                    'customer_id' => $this->customerId,
                    'total' => $this->grandTotal,
                    'tax' => $this->tax,
                    'discount' => $this->discount,
                    'paid' => floatval($this->amountPaid),
                    'payment_method' => 'cash',
                    'status' => 'completed',
                ]);

                foreach ($this->cart as $item) {
                    $sale->items()->create([
                        'product_variant_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'line_total' => $item['unit_price'] * $item['quantity'],
                    ]);

                    Stock::where('branch_id', $this->branchId)
                        ->where('product_variant_id', $item['id'])
                        ->decrement('quantity', $item['quantity']);
                }
                return $sale;
            });

            $this->dispatch('print-receipt', saleId: $sale->id);
            $this->clearCart();
        }
    }

    public function initiateMpesaPayment()
    {
        $this->validate(['mpesaPhone' => 'required|numeric|digits:12']);

        $invoiceNo = 'INV-' . now()->format('YmdHis');

        // Create a pending sale record first
        $sale = Sale::create([
            'invoice_no' => $invoiceNo,
            'branch_id' => $this->branchId,
            'user_id' => Auth::id(),
            'customer_id' => $this->customerId,
            'total' => $this->grandTotal,
            'tax' => $this->tax,
            'discount' => $this->discount,
            'paid' => $this->grandTotal,
            'payment_method' => 'mpesa',
            'status' => 'pending_payment',
        ]);

        foreach ($this->cart as $item) {
            $sale->items()->create([
                'product_variant_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'line_total' => $item['unit_price'] * $item['quantity'],
            ]);
        }

        $mpesa = new Mpesa();
        $stkPush = $mpesa->STKPush(1, $this->mpesaPhone, $invoiceNo, $invoiceNo);

        $sale->update(['meta' => ['checkout_request_id' => $stkPush['CheckoutRequestID']]]);

        $this->isProcessingMpesa = true;
        $this->dispatch('flash-message', message: 'STK Push sent. Waiting for payment...', type: 'success');
    }

    public function mpesaPaymentSuccess($sale)
    {
        $this->isProcessingMpesa = false;
        $this->dispatch('flash-message', message: 'M-Pesa payment successful!', type: 'success');
        $this->dispatch('print-receipt', saleId: $sale['id']);
        $this->clearCart();
    }
    // 💾 Hold Sale
    public function holdSale()
    {
        session()->flash('message', 'Sale held temporarily!');
        $this->clearCart();
    }

    // ↩️ Resume Sale
    public function resumeSale($saleId)
    {
        $sale = Sale::with('items.productVariant.product')->findOrFail($saleId);
        $this->resumingSaleId = $saleId;

        $this->cart = $sale->items->mapWithKeys(fn($item) => [
            $item->product_variant_id => [
                'id' => $item->product_variant_id,
                'name' => "{$item->productVariant->product->name} - {$item->productVariant->label}",
                'unit_price' => $item->unit_price,
                'quantity' => $item->quantity,
            ],
        ])->toArray();
    }

    // ❌ Cancel Sale
    public function cancelSale($saleId)
    {
        Sale::where('status', 'pending')->find($saleId)?->delete();
        session()->flash('message', 'Held sale cancelled!');
    }

    // 🎯 Render
    public function render()
    {
        return view('livewire.point-of-sale', [
            'products' => $this->products,
            'heldSales' => Sale::where('status', 'pending')->latest()->take(5)->get(),
            'customers' => Customer::all(),
        ])->layout('layouts.app');
    }
}
