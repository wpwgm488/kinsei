@extends('layouts.app')
@section('title', __('messages.app_name'))
@section('content')

<div style="min-height: calc(100vh - 120px); display: flex; justify-content: center; align-items: center; width: 100%;">

    <div style="text-align: center; width: 100%; max-width: 700px; padding: 24px; box-sizing: border-box;">

        <h1 style="font-size: 2.5rem; font-weight: bold; color: #1f2937; margin-bottom: 24px;">
            {{ __('messages.app_name') }}
        </h1>


        {{-- ステータス --}}
        @if (session('status'))

            <div style="padding: 12px; background-color: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 16px; font-weight: bold;">
                {{ session('status') }}
            </div>

        @endif


        {{-- エラー --}}
        @if ($errors->any())

            <div style="padding: 12px; background-color: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 16px; font-weight: bold;">
                {{ $errors->first() }}
            </div>

        @endif


        {{-- 前日の未退勤 --}}
        @if ($openAttendance && ! $openAttendance->in->isToday())

            <div style="background: #fff7ed; border: 1px solid #fdba74; padding: 20px; border-radius: 12px; margin-bottom: 24px; text-align: center;">

                <div style="color: #9a3412; font-weight: bold; font-size: 16px; margin-bottom: 8px;">
                    ⚠ 前日の勤怠が未退勤です。
                </div>

                <div style="color: #6b7280; font-size: 14px; margin-bottom: 16px;">
                    {{ $openAttendance->in->format('Y/m/d H:i') }} の出勤がまだ退勤されていません。
                </div>

                <div style="color: #374151; font-size: 14px; margin-bottom: 8px; font-weight: bold;">
                    実際の退勤時刻を入力してください
                </div>

                <form
                    method="POST"
                    action="{{ route('clock-out') }}"
                    style="width: 100%; max-width: 320px; margin: 0 auto;"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="attendance_id"
                        value="{{ $openAttendance->id }}"
                    >

                    <input
                        type="datetime-local"
                        name="out"
                        value="{{ old('out') }}"
                        min="{{ $openAttendance->in->format('Y-m-d\TH:i') }}"
                        required
                        style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; margin-bottom: 10px;"
                    >

                    <button
                        type="submit"
                        style="width: 100%; padding: 12px 16px; background-color: #ea580c; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;"
                    >
                        この時刻で前回を退勤
                    </button>

                </form>

                <form
                    method="POST"
                    action="{{ route('clock-in.close-previous-now') }}"
                    style="width: 100%; max-width: 320px; margin: 10px auto 0;"
                >

                    @csrf

                    <button
                        type="submit"
                        style="width: 100%; padding: 12px 16px; background-color: #6b7280; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;"
                    >
                        現在時刻で前回を退勤
                    </button>

                </form>

                <div style="margin-top: 12px; color: #6b7280; font-size: 13px;">
                    ※ 前日の実際の退勤時刻を入力すると、その後に今日の出勤が登録されます。
                </div>

            </div>

        @endif


        {{-- 今日の勤怠 --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">

            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 4px;">
                {{ today()->format('Y/m/d') }}({{ ['日','月','火','水','木','金','土'][today()->dayOfWeek] }})
            </h2>

            <div style="font-size: 1.2rem; font-weight: bold; color: #374151; margin-bottom: 24px;">
                {{ now()->format('H:i:s') }}
            </div>

            {{-- 今日の勤怠 --}}
            @forelse ($todayAttendances as $attendance)

                <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 24px; margin-bottom: 24px;">

                    {{-- 出退勤時間 --}}
                    <div style="font-size: 16px; color: #374151; margin-bottom: 20px;">

                        出勤時間:
                        <strong>
                            {{ $attendance->in->format('H:i') }}
                        </strong>

                        ~

                        退勤時間:

                        <strong>
                            {{ $attendance->out
                                ? $attendance->out->format('H:i')
                                : '--'
                            }}
                        </strong>

                    </div>


                    {{-- 出勤・退勤ボタン --}}
                    <div style="display: flex; gap: 12px; justify-content: center; margin-bottom: 12px;">

                        {{-- 出勤 --}}
                        <button
                            type="button"
                            disabled
                            style="width: 140px; padding: 12px 16px; background: #e5e7eb; color: #9ca3af; border: none; border-radius: 8px; font-weight: bold; cursor: not-allowed;"
                        >
                            出勤済み
                        </button>


                        {{-- 退勤 --}}
                        @if (! $attendance->out)

                            <form method="POST" action="{{ route('clock-out') }}">

                                @csrf

                                <input
                                    type="hidden"
                                    name="attendance_id"
                                    value="{{ $attendance->id }}"
                                >

                                <input
                                    type="hidden"
                                    name="out"
                                    value="{{ now()->format('Y-m-d\TH:i') }}"
                                >

                                <button
                                    type="submit"
                                    style="width: 140px; padding: 12px 16px; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;"
                                >
                                    退勤
                                </button>

                            </form>

                        @else

                            <button
                                type="button"
                                disabled
                                style="width: 140px; padding: 12px 16px; background: #e5e7eb; color: #9ca3af; border: none; border-radius: 8px; font-weight: bold; cursor: not-allowed;"
                            >
                                退勤済み
                            </button>

                        @endif

                    </div>


                    {{-- 休憩ボタン --}}
                    <div style="display: flex; gap: 12px; justify-content: center;">

                        {{-- 休憩開始 --}}
                        @if (! $attendance->out && ! $attendance->break_start)

                            <form method="POST" action="{{ route('break-start') }}">

                                @csrf

                                <input
                                    type="hidden"
                                    name="attendance_id"
                                    value="{{ $attendance->id }}"
                                >

                                <button
                                    type="submit"
                                    style="width: 140px; padding: 12px 16px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;"
                                >
                                    休憩開始
                                </button>

                            </form>

                        @else

                            <button
                                type="button"
                                disabled
                                style="width: 140px; padding: 12px 16px; background: #e5e7eb; color: #9ca3af; border: none; border-radius: 8px; font-weight: bold; cursor: not-allowed;"
                            >
                                休憩開始
                            </button>

                        @endif


                        {{-- 休憩終了 --}}
                        @if (! $attendance->out && $attendance->break_start && ! $attendance->break_end)

                            <form method="POST" action="{{ route('break-end') }}">

                                @csrf

                                <input
                                    type="hidden"
                                    name="attendance_id"
                                    value="{{ $attendance->id }}"
                                >

                                <button
                                    type="submit"
                                    style="width: 140px; padding: 12px 16px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;"
                                >
                                    休憩終了
                                </button>

                            </form>

                        @else

                            <button
                                type="button"
                                disabled
                                style="width: 140px; padding: 12px 16px; background: #e5e7eb; color: #9ca3af; border: none; border-radius: 8px; font-weight: bold; cursor: not-allowed;"
                            >
                                休憩終了
                            </button>

                        @endif

                    </div>

                </div>

            @empty

                {{-- 今日まだ出勤していない --}}
                <div style="font-size: 16px; color: #6b7280; margin-bottom: 20px;">

                    出勤時間: <strong>--</strong>
                    ~
                    退勤時間: <strong>--</strong>

                </div>


                {{-- 初回出勤 --}}
                <div style="display: flex; gap: 12px; justify-content: center;">

                    @if (! $openAttendance)

                        <form method="POST" action="{{ route('clock-in') }}">

                            @csrf

                            <button
                                type="submit"
                                style="width: 140px; padding: 12px 16px; background: #16a34a; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;"
                            >
                                出勤
                            </button>

                        </form>

                    @else

                        <button
                            type="button"
                            disabled
                            style="width: 140px; padding: 12px 16px; background: #e5e7eb; color: #9ca3af; border: none; border-radius: 8px; font-weight: bold; cursor: not-allowed;"
                        >
                            出勤不可
                        </button>

                    @endif


                    <button
                        type="button"
                        disabled
                        style="width: 140px; padding: 12px 16px; background: #e5e7eb; color: #9ca3af; border: none; border-radius: 8px; font-weight: bold; cursor: not-allowed;"
                    >
                        退勤
                    </button>

                </div>

            @endforelse


            {{-- 退勤済みなら再出勤 --}}
            @if (
                $todayAttendances->isNotEmpty()
                && $todayAttendances->last()->out !== null
                && ! $openAttendance
            )

                <div style="margin-top: 8px;">

                    <form method="POST" action="{{ route('clock-in') }}">

                        @csrf

                        <button
                            type="submit"
                            style="width: 100%; padding: 12px 16px; background: #16a34a; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;"
                        >
                            再出勤
                        </button>

                    </form>

                </div>

            @endif

        </div>


        {{-- マイページ・管理画面 --}}
        <div style="display: flex; gap: 16px; justify-content: center; margin-bottom: 8px;">

            <a
                href="/mypage"
                style="display: inline-block; padding: 8px 16px; background-color: #4f46e5; color: #ffffff; border-radius: 8px; text-decoration: none;"
            >
                {{ __('messages.to_mypage') }}
            </a>


            @auth

                @if (in_array(auth()->user()->role, ['manager', 'admin'], true))

                    <a
                        href="/manage"
                        style="display: inline-block; padding: 8px 16px; background-color: #4b5563; color: #ffffff; border-radius: 8px; text-decoration: none;"
                    >
                        {{ __('messages.to_gmanage') }}
                    </a>

                @endif

            @endauth

        </div>


        {{-- Admin --}}
        @auth

            @if (auth()->user()->role === 'admin')

                <div style="margin-top: 8px; text-align: center;">

                    <a
                        href="/admin"
                        style="display: inline-block; padding: 8px 16px; background-color: #92400e; color: #ffffff; border-radius: 8px; text-decoration: none;"
                    >
                        {{ __('messages.to_admin') }}
                    </a>

                </div>

            @endif

        @endauth

    </div>

</div>

@endsection