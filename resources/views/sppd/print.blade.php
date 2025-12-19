<!-- DEFINISI FUNGSI TERBILANG -->
@php
    if (!function_exists('Terbilang')) {
        function Terbilang($x) {
            $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            if ($x < 12) return $angka[$x];
            elseif ($x < 20) return $angka[$x - 10] . " Belas";
            elseif ($x < 100) return $angka[$x / 10] . " Puluh " . $angka[$x % 10];
            return $x;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak SPPD - {{ $sppd->nomor_sppd }}</title>
    <style>
        /* Pengaturan Kertas F4 (21.5cm x 33cm) */
        @page { size: 21.5cm 33cm; margin: 1cm 1.5cm; }
        
        body { font-family: 'Times New Roman', serif; font-size: 10pt; line-height: 1.1; color: #000; margin: 0; padding: 0; }
        .page { width: 100%; min-height: 29cm; position: relative; padding-top: 10px; }
        .page-break { page-break-before: always; }
        
        .header { text-align: center; margin-bottom: 5px; }
        .header h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10pt; }
        .line { border-bottom: 3px double black; margin-top: 5px; margin-bottom: 15px; }

        .judul { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
        .judul h2 { margin: 0; text-decoration: underline; font-size: 12pt; }
        .judul p { margin: 0; font-size: 11pt; font-weight: normal; text-transform: none; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.data td { vertical-align: top; padding: 4px; border: 1px solid black; }
        table.data tr td:first-child { width: 30px; text-align: center; }
        table.data tr td:nth-child(2) { width: 220px; }

        table.visum { width: 100%; border-collapse: collapse; border: 1px solid black; margin-top: 10px; }
        table.visum td { border: 1px solid black; padding: 5px; vertical-align: top; width: 50%; height: 120px; }
        
        .ttd-area { float: right; width: 45%; text-align: left; margin-top: 20px; }
        .clear { clear: both; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .indent { text-indent: 30px; }
        .label { width: 100px; font-weight: bold; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print" style="position: fixed; top: 0; right: 0; background: #eee; padding: 10px; z-index: 999;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #1e40af; color: white; border: none; cursor: pointer; font-weight: bold;">🖨️ Cetak</button>
        <a href="{{ route('sppd.index') }}" style="margin-left: 10px; color: #333;">&larr; Kembali</a>
    </div>

    <!-- HALAMAN 1: SPPD -->
    <div class="page">
        <div class="header">
            <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h4>SMP NEGERI 3 LAKBOK</h4>
            <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
        </div>
        <div class="line"></div>
        <div class="judul">
            <h2>SURAT PERINTAH PERJALANAN DINAS</h2>
            <p>Nomor: {{ $sppd->nomor_sppd }}</p>
        </div>

        <table class="data">
            <tr><td>1</td><td>Pejabat berwenang yang memberi perintah</td><td colspan="2">{{ $sppd->pejabat_jabatan }}</td></tr>
            <tr><td>2</td><td>Nama / NIP Pegawai yang diperintah</td><td colspan="2"><strong>{{ $sppd->user->name }}</strong><br>NIP. {{ $sppd->user->nip ?? '-' }}</td></tr>
            <tr><td>3</td><td>a. Pangkat dan Golongan<br>b. Jabatan / Instansi<br>c. Tingkat Biaya</td><td colspan="2">a. {{ $sppd->user->pangkat ?? '-' }}<br>b. {{ $sppd->user->position ?? 'Guru' }}<br>c. -</td></tr>
            <tr><td>4</td><td>Maksud Perjalanan Dinas</td><td colspan="2">{{ $sppd->maksud_perjalanan }}</td></tr>
            <tr><td>5</td><td>Alat Angkutan</td><td colspan="2">{{ $sppd->alat_angkut ?? 'Kendaraan Umum' }}</td></tr>
            <tr><td>6</td><td>a. Tempat Berangkat<br>b. Tempat Tujuan</td><td colspan="2">a. {{ $sppd->tempat_berangkat }}<br>b. {{ $sppd->tempat_tujuan }}</td></tr>
            <tr><td>7</td><td>a. Lamanya Perjalanan<br>b. Tanggal Berangkat<br>c. Tanggal Kembali</td><td colspan="2">a. {{ $sppd->lama_hari }} ({{ Terbilang($sppd->lama_hari) }}) hari<br>b. {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}<br>c. {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}</td></tr>
            
            <!-- ROW 8: PENGIKUT -->
            <tr>
                <td>8</td>
                <td>Pengikut: Nama</td>
                <td style="width: 150px; text-align: center;">NIP / NIK</td>
                <td>Keterangan</td>
            </tr>
            @if($sppd->followers->count() > 0)
                @foreach($sppd->followers as $index => $follower)
                <tr>
                    <td></td>
                    <td>{{ $index + 1 }}. {{ $follower->nama }}</td>
                    <td style="text-align: center;">{{ $follower->nip ?? '-' }}</td>
                    <td>{{ $follower->keterangan }}</td>
                </tr>
                @endforeach
            @else
                <tr><td></td><td>1. -</td><td></td><td></td></tr>
            @endif

            <tr><td>9</td><td>Pembebanan Anggaran<br>a. Instansi<br>b. Mata Anggaran</td><td colspan="2"><br>a. {{ $sppd->instansi_pembayar }}<br>b. {{ $sppd->mata_anggaran ?? '-' }}</td></tr>
            <tr><td>10</td><td>Keterangan Lain</td><td colspan="2">{{ $sppd->keterangan_lain ?? '-' }}</td></tr>
        </table>

        <div class="ttd-area">
            <p>Dikeluarkan di: Lakbok</p>
            <p>Pada tanggal: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <br><p style="font-weight: bold;">{{ $sppd->pejabat_jabatan }}</p><br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">{{ $sppd->pejabat_nama }}</p>
            <p>NIP. {{ $sppd->pejabat_nip }}</p>
        </div>
    </div>

    <!-- HALAMAN 2 & 3 (VISUM & LAPORAN) SAMA SEPERTI SEBELUMNYA -->
    <!-- (Disalin ulang agar file lengkap) -->
    
    <div class="page-break"></div>
    <div class="page">
        <table class="visum">
            <tr>
                <td></td>
                <td>
                    <p style="margin:0;">I. Berangkat dari: {{ $sppd->tempat_berangkat }}</p>
                    <p style="margin:0; text-indent: 14px;">(Tempat Kedudukan)</p>
                    <p style="margin:0; text-indent: 14px;">Ke: {{ $sppd->tempat_tujuan }}</p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}</p>
                    <br><p style="margin:0; text-align:center; font-weight:bold;">Kepala SMP Negeri 3 Lakbok</p><br><br><br>
                    <p style="margin:0; text-align:center; font-weight:bold; text-decoration:underline;">{{ $sppd->pejabat_nama }}</p>
                    <p style="margin:0; text-align:center;">NIP. {{ $sppd->pejabat_nip }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin:0;">II. Tiba di: {{ $sppd->tempat_tujuan }}</p>
                    <p style="margin:0; text-indent: 18px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}</p>
                    <br><br><p style="margin:0; text-align:center;">Kepala / Pejabat Setempat</p><br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p><p style="margin:0; text-align:center;">NIP.</p>
                </td>
                <td>
                    <p style="margin:0;">Berangkat dari: {{ $sppd->tempat_tujuan }}</p>
                    <p style="margin:0; text-indent: 14px;">Ke: {{ $sppd->tempat_berangkat }}</p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}</p>
                    <br><p style="margin:0; text-align:center;">Kepala / Pejabat Setempat</p><br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p><p style="margin:0; text-align:center;">NIP.</p>
                </td>
            </tr>
            <tr><td><p>III. Tiba di:</p><br><br><br><br><br><p style="text-align:center;">(..............................................)</p></td><td><p>Berangkat dari:</p><br><br><br><br><br><p style="text-align:center;">(..............................................)</p></td></tr>
            <tr>
                <td>
                    <p style="margin:0;">IV. Tiba di: {{ $sppd->tempat_berangkat }}</p>
                    <p style="margin:0; text-indent: 18px;">(Tempat Kedudukan)</p>
                    <p style="margin:0; text-indent: 18px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}</p>
                    <br><p style="margin:0; text-align:center; font-weight:bold;">Kepala SMP Negeri 3 Lakbok</p><br><br><br>
                    <p style="margin:0; text-align:center; font-weight:bold; text-decoration:underline;">{{ $sppd->pejabat_nama }}</p>
                    <p style="margin:0; text-align:center;">NIP. {{ $sppd->pejabat_nip }}</p>
                </td>
                <td style="text-align: justify; padding: 10px;">Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan.</td>
            </tr>
            <tr><td colspan="2"><p>V. Catatan Lain-lain:</p></td></tr>
            <tr><td colspan="2"><p style="font-weight:bold;">VI. PERHATIAN:</p><p style="text-align: justify; font-size: 9pt;">Pejabat yang berwenang dan pejabat/pegawai yang melakukan perjalanan dinas bertanggung jawab sepenuhnya atas kerugian yang diderita oleh negara.</p></td></tr>
        </table>
    </div>

    <div class="page-break"></div>
    <div class="page">
        <div class="header">
            <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h4>SMP NEGERI 3 LAKBOK</h4>
            <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
        </div>
        <div class="line"></div>
        <div class="judul" style="margin-bottom: 30px;"><h2>LAPORAN PERJALANAN DINAS</h2></div>
        <div class="content" style="line-height: 1.5;">
            <p><span class="label">I. DASAR</span><br><span class="indent" style="display:block; margin-left: 30px; text-align: justify;">Surat Perintah Tugas Kepala SMP Negeri 3 Lakbok Nomor: {{ str_replace('090', '094', $sppd->nomor_sppd) }} Tanggal {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}.</span></p>
            <p><span class="label">II. MAKSUD DAN TUJUAN</span><br><span class="indent" style="display:block; margin-left: 30px; text-align: justify;">{{ $sppd->maksud_perjalanan }}</span></p>
            <p><span class="label">III. WAKTU PELAKSANAAN</span><br><span class="indent" style="display:block; margin-left: 30px; text-align: justify;">Kegiatan dilaksanakan pada hari {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('dddd') }} tanggal {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }} bertempat di {{ $sppd->tempat_tujuan }}.</span></p>
            <p><span class="label">IV. HASIL KEGIATAN</span><br><span class="indent" style="display:block; margin-left: 30px; min-height: 150px; border-bottom: 1px dotted #999;"></span><span class="indent" style="display:block; margin-left: 30px; min-height: 50px; border-bottom: 1px dotted #999;"></span></p>
            <p><span class="label">V. KESIMPULAN / SARAN</span><br><span class="indent" style="display:block; margin-left: 30px; min-height: 100px; border-bottom: 1px dotted #999;"></span></p>
        </div>
        <div style="margin-top: 50px;">
            <div style="float: left; width: 40%; text-align: center;"><p>Mengetahui,<br>Kepala Sekolah</p><br><br><br><br><p style="font-weight: bold; text-decoration: underline;">{{ $sppd->pejabat_nama }}</p><p>NIP. {{ $sppd->pejabat_nip }}</p></div>
            <div style="float: right; width: 40%; text-align: center;"><p>Lakbok, {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}<br>Pelapor,</p><br><br><br><br><p style="font-weight: bold; text-decoration: underline;">{{ $sppd->user->name }}</p><p>NIP. {{ $sppd->user->nip ?? '-' }}</p></div>
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>