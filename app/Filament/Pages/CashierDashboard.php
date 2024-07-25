<?php

namespace App\Filament\Pages;


use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;

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
        return ('Cashier Dashboard');
    }

}
