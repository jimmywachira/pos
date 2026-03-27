<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 p-4 sm:p-6 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    <x-ui.section-card class="mb-6" bodyClass="p-4">
    <nav wire:navigate class="flex flex-wrap justify-evenly gap-3" x-data="{ open: false }">
        <a wire:navigate.hover href="{{ route('settings') }}" class="flex items-center gap-2 rounded-lg px-4 py-2 font-bold transition-colors {{ request()->routeIs('settings') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'text-slate-700 hover:text-blue-600 dark:text-slate-200 dark:hover:text-blue-300' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="settings-outline"></ion-icon>
            <span>settings</span>
        </a>

        <a wire:navigate.hover href="{{ route('shifts.management') }}" class="flex items-center gap-2 rounded-lg px-4 py-2 font-bold transition-colors {{ request()->routeIs('shifts.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'text-slate-700 hover:text-blue-600 dark:text-slate-200 dark:hover:text-blue-300' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="people-outline"></ion-icon>
            <span>shifts</span>
        </a>

        {{-- @if(auth()->user()?->hasRole('admin')) --}}
        <a wire:navigate.hover href="{{ route('users.management') }}" class="flex items-center gap-2 rounded-lg px-4 py-2 font-bold transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'text-slate-700 hover:text-blue-600 dark:text-slate-200 dark:hover:text-blue-300' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="lock-closed-outline"></ion-icon>
            <span>role</span>
        </a>
        {{-- @endif --}}

    </nav>
    </x-ui.section-card>

    @if(session()->has('success'))
    <div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-100 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300" role="alert">
        <p class="font-bold">Success</p>
        {{ session('success') }}
    </div>
    @endif

    <!-- Active Shift Information -->
    <x-ui.section-card class="mb-8" bodyClass="p-6">
    <div class="text-xl uppercase">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <ion-icon name="storefront-outline" class="text-4xl text-blue-600"></ion-icon>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Active Branch</h3>
                    @if(auth()->user()->activeShift)
                    <p class="text-slate-700 dark:text-slate-200">
                        <span class="font-semibold">{{ auth()->user()->activeShift->branch->name }}</span> -
                        {{ auth()->user()->activeShift->branch->address }}
                    </p>
                    @else
                    <p class="text-slate-700 dark:text-slate-200">You do not have an active shift.</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('shifts.management') }}" wire:navigate class="bg-blue-100 text-blue-800 px-4 py-2 rounded-md hover:bg-blue-200 font-semibold text-sm">Manage Shifts</a>
        </div>
    </div>
    </x-ui.section-card>

    <form wire:submit.prevent="save" class="space-y-8">
        <!-- Store Identity Section -->
        <x-ui.section-card title="Store Identity" class="mb-2" bodyClass="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label for="store_name" class="block text-sm text-slate-700 dark:text-slate-300">Store Name</label>
                    <input type="text" id="store_name" wire:model="store_name" class="mt-1 block w-full rounded-md border-slate-300 text-sm uppercase shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">The name of your business, as it will appear on receipts and in the app.</p>
                    @error('store_name') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="logo" class="block text-sm text-slate-700 dark:text-slate-300">Store Logo</label>
                    <div class="mt-1 flex items-center gap-4">
                        @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-md">
                        @elseif ($existingLogo)
                        <img src="{{ asset('storage/' . $existingLogo) }}" alt="Store Logo" class="h-16 w-16 object-cover rounded-md">
                        @endif
                        <input type="file" id="logo" wire:model="logo" class="block w-full text-sm text-slate-700 dark:text-slate-300 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300">
                    </div>
                    <div wire:loading wire:target="logo" class=mt-1">Uploading...</div>
                    @error('logo') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-ui.section-card>

        <!-- Financials & Tax Section -->
        <x-ui.section-card title="Financials & Tax" bodyClass="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="currency" class="block text-sm text-slate-700 dark:text-slate-300">Currency Symbol</label>
                    <input type="text" id="currency" wire:model="currency" class="mt-1 block w-full rounded-md border-slate-300 text-sm uppercase shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="e.g., $, €, Ksh">
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">The symbol for your local currency.</p>
                    @error('currency') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax" class="block text-sm text-slate-700 dark:text-slate-300">Default Tax Rate (%)</label>
                    <input type="number" step="0.01" id="tax" wire:model="tax" class="mt-1 block w-full rounded-md border-slate-300 text-sm uppercase shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="e.g., 16">
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">The default sales tax applied to all transactions.</p>
                    @error('tax') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-ui.section-card>

        <!-- Customer Loyalty Section -->
        <x-ui.section-card title="Customer Loyalty Program" bodyClass="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="loyalty_earn_rate" class="block text-sm text-slate-700 dark:text-slate-300">Spend Amount to Earn 1 Point</label>
                    <input type="number" step="1" id="loyalty_earn_rate" wire:model="loyalty_earn_rate" class="mt-1 block w-full rounded-md border-slate-300 text-sm uppercase shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">For every X amount spent, the customer earns one point.</p>
                    @error('loyalty_earn_rate') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="loyalty_redeem_value" class="block text-sm text-slate-700 dark:text-slate-300">Value of 1 Point (in {{ $currency ?? 'currency' }})</label>
                    <input type="number" step="0.01" id="loyalty_redeem_value" wire:model="loyalty_redeem_value" class="mt-1 block w-full rounded-md border-slate-300 text-sm uppercase shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">The monetary value of a single loyalty point when redeemed.</p>
                    @error('loyalty_redeem_value') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-ui.section-card>

        <!-- Customization Section -->
        <x-ui.section-card title="Customization" bodyClass="p-6">
            <label for="receipt_footer" class="block text-sm text-slate-700 dark:text-slate-300">Receipt Footer Text</label>
            <input type="text" id="receipt_footer" wire:model="receipt_footer" rows="6" class="mt-1 block w-full rounded-md border-slate-300 text-sm uppercase shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></input>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">This text will appear at the bottom of every printed receipt.</p>
            @error('receipt_footer') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
        </x-ui.section-card>

        <!-- Save Button -->
        <div class="flex justify-end pt-4">
            <button type="submit" wire:loading.attr="disabled" class="flex items-center rounded-lg border border-blue-600 px-6 py-3 text-base font-bold uppercase text-blue-700 shadow-sm transition hover:border-blue-700 hover:bg-blue-50 disabled:opacity-50 dark:border-blue-400 dark:text-blue-300 dark:hover:bg-blue-900/20">
                <div wire:loading wire:target="save" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                Save Changes
            </button>
        </div>
    </form>
</div>
