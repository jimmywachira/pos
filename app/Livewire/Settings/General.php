<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class General extends Component
{
    use WithFileUploads;

    // Form properties
    public $store_name;
    public $currency;
    public $tax;
    public $receipt_footer;
    public $logo; // For new logo upload
    public $loyalty_earn_rate;
    public $loyalty_redeem_value;

    // Existing data properties
    public $existingLogo;

    protected function rules(): array
    {
        return [
            'store_name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'tax' => 'required|numeric|min:0|max:100',
            'receipt_footer' => 'nullable|string|max:500',
            'logo' => 'nullable|image|max:1024', // 1MB Max
            'loyalty_earn_rate' => 'required|numeric|min:0', // e.g., spend 100 to get 1 point
            'loyalty_redeem_value' => 'required|numeric|min:0', // e.g., 1 point = 1 Ksh
        ];
    }

    public function mount()
    {
        $this->store_name = Setting::get('store_name', config('app.name', 'My POS Shop'));
        $this->currency = Setting::get('currency', 'Ksh');
        $this->tax = Setting::get('tax', 16.00);
        $this->receipt_footer = Setting::get('receipt_footer', 'Thank you for your business!');
        $this->loyalty_earn_rate = Setting::get('loyalty_earn_rate', 100);
        $this->loyalty_redeem_value = Setting::get('loyalty_redeem_value', 1);
        $this->existingLogo = Setting::get('store_logo');
    }

    public function save()
    {
        $validatedData = $this->validate();

        if (isset($validatedData['logo'])) {
            $logoPath = $this->logo->store('logos', 'public');
            Setting::set('store_logo', $logoPath);
            $this->existingLogo = $logoPath;
            $this->reset('logo');
        }

        // Use a loop for cleaner code
        $settingsToUpdate = ['store_name', 'currency', 'tax', 'receipt_footer', 'loyalty_earn_rate', 'loyalty_redeem_value'];
        foreach ($settingsToUpdate as $key) {
            Setting::set($key, $this->$key);
        }

        session()->flash('success', 'Settings saved.');
    }

    public function render()
    {
        return view('livewire.settings.general')->layout('layouts.app');
    }
}
