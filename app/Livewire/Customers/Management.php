<?php

namespace App\Livewire\Customers;

use Livewire\Component;

class Management extends Component
{
    public function render()
    {
        return view('livewire.customers.management')
            ->layout('layouts.app');
    }
}
