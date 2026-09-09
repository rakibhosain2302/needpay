<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'driver_id', 'wallet_id', 'amount', 'withdrawal_method', 'account_info',
    'status', 'processed_by', 'processed_at', 'rejection_reason',
])]
class DriverWithdrawal extends Model
{
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'account_info' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
