<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ProductVariant;
use App\Models\Branch;
use App\Models\User;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::firstOrCreate(
            ['name' => 'Main Branch'],
            ['code' => 'MAIN', 'address' => 'Main Branch Address', 'phone' => '0000000000']
        );
        $cashier = User::firstOrCreate(['email' => 'cashier@example.com'], ['name' => 'Cashier', 'password' => bcrypt('password')]);
        $customer = \App\Models\Customer::firstOrCreate(['email' => 'walkin@example.com'], ['name' => 'Walk-in Customer', 'phone' => '0000000000']);
        $variant = ProductVariant::first(); // Assumes ProductSeeder runs and creates variants

        $sale = Sale::create([
            'invoice_no' => 'INV0001',
            'branch_id' => $branch->id,
            'user_id' => $cashier->id,
            'customer_id' => $customer->id,
            'total' => 100,
            'tax' => 16,
            'discount' => 0,
            'paid' => 116,
            'payment_method' => 'cash',
            'meta' => ['note' => 'seed sale'],
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'unit_price' => 50,
            'line_total' => 100,
        ]);
    }
}
