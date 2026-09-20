<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ujian Online') }}</title>

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
<body class="font-sans antialiased bg-[#021124] text-white min-h-screen relative overflow-x-hidden flex flex-col justify-between selection:bg-rose-500 selection:text-white">
    
    <!-- GLOBAL FIXED BACKGROUND -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[#021124]"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
             style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
        <div class="absolute inset-0 bg-grid-dark opacity-35"></div>
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-rose-500/10 rounded-full blur-[160px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-[550px] h-[550px] bg-elevate-primary/20 rounded-full blur-[180px] pointer-events-none"></div>
    </div>

    {{-- NAVBAR KHUSUS UJIAN (Minimalis, Terkunci, Dark Glassmorphism) --}}
    <nav class="bg-[#031d3d]/90 backdrop-blur-2xl border-b border-rose-500/30 fixed w-full z-50 top-0 shadow-[0_4px_30px_rgba(0,0,0,0.5)]">
        {{-- Aksen Garis Atas --}}
        <div class="h-1 w-full bg-gradient-to-r from-rose-500 via-elevate-accent to-rose-500"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-4">
                    {{-- Logo Area --}}
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-11 h-11 bg-gradient-to-br from-rose-500 to-rose-700 p-0.5 rounded-2xl shadow-[0_0_20px_rgba(244,63,94,0.35)]">
                            <div class="w-full h-full bg-[#021124] rounded-[14px] flex items-center justify-center text-rose-400">
                                <i class="ph-bold ph-exam text-xl"></i>
                            </div>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-black text-white text-lg tracking-tight">Computer Based Test</h1>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse border border-emerald-300"></span>
                                <p class="text-[10px] font-bold text-rose-300 uppercase tracking-widest">Secure Exam Mode</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Profile & Logout --}}
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-white leading-none flex items-center justify-end gap-1.5">
                            <span>{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </p>
                        <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-white/10 text-rose-300 border border-white/15">
                            NISN: {{ Auth::guard('student')->user()->student_id ?? '-' }}
                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-white/15"></div>

                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-slate-300 hover:bg-rose-500 hover:text-white hover:border-rose-400 shadow-sm transition-all group" title="Keluar Aplikasi">
                            <i class="ph-bold ph-sign-out text-lg group-hover:-translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-28 min-h-screen flex flex-col justify-between relative overflow-hidden">
        {{-- Header Page Optional --}}
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10 w-full">
                {{ $header }}
            </header>
        @endif

        <main class="flex-1 relative z-10">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="text-center py-8 text-slate-400 text-xs font-semibold pb-8 relative z-10 border-t border-white/5 mt-16">
            <p>&copy; {{ date('Y') }} Sistem Ujian Terpadu &bull; SMPN 3 Lakbok. <span class="text-rose-400 font-bold">Mode Ujian Aman.</span></p>
        </footer>
    </div>
</body>
</html>