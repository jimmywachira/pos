<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * Get the product that owns the variant.
     */

    protected $fillable = [
        'product_id', 'label', 'barcode', 'retail_price', 'cost_price'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
