<div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-2xl">
    <h2 class="text-xl font-bold mb-4">POS Settings</h2>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Store Name</label>
            <input type="text" wire:model="store_name" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Currency</label>
            <input type="text" wire:model="currency" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Tax (%)</label>
            <input type="number" step="0.01" wire:model="tax" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Receipt Footer</label>
            <textarea wire:model="receipt_footer" class="w-full border rounded p-2"></textarea>
        </div>
    </div>

    <div class="mt-6">
        <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Save Settings
        </button>
    </div>
</div>

<script>
    window.addEventListener('toast', event => {
        alert(event.detail.message); // replace with SweetAlert or Toastr if you prefer
    });

</script>
