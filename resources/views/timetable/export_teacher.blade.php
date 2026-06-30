@php
    // Mencari mapel dominan yang diajarkan guru ini untuk ditampilkan di Header
    $subjectName = "Belum Ada Jadwal";
    foreach($days as $d) {
        foreach($timeslots as $s) {
            if(isset($timetables[$d][$s->id])) {
                $subjectName = $timetables[$d][$s->id]->subject->name;
                break 2;
            }
        }
    }
@endphp

<table style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
    <thead>
        <!-- KOP JADWAL -->
        <tr>
            <th colspan="{{ count($days) + 2 }}" style="text-align: center; font-weight: bold; font-size: 14px;">JADWAL PELAJARAN SEMESTER GENAP</th>
        </tr>
        <tr>
            <th colspan="{{ count($days) + 2 }}" style="text-align: center; font-weight: bold; font-size: 14px;">SMP NEGERI 3 LAKBOK</th>
        </tr>
        <tr>
            <th colspan="{{ count($days) + 2 }}" style="text-align: center; font-weight: bold; font-size: 12px; text-decoration: underline;">TAHUN AJARAN 2025/2026</th>
        </tr>
        <tr><th colspan="{{ count($days) + 2 }}"></th></tr> <!-- Spacer -->
        
        <!-- INFO GURU -->
        <tr>
            <td colspan="3" style="font-weight: bold; text-align: left;">Nama &nbsp;&nbsp;&nbsp;: {{ strtoupper($teacher->name) }}</td>
            <td colspan="{{ count($days) - 1 }}" style="font-weight: bold; text-align: left;">Mata Pelajaran &nbsp;: {{ strtoupper($subjectName) }}</td>
        </tr>
        <tr>
            <td colspan="{{ count($days) + 2 }}" style="font-weight: bold; text-align: left;">Kode &nbsp;&nbsp;&nbsp;&nbsp;: <span style="background-color: #bef264;">&nbsp;{{ $teacher->id }}&nbsp;</span></td>
        </tr>
        <tr><th colspan="{{ count($days) + 2 }}"></th></tr> <!-- Spacer -->

        <!-- HEADER TABEL -->
        <tr>
            <th rowspan="2" style="font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle; background-color: #e5e7eb; width: 50px;">Jam<br>Ke</th>
            <th rowspan="2" style="font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle; background-color: #e5e7eb; width: 120px;">Alokasi<br>Waktu</th>
            @foreach($days as $day)
                <th style="font-weight: bold; border: 2px solid #000000; text-align: center; background-color: #e5e7eb; width: 90px;">{{ strtoupper($day) }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($days as $day)
                <th style="font-weight: bold; border: 2px solid #000000; text-align: center; background-color: #e5e7eb; font-size: 10px;">KELAS</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $jamKe = 1; @endphp
        @foreach($timeslots as $slot)
            @php
                $timeStr = \Carbon\Carbon::parse($slot->start_time)->format('H.i') . '-' . \Carbon\Carbon::parse($slot->end_time)->format('H.i');
            @endphp
            
            <tr>
                @if($slot->is_break)
                    <!-- BARIS ISTIRAHAT -->
                    <td style="border: 2px solid #000000; text-align: center; background-color: #a7f3d0;"></td>
                    <td style="border: 2px solid #000000; text-align: center; font-weight: bold; background-color: #a7f3d0;">{{ $timeStr }}</td>
                    <td colspan="{{ count($days) }}" style="border: 2px solid #000000; text-align: center; font-weight: bold; background-color: #a7f3d0; letter-spacing: 5px;">
                        I S T I R A H A T
                    </td>
                @else
                    <!-- BARIS PELAJARAN -->
                    <td style="border: 2px solid #000000; text-align: center;">{{ $jamKe++ }}</td>
                    <td style="border: 2px solid #000000; text-align: center;">{{ $timeStr }}</td>
                    
                    @foreach($days as $day)
                        @php
                            $slotDays = array_map('trim', explode(',', $slot->day_of_week));
                            $isValidDay = in_array($day, $slotDays) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                            $cellData = $timetables[$day][$slot->id] ?? null;
                        @endphp

                        @if(!$isValidDay)
                            <td style="border: 2px solid #000000; background-color: #f3f4f6;"></td>
                        @else
                            @if($cellData)
                                <td style="border: 2px solid #000000; text-align: center; font-weight: bold;">{{ $cellData->studentClass->name }}</td>
                            @else
                                <td style="border: 2px solid #000000; text-align: center;">-</td>
                            @endif
                        @endif
                    @endforeach
                @endif
            </tr>
        @endforeach
    </tbody>
</table>