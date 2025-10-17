<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

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
    public $editingCustomerId;
    public $showDeleteModal = false;
    public $deletingCustomerId;

    // Form fields
    public $name, $email, $phone, $address;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $this->editingCustomerId,
            'phone' => 'nullable|string|max:20|unique:customers,phone,' . $this->editingCustomerId,
            'address' => 'nullable|string|max:500',
        ];
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

    public function edit(Customer $customer)
    {
        $this->resetForm();
        $this->editingCustomerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Customer::updateOrCreate(
            ['id' => $this->editingCustomerId],
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]
        );

        session()->flash('success', $this->editingCustomerId ? 'Customer updated successfully.' : 'Customer created successfully.');
        $this->closeModal();
    }

    public function confirmDelete($customerId)
    {
        $this->deletingCustomerId = $customerId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Customer::find($this->deletingCustomerId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'Customer deleted successfully.');
    }

    public function render()
    {
        $customers = Customer::withSum('sales', 'total')
            ->withMax('sales', 'created_at')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.customers.management', ['customers' => $customers])->layout('layouts.app');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'address', 'editingCustomerId']);
        $this->resetErrorBag();
    }
}
