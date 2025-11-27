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
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden" x-data="{ 
    modalOpen: false, 
    activeAnnouncement: null,
    scrolled: false,
    
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
}" @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false">

    <!-- NAVBAR -->
    <nav :class="{ 'bg-white/90 backdrop-blur-md shadow-md': scrolled, 'bg-transparent': !scrolled }" class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-transparent" :class="{ 'border-slate-200': scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="relative w-12 h-12 flex-shrink-0">
                        {{-- Fallback Logo jika file tidak ditemukan --}}
                        <img 
                            src="{{ asset('images/logo.png') }}" 
                            alt="Logo SMPN 3 Lakbok" 
                            class="w-full h-full object-contain drop-shadow-md hover:scale-105 transition-transform duration-300"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <div class="absolute inset-0 bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-500/30 flex items-center justify-center" style="display: none;">
                            <i class="ph-bold ph-graduation-cap text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="block text-xl font-extrabold text-slate-900 leading-none tracking-tight">SMPN 3 LAKBOK</span>
                        <span class="text-xs font-bold text-blue-600 tracking-wide mt-1">
                            BERJAYA : <span class="text-slate-500 font-medium">Unggul, Berkarakter, Juara</span>
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-700 hover:text-blue-600 transition px-4 py-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:inline-flex group relative items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-blue-600 font-pj rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5">
                            <span class="mr-2">Login Staff</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('login') }}" class="md:hidden p-2 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                            <i class="ph-bold ph-sign-in text-2xl"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative bg-slate-900 pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-10 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-slate-900 to-slate-900 opacity-95"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12 lg:gap-20 z-10">
            <div class="lg:w-1/2 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-bold uppercase tracking-wider mb-6">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Sistem Informasi Akademik Terpadu
                </div>
                <h1 class="text-4xl lg:text-6xl font-extrabold text-white tracking-tight mb-6 leading-[1.15]">
                    Membangun Generasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Cerdas & Berdisiplin</span>
                </h1>
                <p class="text-slate-300 text-lg mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed font-medium">
                    Platform digital terintegrasi SMPN 3 Lakbok untuk pemantauan akademik, absensi kehadiran, dan literasi siswa secara real-time.
                </p>
                
                <div class="grid grid-cols-3 gap-3 max-w-md mx-auto lg:mx-0">
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-xl border border-emerald-500/20 shadow-lg group hover:bg-white/10 transition-colors">
                        <div class="text-3xl font-bold text-emerald-400 mb-1 group-hover:scale-110 transition-transform origin-left">{{ $stats['hadir'] ?? 0 }}</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Hadir</div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-xl border border-amber-500/20 shadow-lg group hover:bg-white/10 transition-colors">
                        <div class="text-3xl font-bold text-amber-400 mb-1 group-hover:scale-110 transition-transform origin-left">{{ $stats['terlambat'] ?? 0 }}</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Terlambat</div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-xl border border-rose-500/20 shadow-lg group hover:bg-white/10 transition-colors">
                        <div class="text-3xl font-bold text-rose-400 mb-1 group-hover:scale-110 transition-transform origin-left">{{ $stats['tidak_hadir'] ?? 0 }}</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Absen</div>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/2 w-full" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="relative bg-white/95 backdrop-blur rounded-2xl shadow-2xl p-6 border border-white/20 transform hover:scale-[1.02] transition duration-500 ring-1 ring-black/5">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <div class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                <i class="ph-fill ph-chart-bar"></i>
                            </div>
                            Statistik Mingguan
                        </h3>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-1 rounded-md border border-slate-200">
                            Real-time Data
                        </span>
                    </div>
                    <div class="h-72 w-full relative">
                         <canvas id="publicWeeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0">
             <svg class="w-full h-12 lg:h-24 text-slate-50 fill-current" viewBox="0 0 1440 320" preserveAspectRatio="none">
                 <path d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,197.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
             </svg>
        </div>
    </div>

    <!-- MENU AKSES -->
    <div class="bg-slate-50 py-16 lg:py-24 relative z-20 -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Akses Cepat Layanan</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Pilih menu layanan digital yang tersedia untuk Siswa, Guru, dan Staf.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <a href="{{ route('portal.index') }}" class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:shadow-blue-200/50 border border-slate-100 hover:border-blue-200 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                        <i class="ph-duotone ph-student text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blue-600">Portal Siswa</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Akses data kehadiran, nilai akademik, dan poin kedisiplinan siswa.</p>
                </a>
                <a href="{{ route('kiosk.show') }}" class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:shadow-purple-200/50 border border-slate-100 hover:border-purple-200 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                        <i class="ph-duotone ph-qr-code text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-purple-600">Mesin Absensi</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Mode Kiosk untuk pemindaian kartu pelajar saat kehadiran.</p>
                </a>
                <a href="{{ route('library.kiosk.index') }}" class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:shadow-emerald-200/50 border border-slate-100 hover:border-emerald-200 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                        <i class="ph-duotone ph-books text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-emerald-600">E-Library</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Buku tamu digital dan pencatatan peminjaman buku perpustakaan.</p>
                </a>
                <a href="{{ route('login') }}" class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:shadow-orange-200/50 border border-slate-100 hover:border-orange-200 transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                        <i class="ph-duotone ph-chalkboard-teacher text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-orange-600">Login Guru</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Panel administrasi data untuk Guru, Wali Kelas dan Staff TU.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- [BARU] SECTION: PROFIL SEKOLAH -->
    <div id="profil" class="py-24 bg-white relative overflow-hidden border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <!-- Kiri: Teks -->
                <div class="space-y-6" data-aos="fade-right">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-black uppercase tracking-widest border border-blue-100">Tentang Kami</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">Mewujudkan Generasi <br><span class="text-blue-600">Cerdas & Berkarakter</span></h2>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        SMP Negeri 3 Lakbok berkomitmen untuk memberikan layanan pendidikan terbaik yang mengintegrasikan kecerdasan akademik dengan nilai-nilai karakter luhur. Kami hadir untuk mencetak pemimpin masa depan.
                    </p>
                    
                    <!-- Stats Grid (Placeholder Static Data) -->
                    <div class="grid grid-cols-3 gap-6 pt-6">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-md transition-all">
                            <p class="text-3xl font-black text-slate-800">542</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Total Siswa</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-md transition-all">
                            <p class="text-3xl font-black text-slate-800">32</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Guru & Staff</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:bg-white hover:shadow-md transition-all">
                            <p class="text-3xl font-black text-slate-800">18</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Rombel Kelas</p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Ilustrasi/Foto -->
                <div class="relative" data-aos="fade-left">
                    <div class="absolute inset-0 bg-blue-600 rounded-[2.5rem] rotate-3 opacity-10"></div>
                    <div class="bg-slate-200 rounded-[2rem] overflow-hidden shadow-2xl relative aspect-video group">
                        <!-- Placeholder Image -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900 to-indigo-900 flex items-center justify-center group-hover:scale-105 transition-transform duration-700">
                            <div class="text-center text-white p-8">
                                <i class="ph-duotone ph-buildings text-6xl mb-4 opacity-50"></i>
                                <p class="font-bold opacity-70">Foto Gedung Sekolah</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- [BARU] SECTION: KEGIATAN SEKOLAH -->
    <div id="kegiatan" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-black uppercase tracking-widest border border-indigo-100">Galeri</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mt-4 mb-4">Aktifitas & Kegiatan Siswa</h2>
                <p class="text-slate-500 text-lg">Beragam kegiatan ekstrakurikuler dan acara sekolah yang mendukung pengembangan bakat dan minat siswa secara holistik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 group border border-slate-100 hover:border-blue-200" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-48 bg-blue-50 rounded-2xl mb-4 overflow-hidden relative">
                        <div class="absolute inset-0 flex items-center justify-center bg-blue-100 text-blue-300 group-hover:scale-110 transition-transform duration-500">
                             <i class="ph-duotone ph-basketball text-6xl"></i>
                        </div>
                    </div>
                    <div class="px-2 pb-2">
                        <h4 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-blue-600 transition-colors">Ekstrakurikuler Olahraga</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Pengembangan fisik, sportivitas, dan kerjasama tim melalui kegiatan bola basket, bola voli, dan futsal.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 group border border-slate-100 hover:border-purple-200" data-aos="fade-up" data-aos-delay="200">
                    <div class="h-48 bg-purple-50 rounded-2xl mb-4 overflow-hidden relative">
                        <div class="absolute inset-0 flex items-center justify-center bg-purple-100 text-purple-300 group-hover:scale-110 transition-transform duration-500">
                             <i class="ph-duotone ph-tent text-6xl"></i>
                        </div>
                    </div>
                    <div class="px-2 pb-2">
                        <h4 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-purple-600 transition-colors">Pramuka & Kepemimpinan</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Membentuk karakter disiplin, kemandirian, dan jiwa kepemimpinan yang tangguh melalui kegiatan kepramukaan.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 group border border-slate-100 hover:border-orange-200" data-aos="fade-up" data-aos-delay="300">
                    <div class="h-48 bg-orange-50 rounded-2xl mb-4 overflow-hidden relative">
                        <div class="absolute inset-0 flex items-center justify-center bg-orange-100 text-orange-300 group-hover:scale-110 transition-transform duration-500">
                             <i class="ph-duotone ph-book-open-text text-6xl"></i>
                        </div>
                    </div>
                    <div class="px-2 pb-2">
                        <h4 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-orange-600 transition-colors">Literasi & Keagamaan</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Program pembiasaan membaca pagi (literasi) dan kegiatan keagamaan rutin untuk keseimbangan ilmu dan iman.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LIBRARY SECTION -->
    <div class="py-20 bg-emerald-50/50 border-y border-emerald-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-6">
                        <i class="ph-fill ph-star mr-2"></i> Pusat Literasi
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-6">
                        Budayakan Membaca, <br>
                        <span class="text-emerald-600">Jelajahi Dunia</span>
                    </h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Perpustakaan SMPN 3 Lakbok menyediakan ribuan koleksi buku fisik dan digital. Pantau aktivitas literasi siswa secara transparan.
                    </p>
                    
                    <div class="flex gap-6">
                        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md border border-emerald-100/50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600"><i class="ph-bold ph-users"></i></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pengunjung Hari Ini</p>
                            </div>
                            <p class="text-4xl font-black text-slate-800">{{ $libraryStats['visitors_today'] ?? 0 }}</p>
                        </div>
                        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md border border-emerald-100/50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 rounded-lg text-blue-600"><i class="ph-bold ph-book-bookmark"></i></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Buku Dipinjam</p>
                            </div>
                            <p class="text-4xl font-black text-slate-800">{{ $libraryStats['books_borrowed'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-7/12" data-aos="fade-left">
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-100">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="font-bold text-xl text-slate-800">Tren Kunjungan Perpustakaan</h3>
                            <button class="text-emerald-600 text-sm font-bold hover:underline">Lihat Laporan &rarr;</button>
                        </div>
                        <div class="h-72">
                            <canvas id="publicLibraryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACHIEVEMENTS SECTION -->
    <div class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-yellow-500 font-bold tracking-wider text-sm uppercase mb-2 block">Hall of Fame</span>
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Prestasi & Kebanggaan</h2>
                <div class="w-24 h-1.5 bg-yellow-400 mx-auto mt-6 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($achievements as $item)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-lg hover:shadow-2xl hover:shadow-yellow-100/50 transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="aspect-video w-full bg-slate-100 relative overflow-hidden">
                            @if($item->photo_path)
                                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->title }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                     onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center text-yellow-300 bg-slate-50\'><i class=\'ph-duotone ph-image-broken text-6xl mb-3\'></i><span class=\'text-xs font-bold text-slate-400 uppercase\'>Image Error</span></div>';">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-yellow-300 bg-slate-50 relative">
                                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                                    <i class="ph-duotone ph-trophy text-6xl mb-3 relative z-10"></i>
                                    <span class="text-xs font-bold text-slate-400 uppercase relative z-10">No Image Available</span>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 z-20">
                                <span class="px-3 py-1 bg-yellow-400/90 backdrop-blur text-white text-[10px] font-bold rounded-full shadow-lg uppercase tracking-wide border border-white/20">
                                    {{ $item->level }}
                                </span>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-xs text-yellow-600 font-bold uppercase tracking-wider mb-2">
                                <i class="ph-fill ph-medal"></i> {{ $item->type }}
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-4 line-clamp-2 group-hover:text-yellow-600 transition-colors">{{ $item->title }}</h3>
                            <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                                <div class="w-8 h-8 rounded-full bg-yellow-50 border border-yellow-100 flex items-center justify-center text-yellow-700 font-bold text-xs">
                                    {{ substr($item->achiever_name, 0, 1) }}
                                </div>
                                <p class="text-sm font-bold text-slate-700">{{ $item->achiever_name }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200" data-aos="fade-in">
                        <i class="ph-duotone ph-trophy text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500 font-medium">Belum ada data prestasi yang ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ANNOUNCEMENT & FOOTER SECTION -->
    <div class="bg-slate-900 text-white pt-24 pb-12 relative overflow-hidden mt-12">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
        <div class="absolute -right-20 top-20 w-96 h-96 bg-blue-600 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- PENGUMUMAN -->
            <div class="mb-20">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">Papan Pengumuman</h2>
                        <p class="text-slate-400 text-sm">Informasi terbaru seputar kegiatan sekolah.</p>
                    </div>
                    <a href="#" class="text-sm text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1 transition-colors">
                        Lihat Semua <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
                
                <div class="grid gap-6 md:grid-cols-3">
                    @forelse ($announcements as $index => $item)
                        <article class="bg-slate-800/50 backdrop-blur rounded-xl p-6 border border-slate-700 hover:border-blue-500/50 transition-all duration-300 hover:bg-slate-800 group h-full flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-2 py-1 rounded bg-blue-500/10 text-blue-400 text-[10px] font-bold uppercase tracking-wide border border-blue-500/20">Info Sekolah</span>
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <i class="ph-fill ph-calendar-blank"></i> {{ $item->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-3 line-clamp-2 group-hover:text-blue-400 transition-colors">
                                <a href="#" @click.prevent='openAnnouncementByIndex({{ $index }})'>{{ $item->title }}</a>
                            </h3>
                            <p class="text-slate-400 text-sm line-clamp-3 mb-4 flex-1">
                                {{ Str::limit(strip_tags($item->content), 100) }}
                            </p>
                            <button @click="openAnnouncementByIndex({{ $index }})" class="text-sm text-blue-400 font-semibold hover:text-blue-300 flex items-center gap-1 mt-auto">
                                Baca Selengkapnya <i class="ph-bold ph-arrow-right text-xs"></i>
                            </button>
                        </article>
                    @empty
                        <div class="col-span-3 text-center py-12 border border-dashed border-slate-700 rounded-xl">
                            <p class="text-slate-500">Tidak ada pengumuman terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <hr class="border-slate-800 mb-12">

            <!-- FOOTER WIDGETS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <!-- Footer Logo Update -->
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-white/10 flex items-center justify-center">
                             <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Logo" class="w-full h-full object-contain">
                             <i class="ph-bold ph-graduation-cap text-xl text-white" style="display: none;"></i>
                        </div>
                        <span class="text-xl font-bold text-white">SMPN 3 LAKBOK</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mb-6">
                        Mewujudkan generasi berprestasi, berkarakter mulia, dan berwawasan lingkungan. Hubungi kami untuk informasi lebih lanjut.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="ph-fill ph-facebook-logo text-2xl"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="ph-fill ph-instagram-logo text-2xl"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="ph-fill ph-youtube-logo text-2xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Profil Sekolah</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Tenaga Pendidik</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Ekstrakurikuler</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">PPDB Online</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6">Kontak Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-map-pin mt-1 text-blue-500"></i>
                            <span>Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Kab. Ciamis, Jawa Barat 46385</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-phone text-blue-500"></i>
                            <span>0853</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-envelope text-blue-500"></i>
                            <span>admin@smpn3lakbok.sch.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- COPYRIGHT -->
            <div class="text-center pt-8 border-t border-slate-800">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Developed with <i class="ph-fill ph-heart text-red-500 mx-1"></i> by RI... IT Team.
                </p>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" @click="closeAnnouncement()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-6 py-6 sm:p-8">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wide">Pengumuman</span>
                        <button @click="closeAnnouncement()" class="text-gray-400 hover:text-gray-600 transition"><i class="ph-bold ph-x text-xl"></i></button>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight mb-4" x-text="activeAnnouncement?.title"></h3>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        <div x-html="activeAnnouncement?.content.replace(/\n/g, '<br>')"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3">
                    <button class="inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors" @click="closeAnnouncement()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Init Animate On Scroll
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
        });

        // -----------------------------------------------------
        // DATA GLOBAL UNTUK JS
        // -----------------------------------------------------
        window.announcementsData = @json($announcements);

        document.addEventListener('DOMContentLoaded', function() {
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
                        barThickness: 20,
                        scales: {
                            x: { 
                                stacked: true, 
                                grid: { display: false } 
                            },
                            y: { 
                                beginAtZero: true, 
                                stacked: true, 
                                grid: { color: '#f1f5f9', borderDash: [5, 5] },
                                border: { display: false }
                            }
                        },
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    usePointStyle: true,
                                    padding: 20,
                                    font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                                } 
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { family: "'Plus Jakarta Sans', sans-serif" },
                                bodyFont: { family: "'Plus Jakarta Sans', sans-serif" }
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
                                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
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
                        plugins: { legend: { display: false } },
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                border: { display: false }, 
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" } }
                            }, 
                            x: { 
                                grid: { display: false },
                                ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" } }
                            } 
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>