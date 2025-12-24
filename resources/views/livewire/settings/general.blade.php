<div class="p-6 rounded-lg shadow text-3xl">
    <nav wire:navigate class="flex justify-evenly mb-8" x-data="{ open: false }">
        <a wire:navigate.hover href="{{ route('settings') }}" class="flex justify-evenly items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('settings') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="settings-outline"></ion-icon>
            <span>settings</span>
        </a>

        <a wire:navigate.hover href="{{ route('shifts.management') }}" class="flex justify-evenly items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('shifts.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="people-outline"></ion-icon>
            <span>shifts</span>
        </a>

        {{-- @if(auth()->user()?->hasRole('admin')) --}}
        <a wire:navigate.hover href="{{ route('users.management') }}" class="flex justify-evenly items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('users.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="lock-closed-outline"></ion-icon>
            <span>role</span>
        </a>
        {{-- @endif --}}

    </nav>

    @if(session()->has('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6" role="alert">
        <p class="font-bold">Success</p>
        {{ session('success') }}
    </div>
    @endif

    <!-- Active Shift Information -->
    <div class=" text-xl uppercase shadow-md backdrop-blur-sm rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <ion-icon name="storefront-outline" class="text-4xl text-blue-600"></ion-icon>
                <div>
                    <h3 class="text-lg font-bold 800">Active Branch</h3>
                    @if(auth()->user()->activeShift)
                    <p class="">
                        <span class="font-semibold">{{ auth()->user()->activeShift->branch->name }}</span> -
                        {{ auth()->user()->activeShift->branch->address }}
                    </p>
                    @else
                    <p class="">You do not have an active shift.</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('shifts.management') }}" wire:navigate class="bg-blue-100 text-blue-800 px-4 py-2 rounded-md hover:bg-blue-200 font-semibold text-sm">Manage Shifts</a>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-8">
        <!-- Store Identity Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold  border-b pb-4 mb-6">Store Identity</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label for="store_name" class="block ">Store Name</label>
                    <input type="text" id="store_name" wire:model="store_name" class="mt-1 block w-full text-xl uppercase border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-2 ">The name of your business, as it will appear on receipts and in the app.</p>
                    @error('store_name') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="logo" class="block ">Store Logo</label>
                    <div class="mt-1 flex items-center gap-4">
                        @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-md">
                        @elseif ($existingLogo)
                        <img src="{{ asset('storage/' . $existingLogo) }}" alt="Store Logo" class="h-16 w-16 object-cover rounded-md">
                        @endif
                        <input type="file" id="logo" wire:model="logo" class="block w-full text-sm  file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div wire:loading wire:target="logo" class=mt-1">Uploading...</div>
                    @error('logo') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Financials & Tax Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold 800 border-b pb-4 mb-6">Financials & Tax</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="currency" class="block ">Currency Symbol</label>
                    <input type="text" id="currency" wire:model="currency" class="mt-1 block w-full text-xl uppercase border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., $, €, Ksh">
                    <p class="mt-2 ">The symbol for your local currency.</p>
                    @error('currency') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax" class="block ">Default Tax Rate (%)</label>
                    <input type="number" step="0.01" id="tax" wire:model="tax" class="mt-1 text-xl uppercase block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., 16">
                    <p class="mt-2 ">The default sales tax applied to all transactions.</p>
                    @error('tax') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Customer Loyalty Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold 800 border-b pb-4 mb-6">Customer Loyalty Program</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="loyalty_earn_rate" class="block 700">Spend Amount to Earn 1 Point</label>
                    <input type="number" step="1" id="loyalty_earn_rate" wire:model="loyalty_earn_rate" class="mt-1 block w-full text-xl uppercase border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-2 500">For every X amount spent, the customer earns one point.</p>
                    @error('loyalty_earn_rate') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="loyalty_redeem_value" class="block 700">Value of 1 Point (in {{ $currency ?? 'currency' }})</label>
                    <input type="number" step="0.01" id="loyalty_redeem_value" wire:model="loyalty_redeem_value" class="mt-1 block text-xl uppercase w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-2 500">The monetary value of a single loyalty point when redeemed.</p>
                    @error('loyalty_redeem_value') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Customization Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold 800 border-b pb-4 mb-6">Customization</h3>
            <label for="receipt_footer" class="block 700">Receipt Footer Text</label>
            <input type="text" id="receipt_footer" wire:model="receipt_footer" rows="6" class="mt-1 block w-full text-xl uppercase border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></input>
            <p class="mt-2 500">This text will appear at the bottom of every printed receipt.</p>
            @error('receipt_footer') <span class="text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Save Button -->
        <div class="flex justify-end pt-4">
            <button type="submit" wire:loading.attr="disabled" class="border-2 hover:text-blue-700 shadow-blue-700 hover:border-blue-700 uppercase text-xl border-blue-600  px-6 py-3 hover:border-blue-700 disabled:opacity-50 flex items-center font-bold text-base">
                <div wire:loading wire:target="save" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                Save Changes
            </button>
        </div>
    </form>
</div>
