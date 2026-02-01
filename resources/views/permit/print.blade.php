<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.5; }
        .no-print { display: block; }
        @media print { 
            .no-print { display: none; } 
            body { -webkit-print-color-adjust: exact; }
        }
        
        /* [PERBAIKAN] Header menggunakan Tabel untuk Kop Surat */
        .header-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; }
        .header-table td { vertical-align: middle; }
        .header-title { font-size: 16pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .header-subtitle { font-size: 12pt; font-weight: bold; margin: 5px 0 0; }
        .header-address { font-size: 10pt; font-style: italic; margin: 2px 0; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; text-transform: uppercase; }
        table.data td { vertical-align: top; }
        
        .footer { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .signature { float: right; width: 250px; text-align: center; font-size: 11pt; }
        .signature p { margin-bottom: 70px; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 9999;
        }
        .btn-print:hover { background: #1e40af; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8pt; font-weight: bold; border: 1px solid #000; display: inline-block; }
        .badge-out { background: #ffedd5; }
        .badge-in { background: #d1fae5; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak / Simpan PDF
    </button>

    {{-- KOP SURAT --}}
    <table class="header-table">
        <tr>
            {{-- Logo Placeholder --}}
            <td width="15%" align="center" style="padding-bottom: 10px;">
                <div style="width: 70px; height: 70px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 1px solid #999;">LOGO</div>
                {{-- <img src="{{ asset('img/logo.png') }}" width="70" alt="Logo"> --}}
            </td>
            <td align="center" style="padding-bottom: 10px;">
                <h2 class="header-title">PEMERINTAH KABUPATEN CIAMIS</h2>
                <h3 class="header-subtitle">SMP NEGERI 3 LAKBOK</h3>
                <p class="header-address">Jl. Raya Lakbok No. 123, Cintaratu, Lakbok, Ciamis - 46385</p>
            </td>
            <td width="15%"></td>
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="text-decoration: underline; margin: 0; font-size: 12pt; font-weight: bold;">LAPORAN MONITORING IZIN SISWA</h3>
        <p style="margin: 5px 0; font-size: 10pt;">
            <strong>Periode:</strong> {{ request('date') ? \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') : 'Semua Waktu' }} 
            | <strong>Status:</strong> {{ request('status') ? ucfirst(request('status')) : 'Semua' }}
        </p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="15%">Keperluan</th>
                <th width="10%">Keluar</th>
                <th width="10%">Kembali</th>
                <th width="10%">Durasi</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permits as $index => $permit)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $permit->student->name }}</strong><br>
                    <small>{{ $permit->student->student_id }}</small>
                </td>
                <td style="text-align: center;">{{ $permit->student->schoolClass->name ?? '-' }}</td>
                <td>
                    {{ $permit->reason_category }}
                    @if($permit->notes) <br><i style="font-size:9pt">"{{ $permit->notes }}"</i> @endif
                </td>
                <td style="text-align: center;">
                    {{ $permit->time_out->format('H:i') }}<br>
                    <small>{{ $permit->time_out->format('d/m/y') }}</small>
                </td>
                <td style="text-align: center;">
                    @if($permit->time_in)
                        {{ $permit->time_in->format('H:i') }}
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($permit->duration_minutes)
                        {{ $permit->duration_minutes }} m
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($permit->status == 'OUT')
                        <span class="badge badge-out">SEDANG KELUAR</span>
                    @else
                        <span class="badge badge-in">KEMBALI</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">Data tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>
                Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Petugas Piket,
            </p>
            <div style="font-weight: bold; text-decoration: underline;">{{ Auth::user()->name ?? '.........................' }}</div>
            <div>NIP. .........................</div>
        </div>
    </div>

</body>
</html>