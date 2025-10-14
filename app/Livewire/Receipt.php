<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;

class Receipt extends Component
{
    public $sale;

    public function mount(Sale $sale)
    {
        $this->sale = $sale->load('items.productVariant.product', 'customer', 'branch', 'user');
    }

    public function render()
    {
        return view('livewire.receipt');
    }
}
