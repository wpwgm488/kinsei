<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables;

class AttendancesTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('in')
                    ->label('勤務日')
                    ->formatStateUsing(
                        fn ($record) => $record && $record->in
                            ? \Carbon\Carbon::parse($record->in)->format('Y/m/d (D)')
                            : ''
                    ),

                Tables\Columns\TextColumn::make('in_out')
                    ->state(fn ($record) => $record)
                    ->label('出退勤時間')
                    ->formatStateUsing(
                        fn ($record) => $record && $record->in
                            ? \Carbon\Carbon::parse($record->in)->format('H:i')
                                . ($record->out
                                    ? ' ~ ' . \Carbon\Carbon::parse($record->out)->format('H:i')
                                    : '')
                            : ''
                    ),

                Tables\Columns\TextColumn::make('break_period')
                    ->label('休憩時間帯')
                    ->state(fn ($record) => $record)
                    ->formatStateUsing(
                        fn ($record) => $record && $record->break_start
                            ? \Carbon\Carbon::parse($record->break_start)->format('H:i')
                                . ($record->break_end
                                    ? ' ~ ' . \Carbon\Carbon::parse($record->break_end)->format('H:i')
                                    : '')
                            : ''
                    ),

                Tables\Columns\TextColumn::make('break_time')
                    ->label('休憩時間')
                    ->formatStateUsing(
                        fn ($state) => $state !== null
                            ? sprintf(
                                '%02d:%02d',
                                floor((float) $state),
                                round(((float) $state - floor((float) $state)) * 60)
                            )
                            : ''
                    ),

                Tables\Columns\TextColumn::make('working_hours')
                    ->label('勤怠時間')
                    ->formatStateUsing(
                        fn ($state) => $state !== null
                            ? sprintf(
                                '%02d:%02d',
                                floor((float) $state),
                                round(((float) $state - floor((float) $state)) * 60)
                            )
                            : ''
                    ),
                Tables\Columns\TextColumn::make('work_content')
                    ->label('作業内容')
                    ->wrap()
                    ->limit(100
                ),
            ])
            ->filters([
                //
            ])
            ->recordUrl(
                fn ($record) => (auth()->user()->role === 'admin' || $record->user_id === auth()->id())
                    ? \App\Filament\Resources\Attendances\AttendanceResource::getUrl('edit', ['record' => $record])
                    : null
            )
            ->recordActions([
                EditAction::make()->visible(fn ($record) => auth()->user()->role === 'admin' || $record->user_id === auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}