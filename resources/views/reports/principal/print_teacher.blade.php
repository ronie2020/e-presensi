<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kinerja Guru - {{ $teacher->name }} - {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Injeksi Tema Microsoft Elevate --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        elevate: {
                            dark: '#032b5b',
                            primary: '#0d52a1',
                            accent: '#38bdf8',
                            soft: '#f0f7ff',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Phosphor Icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS F4 / A4 LANDSCAPE */
        @page { 
            size: 33cm 21.5cm; /* F4 Landscape */
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 10.5pt;
            background-color: #f1f5f9;
            color: #111827;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .sheet {
            font-family: 'Bookman Old Style', Bookman, Georgia, serif; 
            background: white;
            width: 33cm;
            min-height: 21.5cm;
            margin: 24px auto;
            padding: 1.5cm 2cm;
            box-sizing: border-box; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            position: relative;
            page-break-after: always; 
            page-break-inside: avoid;
        }

        .kop-surat { width: 100%; border-collapse: collapse; border: none; margin-bottom: 2px; }
        .kop-surat td, .kop-surat th { padding: 0; vertical-align: middle; border: none !important; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 4px; line-height: 1.1; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1.1; }
        .kop-alamat { font-size: 11pt; font-style: normal; line-height: 1.2; }
        .kop-kontak { font-size: 10.5pt; margin-top: 4px; }
        
        .garis-kop { 
            display: block;
            border: none; 
            border-top: 4px solid #000;
            border-bottom: 1.5px solid #000;
            height: 2px;
            background-color: transparent !important; 
            box-sizing: content-box !important;
            margin-top: 8px;
            margin-bottom: 20px; 
        }
        
        .no-print { display: block; }
        @media print {
            body { background: none; margin: 0; }
            .sheet { 
                width: 33cm; 
                margin: 0; 
                padding: 1.5cm 2cm;
                box-sizing: border-box; 
                box-shadow: none; 
                border: none; 
                page-break-after: always; 
                page-break-inside: avoid;
            }
            .sheet:last-child { page-break-after: auto; }
            .no-print { display: none !important; }
        }

        .judul-surat { text-align: center; margin-bottom: 16px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.03em; }
        .judul-surat h3 { margin: 3px 0 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; color: #374151; }
        .judul-surat p { margin: 5px 0 0; font-size: 10.5pt; font-weight: normal; }

        table.laporan-table { page-break-inside: auto; width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        table.laporan-table tr { page-break-inside: avoid; page-break-after: auto; }
        table.laporan-table th, table.laporan-table td { border: 1px solid #111; padding: 6px 8px; vertical-align: top; page-break-inside: avoid; }
        table.laporan-table th { background-color: #f1f5f9; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 9pt; }

        .kpi-container {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 9.5pt;
        }
        .kpi-box {
            flex: 1;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            border-radius: 6px;
            background: #fafafa;
        }
        .kpi-title { font-weight: bold; font-size: 8pt; text-transform: uppercase; color: #4b5563; }
        .kpi-value { font-size: 13pt; font-weight: bold; color: #0f172a; margin-top: 2px; }

        .ttd-box { float: right; width: 320px; text-align: center; margin-top: 25px; page-break-inside: avoid; }
        .clear { clear: both; }
    </style>
</head>
<body class="relative">

    <!-- TOOLBAR AKSI (Tidak tercetak) -->
    <div class="w-[33cm] mx-auto mt-6 mb-4 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl shadow-slate-900/5 border border-slate-200 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-slate-900 font-sans flex items-center gap-2 text-base">
                <i class="ph-bold ph-printer text-[#0d52a1] text-xl"></i> 
                Pratinjau Cetak: Kinerja Pembelajaran Guru
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">
                Guru: {{ $teacher->name }} &bull; Periode: {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            <button onclick="window.close(); if(!window.closed) window.history.back();" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#0d52a1] transition-colors shadow-sm flex items-center gap-2 group cursor-pointer">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-0.5 transition-transform"></i> Kembali
            </button>
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-gradient-to-r from-[#0d52a1] to-sky-600 text-white font-bold rounded-xl hover:from-sky-600 hover:to-[#0d52a1] shadow-lg shadow-sky-900/20 transition-transform active:scale-95 flex items-center gap-2 text-xs group cursor-pointer">
                <i class="ph-bold ph-printer text-base group-hover:scale-110 transition-transform"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- HALAMAN CETAK -->
    <div class="sheet">
        
        {{-- KOP SURAT RESMI --}}
        <table class="kop-surat">
            <tr>
                <td width="15%" style="text-align: center;">
                    <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" style="width: 85px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
                </td>
                <td width="70%" style="text-align: center;">
                    <div class="kop-dinas">PEMERINTAH KABUPATEN CIAMIS</div>
                    <div class="kop-sekolah">SMP NEGERI 3 LAKBOK</div>
                    <div class="kop-alamat">Jalan Mekarjaya No.199, Sidaharja</div>
                    <div class="kop-alamat">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                    <div class="kop-kontak">
                        Laman: <a href="http://www.smpn3lakbok.sch.id" style="color: #0d52a1; text-decoration: underline;">www.smpn3lakbok.sch.id</a> 
                        &nbsp;&nbsp;&bull;&nbsp;&nbsp;
                        E-mail: netila.smp@gmail.com
                    </div>
                </td>
                <td width="15%" style="text-align: center;">
                    <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo SMP" style="width: 90px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
                </td>
            </tr>
        </table>
        <hr class="garis-kop">

        <!-- JUDUL LAPORAN -->
        <div class="judul-surat">
            <h2>LAPORAN KINERJA PEMBELAJARAN GURU</h2>
            <h3>SUPERVISI KEPALA SEKOLAH</h3>
            <p>
                Nama Guru: <strong>{{ $teacher->name }}</strong> 
                @if($teacher->nip) (NIP: {{ $teacher->nip }}) @endif
                &bull; Periode: <strong>{{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</strong>
            </p>
        </div>

        <!-- KPI SUMMARY CARDS -->
        <div class="kpi-container">
            <div class="kpi-box">
                <div class="kpi-title">Total Sesi Mengajar</div>
                <div class="kpi-value">{{ $totalSessions }} <span style="font-size: 9.5pt; font-weight: normal; color: #64748b;">sesi</span></div>
            </div>
            <div class="kpi-box">
                <div class="kpi-title">Materi Selesai</div>
                <div class="kpi-value">{{ $completionRate }}% <span style="font-size: 9.5pt; font-weight: normal; color: #64748b;">({{ $completedMaterials }} tuntas)</span></div>
            </div>
            <div class="kpi-box">
                <div class="kpi-title">Total Siswa Hadir</div>
                <div class="kpi-value" style="color: #15803d;">{{ $totalHadir }} <span style="font-size: 9.5pt; font-weight: normal; color: #64748b;">siswa</span></div>
            </div>
            <div class="kpi-box">
                <div class="kpi-title">Sakit / Izin</div>
                <div class="kpi-value" style="color: #b45309;">{{ $totalSakit + $totalIzin }} <span style="font-size: 9.5pt; font-weight: normal; color: #64748b;">(S: {{ $totalSakit }}, I: {{ $totalIzin }})</span></div>
            </div>
            <div class="kpi-box">
                <div class="kpi-title">Alpha</div>
                <div class="kpi-value" style="color: #b91c1c;">{{ $totalAlpha }} <span style="font-size: 9.5pt; font-weight: normal; color: #64748b;">siswa</span></div>
            </div>
        </div>

        <!-- TABEL RINCIAN -->
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 12%;">Tanggal & Jam</th>
                    <th style="width: 18%;">Mata Pelajaran</th>
                    <th style="width: 8%;">Kelas</th>
                    <th style="width: 32%;">Topik Materi & Aktivitas</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 18%;">Kehadiran Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachings as $index => $session)
                    @php 
                        $hadir = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); 
                        $sakit = $session->sick_count ?? 0;
                        $izin = $session->permit_count ?? 0;
                        $alpha = $session->alpha_count ?? 0; 
                        
                        $subjectName = $session->subject->name ?? ($session->timetable->subject->name ?? ($session->schedule->subject->name ?? '-'));
                        $className = $session->schoolClass->name ?? ($session->timetable->studentClass->name ?? ($session->schedule->studentClass->name ?? '-'));
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">
                            <strong>{{ $session->date ? \Carbon\Carbon::parse($session->date)->format('d/m/Y') : '-' }}</strong><br>
                            <span style="font-size: 8.5pt; color: #475569;">
                                {{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }}
                                @if($session->ended_at) - {{ \Carbon\Carbon::parse($session->ended_at)->format('H:i') }} @endif WIB
                            </span>
                        </td>
                        <td><strong>{{ $subjectName }}</strong></td>
                        <td style="text-align: center;"><strong>{{ $className }}</strong></td>
                        <td style="text-align: justify;">
                            <strong>{{ $session->topic ?? 'Tanpa Topik' }}</strong>
                            @if($session->activities)
                                <br><span style="font-size: 8.5pt; color: #334155;">{{ $session->activities }}</span>
                            @endif
                            @if($session->homework_title)
                                <div style="margin-top: 4px; font-size: 8pt; background: #faf5ff; border: 1px dashed #d8b4fe; padding: 3px 6px; border-radius: 4px;">
                                    <strong>Tugas:</strong> {{ $session->homework_title }}
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($session->material_status == 'completed')
                                <span style="font-weight: bold; color: #166534;">Tuntas</span>
                            @elseif($session->material_status == 'incomplete')
                                <span style="font-weight: bold; color: #b45309;">Lanjut</span>
                            @else
                                <span style="color: #64748b;">-</span>
                            @endif
                        </td>
                        <td style="font-size: 8.5pt; line-height: 1.35;">
                            Hadir/Telat: <strong>{{ $hadir }}</strong><br>
                            Sakit/Izin: <strong>{{ $sakit + $izin }}</strong><br>
                            Alpha: <strong style="{{ $alpha > 0 ? 'color: #dc2626;' : '' }}">{{ $alpha }}</strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; font-style: italic; color: #64748b;">
                            Tidak ada data kegiatan belajar mengajar untuk guru ini pada periode yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- TANDA TANGAN KEPALA SEKOLAH -->
        <div class="ttd-box">
            <p>Lakbok, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>Kepala SMP Negeri 3 Lakbok,</p>
            <div style="height: 65px;"></div>
            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
            <p style="margin-top: 0; font-size: 9.5pt;">NIP. 19820928 201101 1 002</p>
        </div>
        <div class="clear"></div>

    </div>

</body>
</html>
