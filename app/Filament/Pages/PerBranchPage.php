<?php

namespace App\Filament\Pages;

use App\Models\Report;
use Filament\Pages\Page;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use App\Models\InsuranceType;
use Illuminate\Support\Carbon;
use App\Models\InsuranceProvider;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Contracts\Support\Htmlable;

class PerBranchPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'SUMMARY';
    protected static string $view = 'filament.pages.per-branch-page';
    protected static ?string $navigationLabel = 'Per Branch';
    // protected static bool $shouldRegisterNavigation = false;
    
    public function getTitle(): string|Htmlable
    {
        return 'Reports Per Branch';
    }

    public $startMonth;
    public $endMonth;
    public $quarter;

    public function mount()
    {
        $this->startMonth = request()->query('start_month', null);
        $this->endMonth = request()->query('end_month', null);
        $this->quarter = request()->query('quarter', null);
        if ($this->quarter) {
            $this->setMonthsFromQuarter($this->quarter);
        }
    }

    public function resetFilters()
    {
        $this->startMonth = null;
        $this->endMonth = null;
        return redirect(static::getUrl());
    }

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

    public function getQuarterDates($quarter, $year)
    {
        $this->setMonthsFromQuarter($quarter);
        return [
            Carbon::parse($this->startMonth)->startOfDay(),
            Carbon::parse($this->endMonth)->endOfDay(),
        ];
    }

    private function setMonthsFromQuarter($quarter)
    {
        $year = Carbon::now()->year;
        switch ($quarter) {
            case 'Q1':
                $this->startMonth = "{$year}-01-01";
                $this->endMonth = "{$year}-03-31";
                break;
            case 'Q2':
                $this->startMonth = "{$year}-04-01";
                $this->endMonth = "{$year}-06-30";
                break;
            case 'Q3':
                $this->startMonth = "{$year}-07-01";
                $this->endMonth = "{$year}-09-30";
                break;
            case 'Q4':
                $this->startMonth = "{$year}-10-01";
                $this->endMonth = "{$year}-12-31";
                break;
        }
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

        if ($this->startMonth && $this->endMonth) {
            $query->whereBetween('arpr_date', [$this->getStartDate(), $this->getEndDate()]);
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

        if ($this->startMonth && $this->endMonth) {
            $query->whereBetween('arpr_date', [$this->getStartDate(), $this->getEndDate()]);
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

    public function getStartDate()
    {
        if ($this->quarter) {
            $year = Carbon::now()->year;
            return $this->getQuarterDates($this->quarter, $year)[0];
        }
        return Carbon::parse($this->startMonth)->startOfMonth();
    }

    public function getEndDate()
    {
        if ($this->quarter) {
            $year = Carbon::now()->year;
            return $this->getQuarterDates($this->quarter, $year)[1];
        }
        return Carbon::parse($this->endMonth)->endOfMonth();
    }

    public function export(Request $request)
    {
        $startMonth = $request->query('start_month', null);
        $endMonth = $request->query('end_month', null);
        $quarter = $request->query('quarter', null);
        $this->quarter = $quarter;

    
        if ($this->quarter) {
            $this->setMonthsFromQuarter($quarter);
        } else {
            $this->startMonth = $startMonth;
            $this->endMonth = $endMonth;
        }

        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
        $this->quarter = $quarter;
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(20);
        $columnLetters = range('B', 'Z');
        foreach ($columnLetters as $letter) {
            $sheet->getColumnDimension($letter)->setWidth(15);
        }

        $providerHeaders = $this->getProviderHeaders();
        $headers = array_merge(['PER BRANCH'], $providerHeaders, ['TOTAL']);

        if ($this->quarter !== null) {
            $year = Carbon::now()->year;
            $dateRange = "{$this->quarter} - {$year}";
        } else if ($this->startMonth && $this->endMonth) {
            $formattedStartMonth = Carbon::parse($this->startMonth)->format('F Y');
            $formattedEndMonth = Carbon::parse($this->endMonth)->format('F Y');
            $dateRange = "{$formattedStartMonth} - {$formattedEndMonth}";
        } else {
            $yearNow = Carbon::now()->year;
            $dateRange = "All Reports $yearNow";
        }
    
        $sheet->setCellValue('A1', $dateRange);
        $sheet->mergeCells('A1:' . $columnLetters[count($headers) - 1] . '1');
        $sheet->getStyle('A1:' . $columnLetters[count($headers) - 1] . '1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:' . $columnLetters[count($headers) - 1] . '1')->getFont()->setBold(true);
    
        $provider = $this->getSelectedProvider();
        if ($provider) {
            $sheet->setCellValue('B2', strtoupper($provider->name));
            $sheet->mergeCells('B2:' . $columnLetters[count($providerHeaders)] . '2');
            $sheet->getStyle('B2:' . $columnLetters[count($providerHeaders)] . '2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B2:' . $columnLetters[count($providerHeaders)] . '2')->getFont()->setBold(true);
        }

        $sheet->fromArray($headers, NULL, 'A3');
        $sheet->getStyle('A3:' . $columnLetters[count($headers) - 1] . '3')->getFont()->setBold(true);

        $row = 4;
        foreach ($this->getCostCenters() as $costCenter) {
            $dataRow = [$costCenter->name];
            foreach ($providerHeaders as $header) {
                $value = $this->getGrossPremium($costCenter->cost_center_id, $header);
                $dataRow[] = $value == 0 ? '-' : number_format($value, 2, '.', ',');
            }
            $dataRow[] = number_format($this->getTotalGrossPremium($costCenter->cost_center_id), 2, '.', ',');
            $sheet->fromArray($dataRow, NULL, "A$row");
            $row++;
        }
    
        $totalRow = ['TOTAL'];
        foreach ($providerHeaders as $header) {
            $total = $this->getTotalForHeader($header);
            $totalRow[] = $total == 0 ? '-' : number_format($total, 2, '.', ',');
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
                'Content-Disposition' => 'attachment; filename="summary-report.xlsx"',
            ]
        );
    }
}
