<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ProductVariant;
use App\Models\Branch;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $cashiers = User::all();
        $customers = Customer::all();
        $variants = ProductVariant::with('product')->get();

        if ($branches->isEmpty() || $cashiers->isEmpty() || $variants->isEmpty()) {
            return;
        }

        $faker = \Faker\Factory::create();
        $now = now();

        $salesToCreate = 180;
        for ($i = 0; $i < $salesToCreate; $i++) {
            $branch = $branches->random();
            $cashier = $cashiers->random();
            $customer = $customers->isNotEmpty() && $faker->boolean(70) ? $customers->random() : null;
            $saleDate = $now->copy()->subDays($faker->numberBetween(0, 180))->setTime($faker->numberBetween(8, 21), $faker->numberBetween(0, 59));

            $itemsCount = $faker->numberBetween(1, 5);
            $items = [];
            $subtotal = 0;

            foreach ($variants->random($itemsCount) as $variant) {
                $quantity = $faker->numberBetween(1, 4);
                $unitPrice = $variant->retail_price;
                $lineTotal = $unitPrice * $quantity;

                $items[] = [
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];

                $subtotal += $lineTotal;
            }

            $discount = $faker->boolean(20) ? $faker->randomFloat(2, 0, min(150, $subtotal * 0.2)) : 0;
            $tax = ($subtotal - $discount) * 0.16;
            $total = $subtotal + $tax - $discount;

            $sale = Sale::create([
                'invoice_no' => 'INV-' . $saleDate->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'branch_id' => $branch->id,
                'user_id' => $cashier->id,
                'customer_id' => $customer?->id,
                'total' => $total,
                'tax' => $tax,
                'discount' => $discount,
                'paid' => $total,
                'payment_method' => $faker->randomElement(['cash', 'mpesa']),
                'status' => 'completed',
                'meta' => ['seeded' => true],
                'created_at' => $saleDate,
                'updated_at' => $saleDate,
            ]);

            foreach ($items as $item) {
                SaleItem::create(array_merge(['sale_id' => $sale->id], $item));
            }
        }
    }
}
