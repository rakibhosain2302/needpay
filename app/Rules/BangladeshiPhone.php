<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BangladeshiPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^(?:\+?880|0)1[3-9]\d{8}$/', $value)) {
            $fail('The :attribute must be a valid Bangladeshi phone number.');
        }
    }
}
