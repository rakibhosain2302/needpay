<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', Rule::unique('vehicle_types', 'slug')],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'minimum_fare' => ['required', 'numeric', 'min:0'],
            'base_fare' => ['required', 'numeric', 'min:0'],
            'per_km_rate' => ['required', 'numeric', 'min:0'],
            'per_minute_rate' => ['required', 'numeric', 'min:0'],
            'waiting_charge_per_minute' => ['required', 'numeric', 'min:0'],
            'max_passengers' => ['required', 'integer', 'min:1'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}
