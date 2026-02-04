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
                <p class="text-sm font-medium">Laporan Kehadiran Bulanan</p>
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
                <th class="w-8 text-center">No</th>
                <th class="text-left pl-2">Nama Siswa</th>
                
                {{-- Loop Tanggal --}}
                @foreach($dates as $date)
                    <th class="w-6 text-center {{ $date->isSunday() ? 'libur' : '' }}">
                        {{ $date->format('d') }}
                    </th>
                @endforeach

                {{-- Summary --}}
                <th class="w-8 text-center bg-gray-50">S</th>
                <th class="w-8 text-center bg-gray-50">I</th>
                <th class="w-8 text-center bg-gray-50">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="pl-2 font-medium truncate max-w-[150px]">{{ $student->name }}</td>

                    {{-- Loop Data Absen --}}
                    @foreach($dates as $date)
                        @php 
                            $dateStr = $date->format('Y-m-d');
                            $data = $student->attendance_map[$dateStr] ?? ['code' => '', 'class' => ''];
                            $isHoliday = $date->isSunday();
                        @endphp
                        <td class="text-center {{ $isHoliday ? 'libur' : '' }}">
                            <span class="font-bold text-[9px]">{{ $data['code'] }}</span>
                        </td>
                    @endforeach

                    {{-- Statistik --}}
                    <td class="text-center font-bold">{{ $student->summary['S'] > 0 ? $student->summary['S'] : '-' }}</td>
                    <td class="text-center font-bold">{{ $student->summary['I'] > 0 ? $student->summary['I'] : '-' }}</td>
                    <td class="text-center font-bold">{{ $student->summary['A'] > 0 ? $student->summary['A'] : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="flex justify-between text-sm mt-8 px-8 break-inside-avoid">
        <div class="text-center">
            <p class="mb-16">Mengetahui,<br>Kepala Sekolah</p>
            <p class="font-bold underline">_______________________</p>
            <p>NIP. ...........................</p>
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