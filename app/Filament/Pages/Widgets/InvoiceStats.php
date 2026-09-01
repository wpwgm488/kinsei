<?php

namespace App\Filament\Pages\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class InvoiceStats extends Widget
{
    protected string $view = 'filament.pages.widgets.invoice-stats';

    protected int|string|array $columnSpan = 'full';

    public string $month;

    public function getViewData(): array
    {
        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $this->month);
        } catch (\Exception $e) {
            $selectedMonth = now();
        }

        $attendances = Attendance::query()
            ->where('user_id', auth()->id())
            ->whereBetween('in', [
                $selectedMonth->copy()->startOfMonth(),
                $selectedMonth->copy()->endOfMonth(),
            ])
            ->get();

        return [
            'workingHours' => $this->formatHours(
                $attendances->sum('working_hours')
            ),
            'breakHours' => $this->formatHours(
                $attendances->sum('break_time')
            ),
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
