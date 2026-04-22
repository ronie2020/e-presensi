<!-- JADWAL UJIAN CBT -->
<div id="jadwal-ujian" class="py-24 bg-slate-50 dark:bg-slate-900/50 relative overflow-hidden border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
    <!-- Ornamen Background -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 bg-cyan-500/10 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 bg-blue-500/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
            <span class="text-cyan-600 dark:text-cyan-400 font-bold tracking-wider text-xs uppercase mb-3 inline-flex items-center gap-1.5 px-3 py-1 bg-cyan-100/50 dark:bg-cyan-900/30 rounded-full border border-cyan-200 dark:border-cyan-500/20">
                <i class="ph-fill ph-monitor-play text-sm"></i> Info Akademik
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white leading-tight mb-3">Jadwal Ujian Berbasis Komputer (CBT)</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base max-w-2xl mx-auto">Informasi jadwal Penilaian Harian, PTS, dan PAS yang sedang atau akan berlangsung. Silakan login ke Portal Siswa untuk mengikuti ujian.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if(isset($publicExams) && $publicExams->isNotEmpty()): ?>
                <?php $__currentLoopData = $publicExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Menentukan status berdasarkan waktu saat ini
                        $now = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::parse($exam->start_time);
                        $endTime = $exam->end_time ? \Carbon\Carbon::parse($exam->end_time) : null;
                        
                        $isOngoing = $now->greaterThanOrEqualTo($startTime) && ($endTime === null || $now->lessThanOrEqualTo($endTime));
                        
                        $statusLabel = $isOngoing ? 'Sedang Berlangsung' : 'Akan Datang';
                        $statusClass = $isOngoing ? 'bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-500 border-emerald-200 dark:border-emerald-500/20 animate-pulse' : 'bg-amber-50/50 dark:bg-amber-500/10 text-amber-500 border-amber-200 dark:border-amber-500/20';
                        $iconClass = $isOngoing ? 'ph-broadcast' : 'ph-clock-countdown';
                    ?>

                    <!-- Card Ujian -->
                    <div class="bg-white dark:bg-slate-800 rounded-[1.25rem] p-5 shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700 transition-all duration-300 hover:-translate-y-1 group flex flex-col h-full" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                        
                        <!-- Header Status & Icon Kanan -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider border <?php echo e($statusClass); ?>">
                                <i class="ph-fill <?php echo e($iconClass); ?> text-sm"></i> <?php echo e($statusLabel); ?>

                            </span>
                            <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-400 flex items-center justify-center border border-slate-100 dark:border-slate-600 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <i class="ph-duotone ph-exam text-lg"></i>
                            </div>
                        </div>

                        <!-- Info Utama -->
                        <div class="flex-1 mb-5">
                            <!-- BUNGKUSAN BADGE: Mapel & Kelas -->
                            <div class="flex flex-wrap items-center gap-1.5 mb-2">
                                <div class="inline-flex items-center text-[9px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-900/30 px-2 py-1 rounded border border-indigo-100/50 dark:border-indigo-500/20">
                                    <?php echo e($exam->subject_name ?? 'Mata Pelajaran'); ?>

                                </div>
                                <!-- BADGE KELAS -->
                                <div class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-900/30 px-2 py-1 rounded border border-indigo-100/50 dark:border-indigo-500/20">
                                    <i class="ph-fill ph-users-three text-xs"></i> <?php echo e($exam->class_level ?? 'Semua Kelas'); ?>

                                </div>
                            </div>

                            <h3 class="text-lg md:text-xl font-bold text-blue-600 dark:text-cyan-400 mb-3 line-clamp-2 transition-colors">
                                <?php echo e($exam->title); ?>

                            </h3>
                            
                            <!-- Box Tanggal & Waktu (Lebih Compact) -->
                            <div class="bg-slate-50/80 dark:bg-slate-700/50 rounded-xl p-3 border border-slate-100 dark:border-slate-700">
                                <!-- Baris Mulai -->
                                <div class="flex items-center gap-2.5 text-xs mb-2">
                                    <i class="ph-fill ph-play-circle text-emerald-500 text-lg shrink-0"></i>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-slate-400 w-10">Mulai</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300">: <?php echo e($startTime->translatedFormat('d M Y, H:i')); ?></span>
                                    </div>
                                </div>
                                <!-- Baris Akhir -->
                                <div class="flex items-center gap-2.5 text-xs">
                                    <i class="ph-fill ph-stop-circle text-rose-500 text-lg shrink-0"></i>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-slate-400 w-10">Akhir</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300">: <?php echo e($endTime ? $endTime->translatedFormat('d M Y, H:i') : 'Tidak dibatasi'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto">
                            <a href="<?php echo e(route('student.login')); ?>" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-cyan-600 text-white text-xs font-bold hover:bg-cyan-600 dark:hover:bg-cyan-500 transition-colors shadow-md hover:shadow-cyan-500/25 group/btn">
                                <i class="ph-bold ph-lock-key text-sm"></i> Login untuk Mengerjakan
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- State Jika Tidak Ada Ujian -->
                <div class="col-span-full py-12 px-6 bg-white dark:bg-slate-800 rounded-[1.25rem] border border-dashed border-slate-300 dark:border-slate-700 text-center" data-aos="fade-up">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-700 text-slate-300 dark:text-slate-500 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-100 dark:border-slate-600">
                        <i class="ph-duotone ph-coffee text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Belum Ada Jadwal Ujian</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto">Saat ini tidak ada jadwal Penilaian Harian, PTS, atau PAS yang dijadwalkan dalam waktu dekat.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/exams.blade.php ENDPATH**/ ?>