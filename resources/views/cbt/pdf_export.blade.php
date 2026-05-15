<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai - {{ $exam->title }}</title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        
        /* Font disesuaikan menjadi Bookman Old Style */
        body { font-family: 'Bookman Old Style', Bookman, Georgia, serif; color: #000; line-height: 1.3; font-size: 11pt; }
        
        .no-print { display: block; margin-bottom: 20px; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        
        /* --- KOP SURAT STYLE --- */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 4px; line-height: 1.1; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1.1; }
        .kop-alamat { font-size: 12pt; font-style: normal; line-height: 1.2; }
        .kop-kontak { font-size: 11pt; margin-top: 4px; }
        .garis-kop { border: none; border-top: 4px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin-bottom: 15px; }

        /* Judul Laporan */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { margin: 0; text-decoration: underline; text-transform: uppercase; font-size: 14pt; font-weight: bold; }
        .report-info { margin-top: 10px; font-size: 11pt; width: 100%; }
        .report-info td { padding: 4px 2px; vertical-align: top; }

        /* Tabel Data */
        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; }
        table.data th { background-color: #e0e0e0; text-align: center; font-weight: bold; vertical-align: middle; }
        table.data td { vertical-align: middle; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Footer Tanda Tangan */
        .footer { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; width: 35%; }
        
        /* Tombol Cetak (Diseragamkan) */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 9999;
        }
        .btn-print:hover { background: #1e40af; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak / Simpan PDF
    </button>

    {{-- KOP SURAT RESMI --}}
    <table class="kop-surat">
        <tr>
            <td width="15%" style="text-align: center;">
                <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" style="width: 85px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
            </td>
            <td width="70%" style="text-align: center;">
                <div class="kop-dinas">PEMERINTAH KABUPATEN CIAMIS</div>
                <div class="kop-sekolah">SMP NEGERI 3 LAKBOK</div>
                <div class="kop-alamat">Jalan Mekarjaya No.199, Sidaharja</div>
                <div class="kop-alamat">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                <div class="kop-kontak">
                    Laman: <a href="http://www.smpn3lakbok.sch.id" style="color: #1d4ed8; text-decoration: underline;">www.smpn3lakbok.sch.id</a> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    E-mail: netila.smp@gmail.com
                </div>
            </td>
            <td width="15%" style="text-align: center;">
                <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo SMP" style="width: 90px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
            </td>
        </tr>
    </table>
    <hr class="garis-kop">

    <div class="report-title">
        <h3>REKAPITULASI HASIL UJIAN</h3>
        
        <table class="report-info" align="center" style="width: 65%; margin: 15px auto;">
            <tr>
                <td width="35%"><strong>Mata Pelajaran</strong></td>
                <td width="5%">:</td>
                <td>{{ $exam->subject_name }}</td>
            </tr>
            <tr>
                <td><strong>Judul Ujian</strong></td>
                <td>:</td>
                <td>{{ $exam->title }}</td>
            </tr>
            <tr>
                <td><strong>Kelas / Tingkat</strong></td>
                <td>:</td>
                <td>{{ $exam->class_level }}</td>
            </tr>
            <tr>
                <td><strong>KKM / Passing Grade</strong></td>
                <td>:</td>
                <td>{{ $exam->passing_grade }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Siswa</th>
                <th width="15%">NISN</th>
                <th width="15%">Kelas</th>
                <th width="10%">Benar</th>
                <th width="10%">Salah</th>
                <th width="10%">Nilai</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $row->student_name }}</strong>
                </td>
                <td class="text-center">{{ $row->student_nisn ?? '-' }}</td>
                <td class="text-center">{{ $row->class_name ?? '-' }}</td>
                <td class="text-center" style="color: green;">{{ $row->correct_answers }}</td>
                <td class="text-center" style="color: red;">{{ $row->wrong_answers }}</td>
                <td class="text-center font-bold" style="font-size: 11pt;">{{ $row->total_score }}</td>
                <td class="text-center">
                    @if($row->total_score >= $exam->passing_grade)
                        <span style="font-weight: bold;">LULUS</span>
                    @else
                        <span>REMEDIAL</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Belum ada data nilai masuk.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Ringkasan Statistik --}}
    <div style="margin-top: 15px; font-size: 10pt; width: 40%;">
        <table class="data">
            <tr>
                <td style="background: #f0f0f0;"><strong>Rata-rata Nilai</strong></td>
                <td class="text-center"><strong>{{ number_format($stats['average'], 1) }}</strong></td>
            </tr>
            <tr>
                <td style="background: #f0f0f0;"><strong>Nilai Tertinggi</strong></td>
                <td class="text-center">{{ $stats['max_score'] }}</td>
            </tr>
            <tr>
                <td style="background: #f0f0f0;"><strong>Nilai Terendah</strong></td>
                <td class="text-center">{{ $stats['min_score'] }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah
                    <br><br><br><br><br>
                    <strong>TANTAN SUTANDI N., S.Si, M.Pd.</strong><br>
                    NIP. 19820928 201101 1 002
                </td>
                <td></td>
                <td>
                    Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Guru Mata Pelajaran
                    <br><br><br><br><br>
                    <strong>{{ Auth::user()->name }}</strong><br>
                    NIP. .........................
                </td>
            </tr>
        </table>
    </div>

</body>
</html>