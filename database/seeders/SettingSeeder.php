<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('currency', 'KES');
        Setting::set('tax', 16);
        Setting::set('store_name', 'My POS Shop');
        Setting::set('receipt_footer', 'Thank you for shopping with us!');
    }
}
