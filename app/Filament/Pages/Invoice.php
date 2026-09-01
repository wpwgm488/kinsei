<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Invoice as InvoiceModel;
use Filament\Actions\Action;
use Filament\Pages\Page;
use App\Filament\Pages\Widgets\InvoiceStats;
use Illuminate\Support\Carbon;
use BackedEnum;

class Invoice extends Page
{
    protected string $view = 'filament.pages.invoice';
    protected static ?string $title = '請求書';
    protected static ?string $navigationLabel = '請求書';
    protected static ?int $navigationSort = 4;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    public string $month;

    public function mount(): void
    {
        $this->month = request()->query('month', now()->format('Y-m'));
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InvoiceStats::make([
                'month' => $this->month,
            ]),
        ];
    }

    protected function getHeaderActions(): array
    {
        try {
            $month = Carbon::createFromFormat('Y-m', $this->month);
        } catch (\Exception $e) {
            $month = now();
            $this->month = $month->format('Y-m');
        }

        return [
            // Action::make('pdf')
            //     ->label('請求書PDFを出力')
            //     ->icon('heroicon-o-document-arrow-down')
            //     ->url(route('user.invoice.pdf', [
            //         'month' => $this->month,
            //     ])),
            Action::make('previousMonth')
                ->label('＜')
                ->url(url('/mypage/invoice?month=' . $month->copy()->subMonth()->format('Y-m'))),
            Action::make('currentMonth')
                ->label($month->format('Y年n月'))
                ->disabled(),
            Action::make('nextMonth')
                ->label('＞')
                ->url(url('/mypage/invoice?month=' . $month->copy()->addMonth()->format('Y-m'))),
        ];
    }

    protected function getViewData(): array
    {
        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $this->month);
        } catch (\Exception $e) {
            $selectedMonth = now();
            $this->month = $selectedMonth->format('Y-m');
        }

        $attendances = Attendance::query()
            ->where('user_id', auth()->id())
            ->whereBetween('in', [
                $selectedMonth->copy()->startOfMonth(),
                $selectedMonth->copy()->endOfMonth(),
            ])
            ->get();

        $customers = Customer::query()
            ->where('user_id', auth()->id())
            ->get();

        $invoice = InvoiceModel::query()
            ->whereHas('customer', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('billing_month', $this->month)
            ->first();

        $user = auth()->user();
        $workingHours = $attendances->sum('working_hours');

        $unitPrice = $user->billing_type === 'hourly'
            ? ($user->hourly_rate ?? 0)
            : ($user->monthly_rate ?? 0);

        $subtotal = 0;

        if ($user->billing_type === 'hourly') {
            $subtotal = $workingHours * $unitPrice;
        } else {
            $subtotal = $unitPrice;
        }

        if ($user->price_tax_type === 'inclusive') {
            $total = $subtotal;
            $tax = round($total * 10 / 110);
            $subtotal = $total - $tax;
        } else {
            $tax = round($subtotal * 0.10);
            $total = $subtotal + $tax;
        }

        return [
            'monthLabel' => $selectedMonth->format('Y年n月'),
            'workingHours' => $this->formatHours($workingHours),
            'breakHours' => $this->formatHours($attendances->sum('break_time')),
            'customers' => $customers,
            'invoice' => $invoice,
            'user' => $user,
            'unitPrice' => $unitPrice,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    private function formatHours(float|int|null $hours): string
    {
        $totalMinutes = round(((float) ($hours ?? 0)) * 60);

        return sprintf('%02d:%02d', intdiv($totalMinutes, 60), $totalMinutes % 60);
    }
}