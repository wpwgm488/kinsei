<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Filament\Resources\Attendances\Widgets\AttendanceStats;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // requestはここでとる
            AttendanceStats::make([
                'userId' => request()->query('user_id')
                    ? (int) request()->query('user_id')
                    : null,

                'month' => request()->query(
                    'month',
                    now()->format('Y-m')
                ),

                'isManage' => request()->is('manage') || request()->is('manage/*'),
            ]),
        ];
    }

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
        }

        $previousMonth = $month->copy()
            ->subMonth()
            ->format('Y-m');

        $nextMonth = $month->copy()
            ->addMonth()
            ->format('Y-m');

        $query = [];

        if (request()->query('user_id')) {
            $query['user_id'] = request()->query('user_id');
        }

        $actions = [
            Action::make('previousMonth')
                ->label('＜')
                ->url(
                    static::getResource()::getUrl(
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
                    static::getResource()::getUrl(
                        'index',
                        array_merge($query, [
                            'month' => $nextMonth,
                        ])
                    )
                ),
        ];

        return $actions;
    }

    public function getBreadcrumbs(): array
    {
        if (
            request()->headers->get('referer') &&
            str_contains(
                request()->headers->get('referer'),
                '/mypage'
            )
        ) {
            return [
                url('/mypage') => __('messages.mypage'),
                __('messages.attendance_list'),
            ];
        }

        if (request()->query('user_id')) {
            return [
                url('/manage') => __('messages.gmanage'),
                url('/manage/users') => __('messages.user_list'),
                __('messages.attendance_list'),
            ];
        }

        return [
            url('/manage') => __('messages.gmanage'),
            __('messages.attendance_list'),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
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

    public function getHeading(): string
    {
        return '勤怠一覧';
    }
}