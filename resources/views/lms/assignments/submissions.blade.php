<x-app-layout>
    {{-- Header Judul --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Penilaian Tugas') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    {{-- WRAPPER ALPINE JS UTAMA --}}
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20" 
         x-data="submissionGrading()">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION ELEVATE --}}
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-white/60 border border-white/60 text-elevate-dark backdrop-blur-md shadow-sm">
                                <i class="ph-bold ph-tag mr-1.5"></i>
                                {{ str_replace('_', ' ', $assignment->assignment_type) }}
                            </span>
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-white/60 border border-white/60 text-elevate-dark backdrop-blur-md shadow-sm">
                                <i class="ph-bold ph-users-three mr-1.5"></i>
                                @if($assignment->is_bulk)
                                    Semua Kelas {{ $assignment->target_grade }}
                                @else
                                    {{ $assignment->schoolClass->name ?? 'Semua Kelas' }}
                                @endif
                            </span>
                        </div>

                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 text-elevate-dark leading-tight">
                            {{ $assignment->title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-elevate-dark/80 text-sm font-bold">
                            <span class="flex items-center gap-1.5 bg-white/40 backdrop-blur px-4 py-2 rounded-xl border border-white/50 shadow-sm">
                                <i class="ph-bold ph-book-open text-elevate-dark"></i> {{ $assignment->subject->name }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-[#FDE7E9]/90 backdrop-blur px-4 py-2 rounded-xl border border-[#F4C3C9] text-[#D13438] shadow-sm">
                                <i class="ph-bold ph-clock"></i> Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    <a href="{{ route('lms.assignments.index') }}" class="group/btn shrink-0 bg-white/60 hover:bg-white text-elevate-dark px-6 py-3.5 rounded-xl font-bold text-sm backdrop-blur-md border border-white/60 transition-all flex items-center gap-2 shadow-sm active:scale-95">
                        <i class="ph-bold ph-arrow-left text-lg group-hover/btn:-translate-x-1 transition-transform"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            {{-- TABEL SISWA --}}
            <div class="animate-enter bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden min-h-[600px] flex flex-col" style="animation-delay: 100ms">
                
                {{-- PERBAIKAN RESPONSIVE DI SINI --}}
                <div class="p-6 md:p-8 border-b border-slate-100 bg-elevate-gradient-card flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5">
                    <h3 class="font-black text-elevate-dark text-xl flex items-center gap-3 shrink-0">
                        <span class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-elevate-primary shadow-sm border border-slate-100"><i class="ph-bold ph-list-checks"></i></span>
                        Daftar Pengumpulan
                    </h3>
                    
                    <div class="flex flex-wrap gap-3 w-full xl:w-auto items-center justify-start xl:justify-end">
                        @if($assignment->is_bulk || $allStudents->count() > 30)
                            <div class="relative w-full sm:w-[calc(50%-0.375rem)] md:w-auto md:flex-1 xl:flex-none xl:w-40 group">
                                <select id="classFilter" class="w-full pl-4 pr-10 py-3.5 bg-elevate-soft focus:bg-white border-slate-200 rounded-2xl text-sm font-bold focus:ring-elevate-accent/30 focus:border-elevate-accent transition-all appearance-none cursor-pointer text-elevate-dark shadow-sm">
                                    <option value="">Semua Kelas</option>
                                    @foreach($allStudents->pluck('schoolClass.name')->unique()->sort() as $className)
                                        <option value="{{ $className }}">{{ $className }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        @endif

                        {{-- Filter Status Penilaian --}}
                        <div class="relative w-full sm:w-[calc(50%-0.375rem)] md:w-auto md:flex-1 xl:flex-none xl:w-40 group">
                            <select id="statusFilter" class="w-full pl-4 pr-10 py-3.5 bg-elevate-soft focus:bg-white border-slate-200 rounded-2xl text-sm font-bold focus:ring-elevate-accent/30 focus:border-elevate-accent transition-all appearance-none cursor-pointer text-elevate-dark shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="ungraded">Menunggu Dinilai</option>
                                <option value="graded">Sudah Dinilai</option>
                                <option value="missing">Belum Kumpul</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down"></i></div>
                        </div>

                        <div class="relative w-full sm:flex-1 md:w-auto xl:flex-none xl:w-56 group">
                            <input type="text" id="tableSearch" placeholder="Cari nama siswa..." class="pl-12 pr-4 py-3.5 bg-elevate-soft focus:bg-white border border-slate-200 rounded-2xl text-sm font-bold focus:ring-elevate-accent/30 focus:border-elevate-accent w-full transition-all text-elevate-dark shadow-sm">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary"><i class="ph-bold ph-magnifying-glass text-lg"></i></div>
                        </div>

                        {{-- TOMBOL SIMPAN SEMUA --}}
                        <button type="button" onclick="saveAllGrades()" class="w-full sm:w-auto px-6 py-3.5 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl text-sm flex items-center justify-center gap-2 shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 border border-transparent shrink-0">
                            <i class="ph-bold ph-floppy-disk text-lg"></i>
                            <span class="whitespace-nowrap">Simpan Semua</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1 custom-scrollbar">
                    <table class="w-full text-left border-collapse" id="submissionsTable">
                        <thead class="bg-elevate-soft/50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black text-elevate-primary uppercase tracking-wider w-1/4">Siswa</th>
                                <th class="px-6 py-5 text-xs font-black text-elevate-primary uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-5 text-xs font-black text-elevate-primary uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-5 text-xs font-black text-elevate-primary uppercase tracking-wider w-1/4">Jawaban</th>
                                <th class="px-6 py-5 text-xs font-black text-elevate-primary uppercase tracking-wider text-center">Nilai Final</th>
                                <th class="px-6 py-5 text-xs font-black text-elevate-primary uppercase tracking-wider w-1/4">Feedback</th>
                                <th class="px-6 py-5 text-xs font-black text-elevate-primary uppercase tracking-wider text-right">Aksi</th>
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
                                    
                                    // Logika status untuk filter javascript
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

                                {{-- TAMBAHAN: Menyisipkan atribut data-status --}}
                                <tr class="group hover:bg-elevate-soft/30 transition-colors student-row" data-class="{{ $student->schoolClass->name ?? '-' }}" data-status="{{ $rowStatus }}">
                                    <!-- Siswa -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-elevate-peach-light/50 text-elevate-peach-dark border border-elevate-peach flex items-center justify-center font-black text-sm shadow-sm shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-elevate-dark text-base group-hover:text-elevate-primary transition-colors">{{ $student->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $student->schoolClass->name ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- Status (Warna Semantik Dipertahankan) -->
                                    <td class="px-6 py-4 text-center">
                                        @if($submission)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide {{ $isLate ? 'bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8]' : 'bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9]' }} shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isLate ? 'bg-[#D83B01]' : 'bg-[#107C10]' }}"></span> 
                                                {{ $isLate ? 'Late' : 'On Time' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide bg-slate-100 text-slate-400 border border-slate-200 shadow-sm">
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
                                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-elevate-primary border border-slate-200 hover:border-elevate-accent hover:bg-elevate-soft rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/btn active:scale-95">
                                                        <i class="ph-bold ph-eye text-lg"></i>
                                                        Koreksi
                                                        @if($ansCount == 0)
                                                            <span class="ml-1 px-2 py-0.5 rounded-md bg-[#D13438] text-white text-[9px] shadow-sm uppercase tracking-wider">Kosong</span>
                                                        @else
                                                            <span class="ml-1 px-2 py-0.5 rounded-md bg-[#107C10] text-white text-[9px] shadow-sm">{{ $ansCount }} Jawaban</span>
                                                        @endif
                                                    </button>
                                                
                                                @elseif($submission->link_url)
                                                    <a href="{{ $submission->link_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-elevate-primary hover:bg-elevate-soft hover:border-elevate-accent rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/link active:scale-95">
                                                        <i class="ph-bold ph-link text-lg"></i>
                                                        Buka Link
                                                    </a>

                                                @elseif($submission->file_path)
                                                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-elevate-accent hover:bg-elevate-soft hover:text-elevate-primary rounded-xl text-xs font-bold transition-all w-fit shadow-sm text-slate-600 group/file active:scale-95">
                                                        <i class="ph-bold ph-file-text text-lg text-slate-400 group-hover/file:text-elevate-primary"></i>
                                                        Lihat File
                                                    </a>
                                                @endif

                                                @if($submission->student_note)
                                                    <div class="bg-elevate-soft/50 p-3 rounded-xl border border-slate-200 text-xs text-elevate-primary italic relative w-fit max-w-[220px] mt-1">
                                                        <i class="ph-fill ph-quotes text-elevate-primary/20 text-xl absolute -top-2 -left-1"></i>
                                                        <span class="relative z-10 font-medium leading-relaxed">"{{ Str::limit($submission->student_note, 50) }}"</span>
                                                    </div>
                                                @elseif(!$submission->link_url && !$submission->file_path && $assignment->assignment_type != 'quiz')
                                                    <span class="text-xs text-slate-400 italic font-medium">Tanpa lampiran.</span>
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
                                                    <div class="text-[10px] text-slate-400 mb-1.5 font-bold uppercase tracking-widest">
                                                        PG: <span class="text-elevate-primary">{{ $autoScore }}</span>
                                                    </div>
                                                @endif
                                                <input type="number" name="grade" id="grade_input_{{ $submission->id }}"
                                                       value="{{ $submission->grade }}" 
                                                       class="w-20 text-center rounded-xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-base font-black text-elevate-dark py-2.5 shadow-sm transition-colors" placeholder="0">
                                            </form>
                                        @else
                                            <span class="text-slate-300 font-black text-lg">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <input type="text" form="form-grade-{{$submission->id}}" name="teacher_feedback" value="{{ $submission->teacher_feedback }}" class="w-full text-sm font-bold text-elevate-dark rounded-xl border-slate-200 py-3 px-4 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 shadow-sm transition-colors" placeholder="Ketik feedback...">
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if($submission)
                                            {{-- TOMBOL ANALISIS DETAIL KUIS --}}
                                            @if($assignment->assignment_type == 'quiz')
                                                <a href="{{ route('lms.submissions.detail', $submission->id) }}" class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 inline-flex items-center justify-center shadow-sm hover:bg-blue-600 hover:text-white transition-all active:scale-95 mr-1" title="Lihat Analisis Detail">
                                                    <i class="ph-bold ph-chart-bar text-lg"></i>
                                                </a>
                                            @endif

                                            <button type="button" onclick="document.getElementById('form-grade-{{$submission->id}}').submit()" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-elevate-primary inline-flex items-center justify-center shadow-sm hover:bg-elevate-soft hover:border-elevate-accent/50 transition-all active:scale-95" title="Simpan Individu">
                                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                            </button>
                                            
                                            <form action="{{ route('lms.submissions.destroy', $submission->id) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Hapus data jawaban siswa ini? Siswa harus mengerjakan ulang.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-10 h-10 rounded-xl bg-white text-[#D13438] border border-slate-200 inline-flex items-center justify-center hover:bg-[#FDE7E9] hover:border-[#F4C3C9] transition-colors shadow-sm active:scale-95" title="Hapus Jawaban">
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
             
             {{-- PERBAIKAN 1: Gunakan Flexbox Center alih-alih items-end / inline-block --}}
             <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm transition-opacity" @click="showReviewModal = false"></div>

                {{-- PERBAIKAN 2: Tambahkan flex flex-col dan max-h-[90vh] agar modal menyesuaikan layar --}}
                <div class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-3xl w-full border border-slate-100 flex flex-col max-h-[90vh]">
                    
                    {{-- Header Modal (Dibuat shrink-0 agar tidak menyusut) --}}
                    <div class="bg-elevate-peach-light/30 px-6 py-5 border-b border-elevate-peach/30 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-black text-elevate-dark flex items-center gap-2">
                                <i class="ph-bold ph-check-square-offset text-elevate-peach-dark"></i> Koreksi Jawaban
                            </h3>
                            <p class="text-xs text-elevate-dark/70 font-bold mt-1" x-text="'Siswa: ' + (activeReview ? activeReview.student_name : '-')"></p>
                        </div>
                        <button @click="showReviewModal = false" class="bg-white border border-slate-200 rounded-full p-2 text-slate-400 hover:text-elevate-dark hover:bg-elevate-soft transition-colors shadow-sm active:scale-95">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    {{-- Isi Jawaban (Dibuat flex-1 agar mengisi ruang tengah dan bisa di-scroll) --}}
                    <div class="px-6 py-6 overflow-y-auto bg-white space-y-6 custom-scrollbar flex-1">
                        <template x-if="activeReview && activeReview.answers.length > 0">
                            <template x-for="(ans, index) in activeReview.answers" :key="index">
                                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-elevate-accent/30 transition-all">
                                    <div class="flex gap-4 mb-4">
                                        <span class="bg-elevate-soft text-elevate-primary w-8 h-8 flex items-center justify-center rounded-xl font-black text-sm shrink-0 border border-slate-100" x-text="index + 1"></span>
                                        <p class="text-sm font-bold text-elevate-dark pt-1.5" x-text="ans.question_text"></p>
                                    </div>
                                    <div class="pl-12 space-y-3">
                                        <div class="bg-elevate-surface p-4 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Jawaban Siswa</p>
                                            <p class="text-sm text-elevate-dark font-medium whitespace-pre-line leading-relaxed" x-text="ans.student_answer ? ans.student_answer : '(Kosong)'"></p>
                                        </div>

                                        {{-- JIKA ESSAI (Input Manual Guru) --}}
                                        <template x-if="ans.type === 'essay'">
                                            <div class="p-4 bg-[#FFEFD6] rounded-xl border border-[#FFD8A8] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                <div>
                                                    <p class="text-[10px] font-bold text-[#D83B01] uppercase tracking-wider mb-1"><i class="ph-bold ph-pencil-simple"></i> Koreksi Manual</p>
                                                    <p class="text-xs text-[#D83B01]/80 font-bold">Baca jawaban, lalu input poin yang sesuai.</p>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs font-black text-[#D83B01] uppercase">Poin:</span>
                                                    <input type="number" x-model.number="ans.score" min="0" :max="ans.max_points"
                                                           class="w-20 text-center rounded-xl border-[#FFD8A8] focus:ring-[#D83B01] focus:border-[#D83B01] bg-white text-base font-black text-[#D83B01] shadow-sm py-2">
                                                    <span class="text-sm font-black text-[#D83B01]/60">/ <span x-text="ans.max_points"></span></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </template>

                        {{-- JIKA DATA KOSONG --}}
                        <template x-if="!activeReview || activeReview.answers.length === 0">
                            <div class="flex flex-col items-center justify-center py-12 text-center border-2 border-dashed border-slate-200 rounded-[2rem]">
                                <div class="w-20 h-20 bg-[#FDE7E9] text-[#D13438] rounded-full flex items-center justify-center mb-4 text-4xl shadow-sm">
                                    <i class="ph-duotone ph-warning-circle"></i>
                                </div>
                                <h4 class="font-black text-xl text-elevate-dark">Data Jawaban Kosong</h4>
                                <p class="text-sm text-slate-500 max-w-sm mx-auto mt-2 font-medium leading-relaxed">Siswa ini melakukan submit sebelum sistem diperbarui, atau terjadi kesalahan database. Harap hapus submission ini dan minta siswa mengerjakan ulang.</p>
                            </div>
                        </template>
                    </div>

                    {{-- Footer Kalkulator (Dibuat shrink-0 agar selalu menempel di bawah) --}}
                    <div class="bg-elevate-soft/50 px-6 py-5 border-t border-slate-100 shrink-0" x-show="activeReview && activeReview.answers.length > 0">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-5">
                            <div class="text-elevate-dark/60 text-xs font-bold flex items-center gap-2">
                                <i class="ph-fill ph-info text-elevate-primary text-lg"></i> 
                                <span>Poin dihitung otomatis dari <br class="sm:hidden">(PG + Input Esai).</span>
                            </div>
                            <div class="flex items-center gap-5 w-full sm:w-auto">
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest">Total Nilai</p>
                                    <p class="text-3xl font-black text-elevate-dark" x-text="calculateTotal()"></p>
                                </div>
                                <button type="button" @click="applyToTable()"
                                        class="flex-1 sm:flex-none px-6 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 active:scale-95 border border-transparent">
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
                        inputField.classList.add('ring-4', 'ring-elevate-accent/30', 'bg-white');
                        setTimeout(() => inputField.classList.remove('ring-4', 'ring-elevate-accent/30', 'bg-white'), 1000);
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
                    customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl' }
                });
                return;
            }

            // 1. Minta konfirmasi
            const result = await Swal.fire({
                title: 'Simpan Semua Nilai?',
                text: `Sistem akan memproses dan menyimpan nilai dari ${forms.length} siswa secara bersamaan.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2c3f61', // Navy Elevate
                cancelButtonColor: '#e5eff5', // Soft Elevate
                confirmButtonText: '<i class="ph-bold ph-check mr-2"></i> Ya, Simpan Semua!',
                cancelButtonText: '<span class="text-[#2c3f61]">Batal</span>',
                customClass: { 
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl', 
                    confirmButton: 'rounded-xl px-6 py-3 font-bold shadow-sm', 
                    cancelButton: 'rounded-xl px-6 py-3 font-bold shadow-sm text-elevate-dark' 
                }
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
                customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl' }
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
                customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl' }
            }).then(() => {
                // Refresh halaman untuk memperbarui session / status
                if(successCount > 0) window.location.reload();
            });
        };

        // Filter Tabel
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tableSearch');
            const classFilter = document.getElementById('classFilter');
            const statusFilter = document.getElementById('statusFilter'); // TAMBAHAN
            const tableRows = document.querySelectorAll('.student-row');
            
            function applyFilters() {
                const searchVal = searchInput ? searchInput.value.toLowerCase() : '';
                const classVal = classFilter ? classFilter.value : '';
                const statusVal = statusFilter ? statusFilter.value : ''; // TAMBAHAN

                tableRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    const rowClass = row.getAttribute('data-class');
                    const rowStatus = row.getAttribute('data-status'); // TAMBAHAN
                    
                    const matchesSearch = textContent.includes(searchVal);
                    const matchesClass = classVal === '' || rowClass === classVal;
                    const matchesStatus = statusVal === '' || rowStatus === statusVal; // TAMBAHAN

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
                statusFilter.addEventListener('change', applyFilters); // TAMBAHAN
            }
        });
    </script>
</x-app-layout>