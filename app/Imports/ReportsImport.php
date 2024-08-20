<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use App\Models\CostCenter;
use App\Enums\PolicyStatus;
use App\Models\PaymentMode;
use App\Models\InsuranceType;
use App\Enums\ModeApplication;
use App\Models\InsuranceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReportsImport implements WithHeadingRow, ToCollection
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            try {
            $reports = Report::where("policy_num", $row['policy_number'])->first();
            
            // Convert Excel date numbers to proper date format
            $arprDate = $this->convertExcelDate($row['arpr_date']);
            $inceptionDate = $this->convertExcelDate($row['inception_date']);
            
            if($reports){
                $reports->update([
                    'sales_person_id' => self::getClassId($row['sales_person']),
                    'report_cost_center_id' => self::getCostcenterId($row['cost_center']),
                    'arpr_num' => $row['arpr_number'],
                    'arpr_date' => $arprDate,
                    'inception_date' => $inceptionDate,
                    'assured' => $row['assured'],
                    'report_insurance_prod_id' => self::getInsuranceProviderId($row['insurance_provider']),
                    'report_insurance_type_id' => self::getInsuranceTypeId($row['insurance_type']),
                    'terms' => $row['terms'],
                    'gross_premium' => $row['gross_premium'],
                    'report_payment_mode_id' => self::getPaymentModeId($row['mode_of_payment']),
                    'total_payment' => $row['total_payment'],
                    'plate_num' => $row['plate_no'],
                    'car_details' => $row['car_details'],
                    'policy_status' => $this->mapPolicyStatus($row['policy_status']),
                    'application' => $this->mapModeofApplication($row['mode_of_application']),
                    'financing_bank' => $row['mortagagee_or_financing'],
                ]);           
            } else {
                Report::create([          
                    'sales_person_id' => self::getClassId($row['sales_person']),
                    'report_cost_center_id' => self::getCostcenterId($row['cost_center']),
                    'arpr_num' => $row['arpr_number'],
                    'arpr_date' => $arprDate,
                    'inception_date' => $inceptionDate,
                    'assured' => $row['assured'],
                    'policy_num' => $row['policy_number'],
                    'report_insurance_prod_id' => self::getInsuranceProviderId($row['insurance_provider']),
                    'report_insurance_type_id' => self::getInsuranceTypeId($row['insurance_type']),
                    'terms' => $row['terms'],
                    'gross_premium' => $row['gross_premium'],
                    'report_payment_mode_id' => self::getPaymentModeId($row['mode_of_payment']),
                    'total_payment' => $row['total_payment'],
                    'plate_num' => $row['plate_no'],
                    'car_details' => $row['car_details'],
                    'policy_status' => $this->mapPolicyStatus($row['policy_status']),
                    'application' => $this->mapModeofApplication($row['mode_of_application']),
                    'financing_bank' => $row['mortagagee_or_financing'],
                    
                    ]);
                }
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error('Error processing row: ' . $e->getMessage());
            continue; // Skip this row and continue with the next one
        }
    }
}

    /**
     * Convert Excel date number to Carbon date
     *
     * @param mixed $excelDate
     * @return string|null
     */
    private function convertExcelDate($excelDate)
    {
        if (!is_numeric($excelDate)) {
            return $excelDate; // Return as is if it's not a number
        }

        // Excel's date system starts from 1900-01-01, so we add days to that date
        $unixDate = ($excelDate - 25569) * 86400;
        return Carbon::createFromTimestamp($unixDate)->format('Y-m-d');
    }

    private function mapPolicyStatus($status)
    {
        return match (strtoupper($status)) {
            'NEW' => PolicyStatus::NEW,
            'RENEWAL' => PolicyStatus::RENEWAL,
            // Add other mappings as needed
            default => null, // or a default value
        };
    }

    private function mapModeofApplication($application)
    {
        return match (strtoupper($application)) {
            'FB/OL' => ModeApplication::FBOL,
            'CALL' => ModeApplication::CALL,
            'DROPBY' => ModeApplication::DROPBY,
            default => null,
        };
    }

    public static function getClassId($user)
    {
        $user = User::where('name', $user)->first();
        if (!$user) {
            throw new \Exception("User not found: $user");
        }
        return $user->id;
    }
    
    public static function getCostcenterId($costCenter)
    {
        $costCenter = CostCenter::where('name', $costCenter)->first();
        if (!$costCenter) {
            throw new \Exception("Cost Center not found: $costCenter");
        }
        return $costCenter->cost_center_id;
    }
    
    public static function getInsuranceTypeId($insuranceTypeId)
    {
        $insuranceType = InsuranceType::where('name', $insuranceTypeId)->first();
        if (!$insuranceType) {
            throw new \Exception("Insurance Type not found: $insuranceTypeId");
        }
        return $insuranceType->insurance_type_id;
    }
    
    public static function getInsuranceProviderId($insuranceProviderId)
    {
        $insuranceProvider = InsuranceProvider::where('name', $insuranceProviderId)->first();
        if (!$insuranceProvider) {
            throw new \Exception("Insurance Provider not found: $insuranceProviderId");
        }
        return $insuranceProvider->insurance_provider_id;
    }
    
    public static function getPaymentModeId($paymentModeId)
    {
        $paymentMode = PaymentMode::where('name', $paymentModeId)->first();
        if (!$paymentMode) {
            throw new \Exception("Payment Mode not found: $paymentModeId");
        }
        return $paymentMode->payment_id;
    }
}