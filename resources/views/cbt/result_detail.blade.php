<x-app-layout>
    {{-- Konfigurasi MathJax --}}
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' },
            startup: {
                ready: () => {
                    MathJax.startup.defaultReady();
                    window.renderMath = () => { MathJax.typesetPromise(); };
                }
            }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <x-slot name="header">
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
                {{ __('Detail Hasil Ujian') }}
            </h2>
            <button onclick="window.print()" class="text-sm font-bold text-[#2c3f61] hover:text-[#0d52a1] flex items-center gap-2 transition bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                <i class="ph-bold ph-printer text-lg"></i> Cetak Hasil
            </button>
        </div>
    </x-slot>

    {{-- Style Print --}}
    <style>
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-break { page-break-inside: avoid; }
            ::-webkit-scrollbar { display: none; }
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER: INFO SISWA & SKOR --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-[#56bbf1]/10 border border-slate-100 overflow-hidden print:shadow-none print:border-black print:rounded-none">
                <div class="relative p-8 overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#e5eff5] to-white rounded-bl-full opacity-60 pointer-events-none print:hidden"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row gap-8 items-center md:items-start justify-between">
                        <div class="text-center md:text-left">
                            <div class="flex items-center gap-2 justify-center md:justify-start mb-2 no-print">
                                <a href="{{ route('cbt.recap', $exam->id) }}" class="text-xs font-bold text-slate-400 hover:text-[#0d52a1] transition flex items-center gap-1">
                                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap
                                </a>
                            </div>
                            <h1 class="text-3xl font-black text-[#2c3f61] mb-1">{{ $student->name }}</h1>
                            <p class="text-[#2c3f61]/60 font-medium mb-4">{{ $student->schoolClass->name ?? 'Kelas -' }} • NISN: {{ $student->student_id ?? '-' }}</p>
                            
                            <div class="inline-flex flex-col items-start gap-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ujian</span>
                                <span class="font-bold text-[#2c3f61]">{{ $exam->title }} ({{ $exam->subject_name }})</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            {{-- Score Display --}}
                            <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 {{ $examSession->total_score >= $exam->passing_grade ? 'border-emerald-400/50 bg-emerald-50' : 'border-rose-400/50 bg-rose-50' }} print:border-black print:bg-white transition-colors duration-500">
                                <div class="text-center">
                                    <span id="displayTotalScore" class="block text-4xl font-black {{ $examSession->total_score >= $exam->passing_grade ? 'text-emerald-500' : 'text-rose-500' }} print:text-black transition-colors duration-500">
                                        {{ $examSession->total_score }}
                                    </span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Nilai Akhir</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LIST JAWABAN --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 mb-4 px-2 print:hidden">
                    <i class="ph-fill ph-list-magnifying-glass text-[#56bbf1] text-xl"></i>
                    <h3 class="font-bold text-[#2c3f61] text-lg">Analisis & Koreksi Soal</h3>
                </div>

                @forelse($answers as $index => $item)
                    @php
                        $qType = $item->question_type ?? 'choice';
                        $studentAns = trim($item->student_answer ?? '');
                        $correctAns = trim($item->correct_answer ?? '');
                        
                        $isSkipped = is_null($studentAns) || $studentAns === '';
                        $isCorrect = false;

                        if ($qType == 'choice' || $qType == 'true_false') {
                            $isCorrect = strtoupper($studentAns) === strtoupper($correctAns);
                        } elseif ($qType == 'matching') {
                            $keyMap = json_decode($correctAns, true) ?? [];
                            $studentMap = json_decode($studentAns, true) ?? [];
                            if (is_array($keyMap)) ksort($keyMap);
                            if (is_array($studentMap)) ksort($studentMap);
                            $isCorrect = (!empty($keyMap) && $keyMap == $studentMap);
                        }

                        if ($qType == 'essay') {
                            $currentScore = $item->score ?? 0;
                            $isCorrect = $currentScore > 0; 
                        } else {
                            $currentScore = $isCorrect ? ($item->score_weight ?? 0) : 0;
                        }
                    @endphp

                    {{-- Card Soal --}}
                    <div class="bg-white rounded-[2rem] border {{ $isCorrect ? 'border-emerald-200 bg-emerald-50/10' : ($isSkipped ? 'border-slate-100' : ($qType == 'essay' ? 'border-[#56bbf1]/30' : 'border-rose-200 bg-rose-50/10')) }} p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden print-break print:border-black print:rounded-none"
                         x-data="{ 
                            manualScore: {{ $item->score ?? 0 }}, 
                            maxScore: {{ $item->score_weight }},
                            isSaving: false 
                         }">
                        
                        {{-- Status Bar Kiri --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isCorrect ? 'bg-emerald-400' : ($isSkipped ? 'bg-slate-200' : ($qType == 'essay' ? 'bg-[#56bbf1]' : 'bg-rose-400')) }} print:border-r print:border-black"></div>

                        {{-- Header Soal --}}
                        <div class="flex justify-between items-start mb-4 pl-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : ($isSkipped ? 'bg-slate-100 text-slate-500' : ($qType == 'essay' ? 'bg-[#e5eff5] text-[#0d52a1]' : 'bg-rose-100 text-rose-700')) }} print:border print:border-black print:bg-white print:text-black">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        Bobot Maks: {{ $item->score_weight }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        {{ $qType == 'choice' ? 'Pilihan Ganda' : ($qType == 'essay' ? 'Essai' : ucfirst($qType)) }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Nilai Perolehan (Pojok Kanan) --}}
                            <div class="text-right">
                                <span class="block text-2xl font-black {{ $currentScore > 0 ? 'text-emerald-500' : 'text-slate-300' }} transition-colors">
                                    {{ floatval($currentScore) }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Nilai Diperoleh</span>
                            </div>
                        </div>

                        {{-- Konten Soal --}}
                        <div class="pl-4 mb-6">
                            @if($item->question_image)
                                <img src="{{ asset('storage/' . $item->question_image) }}" class="max-h-48 rounded-xl border border-slate-100 mb-4 object-contain print:border-black">
                            @endif
                            <div class="text-[#2c3f61] font-medium text-base leading-relaxed prose prose-sm max-w-none">
                                {!! $item->question_text !!}
                            </div>
                        </div>

                        {{-- TAMPILAN JAWABAN --}}
                        <div class="pl-4">
                            
                            {{-- 1. PILIHAN GANDA & TRUE/FALSE --}}
                            @if($qType == 'choice' || $qType == 'true_false')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach(['A','B','C','D', 'E'] as $opt)
                                        @php
                                            $optionText = $item->{'option_'.$opt} ?? null;
                                            if(!$optionText && $qType == 'true_false' && ($opt == 'A' || $opt == 'B')) {
                                                 $optionText = ($opt == 'A') ? 'Benar' : 'Salah';
                                            }
                                        @endphp
                                        @if($optionText)
                                            @php
                                                $isKey = $opt == $item->correct_answer;
                                                $isStudentChoice = $opt == $item->student_answer;
                                                $bgClass = $isKey ? 'bg-emerald-50 border-emerald-200 text-emerald-800 ring-1 ring-emerald-200' 
                                                         : ($isStudentChoice ? 'bg-rose-50 border-rose-200 text-rose-800 ring-1 ring-rose-200' : 'bg-white border-slate-100 text-[#2c3f61]/80');
                                            @endphp
                                            <div class="flex items-start gap-3 p-3 rounded-xl border text-sm transition-colors {{ $bgClass }} print:border-black print:bg-white print:text-black">
                                                <div class="font-black text-xs pt-0.5 shrink-0 w-5">{{ $opt }}.</div>
                                                <div class="flex-1">{{ $optionText }}</div>
                                                @if($isKey) <i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>
                                                @elseif($isStudentChoice) <i class="ph-fill ph-x-circle text-rose-500 text-lg"></i>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            {{-- 2. ESSAI (TEXT) --}}
                            @elseif($qType == 'essay')
                                <div class="space-y-4">
                                    {{-- Jawaban Siswa --}}
                                    <div class="p-5 rounded-2xl border bg-[#e5eff5]/50 border-[#56bbf1]/30">
                                        <p class="text-[10px] font-bold text-[#0d52a1] uppercase mb-2">Jawaban Siswa:</p>
                                        <p class="font-medium text-[#2c3f61] whitespace-pre-wrap leading-relaxed text-sm">{{ $item->student_answer ?: '(Tidak dijawab)' }}</p>
                                    </div>
                                    
                                    {{-- Kunci Jawaban (Hanya Guru yg lihat) --}}
                                    <div class="p-4 rounded-xl border bg-amber-50 border-amber-200 border-dashed print:hidden">
                                        <p class="text-[10px] font-bold text-amber-600 uppercase mb-1 flex items-center gap-1"><i class="ph-bold ph-key"></i> Kunci Jawaban (Referensi):</p>
                                        <p class="font-medium text-slate-700 whitespace-pre-wrap text-sm">{{ $item->correct_answer ?: '-' }}</p>
                                    </div>

                                    {{-- INPUT KOREKSI MANUAL --}}
                                    <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm print:hidden">
                                        <div class="flex-1">
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Berikan Nilai Manual:</label>
                                            <div class="flex items-center gap-2">
                                                <input type="number" x-model="manualScore" :max="maxScore" min="0" step="0.1"
                                                       @keydown.enter="saveEssayScore({{ $item->answer_id }}, manualScore)"
                                                       class="w-24 font-bold text-[#2c3f61] text-center rounded-xl border-slate-200 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                                <span class="text-sm font-bold text-slate-400">/ <span x-text="maxScore"></span> Poin</span>
                                            </div>
                                        </div>
                                        <button @click="saveEssayScore({{ $item->answer_id }}, manualScore)" 
                                                :disabled="isSaving"
                                                class="px-5 py-3 bg-[#0d52a1] hover:bg-[#0a4282] text-white font-bold rounded-xl shadow-lg shadow-[#0d52a1]/30 transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="ph-bold" :class="isSaving ? 'ph-spinner animate-spin' : 'ph-floppy-disk'"></i>
                                            <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Nilai'"></span>
                                        </button>
                                    </div>
                                </div>

                            {{-- 3. MATCHING --}}
                            @elseif($qType == 'matching')
                                @php
                                    $studentPairs = json_decode($item->student_answer, true) ?? [];
                                    $correctPairs = json_decode($item->correct_answer, true) ?? []; 
                                @endphp
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Pencocokan Jawaban:</p>
                                    <div class="space-y-2">
                                        @foreach($correctPairs as $left => $right)
                                            @php
                                                $studentSelected = $studentPairs[$left] ?? '-';
                                                $isMatch = strtoupper($studentSelected) == strtoupper($right);
                                            @endphp
                                            <div class="flex flex-col sm:flex-row gap-2 items-center bg-white p-2 rounded-lg border {{ $isMatch ? 'border-emerald-200' : 'border-rose-200' }}">
                                                <div class="flex-1 text-sm font-bold text-[#2c3f61] text-center sm:text-left">{{ $left }}</div>
                                                <i class="ph-bold ph-arrow-right text-slate-300"></i>
                                                <div class="flex-1 text-sm text-center sm:text-right font-bold {{ $isMatch ? 'text-emerald-500' : 'text-rose-500 line-through' }}">
                                                    {{ $studentSelected }}
                                                </div>
                                                @if(!$isMatch)
                                                    <div class="text-xs bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-1 rounded">{{ $right }}</div>
                                                @else
                                                    <i class="ph-fill ph-check-circle text-emerald-500"></i>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-[2rem] border border-slate-100">
                        <p class="text-slate-500 font-bold">Data jawaban tidak ditemukan.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- SCRIPT PENILAIAN MANUAL DENGAN LIVE COLOR UPDATE --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function saveEssayScore(answerId, score) {
            if (score < 0) return Swal.fire('Error', 'Nilai tidak boleh minus', 'error');

            fetch("{{ route('cbt.grade_essay') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ answer_id: answerId, score: score })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: 'Nilai berhasil diperbarui.',
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
                    });
                    
                    const displayTotal = document.getElementById('displayTotalScore');
                    if(displayTotal) {
                        displayTotal.innerText = data.new_total;
                        
                        const kkm = {{ $exam->passing_grade ?? 0 }};
                        const circleContainer = displayTotal.closest('.border-8');
                        if (data.new_total >= kkm) {
                            displayTotal.classList.replace('text-rose-500', 'text-emerald-500');
                            circleContainer.classList.replace('border-rose-400/50', 'border-emerald-400/50');
                            circleContainer.classList.replace('bg-rose-50', 'bg-emerald-50');
                        } else {
                            displayTotal.classList.replace('text-emerald-500', 'text-rose-500');
                            circleContainer.classList.replace('border-emerald-400/50', 'border-rose-400/50');
                            circleContainer.classList.replace('bg-emerald-50', 'bg-rose-50');
                        }
                    }
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Gagal menghubungi server.', 'error');
            });
        }
    </script>
    @endpush

</x-app-layout>