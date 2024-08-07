<?php
namespace App\Filament\Auth;

use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Auth\Login as AuthLogin;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\LoginResponse;

class Login extends AuthLogin
{   
    protected static string $view = 'filament.pages.auth.login';

    public function getHeading(): string | Htmlable
    {
        return ('');
    }

    public function getTitle(): string|Htmlable
    {
        return ('AAP Insurance Report Login');
    }

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => 'cashier@admin.com',
            'login' => 'CSH_EC',
            'password' => 'password',
            'remember' => true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // $this->getEmailFormComponent(), 
                $this->getLoginFormComponent(), 
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }
    
    protected function getLoginFormComponent(): Component 
    {
        return TextInput::make('login')
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    } 

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['login'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $formData = $this->form->getState();
        $credentials = $this->getCredentialsFromFormData($formData);

        $user = \App\Models\User::whereRaw('BINARY username = ?', [$credentials['username']])->first();

        if ($user && hash('sha512', $credentials['password']) === $user->password) {
            Auth::login($user, $formData['remember'] ?? false);

            session()->regenerate();

            return app(LoginResponse::class);
        }

        throw ValidationException::withMessages([
            'data.login' => 'Incorrect username or password',
        ]);
    }
}

