<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembagian Kelas 7 - {{ $year }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- INJEKSI TEMA MICROSOFT ELEVATE -->
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
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            margin: 0; 
        }
        
        body {
            /* Font default web (khusus toolbar dll yang diluar kertas) */
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            background-color: #f8fafc; /* Slate-50 */
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR & FONT BOOKMAN OLD STYLE */
        .sheet {
            font-family: 'Bookman Old Style', Bookman, Georgia, serif; 
            background: white;
            width: 21.5cm;
            min-height: 33cm;
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-sizing: border-box;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            /* Memaksa setiap kelas ganti halaman baru */
            page-break-after: always; 
            page-break-inside: avoid;
        }

        /* MODIFIKASI GARIS KOP SURAT */
        .garis-kop { border-bottom: 3px solid black; margin-bottom: 2px; }
        .garis-kop-bawah { border-bottom: 1px solid black; margin-bottom: 24px; }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { 
                width: 21.5cm; 
                margin: 0; 
                padding: 1cm 2cm; /* Margin fisik kertas saat dicetak */
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
        .judul-surat { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 25px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 14pt; }
        .judul-surat p { margin: 0; font-size: 12pt; font-weight: normal; text-transform: none; margin-top: 4px; }

        /* MENCEGAH TABEL TERPOTONG SAAT PRINT */
        table { page-break-inside: auto; }
        tr    { page-break-inside: avoid; page-break-after: auto; }
        td    { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; } /* Jika tabel panjang, header akan berulang */

        /* TABEL DATA KELAS */
        table.data-kelas { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 11pt; }
        table.data-kelas th, table.data-kelas td { border: 1px solid black; padding: 6px 8px; vertical-align: middle; }
        table.data-kelas th { background-color: #f1f5f9; font-weight: bold; text-align: center; }

        .ttd-box { float: right; width: 300px; text-align: center; margin-top: 30px; font-size: 11pt; }
        .clear { clear: both; }
    </style>
</head>
<body class="relative">

    <!-- DEKORASI BACKGROUND (Hanya tampil di layar monitor) -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI (Tidak tercetak) -->
    <div class="w-[21.5cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer text-elevate-primary text-xl"></i> Pratinjau Pembagian Kelas
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">Kertas: F4 (Folio) | Tiap kelas di-print di halaman baru.</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            <a href="{{ route('admin.ppdb.index', ['status' => 'accepted']) }}" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Cetak Dokumen
            </button>
        </div>
    </div>

    <!-- ==============================================
         LOOPING HALAMAN PER KELAS
         ============================================== -->
    @foreach($classes as $class)
        @if($class->students->count() > 0)
        <div class="sheet">
            
            <!-- KOP SURAT (Disesuaikan dengan format SPPD) -->
            <div class="kop-surat garis-kop pb-2 pt-2 flex justify-between items-center px-1">
                <!-- Logo Kiri -->
                <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" class="w-[85px] h-auto object-contain" onerror="this.style.display='none'"> 
                
                <!-- Teks Tengah -->
                <div class="text-center flex-1 px-4 leading-tight">
                    <div class="text-[14pt] tracking-wide mb-1">PEMERINTAH KABUPATEN CIAMIS</div>
                    <div class="font-bold text-[22pt] tracking-wider mb-1">SMP NEGERI 3 LAKBOK</div>
                    <div class="text-[12pt]">Jalan Mekarjaya No.199, Sidaharja</div>
                    <div class="text-[12pt]">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                    <div class="text-[11pt] mt-1">
                        Laman: <a href="http://www.smpn3lakbok.sch.id" style="color: blue; text-decoration: underline;">www.smpn3lakbok.sch.id</a> 
                        <span style="margin: 0 10px;"></span> 
                        E-mail: netila.smp@gmail.com
                    </div>
                </div>

                <!-- Logo Kanan -->
                <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" class="w-[85px] h-auto object-contain" onerror="this.style.display='none'">
            </div>
            <div class="garis-kop-bawah"></div>

            <!-- JUDUL -->
            <div class="judul-surat">
                <h2>PENGUMUMAN PEMBAGIAN KELAS 7</h2>
                <p>Tahun Pelajaran {{ $year }}/{{ $year + 1 }}</p>
            </div>

            <!-- IDENTITAS KELAS -->
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px;">
                <div style="font-size: 12pt; font-weight: bold;">
                    Kelas: <span style="font-size: 14pt; border: 1.5px solid #000; padding: 3px 15px; margin-left: 5px;">{{ $class->name }}</span>
                </div>
                <div style="font-size: 11pt; font-weight: normal;">
                    Wali Kelas: ....................................................
                </div>
            </div>

            <!-- TABEL SISWA -->
            <table class="data-kelas">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">NIS</th>
                        <th style="width: 15%;">NISN</th>
                        <th style="width: 35%;">Nama Lengkap</th>
                        <th style="width: 8%;">L/P</th>
                        <th style="width: 22%;">Asal Sekolah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($class->students as $index => $student)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">{{ $student->nis ?? '-' }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $student->nisn }}</td>
                        <td>{{ $student->name }}</td>
                        <td style="text-align: center;">{{ $student->gender }}</td>
                        <td>{{ $student->school_origin ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- REKAPITULASI -->
            <div style="font-size: 11pt; margin-top: 5px;">
                Jumlah Siswa Laki-laki <span style="display: inline-block; width: 15px;"></span>: {{ $class->students->where('gender', 'L')->count() }} Orang<br>
                Jumlah Siswa Perempuan : {{ $class->students->where('gender', 'P')->count() }} Orang<br>
                <b>Total Keseluruhan <span style="display: inline-block; width: 14px;"></span>: {{ $class->students->count() }} Orang</b>
            </div>

            <!-- TANDA TANGAN (SAMA SEPERTI SPPD) -->
            <div class="ttd-box">
                <p>Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin-top: 2px;">Kepala Sekolah,</p>
                <div style="height: 70px;"></div>
                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;"><strong>TANTAN SUTANDI N., S.Si, M.Pd.</strong></p>
                <p>NIP. 19820928 201101 1 002</p>
            </div>
            <div class="clear"></div>

        </div>
        @endif
    @endforeach

</body>
</html>