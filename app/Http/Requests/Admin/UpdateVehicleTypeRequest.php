<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleTypeId = $this->route('vehicleType')?->id;

        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'slug' => ['sometimes', 'string', 'max:100', Rule::unique('vehicle_types', 'slug')->ignore($vehicleTypeId)],
            'description' => ['sometimes', 'nullable', 'string'],
            'icon' => ['sometimes', 'nullable', 'string', 'max:255'],
            'minimum_fare' => ['sometimes', 'numeric', 'min:0'],
            'base_fare' => ['sometimes', 'numeric', 'min:0'],
            'per_km_rate' => ['sometimes', 'numeric', 'min:0'],
            'per_minute_rate' => ['sometimes', 'numeric', 'min:0'],
            'waiting_charge_per_minute' => ['sometimes', 'numeric', 'min:0'],
            'max_passengers' => ['sometimes', 'integer', 'min:1'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}
