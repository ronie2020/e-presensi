  <!-- MONITORING 7 KEBIASAAN SECTION -->
    <div class="py-24 bg-white relative overflow-hidden border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                
                <!-- Statistik Card -->
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider mb-6 border border-blue-200">
                        <i class="ph-fill ph-shield-check mr-2"></i> Pendidikan Karakter
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-6">
                        Monitoring <br>
                        <span class="text-blue-600">7 Kebiasaan Baik</span>
                    </h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Rekapitulasi harian partisipasi siswa dalam membangun karakter unggul melalui pelaporan jurnal kebiasaan baik secara digital.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-blue-500/5 transition-all group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors"><i class="ph-bold ph-check-circle"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sudah Lapor</p>
                            </div>
                            <p class="text-3xl font-black text-slate-800"><?php echo e($habitStats['submitted'] ?? 0); ?> <span class="text-xs font-bold text-slate-400">Siswa</span></p>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-amber-500/5 transition-all group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-amber-100 rounded-lg text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors"><i class="ph-bold ph-clock-countdown"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum Lapor</p>
                            </div>
                            <p class="text-3xl font-black text-slate-800"><?php echo e($habitStats['missing'] ?? 0); ?> <span class="text-xs font-bold text-slate-400">Siswa</span></p>
                        </div>

                        <div class="sm:col-span-2 bg-blue-600 p-6 rounded-2xl shadow-lg shadow-blue-500/20 flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-2xl group-hover:rotate-12 transition-transform">
                                    <i class="ph-fill ph-chart-pie-slice"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-blue-100 uppercase tracking-widest">Tingkat Partisipasi</p>
                                    <p class="text-3xl font-black text-white"><?php echo e($habitStats['percentage'] ?? 0); ?>%</p>
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
                    <div class="bg-slate-900 rounded-[2.5rem] shadow-2xl p-6 lg:p-10 border border-slate-800">
                        <div class="flex items-center justify-between mb-8 border-b border-slate-800 pb-6">
                            <h3 class="font-bold text-lg text-white flex items-center gap-3">
                                <div class="p-2 bg-blue-500/10 rounded-lg text-blue-400 border border-blue-500/20">
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
    </div> <?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/character.blade.php ENDPATH**/ ?>