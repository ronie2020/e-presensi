<!-- JADWAL UJIAN CBT -->
<div id="jadwal-ujian" class="py-24 bg-slate-50 dark:bg-slate-950 relative overflow-hidden border-t border-slate-100 dark:border-slate-900 transition-colors duration-300">
    
    <!-- Elevate Ambient Ornaments -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-accent/20 dark:bg-elevate-accent/10 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[120px] opacity-50 -translate-y-1/3 translate-x-1/3 pointer-events-none transition-colors duration-300"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/15 dark:bg-elevate-primary/10 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[120px] opacity-50 translate-y-1/3 -translate-x-1/3 pointer-events-none transition-colors duration-300"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors duration-300">
                <i class="ph-fill ph-monitor-play text-sm"></i> Info Akademik
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-elevate-dark dark:text-white leading-tight mb-4 transition-colors duration-300">Jadwal Ujian Komputer (CBT)</h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm md:text-base max-w-2xl mx-auto font-medium transition-colors duration-300">Informasi jadwal Penilaian Harian, PTS, dan PAS yang sedang atau akan berlangsung. Silakan login ke Portal Siswa untuk mengikuti ujian.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if(isset($publicExams) && $publicExams->isNotEmpty()): ?>
                <?php $__currentLoopData = $publicExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Menentukan status berdasarkan waktu saat ini
                        $now = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::parse($exam->start_time);
                        $endTime = $exam->end_time ? \Carbon\Carbon::parse($exam->end_time) : null;
                        
                        $isOngoing = $now->greaterThanOrEqualTo($startTime) && ($endTime === null || $now->lessThanOrEqualTo($endTime));
                        
                        $statusLabel = $isOngoing ? 'Sedang Berlangsung' : 'Akan Datang';
                        $statusClass = $isOngoing 
                            ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/30' 
                            : 'bg-elevate-peach-light/20 dark:bg-elevate-peach-dark/20 text-elevate-peach-dark dark:text-elevate-peach border-elevate-peach/30 dark:border-elevate-peach/30';
                        $iconClass = $isOngoing ? 'ph-broadcast animate-pulse' : 'ph-clock-countdown';
                    ?>

                    <!-- Card Ujian (Elevate Style) -->
                    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 border border-slate-100 dark:border-slate-800 transition-all duration-300 hover:-translate-y-2 group flex flex-col h-full hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50 relative overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                        
                        <!-- Latar belakang card dekoratif -->
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-elevate-soft dark:bg-slate-800 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                        <!-- Header Status & Icon Kanan -->
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border shadow-sm transition-colors <?php echo e($statusClass); ?>">
                                <i class="ph-bold <?php echo e($iconClass); ?> text-sm"></i> <?php echo e($statusLabel); ?>

                            </span>
                            <div class="w-12 h-12 rounded-[1rem] bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-slate-400 flex items-center justify-center border border-elevate-accent/20 dark:border-slate-700 group-hover:bg-elevate-primary dark:group-hover:bg-elevate-primary group-hover:text-white transition-colors shadow-sm">
                                <i class="ph-duotone ph-desktop text-2xl"></i>
                            </div>
                        </div>

                        <!-- Info Utama -->
                        <div class="flex-1 flex flex-col mb-2 relative z-10">
                            <!-- BUNGKUSAN BADGE: Mapel & Kelas -->
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <div class="inline-flex items-center text-[9px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 px-2.5 py-1 rounded-lg border border-slate-100 dark:border-slate-700/50 transition-colors">
                                    <?php echo e($exam->subject_name ?? 'Mata Pelajaran'); ?>

                                </div>
                                <!-- BADGE KELAS -->
                                <div class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 px-2.5 py-1 rounded-lg border border-slate-100 dark:border-slate-700/50 transition-colors">
                                    <i class="ph-bold ph-users-three text-xs"></i> <?php echo e($exam->class_level ?? 'Semua Kelas'); ?>

                                </div>
                            </div>

                            <h3 class="text-xl font-black text-elevate-dark dark:text-white mb-6 line-clamp-2 transition-colors group-hover:text-elevate-primary dark:group-hover:text-elevate-accent leading-snug">
                                <?php echo e($exam->title); ?>

                            </h3>
                            
                            <!-- Box Tanggal & Waktu (Elevate Compact) -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-[1.5rem] p-4 border border-slate-100 dark:border-slate-700 mb-6 flex-1 transition-colors">
                                <!-- Baris Mulai -->
                                <div class="flex items-center gap-3 text-xs mb-4">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-500/30">
                                        <i class="ph-bold ph-play"></i>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-slate-400 dark:text-slate-500 text-[9px] uppercase tracking-widest">Mulai Ujian</span>
                                        <span class="font-black text-elevate-dark dark:text-slate-200 text-xs"><?php echo e($startTime->translatedFormat('d M Y, H:i')); ?></span>
                                    </div>
                                </div>
                                <!-- Baris Akhir -->
                                <div class="flex items-center gap-3 text-xs">
                                    <div class="w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-200 dark:border-rose-500/30">
                                        <i class="ph-bold ph-stop"></i>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-slate-400 dark:text-slate-500 text-[9px] uppercase tracking-widest">Berakhir Ujian</span>
                                        <span class="font-black text-elevate-dark dark:text-slate-200 text-xs"><?php echo e($endTime ? $endTime->translatedFormat('d M Y, H:i') : 'Tidak dibatasi'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto relative z-10">
                            <a href="<?php echo e(route('student.login')); ?>" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-xl bg-elevate-dark dark:bg-elevate-primary text-white text-xs font-black hover:bg-elevate-primary dark:hover:bg-elevate-accent dark:hover:text-elevate-dark transition-all shadow-lg shadow-elevate-dark/20 dark:shadow-elevate-primary/20 hover:-translate-y-0.5 group/btn">
                                <i class="ph-bold ph-lock-key text-sm"></i> Login untuk Mengerjakan
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- State Jika Tidak Ada Ujian -->
                <div class="col-span-1 md:col-span-3 py-16 px-6 bg-white dark:bg-slate-800/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-700 text-center transition-colors" data-aos="fade-up">
                    <div class="w-24 h-24 bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-slate-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-elevate-accent/20 dark:border-slate-700 transition-colors shadow-sm">
                        <i class="ph-duotone ph-coffee text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-elevate-dark dark:text-white mb-2 transition-colors">Belum Ada Jadwal Ujian</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto font-medium transition-colors">Saat ini tidak ada jadwal Penilaian Harian, PTS, atau PAS yang dijadwalkan dalam waktu dekat.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/exams.blade.php ENDPATH**/ ?>