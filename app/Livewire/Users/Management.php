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

    public function updateRole(User $user, $newRole)
    {
        if ($user->id === auth()->id() && $newRole !== 'Admin') {
            session()->flash('error', 'You cannot remove your own Admin role.');
            $this->dispatch('role-change-reverted'); // Dispatch event to revert UI
            return;
        }
        $user->syncRoles($newRole);
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
};