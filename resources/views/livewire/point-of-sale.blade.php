<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    <div class="relative flex h-[calc(100vh-80px)] flex-col lg:flex-row" x-on:print-receipt.window="window.open('/receipt/' + $event.detail.saleId, '_blank')">
        @if(!$activeShift)
        <div class="absolute inset-0 bg-white/20 backdrop-blur-lg z-10 flex items-center justify-center dark:bg-slate-950/40">
            <div class="text-center p-8 shadow-lg border dark:border-slate-700 dark:bg-slate-900">
                <ion-icon name="stop-circle-outline" class="text-5xl text-red-500 mx-auto"></ion-icon> 
                <h2 class="font-semibold mt-4">No Active Shift</h2>
                <p class="text-gray-600 mt-2 dark:text-slate-300">You must clock in to start a new sales session.</p>
                <div class="mt-6">
                    <a href="{{ route('shifts.management') }}" wire:navigate class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Clock In</a>
                </div>
            </div>
        </div>
        @endif

        {{-- Products Section --}}
        <div @class([ 'flex min-h-0 flex-1 flex-col p-4 transition-all duration-300' , 'w-full lg:w-3/5'=> !empty($cart),
            'w-full lg:w-full' => empty($cart),
            ])>
            <div class="mb-4 relative">
                     <input type="text" wire:model.live.debounce.300ms="search" placeholder="Scan or search products..." class="w-full p-3 pl-10 border-2 border-blue-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-400 
             ">
                <ion-icon size="large" name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2"></ion-icon>
            </div>

            <!-- Category Filter -->
            <div class="mb-4">
                <div class="space-x-2 overflow-x-auto pb-2 m-2 px-2">
                    <button wire:click="filterByCategory(null)" class="whitespace-nowrap px-4 py-2  transition-colors {{ !$selectedCategory ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600' }}">
                        All Products
                    </button>
                    @foreach($categories as $category)
                    <button wire:click="filterByCategory({{ $category->id }})" class="whitespace-nowrap px-4 py-2  transition-colors {{ $selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600' }}">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Product Grid - Adjust columns based on cart visibility --}}
            <div @class([ 'flex-1 min-h-0 grid grid-cols-2 gap-4 overflow-y-auto p-2' , 'sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4'=> !empty($cart),
                'sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6' => empty($cart),
                ])>
                @forelse($products as $product)
                <div wire:click="addToCart({{ $product->id }})" class="rounded-lg shadow-sm border border-gray-200 bg-white cursor-pointer hover:border-blue-500 hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden dark:border-slate-700 dark:bg-slate-900" x-data="{ added: false }" @click="added = true; setTimeout(() => added = false, 800)">
                    <div class="relative">
                        <div class="w-full h-32 bg-gray-100 flex items-center justify-center relative overflow-hidden dark:bg-slate-800">
                            <img class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 hover:scale-105" src="{{ $product->product->image_url ?: 'https://picsum.photos/seed/' . $product->id . '/900/700' }}" alt="{{ $product->product->name }}">
                        </div>
                        <div x-show="added" x-transition class="absolute inset-0 bg-blue-500/60 flex items-center justify-center text-white text-lg font-semibold">
                            <ion-icon name="checkmark-outline" class="text-4xl"></ion-icon>
                        </div>
                    </div>
                    <div class="p-3 flex flex-col flex-grow">
                        <h3 class="font-bold text-gray-800 truncate text-base dark:text-slate-100">{{ $product->product->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-slate-300">{{ $product->label }}</p>
                        <p class="font-semibold mt-auto pt-2 text-blue-600 text-lg">Ksh {{ number_format($product->retail_price) }}</p>
                    </div>

                </div>
                @empty
                <div class="col-span-full text-center py-10 text-gray-500 dark:text-slate-300">
                    <p class="text-gray-500 dark:text-slate-300">No products found.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $products->links() }}
            </div>

            {{-- Held Sales --}}
            @if($heldSales->count() > 0)
            <div class="mt-6 p-4 shadow-lg border border-gray-200 dark:border-slate-700 dark:bg-slate-900">
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
        @php($cartCount = count($cart))
        <div class="flex w-full min-h-0 flex-col border-t border-slate-200 bg-slate-50/95 backdrop-blur-sm lg:h-full lg:w-2/5 lg:border-l lg:border-t-0 dark:border-slate-700 dark:bg-slate-900/95">
            <div class="border-b border-slate-200 bg-white/85 px-4 py-3 sm:px-5 sm:py-4 dark:border-slate-700 dark:bg-slate-900/70">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="flex items-center gap-2 text-base font-bold text-slate-900 sm:text-lg dark:text-slate-100">
                        <ion-icon class="text-xl text-blue-600" name="cart-outline"></ion-icon>
                        <span>Current Order</span>
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                            {{ count($cart) }} items
                        </span>
                    </h2>
                    <button wire:click="clearCart" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 sm:text-sm dark:text-rose-300 dark:hover:bg-rose-500/10">
                        <ion-icon name="close-circle-outline"></ion-icon>
                        <span>Clear</span>
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div @class([
                'min-h-0 overflow-y-auto px-3 py-3 sm:px-4 sm:py-4',
                // Few items: let content breathe while preserving overall layout control.
                'max-h-[44vh] sm:max-h-[46vh] md:max-h-[50vh] lg:max-h-[58vh] xl:max-h-[62vh]' => $cartCount <= 3,
                // Medium/large carts: prioritize line-item visibility and scrolling efficiency.
                'max-h-[52vh] sm:max-h-[56vh] md:max-h-[60vh] lg:max-h-none lg:flex-1' => $cartCount > 3,
            ])>
                <div class="space-y-2.5 sm:space-y-3">
                @forelse($cart as $variantId => $item)
                <div class="rounded-xl border border-slate-200/90 bg-white p-3 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_auto_auto] sm:items-center sm:gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-800 sm:text-base dark:text-slate-100">{{ $item['name'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500 sm:text-sm dark:text-slate-300">Ksh {{ number_format($item['unit_price'], 2) }} each</p>
                        </div>

                        <div class="flex items-center justify-start gap-1.5 rounded-full bg-slate-100 px-1.5 py-1 sm:justify-center dark:bg-slate-700/80">
                            <button wire:click="decrementQuantity({{ $variantId }})" class="rounded-full bg-white p-1 text-slate-600 transition-colors hover:bg-rose-100 hover:text-rose-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-rose-900/50">
                                <ion-icon name="remove-outline"></ion-icon>
                            </button>
                            <span class="w-8 text-center text-sm font-bold text-slate-800 sm:text-base dark:text-slate-100">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQuantity({{ $variantId }})" class="rounded-full bg-white p-1 text-slate-600 transition-colors hover:bg-blue-100 hover:text-blue-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-blue-900/50">
                                <ion-icon name="add-outline"></ion-icon>
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 sm:justify-end">
                            <p class="text-right text-sm font-bold text-slate-800 sm:text-base dark:text-slate-100">Ksh {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</p>
                            <button wire:click="removeFromCart({{ $variantId }})" class="rounded-lg p-1 text-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white/70 py-14 text-center text-slate-400 dark:border-slate-600 dark:bg-slate-800/40 dark:text-slate-300">
                    <ion-icon name="cart-outline" class="text-5xl"></ion-icon>
                    <p class="mt-2 text-sm font-medium sm:text-base">Your cart is empty</p>
                </div>
                @endforelse
                </div>
            </div>

            <!-- Totals & Payment Section -->
            <div class="min-h-0 border-t border-slate-200 bg-white px-3 pb-3 pt-4 sm:px-4 sm:pb-4 sm:pt-5 dark:border-slate-700 dark:bg-slate-900">
                <div @class([
                    'space-y-2.5 overflow-y-auto pr-1',
                    // With fewer items, payment block can be taller for easier scanning.
                    'max-h-[40vh] sm:max-h-[42vh] md:max-h-[44vh] lg:max-h-[48vh] xl:max-h-[52vh]' => $cartCount <= 3,
                    // Dense carts keep payment compact but always reachable via scroll.
                    'max-h-[32vh] sm:max-h-[34vh] md:max-h-[36vh] lg:max-h-[40vh] xl:max-h-[44vh]' => $cartCount > 3,
                ])>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                        <label for="customer_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Customer (Loyalty)</label>
                        <select id="customer_id" wire:model.live="customerId" class="mt-2 block w-full rounded-lg border border-slate-300 bg-white p-2.5 text-sm shadow-sm dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                            <option value="">Walk-in customer (no points)</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($selectedCustomer)
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-3 text-blue-900 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-200">
                        <p class="truncate text-sm font-bold sm:text-base">{{ $selectedCustomer->name }}</p>
                        <p class="mt-1 text-xs sm:text-sm">Available Points: <span class="font-bold">{{ number_format($selectedCustomer->loyalty_points, 0) }}</span></p>

                        <label class="mt-3 flex items-center gap-2 rounded-lg border border-blue-200 bg-white/70 px-3 py-2 text-xs font-semibold sm:text-sm dark:border-blue-400/20 dark:bg-slate-900/40">
                            <input type="checkbox" wire:model.live="useAccumulatedPoints" class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                            <span>Use accumulated points on this sale</span>
                        </label>

                        @if ($useAccumulatedPoints)
                        <p class="mt-2 text-xs sm:text-sm">
                            Points to redeem: <span class="font-bold">{{ number_format($loyaltyPointsToRedeem, 0) }}</span>
                        </p>
                        <p class="mt-1 text-xs sm:text-sm">
                            Redemption discount: <span class="font-bold">Ksh {{ number_format($loyaltyDiscount, 2) }}</span>
                        </p>
                        @else
                        <p class="mt-2 text-xs sm:text-sm text-blue-800/90 dark:text-blue-200/90">
                            Points will be earned automatically after checkout.
                        </p>
                        @endif
                    </div>
                    @endif

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm sm:text-base">
                                <span class="text-slate-600 dark:text-slate-300">Subtotal</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-100">Ksh {{ number_format($this->subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm sm:text-base">
                                <span class="text-slate-600 dark:text-slate-300">Tax (16%)</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-100">Ksh {{ number_format($this->tax, 2) }}</span>
                            </div>
                            @if ($loyaltyDiscount > 0)
                            <div class="flex items-center justify-between text-sm font-semibold text-emerald-600 sm:text-base">
                                <span>Loyalty Discount</span>
                                <span>-Ksh {{ number_format($this->loyaltyDiscount, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 font-bold dark:border-blue-500/30 dark:bg-blue-500/10">
                        <span class="text-blue-700 dark:text-blue-300">Total</span>
                        <span class="text-lg text-blue-700 dark:text-blue-300">Ksh {{ number_format($this->grandTotal, 2) }}</span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-2 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="px-1 pb-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Payment Method</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="$set('paymentMethod', 'cash')" class="rounded-lg py-2.5 text-sm font-semibold transition-colors sm:py-3 sm:text-base {{ $paymentMethod === 'cash' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600' }}" @if($isProcessingMpesa) disabled @endif>
                                Cash
                            </button>
                            <button wire:click="$set('paymentMethod', 'mpesa')" class="rounded-lg py-2.5 text-sm font-semibold transition-colors sm:py-3 sm:text-base {{ $paymentMethod === 'mpesa' ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600' }}" @if($isProcessingMpesa) disabled @endif>
                                M-Pesa
                            </button>
                        </div>
                    </div>

                    @if ($paymentMethod === 'cash')
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                        <label for="amount_paid" class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Amount Paid</label>
                        <input type="number" wire:model.live="amountPaid" id="amount_paid" class="mt-2 w-full rounded-lg border-2 border-slate-300 bg-white p-3 text-right text-lg font-semibold focus:border-blue-500 focus:ring-blue-500 sm:text-xl dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                        @if ($this->change >= 0)
                        <div class="mt-2 flex items-center justify-between rounded-lg bg-blue-50 p-2 text-sm font-bold text-blue-700 sm:text-base dark:bg-blue-500/10 dark:text-blue-300">
                            <span>Change Due</span>
                            <span>Ksh {{ number_format($this->change, 2) }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if ($paymentMethod === 'mpesa')
                    <div class="rounded-xl border border-green-200 bg-green-50 p-3 dark:border-green-500/30 dark:bg-green-500/10">
                        <label for="mpesa_phone" class="text-xs font-semibold uppercase tracking-wide text-green-700 dark:text-green-300">M-Pesa Phone</label>
                        <input type="number" wire:model.live="mpesaPhone" id="mpesa_phone" placeholder="254..." class="mt-2 w-full rounded-lg border-2 border-green-500 bg-white p-3 text-center text-lg font-semibold focus:border-green-500 focus:ring-green-500 sm:text-xl dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-400">
                        @error('mpesaPhone') <span class="mt-1 block text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="sticky bottom-0 mt-3 rounded-xl border border-slate-200 bg-slate-50 p-2 shadow-sm dark:border-slate-700 dark:bg-slate-800/60">
                    <div class="space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="holdSale" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-amber-300 bg-amber-100/90 px-3 py-2.5 text-sm font-semibold text-amber-900 transition hover:bg-amber-200 disabled:cursor-not-allowed disabled:opacity-50 sm:py-3 sm:text-base" @if(empty($cart)) disabled @endif>
                                <ion-icon name="pause-outline" class="text-lg"></ion-icon>
                                <span>Hold</span>
                            </button>

                            <button wire:click="clearCart" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-rose-300 bg-rose-500 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-600 disabled:cursor-not-allowed disabled:opacity-50 sm:py-3 sm:text-base" @if(empty($cart)) disabled @endif>
                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                <span>Empty</span>
                            </button>
                        </div>

                        <button wire:click="finalizeSale" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-3 text-base font-bold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50 sm:py-3.5 sm:text-lg" @if(empty($cart) || $isProcessingMpesa) disabled @endif>
                            <div wire:loading wire:target="finalizeSale" class="h-5 w-5 animate-spin rounded-full border-b-2 border-white sm:h-6 sm:w-6"></div>
                            @if($isProcessingMpesa)
                            <div class="flex animate-pulse items-center justify-center gap-2">
                                <ion-icon name="time-outline" class=""></ion-icon>
                                <span>Processing M-Pesa...</span>
                            </div>
                            @else
                            <ion-icon name="checkmark-done-outline" class="text-lg"></ion-icon>
                            <span>Complete Sale</span>
                            @endif
                        </button>
                    </div>
                </div>
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
