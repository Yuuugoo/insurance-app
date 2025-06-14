<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\ReportsResource;
use App\Models\Report;

class EditReports extends EditRecord
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.reports.view', $this->record);
    }

    // This Sends Notifications to other users after editing a record
    protected function afterSave():void {

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['agent', 'cashier', 'acct-staff', 'acct-manager', 'cfo']);
        })->where('id', '!=', auth()->id())->get();


        $arpr_num = $this->record->arpr_num;
        $userRole = Auth::user()->roles->first()->name;
        
        Notification::make()
            ->info()
            ->title('Insurance Report Updated')
            ->body("<strong>" . Auth::user()->name ." ($userRole)". "</strong> updated <strong>Insurance Report $arpr_num</strong>!")
            ->icon('heroicon-o-folder')
            ->actions([
                // Action::make('view')
                //     ->label('View Report')
                //     ->button()
                //     ->url(fn () => route('filament.admin.resources.reports.view', $this->record)),
                Action::make('activities')
                    ->label('View Changes')
                    ->color('aap-blue')
                    ->button()
                    ->url(fn () => route('filament.admin.resources.reports.activities', $this->record)),
                Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->markAsRead(),
            ])
            // ->broadcast()
            ->sendToDatabase($users);

            
    }
}
