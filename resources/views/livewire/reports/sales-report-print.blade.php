<div class="max-w-7xl mx-auto bg-white/50 shadow-2xl rounded-2xl overflow-hidden">
    <!-- Decorative Header -->
    <div class="relative gradient-blue p-10 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
        
        <div class="relative z-10 flex justify-evenly items-start">
            <div class="flex items-center gap-6">
                @if($storeLogo)
                <div class="bg-white/50 p-3 rounded-xl shadow-lg">
                    <img src="{{ asset('storage/' . $storeLogo) }}" alt="Store Logo" class="h-16 w-auto">
                </div>
                @endif
                <div>
                    <h1 class="text-4xl font-display capitalize font-bold tracking-tight">{{ $storeName }}</h1>
                    <p class="text-blue-100 mt-2 text-sm font-medium uppercase tracking-wider">Point of Sale System</p>
                </div>
            </div>
            <div class="text-right bg-white/50 bg-opacity-20 backdrop-blur-sm rounded-xl p-6 border border-white border-opacity-30">
                <h2 class="text-3xl font-display font-bold tracking-tight mb-3">SALES REPORT</h2>
                <div class="space-y-1 text-sm">
                    <p class="flex items-center justify-end gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ now()->format('d M Y, H:i') }}
                    </p>
                    <p class="font-semibold">{{ Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        
        
        @if($branch || $customer)
        <div class="relative z-10 mt-8 flex gap-3">
            @if($branch)
            <div class="inline-flex items-center gap-2 bg-white bg-opacity-25 backdrop-blur-sm px-4 py-2 rounded-full border border-white border-opacity-40">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span class="text-sm font-semibold">{{ $branch->name }}</span>
            </div>
            @endif
            @if($customer)
            <div class="inline-flex items-center gap-2 bg-white bg-opacity-25 backdrop-blur-sm px-4 py-2 rounded-full border border-white border-opacity-40">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-semibold">{{ $customer->name }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Summary Statistics -->
    <div class="p-10 bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 pattern-dots">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1 h-8 bg-gradient-to-b from-blue-500 to-purple-600 rounded-full"></div>
            <h3 class="text-2xl font-bold text-gray-900 font-display">Financial Summary</h3>
        </div>
        <div class="grid grid-cols-3 gap-5">
            <div class="group bg-white/50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500 opacity-5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <p class= font-bold text-blue-600 uppercase tracking-wider">Transactions</p>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-gray-900 font-display">{{ number_format($aggregates['transaction_count']) }}</p>
                </div>
            </div>
            <div class="group bg-white/50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-500 opacity-5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <p class= font-bold text-green-600 uppercase tracking-wider">Total Sales</p>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-green-600 font-display">{{ $currency }} {{ number_format($aggregates['total_sales'], 2) }}</p>
                </div>
            </div>
            <div class="group bg-white/50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500 opacity-5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <p class= font-bold text-emerald-600 uppercase tracking-wider">Gross Profit</p>
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-emerald-600 font-display">{{ $currency }} {{ number_format($aggregates['total_profit'], 2) }}</p>
                </div>
            </div>
            <div class="group bg-white/50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500 opacity-5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <p class= font-bold text-purple-600 uppercase tracking-wider">Total Tax</p>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 font-display">{{ $currency }} {{ number_format($aggregates['total_tax'], 2) }}</p>
                </div>
            </div>
            <div class="group bg-white/50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-500 opacity-5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <p class= font-bold text-orange-600 uppercase tracking-wider">Discounts</p>
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 font-display">{{ $currency }} {{ number_format($aggregates['total_discount'], 2) }}</p>
                </div>
            </div>
            <div class="group bg-white/50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-red-500 opacity-5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <p class= font-bold text-red-600 uppercase tracking-wider">Total Loss</p>
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-red-600 font-display">{{ $currency }} {{ number_format($aggregates['total_loss'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="p-10">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1 h-8 bg-gradient-to-b from-blue-500 to-purple-600 rounded-full"></div>
            <h3 class="text-2xl font-bold text-gray-900 font-display">Transaction Details</h3>
        </div>
        
        @if($sales->count() > 0)
        <div class="bg-white/50 rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <th class="px-5 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Date & Time</th>
                    <th class="px-5 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Invoice #</th>
                    <th class="px-5 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Branch</th>
                    <th class="px-5 py-4 text-right font-bold text-gray-700 uppercase tracking-wider">Items</th>
                    <th class="px-5 py-4 text-right font-bold text-gray-700 uppercase tracking-wider">Tax</th>
                    <th class="px-5 py-4 text-right font-bold text-gray-700 uppercase tracking-wider">Discount</th>
                    <th class="px-5 py-4 text-right font-bold text-gray-700 uppercase tracking-wider">Total</th>
                    <th class="px-5 py-4 text-center font-bold text-gray-700 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($sales as $sale)
                <tr class="{{ $sale->status === 'reversed' ? 'bg-red-50' : 'hover:bg-blue-50' }} transition-colors duration-150">
                    <td class="px-5 py-4 text-gray-700 font-medium">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-5 py-4 font-bold text-blue-600">{{ $sale->invoice_no }}</td>
                    <td class="px-5 py-4 text-gray-700">{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                    <td class="px-5 py-4 text-gray-700">{{ $sale->branch?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-4 text-right text-gray-700 font-semibold">{{ $sale->items->sum('quantity') }}</td>
                    <td class="px-5 py-4 text-right text-gray-700">{{ number_format($sale->tax, 2) }}</td>
                    <td class="px-5 py-4 text-right text-orange-600 font-semibold">{{ number_format($sale->discount, 2) }}</td>
                    <td class="px-5 py-4 text-right font-bold text-green-600 text-base">{{ number_format($sale->total, 2) }}</td>
                    <td class="px-5 py-4 text-center">
                        @if($sale->status === 'reversed')
                        <span class="inline-flex items-center px-3 py-1.5 font-bold text-red-700 bg-red-100 rounded-full border border-red-200">REVERSED</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1.5 font-bold text-green-700 bg-green-100 rounded-full border border-green-200">{{ strtoupper($sale->status) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gradient-to-r from-gray-800 to-gray-900 text-white font-bold">
                    <td colspan="5" class="px-5 py-5 text-right text-base uppercase tracking-wider">Grand Totals:</td>
                    <td class="px-5 py-5 text-right text-base">{{ $currency }} {{ number_format($sales->sum('tax'), 2) }}</td>
                    <td class="px-5 py-5 text-right text-base">{{ $currency }} {{ number_format($sales->sum('discount'), 2) }}</td>
                    <td class="px-5 py-5 text-right text-lg font-bold">{{ $currency }} {{ number_format($sales->sum('total'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        </div>
        @else
        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-xl font-semibold text-gray-600">No transactions found</p>
            <p class="text-sm text-gray-500 mt-2">Try adjusting your filters or date range</p>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-8">
        <div class="flex justify-between items-center">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm"><span class="text-gray-400">Report Period:</span> <span class="font-semibold">{{ Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</span></p>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm"><span class="text-gray-400">Total Transactions:</span> <span class="font-bold">{{ number_format($aggregates['transaction_count']) }}</span></p>
                </div>
            </div>
            <div class="text-right space-y-2">
                <div class="flex items-center justify-end gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm"><span class="text-gray-400">Generated by:</span> <span class="font-semibold">{{ auth()->user()->name ?? 'System' }}</span></p>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm"><span class="text-gray-400">Timestamp:</span> <span class="font-semibold">{{ now()->format('d M Y, H:i:s') }}</span></p>
                </div>
            </div>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-700 text-center text-gray-400">
            <p>This is a computer-generated report. All amounts are in {{ $currency }}.</p>
        </div>
    </div>

    <!-- Print Button -->
    <div class="p-8 text-center no-print bg-gray-50">
        <button onclick="window.print()" class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-10 py-4 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 font-bold shadow-xl hover:shadow-2xl transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span class="text-lg">Print Report</span>
        </button>
        <p class="text-gray-500 mt-4">Click to open print dialog or press Ctrl+P (Cmd+P on Mac)</p>
    </div>
</div>
