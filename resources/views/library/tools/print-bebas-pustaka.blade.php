<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Bebas Pustaka - {{ $student->name }}</title>
    
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
            font-size: 12pt;
            background-color: #f1f5f9; /* Slate-100 */
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 21.5cm;
            min-height: 33cm;
            margin: 30px auto;
            padding: 2cm 2cm; /* Padding standar surat resmi */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            page-break-after: always; 
        }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { width: 100%; margin: 0; padding: 2cm; box-shadow: none; border: none; page-break-after: auto; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY SURAT */
        .header-text h3 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .header-text h4 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 11pt; }
        
        .double-line { border-top: 4px double #000; margin-top: 10px; margin-bottom: 25px; }
        
        .judul-surat { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 25px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 14pt; }
        .judul-surat p { margin: 0; font-size: 12pt; font-weight: normal; text-transform: none; }

        /* TABEL IDENTITAS */
        table.identitas { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; margin-left: 30px; font-size: 12pt; }
        table.identitas td { padding: 6px 4px; vertical-align: top; }
        table.identitas tr td:first-child { width: 180px; }
        
        .isi-surat { text-align: justify; line-height: 1.6; }
        .indent { margin-left: 40px; }
        .ttd-box { float: right; width: 45%; text-align: center; margin-top: 50px; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-900 p-2.5 rounded-xl text-white shadow-lg shadow-indigo-900/20">
                <i class="ph-bold ph-certificate text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-slate-800 text-sm md:text-base font-sans">Pratinjau Surat Bebas Pustaka</h1>
                <p class="text-xs text-slate-500 font-sans font-bold">{{ $student->name }} - {{ $student->schoolClass ? $student->schoolClass->name : 'Kelas Tidak Diketahui' }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            {{-- Tombol Kembali mengarah ke menu Tools Bebas Pustaka --}}
            <a href="{{ route('library.tools.bebas_pustaka') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30 font-sans flex items-center gap-2 transform active:scale-95">
                <i class="ph-bold ph-printer"></i> Cetak Dokumen
            </button>
        </div>
    </div>
    <div class="no-print h-24"></div>

    <div class="sheet">
         <div class="relative py-2">
            <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" class="absolute left-0 top-1 w-20 h-auto object-contain" onerror="this.style.display='none'"> 
            <div class="text-center header-text mx-auto w-3/4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
            </div>
            <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" class="absolute right-0 top-1 w-24 h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <div class="double-line"></div>

        <div class="judul-surat">
            <h2>SURAT KETERANGAN BEBAS PERPUSTAKAAN</h2>
            <p>Nomor: 421.3 / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / SMPN.3 / {{ date('Y') }}</p>
        </div>

        <div class="isi-surat">
            <p>Yang bertanda tangan di bawah ini, Kepala Perpustakaan SMP Negeri 3 Lakbok, menerangkan dengan sesungguhnya bahwa:</p>
            
            <table class="identitas">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><strong>{{ $student->name }}</strong></td>
                </tr>
                <tr>
                    <td>NISN / NIS</td>
                    <td>:</td>
                    <td>{{ $student->nisn ?? $student->student_id ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td>{{ $student->schoolClass ? $student->schoolClass->name : '-' }}</td>
                </tr>
            </table>

            <p>Siswa tersebut di atas telah mengembalikan seluruh fasilitas pinjaman dari Perpustakaan SMP Negeri 3 Lakbok yang meliputi:</p>
            <ol style="margin-left: 20px; padding-left: 20px; margin-top: 5px; margin-bottom: 15px;">
                <li style="padding-left: 5px; margin-bottom: 5px;">Buku Paket / Mata Pelajaran Wajib.</li>
                <li style="padding-left: 5px; margin-bottom: 5px;">Buku Bacaan (Fiksi & Non-Fiksi).</li>
                <li style="padding-left: 5px; margin-bottom: 5px;">Tidak memiliki tanggungan denda administrasi keterlambatan maupun ganti rugi fisik buku.</li>
            </ol>

            <p>Demikian surat keterangan bebas perpustakaan ini diberikan agar dapat dipergunakan sebagaimana mestinya, khususnya sebagai salah satu syarat untuk <strong>Pengambilan Ijazah / Kelulusan / Mutasi Siswa</strong>.</p>
        </div>

        <div class="ttd-box">
            <p>Lakbok, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <p>Kepala Perpustakaan,</p>
            <div style="height: 80px;"></div>
            
            {{-- Silakan ubah nama Kepala Perpustakaan sesuai data sekolah --}}
            <p style="font-weight: bold; text-decoration: underline;">Nama Kepala Perpus, S.Pd.</p>
            <p>NIP. 19800101 200501 2 001</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>