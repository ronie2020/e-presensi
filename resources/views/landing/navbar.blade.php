<!-- NAVBAR SECTION -->
<nav x-data="{
        searchOpen: false,
        mobileMenuOpen: false,
        scrolled: window.scrollY > 20,
    }"
    x-init="
        window.addEventListener('scroll', () => {
            scrolled = window.scrollY > 20;
        }, { passive: true });
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
             :class="{ 'bg-white/5 backdrop-blur-[32px] border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.3)] rounded-[2.5rem] px-4 md:px-6 py-3': scrolled, 'px-2 py-2': !scrolled }">
            
            <!-- Logo Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group z-50">
                <div class="relative w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-xl md:rounded-2xl flex items-center justify-center text-elevate-accent shadow-sm border border-white/20 group-hover:scale-105 group-hover:rotate-3 transition-transform overflow-hidden backdrop-blur-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 md:w-8 md:h-8 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <i class="ph-bold ph-buildings text-xl hidden z-10"></i>
                </div>
                
                <div class="flex flex-col leading-tight">
                    <span class="font-black text-white text-base md:text-lg tracking-tight group-hover:text-elevate-accent transition-colors">SMPN 3 LAKBOK</span>
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-elevate-accent uppercase tracking-widest text-[9px] md:text-[10px]">Berjaya</span>
                        <span class="w-1 h-1 rounded-full bg-white/30 hidden sm:block"></span>
                        <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest hidden sm:block">Unggul & Berkarakter</span>
                    </div>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-1 bg-white/5 backdrop-blur-md px-2 py-1.5 rounded-full border border-white/10 shadow-inner">
                <a href="#home" class="px-5 py-2 rounded-full text-xs font-bold transition-all" :class="activeSection === 'home' ? 'bg-white/20 text-white shadow-sm' : 'text-slate-300 hover:bg-white/10 hover:text-white'">Beranda</a>
                <a href="#profil" class="px-5 py-2 rounded-full text-xs font-bold transition-all" :class="activeSection === 'profil' ? 'bg-white/20 text-white shadow-sm' : 'text-slate-300 hover:bg-white/10 hover:text-white'">Profil</a>
                <a href="#layanan" class="px-5 py-2 rounded-full text-xs font-bold text-slate-300 hover:bg-white/10 hover:text-white transition-all">Layanan</a>
                <a href="#kegiatan" class="px-5 py-2 rounded-full text-xs font-bold transition-all" :class="activeSection === 'kegiatan' ? 'bg-white/20 text-white shadow-sm' : 'text-slate-300 hover:bg-white/10 hover:text-white'">Galeri</a>
                <a href="#prestasi" class="px-5 py-2 rounded-full text-xs font-bold transition-all" :class="activeSection === 'prestasi' ? 'bg-white/20 text-white shadow-sm' : 'text-slate-300 hover:bg-white/10 hover:text-white'">Prestasi</a>
                <a href="#kontak" class="px-5 py-2 rounded-full text-xs font-bold transition-all" :class="activeSection === 'kontak' ? 'bg-white/20 text-white shadow-sm' : 'text-slate-300 hover:bg-white/10 hover:text-white'">Kontak</a>
            </div>                    

            <!-- Right Actions (Desktop) -->
            <div class="hidden md:flex items-center gap-4">
                
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-elevate-accent to-elevate-primary hover:to-elevate-accent text-white text-xs font-bold shadow-[0_0_20px_rgba(86,187,241,0.4)] transition-all flex items-center gap-2 group border border-elevate-accent/30">
                        <span>Dashboard Siswa</span>
                        <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                @else
                    <div class="flex items-center gap-3 mr-2">
                        <a href="#katalog-lms" class="text-xs font-bold text-sky-300 hover:text-white transition flex items-center gap-1.5">
                            <i class="ph-bold ph-book-open text-sm"></i> Ruang Belajar
                        </a>
                        <a href="{{ route('library.catalogue')}}" class="text-xs font-bold text-slate-300 hover:text-elevate-accent transition flex items-center gap-1.5">
                            <i class="ph-bold ph-books text-sm"></i> Katalog Buku
                        </a>
                        <a href="{{ route('ppdb.create') }}" class="text-xs font-bold text-slate-300 hover:text-elevate-accent transition flex items-center gap-1.5">
                            <i class="ph-bold ph-student text-sm"></i> PPDB
                        </a> 
                    </div>
                    
                    <!-- Tombol Masuk Terpadu (Guru & Siswa) -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white text-xs font-black hover:bg-white/20 transition-all shadow-[0_0_15px_rgba(255,255,255,0.1)] flex items-center gap-1.5">
                            <i class="ph-bold ph-sign-in text-elevate-accent"></i> Masuk / Login
                        </a>
                    </div>
                @endif

                <!-- Divider -->
                <div class="h-6 w-px bg-white/20 mx-1"></div>                  
                    
                <!-- Tools (Search) -->
                <div class="flex items-center gap-1.5">
                    <button @click="searchOpen = true" class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center shadow-sm border border-white/20 hover:bg-white/20 transition-all focus:outline-none backdrop-blur-sm" title="Pencarian Global">
                        <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Button & Tools -->
            <div class="flex md:hidden items-center gap-1.5 z-50">
                <button @click="searchOpen = true" class="w-9 h-9 rounded-xl bg-white/10 text-white flex items-center justify-center shadow-sm border border-white/20 backdrop-blur-sm focus:outline-none hover:bg-white/20">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen.toString()" aria-label="Buka menu navigasi" class="w-9 h-9 rounded-xl bg-gradient-to-r from-elevate-accent to-elevate-primary text-white flex items-center justify-center shadow-[0_0_15px_rgba(86,187,241,0.4)] focus:outline-none ml-1">
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

            <div class="flex flex-col gap-3 w-full mt-2">
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="block w-full py-3.5 rounded-xl bg-elevate-dark text-white font-black shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-layout text-elevate-accent"></i> Dashboard Siswa
                    </a>
                @else
                    {{-- Pintasan Khusus Siswa: Belajar & CBT --}}
                    <div class="grid grid-cols-2 gap-2 mb-1">
                        <a href="{{ route('student.login.learning') }}" class="py-3 px-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-blue-600/20">
                            <i class="ph-bold ph-books text-base"></i> Ruang Belajar
                        </a>
                        <a href="{{ route('student.login.cbt') }}" class="py-3 px-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-rose-600/20">
                            <i class="ph-bold ph-monitor-play text-base"></i> Ujian CBT
                        </a>
                    </div>

                    {{-- 1 Tombol Login Terpadu (Siswa & Guru) --}}
                    <a href="{{ route('login') }}" class="block w-full py-3.5 rounded-2xl bg-elevate-dark text-white font-black shadow-xl shadow-elevate-dark/30 flex items-center justify-center gap-2 text-sm">
                        <i class="ph-bold ph-sign-in text-elevate-accent text-lg"></i> Masuk / Login Portal
                    </a>
                    <a href="{{ route('library.catalogue') }}" class="block w-full py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-300 font-bold text-xs flex items-center justify-center gap-2">
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
            <form action="#" method="GET" class="flex items-center px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <i class="ph-bold ph-magnifying-glass text-2xl text-elevate-primary dark:text-elevate-accent"></i>
                <input x-ref="searchInput" type="text" name="q" class="w-full bg-transparent border-0 focus:ring-0 text-elevate-dark dark:text-white px-4 text-xl font-bold placeholder-slate-400 dark:placeholder-slate-500 outline-none" placeholder="Cari guru, e-book, atau informasi...">
                <button type="button" @click="searchOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-widest px-3 transition-colors border border-slate-200 dark:border-slate-700">ESC</button>
            </form>

            <!-- Area Pintasan (Quick Links) -->
            <div class="p-6 bg-slate-50 dark:bg-slate-800/30">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Pencarian Populer & Akses Cepat</h4>
                <div class="flex flex-wrap gap-2.5">
                    <a href="{{ route('student.login.learning') }}" class="px-3.5 py-2 bg-blue-50 dark:bg-blue-950/40 rounded-xl text-xs font-bold text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:border-blue-400 transition shadow-sm flex items-center gap-1.5">
                        <i class="ph-bold ph-books"></i> Ruang Belajar (LMS)
                    </a>
                    <a href="{{ route('student.login.cbt') }}" class="px-3.5 py-2 bg-rose-50 dark:bg-rose-950/40 rounded-xl text-xs font-bold text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 hover:border-rose-400 transition shadow-sm flex items-center gap-1.5">
                        <i class="ph-bold ph-monitor-play"></i> Ujian Online (CBT)
                    </a>
                    <a href="{{ route('portal.index') }}" class="px-3.5 py-2 bg-white dark:bg-slate-800 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent transition shadow-sm flex items-center gap-1.5">
                        <i class="ph-bold ph-identification-card"></i> Portal Siswa
                    </a>
                    <a href="{{ route('ppdb.create') }}" class="px-3.5 py-2 bg-white dark:bg-slate-800 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-elevate-accent transition shadow-sm flex items-center gap-1.5">
                        <i class="ph-bold ph-student"></i> PPDB Online
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>