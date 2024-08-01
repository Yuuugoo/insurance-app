<?php

namespace App\Filament\Pages;

use App\Models\User;
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
use Filament\Forms\Get;
use Illuminate\Support\Facades\Log;

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
                            ->inlineLabel()
                            ->label('Full Name')
                            ->ReadOnly()
                            ->maxLength(255),
                        TextInput::make('username')
                            ->inlineLabel()
                            ->label('Username')
                            ->readOnly(),
                        TextInput::make('email')
                            ->inlineLabel()
                            ->email()
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        FileUpload::make('avatar_url')
                            ->inlineLabel()
                            ->image()
                            ->avatar()
                            ->label('Change Profile Picture')
                            ->imageEditor()
                            ->directory(function () {
                                $userId = auth()->id(); 
                                return "uploads/users/{$userId}/avatars";
                            }),
                        Section::make()
                            ->heading('Change Password')
                            ->schema([
                            TextInput::make('current_password')
                                ->password()
                                ->label('Enter Current Password')
                                ->revealable()
                                ->dehydrated(false)
                                ->reactive()
                                ->rule(function () {
                                    return function ($value, $fail) {
                                        $user = auth()->user();
                                        if (!$user || hash('sha512', $value) !== $user->password) {
                                            $fail('The current password is incorrect.');
                                        }
                                    };
                                }),
                            TextInput::make('password')
                                ->password()
                                ->label('Enter New Password')
                                ->revealable()
                                ->reactive()
                                ->hidden(fn (Get $get) => ($get('current_password')) === null)
                                ->required()
                                ->dehydrated(fn ($state) => filled($state))
                                ->rules([
                                    'min:8',
                                    'different:current_password',
                                ]),
                        ]),
                ])->columns(2)
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

        if (isset($data['password']) && filled($data['password'])) {
            $updateData['password'] = hash('sha512', $data['password']);
            $passwordChanged = true;
        }

        if (isset($data['avatar_url'])) {
            $updateData['avatar_url'] = asset('storage/' . $data['avatar_url']);
        }

        $user->update($updateData);

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();

        if ($passwordChanged) {
            Auth::logoutCurrentDevice();
            Session::flush();
            return $this->redirectToLogin();
        }

        return redirect()->to(EditProfile::getUrl());
    }

}
