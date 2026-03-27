<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 p-4 sm:p-6 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    
    @if(session()->has('success'))
    <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg dark:border-slate-700 dark:bg-slate-900/90">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by product name or SKU..." class="w-full rounded-lg border border-slate-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 md:col-span-1">

            <div>
                <label class="block text-sm text-slate-700 dark:text-slate-300">Branch</label>
                <select wire:model.live="branchId" class="mt-1 block w-full rounded-lg border border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="all">All Branches</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-700 dark:text-slate-300">Expiry Status</label>
                <select wire:model.live="expiryFilter" class="mt-1 block w-full rounded-lg border border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="all">All</option>
                    <option value="expiring_soon">Expiring Soon (30 days)</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stock Table -->
    <x-ui.table-shell>
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70">
                    <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Product</th>
                    <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Branch</th>
                    <th class="cursor-pointer p-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('quantity')">Quantity</th>
                    <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('expiry_date')">Expiry Date</th>
                    <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                @php
                $isExpired = $stock->expiry_date && \Carbon\Carbon::parse($stock->expiry_date)->isPast();
                $isExpiringSoon = $stock->expiry_date && !$isExpired && \Carbon\Carbon::parse($stock->expiry_date)->isBefore(now()->addDays(30));
                @endphp
                <tr class="border-b border-slate-200 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/60 {{ $isExpired ? 'bg-red-50 dark:bg-red-900/20' : ($isExpiringSoon ? 'bg-yellow-50 dark:bg-yellow-900/20' : '') }}">
                    <td class="p-3">
                        {{ $stock->productVariant->product->name }} - {{ $stock->productVariant->label }}
                        <span class="block text-sm text-slate-500 dark:text-slate-400">SKU: {{ $stock->productVariant->product->sku }}</span>
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">{{ $stock->branch->name }}</td>
                    <td class="p-3 text-right font-bold text-slate-800 dark:text-slate-100">{{ $stock->quantity }}</td>
                    <td class="p-3">
                        @if($stock->expiry_date)
                        {{ \Carbon\Carbon::parse($stock->expiry_date)->format('d M Y') }}
                        @if($isExpired)
                        <span class="ml-2  font-semibold text-red-600">Expired</span>
                        @elseif($isExpiringSoon)
                        <span class="ml-2  font-semibold text-yellow-600">Expiring Soon</span>
                        @endif
                        @else
                        <span class="text-slate-400">N/A</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <div class="flex items-center gap-2">
                            <button wire:click="editStock({{ $stock->id }})" class="text-blue-600 hover:underline dark:text-blue-300">Adjust Stock</button>
                            <button wire:click="openTransferModal({{ $stock->id }})" class="text-indigo-600 hover:underline dark:text-indigo-300">Transfer</button>
                            @if($isExpired)
                            <button
                                x-on:click.prevent="if (confirm('Remove expired items and log as loss?')) { $wire.writeOffExpired({{ $stock->id }}) }"
                                class="text-red-600 hover:underline"
                            >
                                Remove Expired
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-3 text-center text-slate-500 dark:text-slate-400">No stock batches found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-ui.table-shell>

    <div class="mt-4">
        {{ $stocks->links() }}
    </div>

    <!-- Stock Adjustment Modal -->
    @if($showStockModal)
    <x-ui.modal-shell maxWidth="max-w-lg" title="Adjust Stock">
        <div>
            <p class="text-slate-600 dark:text-slate-300">Product: <span class="font-semibold">{{ $editingStockVariantName }}</span></p>
            <p class="text-slate-600 dark:text-slate-300">Current Quantity: <span class="font-semibold">{{ $currentQuantity }}</span></p>

            <form wire:submit.prevent="saveStockAdjustment">
                <div class="space-y-4 bg-transparent mt-4">
                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Adjustment Type</label>
                        <select wire:model="adjustmentType" class="w-full rounded-lg border border-slate-300 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            <option value="add">Add to Stock</option>
                            <option value="remove">Remove from Stock</option>
                            <option value="set">Set New Quantity</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Quantity</label>
                        <input type="number" wire:model="adjustmentQuantity" class="w-full rounded-lg border border-slate-300 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" min="0">
                        @error('adjustmentQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="closeModal" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Save Adjustment</button>
                </div>
            </form>
  
            <form wire:submit.prevent="transferStock">
                <div class="space-y-4 bg-transparent mt-4">
                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Destination Branch</label>
                        <select wire:model="transferBranchId" class="w-full rounded-lg border border-slate-300 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Select branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('transferBranchId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Quantity</label>
                        <input type="number" wire:model="transferQuantity" class="w-full rounded-lg border border-slate-300 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" min="1">
                        @error('transferQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="closeTransferModal" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Transfer</button>
                </div>
            </form>
        </div>
    </x-ui.modal-shell>
    @endif
</div>
