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
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // This Sends Notifications to other users after editing a record
    protected function afterSave():void {

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['cashier', 'acct-staff', 'acct-manager']);
        })->where('id', '!=', auth()->id())->get();


        $arpr_num = $this->record->arpr_num;

        
        Notification::make()
            ->success()
            ->title('Insurance Report Updated')
            ->body("<strong>" . Auth::user()->name . "</strong> edited <strong>Insurance Report $arpr_num</strong>!")
            ->icon('heroicon-o-folder')
            ->actions([
                Action::make('view')
                    ->button()
                    ->url(fn () => route('filament.admin.resources.reports.view', $this->record)),
                Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->markAsRead(),
            ])
            // ->broadcast()
            ->sendToDatabase($users);

            
    }
}
