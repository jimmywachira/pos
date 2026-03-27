<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 p-4 sm:p-6 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-slate-100">Sales Report & Analytics</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-300">Track sales performance, trends, and inventory movements</p>
        </div>
        <a href="{{ route('reports.sales.print', ['startDate' => $startDate, 'endDate' => $endDate, 'branchId' => $branchId, 'customerId' => $customerId]) }}" 
           target="_blank"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 sm:px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Report
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-lg dark:border-slate-700 dark:bg-slate-900/90">
        <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Filters</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Start Date</label>
                <input type="date" wire:model.live="startDate" class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">End Date</label>
                <input type="date" wire:model.live="endDate" class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Branch</label>
                <select wire:model.live="branchId" class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="all">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Customer</label>
                <select wire:model.live="customerId" class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="all">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Search Invoice</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="INV-..." class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400">
            </div>
        </div>
    </div>

    <!-- Aggregates / KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="rounded-xl border-2 border-blue-500 bg-white p-6 shadow-lg transition hover:shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-300">Total Sales</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Ksh {{ number_format($this->aggregates['total_sales'], 2) }}</p>
                </div>
                <div class="text-4xl text-blue-500 opacity-20"> 
                    <ion-icon name="wallet-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="rounded-xl border-2 border-purple-500 bg-white p-6 shadow-lg transition hover:shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-300">Total Tax</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Ksh {{ number_format($this->aggregates['total_tax'], 2) }}</p>
                </div>
                <div class="text-4xl text-purple-500 opacity-20">
                    <ion-icon name="receipt-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="rounded-xl border-2 border-orange-500 bg-white p-6 shadow-lg transition hover:shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-300">Total Discount</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Ksh {{ number_format($this->aggregates['total_discount'], 2) }}</p>
                </div>
                <div class="text-4xl text-orange-500 opacity-20">
                    <ion-icon name="pricetag-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="rounded-xl border-2 border-green-500 bg-white p-6 shadow-lg transition hover:shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-300">Total Profit</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">Ksh {{ number_format($this->aggregates['total_profit'], 2) }}</p>
                </div>
                <div class="text-4xl text-green-500 opacity-20">
                    <ion-icon name="trending-up-outline"></ion-icon>
                </div>
            </div>
        </div>
        <div class="rounded-xl border-2 border-red-500 bg-white p-6 shadow-lg transition hover:shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-300">Total Loss</p>
                    <p class="text-2xl font-bold text-red-600 mt-2">Ksh {{ number_format($this->aggregates['total_loss'], 2) }}</p>
                </div>
                <div class="text-4xl text-red-500 opacity-20">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-900">
        <div class="border-b border-slate-200 bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 dark:border-slate-700 dark:from-blue-900/30 dark:to-blue-800/20">
            <h3 class="items-center gap-2 text-2xl font-bold text-blue-900 dark:text-blue-200">Transaction Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70">
                        <th class="px-6 py-3 text-left"></th> 
                        <th class="cursor-pointer px-6 py-3 text-left font-semibold text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/60" wire:click="sortBy('created_at')">Date {!! $this->sortIcon('created_at') !!}</th>
                        <th class="cursor-pointer px-6 py-3 text-left font-semibold text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/60" wire:click="sortBy('invoice_no')">Invoice # {!! $this->sortIcon('invoice_no') !!}</th>
                        <th class="cursor-pointer px-6 py-3 text-left font-semibold text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/60" wire:click="sortBy('customer_id')">Customer {!! $this->sortIcon('customer_id') !!}</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700 dark:text-slate-200">Tax</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700 dark:text-slate-200">Discount</th>
                        <th class="cursor-pointer px-6 py-3 text-right font-semibold text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/60" wire:click="sortBy('total')">Total {!! $this->sortIcon('total') !!}</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-200">Actions</th>
                    </tr>
                </thead>
                <tbody x-data="{ openSaleId: null }">
                    @forelse($sales as $sale)
                    <tr class="border-b border-slate-200 transition hover:bg-blue-50 dark:border-slate-700 dark:hover:bg-blue-900/20 {{ $sale->status === 'reversed' ? 'bg-slate-100 line-through opacity-75 dark:bg-slate-800/70' : '' }}">
                        <td class="px-6 py-4 text-center">
                            <button @click="openSaleId = openSaleId === {{ $sale->id }} ? null : {{ $sale->id }}" class="text-slate-500 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400">
                                <ion-icon name="chevron-down-outline" x-show="openSaleId !== {{ $sale->id }}" style="font-size: 20px;"></ion-icon>
                                <ion-icon name="chevron-up-outline" x-show="openSaleId === {{ $sale->id }}" style="font-size: 20px;"></ion-icon>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-200">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4  font-semibold text-blue-600">{{ $sale->invoice_no }}</td>
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-200">{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                        <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-200">Ksh {{ number_format($sale->tax, 2) }}</td>
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
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                    <ion-icon name="checkmark-circle-outline" style="font-size: 14px;"></ion-icon>
                                    Reversed
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr x-show="openSaleId === {{ $sale->id }}" x-transition class="border-b border-slate-200 bg-blue-50 dark:border-slate-700 dark:bg-blue-900/20">
                        <td colspan="8" class="px-6 py-4">
                            <div class="space-y-2">
                                <h4 class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-100">
                                    <ion-icon name="layers-outline"></ion-icon>
                                    Sale Items ({{ $sale->items->sum('quantity') }} total items)
                                </h4>
                                <table class="w-full mt-4 border-collapse">
                                    <thead>
                                        <tr class="rounded-lg bg-slate-100 dark:bg-slate-800">
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-700 dark:text-slate-200">Product</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-slate-700 dark:text-slate-200">Qty</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-slate-700 dark:text-slate-200">Unit Price</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-slate-700 dark:text-slate-200">Line Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->items as $item)
                                        <tr class="border-b border-slate-200 transition hover:bg-blue-100 dark:border-slate-700 dark:hover:bg-blue-900/30">
                                            <td class="px-4 py-2 text-slate-700 dark:text-slate-200">{{ $item->productVariant->product->name }} <span class="text-slate-500 dark:text-slate-400">- {{ $item->productVariant->label }}</span></td>
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
                        <td colspan="8" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
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
        <div class="mb-6 rounded-xl border border-purple-200 bg-gradient-to-r from-purple-50 to-purple-100 p-6 dark:border-purple-700/60 dark:from-purple-900/30 dark:to-purple-800/20">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="flex items-center gap-2 text-2xl font-bold text-purple-900 dark:text-purple-200">
                    <ion-icon name="swap-vertical-outline" style="font-size: 28px;"></ion-icon>
                    Stock Movements
                </h3>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="movementSearch"
                    placeholder="Search product, SKU, barcode..."
                    class="w-full md:w-80 rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400"
                >
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70">
                            <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-200">Date</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-200">Product</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-200">Branch</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-200">Type</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700 dark:text-slate-200">Direction</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-700 dark:text-slate-200">Qty</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-200">User</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-200">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr class="border-b border-slate-200 transition hover:bg-purple-50 dark:border-slate-700 dark:hover:bg-purple-900/20">
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-200">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-slate-800 dark:text-slate-100">
                                <div class="flex flex-col">
                                    <span>{{ $movement->productVariant->product->name }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $movement->productVariant->label }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-200">{{ $movement->branch->name }}</td>
                            <td class="px-6 py-4 ">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ ucwords(str_replace('_', ' ', $movement->transaction_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $movement->direction === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <ion-icon name="{{ $movement->direction === 'in' ? 'arrow-down-outline' : 'arrow-up-outline' }}" style="font-size: 14px;"></ion-icon>
                                    {{ strtoupper($movement->direction) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-800 dark:text-slate-100">
                                <span class="{{ $movement->direction === 'out' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $movement->direction === 'out' ? '-' : '+' }}{{ $movement->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-200">{{ $movement->user?->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $movement->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
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
