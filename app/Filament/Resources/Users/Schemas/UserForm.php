<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $currentUser = auth()->user();
        $panelId = Filament::getCurrentPanel()?->getId();

        $isManagerPanel = $panelId === 'manage'
            && $currentUser?->role === 'manager';

        return $schema
            ->components([
                TextInput::make('name')
                    ->label('名前')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('メールアドレス')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Select::make('role')
                    ->label('権限')
                    ->options(
                        $isManagerPanel
                            ? [
                                'manager' => 'G管理者',
                                'user' => 'ユーザー',
                            ]
                            : [
                                'admin' => '管理者',
                                'manager' => 'G管理者',
                                'user' => 'ユーザー',
                            ]
                    )
                    ->required(),

                Select::make('group_id')
                    ->label('グループ')
                    ->relationship('group', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled($isManagerPanel)
                    ->dehydrated(true)
                    ->default(
                        $isManagerPanel
                            ? $currentUser?->group_id
                            : null
                    ),

                TextInput::make('password')
                    ->label('パスワード')
                    ->password()
                    ->revealable()
                    ->required(
                        fn (string $operation): bool =>
                            $operation === 'create'
                    )
                    ->dehydrated(
                        fn (string $operation): bool =>
                            $operation === 'create'
                    ),
            ]);
    }
}
