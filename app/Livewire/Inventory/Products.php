<?php

namespace App\Livewire\Inventory;

use Livewire\Component;

class Products extends Component
{
    public function render()
    {
        return view('livewire.inventory.products')
            ->layout('layouts.pos');
    }
}
