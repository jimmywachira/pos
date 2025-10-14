<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'name' => 'John Doe',
            'phone' => '0711000000',
            'email' => 'john@example.com',
            'loyalty_points' => 20,
        ]);

        Customer::create([
            'name' => 'Jane Smith',
            'phone' => '0722000000',
            'email' => 'jane@example.com',
            'loyalty_points' => 50,
        ]);

        Customer::create([
            'name' => 'Walk-in Customer',
            'phone' => null,
            'email' => null,
            'loyalty_points' => 0,
        ]);
    }
}
