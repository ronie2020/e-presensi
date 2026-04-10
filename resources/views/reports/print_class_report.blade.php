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

        .border-day-end {
            border-right: 2px solid #000 !important;
        }

        .libur {
            background-color: #e5e7eb !important;
        }
    </style>
</head>
<body class="bg-white text-black p-4">

    {{-- KOP SURAT PROFESIONAL (DENGAN 2 LOGO) --}}
    <div class="border-b-[3px] border-black pb-2 mb-4 flex items-center justify-between">
        {{-- Logo 1: Kabupaten Ciamis --}}
        <div class="w-20">
            <img src="{{ asset('img/logo_ciamis.png') }}" class="w-16 h-20 object-contain" alt="Logo Ciamis">
        </div>

        {{-- Teks Tengah --}}
        <div class="flex-1 text-center px-4">
            <h2 class="text-sm font-bold uppercase">Pemerintah Kabupaten Ciamis</h2>
            <h2 class="text-sm font-bold uppercase">Dinas Pendidikan</h2>
            <h1 class="text-xl font-extrabold uppercase tracking-widest">SMP NEGERI 3 LAKBOK</h1>
            <p class="text-[10px] italic">Alamat: Jl. Raya Lakbok No. ... Kec. Lakbok, Kab. Ciamis, Jawa Barat</p>
            <p class="text-xs font-bold mt-1 uppercase border-t border-black pt-1 inline-block">Rekapitulasi Absensi Bulanan Siswa (M/P)</p>
        </div>

        {{-- Logo 2: Logo Sekolah --}}
        <div class="w-20 flex justify-end">
            <img src="{{ asset('img/logo_sekolah.png') }}" class="w-16 h-20 object-contain" alt="Logo Sekolah">
        </div>
    </div>

    {{-- INFORMASI KELAS & PERIODE --}}
    <div class="flex justify-between items-end mb-4 text-xs">
        <div>
            <table>
                <tr><td class="font-bold w-20">Kelas</td><td>: {{ $selectedClass->name ?? '-' }}</td></tr>
                <tr><td class="font-bold">Wali Kelas</td><td>: {{ $selectedClass->homeroomTeacher->name ?? '-' }}</td></tr>
            </table>
        </div>
        <div class="text-right">
            <table>
                <tr><td class="font-bold w-20">Periode</td><td>: {{ $startDate->translatedFormat('F Y') }}</td></tr>
                <tr><td class="font-bold text-rose-600">Libur</td><td class="text-rose-600 font-medium">: Sabtu Minggu / Hari Libur Nasional</td></tr>
            </table>
        </div>
    </div>

    {{-- TABEL ABSENSI --}}
    <table class="w-full table-compact border-collapse mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th rowspan="2" class="w-8 text-center align-middle border-day-end">No</th>
                <th rowspan="2" class="text-left pl-2 align-middle border-day-end">Nama Siswa</th>
                
               @foreach($dates as $date)
                    <th colspan="2" class="text-center border-day-end {{ ($date->isSaturday() || $date->isSunday()) ? 'libur' : '' }}">
                        {{ $date->format('d') }}
                    </th>
                @endforeach

                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">H</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">B</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">S</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">I</th>
                <th rowspan="2" class="w-8 text-center bg-gray-50 align-middle">A</th>
            </tr>
            <tr class="bg-gray-50">
                @foreach($dates as $date)
                    <th class="w-4 text-[7px] text-center {{ ($date->isSaturday() || $date->isSunday()) ? 'libur' : '' }}">M</th>
                    <th class="w-4 text-[7px] text-center border-day-end {{ ($date->isSaturday() || $date->isSunday()) ? 'libur' : '' }}">P</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td class="text-center border-day-end">{{ $index + 1 }}</td>
                    <td class="pl-2 font-medium truncate max-w-[150px] border-day-end">{{ $student->name }}</td>

                     @foreach($dates as $date)
                        @php 
                            $dateStr = $date->format('Y-m-d');
                            $data = $student->attendance_map[$dateStr] ?? ['in_code' => '', 'out_code' => ''];
                            $isHoliday = $date->isSaturday() || $date->isSunday();
                        @endphp
                        <td class="text-center {{ $isHoliday ? 'libur' : '' }}">
                            <span class="font-bold text-[9px]">{{ $data['in_code'] }}</span>
                        </td>
                        <td class="text-center border-day-end {{ $isHoliday ? 'libur' : '' }}">
                            <span class="font-bold text-[9px]">{{ $data['out_code'] }}</span>
                        </td>
                    @endforeach

                    <td class="text-center font-bold">{{ $student->summary['H'] > 0 ? $student->summary['H'] : '-' }}</td>
                    <td class="text-center font-bold text-rose-600">{{ $student->summary['B'] > 0 ? $student->summary['B'] : '-' }}</td>
                    <td class="text-center font-bold text-blue-600">{{ $student->summary['S'] > 0 ? $student->summary['S'] : '-' }}</td>
                    <td class="text-center font-bold text-indigo-600">{{ $student->summary['I'] > 0 ? $student->summary['I'] : '-' }}</td>
                    <td class="text-center font-bold text-red-700">{{ $student->summary['A'] > 0 ? $student->summary['A'] : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- KETERANGAN & TANDA TANGAN --}}
    <div class="flex justify-between items-start text-xs mt-4">
        <div class="border border-black p-2 w-fit">
            <span class="font-bold underline mb-1 block">Keterangan Kolom:</span>
            <div class="grid grid-cols-2 gap-x-4">
                <span>M : Absen Masuk</span>
                <span>P : Absen Pulang</span>
                <span>H : Hadir (Lengkap)</span>
                <span>B : Bolos (Tidak Pulang)</span>
                <span>S : Sakit</span>
                <span>I : Izin</span>
                <span>A : Alfa</span>
            </div>
        </div>

        <div class="flex gap-16 pr-8">
            <div class="text-center">
                <p class="mb-20">Mengetahui,<br>Kepala Sekolah</p>
                <p class="font-bold underline uppercase">TANTAN SUTANDI N., S.Si, M.Pd.</p>
                <p>NIP. 19820928 201101 1 002</p>
            </div>
            <div class="text-center">
                <p class="mb-20">
                    Lakbok, {{ now()->translatedFormat('d F Y') }}<br>
                    Wali Kelas
                </p>
                <p class="font-bold underline uppercase">{{ $selectedClass->homeroomTeacher->name ?? '_______________________' }}</p>
                <p>NIP. ...........................</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 800);
        };
    </script>
</body>
</html>