<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile_photo' => $this->profile_photo,
            'account_type' => $this->account_type?->value,
            'status' => $this->status?->value,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'last_login_at' => $this->last_login_at,
            'driver' => $this->whenLoaded('driver', fn () => new DriverResource($this->driver)),
            'roles' => $this->whenLoaded('roles', fn () => RoleResource::collection($this->roles)),
            'created_at' => $this->created_at,
        ];
    }
}
