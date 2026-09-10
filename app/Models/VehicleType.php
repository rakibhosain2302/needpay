<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name', 'slug', 'description', 'icon', 'minimum_fare', 'base_fare',
    'per_km_rate', 'per_minute_rate', 'waiting_charge_per_minute', 'max_passengers', 'status',
])]
class VehicleType extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'minimum_fare' => 'decimal:2',
            'base_fare' => 'decimal:2',
            'per_km_rate' => 'decimal:2',
            'per_minute_rate' => 'decimal:2',
            'waiting_charge_per_minute' => 'decimal:2',
            'status' => ActiveStatus::class,
        ];
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function rideRequests(): HasMany
    {
        return $this->hasMany(RideRequest::class);
    }

    public function promoCodes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }

    public function commissionRules(): HasMany
    {
        return $this->hasMany(CommissionRule::class);
    }
}
