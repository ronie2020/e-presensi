<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Soal - {{ $exam->title }}</title>
    <style>
        /* Menggunakan ukuran A4 Landscape agar tabel muat banyak kolom */
        @page { size: A4 landscape; margin: 1.5cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.3; font-size: 11pt; }
        
        .no-print { display: block; margin-bottom: 20px; text-align: right; }
        @media print { 
            .no-print { display: none; } 
            body { -webkit-print-color-adjust: exact; }
        }
        
        /* Kop Surat */
        .header-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 15px; }
        .header-table td { vertical-align: middle; }
        .header-title { font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .header-subtitle { font-size: 12pt; font-weight: bold; margin: 2px 0 0; }
        .header-address { font-size: 9pt; font-style: italic; margin: 2px 0; }

        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { margin: 0; text-decoration: underline; text-transform: uppercase; font-size: 12pt; }
        .report-info { margin-top: 10px; font-size: 10pt; width: 60%; margin-left: auto; margin-right: auto; }
        .report-info td { padding: 2px; vertical-align: top; }

        /* Tabel Data */
        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 8px; vertical-align: middle; }
        table.data th { background-color: #e0e0e0; text-align: center; font-weight: bold; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Distribusi List */
        .dist-list { margin: 0; padding-left: 15px; list-style-type: square; font-size: 9.5pt; }
        .key-correct { font-weight: bold; color: #166534; } /* Warna hijau tua untuk kunci saat dicetak berwarna */

        /* Footer Tanda Tangan */
        .footer { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; width: 35%; }
        
        /* Tombol Cetak */
        .btn-print {
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 6px; cursor: pointer;
            font-weight: bold; font-family: sans-serif; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            text-decoration: none; display: inline-block;
        }
        .btn-print:hover { background: #1e40af; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    {{-- KOP SURAT --}}
    <table class="header-table">
        <tr>
            <td width="12%" align="center" style="padding-bottom: 10px;">
                <div style="width: 70px; height: 70px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid #000;">LOGO</div>
            </td>
            <td align="center" style="padding-bottom: 10px;">
                <h2 class="header-title">PEMERINTAH KABUPATEN CIAMIS</h2>
                <h2 class="header-subtitle">DINAS PENDIDIKAN</h2>
                <h1 class="header-title" style="font-size: 16pt; margin-top: 5px;">SMP NEGERI 3 LAKBOK</h1>
                <p class="header-address">Jl. Raya Lakbok No. 123, Cintaratu, Lakbok, Ciamis - 46385</p>
            </td>
            <td width="12%"></td>
        </tr>
    </table>

    <div class="report-title">
        <h3>LAPORAN ANALISIS BUTIR SOAL</h3>
        
        <table class="report-info" align="center">
            <tr>
                <td width="30%"><strong>Mata Pelajaran</strong></td>
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
                <td><strong>Sampel Data</strong></td>
                <td>:</td>
                <td>{{ $totalStudents }} Siswa</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="35%">Cuplikan Soal</th>
                <th width="8%">Tipe</th>
                <th width="8%">Kunci</th>
                <th width="15%">Tingkat Kesukaran</th>
                <th width="30%">Distribusi Jawaban Siswa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($analysis as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="text-align: justify;">
                    {{-- Membatasi teks soal agar tabel tidak terlalu panjang ke bawah --}}
                    {{ Str::limit(strip_tags($item->text), 150) }}
                </td>
                <td class="text-center">
                    @if(in_array($item->type, ['choice', 'true_false'])) PG 
                    @elseif($item->type == 'essay') ESSAI
                    @elseif($item->type == 'matching') MATCHING
                    @endif
                </td>
                <td class="text-center font-bold">
                    {{ in_array($item->type, ['choice', 'true_false']) ? $item->correct_key : '-' }}
                </td>
                <td class="text-center">
                    <strong>{{ $item->difficulty_index }}%</strong> Benar<br>
                    <span style="font-size: 9pt;">({{ $item->difficulty_label }})</span>
                </td>
                <td>
                    @if(in_array($item->type, ['choice', 'true_false']))
                        <ul class="dist-list">
                            @foreach(['A','B','C','D','E'] as $opt)
                                @if(isset($item->options[$opt]) || $opt != 'E')
                                    @php 
                                        $count = $item->options[$opt] ?? 0;
                                        $percent = $totalStudents > 0 ? round(($count / $totalStudents) * 100) : 0;
                                        $isKey = $opt == $item->correct_key;
                                    @endphp
                                    <li class="{{ $isKey ? 'key-correct' : '' }}">
                                        Opsi <b>{{ $opt }}</b> : {{ $count }} Siswa ({{ $percent }}%) {!! $isKey ? '&#10003;' : '' !!}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @elseif($item->type == 'essay')
                        <div class="text-center" style="color: gray; font-style: italic; padding-top: 10px;">
                            Dikoreksi Manual
                        </div>
                    @else
                        <div class="text-center">-</div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Belum ada data analisis.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

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