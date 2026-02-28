<div>
    <div class="flex h-[calc(100vh-80px)] relative" x-on:print-receipt.window="window.open('/receipt/' + $event.detail.saleId, '_blank')">
        @if(!$activeShift)
        <div class="absolute inset-0 bg-white/20 backdrop-blur-lg z-10 flex items-center justify-center">
            <div class="text-center p-8 shadow-lg border">
                <ion-icon name="stop-circle-outline" class="text-5xl text-red-500 mx-auto"></ion-icon> 
                <h2 class="font-semibold mt-4">No Active Shift</h2>
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
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Scan or search products..." class="w-full p-3 pl-10 border-2 border-blue-600 
             ">
                <ion-icon size="large" name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2"></ion-icon>
            </div>

            <!-- Category Filter -->
            <div class="mb-4">
                <div class="space-x-2 overflow-x-auto pb-2 m-2 px-2">
                    <button wire:click="filterByCategory(null)" class="whitespace-nowrap px-4 py-2  transition-colors {{ !$selectedCategory ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        All Products
                    </button>
                    @foreach($categories as $category)
                    <button wire:click="filterByCategory({{ $category->id }})" class="whitespace-nowrap px-4 py-2  transition-colors {{ $selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Product Grid - Adjust columns based on cart visibility --}}
            <div @class([ 'flex-1 grid grid-cols-2 gap-4 overflow-y-auto p-2' , 'md:grid-cols-3 lg:grid-cols-4'=> !empty($cart),
                'md:grid-cols-4 lg:grid-cols-6' => empty($cart),
                ])>
                @forelse($products as $product)
                <div wire:click="addToCart({{ $product->id }})" class="rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:border-blue-500 hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden" x-data="{ added: false }" @click="added = true; setTimeout(() => added = false, 800)">
                    <div class="relative">
                        <div class="w-full h-32 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                            <img class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 hover:scale-105" src="{{ $product->product->image_url ?: 'https://picsum.photos/seed/' . $product->id . '/900/700' }}" alt="{{ $product->product->name }}">
                        </div>
                        <div x-show="added" x-transition class="absolute inset-0 bg-blue-500/60 flex items-center justify-center text-white text-lg font-semibold">
                            <ion-icon name="checkmark-outline" class="text-4xl"></ion-icon>
                        </div>
                    </div>
                    <div class="p-3 flex flex-col flex-grow">
                        <h3 class="font-bold text-gray-800 truncate text-base">{{ $product->product->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $product->label }}</p>
                        <p class="font-semibold mt-auto pt-2 text-blue-600 text-lg">Ksh {{ number_format($product->retail_price) }}</p>
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

            {{-- Held Sales --}}
            @if($heldSales->count() > 0)
            <div class="mt-6 p-4 shadow-lg border border-gray-200">
                <h3 class="mb-2 flex items-center gap-2">
                    <ion-icon name="pause-circle-outline"></ion-icon> Held Sales
                </h3>
                <ul class="space-y-2">
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
        </div>

        {{-- Cart Section (conditional) --}}
        @if(!empty($cart))
        <div class="w-2/5 flex flex-col bg-gray-50 border-l border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <ion-icon class="text-blue-600" name="cart-outline"></ion-icon>
                        Current Order ({{ count($cart) }})
                    </h2>
                    <button wire:click="clearCart" class="text-sm text-red-500 hover:underline font-semibold flex items-center gap-1">
                        <ion-icon name="close-circle-outline"></ion-icon>
                        Clear Cart
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                @forelse($cart as $variantId => $item)
                <div class="flex items-center gap-4 p-3 bg-white rounded-lg shadow-sm">
                    <div class="flex-1">
                        <p class="font-bold text-base text-gray-800">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500">Ksh {{ number_format($item['unit_price'], 2) }}</p>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <button wire:click="decrementQuantity({{ $variantId }})" class="p-1 rounded-full bg-gray-200 text-gray-600 hover:bg-red-200 hover:text-red-700 transition-colors">
                            <ion-icon name="remove-outline"></ion-icon>
                        </button>
                        <span class="w-8 text-center font-bold text-base">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $variantId }})" class="p-1 rounded-full bg-gray-200 text-gray-600 hover:bg-blue-200 hover:text-blue-700 transition-colors">
                            <ion-icon name="add-outline"></ion-icon>
                        </button>
                    </div>
                    <p class="w-24 text-right font-bold text-base text-gray-800">Ksh {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</p>
                    <button wire:click="removeFromCart({{ $variantId }})" class="text-gray-400 hover:text-red-600 text-xl transition-colors">
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                </div>
                @empty
                <div class="text-center py-16 text-gray-400">
                    <ion-icon name="cart-outline" class="text-6xl"></ion-icon>
                    <p class="mt-2 text-base">Your cart is empty</p>
                </div>
                @endforelse
            </div>

            <!-- Totals & Payment Section -->
            <div class="p-4 border-t border-gray-200 bg-white">
                <div class="mb-4">
                    <label for="customer_id" class="block text-sm font-semibold text-gray-700">Customer (for loyalty)</label>
                    <select id="customer_id" wire:model.live="customerId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2">
                        <option value="">Walk-in customer (no points)</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2 mb-4">
                    @if ($selectedCustomer)
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded-lg">
                        <p class="font-bold text-base">{{ $selectedCustomer->name }}</p>
                        <p class="text-sm">Available Points: <span class="font-bold">{{ number_format($selectedCustomer->loyalty_points, 0) }}</span></p>
                        <div class="mt-2 flex items-center justify-between gap-3 text-sm">
                            <span>Award loyalty points</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="awardLoyaltyPoints" class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-blue-600 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                            </label>
                            <span class="text-xs text-gray-500">
                                {{ $awardLoyaltyPoints ? 'On' : 'Off' }}
                            </span>
                        </div>
                        <div class="flex items-center mt-2 gap-2">
                            <input type="number" wire:model.live="loyaltyPointsToRedeem" placeholder="Points to use" class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2">
                            <button wire:click="applyLoyaltyPoints" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-semibold">Apply</button>
                        </div>
                    </div>
                    @endif
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold">Ksh {{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Tax (16%):</span>
                        <span class="font-semibold">Ksh {{ number_format($this->tax, 2) }}</span>
                    </div>
                    @if ($loyaltyDiscount > 0)
                    <div class="flex justify-between text-green-600 text-base">
                        <span class="text-gray-600">Loyalty Discount:</span>
                        <span class="font-semibold">-Ksh {{ number_format($this->loyaltyDiscount, 2) }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex justify-between font-bold p-4 bg-gray-100 rounded-lg mb-4">
                    <span class="text-blue-600">Total:</span>
                    <span class="text-blue-600">Ksh {{ number_format($this->grandTotal, 2) }}</span>
                </div>

                <!-- Payment Method -->
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <button wire:click="$set('paymentMethod', 'cash')" class="py-3 font-semibold text-base rounded-md transition-colors {{ $paymentMethod === 'cash' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}" @if($isProcessingMpesa) disabled @endif>
                        Cash
                    </button>
                    <button wire:click="$set('paymentMethod', 'mpesa')" class="py-3 font-semibold text-base rounded-md transition-colors {{ $paymentMethod === 'mpesa' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-800' }}" @if($isProcessingMpesa) disabled @endif>
                        M-Pesa
                    </button>
                </div>

                <!-- Payment Inputs -->
                @if ($paymentMethod === 'cash')
                <div class="space-y-2">
                    <label for="amount_paid" class="font-semibold text-base">Amount Paid:</label>
                    <input type="number" wire:model.live="amountPaid" id="amount_paid" class="w-full p-3 text-right border-2 border-gray-300 rounded-md text-xl focus:ring-blue-500 focus:border-blue-500">
                    @if ($this->change >= 0)
                    <div class="flex justify-between text-lg text-blue-600 font-bold p-2 bg-blue-50 rounded-md">
                        <span>Change Due:</span>
                        <span>Ksh {{ number_format($this->change, 2) }}</span>
                    </div>
                    @endif
                </div>
                @endif

                @if ($paymentMethod === 'mpesa')
                <div class="space-y-2">
                    <label for="mpesa_phone" class="font-semibold text-base">M-Pesa Phone:</label>
                    <input type="number" wire:model.live="mpesaPhone" id="mpesa_phone" placeholder="254..." class="w-full p-3 text-center border-2 border-green-500 rounded-md text-xl focus:ring-green-500 focus:border-green-500">
                    @error('mpesaPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-2 mt-4">
                    <button wire:click="holdSale" class="w-full py-3 bg-yellow-400 text-yellow-900 font-bold text-base rounded-md hover:bg-yellow-500 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart)) disabled @endif>
                        <ion-icon name="pause-outline" class="text-lg"></ion-icon>
                        <span>Hold</span>
                    </button>
                    <button wire:click="clearCart" class="w-full py-3 bg-red-500 text-white font-bold text-base rounded-md hover:bg-red-600 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart)) disabled @endif>
                        <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                        <span>Empty</span>
                    </button>
                </div>

                <button wire:click="finalizeSale" class="w-full mt-2 py-4 bg-blue-600 text-white text-xl font-bold rounded-md hover:bg-blue-700 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart) || $isProcessingMpesa) disabled @endif>
                    <div wire:loading wire:target="finalizeSale" class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
                    @if($isProcessingMpesa)
                    <div class="animate-pulse flex items-center gap-2 justify-center">
                        <ion-icon name="time-outline" class=""></ion-icon>
                        <span>Processing M-Pesa...</span>
                    </div>
                    @else
                    <ion-icon name="checkmark-done-outline" class="p-2"></ion-icon>
                    <span>Complete Sale</span>
                    @endif
                </button>
            </div>
        </div>
        @endif

    {{--Toast Notifications --}}
         <div x-data="{ msg: '', type: '', show: false, showToast(message, t = 'success') {
            this.msg = message; this.type = t; this.show = true;
            setTimeout(() => this.show = false, 3000);
        }}" x-on:flash-message.window="showToast($event.detail.message, $event.detail.type)" x-show="show" x-transition class="fixed top-5 right-5 z-50 px-4 py-3
            shadow-lg text-white text-sm" :class="type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-yellow-500')">
        <span x-text="msg"></span>
    </div>
        </div>
    </div>
