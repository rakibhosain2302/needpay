<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'driver_id', 'vehicle_type_id', 'brand', 'model', 'year', 'color',
    'registration_number', 'registration_expiry_date', 'photo', 'status',
])]
class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'registration_expiry_date' => 'date',
            'status' => VehicleStatus::class,
        ];
    }

    public function isRegistrationValid(): bool
    {
        return $this->registration_expiry_date === null || ! $this->registration_expiry_date->isPast();
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function rideBids(): HasMany
    {
        return $this->hasMany(RideBid::class);
    }

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }
}
