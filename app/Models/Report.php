<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'payment_id', (FK)
        'sale_person', 'cost_center', 'arpr_num', 'arpr_date',
        // 'inception_date', 'assured', 'policy_num', 'insurance_prod',
        // 'application', 'cashier_remarks', 'remit_date', 'acct_remarks',
        // 'depo_slip', 'policy_file',
    ];

    public function user_reports(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report_payment(): HasOne
    {
        return $this->hasOne(ReportPayment::class);
    }

    public function report_vehicle(): HasOne
    {
        return $this->hasOne(ReportVehicle::class);
    }

}
