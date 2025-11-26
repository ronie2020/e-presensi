<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ 
    modalOpen: false, 
    activeAnnouncement: null,
    openAnnouncement(item) {
        this.activeAnnouncement = item;
        this.modalOpen = true;
        document.body.style.overflow = 'hidden';
    },
    closeAnnouncement() {
        this.modalOpen = false;
        setTimeout(() => { this.activeAnnouncement = null }, 300);
        document.body.style.overflow = 'auto';
    }
}">

    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-200 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <x-application-logo class="block h-10 w-auto text-blue-600" />
                    <span class="ml-3 text-xl font-extrabold text-gray-800 tracking-tight hidden md:block">SMP NEGERI 3 LAKBOK</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            Login Guru/Staf
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION (Statistik Kehadiran) -->
    <div class="relative bg-blue-800 overflow-hidden text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 z-0"></div>
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] z-0"></div>
        
        <div class="relative max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center z-10">
            <div class="md:w-1/2 mb-12 md:mb-0 text-center md:text-left">
                <span class="inline-flex items-center py-1 px-3 rounded-full bg-blue-500/20 text-blue-100 text-xs font-bold uppercase tracking-wider mb-6 border border-blue-400/30">
                    <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse"></span>
                    Sistem Informasi Terpadu
                </span>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-6 leading-tight">
                    Wujudkan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Generasi Disiplin</span>
                </h1>
                <p class="text-blue-100/90 text-lg mb-8 max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">
                    Platform digital SMPN 3 Lakbok untuk memantau kehadiran, aktivitas, dan perkembangan akademik siswa secara real-time.
                </p>
                
                <!-- Statistik Mini Presensi -->
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 shadow-lg min-w-[140px]">
                        <div class="text-3xl font-bold">{{ $stats['hadir'] ?? 0 }}</div>
                        <div class="text-xs uppercase font-semibold text-blue-200 tracking-wider mt-1">Siswa Hadir</div>
                    </div>
                     <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 shadow-lg min-w-[140px]">
                        <div class="text-3xl font-bold text-yellow-300">{{ $stats['terlambat'] ?? 0 }}</div>
                        <div class="text-xs uppercase font-semibold text-yellow-100 tracking-wider mt-1">Terlambat</div>
                    </div>
                </div>
            </div>

            <!-- GRAFIK PRESENSI -->
            <div class="md:w-1/2 w-full pl-0 md:pl-10">
                <div class="bg-white/95 backdrop-blur rounded-2xl shadow-2xl p-6 text-gray-800 border border-gray-100/50 transform rotate-1 hover:rotate-0 transition duration-500">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Statistik Kehadiran
                        </h3>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 px-2 py-1 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Live
                        </span>
                    </div>
                    <div class="h-64">
                         <canvas id="publicWeeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave Separator -->
        <div class="absolute bottom-0 w-full text-gray-50">
             <svg class="w-full h-12 md:h-24" viewBox="0 0 1440 320" preserveAspectRatio="none">
                 <path fill="currentColor" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,197.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
             </svg>
        </div>
    </div>

    <!-- MENU AKSES -->
    <div class="bg-gray-50 py-12 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Portal Siswa -->
                <a href="{{ route('portal.index') }}" class="group bg-white p-6 rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-300 transform hover:-translate-y-2 h-full">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-5 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Portal Siswa</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Cek kehadiran, poin disiplin, dan nilai siswa secara mandiri.</p>
                </a>
                
                <!-- Card 2: Mesin Absensi -->
                <a href="{{ route('kiosk.show') }}" class="group bg-white p-6 rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-purple-200/50 transition-all duration-300 transform hover:-translate-y-2 h-full">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-5 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 19v-4H4v4h2zM6 12V7a1 1 0 011-1h10a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Mesin Absensi</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Mode Kiosk untuk scan kehadiran harian siswa di gerbang.</p>
                </a>

                <!-- Card 3: Buku Tamu Perpus -->
                <a href="{{ route('library.kiosk.index') }}" class="group bg-white p-6 rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-emerald-200/50 transition-all duration-300 transform hover:-translate-y-2 h-full">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-5 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">Kiosk Perpus</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Buku tamu digital untuk mencatat kunjungan perpustakaan.</p>
                </a>

                <!-- Card 4: Login Guru -->
                <a href="{{ route('login') }}" class="group bg-white p-6 rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-green-200/50 transition-all duration-300 transform hover:-translate-y-2 h-full">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-5 group-hover:scale-110 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">Login Guru</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Area administratif untuk Guru dan Staf sekolah.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- SECTION BARU: STATISTIK PERPUSTAKAAN -->
    <div class="py-16 bg-emerald-50 border-t border-emerald-100 relative overflow-hidden">
        <!-- Abstract BG for Library -->
        <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/notebook.png')] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center gap-12">
                
                <!-- Teks & Statistik Mini -->
                <div class="w-full md:w-1/2">
                    <span class="inline-flex items-center py-1 px-3 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Pusat Literasi
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight mb-4">
                        Budayakan Membaca, <br>
                        <span class="text-emerald-600">Jelajahi Dunia</span>
                    </h2>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                        Pantau aktivitas perpustakaan secara real-time. Kami berkomitmen menyediakan akses literasi terbaik bagi siswa.
                    </p>
                    
                    <!-- Statistik Mini Library -->
                    <div class="flex gap-6">
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100 w-40">
                            <p class="text-3xl font-black text-emerald-600">{{ $libraryStats['visitors_today'] ?? 0 }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase mt-1">Pengunjung Hari Ini</p>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100 w-40">
                            <p class="text-3xl font-black text-blue-600">{{ $libraryStats['books_borrowed'] ?? 0 }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase mt-1">Buku Dipinjam</p>
                        </div>
                    </div>
                </div>

                <!-- Grafik Perpustakaan -->
                <div class="w-full md:w-1/2">
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-lg text-gray-800">Grafik Kunjungan Minggu Ini</h3>
                            <a href="{{ route('library.dashboard') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Lihat Detail &rarr;</a>
                        </div>
                        <div class="h-64">
                            <canvas id="publicLibraryChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- PENGUMUMAN -->
    <div class="py-20 bg-white relative overflow-hidden">
        {{-- Background blobs --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Papan Pengumuman</h2>
                    <p class="mt-4 text-lg text-gray-500">Informasi resmi terkini untuk siswa, orang tua, dan warga sekolah.</p>
                </div>
                <a href="#" class="hidden md:inline-flex items-center font-bold text-blue-600 hover:text-blue-700 mt-4 md:mt-0 group">
                    Lihat Semua Arsip
                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                @forelse ($announcements as $item)
                    <article class="flex flex-col bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 h-full">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wide">Info</span>
                            <span class="text-xs text-gray-400 font-medium">{{ $item->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <a href="#" @click.prevent='openAnnouncement(@json($item))' class="hover:text-blue-600 transition-colors">{{ $item->title }}</a>
                        </h3>
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-6 flex-1">
                            {{ Str::limit(strip_tags($item->content), 120) }}
                        </p>
                        <button 
                            @click='openAnnouncement(@json($item))' 
                            class="text-blue-600 text-sm font-bold flex items-center group mt-auto pt-4 border-t border-gray-50">
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <p class="font-medium">Belum ada pengumuman terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="closeAnnouncement()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                <div class="bg-white px-6 py-6 sm:p-8">
                    <div class="flex items-start justify-between mb-6">
                        <h3 class="text-2xl font-black text-gray-900 leading-tight" x-text="activeAnnouncement?.title"></h3>
                        <button @click="closeAnnouncement()" class="ml-4 rounded-full p-1 bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-500 mb-6 pb-6 border-b border-gray-100">
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span x-text="new Date(activeAnnouncement?.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span></span>
                        <span>&bull;</span>
                        <span class="text-blue-600 font-semibold">Admin Sekolah</span>
                    </div>
                    <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed">
                        <div x-html="activeAnnouncement?.content.replace(/\n/g, '<br>')"></div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button type="button" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 sm:w-auto transition-colors" @click="closeAnnouncement()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <x-application-logo class="h-8 w-auto text-blue-600" />
                <div class="ml-3">
                    <span class="block text-sm font-bold text-gray-900">SMP NEGERI 3 LAKBOK</span>
                    <span class="block text-xs text-gray-500">Sistem Informasi Presensi & Akademik</span>
                </div>
            </div>
            <p class="text-gray-400 text-sm font-medium">&copy; {{ date('Y') }} Tim IT & Pengembang.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CHART PRESENSI (Sudah Ada)
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
                        responsive: true, maintainAspectRatio: false, borderRadius: 4,
                        scales: { y: { beginAtZero: true, stacked: true, grid: { display: false } }, x: { stacked: true, grid: { display: false } } },
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } } }
                    }
                });
            }

            // CHART PERPUSTAKAAN (BARU)
            const libCtx = document.getElementById('publicLibraryChart');
            if (libCtx) {
                // Mengambil data yang sudah siap di $libraryChartData (atau default jika null)
                @php
                    // Definisikan nilai default agar @json tidak error jika variabel belum ada
                    $defaultLibData = [
                        'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'], 
                        'data' => [0, 0, 0, 0, 0]
                    ];
                    // Gunakan variabel dari controller jika ada, atau default jika tidak
                    $finalLibData = $libraryChartData ?? $defaultLibData;
                @endphp

                const libData = @json($finalLibData);

                new Chart(libCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: libData.labels,
                        datasets: [{
                            label: 'Kunjungan',
                            data: libData.data,
                            borderColor: '#059669', // Emerald 600
                            backgroundColor: 'rgba(5, 150, 105, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#059669',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, border: { display: false }, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>