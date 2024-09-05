<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReportsResource;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\ReportsResource\Widgets\ReportsOverview;
use App\Filament\Resources\ReportsResource\Widgets\ReportsStatsOverview;
use App\Livewire\ReportStatusWidget;
use Carbon\Carbon;

class ListReports extends ListRecords
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('aap-blue')
                ->label('Create Report')
                ->successNotification(
                    Notification::make()
                         ->success()
                         ->title('Report Created')
                         ->body('The report has been created successfully.'),
                 ),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // ReportsStatsOverview::class,
            // ReportStatusWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $today = Carbon::now()->startOfDay();
        $tomorrow = Carbon::now()->endOfDay();

        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'pending')),
            'paid' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'paid')),
            'due-today' => Tab::make('Due Today')->query(function ($query) use ($today, $tomorrow) {
                $query->where(function ($q) use ($today, $tomorrow) {
                    $q->whereBetween('1st_payment_date', [$today, $tomorrow])
                      ->where('1st_is_paid', 0)
                    ->orWhereBetween('2nd_payment_date', [$today, $tomorrow])
                      ->where('2nd_is_paid', 0)
                    ->orWhereBetween('3rd_payment_date', [$today, $tomorrow])
                      ->where('3rd_is_paid', 0)
                    ->orWhereBetween('4th_payment_date', [$today, $tomorrow])
                      ->where('4th_is_paid', 0)
                    ->orWhereBetween('5th_payment_date', [$today, $tomorrow])
                      ->where('5th_is_paid', 0)
                    ->orWhereBetween('6th_payment_date', [$today, $tomorrow])
                      ->where('6th_is_paid', 0);
                });
            }),
            'overdue' => Tab::make('Overdue')->query(function ($query) use ($today) {
                $query->where(function ($q) use ($today) {
                    $q->where('1st_payment_date', '<', $today)
                      ->where('1st_is_paid', 0)
                    ->orWhere('2nd_payment_date', '<', $today)
                      ->where('2nd_is_paid', 0)
                    ->orWhere('3rd_payment_date', '<', $today)
                      ->where('3rd_is_paid', 0)
                    ->orWhere('4th_payment_date', '<', $today)
                      ->where('4th_is_paid', 0)
                    ->orWhere('5th_payment_date', '<', $today)
                      ->where('5th_is_paid', 0)
                    ->orWhere('6th_payment_date', '<', $today)
                      ->where('6th_is_paid', 0);
                });
            }),
            
        ];
    }
}
