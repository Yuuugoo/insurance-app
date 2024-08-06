<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceType extends Model
{
    use HasFactory;

    protected $primaryKey = 'insurance_type_id';

    protected $fillable = ['insurance_type_id' ,'name'];

    public function types()
    {
        return $this->hasMany(Report::class, 'cost_center');
    }
}
