<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;

class ManagerDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.manager-dashboard';

    

    public static function canAccess(): bool
    {       
        $user = Auth::user();
        return $user->hasRole('acct-manager');
    }

    protected function getHeaderWidgets(): array
    {
        return[
            AccountWidget::class
        ];
    }
}
