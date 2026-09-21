<x-app-layout>
    {{-- Konfigurasi KKM/Interval --}}
    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20" 
         x-data="gradeForm({
            kkm: 75,
            intervals: { a: 92, b: 83, c: 75 } 
         })">
         
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION MICROSOFT ELEVATE DARK THEME --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-8 mb-8 text-white shadow-2xl backdrop-blur-xl overflow-hidden border border-white/10">
                {{-- Decorative Shapes --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-sky-500/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-blue-600/10 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-slate-400 text-sm font-bold mb-2">
                            <a href="{{ route('grades.index') }}" class="hover:text-sky-300 transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span class="text-sky-400">Input Per Siswa</span>
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight leading-none text-white mb-2">Nilai Siswa</h1>
                        <div class="flex items-center gap-2 mt-3">
                            <span class="bg-sky-500/20 text-sky-300 border border-sky-400/30 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">{{ $class->name }}</span>
                            <span class="text-slate-400 font-medium text-sm">Semester {{ $semester }}</span>
                        </div>
                    </div>

                    {{-- CARD PILIH SISWA (NAVIGASI) --}}
                    <div class="w-full md:w-96 bg-slate-900/80 backdrop-blur-md p-2 rounded-2xl border border-white/10 shadow-sm flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-400 to-[#0d52a1] text-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0">
                             {{ substr($student->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0 mr-2">
                            <label class="text-[9px] uppercase font-bold text-sky-400 tracking-widest block mb-0.5">Sedang Menilai:</label>
                            <div class="relative">
                                <select onchange="window.location.href = this.value" 
                                        class="w-full p-0 border-none text-white font-bold text-sm focus:ring-0 cursor-pointer truncate bg-transparent hover:text-sky-300 transition appearance-none pr-6 [color-scheme:dark]">
                                    @foreach($students as $s)
                                        <option class="bg-slate-900 text-white" value="{{ route('grades.create_by_student', ['class_id' => $class->id, 'student_id' => $s->id, 'academic_year' => $academic_year, 'semester' => $semester]) }}" 
                                                {{ $s->id == $student->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down text-sky-400 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="gradeForm" action="{{ route('grades.store_by_student') }}" method="POST" @submit="isDirty = false; isSubmitting = true">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="academic_year" value="{{ $academic_year }}">
                <input type="hidden" name="semester" value="{{ $semester }}">

                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden backdrop-blur-xl">
                    <div class="overflow-x-auto max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse relative">
                            <thead class="bg-slate-900/90 sticky top-0 z-10 shadow-sm text-slate-300 border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center text-slate-400">No</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[250px] text-sky-400">Mata Pelajaran</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-64 text-center text-sky-400">Nilai (0-100)</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[300px] text-sky-400">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 bg-slate-900/40">
                                @foreach($subjects as $index => $subject)
                                    @php
                                        $existingScore = $existingGrades[$subject->id]->score ?? '';
                                        $existingDesc = $existingGrades[$subject->id]->description ?? '';
                                    @endphp
                                    <tr class="hover:bg-white/5 transition-colors group focus-within:bg-white/10" 
                                        x-data="{ score: '{{ $existingScore }}', predikat: '' }"
                                        x-init="predikat = calculatePredicate(score)">
                                        
                                        <td class="px-6 py-4 text-center font-bold text-slate-400 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white text-sm group-hover:text-sky-300 transition-colors">{{ $subject->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <input type="number" 
                                                       name="grades[{{ $subject->id }}]" 
                                                       x-model="score"
                                                       @input="isDirty = true; predikat = calculatePredicate(score)"
                                                       @keydown="handleKeydown($event, {{ $index }}, 'score')"
                                                       class="input-score w-20 rounded-xl border border-white/10 bg-slate-900/90 text-white text-center font-black py-2 focus:ring-sky-400 focus:border-sky-400 [color-scheme:dark]"
                                                       placeholder="-">
                                                
                                                <div class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs border"
                                                     :class="{
                                                        'bg-emerald-500/20 text-emerald-400 border-emerald-500/30': predikat === 'A',
                                                        'bg-sky-500/20 text-sky-300 border-sky-400/30': predikat === 'B',
                                                        'bg-amber-500/20 text-amber-300 border-amber-400/30': predikat === 'C',
                                                        'bg-rose-500/20 text-rose-400 border-rose-500/30': predikat === 'D',
                                                        'bg-slate-900 text-slate-600 border-white/5': !predikat
                                                     }" x-text="predikat || '-'"></div>

                                                {{-- TOMBOL HAPUS --}}
                                                <button type="button" 
                                                        @click="if(confirm('Hapus nilai mapel {{ addslashes($subject->name) }}?')) { score = ''; predikat = ''; isDirty = true; }"
                                                        class="p-2 text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition"
                                                        title="Hapus Nilai">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="descriptions[{{ $subject->id }}]" value="{{ $existingDesc }}" @input="isDirty = true" class="w-full rounded-xl border border-white/10 bg-slate-900/90 text-white text-sm py-2 px-3 focus:ring-sky-400 focus:border-sky-400">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-slate-900/90 backdrop-blur-md border-t border-white/10 flex justify-between items-center sticky bottom-0 z-20">
                        <span x-show="isDirty" style="display: none;" class="text-amber-400 font-bold text-xs flex items-center gap-1"><i class="ph-fill ph-warning-circle text-sm"></i> Perubahan belum disimpan</span>
                        <div class="flex gap-3 ml-auto">
                            <a href="{{ route('grades.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-slate-300 font-bold text-sm hover:bg-white/10 transition">Batal</a>
                            
                            {{-- BUTTON DENGAN ANIMASI LOADING --}}
                            <button type="submit" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="px-8 py-3 bg-gradient-to-r from-sky-400 to-[#0d52a1] text-white font-bold rounded-xl hover:from-sky-300 hover:to-sky-700 shadow-lg shadow-sky-500/20 transition flex items-center gap-2">
                                <i x-show="isSubmitting" style="display: none;" class="ph-bold ph-spinner animate-spin"></i>
                                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Nilai'">Simpan Nilai</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gradeForm', (config) => ({
                isDirty: false,
                isSubmitting: false,
                totalRows: {{ count($subjects) }},
                kkm: config.kkm,
                intervals: config.intervals,

                init() {
                    window.addEventListener('beforeunload', (e) => {
                        if (this.isDirty && !this.isSubmitting) { e.preventDefault(); e.returnValue = ''; }
                    });

                    // NOTIFIKASI SUKSES
                    @if(session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: '{{ session('success') }}',
                            timer: 3000,
                            showConfirmButton: false,
                            background: '#0f172a',
                            color: '#f8fafc',
                            customClass: { popup: 'rounded-[2rem] border border-white/10' }
                        });
                    @endif
                    
                    // NOTIFIKASI ERROR
                    @if($errors->any())
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: '{!! implode("<br>", $errors->all()) !!}',
                            background: '#0f172a',
                            color: '#f8fafc',
                            customClass: { popup: 'rounded-[2rem] border border-white/10' }
                        });
                    @endif
                },

                calculatePredicate(val) {
                    let score = parseInt(val);
                    if (isNaN(score)) return '';
                    if (score >= this.intervals.a) return 'A';
                    if (score >= this.intervals.b) return 'B';
                    if (score >= this.intervals.c) return 'C';
                    return 'D';
                },

                handleKeydown(e, index, type) {
                    if (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter') {
                        if(e.key === 'Enter') e.preventDefault();
                        let nextIndex = index + (e.key === 'ArrowUp' ? -1 : 1);
                        if (nextIndex >= 0 && nextIndex < this.totalRows) {
                            const selector = type === 'score' ? '.input-score' : '.input-desc';
                            const rows = document.querySelectorAll('tbody tr');
                            const target = rows[nextIndex].querySelector(selector);
                            if (target) target.focus();
                        }
                    }
                }
            }))
        })
    </script>
</x-app-layout>