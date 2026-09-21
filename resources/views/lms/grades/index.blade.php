<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rekap Nilai Siswa') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .custom-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    </style>

    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-sky-600/10 via-blue-600/5 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Buku Nilai Digital"
                badgeIcon="ph-chart-bar"
                title="Rekap Nilai"
                titleHighlight="LMS & Tugas"
                description="Pantau perkembangan nilai tugas, kuis, dan ulangan harian siswa berdasarkan tingkat atau kelas secara terpadu."
                :chips="[
                    ['icon' => 'ph-chart-line-up', 'label' => 'Analisis Ketuntasan'],
                    ['icon' => 'ph-chalkboard-teacher', 'label' => 'Per Tingkat & Kelas'],
                    ['icon' => 'ph-file-xls', 'label' => 'Export Excel & Cetak PDF']
                ]"
                heroIcon="ph-chart-bar"
                :showcaseNumber="isset($students) && count($students) > 0 ? count($students) : (isset($assignments) && count($assignments) > 0 ? count($assignments) : 0)"
                showcaseLabel="{{ isset($students) && count($students) > 0 ? 'Siswa Terdata' : 'Tugas Terdaftar' }}"
                showcaseSubtitle="Buku Nilai LMS"
                statusOrb="Nilai Terkini"
                statusColor="emerald"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        @if((($selectedLevelId ?? false) || ($selectedClassId ?? false)) && ($selectedSubjectId ?? false) && isset($assignments) && $assignments->isNotEmpty())
                            <a href="{{ route('lms.grades.export', ['level_id' => $selectedLevelId ?? '', 'class_id' => $selectedClassId ?? '', 'subject_id' => $selectedSubjectId]) }}" class="btn-export px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-500/30 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                                <i class="ph-bold ph-microsoft-excel-logo text-base"></i>
                                <span>Export Excel</span>
                            </a>
                            <a href="{{ route('lms.grades.print', ['level_id' => $selectedLevelId ?? '', 'class_id' => $selectedClassId ?? '', 'subject_id' => $selectedSubjectId]) }}" target="_blank" class="btn-print px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm border border-white/20 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-2 active:scale-95">
                                <i class="ph-bold ph-printer text-base"></i>
                                <span>Cetak PDF</span>
                            </a>
                        @endif
                        <a href="{{ route('lms.assignments.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] hover:from-sky-300 hover:to-sky-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-pencil-simple text-base"></i> Kelola Tugas
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-arrow-left text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- CARD FILTER DENGAN ALPINE.JS --}}
            <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 md:p-8 rounded-[2rem] border border-white/10 shadow-2xl mb-8 relative overflow-hidden backdrop-blur-xl" style="animation-delay: 100ms">
                
                {{-- Form dibungkus dengan Alpine component 'gradeFilter' --}}
                <form action="{{ route('lms.grades.index') }}" method="GET" class="relative z-10" id="filterForm" x-data="gradeFilter()">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-5 items-end">
                        
                        {{-- Dropdown Tingkat --}}
                        <div class="w-full">
                            <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Pilih Tingkat</label>
                            <div class="relative group">
                                <select name="level_id" id="level_id" x-model="selectedLevel" @change="handleLevelChange" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 appearance-none cursor-pointer transition-colors shadow-sm [color-scheme:dark]">
                                    <option value="" class="bg-slate-900 text-white">-- Semua Tingkat --</option>
                                    <option value="7" class="bg-slate-900 text-white">Kelas 7 (VII)</option>
                                    <option value="8" class="bg-slate-900 text-white">Kelas 8 (VIII)</option>
                                    <option value="9" class="bg-slate-900 text-white">Kelas 9 (IX)</option>
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                            </div>
                        </div>

                        {{-- Dropdown Kelas (Diisi Otomatis oleh Alpine JS) --}}
                        <div class="w-full">
                            <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Pilih Kelas</label>
                            <div class="relative group">
                                <select name="class_id" id="class_id" x-model="selectedClass" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 appearance-none cursor-pointer transition-colors shadow-sm [color-scheme:dark]">
                                    <option value="" class="bg-slate-900 text-white">-- Semua Kelas --</option>
                                    <template x-for="c in filteredClasses()" :key="c.id">
                                        <option :value="c.id" x-text="c.name" class="bg-slate-900 text-white"></option>
                                    </template>
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                            </div>
                        </div>

                        {{-- Dropdown Mapel --}}
                        <div class="w-full">
                            <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Pilih Mapel <span class="text-rose-400">*</span></label>
                            <div class="relative group">
                                <select name="subject_id" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 appearance-none cursor-pointer transition-colors shadow-sm [color-scheme:dark]" required>
                                    <option value="" class="bg-slate-900 text-white">-- Pilih Mapel --</option>
                                    @foreach($subjects ?? [] as $s)
                                        <option value="{{ $s->id }}" {{ ($selectedSubjectId ?? '') == $s->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                            </div>
                        </div>

                        {{-- Dropdown Periode --}}
                        <div class="w-full">
                            <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Periode Waktu <span class="text-rose-400">*</span></label>
                            <div class="relative group">
                                <select name="period" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 appearance-none cursor-pointer transition-colors shadow-sm [color-scheme:dark]" required>
                                    <option value="semester" {{ ($selectedPeriod ?? 'semester') == 'semester' ? 'selected' : '' }} class="bg-slate-900 text-white">Rekap Semester (Rapor)</option>
                                    <option value="monthly" {{ ($selectedPeriod ?? '') == 'monthly' ? 'selected' : '' }} class="bg-slate-900 text-white">Bulanan (Bulan Ini)</option>
                                    <option value="weekly" {{ ($selectedPeriod ?? '') == 'weekly' ? 'selected' : '' }} class="bg-slate-900 text-white">Mingguan (Minggu Ini)</option>
                                    <option value="daily" {{ ($selectedPeriod ?? '') == 'daily' ? 'selected' : '' }} class="bg-slate-900 text-white">Harian (Hari Ini)</option>
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <button type="submit" class="w-full px-8 py-3 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-sky-500/25 flex items-center justify-center gap-2 h-14 border border-transparent active:scale-95">
                                <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABEL NILAI --}}
            @if((($selectedLevelId ?? false) || ($selectedClassId ?? false)) && ($selectedSubjectId ?? false))
                <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden backdrop-blur-xl" style="animation-delay: 200ms">
                    @if(($assignments ?? collect())->isEmpty())
                        {{-- Empty State Assignments --}}
                        <div class="p-16 text-center flex flex-col items-center">
                            <div class="w-24 h-24 bg-slate-900/80 border border-white/10 rounded-full flex items-center justify-center mb-6 animate-pulse shadow-inner">
                                <i class="ph-duotone ph-clipboard-text text-5xl text-sky-400"></i>
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2">Belum Ada Tugas</h3>
                            <p class="text-slate-400 text-sm max-w-md mx-auto leading-relaxed font-medium">
                                Belum ada tugas atau kuis yang dibuat untuk filter yang Anda pilih.
                            </p>
                            <a href="{{ route('lms.assignments.create') }}" class="mt-8 px-8 py-4 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-400 hover:to-blue-500 shadow-lg shadow-sky-500/25 transition-all flex items-center gap-2 active:scale-95">
                                <i class="ph-bold ph-plus text-lg"></i> Buat Tugas Baru
                            </a>
                        </div>
                    @else
                        {{-- Data Table --}}
                        <div class="overflow-x-auto custom-scrollbar pb-2 relative z-0">
                            <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                                <thead class="bg-slate-900/80 text-sky-400 uppercase font-black text-[10px] tracking-wider border-b border-white/10">
                                    <tr>
                                        <th class="px-5 py-5 sticky left-0 bg-[#021124] z-20 min-w-[4rem] max-w-[4rem] text-center border-r border-white/10">No</th>
                                        <th class="px-5 py-5 sticky left-[4rem] bg-[#021124] z-20 min-w-[240px] border-r border-white/10 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.5)]">Nama Siswa & Kelas</th>
                                        
                                        <!-- Loop Judul Tugas (Kolom) -->
                                        @foreach($assignments ?? [] as $task)
                                            <th class="px-5 py-5 text-center min-w-[140px] group relative border-r border-white/10 hover:bg-white/[0.02] transition-colors">
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <span class="block truncate w-32 cursor-help font-bold text-slate-200" title="{{ $task->title }}">
                                                        {{ Str::limit($task->title, 15) }}
                                                    </span>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-[9px] text-slate-300 font-bold bg-slate-900 border border-white/10 px-2 py-0.5 rounded shadow-sm">{{ $task->created_at->format('d/m') }}</span>
                                                        <span class="text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-widest shadow-sm {{ $task->assignment_type == 'quiz' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-sky-500/10 text-sky-400 border border-sky-500/20' }}">
                                                            {{ $task->assignment_type == 'quiz' ? 'Kuis' : 'Tugas' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                        @endforeach

                                        <th class="px-5 py-5 text-center bg-slate-900/80 text-sky-400 min-w-[100px] border-r border-white/10">Total</th>
                                        <th class="px-5 py-5 text-center {{ ($selectedPeriod ?? 'semester') == 'semester' ? 'bg-sky-500/20 text-sky-300 border-sky-500/30' : 'bg-slate-900 text-slate-200 border-white/10' }} min-w-[110px]">
                                            {{ ($selectedPeriod ?? 'semester') == 'semester' ? 'Nilai Rapor' : 'Rata-rata' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 bg-transparent">
                                    @foreach($students ?? [] as $index => $student)
                                        <tr class="hover:bg-white/[0.02] transition-colors group">
                                            {{-- Kolom No (Sticky) --}}
                                            <td class="px-5 py-4 border-r border-white/10 sticky left-0 min-w-[4rem] max-w-[4rem] bg-[#021124] text-center text-slate-400 font-bold z-10">
                                                {{ $index + 1 }}
                                            </td>
                                            
                                            {{-- Kolom Nama & Info Kelas (Sticky) --}}
                                            <td class="px-5 py-4 border-r border-white/10 sticky left-[4rem] bg-[#021124] z-10 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.5)]">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-white text-sm group-hover:text-sky-400 transition-colors">{{ $student->name }}</span>
                                                    <div class="flex items-center gap-1.5 mt-1">
                                                        <span class="text-[10px] font-bold text-slate-400 font-mono tracking-wider">{{ $student->nisn ?? $student->student_id ?? '-' }}</span>
                                                        <span class="text-slate-600">•</span>
                                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-900 text-slate-300 border border-white/10">{{ $student->schoolClass->name ?? 'Tanpa Kelas' }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Loop Nilai per Tugas -->
                                            @foreach($assignments ?? [] as $task)
                                                @php 
                                                    $score = $gradeBook[$student->id][$task->id] ?? null; 
                                                    $batasLulus = $kkm ?? 70;
                                                @endphp
                                                
                                                <td class="px-5 py-4 text-center border-r border-white/5">
                                                    @if($score !== null)
                                                        <span class="inline-flex w-12 h-9 items-center justify-center rounded-xl text-sm font-black border shadow-sm
                                                            {{ $score < $batasLulus ? 'bg-rose-500/10 text-rose-400 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' }}">
                                                            {{ $score }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-600 text-lg font-bold" title="Belum dinilai">-</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            {{-- Kolom Total --}}
                                            <td class="px-5 py-4 text-center border-r border-white/10 bg-slate-900/60 font-black text-sky-400 text-base">
                                                {{ $student->total_score ?? 0 }}
                                            </td>
                                            
                                            {{-- Kolom Rata-rata / Nilai Rapor --}}
                                            @php
                                                $average = $student->average_score ?? 0;
                                                $isSemester = ($selectedPeriod ?? 'semester') == 'semester';
                                                $avgColor = $average > 0 && $average < $batasLulus ? 'text-rose-400 bg-rose-500/10' : 'text-emerald-400 bg-emerald-500/10';
                                                $semesterColor = $average > 0 && $average < $batasLulus ? 'text-white bg-rose-600' : 'text-white bg-sky-600';
                                            @endphp
                                            <td class="px-5 py-4 text-center text-base font-black {{ $isSemester ? $semesterColor : $avgColor }}">
                                                {{ $average }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @else
                {{-- Empty State Filter --}}
                <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden relative backdrop-blur-xl" style="animation-delay: 200ms">
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(rgba(255,255,255,0.05)_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                    
                    <div class="p-12 md:p-20 text-center relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 bg-sky-500/10 text-sky-400 rounded-[2rem] flex items-center justify-center mb-6 border border-sky-500/20 shadow-sm rotate-3 hover:rotate-6 transition-transform duration-300">
                            <i class="ph-duotone ph-list-magnifying-glass text-5xl"></i>
                        </div>
                        
                        <h3 class="text-3xl font-black text-white mb-4">Mulai Pantau Nilai Siswa</h3>
                        <p class="text-slate-400 max-w-lg mx-auto mb-12 leading-relaxed text-sm font-semibold">
                            Silakan tentukan <b>Tingkat/Kelas</b> dan <b>Mata Pelajaran</b> pada panel filter di atas untuk melihat matriks rekapitulasi nilai secara lengkap.
                        </p>

                        <!-- Quick Guide Steps -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto w-full text-left">
                            <!-- Step 1 -->
                            <div class="bg-slate-900/60 rounded-[1.5rem] p-6 border border-white/10 shadow-sm relative overflow-hidden group hover:border-sky-400/50 transition-all">
                                <div class="w-12 h-12 bg-sky-500/10 rounded-xl flex items-center justify-center text-sky-400 font-black mb-5 border border-sky-500/20 group-hover:bg-sky-500 group-hover:text-white transition-colors z-10 relative text-lg shadow-sm">1</div>
                                <h4 class="font-black text-white mb-2 relative z-10 text-lg">Pilih Filter</h4>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed relative z-10">Pilih tingkat/kelas dan mata pelajaran yang Anda ampu dari menu dropdown di atas.</p>
                            </div>
                            <!-- Step 2 -->
                            <div class="bg-slate-900/60 rounded-[1.5rem] p-6 border border-white/10 shadow-sm relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                                <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 font-black mb-5 border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-colors z-10 relative text-lg shadow-sm">2</div>
                                <h4 class="font-black text-white mb-2 relative z-10 text-lg">Tinjau Matriks</h4>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed relative z-10">Pantau nilai tugas, kuis, beserta kalkulasi total dan rata-rata secara otomatis.</p>
                            </div>
                            <!-- Step 3 -->
                            <div class="bg-slate-900/60 rounded-[1.5rem] p-6 border border-white/10 shadow-sm relative overflow-hidden group hover:border-amber-500/50 transition-all">
                                <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 font-black mb-5 border border-amber-500/20 group-hover:bg-amber-500 group-hover:text-white transition-colors z-10 relative text-lg shadow-sm">3</div>
                                <h4 class="font-black text-white mb-2 relative z-10 text-lg">Cetak & Export</h4>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed relative z-10">Unduh data dalam format Microsoft Excel atau cetak langsung menjadi dokumen PDF.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- SCRIPT ALPINE JS & SWEETALERT --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            // Komponen Alpine JS untuk mengatur Dropdown Tingkat & Kelas yang bersambung
            Alpine.data('gradeFilter', () => ({
                selectedLevel: '{{ $selectedLevelId ?? '' }}',
                selectedClass: '{{ $selectedClassId ?? '' }}',
                allClasses: @json($classes ?? []),
                
                filteredClasses() {
                    if (!this.selectedLevel) return this.allClasses;
                    
                    const romawiMap = {
                        '7': 'VII', '8': 'VIII', '9': 'IX'
                    };
                    const romawi = romawiMap[this.selectedLevel] || this.selectedLevel;
                    
                    return this.allClasses.filter(c => {
                        const name = c.name.toString().toUpperCase();
                        const levelStr = this.selectedLevel.toString().toUpperCase();
                        const romawiStr = romawi.toString().toUpperCase();
                        
                        return name.startsWith(levelStr) || name.startsWith(romawiStr);
                    });
                },
                
                handleLevelChange() {
                    this.selectedClass = '';
                }
            }));
        });

        // Script Sweetalert
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Loading saat Filter Form dikirim
            const filterForm = document.getElementById('filterForm');
            if(filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    const subject = document.querySelector('select[name="subject_id"]').value;
                    const level = document.querySelector('select[name="level_id"]').value;
                    const cls = document.querySelector('select[name="class_id"]').value;
                    
                    if (subject === '' || (level === '' && cls === '')) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Filter',
                            text: 'Harap pilih Mata Pelajaran, serta minimal pilih Tingkat atau Kelas.',
                            background: '#021124',
                            color: '#fff',
                            customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Sedang Memuat Data...',
                        text: 'Mohon tunggu sebentar.',
                        background: '#021124',
                        color: '#fff',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
                    });
                });
            }

            // 2. Notifikasi Toast Export
            const btnExport = document.querySelector('.btn-export');
            if(btnExport) {
                btnExport.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'success', 
                        title: 'Menyiapkan Excel...',
                        text: 'File akan segera diunduh.',
                        toast: true, 
                        position: 'top-end',
                        showConfirmButton: false, 
                        timer: 4000, 
                        timerProgressBar: true,
                        background: '#021124',
                        color: '#fff',
                        customClass: { popup: 'rounded-2xl border border-emerald-500/20 shadow-lg bg-[#021124] text-emerald-300 font-sans' }
                    });
                });
            }

            // 3. Notifikasi Toast Print
            const btnPrint = document.querySelector('.btn-print');
            if(btnPrint) {
                btnPrint.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'info', 
                        title: 'Membuka PDF...',
                        text: 'Membuka dokumen di tab baru.',
                        toast: true, 
                        position: 'top-end',
                        showConfirmButton: false, 
                        timer: 3000, 
                        timerProgressBar: true,
                        background: '#021124',
                        color: '#fff',
                        customClass: { popup: 'rounded-2xl border border-white/10 shadow-lg bg-[#021124] text-white font-sans' }
                    });
                });
            }

            // 4. Session Messages
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", background: '#021124', color: '#fff', customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' } });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", background: '#021124', color: '#fff', customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' } });
            @endif
        });
    </script>
    @endpush
</x-app-layout>