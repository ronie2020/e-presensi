<x-app-layout>
    {{-- Header Judul --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Penilaian Tugas') }}
        </h2>
    </x-slot>

    {{-- WRAPPER ALPINE JS UTAMA --}}
    <div class="py-8 font-sans text-slate-800" 
         x-data="submissionGrading()">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-white/10 border border-white/10 text-blue-100 backdrop-blur-sm">
                                <i class="ph-bold ph-tag mr-1.5"></i>
                                {{ str_replace('_', ' ', $assignment->assignment_type) }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-emerald-500/20 border border-emerald-400/30 text-emerald-100 backdrop-blur-sm">
                                <i class="ph-bold ph-users-three mr-1.5"></i>
                                @if($assignment->is_bulk)
                                    Semua Kelas {{ $assignment->target_grade }}
                                @else
                                    {{ $assignment->schoolClass->name ?? 'Semua Kelas' }}
                                @endif
                            </span>
                        </div>

                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-white leading-tight">
                            {{ $assignment->title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-blue-200 text-sm font-medium">
                            <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg border border-white/5 hover:bg-white/10 transition">
                                <i class="ph-bold ph-book-open text-blue-400"></i> {{ $assignment->subject->name }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20 text-rose-200">
                                <i class="ph-bold ph-clock text-rose-400"></i> Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    <a href="{{ route('lms.assignments.index') }}" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-lg">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            {{-- TABEL SISWA --}}
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-checks text-blue-600"></i> Daftar Pengumpulan
                    </h3>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        @if($assignment->is_bulk || $allStudents->count() > 30)
                            <div class="relative w-full sm:w-48">
                                <select id="classFilter" class="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all appearance-none cursor-pointer">
                                    <option value="">Semua Kelas</option>
                                    @foreach($allStudents->pluck('schoolClass.name')->unique()->sort() as $className)
                                        <option value="{{ $className }}">{{ $className }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="relative w-full sm:w-64">
                            <input type="text" id="tableSearch" placeholder="Cari nama siswa..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500 w-full shadow-sm transition-all">
                        </div>
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
                                    // Hitung jumlah jawaban untuk diagnosa
                                    $ansCount = $submission ? $submission->answers->count() : 0;
                                @endphp

                                <tr class="group hover:bg-slate-50/80 transition-colors student-row" data-class="{{ $student->schoolClass->name ?? '-' }}">
                                    <!-- Siswa -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 flex items-center justify-center font-bold text-xs border border-white shadow-sm shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors">{{ $student->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $student->schoolClass->name ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 text-center">
                                        @if($submission)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide {{ $isLate ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isLate ? 'bg-amber-500' : 'bg-emerald-500' }}"></span> 
                                                {{ $isLate ? 'Late' : 'On Time' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-slate-100 text-slate-400 border border-slate-200">
                                                Belum
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Jawaban (TOMBOL REVIEW) -->
                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <div class="flex flex-col gap-2">
                                                @if($assignment->assignment_type == 'quiz')
                                                    <button type="button" 
                                                            @click="openReview(
                                                                '{{ addslashes($student->name) }}', 
                                                                {{ json_encode($submission->answers->map(function($ans){
                                                                    return [
                                                                        'question_text' => $ans->question ? $ans->question->question_text : 'Soal telah dihapus guru',
                                                                        'type' => $ans->question ? $ans->question->question_type : 'deleted',
                                                                        'student_answer' => $ans->answer_text,
                                                                        'points' => $ans->points,
                                                                        'max_points' => $ans->question ? $ans->question->points : 0,
                                                                        'correct_answer' => $ans->question ? $ans->question->correct_answer : null
                                                                    ];
                                                                })) }},
                                                                {{ $submission->id }}
                                                            )"
                                                            class="inline-flex items-center gap-2 px-3 py-2 bg-purple-50 text-purple-600 border border-purple-200 hover:bg-purple-100 rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/btn">
                                                        <i class="ph-bold ph-eye text-lg"></i>
                                                        Koreksi
                                                        
                                                        {{-- INDIKATOR DEBUG: Tampilkan jumlah data jawaban yg terbaca --}}
                                                        @if($ansCount == 0)
                                                            <span class="ml-1 px-1.5 py-0.5 rounded bg-rose-500 text-white text-[9px]">Kosong (0)</span>
                                                        @else
                                                            <span class="ml-1 px-1.5 py-0.5 rounded bg-emerald-500 text-white text-[9px]">{{ $ansCount }}</span>
                                                        @endif
                                                    </button>
                                                @elseif($submission->file_path)
                                                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-blue-300 hover:text-blue-600 rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/file">
                                                        <i class="ph-bold ph-file-text text-lg text-slate-400 group-hover/file:text-blue-500"></i>
                                                        Lihat File
                                                    </a>
                                                @elseif($submission->student_note)
                                                    <div class="bg-amber-50 p-2.5 rounded-xl border border-amber-100 text-xs text-amber-800 italic relative w-fit max-w-[200px]">
                                                        <i class="ph-fill ph-quotes text-amber-200 text-xl absolute -top-2 -left-1"></i>
                                                        <span class="relative z-10 font-medium">"{{ Str::limit($submission->student_note, 40) }}"</span>
                                                    </div>
                                                @else
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
                                            <form action="{{ route('lms.submissions.grade', $submission->id) }}" method="POST" class="contents grade-form">
                                                @csrf
                                                @if($assignment->assignment_type == 'quiz')
                                                    @php
                                                        $autoScore = $submission->answers->sum(fn($ans) => $ans->is_correct ? $ans->points : 0);
                                                    @endphp
                                                    <div class="text-[10px] text-slate-400 mb-1">
                                                        PG: <span class="font-bold text-blue-600">{{ $autoScore }}</span>
                                                    </div>
                                                @endif
                                                <input type="number" name="grade" id="grade_input_{{ $submission->id }}"
                                                       value="{{ $submission->grade }}" 
                                                       class="w-16 text-center rounded-xl border-slate-200 bg-white focus:border-blue-500 text-sm font-black text-slate-800 h-10 shadow-sm" placeholder="0">
                                            </form>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($submission)
                                            <input type="text" form="form-grade-{{$submission->id}}" value="{{ $submission->teacher_feedback }}" class="w-full text-xs rounded-xl border-slate-200 h-10 px-3" placeholder="Feedback...">
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        @if($submission)
                                            <button type="button" onclick="document.querySelector('.grade-form').submit()" class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-md">
                                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                            </button>
                                            
                                            {{-- FORM HAPUS SUBMISSION --}}
                                            <form action="{{ route('lms.submissions.destroy', $submission->id) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Hapus data jawaban siswa ini? Siswa harus mengerjakan ulang.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 border border-rose-200 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors">
                                                    <i class="ph-bold ph-trash"></i>
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

        {{-- MODAL REVIEW JAWABAN --}}
        <div x-show="showReviewModal" style="display: none;" 
             class="fixed inset-0 z-[999] overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" @click="showReviewModal = false"></div>

                <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-slate-200">
                    
                    {{-- Header Modal --}}
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center sticky top-0 z-10">
                        <div>
                            <h3 class="text-lg leading-6 font-black text-slate-800">Koreksi Jawaban</h3>
                            <p class="text-xs text-slate-500 font-bold" x-text="'Siswa: ' + (activeReview ? activeReview.student_name : '-')"></p>
                        </div>
                        <button @click="showReviewModal = false" class="bg-white rounded-xl p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    {{-- Isi Jawaban --}}
                    <div class="px-6 py-6 max-h-[60vh] overflow-y-auto bg-white space-y-6">
                        <template x-if="activeReview && activeReview.answers.length > 0">
                            <template x-for="(ans, index) in activeReview.answers" :key="index">
                                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex gap-3 mb-3">
                                        <span class="bg-blue-100 text-blue-700 w-6 h-6 flex items-center justify-center rounded-lg font-bold text-xs shrink-0" x-text="index + 1"></span>
                                        <p class="text-sm font-bold text-slate-800" x-text="ans.question_text"></p>
                                    </div>
                                    <div class="pl-9 space-y-3">
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Jawaban Siswa</p>
                                            <p class="text-sm text-slate-700 font-medium whitespace-pre-line" x-text="ans.student_answer ? ans.student_answer : '(Kosong)'"></p>
                                        </div>

                                        {{-- JIKA ESSAI (Input Manual Guru) --}}
                                        <template x-if="ans.type === 'essay'">
                                            <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                <div>
                                                    <p class="text-[10px] font-bold text-amber-600 uppercase mb-1"><i class="ph-bold ph-pencil-simple"></i> Koreksi Manual</p>
                                                    <p class="text-xs text-amber-800">Baca jawaban, lalu input poin yang sesuai.</p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-slate-500">Poin:</span>
                                                    <input type="number" x-model.number="ans.score" min="0" :max="ans.max_points"
                                                           class="w-20 text-center rounded-lg border-amber-300 focus:ring-amber-500 focus:border-amber-500 text-sm font-bold shadow-sm">
                                                    <span class="text-xs font-bold text-slate-400">/ <span x-text="ans.max_points"></span></span>
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
                                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mb-4 text-3xl">
                                    <i class="ph-duotone ph-warning-circle"></i>
                                </div>
                                <h4 class="font-bold text-slate-800">Data Jawaban Tidak Ditemukan</h4>
                                <p class="text-sm text-slate-500 max-w-xs mt-2">Siswa ini melakukan submit sebelum sistem diperbarui, atau terjadi kesalahan database. Harap hapus submission ini dan minta siswa mengerjakan ulang.</p>
                            </div>
                        </template>
                    </div>

                    {{-- Footer Kalkulator --}}
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 sticky bottom-0 z-10" x-show="activeReview && activeReview.answers.length > 0">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-slate-600 text-xs font-medium">
                                <i class="ph-fill ph-info text-blue-500"></i> Poin dihitung otomatis dari (PG + Input Esai).
                            </div>
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Total Nilai Akhir</p>
                                    <p class="text-2xl font-black text-blue-600" x-text="calculateTotal()"></p>
                                </div>
                                <button type="button" @click="applyToTable()"
                                        class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
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
                        inputField.classList.add('ring-4', 'ring-blue-300', 'bg-blue-50');
                        setTimeout(() => inputField.classList.remove('ring-4', 'ring-blue-300', 'bg-blue-50'), 1000);
                    }
                    this.showReviewModal = false;
                }
            }));
        });
        
        // Filter Logic
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tableSearch');
            const tableRows = document.querySelectorAll('.student-row');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const val = this.value.toLowerCase();
                    tableRows.forEach(row => {
                        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
                    });
                });
            }
        });
    </script>
</x-app-layout>