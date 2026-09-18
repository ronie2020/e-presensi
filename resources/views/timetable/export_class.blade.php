@php
    // Kumpulkan daftar guru unik yang mengajar di kelas ini untuk Legenda di bawah
    $guruList = [];
    foreach($days as $day) {
        foreach($timeslots as $slot) {
            if(isset($timetables[$day][$slot->id])) {
                $guru = $timetables[$day][$slot->id]->teacher;
                if ($guru) {
                    $guruList[$guru->id] = $guru->name;
                }
            }
        }
    }
    ksort($guruList); // Urutkan berdasarkan ID/Kode Guru
@endphp

<table style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
    <thead>
        <!-- KOP JADWAL -->
        <tr>
            <th colspan="12" style="text-align: center; font-weight: bold; font-size: 14px;">SMP NEGERI 3 LAKBOK</th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center; font-weight: bold; font-size: 12px;">JADWAL PELAJARAN TAHUN AJARAN 2025/2026</th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center; font-weight: bold; font-size: 12px;">SEMESTER GENAP</th>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; text-align: left; border: 1px solid #000000; background-color: #f3f4f6;">Kelas: {{ $class->name }}</th>
            <th colspan="10"></th>
        </tr>
        
        <!-- HEADER TABEL -->
        <tr>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #e5e7eb; width: 60px;">Sesi / Jam</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #e5e7eb; width: 120px;">Alokasi Waktu</th>
            @foreach($days as $day)
                <th colspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #e5e7eb;">{{ strtoupper($day) }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($days as $day)
                <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #e5e7eb; font-size: 10px; width: 60px;">KODE</th>
                <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #e5e7eb; font-size: 10px; width: 150px;">M. Pel</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($timeslots as $slot)
            @php
                $timeStr = \Carbon\Carbon::parse($slot->start_time)->format('H.i') . '-' . \Carbon\Carbon::parse($slot->end_time)->format('H.i');
            @endphp
            
            <tr>
                @if($slot->is_break)
                    <!-- BARIS ISTIRAHAT -->
                    <td style="border: 1px solid #000000; text-align: center; background-color: #a7f3d0;"></td>
                    <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #a7f3d0;">{{ $timeStr }}</td>
                    <td colspan="{{ count($days) * 2 }}" style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #a7f3d0; letter-spacing: 5px;">
                        {{ strtoupper($slot->name) }}
                    </td>
                @else
                    <!-- BARIS PELAJARAN -->
                    <td style="border: 1px solid #000000; text-align: center; font-weight: bold;">{{ $slot->name }}</td>
                    <td style="border: 1px solid #000000; text-align: center;">{{ $timeStr }}</td>
                    
                    @foreach($days as $day)
                        @php
                            $slotDays = array_map('trim', explode(',', $slot->day_of_week));
                            $isValidDay = in_array($day, $slotDays) || strcasecmp($slot->day_of_week, 'Semua Hari') === 0 || (strcasecmp($slot->day_of_week, 'Selain Senin') === 0 && $day !== 'Senin') || (strcasecmp($slot->day_of_week, 'Selain Jumat') === 0 && $day !== 'Jumat');
                            $cellData = $timetables[$day][$slot->id] ?? null;
                        @endphp

                        @if(!$isValidDay)
                            <td style="border: 1px solid #000000; background-color: #f3f4f6;"></td>
                            <td style="border: 1px solid #000000; background-color: #f3f4f6;"></td>
                        @else
                            @if($cellData)
                                <td style="border: 1px solid #000000; text-align: center; background-color: #fef08a;">{{ $cellData->teacher?->id ?? '-' }}</td>
                                <td style="border: 1px solid #000000; text-align: center; background-color: #fef08a;">{{ $cellData->subject?->name ?? '-' }}</td>
                            @else
                                <td style="border: 1px solid #000000;"></td>
                                <td style="border: 1px solid #000000;"></td>
                            @endif
                        @endif
                    @endforeach
                @endif
            </tr>
        @endforeach

        <!-- KETERANGAN KODE GURU (LEGENDA) -->
        <tr><td colspan="12"></td></tr> <!-- Spacer -->
        <tr>
            <td colspan="2" style="font-weight: bold; vertical-align: top;">KODE GURU :</td>
            
            <!-- Membagi daftar guru menjadi 3 kolom agar rapi di Excel -->
            @php
                $chunkSize = max(1, (int) ceil(count($guruList) / 3));
                $chunks = !empty($guruList) ? array_chunk($guruList, $chunkSize, true) : [];
            @endphp
            
            @if(empty($chunks))
                <td colspan="9" style="vertical-align: top; border: 1px solid #000000; color: #6b7280; font-style: italic;">
                    (Belum ada guru yang dijadwalkan di kelas ini)
                </td>
            @else
                @foreach($chunks as $chunk)
                    <td colspan="3" style="vertical-align: top; border: 1px solid #000000;">
                        @foreach($chunk as $id => $name)
                            {{ $id }} - {{ $name }}<br>
                        @endforeach
                    </td>
                @endforeach
                
                <!-- Jika kolom sisa kurang dari 3, isi dengan colspan kosong -->
                @for($i = count($chunks); $i < 3; $i++)
                    <td colspan="3" style="border: 1px solid #000000;"></td>
                @endfor
            @endif
            
            <!-- Sisa colspan penyeimbang -->
            <td colspan="1"></td>
        </tr>
    </tbody>
</table>