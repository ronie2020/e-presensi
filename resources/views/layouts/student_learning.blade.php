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
</head>
<body class="font-sans antialiased bg-slate-50">
    
    <!-- NAVBAR KHUSUS BELAJAR (Nuansa Putih/Emerald) -->
    <nav class="bg-white border-b border-slate-200 fixed w-full z-50 top-0 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-6">
                    <a href="{{ route('student.learning.index') }}" class="flex items-center gap-3 shrink-0">
                        <div class="w-9 h-9 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                            <i class="ph-bold ph-books text-xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-bold text-slate-800 text-lg tracking-tight">E-Learning</h1>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Ruang Belajar</p>
                        </div>
                    </a>
                    
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('student.learning.index') }}" class="px-3 py-2 rounded-lg text-sm font-bold flex items-center gap-2 text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all">
                            <i class="ph-fill ph-book-open text-lg"></i> Materi
                        </a>
                        <a href="#" class="px-3 py-2 rounded-lg text-sm font-bold flex items-center gap-2 text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all">
                            <i class="ph-fill ph-pencil-simple text-lg"></i> Tugas
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Switch ke Ujian -->
                    <a href="{{ route('student.exam.index') }}" class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all">
                        <i class="ph-bold ph-exam"></i>
                        <span class="hidden lg:block">Mode Ujian</span>
                    </a>

                    <div class="hidden md:block h-6 w-px bg-slate-200"></div>

                    <div class="flex items-center gap-3">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-bold text-slate-700 leading-none">{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</p>
                        </div>
                        <form method="POST" action="{{ route('student.logout') }}">
                            @csrf
                            <button type="submit" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-red-50 hover:text-red-500 flex items-center justify-center text-slate-400 transition-all">
                                <i class="ph-bold ph-sign-out text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- MOBILE BOTTOM NAV (Khusus Belajar) -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 z-50 px-6 py-2 flex justify-around shadow-[0_-4px_20px_-1px_rgba(0,0,0,0.1)]">
        <a href="{{ route('student.learning.index') }}" class="flex flex-col items-center gap-1 p-2 rounded-xl text-emerald-600">
            <i class="ph-fill ph-books text-2xl"></i>
            <span class="text-[10px] font-bold">Belajar</span>
        </a>
        
        <a href="{{ route('student.exam.index') }}" class="flex flex-col items-center gap-1 p-2 rounded-xl text-slate-400 hover:text-rose-500">
            <i class="ph-bold ph-swap text-2xl"></i>
            <span class="text-[10px] font-bold">Ke Ujian</span>
        </a>

        <form method="POST" action="{{ route('student.logout') }}" class="flex flex-col items-center gap-1 p-2">
            @csrf
            <button type="submit" class="flex flex-col items-center text-slate-400 hover:text-red-500">
                <i class="ph-bold ph-sign-out text-2xl"></i>
                <span class="text-[10px] font-bold mt-1">Keluar</span>
            </button>
        </form>
    </div>

    <div class="pt-20 min-h-screen pb-24 md:pb-8">
        @yield('content')
    </div>
</body>
</html>