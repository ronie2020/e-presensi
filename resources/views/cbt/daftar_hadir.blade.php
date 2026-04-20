<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Hadir - {{ $exam->title }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Phosphor Icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            background-color: #f1f5f9; /* Slate-100 */
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 21.5cm;
            min-height: 33cm;
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            page-break-after: always; 
        }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { width: 100%; margin: 0; padding: 1cm 2cm; box-shadow: none; border: none; page-break-after: always; }
            .sheet:last-child { page-break-after: auto; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY SURAT */
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-text h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; }
        
        .double-line { border-top: 4px double #000; margin-top: 8px; margin-bottom: 20px; }
        
        .judul-surat { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 12pt; }
        .judul-surat p { margin: 0; font-size: 11pt; font-weight: normal; text-transform: none; }

        /* TABEL DATA */
        table.info-ujian { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.info-ujian td { padding: 3px 5px; vertical-align: top; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11pt; }
        table.data th { background-color: #f3f4f6 !important; text-align: center; font-weight: bold; padding: 8px; }
        table.data td { border: 1px solid black; padding: 6px; }
        table.data th { border: 1px solid black; }
    </style>
</head>
<body>

    <!-- TOOLBAR (Floating) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-blue-900 p-2.5 rounded-xl text-white shadow-lg shadow-blue-900/20">
                <i class="ph-bold ph-users-three text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-slate-800 text-sm md:text-base font-sans">Pratinjau Daftar Hadir</h1>
                <p class="text-xs text-slate-500 font-sans font-bold">{{ $exam->title }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('cbt.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-blue-900 rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/30 font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak Dokumen
            </button>
        </div>
    </div>
    <div class="no-print h-24"></div>

    <div class="sheet">
         <!-- KOP SURAT -->
        <div class="relative py-2">
            <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" class="absolute left-0 top-1 w-16 h-auto object-contain" onerror="this.style.display='none'"> 
            <div class="text-center header-text mx-auto w-3/4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
            </div>
            <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" class="absolute right-0 top-1 w-20 h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <div class="double-line"></div>

        <div class="judul-surat">
            <h2>DAFTAR HADIR PESERTA UJIAN</h2>
            <p>Tahun Pelajaran {{ date('Y') }}/{{ date('Y', strtotime('+1 year')) }}</p>
        </div>

        <!-- Info Ujian -->
        <table class="info-ujian">
            <tr>
                <td style="width: 15%;">Mata Pelajaran</td><td style="width: 2%;">:</td><td style="width: 43%; font-weight: bold;">{{ $exam->subject_name }}</td>
                <td style="width: 12%;">Tanggal</td><td style="width: 2%;">:</td><td style="width: 26%;">{{ \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
            </tr>
            <tr>
                <td>Kelas Target</td><td>:</td><td style="font-weight: bold;">{{ $exam->class_level }}</td>
                <td>Waktu</td><td>:</td><td>{{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('H:i') : '-' }} - {{ $exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->format('H:i') : '-' }} WIB</td>
            </tr>
        </table>

        <!-- Tabel Peserta -->
        <table class="data">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 130px;">NISN/NIS</th>
                    <th>Nama Peserta</th>
                    <th style="width: 80px;">Kelas</th>
                    <th colspan="2" style="width: 160px;">Tanda Tangan</th>
                    <th style="width: 80px;">Ket</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $student->student_id }}</td>
                    <td style="font-weight: bold;">{{ $student->name }}</td>
                    <td style="text-align: center;">{{ $student->schoolClass->name ?? '-' }}</td>
                    
                    <!-- Kolom Tanda Tangan Zig-Zag -->
                    <td style="border-right: none; position: relative; height: 35px; width: 80px;">
                        @if($index % 2 == 0)
                            <span style="position: absolute; top: 4px; left: 6px; font-size: 10px; color: #666;">{{ $index + 1 }}.</span>
                        @endif
                    </td>
                    <td style="border-left: none; position: relative; height: 35px; width: 80px;">
                        @if($index % 2 != 0)
                            <span style="position: absolute; top: 4px; left: 6px; font-size: 10px; color: #666;">{{ $index + 1 }}.</span>
                        @endif
                    </td>
                    
                    <!-- Keterangan Hadir -->
                    <td style="text-align: center; font-size: 14pt; font-weight: bold; color: #16a34a;">
                        @if(in_array($student->id, $sessions))
                            &#10003;
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #6b7280;">Tidak ada data siswa ditemukan untuk kelas ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan Pengawas -->
        <div style="margin-top: 40px; float: right; width: 250px; text-align: center;">
            <p>Pengawas Ujian,</p>
            <div style="height: 70px;"></div>
            <p style="font-weight: bold; text-decoration: underline;">(..............................................)</p>
            <p>NIP. </p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>