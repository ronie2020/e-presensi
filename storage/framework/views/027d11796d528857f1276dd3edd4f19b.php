<!-- HERO SECTION (Updated to Match Dark Theme & Responsive Mobile) -->
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
            <div class="lg:w-1/2 w-full text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Sistem Informasi Akademik Terpadu
                </div>
                
                <!-- Menambahkan px-2 di HP agar tidak mepet batas layar -->
                <h1 class="text-4xl lg:text-6xl xl:text-7xl font-black text-white tracking-tight mb-6 leading-[1.1] drop-shadow-lg px-2 sm:px-0">
                    Membangun Generasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-teal-300">Cerdas & Berkarakter</span>
                </h1>
                
                <!-- Teks diperhalus warnanya (text-slate-300 -> text-slate-300/90) & ditambahkan leading-relaxed -->
                <p class="text-slate-300/90 text-base md:text-lg mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium px-5 sm:px-0">
                    SIMADU : Platform digital terintegrasi SMPN 3 Lakbok untuk pemantauan akademik, absensi kehadiran, dan pengembangan karakter siswa secara real-time.
                </p>
                
                <!-- Buttons Area -->
                <!-- Di HP dibikin w-full dan stack ke bawah (flex-col), dikasih padding (px-6) -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start mb-12 w-full max-w-sm sm:max-w-none mx-auto px-6 sm:px-0">
                    <a href="<?php echo e(route('ppdb.create')); ?>" class="group relative px-6 py-4 rounded-full bg-blue-600 text-white font-bold text-sm shadow-[0_0_20px_rgba(37,99,235,0.3)] hover:shadow-[0_0_30px_rgba(37,99,235,0.5)] hover:-translate-y-1 transition-all overflow-hidden w-full sm:w-auto flex justify-center items-center">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shine_1s_infinite]"></div>
                        <span class="relative flex items-center gap-2"><i class="ph-bold ph-student text-xl"></i> Daftar PPDB 2025</span>
                    </a>
                    
                    <!-- Tombol Sekunder diperjelas dengan Border dan Bg yg kontrasnya pas -->
                     <a href="<?php echo e(route('ppdb.check')); ?>" class="px-6 py-4 rounded-full bg-slate-800/80 border border-slate-700 text-slate-200 font-bold text-sm hover:bg-slate-700 hover:text-white hover:-translate-y-1 transition-all flex items-center justify-center gap-2 w-full sm:w-auto shadow-lg">
                        <i class="ph-bold ph-magnifying-glass text-xl"></i> Cek Kelulusan
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <!-- Grid diubah menjadi 2 kolom di HP, 3 kolom di layar besar. Ditambahkan px-6 untuk ruang lega di HP -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 w-full max-w-sm sm:max-w-md mx-auto lg:mx-0 px-6 sm:px-0">
                    
                    <!-- Kotak Hadir: Di HP dia makan 2 kolom penuh (col-span-2) agar teksnya bisa memanjang dan tidak bertumpuk -->
                    <div class="col-span-2 sm:col-span-1 bg-white/5 border border-white/10 p-4 sm:p-5 rounded-2xl hover:bg-white/10 transition group flex flex-row sm:flex-col items-center justify-between sm:justify-center">
                        <div class="text-left sm:text-center">
                            <div class="text-3xl md:text-4xl font-black text-emerald-400 mb-0.5 group-hover:scale-110 transition-transform origin-left sm:origin-center"><?php echo e($stats['hadir'] ?? 0); ?></div>
                        </div>
                        <div class="text-[11px] md:text-xs uppercase font-bold text-slate-400 tracking-wider text-right sm:text-center">Hadir Hari Ini</div>
                    </div>
                    
                    <!-- Kotak Terlambat -->
                    <div class="col-span-1 bg-white/5 border border-white/10 p-4 sm:p-5 rounded-2xl hover:bg-white/10 transition group flex flex-col items-center sm:items-start lg:items-center justify-center text-center sm:text-left lg:text-center">
                        <div class="text-2xl md:text-3xl font-black text-amber-400 mb-1 group-hover:scale-110 transition-transform origin-center"><?php echo e($stats['terlambat'] ?? 0); ?></div>
                        <div class="text-[10px] md:text-[11px] uppercase font-bold text-slate-400 tracking-wider">Terlambat</div>
                    </div>
                    
                    <!-- Kotak Absen -->
                    <div class="col-span-1 bg-white/5 border border-white/10 p-4 sm:p-5 rounded-2xl hover:bg-white/10 transition group flex flex-col items-center sm:items-start lg:items-center justify-center text-center sm:text-left lg:text-center">
                        <div class="text-2xl md:text-3xl font-black text-rose-400 mb-1 group-hover:scale-110 transition-transform origin-center"><?php echo e($stats['tidak_hadir'] ?? 0); ?></div>
                        <div class="text-[10px] md:text-[11px] uppercase font-bold text-slate-400 tracking-wider">Absen</div>
                    </div>
                </div>
            </div>

            <!-- Chart / Visual Content -->
            <!-- Ditambahkan padding px-4 agar grafik tidak menempel ke batas luar HP -->
            <div class="lg:w-1/2 w-full px-4 sm:px-0" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="relative glass-dark rounded-[2.5rem] p-5 sm:p-6 lg:p-8 shadow-2xl transform hover:rotate-1 transition duration-500 border-t border-white/10">
                    <div class="flex items-center justify-between mb-4 border-b border-white/5 pb-4">
                        <h3 class="font-bold text-base sm:text-lg text-white flex items-center gap-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                                <i class="ph-fill ph-chart-bar text-lg sm:text-xl"></i>
                            </div>
                            <span class="truncate">Statistik Kehadiran</span>
                        </h3>
                        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 px-2.5 py-1.5 rounded-full border border-emerald-500/20 flex items-center gap-1.5 shrink-0">
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
    </div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\landing\hero.blade.php ENDPATH**/ ?>