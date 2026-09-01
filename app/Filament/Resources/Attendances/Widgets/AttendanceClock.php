<?php

namespace App\Filament\Resources\Attendances\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class AttendanceClock extends Widget
{
    protected string $view = 'filament.resources.attendances.widgets.attendance-clock';

    protected int|string|array $columnSpan = 'full';

    public function clockIn(): void
    {
        if (auth()->user()?->role !== 'user') {
            return;
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'in' => now(),
            'out' => null,
            'work_content' => null,
        ]);

        $this->dispatch('$refresh');
    }

    public function clockOut(): void
    {
        if (auth()->user()?->role !== 'user') {
            return;
        }

        $attendance = Attendance::query()
            ->where('user_id', auth()->id())
            ->whereNull('out')
            ->latest('in')
            ->first();

        if (! $attendance) {
            return;
        }

        $attendance->out = now();
        $attendance->save();

        $this->dispatch('$refresh');
    }

    public function getViewData(): array
    {
        $today = now()->startOfDay();

        $todayAttendance = Attendance::query()
            ->where('user_id', auth()->id())
            ->whereDate('in', $today)
            ->latest('in')
            ->first();

        $isWorking = $todayAttendance && $todayAttendance->out === null;

        $timeText = '';

        if ($todayAttendance) {
            $in = Carbon::parse($todayAttendance->in)->format('H:i');

            $out = $todayAttendance->out
                ? Carbon::parse($todayAttendance->out)->format('H:i')
                : '勤務中';

            $timeText = $in . ' ～ ' . $out;
        }

        return [
            'today' => $today,
            'isWorking' => $isWorking,
            'timeText' => $timeText,
        ];
    }
}
