<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        // Fetch categories to associate with products
        $categories = Category::pluck('id', 'name');

        $productsData = [
            'Food & Beverages' => [
                [
                    'product' => ['name' => 'Coca-Cola', 'sku' => 'BEV-COKE', 'description' => 'Carbonated soft drink'],
                    'variants' => [
                        ['label' => '300ml', 'barcode' => '600123450001', 'retail_price' => 45, 'cost_price' => 30, 'stock_qty' => [200, 300]],
                        ['label' => '500ml', 'barcode' => '600123450002', 'retail_price' => 65, 'cost_price' => 45, 'stock_qty' => [150, 250]],
                        ['label' => '2L', 'barcode' => '600123450003', 'retail_price' => 150, 'cost_price' => 110, 'stock_qty' => [80, 120]],
                    ],
                ],
                [
                    'product' => ['name' => 'Fresh Milk', 'sku' => 'DAIRY-MILK', 'description' => 'Pasteurized fresh milk'],
                    'variants' => [
                        ['label' => '500ml Pouch', 'barcode' => '600123450011', 'retail_price' => 70, 'cost_price' => 55, 'stock_qty' => [100, 150], 'expiry_days' => 7],
                        ['label' => '1L Carton', 'barcode' => '600123450012', 'retail_price' => 130, 'cost_price' => 100, 'stock_qty' => [70, 100], 'expiry_days' => 10],
                    ],
                ],
                [
                    'product' => ['name' => 'White Bread', 'sku' => 'BAKE-BREAD', 'description' => 'Sliced white bread'],
                    'variants' => [
                        ['label' => '400g Loaf', 'barcode' => '600123450021', 'retail_price' => 65, 'cost_price' => 50, 'stock_qty' => [100, 200]],
                    ],
                ],
                [
                    'product' => ['name' => 'Mineral Water', 'sku' => 'BEV-WATER', 'description' => 'Purified drinking water'],
                    'variants' => [
                        ['label' => '500ml Bottle', 'barcode' => '600123450031', 'retail_price' => 35, 'cost_price' => 20, 'stock_qty' => [300, 500]],
                        ['label' => '1L Bottle', 'barcode' => '600123450032', 'retail_price' => 50, 'cost_price' => 35, 'stock_qty' => [200, 400]],
                    ],
                ],
            ],
            'Snacks' => [
                [
                    'product' => ['name' => 'Potato Crisps', 'sku' => 'SNACK-CRISP', 'description' => 'Salted potato crisps'],
                    'variants' => [
                        ['label' => 'Salt & Vinegar 125g', 'barcode' => '600123450041', 'retail_price' => 120, 'cost_price' => 90, 'stock_qty' => [80, 150]],
                        ['label' => 'Cheese & Onion 125g', 'barcode' => '600123450042', 'retail_price' => 120, 'cost_price' => 90, 'stock_qty' => [80, 150]],
                    ],
                ],
                [
                    'product' => ['name' => 'Chocolate Bar', 'sku' => 'SNACK-CHOC', 'description' => 'Milk chocolate bar'],
                    'variants' => [
                        ['label' => '50g', 'barcode' => '600123450051', 'retail_price' => 100, 'cost_price' => 70, 'stock_qty' => [100, 200]],
                        ['label' => '100g', 'barcode' => '600123450052', 'retail_price' => 180, 'cost_price' => 140, 'stock_qty' => [50, 100]],
                    ],
                ],
            ],
            'Household' => [
                [
                    'product' => ['name' => 'Sunlight Soap', 'sku' => 'CLEAN-SOAP', 'description' => 'Multi-purpose soap bar'],
                    'variants' => [
                        ['label' => '200g Bar', 'barcode' => '600123450061', 'retail_price' => 30, 'cost_price' => 20, 'stock_qty' => [200, 300]],
                    ],
                ],
                [
                    'product' => ['name' => 'Dishwashing Liquid', 'sku' => 'CLEAN-DISH', 'description' => 'Lemon-scented dishwashing liquid'],
                    'variants' => [
                        ['label' => '750ml', 'barcode' => '600123450071', 'retail_price' => 150, 'cost_price' => 110, 'stock_qty' => [100, 150]],
                    ],
                ],
            ],
            'Personal Care' => [
                [
                    'product' => ['name' => 'Colgate Toothpaste', 'sku' => 'PC-TOOTHPASTE', 'description' => 'Herbal toothpaste'],
                    'variants' => [
                        ['label' => '100ml', 'barcode' => '600123450081', 'retail_price' => 120, 'cost_price' => 80, 'stock_qty' => [100, 180]],
                    ],
                ],
                [
                    'product' => ['name' => 'Nivea Lotion', 'sku' => 'PC-LOTION', 'description' => 'Body lotion for dry skin'],
                    'variants' => [
                        ['label' => '250ml', 'barcode' => '600123450091', 'retail_price' => 450, 'cost_price' => 350, 'stock_qty' => [50, 80]],
                        ['label' => '400ml', 'barcode' => '600123450092', 'retail_price' => 650, 'cost_price' => 500, 'stock_qty' => [40, 60]],
                    ],
                ],
            ],
        ];

        foreach ($productsData as $categoryName => $products) {
            foreach ($products as $productData) {
                // Use updateOrCreate to avoid duplicates on re-seeding
                $product = Product::updateOrCreate(
                    ['sku' => $productData['product']['sku']],
                    [
                        'name' => $productData['product']['name'],
                        'description' => $productData['product']['description'],
                        'category_id' => $categories[$categoryName] ?? null,
                        'is_active' => true,
                    ]
                );

                foreach ($productData['variants'] as $variantData) {
                    $variant = $product->variants()->updateOrCreate(
                        ['barcode' => $variantData['barcode']],
                        [
                            'label' => $variantData['label'],
                            'retail_price' => $variantData['retail_price'],
                            'cost_price' => $variantData['cost_price'],
                        ]
                    );

                    // Create stock for each variant in each branch
                    foreach ($branches as $branch) {
                        Stock::updateOrCreate(
                            [
                                'branch_id' => $branch->id,
                                'product_variant_id' => $variant->id,
                            ],
                            [
                                'quantity' => rand($variantData['stock_qty'][0], $variantData['stock_qty'][1]),
                                'expiry_date' => isset($variantData['expiry_days']) ? now()->addDays($variantData['expiry_days']) : null,
                            ]
                        );
                    }
                }
            }
        }
    }
}
