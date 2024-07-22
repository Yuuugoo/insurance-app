<?php

namespace App\Livewire;

use App\Models\Report;
use App\Enums\CostCenter;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;



class PreviousMonth extends ChartWidget
{
    protected static ?string $heading = 'Previous Month';

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        return array_combine(
            array_map(fn($case) => $case->value, CostCenter::cases()),
            array_map(fn($case) => $case->value, CostCenter::cases())
        );
    }

    protected function getFilterForm(): array
    {
        return [
            Select::make('filter')
                ->label('Cost Center')
                ->options($this->getFilters())
                ->placeholder('All Cost Centers')
        ];
    }

    protected function getData(): array
    {
        $previousMonth = now()->subMonth();
        $daysInPreviousMonth = $previousMonth->daysInMonth;
        $labels = range(1, $daysInPreviousMonth);

        $costCenters = $this->filter
            ? [CostCenter::from($this->filter)]
            : CostCenter::cases();

        $datasets = [];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

        foreach ($costCenters as $index => $costCenter) {
            $data = Report::select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('count(*) as total')
            )
            ->whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->where('cost_center', $costCenter->value)
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

            $dataset = array_fill(0, $daysInPreviousMonth, 0);
            foreach ($data as $day => $total) {
                $dataset[$day - 1] = $total;
            }

            $color = $colors[$index % count($colors)];
            $datasets[] = [
                'label' => $costCenter->value,
                'data' => $dataset,
                'backgroundColor' => $this->filter === $costCenter->value ? $color : $this->adjustBrightness($color, 0.3),
                'borderColor' => $color,
                'borderWidth' => $this->filter === $costCenter->value ? 2 : 1,
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