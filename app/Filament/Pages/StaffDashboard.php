<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\ReportsResource\Widgets\ReportsStatsOverview;

class StaffDashboard extends Page
{
    
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard.staff-dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = 2;
    
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('acct-staff');
    }
    
    public function getTitle(): string|Htmlable
    {
        return ('Accounting Staff Dashboard');
    }
}
