<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'branch_id',
        'user_id',
        'transaction_type',
        'direction',
        'quantity',
        'reference_type',
        'reference_id',
        'notes',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
