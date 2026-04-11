<!-- KEGIATAN SEKOLAH -->
<div id="kegiatan" class="py-24 bg-white relative overflow-hidden border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="text-indigo-600 font-bold tracking-wider text-sm uppercase mb-2 block flex items-center gap-2">
                    <i class="ph-fill ph-camera text-lg"></i> Galeri Sekolah
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">Aktifitas & Kegiatan Siswa</h2>
            </div>
            
            
            <a href="<?php echo e(route('public.activities')); ?>" class="hidden md:inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition group/btn">
                Lihat Semua Galeri <i class="ph-bold ph-arrow-right ml-2 group-hover/btn:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $rawImage = $activity->image_path;
                    $images = [];

                    if (is_array($rawImage)) {
                        $images = $rawImage;
                    } elseif (is_string($rawImage)) {
                        $decoded = json_decode($rawImage, true);
                        $images = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$rawImage];
                    }
                    
                    $images = array_filter($images);
                    $coverImage = !empty($images) ? array_values($images)[0] : null;
                    $totalImages = count($images);
                ?>

                <!-- Card -->
                <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-2xl hover:shadow-indigo-900/10 hover:-translate-y-2 transition-all duration-500 border border-slate-100 flex flex-col h-full" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                    
                    <!-- Area Gambar (Clickable) -->
                    <a href="<?php echo e(route('public.activities')); ?>" class="relative h-60 overflow-hidden block">
                        
                        <?php if($coverImage): ?>
                            <img src="<?php echo e(asset('storage/' . $coverImage)); ?>" 
                                 loading="lazy"
                                 alt="<?php echo e($activity->title); ?>" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300" style="display: none;">
                                <i class="ph-duotone ph-image-broken text-5xl"></i>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                <i class="ph-duotone ph-image text-5xl"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Efek Hover Overlay Gelap -->
                        <div class="absolute inset-0 bg-indigo-900/0 group-hover:bg-indigo-900/30 transition-all duration-300 z-10 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="bg-white/95 backdrop-blur text-indigo-600 font-bold px-5 py-2.5 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 flex items-center gap-2 text-sm">
                                Buka Galeri <i class="ph-bold ph-arrow-square-out"></i>
                            </span>
                        </div>

                        <!-- Gradient Dasar agar teks putih terbaca -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-80 pointer-events-none"></div>
                        
                        <!-- Badge Tanggal -->
                        <div class="absolute top-4 left-4 z-20">
                            <span class="bg-white/95 backdrop-blur text-slate-800 text-xs font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                <?php echo e($activity->created_at->format('d M Y')); ?>

                            </span>
                        </div>

                        <!-- Badge Indikator Info -->
                        <div class="absolute top-4 right-4 z-20 flex flex-col gap-2 items-end">
                            <?php if($totalImages > 1): ?>
                                <span class="bg-indigo-600/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5 border border-indigo-500/50">
                                    <i class="ph-fill ph-images"></i> +<?php echo e($totalImages - 1); ?> Foto
                                </span>
                            <?php endif; ?>

                            <?php if($activity->video_url): ?>
                                <span class="bg-rose-600/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5 animate-pulse border border-rose-500/50">
                                    <i class="ph-fill ph-play-circle text-sm"></i> VIDEO
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>

                    <!-- Area Teks -->
                    <div class="p-6 flex-1 flex flex-col">
                        <a href="<?php echo e(route('public.activities')); ?>">
                            <h4 class="text-xl font-black text-slate-800 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug">
                                <?php echo e($activity->title); ?>

                            </h4>
                        </a>
                        <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-4 flex-1 font-medium">
                            <?php echo e($activity->description); ?>

                        </p>

                        <!-- Tombol Video Khusus (Hanya muncul jika ada link video) -->
                        <?php if($activity->video_url): ?>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <a href="<?php echo e($activity->video_url); ?>" target="_blank" class="inline-flex items-center justify-between px-4 py-2 bg-rose-50 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-600 hover:text-white transition-colors w-full group/video">
                                    <span class="flex items-center gap-2">
                                        <i class="ph-fill ph-youtube-logo text-xl group-hover/video:scale-110 transition-transform"></i>
                                        Tonton Video
                                    </span>
                                    <i class="ph-bold ph-arrow-right opacity-0 -translate-x-2 group-hover/video:opacity-100 group-hover/video:translate-x-0 transition-all"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-16 text-center animate-enter bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                    <div class="inline-flex bg-white p-5 rounded-full mb-4 text-slate-300 shadow-sm border border-slate-100">
                        <i class="ph-duotone ph-image text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Aktivitas</h3>
                    <p class="text-slate-500 text-sm">Kegiatan terbaru sekolah akan ditampilkan di sini.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="mt-10 text-center md:hidden" data-aos="fade-up">
            <a href="<?php echo e(route('public.activities')); ?>" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-full hover:bg-indigo-100 hover:text-indigo-800 transition-all shadow-sm active:scale-95">
                Lihat Semua Galeri Sekolah <i class="ph-bold ph-arrow-right ml-2"></i>
            </a>
        </div>

    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\landing\activities.blade.php ENDPATH**/ ?>