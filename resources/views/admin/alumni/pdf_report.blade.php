<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Alumni - {{ $year }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
        }
        .header h1 {
            margin: 0 0 4px 0;
            font-size: 15px;
            text-transform: uppercase;
            font-weight: 800;
            color: #0f172a;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            color: #334155;
        }
        .header p {
            margin: 0;
            font-size: 10px;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-blue { background-color: #e0f2fe; color: #0369a1; }
        .badge-amber { background-color: #fef3c7; color: #b45309; }
        .badge-emerald { background-color: #d1fae5; color: #047857; }
        .badge-slate { background-color: #f1f5f9; color: #475569; }

        .meta-info {
            margin-top: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #475569;
        }
        .footer {
            margin-top: 30px;
            float: right;
            text-align: center;
            font-size: 10px;
            width: 200px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN TRACER STUDY & DATA ALUMNI</h1>
        <h2>TAHUN KELULUSAN: {{ $year }}</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB</p>
    </div>

    <table style="width: 100%; border: none; margin-bottom: 8px;">
        <tr style="background: none;">
            <td style="border: none; padding: 0; font-size: 10px; color: #475569;">
                Total Alumni Terdata: <strong>{{ $alumni->count() }} Orang</strong>
            </td>
            <td style="border: none; padding: 0; text-align: right; font-size: 10px; color: #475569;">
                Status Filter Angkatan: <strong>{{ $year }}</strong>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 4%;">No</th>
                <th style="width: 14%;">NISN / ID</th>
                <th style="width: 20%;">Nama Lengkap</th>
                <th class="text-center" style="width: 5%;">L/P</th>
                <th class="text-center" style="width: 9%;">Thn Lulus</th>
                <th style="width: 14%;">Aktivitas Terkini</th>
                <th style="width: 22%;">Nama Sekolah / Instansi</th>
                <th style="width: 12%;">No. WhatsApp</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumni as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $item->student_id ?? '-' }}</td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td class="text-center">{{ $item->gender ?? '-' }}</td>
                    <td class="text-center">
                        {{ $item->graduation_year ?? ($item->graduated_date ? \Carbon\Carbon::parse($item->graduated_date)->year : '-') }}
                    </td>
                    <td>
                        @if($item->alumniProfile && $item->alumniProfile->activity_status)
                            <span class="badge {{ in_array($item->alumniProfile->activity_status, ['SMA', 'SMK', 'MA']) ? 'badge-blue' : (in_array($item->alumniProfile->activity_status, ['Pesantren']) ? 'badge-emerald' : 'badge-slate') }}">
                                {{ $item->alumniProfile->activity_status }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">Belum diisi</span>
                        @endif
                    </td>
                    <td>
                        @if($item->alumniProfile)
                            <div>{{ $item->alumniProfile->campus_name ?? $item->alumniProfile->company_name ?? '-' }}</div>
                            @if($item->alumniProfile->campus_major || $item->alumniProfile->position)
                                <div style="font-size: 8px; color: #64748b;">
                                    {{ $item->alumniProfile->campus_major ?? $item->alumniProfile->position }}
                                </div>
                            @endif
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td>
                        {{ $item->alumniProfile->phone_number ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada data alumni ditemukan untuk kriteria ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>{{ config('app.school_city', 'Kota') }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p style="margin-bottom: 50px;">Koordinator Tracer Study / TU</p>
        <p><strong>_______________________</strong></p>
    </div>

</body>
</html>
