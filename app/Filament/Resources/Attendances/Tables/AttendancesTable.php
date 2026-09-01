<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        $currentMonth = request()->query(
            'month',
            now()->format('Y-m')
        );

        try {
            $month = Carbon::createFromFormat('Y-m', $currentMonth);
        } catch (\Exception $e) {
            $month = now();
            $currentMonth = $month->format('Y-m');
        }

        $previousMonth = $month->copy()->subMonth()->format('Y-m');
        $nextMonth = $month->copy()->addMonth()->format('Y-m');

        $query = [];

        if (request()->query('user_id')) {
            $query['user_id'] = request()->query('user_id');
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('in')
                    ->label('勤務日')
                    ->date('Y/m/d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('in_out')
                    ->state(fn ($record) => $record)
                    ->label('出退勤')
                    ->formatStateUsing(
                        fn ($record) => $record && $record->in
                            ? $record->in->format('H:i')
                                . ($record->out
                                    ? ' ~ ' . $record->out->format('H:i')
                                    : '')
                            : ''
                    ),

                Tables\Columns\TextColumn::make('break_time')
                    ->label('休憩')
                    ->formatStateUsing(
                        fn ($state) => $state !== null
                            ? sprintf(
                                '%02d:%02d',
                                floor((float) $state),
                                round(
                                    ((float) $state - floor((float) $state)) * 60
                                )
                            )
                            : ''
                    ),

                Tables\Columns\TextColumn::make('working_hours')
                    ->label('実働')
                    ->formatStateUsing(
                        fn ($state) => $state !== null
                            ? sprintf(
                                '%02d:%02d',
                                floor((float) $state),
                                round(
                                    ((float) $state - floor((float) $state)) * 60
                                )
                            )
                            : ''
                    ),

                Tables\Columns\TextColumn::make('work_content')
                    ->label('作業内容')
                    ->wrap()
                    ->limit(100),
            ])
            ->filters([
                //
            ])
            ->recordUrl(
                fn ($record) => (
                    auth()->user()->role === 'admin'
                    || $record->user_id === auth()->id()
                )
                    ? \App\Filament\Resources\Attendances\AttendanceResource::getUrl(
                        'edit',
                        ['record' => $record]
                    )
                    : null
            )
            ->recordActions([
                EditAction::make()
                    ->visible(
                        fn ($record) =>
                            auth()->user()->role === 'admin'
                            || $record->user_id === auth()->id()
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function getResourceUrl(array $query): string
    {
        return \App\Filament\Resources\Attendances\AttendanceResource::getUrl(
            'index',
            $query
        );
    }
}
