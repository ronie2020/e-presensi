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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    {{-- NAVBAR: Gradient Cyan ke Biru (Sesuai Landing Page) --}}
    <nav class="bg-gradient-to-r from-cyan-600 via-blue-600 to-blue-800 border-b border-blue-700 fixed w-full z-50 top-0 shadow-lg shadow-blue-900/10">
        {{-- Aksen Garis Atas --}}
        <div class="h-1 w-full bg-gradient-to-r from-cyan-300 via-blue-300 to-cyan-300"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center gap-6">
                    {{-- Logo Area --}}
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-9 h-9 bg-white/20 backdrop-blur border border-white/30 rounded-lg flex items-center justify-center text-white shadow-inner">
                            <i class="ph-bold ph-monitor-play text-xl"></i>
                        </div>
                        <div class="leading-tight">
                            <h1 class="font-bold text-white text-lg tracking-tight">Ujian Online</h1>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse border border-emerald-200"></span>
                                <p class="text-[10px] font-bold text-cyan-100 uppercase tracking-widest">Sistem Terhubung</p>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="hidden md:flex ml-8 border-l border-white/20 pl-8">
                        <a href="{{ route('student.exam.index') }}" 
                           class="text-cyan-100 text-sm font-bold flex items-center gap-2 hover:text-white transition-colors">
                            <i class="ph-fill ph-list-checks"></i> Daftar Ujian
                        </a>
                    </div>
                </div>

                {{-- User Info & Exit Button --}}
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-white">{{ Auth::guard('student')->user()->name }}</p>
                        <p class="text-[10px] text-cyan-200">{{ Auth::guard('student')->user()->student_id }}</p>
                    </div>
                    
                    <a href="{{ route('portal.index') }}" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-xs font-bold hover:bg-rose-500 hover:border-rose-400 hover:text-white transition-all flex items-center gap-2 group backdrop-blur-sm" title="Kembali ke Portal Utama">
                        <i class="ph-bold ph-door-open group-hover:-translate-x-1 transition-transform"></i>
                        <span class="hidden sm:inline">Keluar Mode Ujian</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- MOBILE BOTTOM BAR (Clean Putih agar aplikasi terasa native) --}}
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 z-50 px-6 py-3 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="{{ route('portal.index') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-rose-500 transition-colors">
            <i class="ph-bold ph-door-open text-xl"></i>
            <span class="text-[10px] font-bold">Keluar</span>
        </a>
        
        <div class="px-6 py-2 bg-cyan-50 border border-cyan-100 rounded-full text-cyan-600 flex items-center gap-2">
            <i class="ph-fill ph-desktop text-lg"></i>
            <span class="text-xs font-bold">Mode Ujian</span>
        </div>

        <div class="w-8"></div> {{-- Spacer agar bar seimbang --}}
    </div>

    {{-- MAIN CONTENT --}}
    <div class="pt-16 min-h-screen flex flex-col bg-slate-50">
        <main class="flex-1 w-full">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="text-center py-6 text-slate-400 text-[10px] font-medium pb-24 md:pb-6 flex items-center justify-center gap-1.5">
            <i class="ph-fill ph-student text-slate-300"></i>
            <p>Computer Based Test (CBT) System &copy; {{ date('Y') }}</p>
        </footer>
    </div>
</body>
</html>