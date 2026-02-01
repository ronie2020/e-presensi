<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center;">REKAPITULASI ABSENSI PER KELAS</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                Periode: {{ $startDate }} s/d {{ $endDate }}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama Kelas</th>
            <th>Jumlah Siswa</th>
            <th>Hadir</th>
            <th>Terlambat</th>
            <th>Izin / Sakit</th>
            <th>Alpha</th>
            <th>Persentase Kehadiran (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportData as $index => $data)
        <tr>
            <td style="text-align: center;">{{ $loop->iteration }}</td>
            <td>{{ $data->name }}</td>
            <td style="text-align: center;">{{ $data->total_students }}</td>
            <td style="text-align: center;">{{ $data->hadir }}</td>
            <td style="text-align: center;">{{ $data->telat }}</td>
            <td style="text-align: center;">{{ $data->izin_sakit }}</td>
            <td style="text-align: center;">{{ $data->alpha }}</td>
            <td style="text-align: center;">{{ $data->rate }}</td>
        </tr>
        @endforeach
    </tbody>
</table>