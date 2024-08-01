<?php

namespace App\Models;

use App\Enums\CostCenter;
use App\Enums\PolicyStatus;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\PaymentStatus;
use App\Enums\ModeApplication;
use App\Enums\Payment;
use App\Enums\Terms;
use App\Traits\Systemencryption;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Report extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $primaryKey = 'reports_id';

    protected $fillable = [
        'submitted_by_id', 'approved_by_id',
        'sale_person', 'cost_center', 'arpr_num', 'arpr_date',
        'insurance_prod', 'insurance_type', 'inception_date', 
        'assured', 'policy_num', 'application', 'cashier_remarks', 
        'remit_date', 'acct_remarks', 'depo_slip', 
        'policy_file', 'terms', 'gross_premium','payment_balance',
        'payment_mode',  'total_payment', 'plate_num',
        'car_details', 'policy_status',    'financing_bank',
        'payment_status', 'remit_date_partial', 'add_remarks','others_insurance_type', 
        'others_insurance_prod', 'others_application', 'final_depo_slip'
    ];

    protected $casts = [
        'cost_center' => CostCenter::class,
        'insurance_prod' => InsuranceProd::class,
        'insurance_type' => InsuranceType::class,
        'application' => ModeApplication::class,
        'payment_status' => PaymentStatus::class,
        'policy_status' => PolicyStatus::class,
        'payment_mode' => Payment::class,
        'terms' => Terms::class,
        'add_remarks' => 'boolean',
        'depo_slip' => 'encrypted',
        'policy_file' => 'encrypted',
        'final_depo_slip' => 'encrypted',
        'sale_person' => 'encrypted',
        'assured' => 'encrypted',
        'policy_num' => 'encrypted',
        'plate_num' => 'encrypted',
        'car_details' => 'encrypted',
        'financing_bank' => 'encrypted',
        'remit_deposit' => 'array',
    ];
    

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_id', 'id');
    }

    public function canEdit(): bool
    {
        if ($this->payment_status == PaymentStatus::PAID) {
            return true;
        }

        return false;
    }

    
    

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        

        static::creating(function ($report) {
            if (!$report->submitted_by_id) {
                $report->submitted_by_id = auth()->id();
            }

        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([
            'sale_person', 'cost_center', 'arpr_num', 'arpr_date',
            'insurance_prod', 'insurance_type', 'inception_date', 
            'assured', 'policy_num', 'application', 'cashier_remarks', 
            'remit_date', 'acct_remarks', 'depo_slip', 
            'policy_file', 'terms', 'gross_premium','payment_balance',
            'payment_mode',  'total_payment', 'plate_num',
            'car_details', 'policy_status',    'financing_bank',
            'payment_status', 'remit_date_partial', 'add_remarks','others_insurance_type', 
            'others_insurance_prod', 'others_application', 'final_depo_slip'
        ])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();

    }

    
}
