<x-student-exam-layout>
    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(5%); }
        }
        .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }
        
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

    <div class="min-h-screen flex items-center justify-center p-4 bg-[#020b18] text-slate-100 relative overflow-hidden" x-data="{ isSeb: navigator.userAgent.includes('SEB'), isSubmitting: false, formToken: '' }">
        
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-1/2 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        {{-- Card Konfirmasi --}}
        <div class="max-w-xl w-full bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[3rem] shadow-2xl border border-white/10 overflow-hidden relative transform transition-all my-8 backdrop-blur-xl">
            
            {{-- Header Card --}}
            <div class="bg-slate-900/60 p-8 md:p-10 text-center relative overflow-hidden group border-b border-white/10">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-sky-500/10 rounded-full blur-2xl group-hover:bg-sky-500/20 transition-all duration-1000"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-600/10 rounded-full blur-2xl group-hover:bg-blue-600/20 transition-all duration-1000"></div>
                
                {{-- Icon Gembok Besar --}}
                <div class="w-24 h-24 bg-sky-500/20 backdrop-blur-md border border-sky-400/30 rounded-[2rem] mx-auto flex items-center justify-center text-sky-300 text-5xl shadow-xl shadow-sky-500/20 mb-6 relative z-10 animate-bounce-slow">
                    <i class="ph-duotone ph-lock-key-open"></i>
                </div>

                <h2 class="text-2xl font-black text-white relative z-10 leading-tight">{{ $exam->title }}</h2>
                <div class="inline-flex items-center gap-2 mt-3 px-3.5 py-1 bg-sky-500/20 border border-sky-400/30 shadow-sm rounded-full relative z-10">
                    <i class="ph-fill ph-book-bookmark text-sky-400 text-xs"></i>
                    <p class="text-sky-300 font-bold text-xs uppercase tracking-wide">{{ $exam->subject_name }}</p>
                </div>

                {{-- Badge Status Lingkungan --}}
                <div class="absolute top-6 right-6 z-20">
                    <span x-show="isSeb" class="bg-emerald-500/20 text-emerald-300 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-500/30 flex items-center gap-1 uppercase tracking-wide backdrop-blur-md shadow-sm ring-animate" x-cloak>
                        <i class="ph-fill ph-shield-check"></i> Terproteksi SEB
                    </span>
                    <span x-show="!isSeb" class="bg-amber-500/20 text-amber-300 text-[10px] font-black px-3 py-1 rounded-full border border-amber-500/30 flex items-center gap-1 uppercase tracking-wide backdrop-blur-md shadow-sm" title="Akses via Browser Biasa">
                        <i class="ph-fill ph-warning-circle"></i> Browser Biasa
                    </span>
                </div>
            </div>

            <div class="p-8 md:p-10">
                
                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-5 bg-slate-900/70 rounded-[1.5rem] border border-white/10 text-center">
                        <div class="w-10 h-10 bg-amber-500/20 text-amber-300 rounded-[1rem] flex items-center justify-center shadow-sm mx-auto mb-2 text-xl border border-amber-500/30">
                            <i class="ph-fill ph-timer"></i>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Durasi</p>
                        <p class="text-lg font-black text-white">{{ $exam->duration_minutes }} <span class="text-xs font-bold text-slate-400">Mnt</span></p>
                    </div>
                    
                    {{-- Info Total Soal/Metode --}}
                    @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                        <div class="p-5 bg-emerald-500/15 rounded-[1.5rem] border border-emerald-500/30 text-center">
                            <div class="w-10 h-10 bg-emerald-500/20 text-emerald-400 rounded-[1rem] flex items-center justify-center shadow-sm mx-auto mb-2 text-xl border border-emerald-500/30">
                                <i class="ph-fill ph-google-logo"></i>
                            </div>
                            <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider mb-0.5">Metode</p>
                            <p class="text-lg font-black text-emerald-300">G-Form</p>
                        </div>
                    @else
                        <div class="p-5 bg-slate-900/70 rounded-[1.5rem] border border-white/10 text-center">
                            <div class="w-10 h-10 bg-sky-500/20 text-sky-300 rounded-[1rem] flex items-center justify-center shadow-sm mx-auto mb-2 text-xl border border-sky-400/30">
                                <i class="ph-fill ph-list-numbers"></i>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Total Soal</p>
                            <p class="text-lg font-black text-white">{{ $exam->questions()->count() }} <span class="text-xs font-bold text-slate-400">Butir</span></p>
                        </div>
                    @endif
                </div>

                {{-- Kotak Peringatan Aturan Ujian --}}
                <div class="bg-slate-900/60 border border-white/10 rounded-[1.5rem] p-5 mb-8">
                    <h4 class="font-black text-sky-400 text-sm mb-3 flex items-center gap-2">
                        <i class="ph-fill ph-info text-sky-400"></i> Tata Tertib Sistem
                    </h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-webcam text-sky-400 mt-0.5"></i>
                            <p class="text-xs text-slate-300 font-medium leading-relaxed"><b>Kamera Aktif:</b> Sistem akan memantau dan mengambil foto secara berkala selama ujian berlangsung.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-tabs text-rose-400 mt-0.5"></i>
                            <p class="text-xs text-rose-300 font-medium leading-relaxed"><b>Anti-Kecurangan:</b> Dilarang keras berpindah tab browser, minimize layar, atau membuka aplikasi lain. Pelanggaran akan menghentikan ujian otomatis.</p>
                        </li>
                    </ul>
                </div>

                {{-- Warning Box (Khusus Non-SEB) --}}
                <div x-show="!isSeb" class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-5 mb-6 flex gap-4 items-start" x-cloak>
                    <div class="shrink-0 mt-0.5 bg-rose-500/20 text-rose-400 rounded-[1rem] w-8 h-8 flex items-center justify-center">
                        <i class="ph-bold ph-warning"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-rose-300 text-sm mb-1">Peringatan Browser</h4>
                        <p class="text-xs text-rose-300/80 font-medium leading-relaxed">
                            Anda tidak menggunakan <b>Safe Exam Browser</b>. Pastikan pengawas mengizinkan ujian menggunakan browser biasa (Chrome/Edge).
                        </p>
                    </div>
                </div>

                {{-- Form Token --}}
                <form action="{{ route('student.exam.start', $exam->id) }}" method="POST" 
                      @submit="isSubmitting = true; try { document.documentElement.requestFullscreen() } catch(e) {}">
                    @csrf
                    
                    @if($exam->token)
                        <div class="mb-8">
                            <label class="block text-center text-xs font-black text-sky-400 uppercase tracking-widest mb-4">Masukkan Token Ujian</label>
                            <div class="relative max-w-[220px] mx-auto group">
                                <input type="text" name="token" required 
                                    x-model="formToken"
                                    @input="formToken = $event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                    class="w-full rounded-2xl border-2 border-white/15 shadow-sm focus:ring-4 focus:ring-sky-400/20 focus:border-sky-400 text-center text-3xl font-black tracking-[0.2em] p-4 text-white placeholder-slate-600 transition-all outline-none bg-slate-900" 
                                    placeholder="TOKEN" autocomplete="off" maxlength="6">
                                
                                @if($errors->has('token'))
                                    <div class="absolute -bottom-8 left-0 right-0 text-center animate-bounce">
                                        <span class="bg-rose-500/20 text-rose-300 px-3 py-1 rounded-full text-[10px] font-bold border border-rose-500/30 inline-flex items-center gap-1 shadow-sm">
                                            <i class="ph-bold ph-x-circle"></i> {{ $errors->first('token') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-[10px] text-sky-300 mt-4 text-center font-bold bg-sky-500/20 px-3.5 py-1.5 rounded-lg border border-sky-400/30 mx-auto block w-fit">
                                <i class="ph-fill ph-key"></i> Dapatkan Token dari Pengawas
                            </p>
                        </div>
                    @else
                        <div class="bg-emerald-500/15 text-emerald-300 p-4 rounded-2xl mb-8 text-sm flex items-center justify-center gap-3 border border-emerald-500/30 font-bold">
                            <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-check"></i>
                            </div>
                            Ujian ini tidak memerlukan token.
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-6 border-t border-white/10">
                        <a href="{{ route('student.exam.index') }}" :class="{ 'opacity-50 pointer-events-none': isSubmitting }" class="py-4 px-6 rounded-xl border border-white/10 bg-slate-900/80 text-slate-300 hover:bg-slate-800 hover:text-white font-bold text-sm transition-colors text-center order-2 sm:order-1 shadow-sm">
                            Kembali
                        </a>

                        <button type="submit" :disabled="isSubmitting" :class="{ 'opacity-70 cursor-not-allowed': isSubmitting }" class="py-4 px-6 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-xl font-bold hover:from-sky-400 hover:to-blue-500 shadow-lg shadow-sky-500/25 transition-all transform active:scale-95 flex items-center justify-center gap-2 order-1 sm:order-2 border border-transparent">
                            <template x-if="!isSubmitting">
                                <div class="flex items-center gap-2">
                                    <span>Mulai Ujian</span> 
                                    <i class="ph-bold ph-arrow-right"></i>
                                </div>
                            </template>
                            <template x-if="isSubmitting">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-spinner animate-spin"></i>
                                    <span>Memproses...</span>
                                </div>
                            </template>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-student-exam-layout>