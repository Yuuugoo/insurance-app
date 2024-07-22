<?php

namespace App\Livewire;

use App\Models\Report;
use App\Enums\CostCenter;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarChart extends ChartWidget
{
    protected static ?string $heading = 'Reports by Month';

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All',
            ...$this->getCostCenterFilters(),
            ...$this->getMonthFilters(),
        ];
    }

    protected function getCostCenterFilters(): array
    {
        $filters = [];
        foreach (CostCenter::cases() as $case) {
            $filters['cc_' . $case->value] = 'CC: ' . $case->value;
        }
        return $filters;
    }

    protected function getMonthFilters(): array
    {
        $filters = [];
        $currentMonth = Carbon::now();
        for ($i = 0; $i < 12; $i++) {
            $month = $currentMonth->copy()->subMonths($i);
            $filters['month_' . $month->format('Y-m')] = $month->format('F Y');
        }
        return $filters;
    }

    protected function getData(): array
    {
        $selectedDate = now();
        $costCenters = CostCenter::cases();

        if ($this->filter) {
            if (str_starts_with($this->filter, 'cc_')) {
                $costCenters = [CostCenter::from(substr($this->filter, 3))];
            } elseif (str_starts_with($this->filter, 'month_')) {
                $selectedDate = Carbon::createFromFormat('Y-m', substr($this->filter, 6));
            }
        }

        $daysInMonth = $selectedDate->daysInMonth;
        $labels = range(1, $daysInMonth);

        $datasets = [];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

        foreach ($costCenters as $index => $costCenter) {
            $data = Report::select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('count(*) as total')
            )
                ->whereMonth('created_at', $selectedDate->month)
                ->whereYear('created_at', $selectedDate->year)
                ->where('cost_center', $costCenter->value)
                ->groupBy('day')
                ->pluck('total', 'day')
                ->toArray();

            $dataset = array_fill(0, $daysInMonth, 0);
            foreach ($data as $day => $total) {
                $dataset[$day - 1] = $total;
            }

            $color = $colors[$index % count($colors)];
            $datasets[] = [
                'label' => $costCenter->value,
                'data' => $dataset,
                'backgroundColor' => $this->filter === 'cc_' . $costCenter->value ? $color : $this->adjustBrightness($color, 0.3),
                'borderColor' => $color,
                'borderWidth' => $this->filter === 'cc_' . $costCenter->value ? 2 : 1,
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function adjustBrightness($hex, $factor)
    {
        $rgb = sscanf($hex, "#%02x%02x%02x");
        $rgb = array_map(function($color) use ($factor) {
            return max(0, min(255, $color * $factor));
        }, $rgb);
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }
}