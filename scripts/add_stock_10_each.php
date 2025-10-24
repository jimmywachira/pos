<?php
// Add 10 units to stock for each product variant for the first branch
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    echo "Cannot find autoload.php at $autoload\n";
    exit(1);
}
require $autoload;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductVariant;
use App\Models\Branch;
use App\Models\Stock;

$branch = Branch::first();
if (!$branch) {
    echo "No branch found. Create a branch first.\n";
    exit(1);
}

$variants = ProductVariant::all();
if ($variants->isEmpty()) {
    echo "No product variants found.\n";
    exit(1);
}

echo "Using branch: {$branch->id} - {$branch->name}\n";

foreach ($variants as $variant) {
    $stock = Stock::firstOrNew([
        'branch_id' => $branch->id,
        'product_variant_id' => $variant->id,
    ]);

    $original = $stock->quantity ?? 0;
    $stock->quantity = $original + 10;
    $stock->save();

    echo "Variant {$variant->id} ({$variant->label}) : $original -> {$stock->quantity}\n";
}

echo "Done. Added 10 units for " . $variants->count() . " variants on branch {$branch->id}.\n";
