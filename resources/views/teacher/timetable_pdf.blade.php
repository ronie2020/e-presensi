<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Mengajar - {{ $teacher->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #ecf0f1;
            color: #2c3e50;
            font-weight: bold;
            text-transform: uppercase;
        }
        .time-col {
            background-color: #f9f9f9;
            font-weight: bold;
            width: 120px;
        }
        .break-row {
            background-color: #fdf2e9;
            font-weight: bold;
            color: #d35400;
        }
        .subject-name {
            font-weight: bold;
            color: #2980b9;
            display: block;
            margin-bottom: 4px;
        }
        .class-name {
            font-size: 10px;
            background-color: #eee;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Jadwal Mengajar Guru</h2>
        <p>Nama Guru: <strong>{{ $teacher->name }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                @foreach($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($timeslots as $slot)
                <tr>
                    <td class="time-col">
                        {{ $slot->name }}<br>
                        <span style="font-size: 10px; color: #7f8c8d;">
                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                        </span>
                    </td>

                    @foreach($days as $day)
                        @php
                            $isValidDay = str_contains($slot->day_of_week ?? '', $day) 
                                || $slot->day_of_week === 'Semua Hari' 
                                || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') 
                                || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                                
                            $key = $day . '-' . $slot->id;
                            $schedule = $myTimetables[$key] ?? null;
                        @endphp

                        @if($slot->is_break && $isValidDay)
                            <td class="break-row">{{ $slot->name }}</td>
                        @elseif(!$isValidDay)
                            <td style="background-color: #f1f2f6;"></td>
                        @else
                            <td>
                                @if($schedule)
                                    <span class="subject-name">{{ $schedule->subject->name ?? '-' }}</span>
                                    <span class="class-name">{{ $schedule->studentClass->name ?? '-' }}</span>
                                @else
                                    <span style="color: #bdc3c7;">-</span>
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB <br>
        Oleh Sistem Informasi Akademik
    </div>

</body>
</html>