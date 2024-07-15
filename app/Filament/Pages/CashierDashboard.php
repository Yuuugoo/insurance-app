<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ReportsResource\Widgets\MonthlyReportsChart;
use App\Filament\Resources\ReportsResource\Widgets\ReportsChart;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\TotalReports;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\ReportsResource\Widgets\ReportsStatsOverview;
use App\Filament\Resources\ReportsResource\Widgets\ReportsTable;

class CashierDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard.cashier-dashboard';
    
    
    public static function canAccess(): bool    
    {
        return Auth::user()->hasRole('cashier');
    }

    protected function getHeaderWidgets(): array
    {
        return[

        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return ('Cashier Dashboard');
    }

}
