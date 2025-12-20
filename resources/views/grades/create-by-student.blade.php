<x-app-layout>
    {{-- Konfigurasi KKM/Interval untuk JS --}}
    <div class="py-6 sm:py-8" 
         x-data="gradeForm({
            kkm: 75,
            intervals: { a: 92, b: 83, c: 75 } 
         })">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header & Navigasi --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                <div class="flex-1">
                    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                        <a href="{{ route('grades.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </a>
                        <span class="text-slate-300">/</span>
                        <span class="text-fuchsia-600 font-bold">Input Per Siswa</span>
                    </div>
                    
                    <div class="flex items-center gap-3">
                         <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-none">Nilai Siswa</h1>
                         <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-sm font-bold border border-slate-200">{{ $class->name }}</span>
                    </div>
                </div>

                {{-- CARD PILIH SISWA (NAVIGASI UTAMA) --}}
                <div class="w-full md:w-96 bg-white p-1.5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-200 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-fuchsia-500 to-purple-600 text-white flex items-center justify-center font-bold text-lg shadow-md shrink-0">
                         {{ substr($student->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0 mr-2">
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Sedang Menilai:</label>
                        {{-- Dropdown Auto-Submit saat ganti siswa --}}
                        <select onchange="window.location.href = this.value" 
                                class="w-full p-0 border-none text-slate-800 font-bold text-sm focus:ring-0 cursor-pointer truncate bg-transparent hover:text-fuchsia-600 transition">
                            @foreach($students as $s)
                                <option value="{{ route('grades.create_by_student', ['class_id' => $class->id, 'student_id' => $s->id, 'academic_year' => $academic_year, 'semester' => $semester]) }}" 
                                        {{ $s->id == $student->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->student_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pointer-events-none text-slate-400 pr-2">
                        <i class="ph-bold ph-caret-up-down"></i>
                    </div>
                </div>
            </div>

            <form id="gradeForm" action="{{ route('grades.store_by_student') }}" method="POST" @submit="isDirty = false">
                @csrf
                {{-- Hidden Inputs untuk menjaga state --}}
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="academic_year" value="{{ $academic_year }}">
                <input type="hidden" name="semester" value="{{ $semester }}">

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative">
                    
                    {{-- Instruksi --}}
                    <div class="bg-fuchsia-50/50 px-6 py-3 text-xs text-fuchsia-600 flex items-center justify-between border-b border-fuchsia-100">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-info"></i>
                            <span>Pastikan mengisi seluruh mata pelajaran. Gunakan <strong>Panah Atas/Bawah</strong> untuk navigasi cepat.</span>
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
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[250px]">Mata Pelajaran</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-40 text-center">Nilai (0-100)</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[300px]">Deskripsi / Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                @foreach($subjects as $index => $subject)
                                    @php
                                        // Ambil nilai existing jika ada
                                        $existingScore = $existingGrades[$subject->id]->score ?? '';
                                        $existingDesc = $existingGrades[$subject->id]->description ?? '';
                                    @endphp
                                    <tr class="hover:bg-fuchsia-50/30 transition-colors group focus-within:bg-fuchsia-50" 
                                        data-row-index="{{ $index }}"
                                        x-data="{ score: '{{ $existingScore }}', predikat: '' }"
                                        x-init="predikat = calculatePredicate(score)">
                                        
                                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-100 text-slate-400 flex items-center justify-center shadow-sm">
                                                    <i class="ph-bold ph-book"></i>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-700 text-sm">{{ $subject->name }}</div>
                                                    {{-- Jika mapel wajib/pilihan bisa ditambah badge disini --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="relative flex items-center justify-center gap-2">
                                                <input type="number" 
                                                       name="grades[{{ $subject->id }}]" 
                                                       x-model="score"
                                                       @input="isDirty = true; predikat = calculatePredicate(score)"
                                                       @keydown="handleKeydown($event, {{ $index }}, 'score')"
                                                       min="0" max="100"
                                                       class="input-score w-20 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-fuchsia-500 focus:ring-fuchsia-500 text-center font-black text-fuchsia-700 py-2.5 transition-all shadow-sm placeholder:font-normal placeholder:text-slate-300"
                                                       placeholder="0">
                                                
                                                {{-- LIVE PREDIKAT BADGE --}}
                                                <div class="absolute -right-12 w-8 h-8 flex items-center justify-center rounded-lg font-bold text-xs transition-all duration-300"
                                                     :class="{
                                                        'bg-emerald-100 text-emerald-700': predikat === 'A',
                                                        'bg-blue-100 text-blue-700': predikat === 'B',
                                                        'bg-amber-100 text-amber-700': predikat === 'C',
                                                        'bg-rose-100 text-rose-700': predikat === 'D' || predikat === 'E',
                                                        'text-transparent': !predikat
                                                     }">
                                                    <span x-text="predikat"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" 
                                                   name="descriptions[{{ $subject->id }}]" 
                                                   value="{{ $existingDesc }}"
                                                   @input="isDirty = true"
                                                   @keydown="handleKeydown($event, {{ $index }}, 'desc')"
                                                   class="input-desc w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm text-slate-600 py-2.5 px-4 transition-all shadow-sm"
                                                   placeholder="Deskripsi pencapaian kompetensi...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Action Bar --}}
                    <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between sticky bottom-0 z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
                        <div class="text-xs text-slate-400 hidden sm:block">
                            <span x-show="isDirty" class="text-amber-500 font-bold mr-2 animate-pulse"><i class="ph-fill ph-warning"></i> Perubahan belum disimpan</span>
                            <span>Menilai siswa <strong>{{ $student->name }}</strong></span>
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            <a href="{{ route('grades.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition text-center w-full sm:w-auto">Batal</a>
                            
                            {{-- Button Group --}}
                            <button type="submit" class="px-8 py-3 bg-fuchsia-600 text-white font-bold rounded-xl hover:bg-fuchsia-700 shadow-lg shadow-fuchsia-500/30 transition flex items-center justify-center gap-2 w-full sm:w-auto transform active:scale-95">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Nilai
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Script AlpineJS untuk Navigasi Keyboard --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gradeForm', (config) => ({
                isDirty: false,
                totalRows: {{ count($subjects) }},
                kkm: config.kkm,
                intervals: config.intervals,

                init() {
                    window.addEventListener('beforeunload', (e) => {
                        if (this.isDirty) { e.preventDefault(); e.returnValue = ''; }
                    });
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
                    // Navigasi Arrow Key (Atas/Bawah) antar baris mapel
                    if (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter') {
                        if (e.key === 'Enter') e.preventDefault(); // Prevent submit form on enter
                        
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