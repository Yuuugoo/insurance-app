<?php

namespace App\Filament\Resources\ReportsResource\Widgets;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ReportsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        if (Auth::user()->hasRole('cashier')) {
            $stats [] = Stat::make('Reports This Month', Report::all()->count())
                        ->description('Total')
                        ->chart($this->getReportTrend())
                        ->color('success');
            $stats [] = Stat::make('Pending Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());
            
            return $stats;
        }
        elseif (Auth::user()->hasRole('acct-staff')) {
            $stats [] = Stat::make('All Reports', Report::all()->count());
            $stats [] = Stat::make('Pending Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());
            
            return $stats;
        }
        elseif (Auth::user()->hasRole('acct-manager')) {
            $stats [] = Stat::make('All Reports', Report::all()->count());
            $stats [] = Stat::make('Pending Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());

            return $stats;
        }else{
            return [];
        }


    }

    protected function getReportTrend(): array
    {
        return Report::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', Carbon::now())
            ->groupBy('created_at')
            ->orderBy('created_at')
            ->pluck('count')
            ->toArray();
    }

}