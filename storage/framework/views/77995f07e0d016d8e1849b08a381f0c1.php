<!-- ARTIKEL & OPINI GURU -->
<section id="artikel" class="py-20 relative bg-blue-50">
    <!-- Dekorasi Background -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 translate-y-1/2 -translate-x-1/2"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4">
                <i class="ph-bold ph-pen-nib"></i> Pojok Literasi
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-slate-800 mb-4 tracking-tight">Artikel & Opini Guru</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">Kumpulan tulisan, gagasan, dan opini inspiratif dari tenaga pendidik SMP Negeri 3 Lakbok.</p>
        </div>

        <!-- Grid Artikel (Menampilkan 3 Artikel Terbaru) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php $__empty_1 = true; $__currentLoopData = $latestArticles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-200/40 overflow-hidden group hover:-translate-y-2 transition-transform duration-500 flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                    
                    <!-- Thumbnail Artikel -->
                    <div class="relative h-56 bg-slate-200 overflow-hidden shrink-0">
                        <?php if($article->image_path): ?>
                            <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" alt="<?php echo e($article->title); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-amber-100">
                                <i class="ph-duotone ph-article text-6xl text-orange-300"></i>
                            </div>
                        <?php endif; ?>
                        <!-- Kategori Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-orange-600 text-xs font-black uppercase tracking-wider rounded-lg shadow-sm">
                                <?php echo e($article->category ?? 'Pendidikan'); ?>

                            </span>
                        </div>
                    </div>

                    <!-- Konten Artikel -->
                    <div class="p-6 md:p-8 flex flex-col flex-1">
                        <!-- Info Penulis & Tanggal -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-100 overflow-hidden border border-slate-200 shrink-0">
                                    <img src="<?php echo e($article->user->photo_path ? asset('storage/' . $article->user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($article->user->name).'&background=random'); ?>" alt="Penulis" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-bold text-slate-700 line-clamp-1"><?php echo e($article->user->name); ?></span>
                            </div>
                            <span class="text-xs text-slate-400 font-medium flex items-center gap-1 shrink-0">
                                <i class="ph-bold ph-calendar-blank"></i> <?php echo e(\Carbon\Carbon::parse($article->published_at)->format('d M Y')); ?>

                            </span>
                        </div>

                        <!-- Judul & Excerpt -->
                        <a href="<?php echo e($article->url ?? '#'); ?>" target="_blank" class="block group-hover:text-orange-600 transition-colors">
                            <h3 class="text-xl font-black text-slate-800 mb-3 leading-tight line-clamp-2"><?php echo e($article->title); ?></h3>
                        </a>
                        <p class="text-sm text-slate-500 line-clamp-3 mb-6 flex-1"><?php echo e($article->excerpt); ?></p>

                        <!-- Tombol Baca -->
                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <a href="<?php echo e($article->url ?? route('teachers.show', $article->user_id)); ?>" target="<?php echo e($article->url ? '_blank' : '_self'); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 group/link">
                                Baca Selengkapnya 
                                <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-1 md:col-span-3 text-center py-16 px-4 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 shadow-sm" data-aos="fade-up">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 text-slate-300 mb-4">
                        <i class="ph-duotone ph-pen-nib text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Artikel</h3>
                    <p class="text-sm text-slate-500">Guru-guru kami sedang menyiapkan tulisan-tulisan inspiratif untuk Anda.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if(isset($latestArticles) && count($latestArticles) > 0): ?>
        <!-- Tombol Lihat Semua (Opsional, jika nanti punya halaman khusus blog) -->
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold rounded-full transition-colors shadow-sm">
                <i class="ph-bold ph-books"></i> Lihat Semua Tulisan
            </a>
        </div>
        <?php endif; ?>
        
    </div>
</section><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/articles.blade.php ENDPATH**/ ?>