<nav :class="{ 'bg-slate-900/95 backdrop-blur-md shadow-xl border-b border-slate-800': scrolled, 'bg-transparent border-transparent': !scrolled }" class="fixed top-0 w-full z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <!-- Logo Brand -->
            <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-3 shrink-0 group z-50">
                <div class="relative w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-900 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:rotate-6 transition-transform overflow-hidden border border-white/10">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-7 h-7 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                        <i class="ph-bold ph-buildings text-xl hidden z-10"></i>
                </div>
                
                <div class="flex flex-col leading-tight">
                    <span class="font-bold text-white text-lg tracking-tight group-hover:text-blue-200 transition-colors">SMPN 3 LAKBOK</span>
                    <span class="font-bold text-slate-400 uppercase tracking-widest group-hover:text-yellow-400 transition-colors">Berjaya </span>
                    <span class="text-[8px] font-bold text-slate-200 uppercase tracking-widest group-hover:text-yellow-200 transition-colors">Jujur dan Berkarakter </span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <div class="flex gap-6 text-sm font-medium text-slate-300">
                    <a href="#" class="hover:text-white transition-colors">Beranda</a>
                    <a href="#profil" class="hover:text-white transition-colors">Profil</a>
                    <a href="#akademik" class="hover:text-white transition-colors">Akademik</a>                        
                    <a href="#galeri" class="hover:text-white transition-colors">Galeri</a>
                    <a href="#kontak" class="hover:text-white transition-colors">Kontak</a>   
                </div>                    

                <!-- Divider -->
                <div class="h-6 w-px bg-slate-700"></div>                   
                    
                        <?php if(Auth::guard('student')->check()): ?>
                    <a href="<?php echo e(route('students.learning.index')); ?>" class="px-5 py-2.5 rounded-full bg-blue-600 text-white text-xs font-bold shadow-lg shadow-blue-500/40 hover:bg-blue-500 transition border-t border-white/20 flex items-center gap-2 group">
                        <span>Dashboard</span>
                        <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                <?php else: ?>
                    
                        <a href="<?php echo e(route('library.catalogue')); ?>" class="text-sm font-bold text-blue-400 hover:text-white transition flex items-center gap-2">
                        Katalog Buku
                    </a>
                        <a href="<?php echo e(route('ppdb.create')); ?>" class="text-sm font-bold text-blue-400 hover:text-white transition flex items-center gap-2">
                        PPDB
                    </a> 
                    <a href="<?php echo e(route('portal.index')); ?>" class="mr-2 text-sm font-bold text-blue-300 hover:text-white transition">
                        Portal Siswa
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="px-5 py-2.5 rounded-full bg-slate-700 text-slate-200 text-xs font-bold hover:text-white hover:bg-slate-600 transition border border-slate-600 flex items-center gap-2">
                        <i class="ph-bold ph-lock-key"></i> Staff
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex md:hidden items-center z-50">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-300 hover:text-white bg-white/10 rounded-lg transition-colors focus:outline-none backdrop-blur-sm">
                    <i class="ph-bold text-2xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay (FIXED Z-INDEX) -->
    <div x-show="mobileMenuOpen" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-full"
            class="fixed inset-0 bg-slate-900 z-[60] md:hidden flex flex-col pt-24 px-6 overflow-y-auto">
            
        <nav class="flex flex-col items-center space-y-6 text-center w-full px-8">
            <!-- Mobile PPDB Link (Updated Color) -->
            <a href="<?php echo e(route('ppdb.create')); ?>" class="w-full py-3 bg-blue-600 rounded-xl text-white font-bold text-lg shadow-lg">
                <i class="ph-bold ph-student mr-2"></i> Info PPDB 2025
            </a>
            
            <a href="#profil" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-300 hover:text-yellow-400 transition">Profil Sekolah</a>
            <a href="#guru" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-300 hover:text-yellow-400 transition">Guru & Staff</a>
            <a href="#kegiatan" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-300 hover:text-yellow-400 transition">Kegiatan</a>
            <a href="#prestasi" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-300 hover:text-yellow-400 transition">Prestasi</a>
            <a href="#ekskul" @click="mobileMenuOpen = false" class="text-2xl font-bold text-slate-300 hover:text-yellow-400 transition">Ekskul</a>
            
            <hr class="w-16 border-slate-700">

            <div class="flex flex-col gap-4 w-full">
                <a href="<?php echo e(route('portal.index')); ?>" class="text-lg font-bold text-blue-400">Portal Siswa</a>
                <?php if(Auth::guard('student')->check()): ?>
                    <a href="<?php echo e(route('students.learning.index')); ?>" class="block w-full py-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/30">Dashboard Siswa</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="block w-full py-3 rounded-xl bg-slate-800 text-white font-bold border border-slate-700">Login Staff</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</nav><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/navbar.blade.php ENDPATH**/ ?>