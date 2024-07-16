<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ARPRNO implements Rule
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
        return preg_match('/^[A-Z]{2}\d{7}$/', $value) || preg_match('/^[A-Z]{2}\d{5}$/', $value) || preg_match('/^[A-Z]{2}\d{6}$/', $value) ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The AR/PR NO. format is invalid.';
    }
}
