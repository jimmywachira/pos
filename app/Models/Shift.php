<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashDrawer(): HasOne
    {
        return $this->hasOne(CashDrawer::class);
    }
public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

}
