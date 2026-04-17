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
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    {{-- NAVBAR KHUSUS UJIAN (Minimalis, Terkunci, Tema Elevate) --}}
    <nav class="bg-gradient-to-r from-cyan-600 via-blue-600 to-blue-800 border-b border-blue-700 fixed w-full z-50 top-0 shadow-lg shadow-blue-900/10">
        {{-- Aksen Garis Atas --}}
        <div class="h-1 w-full bg-gradient-to-r from-cyan-300 via-blue-300 to-cyan-300"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-4">
                    {{-- Logo Area (Link diarahkan ke Index Ujian, BUKAN Belajar) --}}
                    <a href="{{ route('student.exam.index') }}" class="flex items-center gap-3 shrink-0 group cursor-default">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur border border-white/30 rounded-xl flex items-center justify-center text-white shadow-inner">
                            <i class="ph-bold ph-exam text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-black text-white text-lg tracking-tight">Computer Based Test</h1>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse border border-emerald-200"></span>
                                <p class="text-[10px] font-bold text-cyan-100 uppercase tracking-widest">Secure Exam Mode</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- User Profile & Logout --}}
                <div class="flex items-center gap-5">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-white leading-none">{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</p>
                        <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-medium bg-white/10 text-cyan-100 border border-white/20">
                            {{ Auth::guard('student')->user()->student_id ?? '-' }}
                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-white/20"></div>

                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-rose-500 hover:text-white hover:border-rose-400 shadow-sm transition-all group" title="Keluar Aplikasi">
                            <i class="ph-bold ph-sign-out text-lg group-hover:-translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MOBILE BOTTOM NAV DIHAPUS --}}
    {{-- Kita menghapus navigasi bawah agar siswa di HP tidak bisa pindah ke menu belajar --}}

    <div class="pt-24 min-h-screen flex flex-col relative overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute top-0 left-0 w-full h-[300px] bg-gradient-to-b from-blue-50/50 to-transparent -z-10"></div>
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-cyan-500/5 rounded-full blur-[100px] -mr-20 -mt-20 -z-10"></div>

        {{-- Header Page Optional --}}
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                {{ $header }}
            </header>
        @endif

        <main class="flex-1 relative z-10">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="text-center py-8 text-slate-400 text-xs font-medium pb-8">
            <p>&copy; {{ date('Y') }} Sistem Ujian Sekolah. <span class="text-cyan-600 font-bold">Mode Ujian Aman.</span></p>
        </footer>
    </div>
</body>
</html>