<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    
    <title><?php echo e(config('app.name', 'SMP Negeri 3 Lakbok')); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles & Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Animation Library (AOS) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; border: 2px solid #f8fafc; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Utility Animations */
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        /* Glassmorphism Utilities */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-dark {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Book Cover 3D Effect */
        .book-card { perspective: 1000px; }
        .book-inner { transition: transform 0.5s; transform-style: preserve-3d; }
        .book-card:hover .book-inner { transform: rotateY(-10deg) scale(1.05); }

        /* Preloader */
        #preloader { position: fixed; inset: 0; z-index: 9999; background: #0f172a; display: flex; justify-content: center; align-items: center; transition: opacity 0.5s ease-out, visibility 0.5s ease-out; }
        .loader { width: 48px; height: 48px; border: 5px solid #FFF; border-bottom-color: #3b82f6; border-radius: 50%; display: inline-block; box-sizing: border-box; animation: rotation 1s linear infinite; }
        @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .hide-preloader { opacity: 0; visibility: hidden; }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden selection:bg-blue-500 selection:text-white" 
    x-data="{ 
        mobileMenuOpen: false,
        modalOpen: false, 
        guestBookModalOpen: false,
        guestListModalOpen: false,
        activeAnnouncement: null,
        scrolled: false,
        showBackToTop: false,
        activeSection: 'home',
        
        openAnnouncementByIndex(index) {
            if (window.announcementsData && window.announcementsData[index]) {
                this.activeAnnouncement = window.announcementsData[index];
                this.modalOpen = true;
                document.body.style.overflow = 'hidden';
            }
        },
        
        closeAnnouncement() {
            this.modalOpen = false;
            setTimeout(() => { this.activeAnnouncement = null }, 300);
            document.body.style.overflow = 'auto';
        },

        init() {
            // Preloader logic
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.getElementById('preloader').classList.add('hide-preloader');
                }, 800);
            });
        }
    }" 
    @scroll.window="
        scrolled = (window.pageYOffset > 20) ? true : false;
        showBackToTop = (window.pageYOffset > 500) ? true : false;
        
        // Simple Scroll Spy
        const sections = ['home', 'profil', 'kegiatan', 'prestasi', 'kontak'];
        for (const section of sections) {
            const el = document.getElementById(section);
            if (el && window.scrollY >= (el.offsetTop - 150)) {
                activeSection = section;
            }
        }
    ">

    <!-- PRELOADER -->
    <div id="preloader">
        <div class="flex flex-col items-center gap-4">
            <span class="loader"></span>
            <p class="text-white text-xs font-bold tracking-widest uppercase animate-pulse">Memuat Netila Berjaya...</p>
        </div>
    </div>


    <!-- NAVBAR (TEMA: DARK CORPORATE - HARMONIZED) -->
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
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center z-50">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-300 hover:text-white bg-white/10 rounded-lg transition-colors focus:outline-none backdrop-blur-sm">
                        <i class="ph-bold text-2xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
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
             class="fixed inset-0 bg-slate-900 z-40 md:hidden flex flex-col pt-24 px-6 overflow-y-auto">
             
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
    </nav>

    <!-- HERO SECTION (Updated to Match Dark Theme) -->
    <div id="home" class="relative bg-slate-900 pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-[90vh] flex items-center">
        <!-- Background -->
        <div class="absolute inset-0 z-0">
             <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-30 transform scale-105 animate-[pulse_10s_ease-in-out_infinite]"></div>
             <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/95 to-blue-950/80"></div>
             <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        </div>
        
        <!-- Animated Blobs -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-600 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-600 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 animate-blob animation-delay-2000"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12 lg:gap-20 z-10 w-full">
            <!-- Text Content -->
            <div class="lg:w-1/2 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Sistem Informasi Akademik Terpadu
                </div>
                <h1 class="text-4xl lg:text-6xl xl:text-7xl font-black text-white tracking-tight mb-6 leading-[1.1] drop-shadow-lg">
                    Membangun Generasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-teal-300">Cerdas & Berkarakter</span>
                </h1>
                <p class="text-slate-300 text-lg mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                    Platform digital terintegrasi SMPN 3 Lakbok untuk pemantauan akademik, absensi kehadiran, dan pengembangan karakter siswa secara real-time.
                </p>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-12">
                    <a href="<?php echo e(route('ppdb.create')); ?>" class="group relative px-8 py-4 rounded-full bg-blue-600 text-white font-bold text-sm shadow-[0_0_20px_rgba(37,99,235,0.3)] hover:shadow-[0_0_30px_rgba(37,99,235,0.5)] hover:-translate-y-1 transition-all overflow-hidden">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shine_1s_infinite]"></div>
                        <span class="relative flex items-center gap-2"><i class="ph-bold ph-student text-xl"></i> Daftar PPDB 2025</span>
                    </a>
                     <a href="<?php echo e(route('ppdb.check')); ?>" class="px-8 py-4 rounded-full glass-dark text-white font-bold text-sm hover:bg-white/10 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-magnifying-glass text-xl"></i> Cek Kelulusan
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0">
                    <div class="glass-dark p-4 rounded-2xl hover:bg-slate-800/80 transition group">
                        <div class="text-3xl font-black text-emerald-400 mb-1 group-hover:scale-110 transition-transform origin-left"><?php echo e($stats['hadir'] ?? 0); ?></div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Hadir Hari Ini</div>
                    </div>
                    <div class="glass-dark p-4 rounded-2xl hover:bg-slate-800/80 transition group">
                        <div class="text-3xl font-black text-amber-400 mb-1 group-hover:scale-110 transition-transform origin-left"><?php echo e($stats['terlambat'] ?? 0); ?></div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Terlambat</div>
                    </div>
                    <div class="glass-dark p-4 rounded-2xl hover:bg-slate-800/80 transition group">
                        <div class="text-3xl font-black text-rose-400 mb-1 group-hover:scale-110 transition-transform origin-left"><?php echo e($stats['tidak_hadir'] ?? 0); ?></div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Absen</div>
                    </div>
                </div>
            </div>

            <!-- Chart / Visual Content -->
            <div class="lg:w-1/2 w-full" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="relative glass-dark rounded-[2.5rem] p-6 lg:p-8 shadow-2xl transform hover:rotate-1 transition duration-500 border-t border-white/10">
                    <div class="flex items-center justify-between mb-4 border-b border-white/5 pb-4">
                        <h3 class="font-bold text-lg text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                                <i class="ph-fill ph-chart-bar text-xl"></i>
                            </div>
                            Statistik Kehadiran
                        </h3>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 px-3 py-1.5 rounded-full border border-emerald-500/20 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                        </span>
                    </div>
                    <!-- Height disesuaikan -->
                    <div class="h-[280px] lg:h-[320px] w-full relative">
                         <canvas id="publicWeeklyChart"></canvas>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl -z-10 blur-xl opacity-40"></div>
                    <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-2xl -z-10 blur-xl opacity-40"></div>
                </div>
            </div>
        </div>
        
        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none">
             <svg class="w-full h-16 lg:h-24 text-slate-50 fill-current" viewBox="0 0 1440 320" preserveAspectRatio="none">
                 <path d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,197.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
             </svg>
        </div>
    </div>    

    
    <!-- ========================================== -->
    <!-- NEW SECTION: JALUR PENDAFTARAN PPDB        -->
    <!-- (Disisipkan di sini agar terlihat jelas)   -->
    <!-- ========================================== -->
    <section class="py-20 bg-slate-50 relative overflow-hidden border-b border-slate-100">
        
        <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center mb-12">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider mb-3 border border-blue-200">
                    <i class="ph-bold ph-student mr-2"></i> Penerimaan Siswa Baru
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Pilih Jalur Pendaftaran</h2>
                <p class="text-slate-500 max-w-2xl mx-auto text-lg">
                    Kami menyediakan berbagai metode pendaftaran untuk memudahkan calon siswa dan sekolah asal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                
                <a href="<?php echo e(route('ppdb.create')); ?>" class="group relative bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 hover:shadow-blue-900/10 transition-all duration-300 h-full flex flex-col">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <i class="ph-duotone ph-student"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">Daftar Mandiri</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6 flex-1">
                        Untuk siswa atau orang tua yang ingin mengisi formulir pendaftaran secara langsung melalui website.
                    </p>
                    <div class="flex items-center text-blue-600 font-bold text-sm mt-auto">
                        Isi Formulir <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                
                <a href="<?php echo e(route('ppdb.collective')); ?>" class="group relative bg-gradient-to-br from-blue-900 to-slate-900 rounded-[2rem] p-8 shadow-xl shadow-blue-900/20 border border-blue-800 hover:-translate-y-2 hover:shadow-blue-900/40 transition-all duration-300 transform md:scale-105 md:-mt-4 ring-4 ring-blue-500/10 h-full flex flex-col">
                    <div class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-wider">
                        Khusus Guru
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-white/10 text-white flex items-center justify-center text-3xl mb-6 backdrop-blur-sm group-hover:scale-110 transition-all duration-300">
                        <i class="ph-duotone ph-microsoft-excel-logo"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">Kolektif Guru SD/MI</h3>
                    <p class="text-sm text-blue-100/80 font-medium leading-relaxed mb-6 flex-1">
                        Fitur khusus bagi Guru Sekolah Dasar untuk mendaftarkan siswanya secara massal menggunakan upload Excel.
                    </p>
                    <div class="flex items-center text-white font-bold text-sm mt-auto">
                        Upload Data <i class="ph-bold ph-upload-simple ml-2 group-hover:-translate-y-1 transition-transform"></i>
                    </div>
                </a>

                
                <a href="<?php echo e(route('ppdb.check')); ?>" class="group relative bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 hover:shadow-emerald-900/10 transition-all duration-300 h-full flex flex-col">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <i class="ph-duotone ph-magnifying-glass"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-emerald-600 transition-colors">Cek Status</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6 flex-1">
                        Pantau status verifikasi berkas pendaftaran dan lihat pengumuman hasil seleksi PPDB secara real-time.
                    </p>
                    <div class="flex items-center text-emerald-600 font-bold text-sm mt-auto">
                        Cek Sekarang <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- MONITORING 7 KEBIASAAN SECTION -->
    <div class="py-24 bg-white relative overflow-hidden border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                
                <!-- Statistik Card -->
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider mb-6 border border-blue-200">
                        <i class="ph-fill ph-shield-check mr-2"></i> Pendidikan Karakter
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-6">
                        Monitoring <br>
                        <span class="text-blue-600">7 Kebiasaan Baik</span>
                    </h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Rekapitulasi harian partisipasi siswa dalam membangun karakter unggul melalui pelaporan jurnal kebiasaan baik secara digital.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-blue-500/5 transition-all group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors"><i class="ph-bold ph-check-circle"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sudah Lapor</p>
                            </div>
                            <p class="text-3xl font-black text-slate-800"><?php echo e($habitStats['submitted'] ?? 0); ?> <span class="text-xs font-bold text-slate-400">Siswa</span></p>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-amber-500/5 transition-all group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-amber-100 rounded-lg text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors"><i class="ph-bold ph-clock-countdown"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum Lapor</p>
                            </div>
                            <p class="text-3xl font-black text-slate-800"><?php echo e($habitStats['missing'] ?? 0); ?> <span class="text-xs font-bold text-slate-400">Siswa</span></p>
                        </div>

                        <div class="sm:col-span-2 bg-blue-600 p-6 rounded-2xl shadow-lg shadow-blue-500/20 flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-2xl group-hover:rotate-12 transition-transform">
                                    <i class="ph-fill ph-chart-pie-slice"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-blue-100 uppercase tracking-widest">Tingkat Partisipasi</p>
                                    <p class="text-3xl font-black text-white"><?php echo e($habitStats['percentage'] ?? 0); ?>%</p>
                                </div>
                            </div>
                            <div class="hidden sm:block">
                                <i class="ph-bold ph-trend-up text-4xl text-white/20"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Tren -->
                <div class="w-full lg:w-7/12" data-aos="fade-left">
                    <div class="bg-slate-900 rounded-[2.5rem] shadow-2xl p-6 lg:p-10 border border-slate-800">
                        <div class="flex items-center justify-between mb-8 border-b border-slate-800 pb-6">
                            <h3 class="font-bold text-lg text-white flex items-center gap-3">
                                <div class="p-2 bg-blue-500/10 rounded-lg text-blue-400 border border-blue-500/20">
                                    <i class="ph-fill ph-activity text-xl"></i>
                                </div>
                                Tren Laporan Mingguan
                            </h3>
                        </div>
                        <div class="h-64 md:h-80 relative">
                            <canvas id="habitWeeklyChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> 

     <!-- QUICK ACCESS MENU -->
    <div class="bg-slate-50 py-20 relative z-20 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-10 right-10 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Akses Cepat Layanan</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Menu layanan digital terintegrasi untuk seluruh civitas akademika.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 md:gap-6">
                <?php
                    $menus = [
                        ['icon' => 'ph-student', 'color' => 'blue', 'title' => 'PPDB Online', 'desc' => 'Pendaftaran Siswa Baru', 'link' => route('ppdb.create')],
                        ['icon' => 'ph-graduation-cap', 'color' => 'purple', 'title' => 'Cek Kelulusan', 'desc' => 'Pengumuman Kelas IX', 'link' => route('graduation.index')],
                        ['icon' => 'ph-desktop', 'color' => 'indigo', 'title' => 'Portal Siswa', 'desc' => 'Dashboard Akademik', 'link' => route('portal.index')],
                        ['icon' => 'ph-megaphone', 'color' => 'rose', 'title' => 'Pengaduan', 'desc' => 'Layanan Suara Siswa', 'link' => route('student.complaints.index')],
                        ['icon' => 'ph-chalkboard-simple', 'color' => 'teal', 'title' => 'E-Learning', 'desc' => 'LMS & Tugas Online', 'link' => route('student.login')],
                        ['icon' => 'ph-monitor-play', 'color' => 'amber', 'title' => 'Ujian CBT', 'desc' => 'Portal Ujian Online', 'link' => route('student.login')],
                        ['icon' => 'ph-qr-code', 'color' => 'emerald', 'title' => 'Mesin Absensi', 'desc' => 'Mode Kiosk Sekolah', 'link' => route('kiosk.show')],
                        ['icon' => 'ph-books', 'color' => 'purple', 'title' => 'E-Library', 'desc' => 'Perpustakaan Digital', 'link' => route('library.kiosk.index')],
                        ['icon' => 'ph-chalkboard-teacher', 'color' => 'slate', 'title' => 'Login Staff', 'desc' => 'Admin & Guru', 'link' => route('login')],
                        ['icon' => 'ph-presentation-chart', 'color' => 'cyan', 'title' => 'Jurnal Mengajar', 'desc' => 'Pembelajaran Guru', 'link' => route('teaching.index')],                       

                    ];  
                ?>

                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($menu['link']); ?>" class="glass bg-white/60 p-6 rounded-2xl hover:bg-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group text-center md:text-left flex flex-col items-center md:items-start" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                        <div class="w-12 h-12 rounded-xl bg-<?php echo e($menu['color']); ?>-50 text-<?php echo e($menu['color']); ?>-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-<?php echo e($menu['color']); ?>-600 group-hover:text-white transition-all shadow-sm">
                            <i class="ph-duotone <?php echo e($menu['icon']); ?>"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 group-hover:text-<?php echo e($menu['color']); ?>-600 transition-colors"><?php echo e($menu['title']); ?></h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed hidden md:block"><?php echo e($menu['desc']); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>  

    <!-- AREA UNDUHAN -->
    <div class="bg-blue-50 py-12 border-y border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-600 rounded-2xl text-white shadow-lg shadow-blue-500/30 shrink-0">
                        <i class="ph-duotone ph-download-simple text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Area Unduhan & Akses</h3>
                        <p class="text-sm text-slate-500">Dokumen akademik dan fitur cepat untuk siswa.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <a href="<?php echo e(route('student.login')); ?>" class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold text-sm hover:border-blue-400 hover:text-blue-600 transition-all shadow-sm">
                        <i class="ph-fill ph-calendar-blank text-blue-500 text-lg"></i> Cek Jadwal Pelajaran
                    </a>
                    <a href="#" class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold text-sm hover:border-blue-400 hover:text-blue-600 transition-all shadow-sm">
                        <i class="ph-fill ph-file-pdf text-red-500 text-lg"></i> Kalender Akademik
                    </a>
                    <a href="#" class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold text-sm hover:border-blue-400 hover:text-blue-600 transition-all shadow-sm">
                        <i class="ph-fill ph-file-text text-blue-500 text-lg"></i> Tata Tertib
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KEPALA SEKOLAH SECTION -->
    <div class="bg-white py-20 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-600 rounded-3xl p-8 md:p-12 relative overflow-hidden flex flex-col md:flex-row items-center gap-10 shadow-2xl">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl"></div>
                
                 <div class="relative shrink-0" data-aos="zoom-in">
                    <div class="w-48 h-48 md:w-64 md:h-64 rounded-full border-4 border-slate-700 p-2 relative z-10 bg-slate-800">
                        <img src="<?php echo e(asset('images/kasek.png')); ?>" alt="Kepala Sekolah" class="w-full h-full object-cover rounded-full filter grayscale hover:grayscale-0 transition-all duration-500">
                    </div>
                    <div class="absolute -bottom-4 -right-4 bg-yellow-500 text-slate-900 w-12 h-12 rounded-full flex items-center justify-center shadow-lg z-20">
                        <i class="ph-fill ph-quotes text-2xl"></i>
                    </div>
                </div>
                
                 <div class="text-center md:text-left text-white relative z-10" data-aos="fade-left">
                    <span class="text-blue-400 font-bold uppercase tracking-widest text-xs mb-2 block">Sambutan Kepala Sekolah</span>
                    <h3 class="text-3xl md:text-4xl font-black mb-6 leading-tight">Membangun Generasi Emas</h3>
                    <p class="text-slate-300 text-lg italic leading-relaxed mb-8 font-light">
                        "Pendidikan bukan sekadar transfer ilmu, melainkan proses pembentukan karakter dan penggalian potensi diri. Mari bersama membangun generasi yang berakhlak mulia dan kompeten di era global."
                    </p>
                    <div>
                        <p class="font-bold text-xl text-white">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
                        <p class="text-blue-400 font-medium">Kepala SMPN 3 Lakbok</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- PROFIL SEKOLAH -->
    <div id="profil" class="py-24 bg-white relative overflow-hidden border-y border-slate-100">
        <div class="absolute right-0 top-0 opacity-5 pointer-events-none">
            <svg width="400" height="400" fill="none" viewBox="0 0 200 200">
                <defs><pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" class="text-slate-900" fill="currentColor"></circle></pattern></defs>
                <rect width="200" height="200" fill="url(#dots)"></rect>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8" data-aos="fade-right">
                    <div class="space-y-2">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest border border-blue-100">Tentang Kami</span>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">Mewujudkan Generasi <br><span class="text-blue-600">Cerdas & Berkarakter</span></h2>
                    </div>
                    <p class="text-lg text-slate-600 leading-relaxed text-justify lg:text-left">
                        SMP Negeri 3 Lakbok berkomitmen untuk memberikan layanan pendidikan terbaik yang mengintegrasikan kecerdasan akademik dengan nilai-nilai karakter luhur. Kami hadir untuk mencetak pemimpin masa depan yang kompetitif dan berakhlak mulia.
                    </p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-lg transition-all duration-300">
                            <p class="text-3xl font-black text-slate-800"><?php echo e($schoolStats['siswa'] ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Siswa</p>
                        </div>
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-lg transition-all duration-300">
                            <p class="text-3xl font-black text-slate-800"><?php echo e($schoolStats['guru'] ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Guru</p>
                        </div>
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-lg transition-all duration-300">
                            <p class="text-3xl font-black text-slate-800"><?php echo e($schoolStats['rombel'] ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Rombel</p>
                        </div>
                        <div class="p-5 bg-teal-50 rounded-2xl border border-teal-100 text-center hover:bg-white hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                            <p class="text-3xl font-black text-teal-600"><?php echo e($schoolStats['materi'] ?? 0); ?></p>
                            <p class="text-[10px] font-bold text-teal-400 uppercase mt-1 tracking-wider">Materi Digital</p>
                        </div>
                        <div class="p-5 bg-indigo-50 rounded-2xl border border-indigo-100 text-center hover:bg-white hover:shadow-lg hover:border-indigo-200 transition-all duration-300 sm:col-span-2">
                            <p class="text-3xl font-black text-indigo-600"><?php echo e($schoolStats['tugas'] ?? 0); ?></p>
                            <p class="text-[10px] font-bold text-indigo-400 uppercase mt-1 tracking-wider">Tugas & Kuis Online</p>
                        </div>
                    </div>
                </div>

                <div class="relative group" data-aos="fade-left"
                    x-data="{ 
                        currentSlide: 0, 
                        slides: [
                            '<?php echo e(asset('images/netila.jpg')); ?>', 
                            '<?php echo e(asset('images/hadir.jpg')); ?>', 
                            '<?php echo e(asset('images/digital1.jpg')); ?>', 
                            '<?php echo e(asset('images/digital2.jpg')); ?>', 
                            '<?php echo e(asset('images/kka.png')); ?>', 
                            '<?php echo e(asset('images/religi.jpg')); ?>'
                        ],
                        init() { setInterval(() => { this.currentSlide = (this.currentSlide + 1) % this.slides.length; }, 4000); }
                    }" x-init="init()">
                    
                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-[2.5rem] opacity-20 blur-lg group-hover:opacity-40 transition duration-500"></div>
                    <div class="bg-slate-200 rounded-[2rem] overflow-hidden shadow-2xl relative aspect-video z-10">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img :src="slide" x-show="currentSlide === index" x-transition.opacity.duration.1000ms class="absolute inset-0 w-full h-full object-cover" alt="Galeri">
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/90 to-transparent flex items-center justify-center z-20 pointer-events-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- VIDEO PROFIL -->
    <div class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('<?php echo e(asset('images/netila.jpg')); ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
            <span class="inline-block py-1 px-3 rounded-full bg-red-600/20 text-red-400 border border-red-500/30 text-xs font-bold uppercase tracking-wider mb-6 animate-pulse">
                <i class="ph-fill ph-youtube-logo mr-1"></i> Tonton Video
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-8">Kenali Kami Lebih Dekat</h2>
            
            <div class="relative aspect-video rounded-3xl overflow-hidden shadow-2xl border-4 border-slate-700 group cursor-pointer">
                <?php
                    $rawVideoUrl = 'https://www.youtube.com/watch?v=cx_Q4pyTNVQ'; 
                    $embedUrl = $rawVideoUrl;
                    if(str_contains($rawVideoUrl, 'watch?v=')) {
                        $embedUrl = str_replace('watch?v=', 'embed/', $rawVideoUrl);
                        $embedUrl = explode('&', $embedUrl)[0];
                    } elseif(str_contains($rawVideoUrl, 'youtu.be/')) {
                        $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $rawVideoUrl);
                    }
                ?>
                 <iframe class="w-full h-full" src="<?php echo e($embedUrl); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <!-- GURU SECTION -->
    <div id="guru" class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest border border-blue-100">SDM Unggul</span>
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl mt-4">Tenaga Pendidik</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Dibimbing oleh guru-guru profesional yang berdedikasi tinggi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                        <div class="aspect-[3/4] w-full relative overflow-hidden bg-slate-100">
                            <?php if($teacher->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $teacher->photo_path)); ?>" loading="lazy" alt="<?php echo e($teacher->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full hidden flex-col items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 text-slate-500">
                                    <span class="text-6xl font-black opacity-30 select-none uppercase"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600">
                                    <span class="text-7xl font-black opacity-20 select-none uppercase group-hover:scale-110 transition-transform"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-5 text-center relative bg-white flex-1 flex flex-col justify-end">
                            <div class="absolute -top-4 left-0 right-0 flex justify-center">
                                <span class="bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider py-1 px-3 rounded-full shadow-lg border-2 border-white"><?php echo e($teacher->position ?? $teacher->role); ?></span>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1"><?php echo e($teacher->name); ?></h3>
                            <?php if(!empty($teacher->nip)): ?>
                                <p class="text-xs text-slate-500 font-medium mt-1">NIP. <?php echo e($teacher->nip); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-12"><p class="text-slate-500">Belum ada data tenaga pendidik.</p></div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="<?php echo e(route('teachers.index')); ?>" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm hover:shadow-md">Lihat Seluruh Staff <i class="ph-bold ph-arrow-right ml-2"></i></a>
            </div>
        </div>
    </div>

    <!-- KEGIATAN SEKOLAH -->
    <div id="kegiatan" class="py-24 bg-white relative overflow-hidden border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
                <div class="max-w-2xl">
                    <span class="text-indigo-600 font-bold tracking-wider text-sm uppercase mb-2 block">Galeri Sekolah</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Aktifitas & Kegiatan Siswa</h2>
                </div>
                <a href="<?php echo e(route('public.activities')); ?>" class="hidden md:inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Lihat Semua Galeri <i class="ph-bold ph-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                        <div class="relative h-60 overflow-hidden">
                            <?php if($activity->image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $activity->image_path)); ?>" 
                                     loading="lazy"
                                     alt="<?php echo e($activity->title); ?>" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400" style="display: none;">
                                    <i class="ph-duotone ph-image-broken text-4xl"></i>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                    <i class="ph-duotone ph-image text-4xl"></i>
                                </div>
                            <?php endif; ?>

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-60"></div>
                            
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    <?php echo e($activity->created_at->format('d M Y')); ?>

                                </span>
                            </div>

                            <?php if($activity->video_url): ?>
                                <div class="absolute top-4 right-4 z-20">
                                    <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-lg flex items-center gap-1 animate-pulse">
                                        <i class="ph-fill ph-play-circle"></i> VIDEO
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                <?php echo e($activity->title); ?>

                            </h4>
                            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-4 flex-1">
                                <?php echo e($activity->description); ?>

                            </p>

                            <?php if($activity->video_url): ?>
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    <a href="<?php echo e($activity->video_url); ?>" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-red-600 hover:text-red-700 transition-colors w-full group/video">
                                        <i class="ph-fill ph-youtube-logo text-xl group-hover/video:scale-110 transition-transform"></i>
                                        <span>Tonton Dokumentasi</span>
                                        <i class="ph-bold ph-arrow-square-out ml-auto opacity-0 group-hover/video:opacity-100 transition-opacity"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-12 text-center text-slate-400">Belum ada aktivitas.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PRESTASI SECTION -->
    <div id="prestasi" class="py-24 bg-gradient-to-b from-yellow-50/50 to-white relative overflow-hidden border-t border-slate-100" x-data="{ activeFilter: 'Terbaru' }">
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold uppercase tracking-wider mb-4 border border-yellow-200"><i class="ph-fill ph-trophy"></i> Hall of Fame</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">Prestasi Membanggakan</h2>
                    <p class="mt-4 text-lg text-slate-600">Jejak juara siswa dan guru yang mengharumkan nama sekolah.</p>
                </div>
                <div class="flex gap-2">
                    <button @click="activeFilter = 'Terbaru'" :class="activeFilter === 'Terbaru' ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 rounded-full text-sm font-bold transition">Terbaru</button>
                    <button @click="activeFilter = 'Nasional'" :class="activeFilter === 'Nasional' ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 rounded-full text-sm font-bold transition">Nasional</button>
                    <button @click="activeFilter = 'Provinsi'" :class="activeFilter === 'Provinsi' ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 rounded-full text-sm font-bold transition">Provinsi</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $achievements ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prestasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group bg-white rounded-2xl border border-yellow-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-yellow-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full flex flex-col" 
                         x-show="activeFilter === 'Terbaru' || activeFilter.toLowerCase() === '<?php echo e(strtolower($prestasi->level ?? '')); ?>'"
                         x-transition.duration.500ms
                         data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                        <div class="h-48 w-full bg-slate-100 relative overflow-hidden group">
                            <?php if(!empty($prestasi->photo_path)): ?>
                                <img src="<?php echo e(asset('storage/' . $prestasi->photo_path)); ?>" loading="lazy" alt="<?php echo e($prestasi->title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-500 text-white" style="display: none;"><i class="ph-bold ph-trophy text-4xl"></i></div>
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-500 text-white"><i class="ph-bold ph-trophy text-4xl"></i></div>
                            <?php endif; ?>
                            <div class="absolute top-3 right-3">
                                 <span class="px-2.5 py-1 rounded-full bg-white/90 backdrop-blur border border-white/20 text-[10px] font-bold uppercase text-yellow-700 tracking-wide shadow-sm"><?php echo e($prestasi->level ?? 'Sekolah'); ?></span>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col relative z-10">
                             <div class="text-xs text-slate-400 font-medium mb-2 flex items-center gap-1"><i class="ph-fill ph-calendar-blank"></i> <?php echo e(isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->format('d M Y') : '-'); ?></div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2 leading-tight group-hover:text-yellow-600 transition-colors line-clamp-2"><?php echo e($prestasi->title ?? 'Juara Lomba'); ?></h4>
                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-sm"><i class="ph-bold ph-user"></i></div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700 line-clamp-1"><?php echo e($prestasi->achiever_name ?? 'Siswa'); ?></p>
                                    <p class="text-xs text-slate-400 uppercase font-bold"><?php echo e($prestasi->type ?? 'Siswa'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-4 text-slate-400 text-sm italic">Belum ada data prestasi.</div>
                <?php endif; ?>
            </div>
            <div class="mt-12 text-center" data-aos="fade-up">
                 <a href="<?php echo e(route('public.achievements')); ?>" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-full hover:bg-yellow-100 hover:text-yellow-800 transition-all shadow-sm">Lihat Arsip Prestasi <i class="ph-bold ph-arrow-right ml-2"></i></a>
            </div>
        </div>
    </div>

    <!-- EKSTRAKURIKULER -->
    <div id="ekskul" class="py-24 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-overlay filter blur-[128px] opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-600 rounded-full mix-blend-overlay filter blur-[128px] opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-purple-500/10 text-purple-300 rounded-full text-xs font-bold uppercase tracking-widest border border-purple-500/20">
                    Bakat & Minat
                </span>
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl mt-4">Ekstrakurikuler</h2>
                <p class="mt-4 text-lg text-slate-400 max-w-2xl mx-auto">
                    Wadah pengembangan potensi siswa di luar jam pelajaran akademik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 p-6 rounded-3xl hover:border-purple-500/50 transition-all duration-300 group hover:-translate-y-1 flex flex-col h-full" data-aos="fade-up">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-slate-700 rounded-2xl flex items-center justify-center text-3xl text-purple-400 shadow-lg group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 overflow-hidden shrink-0">
                                <?php if(filter_var($ekskul->icon, FILTER_VALIDATE_URL) || preg_match('/\.(jpg|jpeg|png|gif|svg|webp)$/i', $ekskul->icon)): ?>
                                    <img src="<?php echo e(asset($ekskul->icon)); ?>" loading="lazy" alt="<?php echo e($ekskul->name); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="<?php echo e($ekskul->icon ?? 'ph-fill ph-star'); ?>"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white leading-tight line-clamp-2"><?php echo e($ekskul->name); ?></h3>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <?php if($lastActivity = $ekskul->attendances->first()): ?>
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                        <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wide">Aktif</span>
                                    <?php else: ?>
                                        <span class="w-2 h-2 rounded-full bg-slate-600"></span>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Vakum</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3 mt-auto">
                            <div class="bg-slate-900/50 rounded-xl p-3 flex items-center gap-3 border border-slate-700/30">
                                <i class="ph-duotone ph-clock text-purple-400 text-lg"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Jadwal</p>
                                    <p class="text-xs text-slate-300 font-mono truncate"><?php echo e($ekskul->schedule ?? '-'); ?></p>
                                </div>
                            </div>
                            <div class="bg-slate-900/50 rounded-xl p-3 flex items-center gap-3 border border-slate-700/30">
                                <i class="ph-duotone ph-user-circle text-blue-400 text-lg"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Pembina</p>
                                    <p class="text-xs text-slate-300 truncate"><?php echo e($ekskul->coach_name ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-12 text-center text-slate-400">Belum ada data ekstrakurikuler.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

        
    <div id="alumni" class="py-24 bg-gradient-to-br from-indigo-900 to-slate-900 relative overflow-hidden border-t border-slate-800">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-xs font-bold uppercase tracking-widest border border-indigo-500/30">
                    Tracer Study
                </span>
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl mt-4">Jejak Langkah Alumni</h2>
                <p class="mt-4 text-lg text-indigo-200 max-w-2xl mx-auto">
                    Melihat sebaran dan kisah sukses para alumni SMPN 3 Lakbok yang telah melanjutkan ke jenjang lebih tinggi.
                </p>
            </div>

            <!-- STATISTIK ALUMNI -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-20">
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="0">
                    <p class="text-4xl font-black text-white mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['total'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Total Alumni</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl font-black text-blue-400 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['sma'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Lanjut SMA</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl font-black text-orange-400 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['smk'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Lanjut SMK</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <p class="text-4xl font-black text-emerald-400 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['pesantren'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Pesantren</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="400">
                    <p class="text-4xl font-black text-slate-300 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['bekerja'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Bekerja</p>
                </div>
            </div>

            <!-- SLIDER TESTIMONI ALUMNI -->
            <?php if(isset($alumniTestimonials) && count($alumniTestimonials) > 0): ?>
                <div class="flex overflow-x-auto gap-6 pb-8 custom-scrollbar hide-scroll snap-x snap-mandatory">
                    <?php $__currentLoopData = $alumniTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="min-w-[300px] md:min-w-[400px] bg-white rounded-3xl p-8 shadow-xl relative snap-center group hover:-translate-y-2 transition-transform duration-300">
                            <i class="ph-fill ph-quotes text-5xl text-indigo-100 absolute top-6 right-6 group-hover:text-indigo-200 transition-colors"></i>
                            
                            <div class="relative z-10 h-full flex flex-col">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 border-2 border-indigo-100 overflow-hidden shrink-0">
                                        <?php if($testi->student && $testi->student->photo_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $testi->student->photo_path)); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-indigo-500 text-white font-bold text-xl"><?php echo e(substr($testi->student->name ?? 'A', 0, 1)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-base line-clamp-1"><?php echo e($testi->student->name ?? 'Alumni'); ?></h4>
                                        <p class="text-xs text-indigo-600 font-bold uppercase mt-0.5">
                                            <?php echo e($testi->activity_status); ?> 
                                            <?php if($testi->campus_name || $testi->company_name): ?>
                                                @ <?php echo e(Str::limit($testi->campus_name ?? $testi->company_name, 20)); ?>

                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-slate-600 text-sm italic leading-relaxed line-clamp-4">
                                        "<?php echo e($testi->testimony); ?>"
                                    </p>
                                </div>

                                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-1 text-yellow-400 text-sm">
                                    <?php for($i=0; $i < ($testi->rating ?? 5); $i++): ?> <i class="ph-fill ph-star"></i> <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="flex justify-center gap-2 mt-4 md:hidden">
                    <i class="ph-bold ph-arrow-left text-indigo-400 animate-pulse"></i>
                    <span class="text-xs text-indigo-300 font-medium">Geser untuk melihat testimoni</span>
                    <i class="ph-bold ph-arrow-right text-indigo-400 animate-pulse"></i>
                </div>

                 
                <div class="mt-12 text-center" data-aos="fade-up">
                    <a href="<?php echo e(route('public.testimonials')); ?>" class="inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-indigo-100 bg-indigo-800/50 border border-indigo-500/50 rounded-full hover:bg-indigo-700 hover:text-white transition-all shadow-lg hover:shadow-indigo-500/30 group">
                        Lihat Semua Testimoni 
                        <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-12 border border-dashed border-indigo-800/30 rounded-3xl bg-indigo-900/20">
                    <p class="text-indigo-300 italic">Belum ada testimoni alumni yang ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- KATA MEREKA / BUKU TAMU -->
    <div class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-slate-900">Kata Mereka</h2>
                <p class="text-slate-500 mt-2 mb-6">Pesan dan kesan dari pengunjung sekolah kami.</p>
                
                <!-- TOMBOL LIHAT SEMUA TAMU (BARU) -->
                <button @click="guestListModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-bold hover:border-blue-400 hover:text-blue-600 transition shadow-sm">
                    <i class="ph-bold ph-list-dashes"></i> Lihat Semua Tamu
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $guestbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold shrink-0 border border-blue-200">
                                <?php echo e(substr($guest->name, 0, 1)); ?>

                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm line-clamp-1"><?php echo e($guest->name); ?></h4>
                                <p class="text-xs text-slate-500 line-clamp-1"><?php echo e($guest->institution); ?></p>
                            </div>
                        </div>
                        <div class="relative flex-1 bg-slate-50 p-4 rounded-xl">
                            <i class="ph-fill ph-quotes text-blue-200 text-2xl absolute -top-2 -left-1"></i>
                            <p class="text-slate-600 text-sm italic leading-relaxed relative z-10 pl-2">
                                "<?php echo e(Str::limit($guest->message, 150)); ?>"
                            </p>
                        </div>
                        <div class="mt-3 text-[10px] text-slate-400 text-right font-medium">
                            <?php echo e($guest->created_at->diffForHumans()); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 text-slate-400 shadow-sm">
                            <i class="ph-duotone ph-chats-teardrop text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Belum Ada Pesan</h3>
                        <p class="text-slate-500 text-sm mt-1">Jadilah pengunjung pertama yang memberikan kesan!</p>
                        <button @click="guestBookModalOpen = true" class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            Isi Buku Tamu
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- LIBRARY SECTION -->
    <div class="py-24 bg-emerald-50/50 border-y border-emerald-100/50 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-6 border border-emerald-200">
                        <i class="ph-fill ph-books mr-2"></i> Pusat Literasi
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-6">
                        Budayakan Membaca, <br>
                        <span class="text-emerald-600">Jelajahi Dunia</span>
                    </h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Perpustakaan digital kami memudahkan pemantauan aktivitas literasi siswa. Data kunjungan dan peminjaman buku tercatat secara real-time.
                    </p>
                    
                    <div class="flex gap-4 sm:gap-6">
                        <div class="flex-1 bg-white p-6 rounded-2xl shadow-lg shadow-emerald-100 border border-emerald-50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600"><i class="ph-bold ph-users"></i></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pengunjung</p>
                            </div>
                            <p class="text-4xl font-black text-slate-800"><?php echo e($libraryStats['visitors_today'] ?? 0); ?></p>
                            <p class="text-xs text-emerald-600 font-medium mt-1">Hari ini</p>
                        </div>
                        <div class="flex-1 bg-white p-6 rounded-2xl shadow-lg shadow-emerald-100 border border-emerald-50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 rounded-lg text-blue-600"><i class="ph-bold ph-book-bookmark"></i></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Dipinjam</p>
                            </div>
                            <p class="text-4xl font-black text-slate-800"><?php echo e($libraryStats['books_borrowed'] ?? 0); ?></p>
                            <p class="text-xs text-blue-600 font-medium mt-1">Buku Aktif</p>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-7/12" data-aos="fade-left">
                    <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-lg text-slate-800">Tren Kunjungan Perpustakaan</h3>
                        </div>
                        <div class="h-64 md:h-80">
                            <canvas id="publicLibraryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    
    <section class="py-24 bg-slate-900 relative overflow-hidden">
        
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold uppercase tracking-widest mb-4">
                        <i class="ph-fill ph-books"></i> E-Library
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white leading-tight">
                        Jelajahi Dunia Pengetahuan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Tanpa Batas Ruang</span>
                    </h2>
                    <p class="text-slate-400 mt-4 text-lg leading-relaxed">
                        Akses koleksi buku digital terbaru SMPN 3 Lakbok kapan saja dan di mana saja.
                    </p>
                </div>
                
                
                <a href="<?php echo e(route('library.catalogue')); ?>" class="group flex items-center gap-2 px-6 py-3 bg-white text-slate-900 font-bold rounded-full hover:bg-blue-50 transition-all shadow-xl shadow-blue-900/20">
                    <span>Lihat Katalog Lengkap</span>
                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $latestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group relative" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                        <div class="book-glass rounded-2xl p-3 h-full flex flex-col hover:-translate-y-2 transition-transform duration-300 shadow-2xl">
                            
                            
                            <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-4 bg-slate-800 shadow-inner">
                                <?php if($book->cover_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" 
                                         alt="<?php echo e($book->title); ?>" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <?php else: ?>
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-600">
                                        <i class="ph-duotone ph-book-open text-4xl mb-2"></i>
                                        <span class="text-[10px] font-bold uppercase">No Cover</span>
                                    </div>
                                <?php endif; ?>
                                
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                    
                                    <a href="<?php echo e(route('library.books.read', $book->id)); ?>" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg flex items-center justify-center gap-2 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                        <i class="ph-bold ph-read-cv-logo"></i> Baca Sekarang
                                    </a>
                                </div>
                            </div>

                            
                            <div class="mt-auto">
                                <h3 class="text-white font-bold text-sm line-clamp-2 leading-snug mb-1 group-hover:text-blue-400 transition-colors" title="<?php echo e($book->title); ?>">
                                    <?php echo e($book->title); ?>

                                </h3>
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="ph-fill ph-pen-nib"></i> <?php echo e($book->author ?? 'Anonim'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-12 text-center book-glass rounded-3xl">
                        <i class="ph-duotone ph-books text-5xl text-slate-600 mb-4"></i>
                        <p class="text-slate-400 font-bold">Belum ada koleksi E-Book terbaru.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <!-- ANNOUNCEMENTS (Bottom) & FOOTER SECTION -->
    <div class="bg-slate-900 text-white pt-24 pb-12 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
        <div class="absolute -right-20 top-20 w-96 h-96 bg-blue-600 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- PENGUMUMAN -->
            <div class="mb-24">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">Papan Pengumuman</h2>
                        <p class="text-slate-400 text-sm">Informasi terbaru seputar kegiatan sekolah.</p>
                    </div>
                </div>
                
                <div class="grid gap-6 md:grid-cols-3">
                    <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <article class="bg-slate-800/50 backdrop-blur-md rounded-2xl p-6 border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300 hover:bg-slate-800 hover:-translate-y-1 group h-full flex flex-col cursor-pointer" @click="openAnnouncementByIndex(<?php echo e($index); ?>)" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-2 py-1 rounded bg-blue-500/10 text-blue-400 text-[10px] font-bold uppercase tracking-wide border border-blue-500/20">Info</span>
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <i class="ph-fill ph-calendar-blank"></i> <?php echo e($item->created_at->format('d M')); ?>

                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-3 line-clamp-2 group-hover:text-blue-400 transition-colors"><?php echo e($item->title); ?></h3>
                            <p class="text-slate-400 text-sm line-clamp-3 mb-4 flex-1 leading-relaxed"><?php echo e(Str::limit(strip_tags($item->content), 100)); ?></p>
                            <div class="flex items-center text-sm text-blue-400 font-semibold mt-auto gap-1 group-hover:gap-2 transition-all">
                                Baca Selengkapnya <i class="ph-bold ph-arrow-right text-xs"></i>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-3 text-center py-12 border border-dashed border-slate-700 rounded-xl bg-slate-800/30">
                            <p class="text-slate-500">Tidak ada pengumuman terbaru saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- AGENDA KEGIATAN -->
            <div class="bg-slate-800/50 rounded-3xl p-8 mb-16 border border-slate-700/50 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-2 bg-blue-500/10 rounded-lg text-blue-400">
                        <i class="ph-fill ph-calendar-check text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Agenda Mendatang</h3>
                        <p class="text-slate-400 text-sm mt-0.5">Jadwal kegiatan akademik dan non-akademik.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $colors = ['blue', 'green', 'purple', 'orange', 'pink'];
                            $color = $colors[$loop->index % count($colors)];
                        ?>
                        
                        <div class="bg-slate-700/50 p-4 rounded-xl border-l-4 border-<?php echo e($color); ?>-500 flex items-start gap-4 hover:bg-slate-700 transition cursor-default group h-full">
                            <div class="text-center bg-slate-800 p-2 rounded-lg min-w-[60px] shadow-lg group-hover:bg-slate-900 transition-colors shrink-0">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e($agenda->event_date->format('M')); ?></span>
                                <span class="block text-xl font-bold text-white"><?php echo e($agenda->event_date->format('d')); ?></span>
                            </div>
                            <div class="flex-1 min-w-0 py-0.5">
                                <h4 class="text-white font-bold text-sm line-clamp-2 leading-snug mb-1" title="<?php echo e($agenda->title); ?>"><?php echo e($agenda->title); ?></h4>
                                <p class="text-slate-400 text-xs flex items-center gap-1.5">
                                    <i class="ph-fill ph-map-pin shrink-0 text-<?php echo e($color); ?>-400"></i> 
                                    <span class="truncate"><?php echo e($agenda->location ?? 'Sekolah'); ?></span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-4 text-center py-6">
                            <p class="text-slate-500 italic">Belum ada agenda kegiatan mendatang.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FOOTER WIDGETS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 border-t border-slate-800 pt-16">
                <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-white flex items-center justify-center p-1">
                             <img src="<?php echo e(asset('images/logo.png')); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Logo" class="w-full h-full object-contain">
                             <i class="ph-bold ph-graduation-cap text-xl text-blue-900" style="display: none;"></i>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">SMPN 3 LAKBOK</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-8">
                        Visi sekolah adalah Terciptanya generasi pemelajar yang beriman dan bertakwa, tangguh, literat, berkecakapan global, serta berkesadaran budaya dan lingkungan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/NetiLakbok" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition-all duration-300"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-pink-600 transition-all duration-300"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                        <a href="https://www.youtube.com/@netilachannel" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-600 transition-all duration-300"><i class="ph-fill ph-youtube-logo text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Menu Utama</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#profil" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Profil Sekolah</a></li>
                        <li><a href="#guru" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Tenaga Pendidik</a></li>
                        <li><a href="#kegiatan" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Galeri Kegiatan</a></li>
                        <li><a href="<?php echo e(route('login')); ?>" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Login Staff</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-map-pin mt-1 text-blue-500 shrink-0"></i>
                            <span class="leading-relaxed">Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Kab. Ciamis, Jawa Barat 46385</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-phone text-blue-500 shrink-0"></i>
                            <span>+62 85135961994</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-envelope text-blue-500 shrink-0"></i>
                            <span>admin@smpn3lakbok.sch.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- COPYRIGHT -->
            <div class="text-center pt-8 border-t border-slate-800">
                <p class="text-slate-500 text-sm">
                    &copy; <?php echo e(date('Y')); ?> SMP Negeri 3 Lakbok. Ri.. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- BACK TO TOP BUTTON -->
    <button 
        x-show="showBackToTop" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 right-6 z-40 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 focus:outline-none"
    >
        <i class="ph-bold ph-arrow-up text-xl"></i>
    </button>

    <!-- MODAL POPUP (ANNOUNCEMENT) -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="closeAnnouncement()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-6 py-6 sm:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wide border border-blue-100" x-text="activeAnnouncement?.category || 'Pengumuman'">
                            
                        </span>
                        <button @click="closeAnnouncement()" class="text-slate-400 hover:text-red-500 transition bg-slate-50 hover:bg-red-50 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight mb-4" x-text="activeAnnouncement?.title"></h3>
                    <div class="flex items-center gap-2 text-sm text-slate-400 mb-6 pb-6 border-b border-slate-100">
                        <i class="ph-fill ph-calendar-blank"></i>
                        <span x-text="new Date(activeAnnouncement?.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                    </div>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        <div x-html="activeAnnouncement?.content.replace(/\n/g, '<br>')"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                    <button class="inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors" @click="closeAnnouncement()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- GUEST BOOK FORM MODAL -->
    <div x-show="guestBookModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="guestBookModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                <form action="<?php echo e(route('guestbook.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="bg-white px-6 py-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-slate-900">Buku Tamu Digital</h3>
                            <button type="button" @click="guestBookModalOpen = false" class="text-slate-400 hover:text-red-500 transition bg-slate-50 hover:bg-red-50 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3" placeholder="Masukkan nama lengkap Anda">
                            </div>
                            
                            <div>
                                <label for="institution" class="block text-sm font-semibold text-slate-700 mb-1">Asal Instansi / Umum</label>
                                <input type="text" name="institution" id="institution" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Dinas Pendidikan / Wali Murid">
                            </div>

                            <div>
                                <label for="purpose" class="block text-sm font-semibold text-slate-700 mb-1">Tujuan Kunjungan</label>
                                <select name="purpose" id="purpose" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3">
                                    <option value="Dinas">Kunjungan Dinas</option>
                                    <option value="Rapat">Rapat / Pertemuan</option>
                                    <option value="Wali Murid">Urusan Wali Murid</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-700 mb-1">Pesan & Saran</label>
                                <textarea name="message" id="message" rows="3" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3" placeholder="Tuliskan pesan atau saran Anda..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" class="inline-flex justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors" @click="guestBookModalOpen = false">Batal</button>
                        <button type="submit" class="inline-flex justify-center rounded-xl bg-pink-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-pink-700 transition-colors shadow-pink-500/30">Kirim Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ALL GUESTS LIST MODAL (NEW) -->
    <div x-show="guestListModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="guestListModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-4xl border border-slate-200 flex flex-col max-h-[90vh]">
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Daftar Kunjungan Tamu</h3>
                        <p class="text-sm text-slate-500">Riwayat pengisian buku tamu sekolah.</p>
                    </div>
                    <button type="button" @click="guestListModalOpen = false" class="text-slate-400 hover:text-red-500 transition bg-slate-50 hover:bg-red-50 p-2 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                </div>
                
                <div class="p-0 overflow-y-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Waktu</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Nama Pengunjung</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Instansi</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Pesan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $allGuestbooks ?? $guestbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                    <?php echo e($item->created_at->format('d M Y, H:i')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                            <?php echo e(substr($item->name, 0, 1)); ?>

                                        </div>
                                        <span class="text-sm font-bold text-slate-700"><?php echo e($item->name); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <?php echo e($item->institution); ?>

                                    <span class="block text-[10px] text-slate-400 mt-0.5"><?php echo e($item->purpose ?? '-'); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 italic">
                                    "<?php echo e($item->message); ?>"
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada data buku tamu.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end shrink-0">
                    <button type="button" class="px-5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-50 transition shadow-sm" @click="guestListModalOpen = false">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        AOS.init({ once: true, offset: 50, duration: 800 });
        window.announcementsData = <?php echo json_encode($announcements, 15, 512) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // Chart Default Styling
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#94a3b8';
            
            // --- 1. CHART ATTENDANCE ---
            const ctx = document.getElementById('publicWeeklyChart');
            if(ctx) {
                const chartData = <?php echo json_encode($barChartData, 15, 512) ?>; 
                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: chartData.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        borderRadius: 6,
                        barThickness: 24,
                        plugins: { legend: { position: 'bottom' } },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#cbd5e1' } },
                            y: { grid: { color: '#334155' }, border: { display: false }, ticks: { color: '#cbd5e1' } }
                        }
                    }
                });
            }

            // --- 2. CHART LIBRARY ---
            const libCtx = document.getElementById('publicLibraryChart');
            if (libCtx) {
                const libData = <?php echo json_encode($libraryChartData, 15, 512) ?>;
                new Chart(libCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: libData.labels,
                        datasets: [{
                            label: 'Kunjungan',
                            data: libData.data,
                            borderColor: '#10b981',
                            backgroundColor: (context) => {
                                const ctx = context.chart.ctx;
                                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
                                gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
                                return gradient;
                            },
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true, 
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#10b981',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 10,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        },
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                border: { display: false }, 
                                grid: { color: '#f1f5f9' },
                                ticks: { stepSize: 1 }
                            }, 
                            x: { 
                                grid: { display: false }
                            } 
                        }
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
        const habitCtx = document.getElementById('habitWeeklyChart');
        if (habitCtx) {
            new Chart(habitCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($habitLabels, 15, 512) ?>,
                    datasets: [{
                        label: 'Siswa Melapor',
                        data: <?php echo json_encode($habitData, 15, 512) ?>,
                        borderColor: '#3b82f6',
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                            return gradient;
                        },
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#1e293b', drawBorder: false },
                            ticks: { color: '#64748b', font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { weight: 'bold' } }
                        }
                    }
                }
            });
        }
    });
    </script>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/welcome.blade.php ENDPATH**/ ?>