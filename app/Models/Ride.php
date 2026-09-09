<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'ride_request_id', 'customer_id', 'driver_id', 'vehicle_id', 'accepted_bid_id',
    'pickup_address', 'pickup_latitude', 'pickup_longitude',
    'destination_address', 'destination_latitude', 'destination_longitude',
    'driver_arrived_at', 'trip_started_at', 'trip_completed_at',
    'actual_distance_km', 'actual_duration_minutes', 'final_fare',
    'waiting_charge', 'cancellation_fee', 'status',
    'cancelled_by', 'cancellation_reason', 'cancelled_at',
])]
class Ride extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'pickup_latitude' => 'decimal:7',
            'pickup_longitude' => 'decimal:7',
            'destination_latitude' => 'decimal:7',
            'destination_longitude' => 'decimal:7',
            'driver_arrived_at' => 'datetime',
            'trip_started_at' => 'datetime',
            'trip_completed_at' => 'datetime',
            'actual_distance_km' => 'decimal:2',
            'final_fare' => 'decimal:2',
            'waiting_charge' => 'decimal:2',
            'cancellation_fee' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function rideRequest(): BelongsTo
    {
        return $this->belongsTo(RideRequest::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function acceptedBid(): BelongsTo
    {
        return $this->belongsTo(RideBid::class, 'accepted_bid_id');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(RideLocation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
