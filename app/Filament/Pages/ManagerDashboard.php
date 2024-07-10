<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class ManagerDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.manager-dashboard';

    protected function getHeaderWidgets(): array
    {
        return[
            AccountWidget::class
        ];
    }
}
