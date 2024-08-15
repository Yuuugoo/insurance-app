<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SummaryReportsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.summary-reports-page';

    public static function canAccess(): bool
    {       
        $user = Auth::user();
        return $user->hasRole('');
    }
}

