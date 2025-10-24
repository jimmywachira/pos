<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Sales Report</h2>

    <!-- Filters -->
    <div class=" shadow rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block  text-gray-700">Start Date</label>
                <input type="date" wire:model.live="startDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block  text-gray-700">End Date</label>
                <input type="date" wire:model.live="endDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block  text-gray-700">Branch</label>
                <select wire:model.live="branchId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="all">All Branches</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block  text-gray-700">Customer</label>
                <select wire:model.live="customerId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="all">All Customers</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-4">
                <label class="block  text-gray-700">Search by Invoice #</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="INV-..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
    </div>

    <!-- Aggregates -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Sales</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_sales'], 2) }}</p>
        </div>
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Tax</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_tax'], 2) }}</p>
        </div>
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Discount</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_discount'], 2) }}</p>
        </div>
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Profit</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_profit'], 2) }}</p>
        </div>
    </div>

    <!-- Sales Table -->
    <div class=" shadow rounded-lg overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-3 w-12"></th>
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('created_at')">Date {!! $this->sortIcon('created_at') !!}</th>
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('invoice_no')">Invoice # {!! $this->sortIcon('invoice_no') !!}</th>
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('customer_id')">Customer {!! $this->sortIcon('customer_id') !!}</th>
                    <th class="p-3 text-right">Tax</th>
                    <th class="p-3 text-right">Discount</th>
                    <th class="p-3 text-right cursor-pointer flex items-center justify-end" wire:click="sortBy('total')">Total {!! $this->sortIcon('total') !!}</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody x-data="{ openSaleId: null }">
                @forelse($sales as $sale)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 text-center">
                        <button @click="openSaleId = openSaleId === {{ $sale->id }} ? null : {{ $sale->id }}">
                            <ion-icon name="chevron-down-outline" x-show="openSaleId !== {{ $sale->id }}"></ion-icon>
                            <ion-icon name="chevron-up-outline" x-show="openSaleId === {{ $sale->id }}"></ion-icon>
                        </button>
                    </td>
                    <td class="p-3">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-3 ">{{ $sale->invoice_no }}</td>
                    <td class="p-3">{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                    <td class="p-3 text-right">{{ number_format($sale->tax, 2) }}</td>
                    <td class="p-3 text-right">{{ number_format($sale->discount, 2) }}</td>
                    <td class="p-3 text-right font-bold">{{ number_format($sale->total, 2) }}</td>
                    <td class="p-3">
                        <a href="{{ route('receipt.print', $sale) }}" target="_blank" class="text-blue-600 hover:underline">View Receipt</a>
                    </td>
                </tr>
                <tr x-show="openSaleId === {{ $sale->id }}" x-transition>
                    <td colspan="8" class="p-4 bg-gray-100">
                        <h4 class="font-bold mb-2">Sale Items ({{ $sale->items->sum('quantity') }} total)</h4>
                        <table class="w-full ">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-2 text-left">Product</th>
                                    <th class="p-2 text-right">Quantity</th>
                                    <th class="p-2 text-right">Unit Price</th>
                                    <th class="p-2 text-right">Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                <tr class="border-b">
                                    <td class="p-2">{{ $item->productVariant->product->name }} - {{ $item->productVariant->label }}</td>
                                    <td class="p-2 text-right">{{ $item->quantity }}</td>
                                    <td class="p-2 text-right">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="p-2 text-right font-semibold">{{ number_format($item->line_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-3 text-center text-gray-500">No sales found for the selected period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
