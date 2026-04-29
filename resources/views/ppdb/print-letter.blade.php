<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Diterima - {{ $registrant->full_name }}</title>
    
    {{-- Menggunakan Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        elevate: {
                            dark: '#2c3f61',
                            primary: '#0d52a1',
                            accent: '#56bbf1',
                            surface: '#ffffff',
                            soft: '#e5eff5',
                        }
                    }
                }
            }
        }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* PENGATURAN KERTAS A4 & ELEVATE CLEAN FONT */
        @page { 
            size: 21cm 29.7cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background-color: #e5eff5; /* Elevate Soft background for screen */
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
            box-shadow: 0 25px 50px -12px rgba(44, 63, 97, 0.25); /* Elevate Dark shadow */
            border-radius: 4px;
        }
        
        @media print {
            body { background-color: white; }
            .sheet { 
                margin: 0; 
                padding: 1.5cm 2cm; 
                box-shadow: none; 
                width: 100%;
                border-radius: 0;
            }
            .no-print { display: none !important; }
        }

        /* KOP SURAT */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
            position: relative;
        }
        
        .logo {
            position: absolute;
            left: 0;
            top: 5px;
            width: 80px;
            height: auto;
        }

        /* TIPOGRAFI SURAT */
        .surat-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        .surat-number {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 30px;
        }

        .content-text {
            text-align: justify;
            font-size: 12pt;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .data-table {
            width: 100%;
            margin: 20px 0;
            margin-left: 20px;
            font-size: 12pt;
        }
        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .status-box {
            border: 2px solid #000;
            padding: 15px;
            text-align: center;
            margin: 30px 40px;
            border-radius: 8px; /* Slightly rounded per elevate clean style */
        }

        .ttd-box {
            float: right;
            width: 300px;
            text-align: left;
            margin-top: 40px;
            font-size: 12pt;
        }
    </style>
</head>
<body>

    <!-- TOOLBAR -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-elevate-dark p-2.5 rounded-xl text-white shadow-lg shadow-elevate-dark/20">
                <i class="ph-bold ph-printer text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-elevate-dark text-sm md:text-base font-sans">Pratinjau Surat Kelulusan</h1>
                <p class="text-xs text-slate-500 font-sans font-bold">Ref: {{ $registrant->registration_number }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ppdb.check') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-elevate-dark transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-elevate-dark rounded-xl hover:bg-elevate-primary transition shadow-lg shadow-elevate-dark/30 font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak Sekarang
            </button>
        </div>
    </div>

    <div class="no-print h-24"></div>

    <!-- HALAMAN KERTAS -->
    <div class="sheet">
        
        <!-- KOP SURAT (Disesuaikan dengan print.blade.php) -->
        <div class="relative py-2">
            <!-- Logo Kiri (Kabupaten Ciamis) -->
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

            <!-- Logo Kanan (Sekolah) -->
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
            
            <p style="font-weight: bold; text-decoration: underline;">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
            <p>NIP. 197xxxxxx...</p>
        </div>

        <div style="clear: both;"></div>
    </div>

    <div class="no-print text-center text-slate-400 text-xs pb-8">
        &copy; {{ date('Y') }} Sistem Informasi PPDB SMPN 3 Lakbok
    </div>

</body>
</html>