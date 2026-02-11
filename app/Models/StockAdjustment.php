<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'product_variant_id',
        'branch_id',
        'user_id',
        'type',
        'quantity',
        'reason',
        'unit_cost',
        'total_cost',
    ];
}
