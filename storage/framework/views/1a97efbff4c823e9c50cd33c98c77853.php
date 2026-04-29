<!-- MONITORING 7 KEBIASAAN SECTION -->
<div id="karakter" class="py-24 bg-slate-50 dark:bg-slate-950 relative overflow-hidden border-t border-slate-100 dark:border-slate-900 transition-colors duration-300">
    
    <!-- Elevate Ambient Backgrounds -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-primary/10 dark:bg-elevate-primary/20 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-accent/10 dark:bg-elevate-accent/15 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Teks & Statistik Card (Kiri) -->
            <div class="w-full lg:w-5/12" data-aos="fade-right">
                
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-6 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors duration-300">
                    <i class="ph-fill ph-shield-check text-sm"></i> Pendidikan Karakter
                </span>
                
                <h2 class="text-3xl lg:text-5xl font-black text-elevate-dark dark:text-white tracking-tight mb-6 leading-tight transition-colors duration-300">
                    Monitoring <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-elevate-primary dark:from-elevate-accent dark:to-white">7 Kebiasaan Baik</span>
                </h2>
                
                <p class="text-slate-600 dark:text-slate-400 mb-10 leading-relaxed font-medium transition-colors duration-300">
                    Rekapitulasi harian partisipasi siswa dalam membangun karakter unggul melalui pelaporan jurnal kebiasaan baik secara digital.
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    
                    <!-- Card Sudah Lapor -->
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 hover:-translate-y-1 hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50 transition-all duration-300 group flex flex-col items-start">
                        <div class="w-12 h-12 rounded-[1rem] bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-elevate-accent flex items-center justify-center text-2xl mb-4 group-hover:bg-elevate-primary group-hover:text-white transition-colors border border-elevate-accent/20 dark:border-slate-700 shadow-sm">
                            <i class="ph-bold ph-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1 transition-colors">Sudah Lapor</p>
                            <p class="text-3xl font-black text-elevate-dark dark:text-white transition-colors">
                                <?php echo e($habitStats['submitted'] ?? 0); ?> 
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500">Siswa</span>
                            </p>
                        </div>
                    </div>

                    <!-- Card Belum Lapor (Peringatan diubah ke warna Peach Elevate) -->
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-peach/10 dark:hover:shadow-elevate-peach/5 hover:-translate-y-1 hover:border-elevate-peach/40 dark:hover:border-elevate-peach/40 transition-all duration-300 group flex flex-col items-start">
                        <div class="w-12 h-12 rounded-[1rem] bg-elevate-peach-light/20 dark:bg-slate-800 text-elevate-peach-dark dark:text-elevate-peach flex items-center justify-center text-2xl mb-4 group-hover:bg-elevate-peach group-hover:text-white transition-colors border border-elevate-peach/30 dark:border-slate-700 shadow-sm">
                            <i class="ph-bold ph-clock-countdown"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1 transition-colors">Belum Lapor</p>
                            <p class="text-3xl font-black text-elevate-dark dark:text-white transition-colors">
                                <?php echo e($habitStats['missing'] ?? 0); ?> 
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500">Siswa</span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Card Tingkat Partisipasi -->
                    <div class="sm:col-span-2 bg-elevate-dark dark:bg-elevate-primary p-8 rounded-[2rem] shadow-xl shadow-elevate-dark/20 dark:shadow-elevate-primary/20 flex items-center justify-between group relative overflow-hidden border border-elevate-primary/30 dark:border-transparent transition-colors">
                        
                        <!-- Latar belakang card dekoratif -->
                        <div class="absolute top-0 right-0 w-40 h-40 bg-elevate-primary/50 dark:bg-elevate-accent/30 rounded-full blur-3xl -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>

                        <div class="flex items-center gap-5 relative z-10">
                            <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl shadow-inner border border-white/20 group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-fill ph-chart-pie-slice"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-1">Tingkat Partisipasi</p>
                                <p class="text-4xl font-black text-white"><?php echo e($habitStats['percentage'] ?? 0); ?>%</p>
                            </div>
                        </div>
                        <div class="hidden sm:block relative z-10">
                            <i class="ph-bold ph-trend-up text-5xl text-white/20 group-hover:text-white/40 transition-colors"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Tren (Kanan) -->
            <div class="w-full lg:w-7/12" data-aos="fade-left">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none p-6 md:p-8 lg:p-10 border border-slate-100 dark:border-slate-800 transition-colors relative group">
                    
                    <!-- Dekorasi Halus di Card Grafik -->
                    <div class="absolute top-0 right-0 p-6 opacity-[0.02] dark:opacity-[0.05] pointer-events-none group-hover:scale-105 transition-transform duration-500">
                        <i class="ph-fill ph-chart-line-up text-9xl text-elevate-primary"></i>
                    </div>

                    <div class="flex items-center justify-between mb-8 border-b border-slate-50 dark:border-slate-800/50 pb-6 transition-colors relative z-10">
                        <h3 class="font-black text-xl text-elevate-dark dark:text-white flex items-center gap-3 transition-colors">
                            <div class="w-10 h-10 bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-elevate-accent rounded-[1rem] flex items-center justify-center border border-elevate-accent/20 dark:border-slate-700 shadow-sm transition-colors">
                                <i class="ph-fill ph-activity text-xl"></i>
                            </div>
                            Tren Laporan Mingguan
                        </h3>
                    </div>
                    
                    <!-- ID habitWeeklyChart HARUS tetap dipertahankan agar scripts.blade.php bisa merender grafik -->
                    <div class="h-64 md:h-80 relative z-10 w-full">
                        <canvas id="habitWeeklyChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/character.blade.php ENDPATH**/ ?>