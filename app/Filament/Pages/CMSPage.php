<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class CMSPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'SETTINGS';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.c-m-s-page';
    protected static ?string $navigationLabel = 'CMS';

    public function getTitle(): string|Htmlable
    {
        return 'CMS';
    }

    public static function canAccess(): bool
    {       
        $user = Auth::user();
        return $user->hasRole(['acct-staff','acct-manager']);
    }
}
