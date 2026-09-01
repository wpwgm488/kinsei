<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ClockIn extends Page
{
    protected static ?string $title = '出勤・退勤';

    protected static ?string $navigationLabel = '出勤・退勤';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected string $view = 'filament.pages.clock-in';

    public function currentAttendance(): ?Attendance
    {
        $attendance = Attendance::query()
            ->where('user_id', auth()->id())
            ->whereNull('out')
            ->latest('in')
            ->first();

        if (
            $attendance
            && $attendance->break_started_at
            && $attendance->break_ended_at
            && now()->gte($attendance->break_ended_at)
        ) {
            $attendance->break_time = 1.00;
            $attendance->save();
        }

        return $attendance;
    }

    public function hasTodayAttendance(): bool
    {
        return Attendance::query()
            ->where('user_id', auth()->id())
            ->whereDate('in', now())
            ->exists();
    }

    public function hasOpenAttendance(): bool
    {
        return $this->currentAttendance() !== null;
    }

    public function canClockIn(): bool
    {
        return ! $this->hasOpenAttendance();
    }

    public function canClockOut(): bool
    {
        return $this->hasOpenAttendance();
    }

    public function isOnBreak(): bool
    {
        $attendance = $this->currentAttendance();

        return $attendance
            && $attendance->break_started_at
            && $attendance->break_ended_at
            && now()->lt($attendance->break_ended_at);
    }

    public function canStartBreak(): bool
    {
        $attendance = $this->currentAttendance();

        return $attendance
            && ! $attendance->break_started_at
            && ! $attendance->break_ended_at;
    }

    public function clockIn(): void
    {
        if (! $this->canClockIn()) {
            Notification::make()
                ->title('すでに出勤しています')
                ->warning()
                ->send();

            return;
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'in' => now(),
            'out' => null,
            'work_content' => null,
        ]);

        Notification::make()
            ->title('出勤しました')
            ->success()
            ->send();
    }

    public function startBreak(): void
    {
        $attendance = $this->currentAttendance();

        if (! $attendance || ! $this->canStartBreak()) {
            return;
        }

        $attendance->break_started_at = now();
        $attendance->break_ended_at = now()->addHour();
        $attendance->break_time = 1.00;
        $attendance->save();

        Notification::make()
            ->title('休憩を開始しました')
            ->success()
            ->send();
    }

    public function clockOut(): void
    {
        $attendance = $this->currentAttendance();

        if (! $attendance) {
            Notification::make()
                ->title('出勤していません')
                ->warning()
                ->send();

            return;
        }

        $attendance->out = now();

        if ($attendance->break_started_at && ! $attendance->break_ended_at) {
            $attendance->break_ended_at = $attendance->out;
        }

        if (
            $attendance->break_started_at &&
            $attendance->break_ended_at &&
            $attendance->break_ended_at->gt($attendance->out)
        ) {
            $attendance->break_ended_at = $attendance->out;
        }

        $attendance->save();

        Notification::make()
            ->title('退勤しました')
            ->success()
            ->send();
    }
}
