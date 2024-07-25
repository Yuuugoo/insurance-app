<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;

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
                Section::make()
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
                            ->required()
                            ->dehydrated(false)
                            ->rule(function () {
                                return function ($attribute, $value, $fail) {
                                    $user = auth()->user();
                                    if (!$user || hash('sha512', $value) !== $user->password) {
                                        $fail('The current password is incorrect.');
                                    }
                                };
                            }),
                        TextInput::make('password')
                            ->password()
                            ->label('New Password')
                            ->dehydrated(fn ($state) => filled($state))
                            ->rules([
                                'nullable',
                                'min:8',
                                'different:current_password'
                            ]),
                        
                        // FileUpload::make('avatar')
                        //     ->image()
                        //     ->directory('avatars'),
                ])
            ])
            ->statePath('data');
    }
    

    public function submit()
    {
        $data = $this->form->getState();
        
        $user = auth()->user();

        if (!$user) {
            Notification::make()
                ->title('Error: User not authenticated')
                ->danger()
                ->send();
            return $this->redirectToLogin();
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        $passwordChanged = false;
        if (filled($data['password'])) {
            $updateData['password'] = hash('sha512', $data['password']);
            $passwordChanged = true;
        }

        $user->update($updateData);

        Notification::make()
            ->title('Profile updated successfully. Please log in again.')
            ->success()
            ->send();

        if ($passwordChanged) {
            Auth::logoutCurrentDevice();
            Session::flush();
            return $this->redirectToLogin();
        }

        return redirect()->to(EditProfile::getUrl());
    }

    protected function redirectToLogin()
    {
        return redirect()->route('filament.admin.auth.login');
    }

}
