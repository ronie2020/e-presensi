<table>
    <thead>
        <tr>
            <th colspan="9" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN PRESENSI HARIAN SISWA</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center; font-weight: bold;">
                Periode: {{ $dateLabel ?? (($startDate ?? '') . (!empty($endDate) ? ' s/d ' . $endDate : '')) }}
            </th>
        </tr>
        @if(!empty($className))
        <tr>
            <th colspan="9" style="text-align: center; font-style: italic;">
                Kelas: {{ $className }}
            </th>
        </tr>
        @endif
        <tr>
            <th colspan="9"></th>
        </tr>
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <th style="border: 1px solid #000; text-align: center; width: 50px;">No</th>
            <th style="border: 1px solid #000; text-align: center; width: 100px;">Tanggal</th>
            <th style="border: 1px solid #000; text-align: left; width: 220px;">Nama Siswa</th>
            <th style="border: 1px solid #000; text-align: center; width: 120px;">NISN</th>
            <th style="border: 1px solid #000; text-align: center; width: 100px;">Kelas</th>
            <th style="border: 1px solid #000; text-align: center; width: 80px;">Masuk</th>
            <th style="border: 1px solid #000; text-align: center; width: 80px;">Pulang</th>
            <th style="border: 1px solid #000; text-align: center; width: 110px;">Status</th>
            <th style="border: 1px solid #000; text-align: left; width: 200px;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances as $index => $item)
            @php
                $student = $item->student;
                $statusText = $item->status;
                if ($item->is_late) {
                    $statusText = 'Terlambat';
                }
            @endphp
            <tr>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ \Carbon\Carbon::parse($item->attendance_date)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #cbd5e1;">{{ $student->name ?? 'Siswa Tidak Dikenal' }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center; mso-number-format:'\@';">{{ $student->nisn ?? ($student->student_id ?? '-') }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ $student->schoolClass->name ?? '-' }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ $item->time_in ? \Carbon\Carbon::parse($item->time_in)->format('H:i') : '-' }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ $item->time_out ? \Carbon\Carbon::parse($item->time_out)->format('H:i') : '-' }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center; font-weight: bold;">{{ $statusText }}</td>
                <td style="border: 1px solid #cbd5e1;">{{ $item->notes ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="border: 1px solid #cbd5e1; text-align: center; padding: 20px;">Tidak ada data presensi untuk periode ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>
