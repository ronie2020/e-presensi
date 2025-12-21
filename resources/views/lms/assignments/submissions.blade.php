<x-app-layout>
    {{-- Header Judul (Hidden, karena sudah ada di Hero) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Penilaian Tugas') }}
        </h2>
    </x-slot>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                {{-- Dekorasi Latar --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        {{-- Badges --}}
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-white/10 border border-white/10 text-blue-100 backdrop-blur-sm">
                                <i class="ph-bold ph-bookmarks-simple mr-1.5"></i>
                                {{ str_replace('_', ' ', $assignment->assignment_type) }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-blue-500/30 border border-blue-400/30 text-white backdrop-blur-sm">
                                <i class="ph-bold ph-users-three mr-1.5"></i>
                                {{ $assignment->schoolClass->name }}
                            </span>
                        </div>

                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-white leading-tight">
                            {{ $assignment->title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-blue-200 text-sm font-medium">
                            <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg border border-white/5">
                                <i class="ph-bold ph-book-open text-blue-400"></i> {{ $assignment->subject->name }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20 text-rose-200">
                                <i class="ph-bold ph-clock text-rose-400"></i> Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('lms.assignments.index') }}" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-lg">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-bold text-sm flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-lg hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- RINGKASAN DATA (STATS CARDS) --}}
            @php
                $totalStudents = $allStudents->count();
                $submittedCount = $submissions->count();
                $pendingCount = $totalStudents - $submittedCount;
                
                // Hitung persentase
                $progressPercent = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Card 1: Total Siswa --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Siswa</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ $totalStudents }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-student"></i>
                    </div>
                </div>

                {{-- Card 2: Sudah Mengumpulkan --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sudah Kumpul</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-black text-emerald-600">{{ $submittedCount }}</h3>
                            <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full">{{ $progressPercent }}%</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-check-fat"></i>
                    </div>
                </div>

                {{-- Card 3: Belum Mengumpulkan --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Belum Kumpul</p>
                        <h3 class="text-3xl font-black text-rose-500">{{ $pendingCount }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-clock-countdown"></i>
                    </div>
                </div>
            </div>

            {{-- TABEL SISWA --}}
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                
                {{-- Header Tabel --}}
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-checks text-blue-600"></i> Daftar Pengumpulan
                    </h3>
                    
                    {{-- Simple Search Filter (Placeholder Layout) --}}
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Cari siswa..." class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-blue-500 focus:border-blue-500 w-64 shadow-sm">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-2.5 text-slate-400"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/6 text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Lampiran / Jawaban</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/6">Waktu Kirim</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/6 text-center">Nilai (0-100)</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Feedback Guru</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($allStudents as $student)
                                @php
                                    $submission = $submissions->where('student_id', $student->id)->first();
                                    
                                    // Cek status terlambat
                                    $isLate = false;
                                    if($submission && $submission->submitted_at > $assignment->deadline) {
                                        $isLate = true;
                                    }
                                @endphp

                                <tr class="group hover:bg-slate-50/80 transition-colors">
                                    <!-- Kolom Siswa -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-slate-100 text-blue-600 flex items-center justify-center font-bold text-xs border border-white shadow-sm shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors">{{ $student->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $student->nisn ?? 'NISN -' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kolom Status -->
                                    <td class="px-6 py-4 text-center">
                                        @if($submission)
                                            @if($isLate)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-amber-50 text-amber-600 border border-amber-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Terlambat
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Masuk
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-rose-50 text-rose-500 border border-rose-100">
                                                Belum
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Kolom Jawaban -->
                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <div class="flex flex-col gap-2">
                                                @if($submission->file_path)
                                                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition-colors w-fit border border-blue-100">
                                                        <i class="ph-bold ph-file-text text-lg"></i>
                                                        Lihat File
                                                    </a>
                                                @endif

                                                @if($submission->student_note)
                                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs text-slate-600 italic relative">
                                                        <i class="ph-fill ph-quotes text-slate-300 text-xl absolute -top-2 -left-1"></i>
                                                        <span class="relative z-10">"{{ Str::limit($submission->student_note, 60) }}"</span>
                                                    </div>
                                                @endif

                                                @if(!$submission->file_path && !$submission->student_note)
                                                    <span class="text-xs text-slate-400 italic">Dikumpul tanpa lampiran.</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-2xl ml-2"><i class="ph-duotone ph-minus-circle"></i></span>
                                        @endif
                                    </td>

                                    <!-- Kolom Waktu -->
                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-slate-700">{{ $submission->submitted_at->format('d M') }}</span>
                                                <span class="text-[10px] font-mono text-slate-400">{{ $submission->submitted_at->format('H:i') }} WIB</span>
                                            </div>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>

                                    <!-- FORM PENILAIAN (WRAPPER) -->
                                    @if($submission)
                                        <form action="{{ route('lms.submissions.grade', $submission->id) }}" method="POST" class="contents">
                                            @csrf
                                            
                                            <!-- Input Nilai -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="grade" min="0" max="100" 
                                                       value="{{ $submission->grade }}" 
                                                       class="w-20 text-center rounded-xl border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500 text-sm font-black text-slate-800 h-10 shadow-sm"
                                                       placeholder="-">
                                            </td>

                                            <!-- Input Feedback -->
                                            <td class="px-6 py-4">
                                                <input type="text" name="feedback" 
                                                       value="{{ $submission->teacher_feedback }}" 
                                                       class="w-full min-w-[180px] rounded-xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 text-xs h-10 px-3 placeholder:text-slate-300 transition-shadow focus:shadow-md"
                                                       placeholder="Tulis masukan untuk siswa...">
                                            </td>

                                            <!-- Tombol Simpan -->
                                            <td class="px-6 py-4 text-right">
                                                <button type="submit" class="w-9 h-9 rounded-xl bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center shadow-md" title="Simpan Nilai">
                                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                                </button>
                                            </td>
                                        </form>
                                    @else
                                        <!-- Placeholder jika belum mengumpulkan -->
                                        <td colspan="3" class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1 text-xs text-slate-400 font-medium bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 border-dashed">
                                                <i class="ph-bold ph-hourglass"></i> Menunggu pengumpulan
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Empty State --}}
                @if($allStudents->count() == 0)
                    <div class="text-center py-16 bg-slate-50/50">
                        <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl">
                            <i class="ph-duotone ph-users-three"></i>
                        </div>
                        <p class="text-slate-500 font-bold text-sm">Tidak ada siswa yang terdaftar di kelas ini.</p>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>