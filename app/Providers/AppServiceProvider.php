<?php

namespace App\Providers;

use App\Filament\Http\Responses\LoginResponse;
use App\Filament\Http\Responses\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            LoginResponseContract::class,
            LoginResponse::class,
        );

        $this->app->bind(
            LogoutResponseContract::class,
            LogoutResponse::class,
        );
    }

    public function boot(): void
    {
        //
    }
}