<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Penggunaan Aplikasi</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 2px 0;
            font-size: 16pt;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 9pt;
            color: #444;
        }
        .meta-box {
            margin-bottom: 15px;
            padding: 8px 12px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            font-size: 10pt;
        }
        .summary-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .summary-item {
            flex: 1;
            padding: 10px;
            border: 1px solid #bbb;
            text-align: center;
        }
        .summary-item .num {
            font-size: 16pt;
            font-weight: bold;
            color: #0b4f8a;
        }
        .summary-item .label {
            font-size: 9pt;
            color: #555;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9.5pt;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
        }
        th {
            background-color: #f1f3f5;
            text-align: left;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .text-center { text-align: center; }
        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Dokumen
        </button>
    </div>

    <div class="header">
        <h2>PEMERINTAH KABUPATEN CIAMIS</h2>
        <h3>SMP NEGERI 3 LAKBOK</h3>
        <p>Jalan Lakbok No. 1, Lakbok, Ciamis, Jawa Barat | Sistem Informasi Manajemen Terpadu (SIMADU)</p>
    </div>

    <div style="text-align: center; margin-bottom: 15px;">
        <h4 style="margin: 0; text-transform: uppercase; text-decoration: underline;">
            LAPORAN REKAPITULASI PENGGUNAAN APLIKASI & SUPERVISI DIGITAL
        </h4>
        <p style="margin: 3px 0; font-size: 10pt; color: #444;">
            Periode: <strong>{{ $periodLabel }}</strong>
        </p>
    </div>

    <div class="summary-grid">
        <div class="summary-item">
            <div class="num">{{ number_format($totalLogins) }}</div>
            <div class="label">Total Sesi Login</div>
        </div>
        <div class="summary-item">
            <div class="num">{{ $uniqueTeachers }}</div>
            <div class="label">Guru Terlibat</div>
        </div>
        <div class="summary-item">
            <div class="num">{{ number_format($kbmSessionsCount) }}</div>
            <div class="label">Jurnal KBM Terisi</div>
        </div>
        <div class="summary-item">
            <div class="num">{{ number_format($attendanceScansCount) }}</div>
            <div class="label">Record Presensi</div>
        </div>
    </div>

    <h5 style="margin: 15px 0 5px 0; text-transform: uppercase; font-size: 10pt;">
        I. Daftar Keterlibatan & Aktivitas Guru
    </h5>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Nama Guru</th>
                <th>NIP</th>
                <th>Jabatan / Role</th>
                <th class="text-center">Jurnal KBM Terisi</th>
                <th>Login Terakhir</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $idx => $teacher)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $teacher->name }}</strong></td>
                    <td>{{ $teacher->nip ?? '-' }}</td>
                    <td>{{ $teacher->position ?? ($teacher->role ?? 'Guru') }}</td>
                    <td class="text-center">{{ $teacher->sessions_count }} Sesi</td>
                    <td>{{ $teacher->last_login ? $teacher->last_login->format('d/m/Y H:i') : 'Belum pernah' }}</td>
                    <td class="text-center">
                        @if($teacher->sessions_count > 0 || $teacher->last_login)
                            <span style="color: green; font-weight: bold;">Aktif</span>
                        @else
                            <span style="color: red; font-weight: bold;">Pasif</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-box">
            <p>Lakbok, {{ now()->translatedFormat('d F Y') }}<br>Kepala Sekolah,</p>
            <br><br><br>
            <p><strong><u>H. Nama Kepala Sekolah, M.Pd.</u></strong><br>NIP. 19700101 199503 1 001</p>
        </div>
    </div>

</body>
</html>
