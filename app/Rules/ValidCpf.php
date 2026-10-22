<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^\d{11}$/', $value)) {
            $fail('O campo :attribute deve conter um CPF válido.');

            return;
        }

        if (preg_match('/^(\d)\1{10}$/', $value)) {
            $fail('O campo :attribute deve conter um CPF válido.');

            return;
        }

        for ($digit = 9; $digit < 11; $digit++) {
            $sum = 0;

            for ($index = 0; $index < $digit; $index++) {
                $sum += (int) $value[$index] * ($digit + 1 - $index);
            }

            $remainder = ($sum * 10) % 11;
            $expected = $remainder === 10 ? 0 : $remainder;

            if ((int) $value[$digit] !== $expected) {
                $fail('O campo :attribute deve conter um CPF válido.');

                return;
            }
        }
    }
}
