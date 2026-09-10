<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_type_id' => ['required', Rule::exists('vehicle_types', 'id')],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1980', 'max:'.(now()->year + 1)],
            'color' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'registration_number')],
            'registration_expiry_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ];
    }
}
