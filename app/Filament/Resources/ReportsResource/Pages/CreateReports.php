<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ReportsResource;


class CreateReports extends CreateRecord
{
    protected static string $resource = ReportsResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // Send Notifications to other users after creation of record
    protected function afterCreate():void {

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['acct-staff', 'acct-manager']);
        })->where('id', '!=', auth()->id())->get();
        
        Notification::make()
            ->title('New Insurance Report Submitted')
            ->body("<strong>" . Auth::user()->name . "</strong> submitted a new Insurance Report!")
            ->icon('heroicon-o-folder')
            ->actions([
                Action::make('view')
                    ->button()
                    ->url(fn () => route('filament.admin.resources.reports.view', $this->record)),
                Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->markAsRead(),
            ])
            ->sendToDatabase($users);
            
    }

}

