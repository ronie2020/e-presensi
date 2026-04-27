<!-- MONITORING 7 KEBIASAAN SECTION -->
    <div class="py-24 bg-white dark:bg-slate-900 relative overflow-hidden border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                
                <!-- Statistik Card -->
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-elevate-accent/10 dark:bg-elevate-accent/20 text-elevate-primary dark:text-elevate-accent text-xs font-bold uppercase tracking-wider mb-6 border border-elevate-accent/20">
                        <i class="ph-fill ph-shield-check mr-2"></i> Pendidikan Karakter
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-elevate-dark dark:text-white tracking-tight mb-6">
                        Monitoring <br>
                        <span class="text-elevate-primary dark:text-elevate-accent">7 Kebiasaan Baik</span>
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 mb-8 leading-relaxed">
                        Rekapitulasi harian partisipasi siswa dalam membangun karakter unggul melalui pelaporan jurnal kebiasaan baik secara digital.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:shadow-elevate-primary/5 transition-all group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-elevate-accent/20 dark:bg-elevate-accent/10 rounded-lg text-elevate-primary dark:text-elevate-accent group-hover:bg-elevate-primary group-hover:text-white transition-colors"><i class="ph-bold ph-check-circle"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Sudah Lapor</p>
                            </div>
                            <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $habitStats['submitted'] ?? 0 }} <span class="text-xs font-bold text-slate-400 dark:text-slate-500">Siswa</span></p>
                        </div>

                        <!-- Belum lapor dipertahankan Amber untuk peringatan UX -->
                        <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:shadow-amber-500/5 transition-all group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-amber-100 dark:bg-amber-900/50 rounded-lg text-amber-600 dark:text-amber-400 group-hover:bg-amber-600 group-hover:text-white transition-colors"><i class="ph-bold ph-clock-countdown"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Belum Lapor</p>
                            </div>
                            <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $habitStats['missing'] ?? 0 }} <span class="text-xs font-bold text-slate-400 dark:text-slate-500">Siswa</span></p>
                        </div>
                        
                        <div class="sm:col-span-2 bg-gradient-to-br from-elevate-accent to-elevate-primary p-6 rounded-2xl shadow-lg shadow-elevate-primary/20 flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-2xl group-hover:rotate-12 transition-transform">
                                    <i class="ph-fill ph-chart-pie-slice"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-white/90 uppercase tracking-widest">Tingkat Partisipasi</p>
                                    <p class="text-3xl font-black text-white">{{ $habitStats['percentage'] ?? 0 }}%</p>
                                </div>
                            </div>
                            <div class="hidden sm:block">
                                <i class="ph-bold ph-trend-up text-4xl text-white/20"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Tren -->
                <div class="w-full lg:w-7/12" data-aos="fade-left">
                    <div class="bg-elevate-dark rounded-[2.5rem] shadow-2xl p-6 lg:p-10 border border-white/10">
                        <div class="flex items-center justify-between mb-8 border-b border-white/10 pb-6">
                            <h3 class="font-bold text-lg text-white flex items-center gap-3">
                                <div class="p-2 bg-elevate-accent/10 rounded-lg text-elevate-accent border border-elevate-accent/20">
                                    <i class="ph-fill ph-activity text-xl"></i>
                                </div>
                                Tren Laporan Mingguan
                            </h3>
                        </div>
                        <div class="h-64 md:h-80 relative">
                            <canvas id="habitWeeklyChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>