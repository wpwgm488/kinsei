<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('名前')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('メールアドレス')
                    ->searchable(),

                TextColumn::make('role')
                    ->label('権限')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => '管理者',
                        'manager' => 'G管理者',
                        'user' => 'ユーザー',
                        default => $state,
                    }),

                TextColumn::make('group.name')
                    ->label('グループ')
                    ->searchable()
                    ->sortable()
                    ->placeholder('未所属'),

                TextColumn::make('created_at')
                    ->label('登録日時')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])

            // ユーザーの行をクリックすると、そのユーザーの勤怠一覧へ
            ->recordUrl(
                fn (User $record): string => AttendanceResource::getUrl(
                    'index',
                    [
                        'user_id' => $record->id,
                    ],
                )
            )

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
