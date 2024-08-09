<?php

namespace App\Models;

use App\Enums\PolicyStatus;
use App\Enums\PaymentStatus;
use App\Enums\ModeApplication;
use App\Enums\Terms;
use App\Models\CostCenter as ModelsCostCenter;
use App\Models\InsuranceType as ModelsInsuranceType;
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
        'submitted_by_id', 'report_cost_center_id',
        'sale_person',  'arpr_num', 'arpr_date',
        'insurance_prod', 'insurance_type', 'inception_date', 
        'assured', 'policy_num', 'application', 'cashier_remarks', 
        'acct_remarks', 'policy_file', 'terms', 'gross_premium','payment_balance',
        'payment_mode',  'total_payment', 'plate_num',
        'car_details', 'policy_status',    'financing_bank',
        'payment_status', 'add_remarks','others_insurance_type', 
        'others_insurance_prod', 'others_application', 'remit_deposit', 'arpr_date_remarks'
    ];

    protected $casts = [
        'application' => ModeApplication::class,
        'payment_status' => PaymentStatus::class,
        'policy_status' => PolicyStatus::class,
        'terms' => Terms::class,
        'add_remarks' => 'boolean',
        'policy_file' => 'encrypted',
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

    protected static function boot() // This gets the id of the user who submitted the report
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


    public function getRemitDepositAttribute($value)
    {
        $decodedValue = json_decode($value, true);
        if (is_array($decodedValue)) {
            foreach ($decodedValue as &$item) {
                if (isset($item['depo_slip'])) {
                    $item['depo_slip'] = Crypt::decryptString($item['depo_slip']);
                }
            }
        }
        return $decodedValue;
    }

    public function setRemitDepositAttribute($value)
    {
        if (is_array($value)) {
            foreach ($value as &$item) {
                if (isset($item['depo_slip'])) {
                    $item['depo_slip'] = Crypt::encryptString($item['depo_slip']);
                }
            }
        }
        $this->attributes['remit_deposit'] = json_encode($value);
    }

    public function providers()
    {
        return $this->hasMany(InsuranceProvider::class, 'insurance_prod_id');
    }
    
    public function types()
    {
        return $this->hasMany(ModelsInsuranceType::class, 'insurance_type_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'report_cost_center_id', 'cost_center_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentMode::class, 'payment_mode_id');
    }
}
