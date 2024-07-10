<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class StaffDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.staff-dashboard';
    protected static ?int $navigationSort = 2;

    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class
        ];
    }
}
