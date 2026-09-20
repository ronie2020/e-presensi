<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Siswa - SMPN 3 Lakbok') }}</title>

    <!-- PWA META TAGS -->
    <link rel="manifest" href="{{ asset('manifest-siswa.json') }}">
    <meta name="theme-color" content="#021124">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-siswa-192x192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }

        .bg-grid-dark {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* Custom scrollbar khusus untuk siswa */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #56bbf1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #0d52a1; }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#021124] text-white min-h-screen relative overflow-x-hidden flex flex-col justify-between selection:bg-elevate-accent selection:text-[#021124]">

    <!-- GLOBAL FIXED BACKGROUND (Elegan, Tenang & Tidak Mengganggu) -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        {{-- Base Canvas --}}
        <div class="absolute inset-0 bg-[#021124]"></div>

        {{-- Foto Gedung Sekolah Soft-Blur Ambient --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
             style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        
        {{-- Radial Vignette & Dark Gradient Overlay --}}
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
        
        {{-- Cyber Grid Texture --}}
        <div class="absolute inset-0 bg-grid-dark opacity-35"></div>

        {{-- Ambient Glow Orbs --}}
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-elevate-accent/15 rounded-full blur-[160px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-[550px] h-[550px] bg-elevate-primary/25 rounded-full blur-[180px] pointer-events-none"></div>
        <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[150px] pointer-events-none"></div>
    </div>
    
    {{-- NAVBAR: Dark Glassmorphism --}}
    <nav class="bg-[#031d3d]/90 backdrop-blur-2xl border-b border-white/15 sticky top-0 z-40 shadow-[0_4px_30px_rgba(0,0,0,0.5)] transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-[72px] items-center">
                
                {{-- Logo Kiri --}}
                <a href="{{ route('portal.index') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-elevate-primary via-[#0d52a1] to-elevate-accent p-0.5 shadow-[0_0_20px_rgba(86,187,241,0.35)] group-hover:scale-105 transition-transform">
                        <div class="w-full h-full bg-[#021124] rounded-[14px] flex items-center justify-center p-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/images/logo.png'">
                        </div>
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="text-base sm:text-lg font-black text-white tracking-tight group-hover:text-elevate-accent transition-colors">SMPN 3 LAKBOK</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-elevate-accent uppercase tracking-widest">Portal Siswa &bull; SIMADU</span>
                    </div>
                </a>

                {{-- Kanan User Info & Navigasi --}}
                <div class="flex items-center gap-2.5 sm:gap-4">
                    @if(Auth::guard('student')->check())
                        <div class="text-right hidden sm:block">
                            <p class="text-xs sm:text-sm font-bold text-white flex items-center justify-end gap-1.5">
                                <span>{{ Auth::guard('student')->user()->name }}</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            </p>
                            <p class="text-[10px] text-slate-400 font-mono tracking-wider">NISN: {{ Auth::guard('student')->user()->student_id }}</p>
                        </div>
                    @endif

                    <a href="{{ route('students.learning.index') }}" class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/15 transition-all">
                        <i class="ph-bold ph-books text-sky-400"></i> LMS
                    </a>

                    <a href="{{ route('student.exam.index') }}" class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 text-xs font-bold border border-rose-500/30 transition-all">
                        <i class="ph-bold ph-desktop text-rose-400"></i> CBT
                    </a>

                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white/10 hover:bg-rose-500/20 text-rose-300 hover:text-rose-200 font-bold text-xs rounded-xl transition-all flex items-center gap-2 border border-white/15 hover:border-rose-500/30 active:scale-95">
                            <i class="ph-bold ph-sign-out text-sm"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MOBILE BOTTOM BAR (Dark Glassmorphism) --}}
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-[#031d3d]/95 backdrop-blur-xl border-t border-white/15 z-50 px-6 py-2.5 flex justify-between items-center shadow-[0_-10px_30px_rgba(0,0,0,0.5)] pb-safe">
        <a href="{{ route('portal.index') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-elevate-accent transition-colors">
            <i class="ph-bold ph-identification-card text-xl"></i>
            <span class="text-[9px] font-bold">Portal</span>
        </a>

        <a href="{{ route('students.learning.index') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-sky-400 transition-colors">
            <i class="ph-bold ph-books text-xl"></i>
            <span class="text-[9px] font-bold">Belajar</span>
        </a>
        
        <a href="{{ route('student.exam.index') }}" class="px-5 py-1.5 bg-gradient-to-r from-rose-600 to-red-600 shadow-lg shadow-rose-600/30 rounded-2xl text-white flex items-center gap-1.5 transform -translate-y-2">
            <i class="ph-fill ph-desktop text-lg"></i>
            <span class="text-[11px] font-black tracking-wide">Ujian</span>
        </a>

        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="flex flex-col items-center gap-1 text-slate-400 hover:text-rose-400 transition-colors">
                <i class="ph-bold ph-sign-out text-xl"></i>
                <span class="text-[9px] font-bold">Keluar</span>
            </button>
        </form>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col relative z-10 w-full">
        <main class="flex-1 w-full pb-24 md:pb-12 pt-4 sm:pt-6">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="text-center py-6 text-xs text-slate-400 border-t border-white/10 hidden md:block relative z-10">
            <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="w-6 h-2 rounded-full bg-elevate-accent shadow-[0_0_10px_rgba(86,187,241,0.7)]"></span>
                    <span class="w-2 h-2 rounded-full bg-white/20"></span>
                    <span class="w-2 h-2 rounded-full bg-white/20"></span>
                    <span class="w-2 h-2 rounded-full bg-white/20"></span>
                </div>
                <p class="font-medium">
                    &copy; {{ date('Y') }} {{ config('app.name', 'SMP Negeri 3 Lakbok') }} &bull; Portal Siswa Terpadu. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>