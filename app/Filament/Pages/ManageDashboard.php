<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Users\UserResource;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;

class ManageDashboard extends Page
{
    protected string $view = 'filament.pages.manage-dashboard';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedUserGroup;

    protected static ?string $title = 'G管理画面';

    protected static ?string $navigationLabel = 'G管理画面';

    public static function getSlug(?Panel $panel = null): string
    {
        return '';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canAccess(): bool
    {
        return in_array(
            auth()->user()?->role,
            ['manager', 'admin'],
            true
        );
    }

    public function getUsersUrl(): string
    {
        return UserResource::getUrl('index');
    }
}
