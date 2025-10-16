<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class General extends Component
{
    public $currency, $tax, $store_name, $receipt_footer;

    public function mount()
    {
        $this->currency = setting('currency', 'KES');
        $this->tax = setting('tax', 16);
        $this->store_name = setting('store_name', 'My POS Shop');
        $this->receipt_footer = setting('receipt_footer', 'Thank you for shopping!');
    }

    public function save()
    {
        Setting::set('currency', $this->currency);
        Setting::set('tax', $this->tax);
        Setting::set('store_name', $this->store_name);
        Setting::set('receipt_footer', $this->receipt_footer);

        session()->flash('success', 'Settings saved.');
    }

    public function render()
    {
        return view('livewire.settings.general')
            ->layout('layouts.pos');
    }
}
