<x-student-exam-layout>
    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(5%); }
        }
        .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }
        
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

    <div class="min-h-screen flex items-center justify-center p-4 bg-slate-50" x-data="{ isSeb: navigator.userAgent.includes('SEB'), isSubmitting: false, formToken: '' }">
        
        {{-- Card Konfirmasi --}}
        <div class="max-w-xl w-full bg-white rounded-[3rem] shadow-2xl shadow-[#56bbf1]/10 border border-slate-100 overflow-hidden relative transform transition-all my-8">
            
            {{-- Header Card (TEMA ELEVATE GRADIENT) --}}
            <div class="bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-10 text-center relative overflow-hidden group border-b border-white/60">
                {{-- Decoration --}}
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/40 rounded-full blur-2xl group-hover:bg-white/50 transition-all duration-1000"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#0d52a1]/10 rounded-full blur-2xl group-hover:bg-[#0d52a1]/20 transition-all duration-1000"></div>
                
                {{-- Icon Gembok Besar --}}
                <div class="w-24 h-24 bg-white/60 backdrop-blur-md border border-white rounded-[2rem] mx-auto flex items-center justify-center text-[#0d52a1] text-5xl shadow-xl shadow-[#56bbf1]/20 mb-6 relative z-10 animate-bounce-slow">
                    <i class="ph-duotone ph-lock-key-open"></i>
                </div>

                <h2 class="text-2xl font-black text-[#2c3f61] relative z-10 leading-tight">{{ $exam->title }}</h2>
                <div class="inline-flex items-center gap-2 mt-3 px-3 py-1 bg-white/60 border border-white shadow-sm rounded-full relative z-10">
                    <i class="ph-fill ph-book-bookmark text-[#0d52a1] text-xs"></i>
                    <p class="text-[#2c3f61] font-bold text-xs uppercase tracking-wide">{{ $exam->subject_name }}</p>
                </div>

                {{-- Badge Status Lingkungan --}}
                <div class="absolute top-6 right-6 z-20">
                    <span x-show="isSeb" class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-200 flex items-center gap-1 uppercase tracking-wide backdrop-blur-md shadow-sm ring-animate" x-cloak>
                        <i class="ph-fill ph-shield-check"></i> Terproteksi SEB
                    </span>
                    <span x-show="!isSeb" class="bg-[#f9a282]/20 text-[#c86845] text-[10px] font-black px-3 py-1 rounded-full border border-[#f9a282]/40 flex items-center gap-1 uppercase tracking-wide backdrop-blur-md shadow-sm" title="Akses via Browser Biasa">
                        <i class="ph-fill ph-warning-circle"></i> Browser Biasa
                    </span>
                </div>
            </div>

            <div class="p-8 md:p-10">
                
                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100 text-center">
                        <div class="w-10 h-10 bg-white rounded-[1rem] flex items-center justify-center shadow-sm mx-auto mb-2 text-[#f9a282] text-xl border border-slate-100">
                            <i class="ph-fill ph-timer"></i>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Durasi</p>
                        <p class="text-lg font-black text-[#2c3f61]">{{ $exam->duration_minutes }} <span class="text-xs font-bold text-slate-500">Mnt</span></p>
                    </div>
                    
                    {{-- Info Total Soal/Metode --}}
                    @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                        <div class="p-5 bg-emerald-50 rounded-[1.5rem] border border-emerald-100 text-center">
                            <div class="w-10 h-10 bg-white rounded-[1rem] flex items-center justify-center shadow-sm mx-auto mb-2 text-emerald-500 text-xl border border-emerald-100">
                                <i class="ph-fill ph-google-logo"></i>
                            </div>
                            <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mb-0.5">Metode</p>
                            <p class="text-lg font-black text-emerald-800">G-Form</p>
                        </div>
                    @else
                        <div class="p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100 text-center">
                            <div class="w-10 h-10 bg-white rounded-[1rem] flex items-center justify-center shadow-sm mx-auto mb-2 text-[#56bbf1] text-xl border border-slate-100">
                                <i class="ph-fill ph-list-numbers"></i>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Total Soal</p>
                            <p class="text-lg font-black text-[#2c3f61]">{{ $exam->questions()->count() }} <span class="text-xs font-bold text-slate-500">Butir</span></p>
                        </div>
                    @endif
                </div>

                {{-- Kotak Peringatan Aturan Ujian --}}
                <div class="bg-[#e5eff5]/50 border border-[#56bbf1]/30 rounded-[1.5rem] p-5 mb-8">
                    <h4 class="font-black text-[#0d52a1] text-sm mb-3 flex items-center gap-2"><i class="ph-fill ph-info text-[#56bbf1]"></i> Tata Tertib Sistem</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-webcam text-[#56bbf1] mt-0.5"></i>
                            <p class="text-xs text-[#2c3f61] font-medium leading-relaxed"><b>Kamera Aktif:</b> Sistem akan memantau dan mengambil foto secara berkala selama ujian berlangsung.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-tabs text-rose-500 mt-0.5"></i>
                            <p class="text-xs text-rose-800 font-medium leading-relaxed"><b>Anti-Kecurangan:</b> Dilarang keras berpindah tab browser, minimize layar, atau membuka aplikasi lain. Pelanggaran akan menghentikan ujian otomatis.</p>
                        </li>
                    </ul>
                </div>

                {{-- Warning Box (Khusus Non-SEB) --}}
                <div x-show="!isSeb" class="bg-rose-50 border border-rose-100 rounded-2xl p-5 mb-6 flex gap-4 items-start" x-cloak>
                    <div class="shrink-0 mt-0.5 bg-rose-100 text-rose-600 rounded-[1rem] w-8 h-8 flex items-center justify-center">
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
                <form action="{{ route('student.exam.start', $exam->id) }}" method="POST" 
                      @submit="isSubmitting = true; try { document.documentElement.requestFullscreen() } catch(e) {}">
                    @csrf
                    
                    @if($exam->token)
                        <div class="mb-8">
                            <label class="block text-center text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Masukkan Token Ujian</label>
                            <div class="relative max-w-[200px] mx-auto group">
                                <input type="text" name="token" required 
                                    x-model="formToken"
                                    @input="formToken = $event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                    class="w-full rounded-2xl border-2 border-slate-200 shadow-sm focus:ring-4 focus:ring-[#56bbf1]/20 focus:border-[#56bbf1] text-center text-3xl font-black tracking-[0.2em] p-4 text-[#2c3f61] placeholder-slate-200 transition-all outline-none bg-slate-50" 
                                    placeholder="TOKEN" autocomplete="off" maxlength="6">
                                
                                @if($errors->has('token'))
                                    <div class="absolute -bottom-8 left-0 right-0 text-center animate-bounce">
                                        <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-full text-[10px] font-bold border border-rose-100 inline-flex items-center gap-1 shadow-sm">
                                            <i class="ph-bold ph-x-circle"></i> {{ $errors->first('token') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-[10px] text-[#0d52a1] mt-4 text-center font-bold bg-[#e5eff5] px-3 py-1.5 rounded-lg border border-[#56bbf1]/30 mx-auto block w-fit">
                                <i class="ph-fill ph-key"></i> Dapatkan Token dari Pengawas
                            </p>
                        </div>
                    @else
                        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl mb-8 text-sm flex items-center justify-center gap-3 border border-emerald-100 font-bold">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-check"></i>
                            </div>
                            Ujian ini tidak memerlukan token.
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('student.exam.index') }}" :class="{ 'opacity-50 pointer-events-none': isSubmitting }" class="py-4 px-6 rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-[#0d52a1] font-bold text-sm transition-colors text-center order-2 sm:order-1 shadow-sm">
                            Kembali
                        </a>

                        <button type="submit" :disabled="isSubmitting" :class="{ 'opacity-70 cursor-not-allowed': isSubmitting }" class="py-4 px-6 bg-[#2c3f61] text-white rounded-xl font-bold hover:bg-[#1c2940] shadow-lg shadow-[#2c3f61]/20 transition-all transform active:scale-95 flex items-center justify-center gap-2 order-1 sm:order-2 border border-transparent">
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