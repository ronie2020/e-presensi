<!-- MONITORING 7 KEBIASAAN SECTION -->
<div id="karakter" class="py-24 relative overflow-hidden transition-colors duration-300">
    
    <!-- Ambient Backgrounds -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[120px] pointer-events-none transition-colors duration-300"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/10 rounded-full blur-[120px] pointer-events-none transition-colors duration-300"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Teks & Statistik Card (Kiri) -->
            <div class="w-full lg:w-5/12" data-aos="fade-right">
                
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-6 border border-elevate-accent/30 shadow-sm transition-colors duration-300">
                    <i class="ph-fill ph-shield-check text-sm"></i> Pendidikan Karakter
                </span>
                
                <h2 class="text-3xl lg:text-5xl font-black text-white tracking-tight mb-6 leading-tight transition-colors duration-300">
                    Monitoring <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-elevate-peach">7 Kebiasaan Baik</span>
                </h2>
                
                <p class="text-slate-300 mb-10 leading-relaxed font-medium transition-colors duration-300">
                    Rekapitulasi harian partisipasi siswa dalam membangun karakter unggul melalui pelaporan jurnal kebiasaan baik secara digital.
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    
                    <!-- Card Sudah Lapor -->
                    <div class="bg-white/5 backdrop-blur-xl p-6 rounded-[2rem] border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:-translate-y-1 hover:border-elevate-accent/30 transition-all duration-300 group flex flex-col items-start hover:bg-white/10">
                        <div class="w-12 h-12 rounded-[1rem] bg-white/10 text-elevate-accent flex items-center justify-center text-2xl mb-4 group-hover:bg-elevate-accent group-hover:text-elevate-dark transition-colors border border-white/20 shadow-sm">
                            <i class="ph-bold ph-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1 transition-colors group-hover:text-elevate-accent">Sudah Lapor</p>
                            <p class="text-3xl font-black text-white transition-colors">
                                {{ $habitStats['submitted'] ?? 0 }} 
                                <span class="text-xs font-bold text-slate-400">Siswa</span>
                            </p>
                        </div>
                    </div>

                    <!-- Card Belum Lapor -->
                    <div class="bg-white/5 backdrop-blur-xl p-6 rounded-[2rem] border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(251,113,133,0.2)] hover:-translate-y-1 hover:border-rose-400/30 transition-all duration-300 group flex flex-col items-start hover:bg-white/10">
                        <div class="w-12 h-12 rounded-[1rem] bg-rose-500/20 text-rose-300 flex items-center justify-center text-2xl mb-4 group-hover:bg-rose-500 group-hover:text-white transition-colors border border-rose-500/30 shadow-sm">
                            <i class="ph-bold ph-clock-countdown"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1 transition-colors group-hover:text-rose-300">Belum Lapor</p>
                            <p class="text-3xl font-black text-white transition-colors">
                                {{ $habitStats['missing'] ?? 0 }} 
                                <span class="text-xs font-bold text-slate-400">Siswa</span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Card Tingkat Partisipasi -->
                    <div class="sm:col-span-2 bg-gradient-to-br from-elevate-accent/20 to-elevate-primary/30 backdrop-blur-2xl p-8 rounded-[2rem] shadow-[0_0_30px_rgba(86,187,241,0.3)] flex items-center justify-between group relative overflow-hidden border border-elevate-accent/30 transition-colors hover:shadow-[0_0_40px_rgba(86,187,241,0.5)]">
                        
                        <!-- Latar belakang card dekoratif -->
                        <div class="absolute top-0 right-0 w-40 h-40 bg-elevate-accent/30 rounded-full blur-3xl -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>

                        <div class="flex items-center gap-5 relative z-10">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl shadow-inner border border-white/30 group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-fill ph-chart-pie-slice"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1">Tingkat Partisipasi</p>
                                <p class="text-4xl font-black text-white">{{ $habitStats['percentage'] ?? 0 }}%</p>
                            </div>
                        </div>
                        <div class="hidden sm:block relative z-10">
                            <i class="ph-bold ph-trend-up text-5xl text-white/30 group-hover:text-white/60 transition-colors"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Tren (Kanan) -->
            <div class="w-full lg:w-7/12" data-aos="fade-left">
                <div class="bg-white/5 backdrop-blur-xl rounded-[2.5rem] shadow-[0_8px_32px_rgba(0,0,0,0.3)] p-6 md:p-8 lg:p-10 border border-white/10 transition-colors relative group hover:border-white/20">
                    
                    <!-- Dekorasi Halus di Card Grafik -->
                    <div class="absolute top-0 right-0 p-6 opacity-[0.05] pointer-events-none group-hover:scale-105 transition-transform duration-500">
                        <i class="ph-fill ph-chart-line-up text-9xl text-white"></i>
                    </div>

                    <div class="flex items-center justify-between mb-8 border-b border-white/10 pb-6 transition-colors relative z-10">
                        <h3 class="font-black text-xl text-white flex items-center gap-3 transition-colors">
                            <div class="w-10 h-10 bg-white/10 text-elevate-accent rounded-[1rem] flex items-center justify-center border border-white/20 shadow-sm transition-colors">
                                <i class="ph-fill ph-activity text-xl"></i>
                            </div>
                            Tren Laporan Mingguan
                        </h3>
                    </div>
                    
                    <!-- ID habitWeeklyChart HARUS tetap dipertahankan agar scripts.blade.php bisa merender grafik -->
                    <div class="h-64 md:h-80 relative z-10 w-full rounded-xl overflow-hidden bg-white/5 backdrop-blur-sm p-4 border border-white/10">
                        <canvas id="habitWeeklyChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>