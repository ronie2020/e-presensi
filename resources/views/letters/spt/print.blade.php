<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SPT - {{ $spt->nomor_spt }}</title>
    
    <!-- Gunakan Font/CDN yang sama dengan aplikasi utama agar seragam -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f3f4f6;
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 21.5cm;
            min-height: 33cm;
            margin: 20px auto;
            padding: 1.5cm 2cm;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* MODIFIKASI SAAT DICETAK (PRINT MODE) */
        @media print {
            body { background: none; margin: 0; }
            .sheet { width: 100%; margin: 0; padding: 1cm 2cm; box-shadow: none; border: none; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY SURAT */
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header-text h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; }
        
        .double-line { 
            border-top: 4px double #000; 
            margin-top: 8px; 
            margin-bottom: 24px; 
        }

        .judul-surat { text-align: center; margin-bottom: 24px; }
        .judul-surat h2 { margin: 0; font-size: 13pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        .judul-surat p { font-size: 11pt; margin-top: 4px; }

        .content-table { width: 100%; border-collapse: collapse; font-size: 11pt; }
        .content-table td { vertical-align: top; padding: 4px 0; }
        .col-label { width: 100px; font-weight: normal; }
        .col-separator { width: 20px; text-align: center; }

        .table-pegawai { width: 100%; border-collapse: collapse; margin: 10px 0 20px 0; font-size: 11pt; }
        .table-pegawai th, .table-pegawai td { border: 1px solid #000; padding: 6px 8px; }
        .table-pegawai th { text-align: center; background-color: #e5e7eb; }
        @media print { .table-pegawai th { background-color: #ddd !important; -webkit-print-color-adjust: exact; } }

        .ttd-box { float: right; width: 40%; margin-top: 40px; text-align: left; font-size: 11pt; }
    </style>
</head>
<body>

    <!-- TOOLBAR (Hanya Tampil di Layar) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white border-b shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 p-2 rounded-full text-blue-600">
                <i class="fas fa-file-alt text-xl"></i>
            </div>
            <div>
                <h1 class="font-bold text-gray-800 text-sm md:text-base">Pratinjau SPT</h1>
                <p class="text-xs text-gray-500">Nomor: {{ $spt->nomor_spt }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('letters.spt.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-lg flex items-center">
                <i class="fas fa-print mr-2"></i> Cetak Dokumen
            </button>
        </div>
    </div>

    <div class="no-print h-20"></div>

    <!-- HALAMAN KERTAS -->
    <div class="sheet">
        
        <!-- KOP SURAT DENGAN LOGO -->
        <div class="relative py-2">
            <!-- LOGO KABUPATEN (KIRI) -->
            <!-- Pastikan file ada di folder public/img/logo_ciamis.png -->
            <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" 
                 class="absolute left-0 top-1 w-20 h-auto object-contain"
                 onerror="this.style.display='none'"> 
            
            <!-- TEKS TENGAH -->
            <div class="text-center header-text mx-auto w-3/4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
                <p>Laman: www.smpn3lakbok.sch.id   E-mail: smpn3lakbok@gmail.com</p>
            </div>

            <!-- LOGO SEKOLAH (KANAN) -->
            <!-- Pastikan file ada di folder public/img/logo_sekolah.png -->
            <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" 
                 class="absolute right-0 top-1 w-20 h-auto object-contain"
                 onerror="this.style.display='none'">
        </div>
        
        <!-- Garis Ganda -->
        <div class="double-line"></div>

        <!-- JUDUL -->
        <div class="judul-surat">
            <h2>SURAT PERINTAH TUGAS</h2>
            <p>Nomor: {{ $spt->nomor_spt }}</p>
        </div>

        <!-- ISI -->
        <table class="content-table">
            <tr>
                <td class="col-label">Dasar</td>
                <td class="col-separator">:</td>
                <td class="text-justify">
                    @if($spt->letterIncoming)
                        Menindaklanjuti Surat dari <strong>{{ $spt->letterIncoming->pengirim }}</strong> 
                        Nomor {{ $spt->letterIncoming->nomor_surat }} 
                        tanggal {{ \Carbon\Carbon::parse($spt->letterIncoming->tgl_surat)->isoFormat('D MMMM Y') }} 
                        perihal "{{ $spt->letterIncoming->perihal }}".
                    @else
                        Kepentingan Dinas Sekolah SMP Negeri 3 Lakbok dalam rangka peningkatan mutu pendidikan dan pelayanan sekolah.
                    @endif
                </td>
            </tr>
        </table>

        <div class="text-center font-bold my-4">MEMBERI TUGAS:</div>

        <table class="content-table">
            <tr>
                <td class="col-label">Kepada</td>
                <td class="col-separator">:</td>
                <td></td>
            </tr>
        </table>

        <!-- LIST PEGAWAI -->
        <table class="table-pegawai">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Nama / NIP</th>
                    <th width="20%">Pangkat/Gol.</th>
                    <th width="35%">Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spt->users as $index => $user)
                <tr>
                    <td align="center" valign="top">{{ $index + 1 }}</td>
                    <td valign="top">
                        <div style="font-weight: bold;">{{ $user->name }}</div>
                        <div style="font-size: 10pt; color: #333;">NIP. {{ $user->nip ?? '-' }}</div>
                    </td>
                    <td align="center" valign="top">{{ $user->pangkat ?? '-' }}</td>
                    <td align="center" valign="top">{{ $user->position ?? 'Guru / Staf' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="content-table">
            <tr>
                <td class="col-label">Untuk</td>
                <td class="col-separator">:</td>
                <td class="text-justify">{{ $spt->untuk }}</td>
            </tr>
            <tr>
                <td class="col-label">Tempat</td>
                <td class="col-separator">:</td>
                <td><strong>{{ $spt->tempat_tujuan }}</strong></td>
            </tr>
            <tr>
                <td class="col-label">Waktu</td>
                <td class="col-separator">:</td>
                <td>
                    {{ \Carbon\Carbon::parse($spt->tgl_berangkat)->isoFormat('dddd, D MMMM Y') }}
                    @if($spt->lama_hari > 1)
                        s.d. {{ \Carbon\Carbon::parse($spt->tgl_kembali)->isoFormat('dddd, D MMMM Y') }}
                    @endif
                </td>
            </tr>
        </table>

        <p class="mt-4 text-justify" style="text-indent: 3rem;">
            Demikian Surat Perintah Tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab dan melaporkan hasilnya setelah kegiatan selesai.
        </p>

        <!-- TANDA TANGAN -->
        <div class="ttd-box">
            <p>Ditetapkan di: Lakbok</p>
            <p class="mb-6">Pada tanggal: {{ \Carbon\Carbon::parse($spt->created_at)->isoFormat('D MMMM Y') }}</p>
            
            <p class="font-bold">Kepala Sekolah,</p>
            
            <div style="height: 70px;"></div> <!-- Ruang Tanda Tangan -->
            
            <p style="font-weight: bold; text-decoration: underline;">{{ $spt->pejabat_nama }}</p>
            <p>NIP. {{ $spt->pejabat_nip }}</p>
        </div>

        <div style="clear: both;"></div>
    </div>

</body>
</html>