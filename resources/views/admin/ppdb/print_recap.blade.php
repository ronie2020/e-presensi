<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi PPDB {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h1 { font-size: 16px; margin: 0; }
        .header h2 { font-size: 14px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; text-align: center; }
        .status-accepted { font-weight: bold; }
        .footer { margin-top: 40px; float: right; text-align: center; width: 200px; }
        @media print {
            @page { size: landscape; margin: 1cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Dokumen</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="header">
        <h1>LAPORAN REKAPITULASI PENERIMAAN PESERTA DIDIK BARU (PPDB)</h1>
        <h2>SMP NEGERI 3 LAKBOK TAHUN PELAJARAN {{ $year }}/{{ $year+1 }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Pendaftaran</th>
                <th width="20%">Nama Lengkap</th>
                <th width="10%">NISN</th>
                <th width="15%">Asal Sekolah</th>
                <th width="10%">Jalur</th>
                <th width="10%">Nilai</th>
                <th width="15%">Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrants as $index => $row)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $row->registration_number }}</td>
                <td style="text-transform: uppercase;">{{ $row->full_name }}</td>
                <td>{{ $row->nisn }}</td>
                <td>{{ $row->school_origin }}</td>
                <td style="text-transform: capitalize;">{{ $row->track }}</td>
                <td style="text-align: center;">{{ $row->average_grade }}</td>
                <td style="text-align: center; text-transform: uppercase;" class="{{ $row->status == 'accepted' ? 'status-accepted' : '' }}">
                    {{ $row->status == 'accepted' ? 'DITERIMA' : ($row->status == 'rejected' ? 'Ditolak' : 'Proses') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Lakbok, {{ date('d F Y') }}</p>
        <p>Kepala Sekolah,</p>
        <br><br><br>
        <p><strong>TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</strong></p>
        <p>NIP. 197xxxxxx...</p>
    </div>

</body>
</html>