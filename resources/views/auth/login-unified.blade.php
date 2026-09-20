<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Masuk - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>

        <!-- PWA META TAGS -->
        <link rel="manifest" href="{{ asset('manifest-guru.json') }}">
        <meta name="theme-color" content="#021124">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-guru-192x192.png') }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="SIMADU Lakbok">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }

            /* Portal Pulse & Orbit Animations */
            @keyframes portalPulse {
                0%, 100% {
                    box-shadow: 0 0 35px rgba(86, 187, 241, 0.4), 0 0 70px rgba(13, 82, 161, 0.3);
                    transform: scale(1);
                }
                50% {
                    box-shadow: 0 0 55px rgba(86, 187, 241, 0.7), 0 0 95px rgba(13, 82, 161, 0.45);
                    transform: scale(1.02);
                }
            }
            .animate-portal-pulse {
                animation: portalPulse 4s ease-in-out infinite;
            }

            @keyframes floatSlow {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-7px) rotate(1deg); }
            }
            .animate-float-badge {
                animation: floatSlow 4s ease-in-out infinite;
            }
        </style>
    </head>

    <body class="text-white antialiased min-h-screen bg-[#021124] relative overflow-x-hidden flex flex-col justify-between selection:bg-elevate-accent selection:text-elevate-dark">

        {{-- ===== TENTUKAN TAB AKTIF AWAL ===== --}}
        @php
            if ($errors->has('student_id')) {
                $initTab = 'siswa';
            } elseif ($errors->has('email') || $errors->has('password')) {
                $initTab = 'guru';
            } else {
                $initTab = $activeTab ?? 'guru';
            }
        @endphp

        <!-- GLOBAL FIXED BACKGROUND (Elegan, Tenang & Tidak Mengganggu) -->
        <div class="fixed inset-0 z-0 w-full h-full pointer-events-none overflow-hidden">
            {{-- Base Solid Canvas --}}
            <div class="absolute inset-0 bg-[#021124]"></div>

            {{-- Foto Sekolah Background: Dibuat Halus, Soft-Blur & Ambient Transparan (Tidak silau/bising) --}}
            <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
                 style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
            
            {{-- Deep Solid Navy Vignette & Gradient Overlay --}}
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.18)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
            <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
            
            {{-- Subtle Architectural Cyber Grid --}}
            <div class="absolute inset-0 bg-grid-dark opacity-35"></div>

            {{-- Floating Ambient Glow Orbs (Aurora / Nebula Ambient) --}}
            <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-elevate-accent/15 rounded-full blur-[160px] pointer-events-none"></div>
            <div class="absolute top-1/3 right-1/4 w-[550px] h-[550px] bg-elevate-primary/25 rounded-full blur-[180px] pointer-events-none"></div>
            <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[150px] pointer-events-none"></div>
        </div>

        <!-- TOP BAR / HEADER -->
        <header class="w-full relative z-20 pt-5 px-4 sm:px-8 lg:px-12 max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-2 flex items-center justify-center text-elevate-accent shadow-[0_0_15px_rgba(86,187,241,0.2)] group-hover:scale-105 transition-transform shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/images/logo.png'">
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-black text-white text-base sm:text-lg tracking-tight group-hover:text-elevate-accent transition-colors">SMPN 3 LAKBOK</span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-elevate-accent uppercase tracking-widest">Sistem Informasi Terpadu</span>
                </div>
            </a>

            <div class="flex items-center gap-2.5 sm:gap-3">
                <a href="{{ route('portal.index') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15 backdrop-blur-sm">
                    <i class="ph-bold ph-newspaper text-elevate-accent"></i> Portal Publik
                </a>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-black shadow-sm transition-all border border-white/20 backdrop-blur-sm">
                    <i class="ph-bold ph-house text-elevate-accent"></i>
                    <span>Beranda</span>
                </a>
            </div>
        </header>

        <!-- MAIN SPLIT-SCREEN CONTAINER (Penyelarasan Opsi 1) -->
        <main class="w-full max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-8 sm:py-12 relative z-20 my-auto flex items-center"
              x-data="{ tab: '{{ $initTab }}' }">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-center w-full">
                
                {{-- ======================================================== --}}
                {{-- SISI KIRI (7 COLS): WELCOME SHOWCASE & GLOWING PORTAL    --}}
                {{-- ======================================================== --}}
                <div class="lg:col-span-7 flex flex-col justify-center relative rounded-[2.5rem] bg-white/[0.04] backdrop-blur-2xl border border-white/15 p-7 sm:p-10 lg:p-11 shadow-[0_20px_60px_rgba(0,0,0,0.4)] group">
                    {{-- Inner ambient glow --}}
                    <div class="absolute -top-24 -right-24 w-52 h-52 bg-elevate-accent/15 rounded-full blur-3xl pointer-events-none transition-transform duration-700 group-hover:scale-125"></div>
                    
                    <!-- Pill Tag -->
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white text-[11px] sm:text-xs font-bold tracking-widest shadow-sm mb-5 backdrop-blur-md w-fit">
                        <i class="ph-duotone ph-sparkle text-elevate-accent text-sm"></i>
                        <span class="uppercase">SIMADU &bull; E-Presensi &amp; E-Learning</span>
                    </div>

                    <!-- Headline Besar -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight mb-4 leading-[1.14]">
                        Selamat Datang di<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#72cbfa] to-white drop-shadow-md">
                            Portal SIMADU.
                        </span>
                    </h1>

                    <!-- Sub-judul -->
                    <p class="text-xs sm:text-sm text-slate-200 mb-6 leading-relaxed max-w-xl font-medium">
                        Satu gerbang resmi sekolah untuk absensi GPS pintar, akses materi belajar kurikulum merdeka (LMS), ujian berbasis komputer (CBT), dan data akademik terintegrasi.
                    </p>

                    <!-- SHOWCASE: PORTAL CIRCULAR RING DENGAN MASKOT SISWA & 6 ORBITING BADGES -->
                    <div class="relative w-64 h-64 sm:w-72 sm:h-72 my-2 mx-auto lg:mx-0 flex items-center justify-center">
                        
                        {{-- Background Radial Ambiance --}}
                        <div class="absolute inset-0 rounded-full bg-elevate-accent/25 blur-3xl pointer-events-none"></div>

                        {{-- Glowing Circular Portal Ring --}}
                        <div class="relative w-56 h-56 sm:w-64 sm:h-64 rounded-full border-[3.5px] border-elevate-accent shadow-[0_0_50px_rgba(86,187,241,0.65)] ring-8 ring-elevate-accent/20 bg-gradient-to-br from-[#021124] via-[#0d52a1] to-[#2c3f61] overflow-hidden flex items-center justify-center animate-portal-pulse group">
                            <div class="absolute inset-0 bg-radial-at-c from-elevate-accent/30 via-transparent to-black/60 pointer-events-none z-10"></div>

                            {{-- Gambar Karakter Siswa Digital --}}
                            <img src="{{ asset('images/portal-student.png') }}" 
                                 alt="Siswa Digital SMPN 3 Lakbok" 
                                 class="w-full h-full object-cover object-center relative z-0 transition-transform duration-700 group-hover:scale-105 filter drop-shadow-2xl"
                                 onerror="this.onerror=null; this.src='{{ asset('images/netila.jpg') }}';">

                            <div class="absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-[#021124]/90 via-[#021124]/40 to-transparent z-10 pointer-events-none"></div>
                            <div class="absolute bottom-2 text-center z-20">
                                <span class="text-[9px] font-black uppercase tracking-widest text-elevate-accent bg-[#021124]/80 backdrop-blur-md px-2.5 py-0.5 rounded-full border border-elevate-accent/30">
                                    SMPN 3 Lakbok
                                </span>
                            </div>
                        </div>

                        {{-- 6 BUBBLE BADGES IKONIK MENGORBIT (Persis Mockup) --}}
                        {{-- Badge 1: Top-Left (LMS Modul Belajar) --}}
                        <div class="absolute top-1 left-2 w-11 h-11 rounded-full bg-gradient-to-tr from-sky-600/90 to-cyan-400/90 p-0.5 shadow-[0_0_20px_rgba(56,189,248,0.7)] animate-float-badge z-20">
                            <div class="w-full h-full rounded-full bg-[#021124]/80 backdrop-blur-md flex items-center justify-center text-white text-base">
                                <i class="ph-bold ph-books text-cyan-300"></i>
                            </div>
                        </div>

                        {{-- Badge 2: Top-Right (Ujian CBT Online) --}}
                        <div class="absolute top-0 right-4 w-11 h-11 rounded-full bg-gradient-to-tr from-blue-600/90 to-indigo-400/90 p-0.5 shadow-[0_0_20px_rgba(99,102,241,0.7)] animate-float-badge z-20" style="animation-delay: 1s;">
                            <div class="w-full h-full rounded-full bg-[#021124]/80 backdrop-blur-md flex items-center justify-center text-white text-base">
                                <i class="ph-bold ph-monitor-play text-sky-300"></i>
                            </div>
                        </div>

                        {{-- Badge 3: Mid-Left (Presensi GPS) --}}
                        <div class="absolute top-1/2 -left-4 -translate-y-1/2 w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-500/90 to-teal-300/90 p-0.5 shadow-[0_0_18px_rgba(16,185,129,0.7)] animate-float-badge z-20" style="animation-delay: 2s;">
                            <div class="w-full h-full rounded-full bg-[#021124]/80 backdrop-blur-md flex items-center justify-center text-white text-sm">
                                <i class="ph-bold ph-map-pin text-emerald-300"></i>
                            </div>
                        </div>

                        {{-- Badge 4: Mid-Right (Grafik Kehadiran) --}}
                        <div class="absolute top-1/2 -right-4 -translate-y-1/2 w-11 h-11 rounded-full bg-gradient-to-tr from-blue-500/90 to-elevate-accent p-0.5 shadow-[0_0_20px_rgba(86,187,241,0.8)] animate-float-badge z-20" style="animation-delay: 1.5s;">
                            <div class="w-full h-full rounded-full bg-[#021124]/80 backdrop-blur-md flex items-center justify-center text-white text-base">
                                <i class="ph-bold ph-chart-line-up text-elevate-accent"></i>
                            </div>
                        </div>

                        {{-- Badge 5: Bottom-Left (Perpustakaan Digital) --}}
                        <div class="absolute -bottom-1 left-4 w-10 h-10 rounded-full bg-gradient-to-tr from-purple-600/90 to-fuchsia-400/90 p-0.5 shadow-[0_0_18px_rgba(192,132,252,0.7)] animate-float-badge z-20" style="animation-delay: 2.5s;">
                            <div class="w-full h-full rounded-full bg-[#021124]/80 backdrop-blur-md flex items-center justify-center text-white text-sm">
                                <i class="ph-bold ph-book-open-text text-purple-300"></i>
                            </div>
                        </div>

                        {{-- Badge 6: Bottom-Right (Suara Siswa) --}}
                        <div class="absolute bottom-1 right-2 w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500/90 to-orange-400/90 p-0.5 shadow-[0_0_18px_rgba(245,158,11,0.7)] animate-float-badge z-20" style="animation-delay: 3s;">
                            <div class="w-full h-full rounded-full bg-[#021124]/80 backdrop-blur-md flex items-center justify-center text-white text-sm">
                                <i class="ph-bold ph-megaphone-simple text-amber-300"></i>
                            </div>
                        </div>
                    </div>

                    <!-- 4 CHECKLIST PILLS (Sesuai Mockup) -->
                    <div class="grid grid-cols-2 gap-2.5 max-w-lg mt-6">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Presensi GPS Pintar</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Modul Belajar LMS</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Ujian CBT Online</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Akses Terpadu Resmi</span>
                        </div>
                    </div>

                </div>


                {{-- ======================================================== --}}
                {{-- SISI KANAN (5 COLS): FOCUSED DARK GLASSMORPHISM LOGIN    --}}
                {{-- ======================================================== --}}
                <div class="lg:col-span-5 flex justify-center">
                    
                    <div class="w-full max-w-[460px] bg-[#031d3d]/90 backdrop-blur-2xl rounded-[2.5rem] p-7 sm:p-9 border border-white/20 shadow-[0_20px_60px_rgba(0,0,0,0.6)] relative overflow-hidden group">
                        
                        <!-- Glowing Accent Line di atas kartu -->
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-transparent via-elevate-accent to-transparent opacity-80"></div>
                        
                        <!-- Ambient Blur Inside Card -->
                        <div class="absolute -top-16 -right-16 w-36 h-36 bg-elevate-accent/15 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-elevate-primary/20 rounded-full blur-2xl pointer-events-none"></div>

                        <!-- Card Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10 relative z-10">
                            <div>
                                <h2 class="text-2xl font-black text-white tracking-tight">Masuk ke Akun</h2>
                                <p class="text-xs text-slate-300 font-medium mt-0.5">Pilih peran Anda untuk melanjutkan</p>
                            </div>
                            <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-elevate-accent shadow-sm">
                                <i class="ph-fill ph-lock-key text-xl"></i>
                            </div>
                        </div>

                        <!-- TAB SWITCHER (Guru / Staff vs Siswa) -->
                        <div class="relative mb-6 bg-black/30 border border-white/10 p-1 rounded-xl flex gap-1 backdrop-blur-md z-10">
                            <button type="button"
                                    @click="tab = 'guru'"
                                    :class="tab === 'guru'
                                        ? 'bg-elevate-accent text-elevate-dark font-black shadow-md'
                                        : 'text-white/80 hover:text-white font-bold'"
                                    class="flex-1 flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg text-xs transition-all duration-200 cursor-pointer">
                                <i class="ph-bold ph-chalkboard-teacher text-sm"></i>
                                <span>Guru / Staff</span>
                            </button>
                            <button type="button"
                                    @click="tab = 'siswa'"
                                    :class="tab === 'siswa'
                                        ? 'bg-elevate-accent text-elevate-dark font-black shadow-md'
                                        : 'text-white/80 hover:text-white font-bold'"
                                    class="flex-1 flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg text-xs transition-all duration-200 cursor-pointer">
                                <i class="ph-bold ph-student text-sm"></i>
                                <span>Siswa</span>
                            </button>
                        </div>

                        <!-- FORM GURU / STAFF -->
                        <div x-show="tab === 'guru'" class="relative z-10 transition-opacity duration-200">
                            @if (session('status'))
                                <div class="mb-4 bg-emerald-500/20 text-emerald-300 px-3.5 py-2.5 rounded-xl text-xs font-semibold border border-emerald-500/30 flex items-center gap-2">
                                    <i class="ph-bold ph-check-circle text-base shrink-0"></i>
                                    <span>{{ session('status') }}</span>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="space-y-4"
                                  x-data="{ isLoggingIn: false }" @submit="isLoggingIn = true">
                                @csrf

                                <!-- Email / NIP -->
                                <div class="space-y-1">
                                    <label for="email" class="text-[11px] font-black text-slate-300 uppercase tracking-wider">Email / NIP</label>
                                    <div class="relative group">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-elevate-accent transition-colors">
                                            <i class="ph-duotone ph-envelope-simple text-lg"></i>
                                        </div>
                                        <input id="email"
                                            class="block w-full rounded-xl border border-white/20 bg-black/25 py-3 pl-10 pr-3.5 text-xs text-white placeholder-slate-400 focus:border-elevate-accent focus:bg-black/35 focus:ring-1 focus:ring-elevate-accent transition-all outline-none"
                                            type="email" name="email" :value="old('email')"
                                            required autofocus autocomplete="username"
                                            placeholder="nama@sekolah.sch.id" />
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-[11px] text-rose-300 font-semibold" />
                                </div>

                                <!-- Password -->
                                <div class="space-y-1">
                                    <div class="flex justify-between items-center">
                                        <label for="password" class="text-[11px] font-black text-slate-300 uppercase tracking-wider">Kata Sandi</label>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-elevate-accent hover:underline">
                                                Lupa Sandi?
                                            </a>
                                        @endif
                                    </div>
                                    <div class="relative group" x-data="{ show: false }">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-elevate-accent transition-colors">
                                            <i class="ph-duotone ph-lock-key text-lg"></i>
                                        </div>
                                        <input id="password"
                                            class="block w-full rounded-xl border border-white/20 bg-black/25 py-3 pl-10 pr-10 text-xs text-white placeholder-slate-400 focus:border-elevate-accent focus:bg-black/35 focus:ring-1 focus:ring-elevate-accent transition-all outline-none"
                                            ::type="show ? 'text' : 'password'"
                                            name="password" required autocomplete="current-password"
                                            placeholder="••••••••" />
                                        <button type="button" @click="show = !show"
                                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-elevate-accent transition-colors"
                                                tabindex="-1">
                                            <i class="ph-bold text-base" :class="show ? 'ph-eye' : 'ph-eye-slash'"></i>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-[11px] text-rose-300 font-semibold" />
                                </div>

                                <!-- Remember Me -->
                                <div class="flex items-center pt-0.5">
                                    <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                                        <input id="remember_me" type="checkbox"
                                               class="rounded border-white/20 bg-white/10 text-elevate-accent shadow-sm focus:ring-elevate-accent/40 cursor-pointer"
                                               name="remember">
                                        <span class="ml-2 text-xs font-semibold text-slate-300 group-hover:text-white transition-colors">Ingat Saya</span>
                                    </label>
                                </div>

                                <!-- Tombol Masuk Guru -->
                                <button type="submit"
                                        :disabled="isLoggingIn"
                                        class="w-full py-3.5 px-4 rounded-xl bg-elevate-accent hover:bg-[#72cbfa] text-elevate-dark font-black text-xs uppercase tracking-wider shadow-lg shadow-elevate-accent/25 hover:shadow-elevate-accent/40 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98">
                                    <span x-show="!isLoggingIn" class="flex items-center gap-1.5">
                                        <span>Masuk Sekarang</span> <i class="ph-bold ph-arrow-right"></i>
                                    </span>
                                    <span x-show="isLoggingIn" class="flex items-center gap-2" style="display:none;">
                                        <i class="ph-bold ph-spinner animate-spin"></i> Memverifikasi...
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- FORM SISWA -->
                        <div x-show="tab === 'siswa'" x-cloak class="relative z-10 transition-opacity duration-200">
                            @if (session('error'))
                                <div class="mb-4 bg-rose-500/20 text-rose-300 px-3.5 py-2.5 rounded-xl text-xs font-semibold border border-rose-500/30 flex items-center gap-2">
                                    <i class="ph-bold ph-warning-circle text-base shrink-0"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                            @endif

                            @error('throttle')
                                <div class="mb-4 bg-amber-500/20 text-amber-300 px-3.5 py-2.5 rounded-xl text-xs font-semibold border border-amber-500/30">
                                    <span>Terlalu banyak percobaan. Silakan tunggu sebentar.</span>
                                </div>
                            @enderror

                            <form method="POST" action="{{ route('student.login.post') }}" class="space-y-4"
                                  x-data="{ isLoggingIn: false }" @submit="isLoggingIn = true">
                                @csrf

                                <!-- Info Cepat Siswa -->
                                <div class="p-3 rounded-xl bg-white/10 border border-white/15 text-[11px] text-slate-200 leading-relaxed flex items-start gap-2">
                                    <i class="ph-fill ph-info text-elevate-accent text-base shrink-0 mt-0.5"></i>
                                    <span>Cukup masukkan nomor NISN atau NIS Anda tanpa memerlukan kata sandi.</span>
                                </div>

                                <!-- Input NISN -->
                                <div class="space-y-1">
                                    <label for="student_id" class="text-[11px] font-black text-slate-300 uppercase tracking-wider">NISN / NIS</label>
                                    <div class="relative group">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-elevate-accent transition-colors">
                                            <i class="ph-duotone ph-identification-card text-lg"></i>
                                        </div>
                                        <input id="student_id" name="student_id" type="text"
                                               autocomplete="off" required
                                               :autofocus="tab === 'siswa'"
                                               value="{{ old('student_id') }}"
                                               class="block w-full rounded-xl border border-white/20 bg-black/25 py-3 pl-10 pr-3.5 text-xs text-white placeholder-slate-400 focus:border-elevate-accent focus:bg-black/35 focus:ring-1 focus:ring-elevate-accent transition-all outline-none tracking-widest font-bold"
                                               placeholder="Contoh: 0056789012">
                                    </div>
                                    <x-input-error :messages="$errors->get('student_id')" class="mt-1 text-[11px] text-rose-300 font-semibold" />
                                </div>

                                <input type="hidden" name="intended_app" value="">

                                <!-- Tombol Masuk Siswa -->
                                <button type="submit"
                                        :disabled="isLoggingIn"
                                        class="w-full py-3.5 px-4 rounded-xl bg-elevate-accent hover:bg-[#72cbfa] text-elevate-dark font-black text-xs uppercase tracking-wider shadow-lg shadow-elevate-accent/25 hover:shadow-elevate-accent/40 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98">
                                    <span x-show="!isLoggingIn" class="flex items-center gap-1.5">
                                        <span>Masuk Portal Siswa</span> <i class="ph-bold ph-arrow-right"></i>
                                    </span>
                                    <span x-show="isLoggingIn" class="flex items-center gap-2" style="display:none;">
                                        <i class="ph-bold ph-spinner animate-spin"></i> Mencari Data...
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- PINTASAN CEPAT LAYANAN (Quick Access Pills di bawah Form) -->
                        <div class="mt-6 pt-5 border-t border-white/10">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2 text-center">Akses Cepat Layanan:</span>
                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                <a href="{{ route('student.login.learning') }}" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-[11px] text-slate-300 hover:text-white transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-books text-elevate-accent"></i> Ruang Belajar
                                </a>
                                <a href="{{ route('student.login.cbt') }}" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-[11px] text-slate-300 hover:text-white transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-monitor-play text-sky-300"></i> Ujian CBT
                                </a>
                                <a href="{{ route('kiosk.show') }}" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-[11px] text-slate-300 hover:text-white transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-qr-code text-emerald-300"></i> Kiosk
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </main>

        <!-- FOOTER & 4-DOTS INDICATOR -->
        <footer class="w-full relative z-20 pb-6 px-4 sm:px-8 max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-white/10 pt-5">
            <!-- 4 Dots Indicator -->
            <div class="flex items-center gap-1.5">
                <span class="w-6 h-2 rounded-full bg-elevate-accent shadow-[0_0_10px_rgba(86,187,241,0.7)]"></span>
                <span class="w-2 h-2 rounded-full bg-white/20"></span>
                <span class="w-2 h-2 rounded-full bg-white/20"></span>
                <span class="w-2 h-2 rounded-full bg-white/20"></span>
            </div>

            <!-- Copyright -->
            <p class="text-xs text-slate-400 font-medium">
                &copy; {{ date('Y') }} SMP Negeri 3 Lakbok &bull; Terakreditasi A &bull; All rights reserved.
            </p>
        </footer>

        <!-- PWA SERVICE WORKER -->
        <script>
            if ('serviceWorker' in navigator && window.isSecureContext) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw-guru.js')
                        .catch(error => {
                            console.warn('PWA Service Worker gagal didaftarkan:', error);
                        });
                });
            }
        </script>
    </body>
</html>
