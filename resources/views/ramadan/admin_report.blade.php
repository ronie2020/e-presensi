<x-app-layout>
    {{-- PRINT STYLES --}}
    <style>
        @media print {
            @page { size: landscape; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
            .no-print, header, nav, footer, form, .shadow-sm, .modal-backdrop { display: none !important; }
            .print-container { padding: 0 !important; margin: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; }
            .print-table th, .print-table td { border: 1px solid #cbd5e1 !important; font-size: 10pt !important; padding: 8px !important; }
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; }
            .print-hidden { display: none !important; }
        }
        .print-header { display: none; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- WRAPPER DENGAN ALPINE JS UNTUK MODAL --}}
    <div x-data="{
        isModalOpen: false,
        studentName: '',
        details: {}, 
        formAction: '',
        currentScore: '',
        currentNote: '',
        
        // FUNGSI BUKA MODAL
        openFeedback(id, name, score, note, fasting, prayerCount, sunnahCount, khotib, summary) {
            this.studentName = name;
            this.currentScore = score ? score : 100; // Default nilai 100 jika belum ada
            this.currentNote = note;
            
            this.details = {
                fasting: fasting,
                prayerCount: prayerCount,
                sunnahCount: sunnahCount,
                khotib: khotib,
                summary: summary
            };

            this.formAction = '{{ route('admin.ramadan.verify', ':id') }}'.replace(':id', id); 
            this.isModalOpen = true;
        },

        // FUNGSI QUICK SCORE
        setScore(value) {
            this.currentScore = value;
        }
    }" class="p-6 md:p-8 space-y-6 min-h-screen bg-slate-50 print-container">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="ph-fill ph-book-open text-emerald-600"></i> Rekap Mutabaah
                </h1>
                <p class="text-sm text-slate-500 mt-1">Monitoring ibadah: <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</span></p>
            </div>
            
            {{-- FILTER FORM --}}
            <form action="{{ route('admin.ramadan.reports') }}" method="GET" class="flex flex-wrap gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center px-3 gap-2 border-r border-slate-100">
                    <i class="ph-bold ph-chalkboard text-slate-400"></i>
                    <select name="class_id" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer min-w-[140px]" onchange="this.form.submit()">
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center px-3 gap-2">
                    <i class="ph-bold ph-calendar text-slate-400"></i>
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer">
                </div>
                <div class="px-2">
                     <button type="submit" class="hidden">Filter</button>
                </div>
            </form>
        </div>

        {{-- QUICK STATS --}}
        @if($selectedClass && $reports->count() > 0)
        @php
            $totalStudents = $reports->count();
            $fastingCount = $reports->filter(fn($s) => $s->ramadanLogs->first()?->is_fasting)->count();
            $fullPrayerCount = $reports->filter(fn($s) => count(array_filter($s->ramadanLogs->first()?->prayers ?? [])) == 5)->count();
            $fastingPercent = $totalStudents > 0 ? round(($fastingCount / $totalStudents) * 100) : 0;
            $isFriday = \Carbon\Carbon::parse($date)->isFriday();
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 no-print">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-student"></i></div>
                <div><div class="text-lg font-black text-slate-800">{{ $totalStudents }}</div><div class="text-[10px] uppercase font-bold text-slate-400">Total Siswa</div></div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-bowl-food"></i></div>
                <div><div class="text-lg font-black text-slate-800">{{ $fastingCount }} <span class="text-xs text-slate-400 font-medium">({{ $fastingPercent }}%)</span></div><div class="text-[10px] uppercase font-bold text-slate-400">Berpuasa</div></div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center"><i class="ph-bold ph-hands-praying"></i></div>
                <div><div class="text-lg font-black text-slate-800">{{ $fullPrayerCount }}</div><div class="text-[10px] uppercase font-bold text-slate-400">Shalat Lengkap</div></div>
            </div>
             @if($isFriday)
            <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm flex items-center gap-3 ring-1 ring-emerald-50">
                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center"><i class="ph-bold ph-mosque"></i></div>
                <div>
                    <div class="text-lg font-black text-slate-800">
                        {{ $reports->filter(fn($s) => $s->ramadanLogs->first()?->friday_khotib)->count() }}
                    </div>
                    <div class="text-[10px] uppercase font-bold text-slate-400">Jurnal Jumat</div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- HEADER KHUSUS PRINT --}}
        <div class="print-header">
            <h2 class="text-xl font-bold uppercase">Laporan Harian Mutabaah Ramadhan</h2>
            <p class="text-sm">{{ $classes->find($selectedClass)->name ?? 'Semua Kelas' }} &bull; {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</p>
        </div>

        {{-- CONTENT --}}
        @if($selectedClass)
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden print-container">
                <div class="overflow-x-auto">
                    <table class="w-full text-left print-table">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-10">No</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Puasa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Shalat 5W</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center no-print">Detail</th>
                                
                                {{-- [BARU] KOLOM KHUSUS JUMAT --}}
                                @if($isFriday)
                                <th class="px-6 py-4 text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center bg-emerald-50/30">Laporan Jumat</th>
                                @endif

                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tilawah</th>
                                <th class="px-6 py-4 text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center bg-emerald-50/50">Feedback Guru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($reports as $index => $student)
                            @php 
                                $log = $student->ramadanLogs->first();
                                $prayerCount = $log ? count(array_filter($log->prayers ?? [])) : 0;
                                $sunnahCount = $log ? count(array_filter($log->sunnah_deeds ?? [])) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-slate-800 text-sm">{{ $student->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400">{{ $student->student_id }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($log && $log->is_fasting)
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto"><i class="ph-bold ph-check"></i></div>
                                    @elseif($log)
                                        <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center mx-auto"><i class="ph-bold ph-x"></i></div>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($log)
                                        <span class="text-sm font-black {{ $prayerCount == 5 ? 'text-emerald-600' : 'text-amber-500' }}">{{ $prayerCount }}/5</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center no-print">
                                    @if($log && is_array($log->prayers))
                                        <div class="flex justify-center gap-1">
                                            @foreach(['subuh','dzuhur','ashar','maghrib','isya'] as $p)
                                                <div title="{{ ucfirst($p) }}" class="w-2 h-2 rounded-full {{ ($log->prayers[$p] ?? false) ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- [BARU] KOLOM JUMAT DI BODY --}}
                                @if($isFriday)
                                <td class="px-6 py-4 text-center">
                                    @if($log && $log->friday_khotib)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-bold border border-emerald-200" title="{{ Str::limit($log->friday_summary, 50) }}">
                                            <i class="ph-bold ph-check-circle"></i> Ada
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-[10px] italic">Kosong</span>
                                    @endif
                                </td>
                                @endif

                                <td class="px-6 py-4">
                                    @if($log && $log->tadarus_surah)
                                        <div class="text-xs font-bold text-slate-700">{{ Str::limit($log->tadarus_surah, 12) }} : {{ $log->tadarus_ayah }}</div>
                                    @else
                                        <span class="text-slate-300 text-[10px]">-</span>
                                    @endif
                                </td>
                                
                                {{-- KOLOM AKSI / FEEDBACK --}}
                                <td class="px-6 py-4 text-center bg-slate-50/30">
                                    @if($log)
                                        <button type="button" 
                                            @click="openFeedback(
                                                {{ $log->id }}, 
                                                {{ json_encode($student->name) }}, 
                                                '{{ $log->teacher_score }}', 
                                                {{ json_encode($log->teacher_note) }},
                                                {{ $log->is_fasting ? 'true' : 'false' }},
                                                {{ $prayerCount }},
                                                {{ $sunnahCount }},
                                                {{ json_encode($log->friday_khotib) }},
                                                {{ json_encode($log->friday_summary) }}
                                            )"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm group
                                            {{ $log->teacher_verified_at ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-white border border-slate-200 text-slate-500 hover:bg-emerald-600 hover:text-white hover:border-emerald-600' }}">
                                            @if($log->teacher_verified_at)
                                                <i class="ph-bold ph-check-circle"></i> Nilai: {{ $log->teacher_score }}
                                            @else
                                                <i class="ph-bold ph-chat-text group-hover:scale-110 transition-transform"></i> Nilai
                                            @endif
                                        </button>
                                    @else
                                        <span class="text-slate-300 text-[10px] italic">Belum Input</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="{{ $isFriday ? 8 : 7 }}" class="text-center py-10 text-slate-400">Tidak ada data siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200 no-print">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-chalkboard-teacher text-5xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-black text-slate-600">Pilih Kelas</h3>
                <p class="text-slate-400 text-sm mt-2">Silakan pilih kelas di atas untuk melihat laporan.</p>
            </div>
        @endif

        {{-- ACTIONS --}}
        @if($selectedClass)
        <div class="flex justify-end gap-4 no-print">
            <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition shadow-lg flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak Laporan
            </button>
        </div>
        @endif

        {{-- MODAL FEEDBACK & MOTIVASI --}}
        <div x-cloak x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4 modal-backdrop">
            {{-- Backdrop --}}
            <div x-show="isModalOpen" 
                x-transition.opacity
                @click="isModalOpen = false"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            {{-- Modal Content --}}
            <div x-show="isModalOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-emerald-800 to-teal-600 p-6 text-white flex justify-between items-start shrink-0">
                    <div>
                        <h3 class="font-black text-xl">Feedback & Motivasi</h3>
                        <p class="text-emerald-100 text-sm" x-text="studentName"></p>
                    </div>
                    <button @click="isModalOpen = false" class="text-white/60 hover:text-white transition"><i class="ph-bold ph-x text-2xl"></i></button>
                </div>

                {{-- Body --}}
                <div class="p-6 overflow-y-auto space-y-6">
                    
                    {{-- RINGKASAN AKTIVITAS HARIAN --}}
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2 mb-2">Rincian Ibadah Hari Ini</h4>
                        
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-white p-2 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400">Puasa</div>
                                <div class="font-black" :class="details.fasting ? 'text-emerald-600' : 'text-rose-500'" x-text="details.fasting ? 'YA' : 'TIDAK'"></div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400">Wajib</div>
                                <div class="font-black text-slate-700"><span x-text="details.prayerCount"></span>/5</div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400">Sunnah</div>
                                <div class="font-black text-slate-700" x-text="details.sunnahCount"></div>
                            </div>
                        </div>

                        {{-- TAMPILKAN KHUSUS JUMAT --}}
                        <template x-if="details.khotib">
                            <div class="mt-3 pt-3 border-t border-slate-200">
                                <div class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Jurnal Jumat</div>
                                <div class="text-xs font-bold text-slate-800 mb-1" x-text="'Khotib: ' + details.khotib"></div>
                                <div class="text-xs text-slate-600 italic bg-white p-3 rounded-xl border border-slate-100" x-text="details.summary"></div>
                            </div>
                        </template>
                         <template x-if="!details.khotib && '{{ $isFriday }}'">
                             <div class="mt-3 pt-3 border-t border-slate-200">
                                <div class="text-[10px] font-bold text-rose-500 uppercase mb-1">Jurnal Jumat: Kosong</div>
                             </div>
                        </template>
                    </div>

                    {{-- FORM INPUT GURU --}}
                    <form :action="formAction" method="POST" id="gradingForm">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nilai Aktivitas (0-100)</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="teacher_score" x-model="currentScore" min="0" max="100" class="w-full rounded-xl border-slate-200 font-bold focus:ring-emerald-500 focus:border-emerald-500 text-lg" placeholder="0">
                                    
                                    {{-- [BARU] Quick Score Buttons --}}
                                    <div class="flex gap-1">
                                        <button type="button" @click="setScore(100)" class="px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold hover:bg-emerald-100">100</button>
                                        <button type="button" @click="setScore(80)" class="px-3 py-2 bg-slate-50 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-100">80</button>
                                        <button type="button" @click="setScore(60)" class="px-3 py-2 bg-rose-50 text-rose-700 rounded-lg text-xs font-bold hover:bg-rose-100">60</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Catatan & Motivasi Guru</label>
                                <textarea name="teacher_note" x-model="currentNote" rows="3" class="w-full rounded-xl border-slate-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 leading-relaxed" placeholder="Berikan kata-kata motivasi..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 shrink-0">
                    <button @click="isModalOpen = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-200 transition text-sm">Batal</button>
                    <button type="submit" form="gradingForm" class="px-5 py-2.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition text-sm shadow-lg shadow-emerald-200 flex items-center gap-2">
                        <i class="ph-bold ph-paper-plane-right"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>