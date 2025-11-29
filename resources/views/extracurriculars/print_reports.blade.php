<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehadiran Ekskul</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14pt;
            font-weight: normal;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
            text-align: right;
        }
        .footer p {
            margin-bottom: 60px;
        }
        /* Print specific styles */
        @media print {
            @page {
                size: A4;
                margin: 1.5cm;
            }
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .btn-print {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <!-- Tombol Cetak (Akan hilang saat diprint) -->
    <button onclick="window.print()" class="btn-print no-print">
        <svg style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:5px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan
    </button>

    <div class="header">
        <h1>Laporan Kehadiran Ekstrakurikuler</h1>
        <h2>SMP Negeri 3 Lakbok</h2>
    </div>

    @if($ekskul)
        <table class="info-table">
            <tr>
                <td width="150"><strong>Nama Kegiatan</strong></td>
                <td width="10">:</td>
                <td>{{ $ekskul->name }}</td>
                <td width="100"><strong>Periode</strong></td>
                <td width="10">:</td>
                <td>
                    {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s.d. 
                    {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td><strong>Pembina</strong></td>
                <td>:</td>
                <td>{{ $ekskul->coach_name ?? '-' }}</td>
                <td><strong>Total Hadir</strong></td>
                <td>:</td>
                <td>{{ $attendances->count() }} Siswa</td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="15%">Jam</th>
                    <th width="15%">Kelas</th>
                    <th>Nama Siswa</th>
                    <th width="15%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $index => $log)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ $log->time_in }}</td>
                    <td style="text-align: center;">{{ $log->student->schoolClass->name ?? $log->student->class_name ?? '-' }}</td>
                    <td>{{ $log->student->name }}</td>
                    <td style="text-align: center;">Hadir</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data kehadiran pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Lakbok, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">{{ $ekskul->coach_name ?? 'Pembina Ekskul' }}</p>
            <span style="font-size: 10pt;">Pembina Ekstrakurikuler</span>
        </div>

    @else
        <div style="text-align: center; padding: 50px;">
            <p>Silakan pilih kegiatan ekstrakurikuler terlebih dahulu untuk mencetak laporan.</p>
        </div>
    @endif

    <script>
        // Otomatis print saat halaman dimuat (opsional, bisa dihapus jika mengganggu)
        window.onload = function() { window.print(); }
    </script>
</body>
</html>