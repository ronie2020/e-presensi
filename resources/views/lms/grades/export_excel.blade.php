<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai</title>
</head>
<body>
    @php
        // Optimasi: Mendefinisikan variabel colspan agar kode lebih bersih
        // Ditambah 1 karena kita menambahkan kolom "Kelas"
        $totalColumns = $assignments->count() + 6; 
        $spasiTengah = $assignments->count() + 2;
    @endphp

    <table>
        <thead>
            {{-- JUDUL LAPORAN --}}
            <tr>
                <th colspan="{{ $totalColumns }}" style="font-size: 16px; font-weight: bold; text-align: center; height: 30px;">
                    REKAPITULASI NILAI SISWA
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalColumns }}" style="font-size: 12px; font-weight: bold; text-align: center;">
                    Tingkat: {{ $selectedLevel->name ?? 'Semua' }} | Kelas: {{ $selectedClass->name ?? 'Semua' }} | Mata Pelajaran: {{ $selectedSubject->name ?? '-' }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalColumns }}" style="font-size: 11px; font-style: italic; text-align: center;">
                    Dicetak pada: {{ date('d F Y') }} oleh {{ $teacher->name ?? Auth::user()->name ?? '-' }} | Periode: {{ ucfirst($selectedPeriod ?? 'Semester') }}
                </th>
            </tr>
            
            <tr></tr> {{-- Spasi Kosong --}}

           {{-- HEADER TABEL (Baris 5) --}}
            <tr>
                <th style="width: 5px;">No</th>
                <th style="width: 35px;">Nama Siswa</th>
                <th style="width: 15px;">NISN</th>
                <th style="width: 15px;">Kelas</th> {{-- KOLOM BARU --}}
                
                @foreach($assignments as $task)
                    {{-- Batasi panjang judul tugas biar kolom tidak terlalu lebar --}}
                    <th style="width: 15px;">{{ \Illuminate\Support\Str::limit($task->title, 15) }}</th>
                @endforeach

                <th style="width: 10px;">Total</th>
                <th style="width: 15px;">{{ ($selectedPeriod ?? 'semester') == 'semester' ? 'Nilai Rapor' : 'Rata2' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->nisn ?? $student->student_id ?? '-' }}</td>
                    
                    {{-- PERBAIKAN: class menjadi schoolClass --}}
                    <td>{{ $student->schoolClass->name ?? '-' }}</td> 
                    
                    @foreach($assignments as $task)
                        @php $score = $gradeBook[$student->id][$task->id] ?? null; @endphp
                        <td>{{ $score ?? '-' }}</td>
                    @endforeach

                    <td style="font-weight: bold;">{{ $student->total_score ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $student->average_score ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>

        {{-- BAGIAN TANDA TANGAN (FOOTER) --}}
        <tfoot>
            <tr></tr>
            <tr></tr>
            <tr>
                <td></td> {{-- Spasi Kiri --}}
                <td style="text-align: center;">Mengetahui,</td>
                {{-- Spasi Tengah menggunakan variabel yang sudah disiapkan --}}
                <td colspan="{{ $spasiTengah }}"></td> 
                <td colspan="2" style="text-align: center;">
                    {{-- Tanggal Lokal Indonesia --}}
                    Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Kepala Sekolah</td>
                <td colspan="{{ $spasiTengah }}"></td>
                <td colspan="2" style="text-align: center;">Guru Mata Pelajaran</td>
            </tr>
            
            {{-- Spasi Tanda Tangan --}}
            <tr style="height: 60px;"></tr> 

            <tr>
                <td></td>
                <td style="text-align: center; font-weight: bold; text-decoration: underline;">
                    {{ $headmaster ?? 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.' }}
                </td>
                <td colspan="{{ $spasiTengah }}"></td>
                <td colspan="2" style="text-align: center; font-weight: bold; text-decoration: underline;">
                    {{ $teacher->name ?? Auth::user()->name ?? '-' }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">NIP. {{ $headmaster_nip ?? '197xxxxxxxxxxxxx' }}</td>
                <td colspan="{{ $spasiTengah }}"></td>
                <td colspan="2" style="text-align: center;">NIP. {{ $teacher->nip ?? Auth::user()->nip ?? '-' }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>