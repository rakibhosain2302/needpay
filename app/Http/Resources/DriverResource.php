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
            'verification_status' => $this->verification_status,
            'is_online' => $this->is_online,
            'availability_status' => $this->availability_status,
            'average_rating' => $this->average_rating,
            'rating_count' => $this->rating_count,
            'total_trips' => $this->total_trips,
        ];
    }
}
