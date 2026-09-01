<?php

namespace App\Filament\Http\Responses;

use Filament\Facades\Filament;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        if (auth()->user()?->role === 'admin') {
            return redirect('/admin/');
        }

        return redirect('/mypage/clock-in');
    }
}
