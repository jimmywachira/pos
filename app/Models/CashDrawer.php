<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashDrawer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}

