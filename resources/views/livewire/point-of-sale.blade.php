<div class="flex h-[calc(100vh-80px)] bg-inherit relative" x-data="{
        init() {
            window.Echo.private('user.{{ auth()->id() }}')
                .listen('MpesaPaymentSuccess', (e) => {
                    $wire.mpesaPaymentSuccess(e.sale);
                });
        }
    }" x-on:print-receipt.window="window.open('/receipt/' + $event.detail.saleId, '_blank')">

    {{-- Products Section --}}
    <div class="@if(!empty($cart)) w-3/5 @else w-full @endif flex flex-col p-4 transition-all duration-300">
        <div class="mb-4 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Scan or search products..." class="w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <ion-icon name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></ion-icon>
        </div>

        {{-- Product Grid - Adjust columns based on cart visibility --}}
        <div class="flex-1 grid grid-cols-2 @if(!empty($cart)) md:grid-cols-3 lg:grid-cols-4 @else md:grid-cols-4 lg:grid-cols-6 @endif gap-4 overflow-y-auto p-2">
            @forelse($products as $product)
            <div wire:click="addToCart({{ $product->id }})" class="bg-white rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:border-blue-500 hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden">
                <img src="{{ $product->product->image_url ?? 'https://picsum.photos/seed/' . $product->id . '/300/200' }}" alt="{{ $product->product->name }}" class="w-full h-32 object-cover">
                <div class="p-3 flex flex-col flex-grow">
                    <h3 class="text-md truncate">{{ $product->product->name }} - {{ $product->label }}</h3>
                    <p class="font-semibold mt-auto text-blue-600 text-lg">Ksh {{ number_format($product->retail_price, 2) }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-10 text-gray-500">
                <p class="text-gray-500">No products found.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Cart Section (conditional) --}}
    @if(!empty($cart))
    <div class="w-2/5 flex flex-col p-4 text-black bg-white border-l-2 border-gray-200 shadow-lg">
        <h2 class="text-2xl mb-4" style="font-family: 'Delicious Handrawn', sans-serif;">Current Sale</h2>
        @error('cart')
        <div class="text-red-500 mb-2 ">{{ $message }}</div>
        @enderror

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto -mx-4 pr-2">
            {{-- Cart Header --}}
            <div class="flex items-center px-4 pb-2 border-b-2 text-xs text-gray-400 tracking-wider">
                <div class="flex-grow">Item</div>
                <div class="w-20 text-center">Qty</div>
                <div class="w-24 text-right">Total</div>
                <div class="w-10 text-right"></div>
            </div>

            <ul class="divide-y divide-gray-100">
                @forelse($cart as $variantId => $item)
                <li class="py-3 flex items-center px-4">
                    <div class="flex-grow">
                        <p class=text-sm">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-500">@ Ksh {{ number_format($item['unit_price'], 2) }}</p>
                    </div>
                    <div class="w-20 flex items-center justify-center gap-2">
                        <button wire:click="decrementQuantity({{ $variantId }})" class="p-1 border rounded-full w-6 h-6 flex items-center justify-center hover:bg-gray-200">-</button>
                        <span class="w-8 text-center">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $variantId }})" class="p-1 border rounded-full w-6 h-6 flex items-center justify-center hover:bg-gray-200">+</button>
                    </div>
                    <div class="w-24 text-right">
                        Ksh {{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                    </div>
                    <button wire:click="removeFromCart({{ $variantId }})" class="w-10 text-right text-gray-400 hover:text-red-600 text-xl">
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                </li>
                @empty
                <li class="text-center py-10 text-gray-500 italic">Cart is empty — start adding products</li>
                @endforelse
            </ul>
        </div>

        {{-- Totals Section --}}
        <div class="mt-auto pt-4 border-t-2 border-gray-100 space-y-2 text-md">
            <div class="flex justify-between">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-semibold">Ksh {{ number_format($this->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Tax (16%):</span>
                <span class="font-semibold">Ksh {{ number_format($this->tax, 2) }}</span>
            </div>
            <div class="flex justify-between text-xl border-t pt-2 mt-2" style="font-family: 'Delicious Handrawn', sans-serif;">
                <span class="text-blue-600">Total:</span>
                <span class="text-blue-600">Ksh {{ number_format($this->grandTotal, 2) }}</span>
            </div>

            {{-- Payment Method --}}
            <div class="mt-4">
                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="$set('paymentMethod', 'cash')" class="py-2 font-semibold rounded-md {{ $paymentMethod === 'cash' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}" @if($isProcessingMpesa) disabled @endif>
                        Cash
                    </button>
                    <button wire:click="$set('paymentMethod', 'mpesa')" class="py-2 font-semibold rounded-md {{ $paymentMethod === 'mpesa' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}" @if($isProcessingMpesa) disabled @endif>
                        M-Pesa
                    </button>
                </div>
            </div>

            {{-- Amount Paid & Change --}}
            @if ($paymentMethod === 'cash')
            <div class="mt-4 space-y-2">
                <div class="flex justify-between items-center">
                    <label for="amount_paid" class="font-semibold">Amount Paid:</label>
                    <input type="number" wire:model.live="amountPaid" id="amount_paid" class="w-1/2 p-2 text-right text-lg border-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                @if ($this->change >= 0)
                <div class="flex justify-between text-lg text-green-600">
                    <span>Change:</span>
                    <span>Ksh {{ number_format($this->change, 2) }}</span>
                </div>
                @endif
            </div>
            @endif

            @if ($paymentMethod === 'mpesa')
            <div class="mt-4 space-y-2">
                <div class="flex justify-between items-center">
                    <label for="mpesa_phone" class="font-semibold">Phone Number:</label>
                    <input type="number" wire:model.live="mpesaPhone" id="mpesa_phone" placeholder="254..." class="w-1/2 p-2 text-right text-lg border-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                @error('mpesaPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="grid grid-cols-2 gap-3 mt-6 text-sm">
                <button wire:click="holdSale" class="w-full py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart)) disabled @endif>
                    <ion-icon name="pause-outline" class="text-lg"></ion-icon>
                    <span>Hold</span>
                </button>
                <button wire:click="clearCart" class="w-full py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart)) disabled @endif>
                    <ion-icon name="close-circle-outline" class="text-lg"></ion-icon>
                    <span>Cancel</span>
                </button>
            </div>

            <button wire:click="finalizeSale" class="w-full mt-3 py-4 bg-blue-600 text-white text-lg rounded-lg hover:bg-blue-700 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart) || $isProcessingMpesa) disabled @endif>
                <div wire:loading wire:target="finalizeSale" class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
                @if($isProcessingMpesa)
                <ion-icon name="time-outline" class="text-2xl"></ion-icon>
                <span>Waiting for Payment...</span>
                @else
                <ion-icon name="checkmark-done-outline" class="text-2xl"></ion-icon>
                <span>Complete Sale</span>
                @endif
            </button>
        </div>
    </div>
    @endif

    {{-- Held Sales --}}
    @if($heldSales->count() > 0)
    <div class="absolute bottom-4 left-4 bg-white p-4 rounded-lg shadow-lg border border-gray-200 max-w-sm">
        <h3 class=mb-2 flex items-center gap-2">
            <ion-icon name="pause-circle-outline"></ion-icon> Held Sales
        </h3>
        <ul class=" space-y-2">
            @foreach($heldSales as $sale)
            <li class="flex justify-between items-center">
                <span>{{ $sale->invoice_no }} (Ksh {{ number_format($sale->total, 2) }})</span>
                <div class="space-x-1">
                    <button wire:click="resumeSale({{ $sale->id }})" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">Resume</button>
                    <button wire:click="cancelSale({{ $sale->id }})" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Cancel</button>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Toast Notifications --}}
    <div x-data="{
        flashMessage: '',
        flashType: '',
        showAlert: false,
        show(message, type = 'success') {
            this.flashMessage = message; this.flashType = type; this.showAlert = true;
            setTimeout(() => this.showAlert = false, 3000);
        }
    }" x-on:flash-message.window="show($event.detail.message, $event.detail.type)" x-show="showAlert" x-transition class="absolute top-5 right-5 px-4 py-2 rounded-lg text-white" :class="flashType === 'success' ? 'bg-green-500' : 'bg-red-500'">
        <span x-text="flashMessage"></span>
    </div>
</div>
