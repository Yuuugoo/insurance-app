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
        if (Auth::user()->hasRole('cashier')) {
            $stats [] = Stat::make('All Reports', Report::all()->count());
            $stats [] = Stat::make('Pending Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());
        }
        elseif (Auth::user()->hasRole('acct-staff')) {
            $stats [] = Stat::make('All Reports', Report::all()->count());
            $stats [] = Stat::make('Pending Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());
        }
        elseif (Auth::user()->hasRole('acct-manager')) {
            $stats [] = Stat::make('All Reports', Report::all()->count());
            $stats [] = Stat::make('Pending Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());
        }
        // For Super-Admin
        elseif (Auth::user()->hasRole('acct-staff')) {
            $stats [] = Stat::make('All Reports', Report::all()->count());
            $stats [] = Stat::make('Pending Reports', Report::where('payment_status', 'pending')->count());
            $stats [] = Stat::make('Paid Reports', Report::where('payment_status', 'paid')->count());
        
        }
        else $stats [] = null;

        return $stats;
    }
}