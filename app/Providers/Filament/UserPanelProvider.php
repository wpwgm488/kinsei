<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AttendanceDashboard;
use App\Filament\Pages\ChangePassword;
use App\Filament\Pages\CustomerCreate;
use App\Filament\Pages\ClockIn;
use App\Filament\Pages\Invoice;
use App\Filament\Pages\InvoiceSettings;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('user')
            ->path('mypage')
            ->homeUrl('/')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->viteTheme('resources/css/filament/user/theme.css')
            ->resources([
                \App\Filament\Resources\Attendances\AttendanceResource::class,
            ])
            ->pages([
                ClockIn::class,
                AttendanceDashboard::class,
                Invoice::class,
                InvoiceSettings::class,
                CustomerCreate::class,
                ChangePassword::class,

            ])
            ->userMenuItems([
                'change-password' => Action::make('change-password')
                    ->label('パスワード変更')
                    ->url(fn (): string => ChangePassword::getUrl()),
            ])
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                // AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
