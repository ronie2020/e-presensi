<!-- HERO SECTION (Konsep E-Learning / Portal Modern Mengacu Mockup) -->
<section id="home" class="relative min-h-screen pt-32 pb-24 md:pt-36 md:pb-28 flex flex-col items-center justify-center overflow-hidden w-full max-w-[100vw]">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full flex-grow flex flex-col justify-center">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center h-full">
            
            {{-- 1. KIRI: HEADLINE, CHECKLIST PILLS, CTA --}}
            <div class="lg:col-span-7 xl:col-span-6 w-full" 
                 data-aos="fade-right" 
                 data-aos-duration="1000">
                
                {{-- Curved Glass Panel --}}
                <div class="relative w-full rounded-[2.5rem] bg-white/5 backdrop-blur-[32px] border border-white/20 p-8 sm:p-12 shadow-[0_8px_32px_rgba(0,0,0,0.3)] overflow-hidden group">
                    
                    {{-- Decorative top-right glow inside the glass --}}
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none transition-transform duration-700 group-hover:scale-150"></div>

                    {{-- Tiny Pill Badge (Persis Konsep Mockup) --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white text-[11px] sm:text-xs font-bold tracking-widest shadow-sm mb-6 backdrop-blur-md">
                        <i class="ph-duotone ph-sparkle text-elevate-accent text-sm"></i>
                        <span class="uppercase">E-Learning &bull; SIMADU Terpadu</span>
                    </div>

                    {{-- Headline Besar --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight mb-5 leading-[1.12]">
                        Membangun.<br>
                        Generasi Cerdas.<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#72cbfa] to-white drop-shadow-md">Berkarakter.</span>
                    </h1>
                    
                    {{-- Sub-judul --}}
                    <p class="text-sm sm:text-base text-slate-200 mb-6 leading-relaxed max-w-lg font-medium">
                        Melangkah ke era digital bersama SMPN 3 Lakbok. Platform terpadu modul belajar interaktif (LMS), ujian CBT online, dan pemantauan presensi GPS real-time.
                    </p>

                    {{-- 4 FITUR CHECKLIST (Persis Sesuai Mockup) --}}
                    <div class="grid grid-cols-2 gap-2.5 mb-8">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Materi & Modul LMS</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Praktik Belajar Digital</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Ujian CBT Terjadwal</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                            <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                            <span>Akses Terpadu Resmi</span>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-wrap items-center gap-3.5 mb-8">
                        {{-- Daftar PPDB --}}
                        <a href="{{ route('ppdb.create') }}" class="group relative px-7 py-3 rounded-full bg-gradient-to-r from-elevate-accent to-elevate-primary text-white font-black text-xs uppercase tracking-wider shadow-[0_0_25px_rgba(86,187,241,0.4)] hover:shadow-[0_0_35px_rgba(86,187,241,0.6)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <span>Daftar PPDB</span> <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        
                        {{-- Buka Ruang Belajar LMS --}}
                        <a href="{{ route('student.login.learning') }}" class="group px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs hover:-translate-y-0.5 transition-all flex items-center gap-2 backdrop-blur-md">
                            <i class="ph-bold ph-books text-elevate-accent text-sm"></i>
                            <span>Ruang Belajar</span>
                        </a>

                        {{-- Cek Kelulusan --}}
                        <a href="{{ route('ppdb.check') }}" class="group px-5 py-3 rounded-full bg-transparent border border-white/20 text-slate-300 hover:text-white font-bold text-xs hover:bg-white/10 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <i class="ph-fill ph-check-circle text-emerald-400"></i>
                            <span>Cek Kelulusan</span>
                        </a>
                    </div>

                    {{-- Mini Badges Info Bawah --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-6 border-t border-white/10">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-elevate-accent shrink-0">
                                <i class="ph-duotone ph-leaf text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-white leading-tight">Ramah Anak</span>
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider">Sekolah Hijau</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-elevate-peach shrink-0">
                                <i class="ph-duotone ph-sparkle text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-white leading-tight">Fasilitas Modern</span>
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider">CBT & Smart Lab</span>
                            </div>
                        </div>
                        <div class="hidden sm:flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-emerald-300 shrink-0">
                                <i class="ph-duotone ph-shield-check text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-white leading-tight">Terakreditasi A</span>
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider">Standar Nasional</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 2. KANAN: GLOWING CIRCULAR PORTAL RING DENGAN 6 ORBITING BADGES & WIDGET CHART --}}
            <div class="lg:col-span-5 xl:col-span-6 w-full flex flex-col items-center justify-center relative"
                 data-aos="fade-left" 
                 data-aos-duration="1000" 
                 data-aos-delay="150">
                
                {{-- PORTAL LINGKARAN BERPENDAR (Persis Mockup Lampiran) --}}
                <div class="relative w-72 h-72 sm:w-88 sm:h-88 xl:w-96 xl:h-96 flex items-center justify-center my-4">
                    
                    {{-- Background Radial Ambiance --}}
                    <div class="absolute inset-0 rounded-full bg-elevate-accent/25 blur-3xl pointer-events-none"></div>

                    {{-- Glowing Circular Portal Ring Utama --}}
                    <div class="relative w-64 h-64 sm:w-80 sm:h-80 rounded-full border-[3.5px] border-elevate-accent shadow-[0_0_55px_rgba(86,187,241,0.65)] ring-8 ring-elevate-accent/20 bg-gradient-to-br from-[#021124] via-[#0d52a1] to-[#2c3f61] overflow-hidden flex items-center justify-center animate-portal-pulse group">
                        
                        {{-- Latar Langit Digital di Dalam Lingkaran --}}
                        <div class="absolute inset-0 bg-radial-at-c from-elevate-accent/30 via-transparent to-black/60 pointer-events-none z-10"></div>

                        {{-- Gambar Karakter / Avatar Siswa (Dapat diganti dengan file gambar apapun di public/images/portal-student.png) --}}
                        <img src="{{ asset('images/digital2.jpg') }}" 
                             alt="Siswa Digital SMPN 3 Lakbok" 
                             class="w-full h-full object-cover object-center relative z-0 transition-transform duration-700 group-hover:scale-105 filter drop-shadow-2xl"
                             onerror="this.onerror=null; this.src='{{ asset('images/netila.jpg') }}';">

                        {{-- Subtle bottom gradient for badge --}}
                        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#021124]/90 via-[#021124]/40 to-transparent z-10 pointer-events-none"></div>
                        <div class="absolute bottom-3 text-center z-20">
                            <span class="text-[9px] font-black uppercase tracking-widest text-elevate-accent bg-[#021124]/80 backdrop-blur-md px-3 py-1 rounded-full border border-elevate-accent/40 shadow-sm">
                                Portal Pembelajaran
                            </span>
                        </div>
                    </div>

                    {{-- 6 BUBBLE BADGES IKONIK MENGORBIT (Persis Mockup Gambar) --}}
                    {{-- Badge 1: Top-Left (LMS Modul Belajar) --}}
                    <div class="absolute top-2 left-2 sm:top-4 sm:left-4 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-gradient-to-tr from-sky-600/90 to-cyan-400/90 p-0.5 shadow-[0_0_20px_rgba(56,189,248,0.7)] animate-float-badge z-20">
                        <div class="w-full h-full rounded-full bg-[#021124]/70 backdrop-blur-md flex items-center justify-center text-white text-lg">
                            <i class="ph-bold ph-books text-cyan-300"></i>
                        </div>
                    </div>

                    {{-- Badge 2: Top-Right (Ujian CBT Online) --}}
                    <div class="absolute top-0 right-6 sm:top-2 sm:right-8 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-tr from-blue-600/90 to-indigo-400/90 p-0.5 shadow-[0_0_25px_rgba(99,102,241,0.7)] animate-float-badge z-20" style="animation-delay: 1s;">
                        <div class="w-full h-full rounded-full bg-[#021124]/70 backdrop-blur-md flex items-center justify-center text-white text-xl">
                            <i class="ph-bold ph-monitor-play text-sky-300"></i>
                        </div>
                    </div>

                    {{-- Badge 3: Mid-Left (Presensi GPS Smart) --}}
                    <div class="absolute top-1/2 -left-3 sm:-left-5 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-tr from-emerald-500/90 to-teal-300/90 p-0.5 shadow-[0_0_20px_rgba(16,185,129,0.7)] animate-float-badge z-20" style="animation-delay: 2s;">
                        <div class="w-full h-full rounded-full bg-[#021124]/70 backdrop-blur-md flex items-center justify-center text-white text-base">
                            <i class="ph-bold ph-map-pin text-emerald-300"></i>
                        </div>
                    </div>

                    {{-- Badge 4: Mid-Right (Grafik Kehadiran Realtime) --}}
                    <div class="absolute top-1/2 -right-3 sm:-right-5 -translate-y-1/2 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-gradient-to-tr from-blue-500/90 to-elevate-accent p-0.5 shadow-[0_0_25px_rgba(86,187,241,0.8)] animate-float-badge z-20" style="animation-delay: 1.5s;">
                        <div class="w-full h-full rounded-full bg-[#021124]/70 backdrop-blur-md flex items-center justify-center text-white text-lg">
                            <i class="ph-bold ph-chart-line-up text-elevate-accent"></i>
                        </div>
                    </div>

                    {{-- Badge 5: Bottom-Left (Perpustakaan Digital) --}}
                    <div class="absolute -bottom-1 left-8 sm:bottom-1 sm:left-10 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-gradient-to-tr from-purple-600/90 to-fuchsia-400/90 p-0.5 shadow-[0_0_20px_rgba(192,132,252,0.7)] animate-float-badge z-20" style="animation-delay: 2.5s;">
                        <div class="w-full h-full rounded-full bg-[#021124]/70 backdrop-blur-md flex items-center justify-center text-white text-lg">
                            <i class="ph-bold ph-book-open-text text-purple-300"></i>
                        </div>
                    </div>

                    {{-- Badge 6: Bottom-Right (Suara Siswa & Pengaduan) --}}
                    <div class="absolute bottom-2 right-4 sm:bottom-4 sm:right-6 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-tr from-amber-500/90 to-orange-400/90 p-0.5 shadow-[0_0_20px_rgba(245,158,11,0.7)] animate-float-badge z-20" style="animation-delay: 3s;">
                        <div class="w-full h-full rounded-full bg-[#021124]/70 backdrop-blur-md flex items-center justify-center text-white text-base">
                            <i class="ph-bold ph-megaphone-simple text-amber-300"></i>
                        </div>
                    </div>

                </div>

                {{-- WIDGET GRAFIK KEHADIRAN DI BAWAH PORTAL (Dibuat Menyatu Halus) --}}
                <div class="w-full max-w-md mx-auto mt-4 hidden sm:block">
                    <div class="relative z-10 w-full rounded-[2rem] bg-[#031d3d]/85 backdrop-blur-2xl border border-white/20 p-5 shadow-[0_12px_40px_rgba(0,0,0,0.5)] flex flex-col">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-white/10 shrink-0 gap-2">
                            <h3 class="font-bold text-xs sm:text-sm text-white flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-elevate-accent/20 flex items-center justify-center text-elevate-accent shrink-0 border border-elevate-accent/30">
                                    <i class="ph-fill ph-chart-bar text-base"></i>
                                </div>
                                Statistik Kehadiran Siswa
                            </h3>
                            <span class="text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 px-2.5 py-0.5 rounded-full border border-emerald-400/30 flex items-center gap-1 shrink-0 backdrop-blur-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Live
                            </span>
                        </div>
                        
                        <div class="w-full relative h-[150px] z-10">
                            <canvas id="publicWeeklyChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- 3. BOTTOM STATS BANNER --}}
        <div class="mt-12 lg:mt-16 w-full" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            <div class="w-full max-w-5xl mx-auto rounded-3xl bg-white/5 backdrop-blur-2xl border border-white/15 p-4 sm:p-6 shadow-[0_8px_32px_rgba(0,0,0,0.3)]">
                <div class="flex flex-wrap sm:flex-nowrap items-center justify-around gap-4 sm:gap-0 sm:divide-x divide-white/10">
                    
                    {{-- Stat 1 --}}
                    <div class="flex items-center gap-3.5 px-4 w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-11 h-11 rounded-2xl border border-white/20 bg-white/5 flex items-center justify-center text-elevate-accent shrink-0 shadow-inner">
                            <i class="ph-duotone ph-users text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $stats['hadir'] ?? '12K+' }}</span>
                            <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest">Hadir Hari Ini</span>
                        </div>
                    </div>
                    
                    {{-- Stat 2 --}}
                    <div class="flex items-center gap-3.5 px-4 w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-11 h-11 rounded-2xl border border-white/20 bg-white/5 flex items-center justify-center text-amber-300 shrink-0 shadow-inner">
                            <i class="ph-duotone ph-clock-countdown text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $stats['terlambat'] ?? '350+' }}</span>
                            <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest">Terlambat</span>
                        </div>
                    </div>

                    {{-- Stat 3 --}}
                    <div class="flex items-center gap-3.5 px-4 w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-11 h-11 rounded-2xl border border-white/20 bg-white/5 flex items-center justify-center text-rose-300 shrink-0 shadow-inner">
                            <i class="ph-duotone ph-x-circle text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $stats['tidak_hadir'] ?? '25K+' }}</span>
                            <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest">Tidak Hadir</span>
                        </div>
                    </div>

                    {{-- Stat 4 --}}
                    <div class="hidden md:flex items-center gap-3.5 px-4 justify-start">
                        <div class="w-11 h-11 rounded-2xl border border-white/20 bg-white/5 flex items-center justify-center text-emerald-300 shrink-0 shadow-inner">
                            <i class="ph-duotone ph-star text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl sm:text-2xl font-black text-white tracking-tight">4.9 / 5.0</span>
                            <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest">Akreditasi A</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- GELOMBANG HALUS (SVG WAVE DIVIDER SEPERTI KONSEP MOCKUP) --}}
    <div class="w-full absolute bottom-0 left-0 overflow-hidden leading-none z-0 pointer-events-none opacity-40">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-12 text-slate-900 fill-current">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,121.31,192,109.52,236.48,100.94,279.7,78.27,321.39,56.44Z"></path>
        </svg>
    </div>
</section>