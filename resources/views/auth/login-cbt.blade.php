<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Ujian Berbasis Komputer (CBT) - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>

    <!-- PWA META TAGS -->
    <link rel="manifest" href="{{ asset('manifest-siswa.json') }}">
    <meta name="theme-color" content="#021124">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-siswa-192x192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Icons -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        .bg-grid-dark {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float { animation: floatSlow 4s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-[#021124] text-white relative overflow-x-hidden flex flex-col justify-between selection:bg-rose-500 selection:text-white">

    <!-- GLOBAL FIXED BACKGROUND (Elegan, Tenang & Tidak Mengganggu) -->
    <div class="fixed inset-0 z-0 w-full h-full pointer-events-none overflow-hidden">
        {{-- Base Solid Canvas --}}
        <div class="absolute inset-0 bg-[#021124]"></div>

        {{-- Foto Gedung Sekolah Soft-Blur Ambient --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
             style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        
        {{-- Radial Vignette & Dark Gradient Overlay (Nuansa Ujian: Rose/Gold Aura) --}}
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(244,63,94,0.15)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-rose-950/30"></div>
        
        {{-- Subtle Grid Texture --}}
        <div class="absolute inset-0 bg-grid-dark opacity-35"></div>

        {{-- Ambient Glow Orbs (Rose & Royal Blue) --}}
        <div class="absolute -top-32 -right-20 w-[550px] h-[550px] bg-rose-600/15 rounded-full blur-[150px] pointer-events-none"></div>
        <div class="absolute top-1/3 -left-32 w-[500px] h-[500px] bg-[#0d52a1]/25 rounded-full blur-[170px] pointer-events-none"></div>
        <div class="absolute -bottom-32 right-1/3 w-[600px] h-[600px] bg-rose-700/10 rounded-full blur-[160px] pointer-events-none"></div>
    </div>

    <!-- TOP NAVIGATION BAR -->
    <header class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-5 flex items-center justify-between relative z-20">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group text-slate-300 hover:text-white transition-colors">
            <div class="w-10 h-10 rounded-2xl bg-white/10 group-hover:bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/15 transition-all text-rose-400 shadow-[0_0_15px_rgba(244,63,94,0.2)]">
                <i class="ph-bold ph-arrow-left text-lg"></i>
            </div>
            <div class="flex flex-col leading-tight">
                <span class="font-black text-white text-sm sm:text-base tracking-tight">SMPN 3 LAKBOK</span>
                <span class="text-[9px] font-bold text-rose-400 uppercase tracking-widest hidden sm:inline">Kembali ke Beranda</span>
            </div>
        </a>

        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('portal.index') }}" class="px-3.5 py-2 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm text-xs font-bold text-white border border-white/15 transition-all flex items-center gap-2">
                <i class="ph-bold ph-newspaper text-elevate-accent"></i>
                <span class="hidden sm:inline">Portal Publik</span>
            </a>
            <a href="{{ route('student.login.learning') }}" class="px-4 py-2 rounded-full bg-blue-500/20 hover:bg-blue-500/30 backdrop-blur-sm text-xs font-bold text-sky-300 hover:text-white border border-blue-500/30 transition-all flex items-center gap-2">
                <i class="ph-bold ph-books text-sky-400"></i>
                <span>Ruang Belajar (LMS)</span>
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-6 sm:py-10 relative z-20">
        <div class="w-full max-w-md">

            <!-- BRANDING & BADGE -->
            <div class="text-center mb-6">
                <div class="relative inline-block mb-3">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-tr from-rose-600 via-red-500 to-amber-500 p-[2px] shadow-[0_0_30px_rgba(244,63,94,0.35)] animate-float">
                        <div class="w-full h-full bg-[#021124] rounded-[22px] flex items-center justify-center p-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/images/logo.png'">
                        </div>
                    </div>
                    <span class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center text-base shadow-lg shadow-rose-600/50 border-2 border-[#021124]">
                        <i class="ph-bold ph-monitor-play text-amber-300"></i>
                    </span>
                </div>

                <!-- BADGE -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-rose-500/15 border border-rose-400/30 text-rose-300 text-[11px] font-bold uppercase tracking-widest mb-3 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>
                    <span>Sistem Ujian Online &bull; CBT</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Ujian Berbasis Komputer
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-sm mx-auto font-normal leading-relaxed">
                    Masukkan NISN untuk memasuki sesi ujian aktif, PTS, PAS, Asesmen Sekolah, atau kuis terjadwal.
                </p>
            </div>

            <!-- LOGIN CARD (Dark Glassmorphism) -->
            <div class="w-full bg-[#031d3d]/90 backdrop-blur-2xl rounded-[2.5rem] p-7 sm:p-9 border border-white/20 shadow-[0_20px_60px_rgba(0,0,0,0.6)] relative overflow-hidden group">
                
                <!-- Glowing Accent Line (Rose) -->
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-transparent via-rose-500 to-transparent opacity-80"></div>
                
                <!-- Ambient Blur Inside Card -->
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-rose-500/15 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-elevate-primary/20 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Alert Error / Notification -->
                @if (session('error'))
                    <div class="mb-5 bg-rose-500/20 text-rose-200 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold border border-rose-500/30 flex items-start gap-3 backdrop-blur-md">
                        <i class="ph-fill ph-warning-circle text-lg text-rose-400 shrink-0 mt-0.5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @error('student_id')
                    <div class="mb-5 bg-amber-500/20 text-amber-200 px-4 py-3 rounded-2xl text-xs font-semibold border border-amber-500/30 flex items-start gap-3 backdrop-blur-md">
                        <i class="ph-fill ph-clock-countdown text-lg text-amber-400 shrink-0 mt-0.5"></i>
                        <span>Terlalu banyak percobaan login. Silakan tunggu 1 menit sebelum mencoba lagi.</span>
                    </div>
                @enderror

                <!-- LOGIN FORM -->
                <form method="POST" action="{{ route('student.login.post') }}" class="space-y-5 relative z-10"
                      x-data="{ isLoggingIn: false }" @submit="isLoggingIn = true">
                    @csrf

                    <!-- Flag Asal: CBT (Ruang Ujian) -->
                    <input type="hidden" name="intended_app" value="cbt">

                    <!-- Input NISN -->
                    <div class="space-y-1.5">
                        <label for="student_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300 ml-1">
                            NISN Peserta Ujian
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-400 transition-colors">
                                <i class="ph-bold ph-identification-card text-xl"></i>
                            </div>
                            <input type="text"
                                   id="student_id"
                                   name="student_id"
                                   value="{{ old('student_id') }}"
                                   required
                                   autofocus
                                   autocomplete="off"
                                   placeholder="Ketik 10 digit NISN Anda"
                                   class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-[#021124]/70 border border-white/15 focus:border-rose-400 focus:bg-[#021124]/90 focus:ring-4 focus:ring-rose-500/20 text-white font-bold placeholder-slate-500 text-sm sm:text-base transition-all outline-none">
                        </div>
                        <p class="text-[11px] text-slate-400 ml-1 font-medium flex items-center gap-1">
                            <i class="ph-bold ph-shield-check text-rose-400"></i> Pastikan Anda berada di ruang ujian atau memiliki sesi aktif.
                        </p>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit"
                            :disabled="isLoggingIn"
                            class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-rose-600 via-rose-700 to-red-600 hover:from-rose-500 hover:via-rose-600 hover:to-red-500 active:scale-[0.99] text-white font-black text-sm sm:text-base tracking-wide shadow-[0_4px_20px_rgba(244,63,94,0.35)] hover:shadow-[0_6px_25px_rgba(244,63,94,0.5)] disabled:opacity-60 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2 group">
                        <template x-if="!isLoggingIn">
                            <span class="flex items-center gap-2">
                                <span>MASUK RUANG UJIAN</span>
                                <i class="ph-bold ph-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </template>
                        <template x-if="isLoggingIn">
                            <span class="flex items-center gap-2 text-white">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Menyiapkan Lembar Ujian...</span>
                            </span>
                        </template>
                    </button>
                </form>

                <!-- CARD FOOTER HELPER -->
                <div class="mt-6 pt-5 border-t border-white/10 text-center relative z-10">
                    <p class="text-xs text-slate-400 font-medium">
                        Kendala soal atau token ujian? 
                        <span class="font-bold text-rose-400">Hubungi Proktor / Pengawas</span>
                    </p>
                </div>
            </div>

            <!-- SEB INFO / COMPATIBILITY HINT -->
            <div class="mt-5 p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-slate-300">
                    <i class="ph-bold ph-shield-star text-emerald-400 text-lg"></i>
                    <span>Ujian memakai Safe Exam Browser?</span>
                </div>
                <a href="{{ route('seb.login') }}" class="font-bold text-emerald-400 hover:text-emerald-300 flex items-center gap-1 transition-colors">
                    <span>SEB Login</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            <!-- ACTIVE EXAM SCHEDULE WIDGET (Dark Glassmorphism) -->
            <div class="mt-6 bg-white/[0.04] backdrop-blur-xl rounded-[2rem] p-6 border border-white/15 shadow-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/20 flex items-center justify-center border border-amber-500/30 text-amber-400">
                        <i class="ph-fill ph-calendar-check text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-sm sm:text-base">Jadwal Ujian Aktif Hari Ini</h2>
                        <p class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                </div>

                @if(isset($activeExams) && $activeExams->count() > 0)
                    <div class="space-y-3">
                        @foreach($activeExams as $exam)
                            <div class="p-3.5 rounded-2xl bg-[#021124]/60 border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:border-rose-500/30 transition-all">
                                <div>
                                    <h3 class="font-bold text-white text-sm flex items-center gap-2">
                                        <i class="ph-bold ph-file-text text-rose-400"></i>
                                        {{ $exam->name }}
                                    </h3>
                                    <div class="flex items-center gap-3 mt-1 text-[11px] text-slate-400 font-medium">
                                        <span class="flex items-center gap-1">
                                            <i class="ph-bold ph-clock text-amber-400"></i>
                                            {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="ph-bold ph-users text-sky-400"></i>
                                            {{ $exam->event ? $exam->event->name : 'Semua Peserta' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Berlangsung
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 px-4 bg-white/[0.02] rounded-2xl border border-white/10 border-dashed">
                        <i class="ph-duotone ph-coffee text-3xl text-slate-400 mb-1.5 inline-block"></i>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-300">Tidak ada jadwal ujian hari ini</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Silakan istirahat atau pelajari materi di Ruang Belajar (LMS).</p>
                    </div>
                @endif
            </div>

        </div>
    </main>

    <!-- FOOTER & 4-DOTS INDICATOR -->
    <footer class="w-full relative z-20 pb-6 px-4 sm:px-8 max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-white/10 pt-5 text-xs text-slate-400">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-white/20"></span>
            <span class="w-2 h-2 rounded-full bg-white/20"></span>
            <span class="w-6 h-2 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.7)]"></span>
            <span class="w-2 h-2 rounded-full bg-white/20"></span>
        </div>
        <p class="font-medium">
            &copy; {{ date('Y') }} {{ config('app.name', 'SMP Negeri 3 Lakbok') }} &bull; Computer-Based Testing System
        </p>
    </footer>

</body>
</html>
