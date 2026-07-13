<div class="flex h-full min-h-0 flex-1 flex-col bg-white dark:bg-[#0a0d0c]">
    <div class="relative flex h-full min-h-0 flex-1 flex-col lg:flex-row" x-on:print-receipt.window="window.open('/receipt/' + $event.detail.saleId, '_blank')">

        @if(!$activeShift)
        <div class="absolute inset-0 z-10 flex items-center justify-center bg-white/40 backdrop-blur-lg dark:bg-[#0a0d0c]/60">
            <div class="border border-slate-200 bg-white p-8 text-center shadow-sm dark:border-emerald-500/10 dark:bg-[#0f1413]">
                <ion-icon name="stop-circle-outline" class="mx-auto text-5xl text-red-500"></ion-icon>
                <h2 class="mt-4 font-mono text-sm font-semibold uppercase tracking-wide text-slate-800 dark:text-slate-100">No Active Shift</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">You must clock in to start a new sales session.</p>
                <div class="mt-6">
                    <a href="{{ route('shifts.management') }}" wire:navigate class="inline-flex border border-emerald-600 bg-emerald-600 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition-colors hover:bg-emerald-700">Clock In</a>
                </div>
            </div>
        </div>
        @endif

        {{-- Products Section --}}
        <div @class([
            'flex min-h-0 flex-1 flex-col p-4 transition-all duration-300',
            'w-full lg:w-3/5' => !empty($cart),
            'w-full lg:w-full' => empty($cart),
        ])>
            <div class="relative mb-4">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Scan or search products..."
                    class="w-full border border-slate-300 bg-white p-3 pl-10 font-mono text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:border-emerald-500/15 dark:bg-[#0f1413] dark:text-slate-100 dark:placeholder-slate-500"
                >
                <ion-icon size="large" name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></ion-icon>
            </div>

            <!-- Category Filter -->
            <div class="mb-4">
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <button wire:click="filterByCategory(null)" class="whitespace-nowrap border px-4 py-2 text-xs font-semibold uppercase tracking-wide transition-colors {{ !$selectedCategory ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 dark:border-emerald-500/10 dark:bg-[#0f1413] dark:text-slate-300 dark:hover:bg-white/[0.03]' }}">
                        All Products
                    </button>
                    @foreach($categories as $category)
                    <button wire:click="filterByCategory({{ $category->id }})" class="whitespace-nowrap border px-4 py-2 text-xs font-semibold uppercase tracking-wide transition-colors {{ $selectedCategory == $category->id ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 dark:border-emerald-500/10 dark:bg-[#0f1413] dark:text-slate-300 dark:hover:bg-white/[0.03]' }}">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Product Grid --}}
            <div @class([
                'flex-1 min-h-0 grid grid-cols-2 gap-3 overflow-y-auto p-1',
                'sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4' => !empty($cart),
                'sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6' => empty($cart),
            ])>
                @forelse($products as $product)
                <div
                    wire:click="addToCart({{ $product->id }})"
                    x-data="{ added: false }"
                    @click="added = true; setTimeout(() => added = false, 800)"
                    class="flex cursor-pointer flex-col overflow-hidden border border-slate-200 bg-white transition-colors hover:border-emerald-500 dark:border-emerald-500/10 dark:bg-[#0f1413] dark:hover:border-emerald-500/40"
                >
                    <div class="relative">
                        <div class="relative h-32 w-full overflow-hidden bg-slate-100 dark:bg-black/30">
                            <img class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 hover:scale-105" src="{{ $product->product->image_url ?: 'https://picsum.photos/seed/' . $product->id . '/900/700' }}" alt="{{ $product->product->name }}">
                        </div>
                        <div x-show="added" x-transition class="absolute inset-0 flex items-center justify-center bg-emerald-600/70 text-lg font-semibold text-white">
                            <ion-icon name="checkmark-outline" class="text-4xl"></ion-icon>
                        </div>
                    </div>
                    <div class="flex flex-grow flex-col p-3">
                        <h3 class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $product->product->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $product->label }}</p>
                        <p class="mt-auto pt-2 font-mono text-base font-semibold text-emerald-700 dark:text-emerald-400">Ksh {{ number_format($product->retail_price) }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-10 text-center text-slate-500 dark:text-slate-400">
                    <p>No products found.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-4 font-mono text-sm">
                {{ $products->links() }}
            </div>

            {{-- Held Sales --}}
            @if($heldSales->count() > 0)
            <div class="mt-6 border border-slate-200 bg-white p-4 dark:border-emerald-500/10 dark:bg-[#0f1413]">
                <h3 class="mb-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-700 dark:text-slate-200">
                    <ion-icon name="pause-circle-outline"></ion-icon> Held Sales
                </h3>
                <ul class="space-y-2">
                    @foreach($heldSales as $sale)
                    <li class="flex items-center justify-between font-mono text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ $sale->invoice_no }} (Ksh {{ number_format($sale->total, 2) }})</span>
                        <div class="flex gap-1">
                            <button wire:click="resumeSale({{ $sale->id }})" class="border border-emerald-600 bg-emerald-600 px-2 py-1 text-xs font-semibold uppercase tracking-wide text-white hover:bg-emerald-700">Resume</button>
                            <button wire:click="cancelSale({{ $sale->id }})" class="border border-red-600 bg-red-600 px-2 py-1 text-xs font-semibold uppercase tracking-wide text-white hover:bg-red-700">Cancel</button>
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
        <div class="flex min-h-0 w-full flex-col border-t border-slate-200 bg-slate-50 lg:h-full lg:w-2/5 lg:border-l lg:border-t-0 dark:border-emerald-500/10 dark:bg-[#0c1110]">
            <div class="border-b border-slate-200 bg-white px-4 py-3 sm:px-5 sm:py-4 dark:border-emerald-500/10 dark:bg-[#0f1413]">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-900 sm:text-base dark:text-slate-100">
                        <ion-icon class="text-xl text-emerald-600 dark:text-emerald-400" name="cart-outline"></ion-icon>
                        <span>Current Order</span>
                        <span class="border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 font-mono text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                            {{ count($cart) }} items
                        </span>
                    </h2>
                    <button wire:click="clearCart" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold uppercase tracking-wide text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                        <ion-icon name="close-circle-outline"></ion-icon>
                        <span>Clear</span>
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div @class([
                'min-h-0 overflow-y-auto px-3 py-3 sm:px-4 sm:py-4',
                'max-h-[44vh] sm:max-h-[46vh] md:max-h-[50vh] lg:max-h-[58vh] xl:max-h-[62vh]' => $cartCount <= 3,
                'max-h-[52vh] sm:max-h-[56vh] md:max-h-[60vh] lg:max-h-none lg:flex-1' => $cartCount > 3,
            ])>
                <div class="space-y-2.5 sm:space-y-3">
                @forelse($cart as $variantId => $item)
                <div class="border border-slate-200 bg-white p-3 transition-colors hover:border-emerald-500/40 dark:border-emerald-500/10 dark:bg-[#0f1413]">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_auto_auto] sm:items-center sm:gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-800 sm:text-base dark:text-slate-100">{{ $item['name'] }}</p>
                            <p class="mt-0.5 font-mono text-xs text-slate-500 sm:text-sm dark:text-slate-400">Ksh {{ number_format($item['unit_price'], 2) }} each</p>
                        </div>

                        <div class="flex items-center justify-start gap-1.5 border border-slate-200 px-1.5 py-1 sm:justify-center dark:border-emerald-500/10">
                            <button wire:click="decrementQuantity({{ $variantId }})" class="border border-slate-200 p-1 text-slate-600 transition-colors hover:border-red-400 hover:text-red-600 dark:border-emerald-500/10 dark:text-slate-300 dark:hover:border-red-500/40 dark:hover:text-red-400">
                                <ion-icon name="remove-outline"></ion-icon>
                            </button>
                            <span class="w-8 text-center font-mono text-sm font-bold text-slate-800 sm:text-base dark:text-slate-100">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQuantity({{ $variantId }})" class="border border-slate-200 p-1 text-slate-600 transition-colors hover:border-emerald-500 hover:text-emerald-700 dark:border-emerald-500/10 dark:text-slate-300 dark:hover:border-emerald-500/40 dark:hover:text-emerald-400">
                                <ion-icon name="add-outline"></ion-icon>
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 sm:justify-end">
                            <p class="text-right font-mono text-sm font-bold text-slate-800 sm:text-base dark:text-slate-100">Ksh {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</p>
                            <button wire:click="removeFromCart({{ $variantId }})" class="p-1 text-lg text-slate-400 transition-colors hover:text-red-600 dark:hover:text-red-400">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="border border-dashed border-slate-300 py-14 text-center text-slate-400 dark:border-slate-700 dark:text-slate-500">
                    <ion-icon name="cart-outline" class="text-5xl"></ion-icon>
                    <p class="mt-2 text-sm font-medium sm:text-base">Your cart is empty</p>
                </div>
                @endforelse
                </div>
            </div>

            <!-- Totals & Payment Section -->
            <div class="min-h-0 border-t border-slate-200 bg-white px-3 pb-3 pt-4 sm:px-4 sm:pb-4 sm:pt-5 dark:border-emerald-500/10 dark:bg-[#0f1413]">
                <div @class([
                    'space-y-2.5 overflow-y-auto pr-1',
                    'max-h-[40vh] sm:max-h-[42vh] md:max-h-[44vh] lg:max-h-[48vh] xl:max-h-[52vh]' => $cartCount <= 3,
                    'max-h-[32vh] sm:max-h-[34vh] md:max-h-[36vh] lg:max-h-[40vh] xl:max-h-[44vh]' => $cartCount > 3,
                ])>
                    <div class="border border-slate-200 bg-slate-50 p-3 dark:border-emerald-500/10 dark:bg-[#0c1110]">
                        <label for="customer_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Customer (Loyalty)</label>
                        <select id="customer_id" wire:model.live="customerId" class="mt-2 block w-full border border-slate-300 bg-white p-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:border-emerald-500/15 dark:bg-[#0f1413] dark:text-slate-100">
                            <option value="">Walk-in customer (no points)</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($selectedCustomer)
                    <div class="border border-emerald-500/30 bg-emerald-500/5 p-3 text-emerald-900 dark:text-emerald-200">
                        <p class="truncate text-sm font-bold sm:text-base">{{ $selectedCustomer->name }}</p>
                        <p class="mt-1 font-mono text-xs sm:text-sm">Available Points: <span class="font-bold">{{ number_format($selectedCustomer->loyalty_points, 0) }}</span></p>

                        <label class="mt-3 flex items-center gap-2 border border-emerald-500/20 bg-white/60 px-3 py-2 text-xs font-semibold sm:text-sm dark:bg-black/20">
                            <input type="checkbox" wire:model.live="useAccumulatedPoints" class="h-4 w-4 border-emerald-400 text-emerald-600 focus:ring-emerald-500">
                            <span>Use accumulated points on this sale</span>
                        </label>

                        @if ($useAccumulatedPoints)
                        <p class="mt-2 font-mono text-xs sm:text-sm">
                            Points to redeem: <span class="font-bold">{{ number_format($loyaltyPointsToRedeem, 0) }}</span>
                        </p>
                        <p class="mt-1 font-mono text-xs sm:text-sm">
                            Redemption discount: <span class="font-bold">Ksh {{ number_format($loyaltyDiscount, 2) }}</span>
                        </p>
                        @else
                        <p class="mt-2 text-xs text-emerald-800/90 sm:text-sm dark:text-emerald-200/80">
                            Points will be earned automatically after checkout.
                        </p>
                        @endif
                    </div>
                    @endif

                    <div class="border border-slate-200 bg-slate-50 p-3 dark:border-emerald-500/10 dark:bg-[#0c1110]">
                        <div class="space-y-2 font-mono">
                            <div class="flex items-center justify-between text-sm sm:text-base">
                                <span class="text-slate-600 dark:text-slate-400">Subtotal</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-100">Ksh {{ number_format($this->subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm sm:text-base">
                                <span class="text-slate-600 dark:text-slate-400">Tax (16%)</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-100">Ksh {{ number_format($this->tax, 2) }}</span>
                            </div>
                            @if ($loyaltyDiscount > 0)
                            <div class="flex items-center justify-between text-sm font-semibold text-emerald-600 sm:text-base dark:text-emerald-400">
                                <span>Loyalty Discount</span>
                                <span>-Ksh {{ number_format($this->loyaltyDiscount, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between border border-emerald-600/40 bg-emerald-500/10 px-4 py-3 font-mono font-bold">
                        <span class="text-emerald-700 dark:text-emerald-300">Total</span>
                        <span class="text-lg text-emerald-700 dark:text-emerald-300">Ksh {{ number_format($this->grandTotal, 2) }}</span>
                    </div>

                    <div class="border border-slate-200 bg-slate-50 p-2 dark:border-emerald-500/10 dark:bg-[#0c1110]">
                        <p class="px-1 pb-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Payment Method</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="$set('paymentMethod', 'cash')" class="border py-2.5 text-sm font-semibold uppercase tracking-wide transition-colors sm:py-3 sm:text-base {{ $paymentMethod === 'cash' ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-100 dark:border-emerald-500/10 dark:bg-[#0f1413] dark:text-slate-300 dark:hover:bg-white/[0.03]' }}" @if($isProcessingMpesa) disabled @endif>
                                Cash
                            </button>
                            <button wire:click="$set('paymentMethod', 'mpesa')" class="border py-2.5 text-sm font-semibold uppercase tracking-wide transition-colors sm:py-3 sm:text-base {{ $paymentMethod === 'mpesa' ? 'border-amber-600 bg-amber-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-100 dark:border-emerald-500/10 dark:bg-[#0f1413] dark:text-slate-300 dark:hover:bg-white/[0.03]' }}" @if($isProcessingMpesa) disabled @endif>
                                M-Pesa
                            </button>
                        </div>
                    </div>

                    @if ($paymentMethod === 'cash')
                    <div class="border border-slate-200 bg-slate-50 p-3 dark:border-emerald-500/10 dark:bg-[#0c1110]">
                        <label for="amount_paid" class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Amount Paid</label>
                        <input type="number" wire:model.live="amountPaid" id="amount_paid" class="mt-2 w-full border-2 border-slate-300 bg-white p-3 text-right font-mono text-lg font-semibold focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 sm:text-xl dark:border-emerald-500/20 dark:bg-[#0f1413] dark:text-slate-100">
                        @if ($this->change >= 0)
                        <div class="mt-2 flex items-center justify-between border border-emerald-500/20 bg-emerald-500/5 p-2 font-mono text-sm font-bold text-emerald-700 sm:text-base dark:text-emerald-300">
                            <span>Change Due</span>
                            <span>Ksh {{ number_format($this->change, 2) }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if ($paymentMethod === 'mpesa')
                    <div class="border border-amber-500/30 bg-amber-500/5 p-3 dark:border-amber-500/20">
                        <label for="mpesa_phone" class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-400">M-Pesa Phone</label>
                        <input type="number" wire:model.live="mpesaPhone" id="mpesa_phone" placeholder="254..." class="mt-2 w-full border-2 border-amber-500 bg-white p-3 text-center font-mono text-lg font-semibold focus:border-amber-500 focus:outline-none focus:ring-amber-500 sm:text-xl dark:bg-[#0f1413] dark:text-slate-100 dark:placeholder-slate-500">
                        @error('mpesaPhone') <span class="mt-1 block text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="sticky bottom-0 mt-3 border border-slate-200 bg-slate-50 p-2 dark:border-emerald-500/10 dark:bg-[#0c1110]">
                    <div class="space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="holdSale" class="inline-flex w-full items-center justify-center gap-2 border border-amber-400 bg-amber-50 px-3 py-2.5 text-sm font-semibold uppercase tracking-wide text-amber-800 transition hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-50 sm:py-3 sm:text-base dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/15" @if(empty($cart)) disabled @endif>
                                <ion-icon name="pause-outline" class="text-lg"></ion-icon>
                                <span>Hold</span>
                            </button>

                            <button wire:click="clearCart" class="inline-flex w-full items-center justify-center gap-2 border border-red-600 bg-red-600 px-3 py-2.5 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50 sm:py-3 sm:text-base" @if(empty($cart)) disabled @endif>
                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                <span>Empty</span>
                            </button>
                        </div>

                        <button wire:click="finalizeSale" class="inline-flex w-full items-center justify-center gap-2 border border-emerald-600 bg-emerald-600 px-4 py-3 text-base font-bold uppercase tracking-wide text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 sm:py-3.5 sm:text-lg" @if(empty($cart) || $isProcessingMpesa) disabled @endif>
                            <div wire:loading wire:target="finalizeSale" class="h-5 w-5 animate-spin rounded-full border-b-2 border-white sm:h-6 sm:w-6"></div>
                            @if($isProcessingMpesa)
                            <div class="flex animate-pulse items-center justify-center gap-2">
                                <ion-icon name="time-outline"></ion-icon>
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

        {{-- Toast Notifications --}}
        <div
            x-data="{ msg: '', type: '', show: false, showToast(message, t = 'success') {
                this.msg = message; this.type = t; this.show = true;
                setTimeout(() => this.show = false, 3000);
            }}"
            x-on:flash-message.window="showToast($event.detail.message, $event.detail.type)"
            x-show="show"
            x-transition
            class="fixed top-5 right-5 z-50 border px-4 py-3 font-mono text-sm text-white shadow-sm"
            :class="type === 'success' ? 'border-emerald-700 bg-emerald-600' : (type === 'error' ? 'border-red-700 bg-red-600' : 'border-amber-600 bg-amber-500')"
        >
            <span x-text="msg"></span>
        </div>
    </div>
</div>