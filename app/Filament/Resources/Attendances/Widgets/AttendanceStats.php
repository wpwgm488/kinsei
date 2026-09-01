<?php

namespace App\Filament\Resources\Attendances\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class AttendanceStats extends Widget
{
    protected string $view = 'filament.resources.attendances.widgets.attendance-stats';

    protected int | string | array $columnSpan = 'full';

    // ListAttendancesでとったrequest
    public ?int $userId = null;
    public string $month;
    public bool $isManage = false;

    public function getViewData(): array
    {
        // dd(request()->is('manage'));
        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $this->month);
        } catch (\Exception $e) {
            $selectedMonth = now();
        }

        $userId = $this->userId ?? auth()->id();

        $attendances = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('in', [
                $selectedMonth->copy()->startOfMonth(),
                $selectedMonth->copy()->endOfMonth(),
            ])
            ->get();

        $workingHours = $attendances->sum('working_hours');
        $breakHours = $attendances->sum('break_time');

        return [
            'workingHours' => sprintf(
                '%02d:%02d',
                floor((float) $workingHours),
                round(((float) $workingHours - floor((float) $workingHours)) * 60)
            ),

            'breakHours' => sprintf(
                '%02d:%02d',
                floor((float) $breakHours),
                round(((float) $breakHours - floor((float) $breakHours)) * 60)
            ),

            'isManage' => $this->isManage,
        ];
    }
}