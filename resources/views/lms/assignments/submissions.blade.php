<x-app-layout>
    {{-- Header Judul --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Penilaian Tugas') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>

    {{-- WRAPPER ALPINE JS UTAMA --}}
    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20" 
         x-data="submissionGrading()">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="{{ str_replace('_', ' ', $assignment->assignment_type) }}"
                badgeIcon="ph-tag"
                title="{{ $assignment->title }}"
                titleHighlight="{{ $assignment->subject->name }}"
                description="Monitoring pengumpulan tugas, tinjau berkas jawaban siswa, dan berikan evaluasi nilai serta umpan balik secara terstruktur."
                :chips="[
                    ['icon' => 'ph-users-three', 'label' => ($assignment->is_bulk ? 'Semua Kelas ' . $assignment->target_grade : ($assignment->schoolClass->name ?? 'Semua Kelas'))],
                    ['icon' => 'ph-clock', 'label' => 'Deadline: ' . $assignment->deadline->format('d M Y, H:i')],
                    ['icon' => 'ph-check-circle', 'label' => 'Penilaian Terintegrasi']
                ]"
                heroIcon="ph-tray"
                :showcaseNumber="$submissions->count()"
                showcaseLabel="Terkumpul"
                showcaseSubtitle="dari {{ $allStudents->count() }} Siswa"
                statusOrb="{{ $submissions->count() >= $allStudents->count() && $allStudents->count() > 0 ? 'Lengkap' : 'Sedang Berjalan' }}"
                statusColor="{{ $submissions->count() >= $allStudents->count() && $allStudents->count() > 0 ? 'emerald' : 'amber' }}"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.assignments.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] hover:from-sky-300 hover:to-sky-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-arrow-left text-base"></i> Daftar Tugas
                        </a>
                        <a href="{{ route('lms.grades.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-chart-bar text-base text-sky-400"></i> Rekap Nilai
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-squares-four text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- TABEL SISWA --}}
            <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden min-h-[600px] flex flex-col backdrop-blur-xl" style="animation-delay: 100ms">
                
                <div class="p-6 md:p-8 border-b border-white/10 bg-slate-900/60 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5">
                    <h3 class="font-black text-white text-xl flex items-center gap-3 shrink-0">
                        <span class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-sky-400 shadow-sm border border-white/10"><i class="ph-bold ph-list-checks"></i></span>
                        Daftar Pengumpulan
                    </h3>
                    
                    <div class="flex flex-wrap gap-3 w-full xl:w-auto items-center justify-start xl:justify-end">
                        @if($assignment->is_bulk || $allStudents->count() > 30)
                            <div class="relative w-full sm:w-[calc(50%-0.375rem)] md:w-auto md:flex-1 xl:flex-none xl:w-40 group">
                                <select id="classFilter" class="w-full pl-4 pr-10 py-3.5 bg-slate-900/80 border-white/10 focus:bg-slate-900 border rounded-2xl text-sm font-bold focus:ring-sky-400/20 focus:border-sky-400 transition-all appearance-none cursor-pointer text-white shadow-sm [color-scheme:dark]">
                                    <option value="" class="bg-slate-900 text-white">Semua Kelas</option>
                                    @foreach($allStudents->pluck('schoolClass.name')->unique()->sort() as $className)
                                        <option value="{{ $className }}" class="bg-slate-900 text-white">{{ $className }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        @endif

                        {{-- Filter Status Penilaian --}}
                        <div class="relative w-full sm:w-[calc(50%-0.375rem)] md:w-auto md:flex-1 xl:flex-none xl:w-40 group">
                            <select id="statusFilter" class="w-full pl-4 pr-10 py-3.5 bg-slate-900/80 border-white/10 focus:bg-slate-900 border rounded-2xl text-sm font-bold focus:ring-sky-400/20 focus:border-sky-400 transition-all appearance-none cursor-pointer text-white shadow-sm [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua Status</option>
                                <option value="ungraded" class="bg-slate-900 text-white">Menunggu Dinilai</option>
                                <option value="graded" class="bg-slate-900 text-white">Sudah Dinilai</option>
                                <option value="missing" class="bg-slate-900 text-white">Belum Kumpul</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down"></i></div>
                        </div>

                        <div class="relative w-full sm:flex-1 md:w-auto xl:flex-none xl:w-56 group">
                            <input type="text" id="tableSearch" placeholder="Cari nama siswa..." class="pl-12 pr-4 py-3.5 bg-slate-900/80 focus:bg-slate-900 border border-white/10 rounded-2xl text-sm font-bold focus:ring-sky-400/20 focus:border-sky-400 w-full transition-all text-white placeholder:text-slate-500 shadow-sm">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-magnifying-glass text-lg"></i></div>
                        </div>

                        {{-- TOMBOL SIMPAN SEMUA --}}
                        <button type="button" onclick="saveAllGrades()" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-300 hover:to-blue-500 text-slate-950 font-bold rounded-2xl text-sm flex items-center justify-center gap-2 shadow-lg shadow-sky-500/20 transition-all active:scale-95 border border-transparent shrink-0">
                            <i class="ph-bold ph-floppy-disk text-lg"></i>
                            <span class="whitespace-nowrap">Simpan Semua</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1 custom-scrollbar">
                    <table class="w-full text-left border-collapse" id="submissionsTable">
                        <thead class="bg-slate-900/80 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black text-sky-400 uppercase tracking-wider w-1/4">Siswa</th>
                                <th class="px-6 py-5 text-xs font-black text-sky-400 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-5 text-xs font-black text-sky-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-5 text-xs font-black text-sky-400 uppercase tracking-wider w-1/4">Jawaban</th>
                                <th class="px-6 py-5 text-xs font-black text-sky-400 uppercase tracking-wider text-center">Nilai Final</th>
                                <th class="px-6 py-5 text-xs font-black text-sky-400 uppercase tracking-wider w-1/4">Feedback</th>
                                <th class="px-6 py-5 text-xs font-black text-sky-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($allStudents as $student)
                                @php
                                    $submission = $submissions->get($student->id); 
                                    $isLate = false;
                                    if($submission && $submission->submitted_at > $assignment->deadline) {
                                        $isLate = true;
                                    }
                                    $ansCount = $submission ? $submission->answers->count() : 0;
                                    
                                    $rowStatus = 'missing';
                                    if ($submission) {
                                        $rowStatus = isset($submission->grade) ? 'graded' : 'ungraded';
                                    }

                                    $mappedAnswers = [];
                                    if ($submission && $assignment->assignment_type == 'quiz') {
                                        $mappedAnswers = $submission->answers->map(function($ans) {
                                            return [
                                                "question_text" => $ans->question ? $ans->question->question_text : "Soal telah dihapus guru",
                                                "type" => $ans->question ? $ans->question->question_type : "deleted",
                                                "student_answer" => $ans->answer_text,
                                                "points" => $ans->points,
                                                "max_points" => $ans->question ? $ans->question->points : 0,
                                                "correct_answer" => $ans->question ? $ans->question->correct_answer : null
                                            ];
                                        })->values()->toArray();
                                    }
                                @endphp

                                <tr class="group hover:bg-slate-900/50 transition-colors student-row" data-class="{{ $student->schoolClass->name ?? '-' }}" data-status="{{ $rowStatus }}">
                                    <!-- Siswa -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-sky-500/20 text-sky-300 border border-sky-400/30 flex items-center justify-center font-black text-sm shadow-sm shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-white text-base group-hover:text-sky-300 transition-colors">{{ $student->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-slate-800 text-slate-300 border border-white/10">
                                            {{ $student->schoolClass->name ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 text-center">
                                        @if($submission)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide {{ $isLate ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }} shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isLate ? 'bg-amber-400' : 'bg-emerald-400' }}"></span> 
                                                {{ $isLate ? 'Late' : 'On Time' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide bg-slate-800 text-slate-400 border border-white/10 shadow-sm">
                                                Belum
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Jawaban -->
                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <div class="flex flex-col gap-2">
                                                @if($assignment->assignment_type == 'quiz')
                                                    <button type="button" 
                                                            @click="openReview('{{ addslashes($student->name) }}', {{ json_encode($mappedAnswers) }}, {{ $submission->id }})"
                                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900/80 text-sky-300 border border-white/10 hover:border-sky-400/50 hover:bg-slate-800 rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/btn active:scale-95">
                                                        <i class="ph-bold ph-eye text-lg"></i>
                                                        Koreksi
                                                        @if($ansCount == 0)
                                                            <span class="ml-1 px-2 py-0.5 rounded-md bg-rose-500/30 text-rose-300 border border-rose-500/40 text-[9px] shadow-sm uppercase tracking-wider">Kosong</span>
                                                        @else
                                                            <span class="ml-1 px-2 py-0.5 rounded-md bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 text-[9px] shadow-sm">{{ $ansCount }} Jawaban</span>
                                                        @endif
                                                    </button>
                                                
                                                @elseif($submission->link_url)
                                                    <a href="{{ $submission->link_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900/80 border border-white/10 text-amber-300 hover:bg-slate-800 hover:border-amber-400/50 rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/link active:scale-95">
                                                        <i class="ph-bold ph-link text-lg"></i>
                                                        Buka Link
                                                    </a>

                                                @elseif($submission->file_path)
                                                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900/80 border border-white/10 hover:border-sky-400/50 hover:bg-slate-800 text-sky-300 rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/file active:scale-95">
                                                        <i class="ph-bold ph-file-text text-lg text-slate-400 group-hover/file:text-sky-300"></i>
                                                        Lihat File
                                                    </a>
                                                @endif

                                                @if($submission->student_note)
                                                    <div class="bg-slate-900/90 p-3 rounded-xl border border-white/10 text-xs text-sky-200 italic relative w-fit max-w-[220px] mt-1">
                                                        <i class="ph-fill ph-quotes text-sky-400/20 text-xl absolute -top-2 -left-1"></i>
                                                        <span class="relative z-10 font-medium leading-relaxed">"{{ Str::limit($submission->student_note, 50) }}"</span>
                                                    </div>
                                                @elseif(!$submission->link_url && !$submission->file_path && $assignment->assignment_type != 'quiz')
                                                    <span class="text-xs text-slate-400 italic font-medium">Tanpa lampiran.</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-600 text-2xl ml-2"><i class="ph-duotone ph-minus-circle"></i></span>
                                        @endif
                                    </td>

                                    <!-- Nilai -->
                                    <td class="px-6 py-4 text-center">
                                        @if($submission)
                                            <form action="{{ route('lms.submissions.grade', $submission->id) }}" method="POST" class="contents grade-form" id="form-grade-{{$submission->id}}">
                                                @csrf
                                                @if($assignment->assignment_type == 'quiz')
                                                    @php
                                                        $autoScore = $submission->answers->sum(fn($ans) => $ans->is_correct ? $ans->points : 0);
                                                    @endphp
                                                    <div class="text-[10px] text-slate-400 mb-1.5 font-bold uppercase tracking-widest">
                                                        PG: <span class="text-sky-400">{{ $autoScore }}</span>
                                                    </div>
                                                @endif
                                                <input type="number" name="grade" id="grade_input_{{ $submission->id }}"
                                                       value="{{ $submission->grade }}" 
                                                       class="w-20 text-center rounded-xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-sky-400 focus:ring-sky-400/20 text-base font-black text-white py-2.5 shadow-sm transition-colors" placeholder="0">
                                            </form>
                                        @else
                                            <span class="text-slate-600 font-black text-lg">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <input type="text" form="form-grade-{{$submission->id}}" name="teacher_feedback" value="{{ $submission->teacher_feedback }}" class="w-full text-sm font-bold text-white placeholder:text-slate-500 rounded-xl border-white/10 py-3 px-4 bg-slate-900/80 focus:bg-slate-900 focus:border-sky-400 focus:ring-sky-400/20 shadow-sm transition-colors" placeholder="Ketik feedback...">
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if($submission)
                                            @if($assignment->assignment_type == 'quiz')
                                                <a href="{{ route('lms.submissions.detail', $submission->id) }}" class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-500/30 text-blue-300 inline-flex items-center justify-center shadow-sm hover:bg-blue-500/30 transition-all active:scale-95 mr-1" title="Lihat Analisis Detail">
                                                    <i class="ph-bold ph-chart-bar text-lg"></i>
                                                </a>
                                            @endif

                                            <button type="button" onclick="document.getElementById('form-grade-{{$submission->id}}').submit()" class="w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-500/30 text-sky-300 inline-flex items-center justify-center shadow-sm hover:bg-sky-500/30 transition-all active:scale-95" title="Simpan Individu">
                                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                            </button>
                                            
                                            <form action="{{ route('lms.submissions.destroy', $submission->id) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Hapus data jawaban siswa ini? Siswa harus mengerjakan ulang.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/30 inline-flex items-center justify-center hover:bg-rose-500/30 transition-colors shadow-sm active:scale-95" title="Hapus Jawaban">
                                                    <i class="ph-bold ph-trash text-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL REVIEW JAWABAN MENGGUNAKAN X-TELEPORT --}}
        <template x-teleport="body">
            <div x-show="showReviewModal" style="display: none;" class="relative z-[9999]" role="dialog" aria-modal="true">
                
                {{-- BACKDROP --}}
                <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" 
                     x-show="showReviewModal"
                     x-transition.opacity.duration.300ms
                     @click="showReviewModal = false"></div>

                {{-- WRAPPER SCROLL VERTICAL & FLEX CENTERING --}}
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 md:p-8">
                        
                        {{-- MODAL PANEL --}}
                        <div class="relative bg-[#021124] rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all w-full sm:max-w-3xl border border-white/10 text-white flex flex-col max-h-[90vh] md:max-h-[85vh]"
                             x-show="showReviewModal"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             @click.stop>
                            
                            {{-- Header Modal --}}
                            <div class="bg-slate-900/90 px-6 py-5 border-b border-white/10 flex justify-between items-center shrink-0">
                                <div>
                                    <h3 class="text-xl font-black text-white flex items-center gap-2">
                                        <i class="ph-bold ph-check-square-offset text-sky-400"></i> Koreksi Jawaban
                                    </h3>
                                    <p class="text-xs text-slate-400 font-bold mt-1" x-text="'Siswa: ' + (activeReview ? activeReview.student_name : '-')"></p>
                                </div>
                                <button @click="showReviewModal = false" class="bg-slate-800 border border-white/10 rounded-full p-2 text-slate-400 hover:text-white hover:bg-slate-700 transition-colors shadow-sm active:scale-95">
                                    <i class="ph-bold ph-x text-lg"></i>
                                </button>
                            </div>

                            {{-- Isi Jawaban --}}
                            <div class="px-6 py-6 overflow-y-auto bg-[#021124] space-y-6 custom-scrollbar flex-1">
                                <template x-if="activeReview && activeReview.answers.length > 0">
                                    <template x-for="(ans, index) in activeReview.answers" :key="index">
                                        <div class="bg-slate-900/60 border border-white/10 rounded-2xl p-5 shadow-sm hover:border-sky-400/40 transition-all">
                                            <div class="flex gap-4 mb-4">
                                                <span class="bg-sky-500/20 text-sky-300 w-8 h-8 flex items-center justify-center rounded-xl font-black text-sm shrink-0 border border-sky-400/30" x-text="index + 1"></span>
                                                <p class="text-sm font-bold text-white pt-1.5" x-text="ans.question_text"></p>
                                            </div>
                                            <div class="pl-12 space-y-3">
                                                <div class="bg-slate-900/90 p-4 rounded-xl border border-white/10">
                                                    <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-1.5">Jawaban Siswa</p>
                                                    <p class="text-sm text-slate-200 font-medium whitespace-pre-line leading-relaxed" x-text="ans.student_answer ? ans.student_answer : '(Kosong)'"></p>
                                                </div>

                                                {{-- JIKA ESSAI --}}
                                                <template x-if="ans.type === 'essay'">
                                                    <div class="p-4 bg-amber-500/10 rounded-xl border border-amber-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                        <div>
                                                            <p class="text-[10px] font-bold text-amber-300 uppercase tracking-wider mb-1"><i class="ph-bold ph-pencil-simple"></i> Koreksi Manual</p>
                                                            <p class="text-xs text-amber-200/80 font-bold">Baca jawaban, lalu input poin yang sesuai.</p>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-xs font-black text-amber-300 uppercase">Poin:</span>
                                                            <input type="number" x-model.number="ans.score" min="0" :max="ans.max_points"
                                                                   class="w-20 text-center rounded-xl border-amber-500/30 focus:ring-amber-400 focus:border-amber-400 bg-slate-900 text-base font-black text-amber-300 shadow-sm py-2">
                                                            <span class="text-sm font-black text-amber-200/60">/ <span x-text="ans.max_points"></span></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </template>

                                {{-- JIKA DATA KOSONG --}}
                                <template x-if="!activeReview || activeReview.answers.length === 0">
                                    <div class="flex flex-col items-center justify-center py-12 text-center border-2 border-dashed border-white/10 rounded-[2rem] bg-slate-900/40">
                                        <div class="w-20 h-20 bg-rose-500/20 text-rose-300 rounded-full flex items-center justify-center mb-4 text-4xl border border-rose-500/30 shadow-sm">
                                            <i class="ph-duotone ph-warning-circle"></i>
                                        </div>
                                        <h4 class="font-black text-xl text-white">Data Jawaban Kosong</h4>
                                        <p class="text-sm text-slate-400 max-w-sm mx-auto mt-2 font-medium leading-relaxed">Siswa ini melakukan submit sebelum sistem diperbarui, atau terjadi kesalahan database. Harap hapus submission ini dan minta siswa mengerjakan ulang.</p>
                                    </div>
                                </template>
                            </div>

                            {{-- Footer Kalkulator --}}
                            <div class="bg-slate-900/90 px-6 py-5 border-t border-white/10 shrink-0" x-show="activeReview && activeReview.answers.length > 0">
                                <div class="flex flex-col sm:flex-row justify-between items-center gap-5">
                                    <div class="text-slate-400 text-xs font-bold flex items-center gap-2">
                                        <i class="ph-fill ph-info text-sky-400 text-lg"></i> 
                                        <span>Poin dihitung otomatis dari <br class="sm:hidden">(PG + Input Esai).</span>
                                    </div>
                                    <div class="flex items-center gap-5 w-full sm:w-auto">
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest">Total Nilai</p>
                                            <p class="text-3xl font-black text-white" x-text="calculateTotal()"></p>
                                        </div>
                                        <button type="button" @click="applyToTable()"
                                                class="flex-1 sm:flex-none px-6 py-3.5 bg-gradient-to-r from-sky-400 to-blue-600 text-slate-950 font-bold rounded-2xl shadow-lg shadow-sky-500/20 hover:from-sky-300 hover:to-blue-500 transition-all flex items-center justify-center gap-2 active:scale-95">
                                            <i class="ph-bold ph-check-circle text-lg"></i> Terapkan Nilai
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('submissionGrading', () => ({
                showReviewModal: false,
                activeReview: null,

                openReview(studentName, answers, submissionId) {
                    this.activeReview = {
                        student_name: studentName,
                        submission_id: submissionId,
                        answers: answers.map(a => ({ ...a, score: a.points }))
                    };
                    this.showReviewModal = true;
                },

                calculateTotal() {
                    if(!this.activeReview) return 0;
                    return this.activeReview.answers.reduce((sum, a) => sum + (parseInt(a.score) || 0), 0);
                },

                applyToTable() {
                    const totalScore = this.calculateTotal();
                    const inputField = document.getElementById('grade_input_' + this.activeReview.submission_id);
                    if(inputField) {
                        inputField.value = totalScore;
                        inputField.classList.add('ring-4', 'ring-sky-400/40', 'bg-slate-900');
                        setTimeout(() => inputField.classList.remove('ring-4', 'ring-sky-400/40', 'bg-slate-900'), 1000);
                    }
                    this.showReviewModal = false;
                }
            }));
        });
        
        // FUNGSI SIMPAN SEMUA
        window.saveAllGrades = async function() {
            const forms = document.querySelectorAll('.grade-form');
            if (forms.length === 0) {
                Swal.fire({ 
                    icon: 'info', 
                    title: 'Kosong', 
                    text: 'Tidak ada data jawaban siswa yang bisa dinilai saat ini.',
                    background: '#021124',
                    color: '#fff',
                    customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Simpan Semua Nilai?',
                text: `Sistem akan memproses dan menyimpan nilai dari ${forms.length} siswa secara bersamaan.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#38bdf8',
                cancelButtonColor: '#475569',
                confirmButtonText: '<i class="ph-bold ph-check mr-2"></i> Ya, Simpan Semua!',
                cancelButtonText: 'Batal',
                background: '#021124',
                color: '#fff',
                customClass: { 
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl', 
                    confirmButton: 'rounded-xl px-6 py-3 font-bold shadow-sm text-slate-950', 
                    cancelButton: 'rounded-xl px-6 py-3 font-bold shadow-sm hover:bg-slate-800 text-slate-300' 
                }
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Memproses Data...',
                html: 'Menyimpan nilai ke server: <b>0</b>%',
                allowOutsideClick: false,
                background: '#021124',
                color: '#fff',
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
            });

            let successCount = 0;
            let errorCount = 0;
            let total = forms.length;
            let processed = 0;
            const swalHtml = Swal.getHtmlContainer().querySelector('b');

            for (const form of forms) {
                try {
                    const formData = new FormData(form);
                    
                    const feedbackInput = document.querySelector(`input[name="teacher_feedback"][form="${form.id}"]`);
                    if (feedbackInput && !formData.has('teacher_feedback')) {
                        formData.append('teacher_feedback', feedbackInput.value);
                    }

                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        successCount++;
                    } else {
                        errorCount++;
                    }
                } catch (err) {
                    console.error('Error saving form:', form.id, err);
                    errorCount++;
                }
                
                processed++;
                if(swalHtml) {
                    swalHtml.textContent = Math.round((processed / total) * 100);
                }
            }

            Swal.fire({
                icon: errorCount === 0 ? 'success' : 'warning',
                title: 'Selesai!',
                text: `Berhasil menyimpan ${successCount} nilai. ${errorCount > 0 ? `Gagal: ${errorCount} data.` : ''}`,
                background: '#021124',
                color: '#fff',
                customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
            }).then(() => {
                if(successCount > 0) window.location.reload();
            });
        };

        // Filter Tabel
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tableSearch');
            const classFilter = document.getElementById('classFilter');
            const statusFilter = document.getElementById('statusFilter');
            const tableRows = document.querySelectorAll('.student-row');
            
            function applyFilters() {
                const searchVal = searchInput ? searchInput.value.toLowerCase() : '';
                const classVal = classFilter ? classFilter.value : '';
                const statusVal = statusFilter ? statusFilter.value : '';

                tableRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    const rowClass = row.getAttribute('data-class');
                    const rowStatus = row.getAttribute('data-status');
                    
                    const matchesSearch = textContent.includes(searchVal);
                    const matchesClass = classVal === '' || rowClass === classVal;
                    const matchesStatus = statusVal === '' || rowStatus === statusVal;

                    if (matchesSearch && matchesClass && matchesStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            if (searchInput) {
                searchInput.addEventListener('keyup', applyFilters);
            }
            if (classFilter) {
                classFilter.addEventListener('change', applyFilters);
            }
            if (statusFilter) {
                statusFilter.addEventListener('change', applyFilters);
            }
        });
    </script>
</x-app-layout>