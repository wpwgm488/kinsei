<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'in',
        'out',
        'break_start',
        'break_end',
        'working_hours',
        'break_time',
        'work_content',
    ];

    protected function casts(): array
    {
        return [
            'in' => 'datetime',
            'out' => 'datetime',
            'break_start' => 'datetime',
            'break_end' => 'datetime',
            'working_hours' => 'float',
            'break_time' => 'float',
        ];
    }

    protected static function booted()
    {
        static::saving(function ($attendance) {
            $breakMinutes = 0;

            if ($attendance->break_start && $attendance->break_end) {
                $breakStart = Carbon::parse($attendance->break_start);
                $breakEnd = Carbon::parse($attendance->break_end);

                $breakMinutes = $breakStart->diffInMinutes($breakEnd);

                $attendance->break_time = round($breakMinutes / 60, 2);
            } else {
                $attendance->break_time = 0;
            }

            if ($attendance->in && $attendance->out) {
                $in = Carbon::parse($attendance->in);
                $out = Carbon::parse($attendance->out);

                $totalMinutes = $in->diffInMinutes($out);

                $workingMinutes = max(0, $totalMinutes - $breakMinutes);

                $attendance->working_hours = round($workingMinutes / 60, 2);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}