<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bimbingan Konseling - {{ \Carbon\Carbon::now()->format('d-m-Y') }}</title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.3; font-size: 10pt; }
        
        .no-print { display: block; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; }
            .page-break { page-break-before: always; }
        }
        
        /* Header Kop Surat */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h1 { font-size: 16pt; margin: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 14pt; margin: 5px 0 0; font-weight: bold; text-transform: uppercase; }
        .header p { font-size: 10pt; margin: 2px 0; font-style: italic; }

        /* Meta Info */
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 10pt; border: none; }
        .meta-table td { padding: 2px 0; vertical-align: top; }
        .meta-title { font-weight: bold; width: 120px; }

        /* Main Table */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 9pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 4px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; vertical-align: middle; height: 30px; }
        table.data td { vertical-align: top; }
        table.data .center { text-align: center; }
        table.data .left { text-align: left; padding-left: 5px; }
        
        /* Footer */
        .footer { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-box p { margin-bottom: 60px; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; bottom: 30px; right: 30px;
            background: #e11d48; color: white; border: none;
            padding: 12px 24px; border-radius: 50px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 15px rgba(225, 29, 72, 0.4);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 1000;
        }
        .btn-print:hover { background: #be123c; }
        
        .status-badge { font-weight: bold; text-transform: uppercase; font-size: 8pt;}
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan / Simpan PDF
    </button>

    <div class="header">
        <h1>REKAPITULASI LAPORAN BIMBINGAN KONSELING</h1>
        <h2>NAMA SEKOLAH ANDA</h2>
        <p>Jl. Contoh Alamat Sekolah No. 123, Kota/Kabupaten, Kode Pos 12345</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-title">Tanggal Dicetak</td>
            <td width="10">:</td>
            <td width="300">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
            
            <td class="meta-title">Total Data</td>
            <td width="10">:</td>
            <td>{{ $sessions->count() }} Laporan</td>
        </tr>
        <tr>
            <td class="meta-title">Dicetak Oleh</td>
            <td>:</td>
            <td>{{ auth()->user()->name ?? 'Administrator' }}</td>

            <td class="meta-title">Filter Pencarian</td>
            <td>:</td>
            <td>
                Status: {{ ucfirst(request('status', 'Semua')) }} | Tipe: {{ ucfirst(request('type', 'Semua')) }} 
                @if(request('search')) | Keyword: "{{ request('search') }}" @endif
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="12%">Kategori</th>
                <th width="35%">Pesan / Laporan</th>
                <th width="8%">Metode</th>
                <th width="8%">Status</th>
                <th width="8%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $index => $session)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="left" style="text-transform: uppercase;">{{ $session->student->name ?? 'Data Terhapus' }}</td>
                <td class="center">{{ $session->student->schoolClass->name ?? '-' }}</td>
                <td class="center">{{ $session->category->name ?? 'Umum' }}</td>
                <td class="left" style="font-style: italic;">
                    @if($session->is_system_generated)
                        <strong>[SISTEM]</strong> 
                    @endif
                    "{{ $session->initial_message }}"
                </td>
                <td class="center">{{ $session->method == 'online' ? 'Online' : 'Tatap Muka' }}</td>
                <td class="center status-badge">
                    {{ $session->status == 'approved' ? 'Terjadwal' : $session->status }}
                </td>
                <td class="center">{{ $session->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="center" style="padding: 20px;">Tidak ada data laporan yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>
                Mengetahui,<br>
                Guru Bimbingan Konseling
            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 20px;">
                {{ auth()->user()->name ?? '( ........................................... )' }}
            </div>
            <div style="margin-top: 5px;">NIP. .....................................</div>
        </div>
    </div>

</body>
</html>