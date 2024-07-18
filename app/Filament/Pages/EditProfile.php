<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = 'Edit Profile';
    protected static ?string $navigationGroup = 'SETTINGS';
    protected static string $view = 'filament.pages.edit-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());


    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                    TextInput::make('name')
                        ->ReadOnly()
                       
                        ->maxLength(255),
                    TextInput::make('username')
                        ->label('Username')
                        ->readOnly(),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique()
                        ->maxLength(255),
                    TextInput::make('current_password')
                        ->password()
                        ->label('Current Password')
                        ->required(fn (string $context): bool => $context === 'edit')
                        ->rule('current_password'),
                    TextInput::make('password')
                        ->password()
                        ->label('New Password')
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create'),
                    
                    // FileUpload::make('avatar')
                    //     ->image()
                    //     ->directory('avatars'),
                ])
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $user = auth()->user();
        $data = $this->form->getState();

        $user->update($data);

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();

        return redirect('/');
    }

}
