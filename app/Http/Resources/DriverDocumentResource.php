<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'document_type' => $this->document_type?->value,
            'document_number' => $this->document_number,
            'issued_at' => $this->issued_at,
            'expires_at' => $this->expires_at,
            'status' => $this->status?->value,
            'is_expired' => $this->isExpired(),
            'rejection_reason' => $this->rejection_reason,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
        ];
    }
}
