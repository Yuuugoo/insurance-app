<?php

namespace App\Filament\Resources\ReportsResource\Widgets;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ReportsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [
            Stat::make('All Reports', Report::all()->count()), 
            
        ];
        
        if (Auth::user()->hasRole('acct-staff')) {
          
            $stats [] = Stat::make('Pending Paid Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());
        }

        return $stats;
    }
}