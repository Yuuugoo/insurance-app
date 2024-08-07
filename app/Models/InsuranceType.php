<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceType extends Model
{
    use HasFactory;

    protected $primaryKey = 'insurance_type_id';

    protected $fillable = ['insurance_type_id' ,'name'];

    public function types()
    {
        return $this->BelongsTo(Report::class, 'insurance_type');
    }
}
