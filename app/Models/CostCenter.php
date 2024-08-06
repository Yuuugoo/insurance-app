<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;

    protected $primaryKey = 'cost_center_id';

    protected $fillable = ['cost_center_id' ,'name'];

    public function reports()
    {
        return $this->hasMany(Report::class, 'cost_center');
    }
}
