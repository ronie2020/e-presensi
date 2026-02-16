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
<?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/hero.blade.php ENDPATH**/ ?>