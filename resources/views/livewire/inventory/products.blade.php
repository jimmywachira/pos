<div class="min-h-screen p-4 sm:p-6 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
   
    @if(session()->has('success'))
    <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ session('success') }}</div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-4 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg dark:border-slate-700 dark:bg-slate-900/90">
        <div class="flex flex-col items-stretch gap-3 md:flex-row md:items-center">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or SKU ..." class="w-full rounded-lg border border-slate-300 p-3 text-base shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 md:w-3/4">
            <button wire:click="create" class="inline-flex w-full items-center justify-center rounded-lg border border-blue-600 px-4 py-2 text-base font-semibold text-blue-600 transition hover:bg-blue-50 hover:text-blue-700 dark:border-blue-400 dark:text-blue-300 dark:hover:bg-blue-500/10 md:w-1/4">
            <ion-icon class=" font-bold" size="large" name="add-outline"></ion-icon>
            <span class="ml-1">Add Product</span>
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <x-ui.table-shell>
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70">
                    <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Image</th>
                    <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('name')">Name</th>
                    <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('sku')">SKU</th>
                    <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Category</th>
                    <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Variants</th>
                    <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('is_active')">Status</th>
                    <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b border-slate-200 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/60" wire:dblClick="edit({{ $product->id }})" id="product-{{ $product->id }}-row">
                    <td class="p-3">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://picsum.photos/seed/' . $product->id . '/200/200' }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover">
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">{{ $product->name }}</td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">{{ $product->sku }}</td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">{{ $product->category?->name ?? 'N/A' }}</td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">{{ $product->variants->count() }}</td>
                    <td class="p-3">
                        <span class="rounded-full px-2 py-1 text-xs {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="p-3">
                        <button wire:click="edit({{ $product->id }})" class="text-blue-600 hover:underline dark:text-blue-300">Edit</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-3 text-center text-slate-500 dark:text-slate-400">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-ui.table-shell>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <x-ui.modal-shell maxWidth="max-w-3xl" :title="$editingProductId ? 'Edit Product' : 'Create Product'">
        <div class="max-h-[70vh] overflow-y-auto">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block">Name</label>
                        <input type="text" wire:model="name" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">SKU</label>
                        <input type="text" wire:model="sku" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        @error('sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block">Description</label>
                        <textarea wire:model="description" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block">Image</label>
                        <input type="file" wire:model="image" accept="image/*" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @if($currentImagePath)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $currentImagePath) }}" alt="Current product image" class="h-20 w-20 rounded object-cover">
                        </div>
                        @endif
                    </div>
                    <div>
                        <label class="block">Category</label>
                        <select wire:model="categoryId" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categoryId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">Status</label>
                        <select wire:model="isActive" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <hr class="my-6">

                <h4 class="font-bold mb-2">Variants</h4>
                <div class="space-y-4">
                    @foreach($variants as $index => $variant)
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-2 rounded-xl border border-slate-200 p-3 dark:border-slate-700" wire:key="variant-{{ $index }}">
                        <div class="md:col-span-2">
                            <label class="block text-sm">Label (e.g., 500ml, Large)</label>
                            <input type="text" wire:model="variants.{{ $index }}.label" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            @error('variants.'.$index.'.label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm">Retail Price</label>
                            <input type="number" step="0.01" wire:model="variants.{{ $index }}.retail_price" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            @error('variants.'.$index.'.retail_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm">Cost Price</label>
                            <input type="number" step="0.01" wire:model="variants.{{ $index }}.cost_price" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            @error('variants.'.$index.'.cost_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm">Initial Stock</label>
                            <input type="number" min="0" wire:model="variants.{{ $index }}.initial_stock" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            @error('variants.'.$index.'.initial_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-end">
                            <button type="button" wire:click="removeVariant({{ $index }})" class="text-red-500 hover:underline">Remove</button>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-sm">Barcode (Optional)</label>
                            <input type="text" wire:model="variants.{{ $index }}.barcode" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            @error('variants.'.$index.'.barcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" wire:click="addVariant" class="mt-2 text-sm text-blue-600 hover:underline">+ Add Another Variant</button>

                <div class="flex justify-end gap-4 mt-6">
                    @if($editingProductId)
                    <button type="button" wire:click="deleteProduct" onclick="confirm('Delete this product? This cannot be undone.') || event.stopImmediatePropagation()" class="mr-auto rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">Delete Product</button>
                    @endif
                    <button type="button" wire:click="closeModal" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Save Product</button>
                </div>
            </form>
        </div>
    </x-ui.modal-shell>
    @endif
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
