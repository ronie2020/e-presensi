<x-app-layout>
    {{-- Konfigurasi MathJax untuk menampilkan rumus matematika di soal --}}
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <x-slot name="header">
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Detail Hasil Ujian') }}
            </h2>
            <button onclick="window.print()" class="text-sm font-bold text-slate-500 hover:text-blue-600 flex items-center gap-2 transition">
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

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER: INFO SISWA & SKOR --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden print:shadow-none print:border-black print:rounded-none">
                <div class="relative p-8 overflow-hidden">
                    {{-- Background Pattern --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-bl-full opacity-50 pointer-events-none print:hidden"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row gap-8 items-center md:items-start justify-between">
                        {{-- Info Siswa --}}
                        <div class="text-center md:text-left">
                            <div class="flex items-center gap-2 justify-center md:justify-start mb-2 no-print">
                                <a href="{{ route('cbt.recap', $exam->id) }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition flex items-center gap-1">
                                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap
                                </a>
                            </div>
                            <h1 class="text-3xl font-black text-slate-800 mb-1">{{ $student->name }}</h1>
                            <p class="text-slate-500 font-medium mb-4">{{ $student->schoolClass->name ?? 'Kelas -' }} • NISN: {{ $student->student_id ?? '-' }}</p>
                            
                            <div class="inline-flex flex-col items-start gap-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ujian</span>
                                <span class="font-bold text-slate-700">{{ $exam->title }} ({{ $exam->subject_name }})</span>
                            </div>
                        </div>

                        {{-- Skor Card --}}
                        <div class="flex items-center gap-6">
                            {{-- Statistik Kecil --}}
                            <div class="hidden sm:flex flex-col gap-2 text-right">
                                <div>
                                    <span class="text-xs text-slate-400 font-bold uppercase">Benar</span>
                                    <p class="text-lg font-black text-emerald-600">{{ $stats['correct'] }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 font-bold uppercase">Salah</span>
                                    <p class="text-lg font-black text-rose-500">{{ $stats['wrong'] }}</p>
                                </div>
                            </div>

                            {{-- Lingkaran Nilai Utama --}}
                            <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 {{ $examSession->total_score >= $exam->passing_grade ? 'border-emerald-100 bg-emerald-50' : 'border-rose-100 bg-rose-50' }} print:border-black print:bg-white">
                                <div class="text-center">
                                    <span class="block text-4xl font-black {{ $examSession->total_score >= $exam->passing_grade ? 'text-emerald-600' : 'text-rose-600' }} print:text-black">
                                        {{ $examSession->total_score }}
                                    </span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Nilai Akhir</span>
                                </div>
                                
                                {{-- Status Badge --}}
                                <div class="absolute -bottom-3 px-4 py-1 rounded-full text-xs font-black uppercase tracking-wider shadow-sm border {{ $examSession->total_score >= $exam->passing_grade ? 'bg-emerald-500 text-white border-emerald-600' : 'bg-rose-500 text-white border-rose-600' }} print:border-black print:bg-white print:text-black">
                                    {{ $examSession->total_score >= $exam->passing_grade ? 'LULUS' : 'REMEDIAL' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LIST JAWABAN --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 mb-4 px-2 print:hidden">
                    <i class="ph-fill ph-list-magnifying-glass text-indigo-500 text-xl"></i>
                    <h3 class="font-bold text-slate-700 text-lg">Analisis Butir Soal</h3>
                </div>

                @forelse($answers as $index => $item)
                    @php
                        $isCorrect = strtoupper($item->student_answer) == strtoupper($item->correct_answer);
                        $isSkipped = is_null($item->student_answer);
                    @endphp

                    <div class="bg-white rounded-[2rem] border {{ $isCorrect ? 'border-emerald-100' : ($isSkipped ? 'border-slate-200' : 'border-rose-100') }} p-6 shadow-sm relative overflow-hidden print-break print:border-black print:rounded-none">
                        
                        {{-- Status Bar Kiri --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isCorrect ? 'bg-emerald-400' : ($isSkipped ? 'bg-slate-300' : 'bg-rose-400') }} print:border-r print:border-black"></div>

                        {{-- Header Soal --}}
                        <div class="flex justify-between items-start mb-4 pl-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : ($isSkipped ? 'bg-slate-100 text-slate-500' : 'bg-rose-100 text-rose-700') }} print:border print:border-black print:bg-white print:text-black">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    Bobot: {{ $item->score_weight }}
                                </span>
                            </div>
                            <div class="text-right">
                                @if($isCorrect)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md print:text-black print:bg-white print:border print:border-black">
                                        <i class="ph-bold ph-check"></i> Benar (+{{ $item->score_weight }})
                                    </span>
                                @elseif($isSkipped)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                                        <i class="ph-bold ph-minus"></i> Kosong (0)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-md print:text-black print:bg-white print:border print:border-black">
                                        <i class="ph-bold ph-x"></i> Salah (0)
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Konten Soal --}}
                        <div class="pl-4 mb-6">
                            @if($item->question_image)
                                <img src="{{ asset('storage/' . $item->question_image) }}" class="max-h-48 rounded-xl border border-slate-100 mb-4 object-contain print:border-black">
                            @endif
                            <div class="text-slate-800 font-medium text-base leading-relaxed">
                                {!! nl2br(e($item->question_text)) !!}
                            </div>
                        </div>

                        {{-- Pilihan Jawaban --}}
                        <div class="pl-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach(['A','B','C','D'] as $opt)
                                @php
                                    // Logic Pewarnaan
                                    // Menggunakan properti dynamic yang sudah di-set di Controller (transform)
                                    $optionText = $item->{'option_'.$opt} ?? '-';
                                    $isKey = $opt == $item->correct_answer;
                                    $isStudentChoice = $opt == $item->student_answer;
                                    
                                    $bgClass = 'bg-white border-slate-100 text-slate-600'; // Default
                                    
                                    if ($isKey) {
                                        $bgClass = 'bg-emerald-50 border-emerald-200 text-emerald-800 ring-1 ring-emerald-200'; // Kunci Jawaban (Selalu Hijau)
                                    } elseif ($isStudentChoice && !$isKey) {
                                        $bgClass = 'bg-rose-50 border-rose-200 text-rose-800 ring-1 ring-rose-200'; // Jawaban Siswa Salah (Merah)
                                    } elseif ($isStudentChoice && $isKey) {
                                        $bgClass = 'bg-emerald-100 border-emerald-300 text-emerald-900 ring-2 ring-emerald-400'; // Jawaban Siswa Benar
                                    }
                                @endphp

                                <div class="flex items-start gap-3 p-3 rounded-xl border text-sm transition-colors {{ $bgClass }} print:border-black print:bg-white print:text-black">
                                    <div class="font-black text-xs pt-0.5 shrink-0 w-5">{{ $opt }}.</div>
                                    <div class="flex-1">{{ $optionText }}</div>
                                    
                                    {{-- Ikon Penanda --}}
                                    @if($isKey)
                                        <i class="ph-fill ph-check-circle text-emerald-500 text-lg print:text-black"></i>
                                    @elseif($isStudentChoice)
                                        <i class="ph-fill ph-x-circle text-rose-500 text-lg print:text-black"></i>
                                    @endif
                                </div>
                            @endforeach
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
</x-app-layout>