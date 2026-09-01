<?php

namespace App\Filament\Resources\Attendances\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class AttendanceStats extends Widget
{
    protected string $view = 'filament.resources.attendances.widgets.attendance-stats';

    protected int|string|array $columnSpan = 'full';

    public ?int $userId = null;

    public string $month;

    public function getViewData(): array
    {
        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $this->month);
        } catch (\Exception $e) {
            $selectedMonth = now();
        }

        $query = Attendance::query()
            ->whereBetween('in', [
                $selectedMonth->copy()->startOfMonth(),
                $selectedMonth->copy()->endOfMonth(),
            ]);

        // 特定ユーザーを表示している場合は、そのユーザーだけ集計
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $attendances = $query->get();

        $workingHours = $attendances->sum('working_hours');
        $breakHours = $attendances->sum('break_time');

        return [
            'workingHours' => $this->formatHours($workingHours),
            'breakHours' => $this->formatHours($breakHours),
        ];
    }

    private function formatHours(float|int|null $hours): string
    {
        $hours = (float) ($hours ?? 0);

        $totalMinutes = round($hours * 60);

        $wholeHours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        return sprintf(
            '%02d:%02d',
            $wholeHours,
            $minutes
        );
    }
}