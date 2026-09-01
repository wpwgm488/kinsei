<x-filament-panels::page>
    @php
        $currentAttendance = $this->currentAttendance();

        $attendance = \App\Models\Attendance::query()
            ->where('user_id', auth()->id())
            ->whereDate('in', now())
            ->latest('in')
            ->first();

        $isWorking = $currentAttendance !== null;
        $isOnBreak = $this->isOnBreak();
        $hasTodayAttendance = $this->hasTodayAttendance();
    @endphp

    <div style="display:flex; flex-direction:column; align-items:center;">

        <div style="margin-bottom:24px; font-size:20px; font-weight:600;">
            {{ now()->format('Y/m/d') }}
        </div>

        <div style="
            width:128px;
            height:48px;
            margin-bottom:24px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#ffffff;
            color:#111827;
            font-size:20px;
            font-weight:600;
            border:none;
            border-radius:0;
            box-shadow:none;
        ">
            {{ now()->format('H:i') }}
        </div>

        <div style="margin-bottom:28px; font-size:18px; font-weight:500;">
            @if ($isWorking)
                出勤中
            @elseif ($attendance?->out)
                お疲れ様でした
            @else
                おはようございます
            @endif
        </div>

        <div style="
            display:flex;
            flex-direction:row;
            gap:8px;
            margin-bottom:28px;
        ">
            <x-filament::button
                wire:click="clockIn"
                wire:confirm="{{ $hasTodayAttendance ? '本日すでに出勤しています。もう一度出勤しますか？' : '' }}"
                :disabled="$isWorking"
            >
                出勤
            </x-filament::button>

            <x-filament::button
                wire:click="clockOut"
                :disabled="! $isWorking"
            >
                退勤
            </x-filament::button>
        </div>

        <div style="margin-bottom:32px;">
            <x-filament::button
                wire:click="startBreak"
                :disabled="! $this->canStartBreak()"
            >
                休憩スタート
            </x-filament::button>
        </div>

        <div style="font-size:18px; line-height:2.2;">
            <div>
                出勤
                {{ $attendance?->in?->format('H:i') ?? '--:--' }}
            </div>

            <div>
                休憩
                {{ $attendance?->break_started_at?->format('H:i') ?? '--:--' }}
                ～
                @if ($isOnBreak)
                    {{ $attendance?->break_ended_at?->format('H:i') ?? '--:--' }}
                @else
                    {{ $attendance?->break_ended_at?->format('H:i') ?? '--:--' }}
                @endif
            </div>

            <div>
                退勤
                {{ $attendance?->out?->format('H:i') ?? '--:--' }}
            </div>
        </div>

    </div>
</x-filament-panels::page>
