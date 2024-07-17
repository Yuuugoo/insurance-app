<?php

namespace App\Livewire;

use App\Models\Report;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarChart extends ChartWidget
{
    
    protected static ?string $heading = 'Recent Month'; 


    protected function getData(): array
    {
        $data = Report::select(
            DB::raw('DAY(created_at) as day'),
            DB::raw('count(*) as total')
        )
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->groupBy('day')
        ->get()
        ->toArray();
    
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, now()->month, now()->year);
        $labels = range(1, $daysInMonth);
    
        $dataset = array_fill(0, $daysInMonth, 0);
        foreach ($data as $item) {
            $dataset[$item['day'] - 1] = $item['total'];
        }
    
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Reports',
                    'data' => $dataset,
                    'backgroundColor' => '#000000',
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
