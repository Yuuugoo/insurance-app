<?php

namespace App\Livewire;

use App\Models\CostCenter;
use App\Models\Report;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class PreviousMonth extends ApexChartWidget
{
    protected static ?string $chartId = 'previousMonthReportsChart';
    protected static ?string $heading = 'Previous Month Reports';

    protected function getOptions(): array
    {
        $data = $this->getData();
        $costCenterName = $this->getCostCenterName();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => [
                    'show' => false
                ],
            ],
            'title' => [
                'text' => $costCenterName,
                'align' => 'center',
                'style' => [
                    'fontSize' => '18px',
                    'fontWeight' => 800,
                    'color' => '#002c69'
                ],
            ],
            'series' => [
                [
                    'name' => 'Reports',
                    'data' => $data['datasets'][0]['data'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
                'labels' => [
                    'style' => [
                        'colors' => '#000000',
                        'fontWeight' => 300,
                    ],
                ],
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'light',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.5,
                    'gradientToColors' => ['#003366'],
                    'inverseColors' => false,
                    'opacityFrom' => 0.8,
                    'opacityTo' => 1,
                    'stops' => [0, 100],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#000000',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'colors' => ['#FFD700'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'columnWidth' => '70%',
                ],
            ],
            'grid' => [
                'borderColor' => '#E0E0E0',
            ],
            'theme' => [
                'mode' => 'light',
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return $this->filterFormData;
    }

    protected function getData(): array
    {
        $filters = $this->getFilters();
        $selectedDate = isset($filters['selectedDate']) ? Carbon::parse($filters['selectedDate']) : now()->subMonth();
        $costCenter = $filters['filter'] ?? 'All';

        if($costCenter === 'All' && Auth::user()->branch_id !== null) {
            $costCenter = Auth::user()->branch_id;
        }

        if ($costCenter !== 'All' || Auth::user()->hasAnyRole(['cashier', 'agent'])) {
            return $this->getDailyDataForCostCenter($selectedDate, $costCenter);
        }

        return $this->getMonthlyDataForAllCostCenters($selectedDate);
    }

    protected function getMonthlyDataForAllCostCenters(Carbon $selectedDate): array
    {
        $costCenters = CostCenter::distinct('name')->pluck('name')->sort()->values();
        $labels = $costCenters->toArray();

        $data = Report::join('cost_centers', 'reports.report_cost_center_id', '=', 'cost_center_id')
            ->select('cost_centers.name', DB::raw('count(*) as total'))
            ->whereRaw("DATE_FORMAT(STR_TO_DATE(arpr_date, '%Y-%m-%d'), '%Y-%m') = ?", [$selectedDate->format('Y-m')])
            ->groupBy('cost_centers.name')
            ->pluck('total', 'name')
            ->toArray();

        $dataset = $costCenters->map(function($cc) use ($data) {
            return $data[$cc] ?? 0;
        })->toArray();

        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Reports',
                    'data' => $dataset,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getDailyDataForCostCenter(Carbon $selectedDate, $costCenter): array
    {
        $daysInMonth = $selectedDate->daysInMonth;
        $labels = range(1, $daysInMonth);

        $data = Report::select(
            DB::raw("DAY(STR_TO_DATE(arpr_date, '%Y-%m-%d')) as day"),
            DB::raw('count(*) as total')
        )
            ->whereRaw("DATE_FORMAT(STR_TO_DATE(arpr_date, '%Y-%m-%d'), '%Y-%m') = ?", [$selectedDate->format('Y-m')])
            ->join('cost_centers', 'reports.report_cost_center_id', '=', 'cost_centers.cost_center_id')
            ->whereRaw('LOWER(report_cost_center_id) = ?', [strtolower($costCenter)])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $dataset = array_fill(0, $daysInMonth, 0);
        foreach ($data as $day => $total) {
            $dataset[$day - 1] = $total;
        }

        $color = '#36A2EB';

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $costCenter,
                    'data' => $dataset,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('selectedDate')
                ->type('month')
                ->reactive(),

            Select::make('filter')
                ->label('Cost Center')
                ->native(false)
                ->hidden(fn () => Auth::user()->hasAnyRole(['cashier', 'agent']))
                ->options(CostCenter::pluck('name', 'cost_center_id')->prepend('All', 'All')->toArray())
                ->default('All')
                ->reactive(),
        ];
    }

    protected function getCostCenterName(): string
    {
        $filters = $this->getFilters();
        $costCenterId = $filters['filter'] ?? 'All';

        if ($costCenterId === 'All' || Auth::user()->branch_id !== null) {
            return CostCenter::where('cost_center_id', Auth::user()->branch_id)->value('name') ?? 'All Branches';
        } else {
            return CostCenter::where('cost_center_id', $costCenterId)->value('name') ?? 'Unknown';
        }
    }

    public function getHeading(): ?string
    {
        $filters = $this->getFilters();
        $selectedDate = isset($filters['selectedDate']) ? Carbon::parse($filters['selectedDate']) : now()->subMonth();

        return "Reports for {$selectedDate->format('F Y')}";
    }
}