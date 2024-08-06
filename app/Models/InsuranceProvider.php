<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    use HasFactory;

    protected $primaryKey = 'cost_center_id';

    protected $fillable = ['insurance_type_id' ,'name'];

    public function providers()
    {
        return $this->hasMany(Report::class, 'cost_center');
    }
}
