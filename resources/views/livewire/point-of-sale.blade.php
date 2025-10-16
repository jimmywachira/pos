<div class="flex h-[calc(100vh-80px)] bg-inherit relative">

    {{-- Products Section --}}
    <div class="@if(!empty($cart)) w-3/5 @else w-full @endif flex flex-col p-4 transition-all duration-300">
        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Scan or search products by name or barcode..." class="w-full p-3 border-2 border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
        </div>

        {{-- Product Grid - Adjust columns based on cart visibility --}}
        <div class="flex-1 grid grid-cols-2 @if(!empty($cart)) md:grid-cols-3 lg:grid-cols-4 @else md:grid-cols-4 lg:grid-cols-6 @endif gap-4 overflow-y-auto p-2">
            @forelse($products as $product)
            <div wire:click="addToCart({{ $product->id }})" class="bg-white shadow hover:shadow-lg border border-blue-200 cursor-pointer hover:ring-2 hover:ring-indigo-600 transition-all duration-200 flex flex-col overflow-hidden">
                <img src="{{ $product->product->image_url ?? 'https://picsum.photos/seed/' . $product->id . '/300/200' }}" alt="{{ $product->product->name }}" class="w-full h-32 object-cover">
                <div class="p-3 flex flex-col flex-grow">
                    <h3 class="font-semibold text-lg truncate">{{ $product->product->name }} - {{ $product->label }}</h3>
                    <p class="font-bold mt-2 text-indigo-700 text-xl">Ksh {{ number_format($product->retail_price, 2) }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-10">
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
    <div class="w-2/5 flex flex-col p-4 text-black bg-inherit border-l-2 border-gray-200">
        <h2 class="text-xl text-center border-2 rounded-b-lg border-blue-600 p-2 font-bold mb-4">🛒 Cart</h2>
        @error('cart')
        <div class="text-red-500 mb-2 ">{{ $message }}</div>
        @enderror

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto mx-4 px-4">
            <ul class="divide-y divide-gray-200">
                @forelse($cart as $variantId => $item)
                <li class="py-3 flex items-center justify-between">
                    <div class="flex-1">
                        <p class="font-semibold ">{{ $item['name'] }}</p>
                        <p class=" text-gray-500">Ksh {{ number_format($item['unit_price'], 2) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="decrementQuantity({{ $variantId }})" class="px-2 border border-gray-300 rounded-full hover:bg-gray-300 font-bold">-</button>
                        <span class="w-8 text-center font-semibold">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $variantId }})" class="px-2 border border-gray-300 rounded-full hover:bg-gray-300 font-bold">+</button>
                    </div>
                    <div class="w-24 text-right font-semibold">
                        Ksh {{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                    </div>
                    <button wire:click="removeFromCart({{ $variantId }})" class="ml-4 text-red-500 hover:text-red-700 text-xl">&times;</button>
                </li>
                @empty
                <li class="text-center py-10 text-gray-500 italic">Cart is empty — start adding products</li>
                @endforelse
            </ul>
        </div>

        {{-- Totals Section --}}
        <div class="mt-auto pt-4 border-t border-gray-300 space-y-2 text-lg">
            <div class="flex justify-between">
                <span>Subtotal:</span>
                <span class="font-semibold">Ksh {{ number_format($this->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tax (16%):</span>
                <span class="font-semibold">Ksh {{ number_format($this->tax, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-2xl border-t pt-2">
                <span class="text-green-600">Total:</span>
                <span>Ksh {{ number_format($this->grandTotal, 2) }}</span>
            </div>

            {{-- Payment Method --}}
            <div class="mt-4">
                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="$set('paymentMethod', 'cash')" class="py-2 font-semibold rounded-md {{ $paymentMethod === 'cash' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                        Cash
                    </button>
                    <button wire:click="$set('paymentMethod', 'card')" class="py-2 font-semibold rounded-md {{ $paymentMethod === 'card' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                        Card/M-Pesa
                    </button>
                </div>
            </div>

            {{-- Amount Paid & Change --}}
            @if ($paymentMethod === 'cash')
            <div class="mt-4 space-y-2">
                <div class="flex justify-between items-center">
                    <label for="amount_paid" class="text-lg">Amount Paid:</label>
                    <input type="number" wire:model.live="amountPaid" id="amount_paid" class="w-1/2 p-2 text-right font-bold text-lg border-2 border-gray-300 rounded-md">
                </div>
                @if ($this->change >= 0)
                <div class="flex justify-between font-bold text-xl text-blue-700">
                    <span>Change:</span>
                    <span>Ksh {{ number_format($this->change, 2) }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Controls --}}
            <div class="grid grid-cols-2 gap-3 mt-6">
                <button wire:click="holdSale" class="w-full py-3 bg-yellow-500 text-white font-bold hover:bg-yellow-600 disabled:opacity-50" @if(empty($cart)) disabled @endif>
                    Hold
                </button>
                <button wire:click="clearCart" class="w-full py-3 bg-red-500 text-white font-bold hover:bg-red-600 disabled:opacity-50 flex items-center justify-center gap-2" @if(empty($cart)) disabled @endif>
                    <ion-icon name="trash-outline" class="text-xl"></ion-icon>
                    <span>Cancel</span>
                </button>
            </div>

            <button wire:click="finalizeSale" class="w-full mt-3 py-4 bg-green-600 text-white font-bold text-lg hover:bg-green-700 disabled:opacity-50" @if(empty($cart)) disabled @endif>
                ✅ Complete Sale
            </button>

            <button wire:click="printReceipt" class="w-full mt-2 py-3 bg-blue-600 text-white font-bold hover:bg-blue-700">
                🧾 Print Receipt
            </button>
        </div>
    </div>
    @endif

    {{-- Held Sales --}}
    @if($heldSales->count())
    <div class="absolute bottom-4 left-4 bg-white p-4 shadow-lg border border-gray-200 max-w-sm">
        <h3 class="font-bold mb-2">🕒 Held Sales</h3>
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
    @if(session()->has('message'))
    <div class="absolute top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
    @endif

    @if(session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="absolute top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif
</div>
