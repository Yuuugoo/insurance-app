<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Report;

class ARPRNO implements Rule
{
    private $insuranceProd;
    private $recordId;
    private $errorMessage;

    public function __construct($insuranceProd, $recordId = null)
    {
        $this->insuranceProd = $insuranceProd;
        $this->recordId = $recordId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check format
        $formatValid = preg_match('/^[A-Z]{2}\d{7}$/', $value) || 
                       preg_match('/^[A-Z]{2}\d{5}$/', $value) || 
                       preg_match('/^[A-Z]{2}\d{6}$/', $value);

        if (!$formatValid) {
            $this->errorMessage = 'The AR/PR NO. format is invalid.';
            return false;
        }

        // Check uniqueness
        $query = Report::where('insurance_prod', $this->insuranceProd)
                       ->where('arpr_num', $value);

        if ($this->recordId) {
            $query->where('id', '!=', $this->recordId);
        }

        if ($query->exists()) {
            $this->errorMessage = 'The AR/PR number already exists for this insurance product.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}