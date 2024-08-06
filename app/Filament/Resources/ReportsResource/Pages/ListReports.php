<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReportsResource;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\ReportsResource\Widgets\ReportsOverview;
use App\Filament\Resources\ReportsResource\Widgets\ReportsStatsOverview;

class ListReports extends ListRecords
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Report')
                ->successNotification(
                    Notification::make()
                         ->success()
                         ->title('Report Created')
                         ->body('The report has been created successfully.'),
                 ),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ReportsStatsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'pending')),
            'paid' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'paid')),
            // 'aurora' => Tab::make()->query(fn ($query) => $query->where('cost_center', 'aurora')),
            // 'fairview' => Tab::make()->query(fn ($query) => $query->where('cost_center', 'fairview')),
            // 'new' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'pending')),
            // 'paid' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'paid')),
            // 'new' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'pending')),
            // 'paid' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'paid')),
            // 'new' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'pending')),
            // 'paid' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'paid')),
        ];
    }
    
}
