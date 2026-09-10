<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'minimum_fare' => $this->minimum_fare,
            'base_fare' => $this->base_fare,
            'per_km_rate' => $this->per_km_rate,
            'per_minute_rate' => $this->per_minute_rate,
            'waiting_charge_per_minute' => $this->waiting_charge_per_minute,
            'max_passengers' => $this->max_passengers,
            'status' => $this->status?->value,
        ];
    }
}
