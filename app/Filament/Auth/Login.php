<?php
namespace App\Filament\Auth;


use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Auth\Login as AuthLogin;

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
}

