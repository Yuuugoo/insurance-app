<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SummaryReportsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.summary-reports-page';
}
