<div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>

    {{-- Search --}}
    <input type="text" wire:model="search" placeholder="Search products..." class="w-full border p-2 mb-3 rounded">

    {{-- Search Results --}}
    @if($results)
    <ul class="border rounded mb-4">
        @foreach($results as $result)
        <li class="flex justify-between items-center p-2 border-b">
            <span>{{ $result->product->name }} - {{ $result->label }} (Ksh {{ $result->retail_price }})</span>
            <button wire:click="addToCart({{ $result->id }})" class="bg-blue-600 text-white px-2 py-1 rounded">Add</button>
        </li>
        @endforeach
    </ul>
    @endif

    {{-- Cart --}}
    <h2 class="text-lg font-bold mb-2">Cart</h2>
    @error('cart') <div class="text-red-600 mb-2">{{ $message }}</div> @enderror

    <table class="w-full mb-4">
        <thead>
            <tr class="border-b">
                <th class="text-left">Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="text-right">{{ $item['quantity'] }}</td>
                <td class="text-right">{{ number_format($item['unit_price'], 2) }}</td>
                <td class="text-right">{{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                <td><button wire:click="removeFromCart({{ $item['id'] }})" class="text-red-600">X</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right mb-4">
        <div>Subtotal: Ksh {{ number_format($this->subtotal, 2) }}</div>
        <div>Tax: Ksh {{ number_format($this->tax, 2) }}</div>
        <div>Discount: Ksh {{ number_format($this->discount, 2) }}</div>
        <div class="font-bold text-lg">Total: Ksh {{ number_format($this->grandTotal, 2) }}</div>
    </div>

    {{-- Actions --}}
    <div class="flex space-x-2">
        <button wire:click="finalizeSale" class="bg-green-600 text-white px-3 py-2 rounded">Complete Sale</button>
        <button wire:click="holdSale" class="bg-yellow-500 text-white px-3 py-2 rounded">Hold Sale</button>
    </div>

    {{-- Held Sales --}}
    @if($heldSales->count())
    <h3 class="mt-6 font-bold">Held Sales</h3>
    <ul>
        @foreach($heldSales as $sale)
        <li class="flex justify-between items-center border-b py-2">
            <span>Invoice: {{ $sale->invoice_no }} | Ksh {{ number_format($sale->total, 2) }}</span>
            <div class="flex space-x-2">
                <button wire:click="resumeSale({{ $sale->id }})" class="bg-blue-600 text-white px-2 py-1 rounded">Resume</button>
                <button wire:click="cancelSale({{ $sale->id }})" class="bg-red-600 text-white px-2 py-1 rounded">Cancel</button>
            </div>
        </li>
        @endforeach
    </ul>
    @endif

    @if(session()->has('message'))
    <div class="mt-4 text-green-600">{{ session('message') }}</div>
    @endif
</div>
