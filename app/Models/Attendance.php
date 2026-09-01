<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'in',
        'out',
        'break_started_at',
        'break_ended_at',
        'working_hours',
        'break_time',
        'work_content',
    ];

    protected function casts(): array
    {
        return [
            'in' => 'datetime',
            'out' => 'datetime',
            'break_started_at' => 'datetime',
            'break_ended_at' => 'datetime',
            'working_hours' => 'float',
            'break_time' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($attendance) {
            if (! $attendance->in || ! $attendance->out) {
                $attendance->working_hours = null;
                $attendance->break_time = null;

                return;
            }

            $in = Carbon::parse($attendance->in);
            $out = Carbon::parse($attendance->out);

            $totalMinutes = $in->diffInMinutes($out);

            // 休憩は固定1時間
            $breakMinutes = 60;

            $workingMinutes = max(0, $totalMinutes - $breakMinutes);

            // 5分単位に丸める
            $roundedMinutes = round($workingMinutes / 5) * 5;

            $attendance->break_time = 1.00;
            $attendance->working_hours = round($roundedMinutes / 60, 2);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}