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

use Illuminate\Support\Facades\DB;
$rows = DB::select('select id, invoice_no, status from sales where invoice_no like "HOLD-%" order by id desc limit 5');
if (empty($rows)) { echo "No held sales found\n"; exit; }
foreach($rows as $r) {
    echo "Sale {$r->id} - {$r->invoice_no} ({$r->status})\n";
    $items = DB::select('select product_variant_id, quantity, unit_price from sale_items where sale_id = ?', [$r->id]);
    foreach($items as $it) {
        echo "  Item: {$it->product_variant_id} x {$it->quantity} @ {$it->unit_price}\n";
    }
}
