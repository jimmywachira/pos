<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Sales Report</h2>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" wire:model.live="startDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" wire:model.live="endDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Branch</label>
                <select wire:model.live="branchId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="all">All Branches</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Customer</label>
                <select wire:model.live="customerId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="all">All Customers</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-4">
                <label class="block text-sm font-medium text-gray-700">Search by Invoice #</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="INV-..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
    </div>

    <!-- Aggregates -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Sales</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_sales'], 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Tax</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_tax'], 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Discount</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_discount'], 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Profit</h3>
            <p class="text-2xl font-bold">Ksh {{ number_format($this->aggregates['total_profit'], 2) }}</p>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('created_at')">Date {!! $this->sortIcon('created_at') !!}</th>
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('invoice_no')">Invoice # {!! $this->sortIcon('invoice_no') !!}</th>
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('customer_id')">Customer {!! $this->sortIcon('customer_id') !!}</th>
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('branch_id')">Branch {!! $this->sortIcon('branch_id') !!}</th>
                    <th class="p-3 text-right cursor-pointer flex items-center justify-end" wire:click="sortBy('total')">Total {!! $this->sortIcon('total') !!}</th>
                    <th class="p-3 text-right">Items</th>
                    <th class="p-3 text-left cursor-pointer flex items-center" wire:click="sortBy('user_id')">Cashier {!! $this->sortIcon('user_id') !!}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-3">{{ $sale->invoice_no }}</td>
                    <td class="p-3">{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                    <td class="p-3">{{ $sale->branch->name }}</td>
                    <td class="p-3 text-right">{{ number_format($sale->total, 2) }}</td>
                    <td class="p-3 text-right">{{ $sale->items->sum('quantity') }}</td>
                    <td class="p-3">{{ $sale->user->name }}</td>
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
