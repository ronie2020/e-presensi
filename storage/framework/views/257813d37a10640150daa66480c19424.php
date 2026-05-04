<!-- ARTIKEL & OPINI GURU -->
<section id="artikel" class="py-24 bg-slate-50 dark:bg-slate-950 relative overflow-hidden border-t border-slate-100 dark:border-slate-900 transition-colors duration-300">
    
    <!-- Elevate Ambient Backgrounds -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-accent/20 dark:bg-elevate-accent/10 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[120px] opacity-50 -translate-y-1/2 translate-x-1/4 transition-colors duration-300 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/15 dark:bg-elevate-primary/10 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[120px] opacity-50 translate-y-1/3 -translate-x-1/4 transition-colors duration-300 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors duration-300">
                <i class="ph-fill ph-pen-nib text-sm"></i> Pojok Literasi
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-elevate-dark dark:text-white mb-4 tracking-tight transition-colors duration-300">Artikel & Opini Guru</h2>
            <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto text-sm md:text-base font-medium transition-colors duration-300">Kumpulan tulisan, gagasan, dan opini inspiratif dari tenaga pendidik SMP Negeri 3 Lakbok.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $latestArticles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <!-- Elevate Card -->
                <article class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-2 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 overflow-hidden group hover:-translate-y-2 hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50 transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                    
                    
                    <div class="relative h-56 sm:h-64 rounded-[2rem] bg-slate-200 dark:bg-slate-800 overflow-hidden shrink-0">
                        <?php if($article->image_path): ?>
                            <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($article->title)); ?>&background=e5eff5&color=0d52a1&size=500';" alt="<?php echo e($article->title); ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-elevate-soft dark:bg-slate-800 transition-colors"><i class="ph-duotone ph-article text-6xl text-elevate-primary/30 dark:text-slate-600"></i></div>
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        
                        
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3 py-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md text-elevate-primary dark:text-elevate-accent text-[9px] font-black uppercase tracking-widest rounded-xl shadow-sm border border-white/50 dark:border-slate-700/50 transition-colors"><?php echo e($article->category ?? 'Pendidikan'); ?></span>
                        </div>
                    </div>

                    
                    <div class="p-6 flex flex-col flex-1 relative z-10 bg-white dark:bg-slate-900 rounded-b-[2rem] transition-colors">
                        
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[1rem] bg-slate-100 dark:bg-slate-800 overflow-hidden border border-slate-200 dark:border-slate-700 shrink-0 shadow-sm transition-colors">
                                    <img src="<?php echo e(optional($article->user)->photo_path ? asset('storage/' . $article->user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(optional($article->user)->name ?? 'A').'&background=e5eff5&color=0d52a1'); ?>" alt="Penulis" loading="lazy" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-bold text-elevate-dark dark:text-slate-300 line-clamp-1 transition-colors"><?php echo e(optional($article->user)->name ?? 'Anonim'); ?></span>
                            </div>
                            
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest flex items-center gap-1 transition-colors" title="Estimasi Waktu Baca">
                                    <i class="ph-bold ph-clock text-elevate-accent"></i> 3 Min
                                </span>
                            </div>
                        </div>

                        
                        <a href="<?php echo e($article->url ?? '#'); ?>" target="<?php echo e($article->url ? '_blank' : '_self'); ?>" class="block group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors">
                            <h3 class="text-lg font-black text-elevate-dark dark:text-slate-100 mb-3 leading-snug line-clamp-2 transition-colors"><?php echo e($article->title); ?></h3>
                        </a>
                        
                        
                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-3 mb-6 flex-1 font-medium leading-relaxed transition-colors"><?php echo e(Str::limit(strip_tags($article->excerpt), 150)); ?></p>

                        
                        <div class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800/50 flex items-center justify-between transition-colors">
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest flex items-center gap-1.5 transition-colors">
                                <i class="ph-bold ph-calendar-blank text-elevate-accent"></i> 
                                <?php echo e($article->published_at ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') : '-'); ?>

                            </span>
                            
                            <a href="<?php echo e($article->url ?? route('teachers.show', $article->user_id)); ?>" target="<?php echo e($article->url ? '_blank' : '_self'); ?>" aria-label="Baca selengkapnya tentang <?php echo e($article->title); ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-elevate-accent hover:bg-elevate-primary hover:text-white dark:hover:bg-elevate-primary dark:hover:text-white transition-all shadow-sm border border-elevate-accent/20 dark:border-slate-700 group/link">
                                <?php if($article->url): ?>
                                    <i class="ph-bold ph-arrow-up-right text-lg group-hover/link:scale-110 transition-transform"></i>
                                <?php else: ?>
                                    <i class="ph-bold ph-arrow-right text-lg group-hover/link:translate-x-0.5 transition-transform"></i>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-1 md:col-span-3 text-center py-16 px-4 bg-white dark:bg-slate-800/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-700 shadow-sm transition-colors" data-aos="fade-up">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-slate-500 mb-6 border border-elevate-accent/20 dark:border-slate-700 transition-colors"><i class="ph-duotone ph-pen-nib text-5xl"></i></div>
                    <h3 class="text-xl font-black text-elevate-dark dark:text-white mb-2 transition-colors">Belum Ada Artikel</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Guru-guru kami sedang menyiapkan tulisan-tulisan inspiratif untuk Anda.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if(isset($latestArticles) && count($latestArticles) > 0): ?>
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="<?php echo e(route('articles.index')); ?>" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-elevate-primary dark:text-white bg-elevate-soft dark:bg-elevate-primary border border-elevate-accent/30 dark:border-transparent rounded-full hover:bg-white dark:hover:bg-elevate-dark hover:border-elevate-primary dark:hover:border-elevate-primary transition-all shadow-sm group">
                Jelajahi Semua Tulisan
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/articles.blade.php ENDPATH**/ ?>