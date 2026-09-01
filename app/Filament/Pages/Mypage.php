<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;

class Mypage extends Page
{
    protected string $view = 'filament.pages.mypage';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedUser;

    protected static ?string $title = 'マイページ';

    protected static ?string $navigationLabel = 'マイページ';

    public static function getSlug(?Panel $panel = null): string
    {
        return '';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getAttendancesUrl(): string
    {
        return AttendanceResource::getUrl('index');
    }
}
