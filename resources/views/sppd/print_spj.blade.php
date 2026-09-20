@php
    if (!function_exists('TerbilangSpj')) {
        function TerbilangSpj($n) {
            $satuan = ['''', ''Satu'', ''Dua'', ''Tiga'', ''Empat'', ''Lima'', ''Enam'', ''Tujuh'', ''Delapan'', ''Sembilan'', ''Sepuluh'', ''Sebelas''];
            if ($n < 12)   return $satuan[$n];
            if ($n < 20)   return $satuan[$n - 10] . '' Belas'';
            if ($n < 100)  return $satuan[intdiv($n, 10)] . '' Puluh'' . ($n % 10 ? '' '' . $satuan[$n % 10] : '''');
            if ($n < 200)  return ''Seratus'' . ($n > 100 ? '' '' . TerbilangSpj($n - 100) : '''');
            if ($n < 1000) return $satuan[intdiv($n, 100)] . '' Ratus'' . ($n % 100 ? '' '' . TerbilangSpj($n % 100) : '''');
            if ($n < 2000) return ''Seribu'' . ($n > 1000 ? '' '' . TerbilangSpj($n - 1000) : '''');
            if ($n < 1000000) return TerbilangSpj(intdiv($n, 1000)) . '' Ribu'' . ($n % 1000 ? '' '' . TerbilangSpj($n % 1000) : '''');
            if ($n < 1000000000) return TerbilangSpj(intdiv($n, 1000000)) . '' Juta'' . ($n % 1000000 ? '' '' . TerbilangSpj($n % 1000000) : '''');
            return $n;
        }
    }
    $totalBiaya = ($sppd->biaya_transport ?? 0) + ($sppd->biaya_penginapan ?? 0) + ($sppd->uang_harian ?? 0);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPJ - {{ $sppd->nomor_sppd }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { elevate: { dark: ''#032b5b'', primary: ''#3b5889'', accent: ''#38bdf8'' } } } } }</script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @page { size: 21.5cm 33cm; margin: 0; }
        body { font-family: ''Times New Roman'', serif; font-size: 11pt; background: #f8fafc; -webkit-print-color-adjust: exact; }
        .sheet { font-family: ''Bookman Old Style'', Bookman, Georgia, serif; background: white; width: 21.5cm; min-height: 33cm; margin: 30px auto; padding: 1.5cm 2cm; box-sizing: border-box; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .garis-kop { border-bottom: 3px solid black; margin-bottom: 2px; }
        .garis-kop-bawah { border-bottom: 1px solid black; margin-bottom: 20px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 11pt; }
        table.data td, table.data th { border: 1px solid black; padding: 6px 10px; vertical-align: top; }
        table.data th { background: #f1f5f9; font-weight: bold; text-align: center; }
        @media print {
            body { background: none; }
            .sheet { margin: 0; box-shadow: none; border: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    {{-- Toolbar --}}
    <div class="w-[21.5cm] mx-auto mt-6 mb-6 no-print flex justify-between items-center bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-white/60 sticky top-4 z-50 font-sans">
        <div>
            <h2 class="font-black text-elevate-dark flex items-center gap-2"><i class="ph-bold ph-receipt text-elevate-primary text-xl"></i> Pratinjau Cetak SPJ</h2>
            <p class="text-xs text-slate-500 font-bold ml-7">{{ $sppd->nomor_sppd }} | Kertas F4</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route(''sppd.print'', $sppd->id) }}" target="_blank" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-elevate-primary hover:bg-slate-50 flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak SPPD
            </a>
            <a href="{{ route(''sppd.index'') }}" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2 group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg text-xs flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak / PDF
            </button>
        </div>
    </div>

    <div class="sheet">
        {{-- KOP SURAT --}}
        <div class="kop-surat garis-kop pb-2 pt-2 flex justify-between items-center px-1">
            <img src="{{ asset(''img/logo_ciamis.png'') }}" alt="Logo" class="w-[85px] h-auto object-contain" onerror="this.style.display=''none''">
            <div class="text-center flex-1 px-4 leading-tight">
                <div style="font-size:14pt; letter-spacing:0.05em; margin-bottom:4px;">PEMERINTAH KABUPATEN CIAMIS</div>
                <div style="font-size:22pt; font-weight:bold; letter-spacing:0.05em; margin-bottom:4px;">SMP NEGERI 3 LAKBOK</div>
                <div style="font-size:12pt;">Jalan Mekarjaya No.199, Sidaharja</div>
                <div style="font-size:12pt;">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                <div style="font-size:11pt; margin-top:4px;">Laman: www.smpn3lakbok.sch.id &nbsp;&nbsp; E-mail: netila.smp@gmail.com</div>
            </div>
            <img src="{{ asset(''img/logo_sekolah.png'') }}" alt="Logo" class="w-[85px] h-auto object-contain" onerror="this.style.display=''none''">
        </div>
        <div class="garis-kop-bawah"></div>

        {{-- JUDUL --}}
        <div style="text-align:center; margin-bottom:20px;">
            <h2 style="font-size:13pt; font-weight:bold; text-decoration:underline; text-transform:uppercase; margin:0;">SURAT PERTANGGUNGJAWABAN BIAYA PERJALANAN DINAS</h2>
            <p style="font-size:11pt; margin:4px 0 0;">Nomor SPPD: {{ $sppd->nomor_sppd }}</p>
        </div>

        {{-- DATA PEGAWAI --}}
        <table style="width:100%; font-size:11pt; border-collapse:collapse; margin-bottom:20px;">
            <tr><td style="width:35%; padding:4px 0; vertical-align:top;">Nama / NIP</td><td style="width:3%; padding:4px 0; vertical-align:top;">:</td><td style="padding:4px 0;"><strong>{{ $sppd->user?->name ?? ''Pegawai Tidak Ditemukan'' }}</strong> / NIP. {{ $sppd->user?->nip ?? ''-'' }}</td></tr>
            <tr><td style="padding:4px 0; vertical-align:top;">Jabatan</td><td style="padding:4px 0; vertical-align:top;">:</td><td style="padding:4px 0;">{{ $sppd->user?->position ?? ''Guru'' }}</td></tr>
            <tr><td style="padding:4px 0; vertical-align:top;">Maksud Perjalanan</td><td style="padding:4px 0; vertical-align:top;">:</td><td style="padding:4px 0;">{{ $sppd->maksud_perjalanan }}</td></tr>
            <tr><td style="padding:4px 0; vertical-align:top;">Tempat Tujuan</td><td style="padding:4px 0; vertical-align:top;">:</td><td style="padding:4px 0;">{{ $sppd->tempat_tujuan }}</td></tr>
            <tr><td style="padding:4px 0; vertical-align:top;">Tanggal</td><td style="padding:4px 0; vertical-align:top;">:</td><td style="padding:4px 0;">{{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat(''D MMMM Y'') }} s/d {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat(''D MMMM Y'') }} ({{ $sppd->lama_hari }} hari)</td></tr>
            <tr><td style="padding:4px 0; vertical-align:top;">Alat Angkut</td><td style="padding:4px 0; vertical-align:top;">:</td><td style="padding:4px 0;">{{ $sppd->alat_angkut ?? ''Kendaraan Umum'' }}</td></tr>
        </table>

        {{-- TABEL RINCIAN BIAYA --}}
        <p style="font-size:11pt; font-weight:bold; margin-bottom:8px;">RINCIAN BIAYA PERJALANAN DINAS:</p>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th>Uraian Biaya</th>
                    <th style="width:200px;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:center;">1</td>
                    <td>Biaya Transportasi (Pergi - Pulang)</td>
                    <td style="text-align:right;">{{ $sppd->biaya_transport ? number_format($sppd->biaya_transport, 0, '','', ''.'') : ''-'' }}</td>
                </tr>
                <tr>
                    <td style="text-align:center;">2</td>
                    <td>Biaya Penginapan ({{ $sppd->lama_hari }} malam)</td>
                    <td style="text-align:right;">{{ $sppd->biaya_penginapan ? number_format($sppd->biaya_penginapan, 0, '','', ''.'') : ''-'' }}</td>
                </tr>
                <tr>
                    <td style="text-align:center;">3</td>
                    <td>Uang Harian ({{ $sppd->lama_hari }} hari × sesuai standar)</td>
                    <td style="text-align:right;">{{ $sppd->uang_harian ? number_format($sppd->uang_harian, 0, '','', ''.'') : ''-'' }}</td>
                </tr>
                <tr style="background:#f8fafc;">
                    <td colspan="2" style="text-align:right; font-weight:bold;">JUMLAH TOTAL</td>
                    <td style="text-align:right; font-weight:bold;">{{ number_format($totalBiaya, 0, '','', ''.'') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- TERBILANG --}}
        <div style="margin-top:12px; padding:10px 16px; border:1px solid black; font-size:10.5pt;">
            <strong>Terbilang:</strong> <em>{{ TerbilangSpj((int)$totalBiaya) }} Rupiah</em>
        </div>

        {{-- PEMBEBANAN ANGGARAN --}}
        <div style="margin-top:16px; font-size:11pt;">
            <p><strong>Pembebanan Anggaran:</strong></p>
            <p style="margin-left:20px;">Instansi : {{ $sppd->instansi_pembayar }}</p>
            <p style="margin-left:20px;">Kode Rekening : {{ $sppd->mata_anggaran ?? ''-'' }}</p>
        </div>

        {{-- PERNYATAAN --}}
        <div style="margin-top:20px; font-size:11pt; text-align:justify;">
            <p>Yang bertanda tangan di bawah ini menyatakan dengan sesungguhnya bahwa:</p>
            <ol style="margin-left:20px; margin-top:6px; line-height:1.8;">
                <li>Biaya perjalanan dinas tersebut di atas benar-benar dikeluarkan untuk keperluan perjalanan dinas dimaksud.</li>
                <li>Bukti-bukti pengeluaran terlampir/tidak dapat diperoleh sesuai ketentuan yang berlaku.</li>
                <li>Apabila di kemudian hari terdapat kelebihan pembayaran, saya bersedia menyetorkan kembali ke Kas Negara.</li>
            </ol>
        </div>

        {{-- TTD --}}
        <div style="margin-top:40px; display:flex; justify-content:space-between;">
            <div style="width:45%; text-align:center;">
                <p>Mengetahui,<br>Kepala SMP Negeri 3 Lakbok</p>
                <div style="height:65px;"></div>
                <p style="font-weight:bold; text-decoration:underline; white-space:nowrap;">{{ $sppd->pejabat_nama }}</p>
                <p>NIP. {{ $sppd->pejabat_nip }}</p>
            </div>
            <div style="width:45%; text-align:center;">
                <p>Lakbok, {{ \Carbon\Carbon::now()->isoFormat(''D MMMM Y'') }}<br>Yang Membuat Pernyataan,</p>
                <div style="height:65px;"></div>
                <p style="font-weight:bold; text-decoration:underline; white-space:nowrap;">{{ $sppd->user?->name ?? ''-'' }}</p>
                <p>NIP. {{ $sppd->user?->nip ?? ''-'' }}</p>
            </div>
        </div>

        @if($sppd->catatan_kepala)
        <div style="margin-top:30px; padding:10px 16px; background:#f0fdf4; border:1px solid #bbf7d0; font-size:10pt;">
            <strong>Catatan Kepala Sekolah:</strong><br>{{ $sppd->catatan_kepala }}
        </div>
        @endif
    </div>
</body>
</html>
