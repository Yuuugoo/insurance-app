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
            $this->validateRow($row);

            $reports = Report::where("policy_num", $row['policy_number'])->first();

            // Convert Excel date numbers to proper date format
            $arprDate = $this->convertExcelDate($row['arpr_date']);
            $inceptionDate = $this->convertExcelDate($row['inception_date']);

            if ($reports) {
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
            'FACEBOOK/ONLINE' => ModeApplication::FBOL,
            'CALL' => ModeApplication::CALL,
            'DROPBY' => ModeApplication::DROPBY,
            default => null,
        };
    }

    public static function getClassId($salesPerson)
    public static function getClassId($salesPerson)
    {
        if (empty($salesPerson)) {
            throw new \Exception("Sales Person field is blank. This field is required.");
        }
    
        $user = User::where('name', $salesPerson)->first();
    
        if (!$user) {
            throw new \Exception("Sales Person $salesPerson not found. This field is required.");
        }
    
        return $user->id;
    }

    public static function getCostcenterId($costCenter)
    {
        if (empty($costCenter)) {
            throw new \Exception("Cost Center field is blank. This field is required.");
        }
    
        $branch = CostCenter::where('name', $costCenter)->first();
    
        if (!$branch) {
            throw new \Exception("Cost Center '{$costCenter}' not found. This field is required.");
        }
    
        return $branch->cost_center_id;
    }
   
    
    public static function getInsuranceTypeId($insuranceTypeId)
    {
        if (empty($insuranceTypeId)) {
            throw new \Exception("Insurance Type field is blank. This field is required.");
        }

        $type = InsuranceType::where('name', $insuranceTypeId)->first();
        if (!$type) {
            throw new \Exception("Insurance Type '{$insuranceTypeId}' not found. This field is required.");
        }

        return $type->insurance_type_id;
       
    }
    
    public static function getInsuranceProviderId($insuranceProviderId)
    {
        if (empty($insuranceProviderId)) {
            throw new \Exception("Insurance Provider field is blank. This field is required.");
        }

        $provider = InsuranceProvider::where('name', $insuranceProviderId)->first();
        if (!$provider) {
            throw new \Exception("Insurance Provider '{$insuranceProviderId}' not found. This field is required.");
        }

        return $provider->insurance_provider_id;
      
    }
    
    public static function getPaymentModeId($paymentModeId)
    {

        if (empty($paymentModeId)) {
            throw new \Exception("Payment Mode field is blank. This field is required.");
        }

        $payment = PaymentMode::where('name', $paymentModeId)->first();
        if (!$payment) {
            throw new \Exception("Payment Mode '{$paymentModeId}' not found. This field is required.");
        }
        return $payment->payment_id;
       
    }

      /**
     * Validate required fields in the row
     *
     * @param array $row
     * @throws \Exception
     */
    private function validateRow($row)
    {
        if (empty($row['arpr_number'])) {
            throw new \Exception("ARPR Number field is blank. This field is required.");
        }

        if (empty($row['arpr_date'])) {
            throw new \Exception("ARPR Date field is blank. This field is required.");
        }

        if (empty($row['inception_date'])) {
            throw new \Exception("Inception Date field is blank. This field is required.");
        }

        if (empty($row['assured'])) {
            throw new \Exception('Assured field is blank. This field is required.');
        }

        if (empty($row['policy_number'])) {
            throw new \Exception('Policy Number field is blank. This field is required.');
        }

        if (empty($row['terms'])) {
            throw new \Exception('Terms field is blank. This field is required.');
        }

        if (empty($row['gross_premium'])) {
            throw new \Exception('Gross Premium field is blank. This field is required.');
        }

        if (empty($row['total_payment'])) {
            throw new \Exception('Total Payment field is blank. This field is required.');
        }

        if (empty($row['plate_no'])) {
            throw new \Exception('Plate Number field is blank. This field is required.');
        }

        if (empty($row['car_details'])) {
            throw new \Exception('Car Details field is blank. This field is required.');
        }

        if (empty($row['policy_status'])) {
            throw new \Exception('Policy Status field is blank. This field is required.');
        }

        if (empty($row['mortagagee_or_financing'])) {
            throw new \Exception('Mortagagee or Financing field is blank. This field is required.');
        }

        if (empty($row['mode_of_application'])) {
            throw new \Exception('Mode of Application field is blank. This field is required.');
        }  
        // Add additional validations if needed
    }
}


 //    $branch = CostCenter::where('name', $costCenter)->first();

    //     if (!$branch) {
    //         throw new \Exception("Cost Center '{$costCenter}' not found. This field is required.");
    //     }

    //     return $branch->cost_center_id;
    

    
    
    // public static function getCostcenterId($costCenter)
    // {
    //     $costCenter = CostCenter::where('name', $costCenter)->first();
    //     return $costCenter->cost_center_id;
    // }