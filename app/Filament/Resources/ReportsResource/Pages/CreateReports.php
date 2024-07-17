<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ReportsResource;
use Filament\Notifications\Actions\Action;

class CreateReports extends CreateRecord
{
    protected static string $resource = ReportsResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterCreate():void {

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['acct-staff', 'acct-manager']);
        })->get();
        
        Notification::make()
            ->title('asdasdas')
            ->body("<strong>" . Auth::user()->name . "</strong> submitted a new Insurance Report!")
            ->icon('heroicon-o-folder')
            ->actions([
                Action::make('view')
                    ->button()
                    ->url(fn () => route('filament.admin.resources.reports.view', $this->record)),
            ])
            ->sendToDatabase($users);
            
    }

}

