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
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    {{-- NAVBAR: TEMA BIRU (CALM & FOCUS) --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 fixed w-full z-50 top-0 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-8">
                    {{-- Logo Area --}}
                    <a href="{{ route('students.learning.index') }}" class="flex items-center gap-3 shrink-0 group">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/30 group-hover:scale-105 transition-transform">
                            <i class="ph-bold ph-books text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-black text-slate-800 text-lg tracking-tight group-hover:text-blue-600 transition-colors">Ruang Belajar</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ config('app.name') }}</p>
                        </div>
                    </a>

                    {{-- Desktop Menu --}}
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('students.learning.index') }}" 
                           class="px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 transition-all border
                           {{ request()->routeIs('students.learning.*') 
                                ? 'bg-blue-50 text-blue-600 border-blue-100' 
                                : 'text-slate-500 border-transparent hover:bg-slate-50 hover:text-slate-700' }}">
                            <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                            Materi & Tugas
                        </a>
                        
                        {{-- Link Switch ke Ujian --}}
                        <a href="{{ route('student.exam.index') }}" 
                           class="px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 transition-all text-slate-400 hover:text-rose-500 hover:bg-rose-50 border border-transparent hover:border-rose-100" title="Pindah ke Mode Ujian">
                            <i class="ph-bold ph-desktop text-lg"></i>
                            Mode Ujian
                        </a>
                    </div>
                </div>

                {{-- User Profile --}}
                <div class="flex items-center gap-5">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-700 leading-none">{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</p>
                        <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            {{ Auth::guard('student')->user()->student_id ?? '-' }}
                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-slate-200"></div>

                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all group" title="Keluar">
                            <i class="ph-bold ph-sign-out text-lg group-hover:-translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MOBILE BOTTOM NAV (Learning Focused) --}}
    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-white/90 backdrop-blur-xl border border-white/20 z-50 px-6 py-3 flex justify-between rounded-[2rem] shadow-2xl shadow-slate-200/50">
        <a href="{{ route('students.learning.index') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('students.learning.*') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="{{ request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold' }} ph-books text-2xl"></i>
        </a>
        
        <a href="{{ route('student.exam.index') }}" class="flex flex-col items-center gap-1 transition-all text-slate-300 hover:text-rose-400">
            <i class="ph-bold ph-desktop text-2xl"></i>
        </a>

        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="text-slate-300 hover:text-rose-500">
                <i class="ph-bold ph-sign-out text-2xl"></i>
            </button>
        </form>
    </div>

    <div class="pt-24 min-h-screen flex flex-col">
        {{-- Header Page Optional --}}
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                {{ $header }}
            </header>
        @endif

        <main class="flex-1">
            {{ $slot ?? '' }}
        </main>

        <footer class="text-center py-8 text-slate-400 text-xs font-medium pb-24 md:pb-8">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. <span class="text-blue-500">Ruang Belajar Siswa.</span></p>
        </footer>
    </div>
</body>
</html>