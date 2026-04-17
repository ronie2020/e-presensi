    <!-- KATA MEREKA / BUKU TAMU -->
    <div class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-slate-900">Kata Mereka</h2>
                <p class="text-slate-500 mt-2 mb-6">Pesan dan kesan dari pengunjung sekolah kami.</p>
                
                <!-- TOMBOL LIHAT SEMUA TAMU (BARU) -->
                <button @click="guestListModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-bold hover:border-blue-400 hover:text-blue-600 transition shadow-sm">
                    <i class="ph-bold ph-list-dashes"></i> Lihat Semua Tamu
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $guestbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold shrink-0 border border-blue-200">
                                <?php echo e(substr($guest->name, 0, 1)); ?>

                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm line-clamp-1"><?php echo e($guest->name); ?></h4>
                                <p class="text-xs text-slate-500 line-clamp-1"><?php echo e($guest->institution); ?></p>
                            </div>
                        </div>
                        <div class="relative flex-1 bg-slate-50 p-4 rounded-xl">
                            <i class="ph-fill ph-quotes text-blue-200 text-2xl absolute -top-2 -left-1"></i>
                            <p class="text-slate-600 text-sm italic leading-relaxed relative z-10 pl-2">
                                "<?php echo e(Str::limit($guest->message, 150)); ?>"
                            </p>
                        </div>
                        <div class="mt-3 text-[10px] text-slate-400 text-right font-medium">
                            <?php echo e($guest->created_at->diffForHumans()); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 text-slate-400 shadow-sm">
                            <i class="ph-duotone ph-chats-teardrop text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Belum Ada Pesan</h3>
                        <p class="text-slate-500 text-sm mt-1">Jadilah pengunjung pertama yang memberikan kesan!</p>
                        <button @click="guestBookModalOpen = true" class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            Isi Buku Tamu
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/guestbook.blade.php ENDPATH**/ ?>