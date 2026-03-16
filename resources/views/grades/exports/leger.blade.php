<table>
    <thead>
        <!-- BARIS 1: Header Utama dengan Colspan & Rowspan -->
        <tr>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000;">NO</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; width: 30px;">NAMA SISWA</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; width: 15px;">NISN</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; width: 15px;">NIS</th>
            
            <!-- Colspan dinamis sebanyak jumlah mapel -->
            <th colspan="{{ count($subjects) }}" align="center" style="font-weight: bold; border: 1px solid #000; background-color: #8ea9db;">MATA PELAJARAN</th>
            
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; background-color: #ffd966;">Jml</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; background-color: #ffd966;">Rank</th>
            <th colspan="3" align="center" style="font-weight: bold; border: 1px solid #000; background-color: #8ea9db;">Ketidakhadiran</th>
        </tr>
        
        <!-- BARIS 2: Singkatan Mapel & Kehadiran -->
        <tr>
            @foreach($subjects as $subject)
                <!-- Gunakan Kode Mapel jika sudah diisi di menu Mata Pelajaran, jika kosong gunakan 3 huruf pertama -->
                <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">
                    {{ !empty($subject->code) ? strtoupper($subject->code) : strtoupper(substr($subject->name, 0, 3)) }}
                </th>
            @endforeach
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #e2efda;">Sakit</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #e2efda;">Izin</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #e2efda;">Alpa</th>
        </tr>
    </thead>
    <tbody>
        @php
            // HELPER: Fungsi untuk mengubah angka menjadi huruf Kolom Excel (Contoh: 1=>A, 5=>E)
            $getExcelColumn = function($num) {
                $letter = '';
                while ($num > 0) {
                    $modulo = ($num - 1) % 26;
                    $letter = chr(65 + $modulo) . $letter;
                    $num = intval(($num - $modulo) / 26);
                }
                return $letter;
            };

            $totalMapel = count($subjects);
            $totalSiswa = count($students);

            // Kolom Mapel dimulai dari E (Index 5)
            $lastMapelColNum = 4 + $totalMapel;
            $lastMapelCol = $getExcelColumn($lastMapelColNum);

            // Kolom Jumlah (Jml) ada tepat setelah Mapel terakhir
            $jmlColNum = $lastMapelColNum + 1;
            $jmlCol = $getExcelColumn($jmlColNum);

            // Baris terakhir data siswa (Baris 1 & 2 adalah Header)
            $lastRow = 2 + $totalSiswa;
        @endphp

        <!-- Loop Data Siswa -->
        @foreach($students as $index => $student)
            @php
                $rowNum = $index + 3; // Karena data siswa dimulai di baris ke-3 Excel
                $bgColor = $index % 2 == 0 ? '#ffffff' : '#f8fafc'; // Efek warna selang-seling (Zebra)
            @endphp
            
            <tr style="background-color: {{ $bgColor }};">
                <td align="center" style="border: 1px solid #000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $student->name }}</td>
                <td align="center" style="border: 1px solid #000;">{{ $student->student_id }}</td> <!-- NISN -->
                <td align="center" style="border: 1px solid #000;">{{ $student->nis ?? '' }}</td> <!-- NIS -->
                
                <!-- Ruang kosong untuk diisi nilai Mapel oleh guru -->
                @foreach($subjects as $subject)
                    <td align="center" style="border: 1px solid #000;"></td>
                @endforeach
                
                <!-- RUMUS OTOMATIS: Jml (SUM) dan Rank (RANK) -->
                <td align="center" style="border: 1px solid #000; font-weight: bold; color: #b45f06; background-color: #fff2cc;">
                    =SUM(E{{$rowNum}}:{{$lastMapelCol}}{{$rowNum}})
                </td>
                <td align="center" style="border: 1px solid #000; font-weight: bold; color: #b45f06; background-color: #fff2cc;">
                    =RANK({{$jmlCol}}{{$rowNum}}, {{$jmlCol}}$3:{{$jmlCol}}${{$lastRow}}, 0)
                </td>
                
                <!-- Kehadiran -->
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
            </tr>
        @endforeach
    </tbody>
</table>