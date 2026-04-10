<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Kelas - {{ $selectedClass->name ?? '' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap');
        
        body {
            font-family: 'Noto Sans', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        .table-compact th, .table-compact td {
            padding: 4px 2px;
            font-size: 10px; 
            border: 1px solid #000;
        }

        /* Border pembatas antar hari */
        .border-day-end {
            border-right: 2px solid #000 !important;
        }

        .libur {
            background-color: #e5e7eb !important; /* gray-200 */
        }
    </style>
</head>
<body onload="window.print()" class="bg-white text-black p-4">

    {{-- KOP SURAT SEDERHANA --}}
    <div class="border-b-2 border-black pb-4 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            {{-- Ganti src dengan URL logo sekolah Anda --}}
            <img src="https://ui-avatars.com/api/?name=S&background=000&color=fff&size=64" class="w-16 h-16 object-contain" alt="Logo">
            <div>
                <h1 class="text-xl font-bold uppercase tracking-wide">Rekapitulasi Absensi Siswa</h1>
                <h1 class="text-xl font-bold uppercase tracking-wide">SMP NEGERI 3 LAKBOK</h1>
                <p class="text-sm font-medium">Laporan Kehadiran Bulanan (Detail Masuk/Pulang)</p>
            </div>
        </div>
        <div class="text-right text-sm">
            <table>
                <tr>
                    <td class="font-bold pr-2">Kelas</td>
                    <td>: {{ $selectedClass->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-bold pr-2">Periode</td>
                    <td>: {{ $startDate->translatedFormat('F Y') }}</td>
                </tr>
                <tr>
                    <td class="font-bold pr-2">Wali Kelas</td>
                    <td>: {{ $selectedClass->homeroomTeacher->name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- TABEL --}}
    <table class="w-full table-compact border-collapse mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th rowspan="2" class="w-8 text-center align-middle border-day-end">No</th>
                <th rowspan="2" class="text-left pl-2 align-middle border-day-end">Nama Siswa</th>
                
                {{-- Loop Tanggal --}}
                @foreach($dates as $date)
                    <th colspan="2" class="text-center border-day-end {{ $date->isSunday() ? 'libur' : '' }}">
                        {{ $date->format('d') }}
                    </th>
                @endforeach

                {{-- Summary --}}
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle" title="Hadir Full">H</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle" title="Bolos (Tidak Absen Pulang)">B</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">S</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">I</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">A</th>
            </tr>
            <tr class="bg-gray-50">
                {{-- M dan P untuk Setiap Hari --}}
                @foreach($dates as $date)
                    <th class="w-4 text-[8px] text-center {{ $date->isSunday() ? 'libur' : '' }}">M</th>
                    <th class="w-4 text-[8px] text-center border-day-end {{ $date->isSunday() ? 'libur' : '' }}">P</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td class="text-center border-day-end">{{ $index + 1 }}</td>
                    <td class="pl-2 font-medium truncate max-w-[150px] border-day-end">{{ $student->name }}</td>

                    {{-- Loop Data Absen --}}
                    @foreach($dates as $date)
                        @php 
                            $dateStr = $date->format('Y-m-d');
                            $data = $student->attendance_map[$dateStr] ?? ['in_code' => '', 'out_code' => ''];
                            $isHoliday = $date->isSunday();
                        @endphp
                        <td class="text-center {{ $isHoliday ? 'libur' : '' }}">
                            <span class="font-bold text-[9px]">{{ $data['in_code'] }}</span>
                        </td>
                        <td class="text-center border-day-end {{ $isHoliday ? 'libur' : '' }}">
                            <span class="font-bold text-[9px]">{{ $data['out_code'] }}</span>
                        </td>
                    @endforeach

                    {{-- Statistik --}}
                    <td class="text-center font-bold">{{ $student->summary['H'] > 0 ? $student->summary['H'] : '-' }}</td>
                    <td class="text-center font-bold">{{ $student->summary['B'] > 0 ? $student->summary['B'] : '-' }}</td>
                    <td class="text-center font-bold">{{ $student->summary['S'] > 0 ? $student->summary['S'] : '-' }}</td>
                    <td class="text-center font-bold">{{ $student->summary['I'] > 0 ? $student->summary['I'] : '-' }}</td>
                    <td class="text-center font-bold">{{ $student->summary['A'] > 0 ? $student->summary['A'] : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- LEGENDA --}}
    <div class="mb-8 text-xs font-medium border border-black p-2 inline-block">
        <strong>Keterangan:</strong><br>
        M: Masuk &nbsp;|&nbsp; P: Pulang <br>
        H: Hadir &nbsp;|&nbsp; B: Bolos (Tidak absen pulang) &nbsp;|&nbsp; S: Sakit &nbsp;|&nbsp; I: Izin &nbsp;|&nbsp; A: Alfa
    </div>

    {{-- TANDA TANGAN --}}
    <div class="flex justify-between text-sm px-8 break-inside-avoid">
        <div class="text-center">
            <p class="mb-16">Mengetahui,<br>Kepala Sekolah</p>
            <strong>TANTAN SUTANDI N., S.Si, M.Pd.</strong><br>
            NIP. 19820928 201101 1 002
        </div>
        <div class="text-center">
            <p class="mb-16">
                {{-- Lokasi Default (bisa diganti) --}}, {{ now()->translatedFormat('d F Y') }}<br>
                Wali Kelas
            </p>
            <p class="font-bold underline">{{ $selectedClass->homeroomTeacher->name ?? '_______________________' }}</p>
            <p>NIP. ...........................</p>
        </div>
    </div>

</body>
</html>