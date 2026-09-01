<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->default(auth()->id())
                    ->disabledOn('edit'),

                Forms\Components\DateTimePicker::make('in')
                    ->label('出勤')
                    ->required()
                    ->default(now()),

                Forms\Components\DateTimePicker::make('out')
                    ->label('退勤'),

                Forms\Components\Textarea::make('work_content')
                    ->label('作業内容')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}