<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getRedirectUrl(): string
    {
        return auth()->user()?->role === 'admin'
            ? '/admin'
            : '/mypage';
    }
}
