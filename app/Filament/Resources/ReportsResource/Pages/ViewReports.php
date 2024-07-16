<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Filament\Resources\ReportsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Report;
use Filament\Actions\EditAction;

class ViewReports extends ViewRecord
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->color('warning')
                ->action(function () {
            
                    return redirect('/reports');
                }),
            EditAction::make('edit')
                ->label('Edit this Report'),
            Action::make('pdf')
                ->label('Export to PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (Report $record) => route('pdf', $record))
                ->openUrlInNewTab(),
        ];
    }
}
