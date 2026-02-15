<x-student-exam-layout>
    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(5%); }
        }
        .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }
        .bg-pattern { background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px; }
        
        /* Animasi Pulse untuk indikator status */
        @keyframes pulse-ring {
            0% { transform: scale(0.33); }
            80%, 100% { opacity: 0; }
        }
        .ring-animate::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid currentColor;
            animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
    </style>

    <div class="min-h-screen flex items-center justify-center p-4 bg-slate-50 bg-pattern" x-data="{ isSeb: navigator.userAgent.includes('SEB') }">
        
        {{-- Card Konfirmasi --}}
        <div class="max-w-xl w-full bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200 border border-white overflow-hidden relative transform transition-all">
            
            {{-- Header Card --}}
            <div class="bg-slate-900 p-10 text-center relative overflow-hidden group">
                {{-- Decoration --}}
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900 to-rose-900/40"></div>
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-rose-500/20 rounded-full blur-3xl group-hover:bg-rose-500/30 transition-all duration-1000"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all duration-1000"></div>
                
                {{-- Icon Gembok Besar --}}
                <div class="w-24 h-24 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl mx-auto flex items-center justify-center text-white text-5xl shadow-2xl mb-6 relative z-10 animate-bounce-slow">
                    <i class="ph-duotone ph-lock-key-open text-rose-400"></i>
                </div>

                <h2 class="text-2xl font-black text-white relative z-10 leading-tight">{{ $exam->title }}</h2>
                <div class="inline-flex items-center gap-2 mt-3 px-3 py-1 bg-white/5 border border-white/10 rounded-full relative z-10">
                    <i class="ph-fill ph-book-bookmark text-blue-400 text-xs"></i>
                    <p class="text-slate-300 font-bold text-xs uppercase tracking-wide">{{ $exam->subject_name }}</p>
                </div>

                {{-- Badge Status Lingkungan (SEB vs Browser Biasa) --}}
                <div class="absolute top-6 right-6 z-20">
                    {{-- Tampil jika di dalam SEB --}}
                    <span x-show="isSeb" class="bg-emerald-500/20 text-emerald-300 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-500/30 flex items-center gap-1 uppercase tracking-wide backdrop-blur-md shadow-sm ring-animate text-emerald-400">
                        <i class="ph-fill ph-shield-check"></i> Terproteksi SEB
                    </span>

                    {{-- Tampil jika BUKAN SEB (Browser Biasa) --}}
                    <span x-show="!isSeb" class="bg-amber-500/20 text-amber-300 text-[10px] font-black px-3 py-1 rounded-full border border-amber-500/30 flex items-center gap-1 uppercase tracking-wide backdrop-blur-md shadow-sm" title="Akses via Browser Biasa">
                        <i class="ph-fill ph-warning-circle"></i> Browser Biasa
                    </span>
                </div>
            </div>

            <div class="p-8 md:p-10">
                
                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100 text-center hover:bg-slate-100 transition-colors">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-2 text-rose-500 text-xl border border-slate-100">
                            <i class="ph-fill ph-timer"></i>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Durasi</p>
                        <p class="text-lg font-black text-slate-800">{{ $exam->duration_minutes }} <span class="text-xs font-bold text-slate-500">Mnt</span></p>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100 text-center hover:bg-slate-100 transition-colors">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-2 text-blue-500 text-xl border border-slate-100">
                            <i class="ph-fill ph-list-numbers"></i>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Total Soal</p>
                        <p class="text-lg font-black text-slate-800">{{ $exam->questions()->count() }} <span class="text-xs font-bold text-slate-500">Butir</span></p>
                    </div>
                </div>

                {{-- Warning Box (Khusus Non-SEB) --}}
                <div x-show="!isSeb" class="bg-rose-50 border border-rose-100 rounded-2xl p-5 mb-6 flex gap-4 items-start animate-pulse">
                    <div class="shrink-0 mt-0.5 bg-rose-100 text-rose-600 rounded-lg w-8 h-8 flex items-center justify-center">
                        <i class="ph-bold ph-warning"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-rose-800 text-sm mb-1">Peringatan Browser</h4>
                        <p class="text-xs text-rose-700 font-medium leading-relaxed">
                            Anda tidak menggunakan <b>Safe Exam Browser</b>. Pastikan pengawas mengizinkan ujian menggunakan browser biasa (Chrome/Edge).
                        </p>
                    </div>
                </div>

                {{-- Form Token --}}
                <form action="{{ route('student.exam.start', $exam->id) }}" method="POST">
                    @csrf
                    
                    @if($exam->token)
                        <div class="mb-8">
                            <label class="block text-center text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Masukkan Token Ujian</label>
                            <div class="relative max-w-[200px] mx-auto group">
                                <input type="text" name="token" required 
                                    class="w-full rounded-2xl border-2 border-slate-200 shadow-sm focus:ring-4 focus:ring-rose-100 focus:border-rose-500 text-center text-3xl font-black tracking-[0.2em] p-4 text-slate-800 placeholder-slate-200 uppercase transition-all outline-none" 
                                    placeholder="TOKEN" autocomplete="off" maxlength="6">
                                
                                @if($errors->has('token'))
                                    <div class="absolute -bottom-8 left-0 right-0 text-center animate-bounce">
                                        <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-full text-[10px] font-bold border border-rose-100 inline-flex items-center gap-1 shadow-sm">
                                            <i class="ph-bold ph-x-circle"></i> {{ $errors->first('token') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 mt-4 text-center font-bold bg-slate-50 inline-block px-3 py-1 rounded-lg border border-slate-100 mx-auto block w-fit">
                                <i class="ph-fill ph-key"></i> Token dari Pengawas
                            </p>
                        </div>
                    @else
                        <div class="bg-blue-50 text-blue-700 p-4 rounded-2xl mb-8 text-sm flex items-center justify-center gap-3 border border-blue-100 font-bold">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-check"></i>
                            </div>
                            Ujian ini tidak memerlukan token.
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('student.exam.index') }}" class="py-3.5 px-6 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-bold text-sm transition-colors text-center order-2 sm:order-1">
                            Batal
                        </a>

                        <button type="submit" class="py-3.5 px-6 bg-slate-900 text-white rounded-xl font-bold hover:bg-rose-600 shadow-lg shadow-slate-900/20 hover:shadow-rose-600/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 order-1 sm:order-2">
                            <span>Mulai Mengerjakan</span> 
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-student-exam-layout>