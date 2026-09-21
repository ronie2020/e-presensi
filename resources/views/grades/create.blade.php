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
                    <div>
                        <div class="flex items-center gap-2 text-slate-400 text-sm font-bold mb-2">
                            <a href="{{ route('grades.index') }}" class="hover:text-sky-300 transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span class="text-sky-400">Input Nilai Mapel</span>
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight leading-none text-white mb-2">Form Penilaian</h1>
                        <p class="text-slate-300 text-sm font-medium">Masukan nilai pengetahuan/keterampilan siswa.</p>
                    </div>

                    {{-- Badge Info Kelas & Mapel --}}
                    <div class="flex gap-3">
                        <div class="bg-slate-900/80 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/10 flex items-center gap-3 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-300 border border-sky-400/30 flex items-center justify-center text-lg shadow-md">
                                <i class="ph-bold ph-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-sky-400 uppercase tracking-wider">Kelas</p>
                                <p class="text-sm font-bold text-white">{{ $class->name }}</p>
                            </div>
                        </div>
                        <div class="bg-slate-900/80 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/10 flex items-center gap-3 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-400/30 flex items-center justify-center text-lg shadow-md">
                                <i class="ph-bold ph-book-open"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-amber-400 uppercase tracking-wider">Mapel</p>
                                <p class="text-sm font-bold text-white">{{ $subject->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Input --}}
            <form id="gradeForm" action="{{ route('grades.store') }}" method="POST" @submit="isDirty = false; isSubmitting = true">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                <input type="hidden" name="academic_year" value="{{ $academic_year }}">
                <input type="hidden" name="semester" value="{{ $semester }}">

                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden backdrop-blur-xl">
                    <div class="overflow-x-auto max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse relative">
                            <thead class="bg-slate-900/90 sticky top-0 z-10 shadow-sm text-slate-300 border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center text-slate-400">No</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[250px] text-sky-400">Nama Siswa</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-64 text-center text-sky-400">Nilai (0-100)</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[300px] text-sky-400">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 bg-slate-900/40">
                                @foreach($students as $index => $student)
                                    @php
                                        $existingScore = $existingGrades[$student->id]->score ?? '';
                                        $existingDesc = $existingGrades[$student->id]->description ?? '';
                                    @endphp
                                    <tr class="hover:bg-white/5 transition-colors group focus-within:bg-white/10" 
                                        x-data="{ score: '{{ $existingScore }}', predikat: '' }"
                                        x-init="predikat = calculatePredicate(score)">
                                        
                                        <td class="px-6 py-4 text-center font-bold text-slate-400 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white text-sm group-hover:text-sky-300 transition-colors">{{ $student->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono tracking-wide mt-0.5">NIS: {{ $student->student_id }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <input type="number" 
                                                       name="grades[{{ $student->id }}]" 
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
                                                        @click="if(confirm('Hapus nilai {{ addslashes($student->name) }}?')) { score = ''; predikat = ''; isDirty = true; }"
                                                        class="p-2 text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition"
                                                        title="Hapus Nilai">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="descriptions[{{ $student->id }}]" value="{{ $existingDesc }}" @input="isDirty = true" class="w-full rounded-xl border border-white/10 bg-slate-900/90 text-white text-sm py-2 px-3 focus:ring-sky-400 focus:border-sky-400">
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
                                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Data'">Simpan Data</span>
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
                totalRows: {{ count($students) }},
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