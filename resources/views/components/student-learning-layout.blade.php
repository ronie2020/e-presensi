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
    
    {{-- NAVBAR: TEMA CYAN-BLUE ELEVATE --}}
    <nav class="bg-gradient-to-r from-cyan-600 via-blue-600 to-blue-900 border-b border-blue-600/30 fixed w-full z-50 top-0 transition-all shadow-lg shadow-blue-900/10">
        <div class="h-1 w-full bg-gradient-to-r from-cyan-300 via-blue-300 to-cyan-300"></div> {{-- Garis Aksen Atas --}}
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-8">
                    {{-- Logo Area --}}
                    <a href="{{ route('students.learning.index') }}" class="flex items-center gap-3 shrink-0 group">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl flex items-center justify-center text-white shadow-inner group-hover:rotate-6 transition-transform duration-300">
                            <i class="ph-bold ph-books text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-black text-white text-lg tracking-tight group-hover:text-cyan-200 transition-colors">Ruang Belajar</h1>
                            <p class="text-[10px] font-bold text-cyan-200 uppercase tracking-widest">{{ config('app.name') }}</p>
                        </div>
                    </a>

                    {{-- Desktop Menu --}}
                    <div class="hidden md:flex space-x-1 border-l border-white/20 pl-8">
                        <a href="{{ route('students.learning.index') }}" 
                           class="px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all border
                           {{ request()->routeIs('students.learning.*') 
                                ? 'bg-white/20 text-white border-white/30 shadow-inner' 
                                : 'text-cyan-100 border-transparent hover:bg-white/10 hover:text-white' }}">
                            <i class="{{ request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold' }} ph-chalkboard-teacher text-lg"></i>
                            Materi & Tugas
                        </a>                        
                       
                    </div>
                </div>

                {{-- User Profile --}}
                <div class="flex items-center gap-5">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-white leading-none">{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</p>
                        <span class="inline-flex mt-1.5 items-center px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-cyan-100 border border-white/20">
                            {{ Auth::guard('student')->user()->student_id ?? '-' }}
                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-white/20"></div>

                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-rose-500 hover:text-white hover:border-rose-400 hover:shadow-lg hover:shadow-rose-500/20 transition-all group" title="Keluar">
                            <i class="ph-bold ph-sign-out text-lg group-hover:translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MOBILE BOTTOM NAV --}}
    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-blue-950/98 backdrop-blur-xl border border-blue-900 z-50 px-6 py-4 flex justify-between rounded-[2rem] shadow-2xl shadow-blue-900/30">
        <a href="{{ route('students.learning.index') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('students.learning.*') ? 'text-cyan-400' : 'text-blue-200 hover:text-cyan-400' }}">
            <i class="{{ request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold' }} ph-books text-2xl"></i>
        </a>
        
        <a href="{{ route('student.exam.index') }}" class="flex flex-col items-center gap-1 transition-all text-blue-200 hover:text-cyan-400">
            <i class="ph-bold ph-desktop text-2xl"></i>
        </a>

        <div class="h-8 w-px bg-blue-800/50 mx-2"></div>

        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="text-rose-400 hover:text-rose-300 transition-colors flex items-center pt-1">
                <i class="ph-bold ph-sign-out text-2xl"></i>
            </button>
        </form>
    </div>

    <div class="pt-28 min-h-screen flex flex-col relative overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-50/50 to-transparent -z-10"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-20 -left-20 w-72 h-72 bg-blue-600/10 rounded-full blur-3xl -z-10"></div>

        {{-- Header Page Optional --}}
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                {{ $header }}
            </header>
        @endif

        <main class="flex-1 relative z-10">
            {{ $slot ?? '' }}
        </main>

        <footer class="text-center py-12 text-slate-400 text-xs font-bold pb-28 md:pb-12">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. <span class="text-blue-600">Ruang Belajar Siswa.</span></p>
        </footer>
    </div>
</body>
</html>