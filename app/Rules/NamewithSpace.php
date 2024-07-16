<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NamewithSpace implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Use regular expression to check if the string contains only alphabetic letters and spaces
        return preg_match('/^[a-zA-Z\s.\]]+$/', $value);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must contain only letters and spaces.';
    }
}
