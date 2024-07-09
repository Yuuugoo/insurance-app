<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Settings';
    protected static string $view = 'filament.pages.edit-profile';
}
