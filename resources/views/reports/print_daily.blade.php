<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Harian - {{ $selectedDate_db->format('d-m-Y') }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.5; font-size: 11pt; }
        
        .no-print { display: block; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; }
            .page-break { page-break-before: always; }
        }
        
        /* Header */
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h1 { font-size: 16pt; margin: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 12pt; margin: 5px 0 0; font-weight: normal; }
        .header p { font-size: 10pt; margin: 2px 0; font-style: italic; }

        /* Meta Info */
        .meta-table { width: 100%; margin-bottom: 20px; font-size: 11pt; border: none; }
        .meta-table td { padding: 2px 0; vertical-align: top; }
        .meta-title { font-weight: bold; width: 150px; }

        /* Tables */
        .section-title { font-weight: bold; font-size: 12pt; margin-top: 20px; margin-bottom: 8px; text-decoration: underline; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 8px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; text-transform: uppercase; }
        table.data td { vertical-align: middle; }
        table.data .center { text-align: center; }
        table.data .right { text-align: right; }
        
        /* Helpers */
        .text-danger { color: #d32f2f; font-weight: bold; }
        .text-muted { color: #666; font-style: italic; }
        
        /* Footer */
        .footer { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-box p { margin-bottom: 70px; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #2563eb; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 1000;
        }
        .btn-print:hover { background: #1d4ed8; }
        .summary-box { border: 1px solid #000; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-around; background: #fafafa; }
        .summary-item { text-align: center; }
        .summary-val { font-weight: bold; font-size: 1.2em; display: block; }
        .summary-label { font-size: 0.9em; text-transform: uppercase; color: #555; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan
    </button>

    <div class="header">
        <h1>Laporan Absensi Harian Siswa</h1>
        <h2>SMP NEGERI 3 LAKBOK</h2>
        <p>Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Ciamis, Jawa Barat</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-title">Hari / Tanggal</td>
            <td width="10">:</td>
            <td>{{ $selectedDate_db->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td class="meta-title">Dicetak Oleh</td>
            <td>:</td>
            <td>{{ auth()->user()->name ?? 'Administrator' }}</td>
        </tr>
    </table>

    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-val">{{ $stats['hadir'] + $stats['terlambat'] }}</span>
            <span class="summary-label">Hadir</span>
        </div>
        <div class="summary-item">
            <span class="summary-val">{{ $stats['sakit'] }}</span>
            <span class="summary-label">Sakit</span>
        </div>
        <div class="summary-item">
            <span class="summary-val">{{ $stats['izin'] }}</span>
            <span class="summary-label">Izin</span>
        </div>
        <div class="summary-item">
            <span class="summary-val">{{ $stats['alfa'] }}</span>
            <span class="summary-label">Alfa</span>
        </div>
        <div class="summary-item">
            <span class="summary-val">{{ $stats['belum'] }}</span>
            <span class="summary-label">Belum Absen</span>
        </div>
    </div>

    {{-- Tabel 1: Siswa Hadir & Terlambat --}}
    @if($attendancesHadir->count() > 0)
        <div class="section-title">A. Daftar Siswa Hadir</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="15%">Jam Masuk</th>
                    <th width="15%">Jam Pulang</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendancesHadir as $index => $att)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $att->student->name }}</td>
                    <td class="center">{{ $att->student->schoolClass->name ?? '-' }}</td>
                    <td class="center">{{ $att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('H:i') : '-' }}</td>
                    <td class="center">{{ $att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('H:i') : '-' }}</td>
                    <td>
                        {{ $att->status }}
                        @if($att->status == 'Terlambat') <small class="text-danger">(- Poin)</small> @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Tabel 2: Siswa Izin/Sakit/Alfa --}}
    @if($attendancesLain->count() > 0)
        <div class="section-title">B. Daftar Ketidakhadiran (Izin / Sakit / Alfa)</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="15%">Status</th>
                    <th>Catatan / Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendancesLain as $index => $att)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $att->student->name }}</td>
                    <td class="center">{{ $att->student->schoolClass->name ?? '-' }}</td>
                    <td class="center" style="font-weight: bold;">
                        {{ $att->status }}
                        {{-- TAMBAHAN: Indikator Poin --}}
                        @if(in_array($att->status, ['Alfa', 'Alpa']))
                            <br><small class="text-danger">(-10 Poin)</small>
                        @endif
                    </td>
                    <td>{{ $att->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Tabel 3: Belum Absen --}}
    @if($belumAbsenList->count() > 0)
        <div class="section-title text-danger">C. Daftar Siswa Belum Absen</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Nama Siswa</th>
                    <th width="20%">Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($belumAbsenList as $index => $student)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td class="center">{{ $student->schoolClass->name ?? '-' }}</td>
                    <td class="center text-muted">Belum ada keterangan</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <div class="signature-box">
            <p>
                Lakbok, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                Mengetahui,<br>
                Kepala Sekolah / Guru Piket
            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 20px;">
                ( ........................................... )
            </div>
            <div style="margin-top: 5px;">NIP. .....................................</div>
        </div>
    </div>

</body>
</html>