<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('admin.alumni.index')); ?>" class="p-3 bg-white rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm text-slate-500 group">
                        <i class="ph-bold ph-arrow-left text-xl group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-elevate-dark tracking-tight">Rekap Testimoni Alumni</h1>
                        <p class="text-slate-500 text-sm font-medium mt-1">Daftar pesan dan kesan dari alumni yang telah mengisi tracer study.</p>
                    </div>
                </div>
                
                
                <div class="px-5 py-3 bg-white rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                    <div class="p-2 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 rounded-xl">
                        <i class="ph-fill ph-quotes text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Testimoni</p>
                        <p class="text-xl font-black text-elevate-dark leading-none mt-1"><?php echo e($testimonials->total()); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/30 hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group relative overflow-hidden">
                        
                        <div class="absolute top-0 right-0 w-24 h-24 bg-elevate-primary/5 rounded-bl-full pointer-events-none -z-10"></div>

                        
                        <div class="flex items-center gap-4 mb-5 border-b border-slate-50 pb-5">
                            <div class="relative shrink-0">
                                <div class="w-14 h-14 rounded-[1.25rem] bg-slate-100 overflow-hidden border border-slate-200 shadow-sm flex items-center justify-center text-elevate-primary font-black text-xl">
                                    <?php if($item->student && $item->student->photo_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $item->student->photo_path)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?php echo e(substr($item->student->name ?? 'A', 0, 1)); ?>

                                    <?php endif; ?>
                                </div>
                                <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm border border-slate-100">
                                    <?php if($item->rating >= 4): ?>
                                        <i class="ph-fill ph-star text-amber-400 text-sm drop-shadow-sm"></i>
                                    <?php else: ?>
                                        <i class="ph-fill ph-star text-slate-300 text-sm"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-elevate-dark text-base truncate group-hover:text-elevate-primary transition-colors" title="<?php echo e($item->student->name ?? 'Alumni'); ?>">
                                    <?php echo e($item->student->name ?? 'Data Siswa Terhapus'); ?>

                                </h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded-md bg-elevate-accent/10 text-elevate-primary uppercase tracking-wider border border-elevate-accent/20">
                                        Lulus <?php echo e($item->student->graduation_year ?? '-'); ?>

                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 truncate max-w-[100px] uppercase tracking-wider">
                                        <?php echo e($item->activity_status); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="relative flex-1 mb-5 z-10 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50">
                            <i class="ph-fill ph-quotes text-3xl text-elevate-accent/20 absolute -top-1 -left-1 -z-10"></i>
                            <p class="text-sm text-slate-600 font-medium leading-relaxed italic relative z-10">
                                "<?php echo e(Str::limit($item->testimony, 150)); ?>"
                            </p>
                            <?php if(strlen($item->testimony) > 150): ?>
                                
                                <button type="button" onclick="alert(<?php echo e(json_encode($item->testimony)); ?>)" class="text-xs font-black text-elevate-primary hover:text-elevate-dark mt-2 cursor-pointer transition-colors uppercase tracking-wider">
                                    Baca selengkapnya
                                </button>
                            <?php endif; ?>
                        </div>

                        
                        <div class="mt-auto pt-4 border-t border-slate-50 flex justify-between items-center text-xs">
                            <div class="text-slate-400 font-bold flex items-center gap-1.5 uppercase tracking-wider text-[9px]">
                                <i class="ph-bold ph-calendar-blank text-xs"></i>
                                <?php echo e($item->updated_at->format('d M Y')); ?>

                            </div>
                            <a href="<?php echo e(route('admin.alumni.show', $item->student_id)); ?>" class="font-bold text-elevate-primary hover:text-elevate-dark flex items-center gap-1 group-hover:gap-2 transition-all bg-white px-3 py-1.5 rounded-lg border border-slate-100 shadow-sm hover:shadow-md">
                                Detail Profil <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-16 text-center border-2 border-dashed border-slate-200 rounded-[2.5rem] bg-white">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-sm text-slate-300">
                            <i class="ph-duotone ph-chat-teardrop-slash text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-elevate-dark">Belum Ada Testimoni</h3>
                        <p class="text-slate-500 text-sm font-medium mt-1">Belum ada alumni yang mengisi kolom testimoni pada tracer study.</p>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="mt-10">
                <?php echo e($testimonials->links()); ?>

            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/alumni/testimonials.blade.php ENDPATH**/ ?>