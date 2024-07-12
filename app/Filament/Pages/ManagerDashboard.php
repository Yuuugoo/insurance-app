<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;

class ManagerDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard.manager-dashboard';

    

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

    public function getTitle(): string|Htmlable
    {
        return ('Accounting Manager Dashboard');
    }
}
