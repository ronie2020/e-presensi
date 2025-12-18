@props(['student', 'semester', 'year', 'subjects', 'record'])

<div class="relative w-full h-full font-serif text-slate-900">
    <div class="watermark absolute inset-0 pointer-events-none z-0"></div>
    <div class="relative z-10">
        {{-- Header & Identitas (Sama seperti sebelumnya) --}}
        <div class="text-center mb-6 border-b-2 border-slate-900 pb-4">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('images/logo.png') }}" class="h-20 w-auto" alt="Logo" onerror="this.style.display='none'">
            </div>
            <h1 class="text-2xl font-bold uppercase tracking-wide font-sans">Laporan Hasil Belajar</h1>
            <p class="text-sm font-bold text-slate-600 mt-1 uppercase tracking-widest font-sans">SMP Negeri 3 Lakbok</p>
        </div>

        <div class="grid grid-cols-2 gap-x-12 text-sm mb-6 font-sans">
            <table class="w-full">
                <tr><td class="py-1 w-32 font-bold text-slate-600 align-top">Nama Siswa</td><td class="py-1 uppercase font-bold text-slate-900 align-top">: {{ $student->name }}</td></tr>
                <tr><td class="py-1 font-bold text-slate-600 align-top">NISN</td><td class="py-1 font-mono align-top">: {{ $student->student_id }}</td></tr>
            </table>
            <table class="w-full">
                <tr><td class="py-1 w-32 font-bold text-slate-600 align-top">Kelas</td><td class="py-1 align-top">: {{ $student->schoolClass->name }}</td></tr>
                <tr><td class="py-1 font-bold text-slate-600 align-top">Semester</td><td class="py-1 align-top">: {{ $semester }} / {{ $year }}</td></tr>
            </table>
        </div>

        {{-- Tabel Nilai (Sama seperti sebelumnya) --}}
        <div class="mb-6">
            <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">A. Nilai Akademik</h3>
            <table class="w-full border-collapse border border-slate-900 text-sm">
                <thead>
                    <tr class="bg-slate-200 print:bg-slate-200">
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
                            <td class="border border-slate-600 px-2 py-2 text-center align-top">{{ $index + 1 }}</td>
                            <td class="border border-slate-600 px-3 py-2 font-medium align-top">{{ $subject->name }}</td>
                            <td class="border border-slate-600 px-2 py-2 text-center font-bold text-slate-900 align-top">{{ $grade ? $grade->score : '-' }}</td>
                            <td class="border border-slate-600 px-2 py-2 text-center font-bold align-top">{{ $grade ? $grade->predicate : '-' }}</td>
                            <td class="border border-slate-600 px-3 py-2 text-xs leading-relaxed text-justify align-top">{{ $grade && $grade->description ? Str::limit($grade->description, 150) : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Ekstrakurikuler & Absensi (Sama) --}}
        <div class="grid grid-cols-2 gap-6 mb-6"> 
            <div>
                <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">B. Ekstrakurikuler</h3>
                <table class="w-full border-collapse border border-slate-900 text-sm">
                    <thead>
                        <tr class="bg-slate-200 print:bg-slate-200">
                            <th class="border border-slate-600 px-2 py-1 text-left">Kegiatan</th>
                            <th class="border border-slate-600 px-2 py-1 w-14 text-center">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($record->extracurriculars ?? [] as $ex)
                            <tr>
                                <td class="border border-slate-600 px-2 py-1 align-top">{{ $ex->activity_name }}</td>
                                <td class="border border-slate-600 px-2 py-1 text-center font-bold align-top">{{ $ex->score }}</td>
                            </tr>
                        @empty
                            <tr><td class="border border-slate-600 px-2 py-1 text-center" colspan="2">-</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">C. Ketidakhadiran</h3>
                <table class="w-full border-collapse border border-slate-900 text-sm">
                    <tr><td class="border border-slate-600 px-3 py-1">Sakit</td><td class="border border-slate-600 px-3 py-1 text-center font-bold w-16">{{ $record->absent_s ?? '-' }}</td></tr>
                    <tr><td class="border border-slate-600 px-3 py-1">Izin</td><td class="border border-slate-600 px-3 py-1 text-center font-bold">{{ $record->absent_i ?? '-' }}</td></tr>
                    <tr><td class="border border-slate-600 px-3 py-1">Tanpa Ket.</td><td class="border border-slate-600 px-3 py-1 text-center font-bold">{{ $record->absent_a ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Catatan Wali Kelas --}}
        <div class="mb-6">
            <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">D. Catatan Wali Kelas</h3>
            <div class="border border-slate-900 p-3 min-h-[60px]">
                <p class="text-sm italic text-slate-800">{{ $record->notes ?? 'Tetap semangat dalam belajar.' }}</p>
            </div>
        </div>

        @if($semester == '2' || $semester == 'Genap')
        <div class="mb-8 border-2 border-double border-slate-900 p-4 text-center bg-slate-50 print:bg-white">
            <p class="font-bold text-sm text-slate-600 mb-1 font-sans">Keputusan Rapat Dewan Guru:</p>
            <p class="font-black text-lg uppercase tracking-wider font-sans">Naik ke Kelas {{ intval(preg_replace('/[^0-9]/', '', $student->schoolClass->name)) + 1 }}</p>
        </div>
        @endif

        {{-- Tanda Tangan dengan QR CODE --}}
        <div class="flex justify-between items-end text-sm mt-10 px-4 font-sans avoid-break">
            <div class="text-center w-1/3 mb-2">
                <p class="mb-20 text-slate-600">Mengetahui,<br>Orang Tua/Wali</p>
                <div class="border-b border-slate-900 w-32 mx-auto"></div>
            </div>
            
            <div class="text-center w-1/3 mb-2">
                <p class="mb-2 text-slate-600">Lakbok, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="mb-20 text-slate-600">Wali Kelas</p>
                <p class="font-bold underline uppercase mb-1">Nama Wali Kelas</p>
                <p class="text-xs">NIP. ...........................</p>
            </div>
        </div>
        
        <div class="flex items-end justify-center mt-8 gap-6 font-sans avoid-break">
            {{-- QR Code (Dummy dari API) --}}
            <div class="mb-2">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=Validasi:{{ $student->student_id }}-{{ $semester }}-{{ $year }}" alt="QR Validasi" class="w-20 h-20 opacity-80">
                <p class="text-[9px] text-slate-400 mt-1">Scan untuk validasi</p>
            </div>

            <div class="text-center">
                <p class="mb-20 text-slate-600">Mengetahui,<br>Kepala Sekolah</p>
                <p class="font-bold underline uppercase mb-1">Nama Kepala Sekolah</p>
                <p class="text-xs">NIP. ...........................</p>
            </div>
        </div>
    </div>
</div>