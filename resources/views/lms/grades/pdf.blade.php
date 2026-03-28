<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai - {{ $selectedClass->name }} - {{ $selectedSubject->name }}</title>
    <style>
        /* Mengatur halaman Landscape agar tabel nilai muat */
        @page { 
            size: A4 landscape; 
            margin: 1cm 1.5cm 1cm 1.5cm; 
        }

        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.3; 
            color: #000; 
        }

        /* HEADER / KOP SURAT */
        .header { 
            text-align: center; 
            border-bottom: 3px double #000; 
            padding-bottom: 8px; 
            margin-bottom: 20px; 
            position: relative;
        }
        
        /* Logo diposisikan absolute agar tidak merusak centering teks */
        .logo { 
            width: 75px; 
            height: auto; 
            position: absolute; 
            left: 20px; 
            top: 5px; 
        }
        
        .header h3 { margin: 0; font-size: 14pt; font-weight: normal; }
        .header h2 { margin: 0; font-size: 16pt; font-weight: bold; }
        .header h1 { margin: 0; font-size: 18pt; font-weight: bold; }
        .header p { margin: 0; font-size: 9pt; font-style: italic; }

        /* JUDUL LAPORAN */
        .title { 
            font-size: 14pt; 
            font-weight: bold; 
            text-decoration: underline; 
            text-align: center; 
            margin-bottom: 20px; 
            text-transform: uppercase;
        }

        /* INFO KELAS */
        .info-table { width: 100%; margin-bottom: 15px; font-size: 11pt; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .label { width: 150px; font-weight: bold; }
        .colon { width: 15px; text-align: center; }

        /* TABEL NILAI */
        .grade-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            font-size: 10pt; 
        }
        .grade-table th, .grade-table td { 
            border: 1px solid #000; 
            padding: 6px 4px; 
            text-align: center; 
        }
        .grade-table th { 
            background-color: #f0f0f0; 
            font-weight: bold; 
            vertical-align: middle;
        }
        .grade-table td.left { text-align: left; padding-left: 8px; }
        .grade-table td.name-col { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }

        /* TANDA TANGAN */
        .signature-section { 
            width: 100%; 
            margin-top: 30px; 
            display: table; /* Pengganti Flexbox untuk PDF support yang lebih baik */
        }
        .sign-box { 
            display: table-cell; 
            width: 33%; 
            text-align: center; 
            vertical-align: top;
        }
        .sign-space { height: 70px; }
        .sign-name { font-weight: bold; text-decoration: underline; }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="header">
        <!-- Pastikan path logo benar -->
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        
        <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
        <h2>DINAS PENDIDIKAN</h2>
        <h1>SMP NEGERI 3 LAKBOK</h1>
        <p>Alamat: Jl. Mekarjaya No. 199, Desa Sidaharja, Kec. Lakbok, Kab. Ciamis 46385</p>
    </div>

    <h3 class="title">REKAPITULASI NILAI SISWA</h3>

     <!-- INFO KELAS -->
    <table class="info-table">
        <tr>
            <td class="label">Mata Pelajaran</td><td class="colon">:</td><td width="40%">{{ $selectedSubject->name }}</td>
            <td class="label">Kelas</td><td class="colon">:</td><td>{{ $selectedClass->name }}</td>
        </tr>
        <tr>
            <td class="label">Guru Pengampu</td><td class="colon">:</td><td>{{ $teacher->name }}</td>
            {{-- FITUR BARU: Tambahkan info periode ke Kop Surat PDF --}}
            <td class="label">Periode Waktu</td><td class="colon">:</td><td>{{ ucfirst($selectedPeriod ?? 'Semester') }}</td>
        </tr>
        <tr>
            <td class="label">Tahun Ajaran</td><td class="colon">:</td><td>{{ date('Y') }}/{{ date('Y')+1 }}</td>
            <td></td><td></td><td></td>
        </tr>
    </table>

    <!-- TABEL NILAI -->
    <table class="grade-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Siswa</th>
                <th width="80">NISN</th>
                
                {{-- Loop Header Tugas (Max 10 kolom agar muat di PDF) --}}
                @foreach($assignments->take(10) as $task)
                    <th style="font-size: 8pt;">
                        {{ \Illuminate\Support\Str::limit($task->title, 15) }}
                        <br>
                        <span style="font-weight: normal; font-size: 7pt;">({{ $task->created_at->format('d/m') }})</span>
                    </th>
                @endforeach

                {{-- PERBAIKAN: Judul berubah sesuai periode filter --}}
                <th width="60">{{ ($selectedPeriod ?? 'semester') == 'semester' ? 'Nilai Rapor' : 'Rata2' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left name-col">{{ $student->name }}</td>
                    {{-- Sinkronisasi pemanggilan ID siswa --}}
                    <td>{{ $student->nisn ?? $student->student_id ?? '-' }}</td>
                    
                    {{-- Loop data kolom (Hanya 10 pertama yang dicetak) --}}
                    @foreach($assignments->take(10) as $task)
                        @php
                            $score = $gradeBook[$student->id][$task->id] ?? null;
                        @endphp
                        <td>{{ $score ?? '-' }}</td>
                    @endforeach

                    {{-- PERBAIKAN: Hapus blok foreach perhitungan yang berat, panggil langsung average_score --}}
                    <td style="font-weight: bold; background-color: #f9f9f9;">
                        {{ $student->average_score ?? 0 }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <!-- Kiri: Mengetahui -->
        <div class="sign-box">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="sign-space"></div>
            <p class="sign-name">{{ $headmaster ?? 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.' }}</p>
            <p>NIP. {{ $headmaster_nip ?? '-' }}</p>
        </div>

        <!-- Tengah: Kosong (Spasi) -->
        <div class="sign-box"></div>

        <!-- Kanan: Guru -->
        <div class="sign-box">
            <p>Lakbok, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>Guru Mata Pelajaran</p>
            <div class="sign-space"></div>
            <p class="sign-name">{{ $teacher->name }}</p>
            <p>NIP. {{ $teacher->nip ?? '-' }}</p>
        </div>
    </div>

    <div class="footer">
        Dicetak melalui Sistem Informasi Sekolah SMPN 3 Lakbok pada {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>