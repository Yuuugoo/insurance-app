<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Report;
use App\Models\CostCenter;
use App\Enums\PolicyStatus;
use App\Models\PaymentMode;
use App\Models\InsuranceType;
use App\Enums\ModeApplication;
use App\Models\InsuranceProvider;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;



class ReportsImport implements WithHeadingRow, ToCollection
{
    /**
    * @param array $row
     *@param collection $collection
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function collection(collection $rows)
    {
        foreach ($rows as $row)
        {
            $reports = Report::where("policy_num", $row['policy_number'])->first();
            if($reports){

                $reports->update([
                    'sales_person_id' => self::getClassId($row['sales_person']),
                    'report_cost_center_id' => self::getCostcenterId($row['cost_center']),
                    'arpr_num' => $row['arpr_number'],
                    'arpr_date' => $row['arpr_date'],
                    'inception_date' => $row['inception_date'],
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
            }else {
                
                Report::create([          
                    'sales_person_id' => self::getClassId($row['sales_person']),
                    'report_cost_center_id' => self::getCostcenterId($row['cost_center']),
                    'arpr_num' => $row['arpr_number'],
                    'arpr_date' => $row['arpr_date'],
                    'inception_date' => $row['inception_date'],
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



        // public function model(array $row)
        // {


        //     return new Report([
            
        //     'sales_person_id' => self::getClassId($row['sales_person']),
        //     'report_cost_center_id' => self::getCostcenterId($row['cost_center']),
        //     'arpr_num' => $row['arpr_number'],
        //     'arpr_date' => $row['arpr_date'],
        //     'inception_date' => $row['inception_date'],
        //     'assured' => $row['assured'],
        //     'policy_num' => $row['policy_number'],
        //     'report_insurance_prod_id' => self::getInsuranceProviderId($row['insurance_provider']),
        //     'report_insurance_type_id' => self::getInsuranceTypeId($row['insurance_type']),
        //     'terms' => $row['terms'],
        //     'gross_premium' => $row['gross_premium'],
        //     'report_payment_mode_id' => self::getPaymentModeId($row['mode_of_payment']),
        //     'total_payment' => $row['total_payment'],
        //     'plate_num' => $row['plate_no'],
        //     'car_details' => $row['car_details'],
        //     'policy_status' => $this->mapPolicyStatus($row['policy_status']),
        //     'application' => $this->mapModeofApplication($row['mode_of_application']),
        //     'financing_bank' => $row['mortagagee_or_financing'],
                
        //     ]);
        // }

    

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
        return $user->id;
    }

    public static function getCostcenterId($costCenter)
    {
        $costCenter = CostCenter::where('name', $costCenter)->first();
        return $costCenter->cost_center_id;
    }

    public static function getInsuranceTypeId($insuranceTypeId)
    {
        $insuranceTypeId = InsuranceType::where('name', $insuranceTypeId)->first();
        return $insuranceTypeId->insurance_type_id;
    }

    public static function getInsuranceProviderId($insuranceProviderId)
    {
        $insuranceProviderId = InsuranceProvider::where('name', $insuranceProviderId)->first();
        return $insuranceProviderId->insurance_provider_id;
    }

    public static function getPaymentModeId($paymentModeId)
    {
        $paymentModeId = PaymentMode::where('name', $paymentModeId)->first();
        return $paymentModeId->payment_id;
    }
   

    // public static function getSectionId($class, $section)
    // {
    //     $class_id = self::getClassId($class);

    //     $section_model = Section::where([
    //         'class_id' => $class_id,
    //         'name' => $section
    //     ])->first();

    //     return $section_model->id;
    // }
}
