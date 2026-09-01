<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Pages\Page;

class AttendanceDashboard extends Page
{
    protected static ?string $title = '勤怠管理';

    protected static ?string $navigationLabel = '勤怠管理';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return '';
    }

    public function mount(): void
    {
        $this->redirect(
            AttendanceResource::getUrl(
                'index',
                panel: 'user'
            )
        );
    }

    protected string $view = 'filament.pages.attendance-dashboard';
}
