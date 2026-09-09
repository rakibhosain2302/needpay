<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'password' => ['required', 'string'],
        ];
    }
}
