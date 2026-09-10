<?php

namespace App\Models;

use App\Enums\DriverAvailabilityStatus;
use App\Enums\DriverVerificationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'date_of_birth', 'gender', 'address', 'license_number',
    'license_expiry_date', 'verification_status', 'is_online', 'availability_status',
    'current_latitude', 'current_longitude', 'last_location_at',
    'average_rating', 'rating_count', 'total_trips',
])]
class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'license_expiry_date' => 'date',
            'is_online' => 'boolean',
            'current_latitude' => 'decimal:7',
            'current_longitude' => 'decimal:7',
            'last_location_at' => 'datetime',
            'average_rating' => 'decimal:2',
            'verification_status' => DriverVerificationStatus::class,
            'availability_status' => DriverAvailabilityStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DriverDocument::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function rideBids(): HasMany
    {
        return $this->hasMany(RideBid::class);
    }

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }

    public function rideLocations(): HasMany
    {
        return $this->hasMany(RideLocation::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(DriverWithdrawal::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
