<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Products</h2>
        <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Product</button>
    </div>

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or SKU..." class="w-full border rounded p-2">
    </div>

    <!-- Products Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('name')">Name</th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('sku')">SKU</th>
                    <th class="p-3 text-left">Category</th>
                    <th class="p-3 text-left">Variants</th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('is_active')">Status</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3">{{ $product->sku }}</td>
                    <td class="p-3">{{ $product->category?->name ?? 'N/A' }}</td>
                    <td class="p-3">{{ $product->variants->count() }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="p-3">
                        <button wire:click="edit({{ $product->id }})" class="text-blue-600 hover:underline">Edit</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold mb-4">{{ $editingProductId ? 'Edit Product' : 'Create Product' }}</h3>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block">Name</label>
                        <input type="text" wire:model="name" class="w-full border rounded p-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">SKU</label>
                        <input type="text" wire:model="sku" class="w-full border rounded p-2">
                        @error('sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block">Description</label>
                        <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">Category</label>
                        <select wire:model="categoryId" class="w-full border rounded p-2">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categoryId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">Status</label>
                        <select wire:model="isActive" class="w-full border rounded p-2">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <hr class="my-6">

                <h4 class="font-bold mb-2">Variants</h4>
                <div class="space-y-4">
                    @foreach($variants as $index => $variant)
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2 p-3 border rounded" wire:key="variant-{{ $index }}">
                        <div class="md:col-span-2">
                            <label class="block text-sm">Label (e.g., 500ml, Large)</label>
                            <input type="text" wire:model="variants.{{ $index }}.label" class="w-full border rounded p-2">
                            @error('variants.'.$index.'.label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm">Retail Price</label>
                            <input type="number" step="0.01" wire:model="variants.{{ $index }}.retail_price" class="w-full border rounded p-2">
                            @error('variants.'.$index.'.retail_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm">Cost Price</label>
                            <input type="number" step="0.01" wire:model="variants.{{ $index }}.cost_price" class="w-full border rounded p-2">
                            @error('variants.'.$index.'.cost_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-end">
                            <button type="button" wire:click="removeVariant({{ $index }})" class="text-red-500 hover:underline">Remove</button>
                        </div>
                        <div class="md:col-span-5">
                            <label class="block text-sm">Barcode (Optional)</label>
                            <input type="text" wire:model="variants.{{ $index }}.barcode" class="w-full border rounded p-2">
                            @error('variants.'.$index.'.barcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" wire:click="addVariant" class="mt-2 text-sm text-blue-600 hover:underline">+ Add Another Variant</button>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Product</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
