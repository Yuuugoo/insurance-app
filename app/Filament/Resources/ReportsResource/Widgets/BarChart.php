<?php

namespace App\Filament\Resources\ReportsResource\Widgets;

use App\Models\Report;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BarChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
{
    $data = Report::select('insurance_prod', DB::raw('count(*) as total'))
        ->groupBy('insurance_prod')
        ->get()
        ->toArray();

    return [
        'labels' => array_column($data, 'insurance_prod'),
        'datasets' => [
            [
                'label' => 'Reports',
                'data' => array_column($data, 'total'),
                'backgroundColor' => '#22c55e',
            ],
        ],
    ];
}

    protected function getType(): string
    {
        return 'bar';
    }
}
