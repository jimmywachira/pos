<style>
    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    @media print {
        .report-shell {
            max-width: none;
            border: 0;
            border-radius: 0;
            box-shadow: none;
            margin: 0;
        }

        .report-header,
        .report-summary,
        .report-footer {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .report-header {
            padding: 14px 16px;
        }

        .report-summary,
        .report-transactions,
        .report-footer {
            padding: 10px 16px;
        }

        .print-page-break-before {
            break-before: page;
            page-break-before: always;
        }

        .report-table {
            font-size: 10px;
            line-height: 1.2;
        }

        .report-table thead {
            display: table-header-group;
        }

        .report-table tfoot {
            display: table-footer-group;
        }

        .report-table tr,
        .report-table td,
        .report-table th {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .report-table th,
        .report-table td {
            padding: 3px 6px !important;
        }

        .summary-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

@php
    $paperSize = strtolower((string) request('paper', 'a4'));
    $orientation = strtolower((string) request('orientation', 'portrait'));

    // Base first-page row capacity tuned for common print targets.
    $printBreakThreshold = match ($paperSize) {
        'letter' => 24,
        'legal' => 30,
        default => 22, // A4 portrait
    };

    if ($orientation === 'landscape') {
        $printBreakThreshold += 6;
    }

    // Header metadata blocks consume vertical space, so reduce capacity slightly.
    if ($storeLogo) {
        $printBreakThreshold -= 1;
    }

    if ($branch || $customer) {
        $printBreakThreshold -= 2;
    }

    $printBreakThreshold = max(14, $printBreakThreshold);
@endphp

<div class="report-shell mx-auto max-w-7xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
    <header class="report-header relative overflow-hidden bg-gradient-to-r from-slate-900 via-blue-900 to-blue-700 px-6 py-8 text-white sm:px-10 sm:py-10">
        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-20 -left-20 h-56 w-56 rounded-full bg-white/10"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex items-center gap-4 sm:gap-6">
                @if ($storeLogo)
                    <div class="rounded-xl border border-white/20 bg-white/20 p-2.5 shadow-lg backdrop-blur">
                        <img src="{{ asset('storage/' . $storeLogo) }}" alt="Store Logo" class="h-14 w-auto sm:h-16">
                    </div>
                @endif

                <div>
                    <h1 class="text-2xl font-bold capitalize tracking-tight sm:text-3xl">{{ $storeName }}</h1>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-100">Sales Performance Report</p>
                </div>
            </div>

            <div class="rounded-xl border border-white/20 bg-white/15 px-4 py-4 text-sm shadow-lg backdrop-blur sm:px-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-100">Generated</p>
                <p class="mt-1 text-base font-semibold">{{ now()->format('d M Y, H:i') }}</p>
                <p class="mt-3 text-xs font-semibold uppercase tracking-wider text-blue-100">Period</p>
                <p class="mt-1 font-medium">{{ Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>
        </div>

        @if ($branch || $customer)
            <div class="relative z-10 mt-5 flex flex-wrap items-center gap-2.5">
                @if ($branch)
                    <span class="inline-flex items-center rounded-full border border-white/25 bg-white/20 px-3 py-1.5 text-xs font-semibold backdrop-blur">
                        Branch: {{ $branch->name }}
                    </span>
                @endif
                @if ($customer)
                    <span class="inline-flex items-center rounded-full border border-white/25 bg-white/20 px-3 py-1.5 text-xs font-semibold backdrop-blur">
                        Customer: {{ $customer->name }}
                    </span>
                @endif
            </div>
        @endif
    </header>

    <section class="report-summary bg-slate-50 px-6 py-7 sm:px-10">
        <div class="mb-5 flex items-center gap-3">
            <div class="h-7 w-1 rounded-full bg-blue-600"></div>
            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">Financial Summary</h2>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article class="summary-card rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Transactions</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($aggregates['transaction_count']) }}</p>
            </article>

            <article class="summary-card rounded-xl border border-emerald-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Total Sales</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $currency }} {{ number_format($aggregates['total_sales'], 2) }}</p>
            </article>

            <article class="summary-card rounded-xl border border-blue-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Gross Profit</p>
                <p class="mt-2 text-2xl font-bold text-blue-700">{{ $currency }} {{ number_format($aggregates['total_profit'], 2) }}</p>
            </article>

            <article class="summary-card rounded-xl border border-purple-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-purple-700">Total Tax</p>
                <p class="mt-2 text-xl font-bold text-slate-900">{{ $currency }} {{ number_format($aggregates['total_tax'], 2) }}</p>
            </article>

            <article class="summary-card rounded-xl border border-amber-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Discounts</p>
                <p class="mt-2 text-xl font-bold text-slate-900">{{ $currency }} {{ number_format($aggregates['total_discount'], 2) }}</p>
            </article>

            <article class="summary-card rounded-xl border border-rose-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-700">Total Loss</p>
                <p class="mt-2 text-xl font-bold text-rose-700">{{ $currency }} {{ number_format($aggregates['total_loss'], 2) }}</p>
            </article>
        </div>
    </section>

    <section class="report-transactions px-6 py-8 sm:px-10 {{ $sales->count() > $printBreakThreshold ? 'print-page-break-before' : '' }}">
        <div class="mb-5 flex items-center gap-3">
            <div class="h-7 w-1 rounded-full bg-blue-600"></div>
            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">Transaction Details</h2>
        </div>

        @if ($sales->count() > 0)
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="report-table w-full text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700">
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Date & Time</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Invoice #</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Branch</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider">Items</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider">Tax</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider">Discount</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($sales as $sale)
                            <tr class="{{ $sale->status === 'reversed' ? 'bg-rose-50/60' : 'hover:bg-blue-50/40' }}">
                                <td class="px-4 py-3 text-slate-700">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 font-semibold text-blue-700">{{ $sale->invoice_no }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $sale->branch?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-700">{{ $sale->items->sum('quantity') }}</td>
                                <td class="px-4 py-3 text-right text-slate-700">{{ number_format($sale->tax, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-amber-700">{{ number_format($sale->discount, 2) }}</td>
                                <td class="px-4 py-3 text-right text-base font-bold text-emerald-700">{{ number_format($sale->total, 2) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($sale->status === 'reversed')
                                        <span class="inline-flex rounded-full border border-rose-200 bg-rose-100 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-rose-700">Reversed</span>
                                    @else
                                        <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-100 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">{{ strtoupper($sale->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="5" class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider">Grand Totals</td>
                            <td class="px-4 py-4 text-right text-sm font-semibold">{{ $currency }} {{ number_format($sales->sum('tax'), 2) }}</td>
                            <td class="px-4 py-4 text-right text-sm font-semibold">{{ $currency }} {{ number_format($sales->sum('discount'), 2) }}</td>
                            <td class="px-4 py-4 text-right text-base font-bold">{{ $currency }} {{ number_format($sales->sum('total'), 2) }}</td>
                            <td class="px-4 py-4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 py-16 text-center">
                <p class="text-lg font-semibold text-slate-600">No transactions found</p>
                <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or date range.</p>
            </div>
        @endif
    </section>

    <footer class="report-footer border-t border-slate-200 bg-slate-50 px-6 py-6 sm:px-10">
        <div class="flex flex-col gap-4 text-sm text-slate-600 md:flex-row md:items-start md:justify-between">
            <div class="space-y-1">
                <p><span class="font-semibold text-slate-700">Report Period:</span> {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                <p><span class="font-semibold text-slate-700">Total Transactions:</span> {{ number_format($aggregates['transaction_count']) }}</p>
            </div>
            <div class="space-y-1 text-left md:text-right">
                <p><span class="font-semibold text-slate-700">Generated By:</span> {{ auth()->user()->name ?? 'System' }}</p>
                <p><span class="font-semibold text-slate-700">Timestamp:</span> {{ now()->format('d M Y, H:i:s') }}</p>
            </div>
        </div>

        <p class="mt-4 border-t border-slate-200 pt-4 text-center text-xs text-slate-500">
            This is a computer-generated report. All amounts are in {{ $currency }}.
        </p>
    </footer>

    <div class="no-print bg-white px-6 py-6 text-center sm:px-10">
        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700">
            <ion-icon name="print-outline" class="text-base"></ion-icon>
            <span>Print Report</span>
        </button>
        <p class="mt-2 text-xs text-slate-500">Press Ctrl+P (Cmd+P on Mac) to print.</p>
    </div>
</div>
