<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PolicyNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // The regular expression for the format MC-AAP-DV-24-0000108-00
        $regex = '/^[A-Z]{2}-[A-Z]{3}-[A-Z]{2}-\d{2}-\d{7}-\d{2}$/';
        
        if (!preg_match($regex, $value)) {
            $fail('The :attribute does not match the expected format.');
        }
    }
}
