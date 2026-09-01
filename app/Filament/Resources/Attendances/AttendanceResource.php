<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Resources\Attendances\Schemas\AttendanceForm;
use App\Filament\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendance;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    public static function getNavigationParentItem(): ?string
    {
        return 'マイページ';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        $panelId = Filament::getCurrentPanel()?->getId();

        /*
         * /admin
         *
         * adminのみ。
         * 通常は全員分の勤怠を見る。
         *
         * user_id が指定された場合は、
         * そのユーザーの勤怠だけを見る。
         */
        if ($panelId === 'admin') {

            if ($user->role !== 'admin') {
                return $query->whereRaw('1 = 0');
            }

            $targetUserId = request()->query('user_id');

            if ($targetUserId) {
                return $query->where('user_id', $targetUserId);
            }

            return $query;
        }

        /*
         * /manage
         *
         * manager / admin がアクセス可能。
         */
        if ($panelId === 'manage') {

            if (! in_array($user->role, ['manager', 'admin'], true)) {
                return $query->whereRaw('1 = 0');
            }

            $targetUserId = request()->query('user_id');

            /*
             * admin
             */
            if ($user->role === 'admin') {

                if ($targetUserId) {
                    return $query->where('user_id', $targetUserId);
                }

                return $query;
            }

            /*
             * manager
             */
            if (! $user->group_id) {
                return $query->whereRaw('1 = 0');
            }

            /*
             * 特定ユーザーの勤怠
             */
            if ($targetUserId) {
                return $query
                    ->where('user_id', $targetUserId)
                    ->whereHas('user', function (Builder $q) use ($user) {
                        $q->where('group_id', $user->group_id);
                    });
            }

            /*
             * グループ全員の勤怠
             */
            return $query->whereHas('user', function (Builder $q) use ($user) {
                $q->where('group_id', $user->group_id);
            });
        }

        /*
         * /mypage
         *
         * 自分自身の勤怠だけ。
         */
        if ($panelId === 'user') {
            return $query->where('user_id', $user->id);
        }

        /*
         * その他のPanelからは表示させない。
         */
        return $query->whereRaw('1 = 0');
    }

    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return '勤怠一覧';
    }

    public static function getModelLabel(): string
    {
        return '勤怠';
    }

    public static function getPluralModelLabel(): string
    {
        return '勤怠一覧';
    }
}