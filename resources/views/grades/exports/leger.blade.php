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
            
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000;">Jml</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000;">Rank</th>
            <th colspan="3" align="center" style="font-weight: bold; border: 1px solid #000; background-color: #8ea9db;">Ketidakhadiran</th>
        </tr>
        
        <!-- BARIS 2: Singkatan Mapel & Kehadiran -->
        <tr>
            @foreach($subjects as $subject)
                <!-- Gunakan Kode Mapel jika ada, jika kosong gunakan 3 huruf pertama -->
                <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">
                    {{ $subject->code ? strtoupper($subject->code) : strtoupper(substr($subject->name, 0, 3)) }}
                </th>
            @endforeach
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">Sakit</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">Izin</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">Alpa</th>
        </tr>
    </thead>
    <tbody>
        <!-- Loop Data Siswa -->
        @foreach($students as $index => $student)
            <tr>
                <td align="center" style="border: 1px solid #000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $student->name }}</td>
                <td align="center" style="border: 1px solid #000;">{{ $student->student_id }}</td> <!-- NISN -->
                <td align="center" style="border: 1px solid #000;">{{ $student->nis ?? '' }}</td> <!-- Jika ada field NIS terpisah -->
                
                <!-- Ruang kosong untuk diisi nilai Mapel oleh guru -->
                @foreach($subjects as $subject)
                    <td align="center" style="border: 1px solid #000;"></td>
                @endforeach
                
                <!-- Ruang kosong untuk Jml, Rank, Kehadiran -->
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
            </tr>
        @endforeach
    </tbody>
</table>