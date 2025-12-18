<x-app-layout>
    {{-- Kita tambahkan konfigurasi KKM/Interval di sini (bisa diambil dari database nanti) --}}
    <div class="py-6 sm:py-8" 
         x-data="gradeForm({
            kkm: 75,
            intervals: { a: 92, b: 83, c: 75 } 
         })">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Navigasi --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                        <a href="{{ route('grades.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </a>
                        <span class="text-slate-300">/</span>
                        <span class="text-blue-600 font-bold">Isi Data</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-none">Form Penilaian</h1>
                </div>
                
                {{-- Info Badge --}}
                <div class="flex gap-2">
                    <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="ph-bold ph-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Kelas</p>
                            <p class="text-sm font-bold text-slate-800">{{ $class->name }}</p>
                        </div>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <i class="ph-bold ph-book-open"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Mapel</p>
                            <p class="text-sm font-bold text-slate-800">{{ $subject->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form id="gradeForm" action="{{ route('grades.store') }}" method="POST" @submit="isDirty = false">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                <input type="hidden" name="academic_year" value="{{ $academic_year }}">
                <input type="hidden" name="semester" value="{{ $semester }}">

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative">
                    
                    {{-- Instruksi Navigasi --}}
                    <div class="bg-blue-50/50 px-6 py-2 text-xs text-blue-600 flex items-center justify-between border-b border-blue-100">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-info"></i>
                            <span>Tips: Gunakan <strong>Panah Atas/Bawah</strong> untuk pindah baris.</span>
                        </div>
                        <div class="flex gap-3 font-mono opacity-70">
                            <span>A: >92</span>
                            <span>B: >83</span>
                            <span>C: >75</span>
                        </div>
                    </div>

                    {{-- Table Wrapper --}}
                    <div class="overflow-x-auto max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse relative">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-16 text-center">No</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[200px]">Nama Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-40 text-center">Nilai (0-100)</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[300px]">Deskripsi (Opsional)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                @foreach($students as $index => $student)
                                    @php
                                        $existingScore = $existingGrades[$student->id]->score ?? '';
                                        $existingDesc = $existingGrades[$student->id]->description ?? '';
                                    @endphp
                                    <tr class="hover:bg-blue-50/30 transition-colors group focus-within:bg-blue-50" 
                                        data-row-index="{{ $index }}"
                                        x-data="{ score: '{{ $existingScore }}', predikat: '' }"
                                        x-init="predikat = calculatePredicate(score)">
                                        
                                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs shadow-sm">
                                                    {{ substr($student->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-700 text-sm">{{ $student->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-mono">NIS: {{ $student->student_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="relative flex items-center gap-2">
                                                <input type="number" 
                                                       name="grades[{{ $student->id }}]" 
                                                       x-model="score"
                                                       @input="isDirty = true; predikat = calculatePredicate(score)"
                                                       @keydown="handleKeydown($event, {{ $index }}, 'score')"
                                                       min="0" max="100"
                                                       class="input-score w-20 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-center font-black text-blue-700 py-2.5 transition-all shadow-sm placeholder:font-normal placeholder:text-slate-300"
                                                       placeholder="0">
                                                
                                                {{-- LIVE PREDIKAT BADGE --}}
                                                <div class="w-8 h-8 flex items-center justify-center rounded-lg font-bold text-xs shadow-sm transition-all duration-300"
                                                     :class="{
                                                        'bg-emerald-100 text-emerald-700': predikat === 'A',
                                                        'bg-blue-100 text-blue-700': predikat === 'B',
                                                        'bg-amber-100 text-amber-700': predikat === 'C',
                                                        'bg-rose-100 text-rose-700': predikat === 'D' || predikat === 'E',
                                                        'bg-slate-100 text-slate-300': !predikat
                                                     }">
                                                    <span x-text="predikat || '-'"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" 
                                                   name="descriptions[{{ $student->id }}]" 
                                                   value="{{ $existingDesc }}"
                                                   @input="isDirty = true"
                                                   @keydown="handleKeydown($event, {{ $index }}, 'desc')"
                                                   class="input-desc w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm text-slate-600 py-2.5 px-4 transition-all shadow-sm"
                                                   placeholder="Deskripsi pencapaian...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Action Bar --}}
                    <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between sticky bottom-0 z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
                        <div class="text-xs text-slate-400 hidden sm:block">
                            <span x-show="isDirty" class="text-amber-500 font-bold mr-2"><i class="ph-fill ph-warning"></i> Belum disimpan</span>
                            <span class="font-bold text-slate-600">{{ count($students) }}</span> Siswa.
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            <a href="{{ route('grades.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition text-center w-full sm:w-auto">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition flex items-center justify-center gap-2 w-full sm:w-auto transform active:scale-95">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gradeForm', (config) => ({
                isDirty: false,
                totalRows: {{ count($students) }},
                kkm: config.kkm,
                intervals: config.intervals,

                init() {
                    window.addEventListener('beforeunload', (e) => {
                        if (this.isDirty) { e.preventDefault(); e.returnValue = ''; }
                    });
                },

                // Logika Predikat Sederhana
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
                        e.preventDefault();
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