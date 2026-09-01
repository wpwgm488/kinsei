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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

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

        if ($panelId === 'admin') {
            return $user->role === 'admin'
                ? $query
                : $query->whereRaw('1 = 0');
        }

        if ($panelId === 'user') {
            return $query->where('id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'admin'
            && Filament::getCurrentPanel()?->getId() === 'admin';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role === 'admin'
            && Filament::getCurrentPanel()?->getId() === 'admin';
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
        return 'ユーザー管理';
    }

    public static function getModelLabel(): string
    {
        return 'ユーザー';
    }

    public static function getPluralModelLabel(): string
    {
        return 'ユーザー管理';
    }
}
