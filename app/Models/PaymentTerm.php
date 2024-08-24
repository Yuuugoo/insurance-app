<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    use HasFactory;

    protected $primaryKey = 'terms_id';

    protected $fillable = [
        'terms_id','report_terms_id', 'payment_date','terms_payment', 
        'payment_order', 'terms_total_payment',
        'terms_outstanding_balance', 'is_paid', 'terms_status'
    ];

    public function reportTerms()
    {
        return $this->hasMany(Report::class, 'report_terms_id', 'reports_id');
    }

}
