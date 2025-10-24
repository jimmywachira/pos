<div class="p-6 ">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Customers</h2>
        <button wire:click="create" class="border-2 uppercase border-blue-500 text-blue-500 px-4 py-2 hover:bg-blue-500 hover:text-white">Add Customer</button>
    </div>

    @if(session()->has('success'))
    <div class="border-2 border-green-100 text-green-800 p-3 mb-4">{{ session('success') }}</div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-4 ">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, or phone..." class="w-full text-3xl uppercase border  p-2">
    </div>

    <!-- Customers Table -->
    <div class=" shadow rounded-b-lg overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b shadow-lg bg-gray-400/50">
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('name')">Name</th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('email')">Email</th>
                    <th class="p-3 text-left">Phone</th>
                    <th class="p-3 text-right">Total Spent</th>
                    <th class="p-3 text-left">Last Purchase</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $customer->name }}</td>
                    <td class="p-3">{{ $customer->email }}</td>
                    <td class="p-3">{{ $customer->phone ?? 'N/A' }}</td>
                    <td class="p-3 text-right">Ksh {{ number_format($customer->sales_sum_total ?? 0, 2) }}</td>
                    <td class="p-3">{{ $customer->sales_max_created_at ? \Carbon\Carbon::parse($customer->sales_max_created_at)->diffForHumans() : 'N/A' }}</td>
                    <td class="p-3 flex gap-2">
                        <button wire:click="edit({{ $customer->id }})" class="text-blue-600 hover:underline">Edit</button>
                        <button wire:click="confirmDelete({{ $customer->id }})" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-3 text-center">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50">
        <div class=" rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h3 class="text-xl font-bold mb-4">{{ $editingCustomerId ? 'Edit Customer' : 'Create Customer' }}</h3>
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="block">Name</label>
                        <input type="text" wire:model="name" class="w-full border rounded p-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">Email</label>
                        <input type="email" wire:model="email" class="w-full border rounded p-2">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">Phone</label>
                        <input type="text" wire:model="phone" class="w-full border rounded p-2">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block">Address</label>
                        <textarea wire:model="address" class="w-full border rounded p-2"></textarea>
                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50">
        <div class=" rounded-lg shadow-lg p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Confirm Deletion</h3>
            <p>Are you sure you want to delete this customer? This action cannot be undone.</p>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" wire:click="$set('showDeleteModal', false)" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="button" wire:click="delete" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Customer</button>
            </div>
        </div>
    </div>
    @endif
</div>
