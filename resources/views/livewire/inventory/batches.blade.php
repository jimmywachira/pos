<div class="text-xl ">
    
    @if(session()->has('success'))
    <div class="bg-green-100 text-green-800 p-3 mb-4">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class=" shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by product name or SKU..." class="md:col-span-1 w-full  border-gray-300 shadow-sm">

            <div>
                <label class="block ">Branch</label>
                <select wire:model.live="branchId" class="mt-1 block w-full border-gray-300 shadow-sm">
                    <option value="all">All Branches</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block ">Expiry Status</label>
                <select wire:model.live="expiryFilter" class="mt-1 block text-xl  w-full border-gray-300 shadow-sm">
                    <option value="all">All</option>
                    <option value="expiring_soon">Expiring Soon (30 days)</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stock Table -->
    <div class=" shadow overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 p-3 bg-gray-100">
                    <th class="p-3  text-left">Product</th>
                    <th class="p-3 text-left">Branch</th>
                    <th class="p-3 text-right cursor-pointer" wire:click="sortBy('quantity')">Quantity</th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('expiry_date')">Expiry Date</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                @php
                $isExpired = $stock->expiry_date && \Carbon\Carbon::parse($stock->expiry_date)->isPast();
                $isExpiringSoon = $stock->expiry_date && !$isExpired && \Carbon\Carbon::parse($stock->expiry_date)->isBefore(now()->addDays(30));
                @endphp
                <tr class="border-b hover:bg-gray-50 {{ $isExpired ? 'bg-red-50' : ($isExpiringSoon ? 'bg-yellow-50' : '') }}">
                    <td class="p-3">
                        {{ $stock->productVariant->product->name }} - {{ $stock->productVariant->label }}
                        <span class="block  text-gray-500">SKU: {{ $stock->productVariant->product->sku }}</span>
                    </td>
                    <td class="p-3">{{ $stock->branch->name }}</td>
                    <td class="p-3 text-right font-bold">{{ $stock->quantity }}</td>
                    <td class="p-3">
                        @if($stock->expiry_date)
                        {{ \Carbon\Carbon::parse($stock->expiry_date)->format('d M Y') }}
                        @if($isExpired)
                        <span class="ml-2  font-semibold text-red-600">Expired</span>
                        @elseif($isExpiringSoon)
                        <span class="ml-2  font-semibold text-yellow-600">Expiring Soon</span>
                        @endif
                        @else
                        <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <div class="flex items-center gap-2">
                            <button wire:click="editStock({{ $stock->id }})" class="text-blue-600 hover:underline">Adjust Stock</button>
                            <button wire:click="openTransferModal({{ $stock->id }})" class="text-indigo-600 hover:underline">Transfer</button>
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
                    <td colspan="5" class="p-3 text-center text-gray-500">No stock batches found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stocks->links() }}
    </div>

    <!-- Stock Adjustment Modal -->
    @if($showStockModal)
    <div class="fixed inset-0 bg-gray-50/20 backdrop-blur-lg flex items-center justify-center z-50">
        <div class=" shadow-lg p-6 w-full max-w-lg">
            <h3 class="text-xl font-bold ">Adjust Stock</h3>
            <p class="text-gray-600 ">Product: <span class="font-semibold">{{ $editingStockVariantName }}</span></p>
            <p class="text-gray-600 ">Current Quantity: <span class="font-semibold">{{ $currentQuantity }}</span></p>

            <form wire:submit.prevent="saveStockAdjustment">
                <div class="space-y-4 bg-transparent mt-4">
                    <div>
                        <label class="block">Adjustment Type</label>
                        <select wire:model="adjustmentType" class="w-full   border-gray-300 shadow-sm">
                            <option value="add">Add to Stock</option>
                            <option value="remove">Remove from Stock</option>
                            <option value="set">Set New Quantity</option>
                        </select>
                    </div>
                    <div>
                        <label class="block">Quantity</label>
                        <input type="number" wire:model="adjustmentQuantity" class="w-full   border-gray-300 shadow-sm" min="0">
                        @error('adjustmentQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">Save Adjustment</button>
                </div>
            </form>
  
            <form wire:submit.prevent="transferStock">
                <div class="space-y-4 bg-transparent mt-4">
                    <div>
                        <label class="block">Destination Branch</label>
                        <select wire:model="transferBranchId" class="w-full border-gray-300 shadow-sm">
                            <option value="">Select branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('transferBranchId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">Quantity</label>
                        <input type="number" wire:model="transferQuantity" class="w-full border-gray-300 shadow-sm" min="1">
                        @error('transferQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="closeTransferModal" class="bg-gray-300 text-gray-800 px-4 py-2 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">Transfer</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
