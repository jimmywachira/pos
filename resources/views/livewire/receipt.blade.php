<x-receipt-layout :sale="$sale">
    <div class="max-w-xs mx-auto bg-white p-4 shadow-lg my-4 text-sm">
        @if(isset($settings['logo']) && $settings['logo'])
        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Store Logo" class="mx-auto h-16 w-auto mb-4">
        @endif
        <div class="text-center mb-4">
            <h1 class="text-xl font-bold uppercase">{{ $settings['store_name'] ?? 'DemoPOS' }}</h1>
            <p>{{ $sale->branch->name }}</p>
            <p>{{ $sale->branch->address }}</p>
        </div>

        <div class="mb-4">
            <p><strong>Date:</strong> {{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Invoice:</strong> {{ $sale->invoice_no }}</p>
            <p><strong>Cashier:</strong> {{ $sale->user->name }}</p>
            @if($sale->customer)
            <p><strong>Customer:</strong> {{ $sale->customer->name }}</p>
            @endif
        </div>

        <div class="border-t border-b border-dashed border-black py-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">ITEM</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">PRICE</th>
                        <th class="text-right">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td colspan="4">{{ $item->productVariant->product->name }} - {{ $item->productVariant->label }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="py-2">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="font-semibold">Subtotal</td>
                        <td class="text-right">{{ number_format($sale->total - $sale->tax + $sale->discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tax</td>
                        <td class="text-right">{{ number_format($sale->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Discount</td>
                        <td class="text-right">-{{ number_format($sale->discount, 2) }}</td>
                    </tr>
                    <tr class="font-bold text-base border-t border-dashed border-black">
                        <td>GRAND TOTAL</td>
                        <td class="text-right">{{ number_format($sale->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($sale->etims_status || $sale->etims_qr_src)
        <div class="pt-2 border-t border-dashed border-black text-center">
            <p class="font-semibold">eTIMS</p>
            @if($sale->etims_receipt_no)
            <p><strong>Receipt No:</strong> {{ $sale->etims_receipt_no }}</p>
            @endif
            @if($sale->etims_status)
            <p><strong>Status:</strong> {{ ucfirst($sale->etims_status) }}</p>
            @endif
            @if($sale->etims_qr_src)
            <div class="mt-2 flex justify-center">
                <img src="{{ $sale->etims_qr_src }}" alt="eTIMS QR" class="h-28 w-28">
            </div>
            @endif
        </div>
        @endif

        <div class="text-center pt-2 border-t border-dashed border-black">
            <p class="font-semibold">{{ $settings['receipt_footer'] ?? 'Thank you for your purchase!' }}</p>
        </div>

        <div class="text-center mt-6 no-print">
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-md text-base hover:bg-black">
                Print Receipt
            </button>
        </div>
    </div>
</x-receipt-layout>
