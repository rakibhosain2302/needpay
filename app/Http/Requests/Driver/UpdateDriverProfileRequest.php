<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $driverId = $this->user()->driver?->id;

        return [
            'date_of_birth' => ['sometimes', 'nullable', 'date', 'before:today'],
            'gender' => ['sometimes', 'nullable', Rule::in(['male', 'female', 'other'])],
            'address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'license_number' => ['sometimes', 'nullable', 'string', 'max:100', Rule::unique('drivers', 'license_number')->ignore($driverId)],
            'license_expiry_date' => ['sometimes', 'nullable', 'date', 'after:today'],
        ];
    }
}
