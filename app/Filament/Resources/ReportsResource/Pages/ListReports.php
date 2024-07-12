<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Filament\Resources\ReportsResource;
use App\Filament\Resources\ReportsResource\Widgets\ReportsOverview;
use App\Filament\Resources\ReportsResource\Widgets\ReportsStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Report'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ReportsStatsOverview::class,
        ];
    }
}
