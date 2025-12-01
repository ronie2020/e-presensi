<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Library Eksternal -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        .min-h-content { min-height: calc(100vh - 400px); }
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden flex flex-col min-h-screen" 
    x-data="{ 
        mobileMenuOpen: false,
        scrolled: false
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false">

    <!-- === NAVBAR === -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-slate-200 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 z-50 group">
                    <div class="relative w-10 h-10 flex-shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="flex flex-col">
                        <span class="block text-lg font-extrabold leading-none tracking-tight text-slate-900">
                            SMPN 3 LAKBOK
                        </span>
                        <span class="text-[10px] font-bold tracking-wide mt-1 text-blue-600">
                            BERJAYA : <span class="font-medium text-slate-500">Unggul, Berkarakter</span>
                        </span>
                    </div>
                </a>

                <!-- Desktop Menu (DIPERBAIKI) -->
                <!-- Menggunakan 'gap-8' agar jaraknya konsisten -->
                <div class="hidden md:flex items-center gap-8">
                    
                    <!-- Grup 1: Menu Informasi Sekolah -->
                    <div class="flex items-center gap-6">
                        <a href="{{ url('/') }}#profil" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition">Profil</a>
                        <a href="{{ url('/') }}#guru" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition">Guru</a>
                        <a href="{{ url('/') }}#prestasi" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition">Prestasi</a>
                    </div>

                    <!-- Divider (Garis Pemisah Kecil) -->
                    <div class="h-5 w-px bg-slate-200"></div>

                    <!-- Grup 2: Menu Aplikasi -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('portal.index') }}" class="text-sm font-bold {{ request()->routeIs('portal.*') ? 'text-blue-600' : 'text-slate-700 hover:text-blue-600' }} transition flex items-center gap-2">
                            Portal Siswa
                        </a>

                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-bold px-5 py-2.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold px-5 py-2.5 rounded-full bg-slate-900 text-white hover:bg-slate-800 transition shadow-lg">
                                Login Staff
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center z-50">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-800 rounded-lg transition-colors focus:outline-none hover:bg-slate-100">
                        <i class="ph-bold text-2xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-5"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-5"
             class="absolute top-20 left-0 w-full bg-white border-b border-slate-200 shadow-xl md:hidden">
             
            <nav class="flex flex-col p-6 space-y-4">
                <a href="{{ url('/') }}#profil" class="text-lg font-bold text-slate-600 hover:text-blue-600">Profil Sekolah</a>
                <a href="{{ url('/') }}#guru" class="text-lg font-bold text-slate-600 hover:text-blue-600">Guru & Staff</a>
                <a href="{{ route('portal.index') }}" class="text-lg font-bold text-blue-600">Portal Siswa</a>
                <hr class="border-slate-100">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-blue-600 text-white font-bold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-slate-900 text-white font-bold">Login Staff</a>
                @endauth
            </nav>
        </div>
    </nav>

    <!-- === KONTEN UTAMA (YIELD) === -->
    <main class="flex-grow pt-24 pb-12 relative z-10 min-h-content">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <!-- === FOOTER === -->
    <footer class="bg-slate-900 text-white pt-16 pb-8 border-t border-slate-800 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-white flex items-center justify-center p-1">
                             <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-xl font-bold tracking-tight">SMPN 3 LAKBOK</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Platform layanan pendidikan digital terintegrasi untuk mendukung kegiatan akademik dan pembentukan karakter siswa.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Akses Cepat</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="{{ url('/') }}" class="hover:text-blue-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('portal.index') }}" class="hover:text-blue-400 transition-colors">Portal Siswa</a></li>
                        <li><a href="{{ route('library.kiosk.index') }}" class="hover:text-blue-400 transition-colors">E-Library</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Kontak</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li class="flex items-center gap-3"><i class="ph-fill ph-phone text-blue-500"></i> (0265) 1234567</li>
                        <li class="flex items-center gap-3"><i class="ph-fill ph-envelope text-blue-500"></i> admin@smpn3lakbok.sch.id</li>
                    </ul>
                </div>
            </div>
            <div class="text-center pt-8 border-t border-slate-800">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Init AOS -->
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
    </script>
</body>
</html>