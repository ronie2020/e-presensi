<!-- GURU SECTION -->
<div id="guru" class="py-24 bg-slate-50 dark:bg-slate-950 relative overflow-hidden border-t border-slate-100 dark:border-slate-900 transition-colors duration-300">
    
    <!-- Elevate Ambient Backgrounds -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-accent/10 dark:bg-elevate-accent/5 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300 -translate-y-1/4 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/10 dark:bg-elevate-primary/15 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300 translate-y-1/4 -translate-x-1/4"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header Section -->
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors">
                <i class="ph-fill ph-users-three text-sm"></i> SDM Unggul
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-elevate-dark dark:text-white leading-tight mb-4 transition-colors">Tenaga Pendidik</h2>
            <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto text-sm md:text-lg font-medium transition-colors">Dibimbing oleh guru-guru profesional yang berdedikasi tinggi dalam mencetak generasi emas.</p>
        </div>

        <!-- Grid Guru -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    // LOGIKA ROLE ASLI DIPERTAHANKAN
                    $displayRole = $teacher->position;
                    if (empty($displayRole)) {
                        $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                        $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                    }
                ?>

                <!-- Teacher Card Elevate -->
                <div class="group bg-white dark:bg-slate-900 rounded-[2.5rem] p-2 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 hover:-translate-y-2 transition-all duration-500 h-full flex flex-col hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50 overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                    
                    
                    <div class="aspect-[3/4] w-full relative rounded-[2rem] overflow-hidden bg-slate-100 dark:bg-slate-800 transition-colors">
                        <?php if($teacher->photo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $teacher->photo_path)); ?>" loading="lazy" alt="<?php echo e($teacher->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-full hidden flex-col items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 text-slate-500 dark:text-slate-400">
                                <span class="text-6xl font-black opacity-30 select-none uppercase"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-slate-600 transition-colors">
                                <span class="text-7xl font-black opacity-20 select-none uppercase group-hover:scale-110 transition-transform"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-elevate-dark/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        
                        <div class="absolute bottom-4 left-0 right-0 px-4 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                             <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/50 dark:border-slate-700 text-center shadow-lg">
                                <span class="text-[9px] font-black uppercase text-elevate-primary dark:text-elevate-accent tracking-widest truncate block">
                                    <?php echo e($displayRole); ?>

                                </span>
                             </div>
                        </div>
                    </div>

                    
                    <div class="p-5 text-center relative flex-1 flex flex-col items-center justify-center">
                        
                        <div class="mb-3 group-hover:opacity-0 transition-opacity duration-200">
                            <span class="bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[9px] font-black uppercase tracking-widest py-1 px-3 rounded-lg border border-elevate-accent/20 dark:border-elevate-accent/30 truncate max-w-[150px] inline-block shadow-sm">
                                <?php echo e($displayRole); ?>

                            </span>
                        </div>
                        
                        <h3 class="text-lg font-black text-elevate-dark dark:text-white group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors line-clamp-1 leading-tight"><?php echo e($teacher->name); ?></h3>
                        
                        <?php if(!empty($teacher->nip)): ?>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider">NIP. <?php echo e($teacher->nip); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-20 text-center animate-enter bg-white dark:bg-slate-900 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800 transition-colors">
                    <div class="inline-flex p-5 bg-elevate-soft dark:bg-slate-800 rounded-full mb-4 text-elevate-primary dark:text-slate-500 shadow-sm border border-elevate-accent/20 dark:border-slate-800 transition-colors">
                        <i class="ph-duotone ph-chalkboard-teacher text-4xl"></i>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada data tenaga pendidik.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer CTA -->
        <div class="text-center mt-16" data-aos="fade-up">
            <a href="<?php echo e(route('teachers.index')); ?>" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-elevate-primary dark:text-white bg-elevate-soft dark:bg-elevate-primary border border-elevate-accent/30 dark:border-transparent rounded-full hover:bg-white dark:hover:bg-elevate-dark hover:border-elevate-primary transition-all shadow-sm group">
                Lihat Seluruh Staff 
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/teachers.blade.php ENDPATH**/ ?>