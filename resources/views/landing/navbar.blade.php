<!-- NAVBAR SECTION -->
<!-- PERBAIKAN: Menambahkan x-data lokal untuk mengontrol Search Modal dan status Dark Mode -->
<nav x-data="{
        searchOpen: false,
        isDark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }"
    x-init="
        if(isDark) document.documentElement.classList.add('dark');
        $watch('searchOpen', value => {
            if(value) {
                setTimeout(() => $refs.searchInput.focus(), 100);
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    "
    :class="{ 'bg-[#1c2940]/95 backdrop-blur-xl shadow-lg border-b border-[#56bbf1]/20': scrolled, 'bg-transparent border-transparent': !scrolled }" 
    class="fixed top-0 w-full z-50 transition-all duration-300">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <!-- Logo Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group z-50">
                <!-- Icon Box (Elevate Glassmorphism) -->
                <div class="relative w-11 h-11 bg-white/10 border border-white/20 rounded-[1rem] flex items-center justify-center text-white shadow-lg shadow-[#56bbf1]/20 group-hover:rotate-6 transition-transform overflow-hidden shrink-0 backdrop-blur-sm">
                     <img src="{{ asset('images/netila.jpg') }}" alt="Logo" class="w-full h-full object-cover z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                     <i class="ph-bold ph-graduation-cap text-2xl hidden z-10"></i>
                </div>
                
                <!-- Text Brand -->
                <div class="flex flex-col leading-tight">
                    <span class="font-black text-white text-lg tracking-tight group-hover:text-[#56bbf1] transition-colors">SMPN 3 LAKBOK</span>
                    <span class="font-bold text-[#56bbf1] uppercase tracking-widest group-hover:text-white transition-colors text-[10px]">Berjaya</span>
                    <span class="text-[8px] font-bold text-white/70 uppercase tracking-widest group-hover:text-white transition-colors">Unggul & Berkarakter</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <!-- Grup 1: Menu Informasi -->
                <div class="flex gap-6 text-sm font-bold text-white/80">
                    <a href="#" class="hover:text-white transition relative group">
                        Beranda <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#56bbf1] transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#profil" class="hover:text-white transition relative group">
                        Profil <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#56bbf1] transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#akademik" class="hover:text-white transition relative group">
                        Akademik <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#56bbf1] transition-all group-hover:w-full"></span>
                    </a>                        
                    <a href="#galeri" class="hover:text-white transition relative group">
                        Galeri <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#56bbf1] transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#kontak" class="hover:text-white transition relative group">
                        Kontak <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#56bbf1] transition-all group-hover:w-full"></span>
                    </a>   
                </div>                    

                <!-- Divider -->
                <div class="h-6 w-px bg-white/20"></div>                   
                    
                <!-- Grup 2: Menu Aplikasi -->
                <div class="flex items-center gap-4">
                    @if(Auth::guard('student')->check())
                        <!-- Jika Login sebagai SISWA -->
                        <div class="flex items-center gap-3 pl-2">
                            <a href="{{ route('students.learning.index') }}" class="px-5 py-2.5 rounded-full bg-[#56bbf1] text-[#1c2940] text-xs font-bold shadow-lg shadow-[#56bbf1]/30 hover:bg-white transition flex items-center gap-2 group active:scale-95">
                                <span>Dashboard</span>
                                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <!-- Tombol Logout -->
                            <form method="POST" action="{{ route('student.logout') }}">
                                @csrf
                                <button type="submit" class="w-9 h-9 rounded-full bg-white/10 text-rose-400 hover:text-white hover:bg-rose-500 flex items-center justify-center transition border border-white/20 active:scale-95" title="Keluar">
                                    <i class="ph-bold ph-sign-out text-lg"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Tautan Pintasan -->
                        <a href="{{ route('library.catalogue')}}" class="text-sm font-bold text-[#56bbf1] hover:text-white transition flex items-center gap-2">
                            Katalog Buku
                        </a>
                        <a href="{{ route('ppdb.create') }}" class="text-sm font-bold text-[#56bbf1] hover:text-white transition flex items-center gap-2">
                            PPDB
                        </a> 
                        <a href="{{ route('portal.index') }}" class="mr-2 text-sm font-bold text-white/80 hover:text-white transition">
                            Portal Siswa
                        </a>
                        
                        <!-- Tombol Staff -->
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-md text-white text-xs font-bold hover:bg-[#56bbf1] hover:text-[#1c2940] transition border border-white/20 flex items-center gap-2 active:scale-95">
                            <i class="ph-bold ph-lock-key"></i> Staff
                        </a>
                    @endif
                </div>

                <!-- Divider Tools -->
                <div class="h-6 w-px bg-white/20 ml-2"></div>

                <!-- Tools (Search & Dark Mode) -->
                <div class="flex items-center gap-1">
                    <button @click="searchOpen = true" class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition-colors focus:outline-none" title="Pencarian Global">
                        <i class="ph-bold ph-magnifying-glass text-xl"></i>
                    </button>
                    <button @click="toggleTheme()" class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition-colors focus:outline-none" title="Mode Gelap / Terang">
                        <!-- Mengganti icon Matahari/Bulan secara dinamis -->
                        <i class="ph-bold text-xl" :class="isDark ? 'ph-sun' : 'ph-moon'"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Button & Tools -->
            <div class="flex md:hidden items-center gap-1 z-50">
                <button @click="searchOpen = true" class="p-2 text-white/80 hover:text-white bg-white/10 rounded-xl transition-colors focus:outline-none backdrop-blur-sm">
                    <i class="ph-bold ph-magnifying-glass text-[22px]"></i>
                </button>
                <button @click="toggleTheme()" class="p-2 text-white/80 hover:text-white bg-white/10 rounded-xl transition-colors focus:outline-none backdrop-blur-sm">
                    <i class="ph-bold text-[22px]" :class="isDark ? 'ph-sun' : 'ph-moon'"></i>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-white/80 hover:text-white bg-white/10 rounded-xl transition-colors focus:outline-none backdrop-blur-sm ml-1">
                    <i class="ph-bold text-[22px]" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-full"
            class="fixed inset-0 bg-[#1c2940]/98 backdrop-blur-xl z-[60] md:hidden flex flex-col pt-24 px-6 overflow-y-auto">
            
        <nav class="flex flex-col items-center space-y-6 text-center w-full px-8 pb-10">
            <!-- Mobile PPDB Link -->
            <a href="{{ route('ppdb.create') }}" class="w-full py-3 bg-[#56bbf1] rounded-[1rem] text-[#1c2940] font-bold text-lg shadow-lg shadow-[#56bbf1]/30">
                <i class="ph-bold ph-student mr-2"></i> Info PPDB 2025
            </a>
            
            <a href="#profil" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white/80 hover:text-[#56bbf1] transition">Profil Sekolah</a>
            <a href="#guru" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white/80 hover:text-[#56bbf1] transition">Guru & Staff</a>
            <a href="#kegiatan" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white/80 hover:text-[#56bbf1] transition">Kegiatan</a>
            <a href="#prestasi" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white/80 hover:text-[#56bbf1] transition">Prestasi</a>
            <a href="#ekskul" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white/80 hover:text-[#56bbf1] transition">Ekskul</a>
            
            <hr class="w-16 border-white/20">

            <div class="flex flex-col gap-4 w-full">
                <a href="{{ route('portal.index') }}" class="text-lg font-bold text-[#56bbf1]">Portal Siswa</a>
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="block w-full py-3 rounded-xl bg-gradient-to-r from-[#56bbf1] to-[#0d52a1] text-white font-bold shadow-lg shadow-[#56bbf1]/30">Dashboard Siswa</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full py-3 rounded-xl bg-white/10 border border-white/20 text-white font-bold hover:bg-[#56bbf1] transition-colors">Login Staff</a>
                @endif
            </div>
        </nav>
    </div>

    <!-- MODAL PENCARIAN GLOBAL (COMMAND PALETTE) -->
    <div x-show="searchOpen" x-cloak
         class="fixed inset-0 z-[70] flex items-start justify-center pt-16 sm:pt-24 px-4"
         @keydown.escape.window="searchOpen = false">

        <!-- Overlay Backdrop -->
        <div class="fixed inset-0 bg-[#1c2940]/80 backdrop-blur-md"
             @click="searchOpen = false"
             x-show="searchOpen"
             x-transition.opacity></div>

        <!-- Panel Modal (Tema Elevate Light & Dark) -->
        <div x-show="searchOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
             class="relative bg-white dark:bg-[#1c2940] w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden border border-slate-200 dark:border-[#2c3f61] flex flex-col"
             @click.stop>

            <!-- Area Input Pencarian -->
            <form action="#" method="GET" class="flex items-center px-5 py-4 border-b border-slate-100 dark:border-[#2c3f61]">
                <i class="ph-bold ph-magnifying-glass text-2xl text-[#56bbf1]"></i>
                <input x-ref="searchInput" type="text" name="q" class="w-full bg-transparent border-0 focus:ring-0 text-[#2c3f61] dark:text-white px-4 text-lg font-medium placeholder-slate-400 dark:placeholder-slate-500 outline-none" placeholder="Cari guru, e-book, atau informasi...">
                <button type="button" @click="searchOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-[#2c3f61] dark:text-slate-400 dark:hover:text-white bg-slate-100 dark:bg-[#2c3f61] text-[10px] font-bold uppercase tracking-wider px-2 transition-colors">ESC</button>
            </form>

            <!-- Area Pintasan (Quick Links) -->
            <div class="p-6 bg-slate-50 dark:bg-[#1c2940]/50">
                <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Pencarian Populer & Cepat</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('ppdb.create') }}" class="px-4 py-2 bg-white dark:bg-[#2c3f61] rounded-xl text-sm font-bold text-[#2c3f61] dark:text-white/90 border border-slate-200 dark:border-[#2c3f61] hover:border-[#56bbf1] dark:hover:border-[#56bbf1] hover:text-[#0d52a1] dark:hover:text-[#56bbf1] transition shadow-sm flex items-center gap-2">
                        <i class="ph-duotone ph-student text-[#56bbf1] text-lg"></i> Pendaftaran PPDB
                    </a>
                    <a href="{{ route('library.catalogue') }}" class="px-4 py-2 bg-white dark:bg-[#2c3f61] rounded-xl text-sm font-bold text-[#2c3f61] dark:text-white/90 border border-slate-200 dark:border-[#2c3f61] hover:border-[#56bbf1] dark:hover:border-[#56bbf1] hover:text-[#0d52a1] dark:hover:text-[#56bbf1] transition shadow-sm flex items-center gap-2">
                        <i class="ph-duotone ph-books text-[#56bbf1] text-lg"></i> Katalog E-Book
                    </a>
                    <a href="#guru" @click="searchOpen = false" class="px-4 py-2 bg-white dark:bg-[#2c3f61] rounded-xl text-sm font-bold text-[#2c3f61] dark:text-white/90 border border-slate-200 dark:border-[#2c3f61] hover:border-[#56bbf1] dark:hover:border-[#56bbf1] hover:text-[#0d52a1] dark:hover:text-[#56bbf1] transition shadow-sm flex items-center gap-2">
                        <i class="ph-duotone ph-users-three text-[#56bbf1] text-lg"></i> Direktori Guru
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>