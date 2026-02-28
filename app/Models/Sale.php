<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no', 'branch_id', 'user_id', 'customer_id',
        'total', 'tax', 'discount', 'paid', 'payment_method', 'status', 'meta',
        'etims_status', 'etims_receipt_no', 'etims_qr_code', 'etims_response',
        'etims_submitted_at', 'etims_last_checked_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'etims_response' => 'array',
        'etims_submitted_at' => 'datetime',
        'etims_last_checked_at' => 'datetime',
    ];

    public function getEtimsQrSrcAttribute(): ?string
    {
        $qrCode = $this->etims_qr_code;

        if (! $qrCode) {
            return null;
        }

        if (Str::startsWith($qrCode, ['http://', 'https://', 'data:image'])) {
            return $qrCode;
        }

        return 'data:image/png;base64,' . $qrCode;
    }

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
