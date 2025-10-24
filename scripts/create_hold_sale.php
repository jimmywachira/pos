<?php
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
use App\Models\User;
use App\Models\Sale;

$variants = ProductVariant::limit(2)->get();
if ($variants->count() < 1) {
    echo "No product variants found\n";
    exit;
}

$branch = Branch::first();
$user = User::first();

$total = $variants->sum(fn($v) => $v->retail_price);

$sale = Sale::create([
    'invoice_no' => 'HOLD-' . now()->format('YmdHis'),
    'branch_id' => $branch?->id,
    'user_id' => $user?->id,
    'customer_id' => null,
    'total' => $total,
    'tax' => 0,
    'discount' => 0,
    'meta' => [],
    'paid' => 0,
    'payment_method' => 'cash',
    'status' => 'pending',
]);

foreach ($variants as $v) {
    $sale->items()->create([
        'product_variant_id' => $v->id,
        'quantity' => 1,
        'unit_price' => $v->retail_price,
        'line_total' => $v->retail_price,
    ]);
}

echo "Created held sale ID: {$sale->id}\n";
foreach ($sale->items as $it) {
    echo "Item: {$it->product_variant_id} x {$it->quantity} @ {$it->unit_price}\n";
}
