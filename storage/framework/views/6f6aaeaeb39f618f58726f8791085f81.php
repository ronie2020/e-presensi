<!-- HERO SECTION (Gaya Microsoft Elevate - Fixed Mobile View) -->
<section id="home" class="relative pt-32 pb-40 md:pt-48 md:pb-40 lg:pt-56 lg:pb-48 overflow-hidden bg-gradient-to-br from-elevate-dark to-elevate-primary block w-full max-w-[100vw]">
    
    
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-full md:w-[60%] h-full bg-elevate-accent/20 rounded-full blur-[100px] -translate-x-1/4 -translate-y-1/4 pointer-events-none animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-full md:w-[50%] h-[80%] bg-elevate-peach/20 rounded-full blur-[120px] translate-x-1/4 translate-y-1/4 pointer-events-none animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none mix-blend-overlay"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-8 items-center">
            
            
            <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0 w-full min-w-0" 
                 data-aos="fade-right" 
                 data-aos-duration="1000">
                
                
                <div class="inline-flex flex-wrap items-center justify-center lg:justify-start gap-2 px-3 py-1.5 rounded-3xl bg-white/10 border border-white/20 text-elevate-surface text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-md shadow-sm w-fit max-w-full mx-auto lg:mx-0">
                    <span class="relative flex h-2 w-2 shrink-0">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-accent"></span>
                    </span>
                    <span class="leading-tight text-center truncate sm:whitespace-normal">Sistem Informasi Akademik Terpadu</span>
                </div>

                
                <h1 class="text-[1.75rem] leading-[1.1] sm:text-5xl lg:text-6xl xl:text-7xl font-black text-white tracking-tight mb-4 sm:mb-6 break-words w-full">
                    Membangun Generasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-white">
                        Cerdas & Berkarakter
                    </span>
                </h1>
                
                
                <p class="text-xs sm:text-base md:text-lg text-slate-100 mb-6 sm:mb-8 leading-relaxed max-w-xl mx-auto lg:mx-0 font-medium opacity-90 break-words w-full">
                    SIMADU : Platform digital terintegrasi SMPN 3 Lakbok untuk pemantauan akademik, absensi kehadiran, dan pengembangan karakter siswa secara real-time.
                </p>

                
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-10 w-full mx-auto sm:px-0">
                    <a href="<?php echo e(route('ppdb.create')); ?>" class="group relative px-4 sm:px-6 py-3.5 rounded-full bg-white text-elevate-primary font-bold text-xs sm:text-sm shadow-lg shadow-white/10 hover:bg-slate-50 hover:-translate-y-1 transition-all overflow-hidden w-full sm:w-auto flex justify-center items-center gap-2">
                        <i class="ph-bold ph-student text-lg sm:text-xl"></i> Daftar PPDB 2025
                    </a>
                    <a href="<?php echo e(route('ppdb.check')); ?>" class="px-4 sm:px-6 py-3.5 rounded-full bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold text-xs sm:text-sm shadow-sm hover:bg-white/20 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                        <i class="ph-bold ph-magnifying-glass text-lg sm:text-xl"></i> Cek Kelulusan
                    </a>
                </div>

                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full max-w-sm sm:max-w-md mx-auto lg:mx-0">
                    <div class="col-span-2 sm:col-span-1 bg-white/10 dark:bg-elevate-dark/80 backdrop-blur-md border border-white/20 dark:border-white/10 p-3 sm:p-5 rounded-2xl shadow-lg flex flex-row sm:flex-col items-center justify-between sm:justify-center min-w-0">
                        <div class="text-3xl md:text-4xl font-black text-emerald-400 mb-0.5"><?php echo e($stats['hadir'] ?? 0); ?></div>
                        <div class="text-[10px] md:text-xs uppercase font-bold text-white/80 tracking-wider text-right sm:text-center truncate w-full">Hadir Hari Ini</div>
                    </div>
                    
                    <div class="col-span-1 bg-white/10 dark:bg-elevate-dark/80 backdrop-blur-md border border-white/20 dark:border-white/10 p-3 sm:p-5 rounded-2xl shadow-lg flex flex-col items-center justify-center text-center min-w-0">
                        <div class="text-2xl md:text-3xl font-black text-amber-400 mb-1"><?php echo e($stats['terlambat'] ?? 0); ?></div>
                        <div class="text-[9px] sm:text-[10px] md:text-[11px] uppercase font-bold text-white/80 tracking-wider truncate w-full">Terlambat</div>
                    </div>
                    
                    <div class="col-span-1 bg-white/10 dark:bg-elevate-dark/80 backdrop-blur-md border border-white/20 dark:border-white/10 p-3 sm:p-5 rounded-2xl shadow-lg flex flex-col items-center justify-center text-center min-w-0">
                        <div class="text-2xl md:text-3xl font-black text-rose-400 mb-1"><?php echo e($stats['tidak_hadir'] ?? 0); ?></div>
                        <div class="text-[9px] sm:text-[10px] md:text-[11px] uppercase font-bold text-white/80 tracking-wider truncate w-full">Absen</div>
                    </div>
                </div>
            </div>

            
            <div class="relative w-full max-w-lg mx-auto lg:ml-auto mt-12 lg:mt-0 min-w-0"
                 data-aos="fade-left" 
                 data-aos-duration="1000" 
                 data-aos-delay="200">
                
                <div class="relative w-full">
                    <div class="absolute -top-4 -left-2 sm:-top-6 sm:-left-6 w-24 h-32 sm:w-40 sm:h-48 bg-elevate-dark/80 backdrop-blur rounded-[2rem] shadow-2xl transform -rotate-6 z-0 pointer-events-none"></div>
                    <div class="absolute -top-6 -right-2 sm:-top-10 sm:-right-8 w-32 h-32 sm:w-56 sm:h-56 bg-elevate-peach rounded-[2.5rem] shadow-xl transform rotate-6 z-0 pointer-events-none"></div>

                    <div class="relative z-10 w-full rounded-[2rem] overflow-hidden border-[4px] sm:border-[6px] border-white/80 dark:border-elevate-dark/80 shadow-2xl bg-white/95 dark:bg-elevate-dark/95 backdrop-blur-xl flex flex-col p-4 sm:p-6 lg:p-8 transform hover:scale-[1.02] transition duration-500 max-w-full">
                        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-white/10 pb-3 shrink-0 gap-2 relative z-10">
                            <h3 class="font-bold text-sm sm:text-base lg:text-lg text-elevate-dark dark:text-white flex items-center gap-2 min-w-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-elevate-accent/20 flex items-center justify-center text-elevate-primary dark:text-elevate-accent shrink-0">
                                    <i class="ph-fill ph-chart-bar text-base sm:text-xl"></i>
                                </div>
                                <span class="truncate">Statistik Kehadiran</span>
                            </h3>
                            <span class="text-[8px] sm:text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-2 py-1 rounded-full border border-emerald-100 dark:border-emerald-500/30 flex items-center gap-1 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                            </span>
                        </div>
                        
                        <div class="w-full relative h-[220px] sm:h-[280px] z-10">
                             <canvas id="publicWeeklyChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- GELOMBANG (WAVE) SEPARATOR -->
    <!-- fill="currentColor" dan text-slate-50 memastikan warna wave sama dengan background PPDB -->
    <div class="absolute bottom-0 left-0 w-full leading-[0] z-20 pointer-events-none translate-y-[1px]">
        <svg class="block w-full h-[60px] md:h-[120px] text-slate-50 dark:text-slate-950 fill-current" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,197.3C1248,171,1344,149,1392,138.7L1440,128V320H0Z"></path>
        </svg>
    </div>
</section><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/hero.blade.php ENDPATH**/ ?>