<div class="p-6 max-w-md mx-auto bg-white shadow rounded">
    <h1 class="text-xl font-bold mb-2 text-center">Supermarket Receipt</h1>
    <p class="text-center text-sm text-gray-600 mb-4">
        Invoice: {{ $sale->invoice_no }} | Date: {{ $sale->created_at->format('d M Y H:i') }}
    </p>

    <p><strong>Branch:</strong> {{ $sale->branch->name }}</p>
    <p><strong>Cashier:</strong> {{ $sale->user->name }}</p>
    @if($sale->customer)
    <p><strong>Customer:</strong> {{ $sale->customer->name }}</p>
    @endif

    <hr class="my-3">

    <table class="w-full text-sm mb-4">
        <thead>
            <tr class="border-b">
                <th class="text-left">Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->productVariant->product->name }} - {{ $item->productVariant->label }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <div>Subtotal: Ksh {{ number_format($sale->total - $sale->tax + $sale->discount, 2) }}</div>
        <div>Tax: Ksh {{ number_format($sale->tax, 2) }}</div>
        <div>Discount: Ksh {{ number_format($sale->discount, 2) }}</div>
        <div class="font-bold text-lg">Grand Total: Ksh {{ number_format($sale->total, 2) }}</div>
    </div>

    <hr class="my-3">

    <p class="text-center text-sm text-gray-600">Thank you for shopping with us!</p>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="bg-gray-800 text-white px-3 py-1 rounded">Print Receipt</button>
    </div>
</div>
