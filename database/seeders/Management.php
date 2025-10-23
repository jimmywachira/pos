<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Management extends Component
{
    use WithPagination;

    // Table properties
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Modal properties
    public $showModal = false;
    public $editingUserId;
    public $showDeleteModal = false;
    public $deletingUserId;

    // Form fields
    public $name, $email, $password, $password_confirmation, $role;

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'role' => 'required|exists:roles,name',
        ];

        // Password is required only when creating a new user
        if (!$this->editingUserId) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } elseif (!empty($this->password)) {
            // Or if the password field is filled for an existing user
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(User $user)
    {
        $this->resetForm();
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name;
        $this->showModal = true;
    }

    public function save()
    {
        $validatedData = $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $userData['password'] = $this->password;
        }

        $user = User::updateOrCreate(['id' => $this->editingUserId], $userData);
        $user->syncRoles($this->role);

        session()->flash('success', $this->editingUserId ? 'User updated successfully.' : 'User created successfully.');
        $this->closeModal();
    }

    public function confirmDelete($userId)
    {
        $this->deletingUserId = $userId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        User::find($this->deletingUserId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'User deleted successfully.');
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.users.management', [
            'users' => $users,
            'roles' => Role::all(),
        ])->layout('layouts.app');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role', 'editingUserId']);
        $this->resetErrorBag();
    }
}

// ```

// ### 2. Create the View

// Now, open the view file at `resources/views/livewire/users/management.blade.php` and add the following code for the UI.

// ```diff
// --- /dev/null
// +++ b/c:\xampp\htdocs\code\pos\resources\views\livewire\users\management.blade.php
// @@ -0,0 +1,146 @@
// +<div class="p-6">
// +    <div class="flex justify-between items-center mb-4">
// +        <h2 class="text-2xl font-bold">User Management</h2>
// +        <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add User</button>
// +    </div>
// +
// +    @if(session()->has('success'))
// +        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
// +    @endif
// +
// +    <!-- Search and Filters -->
// +    <div class="mb-4">
// +        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..." class="w-full border rounded p-2">
// +    </div>
// +
// +    <!-- Users Table -->
// +    <div class="bg-white shadow rounded-lg overflow-x-auto">
// +        <table class="w-full">
// +            <thead>
// +                <tr class="border-b bg-gray-50">
// +                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('name')">Name</th>
// +                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('email')">Email</th>
// +                    <th class="p-3 text-left">Role</th>
// +                    <th class="p-3 text-left">Actions</th>
// +                </tr>
// +            </thead>
// +            <tbody>
// +                @forelse($users as $user)
// +                    <tr class="border-b hover:bg-gray-50">
// +                        <td class="p-3">{{ $user->name }}</td>
// +                        <td class="p-3">{{ $user->email }}</td>
// +                        <td class="p-3">
// +                            @foreach($user->roles as $role)
// +                                <span class="px-2 py-1 text-xs rounded-full bg-blue-200 text-blue-800">{{ $role->name }}</span>
// +                            @endforeach
// +                        </td>
// +                        <td class="p-3 flex gap-2">
// +                            <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:underline">Edit</button>
// +                            <button wire:click="confirmDelete({{ $user->id }})" class="text-red-600 hover:underline">Delete</button>
// +                        </td>
// +                    </tr>
// +                @empty
// +                    <tr>
// +                        <td colspan="4" class="p-3 text-center text-gray-500">No users found.</td>
// +                    </tr>
// +                @endforelse
// +            </tbody>
// +        </table>
// +    </div>
// +
// +    <div class="mt-4">
// +        {{ $users->links() }}
// +    </div>
// +
// +    <!-- Create/Edit Modal -->
// +    @if($showModal)
// +        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
// +            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
// +                <h3 class="text-xl font-bold mb-4">{{ $editingUserId ? 'Edit User' : 'Create User' }}</h3>
// +                <form wire:submit.prevent="save">
// +                    <div class="space-y-4">
// +                        <div>
// +                            <label class="block">Name</label>
// +                            <input type="text" wire:model="name" class="w-full border rounded p-2">
// +                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
// +                        </div>
// +                        <div>
// +                            <label class="block">Email</label>
// +                            <input type="email" wire:model="email" class="w-full border rounded p-2">
// +                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
// +                        </div>
// +                        <div>
// +                            <label class="block">Role</label>
// +                            <select wire:model="role" class="w-full border rounded p-2">
// +                                <option value="">Select a role</option>
// +                                @foreach($roles as $roleOption)
// +                                    <option value="{{ $roleOption->name }}">{{ $roleOption->name }}</option>
// +                                @endforeach
// +                            </select>
// +                            @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
// +                        </div>
// +                        <div>
// +                            <label class="block">Password</label>
// +                            <input type="password" wire:model="password" class="w-full border rounded p-2" placeholder="{{ $editingUserId ? 'Leave blank to keep current password' : '' }}">
// +                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
// +                        </div>
// +                        <div>
// +                            <label class="block">Confirm Password</label>
// +                            <input type="password" wire:model="password_confirmation" class="w-full border rounded p-2">
// +                        </div>
// +                    </div>
// +
// +                    <div class="flex justify-end gap-4 mt-6">
// +                        <button type="button" wire:click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
// +                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save User</button>
// +                    </div>
// +                </form>
// +            </div>
// +        </div>
// +    @endif
// +
// +    <!-- Delete Confirmation Modal -->
// +    @if($showDeleteModal)
// +        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
// +            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
// +                <h3 class="text-xl font-bold mb-4">Confirm Deletion</h3>
// +                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
// +                
// +                <div class="flex justify-end gap-4 mt-6">
// +                    <button type="button" wire:click="$set('showDeleteModal', false)" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
// +                    <button type="button" wire:click="delete" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete User</button>
// +                </div>
// +            </div>
// +        </div>
// +    @endif
// +</div>
// ```

