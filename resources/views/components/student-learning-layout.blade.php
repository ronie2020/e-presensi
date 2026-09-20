<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ruang Belajar') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }

        .bg-grid-dark {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #56bbf1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #0d52a1; }
    </style>
</head>
<body class="font-sans antialiased bg-[#021124] text-white min-h-screen relative overflow-x-hidden flex flex-col justify-between selection:bg-elevate-accent selection:text-[#021124]">
    
    <!-- GLOBAL FIXED BACKGROUND -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[#021124]"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
             style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
        <div class="absolute inset-0 bg-grid-dark opacity-35"></div>
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-elevate-accent/15 rounded-full blur-[160px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-[550px] h-[550px] bg-elevate-primary/25 rounded-full blur-[180px] pointer-events-none"></div>
        <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[150px] pointer-events-none"></div>
    </div>

    {{-- NAVBAR: Dark Glassmorphism --}}
    <nav class="bg-[#031d3d]/90 backdrop-blur-2xl border-b border-white/15 fixed w-full z-50 top-0 transition-all shadow-[0_4px_30px_rgba(0,0,0,0.5)]">
        <div class="h-1 w-full bg-gradient-to-r from-elevate-primary via-elevate-accent to-sky-300"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-6 sm:gap-8">
                    {{-- Logo Area --}}
                    <a href="{{ route('students.learning.index') }}" class="flex items-center gap-3 shrink-0 group">
                        <div class="w-11 h-11 bg-gradient-to-br from-elevate-primary via-[#0d52a1] to-elevate-accent p-0.5 rounded-2xl shadow-[0_0_20px_rgba(86,187,241,0.35)] group-hover:scale-105 transition-transform">
                            <div class="w-full h-full bg-[#021124] rounded-[14px] flex items-center justify-center text-elevate-accent">
                                <i class="ph-bold ph-books text-xl"></i>
                            </div>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-black text-white text-lg tracking-tight group-hover:text-elevate-accent transition-colors">Ruang Belajar</h1>
                            <p class="text-[10px] font-bold text-elevate-accent uppercase tracking-widest">{{ config('app.name') }}</p>
                        </div>
                    </a>

                    {{-- Desktop Menu --}}
                    <div class="hidden md:flex items-center space-x-2 border-l border-white/15 pl-6">
                        <a href="{{ route('students.learning.index') }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all border
                           {{ request()->routeIs('students.learning.*') 
                                ? 'bg-elevate-primary text-white border-elevate-accent/40 shadow-lg shadow-elevate-primary/30' 
                                : 'text-slate-300 border-transparent hover:bg-white/10 hover:text-white' }}">
                            <i class="{{ request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold' }} ph-chalkboard-teacher text-base text-sky-400"></i>
                            Materi & Tugas
                        </a>
                        <a href="{{ route('portal.index') }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 text-slate-300 hover:bg-white/10 hover:text-white border border-transparent transition-all">
                            <i class="ph-bold ph-house text-base text-elevate-accent"></i>
                            Portal Siswa
                        </a>
                    </div>
                </div>

                {{-- User Profile & Actions --}}
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-white leading-none flex items-center justify-end gap-1.5">
                            <span>{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </p>
                        <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-white/10 text-elevate-accent border border-white/15">
                            NISN: {{ Auth::guard('student')->user()->student_id ?? '-' }}
                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-white/15"></div>

                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-slate-300 hover:bg-rose-500/80 hover:text-white hover:border-rose-400 hover:shadow-lg hover:shadow-rose-500/20 transition-all group" title="Keluar Ruang Belajar">
                            <i class="ph-bold ph-sign-out text-lg group-hover:translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MOBILE BOTTOM NAV --}}
    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-[#031d3d]/95 backdrop-blur-xl border border-white/15 z-50 px-6 py-3.5 flex justify-between items-center rounded-[2rem] shadow-2xl shadow-black/60">
        <a href="{{ route('students.learning.index') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('students.learning.*') ? 'text-elevate-accent' : 'text-slate-400 hover:text-elevate-accent' }}">
            <i class="{{ request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold' }} ph-books text-2xl"></i>
            <span class="text-[9px] font-bold">Materi</span>
        </a>

        <a href="{{ route('portal.index') }}" class="flex flex-col items-center gap-1 transition-all text-slate-400 hover:text-elevate-accent">
            <i class="ph-bold ph-house text-2xl"></i>
            <span class="text-[9px] font-bold">Portal</span>
        </a>
        
        <a href="{{ route('student.exam.index') }}" class="flex flex-col items-center gap-1 transition-all text-slate-400 hover:text-elevate-accent">
            <i class="ph-bold ph-desktop text-2xl"></i>
            <span class="text-[9px] font-bold">Ujian</span>
        </a>

        <div class="h-6 w-px bg-white/15 mx-1"></div>

        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="text-rose-400 hover:text-rose-300 transition-colors flex flex-col items-center gap-1">
                <i class="ph-bold ph-sign-out text-2xl"></i>
                <span class="text-[9px] font-bold">Keluar</span>
            </button>
        </form>
    </div>

    <div class="pt-28 min-h-screen flex flex-col justify-between relative overflow-hidden">
        {{-- Optional Page Header --}}
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10 w-full">
                {{ $header }}
            </header>
        @endif

        <main class="flex-1 relative z-10">
            {{ $slot ?? '' }}
        </main>

        <footer class="text-center py-10 text-slate-400 text-xs font-semibold pb-28 md:pb-10 relative z-10 border-t border-white/5 mt-16">
            <div class="flex items-center justify-center gap-1.5 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-elevate-primary"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-elevate-accent"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            </div>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. <span class="text-elevate-accent">Ruang Belajar Mandiri.</span></p>
        </footer>
    </div>
</body>
</html>