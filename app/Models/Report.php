<?php

namespace App\Models;

use App\Enums\CostCenter;
use App\Enums\PolicyStatus;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\PaymentStatus;
use App\Enums\ModeApplication;
use App\Enums\Payment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;



class Report extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'reports_id';

    protected $fillable = [
        'user_id',
        'sale_person', 'cost_center', 'arpr_num', 'arpr_date',
        'insurance_prod', 'insurance_type', 'inception_date', 
        'assured', 'policy_num', 'application', 'cashier_remarks', 
        'remit_date', 'acct_remarks', 'depo_slip', 
        'policy_file', 'terms', 'gross_premium','payment_balance',
        'payment_mode',  'total_payment', 'plate_num',
        'car_details', 'policy_status',    'financing_bank',
        'payment_status'
    ];

    
    

    protected $casts = [
        'cost_center' => CostCenter::class,
        'insurance_prod' => InsuranceProd::class,
        'insurance_type' => InsuranceType::class,
        'application' => ModeApplication::class,
        'payment_status' => PaymentStatus::class,
        'policy_status' => PolicyStatus::class,
        'payment_mode' => Payment::class,
       // 'depo_slip' => 'encrypted',
      //  'plate_num' => 'encrypted',
      
    ];


    public function user_reports(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (!$report->user_id) {
                $report->user_id = auth()->id();
            }
        });
    }

   
}
