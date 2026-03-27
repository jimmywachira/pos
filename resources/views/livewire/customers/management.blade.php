<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 p-4 sm:p-6 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    <div x-data  x-show="!$wire.showModal" >
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Customers</h2>
                <button wire:click="create" class="rounded-lg border border-blue-500 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-500 hover:text-white dark:border-blue-400 dark:text-blue-300">Add Customer</button>
            </div>

            @if(session()->has('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ session('success') }}</div>
            @endif

            <!-- Search and Filters -->
            <div class="mb-4 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg dark:border-slate-700 dark:bg-slate-900/90">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, or phone..." class="w-full rounded-lg border border-slate-300 p-2 text-base shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400">
            </div>
            <!-- Customers Table -->
            <x-ui.table-shell>
                <div class="hidden md:block">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70">
                                <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('name')">Name</th>
                                <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('email')">Email</th>
                                <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Phone</th>
                                <th class="p-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-200">Total Spent</th>
                                <th class="p-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-200">Points</th>
                                <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Last Purchase</th>
                                <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr class="border-b border-slate-200 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/60" wire:dblClick="edit({{ $customer->id }})" id="customer-{{ $customer->id }}-row">
                                <td class="p-3 text-slate-700 dark:text-slate-200">{{ $customer->name }}</td>
                                <td class="p-3 text-slate-700 dark:text-slate-200">{{ $customer->email }}</td>
                                <td class="p-3 text-slate-700 dark:text-slate-200">{{ $customer->phone ?? 'N/A' }}</td>
                                <td class="p-3 text-right text-slate-700 dark:text-slate-200">Ksh {{ number_format($customer->sales_sum_total ?? 0, 2) }}</td>
                                <td class="p-3 text-right text-slate-700 dark:text-slate-200">{{ number_format($customer->loyalty_points ?? 0, 0) }}</td>
                                <td class="p-3 text-slate-700 dark:text-slate-200">{{ $customer->sales_max_created_at ? \Carbon\Carbon::parse($customer->sales_max_created_at)->diffForHumans() : 'N/A' }}</td>
                                <td class="p-3 flex gap-2">
                                    <button wire:click="edit({{ $customer->id }})" class="text-blue-600 hover:underline dark:text-blue-300" id="edit-{{ $customer->id }}-btn">Edit</button>
                                    <button wire:click="confirmDelete({{ $customer->id }})" class="text-red-600 hover:underline" id="delete-{{ $customer->id }}-btn">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-3 text-center text-slate-500 dark:text-slate-400">No customers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="grid grid-cols-1 gap-4 md:hidden">
                    @foreach($customers as $customer)
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex justify-between items-center">
                                <div class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $customer->name }}</div>
                                <div class="text-sm text-slate-600 dark:text-slate-300">Ksh {{ number_format($customer->sales_sum_total ?? 0, 2) }}</div>
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-300">{{ $customer->email }}</div>
                            <div class="text-sm text-slate-600 dark:text-slate-300">{{ $customer->phone ?? 'N/A' }}</div>
                            <div class="text-sm text-slate-600 dark:text-slate-300">Points: {{ number_format($customer->loyalty_points ?? 0, 0) }}</div>
                            <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">Last purchase: {{ $customer->sales_max_created_at ? \Carbon\Carbon::parse($customer->sales_max_created_at)->diffForHumans() : 'N/A' }}</div>
                            <div class="flex gap-2 mt-3">
                                <button wire:click="edit({{ $customer->id }})" class="text-sm text-blue-600 hover:underline dark:text-blue-300">Edit</button>
                                <button wire:click="confirmDelete({{ $customer->id }})" class="text-red-600 hover:underline text-sm">Delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.table-shell>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <x-ui.modal-shell maxWidth="max-w-lg" :title="$editingCustomerId ? 'Edit Customer' : 'Create Customer'">
                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div>
                            <x-form.text-input label="Name" name="name" />
                            
                        </div>
                        <div>
                            <x-form.input label="Email" name="email" type="email" />
                        </div>
                        <div>
                            <x-form.text-input label="Phone" name="phone" class="text-blue-800" data-agile="true"/>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-700 dark:text-slate-300">Address</label>
                            <textarea wire:model="address" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></textarea>
                            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" wire:click="closeModal" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Save Customer</button>
                    </div>
                </form>
        </x-ui.modal-shell>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <x-ui.modal-shell maxWidth="max-w-md" title="Confirm Deletion">
                <p class="text-slate-700 dark:text-slate-300">Are you sure you want to delete this customer? This action cannot be undone.</p>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="$set('showDeleteModal', false)" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">Delete Customer</button>
                </div>
        </x-ui.modal-shell>
    @endif

</div>
