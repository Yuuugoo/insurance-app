<?php

namespace App\Models;

use App\Enums\CostCenter;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\ModeApplication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'payment_id', (FK)
        'sale_person', 'cost_center', 'arpr_num', 'arpr_date',
        'insurance_prod', 'insurance_type',
        // 'inception_date', 'assured', 'policy_num',
        // 'application', 'cashier_remarks', 'remit_date', 'acct_remarks',
        // 'depo_slip', 'policy_file',
    ];

    protected $casts = [
        'cost_center' => CostCenter::class,
        'insurance_prod' => InsuranceProd::class,
        'insurance_type' => InsuranceType::class,
        'application' => ModeApplication::class,
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
