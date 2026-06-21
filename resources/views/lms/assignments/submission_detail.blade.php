<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analisis Jawaban Kuis') }}
            </h2>
            <a href="{{ route('lms.assignments.submissions', $submission->assignment_id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm font-bold hover:bg-gray-600 transition">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- HEADER INFO --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-student"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900">{{ $submission->student->name }}</h3>
                        <p class="text-sm font-bold text-gray-500">{{ $submission->student->schoolClass->name ?? 'Tanpa Kelas' }} • {{ $submission->assignment->title }}</p>
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nilai Akhir</p>
                    <div class="inline-block px-5 py-2 bg-blue-600 text-white text-2xl font-black rounded-xl shadow-lg">
                        {{ $submission->grade ?? 0 }} <span class="text-sm opacity-70">/ 100</span>
                    </div>
                </div>
            </div>

            {{-- DAFTAR JAWABAN --}}
            @if($submission->answers->isEmpty())
                <div class="bg-white p-10 rounded-2xl border border-gray-100 text-center shadow-sm">
                    <i class="ph-duotone ph-file-dashed text-5xl text-gray-400 mb-3"></i>
                    <h4 class="text-lg font-bold text-gray-700">Tidak ada detail jawaban</h4>
                    <p class="text-gray-500 text-sm mt-1">Kuis ini diselesaikan sebelum fitur analisis rekam jejak jawaban diaktifkan.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($submission->answers as $index => $answer)
                        @php
                            $isCorrect = $answer->is_correct;
                            $qType = $answer->question->question_type ?? 'multiple_choice';
                            $borderColor = $isCorrect ? 'border-green-200' : 'border-red-200';
                            $bgColor = $isCorrect ? 'bg-green-50' : 'bg-red-50';
                            $icon = $isCorrect ? 'ph-check-circle text-green-600' : 'ph-x-circle text-red-600';
                        @endphp
                        
                        <div class="bg-white rounded-2xl border-2 {{ $borderColor }} overflow-hidden shadow-sm">
                            <div class="p-4 {{ $bgColor }} border-b {{ $borderColor }} flex items-start gap-3">
                                <i class="ph-fill {{ $icon }} text-2xl mt-0.5"></i>
                                <div>
                                    <span class="text-xs font-black text-gray-500 uppercase tracking-wider">Soal No. {{ $index + 1 }}</span>
                                    <p class="text-sm md:text-base font-bold text-gray-900 mt-1">{!! nl2br(e($answer->question->question_text ?? 'Soal telah dihapus')) !!}</p>
                                </div>
                            </div>
                            
                            <div class="p-5 flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Jawaban Siswa:</p>
                                    @if($qType == 'multiple_choice')
                                        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-xl border-2 {{ $isCorrect ? 'border-green-500 text-green-700 bg-green-50' : 'border-red-500 text-red-700 bg-red-50' }} font-bold">
                                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white {{ $isCorrect ? 'bg-green-600' : 'bg-red-600' }}">
                                                {{ $answer->answer_text ?: '-' }}
                                            </span>
                                            {{-- Mencari teks opsi dari JSON --}}
                                            @php
                                                $options = is_array($answer->question->options) ? $answer->question->options : json_decode($answer->question->options, true);
                                                $optionText = $options[$answer->answer_text] ?? 'Opsi tidak ditemukan';
                                            @endphp
                                            <span>{{ $optionText }}</span>
                                        </div>
                                    @else
                                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-gray-800 text-sm">
                                            {!! nl2br(e($answer->answer_text)) !!}
                                        </div>
                                    @endif
                                </div>

                                @if($qType == 'multiple_choice' && !$isCorrect)
                                    <div class="flex-1 md:border-l md:border-gray-200 md:pl-6">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Kunci Jawaban Benar:</p>
                                        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-xl border-2 border-gray-200 text-gray-700 font-bold bg-gray-50">
                                            <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-300 text-gray-800">
                                                {{ $answer->question->correct_answer ?? '-' }}
                                            </span>
                                            @php
                                                $correctText = $options[$answer->question->correct_answer ?? ''] ?? '';
                                            @endphp
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