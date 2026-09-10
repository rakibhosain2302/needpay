<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'vehicle_type' => new VehicleTypeResource($this->whenLoaded('vehicleType')),
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'registration_number' => $this->registration_number,
            'registration_expiry_date' => $this->registration_expiry_date,
            'is_registration_valid' => $this->isRegistrationValid(),
            'photo_url' => $this->photo ? Storage::disk('public')->url($this->photo) : null,
            'status' => $this->status?->value,
            'created_at' => $this->created_at,
        ];
    }
}
