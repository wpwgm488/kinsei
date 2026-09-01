<?php

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| トップページ
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (! auth()->check()) {
        return redirect('/login');
    }

    /*
     * 今日の勤怠
     */
    $todayAttendances = Attendance::where('user_id', auth()->id())
        ->whereDate('in', today())
        ->orderBy('in')
        ->get();

    /*
     * 日付に関係なく、未退勤の勤怠を取得。
     *
     * 前日の退勤漏れがあっても、
     * 勝手に退勤処理はしない。
     */
    $openAttendance = Attendance::where('user_id', auth()->id())
        ->whereNull('out')
        ->latest('in')
        ->first();

    return view('top', compact(
        'todayAttendances',
        'openAttendance'
    ));
});


/*
|--------------------------------------------------------------------------
| 出勤
|--------------------------------------------------------------------------
*/
Route::post('/clock-in', function () {

    $userId = auth()->id();

    /*
     * 日付に関係なく未退勤の勤怠を確認。
     */
    $openAttendance = Attendance::where('user_id', $userId)
        ->whereNull('out')
        ->latest('in')
        ->first();

    /*
     * 未退勤がある場合は、新しい出勤を作らない。
     *
     * ユーザーに退勤時刻を入力してもらう。
     */
    if ($openAttendance) {

        return back()
            ->with('attendance_warning', true)
            ->with('attendance_id', $openAttendance->id);
    }

    /*
     * 未退勤がなければ通常の出勤。
     */
    Attendance::create([
        'user_id' => $userId,
        'in' => now(),
        'out' => null,
    ]);

    return back()->with(
        'status',
        __('messages.clocked_in')
    );

})->middleware('auth')->name('clock-in');


/*
|--------------------------------------------------------------------------
| 退勤
|--------------------------------------------------------------------------
|
| attendance_id で指定された勤怠だけを退勤処理する。
|
*/
Route::post('/clock-in/close-previous-now', function () {
    $openAttendance = Attendance::where('user_id', auth()->id())->whereNull('out')->latest('in')->first();
    if ($openAttendance) {
        $openAttendance->update(['out' => now()]);
    }
    return back()->with('status', __('messages.clocked_out'));
})->middleware('auth')->name('clock-in.close-previous-now');
Route::post('/clock-out', function (Request $request) {

    $request->validate([
        'attendance_id' => ['required', 'integer'],
        'out' => ['required', 'date'],
    ]);

    $attendance = Attendance::where('id', $request->input('attendance_id'))
        ->where('user_id', auth()->id())
        ->whereNull('out')
        ->first();

    if (! $attendance) {
        return back()->with(
            'status',
            'この勤怠はすでに退勤済みです。'
        );
    }

    $out = \Carbon\Carbon::parse($request->input('out'));

    if ($out->format('Y-m-d H:i:s') <= $attendance->in->format('Y-m-d H:i:s')) {
        $out = $attendance->in->copy()->addSecond();
    }

    $attendance->update([
        'out' => $out,
    ]);

    return back()->with(
        'status',
        __('messages.clocked_out')
    );

})->middleware('auth')->name('clock-out');

/*
|--------------------------------------------------------------------------
| 休憩開始
|--------------------------------------------------------------------------
*/
Route::post('/break-start', function (Request $request) {

    $request->validate([
        'attendance_id' => ['required', 'integer'],
    ]);

    $attendance = Attendance::where('id', $request->input('attendance_id'))
        ->where('user_id', auth()->id())
        ->whereNull('out')
        ->first();

    if (! $attendance) {
        return back()->with(
            'status',
            'この勤怠では休憩を開始できません。'
        );
    }

    if ($attendance->break_start) {
        return back()->with(
            'status',
            'すでに休憩開始済みです。'
        );
    }

    $attendance->update([
        'break_start' => now(),
        'break_end' => null,
    ]);

    return back()->with(
        'status',
        '休憩を開始しました。'
    );

})->middleware('auth')->name('break-start');


/*
|--------------------------------------------------------------------------
| 休憩終了
|--------------------------------------------------------------------------
*/
Route::post('/break-end', function (Request $request) {

    $request->validate([
        'attendance_id' => ['required', 'integer'],
    ]);

    $attendance = Attendance::where('id', $request->input('attendance_id'))
        ->where('user_id', auth()->id())
        ->whereNull('out')
        ->first();

    if (! $attendance) {
        return back()->with(
            'status',
            'この勤怠では休憩を終了できません。'
        );
    }

    if (! $attendance->break_start) {
        return back()->with(
            'status',
            '休憩開始が記録されていません。'
        );
    }

    if ($attendance->break_end) {
        return back()->with(
            'status',
            'すでに休憩終了済みです。'
        );
    }

    $attendance->update([
        'break_end' => now(),
    ]);

    return back()->with(
        'status',
        '休憩を終了しました。'
    );

})->middleware('auth')->name('break-end');
/*
|--------------------------------------------------------------------------
| About
|--------------------------------------------------------------------------
*/
Route::get('/about', function () {
    return view('about');
})->name('about');

/*
|--------------------------------------------------------------------------
| ログアウト
|--------------------------------------------------------------------------
*/
Route::get('/logout', function () {

    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');

})->middleware('auth')->name('logout');