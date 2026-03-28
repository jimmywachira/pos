<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 p-4 sm:p-6 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    <x-ui.section-card class="mb-4" bodyClass="p-4">
        <nav wire:navigate class="flex flex-wrap items-center gap-2">
            <a
                wire:navigate.hover
                href="{{ route('settings') }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('settings*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}"
            >
                <ion-icon name="settings-outline"></ion-icon>
                <span>Settings</span>
            </a>

            <a
                wire:navigate.hover
                href="{{ route('shifts.management') }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('shifts.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}"
            >
                <ion-icon name="people-outline"></ion-icon>
                <span>Shifts</span>
            </a>

            @if (auth()->user()?->hasRole('admin'))
                <a
                    wire:navigate.hover
                    href="{{ route('users.management') }}"
                    class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}"
                >
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <span>Users & Roles</span>
                </a>
            @endif
        </nav>
    </x-ui.section-card>

    <x-ui.section-card bodyClass="p-6">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">User Management</h2>
            <button wire:click="create" class="inline-flex items-center justify-center rounded-lg border border-blue-600 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50 hover:text-blue-700 dark:border-blue-400 dark:text-blue-300 dark:hover:bg-blue-500/10">
                Add User
            </button>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ session('success') }}</div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800 dark:bg-red-900/30 dark:text-red-300">{{ session('error') }}</div>
        @endif

        <div class="mb-4 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg dark:border-slate-700 dark:bg-slate-900/90">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..." class="w-full rounded-lg border border-slate-300 p-3 text-base shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400">
        </div>

        <x-ui.table-shell>
            <table class="w-full table-auto">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70">
                        <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('name')">Name</th>
                        <th class="cursor-pointer p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200" wire:click="sortBy('email')">Email</th>
                        <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Role</th>
                        <th class="p-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-slate-200 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/60">
                            <td class="p-3 text-slate-700 dark:text-slate-200">{{ $user->name }}</td>
                            <td class="p-3 text-slate-700 dark:text-slate-200">{{ $user->email }}</td>
                            <td class="p-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->roles as $role)
                                        <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="flex gap-3">
                                    <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:underline dark:text-blue-300">Edit</button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="text-red-600 hover:underline">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-3 text-center text-slate-500 dark:text-slate-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-shell>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        @if ($showModal)
            <x-ui.modal-shell maxWidth="max-w-lg" :title="$editingUserId ? 'Edit User' : 'Create User'">
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Name</label>
                        <input type="text" wire:model="name" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Email</label>
                        <input type="email" wire:model="email" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Role</label>
                        <select wire:model="role" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Select a role</option>
                            @foreach ($roles as $roleOption)
                                <option value="{{ $roleOption->name }}">{{ $roleOption->name }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Password</label>
                        <input type="password" wire:model="password" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="{{ $editingUserId ? 'Leave blank to keep current password' : '' }}">
                        @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 dark:text-slate-300">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button" wire:click="closeModal" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">Save User</button>
                    </div>
                </form>
            </x-ui.modal-shell>
        @endif

        @if ($showDeleteModal)
            <x-ui.modal-shell maxWidth="max-w-md" title="Confirm Deletion">
                <p class="text-slate-700 dark:text-slate-300">Are you sure you want to delete this user? This action cannot be undone.</p>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" wire:click="$set('showDeleteModal', false)" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-white transition hover:bg-red-700">Delete User</button>
                </div>
            </x-ui.modal-shell>
        @endif
    </x-ui.section-card>
</div>
