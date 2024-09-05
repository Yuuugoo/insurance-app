<?php

namespace App\Models;


use App\Enums\Terms;
use App\Enums\PolicyStatus;
use App\Enums\PaymentStatus;
use App\Enums\ModeApplication;
use App\Traits\Systemencryption;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CostCenter as ModelsCostCenter;
use App\Models\InsuranceType as ModelsInsuranceType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Report extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $primaryKey = 'reports_id';

    protected $fillable = [
        'submitted_by_id', 'report_cost_center_id', 'report_insurance_prod_id', 
        'report_insurance_type_id', 'report_payment_mode_id', 'sales_person_id', 'sale_person',  'arpr_num', 'arpr_date',
        'inception_date', 'assured', 'policy_num', 'application', 'cashier_remarks', 
        'acct_remarks', 'policy_file', 'terms', 'gross_premium','payment_balance',
        'payment_mode',  'total_payment', 'plate_num','car_details', 
        'policy_status', 'financing_bank', 'payment_status', 'remit_deposit', 
        'arpr_date_remarks', 'add_remarks', 'payment_status_aap',
        '1st_payment','1st_payment_date','1st_is_paid',
        '2nd_payment','2nd_payment_date','2nd_is_paid',
        '3rd_payment','3rd_payment_date','3rd_is_paid',
        '4th_payment','4th_payment_date','4th_is_paid',
        '5th_payment','5th_payment_date','5th_is_paid',
        '6th_payment','6th_payment_date','6th_is_paid',
        '1st_terms_status','2nd_terms_status','3rd_terms_status','4th_terms_status','5th_terms_status','6th_terms_status',
    ];

   

    protected $casts = [
        'application' => ModeApplication::class,
        'payment_status' => PaymentStatus::class,
        'payment_status_aap' => PaymentStatus::class,
        'policy_status' => PolicyStatus::class,
        'terms' => Terms::class,
        'add_remarks' => 'boolean',
        'policy_file' => 'encrypted',
       
        'assured' => 'encrypted',
        // 'arpr_date' => 'datetime:m-d-Y',
        // 'inception_date' => 'datetime:m-d-Y',
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
        if ($this->payment_status == PaymentStatus::PAID && $this->payment_status_aap == PaymentStatus::PAID) {
            return true;
        }

        return false;
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

    public function updateOrCreatePaymentTerm($paymentOrder, $termsPayment, $paymentDate)
    {
        Log::info("Updating/Creating payment term", [
            'report_id' => $this->reports_id,
            'payment_order' => $paymentOrder,
            'terms_payment' => $termsPayment,
            'payment_date' => $paymentDate
       
            
        ]);
        
        //dagdag ka rito pre ng gusto mong ipasok sa database like terms_outstanding_balance and payment_date

            $result = $this->paymentTerms()->updateOrCreate(
                [
                    'report_terms_id' => $this->reports_id,
                    'payment_order' => $paymentOrder,
                ],
                [
                    'terms_payment' => $termsPayment,
                    'payment_date' => $paymentDate,
                ]
            );

        return $result;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([
            'sale_person', 'cost_center', 'arpr_num', 'arpr_date',
            'insurance_prod', 'insurance_type', 'inception_date', 
            'assured', 'policy_num', 'application', 'cashier_remarks', 'acct_remarks',  
            'policy_file', 'terms', 'gross_premium','payment_balance',
            'payment_mode',  'total_payment', 'plate_num',
            'car_details', 'policy_status',    'financing_bank', 'report_payment_mode_id',
            'payment_status', 'remit_deposit', 'arpr_date_remarks', 'report_cost_center_id', 
            'report_insurance_prod_id', 'report_insurance_type_id', 'payment_status_aap', 'sales_person_id',
            '1st_payment','1st_payment_date','1st_is_paid',
            '2nd_payment','2nd_payment_date','2nd_is_paid',
            '3rd_payment','3rd_payment_date','3rd_is_paid',
            '4th_payment','4th_payment_date','4th_is_paid',
            '5th_payment','5th_payment_date','5th_is_paid',
            '6th_payment','6th_payment_date','6th_is_paid',
            
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
        return $this->belongsTo(InsuranceProvider::class, 'report_insurance_prod_id', 'insurance_provider_id');
    }
    
    public function types()
    {
        return $this->belongsTo(ModelsInsuranceType::class, 'report_insurance_type_id', 'insurance_type_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'report_cost_center_id', 'cost_center_id');
    }

    public function payments()
    {
        return $this->belongsTo(PaymentMode::class, 'report_payment_mode_id' ,'payment_id');
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id' ,'id');
    }

    public function paymentTerms()
    {
        return $this->hasMany(PaymentTerm::class, 'report_terms_id', 'reports_id');
    }

}
