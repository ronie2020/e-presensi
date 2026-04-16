<!-- HERO SECTION (Gaya Microsoft Elevate - Fixed Mobile View) -->
<section id="home" class="relative pt-44 pb-56 md:pt-48 md:pb-40 lg:pt-56 lg:pb-48 overflow-hidden bg-gradient-to-br from-cyan-500 via-blue-600 to-blue-900 block">
    
    {{-- 1. BACKGROUND MESH --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-full md:w-[60%] h-full bg-cyan-300/20 rounded-full blur-[100px] -translate-x-1/4 -translate-y-1/4 pointer-events-none animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-full md:w-[50%] h-[80%] bg-indigo-900/30 rounded-full blur-[120px] translate-x-1/4 translate-y-1/4 pointer-events-none animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none mix-blend-overlay"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            {{-- 2. KOLOM TEKS & DATA (KIRI) --}}
            <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0 w-full" 
                 data-aos="fade-right" 
                 data-aos-duration="1000">
                
                {{-- Badge Status (PERBAIKAN: whitespace-normal agar bisa turun baris di HP) --}}
                <div class="inline-flex items-center justify-center lg:justify-start gap-2 px-3 sm:px-4 py-1.5 rounded-3xl sm:rounded-full bg-white/10 border border-white/20 text-cyan-100 text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-md shadow-sm max-w-full text-center">
                    <span class="relative flex h-2 w-2 shrink-0">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-300 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                    </span>
                    <span class="whitespace-normal leading-tight">Sistem Informasi Akademik Terpadu</span>
                </div>

                {{-- Judul Utama (PERBAIKAN: break-words agar teks panjang tidak menjebol lebar layar) --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-black text-white tracking-tight leading-[1.1] mb-6 px-1 sm:px-0 break-words w-full">
                    Membangun Generasi <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-200">
                        Cerdas & Berkarakter
                    </span>
                </h1>
                
                {{-- Sub-judul --}}
                <p class="text-sm sm:text-base md:text-lg text-blue-50 mb-8 leading-relaxed max-w-xl mx-auto lg:mx-0 font-medium px-2 sm:px-0 opacity-90 break-words w-full">
                    SIMADU : Platform digital terintegrasi SMPN 3 Lakbok untuk pemantauan akademik, absensi kehadiran, dan pengembangan karakter siswa secara real-time.
                </p>

                {{-- Tombol Aksi (PERBAIKAN: Padding & teks dikurangi sedikit di HP agar tombol tidak mendesak layar ke kanan) --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-12 w-full mx-auto px-2 sm:px-0">
                    <a href="{{ route('ppdb.create') }}" class="group relative px-4 sm:px-6 py-3 sm:py-4 rounded-full bg-white text-blue-700 font-bold text-xs sm:text-sm shadow-[0_10px_20px_rgba(0,0,0,0.15)] hover:bg-slate-50 hover:-translate-y-1 transition-all overflow-hidden w-full sm:w-auto flex justify-center items-center gap-2">
                        <i class="ph-bold ph-student text-lg sm:text-xl"></i> Daftar PPDB 2025
                    </a>
                    <a href="{{ route('ppdb.check') }}" class="px-4 sm:px-6 py-3 sm:py-4 rounded-full bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold text-xs sm:text-sm shadow-sm hover:bg-white/20 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                        <i class="ph-bold ph-magnifying-glass text-lg sm:text-xl"></i> Cek Kelulusan
                    </a>
                </div>

                {{-- Quick Stats --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 w-full max-w-sm sm:max-w-md mx-auto lg:mx-0 px-2 sm:px-0">
                    <div class="col-span-2 sm:col-span-1 bg-white/90 backdrop-blur-md border border-white/50 p-4 sm:p-5 rounded-2xl shadow-lg hover:shadow-xl transition-all group flex flex-row sm:flex-col items-center justify-between sm:justify-center">
                        <div class="text-left sm:text-center">
                            <div class="text-3xl md:text-4xl font-black text-emerald-500 mb-0.5 group-hover:scale-110 transition-transform origin-left sm:origin-center">{{ $stats['hadir'] ?? 0 }}</div>
                        </div>
                        <div class="text-[11px] md:text-xs uppercase font-bold text-slate-500 tracking-wider text-right sm:text-center">Hadir Hari Ini</div>
                    </div>
                    
                    <div class="col-span-1 bg-white/90 backdrop-blur-md border border-white/50 p-4 sm:p-5 rounded-2xl shadow-lg hover:shadow-xl transition-all group flex flex-col items-center justify-center text-center">
                        <div class="text-2xl md:text-3xl font-black text-amber-500 mb-1 group-hover:scale-110 transition-transform origin-center">{{ $stats['terlambat'] ?? 0 }}</div>
                        <div class="text-[10px] md:text-[11px] uppercase font-bold text-slate-500 tracking-wider">Terlambat</div>
                    </div>
                    
                    <div class="col-span-1 bg-white/90 backdrop-blur-md border border-white/50 p-4 sm:p-5 rounded-2xl shadow-lg hover:shadow-xl transition-all group flex flex-col items-center justify-center text-center">
                        <div class="text-2xl md:text-3xl font-black text-rose-500 mb-1 group-hover:scale-110 transition-transform origin-center">{{ $stats['tidak_hadir'] ?? 0 }}</div>
                        <div class="text-[10px] md:text-[11px] uppercase font-bold text-slate-500 tracking-wider">Absen</div>
                    </div>
                </div>
            </div>

            {{-- 3. KOLOM GRAFIK & SHAPES (KANAN) --}}
            <div class="relative w-full max-w-lg mx-auto lg:ml-auto mt-16 lg:mt-0 px-2 sm:px-0"
                 data-aos="fade-left" 
                 data-aos-duration="1000" 
                 data-aos-delay="200">
                
                <div class="relative w-full h-[420px] sm:h-[450px] lg:h-[480px]">
                    
                    {{-- OVERLAPPING SHAPES --}}
                    <div class="absolute -top-6 -left-4 sm:-left-6 w-32 h-40 sm:w-40 sm:h-48 bg-blue-900/80 backdrop-blur rounded-[2rem] shadow-2xl transform -rotate-6 z-0 animate-[wiggle_8s_ease-in-out_infinite]"></div>
                    <div class="absolute -top-10 -right-2 sm:-right-8 w-40 h-40 sm:w-56 sm:h-56 bg-orange-200 rounded-[2.5rem] shadow-xl transform rotate-6 z-0"></div>

                    {{-- KARTU GRAFIK UTAMA --}}
                    <div class="relative z-10 w-full h-full rounded-[2.5rem] overflow-hidden border-[6px] border-white/80 shadow-2xl bg-white/95 backdrop-blur-xl flex flex-col p-4 sm:p-6 lg:p-8 transform hover:scale-[1.02] transition duration-500">
                        
                        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3 sm:pb-4 shrink-0">
                            <h3 class="font-bold text-sm sm:text-base lg:text-lg text-slate-800 flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="ph-fill ph-chart-bar text-base sm:text-xl"></i>
                                </div>
                                <span class="truncate">Statistik Kehadiran</span>
                            </h3>
                            <span class="text-[8px] sm:text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-full border border-emerald-100 flex items-center gap-1 sm:gap-1.5 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                            </span>
                        </div>
                        
                        <!-- PERBAIKAN: Gunakan absolute layouting untuk Chart.js agar canvas tidak memaksa lebar pembungkus flexbox -->
                        <div class="relative flex-1 w-full min-h-[150px] sm:min-h-[200px]">
                             <canvas id="publicWeeklyChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                    </div>

                    {{-- SHAPE KIRI BAWAH --}}
                    <div class="absolute -bottom-8 -left-2 sm:-left-4 w-24 h-32 sm:w-32 sm:h-40 bg-white/95 backdrop-blur-md border border-white rounded-[1.5rem] shadow-2xl z-20 flex flex-col items-center justify-center gap-2 transform -rotate-3 hover:rotate-0 transition-transform cursor-default">
                        <div class="bg-blue-100 text-blue-600 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center mb-1">
                            <i class="ph-bold ph-shield-check text-xl sm:text-2xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-600 text-center px-2 leading-tight">Sistem<br>Terintegrasi</span>
                    </div>

                </div>
            </div>

        </div>
    </div>
    
    <!-- Wave Separator -->
    <div class="absolute bottom-0 left-0 right-0 pointer-events-none translate-y-[1px] z-20">
         <svg class="w-full h-16 lg:h-24 text-slate-50 fill-current" viewBox="0 0 1440 320" preserveAspectRatio="none">
             <path d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,197.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
         </svg>
    </div>
</section>