<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // 子項目なのでアイコンなし
    protected static string|BackedEnum|null $navigationIcon = null;

    public static function getNavigationParentItem(): ?string
    {
        return 'G管理画面';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    /**
     * ユーザー一覧の表示範囲
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        $panelId = Filament::getCurrentPanel()?->getId();

        /*
         * /manage
         *
         * manager / admin ともにアクセス可能。
         */
        if ($panelId === 'manage') {

            // manager / admin のみアクセス可能
            if (! in_array($user->role, ['manager', 'admin'], true)) {
                return $query->whereRaw('1 = 0');
            }

            // admin は全ユーザーを表示
            if ($user->role === 'admin') {
                return $query;
            }

            // manager は自分のグループだけ
            if (! $user->group_id) {
                return $query->whereRaw('1 = 0');
            }

            return $query->where('group_id', $user->group_id);
        }

        /*
         * /admin
         *
         * admin は全ユーザーを見ることができる。
         */
        if ($panelId === 'admin') {

            if ($user->role !== 'admin') {
                return $query->whereRaw('1 = 0');
            }

            return $query;
        }

        /*
         * /mypage
         *
         * 自分自身だけ。
         */
        if ($panelId === 'user') {
            return $query->where('id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    /**
     * 作成可能か
     */
    public static function canCreate(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        $panelId = Filament::getCurrentPanel()?->getId();

        /*
         * /manage
         *
         * manager / admin ともに作成可能。
         */
        if ($panelId === 'manage') {
            return in_array($user->role, ['manager', 'admin'], true);
        }

        /*
         * /admin
         *
         * admin のみ作成可能。
         */
        if ($panelId === 'admin') {
            return $user->role === 'admin';
        }

        return false;
    }

    /**
     * 編集可能か
     */
    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        $panelId = Filament::getCurrentPanel()?->getId();

        /*
         * /manage
         */
        if ($panelId === 'manage') {

            // manager / admin のみ
            if (! in_array($user->role, ['manager', 'admin'], true)) {
                return false;
            }

            // admin は全ユーザーを編集可能
            if ($user->role === 'admin') {
                return true;
            }

            // manager は自分のグループだけ
            if (! $user->group_id) {
                return false;
            }

            return $record->group_id === $user->group_id;
        }

        /*
         * /admin
         */
        if ($panelId === 'admin') {
            return $user->role === 'admin';
        }

        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'ユーザー一覧';
    }

    public static function getModelLabel(): string
    {
        return 'ユーザー';
    }

    public static function getPluralModelLabel(): string
    {
        return 'ユーザー一覧';
    }
}
