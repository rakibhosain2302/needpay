<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'ride_bid_id', 'actor_type', 'actor_id', 'action',
    'old_amount', 'new_amount', 'old_status', 'new_status', 'metadata',
])]
class BidHistory extends Model
{
    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'old_amount' => 'decimal:2',
            'new_amount' => 'decimal:2',
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function rideBid(): BelongsTo
    {
        return $this->belongsTo(RideBid::class);
    }
}
