<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ClockIn extends Page
{
    protected string $view = 'filament.pages.clock-in';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static bool $shouldRegisterNavigation = false;

    /**
     * 今日の勤怠一覧
     */
    public function getTodayAttendances()
    {
        return Attendance::where('user_id', auth()->id())
            ->whereDate('in', today())
            ->orderBy('in')
            ->get();
    }

    /**
     * 現在、未退勤の勤怠があるか
     */
    public function getCurrentAttendance(): ?Attendance
    {
        return Attendance::where('user_id', auth()->id())
            ->whereNull('out')
            ->latest('in')
            ->first();
    }

    /**
     * 出勤できるか
     *
     * 未退勤の勤怠がなければ出勤可能。
     * つまり、退勤後は再出勤できる。
     */
    public function canClockIn(): bool
    {
        return $this->getCurrentAttendance() === null;
    }

    /**
     * 退勤できるか
     */
    public function canClockOut(): bool
    {
        return $this->getCurrentAttendance() !== null;
    }

    /**
     * 出勤
     */
    public function clockIn(): void
    {
        // 未退勤の勤怠があるなら二重出勤を防ぐ
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
        ]);

        Notification::make()
            ->title(__('messages.clocked_in'))
            ->success()
            ->send();

        $this->redirect(request()->header('Referer') ?? '/');
    }

    /**
     * 退勤
     */
    public function clockOut(): void
    {
        $attendance = $this->getCurrentAttendance();

        // 出勤していない場合は退勤できない
        if (! $attendance) {
            Notification::make()
                ->title('出勤していません')
                ->warning()
                ->send();

            return;
        }

        $attendance->update([
            'out' => now(),
        ]);

        Notification::make()
            ->title(__('messages.clocked_out'))
            ->success()
            ->send();

        $this->redirect(request()->header('Referer') ?? '/');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.clock_in');
    }
}
