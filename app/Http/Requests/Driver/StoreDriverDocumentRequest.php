<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriverDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_type' => ['required', Rule::in(['driving_license', 'nid', 'vehicle_registration', 'insurance'])],
            'document_number' => ['nullable', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'issued_at' => ['nullable', 'date', 'before_or_equal:today'],
            'expires_at' => ['nullable', 'date', 'after:issued_at'],
        ];
    }
}
