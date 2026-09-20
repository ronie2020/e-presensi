<table>
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN PEMBIASAAN IBADAH (SHOLAT {{ strtoupper($activity ?? $selectedActivity ?? 'DHUHA') }})</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">
                Tanggal: {{ $dateLabel ?? ($date ?? '') }}
            </th>
        </tr>
        @if(!empty($className))
        <tr>
            <th colspan="8" style="text-align: center; font-style: italic;">
                Kelas: {{ $className }}
            </th>
        </tr>
        @endif
        <tr>
            <th colspan="8"></th>
        </tr>
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <th style="border: 1px solid #000; text-align: center; width: 50px;">No</th>
            <th style="border: 1px solid #000; text-align: center; width: 100px;">Tanggal</th>
            <th style="border: 1px solid #000; text-align: left; width: 220px;">Nama Siswa</th>
            <th style="border: 1px solid #000; text-align: center; width: 120px;">NISN</th>
            <th style="border: 1px solid #000; text-align: center; width: 100px;">Kelas</th>
            <th style="border: 1px solid #000; text-align: center; width: 100px;">Kegiatan</th>
            <th style="border: 1px solid #000; text-align: center; width: 80px;">Waktu</th>
            <th style="border: 1px solid #000; text-align: center; width: 110px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances as $index => $item)
            @php
                $student = $item->student;
            @endphp
            <tr>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ \Carbon\Carbon::parse($item->attendance_date)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #cbd5e1;">{{ $student->name ?? 'Siswa Tidak Dikenal' }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center; mso-number-format:'\@';">{{ $student->nisn ?? ($student->student_id ?? '-') }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ $student->schoolClass->name ?? '-' }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center; font-weight: bold;">{{ $item->activity ?? $activity }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ $item->time_in ? \Carbon\Carbon::parse($item->time_in)->format('H:i') : '-' }}</td>
                <td style="border: 1px solid #cbd5e1; text-align: center; font-weight: bold;">{{ $item->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="border: 1px solid #cbd5e1; text-align: center; padding: 20px;">Tidak ada data kegiatan ibadah untuk filter ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>
