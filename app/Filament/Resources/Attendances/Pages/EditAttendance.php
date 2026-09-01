<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => auth()->user()?->role === 'admin'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        if (
            request()->headers->get('referer') &&
            str_contains(
                request()->headers->get('referer'),
                '/mypage'
            )
        ) {
            return [
                url('/mypage') => 'マイページ',
                '編集',
            ];
        }

        return [
            url('/manage') =>__('messages.gmanage'),
            $this->getResource()::getUrl('index') => '勤怠一覧',
            '編集',
        ];
    }

    public function form(Schema $schema): Schema
    {
        $schema = parent::form($schema);

        /*
         * adminは全員の勤怠を編集可能。
         *
         * admin以外は自分の勤怠だけ編集可能。
         * 他人の勤怠は閲覧のみ。
         */
        if (
            auth()->user()?->role !== 'admin'
            && $this->record->user_id !== auth()->id()
        ) {
            $schema = $schema->disabled();
        }

        return $schema;
    }

    protected function getFormActions(): array
    {
        /*
         * admin、または自分の勤怠なら更新可能。
         *
         * 他人の勤怠は更新ボタンを出さない。
         */
        if (
            auth()->user()?->role === 'admin'
            || $this->record->user_id === auth()->id()
        ) {
            return parent::getFormActions();
        }

        return [];
    }
}
