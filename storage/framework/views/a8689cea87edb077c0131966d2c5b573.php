<!-- EKSTRAKURIKULER -->
    <div id="ekskul" class="py-24 bg-white dark:bg-slate-900 relative overflow-hidden border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <!-- PERBAIKAN: Blobs (bercak) diubah ke warna Cyan dan Blue, badge juga disesuaikan -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-200 dark:bg-cyan-600 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[128px] opacity-30 dark:opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-200 dark:bg-blue-600 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[128px] opacity-30 dark:opacity-20 animate-blob" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1.5 bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 rounded-full text-xs font-bold uppercase tracking-widest border border-cyan-100 dark:border-cyan-500/20 shadow-sm backdrop-blur-sm">
                    Bakat & Minat
                </span>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white sm:text-4xl mt-4 tracking-tight">Ekstrakurikuler</h2>
                <p class="mt-4 text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                    Wadah pengembangan potensi siswa di luar jam pelajaran akademik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <!-- PERBAIKAN: Gaya hover diubah ke Cyan dan efek glow ditambahkan -->
                    <div class="bg-white dark:bg-slate-800/50 backdrop-blur-md border border-slate-100 dark:border-slate-700/50 p-6 rounded-[2rem] hover:border-cyan-300 dark:hover:border-cyan-500/50 shadow-lg shadow-slate-200/50 dark:shadow-none hover:shadow-2xl dark:hover:shadow-[0_0_30px_rgba(34,211,238,0.1)] transition-all duration-300 group hover:-translate-y-1.5 flex flex-col h-full" data-aos="fade-up">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl flex items-center justify-center text-3xl text-cyan-600 dark:text-cyan-400 shadow-sm group-hover:bg-gradient-to-br group-hover:from-cyan-500 group-hover:to-blue-600 group-hover:border-transparent group-hover:text-white transition-all duration-300 overflow-hidden shrink-0">
                                <?php if(filter_var($ekskul->icon, FILTER_VALIDATE_URL) || preg_match('/\.(jpg|jpeg|png|gif|svg|webp)$/i', $ekskul->icon)): ?>
                                    <img src="<?php echo e(asset($ekskul->icon)); ?>" loading="lazy" alt="<?php echo e($ekskul->name); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="<?php echo e($ekskul->icon ?? 'ph-fill ph-star'); ?>"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white leading-tight line-clamp-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-300 transition-colors"><?php echo e($ekskul->name); ?></h3>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <?php if($lastActivity = $ekskul->attendances->first()): ?>
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                                        </span>
                                        <span class="text-[10px] font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-wide">Aktif</span>
                                    <?php else: ?>
                                        <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">Vakum</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3 mt-auto">
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-3 flex items-center gap-3 border border-slate-100 dark:border-slate-700/30 group-hover:border-cyan-200 dark:group-hover:border-cyan-500/20 transition-colors">
                                <i class="ph-duotone ph-clock text-cyan-500 dark:text-cyan-400 text-lg"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Jadwal</p>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 font-mono truncate"><?php echo e($ekskul->schedule ?? '-'); ?></p>
                                </div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-3 flex items-center gap-3 border border-slate-100 dark:border-slate-700/30 group-hover:border-blue-200 dark:group-hover:border-blue-500/20 transition-colors">
                                <i class="ph-duotone ph-user-circle text-blue-500 dark:text-blue-400 text-lg"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Pembina</p>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 truncate"><?php echo e($ekskul->coach_name ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400">Belum ada data ekstrakurikuler.</div>
                <?php endif; ?>
            </div>
        </div>
    </div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/extracurricular.blade.php ENDPATH**/ ?>