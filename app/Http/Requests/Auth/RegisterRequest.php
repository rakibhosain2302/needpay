<?php

namespace App\Http\Requests\Auth;

use App\Rules\BangladeshiPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'account_type' => ['required', Rule::in(['customer', 'driver'])],
            'email' => [
                'nullable', 'required_without:phone', 'email', 'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone' => [
                'nullable', 'required_without:email', 'string', new BangladeshiPhone,
                Rule::unique('users', 'phone'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => 'Either an email or a phone number is required.',
            'phone.required_without' => 'Either an email or a phone number is required.',
            'account_type.in' => 'Account type must be either customer or driver.',
        ];
    }
}
