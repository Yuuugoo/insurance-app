<?php

namespace App\Filament\Pages;

use App\Livewire\AccountDashboardWidget;
use App\Livewire\ReportStats;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Console\View\Components\Info;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

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

    public function getTitle(): string|Htmlable
    {
        return ('');
    }


}
