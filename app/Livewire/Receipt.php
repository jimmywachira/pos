<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Sale, Setting};

class Receipt extends Component
{
    public $sale;
    public $storeName;
    public $storeLogo;
    public $currency;
    public $receiptFooter;

    public function mount(Sale $sale)
    {
        $this->sale = $sale->load('items.productVariant.product', 'customer', 'branch', 'user');

        // Load settings for the receipt
        $this->storeName = Setting::get('store_name', config('app.name'));
        $this->storeLogo = Setting::get('store_logo');
        $this->currency = Setting::get('currency', 'Ksh');
        $this->receiptFooter = Setting::get('receipt_footer', 'Thank you for your business!');
    }

    public function render()
    {
        return view('livewire.receipt')// Use a dedicated layout for printing
            ->layout('layouts.receipt'); // Use a dedicated layout for printing
    }
}
