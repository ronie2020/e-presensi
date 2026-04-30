<!-- NAVBAR SECTION -->
<nav x-data="{
        searchOpen: false,
        mobileMenuOpen: false,
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
    :class="{ 'py-4': !scrolled, 'py-2': scrolled }" 
    class="fixed top-0 w-full z-50 transition-all duration-300">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Floating Pill Style on Scroll -->
        <div class="flex justify-between items-center transition-all duration-500"
             :class="{ 'bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-white/80 dark:border-slate-800 shadow-xl shadow-elevate-dark/5 rounded-[2.5rem] px-4 md:px-6 py-3': scrolled, 'px-2 py-2': !scrolled }">
            
            <!-- Logo Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group z-50">
                <div class="relative w-10 h-10 md:w-12 md:h-12 bg-white dark:bg-slate-800 rounded-xl md:rounded-2xl flex items-center justify-center text-elevate-primary shadow-sm border border-slate-100 dark:border-slate-700 group-hover:scale-105 group-hover:rotate-3 transition-transform overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 md:w-8 md:h-8 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <i class="ph-bold ph-buildings text-xl hidden z-10"></i>
                </div>
                
                <div class="flex flex-col leading-tight">
                    <span class="font-black text-elevate-dark dark:text-white text-base md:text-lg tracking-tight group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors">SMPN 3 LAKBOK</span>
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-elevate-accent uppercase tracking-widest text-[9px] md:text-[10px]">Berjaya</span>
                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600 hidden sm:block"></span>
                        <span class="text-[8px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest hidden sm:block">Unggul & Berkarakter</span>
                    </div>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-1 bg-slate-100/80 dark:bg-slate-800/80 backdrop-blur-md px-2 py-1.5 rounded-full border border-slate-200/60 dark:border-slate-700/50 shadow-inner">
                <!-- State Aktif (Beranda) -->
                <a href="#" class="px-5 py-2 rounded-full text-xs font-bold bg-white dark:bg-slate-700 text-elevate-primary dark:text-elevate-accent shadow-sm transition-all">Beranda</a>
                <!-- Link Normal -->
                <a href="#profil" class="px-5 py-2 rounded-full text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent hover:shadow-sm transition-all">Profil</a>
                <a href="#akademik" class="px-5 py-2 rounded-full text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent hover:shadow-sm transition-all">Akademik</a>                        
                <a href="#galeri" class="px-5 py-2 rounded-full text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent hover:shadow-sm transition-all">Galeri</a>               
            </div>                    

            <!-- Right Actions (Desktop) -->
            <div class="hidden md:flex items-center gap-4">
                
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="px-6 py-2.5 rounded-full bg-elevate-accent hover:bg-elevate-accent/80 text-elevate-dark text-xs font-bold shadow-lg shadow-elevate-accent/20 transition-all flex items-center gap-2 group">
                        <span>Dashboard Siswa</span>
                        <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                @else
                    <div class="flex items-center gap-3 mr-2">
                        <a href="{{ route('library.catalogue')}}" class="text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-elevate-primary dark:hover:text-elevate-accent transition flex items-center gap-1.5">
                            <i class="ph-bold ph-books text-sm"></i> Katalog
                        </a>
                        <a href="{{ route('ppdb.create') }}" class="text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-elevate-primary dark:hover:text-elevate-accent transition flex items-center gap-1.5">
                            <i class="ph-bold ph-student text-sm"></i> PPDB
                        </a> 
                    </div>
                    
                    <!-- PERBAIKAN: Tombol Portal & Login Staff di Desktop -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent hover:bg-slate-50 transition-all shadow-sm flex items-center gap-1.5">
                            <i class="ph-bold ph-lock-key"></i> Akses Guru
                        </a>
                        <a href="{{ route('portal.index') }}" class="px-5 py-2.5 rounded-full bg-elevate-dark dark:bg-elevate-primary text-white text-xs font-black hover:bg-elevate-primary dark:hover:bg-elevate-accent dark:hover:text-elevate-dark transition-all shadow-lg shadow-elevate-dark/20 flex items-center gap-1.5">
                            <i class="ph-bold ph-sign-in"></i> Portal Siswa
                        </a>
                    </div>
                @endif

                <!-- Divider -->
                <div class="h-6 w-px bg-slate-300 dark:bg-slate-700 mx-1"></div>                  
                    
                <!-- Tools (Search & Dark Mode) -->
                <div class="flex items-center gap-1.5">
                    <button @click="searchOpen = true" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 text-elevate-dark dark:text-slate-200 flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-elevate-soft dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent transition-all focus:outline-none" title="Pencarian Global">
                        <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    </button>
                    <button @click="toggleTheme()" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 text-elevate-dark dark:text-slate-200 flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-elevate-soft dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent transition-all focus:outline-none" title="Mode Gelap / Terang">
                        <i class="ph-bold text-lg" :class="isDark ? 'ph-sun' : 'ph-moon'"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Button & Tools -->
            <div class="flex md:hidden items-center gap-1.5 z-50">
                <button @click="searchOpen = true" class="w-9 h-9 rounded-xl bg-white/80 dark:bg-slate-800/80 text-elevate-dark dark:text-slate-200 flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-700 backdrop-blur-sm focus:outline-none">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                </button>
                <button @click="toggleTheme()" class="w-9 h-9 rounded-xl bg-white/80 dark:bg-slate-800/80 text-elevate-dark dark:text-slate-200 flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-700 backdrop-blur-sm focus:outline-none">
                    <i class="ph-bold text-lg" :class="isDark ? 'ph-sun' : 'ph-moon'"></i>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="w-9 h-9 rounded-xl bg-elevate-dark text-white flex items-center justify-center shadow-md focus:outline-none ml-1">
                    <i class="ph-bold text-xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
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
            class="fixed inset-0 bg-white/98 dark:bg-slate-950/98 backdrop-blur-xl z-[60] md:hidden flex flex-col pt-24 px-6 overflow-y-auto pb-10">
            
        <nav class="flex flex-col items-center space-y-6 text-center w-full px-4">
            <!-- Mobile PPDB Link -->
            <a href="{{ route('ppdb.create') }}" class="w-full py-4 bg-elevate-primary hover:bg-elevate-dark rounded-2xl text-white font-black text-lg shadow-xl shadow-elevate-primary/30 flex justify-center items-center transition-colors">
                <i class="ph-bold ph-student mr-2"></i> Info PPDB 2025
            </a>
            
            <a href="#profil" @click="mobileMenuOpen = false" class="text-xl font-black text-elevate-dark dark:text-slate-100 hover:text-elevate-primary transition-colors">Profil Sekolah</a>
            <a href="#akademik" @click="mobileMenuOpen = false" class="text-xl font-black text-elevate-dark dark:text-slate-100 hover:text-elevate-primary transition-colors">Akademik</a>
            <a href="#kegiatan" @click="mobileMenuOpen = false" class="text-xl font-black text-elevate-dark dark:text-slate-100 hover:text-elevate-primary transition-colors">Galeri Kegiatan</a>
            <a href="#prestasi" @click="mobileMenuOpen = false" class="text-xl font-black text-elevate-dark dark:text-slate-100 hover:text-elevate-primary transition-colors">Prestasi</a>
            <a href="#kontak" @click="mobileMenuOpen = false" class="text-xl font-black text-elevate-dark dark:text-slate-100 hover:text-elevate-primary transition-colors">Kontak</a>
            
            <div class="w-16 h-1 rounded-full bg-slate-200 dark:bg-slate-800 my-4"></div>

            <div class="flex flex-col gap-4 w-full mt-2">
                <a href="{{ route('portal.index') }}" class="text-lg font-black text-elevate-accent text-center mb-2">Pusat Portal Siswa</a>
                
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="block w-full py-3.5 rounded-xl bg-elevate-dark text-white font-black shadow-lg shadow-elevate-dark/30">Dashboard Siswa</a>
                @else
                    <a href="{{ route('portal.index') }}" class="block w-full py-3.5 rounded-xl bg-elevate-dark text-white font-black shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-sign-in text-elevate-accent"></i> Portal Siswa
                    </a>
                    <a href="{{ route('login') }}" class="block w-full py-3.5 rounded-xl bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold flex items-center justify-center gap-2 mt-2">
                        <i class="ph-bold ph-lock-key text-elevate-primary"></i> Login Staff / Guru
                    </a>
                    <a href="{{ route('library.catalogue') }}" class="block w-full py-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-300 font-bold flex items-center justify-center gap-2 mt-2">
                        <i class="ph-bold ph-books"></i> Katalog Perpustakaan
                    </a>
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
             class="relative bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col"
             @click.stop>

            <!-- Area Input Pencarian -->
            <!-- Form dummy: Nantinya Anda bisa mengarahkan action form ini ke Controller pencarian -->
            <form action="#" method="GET" class="flex items-center px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <i class="ph-bold ph-magnifying-glass text-2xl text-elevate-primary dark:text-elevate-accent"></i>
                <input x-ref="searchInput" type="text" name="q" class="w-full bg-transparent border-0 focus:ring-0 text-elevate-dark dark:text-white px-4 text-xl font-bold placeholder-slate-400 dark:placeholder-slate-500 outline-none" placeholder="Cari guru, e-book, atau informasi...">
                <button type="button" @click="searchOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-widest px-3 transition-colors border border-slate-200 dark:border-slate-700">ESC</button>
            </form>

            <!-- Area Pintasan (Quick Links) -->
            <div class="p-6 bg-slate-50 dark:bg-slate-800/30">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Pencarian Populer & Cepat</h4>
                <div class="flex flex-wrap gap-2.5">
                    <a href="{{ route('ppdb.create') }}" class="px-4 py-2 bg-white dark:bg-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent dark:hover:border-elevate-accent hover:text-elevate-primary dark:hover:text-elevate-accent transition shadow-sm">🎓 Pendaftaran PPDB</a>
                    <a href="{{ route('library.catalogue') }}" class="px-4 py-2 bg-white dark:bg-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent dark:hover:border-elevate-accent hover:text-elevate-primary dark:hover:text-elevate-accent transition shadow-sm">📚 Katalog E-Book</a>
                    <a href="#guru" @click="searchOpen = false" class="px-4 py-2 bg-white dark:bg-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent dark:hover:border-elevate-accent hover:text-elevate-primary dark:hover:text-elevate-accent transition shadow-sm">👨‍🏫 Direktori Guru</a>
                </div>
            </div>
        </div>
    </div>
</nav>