@extends('layouts.student')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto min-h-screen bg-slate-50/50">
    
    <div class="bg-gradient-to-br from-slate-900 to-blue-950 rounded-[2rem] shadow-xl shadow-blue-900/10 border border-slate-800 p-8 mb-8 relative overflow-hidden text-white">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white opacity-5 rounded-full blur-3xl -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full blur-2xl -ml-5 -mb-5"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="inline-block px-3 py-1 rounded-lg bg-blue-800/50 border border-blue-700 text-blue-200 text-[10px] font-bold uppercase tracking-widest shadow-sm">
                    Kuis Online
                </span>
                <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
            </div>
            
            <h1 class="text-3xl font-black text-white mb-3 tracking-tight">{{ $assignment->title }}</h1>
            <p class="text-slate-300 leading-relaxed max-w-2xl">{{ $assignment->description }}</p>
            
            <div class="flex items-center gap-6 mt-8 text-sm font-bold text-slate-300 border-t border-white/10 pt-6">
                <span class="flex items-center gap-2"><i class="ph-fill ph-clock text-yellow-400"></i> {{ $assignment->duration_minutes }} Menit</span>
                <span class="flex items-center gap-2"><i class="ph-fill ph-list-numbers text-yellow-400"></i> {{ $assignment->questions->count() }} Soal</span>
            </div>
        </div>
    </div>

    <form action="{{ route('students.learning.assignment.quiz.submit', $assignment->id) }}" method="POST" id="quizForm">
        @csrf
        
        <div class="space-y-6">
            @foreach($assignment->questions as $index => $q)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative hover:shadow-md transition-shadow duration-300">
                    <span class="absolute top-6 left-6 w-8 h-8 rounded-lg bg-slate-100 text-slate-600 font-black flex items-center justify-center text-sm border border-slate-200">
                        {{ $index + 1 }}
                    </span>
                    
                    <div class="pl-12 md:pl-14">
                        <div class="text-lg font-medium text-slate-800 mb-6 leading-relaxed">
                            {!! nl2br(e($q->question_text)) !!}
                        </div>

                        <div class="space-y-3">
                            @if($q->question_type == 'multiple_choice')
                                @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                                    @if(isset($q->options[$opt]) && $q->options[$opt])
                                        <label class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all group relative overflow-hidden select-none">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="peer sr-only">
                                            
                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center text-xs font-bold text-transparent peer-checked:bg-blue-900 peer-checked:text-yellow-400 peer-checked:border-blue-900 transition-all mt-0.5 shrink-0 shadow-sm z-10">
                                                {{ $opt }}
                                            </div>
                                            
                                            <span class="text-slate-600 font-medium peer-checked:text-blue-900 relative z-10 transition-colors">
                                                {{ $q->options[$opt] }}
                                            </span>
                                            
                                            <div class="absolute inset-0 bg-blue-50 border-2 border-yellow-400 rounded-xl opacity-0 peer-checked:opacity-100 transition-all duration-200 pointer-events-none"></div>
                                        </label>
                                    @endif
                                @endforeach
                            @else
                                <textarea name="answers[{{ $q->id }}]" rows="4" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-slate-700 placeholder:text-slate-400" placeholder="Tuliskan jawaban Anda disini..."></textarea>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 mb-20 flex justify-end">
            <button type="submit" onclick="return confirm('Yakin ingin mengumpulkan jawaban? Anda tidak bisa mengubahnya setelah ini.')" class="px-8 py-4 bg-blue-900 text-yellow-400 font-bold rounded-2xl shadow-xl shadow-blue-900/20 hover:bg-blue-800 hover:-translate-y-1 transition-all flex items-center gap-3 border border-blue-800">
                <i class="ph-bold ph-paper-plane-right text-xl"></i> 
                <span>Kumpulkan Jawaban</span>
            </button>
        </div>
    </form>

</div>
@endsection