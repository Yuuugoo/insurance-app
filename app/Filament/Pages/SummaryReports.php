<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\CostCenter;
use App\Models\InsuranceProvider;
use App\Models\InsuranceType;
use App\Models\Report;

class SummaryReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.summary-report';

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

    public function getSelectedCostCenter()
    {
        $costCenterId = request()->query('cost_center', null);
        return $costCenterId ? CostCenter::find($costCenterId) : null;
    }

    public function getSelectedInsuranceType()
    {
        $insuranceTypeId = request()->query('insurance_type', null);
        return $insuranceTypeId ? InsuranceType::find($insuranceTypeId) : null;
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

    public function getGrossPremium()
    {
        // Retrieve selected criteria
        $provider = $this->getSelectedProvider();
        $costCenter = $this->getSelectedCostCenter();
        $insuranceType = $this->getSelectedInsuranceType();

        // dd($insuranceType);

        // Build the query to filter reports based on the selected criteria
        $query = Report::query();

        if ($provider) {
            
            $query->where('insurance_prod', $provider->name);
            
        }

        if ($costCenter) {
            
            $query->where('cost_center', $costCenter->name);
           
        }

        if ($insuranceType) {
            $query->where('insurance_type', $insuranceType->name);
        }

        // Calculate the sum of gross premiums from the filtered reports
        return $query->sum('gross_premium');
    }
}

