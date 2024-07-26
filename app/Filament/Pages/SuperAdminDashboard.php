<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;

class SuperAdminDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Admin Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard.super-admin-dashboard';

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('super-admin');
    }

    public function getTitle(): string|Htmlable
    {
        return ('');
    }
}
