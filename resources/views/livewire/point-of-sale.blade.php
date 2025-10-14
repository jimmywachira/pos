<div class="flex h-[calc(100vh-100px)]">
    {{-- Products Section --}}
    <div class="w-3/5 flex flex-col p-4">
        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Scan or search products by name or barcode..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex-1 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto p-2 bg-gray-50 rounded-lg">
            @forelse($results as $product)
            <div wire:click="addToCart({{ $product->id }})" class="bg-white p-3 rounded-lg shadow-sm cursor-pointer hover:ring-2 hover:ring-blue-500 transition-shadow duration-200 flex flex-col justify-between">
                <h3 class="font-semibold text-sm text-gray-800 truncate">{{ $product->product->name }} - {{ $product->label }}</h3>
                <p class="text-gray-600 font-bold mt-2">Ksh {{ number_format($product->retail_price, 2) }}</p>
            </div>
            @empty
            <div class="col-span-full text-center p-10">
                <p class="text-gray-500">No products found.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Cart Section --}}
    <div class="w-2/5 flex flex-col bg-white p-4 border-l border-gray-200">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Cart</h2>
        @error('cart') <div class="text-red-500 mb-2 text-sm">{{ $message }}</div> @enderror

        <div class="flex-1 overflow-y-auto border-t border-b border-gray-200 -mx-4 px-4">
            <ul class="divide-y divide-gray-200">
                @forelse($cart as $variantId => $item)
                <li class="py-3 flex items-center">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-500">Ksh {{ number_format($item['unit_price'], 2) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button wire:click="decrementQuantity({{ $variantId }})" class="p-1 rounded-full bg-gray-200 hover:bg-gray-300 text-lg font-bold leading-none">-</button>
                        <span class="w-8 text-center font-semibold">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $variantId }})" class="p-1 rounded-full bg-gray-200 hover:bg-gray-300 text-lg font-bold leading-none">+</button>
                    </div>
                    <div class="w-24 text-right font-semibold">
                        Ksh {{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                    </div>
                    <button wire:click="removeFromCart({{ $variantId }})" class="ml-4 text-red-500 hover:text-red-700 text-2xl leading-none">&times;</button>
                </li>
                @empty
                <li class="text-center py-10 text-gray-500">Cart is empty</li>
                @endforelse
            </ul>
        </div>

        <div class="mt-auto pt-4">
            <div class="space-y-2 text-lg">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">Ksh {{ number_format($this->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tax (16%):</span>
                    <span class="font-semibold">Ksh {{ number_format($this->tax, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-2xl border-t pt-2">
                    <span>Total:</span>
                    <span>Ksh {{ number_format($this->grandTotal, 2) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <button wire:click="holdSale" class="w-full py-3 bg-amber-500 text-white font-bold rounded-lg hover:bg-amber-600 disabled:bg-gray-400" @if(empty($cart)) disabled @endif>
                    Hold
                </button>
                <button wire:click="clearCart" class="w-full py-3 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600 disabled:bg-gray-400" @if(empty($cart)) disabled @endif>
                    Cancel
                </button>
            </div>
            <button wire:click="finalizeSale" class="w-full mt-2 py-4 bg-green-600 text-white font-bold text-lg rounded-lg hover:bg-green-700 disabled:bg-gray-400" @if(empty($cart)) disabled @endif>
                Complete Sale
            </button>
        </div>
    </div>

    {{-- Held Sales (Optional Modal or separate view) --}}
    @if($heldSales->count())
    <div class="absolute bottom-4 left-4 bg-white p-4 rounded-lg shadow-lg border max-w-sm">
        <h3 class="font-bold mb-2">Held Sales</h3>
        <ul class="text-sm space-y-2">
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

    @if(session()->has('message'))
    <div class="absolute top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
    @endif
</div>
