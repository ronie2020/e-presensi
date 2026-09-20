<x-app-layout>
    <div class="py-4 sm:py-6 font-sans text-white relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Analisis Hasil Kuis"
                badgeIcon="ph-clipboard-text"
                title="Analisis Jawaban"
                titleHighlight="{{ $submission->student->name }}"
                description="Tinjau rincian hasil pengerjaan kuis untuk penugasan {{ $submission->assignment->title }}."
                :chips="[
                    ['icon' => 'ph-student', 'label' => $submission->student->schoolClass->name ?? 'Tanpa Kelas'],
                    ['icon' => 'ph-book-open-text', 'label' => $submission->assignment->title],
                    ['icon' => 'ph-calendar', 'label' => 'Dikumpulkan: ' . ($submission->submitted_at ? $submission->submitted_at->translatedFormat('d M Y, H:i') : '-')]
                ]"
                heroIcon="ph-student"
                :showcaseNumber="$submission->grade ?? 0"
                showcaseLabel="Nilai Akhir"
                showcaseSubtitle="Skala 0 - 100"
                statusOrb="{{ ($submission->grade ?? 0) >= 75 ? 'Tuntas' : 'Remedial' }}"
                statusColor="{{ ($submission->grade ?? 0) >= 75 ? 'emerald' : 'amber' }}"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.assignments.submissions', $submission->assignment_id) }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left text-base text-sky-400"></i> Kembali ke Pengumpulan
                        </a>
                        <a href="{{ route('lms.grades.index') }}" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs rounded-xl border border-white/10 transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-chart-bar text-sm text-sky-400"></i> Rekap Nilai
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- DAFTAR JAWABAN --}}
            @if($submission->answers->isEmpty())
                <div class="bg-[#031d3d]/80 rounded-[2rem] border border-white/15 p-12 text-center shadow-xl backdrop-blur-xl">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-3xl text-slate-400 mx-auto mb-3 border border-white/10">
                        <i class="ph-duotone ph-file-dashed"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-200">Tidak ada detail jawaban</h4>
                    <p class="text-slate-400 text-xs mt-1">Kuis ini diselesaikan sebelum fitur analisis rekam jejak jawaban diaktifkan.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($submission->answers as $index => $answer)
                        @php
                            $isCorrect = $answer->is_correct;
                            $qType = $answer->question->question_type ?? 'multiple_choice';
                            $borderColor = $isCorrect ? 'border-emerald-400/30' : 'border-rose-400/30';
                            $bgColor = $isCorrect ? 'bg-emerald-500/10' : 'bg-rose-500/10';
                            $icon = $isCorrect ? 'ph-check-circle text-emerald-400' : 'ph-x-circle text-rose-400';
                        @endphp
                        
                        <div class="rounded-2xl border {{ $borderColor }} bg-[#031d3d]/80 backdrop-blur-xl overflow-hidden shadow-lg shadow-black/20">
                            <div class="p-4 {{ $bgColor }} border-b {{ $borderColor }} flex items-start gap-3">
                                <i class="ph-fill {{ $icon }} text-2xl mt-0.5 shrink-0"></i>
                                <div>
                                    <span class="text-xs font-black text-sky-300 uppercase tracking-wider">Soal No. {{ $index + 1 }}</span>
                                    <p class="text-sm md:text-base font-bold text-white mt-1 leading-relaxed">{!! nl2br(e($answer->question->question_text ?? 'Soal telah dihapus')) !!}</p>
                                </div>
                            </div>
                            
                            <div class="p-5 flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jawaban Siswa:</p>
                                    @if($qType == 'multiple_choice')
                                        @php
                                            $options = is_array($answer->question->options) ? $answer->question->options : json_decode($answer->question->options, true);
                                            $optionText = $options[$answer->answer_text] ?? 'Opsi tidak ditemukan';
                                        @endphp
                                        <div class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border {{ $isCorrect ? 'border-emerald-400/40 text-emerald-300 bg-emerald-500/15' : 'border-rose-400/40 text-rose-300 bg-rose-500/15' }} font-bold text-xs sm:text-sm shadow-sm">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-black {{ $isCorrect ? 'bg-emerald-600' : 'bg-rose-600' }}">
                                                {{ $answer->answer_text ?: '-' }}
                                            </span>
                                            <span>{{ $optionText }}</span>
                                        </div>
                                    @else
                                        <div class="p-4 bg-[#021124]/60 rounded-xl border border-white/10 text-slate-200 text-sm leading-relaxed">
                                            {!! nl2br(e($answer->answer_text)) !!}
                                        </div>
                                    @endif
                                </div>

                                @if($qType == 'multiple_choice' && !$isCorrect)
                                    <div class="flex-1 md:border-l md:border-white/10 md:pl-6">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kunci Jawaban Benar:</p>
                                        @php
                                            $correctText = $options[$answer->question->correct_answer ?? ''] ?? '';
                                        @endphp
                                        <div class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-white/15 text-slate-200 font-bold text-xs sm:text-sm bg-[#021124]/70">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center bg-white/10 text-white text-xs font-black">
                                                {{ $answer->question->correct_answer ?? '-' }}
                                            </span>
                                            <span>{{ $correctText }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>