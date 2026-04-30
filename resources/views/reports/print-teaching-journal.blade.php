<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jurnal Mengajar</title>
    <style>
        /* Pengaturan Cetak Kertas A4 Landscape */
        @page {
            size: A4 landscape; 
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        
        /* Kop Surat Resmi */
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h2 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-surat h1 { margin: 5px 0; font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop-surat p { margin: 0; font-size: 10pt; font-style: italic; }

        /* Header Laporan */
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h3 { margin: 0; font-size: 12pt; text-transform: uppercase; text-decoration: underline; font-weight: bold; }
        .judul-laporan p { margin: 5px 0 0; font-size: 10pt; }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Utilitas Kolom */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .col-no { width: 4%; text-align: center; }
        .col-tgl { width: 12%; text-align: center; }
        .col-guru { width: 22%; }
        .col-kelas { width: 8%; text-align: center; }
        .col-materi { width: 38%; }
        .col-hadir { width: 16%; text-align: left; }

        /* Area Tanda Tangan */
        .signature-area { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .signature-box { float: right; width: 300px; text-align: center; }
        .clearfix::after { content: ""; clear: both; display: table; }

        /* Cegah tabel terpotong jelek saat pindah halaman */
        tr { page-break-inside: avoid; }
    </style>
</head>
<!-- Fungsi window.print() akan memunculkan dialog cetak secara otomatis -->
<body onload="window.print()">

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <h2>Pemerintah Kabupaten Ciamis</h2>
        <h1>SMP NEGERI 3 LAKBOK</h1>
        <p>Jl. Raya Lakbok, Kecamatan Lakbok, Kabupaten Ciamis - Jawa Barat</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>Laporan Rekapitulasi Jurnal Mengajar</h3>
        <p>
            Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}</strong>
        </p>
    </div>

    <!-- TABEL JURNAL -->
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-tgl">Tanggal & Waktu</th>
                <th class="col-guru">Nama Guru & Mapel</th>
                <th class="col-kelas">Kelas</th>
                <th class="col-materi">Topik Materi & Aktivitas</th>
                <th class="col-hadir">Kehadiran Siswa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $index => $session)
                @php 
                    // Mengambil nilai count yang dilempar dari controller
                    $hadir = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); 
                    $sakit = $session->sick_count ?? 0;
                    $izin = $session->permission_count ?? 0;
                    $alpha = $session->alpha_count ?? 0; 
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        <span class="text-bold">{{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</span><br>
                        {{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }} WIB
                    </td>
                    <td>
                        <span class="text-bold">{{ $session->teacher->name ?? '-' }}</span><br>
                        Mapel: {{ $session->schedule->subject->name ?? '-' }}
                    </td>
                    <td class="text-center">{{ $session->schedule->schoolClass->name ?? '-' }}</td>
                    <td>
                        <span class="text-bold">{{ $session->topic ?? 'Tanpa Topik' }}</span><br>
                        <span style="font-size: 9.5pt;">{{ $session->activities ?? '-' }}</span>
                    </td>
                    <td style="font-size: 9.5pt; line-height: 1.4;">
                        Hadir/Telat: <strong>{{ $hadir }}</strong> Siswa<br>
                        Sakit/Izin: <strong>{{ $sakit + $izin }}</strong> Siswa<br>
                        Alpha: <strong>{{ $alpha }}</strong> Siswa
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">
                        <em>Tidak ada data jurnal mengajar pada periode atau filter yang dipilih.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="signature-area clearfix">
        <div class="signature-box">
            <p>Lakbok, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>Kepala Sekolah,</p>
            <br><br><br><br>
            <p class="text-bold" style="text-decoration: underline; margin-bottom: 2px;">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
            <p style="margin-top: 0;">NIP. 19820928201101 1002</p>
        </div>
    </div>

</body>
</html>