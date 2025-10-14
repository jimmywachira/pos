<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Branch;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        $products = [
            [
                'product' => ['sku' => 'COKE500', 'name' => 'Coca Cola 500ml', 'description' => 'Refreshing soft drink', 'retail_price' => 50, 'cost_price' => 35, 'is_active' => true],
                'variant' => ['barcode' => '1234567890123', 'label' => '500ml Bottle', 'retail_price' => 50, 'cost_price' => 35],
                'stock' => ['quantity' => 100],
            ],
            [
                'product' => ['sku' => 'SOAP001', 'name' => 'Sunlight Soap', 'description' => 'Multi-purpose soap bar', 'retail_price' => 30, 'cost_price' => 20, 'is_active' => true],
                'variant' => ['barcode' => '9876543210987', 'label' => '200g Bar', 'retail_price' => 30, 'cost_price' => 20],
                'stock' => ['quantity' => 200],
            ],
            [
                'product' => ['sku' => 'BREAD01', 'name' => 'White Bread', 'description' => 'Sliced white bread', 'retail_price' => 65, 'cost_price' => 50, 'is_active' => true],
                'variant' => ['barcode' => '1122334455667', 'label' => '400g Loaf', 'retail_price' => 65, 'cost_price' => 50],
                'stock' => ['quantity' => 150],
            ],
            [
                'product' => ['sku' => 'MILK01', 'name' => 'Fresh Milk', 'description' => 'Pasteurized fresh milk', 'retail_price' => 70, 'cost_price' => 55, 'is_active' => true],
                'variant' => ['barcode' => '2233445566778', 'label' => '500ml Pouch', 'retail_price' => 70, 'cost_price' => 55],
                'stock' => ['quantity' => 80, 'expiry_date' => now()->addDays(7)],
            ],
            [
                'product' => ['sku' => 'CHIPS01', 'name' => 'Potato Crisps', 'description' => 'Salted potato crisps', 'retail_price' => 100, 'cost_price' => 75, 'is_active' => true],
                'variant' => ['barcode' => '3344556677889', 'label' => '150g Bag', 'retail_price' => 100, 'cost_price' => 75],
                'stock' => ['quantity' => 300],
            ],
            [
                'product' => ['sku' => 'WATER01', 'name' => 'Mineral Water', 'description' => 'Purified drinking water', 'retail_price' => 40, 'cost_price' => 25, 'is_active' => true],
                'variant' => ['barcode' => '4455667788990', 'label' => '1L Bottle', 'retail_price' => 40, 'cost_price' => 25],
                'stock' => ['quantity' => 250],
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData['product']);

            $variant = ProductVariant::create(
                array_merge($productData['variant'], ['product_id' => $product->id])
            );

            foreach ($branches as $branch) {
                Stock::create(
                    array_merge(
                        $productData['stock'],
                        [
                            'branch_id' => $branch->id,
                            'product_variant_id' => $variant->id,
                        ]
                    )
                );
            }
        }
    }
}
