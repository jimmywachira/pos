<div>
    <div class="flex h-[calc(100vh-80px)] relative" x-on:print-receipt.window="window.open('/receipt/' + $event.detail.saleId, '_blank')">
        @if(!$activeShift)
        <div class="absolute inset-0 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="text-center p-8  shadow-lg 
         border">
                <ion-icon name="stop-circle-outline" class="text-5xl text-red-500 mx-auto"></ion-icon>
                <h2 class="text-2xl font-bold mt-4">No Active Shift</h2>
                <p class="text-gray-600 mt-2">You must clock in to start a new sales session.</p>
                <div class="mt-6">
                    <a href="{{ route('shifts.management') }}" wire:navigate class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Clock In</a>
                </div>
            </div>
        </div>
        @endif
        {{-- Products Section --}}
        <div @class([ 'flex flex-col p-4 transition-all duration-300' , 'w-3/5'=> !empty($cart),
            'w-full' => empty($cart),
            ])>
            <div class="mb-4 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Scan or search products..." class="w-full uppercase p-3 pl-10 border-2 border-blue-600 
             ">
                <ion-icon size="large" name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-2xl"></ion-icon>
            </div>

            {{-- Product Grid - Adjust columns based on cart visibility --}}
            <div @class([ 'flex-1 grid grid-cols-2 gap-4 overflow-y-auto p-2' , 'md:grid-cols-3 lg:grid-cols-4'=> !empty($cart),
                'md:grid-cols-4 lg:grid-cols-6' => empty($cart),
                ])>
                @forelse($products as $product)
                <div wire:click="addToCart({{ $product->id }})" class=" shadow-sm border border-gray-200 cursor-pointer hover:border-blue-500 
             hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden" x-data="{ added: false }" @click="added = true; setTimeout(() => added = false, 800)">
                    <div class="relative">
                        <img src="{{ $product->product->image_url ?? 'https://picsum.photos/seed/' . $product->id . '/300/200' }}" alt="{{ $product->product->name }}" class="w-full h-32 object-cover">
                        <div x-show="added" x-transition class="absolute inset-0 bg-blue-500/60 flex items-center justify-center text-white text-lg font-semibold"></div>
                    </div>
                    <div class="p-1 flex flex-col flex-grow">
                        <h3 class="text-md  truncate">{{ $product->product->name }} - {{ $product->label }}</h3>
                        <p class="font-semibold mt-2 text-blue-600 text-lg">Ksh {{ number_format($product->retail_price, 2) }}</p>
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
        <div class="w-2/5 flex flex-col p-2">
            <h2 class="w-full py-3 disabled:opacity-50 flex items-center justify-center gap-2">
                Cart <ion-icon class="text-blue-600 big p-2 " size="large" name="cart-outline" aria-hidden="true"></ion-icon>( {{ count($cart) }} )
            </h2>
            @error('cart')
            <div class="text-red-500 mb-2">{{ $message }}</div>
            @enderror

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto
         p-2 ">
                <table class="w-full ">
                    <thead class="backdrop-blur-sm bg-white font-bold tracking-wider uppercase">
                        <tr class="border-b-2 border-gray-200">
                            <th class="p-2 text-left">Item</th>
                            <th class="p-2 text-center">Price</th>
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-right">Total</th>
                            <th class="p-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($cart as $variantId => $item)
                        <tr>
                            <td class="p-2">
                                <p class="font-semibold text-sm">{{ $item['name'] }}</p>
                            </td>
                            <td class="p-2 text-center font-semibold align-middle">{{ number_format($item['unit_price'], 2) }}</td>
                            <td class="p-2 align-middle">
                                <div class="flex items-center justify-center">
                                    <button wire:click="decrementQuantity({{ $variantId }})" class="p-1 text-gray-500 hover:text-red-600">-</button>
                                    <span class="w-8 text-center font-bold">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQuantity({{ $variantId }})" class="p-1 text-gray-500 hover:text-blue-600">+</button>
                                </div>
                            </td>
                            <td class="p-2 text-right font-semibold align-middle">
                                Ksh {{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                            </td>
                            <td class="p-2 text-right align-middle">
                                <button wire:click="removeFromCart({{ $variantId }})" class="text-gray-400 hover:text-red-600 text-xl transition-colors">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-16 text-gray-500">
                                <ion-icon name="cart-outline" class="text-5xl"></ion-icon>
                                <p class="mt-2">Your cart is empty</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Totals Section --}}
            <div class="mt-auto p-2 border-2 border-white space-y-2 ">
                <div class="flex justify-between ">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">Ksh {{ number_format($this->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tax (16%):</span>
                    <span class="font-semibold">Ksh {{ number_format($this->tax, 2) }}</span>
                </div>
                @if ($loyaltyDiscount > 0)
                <div class="flex justify-between text-green-600">
                    <span class="text-gray-600">Loyalty Discount:</span>
                    <span class="font-semibold">-Ksh {{ number_format($this->loyaltyDiscount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-xl border-2 p-2 m-2">
                    <span class="text-blue-600 font-semibold">Total:</span>
                    <span class="text-blue-600 font-semibold">Ksh {{ number_format($this->grandTotal, 2) }}</span>
                </div>

                {{-- Payment Method --}}
                <div class="mt-4">
                    @if ($selectedCustomer)
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 
                 mb-4">
                        <p class="font-semibold">{{ $selectedCustomer->name }}</p>
                        <p>Available Points: <span class="font-bold">{{ number_format($selectedCustomer->loyalty_points, 0) }}</span></p>
                        <div class="flex items-center mt-2">
                            <input type="number" wire:model.live="loyaltyPointsToRedeem" placeholder="Points to redeem" class="w-full border-gray-300 rounded-l-md shadow-sm text-sm">
                            <button wire:click="applyLoyaltyPoints" class="bg-blue-600 text-white px-3 py-2 rounded-r-md hover:bg-blue-700 text-sm">Apply</button>
                        </div>
                    </div>
                    @endif




                    <div class="grid grid-cols-2 ">
                        <button wire:click="$set('paymentMethod', 'cash')" class="py-2 font-semibold {{ $paymentMethod === 'cash' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}" @if($isProcessingMpesa) disabled @endif>
                            Cash
                        </button>
                        <button wire:click="$set('paymentMethod', 'mpesa')" class="py-2 font-semibold {{ $paymentMethod === 'mpesa' ? 'bg-green-600 text-white' : 'bg-gray-200' }}" @if($isProcessingMpesa) disabled @endif>
                            M-Pesa
                        </button>
                    </div>
                </div>

                {{-- Amount Paid & Change --}}
                @if ($paymentMethod === 'cash')
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="amount_paid" class="font-semibold">Amount Paid:</label>
                        <input type="number" wire:model.live="amountPaid" id="amount_paid" class="w-1/2 p-2 text-right border-2 border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @if ($this->change >= 0)
                    <div class="flex justify-between text-blue-600 mt-2 border p-2 ">
                        <span>Change:</span>
                        <span>Ksh {{ number_format($this->change, 2) }}</span>
                    </div>
                    @endif
                </div>
                @endif

                @if ($paymentMethod === 'mpesa')
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="mpesa_phone" class="font-bold">Phone Number:</label>
                        <input type="number" wire:model.live="mpesaPhone" id="mpesa_phone" placeholder="254..." class="w-1/2 p-2 text-center text-2xl uppercase border-2 border-green-500 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('mpesaPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 mt-6 text-sm">
                    <button wire:click="holdSale" class="w-full py-3 bg-gray-300 text-gray-800 hover:bg-gray-300 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart)) disabled @endif>
                        <ion-icon name="pause-outline" class="text-lg"></ion-icon>
                        <span>Hold</span>
                    </button>
                    <button wire:click="clearCart" class="w-full py-3 bg-red-200 text-red-600 hover:bg-red-200 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart)) disabled @endif>
                        <ion-icon name="close-circle-outline" class="text-lg"></ion-icon>
                        <span>Cancel</span>
                    </button>
                </div>

                <button wire:click="finalizeSale" class="w-full mt-3 py-4 bg-blue-600 text-white text-lg hover:bg-blue-700 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart) || $isProcessingMpesa) disabled @endif>
                    <div wire:loading wire:target="finalizeSale" class="animate-spin h-6 w-6 border-b-2 border-white"></div>
                    @if($isProcessingMpesa)
                    <div class="animate-pulse flex items-center gap-2 justify-center">
                        <ion-icon name="time-outline" class="text-2xl"></ion-icon>
                        <span>Waiting for M-Pesa confirmation...</span>
                    </div>

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
        <div class="absolute bottom-4 left-4  p-4 
     shadow-lg border border-gray-200 max-w-sm">
            <h3 class="mb-2 flex items-center gap-2">
                <ion-icon name="pause-circle-outline"></ion-icon> Held Sales
            </h3>
            <ul class=" space-y-2">
                @foreach($heldSales as $sale)
                <li class="flex justify-between items-center">
                    <span>{{ $sale->invoice_no }} (Ksh {{ number_format($sale->total, 2) }})</span>
                    <div class="space-x-1">
                        <button wire:click="resumeSale({{ $sale->id }})" class="px-2 py-1 bg-blue-500 text-white hover:bg-blue-600 text-xs">Resume</button>
                        <button wire:click="cancelSale({{ $sale->id }})" class="px-2 py-1 bg-red-500 text-white hover:bg-red-600 text-xs">Cancel</button>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Toast Notifications --}}
        <div x-data="{ msg: '', type: '', show: false, showToast(message, t = 'success') {
        this.msg = message; this.type = t; this.show = true;
        setTimeout(() => this.show = false, 3000);
    }}" x-on:flash-message.window="showToast($event.detail.message, $event.detail.type)" x-show="show" x-transition class="fixed top-5 right-5 z-50 px-4 py-3 
     shadow-lg text-white text-sm" :class="type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-yellow-500')">
            <span x-text="msg"></span>
        </div>
        {{-- <div x-data="{
        flashMessage: '',
        flashType: '',
        showAlert: false,
        show(message, type = 'success') {
            this.flashMessage = message; this.flashType = type; this.showAlert = true;
            setTimeout(() => this.showAlert = false, 3000);
        }
    }" x-on:flash-message.window="show($event.detail.message, $event.detail.type)" x-show="showAlert" x-transition class="absolute top-5 right-5 px-4 py-2 
     text-white" :class="flashType === 'success' ? 'bg-blue-500' : 'bg-red-500'">
        <span x-text="flashMessage"></span>
    </div> --}}

    </div>
</div>
