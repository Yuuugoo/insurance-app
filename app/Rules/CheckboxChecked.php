<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckboxChecked implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is 'on' or true, which indicates the checkbox is checked
        if ($value !== 'on' && $value !== true && $value !== 1) {
            $fail('The :attribute must be checked.');
        }
    }
}
