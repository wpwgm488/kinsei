<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Groups\GroupResource;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;

class AdminDashboard extends Page
{
    protected string $view = 'filament.pages.admin-dashboard';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedShieldCheck;

    protected static ?string $title = '管理者画面';

    protected static ?string $navigationLabel = '管理者画面';

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
        return auth()->user()?->role === 'admin';
    }

    public function getGroupsUrl(): string
    {
        return GroupResource::getUrl('index');
    }
}
