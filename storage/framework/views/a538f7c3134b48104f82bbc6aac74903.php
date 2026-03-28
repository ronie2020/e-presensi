 <!-- KEGIATAN SEKOLAH -->
    <div id="kegiatan" class="py-24 bg-white relative overflow-hidden border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
                <div class="max-w-2xl">
                    <span class="text-indigo-600 font-bold tracking-wider text-sm uppercase mb-2 block">Galeri Sekolah</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Aktifitas & Kegiatan Siswa</h2>
                </div>
                <a href="<?php echo e(route('public.activities')); ?>" class="hidden md:inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Lihat Semua Galeri <i class="ph-bold ph-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                        <div class="relative h-60 overflow-hidden">
                            <?php if($activity->image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $activity->image_path)); ?>" 
                                     loading="lazy"
                                     alt="<?php echo e($activity->title); ?>" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400" style="display: none;">
                                    <i class="ph-duotone ph-image-broken text-4xl"></i>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                    <i class="ph-duotone ph-image text-4xl"></i>
                                </div>
                            <?php endif; ?>

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-60"></div>
                            
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    <?php echo e($activity->created_at->format('d M Y')); ?>

                                </span>
                            </div>

                            <?php if($activity->video_url): ?>
                                <div class="absolute top-4 right-4 z-20">
                                    <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-lg flex items-center gap-1 animate-pulse">
                                        <i class="ph-fill ph-play-circle"></i> VIDEO
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                <?php echo e($activity->title); ?>

                            </h4>
                            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-4 flex-1">
                                <?php echo e($activity->description); ?>

                            </p>

                            <?php if($activity->video_url): ?>
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    <a href="<?php echo e($activity->video_url); ?>" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-red-600 hover:text-red-700 transition-colors w-full group/video">
                                        <i class="ph-fill ph-youtube-logo text-xl group-hover/video:scale-110 transition-transform"></i>
                                        <span>Tonton Dokumentasi</span>
                                        <i class="ph-bold ph-arrow-square-out ml-auto opacity-0 group-hover/video:opacity-100 transition-opacity"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-12 text-center text-slate-400">Belum ada aktivitas.</div>
                <?php endif; ?>
            </div>
        </div>
    </div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/activities.blade.php ENDPATH**/ ?>