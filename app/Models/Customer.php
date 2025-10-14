<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email', 'loyalty_points'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
