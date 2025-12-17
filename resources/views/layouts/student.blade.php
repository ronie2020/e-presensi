<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Area Siswa') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    <!-- NAVBAR UTAMA -->
    <nav class="bg-white border-b border-slate-200 fixed w-full z-50 top-0 transition-all shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- KIRI: Logo & Menu Desktop -->
                <div class="flex items-center gap-8">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                            <i class="ph-bold ph-graduation-cap text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-bold text-slate-800 text-lg tracking-tight">Area Siswa</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SMPN 3 Lakbok</p>
                        </div>
                    </div>

                    <!-- Menu Desktop (Hidden on Mobile) -->
                    <div class="hidden md:flex space-x-1">
                        <!-- Menu: Ruang Belajar (LMS) -->
                        <a href="{{ route('students.learning.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all {{ request()->routeIs('students.learning.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                            <i class="ph-fill ph-books text-lg {{ request()->routeIs('students.learning.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                            Ruang Belajar
                        </a>

                        <!-- Menu: Ujian Online (CBT) -->
                        <a href="{{ route('student.exam.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all {{ request()->routeIs('student.exam.*') ? 'bg-rose-50 text-rose-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                            <i class="ph-fill ph-desktop text-lg {{ request()->routeIs('student.exam.*') ? 'text-rose-600' : 'text-slate-400' }}"></i>
                            Ujian Online
                        </a>
                    </div>
                </div>

                <!-- KANAN: User Info & Logout -->
                <div class="flex items-center gap-4">
                    <!-- Nama User (Desktop) -->
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-700 leading-none">{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</p>
                        <p class="text-[10px] text-slate-400 font-mono mt-1">{{ Auth::guard('student')->user()->student_id ?? '-' }}</p>
                    </div>

                    <!-- Tombol Logout -->
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:text-red-600 transition-colors" title="Keluar">
                            <i class="ph-bold ph-sign-out text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- MENU MOBILE (Bottom Bar - Agar mudah dijangkau jari) -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 z-50 px-6 py-2 flex justify-around shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="{{ route('students.learning.index') }}" class="flex flex-col items-center gap-1 p-2 rounded-xl {{ request()->routeIs('students.learning.*') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="ph-fill ph-books text-2xl"></i>
            <span class="text-[10px] font-bold">Belajar</span>
        </a>
        <a href="{{ route('student.exam.index') }}" class="flex flex-col items-center gap-1 p-2 rounded-xl {{ request()->routeIs('student.exam.*') ? 'text-rose-600' : 'text-slate-400' }}">
            <i class="ph-fill ph-desktop text-2xl"></i>
            <span class="text-[10px] font-bold">Ujian</span>
        </a>
    </div>

    <!-- CONTENT WRAPPER -->
    <div class="pt-20 pb-24 md:pb-12 min-h-screen">
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-100">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            @yield('content') {{-- Gunakan yield agar kompatibel dengan extends --}}
            {{ $slot ?? '' }} {{-- Gunakan slot agar kompatibel dengan component --}}
        </main>
    </div>

</body>
</html>