<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'address' => $this->address,
            'license_number' => $this->license_number,
            'license_expiry_date' => $this->license_expiry_date,
            'verification_status' => $this->verification_status?->value,
            'is_online' => $this->is_online,
            'availability_status' => $this->availability_status?->value,
            'average_rating' => $this->average_rating,
            'rating_count' => $this->rating_count,
            'total_trips' => $this->total_trips,
            'documents' => DriverDocumentResource::collection($this->whenLoaded('documents')),
            'vehicles' => VehicleResource::collection($this->whenLoaded('vehicles')),
        ];
    }
}
