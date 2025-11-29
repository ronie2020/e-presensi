<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Animation Library (AOS) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        
        /* Glass Effect Utility */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden" 
    x-data="{ 
        mobileMenuOpen: false,
        modalOpen: false, 
        activeAnnouncement: null,
        scrolled: false,
        showBackToTop: false,
        
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
        }
    }" 
    @scroll.window="
        scrolled = (window.pageYOffset > 20) ? true : false;
        showBackToTop = (window.pageYOffset > 500) ? true : false;
    ">

    <!-- NAVBAR -->
    <nav :class="{ 'bg-white/90 backdrop-blur-md shadow-lg border-slate-200': scrolled, 'bg-transparent border-transparent': !scrolled }" class="fixed top-0 w-full z-50 transition-all duration-300 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex items-center gap-3 z-50">
                    <div class="relative w-10 h-10 lg:w-12 lg:h-12 flex-shrink-0">
                        <img 
                            src="{{ asset('images/logo.png') }}" 
                            alt="Logo SMPN 3 Lakbok" 
                            class="w-full h-full object-contain drop-shadow-md hover:scale-105 transition-transform duration-300"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <div class="absolute inset-0 bg-blue-600 text-white rounded-xl shadow-lg flex items-center justify-center" style="display: none;">
                            <i class="ph-bold ph-graduation-cap text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="block text-lg lg:text-xl font-extrabold text-slate-900 leading-none tracking-tight" :class="{'text-white': !scrolled && mobileMenuOpen}">SMPN 3 LAKBOK</span>
                        <span class="text-[10px] lg:text-xs font-bold text-blue-600 tracking-wide mt-1" :class="{'text-blue-300': !scrolled && mobileMenuOpen}">
                            BERJAYA : <span class="text-slate-500 font-medium" :class="{'text-slate-300': !scrolled && mobileMenuOpen}">Unggul, Berkarakter</span>
                        </span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#profil" class="text-sm font-semibold transition hover:text-blue-600" :class="{ 'text-slate-700': scrolled, 'text-slate-200 hover:text-white': !scrolled }">Profil</a>
                    <a href="#guru" class="text-sm font-semibold transition hover:text-blue-600" :class="{ 'text-slate-700': scrolled, 'text-slate-200 hover:text-white': !scrolled }">Guru</a>
                    <a href="#kegiatan" class="text-sm font-semibold transition hover:text-blue-600" :class="{ 'text-slate-700': scrolled, 'text-slate-200 hover:text-white': !scrolled }">Kegiatan</a>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold px-5 py-2.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="group relative items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-blue-600 font-pj rounded-full focus:outline-none hover:bg-blue-700 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 flex gap-2">
                            <span>Login Staff</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center z-50">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg transition-colors focus:outline-none" :class="{ 'text-slate-800': scrolled && !mobileMenuOpen, 'text-white': !scrolled || mobileMenuOpen }">
                        <i class="ph-bold text-3xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-10"
             class="absolute top-0 left-0 w-full h-screen bg-slate-900/95 backdrop-blur-xl flex flex-col items-center justify-center space-y-8 z-40 md:hidden">
             
            <nav class="flex flex-col items-center space-y-6 text-center">
                <a href="#profil" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white hover:text-blue-400 transition">Profil Sekolah</a>
                <a href="#guru" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white hover:text-blue-400 transition">Guru & Staff</a>
                <a href="#kegiatan" @click="mobileMenuOpen = false" class="text-2xl font-bold text-white hover:text-blue-400 transition">Kegiatan</a>
                
                <hr class="w-16 border-slate-700">

                <div class="flex flex-col gap-4 w-full">
                    <a href="{{ route('portal.index') }}" class="text-lg font-medium text-slate-300 hover:text-white">Portal Siswa</a>
                    <a href="{{ route('library.kiosk.index') }}" class="text-lg font-medium text-slate-300 hover:text-white">E-Library</a>
                </div>
                
                <div class="mt-8">
                     @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-3 rounded-full bg-blue-600 text-white font-bold text-lg shadow-xl shadow-blue-500/20">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-3 rounded-full bg-white text-blue-900 font-bold text-lg shadow-xl">Login Staff</a>
                    @endauth
                </div>
            </nav>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative bg-slate-900 pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-[90vh] flex items-center">
        <!-- Background -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900/95 to-blue-900/90"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob animation-delay-2000"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12 lg:gap-20 z-10 w-full">
            <!-- Text Content -->
            <div class="lg:w-1/2 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-blue-300 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Sistem Informasi Akademik Terpadu
                </div>
                <h1 class="text-4xl lg:text-6xl xl:text-7xl font-extrabold text-white tracking-tight mb-6 leading-[1.1] drop-shadow-sm">
                    Membangun Generasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Cerdas & Berdisiplin</span>
                </h1>
                <p class="text-slate-300 text-lg mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed font-medium">
                    Platform digital terintegrasi SMPN 3 Lakbok untuk pemantauan akademik, absensi kehadiran, dan literasi siswa secara real-time.
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0">
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-2xl border border-white/10 shadow-lg hover:bg-white/10 transition-colors group cursor-default">
                        <div class="text-3xl font-bold text-emerald-400 mb-1 group-hover:scale-110 transition-transform origin-left">{{ $stats['hadir'] ?? 0 }}</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Hadir</div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-2xl border border-white/10 shadow-lg hover:bg-white/10 transition-colors group cursor-default">
                        <div class="text-3xl font-bold text-amber-400 mb-1 group-hover:scale-110 transition-transform origin-left">{{ $stats['terlambat'] ?? 0 }}</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Terlambat</div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-2xl border border-white/10 shadow-lg hover:bg-white/10 transition-colors group cursor-default">
                        <div class="text-3xl font-bold text-rose-400 mb-1 group-hover:scale-110 transition-transform origin-left">{{ $stats['tidak_hadir'] ?? 0 }}</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Absen</div>
                    </div>
                </div>
            </div>

            <!-- Chart / Visual Content -->
            <div class="lg:w-1/2 w-full" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="relative bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-6 lg:p-8 border border-white/20 transform hover:scale-[1.01] transition duration-500 ring-1 ring-black/5">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                                <i class="ph-fill ph-chart-bar text-xl"></i>
                            </div>
                            Statistik Kehadiran
                        </h3>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 px-2 py-1 rounded-md border border-green-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-600 animate-pulse"></span> Live
                        </span>
                    </div>
                    <div class="h-[300px] w-full relative">
                         <canvas id="publicWeeklyChart"></canvas>
                    </div>
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

    <!-- MENU AKSES -->
    <div class="bg-slate-50 py-16 lg:py-24 relative z-20 -mt-8 lg:-mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Akses Cepat Layanan</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Menu layanan digital terintegrasi untuk seluruh civitas akademika.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Portal Siswa -->
                <a href="{{ route('portal.index') }}" class="group bg-white rounded-2xl p-8 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-2xl hover:shadow-blue-500/10 border border-slate-100 hover:border-blue-200 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 shadow-inner">
                        <i class="ph-duotone ph-student text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600">Portal Siswa</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Cek kehadiran, nilai akademik, dan poin kedisiplinan.</p>
                </a>
                
                <!-- Mesin Absensi -->
                <a href="{{ route('kiosk.show') }}" class="group bg-white rounded-2xl p-8 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-2xl hover:shadow-purple-500/10 border border-slate-100 hover:border-purple-200 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 shadow-inner">
                        <i class="ph-duotone ph-qr-code text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-purple-600">Mesin Absensi</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Mode Kiosk untuk pemindaian kartu pelajar saat kehadiran.</p>
                </a>
                
                <!-- E-Library -->
                <a href="{{ route('library.kiosk.index') }}" class="group bg-white rounded-2xl p-8 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-2xl hover:shadow-emerald-500/10 border border-slate-100 hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 shadow-inner">
                        <i class="ph-duotone ph-books text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-emerald-600">E-Library</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Buku tamu digital dan katalog perpustakaan sekolah.</p>
                </a>
                
                <!-- Login Guru -->
                <a href="{{ route('login') }}" class="group bg-white rounded-2xl p-8 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-2xl hover:shadow-orange-500/10 border border-slate-100 hover:border-orange-200 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 shadow-inner">
                        <i class="ph-duotone ph-chalkboard-teacher text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-600">Login Staff</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Panel administrasi untuk Guru, Wali Kelas dan TU.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- SECTION: PROFIL SEKOLAH -->
    <div id="profil" class="py-24 bg-white relative overflow-hidden border-y border-slate-100">
        <!-- Background Pattern -->
        <div class="absolute right-0 top-0 opacity-5 pointer-events-none">
            <svg width="400" height="400" fill="none" viewBox="0 0 200 200">
                <defs><pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" class="text-slate-900" fill="currentColor"></circle></pattern></defs>
                <rect width="200" height="200" fill="url(#dots)"></rect>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <!-- Kiri: Teks -->
                <div class="space-y-8" data-aos="fade-right">
                    <div class="space-y-2">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest border border-blue-100">Tentang Kami</span>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">Mewujudkan Generasi <br><span class="text-blue-600">Cerdas & Berkarakter</span></h2>
                    </div>
                    <p class="text-lg text-slate-600 leading-relaxed text-justify lg:text-left">
                        SMP Negeri 3 Lakbok berkomitmen untuk memberikan layanan pendidikan terbaik yang mengintegrasikan kecerdasan akademik dengan nilai-nilai karakter luhur. Kami hadir untuk mencetak pemimpin masa depan yang kompetitif dan berakhlak mulia.
                    </p>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-lg hover:border-blue-100 transition-all duration-300">
                            <p class="text-3xl font-black text-slate-800">542</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Siswa</p>
                        </div>
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-lg hover:border-blue-100 transition-all duration-300">
                            <p class="text-3xl font-black text-slate-800">32</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Guru</p>
                        </div>
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-lg hover:border-blue-100 transition-all duration-300">
                            <p class="text-3xl font-black text-slate-800">18</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Rombel</p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Slideshow Ilustrasi/Foto -->
                <div class="relative group" data-aos="fade-left"
                    x-data="{ 
                        currentSlide: 0, 
                        slides: [
                            '{{ asset('images/netila.jpg') }}', 
                            '{{ asset('images/hadir.jpg') }}', 
                            '{{ asset('images/digital1.jpg') }}', 
                            '{{ asset('images/digital2.jpg') }}', 
                            '{{ asset('images/kka.png') }}', 
                            '{{ asset('images/religi.jpg') }}', 
                        ],
                            
                        init() {
                            setInterval(() => {
                                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                            }, 4000);
                        }
                    }"
                    x-init="init()">
                    
                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-[2.5rem] opacity-20 blur-lg group-hover:opacity-40 transition duration-500"></div>
                    <div class="absolute inset-0 bg-blue-600 rounded-[2.5rem] rotate-3 opacity-10"></div>
                    
                    <div class="bg-slate-200 rounded-[2rem] overflow-hidden shadow-2xl relative aspect-video z-10">
                        <!-- Loop Gambar Slideshow -->
                        <template x-for="(slide, index) in slides" :key="index">
                            <img :src="slide" 
                                x-show="currentSlide === index"
                                x-transition:enter="transition ease-in-out duration-1000"
                                x-transition:enter-start="opacity-0 scale-105"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in-out duration-1000"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-100"
                                class="absolute inset-0 w-full h-full object-cover" 
                                alt="Gedung Sekolah">
                        </template>

                        <!-- Overlay Statis -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/90 to-slate-800/40 flex items-center justify-center z-20">
                            <div class="flex flex-col items-center justify-center text-white p-8 text-center">
                               
                                
                                <!-- Indikator Slideshow -->
                                <div class="flex gap-2 mt-6">
                                    <template x-for="(_, index) in slides" :key="index">
                                        <button @click="currentSlide = index" 
                                                class="h-1.5 rounded-full transition-all duration-500 cursor-pointer hover:bg-white" 
                                                :class="currentSlide === index ? 'w-8 bg-white' : 'w-2 bg-white/40'">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- TEACHER PROFILE SECTION -->
    <div id="guru" class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest border border-blue-100">
                    SDM Unggul
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl mt-4">Tenaga Pendidik</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                    Dibimbing oleh guru-guru profesional yang berdedikasi tinggi.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($teachers as $teacher)
                    <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 h-full flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        
                        <!-- Foto Profil -->
                        <div class="aspect-[3/4] w-full relative overflow-hidden bg-slate-100">
                            @if($teacher->photo_path)
                                <img src="{{ asset('storage/' . $teacher->photo_path) }}" 
                                     alt="{{ $teacher->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                
                                <div class="w-full h-full hidden flex-col items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 text-slate-500">
                                    <span class="text-6xl font-black opacity-30 select-none uppercase">
                                        {{ substr($teacher->name, 0, 2) }}
                                    </span>
                                </div>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600">
                                    <span class="text-7xl font-black opacity-20 select-none uppercase group-hover:scale-110 transition-transform">
                                        {{ substr($teacher->name, 0, 2) }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <!-- Info -->
                        <div class="p-5 text-center relative bg-white flex-1 flex flex-col justify-end">
                            <div class="absolute -top-4 left-0 right-0 flex justify-center">
                                <span class="bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider py-1 px-3 rounded-full shadow-lg border-2 border-white">
                                    {{ $teacher->position ?? $teacher->role }}
                                </span>
                            </div>
                            
                            <h3 class="mt-4 text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $teacher->name }}
                            </h3>
                            
                            @if(!empty($teacher->nip))
                                <p class="text-xs text-slate-500 font-medium mt-1">
                                    NIP. {{ $teacher->nip }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                            <i class="ph-duotone ph-users text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Belum ada data tenaga pendidik.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm hover:shadow-md">
                    Lihat Seluruh Staff
                    <i class="ph-bold ph-arrow-right ml-2"></i>
                </a>
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
                <a href="#" class="hidden md:inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Lihat Semua Galeri <i class="ph-bold ph-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($activities as $activity)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="relative h-60 overflow-hidden">
                            @if($activity->image_path)
                                <img src="{{ asset('storage/' . $activity->image_path) }}" 
                                     alt="{{ $activity->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400" style="display: none;">
                                    <i class="ph-duotone ph-image-broken text-4xl"></i>
                                </div>
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                    <i class="ph-duotone ph-image text-4xl"></i>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-60"></div>
                            
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    {{ $activity->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                {{ $activity->title }}
                            </h4>
                            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-4 flex-1">
                                {{ $activity->description }}
                            </p>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder Data -->
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-md border border-slate-100" data-aos="fade-up">
                        <div class="h-60 bg-slate-200 animate-pulse"></div>
                        <div class="p-6">
                            <div class="h-6 bg-slate-200 rounded w-3/4 mb-3 animate-pulse"></div>
                            <div class="h-4 bg-slate-100 rounded w-full animate-pulse"></div>
                        </div>
                    </div>
                @endforelse
            </div>
            
             <div class="mt-8 text-center md:hidden">
                <a href="#" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Lihat Semua Galeri <i class="ph-bold ph-arrow-right ml-2"></i>
                </a>
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
                            <p class="text-4xl font-black text-slate-800">{{ $libraryStats['visitors_today'] ?? 0 }}</p>
                            <p class="text-xs text-emerald-600 font-medium mt-1">Hari ini</p>
                        </div>
                        <div class="flex-1 bg-white p-6 rounded-2xl shadow-lg shadow-emerald-100 border border-emerald-50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 rounded-lg text-blue-600"><i class="ph-bold ph-book-bookmark"></i></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Dipinjam</p>
                            </div>
                            <p class="text-4xl font-black text-slate-800">{{ $libraryStats['books_borrowed'] ?? 0 }}</p>
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

    <!-- ANNOUNCEMENT & FOOTER SECTION -->
    <div class="bg-slate-900 text-white pt-24 pb-12 relative overflow-hidden">
        <!-- Decoration -->
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
                    @forelse ($announcements as $index => $item)
                        <article class="bg-slate-800/50 backdrop-blur-md rounded-2xl p-6 border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300 hover:bg-slate-800 hover:-translate-y-1 group h-full flex flex-col cursor-pointer" @click="openAnnouncementByIndex({{ $index }})" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-2 py-1 rounded bg-blue-500/10 text-blue-400 text-[10px] font-bold uppercase tracking-wide border border-blue-500/20">Info Sekolah</span>
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <i class="ph-fill ph-calendar-blank"></i> {{ $item->created_at->format('d M') }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-3 line-clamp-2 group-hover:text-blue-400 transition-colors">
                                {{ $item->title }}
                            </h3>
                            <p class="text-slate-400 text-sm line-clamp-3 mb-4 flex-1 leading-relaxed">
                                {{ Str::limit(strip_tags($item->content), 100) }}
                            </p>
                            <div class="flex items-center text-sm text-blue-400 font-semibold mt-auto gap-1 group-hover:gap-2 transition-all">
                                Baca Selengkapnya <i class="ph-bold ph-arrow-right text-xs"></i>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-3 text-center py-12 border border-dashed border-slate-700 rounded-xl bg-slate-800/30">
                            <p class="text-slate-500">Tidak ada pengumuman terbaru saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- FOOTER WIDGETS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 border-t border-slate-800 pt-16">
                <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-white flex items-center justify-center">
                             <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Logo" class="w-full h-full object-contain p-1">
                             <i class="ph-bold ph-graduation-cap text-xl text-blue-900" style="display: none;"></i>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">SMPN 3 LAKBOK</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-8">
                        Lembaga pendidikan yang berdedikasi untuk mencetak generasi berprestasi, berkarakter mulia, dan peduli lingkungan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition-all duration-300"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-pink-600 transition-all duration-300"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-600 transition-all duration-300"><i class="ph-fill ph-youtube-logo text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Menu Utama</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="{{ route('teachers.index') }}" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Profil Sekolah</a></li>
                        <li><a href="#guru" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Tenaga Pendidik</a></li>
                        <li><a href="#kegiatan" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Galeri Kegiatan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Login Staff</a></li>
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
                            <span>+6285135961994</span>
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
                    &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Ri.. and All rights reserved.
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

    <!-- MODAL POPUP -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="closeAnnouncement()"></div>
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

    <!-- SCRIPTS -->
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });

        window.announcementsData = @json($announcements);

        document.addEventListener('DOMContentLoaded', function() {
            // Chart Defaults
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#64748b';

            // Chart 1: Attendance
            const ctx = document.getElementById('publicWeeklyChart');
            if(ctx) {
                const chartData = @json($barChartData); 
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
                        barThickness: 24, // Sedikit lebih tebal
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    usePointStyle: true,
                                    padding: 20,
                                    font: { size: 12, weight: 600 }
                                } 
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                padding: 14,
                                cornerRadius: 8,
                                titleFont: { size: 13, weight: 700 },
                                bodyFont: { size: 13 },
                                displayColors: false
                            }
                        },
                        scales: {
                            x: { 
                                stacked: true, 
                                grid: { display: false },
                                ticks: { font: { weight: 600 } }
                            },
                            y: { 
                                beginAtZero: true, 
                                stacked: true, 
                                grid: { color: '#f1f5f9', borderDash: [5, 5] },
                                border: { display: false }
                            }
                        }
                    }
                });
            }

            // Chart 2: Library
            const libCtx = document.getElementById('publicLibraryChart');
            if (libCtx) {
                const libData = @json($libraryChartData);
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
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
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
    </script>
</body>
</html>