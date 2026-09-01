@php
    $today = now()->startOfDay();

    $todayAttendance = \App\Models\Attendance::query()
        ->where('user_id', auth()->id())
        ->whereDate('in', $today)
        ->latest('in')
        ->first();

    $isWorking = $todayAttendance && $todayAttendance->out === null;

    $timeText = '';

    if ($todayAttendance) {
        $in = \Illuminate\Support\Carbon::parse($todayAttendance->in)->format('H:i');

        $out = $todayAttendance->out
            ? \Illuminate\Support\Carbon::parse($todayAttendance->out)->format('H:i')
            : '勤務中';

        $timeText = $in . ' ～ ' . $out;
    }
@endphp

@if (auth()->user()?->role === 'user')
    <div class="mb-6 flex flex-col items-center gap-3">
        <div class="text-base font-medium">
            {{ $today->format('Y/m/d') }}
        </div>

        <div class="flex gap-2">
            <button
                type="button"
                wire:click="clockIn"
                @disabled($isWorking)
                class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50"
            >
                出勤
            </button>

            <button
                type="button"
                wire:click="clockOut"
                @disabled(! $isWorking)
                class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50"
            >
                退勤
            </button>
        </div>

        <div class="text-base">
            {{ $timeText ?: '本日の勤怠なし' }}
        </div>
    </div>
@endif
