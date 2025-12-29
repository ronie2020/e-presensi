<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Ujian') }}</title>

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
    
    <!-- NAVBAR KHUSUS UJIAN (Nuansa Biru Gelap/Rose) -->
    <nav class="bg-slate-900 border-b border-slate-800 fixed w-full z-50 top-0 shadow-xl shadow-blue-900/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center gap-6">
                    <!-- Logo Area -->
                    <a href="{{ route('student.exam.index') }}" class="flex items-center gap-3 shrink-0">
                        <div class="w-9 h-9 bg-rose-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
                            <i class="ph-bold ph-exam text-xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-bold text-white text-lg tracking-tight">CBT System</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mode Ujian</p>
                        </div>
                    </a>

                    <!-- Divider -->
                    <div class="hidden md:block h-6 w-px bg-slate-700"></div>

                    <!-- Menu Desktop (Hanya Menu Ujian) -->
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('student.exam.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all 
                           {{ request()->routeIs('student.exam.*') 
                                ? 'text-white bg-white/10' 
                                : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <i class="ph-fill ph-list-checks text-lg"></i>
                            Daftar Ujian
                        </a>

                        <!-- Contoh menu riwayat (jika ada routenya) -->
                        <a href="#" 
                           class="px-3 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all text-slate-400 hover:text-white hover:bg-white/5">
                            <i class="ph-fill ph-chart-bar text-lg"></i>
                            Riwayat Nilai
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Switch Mode Button (Pindah ke Materi) -->
                    <a href="{{ route('student.learning.index') }}" class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-xs font-bold text-slate-300 hover:text-white hover:border-slate-500 transition-all" title="Pindah ke Mode Belajar">
                        <i class="ph-bold ph-books"></i>
                        <span class="hidden lg:block">Mode Belajar</span>
                    </a>

                    <div class="hidden md:block h-6 w-px bg-slate-700"></div>

                    <!-- Profile & Logout -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-bold text-slate-200 leading-none">{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</p>
                            <p class="text-[10px] text-rose-400 font-mono mt-0.5">{{ Auth::guard('student')->user()->student_id ?? '-' }}</p>
                        </div>
                        <form method="POST" action="{{ route('student.logout') }}">
                            @csrf
                            <button type="submit" class="w-9 h-9 rounded-full bg-rose-600/10 border border-rose-600/20 flex items-center justify-center text-rose-500 hover:bg-rose-600 hover:text-white transition-all" title="Keluar">
                                <i class="ph-bold ph-power text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- MOBILE BOTTOM NAV (Khusus Ujian) -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-slate-900 border-t border-slate-800 z-50 px-6 py-2 flex justify-around backdrop-blur-lg bg-opacity-95">
        <a href="{{ route('student.exam.index') }}" class="flex flex-col items-center gap-1 p-2 rounded-xl {{ request()->routeIs('student.exam.*') ? 'text-rose-400' : 'text-slate-500' }}">
            <i class="{{ request()->routeIs('student.exam.*') ? 'ph-fill' : 'ph-bold' }} ph-list-checks text-2xl"></i>
            <span class="text-[10px] font-bold">Ujian</span>
        </a>
        
        <!-- Switch ke Belajar di Mobile -->
        <a href="{{ route('student.learning.index') }}" class="flex flex-col items-center gap-1 p-2 rounded-xl text-slate-500 hover:text-blue-400">
            <i class="ph-bold ph-swap text-2xl"></i>
            <span class="text-[10px] font-bold">Mode Belajar</span>
        </a>

        <form method="POST" action="{{ route('student.logout') }}" class="flex flex-col items-center gap-1 p-2">
            @csrf
            <button type="submit" class="flex flex-col items-center text-slate-500 hover:text-rose-500">
                <i class="ph-bold ph-power text-2xl"></i>
                <span class="text-[10px] font-bold mt-1">Keluar</span>
            </button>
        </form>
    </div>

    <!-- MAIN CONTENT -->
    <div class="pt-20 min-h-screen pb-24 md:pb-8">
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                {{ $header }}
            </header>
        @endif

        <main>
            @yield('content')
        </main>
    </div>

</body>
</html>