<div class="p-6 text-2xl rounded-lg shadow">
    <nav wire:navigate class="flex justify-evenly mb-6" x-data="{ open: false }">
        <a class="flex justify-evenly items-center font-bold text-blue-600 transition-colors {{ request()->routeIs('shifts.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="settings-outline"></ion-icon>
            <span>settings</span>
        </a>

        <a wire:navigate.hover href="{{ route('shifts.management') }}" class="flex justify-evenly items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('shifts.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
            <ion-icon class="text-3xl" name="people-outline"></ion-icon>
            <span>shifts</span>
        </a>

        @unless(auth()->user()?->hasRole('admin'))
            <a wire:navigate.hover href="{{ route('users.management') }}" class="flex justify-evenly items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('users.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
                <ion-icon class="text-3xl" name="lock-closed-outline"></ion-icon>
                <span>role</span>
            </a>
        @endunless  
    </nav>

    <div class="p-6 /50 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">User Management</h2>
            <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add User</button>
        </div>

        @if(session()->has('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <!-- Search and Filters -->
        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..." class="w-full border text-xl uppercase rounded p-2">
        </div>

        <!-- Users Table -->
        <div class=" shadow rounded-lg overflow-x-auto">
            <table class="w-full backdrop-blur-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-3 text-left cursor-pointer" wire:click="sortBy('name')">Name</th>
                        <th class="p-3 text-left cursor-pointer" wire:click="sortBy('email')">Email</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">
                            @foreach($user->roles as $role)
                            <span class="px-2 py-1 rounded-full bg-blue-200 text-blue-800">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="p-3 flex gap-2">
                            <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $user->id }})" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-3 text-center text-gray-500">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        <!-- Create/Edit Modal -->
        @if($showModal)
        <div class="fixed inset-0 backdrop-blur-lg flex items-center justify-center z-50">
            <div class="rounded-lg shadow-lg p-6 w-full max-w-lg">
                <h3 class="text-xl font-bold mb-4">{{ $editingUserId ? 'Edit User' : 'Create User' }}</h3>
                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block">Name</label>
                            <input type="text" wire:model="name" class="w-full border rounded text-xl uppercase p-2">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block">Email</label>
                            <input type="email" wire:model="email" class="w-full border rounded text-xl uppercase p-2">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block">Role</label>
                            <select wire:model="role" class="w-full border rounded p-2">
                                <option value="">Select a role</option>
                                @foreach($roles as $roleOption)
                                <option value="{{ $roleOption->name }}">{{ $roleOption->name }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block">Password</label>
                            <input type="password" wire:model="password" class="w-full border text-xl uppercase rounded p-2" placeholder="{{ $editingUserId ? 'Leave blank to keep current password' : '' }}">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" class="w-full border text-xl uppercase rounded p-2">
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" wire:click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save User</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class=" rounded-lg shadow-lg p-6 w-full max-w-md">
                <h3 class="text-xl font-bold mb-4">Confirm Deletion</h3>
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="$set('showDeleteModal', false)" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="button" wire:click="delete" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete User</button>
                </div>
            </div>
        </div>
        @endif
    </div>
