<div class="p-6">
    <h2 class=" font-bold mb-6">Sales Report</h2>

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
                <label class="block text-gray-700">Customer</label>
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

    <!-- Charts/Infographics -->
    {{-- <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6" wire:ignore>
        <div class="lg:col-span-3 shadow rounded-lg p-4" x-data="salesTrendChart" x-init="initChart(@json($this->salesTrendData))" @update-charts.window="updateChart($event.detail.salesTrendData)">
            <h3 class="font-bold text-gray-800 mb-2">Sales Trend</h3>
            <div class="h-64">
                <canvas x-ref="chartCanvas"></canvas>
            </div>
        </div>
        <div class="lg:col-span-2 shadow rounded-lg p-4" x-data="salesByBranchChart" x-init="initChart(@json($this->salesByBranchData))" @update-charts.window="updateChart($event.detail.salesByBranchData)">
            <h3 class="font-bold text-gray-800 mb-2">Sales by Branch</h3>
            <div class="h-64"><canvas x-ref="chartCanvas"></canvas></div>
        </div>
    </div> --}}

    <!-- Aggregates -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Sales</h3>
            <p class=" font-bold">Ksh {{ number_format($this->aggregates['total_sales'], 2) }}</p>
        </div>
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Tax</h3>
            <p class=" font-bold">Ksh {{ number_format($this->aggregates['total_tax'], 2) }}</p>
        </div>
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Discount</h3>
            <p class=" font-bold">Ksh {{ number_format($this->aggregates['total_discount'], 2) }}</p>
        </div>
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Profit</h3>
            <p class=" font-bold">Ksh {{ number_format($this->aggregates['total_profit'], 2) }}</p>
        </div>
        <div class=" shadow rounded-lg p-4">
            <h3 class="text-gray-500">Total Loss</h3>
            <p class=" font-bold text-red-600">Ksh {{ number_format($this->aggregates['total_loss'], 2) }}</p>
        </div>
    </div>

    <!-- Sales Table -->
    <div class=" shadow rounded-lg overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b backdrop-blur-md">
                    <th class="p-3 "></th>
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
                <tr class="border-b hover:bg-gray-50 {{ $sale->status === 'reversed' ? 'line-through text-gray-400' : '' }}">
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
                        <div class="flex items-center gap-2">
                            <a href="{{ route('receipt.print', $sale) }}" target="_blank" class="text-blue-600 hover:underline">View Receipt</a>
                            @if($sale->status === 'completed')
                                <button
                                    x-on:click.prevent="if (confirm('Reverse this sale and restore stock?')) { $wire.reverseSale({{ $sale->id }}) }"
                                    class="text-red-600 hover:underline"
                                >
                                    Reverse
                                </button>
                            @elseif($sale->status === 'reversed')
                            <span class="font-semibold text-red-600">Reversed</span>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr x-show="openSaleId === {{ $sale->id }}" x-transition>
                    <td colspan="8" class="p-3 backdrop-blur-sm bg-white/50">
                        <h4 class="font-bold mb-2">Sale Items ({{ $sale->items->sum('quantity') }} total)</h4>
                        <table class="w-full table-auto border-collapse">
                            <thead class="backdrop-blur-sm">
                                <tr>
                                    <th class="p-2 text-left">Product</th>
                                    <th class="p-2 text-center">Quantity</th>
                                    <th class="p-2 text-center">Unit Price</th>
                                    <th class="p-2 text-center">Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                <tr class="border-b">
                                    <td class="p-2">{{ $item->productVariant->product->name }} - {{ $item->productVariant->label }}</td>
                                    <td class="p-2 text-right">{{ $item->quantity }}</td>
                                    <td class="p-2 text-center">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="p-2 text-center font-semibold">{{ number_format($item->line_total, 2) }}</td>
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

    <!-- Stock Movements -->
    <div class="mt-10">
        <div class="flex flex-col md:flex-row md:items-center justify-center gap-3 mb-4">
            <h3 class="text-2xl text-center p-2 text-blue-500 font-bold">Stock Movements</h3>
            <input
                type="text"
                wire:model.live.debounce.300ms="movementSearch"
                placeholder="Search product, SKU, barcode..."
                class="w-full md:w-80 border-gray-300 rounded-md shadow-sm"
            >
        </div>

        <div class="shadow rounded-lg overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b backdrop-blur-md">
                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3 text-left">Branch</th>
                        <th class="p-3 text-left">Transaction Type</th>
                        <th class="p-3 text-center">Direction</th>
                        <th class="p-3 text-right">Qty</th>
                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                        <td class="p-3">
                            {{ $movement->productVariant->product->name }} - {{ $movement->productVariant->label }}
                        </td>
                        <td class="p-3">{{ $movement->branch->name }}</td>
                        <td class="p-3">{{ ucwords(str_replace('_', ' ', $movement->transaction_type)) }}</td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 text-xs rounded-full {{ $movement->direction === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ strtoupper($movement->direction) }}
                            </span>
                        </td>
                        <td class="p-3 text-right font-semibold">
                            {{ $movement->direction === 'out' ? '-' : '+' }}{{ $movement->quantity }}
                        </td>
                        <td class="p-3">{{ $movement->user?->name ?? 'System' }}</td>
                        <td class="p-3">{{ $movement->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-3 text-center text-gray-500">No stock movements found for the selected period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $movements->links() }}
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('salesTrendChart', () => ({
                chart: null
                , initChart(data) {
                    const ctx = this.$refs.chartCanvas.getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'line'
                        , data: {
                            labels: data.labels
                            , datasets: [{
                                label: 'Total Sales'
                                , data: data.values
                                , backgroundColor: 'rgba(37, 99, 235, 0.2)'
                                , borderColor: 'rgba(37, 99, 235, 1)'
                                , borderWidth: 2
                                , fill: true
                                , tension: 0.3
                            }]
                        }
                        , options: {
                            responsive: true
                            , maintainAspectRatio: false
                            , scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
                , updateChart(data) {
                    if (this.chart) {
                        this.chart.data.labels = data.labels;
                        this.chart.data.datasets[0].data = data.values;
                        this.chart.update();
                    }
                }
            }));

            Alpine.data('salesByBranchChart', () => ({
                chart: null
                , initChart(data) {
                    const ctx = this.$refs.chartCanvas.getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'doughnut'
                        , data: {
                            labels: data.labels
                            , datasets: [{
                                data: data.values
                            }]
                        }
                        , options: {
                            responsive: true
                            , maintainAspectRatio: false
                        }
                    });
                }
                , updateChart(data) {
                    if (this.chart) {
                        this.chart.data.labels = data.labels;
                        this.chart.data.datasets[0].data = data.values;
                        this.chart.update();
                    }
                }
            }));
        });

    </script>
    @endpush
</div>
