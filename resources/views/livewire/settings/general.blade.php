<div class=" shadow rounded-2xl p-6 max-w-xl mx-auto border border-black-50 bg-white/75">

    <nav wire:navigate class="flex justify-evenly mb-6" x-data="{ open: false }">
        <a class="flex justify-evenly items-center font-bold text-blue-600 transition-colors {{ request()->routeIs('shifts.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="settings-outline"></ion-icon>
            <span></span>
        </a>

        <a wire:navigate.hover href="{{ route('shifts.management') }}" class="flex justify-evenly items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('shifts.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="people-outline"></ion-icon>
            <span>shifts</span>
        </a>

        <a wire:navigate.hover href="{{ route('users.management') }}" class="flex justify-evenly items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('users.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="lock-closed-outline"></ion-icon>
            <span>role</span>
        </a>


    </nav>

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div>
                <label for="store_name" class="font-medium">Store Name</label>
                <input type="text" id="store_name" wire:model="store_name" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                @error('store_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="currency" class="font-medium">Currency Symbol</label>
                    <input type="text" id="currency" wire:model="currency" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                    @error('currency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax" class="font-medium">Tax Rate (%)</label>
                    <input type="number" step="0.01" id="tax" wire:model="tax" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                    @error('tax') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="receipt_footer" class="font-medium">Receipt Footer Text</label>
                <textarea id="receipt_footer" wire:model="receipt_footer" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1"></textarea>
                @error('receipt_footer') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-medium">Loyalty Program</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="loyalty_earn_rate" class="font-medium">Spend Amount to Earn 1 Point</label>
                        <input type="number" step="1" id="loyalty_earn_rate" wire:model="loyalty_earn_rate" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                        @error('loyalty_earn_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="loyalty_redeem_value" class="font-medium">Value of 1 Point (in {{ $currency }})</label>
                        <input type="number" step="0.01" id="loyalty_redeem_value" wire:model="loyalty_redeem_value" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                        @error('loyalty_redeem_value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="logo" class="font-medium">Store Logo</label>
                <div class="mt-1 flex items-center gap-4">
                    @if ($existingLogo)
                    <img src="{{ asset('storage/' . $existingLogo) }}" alt="Store Logo" class="h-16 w-16 object-cover rounded-md">
                    @endif
                    <input type="file" id="logo" wire:model="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div wire:loading wire:target="logo" class="text-sm text-gray-500 mt-1">Uploading...</div>
                @error('logo') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" wire:loading.attr="disabled" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50 flex items-center">
                <div wire:loading wire:target="save" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                <span wire:loading.remove wire:target="save">Save Settings</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
