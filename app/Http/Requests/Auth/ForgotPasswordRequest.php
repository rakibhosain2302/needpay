<?php

namespace App\Http\Requests\Auth;

use App\Rules\BangladeshiPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login_type' => ['required', Rule::in(['email', 'phone'])],
            'identifier' => [
                'required', 'string',
                $this->input('login_type') === 'phone' ? new BangladeshiPhone : 'email',
            ],
        ];
    }
}
