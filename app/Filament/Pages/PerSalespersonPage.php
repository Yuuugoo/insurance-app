<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use Filament\Pages\Page;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use App\Models\InsuranceType;
use App\Models\InsuranceProvider;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Contracts\Support\Htmlable;

class PerSalespersonPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'SUMMARY';
    protected static string $view = 'filament.pages.per-salesperson-page';
    protected static ?string $navigationLabel = 'Per Salesperson';

    public function getTitle(): string|Htmlable
    {
        return 'Reports per Salesperson';
    }

    public static function canAccess(): bool
    {       
        $user = Auth::user();
        return $user->hasRole(['cfo']);
    }

    public $startMonth;
    public $endMonth;
    public $selectedProvider;
    public $selectedCostCenter;

    public function mount()
    {
        $this->startMonth = request()->query('start_month', Carbon::now()->startOfMonth()->format('Y-m'));
        $this->endMonth = request()->query('end_month', Carbon::now()->endOfMonth()->format('Y-m'));
        $this->selectedProvider = request()->query('provider');
        $this->selectedCostCenter = request()->query('cost_center');
    }

    private function sanitizeDate($date)
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m');
        }
        
        try {
            return Carbon::parse($date)->format('Y-m');
        } catch (\Exception $e) {
            return Carbon::now()->format('Y-m');
        }
    }

    public function resetFilters()
    {
        $this->startMonth = Carbon::now()->startOfMonth();
        $this->endMonth = Carbon::now()->endOfMonth();
        $this->selectedProvider = null;
        $this->selectedCostCenter = null;
        return redirect(static::getUrl());
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

    public function getStartDate()
    {
        return Carbon::parse($this->startMonth)->startOfMonth();
    }

    public function getEndDate()
    {
        return Carbon::parse($this->endMonth)->endOfMonth();
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

    public function getSalespersons()
    {
        $query = User::role('agent');

        if ($this->selectedCostCenter) {
            $query->where('branch_id', $this->selectedCostCenter);
        }

        return $query->get();
    }

    public function getSalespersonGrossPremium($salespersonId, $insuranceTypeId)
    {
        $query = Report::query()
            ->where('sales_person_id', $salespersonId)
            ->where('report_insurance_type_id', $insuranceTypeId);

        if ($this->selectedProvider) {
            $query->where('report_insurance_prod_id', $this->selectedProvider);
        }

        if ($this->selectedCostCenter) {
            $query->where('report_cost_center_id', $this->selectedCostCenter);
        }

        if ($this->startMonth && $this->endMonth) {
            $query->whereBetween('arpr_date', [$this->getStartDate(), $this->getEndDate()]);
        }

        return $query->sum('gross_premium');
    }

    public function getTotalForSalesperson($salespersonId)
    {
        $query = Report::query()
            ->where('sales_person_id', $salespersonId);

        if ($this->selectedProvider) {
            $query->where('report_insurance_prod_id', $this->selectedProvider);
        }

        if ($this->selectedCostCenter) {
            $query->where('report_cost_center_id', $this->selectedCostCenter);
        }

        if ($this->startMonth && $this->endMonth) {
            $query->whereBetween('arpr_date', [$this->getStartDate(), $this->getEndDate()]);
        }

        return $query->sum('gross_premium');
    }

    public function getTotalForInsuranceType($insuranceTypeId)
    {
        $query = Report::query();

        if ($this->selectedProvider) {
            $query->where('report_insurance_prod_id', $this->selectedProvider);
        }

        if ($this->selectedCostCenter) {
            $query->where('report_cost_center_id', $this->selectedCostCenter);
        }

        if ($this->startMonth && $this->endMonth) {
            $query->whereBetween('arpr_date', [$this->getStartDate(), $this->getEndDate()]);
        }

        $query->where('report_insurance_type_id', $insuranceTypeId);

        return $query->sum('gross_premium');
    }

    public function getGrandTotal()
    {
        $query = Report::query();

        if ($this->selectedProvider) {
            $query->where('report_insurance_prod_id', $this->selectedProvider);
        }

        if ($this->selectedCostCenter) {
            $query->where('report_cost_center_id', $this->selectedCostCenter);
        }

        if ($this->startMonth && $this->endMonth) {
            $query->whereBetween('arpr_date', [$this->getStartDate(), $this->getEndDate()]);
        }

        return $query->sum('gross_premium');
    }

    public function export(Request $request)
    {
        $startMonth = $request->query('start_month', null);
        $endMonth = $request->query('end_month', null);
        $costCenterId = $request->query('cost_center');
        $provider = $this->getSelectedProvider();
        $costCenter = $costCenterId ? CostCenter::find($costCenterId) : null;
        
        $this->selectedProvider = $request->query('provider');
        $this->selectedCostCenter = $request->query('cost_center');
        $this->startMonth = Carbon::parse($startMonth);
        $this->endMonth = Carbon::parse($endMonth);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(30);
        $columnLetters = range('B', 'Z');
        foreach ($columnLetters as $letter) {
            $sheet->getColumnDimension($letter)->setWidth(15);
        }

        $providerHeaders = $this->getProviderHeaders();
        $providerHeaders = [];
        $headers = ['PER SALESPERSON'];
        $insuranceTypes = $this->getInsuranceTypes();
        if($this->selectedProvider == null){
            if (isset($insuranceTypes)) {
                foreach ($insuranceTypes as $type) {
                    $headers[] = $type->name;
                }
            }
        }
        if ($this->selectedProvider || $this->selectedCostCenter) {
            $providerHeaders = $this->getProviderHeaders();
            $headers = array_merge($headers, $providerHeaders);
        }

        $headers[] = 'TOTAL';
        $providerName = $provider ? strtoupper($provider->name) : "ALL PROVIDERS";
        $costCenterName = $costCenter ? strtoupper($costCenter->name) : "ALL COST CENTERS";

        if($this->startMonth == $this->endMonth) {
            $formattedStartMonth = $this->startMonth->format('F Y');
            $title = "{$providerName} and {$costCenterName} - {$formattedStartMonth}";
        }
        else {
            $formattedStartMonth = $this->startMonth->format('F Y');
            $formattedEndMonth = $this->endMonth->format('F Y');
            $title = "{$providerName} and {$costCenterName} - {$formattedStartMonth} - {$formattedEndMonth}";
        }


        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:' . $columnLetters[count($headers) - 1] . '1');
        $sheet->getStyle('A1:' . $columnLetters[count($headers) - 1] . '1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:' . $columnLetters[count($headers) - 1] . '1')->getFont()->setBold(true);

        if ($this->selectedProvider) {
            $provider = $this->getSelectedProvider();
            $sheet->setCellValue('B2', strtoupper($provider->name));
            $sheet->mergeCells('B2:' . $columnLetters[count($providerHeaders)] . '2');
            $sheet->getStyle('B2:' . $columnLetters[count($providerHeaders)] . '2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B2:' . $columnLetters[count($providerHeaders)] . '2')->getFont()->setBold(true);
        }
        if($this->selectedProvider == null){
            $sheet->fromArray($headers, NULL, 'A2');
            $sheet->getStyle('A2:' . $columnLetters[count($headers) - 1] . '2')->getFont()->setBold(true);
    
            $row = 3;
        }else{
            $sheet->fromArray($headers, NULL, 'A3');
            $sheet->getStyle('A3:' . $columnLetters[count($headers) - 1] . '3')->getFont()->setBold(true);

            $row = 4;
        }
        
        $insuranceTypes = $this->getInsuranceTypes();


        foreach ($this->getSalespersons() as $salesperson) {
            $dataRow = [$salesperson->name];

            if (isset($insuranceTypes)) {
                foreach ($insuranceTypes as $type) {
                    $value = $this->getSalespersonGrossPremium($salesperson->id, $type->insurance_type_id);
                    $dataRow[] = $value == 0 ? '-' : number_format($value, 2, '.', ',');
                }
            }

            $dataRow[] = number_format($this->getTotalForSalesperson($salesperson->id), 2, '.', ',');
            $sheet->fromArray($dataRow, NULL, "A$row");
            $row++;
        }

        $totalRow = ['TOTAL'];
        if (isset($insuranceTypes)) {
            foreach ($insuranceTypes as $type) {
                $total = $this->getTotalForInsuranceType($type->insurance_type_id);
                $totalRow[] = $total == 0 ? '-' : number_format($total, 2, '.', ',');
            }
        }
        $totalRow[] = number_format($this->getGrandTotal(), 2, '.', ',');
        $sheet->fromArray($totalRow, NULL, "A$row");
        $sheet->getStyle("A$row:" . $columnLetters[count($headers) - 1] . $row)->getFont()->setBold(true);

        $lastColumn = $columnLetters[count($headers) - 2];
        $sheet->getStyle("A1:$lastColumn$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->getStyle("B3:$lastColumn$row")->getNumberFormat()->setFormatCode('#,##0.00');


        $writer = new Xlsx($spreadsheet);

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="per-salesperson-report.xlsx"',
            ]
        );
    }


}
