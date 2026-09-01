<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Filament\Resources\Attendances\Widgets\AttendanceStats;
use App\Models\Attendance;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $title = '';

    protected function getHeaderActions(): array
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

        return [
            Action::make('pdf')
                ->label('勤怠PDFを出力')
                ->icon('heroicon-o-document-arrow-down')
                ->url(
                    auth()->user()?->role === 'user'
                        ? route('user.attendances.pdf', [
                            'month' => $currentMonth,
                        ])
                        : route('admin.attendances.pdf', [
                            'user_id' => request()->query('user_id'),
                            'month' => $currentMonth,
                        ])
                ),

            Action::make('previousMonth')
                ->label('＜')
                ->url(
                    AttendanceResource::getUrl(
                        'index',
                        array_merge($query, [
                            'month' => $previousMonth,
                        ])
                    )
                ),

            Action::make('currentMonth')
                ->label($month->format('Y年n月'))
                ->disabled(),

            Action::make('nextMonth')
                ->label('＞')
                ->url(
                    AttendanceResource::getUrl(
                        'index',
                        array_merge($query, [
                            'month' => $nextMonth,
                        ])
                    )
                ),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AttendanceStats::make([
                'userId' => request()->query('user_id')
                    ? (int) request()->query('user_id')
                    : null,

                'month' => request()->query(
                    'month',
                    now()->format('Y-m')
                ),
            ]),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        $month = request()->query(
            'month',
            now()->format('Y-m')
        );

        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $month);

            $query->whereBetween('in', [
                $selectedMonth->copy()->startOfMonth(),
                $selectedMonth->copy()->endOfMonth(),
            ]);
        } catch (\Exception $e) {
            $query->whereBetween('in', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]);
        }

        return $query;
    }

    public function getSubheading(): ?string
    {
        $userId = request()->query('user_id');

        if ($userId) {
            $user = User::find($userId);

            return $user?->name;
        }

        if (request()->is('mypage/attendances')) {
            return auth()->user()?->name;
        }

        return null;
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getHeading(): string
    {
        return '勤怠一覧';
    }
}