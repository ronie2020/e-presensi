<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Prestasi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Prestasi & Penghargaan</h1>
        <p>SMP NEGERI 3 LAKBOK</p>
        <p>Dicetak pada: {{ date('d F Y') }}</p>
    </div>

    @if(request('search'))
        <p style="font-style: italic; font-size: 11px;">
            Filter Pencarian: "{{ request('search') }}"
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Juara</th>
                <th style="width: 10%">Tipe</th>
                <th style="width: 30%">Judul Prestasi</th>
                <th style="width: 15%">Tingkat</th>
                <th style="width: 15%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($achievements as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{-- Logika Nama --}}
                        @if($item->type === 'Siswa' && $item->student)
                            {{ $item->student->name }} <br>
                            <small style="color: #666">({{ $item->student->schoolClass->name ?? '-' }})</small>
                        @else
                            {{ $item->achiever_name ?? $item->name_manual }}
                        @endif
                    </td>
                    <td class="text-center">{{ $item->type }}</td>
                    <td>{{ $item->title }}</td>
                    <td class="text-center">{{ $item->level }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data prestasi yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,<br>Kepala Sekolah / Waka Kesiswaan</p>
        <br><br><br>
        <p>__________________________</p>
    </div>

</body>
</html>