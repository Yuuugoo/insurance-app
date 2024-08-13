<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class PerSalespersonPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'REPORTS';
    protected static string $view = 'filament.pages.per-salesperson-page';

    public function getTitle(): string|Htmlable
    {
        return 'Reports per Salesperson';
    }
}
