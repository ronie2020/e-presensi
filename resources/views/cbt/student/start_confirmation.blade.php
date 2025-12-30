<x-student-exam-layout>
    
    <div class="min-h-screen flex items-center justify-center p-4">
        
        {{-- Card Konfirmasi --}}
        <div class="max-w-xl w-full bg-white rounded-[2.5rem] shadow-2xl shadow-rose-900/10 border border-slate-200 overflow-hidden relative">
            
            {{-- Header Card --}}
            <div class="bg-slate-900 p-8 text-center relative overflow-hidden">
                {{-- Pattern --}}
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                
                {{-- Icon Gembok Besar --}}
                <div class="w-20 h-20 bg-rose-600 rounded-3xl mx-auto flex items-center justify-center text-white text-4xl shadow-xl shadow-rose-600/30 mb-6 relative z-10 animate-bounce-slow">
                    <i class="ph-duotone ph-lock-key-open"></i>
                </div>

                <h2 class="text-2xl font-black text-white relative z-10">{{ $exam->title }}</h2>
                <p class="text-slate-400 font-medium text-sm mt-1 relative z-10">{{ $exam->subject_name }}</p>

                {{-- Badge SEB --}}
                <div class="absolute top-6 right-6">
                    <span class="bg-emerald-500/10 text-emerald-400 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-500/20 flex items-center gap-1 uppercase tracking-wide backdrop-blur-md">
                        <i class="ph-fill ph-shield-check"></i> SEB Protected
                    </span>
                </div>
            </div>

            <div class="p-8">
                
                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <i class="ph-duotone ph-timer text-2xl text-rose-500 mb-2"></i>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Durasi</p>
                        <p class="text-lg font-black text-slate-800">{{ $exam->duration_minutes }} <span class="text-xs font-medium text-slate-500">Menit</span></p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <i class="ph-duotone ph-list-numbers text-2xl text-blue-500 mb-2"></i>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jumlah Soal</p>
                        <p class="text-lg font-black text-slate-800">{{ $exam->questions()->count() }} <span class="text-xs font-medium text-slate-500">Butir</span></p>
                    </div>
                </div>

                {{-- Warning Box --}}
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-8 flex gap-4 items-start">
                    <div class="shrink-0 mt-0.5">
                        <i class="ph-fill ph-warning-octagon text-amber-500 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-800 text-sm mb-1">Peraturan Penting</h4>
                        <ul class="text-xs text-amber-700 space-y-1.5 font-medium list-disc list-inside leading-relaxed">
                            <li>Waktu akan langsung berjalan setelah tombol "Mulai" ditekan.</li>
                            <li>Dilarang berpindah tab atau meminimize browser.</li>
                            <li>Sistem otomatis mendeteksi kecurangan.</li>
                        </ul>
                    </div>
                </div>

                {{-- Form Token --}}
                <form action="{{ route('student.exam.start', $exam->id) }}" method="POST">
                    @csrf
                    
                    @if($exam->token)
                        <div class="mb-8">
                            <label class="block text-center text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Masukkan Token Ujian</label>
                            <div class="relative max-w-xs mx-auto">
                                <input type="text" name="token" required 
                                    class="w-full rounded-2xl border-2 border-slate-200 shadow-sm focus:ring-0 focus:border-rose-500 text-center text-3xl font-black tracking-[0.5em] p-4 text-slate-800 placeholder-slate-200 uppercase transition-colors" 
                                    placeholder="TOKEN" autocomplete="off" maxlength="6">
                                
                                @if($errors->has('token'))
                                    <div class="absolute -bottom-8 left-0 right-0 text-center">
                                        <span class="text-rose-500 text-xs font-bold flex items-center justify-center gap-1">
                                            <i class="ph-bold ph-warning-circle"></i> {{ $errors->first('token') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 mt-3 text-center font-medium">*Token diberikan oleh pengawas ruangan</p>
                        </div>
                    @else
                        <div class="bg-blue-50 text-blue-700 p-4 rounded-xl mb-8 text-sm flex items-center justify-center gap-2 border border-blue-100 font-bold mx-auto max-w-xs">
                            <i class="ph-fill ph-info"></i>
                            Ujian ini tidak memerlukan token.
                        </div>
                    @endif

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('student.exam.index') }}" class="w-full sm:w-auto py-3 px-6 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-bold text-sm transition-colors text-center">
                            Batal
                        </a>

                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-rose-600 text-white rounded-xl font-bold hover:bg-rose-700 shadow-lg shadow-rose-600/30 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <span>Mulai Mengerjakan</span> 
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(5%); }
        }
        .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }
    </style>
</x-student-exam-layout>