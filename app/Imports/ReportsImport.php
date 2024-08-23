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
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row)
        {
            $rowNumber = $index + 2;

            if ($this->isRowEmpty($row)) {
                continue;
            }

            $this->validateRow($row, $rowNumber);

            $reports = Report::where("policy_num", $row['policy_number'])->first();

            $arprDate = $this->convertExcelDate($row['arpr_date']);
            $inceptionDate = $this->convertExcelDate($row['inception_date']);

            $data = [
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
            ];

            if ($reports) {
                $reports->update($data);           
            } else {
                $data['policy_num'] = $row['policy_number'];
                Report::create($data);
            }
        }
    }

    private function isRowEmpty($row)
    {
        return empty(array_filter($row->toArray()));
    }

    private function convertExcelDate($excelDate)
    {
        if (!is_numeric($excelDate)) {
            return $excelDate;
        }
        $unixDate = ($excelDate - 25569) * 86400;
        return Carbon::createFromTimestamp($unixDate)->format('Y-m-d');
    }

    private function mapPolicyStatus($status)
    {
        return match (strtoupper($status)) {
            'NEW' => PolicyStatus::NEW,
            'RENEWAL' => PolicyStatus::RENEWAL,
            default => null,
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

    private function validateRow($row, $rowNumber)
    {
        $requiredFields = [
            'arpr_number', 'arpr_date', 'inception_date', 'assured', 'policy_number',
            'terms', 'gross_premium', 'total_payment', 'plate_no', 'car_details',
            'policy_status', 'mortagagee_or_financing', 'mode_of_application'
        ];

        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                throw new \Exception("Row {$rowNumber}: " . ucfirst(str_replace('_', ' ', $field)) . " field is blank. This field is required.");
            }
        }
    }
}