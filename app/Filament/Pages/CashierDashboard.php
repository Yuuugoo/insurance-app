<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TotalReports;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;

class CashierDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.cashier-dashboard';

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('cashier');
    }

    protected function getHeaderWidgets(): array
    {
        return[
            AccountWidget::class
        ];
    }


}
