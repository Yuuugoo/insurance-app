<?php

namespace App\Filament\Pages;

use App\Models\Report;
use Filament\Pages\Page;
use App\Models\CostCenter;
use App\Models\InsuranceType;
use Illuminate\Support\Carbon;
use App\Models\InsuranceProvider;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SummaryReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.summary-report';

    public $selectedMonth;

    public function mount()
    {
        $this->selectedMonth = request()->query('selected_month', Carbon::now()->format('Y-m'));
    }

    public function resetFilters()
    {
        $this->selectedMonth = null;
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

        if ($this->selectedMonth) {
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

        if ($this->selectedMonth) {
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
        return Carbon::parse($this->selectedMonth)->startOfMonth();
    }

    public function getEndDate()
    {
        return Carbon::parse($this->selectedMonth)->endOfMonth();
    }

    public function export(Request $request)
{
    // Retrieve the selected month from the request
    $selectedMonth = $request->query('selected_month', Carbon::now()->format('Y-m'));
    $this->selectedMonth = $selectedMonth;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(20);
    $columnLetters = range('B', 'Z');
    foreach ($columnLetters as $letter) {
        $sheet->getColumnDimension($letter)->setWidth(15);
    }

    // Get dynamic headers
    $providerHeaders = $this->getProviderHeaders();
    $headers = array_merge(['PER BRANCH'], $providerHeaders, ['TOTAL']);

    // Add provider name header
    $provider = $this->getSelectedProvider();
    if ($provider) {
        $sheet->setCellValue('B1', strtoupper($provider->name));
        $sheet->mergeCells('B1:' . $columnLetters[count($providerHeaders)].'1');
        $sheet->getStyle('B1:' . $columnLetters[count($providerHeaders)].'1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B1:' . $columnLetters[count($providerHeaders)].'1')->getFont()->setBold(true);
    }

    // Add sub-headers
    $sheet->fromArray($headers, NULL, 'A2');
    $sheet->getStyle('A2:' . $columnLetters[count($headers)-1] . '2')->getFont()->setBold(true);

    // Add data
    $row = 3;
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

    // Add total row
    $totalRow = ['TOTAL'];
    foreach ($providerHeaders as $header) {
        $total = $this->getTotalForHeader($header);
        $totalRow[] = $total == 0 ? '-' : number_format($total, 2, '.', ',');
    }
    $totalRow[] = number_format($this->getGrandTotal(), 2, '.', ',');
    $sheet->fromArray($totalRow, NULL, "A$row");
    $sheet->getStyle("A$row:" . $columnLetters[count($headers)-1] . $row)->getFont()->setBold(true);

    // Add borders
    $lastColumn = $columnLetters[count($headers)-1];
    $sheet->getStyle("A1:$lastColumn$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // Set number format for numeric cells
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
