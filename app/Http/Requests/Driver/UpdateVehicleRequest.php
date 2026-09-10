<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle')?->id;

        return [
            'vehicle_type_id' => ['sometimes', Rule::exists('vehicle_types', 'id')],
            'brand' => ['sometimes', 'string', 'max:100'],
            'model' => ['sometimes', 'string', 'max:100'],
            'year' => ['sometimes', 'integer', 'min:1980', 'max:'.(now()->year + 1)],
            'color' => ['sometimes', 'nullable', 'string', 'max:50'],
            'registration_number' => ['sometimes', 'string', 'max:50', Rule::unique('vehicles', 'registration_number')->ignore($vehicleId)],
            'registration_expiry_date' => ['sometimes', 'nullable', 'date'],
            'photo' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ];
    }
}
