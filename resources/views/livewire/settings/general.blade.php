<div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">General Settings</h2>

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div>
                <label for="store_name" class="block font-medium">Store Name</label>
                <input type="text" id="store_name" wire:model="store_name" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                @error('store_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="currency" class="block font-medium">Currency Symbol</label>
                    <input type="text" id="currency" wire:model="currency" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                    @error('currency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax" class="block font-medium">Tax Rate (%)</label>
                    <input type="number" step="0.01" id="tax" wire:model="tax" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                    @error('tax') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="receipt_footer" class="block font-medium">Receipt Footer Text</label>
                <textarea id="receipt_footer" wire:model="receipt_footer" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1"></textarea>
                @error('receipt_footer') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="logo" class="block font-medium">Store Logo</label>
                <div class="mt-1 flex items-center gap-4">
                    @if ($existingLogo)
                    <img src="{{ asset('storage/' . $existingLogo) }}" alt="Store Logo" class="h-16 w-16 object-cover rounded-md">
                    @endif
                    <input type="file" id="logo" wire:model="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
