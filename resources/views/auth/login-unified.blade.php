<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Masuk - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>

        <!-- PWA META TAGS -->
        <link rel="manifest" href="{{ asset('manifest-guru.json') }}">
        <meta name="theme-color" content="#0d52a1">
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

            /* Ambient mesh background */
            .bg-ambient-glow {
                background: radial-gradient(circle at 15% 20%, rgba(86, 187, 241, 0.35) 0%, transparent 40%),
                            radial-gradient(circle at 85% 15%, rgba(13, 82, 161, 0.45) 0%, transparent 45%),
                            radial-gradient(circle at 50% 85%, rgba(44, 63, 97, 0.3) 0%, transparent 50%),
                            #031c3b;
            }

            /* Floating animation for orbiting badges */
            @keyframes orbitFloat {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-6px); }
            }
            .animate-orbit {
                animation: orbitFloat 4s ease-in-out infinite;
            }
            .delay-orbit-1 { animation-delay: 0.5s; }
            .delay-orbit-2 { animation-delay: 1s; }
            .delay-orbit-3 { animation-delay: 1.5s; }
            .delay-orbit-4 { animation-delay: 2s; }
            .delay-orbit-5 { animation-delay: 2.5s; }

            /* Shimmer button effect */
            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }
        </style>
    </head>

    <body class="text-slate-800 antialiased min-h-screen bg-ambient-glow py-4 sm:py-8 px-3 sm:px-6 flex items-center justify-center selection:bg-elevate-accent selection:text-elevate-dark">

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

        <!-- MAIN APP WINDOW CONTAINER (Mengacu pada konsep mockup terlampir) -->
        <div class="w-full max-w-6xl mx-auto bg-white rounded-[2.5rem] sm:rounded-[3.5rem] shadow-[0_25px_80px_rgba(0,0,0,0.45)] border border-white/60 overflow-hidden flex flex-col relative z-10"
             x-data="{ tab: '{{ $initTab }}' }">

            {{-- ======================================================== --}}
            {{-- BAGIAN ATAS: HERO BLUE WAVE & GLOWING CIRCULAR PORTAL   --}}
            {{-- ======================================================== --}}
            <section class="bg-gradient-to-br from-elevate-dark via-elevate-primary to-[#0e60bf] text-white pt-8 pb-16 sm:pb-20 px-6 sm:px-12 relative overflow-hidden rounded-b-[3rem] sm:rounded-b-[4rem] shadow-xl">
                
                <!-- Ambient Glow Orbs di dalam Header -->
                <div class="absolute -top-24 -left-24 w-80 h-80 bg-elevate-accent/25 rounded-full blur-[90px] pointer-events-none"></div>
                <div class="absolute top-1/2 right-10 w-96 h-96 bg-elevate-accent/20 rounded-full blur-[100px] pointer-events-none"></div>
                
                <!-- Organic Background Curves -->
                <div class="absolute -bottom-24 -left-20 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- TOP BAR: Logo & Navigasi Cepat -->
                <header class="flex items-center justify-between pb-8 sm:pb-10 relative z-20 border-b border-white/10">
                    <div class="flex items-center gap-3.5">
                        <div class="p-2 bg-white/15 backdrop-blur-md rounded-2xl border border-white/20 shadow-md shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain" onerror="this.src='/images/logo.png'">
                        </div>
                        <div>
                            <span class="text-lg sm:text-xl font-black text-white tracking-tight flex items-center gap-2">
                                SMPN 3 LAKBOK
                            </span>
                            <span class="text-[10px] text-elevate-accent font-bold uppercase tracking-widest block">
                                Sistem Informasi Terpadu
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <a href="{{ route('portal.index') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15 backdrop-blur-sm">
                            <i class="ph-bold ph-newspaper text-elevate-accent"></i> Portal Publik
                        </a>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-elevate-accent hover:bg-[#72cbfa] text-elevate-dark text-xs font-black shadow-lg shadow-elevate-accent/30 transition-all hover:scale-105 active:scale-95">
                            <i class="ph-bold ph-house"></i>
                            <span>Beranda</span>
                        </a>
                    </div>
                </header>

                <!-- HERO CONTENT: E-Learning Headline & Glowing Circular Portal -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center pt-8 sm:pt-10 relative z-20">
                    
                    <!-- Kiri: Headline & Fitur Ceklis -->
                    <div class="lg:col-span-7 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-elevate-accent text-xs font-black uppercase tracking-wider mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-sparkle text-sm"></i> E-Presensi & E-Learning Portal
                        </div>
                        
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-4 leading-[1.15]">
                            SIMADU <br class="hidden sm:block">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#9be0ff] to-white">
                                TERPADU
                            </span>
                        </h1>
                        
                        <p class="text-slate-200 text-sm sm:text-base font-normal leading-relaxed max-w-lg mx-auto lg:mx-0 mb-8">
                            Akses mudah ke presensi GPS real-time, modul belajar digital (LMS), ujian berbasis komputer (CBT), dan data akademik sekolah.
                        </p>

                        <!-- Baris Fitur (Sesuai Konsep Checklist pada Mockup) -->
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5 sm:gap-3 text-xs font-bold text-slate-200">
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-sm">
                                <i class="ph-bold ph-check-circle text-elevate-accent text-sm"></i>
                                <span>Presensi GPS</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-sm">
                                <i class="ph-bold ph-check-circle text-elevate-accent text-sm"></i>
                                <span>Ruang Belajar LMS</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-sm">
                                <i class="ph-bold ph-check-circle text-elevate-accent text-sm"></i>
                                <span>Ujian CBT Online</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-sm">
                                <i class="ph-bold ph-check-circle text-elevate-peach text-sm"></i>
                                <span>Akses Resmi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Lingkaran Cahaya Portal & Orbiting Badges (Persis Mockup) -->
                    <div class="lg:col-span-5 flex justify-center items-center relative py-6">
                        <div class="relative w-56 h-56 sm:w-64 sm:h-64 flex items-center justify-center">
                            
                            <!-- Glowing Aura Ring di belakang portal -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-elevate-accent/40 via-elevate-primary to-transparent blur-2xl pointer-events-none"></div>

                            <!-- Portal Circular Frame -->
                            <div class="w-48 h-48 sm:w-56 sm:h-56 rounded-full border-[3px] border-elevate-accent p-1.5 shadow-[0_0_40px_rgba(86,187,241,0.7)] relative flex items-center justify-center bg-gradient-to-tr from-elevate-primary to-elevate-dark overflow-hidden z-10">
                                <img src="{{ asset('images/netila.jpg') }}" alt="Sekolah" class="w-full h-full object-cover rounded-full filter brightness-90 contrast-110">
                                
                                <!-- Inner soft overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-elevate-dark/80 via-transparent to-transparent"></div>
                                
                                <div class="absolute bottom-3 text-center z-10">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-elevate-accent bg-elevate-dark/80 px-2.5 py-0.5 rounded-full border border-elevate-accent/30">SMPN 3 Lakbok</span>
                                </div>
                            </div>

                            <!-- Orbiting Floating Badges (Mengelilingi Lingkaran Portal seperti di Mockup) -->
                            <!-- Badge 1: Atas (Guru) -->
                            <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-white text-elevate-primary shadow-xl flex items-center justify-center border-2 border-elevate-accent/50 z-20 animate-orbit">
                                <i class="ph-fill ph-chalkboard-teacher text-lg"></i>
                            </div>

                            <!-- Badge 2: Kanan Atas (Pesan) -->
                            <div class="absolute top-4 -right-1 w-10 h-10 rounded-full bg-elevate-accent text-elevate-dark shadow-xl flex items-center justify-center border-2 border-white/60 z-20 animate-orbit delay-orbit-1">
                                <i class="ph-fill ph-envelope-simple text-lg"></i>
                            </div>

                            <!-- Badge 3: Kanan Bawah (Statistik/Grafik) -->
                            <div class="absolute bottom-6 -right-2 w-11 h-11 rounded-full bg-white/20 backdrop-blur-md text-white shadow-xl flex items-center justify-center border border-white/40 z-20 animate-orbit delay-orbit-2">
                                <i class="ph-fill ph-chart-line-up text-elevate-peach text-xl"></i>
                            </div>

                            <!-- Badge 4: Bawah (Buku LMS) -->
                            <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-elevate-dark text-elevate-accent shadow-xl flex items-center justify-center border-2 border-elevate-accent/40 z-20 animate-orbit delay-orbit-3">
                                <i class="ph-fill ph-books text-lg"></i>
                            </div>

                            <!-- Badge 5: Kiri Bawah (Komputer CBT) -->
                            <div class="absolute bottom-6 -left-2 w-10 h-10 rounded-full bg-white text-elevate-primary shadow-xl flex items-center justify-center border-2 border-elevate-accent/50 z-20 animate-orbit delay-orbit-4">
                                <i class="ph-fill ph-desktop text-lg"></i>
                            </div>

                            <!-- Badge 6: Kiri Atas (Siswa) -->
                            <div class="absolute top-4 -left-1 w-10 h-10 rounded-full bg-elevate-primary text-white shadow-xl flex items-center justify-center border-2 border-elevate-accent/60 z-20 animate-orbit delay-orbit-5">
                                <i class="ph-fill ph-student text-lg"></i>
                            </div>

                        </div>
                    </div>

                </div>
            </section>


            {{-- ======================================================== --}}
            {{-- BAGIAN BAWAH: 3 FEATURED CARDS & INTERACTIVE LOGIN BOX   --}}
            {{-- ======================================================== --}}
            <section class="p-6 sm:p-10 lg:p-12 bg-slate-50/70">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- ==================================================== --}}
                    {{-- KOLOM KIRI (7/12): FEATURED CARDS & INFO LAYANAN     --}}
                    {{-- ==================================================== --}}
                    <div class="lg:col-span-7 flex flex-col justify-between h-full">
                        <div>
                            <!-- Header Bagian Kiri -->
                            <div class="mb-6">
                                <h2 class="text-xl sm:text-2xl font-black text-elevate-dark tracking-tight">
                                    Layanan Pembelajaran & Akademik
                                </h2>
                                <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">
                                    Pilih portal yang ingin Anda tuju langsung atau ikuti aktivitas harian.
                                </p>
                            </div>

                            <!-- 3 VERTICAL CARDS (Persis Grid 3 Kartu "Featured Course" Mockup) -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                                
                                <!-- Card 1: Ruang Belajar (LMS) -->
                                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_25px_rgba(13,82,161,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                                    <!-- Image / Visual Banner -->
                                    <div class="h-28 bg-gradient-to-br from-elevate-primary to-elevate-accent p-3 flex flex-col justify-between relative overflow-hidden">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center border border-white/30">
                                            <i class="ph-bold ph-books text-lg"></i>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-white/90 bg-black/20 w-fit px-2 py-0.5 rounded-md">LMS Online</span>
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <h3 class="font-black text-sm text-elevate-dark group-hover:text-elevate-primary transition-colors">Ruang Belajar</h3>
                                            <p class="text-[11px] text-slate-500 font-medium mt-1 leading-snug">Modul interaktif, tugas, & materi pelajaran.</p>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                            <div class="flex text-amber-400 text-xs">
                                                <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i>
                                            </div>
                                            <a href="{{ route('student.login.learning') }}" class="px-3 py-1 rounded-lg bg-elevate-soft text-elevate-primary text-[10px] font-black hover:bg-elevate-primary hover:text-white transition-colors">
                                                Buka LMS
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Ruang Ujian (CBT) -->
                                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_25px_rgba(225,29,72,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                                    <!-- Image / Visual Banner -->
                                    <div class="h-28 bg-gradient-to-br from-rose-600 to-rose-400 p-3 flex flex-col justify-between relative overflow-hidden">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center border border-white/30">
                                            <i class="ph-bold ph-desktop text-lg"></i>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-white/90 bg-black/20 w-fit px-2 py-0.5 rounded-md">CBT Exam</span>
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <h3 class="font-black text-sm text-elevate-dark group-hover:text-rose-600 transition-colors">Ruang Ujian</h3>
                                            <p class="text-[11px] text-slate-500 font-medium mt-1 leading-snug">Penilaian Harian, PTS, dan PAS komputer.</p>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                            <div class="flex text-amber-400 text-xs">
                                                <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i>
                                            </div>
                                            <a href="{{ route('student.login.cbt') }}" class="px-3 py-1 rounded-lg bg-rose-50 text-rose-600 text-[10px] font-black hover:bg-rose-600 hover:text-white transition-colors">
                                                Buka CBT
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 3: Portal Informasi Sekolah -->
                                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_25px_rgba(86,187,241,0.15)] hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                                    <!-- Image / Visual Banner -->
                                    <div class="h-28 bg-gradient-to-br from-elevate-dark to-slate-800 p-3 flex flex-col justify-between relative overflow-hidden">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center border border-white/30">
                                            <i class="ph-bold ph-identification-card text-lg"></i>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-white/90 bg-black/20 w-fit px-2 py-0.5 rounded-md">Publik</span>
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <h3 class="font-black text-sm text-elevate-dark group-hover:text-elevate-primary transition-colors">Portal Siswa</h3>
                                            <p class="text-[11px] text-slate-500 font-medium mt-1 leading-snug">Jadwal pelajaran, kalender, & informasi resmi.</p>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                            <div class="flex text-amber-400 text-xs">
                                                <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i>
                                            </div>
                                            <a href="{{ route('portal.index') }}" class="px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-black hover:bg-elevate-dark hover:text-white transition-colors">
                                                Cek Data
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Banner Info Tambahan di bawah kartu (Mirip testimonial text di mockup) -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-elevate-primary/10 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-shield-check text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-black text-elevate-dark">Keamanan Akun Terjamin</h4>
                                <p class="text-[11px] text-slate-500 font-medium truncate">Autentikasi terenkripsi langsung ke server database resmi sekolah.</p>
                            </div>
                        </div>
                    </div>


                    {{-- ==================================================== --}}
                    {{-- KOLOM KANAN (5/12): THE INTERACTIVE LOGIN CARD BOX   --}}
                    {{-- (Sesuai Widget Card Gelap Berlekuk di Kanan Mockup)  --}}
                    {{-- ==================================================== --}}
                    <div class="lg:col-span-5">
                        <div class="bg-gradient-to-b from-elevate-dark via-[#133c6d] to-elevate-primary rounded-3xl p-6 sm:p-7 shadow-[0_15px_35px_rgba(13,82,161,0.3)] text-white border border-white/15 relative overflow-hidden">
                            
                            <!-- Hiasan Lengkungan Gelombang Atas (Persis Mockup Box) -->
                            <div class="absolute -top-6 -right-6 w-32 h-32 bg-elevate-accent/20 rounded-full blur-2xl pointer-events-none"></div>

                            <!-- Header Widget Card -->
                            <div class="flex items-center justify-between mb-5 pb-4 border-b border-white/10 relative z-10">
                                <div>
                                    <h3 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                        Masuk ke Akun
                                    </h3>
                                    <p class="text-[11px] text-slate-300 font-medium">Pilih peran Anda untuk melanjutkan</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-elevate-accent border border-white/15">
                                    <i class="ph-fill ph-lock-key text-base"></i>
                                </div>
                            </div>

                            <!-- TAB SWITCHER (Guru vs Siswa) -->
                            <div class="relative mb-6 bg-black/25 border border-white/10 p-1 rounded-xl flex gap-1 backdrop-blur-md">
                                <button type="button"
                                        @click="tab = 'guru'"
                                        :class="tab === 'guru'
                                            ? 'bg-elevate-accent text-elevate-dark font-black shadow-md'
                                            : 'text-white/80 hover:text-white font-bold'"
                                        class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-xs transition-all duration-200 cursor-pointer">
                                    <i class="ph-bold ph-chalkboard-teacher"></i>
                                    <span>Guru / Staff</span>
                                </button>
                                <button type="button"
                                        @click="tab = 'siswa'"
                                        :class="tab === 'siswa'
                                            ? 'bg-elevate-accent text-elevate-dark font-black shadow-md'
                                            : 'text-white/80 hover:text-white font-bold'"
                                        class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-xs transition-all duration-200 cursor-pointer">
                                    <i class="ph-bold ph-student"></i>
                                    <span>Siswa</span>
                                </button>
                            </div>

                            <!-- FORM GURU -->
                            <div x-show="tab === 'guru'" class="transition-opacity duration-200">
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
                                                class="block w-full rounded-xl border border-white/20 bg-black/20 py-3 pl-10 pr-3.5 text-xs text-white placeholder-slate-400 focus:border-elevate-accent focus:bg-black/30 focus:ring-1 focus:ring-elevate-accent transition-all outline-none"
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
                                                    Lupa?
                                                </a>
                                            @endif
                                        </div>
                                        <div class="relative group" x-data="{ show: false }">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-elevate-accent transition-colors">
                                                <i class="ph-duotone ph-lock-key text-lg"></i>
                                            </div>
                                            <input id="password"
                                                class="block w-full rounded-xl border border-white/20 bg-black/20 py-3 pl-10 pr-10 text-xs text-white placeholder-slate-400 focus:border-elevate-accent focus:bg-black/30 focus:ring-1 focus:ring-elevate-accent transition-all outline-none"
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
                            <div x-show="tab === 'siswa'" x-cloak class="transition-opacity duration-200">
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
                                                   class="block w-full rounded-xl border border-white/20 bg-black/20 py-3 pl-10 pr-3.5 text-xs text-white placeholder-slate-400 focus:border-elevate-accent focus:bg-black/30 focus:ring-1 focus:ring-elevate-accent transition-all outline-none tracking-widest font-bold"
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

                        </div>
                    </div>

                </div>

                <!-- 4 DOTS PAGINATION & COPYRIGHT (Persis Mockup di Bagian Bawah) -->
                <div class="mt-10 pt-6 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-6 h-2 rounded-full bg-elevate-primary"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                    </div>

                    <p class="text-xs text-slate-400 font-medium">
                        &copy; {{ date('Y') }} SMP Negeri 3 Lakbok &bull; Terakreditasi A
                    </p>
                </div>

            </section>
        </div>

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
