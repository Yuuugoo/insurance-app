<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    use HasFactory;

    protected $primaryKey = 'insurance_provider_id';

    protected $fillable = ['name'];

    public function providers()
    {
        return $this->belongsTo(Report::class, 'insurance_provider');
    }
}
