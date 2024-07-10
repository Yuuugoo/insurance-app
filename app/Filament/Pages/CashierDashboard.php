<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class CashierDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.cashier-dashboard';

    protected function getHeaderWidgets(): array
    {
        return[
            AccountWidget::class
        ];
    }

}
