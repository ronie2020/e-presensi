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
        
        body { 
            font-family: 'Times New Roman', serif; 
            font-size: 10pt; 
            line-height: 1.1; 
            color: #000; 
            margin: 0; padding: 0;
        }

        /* Container Halaman (Penting untuk Page Break) */
        .page {
            width: 100%;
            min-height: 29cm; /* Tinggi minimal agar page break efektif */
            position: relative;
            padding-top: 10px;
        }
        
        /* Pemisah Halaman */
        .page-break { page-break-before: always; }

        /* KOP SURAT */
        .header { text-align: center; margin-bottom: 5px; }
        .header h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10pt; }
        .line { border-bottom: 3px double black; margin-top: 5px; margin-bottom: 15px; }

        /* Judul Dokumen */
        .judul { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
        .judul h2 { margin: 0; text-decoration: underline; font-size: 12pt; }
        .judul p { margin: 0; font-size: 11pt; font-weight: normal; text-transform: none; }

        /* Tabel Data Utama */
        table.data { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.data td { vertical-align: top; padding: 4px; border: 1px solid black; }
        table.data tr td:first-child { width: 30px; text-align: center; }
        table.data tr td:nth-child(2) { width: 220px; }

        /* Tabel Visum (Halaman 2) */
        table.visum { width: 100%; border-collapse: collapse; border: 1px solid black; margin-top: 10px; }
        table.visum td { border: 1px solid black; padding: 5px; vertical-align: top; width: 50%; height: 120px; }
        .visum-header { font-weight: bold; text-align: center; background-color: #f9f9f9; height: auto !important; }
        
        /* Area Tanda Tangan */
        .ttd-area { float: right; width: 45%; text-align: left; margin-top: 20px; }
        .ttd-left { float: left; width: 45%; text-align: left; margin-top: 20px; }
        .clear { clear: both; }

        /* Utility */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .indent { text-indent: 30px; }

        /* Tombol Print */
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <!-- Tombol Navigasi -->
    <div class="no-print" style="position: fixed; top: 0; right: 0; background: #eee; padding: 10px; border-bottom-left-radius: 8px; z-index: 999;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #1e40af; color: white; border: none; cursor: pointer; font-weight: bold; border-radius: 4px;">🖨️ Cetak Semua</button>
        <a href="{{ route('sppd.index') }}" style="margin-left: 10px; color: #333; text-decoration: none;">&larr; Kembali</a>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 1: SPPD (Surat Perjalanan Dinas)   -->
    <!-- ========================================== -->
    <div class="page">
        <!-- Kop Surat -->
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
            <tr>
                <td>1</td>
                <td>Pejabat berwenang yang memberi perintah</td>
                <td colspan="2">{{ $sppd->pejabat_jabatan }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Nama / NIP Pegawai yang diperintah</td>
                <td colspan="2">
                    <strong>{{ $sppd->user->name }}</strong><br>
                    NIP. {{ $sppd->user->nip ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>
                    a. Pangkat dan Golongan<br>
                    b. Jabatan / Instansi<br>
                    c. Tingkat Biaya Perjalanan Dinas
                </td>
                <td colspan="2">
                    a. {{ $sppd->user->pangkat ?? '-' }}<br>
                    b. {{ $sppd->user->position ?? 'Guru' }}<br>
                    c. -
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Maksud Perjalanan Dinas</td>
                <td colspan="2">{{ $sppd->maksud_perjalanan }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Alat Angkutan yang dipergunakan</td>
                <td colspan="2">{{ $sppd->alat_angkut ?? 'Kendaraan Umum' }}</td>
            </tr>
            <tr>
                <td>6</td>
                <td>
                    a. Tempat Berangkat<br>
                    b. Tempat Tujuan
                </td>
                <td colspan="2">
                    a. {{ $sppd->tempat_berangkat }}<br>
                    b. {{ $sppd->tempat_tujuan }}
                </td>
            </tr>
            <tr>
                <td>7</td>
                <td>
                    a. Lamanya Perjalanan Dinas<br>
                    b. Tanggal Berangkat<br>
                    c. Tanggal Harus Kembali
                </td>
                <td colspan="2">
                    a. {{ $sppd->lama_hari }} ({{ Terbilang($sppd->lama_hari) }}) hari<br>
                    b. {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}<br>
                    c. {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td>8</td>
                <td>Pengikut: Nama</td>
                <td style="width: 150px; text-align: center;">Tanggal Lahir</td>
                <td>Keterangan</td>
            </tr>
            <tr>
                <td></td>
                <td>1. -</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>9</td>
                <td>
                    Pembebanan Anggaran<br>
                    a. Instansi<br>
                    b. Mata Anggaran
                </td>
                <td colspan="2">
                    <br>
                    a. {{ $sppd->instansi_pembayar }}<br>
                    b. {{ $sppd->mata_anggaran ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>10</td>
                <td>Keterangan Lain</td>
                <td colspan="2">{{ $sppd->keterangan_lain ?? '-' }}</td>
            </tr>
        </table>

        <div class="ttd-area">
            <p>Dikeluarkan di: Lakbok</p>
            <p>Pada tanggal: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <br>
            <p style="font-weight: bold;">{{ $sppd->pejabat_jabatan }}</p>
            <br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">{{ $sppd->pejabat_nama }}</p>
            <p>NIP. {{ $sppd->pejabat_nip }}</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 2: VISUM (TANDA TANGAN & CAP)      -->
    <!-- ========================================== -->
    <div class="page-break"></div>

    <div class="page">
        <!-- Header Visum (Opsional, biasanya kosong atau judul kecil) -->
        <!-- <div class="text-center text-bold" style="margin-bottom:10px;">LEMBAR KE-II</div> -->
        
        <table class="visum">
            <!-- BARIS I: Berangkat dari kedudukan -->
            <tr>
                <td></td>
                <td>
                    <p style="margin:0;">I. Berangkat dari: {{ $sppd->tempat_berangkat }}</p>
                    <p style="margin:0; text-indent: 14px;">(Tempat Kedudukan)</p>
                    <p style="margin:0; text-indent: 14px;">Ke: {{ $sppd->tempat_tujuan }}</p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}</p>
                    <br>
                    <p style="margin:0; text-align:center; font-weight:bold;">Kepala SMP Negeri 3 Lakbok</p>
                    <br><br><br>
                    <p style="margin:0; text-align:center; font-weight:bold; text-decoration:underline;">{{ $sppd->pejabat_nama }}</p>
                    <p style="margin:0; text-align:center;">NIP. {{ $sppd->pejabat_nip }}</p>
                </td>
            </tr>

            <!-- BARIS II: Tiba dan Berangkat di Tujuan -->
            <tr>
                <td>
                    <p style="margin:0;">II. Tiba di: {{ $sppd->tempat_tujuan }}</p>
                    <p style="margin:0; text-indent: 18px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}</p>
                    <br><br>
                    <p style="margin:0; text-align:center;">Kepala / Pejabat Setempat</p>
                    <br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p>
                    <p style="margin:0; text-align:center;">NIP.</p>
                </td>
                <td>
                    <p style="margin:0;">Berangkat dari: {{ $sppd->tempat_tujuan }}</p>
                    <p style="margin:0; text-indent: 14px;">Ke: {{ $sppd->tempat_berangkat }}</p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}</p>
                    <br>
                    <p style="margin:0; text-align:center;">Kepala / Pejabat Setempat</p>
                    <br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p>
                    <p style="margin:0; text-align:center;">NIP.</p>
                </td>
            </tr>

            <!-- BARIS III (Kosong untuk multi tujuan) -->
            <tr>
                <td>
                    <p style="margin:0;">III. Tiba di:</p>
                    <p style="margin:0; text-indent: 22px;">Pada Tanggal:</p>
                    <br><br><br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p>
                </td>
                <td>
                    <p style="margin:0;">Berangkat dari:</p>
                    <p style="margin:0; text-indent: 14px;">Ke:</p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal:</p>
                    <br><br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p>
                </td>
            </tr>

            <!-- BARIS IV (Kosong untuk multi tujuan) -->
            <tr>
                <td>
                    <p style="margin:0;">IV. Tiba di:</p>
                    <p style="margin:0; text-indent: 22px;">Pada Tanggal:</p>
                    <br><br><br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p>
                </td>
                <td>
                    <p style="margin:0;">Berangkat dari:</p>
                    <p style="margin:0; text-indent: 14px;">Ke:</p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal:</p>
                    <br><br><br><br>
                    <p style="margin:0; text-align:center;">( .............................................. )</p>
                </td>
            </tr>

            <!-- BARIS V: Tiba Kembali di Tempat Kedudukan -->
            <tr>
                <td>
                    <p style="margin:0;">V. Tiba di: {{ $sppd->tempat_berangkat }}</p>
                    <p style="margin:0; text-indent: 18px;">(Tempat Kedudukan)</p>
                    <p style="margin:0; text-indent: 18px;">Pada Tanggal: {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}</p>
                    <br>
                    <p style="margin:0; text-align:center; font-weight:bold;">Kepala SMP Negeri 3 Lakbok</p>
                    <br><br><br>
                    <p style="margin:0; text-align:center; font-weight:bold; text-decoration:underline;">{{ $sppd->pejabat_nama }}</p>
                    <p style="margin:0; text-align:center;">NIP. {{ $sppd->pejabat_nip }}</p>
                </td>
                <td style="text-align: justify; padding: 10px;">
                    Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan dalam kurun waktu yang sesingkat-singkatnya.
                </td>
            </tr>

            <!-- BARIS VI: Catatan Lain-lain -->
            <tr>
                <td colspan="2" style="height: 50px;">
                    <p style="margin:0;">VI. Catatan Lain-lain:</p>
                </td>
            </tr>
            
            <!-- BARIS VII: PERHATIAN -->
            <tr>
                <td colspan="2" style="height: auto;">
                    <p style="margin:0; font-weight:bold;">VII. PERHATIAN:</p>
                    <p style="margin:0; text-align: justify; font-size: 9pt;">
                        Pejabat yang berwenang dan pejabat/pegawai yang melakukan perjalanan dinas bertanggung jawab sepenuhnya atas kerugian yang diderita oleh negara sebagai akibat dari kesalahan, kelalaian, atau kealpaan yang bersangkutan dalam hubungannya dengan penggunaan biaya perjalanan dinas.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 3: LAPORAN PERJALANAN DINAS        -->
    <!-- ========================================== -->
    <div class="page-break"></div>

    <div class="page">
        <!-- Kop Surat -->
        <div class="header">
            <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h4>SMP NEGERI 3 LAKBOK</h4>
            <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
        </div>
        <div class="line"></div>

        <div class="judul" style="margin-bottom: 30px;">
            <h2>LAPORAN PERJALANAN DINAS</h2>
        </div>

        <div class="content" style="line-height: 1.5;">
            <p><span class="label">I. DASAR</span><br>
            <span class="indent" style="display:block; margin-left: 30px; text-align: justify;">
                Surat Perintah Tugas Kepala SMP Negeri 3 Lakbok Nomor: {{ str_replace('090', '094', $sppd->nomor_sppd) }} Tanggal {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }}.
            </span>
            </p>

            <p><span class="label">II. MAKSUD DAN TUJUAN</span><br>
            <span class="indent" style="display:block; margin-left: 30px; text-align: justify;">
                {{ $sppd->maksud_perjalanan }}
            </span>
            </p>

            <p><span class="label">III. WAKTU PELAKSANAAN</span><br>
            <span class="indent" style="display:block; margin-left: 30px; text-align: justify;">
                Kegiatan dilaksanakan pada hari {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('dddd') }} tanggal {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y') }} bertempat di {{ $sppd->tempat_tujuan }}.
            </span>
            </p>

            <p><span class="label">IV. HASIL KEGIATAN</span><br>
            <span class="indent" style="display:block; margin-left: 30px; min-height: 150px; border-bottom: 1px dotted #999;">
                <!-- Area Kosong Untuk Diisi Tulis Tangan -->
            </span>
            <span class="indent" style="display:block; margin-left: 30px; min-height: 50px; border-bottom: 1px dotted #999;"></span>
            <span class="indent" style="display:block; margin-left: 30px; min-height: 50px; border-bottom: 1px dotted #999;"></span>
            </p>

            <p><span class="label">V. KESIMPULAN / SARAN-SARAN</span><br>
            <span class="indent" style="display:block; margin-left: 30px; min-height: 100px; border-bottom: 1px dotted #999;">
                <!-- Area Kosong Untuk Diisi Tulis Tangan -->
            </span>
            <span class="indent" style="display:block; margin-left: 30px; min-height: 50px; border-bottom: 1px dotted #999;"></span>
            </p>
        </div>

        <div style="margin-top: 50px;">
            <div style="float: left; width: 40%; text-align: center;">
                <p>Mengetahui,<br>Kepala Sekolah</p>
                <br><br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">{{ $sppd->pejabat_nama }}</p>
                <p>NIP. {{ $sppd->pejabat_nip }}</p>
            </div>
            
            <div style="float: right; width: 40%; text-align: center;">
                <p>Lakbok, {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y') }}<br>Pelapor,</p>
                <br><br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">{{ $sppd->user->name }}</p>
                <p>NIP. {{ $sppd->user->nip ?? '-' }}</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>