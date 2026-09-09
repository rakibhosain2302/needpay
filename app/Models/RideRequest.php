<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'customer_id', 'pickup_address', 'pickup_latitude', 'pickup_longitude',
    'destination_address', 'destination_latitude', 'destination_longitude',
    'distance_km', 'estimated_duration_minutes', 'vehicle_type_id', 'passenger_count',
    'customer_offered_fare', 'special_instruction', 'status', 'bid_started_at',
    'bid_expires_at', 'selected_bid_id', 'selected_driver_id',
    'cancelled_by', 'cancellation_reason', 'cancelled_at',
])]
class RideRequest extends Model
{
    protected function casts(): array
    {
        return [
            'pickup_latitude' => 'decimal:7',
            'pickup_longitude' => 'decimal:7',
            'destination_latitude' => 'decimal:7',
            'destination_longitude' => 'decimal:7',
            'distance_km' => 'decimal:2',
            'customer_offered_fare' => 'decimal:2',
            'bid_started_at' => 'datetime',
            'bid_expires_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function selectedDriver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'selected_driver_id');
    }

    public function selectedBid(): BelongsTo
    {
        return $this->belongsTo(RideBid::class, 'selected_bid_id');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(RideBid::class);
    }

    public function ride(): HasOne
    {
        return $this->hasOne(Ride::class);
    }
}
