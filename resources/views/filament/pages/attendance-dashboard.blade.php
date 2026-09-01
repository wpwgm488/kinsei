<x-filament-panels::page>

    <div class="space-y-6">

        {{-- 今日の勤怠 --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        今日の勤怠
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ today()->format('Y年m月d日') }}
                    </p>
                </div>

                <div class="text-2xl font-bold text-gray-900">
                    {{ now()->format('H:i:s') }}
                </div>
            </div>

            @if ($openAttendance)
                <div class="mt-6 rounded-lg bg-gray-50 p-5">
                    <div class="text-sm font-medium text-gray-500">
                        現在勤務中
                    </div>

                    <div class="mt-2 text-xl font-bold text-gray-900">
                        {{ $openAttendance->in->format('H:i') }}
                        〜
                    </div>

                    <div class="mt-3 text-sm text-gray-600">
                        休憩：1時間
                    </div>
                </div>
            @endif

        </div>


        {{-- 今日の記録 --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

            <h2 class="text-xl font-bold text-gray-900">
                今日の記録
            </h2>

            <div class="mt-6">

                @forelse ($todayAttendances as $attendance)

                    <div class="border-b border-gray-200 py-4 last:border-b-0">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                            <div>
                                <div class="text-sm text-gray-500">
                                    出退勤
                                </div>

                                <div class="mt-1 font-semibold text-gray-900">
                                    {{ $attendance->in->format('H:i') }}
                                    〜
                                    {{ $attendance->out?->format('H:i') ?? '--:--' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    休憩
                                </div>

                                <div class="mt-1 font-semibold text-gray-900">
                                    {{ $attendance->break_time !== null
                                        ? sprintf(
                                            '%02d:%02d',
                                            floor((float) $attendance->break_time),
                                            round(
                                                (
                                                    (float) $attendance->break_time
                                                    - floor((float) $attendance->break_time)
                                                ) * 60
                                            )
                                        )
                                        : '--'
                                    }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    実働
                                </div>

                                <div class="mt-1 font-semibold text-gray-900">
                                    {{ $attendance->working_hours !== null
                                        ? sprintf(
                                            '%02d:%02d',
                                            floor((float) $attendance->working_hours),
                                            round(
                                                (
                                                    (float) $attendance->working_hours
                                                    - floor((float) $attendance->working_hours)
                                                ) * 60
                                            )
                                        )
                                        : '--'
                                    }}
                                </div>
                            </div>

                        </div>

                        @if ($attendance->work_content)
                            <div class="mt-4 rounded-lg bg-gray-50 p-3 text-sm text-gray-600">
                                {{ $attendance->work_content }}
                            </div>
                        @endif

                    </div>

                @empty

                    <p class="text-gray-500">
                        今日はまだ出勤していません。
                    </p>

                @endforelse

            </div>

        </div>


        {{-- 過去の勤怠 --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        過去の勤怠一覧
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        今月の勤怠をPDFで出力できます。
                    </p>
                </div>

                <a
                    href="{{ route('user.attendances.pdf', [
                        'month' => now()->format('Y-m'),
                    ]) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500"
                >
                    勤怠PDFを出力
                </a>

            </div>


            <div class="mt-6 overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead>
                        <tr class="border-b border-gray-200">

                            <th class="px-3 py-3 font-semibold text-gray-700">
                                勤務日
                            </th>

                            <th class="px-3 py-3 font-semibold text-gray-700">
                                出退勤
                            </th>

                            <th class="px-3 py-3 font-semibold text-gray-700">
                                休憩
                            </th>

                            <th class="px-3 py-3 font-semibold text-gray-700">
                                実働
                            </th>

                            <th class="px-3 py-3 font-semibold text-gray-700">
                                作業内容
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        @forelse ($pastAttendances as $attendance)

                            <tr class="border-b border-gray-100">

                                <td class="whitespace-nowrap px-3 py-4 text-gray-900">
                                    {{ $attendance->in->format('Y/m/d') }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 text-gray-900">
                                    {{ $attendance->in->format('H:i') }}
                                    〜
                                    {{ $attendance->out?->format('H:i') ?? '--:--' }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 text-gray-900">
                                    {{ $attendance->break_time !== null
                                        ? sprintf(
                                            '%02d:%02d',
                                            floor((float) $attendance->break_time),
                                            round(
                                                (
                                                    (float) $attendance->break_time
                                                    - floor((float) $attendance->break_time)
                                                ) * 60
                                            )
                                        )
                                        : '--'
                                    }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 text-gray-900">
                                    {{ $attendance->working_hours !== null
                                        ? sprintf(
                                            '%02d:%02d',
                                            floor((float) $attendance->working_hours),
                                            round(
                                                (
                                                    (float) $attendance->working_hours
                                                    - floor((float) $attendance->working_hours)
                                                ) * 60
                                            )
                                        )
                                        : '--'
                                    }}
                                </td>

                                <td class="min-w-[250px] px-3 py-4 text-gray-700">
                                    {{ $attendance->work_content ?? '' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="px-3 py-8 text-center text-gray-500"
                                >
                                    過去の勤怠はありません。
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-filament-panels::page>
