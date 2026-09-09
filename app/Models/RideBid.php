<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'ride_request_id', 'driver_id', 'vehicle_id', 'offered_amount',
    'estimated_arrival_minutes', 'message', 'status', 'expires_at',
    'parent_bid_id', 'submitted_at', 'accepted_at', 'rejected_at', 'withdrawn_at',
])]
class RideBid extends Model
{
    protected function casts(): array
    {
        return [
            'offered_amount' => 'decimal:2',
            'expires_at' => 'datetime',
            'submitted_at' => 'datetime',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
            'withdrawn_at' => 'datetime',
        ];
    }

    public function rideRequest(): BelongsTo
    {
        return $this->belongsTo(RideRequest::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function parentBid(): BelongsTo
    {
        return $this->belongsTo(RideBid::class, 'parent_bid_id');
    }

    public function counterBids(): HasMany
    {
        return $this->hasMany(RideBid::class, 'parent_bid_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BidHistory::class);
    }

    public function ride(): HasOne
    {
        return $this->hasOne(Ride::class, 'accepted_bid_id');
    }
}
