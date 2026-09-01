<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">

    <style>
        body,
        table,
        thead,
        tbody,
        tr,
        th,
        td {
            font-family: 'IPAexGothic';
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        h1 {
            margin-bottom: 10px;
        }

        .summary {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h1>勤怠表</h1>

    <div class="summary">
        <p>氏名：{{ $user->name }}</p>
        <p>対象月：{{ $month->format('Y年n月') }}</p>
        <p>月間休憩時間：{{ $breakHours }}</p>
        <p>月間実働時間：{{ $workingHours }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>勤務日</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>実働</th>
                <th>作業内容</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>
                        {{ $attendance->in?->format('Y/m/d') }}
                    </td>

                    <td>
                        {{ $attendance->in?->format('H:i') }}
                    </td>

                    <td>
                        {{ $attendance->out?->format('H:i') }}
                    </td>

                    <td>
                        @if ($attendance->break_time !== null)
                            {{ sprintf(
                                '%02d:%02d',
                                floor((float) $attendance->break_time),
                                round(
                                    ((float) $attendance->break_time
                                        - floor((float) $attendance->break_time)) * 60
                                )
                            ) }}
                        @endif
                    </td>

                    <td>
                        @if ($attendance->working_hours !== null)
                            {{ sprintf(
                                '%02d:%02d',
                                floor((float) $attendance->working_hours),
                                round(
                                    ((float) $attendance->working_hours
                                        - floor((float) $attendance->working_hours)) * 60
                                )
                            ) }}
                        @endif
                    </td>

                    <td>
                        {{ $attendance->work_content }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        この月の勤怠データはありません。
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
