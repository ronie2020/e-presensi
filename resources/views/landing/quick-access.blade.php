<!-- QUICK ACCESS & FEATURED SERVICES (Konsep Mockup E-Learning Modern) -->
<div id="layanan" class="py-24 relative z-20 overflow-hidden transition-colors duration-300">
    
    <!-- Ambient Ornaments (Elevate Brand Glows) -->
    <div class="absolute top-10 left-10 w-96 h-96 bg-elevate-accent/10 rounded-full blur-[140px] pointer-events-none transition-colors duration-300 animate-blob"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-elevate-primary/15 rounded-full blur-[140px] pointer-events-none transition-colors duration-300 animate-blob" style="animation-delay: 2s;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6" data-aos="fade-up">
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-3 border border-elevate-accent/30 shadow-sm">
                    <i class="ph-fill ph-sparkle text-sm"></i> Layanan Unggulan Terpadu
                </span>
                <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight">
                    Portal Utama <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-blue-300">&amp; E-Learning</span>
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-300 max-w-xl font-medium">
                    Akses langsung ke seluruh fasilitas digital interaktif sekolah dalam satu pintu gerbang resmi.
                </p>
            </div>
            
            <!-- Link Cepat Masuk -->
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold transition-all shadow-sm w-fit backdrop-blur-md">
                <i class="ph-bold ph-sign-in text-elevate-accent"></i>
                <span>Masuk Akun Sekarang</span>
            </a>
        </div>
        
        <!-- BAGIAN UTAMA: 3 KARTU UNGGULAN ("FEATURED COURSE" STYLE) + 1 WIDGET INTERAKTIF ("TESTIMONIAL" STYLE) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 mb-16 items-stretch">
            
            {{-- KARTU 1: RUANG BELAJAR (LMS) --}}
            <div class="lg:col-span-3 rounded-[2.5rem] bg-[#031d3d]/85 backdrop-blur-2xl border border-white/20 p-5 shadow-[0_12px_40px_rgba(0,0,0,0.4)] flex flex-col justify-between group hover:-translate-y-1.5 transition-all duration-300 overflow-hidden relative" data-aos="fade-up" data-aos-delay="50">
                <div class="absolute -top-12 -right-12 w-28 h-28 bg-elevate-accent/20 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition-transform"></div>
                <div>
                    <!-- Banner Header Gambar -->
                    <div class="w-full h-36 rounded-2xl bg-gradient-to-tr from-[#021124] to-[#0d52a1] p-4 flex flex-col justify-between relative overflow-hidden border border-white/10 mb-4">
                        <div class="flex justify-between items-center z-10">
                            <span class="px-2.5 py-1 rounded-full bg-elevate-accent/20 border border-elevate-accent/30 text-elevate-accent text-[9px] font-black uppercase tracking-wider backdrop-blur-sm">LMS Digital</span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-white text-sm"><i class="ph-bold ph-book-open"></i></span>
                        </div>
                        <div class="relative z-10">
                            <span class="text-xs font-black text-white">Modul &amp; Tugas</span>
                            <p class="text-[10px] text-slate-300">Belajar fleksibel kapan saja</p>
                        </div>
                    </div>

                    <h3 class="text-base font-black text-white mb-1.5 group-hover:text-elevate-accent transition-colors">Ruang Belajar (LMS)</h3>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium mb-3">Materi interaktif kurikulum merdeka, video pembelajaran, dan pengumpulan tugas online.</p>
                    
                    <!-- Rating 5 Bintang -->
                    <div class="flex items-center gap-1 text-amber-400 text-xs mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <span class="text-slate-300 text-[11px] font-bold ml-1.5">5.0 (Aktif)</span>
                    </div>
                </div>

                <a href="{{ route('student.login.learning') }}" class="w-full py-2.5 px-4 rounded-xl bg-elevate-accent hover:bg-[#72cbfa] text-elevate-dark font-black text-xs uppercase tracking-wider text-center transition-all shadow-md shadow-elevate-accent/20 flex items-center justify-center gap-1.5">
                    <span>Buka Ruang Belajar</span> <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            {{-- KARTU 2: RUANG UJIAN (CBT) --}}
            <div class="lg:col-span-3 rounded-[2.5rem] bg-[#031d3d]/85 backdrop-blur-2xl border border-white/20 p-5 shadow-[0_12px_40px_rgba(0,0,0,0.4)] flex flex-col justify-between group hover:-translate-y-1.5 transition-all duration-300 overflow-hidden relative" data-aos="fade-up" data-aos-delay="100">
                <div class="absolute -top-12 -right-12 w-28 h-28 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition-transform"></div>
                <div>
                    <!-- Banner Header Gambar -->
                    <div class="w-full h-36 rounded-2xl bg-gradient-to-tr from-[#021124] to-[#4338ca] p-4 flex flex-col justify-between relative overflow-hidden border border-white/10 mb-4">
                        <div class="flex justify-between items-center z-10">
                            <span class="px-2.5 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-[9px] font-black uppercase tracking-wider backdrop-blur-sm">CBT Online</span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-white text-sm"><i class="ph-bold ph-monitor-play"></i></span>
                        </div>
                        <div class="relative z-10">
                            <span class="text-xs font-black text-white">Asesmen &amp; Ujian</span>
                            <p class="text-[10px] text-slate-300">Sistem ujian aman anti-curang</p>
                        </div>
                    </div>

                    <h3 class="text-base font-black text-white mb-1.5 group-hover:text-sky-300 transition-colors">Ujian Berbasis Komputer</h3>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium mb-3">Penilaian Harian, PTS, dan PAS berbasis komputer online dengan koreksi nilai otomatis.</p>
                    
                    <!-- Rating 5 Bintang -->
                    <div class="flex items-center gap-1 text-amber-400 text-xs mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <span class="text-slate-300 text-[11px] font-bold ml-1.5">4.9 (Teruji)</span>
                    </div>
                </div>

                <a href="{{ route('student.login.cbt') }}" class="w-full py-2.5 px-4 rounded-xl bg-elevate-accent hover:bg-[#72cbfa] text-elevate-dark font-black text-xs uppercase tracking-wider text-center transition-all shadow-md shadow-elevate-accent/20 flex items-center justify-center gap-1.5">
                    <span>Mulai Ujian CBT</span> <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            {{-- KARTU 3: PORTAL SISWA & PRESTASI --}}
            <div class="lg:col-span-3 rounded-[2.5rem] bg-[#031d3d]/85 backdrop-blur-2xl border border-white/20 p-5 shadow-[0_12px_40px_rgba(0,0,0,0.4)] flex flex-col justify-between group hover:-translate-y-1.5 transition-all duration-300 overflow-hidden relative" data-aos="fade-up" data-aos-delay="150">
                <div class="absolute -top-12 -right-12 w-28 h-28 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition-transform"></div>
                <div>
                    <!-- Banner Header Gambar -->
                    <div class="w-full h-36 rounded-2xl bg-gradient-to-tr from-[#021124] to-[#047857] p-4 flex flex-col justify-between relative overflow-hidden border border-white/10 mb-4">
                        <div class="flex justify-between items-center z-10">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[9px] font-black uppercase tracking-wider backdrop-blur-sm">Portal Siswa</span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-white text-sm"><i class="ph-bold ph-identification-card"></i></span>
                        </div>
                        <div class="relative z-10">
                            <span class="text-xs font-black text-white">Akademik &amp; Rapor</span>
                            <p class="text-[10px] text-slate-300">Data nilai &amp; jadwal belajar</p>
                        </div>
                    </div>

                    <h3 class="text-base font-black text-white mb-1.5 group-hover:text-emerald-300 transition-colors">Portal Siswa &amp; Rapor</h3>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium mb-3">Lihat riwayat kehadiran GPS, jadwal pelajaran harian, pengumuman kelas, dan unduh rapor.</p>
                    
                    <!-- Rating 5 Bintang -->
                    <div class="flex items-center gap-1 text-amber-400 text-xs mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <span class="text-slate-300 text-[11px] font-bold ml-1.5">5.0 (Resmi)</span>
                    </div>
                </div>

                <a href="{{ route('portal.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-elevate-accent hover:bg-[#72cbfa] text-elevate-dark font-black text-xs uppercase tracking-wider text-center transition-all shadow-md shadow-elevate-accent/20 flex items-center justify-center gap-1.5">
                    <span>Akses Portal</span> <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            {{-- KARTU 4: WIDGET INTERAKTIF RINGKASAN ("TESTIMONIAL CARD" STYLE DI MOCKUP) --}}
            <div class="lg:col-span-3 rounded-[2.5rem] bg-[#031d3d]/95 backdrop-blur-2xl border border-white/20 p-6 shadow-[0_12px_45px_rgba(0,0,0,0.5)] flex flex-col justify-between relative overflow-hidden group" data-aos="fade-up" data-aos-delay="200">
                <!-- Glowing Cyan Accent Corner -->
                <div class="absolute -top-10 -right-10 w-36 h-36 bg-elevate-accent/25 rounded-full blur-2xl pointer-events-none"></div>

                <div>
                    <!-- Header Widget -->
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/10">
                        <span class="text-xs font-black uppercase tracking-wider text-white flex items-center gap-2">
                            <i class="ph-fill ph-shield-check text-elevate-accent text-base"></i> Sistem Terpadu
                        </span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    </div>

                    <!-- Row Ikon Bubble Mini (Persis Mockup) -->
                    <div class="flex items-center justify-between gap-1 mb-6 px-1">
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-elevate-accent text-xs">
                            <i class="ph-bold ph-grid-four"></i>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-sky-300 text-xs">
                            <i class="ph-bold ph-envelope-simple"></i>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-emerald-300 text-xs">
                            <i class="ph-bold ph-chart-bar"></i>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-amber-300 text-xs">
                            <i class="ph-bold ph-gear-six"></i>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-purple-300 text-xs">
                            <i class="ph-bold ph-arrows-left-right"></i>
                        </div>
                    </div>

                    <!-- Glowing Progress Slider (Persis Mockup) -->
                    <div class="relative w-full h-1.5 bg-white/15 rounded-full mb-6">
                        <div class="absolute left-0 top-0 h-full w-2/3 bg-gradient-to-r from-sky-400 to-elevate-accent rounded-full"></div>
                        <div class="absolute left-2/3 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 rounded-full bg-elevate-accent shadow-[0_0_12px_#56bbf1] border-2 border-white"></div>
                    </div>

                    <p class="text-xs text-slate-300 leading-relaxed font-medium mb-3">
                        Seluruh sistem e-presensi, absensi GPS, dan materi e-learning tersinkronisasi otomatis dengan database induk sekolah.
                    </p>
                </div>

                <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-300">
                        <i class="ph-fill ph-check-circle text-emerald-400"></i>
                        <span>Online 24 Jam</span>
                    </div>
                    <a href="{{ route('kiosk.show') }}" class="px-3 py-1.5 rounded-lg bg-elevate-accent hover:bg-[#72cbfa] text-elevate-dark font-black text-[11px] tracking-wide transition-all shadow-sm">
                        Kiosk Absensi
                    </a>
                </div>
            </div>

        </div>

        <!-- 4 TITIK INDIKATOR PAGINASI (Persis Mockup Di Bawah Bagian Course) -->
        <div class="flex items-center justify-center gap-2 mb-16" data-aos="fade-up">
            <span class="w-7 h-2 rounded-full bg-elevate-accent shadow-[0_0_10px_rgba(86,187,241,0.8)]"></span>
            <span class="w-2 h-2 rounded-full bg-white/25"></span>
            <span class="w-2 h-2 rounded-full bg-white/25"></span>
            <span class="w-2 h-2 rounded-full bg-white/25"></span>
        </div>
        
        <!-- SUB-SECTION: LAYANAN OPERASIONAL & KESISWAAN LAINNYA -->
        <div class="text-center mb-10" data-aos="fade-up">
            <h3 class="text-xl md:text-2xl font-black text-white tracking-tight">
                Layanan Operasional &amp; Administrasi
            </h3>
            <p class="text-xs sm:text-sm text-slate-300 mt-1">Pilihan menu administrasi, pendaftaran, dan informasi sekolah.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5 md:gap-4">
            @php
                $secondaryMenus = [
                    ['icon' => 'ph-student', 'color' => 'blue', 'title' => 'PPDB Online', 'desc' => 'Daftar Siswa Baru', 'link' => route('ppdb.create')],
                    ['icon' => 'ph-graduation-cap', 'color' => 'purple', 'title' => 'Kelulusan', 'desc' => 'Cek Status Kelas IX', 'link' => route('graduation.index')],
                    ['icon' => 'ph-books', 'color' => 'purple', 'title' => 'E-Library', 'desc' => 'Perpustakaan Digital', 'link' => route('library.kiosk.index')],
                    ['icon' => 'ph-megaphone', 'color' => 'rose', 'title' => 'Pengaduan', 'desc' => 'Suara Siswa', 'link' => route('student.complaints.index')],
                    ['icon' => 'ph-presentation-chart', 'color' => 'sky', 'title' => 'Jurnal Mengajar', 'desc' => 'Administrasi Guru', 'link' => route('teaching.index')],
                    ['icon' => 'ph-broadcast', 'color' => 'amber', 'title' => 'Display Jadwal', 'desc' => 'Bel & Jadwal Live', 'link' => route('display.schedules.show')],
                ];
            @endphp

            @foreach($secondaryMenus as $menu)
                <a href="{{ $menu['link'] }}" 
                   class="relative group bg-white/5 backdrop-blur-xl rounded-2xl p-4 border border-white/10 hover:border-white/25 hover:bg-white/10 hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center overflow-hidden" 
                   data-aos="fade-up" 
                   data-aos-delay="{{ $loop->index * 40 }}">
                    
                    <div class="w-11 h-11 rounded-xl bg-white/10 text-{{ $menu['color'] }}-300 flex items-center justify-center text-2xl mb-2.5 group-hover:scale-110 transition-transform">
                        <i class="ph-duotone {{ $menu['icon'] }}"></i>
                    </div>

                    <h4 class="font-bold text-white text-xs group-hover:text-elevate-accent transition-colors leading-tight mb-1">
                        {{ $menu['title'] }}
                    </h4>
                    <p class="text-[10px] text-slate-300">
                        {{ $menu['desc'] }}
                    </p>
                </a>
            @endforeach
        </div>

    </div>
</div>