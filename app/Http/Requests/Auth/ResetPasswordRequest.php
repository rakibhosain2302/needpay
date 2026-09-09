<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login_type' => ['required', Rule::in(['email', 'phone'])],
            'identifier' => ['required', 'string'],
            'token' => ['required_if:login_type,email', 'string'],
            'otp' => ['required_if:login_type,phone', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
