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
    :class="{ 'bg-elevate-dark/95 backdrop-blur-md shadow-xl border-b border-elevate-primary/50': scrolled, 'bg-transparent border-transparent': !scrolled }" 
    class="fixed top-0 w-full z-50 transition-all duration-300">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <!-- Logo Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group z-50">
                <div class="relative w-10 h-10 bg-white rounded-xl flex items-center justify-center text-elevate-primary shadow-lg shadow-elevate-dark/20 group-hover:rotate-6 transition-transform overflow-hidden border border-white/20">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                        <i class="ph-bold ph-buildings text-xl hidden z-10"></i>
                </div>
                
                <div class="flex flex-col leading-tight">
                    <span class="font-bold text-white text-lg tracking-tight group-hover:text-elevate-accent transition-colors">SMPN 3 LAKBOK</span>
                    <span class="font-bold text-elevate-accent uppercase tracking-widest group-hover:text-white transition-colors">Berjaya </span>
                    <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest group-hover:text-white transition-colors">Unggul & Berkarakter </span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <div class="flex gap-6 text-sm font-medium text-slate-100">
                    <a href="#" class="hover:text-elevate-accent transition-colors">Beranda</a>
                    <a href="#profil" class="hover:text-elevate-accent transition-colors">Profil</a>
                    <a href="#akademik" class="hover:text-elevate-accent transition-colors">Akademik</a>                        
                    <a href="#galeri" class="hover:text-elevate-accent transition-colors">Galeri</a>
                    <a href="#kontak" class="hover:text-elevate-accent transition-colors">Kontak</a>   
                </div>                    

                <!-- Divider -->
                <div class="h-6 w-px bg-elevate-primary/50"></div>                   
                    
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="px-5 py-2.5 rounded-full bg-elevate-accent text-elevate-dark text-xs font-bold shadow-lg shadow-elevate-accent/40 hover:bg-elevate-accent/80 transition border border-elevate-accent flex items-center gap-2 group">
                        <span>Dashboard</span>
                        <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                @else
                    <a href="{{ route('library.catalogue')}}" class="text-sm font-bold text-elevate-accent hover:text-white transition flex items-center gap-2">
                        Katalog Buku
                    </a>
                    <a href="{{ route('ppdb.create') }}" class="text-sm font-bold text-elevate-accent hover:text-white transition flex items-center gap-2">
                        PPDB
                    </a> 
                    <a href="{{ route('portal.index') }}" class="mr-2 text-sm font-bold text-slate-100 hover:text-white transition">
                        Portal Siswa
                    </a>
                    <!-- Tombol Staff -->
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-md text-white text-xs font-bold hover:bg-white hover:text-elevate-primary transition border border-white/20 flex items-center gap-2">
                        <i class="ph-bold ph-lock-key"></i> Staff
                    </a>
                @endif

                <!-- Divider Tools -->
                <div class="h-6 w-px bg-elevate-primary/50 ml-2"></div>

                <!-- Tools (Search & Dark Mode) -->
                <div class="flex items-center gap-1">
                    <button @click="searchOpen = true" class="p-2 text-elevate-accent hover:text-white hover:bg-white/10 rounded-full transition-colors focus:outline-none" title="Pencarian Global">
                        <i class="ph-bold ph-magnifying-glass text-xl"></i>
                    </button>
                    <button @click="toggleTheme()" class="p-2 text-elevate-accent hover:text-white hover:bg-white/10 rounded-full transition-colors focus:outline-none" title="Mode Gelap / Terang">
                        <!-- Mengganti icon Matahari/Bulan secara dinamis -->
                        <i class="ph-bold text-xl" :class="isDark ? 'ph-sun' : 'ph-moon'"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Button & Tools -->
            <div class="flex md:hidden items-center gap-1 z-50">
                <button @click="searchOpen = true" class="p-2 text-slate-100 hover:text-white bg-white/10 rounded-lg transition-colors focus:outline-none backdrop-blur-sm">
                    <i class="ph-bold ph-magnifying-glass text-[22px]"></i>
                </button>
                <button @click="toggleTheme()" class="p-2 text-slate-100 hover:text-white bg-white/10 rounded-lg transition-colors focus:outline-none backdrop-blur-sm">
                    <i class="ph-bold text-[22px]" :class="isDark ? 'ph-sun' : 'ph-moon'"></i>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-100 hover:text-white bg-white/10 rounded-lg transition-colors focus:outline-none backdrop-blur-sm ml-1">
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
            class="fixed inset-0 bg-elevate-dark/98 backdrop-blur-xl z-[60] md:hidden flex flex-col pt-24 px-6 overflow-y-auto">
            
        <nav class="flex flex-col items-center space-y-6 text-center w-full px-8">
            <!-- Mobile PPDB Link -->
            <a href="{{ route('ppdb.create') }}" class="w-full py-3 bg-elevate-accent rounded-xl text-elevate-dark font-bold text-lg shadow-lg shadow-elevate-accent/30">
                <i class="ph-bold ph-student mr-2"></i> Info PPDB 2025
            </a>
            
            <a href="#profil" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-100 hover:text-elevate-accent transition">Profil Sekolah</a>
            <a href="#guru" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-100 hover:text-elevate-accent transition">Guru & Staff</a>
            <a href="#kegiatan" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-100 hover:text-elevate-accent transition">Kegiatan</a>
            <a href="#prestasi" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-100 hover:text-elevate-accent transition">Prestasi</a>
            <a href="#ekskul" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-100 hover:text-elevate-accent transition">Ekskul</a>
            
            <hr class="w-16 border-elevate-primary/50">

            <div class="flex flex-col gap-4 w-full pb-10">
                <a href="{{ route('portal.index') }}" class="text-lg font-bold text-elevate-accent">Portal Siswa</a>
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="block w-full py-3 rounded-xl bg-elevate-primary text-white font-bold shadow-lg shadow-elevate-dark/30">Dashboard Siswa</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full py-3 rounded-xl bg-white/10 border border-white/20 text-white font-bold">Login Staff</a>
                @endif
            </div>
        </nav>
    </div>

    <!-- MODAL PENCARIAN GLOBAL (COMMAND PALETTE) -->
    <div x-show="searchOpen" x-cloak
         class="fixed inset-0 z-[70] flex items-start justify-center pt-16 sm:pt-24 px-4"
         @keydown.escape.window="searchOpen = false">

        <!-- Overlay Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="searchOpen = false"
             x-show="searchOpen"
             x-transition.opacity></div>

        <!-- Panel Modal -->
        <div x-show="searchOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
             class="relative bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col"
             @click.stop>

            <!-- Area Input Pencarian -->
            <!-- Form dummy: Nantinya Anda bisa mengarahkan action form ini ke Controller pencarian -->
            <form action="#" method="GET" class="flex items-center px-4 py-4 border-b border-slate-100 dark:border-slate-800">
                <i class="ph-bold ph-magnifying-glass text-xl text-slate-400 dark:text-slate-500"></i>
                <input x-ref="searchInput" type="text" name="q" class="w-full bg-transparent border-0 focus:ring-0 text-slate-900 dark:text-white px-4 text-lg placeholder-slate-400 dark:placeholder-slate-500 outline-none" placeholder="Cari guru, e-book, atau informasi...">
                <button type="button" @click="searchOpen = false" class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold uppercase tracking-wider px-2 transition-colors">ESC</button>
            </form>

            <!-- Area Pintasan (Quick Links) -->
            <div class="p-5 bg-slate-50 dark:bg-slate-800/30">
                <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Pencarian Populer & Cepat</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('ppdb.create') }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent dark:hover:border-elevate-accent hover:text-elevate-primary dark:hover:text-elevate-accent transition shadow-sm">🎓 Pendaftaran PPDB</a>
                    <a href="{{ route('library.catalogue') }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent dark:hover:border-elevate-accent hover:text-elevate-primary dark:hover:text-elevate-accent transition shadow-sm">📚 Katalog E-Book</a>
                    <a href="#guru" @click="searchOpen = false" class="px-3 py-1.5 bg-white dark:bg-slate-800 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent dark:hover:border-elevate-accent hover:text-elevate-primary dark:hover:text-elevate-accent transition shadow-sm">👨‍🏫 Direktori Guru</a>
                </div>
            </div>
        </div>
    </div>
</nav>