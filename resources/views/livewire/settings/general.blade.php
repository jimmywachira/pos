<div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">General Settings</h2>

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-4">
        <div>
            <label class="block">Store Name</label>
            <input type="text" wire:model="store_name" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block">Currency</label>
            <input type="text" wire:model="currency" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block">Tax (%)</label>
            <input type="number" wire:model="tax" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block">Receipt Footer</label>
            <textarea wire:model="receipt_footer" class="w-full border rounded p-2"></textarea>
        </div>
    </div>

    <button wire:click="save" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Save
    </button>
</div>
