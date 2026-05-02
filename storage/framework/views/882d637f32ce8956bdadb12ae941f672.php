<!-- KATA MEREKA / BUKU TAMU -->
    <div class="py-20 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Kata Mereka</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2 mb-6">Pesan dan kesan dari pengunjung sekolah kami.</p>
                
                <button @click="guestListModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-sm font-bold hover:border-elevate-accent dark:hover:border-elevate-accent/50 hover:text-elevate-primary dark:hover:text-elevate-accent transition shadow-sm">
                    <i class="ph-bold ph-list-dashes"></i> Lihat Semua Tamu
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $guestbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-elevate-accent/10 dark:bg-elevate-accent/20 flex items-center justify-center text-elevate-primary dark:text-elevate-accent font-bold shrink-0 border border-elevate-accent/20 dark:border-elevate-accent/30">
                                <?php echo e(substr($guest->name, 0, 1)); ?>

                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm line-clamp-1"><?php echo e($guest->name); ?></h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1"><?php echo e($guest->institution); ?></p>
                            </div>
                        </div>
                        <div class="relative flex-1 bg-slate-50 dark:bg-slate-700/50 p-4 rounded-xl">
                            <i class="ph-fill ph-quotes text-elevate-accent/30 dark:text-elevate-accent/20 text-2xl absolute -top-2 -left-1"></i>
                            <p class="text-slate-600 dark:text-slate-300 text-sm italic leading-relaxed relative z-10 pl-2">
                                "<?php echo e(Str::limit($guest->message, 150)); ?>"
                            </p>
                        </div>
                        <div class="mt-3 text-[10px] text-slate-400 text-right font-medium">
                            <?php echo e($guest->created_at->diffForHumans()); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-3 text-center py-12 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-700 mb-4 text-slate-400 shadow-sm">
                            <i class="ph-duotone ph-chats-teardrop text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 dark:text-white">Belum Ada Pesan</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Jadilah pengunjung pertama yang memberikan kesan!</p>
                        <button @click="guestBookModalOpen = true" class="mt-4 px-4 py-2 bg-elevate-primary text-white text-sm font-bold rounded-lg hover:bg-elevate-dark transition shadow-lg shadow-elevate-primary/30">
                            Isi Buku Tamu
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/guestbook.blade.php ENDPATH**/ ?>