<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InvoicePdfController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $month = $request->query('month');

        if (! $month) {
            $month = now()->format('Y-m');
        }

        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $month);
        } catch (\Exception $e) {
            $selectedMonth = now();
            $month = $selectedMonth->format('Y-m');
        }

        $startOfMonth = $selectedMonth->copy()->startOfMonth();
        $endOfMonth = $selectedMonth->copy()->endOfMonth();

        $attendances = Attendance::query()
            ->where('user_id', $user->id)
            ->whereBetween('in', [
                $startOfMonth,
                $endOfMonth,
            ])
            ->orderBy('in')
            ->get();

        $workingHours = $attendances->sum('working_hours');
        $breakHours = $attendances->sum('break_time');

        $pdf = Pdf::loadView('pdf.invoice', [
            'user' => $user,
            'month' => $selectedMonth,
            'attendances' => $attendances,
            'workingHours' => $this->formatHours($workingHours),
            'breakHours' => $this->formatHours($breakHours),
        ]);

        $dompdf = $pdf->getDomPDF();

        $dompdf->getFontMetrics()->registerFont(
            [
                'family' => 'IPAexGothic',
                'style' => 'normal',
                'weight' => 'normal',
            ],
            storage_path('fonts/ipaexg.ttf')
        );

        $dompdf->set_option('defaultFont', 'IPAexGothic');

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(
            '請求書_' . $user->name . '_' . $month . '.pdf'
        );
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
