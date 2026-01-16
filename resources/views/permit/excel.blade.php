<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN IZIN KELUAR SISWA</th>
        </tr>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Alasan</th>
            <th>Catatan</th>
            <th>Waktu Keluar</th>
            <th>Waktu Kembali</th>
            <th>Durasi (Menit)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permits as $index => $permit)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $permit->student->student_id }}</td>
            <td>{{ $permit->student->name }}</td>
            <td>{{ $permit->student->schoolClass->name ?? '-' }}</td>
            <td>{{ $permit->reason_category }}</td>
            <td>{{ $permit->notes }}</td>
            <td>{{ $permit->time_out }}</td>
            <td>{{ $permit->time_in }}</td>
            <td>{{ $permit->duration_minutes }}</td>
            <td>{{ $permit->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>