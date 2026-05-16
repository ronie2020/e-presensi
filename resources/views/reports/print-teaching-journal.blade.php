<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Jurnal Mengajar</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- INJEKSI TEMA MICROSOFT ELEVATE UNTUK TOOLBAR -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        elevate: {
                            dark: '#032b5b',
                            primary: '#3b5889',
                            accent: '#38bdf8',
                            text: '#1e293b',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Phosphor Icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS F4 (Folio) LANDSCAPE */
        @page { 
            size: 33cm 21.5cm; /* F4 Landscape */
            margin: 0; 
        }
        
        body {
            /* Font default web (khusus toolbar dll yang diluar kertas) */
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            background-color: #f8fafc; /* Slate-50 */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR & FONT BOOKMAN OLD STYLE */
        .sheet {
            font-family: 'Bookman Old Style', Bookman, Georgia, serif; 
            background: white;
            width: 33cm; /* Lebar F4 Landscape */
            min-height: 21.5cm; /* Tinggi F4 Landscape */
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-sizing: border-box; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            page-break-after: always; 
            page-break-inside: avoid;
        }

        /* --- KOP SURAT STYLE --- */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 4px; line-height: 1.1; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1.1; }
        .kop-alamat { font-size: 12pt; font-style: normal; line-height: 1.2; }
        .kop-kontak { font-size: 11pt; margin-top: 4px; }
        .garis-kop { border: none; border-top: 4px solid #000; border-bottom: 1.5px solid #000; height: 2px; background-color: transparent; margin-bottom: 24px; }
        
        /* MODE PRINT */
        .no-print { display: block; }
        @media print {
            body { background: none; margin: 0; }
            .sheet { 
                width: 33cm; 
                margin: 0; 
                padding: 1.5cm 2cm; /* Margin untuk cetakan */
                box-sizing: border-box; 
                box-shadow: none; 
                border: none; 
                page-break-after: always; 
                page-break-inside: avoid;
            }
            .sheet:last-child { page-break-after: auto; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY SURAT */
        .judul-surat { text-align: center; margin-bottom: 20px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .judul-surat p { margin: 5px 0 0; font-size: 11pt; font-weight: normal; }

        /* MENCEGAH TABEL TERPOTONG SAAT PRINT */
        table { page-break-inside: auto; width: 100%; border-collapse: collapse; font-size: 10pt; }
        tr    { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid black; padding: 8px; vertical-align: top; page-break-inside: avoid; page-break-after: auto; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }

        /* UTILITAS */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .col-no { width: 4%; text-align: center; }
        .col-tgl { width: 12%; text-align: center; }
        .col-guru { width: 22%; }
        .col-kelas { width: 8%; text-align: center; }
        .col-materi { width: 38%; text-align: justify; }
        .col-hadir { width: 16%; text-align: left; }

        .ttd-box { float: right; width: 350px; text-align: center; margin-top: 30px; page-break-inside: avoid; }
        .clear { clear: both; }
    </style>
</head>
<body class="relative">

    <!-- DEKORASI BACKGROUND (Hanya tampil di layar) -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI (Tidak tercetak) - Tema Microsoft Elevate -->
    <div class="w-[33cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer text-elevate-primary text-xl"></i> Pratinjau Laporan Jurnal
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">Kertas: F4 / Folio (Landscape)</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            <!-- Tombol Kembali menggunakan history back agar fleksibel -->
            <button onclick="window.history.back()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </button>
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Cetak / PDF
            </button>
        </div>
    </div>

    <!-- ==============================================
         HALAMAN LAPORAN
         ============================================== -->
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
                        Laman: <a href="http://www.smpn3lakbok.sch.id" style="color: #1d4ed8; text-decoration: underline;">www.smpn3lakbok.sch.id</a> 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
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
            <h2>LAPORAN REKAPITULASI JURNAL MENGAJAR</h2>
            <p>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}</strong></p>
        </div>

        <!-- TABEL DATA -->
        <table>
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-tgl">Tanggal & Waktu</th>
                    <th class="col-guru">Nama Guru & Mapel</th>
                    <th class="col-kelas">Kelas</th>
                    <th class="col-materi">Topik Materi & Aktivitas</th>
                    <th class="col-hadir">Kehadiran Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $index => $session)
                    @php 
                        $hadir = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); 
                        $sakit = $session->sick_count ?? 0;
                        $izin = $session->permission_count ?? 0;
                        $alpha = $session->alpha_count ?? 0; 
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">
                            <span class="text-bold">{{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</span><br>
                            {{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }} WIB
                        </td>
                        <td>
                            <span class="text-bold">{{ $session->teacher->name ?? '-' }}</span><br>
                            Mapel: {{ $session->schedule->subject->name ?? '-' }}
                        </td>
                        <td class="text-center">{{ $session->schedule->schoolClass->name ?? '-' }}</td>
                        <td class="col-materi">
                            <span class="text-bold">{{ $session->topic ?? 'Tanpa Topik' }}</span><br>
                            <span style="font-size: 9.5pt;">{{ $session->activities ?? '-' }}</span>
                        </td>
                        <td style="font-size: 9.5pt; line-height: 1.4;">
                            Hadir/Telat: <strong>{{ $hadir }}</strong> Siswa<br>
                            Sakit/Izin: <strong>{{ $sakit + $izin }}</strong> Siswa<br>
                            Alpha: <strong>{{ $alpha }}</strong> Siswa
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px;">
                            <em>Tidak ada data jurnal mengajar pada periode atau filter yang dipilih.</em>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- AREA TANDA TANGAN (Otomatis pindah ke halaman baru jika tidak muat) -->
        <div class="ttd-box">
            <p>Lakbok, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>Kepala Sekolah,</p>
            <div style="height: 70px;"></div>
            <p class="text-bold" style="text-decoration: underline; margin-bottom: 2px;">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
            <p style="margin-top: 0;">NIP. 19820928 201101 1 002</p>
        </div>
        <div class="clear"></div>

    </div>

</body>
</html>