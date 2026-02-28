<div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-bold text-blue-800">Sales Report & Analytics</h1>
            <p class="text-blue-600 mt-2">Track sales performance, trends, and inventory movements</p>
        </div>
        <a href="{{ route('reports.sales.print', ['startDate' => $startDate, 'endDate' => $endDate, 'branchId' => $branchId, 'customerId' => $customerId]) }}" 
           target="_blank"
           class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-lg font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Report
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-lg rounded-xl p-6 mb-8 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Start Date</label>
                <input type="date" wire:model.live="startDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">End Date</label>
                <input type="date" wire:model.live="endDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Branch</label>
                <select wire:model.live="branchId" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Customer</label>
                <select wire:model.live="customerId" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Search Invoice</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="INV-..." class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Aggregates / KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white shadow-lg rounded-xl p-6 border-2 border-blue-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" text-gray-600">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">Ksh {{ number_format($this->aggregates['total_sales'], 2) }}</p>
                </div>
                <div class="text-4xl text-blue-500 opacity-20"> 
                    <ion-icon name="wallet-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-xl p-6 border-2 border-purple-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" text-gray-600">Total Tax</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">Ksh {{ number_format($this->aggregates['total_tax'], 2) }}</p>
                </div>
                <div class="text-4xl text-purple-500 opacity-20">
                    <ion-icon name="receipt-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-xl p-6 border-2 border-orange-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" text-gray-600">Total Discount</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">Ksh {{ number_format($this->aggregates['total_discount'], 2) }}</p>
                </div>
                <div class="text-4xl text-orange-500 opacity-20">
                    <ion-icon name="pricetag-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-xl p-6 border-2 border-green-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" text-gray-600">Total Profit</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">Ksh {{ number_format($this->aggregates['total_profit'], 2) }}</p>
                </div>
                <div class="text-4xl text-green-500 opacity-20">
                    <ion-icon name="trending-up-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-xl p-6 border-2 border-red-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" text-gray-600">Total Loss</p>
                    <p class="text-2xl font-bold text-red-600 mt-2">Ksh {{ number_format($this->aggregates['total_loss'], 2) }}</p>
                </div>
                <div class="text-4xl text-red-500 opacity-20">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-blue-900 items-center gap-2">Transaction Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left"></th>
                        <th class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 font-semibold text-gray-700 " wire:click="sortBy('created_at')">Date {!! $this->sortIcon('created_at') !!}</th>
                        <th class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 font-semibold text-gray-700 " wire:click="sortBy('invoice_no')">Invoice # {!! $this->sortIcon('invoice_no') !!}</th>
                        <th class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 font-semibold text-gray-700 " wire:click="sortBy('customer_id')">Customer {!! $this->sortIcon('customer_id') !!}</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700 ">Tax</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700 ">Discount</th>
                        <th class="px-6 py-3 text-right cursor-pointer hover:bg-gray-100 font-semibold text-gray-700 " wire:click="sortBy('total')">Total {!! $this->sortIcon('total') !!}</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 ">Actions</th>
                    </tr>
                </thead>
                <tbody x-data="{ openSaleId: null }">
                    @forelse($sales as $sale)
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition {{ $sale->status === 'reversed' ? 'bg-gray-100 line-through opacity-75' : '' }}">
                        <td class="px-6 py-4 text-center">
                            <button @click="openSaleId = openSaleId === {{ $sale->id }} ? null : {{ $sale->id }}" class="text-gray-500 hover:text-blue-600 transition">
                                <ion-icon name="chevron-down-outline" x-show="openSaleId !== {{ $sale->id }}" style="font-size: 20px;"></ion-icon>
                                <ion-icon name="chevron-up-outline" x-show="openSaleId === {{ $sale->id }}" style="font-size: 20px;"></ion-icon>
                            </button>
                        </td>
                        <td class="px-6 py-4  text-gray-700">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4  font-semibold text-blue-600">{{ $sale->invoice_no }}</td>
                        <td class="px-6 py-4  text-gray-700">{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                        <td class="px-6 py-4  text-right text-gray-700">Ksh {{ number_format($sale->tax, 2) }}</td>
                        <td class="px-6 py-4  text-right text-orange-600 font-semibold">Ksh {{ number_format($sale->discount, 2) }}</td>
                        <td class="px-6 py-4  text-right font-bold text-green-600">Ksh {{ number_format($sale->total, 2) }}</td>
                        <td class="px-6 py-4 ">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('receipt.print', $sale) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition">
                                    <ion-icon name="document-text-outline" style="font-size: 18px;"></ion-icon>
                                </a>
                                @if($sale->status === 'completed')
                                    <button
                                        x-on:click.prevent="if (confirm('Reverse this sale and restore stock?')) { $wire.reverseSale({{ $sale->id }}) }"
                                        class="text-red-600 hover:text-red-800 transition" title="Reverse Sale"
                                    >
                                        <ion-icon name="close-circle-outline" style="font-size: 18px;"></ion-icon>
                                    </button>
                                @elseif($sale->status === 'reversed')
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                    <ion-icon name="checkmark-circle-outline" style="font-size: 14px;"></ion-icon>
                                    Reversed
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr x-show="openSaleId === {{ $sale->id }}" x-transition class="bg-blue-50 border-b border-gray-200">
                        <td colspan="8" class="px-6 py-4">
                            <div class="space-y-2">
                                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                    <ion-icon name="layers-outline"></ion-icon>
                                    Sale Items ({{ $sale->items->sum('quantity') }} total items)
                                </h4>
                                <table class="w-full mt-4 border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 rounded-lg">
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Product</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Qty</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Unit Price</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Line Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->items as $item)
                                        <tr class="border-b border-gray-200 hover:bg-blue-100 transition">
                                            <td class="px-4 py-2  text-gray-700">{{ $item->productVariant->product->name }} <span class="text-gray-500">- {{ $item->productVariant->label }}</span></td>
                                            <td class="px-4 py-2  text-center font-semibold">{{ $item->quantity }}</td>
                                            <td class="px-4 py-2  text-center">Ksh {{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-4 py-2  text-center font-bold text-green-600">Ksh {{ number_format($item->line_total, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <ion-icon name="search-outline" style="font-size: 32px; opacity: 0.5;"></ion-icon>
                                <p>No sales found for the selected period.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $sales->links() }}
    </div>

    <!-- Stock Movements -->
    <div class="mt-12">
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl mb-6 border border-purple-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-2xl font-bold text-purple-900 flex items-center gap-2">
                    <ion-icon name="swap-vertical-outline" style="font-size: 28px;"></ion-icon>
                    Stock Movements
                </h3>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="movementSearch"
                    placeholder="Search product, SKU, barcode..."
                    class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                >
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 ">Date</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 ">Product</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 ">Branch</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 ">Type</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700 ">Direction</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700 ">Qty</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 ">User</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 ">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr class="border-b border-gray-200 hover:bg-purple-50 transition">
                            <td class="px-6 py-4  text-gray-700">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4  text-gray-800">
                                <div class="flex flex-col">
                                    <span>{{ $movement->productVariant->product->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $movement->productVariant->label }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4  text-gray-700">{{ $movement->branch->name }}</td>
                            <td class="px-6 py-4 ">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                    {{ ucwords(str_replace('_', ' ', $movement->transaction_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $movement->direction === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <ion-icon name="{{ $movement->direction === 'in' ? 'arrow-down-outline' : 'arrow-up-outline' }}" style="font-size: 14px;"></ion-icon>
                                    {{ strtoupper($movement->direction) }}
                                </span>
                            </td>
                            <td class="px-6 py-4  text-right font-semibold text-gray-800">
                                <span class="{{ $movement->direction === 'out' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $movement->direction === 'out' ? '-' : '+' }}{{ $movement->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4  text-gray-700">{{ $movement->user?->name ?? 'System' }}</td>
                            <td class="px-6 py-4  text-gray-600">{{ $movement->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <ion-icon name="layers-outline" style="font-size: 32px; opacity: 0.5;"></ion-icon>
                                    <p>No stock movements found for the selected period.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $movements->links() }}
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        console.log('Charts script loaded');
        
        const chartColors = {
            primary: 'rgba(59, 130, 246, 1)',
            primaryLight: 'rgba(59, 130, 246, 0.1)',
            success: 'rgba(34, 197, 94, 1)',
            successLight: 'rgba(34, 197, 94, 0.1)',
            danger: 'rgba(239, 68, 68, 1)',
            dangerLight: 'rgba(239, 68, 68, 0.1)',
            warning: 'rgba(251, 146, 60, 1)',
            warningLight: 'rgba(251, 146, 60, 0.1)',
            purple: 'rgba(168, 85, 247, 1)',
            purpleLight: 'rgba(168, 85, 247, 0.1)',
            info: 'rgba(14, 165, 233, 1)',
            infoLight: 'rgba(14, 165, 233, 0.1)',
        };

        const generatePaletteColors = (count) => {
            const colors = [
                'rgba(59, 130, 246, 1)',
                'rgba(34, 197, 94, 1)',
                'rgba(239, 68, 68, 1)',
                'rgba(251, 146, 60, 1)',
                'rgba(168, 85, 247, 1)',
                'rgba(14, 165, 233, 1)',
                'rgba(236, 72, 153, 1)',
                'rgba(99, 102, 241, 1)',
                'rgba(8, 145, 178, 1)',
                'rgba(139, 92, 246, 1)',
            ];
            return colors.slice(0, count).concat(Array(Math.max(0, count - colors.length)).fill('rgba(156, 163, 175, 1)'));
        };

        // Wait for Alpine to be ready
        window.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded, Alpine version:', Alpine?.version);
        });

        document.addEventListener('alpine:init', () => {
            console.log('Alpine:init event triggered');
            
            Alpine.data('salesTrendChart', () => ({
                chart: null,
                initChart(data) {
                    try {
                        console.log('Sales Trend Chart data:', data);
                        if (!data || !data.labels) {
                            console.warn('No data for sales trend chart');
                            return;
                        }
                        const ctx = this.$refs.chartCanvas?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas context not found for sales trend');
                            return;
                        }
                        this.chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels || [],
                                datasets: [{
                                    label: 'Total Sales (Ksh)',
                                    data: data.values || [],
                                    backgroundColor: chartColors.primaryLight,
                                    borderColor: chartColors.primary,
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 5,
                                    pointHoverRadius: 7,
                                    pointBackgroundColor: chartColors.primary,
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                                        ticks: { callback: v => 'Ksh ' + v.toLocaleString() }
                                    },
                                    x: { grid: { display: false } }
                                },
                                plugins: {
                                    legend: { display: true, position: 'top' },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        titleFont: { size: 13 },
                                        bodyFont: { size: 12 },
                                        callbacks: { label: ctx => 'Ksh ' + ctx.parsed.y.toLocaleString() }
                                    }
                                }
                            }
                        });
                        console.log('Sales Trend Chart initialized successfully');
                    } catch (e) {
                        console.error('Error initializing sales trend chart:', e);
                    }
                },
                updateChart(data) {
                    try {
                        if (this.chart) {
                            this.chart.data.labels = data.labels || [];
                            this.chart.data.datasets[0].data = data.values || [];
                            this.chart.update();
                            console.log('Sales Trend Chart updated');
                        }
                    } catch (e) {
                        console.error('Error updating sales trend chart:', e);
                    }
                }
            }));

            Alpine.data('salesByBranchChart', () => ({
                chart: null,
                initChart(data) {
                    try {
                        console.log('Sales By Branch Chart data:', data);
                        if (!data || !data.labels) {
                            console.warn('No data for sales by branch chart');
                            return;
                        }
                        const ctx = this.$refs.chartCanvas?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas context not found for sales by branch');
                            return;
                        }
                        const colors = generatePaletteColors((data.labels || []).length);
                        this.chart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: data.labels || [],
                                datasets: [{
                                    data: data.values || [],
                                    backgroundColor: colors,
                                    borderColor: '#fff',
                                    borderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom', labels: { padding: 15 } },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        callbacks: { label: ctx => ctx.label + ': Ksh ' + ctx.parsed.toLocaleString() }
                                    }
                                }
                            }
                        });
                        console.log('Sales By Branch Chart initialized successfully');
                    } catch (e) {
                        console.error('Error initializing sales by branch chart:', e);
                    }
                },
                updateChart(data) {
                    try {
                        if (this.chart) {
                            const colors = generatePaletteColors((data.labels || []).length);
                            this.chart.data.labels = data.labels || [];
                            this.chart.data.datasets[0].data = data.values || [];
                            this.chart.data.datasets[0].backgroundColor = colors;
                            this.chart.update();
                            console.log('Sales By Branch Chart updated');
                        }
                    } catch (e) {
                        console.error('Error updating sales by branch chart:', e);
                    }
                }
            }));

            Alpine.data('topProductsChart', () => ({
                chart: null,
                initChart(data) {
                    try {
                        console.log('Top Products Chart data:', data);
                        if (!data || !data.labels) {
                            console.warn('No data for top products chart');
                            return;
                        }
                        const ctx = this.$refs.chartCanvas?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas context not found for top products');
                            return;
                        }
                        this.chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels || [],
                                datasets: [{
                                    label: 'Quantity Sold',
                                    data: data.quantities || [],
                                    backgroundColor: chartColors.purple,
                                    borderColor: 'rgba(168, 85, 247, 0.8)',
                                    borderWidth: 2,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                                    y: { grid: { display: false } }
                                },
                                plugins: {
                                    legend: { display: true },
                                    tooltip: { backgroundColor: 'rgba(0, 0, 0, 0.8)', padding: 12 }
                                }
                            }
                        });
                        console.log('Top Products Chart initialized successfully');
                    } catch (e) {
                        console.error('Error initializing top products chart:', e);
                    }
                },
                updateChart(data) {
                    try {
                        if (this.chart) {
                            this.chart.data.labels = data.labels || [];
                            this.chart.data.datasets[0].data = data.quantities || [];
                            this.chart.update();
                            console.log('Top Products Chart updated');
                        }
                    } catch (e) {
                        console.error('Error updating top products chart:', e);
                    }
                }
            }));

            Alpine.data('productCategoryChart', () => ({
                chart: null,
                initChart(data) {
                    try {
                        console.log('Product Category Chart data:', data);
                        if (!data || !data.labels) {
                            console.warn('No data for product category chart');
                            return;
                        }
                        const ctx = this.$refs.chartCanvas?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas context not found for product category');
                            return;
                        }
                        const colors = generatePaletteColors((data.labels || []).length);
                        this.chart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: data.labels || [],
                                datasets: [{
                                    data: data.values || [],
                                    backgroundColor: colors,
                                    borderColor: '#fff',
                                    borderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom', labels: { padding: 15 } },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        callbacks: { label: ctx => ctx.label + ': Ksh ' + ctx.parsed.toLocaleString() }
                                    }
                                }
                            }
                        });
                        console.log('Product Category Chart initialized successfully');
                    } catch (e) {
                        console.error('Error initializing product category chart:', e);
                    }
                },
                updateChart(data) {
                    try {
                        if (this.chart) {
                            const colors = generatePaletteColors((data.labels || []).length);
                            this.chart.data.labels = data.labels || [];
                            this.chart.data.datasets[0].data = data.values || [];
                            this.chart.data.datasets[0].backgroundColor = colors;
                            this.chart.update();
                            console.log('Product Category Chart updated');
                        }
                    } catch (e) {
                        console.error('Error updating product category chart:', e);
                    }
                }
            }));

            Alpine.data('paymentMethodChart', () => ({
                chart: null,
                initChart(data) {
                    try {
                        console.log('Payment Method Chart data:', data);
                        if (!data || !data.labels) {
                            console.warn('No data for payment method chart');
                            return;
                        }
                        const ctx = this.$refs.chartCanvas?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas context not found for payment method');
                            return;
                        }
                        const colors = generatePaletteColors((data.labels || []).length);
                        this.chart = new Chart(ctx, {
                            type: 'polarArea',
                            data: {
                                labels: data.labels || [],
                                datasets: [{
                                    data: data.revenues || [],
                                    backgroundColor: colors.map((c, i) => c.replace('1)', '0.7)')),
                                    borderColor: colors,
                                    borderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { r: { beginAtZero: true } },
                                plugins: {
                                    legend: { position: 'bottom', labels: { padding: 15 } },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        callbacks: { label: ctx => 'Ksh ' + ctx.parsed.r.toLocaleString() }
                                    }
                                }
                            }
                        });
                        console.log('Payment Method Chart initialized successfully');
                    } catch (e) {
                        console.error('Error initializing payment method chart:', e);
                    }
                },
                updateChart(data) {
                    try {
                        if (this.chart) {
                            const colors = generatePaletteColors((data.labels || []).length);
                            this.chart.data.labels = data.labels || [];
                            this.chart.data.datasets[0].data = data.revenues || [];
                            this.chart.data.datasets[0].backgroundColor = colors.map((c, i) => c.replace('1)', '0.7)'));
                            this.chart.data.datasets[0].borderColor = colors;
                            this.chart.update();
                            console.log('Payment Method Chart updated');
                        }
                    } catch (e) {
                        console.error('Error updating payment method chart:', e);
                    }
                }
            }));

            Alpine.data('salesComparisonChart', () => ({
                chart: null,
                initChart(data) {
                    try {
                        console.log('Sales Comparison Chart data:', data);
                        if (!data || !data.labels) {
                            console.warn('No data for sales comparison chart');
                            return;
                        }
                        const ctx = this.$refs.chartCanvas?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas context not found for sales comparison');
                            return;
                        }
                        this.chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels || [],
                                datasets: [{
                                    label: 'Daily Sales (Ksh)',
                                    data: data.values || [],
                                    backgroundColor: chartColors.success,
                                    borderColor: 'rgba(34, 197, 94, 0.8)',
                                    borderWidth: 2,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                                        ticks: { callback: v => 'Ksh ' + v.toLocaleString() }
                                    },
                                    x: { grid: { display: false } }
                                },
                                plugins: {
                                    legend: { display: true },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        callbacks: { label: ctx => 'Ksh ' + ctx.parsed.y.toLocaleString() }
                                    }
                                }
                            }
                        });
                        console.log('Sales Comparison Chart initialized successfully');
                    } catch (e) {
                        console.error('Error initializing sales comparison chart:', e);
                    }
                },
                updateChart(data) {
                    try {
                        if (this.chart) {
                            this.chart.data.labels = data.labels || [];
                            this.chart.data.datasets[0].data = data.values || [];
                            this.chart.update();
                            console.log('Sales Comparison Chart updated');
                        }
                    } catch (e) {
                        console.error('Error updating sales comparison chart:', e);
                    }
                }
            }));
        });
    </script>
    @endpush
</div>
