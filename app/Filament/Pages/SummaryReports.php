<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\CostCenter;
use App\Models\InsuranceProvider;
use App\Models\InsuranceType;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class SummaryReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.summary-report';

    public static function canAccess(): bool
    {       
        $user = Auth::user();
        return $user->hasRole(['cfo']);
    }

    public function getCostCenters()
    {
        return CostCenter::all();
    }

    public function getInsuranceProviders()
    {
        return InsuranceProvider::all();
    }

    public function getInsuranceTypes()
    {
        return InsuranceType::all();
    }

    public function getSelectedProvider()
    {
        $providerId = request()->query('provider', null);
        return $providerId ? InsuranceProvider::find($providerId) : null;
    }

    public function getProviderHeaders()
    {
        $provider = $this->getSelectedProvider();
        if ($provider) {
            $name = strtoupper($provider->name);
            $types = InsuranceType::all();
            return $types->map(function ($type) use ($name) {
                return "{$name} {$type->name}";
            })->toArray();
        }

        return [];
    }

    public function getGrossPremium($costCenterId, $header)
    {
        $insuranceTypeName = explode(' ', $header);
        $insuranceTypeName = end($insuranceTypeName);

        $provider = $this->getSelectedProvider();
        $insuranceType = InsuranceType::where('name', $insuranceTypeName)->first();
        $costCenter = CostCenter::where('cost_center_id', $costCenterId)->first();

        $query = Report::query();

        if ($provider) {
            $query->where('report_insurance_prod_id', $provider->insurance_provider_id);
        }

        if ($costCenter) {
            $query->where('report_cost_center_id', $costCenter->cost_center_id);
        }

        if ($insuranceType) {
            $query->where('report_insurance_type_id', $insuranceType->insurance_type_id);
        }

        return $query->sum('gross_premium');
    }

    public function getTotalGrossPremium($costCenterId)
    {
        $provider = $this->getSelectedProvider();
        $costCenter = CostCenter::where('cost_center_id', $costCenterId)->first();

        $query = Report::query();

        if ($provider) {
            $query->where('report_insurance_prod_id', $provider->insurance_provider_id);
        }

        if ($costCenter) {
            $query->where('report_cost_center_id', $costCenter->cost_center_id);
        }


        return $query->sum('gross_premium');
    }

    public function getTotalForHeader($header)
    {
        return $this->getCostCenters()->sum(function ($costCenter) use ($header) {
            return $this->getGrossPremium($costCenter->cost_center_id, $header);
        });
    }

    public function getGrandTotal()
    {
        return $this->getCostCenters()->sum(function ($costCenter) {
            return $this->getTotalGrossPremium($costCenter->cost_center_id);
        });
    }
}