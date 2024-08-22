<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    use HasFactory;

    protected $primaryKey = 'terms_id';

    protected $fillable = [
        'report_terms_id', 'due_date', 'terms_gross_premium',
        'terms_payments', 'terms_outstanding_balance', 'is_paid',
        'terms_status'
    ];

    public function reportTerms()
    {
        return $this->hasMany(Report::class, 'report_terms_id', 'reports_id');
    }

}
