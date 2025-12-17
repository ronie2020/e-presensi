@extends('layouts.student')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    
    <!-- Header Kuis -->
    <div class="bg-white rounded-[2rem] shadow-lg border border-purple-100 p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100 rounded-full blur-3xl -mr-10 -mt-10"></div>
        <div class="relative z-10">
            <span class="inline-block px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold uppercase tracking-widest border border-purple-200 mb-4">
                Kuis Online
            </span>
            <h1 class="text-3xl font-black text-slate-800 mb-2">{{ $assignment->title }}</h1>
            <p class="text-slate-500">{{ $assignment->description }}</p>
            
            <div class="flex items-center gap-6 mt-6 text-sm font-bold text-slate-600">
                <span class="flex items-center gap-2"><i class="ph-fill ph-clock text-purple-500"></i> {{ $assignment->duration_minutes }} Menit</span>
                <span class="flex items-center gap-2"><i class="ph-fill ph-list-numbers text-purple-500"></i> {{ $assignment->questions->count() }} Soal</span>
            </div>
        </div>
    </div>

    <!-- Form Soal -->
    <form action="{{ route('students.learning.assignment.quiz.submit', $assignment->id) }}" method="POST" id="quizForm">
        @csrf
        
        <div class="space-y-6">
            @foreach($assignment->questions as $index => $q)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative">
                    <span class="absolute top-6 left-6 w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-sm">
                        {{ $index + 1 }}
                    </span>
                    
                    <div class="pl-12">
                        <div class="text-lg font-medium text-slate-800 mb-6 leading-relaxed">
                            {!! nl2br(e($q->question_text)) !!}
                        </div>

                        <!-- Opsi Jawaban -->
                        <div class="space-y-3">
                            @if($q->question_type == 'multiple_choice')
                                @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                                    @if(isset($q->options[$opt]) && $q->options[$opt])
                                        <label class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all group relative overflow-hidden">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="peer sr-only">
                                            
                                            <!-- Custom Radio UI -->
                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center text-xs font-bold text-white peer-checked:bg-purple-600 peer-checked:border-purple-600 transition-colors mt-0.5 shrink-0">
                                                {{ $opt }}
                                            </div>
                                            
                                            <span class="text-slate-600 font-medium peer-checked:text-purple-900 group-hover:text-purple-700">
                                                {{ $q->options[$opt] }}
                                            </span>
                                            
                                            <!-- Highlight Border Active -->
                                            <div class="absolute inset-0 border-2 border-purple-500 rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                        </label>
                                    @endif
                                @endforeach
                            @else
                                <textarea name="answers[{{ $q->id }}]" rows="4" class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500" placeholder="Tuliskan jawaban Anda disini..."></textarea>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 mb-20 flex justify-end">
            <button type="submit" onclick="return confirm('Yakin ingin mengumpulkan jawaban? Anda tidak bisa mengubahnya setelah ini.')" class="px-8 py-4 bg-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-purple-200 hover:bg-purple-700 hover:-translate-y-1 transition-all flex items-center gap-2">
                <i class="ph-bold ph-paper-plane-right text-xl"></i> Kumpulkan Jawaban
            </button>
        </div>
    </form>

</div>
@endsection