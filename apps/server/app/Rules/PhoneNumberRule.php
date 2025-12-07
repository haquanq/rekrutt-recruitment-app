<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) > 15) {
            $fail("Phone number must not exceed 15 characters.");
            return;
        }

        if (!preg_match('/^[0-9-+()]+$/', $value)) {
            $fail("Phone number must contain only digits and special characters: +-().");
            return;
        }
    }
}
