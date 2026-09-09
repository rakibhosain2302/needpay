<?php

namespace App\Http\Requests\Profile;

use App\Rules\BangladeshiPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['sometimes', 'nullable', 'string', new BangladeshiPhone, Rule::unique('users', 'phone')->ignore($userId)],
            'profile_photo' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ];
    }
}
