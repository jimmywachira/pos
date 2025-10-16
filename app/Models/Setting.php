<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class SettingsPage extends Component
{
    public $currency;
    public $tax;
    public $store_name;
    public $receipt_footer;

    public function mount()
    {
        $this->currency = setting('currency', 'KES');
        $this->tax = setting('tax', 16);
        $this->store_name = setting('store_name', 'My POS Shop');
        $this->receipt_footer = setting('receipt_footer', 'Thank you for shopping with us!');
    }

    public function save()
    {
        Setting::set('currency', $this->currency);
        Setting::set('tax', $this->tax);
        Setting::set('store_name', $this->store_name);
        Setting::set('receipt_footer', $this->receipt_footer);

        $this->dispatchBrowserEvent('toast', [
            'type' => 'success',
            'message' => 'Settings updated successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.settings-page');
    }
}