// ### 3. Add the Route and Navigation Link

// Finally, let's add the route and a link in the main navigation so authorized users can access this new page.

// **A. Add the Route**

// Open `routes/web.php` and add the new route, protected by the `users.manage` permission.

// ```diff
// --- a/c:\xampp\htdocs\code\pos\routes\web.php
// +++ b/c:\xampp\htdocs\code\pos\routes\web.php
// @@ -5,6 +5,7 @@
//  use App\Livewire\Inventory\Products;
//  use App\Livewire\Inventory\Batches;
//  use App\Livewire\Reports\Sales;
// +use App\Livewire\Users\Management as UserManagement;
//  use App\Livewire\Customers\Management;
//  use App\Livewire\Settings\General;
 
// @@ -15,6 +16,7 @@
//      Route::get('/inventory/batches', Batches::class)->name('inventory.batches')->middleware('can:inventory.view-batches');
//      Route::get('/reports/sales', Sales::class)->name('reports.sales')->middleware('can:reports.view-sales');
//      Route::get('/customers', Management::class)->name('customers.management')->middleware('can:customers.view');
// +    Route::get('/users', UserManagement::class)->name('users.management')->middleware('can:users.manage');
//  });
 
//  Route::get('/dashboard', function () {

// ```

// **B. Add the Navigation Link**

// Open `resources/views/layouts/app.blade.php` and add a link to the new page, wrapped in a `@can` directive.

// ```diff
// --- a/c:\xampp\htdocs\code\pos\resources\views\layouts\app.blade.php
// +++ b/c:\xampp\htdocs\code\pos\resources\views\layouts\app.blade.php
// @@ -43,6 +43,12 @@
//                  <ion-icon class="text-3xl" name="settings-outline"></ion-icon>
//                  <span>Settings</span>
//              </a>
// +            @can('users.manage')
// +            <a wire:navigate.hover href="{{ route('users.management') }}" class="flex flex-col items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('users.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
// +                <ion-icon class="text-3xl" name="people-circle-outline"></ion-icon>
// +                <span>Users</span>
// +            </a>
// +            @endcan
//          </div>
 
//          {{-- Auth Section --}}

// ```

// You now have a fully functional and secure page for managing users and their roles, seamlessly integrated into your application!

// <!--
// [PROMPT_SUGGESTION]Help me start building the shift management (clock in/out) feature.[/PROMPT_SUGGESTION]
// [PROMPT_SUGGESTION]How can I implement audit logs to track user actions?[/PROMPT_SUGGESTION]
// -->
