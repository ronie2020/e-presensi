<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Massal Surat Kelulusan</title>
    
    {{-- Menggunakan Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Phosphor Icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* PENGATURAN KERTAS A4 */
        @page { 
            size: 21cm 29.7cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background-color: #555; /* Background abu gelap di mode preview */
            -webkit-print-color-adjust: exact;
            color: #000;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 21cm;
            min-height: 29.7cm;
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            position: relative;
            page-break-after: always; /* PENTING: Memutus halaman saat print */
        }

        /* Hapus break pada halaman terakhir agar tidak ada kertas kosong */
        .sheet:last-child {
            page-break-after: auto;
        }

        /* PRINT MODE */
        @media print {
            body { background: none; margin: 0; }
            .sheet { 
                width: 100%; 
                margin: 0; 
                padding: 2cm 2.5cm; 
                box-shadow: none; 
                border: none;
            }
            .no-print { display: none !important; }
            
            /* Sembunyikan URL/Header bawaan browser */
            @page { margin: 0; }
        }

        /* TYPOGRAPHY */
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header-text h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; }
        
        .double-line { 
            border-top: 4px double #000; 
            margin-top: 8px; 
            margin-bottom: 24px; 
        }

        .judul-surat { text-align: center; margin-bottom: 24px; }
        .judul-surat h2 { margin: 0; font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        .judul-surat p { font-size: 11pt; margin-top: 4px; }

        .content-text { font-size: 11pt; line-height: 1.5; text-align: justify; margin-bottom: 12px; }

        .content-table { width: 100%; border-collapse: collapse; font-size: 11pt; margin-bottom: 16px; margin-left: 10px; }
        .content-table td { vertical-align: top; padding: 4px 0; }
        .col-label { width: 180px; font-weight: normal; }
        .col-separator { width: 20px; text-align: center; }
        .col-value { font-weight: bold; }

        .status-box {
            border: 2px solid #000;
            padding: 12px;
            margin: 20px 0;
            text-align: center;
        }

        .ttd-box { 
            float: right; 
            width: 40%; 
            margin-top: 40px; 
            text-align: left; 
            font-size: 11pt; 
        }
    </style>
</head>
<body>

    <!-- TOOLBAR (Hanya di Layar) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-slate-900 text-white shadow-lg p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-blue-600 p-2 rounded-lg">
                <i class="ph-bold ph-files text-xl"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg">Mode Cetak Massal</h1>
                <p class="text-xs text-slate-300">Siap mencetak {{ count($registrants) }} surat sekaligus.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="window.close()" class="px-5 py-2 text-sm font-bold bg-slate-700 hover:bg-slate-600 rounded-lg transition">
                Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2 text-sm font-bold bg-blue-600 hover:bg-blue-500 rounded-lg transition shadow-lg flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Print Semua
            </button>
        </div>
    </div>

    <div class="no-print h-24"></div>

    <!-- LOOPING SURAT -->
    @foreach($registrants as $registrant)
    <div class="sheet">
        
        <!-- KOP SURAT (Disesuaikan dengan print.blade.php) -->
        <div class="relative py-2">
            <!-- Logo Kiri -->
            <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Kab" 
                 class="absolute left-0 top-1 w-20 h-auto object-contain"
                 onerror="this.style.display='none'"> 
            
            <div class="text-center header-text mx-auto w-3/4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis 46385</p>
                <p>Laman: www.smpn3lakbok.sch.id   E-mail: smpn3lakbok@gmail.com</p>
            </div>

            <!-- Logo Kanan -->
            <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" 
                 class="absolute right-0 top-1 w-20 h-auto object-contain"
                 onerror="this.style.display='none'">
        </div>
        
        <div class="double-line"></div>

        <!-- JUDUL SURAT -->
        <div class="judul-surat">
            <h2>SURAT KETERANGAN DITERIMA</h2>
            <p>Nomor: 421.3/{{ str_pad($registrant->id, 3, '0', STR_PAD_LEFT) }}/PPDB/{{ date('Y') }}</p>
        </div>

        <p class="content-text">
            Yang bertanda tangan di bawah ini, Kepala SMP Negeri 3 Lakbok, Kabupaten Ciamis, berdasarkan hasil seleksi Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran {{ $registrant->academic_year }}/{{ $registrant->academic_year + 1 }}, menerangkan bahwa:
        </p>

        <!-- DATA SISWA -->
        <table class="content-table">
            <tr>
                <td class="col-label">Nama Peserta Didik</td>
                <td class="col-separator">:</td>
                <td class="col-value" style="text-transform: uppercase;">{{ $registrant->full_name }}</td>
            </tr>
            <tr>
                <td class="col-label">Nomor Pendaftaran</td>
                <td class="col-separator">:</td>
                <td class="col-value">{{ $registrant->registration_number }}</td>
            </tr>
            <tr>
                <td class="col-label">NISN</td>
                <td class="col-separator">:</td>
                <td>{{ $registrant->nisn }}</td>
            </tr>
            <tr>
                <td class="col-label">Tempat, Tanggal Lahir</td>
                <td class="col-separator">:</td>
                <td>{{ $registrant->birth_place }}, {{ \Carbon\Carbon::parse($registrant->birth_date)->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="col-label">Asal Sekolah</td>
                <td class="col-separator">:</td>
                <td>{{ $registrant->school_origin }}</td>
            </tr>
            <tr>
                <td class="col-label">Jalur Pendaftaran</td>
                <td class="col-separator">:</td>
                <td style="text-transform: capitalize;">{{ $registrant->track }}</td>
            </tr>
        </table>

        <!-- STATUS -->
        <p class="content-text">
            Berdasarkan verifikasi data dan pemenuhan persyaratan yang berlaku, maka peserta didik tersebut dinyatakan:
        </p>

        <div class="status-box">
            <h1 style="margin: 0; font-size: 22pt; font-weight: bold; letter-spacing: 4px; text-transform: uppercase;">DITERIMA</h1>
            <p style="margin: 5px 0 0 0; font-size: 10pt;">Sebagai Calon Peserta Didik Kelas VII</p>
        </div>

        <p class="content-text">
            Selanjutnya, kepada orang tua/wali siswa dimohon untuk segera melakukan <strong>Daftar Ulang</strong> sesuai dengan jadwal yang telah ditentukan dengan membawa bukti surat keterangan ini beserta dokumen persyaratan asli untuk diverifikasi.
        </p>
        
        <p class="content-text">
            Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>

        <!-- TANDA TANGAN -->
        <div class="ttd-box">
            <p>Ditetapkan di: Lakbok</p>
            <p class="mb-6">Pada tanggal: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            
            <p class="font-bold">Kepala Sekolah,</p>
            
            <div style="height: 70px;"></div>
            
            <p style="font-weight: bold; text-decoration: underline;">TANTAN SUTANDI NUGRAHA,S.Si,M.Pd.</p>
            <p>NIP.1982........</p>
        </div>

        <div style="clear: both;"></div>
    </div>
    @endforeach

</body>
</html>