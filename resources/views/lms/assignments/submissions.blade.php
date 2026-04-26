<x-app-layout>
    {{-- Header Judul --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Penilaian Tugas') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108); transform: translateY(-2px); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    {{-- WRAPPER ALPINE JS UTAMA --}}
    <div class="py-8 font-sans text-slate-800 pb-20" 
         x-data="submissionGrading()">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION ELEVATE --}}
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 mb-8 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/30 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-white/40 border border-white/50 text-[#2A3B52] backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-tag mr-1.5"></i>
                                {{ str_replace('_', ' ', $assignment->assignment_type) }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-white/40 border border-white/50 text-[#2A3B52] backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-users-three mr-1.5"></i>
                                @if($assignment->is_bulk)
                                    Semua Kelas {{ $assignment->target_grade }}
                                @else
                                    {{ $assignment->schoolClass->name ?? 'Semua Kelas' }}
                                @endif
                            </span>
                        </div>

                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-[#2A3B52] leading-tight">
                            {{ $assignment->title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-[#2A3B52]/80 text-sm font-bold">
                            <span class="flex items-center gap-1.5 bg-white/20 px-3 py-1.5 rounded-lg border border-white/30 shadow-sm">
                                <i class="ph-bold ph-book-open text-[#2A3B52]"></i> {{ $assignment->subject->name }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-[#FDE7E9]/80 px-3 py-1.5 rounded-lg border border-[#F4C3C9] text-[#D13438] shadow-sm">
                                <i class="ph-bold ph-clock"></i> Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    <a href="{{ route('lms.assignments.index') }}" class="group bg-white/40 hover:bg-white/60 text-[#2A3B52] px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm active:scale-95">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            {{-- TABEL SISWA --}}
            <div class="animate-enter bg-white rounded-xl fluent-card overflow-hidden" style="animation-delay: 100ms">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-[#2A3B52] text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-checks text-[#5295FF]"></i> Daftar Pengumpulan
                    </h3>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
                        @if($assignment->is_bulk || $allStudents->count() > 30)
                            <div class="relative w-full sm:w-48">
                                <select id="classFilter" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-[#5295FF] focus:border-[#5295FF] shadow-sm transition-all appearance-none cursor-pointer text-[#2A3B52]">
                                    <option value="">Semua Kelas</option>
                                    @foreach($allStudents->pluck('schoolClass.name')->unique()->sort() as $className)
                                        <option value="{{ $className }}">{{ $className }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        @endif

                        <div class="relative w-full sm:w-64">
                            <input type="text" id="tableSearch" placeholder="Cari nama siswa..." class="pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-[#5295FF] focus:border-[#5295FF] w-full shadow-sm transition-all text-[#2A3B52]">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-magnifying-glass"></i></div>
                        </div>

                        {{-- TOMBOL SIMPAN SEMUA --}}
                        <button type="button" onclick="saveAllGrades()" class="w-full sm:w-auto px-5 py-2.5 bg-[#2A3B52] hover:bg-[#182436] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-md transition-all group active:scale-95 border border-transparent">
                            <i class="ph-bold ph-floppy-disk text-lg"></i>
                            Simpan Semua
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="submissionsTable">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Jawaban</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Nilai Final</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Feedback</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($allStudents as $student)
                                @php
                                    $submission = $submissions->get($student->id); 
                                    $isLate = false;
                                    if($submission && $submission->submitted_at > $assignment->deadline) {
                                        $isLate = true;
                                    }
                                    $ansCount = $submission ? $submission->answers->count() : 0;
                                    
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

                                <tr class="group hover:bg-slate-50/80 transition-colors student-row" data-class="{{ $student->schoolClass->name ?? '-' }}">
                                    <!-- Siswa -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center font-bold text-xs shadow-sm shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-[#2A3B52] text-sm group-hover:text-[#5295FF] transition-colors">{{ $student->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $student->schoolClass->name ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 text-center">
                                        @if($submission)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide {{ $isLate ? 'bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8]' : 'bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9]' }} shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isLate ? 'bg-[#D83B01]' : 'bg-[#107C10]' }}"></span> 
                                                {{ $isLate ? 'Late' : 'On Time' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-slate-100 text-slate-400 border border-slate-200 shadow-sm">
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
                                                            class="inline-flex items-center gap-2 px-3 py-2 bg-white text-[#5295FF] border border-slate-200 hover:border-[#D0E7F8] hover:bg-[#F3F9FD] rounded-lg text-xs font-bold transition-all w-fit shadow-sm group/btn active:scale-95">
                                                        <i class="ph-bold ph-eye text-lg"></i>
                                                        Koreksi
                                                        @if($ansCount == 0)
                                                            <span class="ml-1 px-1.5 py-0.5 rounded bg-[#D13438] text-white text-[9px] shadow-sm">Kosong</span>
                                                        @else
                                                            <span class="ml-1 px-1.5 py-0.5 rounded bg-[#107C10] text-white text-[9px] shadow-sm">{{ $ansCount }}</span>
                                                        @endif
                                                    </button>
                                                
                                                @elseif($submission->link_url)
                                                    <a href="{{ $submission->link_url }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 text-[#5295FF] hover:bg-[#F3F9FD] hover:border-[#D0E7F8] rounded-lg text-xs font-bold transition-all w-fit shadow-sm group/link active:scale-95">
                                                        <i class="ph-bold ph-link text-lg"></i>
                                                        Buka Link
                                                    </a>

                                                @elseif($submission->file_path)
                                                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-[#D0E7F8] hover:bg-[#F3F9FD] hover:text-[#5295FF] rounded-lg text-xs font-bold transition-all w-fit shadow-sm text-slate-600 group/file active:scale-95">
                                                        <i class="ph-bold ph-file-text text-lg text-slate-400 group-hover/file:text-[#5295FF]"></i>
                                                        Lihat File
                                                    </a>
                                                @endif

                                                @if($submission->student_note)
                                                    <div class="bg-[#F3F9FD] p-2.5 rounded-xl border border-[#D0E7F8] text-xs text-[#5295FF] italic relative w-fit max-w-[200px]">
                                                        <i class="ph-fill ph-quotes text-[#5295FF]/20 text-xl absolute -top-2 -left-1"></i>
                                                        <span class="relative z-10 font-medium">"{{ Str::limit($submission->student_note, 40) }}"</span>
                                                    </div>
                                                @elseif(!$submission->link_url && !$submission->file_path && $assignment->assignment_type != 'quiz')
                                                    <span class="text-xs text-slate-400 italic">Tanpa lampiran.</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-2xl ml-2"><i class="ph-duotone ph-minus-circle"></i></span>
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
                                                    <div class="text-[10px] text-slate-400 mb-1 font-bold">
                                                        PG: <span class="text-[#5295FF]">{{ $autoScore }}</span>
                                                    </div>
                                                @endif
                                                <input type="number" name="grade" id="grade_input_{{ $submission->id }}"
                                                       value="{{ $submission->grade }}" 
                                                       class="w-16 text-center rounded-lg border-slate-200 bg-white focus:border-[#5295FF] focus:ring-[#5295FF] text-sm font-black text-[#2A3B52] h-10 shadow-sm transition-colors" placeholder="0">
                                            </form>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <input type="text" form="form-grade-{{$submission->id}}" name="teacher_feedback" value="{{ $submission->teacher_feedback }}" class="w-full text-xs rounded-lg border-slate-200 h-10 px-3 bg-white focus:border-[#5295FF] focus:ring-[#5295FF] shadow-sm transition-colors" placeholder="Feedback...">
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        @if($submission)
                                            <button type="button" onclick="document.getElementById('form-grade-{{$submission->id}}').submit()" class="w-9 h-9 rounded-lg bg-white border border-slate-200 text-[#5295FF] flex items-center justify-center shadow-sm hover:bg-[#F3F9FD] hover:border-[#D0E7F8] transition active:scale-95" title="Simpan Individu">
                                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                            </button>
                                            
                                            <form action="{{ route('lms.submissions.destroy', $submission->id) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Hapus data jawaban siswa ini? Siswa harus mengerjakan ulang.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-9 h-9 rounded-lg bg-white text-[#D13438] border border-slate-200 flex items-center justify-center hover:bg-[#FDE7E9] hover:border-[#F4C3C9] transition-colors shadow-sm active:scale-95" title="Hapus Jawaban">
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

        {{-- MODAL REVIEW JAWABAN (ELEVATE THEME) --}}
        <div x-show="showReviewModal" style="display: none;" 
             class="fixed inset-0 z-[999] overflow-y-auto" role="dialog" aria-modal="true">
             <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" @click="showReviewModal = false"></div>

                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-slate-200 fluent-modal">
                    
                    {{-- Header Modal --}}
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center sticky top-0 z-10">
                        <div>
                            <h3 class="text-lg leading-6 font-black text-[#2A3B52]">Koreksi Jawaban</h3>
                            <p class="text-xs text-slate-500 font-bold" x-text="'Siswa: ' + (activeReview ? activeReview.student_name : '-')"></p>
                        </div>
                        <button @click="showReviewModal = false" class="bg-white border border-slate-200 rounded-lg p-2 text-slate-400 hover:text-[#D13438] hover:bg-[#FDE7E9] transition-colors shadow-sm active:scale-95">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    {{-- Isi Jawaban --}}
                    <div class="px-6 py-6 max-h-[60vh] overflow-y-auto bg-white space-y-6">
                        <template x-if="activeReview && activeReview.answers.length > 0">
                            <template x-for="(ans, index) in activeReview.answers" :key="index">
                                <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex gap-3 mb-3">
                                        <span class="bg-[#F3F9FD] border border-[#D0E7F8] text-[#5295FF] w-6 h-6 flex items-center justify-center rounded-lg font-bold text-xs shrink-0" x-text="index + 1"></span>
                                        <p class="text-sm font-bold text-[#2A3B52]" x-text="ans.question_text"></p>
                                    </div>
                                    <div class="pl-9 space-y-3">
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Jawaban Siswa</p>
                                            <p class="text-sm text-[#2A3B52] font-medium whitespace-pre-line" x-text="ans.student_answer ? ans.student_answer : '(Kosong)'"></p>
                                        </div>

                                        {{-- JIKA ESSAI (Input Manual Guru) --}}
                                        <template x-if="ans.type === 'essay'">
                                            <div class="p-4 bg-[#FFEFD6] rounded-xl border border-[#FFD8A8] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                <div>
                                                    <p class="text-[10px] font-bold text-[#D83B01] uppercase mb-1"><i class="ph-bold ph-pencil-simple"></i> Koreksi Manual</p>
                                                    <p class="text-xs text-[#D83B01]/80 font-medium">Baca jawaban, lalu input poin yang sesuai.</p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-[#D83B01]">Poin:</span>
                                                    <input type="number" x-model.number="ans.score" min="0" :max="ans.max_points"
                                                           class="w-20 text-center rounded-lg border-[#FFD8A8] focus:ring-[#D83B01] focus:border-[#D83B01] text-sm font-bold shadow-sm">
                                                    <span class="text-xs font-bold text-[#D83B01]">/ <span x-text="ans.max_points"></span></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </template>

                        {{-- JIKA DATA KOSONG --}}
                        <template x-if="!activeReview || activeReview.answers.length === 0">
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <div class="w-16 h-16 bg-[#FDE7E9] text-[#D13438] rounded-full flex items-center justify-center mb-4 text-3xl">
                                    <i class="ph-duotone ph-warning-circle"></i>
                                </div>
                                <h4 class="font-bold text-[#2A3B52]">Data Jawaban Tidak Ditemukan</h4>
                                <p class="text-sm text-slate-500 max-w-xs mt-2">Siswa ini melakukan submit sebelum sistem diperbarui, atau terjadi kesalahan database. Harap hapus submission ini dan minta siswa mengerjakan ulang.</p>
                            </div>
                        </template>
                    </div>

                    {{-- Footer Kalkulator --}}
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 sticky bottom-0 z-10" x-show="activeReview && activeReview.answers.length > 0">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-slate-600 text-xs font-medium">
                                <i class="ph-fill ph-info text-[#5295FF]"></i> Poin dihitung otomatis dari (PG + Input Esai).
                            </div>
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Total Nilai Akhir</p>
                                    <p class="text-2xl font-black text-[#5295FF]" x-text="calculateTotal()"></p>
                                </div>
                                <button type="button" @click="applyToTable()"
                                        class="flex-1 sm:flex-none px-6 py-3 bg-[#2A3B52] text-white font-bold rounded-xl text-sm shadow-md hover:bg-[#182436] transition-all flex items-center justify-center gap-2 active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-check-circle text-lg"></i> Terapkan Nilai
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        inputField.classList.add('ring-4', 'ring-[#D0E7F8]', 'bg-[#F3F9FD]');
                        setTimeout(() => inputField.classList.remove('ring-4', 'ring-[#D0E7F8]', 'bg-[#F3F9FD]'), 1000);
                    }
                    this.showReviewModal = false;
                }
            }));
        });
        
        // FUNGSI SIMPAN SEMUA (BULK SAVE dengan AJAX)
        window.saveAllGrades = async function() {
            const forms = document.querySelectorAll('.grade-form');
            if (forms.length === 0) {
                Swal.fire({ 
                    icon: 'info', 
                    title: 'Kosong', 
                    text: 'Tidak ada data jawaban siswa yang bisa dinilai saat ini.',
                    customClass: { popup: 'rounded-xl fluent-modal font-sans border-0' }
                });
                return;
            }

            // 1. Minta konfirmasi
            const result = await Swal.fire({
                title: 'Simpan Semua Nilai?',
                text: `Sistem akan memproses dan menyimpan nilai dari ${forms.length} siswa secara bersamaan.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2A3B52', // Navy Elevate
                cancelButtonColor: '#D13438', // Red Elevate
                confirmButtonText: '<i class="ph-bold ph-check"></i> Ya, Simpan Semua!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-xl fluent-modal font-sans border-0', confirmButton: 'rounded-lg px-5 py-2.5 font-bold shadow-sm', cancelButton: 'rounded-lg px-5 py-2.5 font-bold shadow-sm' }
            });

            if (!result.isConfirmed) return;

            // 2. Munculkan Loading Bar Progress
            Swal.fire({
                title: 'Memproses Data...',
                html: 'Menyimpan nilai ke server: <b>0</b>%',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: { popup: 'rounded-xl fluent-modal font-sans border-0' }
            });

            let successCount = 0;
            let errorCount = 0;
            let total = forms.length;
            let processed = 0;
            const swalHtml = Swal.getHtmlContainer().querySelector('b');

            // 3. Eksekusi pengiriman data satu persatu agar tidak overload server
            for (const form of forms) {
                try {
                    const formData = new FormData(form);
                    
                    // Ambil input feedback yang posisinya di luar <form>
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
                
                // Update text persentase
                processed++;
                if(swalHtml) {
                    swalHtml.textContent = Math.round((processed / total) * 100);
                }
            }

            // 4. Tampilkan Notifikasi Selesai
            Swal.fire({
                icon: errorCount === 0 ? 'success' : 'warning',
                title: 'Selesai!',
                text: `Berhasil menyimpan ${successCount} nilai. ${errorCount > 0 ? `Gagal: ${errorCount} data.` : ''}`,
                customClass: { popup: 'rounded-xl fluent-modal font-sans border-0' }
            }).then(() => {
                // Refresh halaman untuk memperbarui session / status
                if(successCount > 0) window.location.reload();
            });
        };

        // Filter Tabel
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tableSearch');
            const classFilter = document.getElementById('classFilter');
            const tableRows = document.querySelectorAll('.student-row');
            
            function applyFilters() {
                const searchVal = searchInput ? searchInput.value.toLowerCase() : '';
                const classVal = classFilter ? classFilter.value : '';

                tableRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    const rowClass = row.getAttribute('data-class');
                    
                    const matchesSearch = textContent.includes(searchVal);
                    const matchesClass = classVal === '' || rowClass === classVal;

                    if (matchesSearch && matchesClass) {
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
        });
    </script>
</x-app-layout>