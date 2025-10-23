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
        Setting::set('loyalty_earn_rate', 100); // Spend 100 to get 1 point
        Setting::set('loyalty_redeem_value', 1); // 1 point is worth 1 Ksh
    }
}
