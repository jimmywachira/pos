<?php

namespace App\Livewire\Inventory;

use Livewire\Component;

class Batches extends Component
{
    public function render()
    {
        return view('livewire.inventory.batches')
            ->layout('layouts.app');
    }
}
