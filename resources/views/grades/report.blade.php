<x-app-layout>
    {{-- CSS Khusus Cetak --}}
    @push('styles')
    <style>
        /* Tampilan Layar Normal */
        .watermark {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png'); /* Ganti dengan logo sekolah */
            background-repeat: no-repeat;
            background-position: center;
            background-size: 300px;
            opacity: 0.05;
        }

        /* --- LOGIKA PRINT (DIPERBAIKI) --- */
        @media print {
            /* 1. Reset seluruh halaman */
            body {
                visibility: hidden; /* Sembunyikan visual body, TAPI jangan display:none agar struktur tetap ada */
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* 2. Sembunyikan elemen UI spesifik agar tidak memakan tempat */
            nav, header, aside, .no-print, footer {
                display: none !important; 
            }

            /* 3. Tampilkan HANYA container rapor */
            .print-container {
                visibility: visible !important; /* Paksa terlihat */
                position: absolute !important;   /* Lepaskan dari aliran dokumen normal */
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                z-index: 99999 !important;
            }

            /* Pastikan semua isi di dalam rapor juga terlihat */
            .print-container * {
                visibility: visible !important;
            }

            /* Aturan Halaman Kertas */
            @page {
                size: A4;
                margin: 10mm; /* Margin dari tepi kertas fisik */
            }
        }
    </style>
    @endpush

    <div class="py-8 bg-slate-100 min-h-screen">
        <div class="max-w-[210mm] mx-auto">
            
            {{-- Toolbar Atas (Tombol Kembali & Print) --}}
            <div class="flex justify-between items-center mb-6 px-4 no-print">
                <a href="{{ route('grades.list', ['class_id' => $student->class_id, 'academic_year' => $year, 'semester' => $semester]) }}" 
                   class="flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold transition">
                    <i class="ph-bold ph-arrow-left"></i> Kembali
                </a>
                <button onclick="window.print()" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="ph-bold ph-printer"></i> Cetak Rapor
                </button>
            </div>

            {{-- LEMBAR RAPOR --}}
            <div class="print-container bg-white p-10 md:p-12 shadow-xl relative overflow-hidden text-slate-900 font-serif min-h-[297mm]">
                
                {{-- Watermark --}}
                <div class="watermark absolute inset-0 pointer-events-none"></div>

                <div class="relative z-10">
                    {{-- Header Rapor --}}
                    <div class="text-center mb-6 border-b-2 border-slate-800 pb-4">
                        <div class="flex items-center justify-center gap-4 mb-2">
                            <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto opacity-80" alt="Logo">
                        </div>
                        <h1 class="text-2xl font-bold uppercase tracking-wide">Laporan Hasil Belajar</h1>
                        <p class="text-sm font-bold text-slate-600 mt-1 uppercase tracking-widest">SMP Negeri 3 Lakbok</p>
                    </div>

                    {{-- Identitas Siswa --}}
                    <div class="grid grid-cols-2 gap-x-12 text-sm mb-6 font-sans">
                        <table class="w-full">
                            <tr>
                                <td class="py-1 w-32 font-bold text-slate-600">Nama Siswa</td>
                                <td class="py-1 uppercase font-bold text-slate-900">: {{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-slate-600">NISN</td>
                                <td class="py-1 font-mono">: {{ $student->student_id }}</td>
                            </tr>
                        </table>
                        <table class="w-full">
                            <tr>
                                <td class="py-1 w-32 font-bold text-slate-600">Kelas</td>
                                <td class="py-1">: {{ $student->schoolClass->name }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-slate-600">Semester</td>
                                <td class="py-1">: {{ $semester }} ({{ $semester == '1' ? 'Ganjil' : 'Genap' }}) / {{ $year }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- A. Tabel Nilai Akademik --}}
                    <div class="mb-6">
                        <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1">A. Nilai Akademik</h3>
                        <table class="w-full border-collapse border border-slate-800 text-sm">
                            <thead>
                                <tr class="bg-slate-100">
                                    <th class="border border-slate-600 px-2 py-2 w-8 text-center">No</th>
                                    <th class="border border-slate-600 px-3 py-2 text-left">Mata Pelajaran</th>
                                    <th class="border border-slate-600 px-2 py-2 w-16 text-center">Nilai</th>
                                    <th class="border border-slate-600 px-2 py-2 w-16 text-center">Predikat</th>
                                    <th class="border border-slate-600 px-3 py-2 text-left">Capaian Kompetensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $index => $subject)
                                    @php $grade = $subject->grade; @endphp
                                    <tr>
                                        <td class="border border-slate-600 px-2 py-2 text-center">{{ $index + 1 }}</td>
                                        <td class="border border-slate-600 px-3 py-2 font-medium">{{ $subject->name }}</td>
                                        <td class="border border-slate-600 px-2 py-2 text-center font-bold text-slate-800">
                                            {{ $grade ? $grade->score : '-' }}
                                        </td>
                                        <td class="border border-slate-600 px-2 py-2 text-center font-bold">
                                            {{ $grade ? $grade->predicate : '-' }}
                                        </td>
                                        <td class="border border-slate-600 px-3 py-2 text-xs leading-relaxed text-justify">
                                            {{ $grade && $grade->description ? $grade->description : 'Belum ada deskripsi.' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Grid B (Ekskul) & C (Absensi) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        {{-- B. Ekstrakurikuler --}}
                        <div>
                            <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1">B. Ekstrakurikuler</h3>
                            <table class="w-full border-collapse border border-slate-800 text-sm">
                                <thead>
                                    <tr class="bg-slate-100">
                                        <th class="border border-slate-600 px-2 py-1 text-left">Kegiatan</th>
                                        <th class="border border-slate-600 px-2 py-1 w-20 text-center">Nilai</th>
                                        <th class="border border-slate-600 px-2 py-1 text-left">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($record->extracurriculars ?? [] as $ex)
                                        <tr>
                                            <td class="border border-slate-600 px-2 py-1">{{ $ex->activity_name }}</td>
                                            <td class="border border-slate-600 px-2 py-1 text-center font-bold">{{ $ex->score }}</td>
                                            <td class="border border-slate-600 px-2 py-1 text-xs">{{ $ex->description }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="border border-slate-600 px-2 py-4 text-center italic text-xs text-slate-500">Tidak ada data ekstrakurikuler.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- C. Ketidakhadiran --}}
                        <div>
                            <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1">C. Ketidakhadiran</h3>
                            <table class="w-full border-collapse border border-slate-800 text-sm">
                                <tr>
                                    <td class="border border-slate-600 px-3 py-1 w-32">Sakit</td>
                                    <td class="border border-slate-600 px-3 py-1 text-center font-bold">{{ $record->absent_s ?? 0 }}</td>
                                    <td class="border border-slate-600 px-2 py-1 w-12 text-center text-xs">Hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600 px-3 py-1">Izin</td>
                                    <td class="border border-slate-600 px-3 py-1 text-center font-bold">{{ $record->absent_i ?? 0 }}</td>
                                    <td class="border border-slate-600 px-2 py-1 text-center text-xs">Hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600 px-3 py-1">Tanpa Keterangan</td>
                                    <td class="border border-slate-600 px-3 py-1 text-center font-bold">{{ $record->absent_a ?? 0 }}</td>
                                    <td class="border border-slate-600 px-2 py-1 text-center text-xs">Hari</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- D. Catatan Wali Kelas --}}
                    <div class="mb-6">
                        <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1">D. Catatan Wali Kelas</h3>
                        <div class="border border-slate-800 p-3 rounded-sm min-h-[60px]">
                            <p class="text-sm italic text-slate-800 font-serif">
                                "{{ $record->notes ?? 'Tingkatkan terus prestasi belajarmu. Pertahankan semangat belajar yang tinggi.' }}"
                            </p>
                        </div>
                    </div>

                    {{-- E. Keputusan (Khusus Semester Genap) --}}
                    @if($semester == '2' || $semester == 'Genap')
                    <div class="mb-8 border-2 border-double border-slate-800 p-4 text-center bg-slate-50">
                        <p class="font-bold text-sm text-slate-600 mb-1">Keputusan:</p>
                        <p class="font-black text-lg uppercase tracking-wider">
                            Naik ke Kelas {{ intval(preg_replace('/[^0-9]/', '', $student->schoolClass->name)) + 1 }}
                        </p>
                    </div>
                    @endif

                    {{-- Tanda Tangan --}}
                    <div class="flex justify-between items-start text-sm mt-12 px-8">
                        <div class="text-center w-1/3">
                            <p class="mb-20 text-slate-600">Mengetahui,<br>Orang Tua/Wali</p>
                            <div class="border-b border-slate-800 w-40 mx-auto"></div>
                        </div>
                        
                        <div class="text-center w-1/3">
                            <p class="mb-2 text-slate-600">
                                Lakbok, {{ $record && $record->report_date ? \Carbon\Carbon::parse($record->report_date)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                            </p>
                            <p class="mb-20 text-slate-600">Wali Kelas</p>
                            <p class="font-bold underline uppercase mb-1">Nama Wali Kelas</p>
                            <p class="text-xs">NIP. ...........................</p>
                        </div>
                    </div>

                    <div class="text-center mt-12">
                        <p class="mb-20 text-slate-600">Mengetahui,<br>Kepala Sekolah</p>
                        <p class="font-bold underline uppercase mb-1">Nama Kepala Sekolah</p>
                        <p class="text-xs">NIP. ...........................</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>