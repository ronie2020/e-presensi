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
        
        /* Tombol Modern untuk Mode Tampilan */
        .no-print { margin-bottom: 20px; padding: 15px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px; transition: 0.2s;}
        .btn-primary { background: #0d52a1; color: white; }
        .btn-primary:hover { background: #2c3f61; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }

        @media print {
            @page { size: landscape; margin: 1cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button class="btn btn-primary" onclick="window.print()">Cetak Dokumen</button>
        <button class="btn btn-secondary" onclick="window.close()">Tutup</button>
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
        <p>NIP. 198209282011011002</p>
    </div>

</body>
</html>