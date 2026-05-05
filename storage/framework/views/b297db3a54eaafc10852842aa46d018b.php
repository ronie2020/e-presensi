<!-- PRESTASI SECTION -->
<div id="prestasi" class="py-24 bg-slate-50 dark:bg-slate-950 relative overflow-hidden border-t border-slate-100 dark:border-slate-900 transition-colors duration-300" x-data="{ activeFilter: 'Terbaru' }">
    <!-- Abstract Ambient Elevate -->
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-elevate-accent/20 dark:bg-elevate-accent/10 rounded-full filter blur-[100px] pointer-events-none transition-colors duration-300"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-elevate-primary/10 dark:bg-elevate-primary/20 rounded-full filter blur-[100px] pointer-events-none transition-colors duration-300"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors duration-300">
                    <i class="ph-fill ph-trophy text-sm"></i> Hall of Fame
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-elevate-dark dark:text-white leading-tight transition-colors duration-300">Prestasi Membanggakan</h2>
                <p class="mt-4 text-sm md:text-base text-slate-600 dark:text-slate-400 font-medium">Jejak juara siswa dan guru yang mengharumkan nama sekolah.</p>
            </div>
            
            
            <div class="flex overflow-x-auto w-full md:w-auto pb-2 md:pb-0 gap-2 no-scrollbar custom-scrollbar snap-x">
                <?php $__currentLoopData = ['Terbaru', 'Nasional', 'Provinsi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeFilter = '<?php echo e($filter); ?>'" 
                            class="snap-start shrink-0 px-5 py-2.5 rounded-full text-xs font-bold transition-all duration-300 border shadow-sm flex items-center gap-2"
                            :class="activeFilter === '<?php echo e($filter); ?>' 
                                ? 'bg-elevate-dark dark:bg-elevate-primary text-white border-elevate-dark dark:border-elevate-primary shadow-lg shadow-elevate-dark/20 dark:shadow-elevate-primary/20 scale-105' 
                                : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:bg-elevate-soft dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50'">
                        <?php echo e($filter); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $achievements ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prestasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden h-full flex flex-col hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50" 
                     x-show="activeFilter === 'Terbaru' || activeFilter.toLowerCase() === '<?php echo e(strtolower($prestasi->level ?? '')); ?>'"
                     x-transition.duration.500ms
                     data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                    
                    <div class="h-48 w-full bg-slate-100 dark:bg-slate-800 relative overflow-hidden group">
                        <?php if(!empty($prestasi->photo_path)): ?>
                            <img src="<?php echo e(asset('storage/' . $prestasi->photo_path)); ?>" loading="lazy" alt="<?php echo e($prestasi->title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="absolute inset-0 flex items-center justify-center bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-slate-600" style="display: none;"><i class="ph-duotone ph-trophy text-5xl"></i></div>
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-elevate-soft dark:bg-slate-800 text-elevate-primary/40 dark:text-slate-600"><i class="ph-duotone ph-trophy text-5xl"></i></div>
                        <?php endif; ?>
                        
                        <div class="absolute top-3 right-3">
                             <span class="px-3 py-1.5 rounded-full bg-white/90 dark:bg-slate-900/90 backdrop-blur border border-white/20 dark:border-slate-700/50 text-[9px] font-black uppercase text-elevate-primary dark:text-elevate-accent tracking-widest shadow-sm"><?php echo e($prestasi->level ?? 'Sekolah'); ?></span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col relative z-10">
                        <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mb-2 flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank text-elevate-accent"></i> <?php echo e(isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->format('d M Y') : '-'); ?></div>
                        <h4 class="text-lg font-black text-elevate-dark dark:text-slate-100 mb-2 leading-tight group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors line-clamp-2"><?php echo e($prestasi->title ?? 'Juara Lomba'); ?></h4>
                        
                        <div class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800/50 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-[1rem] bg-elevate-soft dark:bg-slate-800 flex items-center justify-center text-elevate-primary dark:text-slate-400 text-lg border border-elevate-accent/20 dark:border-slate-700 group-hover:bg-elevate-primary dark:group-hover:bg-elevate-primary group-hover:text-white transition-colors"><i class="ph-fill ph-user"></i></div>
                            <div>
                                <p class="text-xs font-black text-elevate-dark dark:text-slate-300 line-clamp-1"><?php echo e($prestasi->achiever_name ?? 'Siswa'); ?></p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-widest"><?php echo e($prestasi->type ?? 'Siswa'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-16 text-center animate-enter bg-white dark:bg-slate-800/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-700 transition-colors">
                    <div class="inline-flex p-5 bg-elevate-soft dark:bg-elevate-primary/20 rounded-full mb-4 text-elevate-primary dark:text-elevate-accent shadow-sm border border-elevate-accent/20 dark:border-elevate-accent/10"><i class="ph-duotone ph-trophy text-5xl"></i></div>
                    <h3 class="text-xl font-black text-elevate-dark dark:text-slate-200 mb-1">Belum Ada Prestasi</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Jadilah yang pertama mengukir prestasi gemilang!</p>
                </div>
            <?php endif; ?>
         </div>
         
        <div class="mt-12 text-center" data-aos="fade-up">
             <a href="<?php echo e(route('public.achievements')); ?>" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-elevate-primary dark:text-white bg-elevate-soft dark:bg-elevate-primary border border-elevate-accent/30 dark:border-transparent rounded-full hover:bg-white dark:hover:bg-elevate-dark hover:border-elevate-primary dark:hover:border-elevate-primary transition-all shadow-sm group">
                Lihat Arsip Prestasi 
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
             </a>
        </div>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/achievements.blade.php ENDPATH**/ ?>