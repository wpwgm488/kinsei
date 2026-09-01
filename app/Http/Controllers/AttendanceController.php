<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function clockIn()
    {
        $userId = auth()->id();

        $openAttendance = Attendance::where('user_id', $userId)
            ->whereNull('out')
            ->latest('in')
            ->first();

        if ($openAttendance) {
            return back()
                ->with('attendance_warning', true)
                ->with('attendance_id', $openAttendance->id);
        }

        Attendance::create([
            'user_id' => $userId,
            'in' => now(),
            'out' => null,
        ]);

        return back()->with('status', __('messages.clocked_in'));
    }

    public function closePreviousNow()
    {
        $openAttendance = Attendance::where('user_id', auth()->id())
            ->whereNull('out')
            ->latest('in')
            ->first();

        if ($openAttendance) {
            $openAttendance->update([
                'out' => now(),
            ]);
        }

        return back()->with('status', __('messages.clocked_out'));
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'attendance_id' => ['required', 'integer'],
            'out' => ['required', 'date'],
        ]);

        $attendance = Attendance::where('id', $request->input('attendance_id'))
            ->where('user_id', auth()->id())
            ->whereNull('out')
            ->first();

        if (! $attendance) {
            return back()->with('status', 'この勤怠はすでに退勤済みです。');
        }

        $out = Carbon::parse($request->input('out'));

        if ($out->lte($attendance->in)) {
            $out = $attendance->in->copy()->addSecond();
        }

        $attendance->update([
            'out' => $out,
        ]);

        return back()->with('status', __('messages.clocked_out'));
    }
}