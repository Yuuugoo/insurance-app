<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Report;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyReportsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Reports';

    protected function getData(): array
    {
        // Get the current year
        $year = now()->year;

        // Retrieve the monthly report data from the database
        $reportData = Report::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->get();

        // Format the data for the chart
        $data = [];
        $labels = [];

        for ($i = 1; $i <= 12; $i++) {
            $count = $reportData->where('month', $i)->first()->count ?? 0;
            $data[] = $count;
            $labels[] = Carbon::create($year, $i)->format('M');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Reports created',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

