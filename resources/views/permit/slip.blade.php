<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Izin - {{ $permit->student->name }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 4mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 8px;
            line-height: 1.35;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .double-divider {
            border-top: 2px solid #000;
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 8px;
            background: #0284c7;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            margin-bottom: 12px;
            font-family: sans-serif;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">🖨️ Cetak Slip (Printer Thermal / Kertas)</button>

    <div class="text-center">
        <div class="font-bold uppercase" style="font-size: 13px;">SMP NEGERI 3 LAKBOK</div>
        <div style="font-size: 9px;">Kec. Lakbok, Kab. Ciamis, Jawa Barat</div>
        <div class="double-divider"></div>
        <div class="font-bold uppercase" style="font-size: 12px; letter-spacing: 0.5px;">SURAT IZIN KELUAR</div>
        <div style="font-size: 9px;">NO: #IZN-{{ str_pad($permit->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td width="38%">Tanggal / Jam</td>
            <td width="4%">:</td>
            <td width="58%" class="font-bold">{{ $permit->time_out->translatedFormat('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td>Nama Siswa</td>
            <td>:</td>
            <td class="font-bold uppercase">{{ $permit->student->name }}</td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td>{{ $permit->student->student_id ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td class="font-bold">{{ $permit->student->schoolClass->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Keperluan</td>
            <td>:</td>
            <td class="font-bold">{{ $permit->reason_category }}</td>
        </tr>
        @if($permit->notes)
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td><i>{{ $permit->notes }}</i></td>
        </tr>
        @endif
        <tr>
            <td>Batas Kembali</td>
            <td>:</td>
            <td class="font-bold" style="color: #000;">
                @if($permit->reason_category === 'Pulang Cepat')
                    Pulang ke Rumah
                @else
                    {{ $permit->time_out->copy()->addMinutes(match($permit->reason_category){ 'Toilet' => 10, 'Barang Tertinggal' => 15, 'UKS' => 45, default => 60 })->format('H:i') }} WIB
                @endif
            </td>
        </tr>
        <tr>
            <td>Guru Piket</td>
            <td>:</td>
            <td>{{ $permit->teacher->name ?? 'Petugas Piket' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 6px;">
        <div style="display: inline-block; padding: 4px; background: #fff;">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($permit->student->student_id) !!}
        </div>
        <div style="font-size: 8px; margin-top: 3px; font-weight: bold;">
            Scan Barcode untuk Verifikasi / Kembali
        </div>
        <div style="font-size: 8px; font-style: italic; color: #555; margin-top: 2px;">
            Tunjukkan slip ini kepada Petugas Satpam di Gerbang Sekolah.
        </div>
    </div>

    <div class="divider"></div>
    <div class="text-center" style="font-size: 8px; color: #666;">
        Dicetak: {{ now()->translatedFormat('d/m/Y H:i:s') }}
    </div>

    <script>
        // Auto print jika dibuka dengan parameter ?autoprint=1
        if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
            window.onload = () => window.print();
        }
    </script>
</body>
</html>
