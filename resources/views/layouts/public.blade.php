<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SMP Negeri 3 Lakbok'))</title>
    
     <!-- Meta Description (Optional via stack) -->
    @stack('meta')

     <!-- ============================================== -->
     <!-- PWA META TAGS KHUSUS SISWA (NETILA)            -->
     <!-- ============================================== -->
    <link rel="manifest" href="{{ asset('manifest-siswa.json') }}">
    <meta name="theme-color" content="#0284c7"> <!-- Tema Sky/Blue 600 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Netila">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-siswa-192x192.png') }}">
     <!-- ============================================== -->


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Library Eksternal -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>   
    
    <!-- Global Styles -->
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
        
        /* Utility */
        .min-h-content { min-height: calc(100vh - 400px); }
        
        /* Animasi Global */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>

    @stack('styles')
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden flex flex-col min-h-screen selection:bg-cyan-500 selection:text-white"
    x-data="{ 
        mobileMenuOpen: false,
        scrolled: false
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false">

    <!-- === NAVBAR (TEMA: CYAN - BLUE ELEVATE) === -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-blue-600/30 bg-gradient-to-r from-cyan-600 via-blue-600 to-blue-900 shadow-xl shadow-blue-900/10"
         :class="{ 'bg-opacity-95 backdrop-blur-md': scrolled, 'bg-opacity-100': !scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Logo Brand -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group">
                    <!-- Icon Box (Glassmorphism) -->
                    <div class="relative w-10 h-10 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl flex items-center justify-center text-white shadow-lg shadow-cyan-900/30 group-hover:rotate-6 transition-transform overflow-hidden">
                         <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                         <i class="ph-bold ph-buildings text-xl hidden z-10"></i>
                    </div>
                    
                    <!-- Text Brand -->
                    <div class="flex flex-col leading-tight">
                        <span class="font-bold text-white text-lg tracking-tight group-hover:text-cyan-200 transition-colors">SMPN 3 LAKBOK</span>
                        <span class="text-[10px] font-bold text-cyan-200 uppercase tracking-widest group-hover:text-white transition-colors">Unggul & Berkarakter</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    
                    <!-- Grup 1: Menu Informasi -->
                    <div class="flex items-center gap-6">
                        <a href="{{ url('/') }}#profil" class="text-sm font-bold text-blue-100 hover:text-white transition relative group">
                            Profil
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-300 transition-all group-hover:w-full"></span>
                        </a>
                        <a href="{{ url('/') }}#guru" class="text-sm font-bold text-blue-100 hover:text-white transition relative group">
                            Guru
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-300 transition-all group-hover:w-full"></span>
                        </a>
                        <a href="{{ url('/') }}#prestasi" class="text-sm font-bold text-blue-100 hover:text-white transition relative group">
                            Prestasi
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-300 transition-all group-hover:w-full"></span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-white/20"></div>

                    <!-- Grup 2: Menu Aplikasi -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('portal.index') }}" class="text-sm font-bold {{ request()->routeIs('portal.*') ? 'text-cyan-300' : 'text-blue-100 hover:text-white' }} transition flex items-center gap-2">
                            Portal Siswa
                        </a>

                        {{-- [LOGIKA TOMBOL DINAMIS] --}}
                        @if(Auth::guard('student')->check())
                            <!-- Jika Login sebagai SISWA -->
                            <div class="flex items-center gap-3 pl-2">
                                <a href="{{ route('students.learning.index') }}" class="text-xs font-bold px-4 py-2 rounded-full bg-white text-blue-700 hover:bg-cyan-50 transition shadow-lg shadow-blue-900/30 flex items-center gap-2">
                                    <i class="ph-bold ph-student"></i> Area Siswa
                                </a>
                                <!-- Tombol Logout -->
                                <form method="POST" action="{{ route('student.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-full bg-white/10 text-white hover:text-rose-400 hover:bg-white/20 flex items-center justify-center transition border border-white/20" title="Keluar">
                                        <i class="ph-bold ph-sign-out text-lg"></i>
                                    </button>
                                </form>
                            </div>

                        @elseif(Auth::check())
                            <!-- Jika Login sebagai GURU/ADMIN -->
                            <a href="{{ route('dashboard') }}" class="text-xs font-bold px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-md text-white hover:bg-white hover:text-blue-700 transition shadow-lg border border-white/20 flex items-center gap-2">
                                <i class="ph-bold ph-squares-four"></i> Dashboard Guru
                            </a>

                        @else
                            <!-- Jika BELUM LOGIN -->
                            <a href="{{ route('login') }}" class="text-xs font-bold px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-md text-white hover:bg-white hover:text-blue-700 transition shadow-lg border border-white/20 flex items-center gap-2">
                                <i class="ph-bold ph-lock-key"></i> Login Staff
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-white hover:bg-white/10 rounded-lg transition-colors focus:outline-none">
                        <i class="ph-bold text-2xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-cloak
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-5"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-5"
             class="absolute top-20 left-0 w-full bg-blue-950/98 backdrop-blur-xl border-b border-blue-900 shadow-2xl md:hidden z-40">
             
            <nav class="flex flex-col p-6 space-y-4">
                <a href="{{ url('/') }}#profil" class="text-lg font-bold text-blue-100 hover:text-cyan-300">Profil Sekolah</a>
                <a href="{{ url('/') }}#guru" class="text-lg font-bold text-blue-100 hover:text-cyan-300">Guru & Staff</a>
                <a href="{{ url('/') }}#prestasi" class="text-lg font-bold text-blue-100 hover:text-cyan-300">Prestasi</a>
                <a href="{{ route('portal.index') }}" class="text-lg font-bold text-cyan-400">Portal Siswa</a>
                
                <hr class="border-blue-800/50">
                
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-cyan-500 text-blue-950 font-bold shadow-lg shadow-cyan-900/20">Dashboard Siswa</a>
                    <form method="POST" action="{{ route('student.logout') }}" class="block w-full">
                        @csrf
                        <button type="submit" class="w-full text-center px-6 py-3 rounded-xl border border-rose-500/50 text-rose-400 font-bold hover:bg-rose-500/10">Keluar</button>
                    </form>
                @elseif(Auth::check())
                    <a href="{{ route('dashboard') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-white/10 text-white font-bold border border-white/20">Dashboard Guru</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-white/10 text-white font-bold border border-white/20">Login Staff</a>
                @endif
            </nav>
        </div>
    </nav>

    <!-- === KONTEN UTAMA === -->
    <!-- pt-24 untuk memberi ruang karena navbar fixed dan lebih tinggi -->
    <main class="flex-grow pt-24 pb-12 relative z-10 min-h-content">
        <!-- Background Dekorasi Halus -->
        <div class="fixed inset-0 z-[-1] pointer-events-none">
             <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-cyan-500/5 rounded-full blur-[100px] -mr-20 -mt-20"></div>
             <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-600/5 rounded-full blur-[100px] -ml-20 -mb-20"></div>
        </div>

        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <!-- === FOOTER === -->
    <footer class="bg-blue-950 text-white pt-16 pb-8 border-t border-blue-900 relative z-20 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white border border-white/20 rounded-xl flex items-center justify-center text-blue-600 shadow-lg shadow-cyan-900/50">
                             <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                             <i class="ph-bold ph-buildings text-xl hidden"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">SMPN 3 LAKBOK</span>
                    </div>
                    <p class="text-blue-200/70 text-sm leading-relaxed mb-6">
                        Platform layanan pendidikan digital terintegrasi untuk mendukung kegiatan akademik dan pembentukan karakter siswa.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Akses Cepat</h4>
                    <ul class="space-y-3 text-sm text-blue-200/70">
                        <li><a href="{{ url('/') }}" class="hover:text-cyan-300 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('portal.index') }}" class="hover:text-cyan-300 transition-colors">Portal Siswa</a></li>
                        <li><a href="{{ route('library.kiosk.index') }}" class="hover:text-cyan-300 transition-colors">E-Library</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Kontak</h4>
                    <ul class="space-y-3 text-sm text-blue-200/70">
                        <li class="flex items-center gap-3"><i class="ph-fill ph-phone text-cyan-400"></i> +62 85135961994</li>
                        <li class="flex items-center gap-3"><i class="ph-fill ph-envelope text-cyan-400"></i> admin@smpn3lakbok.sch.id</li>
                    </ul>
                </div>
            </div>
            <div class="text-center pt-8 border-t border-blue-900/50">
                <p class="text-blue-300/50 text-sm">
                    &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Unggul & Berkarakter. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Global Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .catch(err => console.log('PWA ServiceWorker gagal: ', err));
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>