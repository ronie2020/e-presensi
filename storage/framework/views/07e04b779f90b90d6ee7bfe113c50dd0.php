<!-- GURU SECTION -->
    <div id="guru" class="py-24 bg-slate-50 dark:bg-slate-900/50 relative transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-full text-xs font-bold uppercase tracking-widest border border-cyan-100 dark:border-cyan-500/20">SDM Unggul</span>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl mt-4">Tenaga Pendidik</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Dibimbing oleh guru-guru profesional yang berdedikasi tinggi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Logika untuk mendecode Role yang berbentuk JSON
                        $displayRole = $teacher->position;
                        if (empty($displayRole)) {
                            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                        }
                    ?>
                    <div class="group relative bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                        <div class="aspect-[3/4] w-full relative overflow-hidden bg-slate-100 dark:bg-slate-700">
                            <?php if($teacher->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $teacher->photo_path)); ?>" loading="lazy" alt="<?php echo e($teacher->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full hidden flex-col items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 text-slate-500 dark:text-slate-400">
                                    <span class="text-6xl font-black opacity-30 select-none uppercase"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-cyan-50 to-blue-100 dark:from-slate-700 dark:to-slate-600 text-cyan-600 dark:text-slate-400">
                                    <span class="text-7xl font-black opacity-20 select-none uppercase group-hover:scale-110 transition-transform"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-5 text-center relative bg-white dark:bg-slate-800 flex-1 flex flex-col justify-end">
                            <div class="absolute -top-4 left-0 right-0 flex justify-center px-4">
                                <span class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-[10px] font-bold uppercase tracking-wider py-1 px-3 rounded-full shadow-lg border-2 border-white dark:border-slate-800 truncate max-w-full" title="<?php echo e($displayRole); ?>">
                                    <?php echo e($displayRole); ?>

                                </span>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors line-clamp-1"><?php echo e($teacher->name); ?></h3>
                            <?php if(!empty($teacher->nip)): ?>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">NIP. <?php echo e($teacher->nip); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-12"><p class="text-slate-500 dark:text-slate-400">Belum ada data tenaga pendidik.</p></div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="<?php echo e(route('teachers.index')); ?>" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full hover:bg-cyan-50 dark:hover:bg-slate-700 hover:text-cyan-600 dark:hover:text-cyan-400 hover:border-cyan-200 dark:hover:border-cyan-500 transition-all shadow-sm hover:shadow-md">Lihat Seluruh Staff <i class="ph-bold ph-arrow-right ml-2"></i></a>
            </div>
        </div>
    </div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/teachers.blade.php ENDPATH**/ ?>