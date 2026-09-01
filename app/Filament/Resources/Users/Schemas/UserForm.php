<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
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
                    ->options([
                        'admin' => '管理者',
                        'user' => 'ユーザー',
                    ])
                    ->required(),

                Textarea::make('memo')
                    ->label('メモ')
                    ->rows(4)
                    ->columnSpanFull(),

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
