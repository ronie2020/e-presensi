<div class="mb-8 sticky top-4 z-40 transition-all duration-300" id="sticky-nav">
    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-2 rounded-[1.5rem] shadow-lg shadow-elevate-dark/5 border border-white dark:border-slate-700/50 relative group transition-colors duration-300">
        
        <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white dark:from-slate-800 to-transparent pointer-events-none md:hidden z-10 rounded-r-[1.5rem]"></div>
        
        <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white dark:from-slate-800 to-transparent pointer-events-none md:hidden z-10 rounded-l-[1.5rem]"></div>
        
        <div class="overflow-x-auto custom-scrollbar w-full scroll-smooth px-1 md:overflow-visible">
            <div class="flex items-center gap-2 w-max md:w-full md:flex-wrap md:justify-center py-1"> 
                
                <?php if(isset($tabs) && is_array($tabs)): ?>
                    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button @click="
                                    updateTab('<?php echo e($key); ?>');
                                    $el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                                " 
                            :class="activeTab === '<?php echo e($key); ?>' 
                                ? 'bg-elevate-dark text-white shadow-md shadow-elevate-dark/20 scale-100' 
                                : 'bg-transparent text-elevate-dark/60 dark:text-slate-400 hover:bg-elevate-soft hover:text-elevate-primary dark:hover:bg-slate-700'"
                            class="relative px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap flex-shrink-0 outline-none group">
                            
                            
                            <div class="w-6 h-6 rounded-md flex items-center justify-center transition-colors"
                                 :class="activeTab === '<?php echo e($key); ?>' ? 'bg-white/20 text-white' : 'bg-elevate-soft text-elevate-primary group-hover:bg-elevate-primary group-hover:text-white'">
                                <i :class="activeTab === '<?php echo e($key); ?>' ? 'ph-fill' : 'ph-bold'" 
                                   class="ph-<?php echo e($tab['icon']); ?> text-base transition-colors duration-300"></i>
                            </div>
                             
                            <?php echo e($tab['label']); ?>


                            
                            <?php if(isset($tab['badge']) && $tab['badge'] > 0): ?>
                                <span class="absolute -top-1.5 -right-1 flex h-4 w-4 z-20">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-peach opacity-75"></span>
                                    <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-elevate-peach-dark text-[9px] font-black text-white border border-white shadow-sm">
                                        <?php echo e($tab['badge'] > 9 ? '9+' : $tab['badge']); ?>

                                    </span>
                                </span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-xs text-red-500 font-bold px-4 py-2">Error: Menu Tabs tidak dimuat dari Controller.</div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tabs-nav.blade.php ENDPATH**/ ?>