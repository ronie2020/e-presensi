<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan BK - {{ \Carbon\Carbon::now()->format('d-m-Y') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- INJEKSI TEMA MICROSOFT ELEVATE UNTUK HALAMAN STANDALONE -->
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
        /* PENGATURAN KERTAS F4 (Folio) - Landscape untuk Tabel Lebar */
        @page { 
            size: 33cm 21.5cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            background-color: #f8fafc; /* Slate-50 */
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 33cm;
            min-height: 21.5cm;
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            page-break-after: always; 
        }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; padding: 0; }
            .sheet { width: 100%; margin: 0; padding: 1cm 1.5cm; box-shadow: none; border: none; page-break-after: always; }
            .sheet:last-child { page-break-after: auto; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY SURAT */
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-text h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; }
        
        .double-line { border-top: 4px double #000; margin-top: 8px; margin-bottom: 20px; }
        
        .judul-surat { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 13pt; }
        .judul-surat p { margin: 0; font-size: 11pt; font-weight: normal; text-transform: none; }

        /* TABEL DATA UTAMA */
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid black; padding: 8px 5px; vertical-align: top; }
        table.data th { background-color: #f2f2f2 !important; font-weight: bold; text-align: center; text-transform: uppercase; }
        
        .text-center { text-align: center; }
        .text-italic { font-style: italic; }
        
        /* FOOTER TTD */
        .footer-section { margin-top: 30px; width: 100%; }
        .ttd-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .ttd-box { width: 48%; text-align: center; }
        
        .clear { clear: both; }
    </style>
</head>
<body class="relative">

    <!-- DEKORASI BACKGROUND (Hanya tampil di layar) -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR (Floating) - TEMA ELEVATE -->
    <div class="no-print w-[33cm] mx-auto mt-6 mb-6 bg-white/80 backdrop-blur-md border border-white/60 shadow-lg shadow-elevate-dark/5 p-4 flex justify-between items-center rounded-2xl sticky top-4 z-50">
        <div class="flex items-center gap-4 font-sans">
            <div class="bg-elevate-primary p-2.5 rounded-xl text-white shadow-lg shadow-elevate-primary/20">
                <i class="ph-bold ph-printer text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-elevate-dark text-sm md:text-base">Pratinjau Laporan Konseling</h1>
                <p class="text-xs text-slate-500 font-bold">Format: Kedinasan (Folio Landscape)</p>
            </div>
        </div>
        <div class="flex gap-3 font-sans">
            <button onclick="window.close()" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-x group-hover:scale-110 transition-transform"></i> Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-elevate-primary rounded-xl hover:bg-elevate-dark transition-all shadow-lg shadow-elevate-primary/30 flex items-center gap-2 active:scale-95 group">
                <i class="ph-bold ph-printer group-hover:scale-110 transition-transform"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- HALAMAN REKAPITULASI -->
    <div class="sheet">
         <!-- KOP SURAT (Sesuai Konsep SPPD) -->
        <div class="relative py-2 flex items-center justify-center">
            <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Daerah" class="absolute left-0 top-1 w-16 h-auto object-contain" onerror="this.style.display='none'"> 
            <div class="text-center header-text">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
            </div>
            <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" class="absolute right-0 top-1 w-20 h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <div class="double-line"></div>

        <div class="judul-surat">
            <h2>REKAPITULASI DATA BIMBINGAN KONSELING (BK)</h2>
            <p>Periode Laporan: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>

        <!-- INFO FILTER -->
        <div class="mb-4 grid grid-cols-2 text-xs font-bold uppercase" style="font-family: sans-serif;">
            <div>
                <p>Status: {{ request('status') ? ucfirst(request('status')) : 'SEMUA STATUS' }}</p>
                <p>Tipe: {{ request('type') ? ucfirst(request('type')) : 'SEMUA TIPE' }}</p>
            </div>
            <div class="text-right">
                <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
                <p>Total Data: {{ $sessions->count() }} Laporan</p>
            </div>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 150px;">Nama Siswa</th>
                    <th style="width: 80px;">Kelas</th>
                    <th style="width: 120px;">Kategori</th>
                    <th>Pesan / Permasalahan</th>
                    <th style="width: 100px;">Metode</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 100px;">Tgl Lapor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $index => $session)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="text-transform: uppercase; font-weight: bold;">{{ $session->student->name ?? 'Data Terhapus' }}</td>
                    <td class="text-center">{{ $session->student->schoolClass->name ?? '-' }}</td>
                    <td class="text-center">{{ $session->category->name ?? 'Umum' }}</td>
                    <td class="text-italic">
                        @if($session->is_system_generated)
                            <strong>[SISTEM]</strong> 
                        @endif
                        "{{ $session->initial_message }}"
                    </td>
                    <td class="text-center">{{ $session->method == 'online' ? 'Online' : 'Tatap Muka' }}</td>
                    <td class="text-center" style="font-weight: bold; text-transform: uppercase; font-size: 9pt;">
                        {{ $session->status == 'approved' ? 'Terjadwal' : $session->status }}
                    </td>
                    <td class="text-center">{{ $session->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 30px;">Tidak ada data yang tersedia untuk laporan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- BAGIAN TANDA TANGAN -->
        <div class="footer-section">
            <div class="ttd-container">
                <div class="ttd-box">
                    <p>Mengetahui,</p>
                    <p class="mb-16">Kepala Sekolah</p>
                    <p style="font-weight: bold; text-decoration: underline; white-space: nowrap;">TANTAN SUTANDI N., S.Pd., M.Pd</p>
                    <p>NIP. 19820928 201101 1 002</p>
                </div>
                <div class="ttd-box">
                    <p>Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p class="mb-16">Guru Bimbingan Konseling</p>
                    <p style="font-weight: bold; text-decoration: underline; white-space: nowrap;">{{ auth()->user()->name ?? '( ........................................... )' }}</p>
                    <p>NIP. .....................................</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>