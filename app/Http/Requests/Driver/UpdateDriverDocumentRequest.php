<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_number' => ['sometimes', 'nullable', 'string', 'max:100'],
            'file' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'issued_at' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:issued_at'],
        ];
    }
}
