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
                            dark: '#032b5b',
                            primary: '#3b5889',
                            accent: '#38bdf8',
                            text: '#1e293b',
                            surface: '#ffffff',
                            soft: '#f8fafc',
                        }
                    }
                }
            }
        }
    </script>
    
    {{-- Menggunakan Phosphor Icons untuk Toolbar --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS A4 */
        @page { 
            size: 21cm 29.7cm; 
            margin: 0; 
        }
        
        body {
            /* Font default untuk elemen web / non-cetak */
            font-family: 'Times New Roman', serif;
            background-color: #f8fafc; /* Slate-50 */
            -webkit-print-color-adjust: exact;
            color: #000;
        }

        /* FONT KHUSUS KOP & ISI SURAT (BOOKMAN OLD STYLE) */
        .area-surat {
            font-family: 'Bookman Old Style', Bookman, Georgia, serif;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 21cm;
            min-height: 29.7cm;
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }
        
        @media print {
            body { background-color: white !important; margin: 0; padding: 0; }
            .sheet { 
                margin: 0 !important; 
                padding: 1.5cm 2cm !important; 
                box-shadow: none !important; 
                border: none !important;
                width: 100%;
            }
            .no-print { display: none !important; }
        }

        /* MODIFIKASI GARIS KOP SURAT (Garis Tebal & Tipis) */
        .garis-kop {
            border-bottom: 3px solid black;
            margin-bottom: 2px;
        }
        .garis-kop-bawah {
            border-bottom: 1px solid black;
            margin-bottom: 24px; /* Jarak dari garis ke judul surat */
        }

        /* MODIFIKASI ISI SURAT */
        .content-table { width: 100%; border-collapse: collapse; font-size: 11pt; margin-bottom: 16px; margin-left: 10px; }
        .content-table td { vertical-align: top; padding: 5px 0; }
        .col-label { width: 180px; font-weight: normal; }
        .col-separator { width: 20px; text-align: center; }
        .col-value { font-weight: bold; }

        .status-box {
            border: 2px solid #000;
            padding: 12px;
            margin: 20px 0;
            text-align: center;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="min-h-screen relative">

    <!-- DEKORASI BACKGROUND (Hanya tampil di layar) -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI (Sesuai Gaya Cetak Massal) -->
    <div class="w-[21cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-file-text text-elevate-primary text-xl"></i> Pratinjau Surat Kelulusan
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">No. Reg: {{ $registrant->registration_number }}</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            {{-- Tombol Tutup Tab Otomatis --}}
            <button onclick="window.close()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-x group-hover:scale-110 transition-transform"></i> Tutup
            </button>

            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Cetak Sekarang
            </button>
        </div>
    </div>

    <!-- HALAMAN KERTAS -->
    <div class="sheet area-surat text-[12pt]">
        
        <!-- KOP SURAT (FLEXBOX) -->
        <div class="kop-surat garis-kop pb-2 pt-2 flex justify-between items-center px-1">
            <!-- Logo Kiri -->
            <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" class="w-[85px] h-auto object-contain" onerror="this.style.display='none'"> 
            
            <!-- Teks Tengah -->
            <div class="text-center flex-1 px-4 leading-tight">
                <div class="text-[14pt] tracking-wide mb-1">PEMERINTAH KABUPATEN CIAMIS</div>
                <div class="text-[14pt] tracking-wide mb-1">DINAS PENDIDIKAN</div>
                <div class="font-bold text-[22pt] tracking-wider mb-1">SMP NEGERI 3 LAKBOK</div>
                <div class="text-[12pt]">Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok</div>
                <div class="text-[12pt]">Kabupaten Ciamis 46385</div>
                <div class="text-[10pt] mt-1">
                    Laman: <a href="http://www.smpn3lakbok.sch.id" class="text-blue-700 underline">www.smpn3lakbok.sch.id</a> 
                    <span class="mx-2"></span> 
                    E-mail: smpn3lakbok@gmail.com
                </div>
            </div>

            <!-- Logo Kanan -->
            <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" class="w-[85px] h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <!-- Garis tipis pelengkap batas kop -->
        <div class="garis-kop-bawah"></div>

        <!-- JUDUL SURAT -->
        <div class="text-center mb-6">
            <h2 class="text-lg font-bold uppercase underline underline-offset-4 mb-1">SURAT KETERANGAN DITERIMA</h2>
            <p>Nomor: 421.3/{{ str_pad($registrant->id, 3, '0', STR_PAD_LEFT) }}/PPDB/{{ date('Y') }}</p>
        </div>

        <p class="text-justify mb-4" style="text-indent: 3rem;">
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
        <p class="text-justify mt-4">
            Berdasarkan verifikasi data dan pemenuhan persyaratan yang berlaku, maka peserta didik tersebut dinyatakan:
        </p>

        <div class="status-box">
            <h1 style="margin: 0; font-size: 24pt; font-weight: bold; letter-spacing: 4px; text-transform: uppercase;">DITERIMA</h1>
            <p style="margin: 5px 0 0 0; font-size: 11pt;">Sebagai Calon Peserta Didik Kelas VII</p>
        </div>

        <p class="text-justify" style="text-indent: 3rem;">
            Selanjutnya, kepada orang tua/wali siswa dimohon untuk segera melakukan <strong>Daftar Ulang</strong> sesuai dengan jadwal yang telah ditentukan dengan membawa bukti surat keterangan ini beserta dokumen persyaratan asli untuk diverifikasi.
        </p>
        
        <p class="text-justify mt-2" style="text-indent: 3rem;">
            Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>

        <!-- FOOTER: QR CODE & TANDA TANGAN -->
        <div class="flex justify-between items-end mt-10" style="page-break-inside: avoid;">
            
            <!-- Area QR Code Kiri -->
            <div class="pl-8 text-center">
                @php
                    /* Membuat URL Validasi mengarah ke detail surat kelulusan */
                    $urlValidasi = route('ppdb.print.letter', $registrant->id);
                @endphp
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode($urlValidasi) }}" 
                     alt="QR Code" 
                     class="w-[90px] h-[90px] mx-auto border border-black p-1">
                <div class="mt-2">
                    <p class="text-[8pt] font-sans font-bold text-black m-0 leading-tight">SCAN QR CODE</p>
                    <p class="text-[7pt] font-sans text-gray-600 m-0 leading-tight">Untuk Validasi Data</p>
                </div>
            </div>

            <!-- Area Tanda Tangan Kanan -->
            <div class="w-[45%] text-left text-[11pt]">
                <p>Ditetapkan di : Lakbok</p>
                <p class="mb-6">Pada tanggal &nbsp;: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                
                <p class="font-bold">Kepala Sekolah,</p>
                
                <div style="height: 70px;"></div>
                
                <p class="font-bold underline whitespace-nowrap">TANTAN SUTANDI NUGRAHA,S.Si,M.Pd.</p>
                <p>NIP. 19820928 201101 1 002</p>
            </div>
        </div>

        <div style="clear: both;"></div>
    </div> <!-- End Kertas -->

    <div class="no-print text-center text-slate-400 text-xs mt-6 pb-8 font-sans font-bold tracking-widest uppercase">
        &copy; {{ date('Y') }} Sistem Informasi PPDB SMPN 3 Lakbok
    </div>

</body>
</html>