<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ReportsResource\Widgets\ReportsStatsOverview;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;

class StaffDashboard extends Page
{
    
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.staff-dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = 2;
    
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('acct-staff');
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            ReportsStatsOverview::class
        ];
    }
}
