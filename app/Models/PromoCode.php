<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 'type', 'value', 'minimum_fare', 'maximum_discount', 'usage_limit',
    'per_user_limit', 'start_at', 'end_at', 'vehicle_type_id', 'zone_id', 'status',
])]
class PromoCode extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_fare' => 'decimal:2',
            'maximum_discount' => 'decimal:2',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PromoUsage::class);
    }
}
