<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
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

class PerMonthPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'SUMMARY';
    protected static string $view = 'filament.pages.per-month-page';
    protected static ?string $navigationLabel = 'Per Month';


    public function getTitle(): string|Htmlable
    {
        return 'Reports Per Month';
    }

    public $year;
    public $selectedProvider;
    public $selectedCostCenter;

    public function mount()
    {
        $this->year = request()->query('year', Carbon::now()->year);
        $this->selectedProvider = request()->query('provider');
        $this->selectedCostCenter = request()->query('cost_center');
    }

    public function resetFilters()
    {
        $this->year = Carbon::now()->year;
        $this->selectedProvider = null;
        $this->selectedCostCenter = null;
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

    public function getMonthlyGrossPremium($month, $insuranceTypeId)
    {
        $query = Report::query();

        if ($this->selectedProvider) {
            $query->where('report_insurance_prod_id', $this->selectedProvider);
        }

        if ($this->selectedCostCenter) {
            $query->where('report_cost_center_id', $this->selectedCostCenter);
        }

        $startDate = Carbon::create($this->year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query->whereBetween('arpr_date', [$startDate, $endDate]);
        $query->where('report_insurance_type_id', $insuranceTypeId);

        return $query->sum('gross_premium');
    }

    public function getTotalForMonth($month)
    {
        $query = Report::query();

        if ($this->selectedProvider) {
            $query->where('report_insurance_prod_id', $this->selectedProvider);
        }

        if ($this->selectedCostCenter) {
            $query->where('report_cost_center_id', $this->selectedCostCenter);
        }

        $startDate = Carbon::create($this->year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query->whereBetween('arpr_date', [$startDate, $endDate]);

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

        $startDate = Carbon::create($this->year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        $query->whereBetween('arpr_date', [$startDate, $endDate]);
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

        $startDate = Carbon::create($this->year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        $query->whereBetween('arpr_date', [$startDate, $endDate]);

        return $query->sum('gross_premium');
    }

    public function export(Request $request)
    {
        $year = $request->query('year', Carbon::now()->year);
        $providerId = $request->query('provider');
        $costCenterId = $request->query('cost_center');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $provider = $this->getSelectedProvider();
        $costCenter = $costCenterId ? CostCenter::find($costCenterId) : null;
        $currentYear = Carbon::now()->year;

        $providerName = $provider ? strtoupper($provider->name) : "ALL PROVIDERS";
        $costCenterName = $costCenter ? strtoupper($costCenter->name) : "ALL COST CENTERS";

        $title = "$providerName - $costCenterName - $currentYear";

        $headers = ['MONTHS'];
        foreach ($this->getInsuranceTypes() as $type) {
            $headers[] = strtoupper($provider ? $provider->name . ' ' . $type->name : $type->name);
        }
        $headers[] = 'TOTAL';

        $lastColumn = chr(64 + count($headers));

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true);

        $sheet->fromArray($headers, NULL, 'A2');
        $sheet->getStyle("A2:{$lastColumn}2")->getFont()->setBold(true);

        $sheet->getColumnDimension('A')->setWidth(20);
        foreach (range('B', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setWidth(15);
        }

        $this->year = $year;
        $this->selectedProvider = $providerId;
        $this->selectedCostCenter = $costCenterId;

        $row = 3;
        $totalRow = array_fill(0, count($headers), 0);
        $totalRow[0] = 'TOTAL';

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        foreach ($months as $index => $month) {
            $monthData = [$month];
            $monthTotal = 0;

            foreach ($this->getInsuranceTypes() as $typeIndex => $insuranceType) {
                $grossPremium = $this->getMonthlyGrossPremium($index + 1, $insuranceType->insurance_type_id);
                $monthData[] = $grossPremium ?: '-';
                $monthTotal += $grossPremium;
                $totalRow[$typeIndex + 1] += $grossPremium;
            }

            $monthData[] = $monthTotal ?: '-';
            $totalRow[count($headers) - 1] += $monthTotal;

            $sheet->fromArray($monthData, NULL, "A$row");
            $row++;
        }

        $sheet->fromArray($totalRow, NULL, "A$row");
        $sheet->getStyle("A$row:{$lastColumn}$row")->getFont()->setBold(true);

        $sheet->getStyle("B3:{$lastColumn}$row")->getNumberFormat()->setFormatCode('#,##0.00');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle("A1:{$lastColumn}$row")->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="per-month-report.xlsx"',
            ]
        );
    }
}
