<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai</title>
</head>
<body>
    <table>
        <thead>
            {{-- JUDUL LAPORAN --}}
            <tr>
                <th colspan="{{ $assignments->count() + 5 }}" style="font-size: 16px; font-weight: bold; text-align: center; height: 30px;">
                    REKAPITULASI NILAI SISWA
                </th>
            </tr>
            <tr>
                <th colspan="{{ $assignments->count() + 5 }}" style="font-size: 12px; font-weight: bold; text-align: center;">
                    Kelas: {{ $selectedClass->name ?? '-' }} | Mata Pelajaran: {{ $selectedSubject->name ?? '-' }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $assignments->count() + 5 }}" style="font-size: 11px; font-style: italic; text-align: center;">
                    Dicetak pada: {{ date('d F Y') }} oleh {{ Auth::user()->name }}
                </th>
            </tr>
            
            <tr></tr> {{-- Spasi Kosong --}}

            {{-- HEADER TABEL (Baris 5) --}}
            <tr>
                <th style="width: 5px;">No</th>
                <th style="width: 35px;">Nama Siswa</th>
                <th style="width: 15px;">NISN</th>
                
                @foreach($assignments as $task)
                    {{-- Batasi panjang judul tugas biar kolom tidak terlalu lebar --}}
                    <th style="width: 15px;">{{ \Illuminate\Support\Str::limit($task->title, 15) }}</th>
                @endforeach

                <th style="width: 10px;">Total</th>
                <th style="width: 10px;">Rata2</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                @php
                    $total = 0; 
                    $count = 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->nisn ?? $student->student_id }}</td>
                    
                    @foreach($assignments as $task)
                        @php
                            $score = $gradeBook[$student->id][$task->id] ?? null;
                            if($score !== null) { 
                                $total += $score; 
                                $count++; 
                            }
                        @endphp
                        <td>{{ $score ?? '-' }}</td>
                    @endforeach

                    <td style="font-weight: bold;">{{ $total }}</td>
                    <td style="font-weight: bold;">{{ $count > 0 ? round($total / $count, 1) : 0 }}</td>
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
                {{-- Spasi Tengah (sesuaikan colspan dengan jumlah tugas) --}}
                <td colspan="{{ $assignments->count() + 1 }}"></td> 
                <td colspan="2" style="text-align: center;">
                    {{-- Tanggal Lokal Indonesia --}}
                    Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Kepala Sekolah</td>
                <td colspan="{{ $assignments->count() + 1 }}"></td>
                <td colspan="2" style="text-align: center;">Guru Mata Pelajaran</td>
            </tr>
            
            {{-- Spasi Tanda Tangan --}}
            <tr style="height: 60px;"></tr> 

            <tr>
                <td></td>
                <td style="text-align: center; font-weight: bold; text-decoration: underline;">
                    {{-- Ganti nama kepsek sesuai data real --}}
                    TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.
                </td>
                <td colspan="{{ $assignments->count() + 1 }}"></td>
                <td colspan="2" style="text-align: center; font-weight: bold; text-decoration: underline;">
                    {{ Auth::user()->name }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">NIP. 197xxxxxxxxxxxxx</td>
                <td colspan="{{ $assignments->count() + 1 }}"></td>
                <td colspan="2" style="text-align: center;">NIP. {{ Auth::user()->nip ?? '-' }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>