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
    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('admin.alumni.index')); ?>" class="p-3 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm text-slate-500">
                        <i class="ph-bold ph-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800">Rekap Testimoni Alumni</h1>
                        <p class="text-slate-500 text-sm">Daftar pesan dan kesan dari alumni yang telah mengisi tracer study.</p>
                    </div>
                </div>
                
                
                <div class="px-5 py-2 bg-white rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <i class="ph-fill ph-quotes text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Testimoni</p>
                        <p class="text-lg font-black text-slate-800"><?php echo e($testimonials->total()); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group">
                        
                        
                        <div class="flex items-center gap-4 mb-4 border-b border-slate-50 pb-4">
                            <div class="relative shrink-0">
                                <div class="w-12 h-12 rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                    <?php if($item->student && $item->student->photo_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $item->student->photo_path)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-lg">
                                            <?php echo e(substr($item->student->name ?? 'A', 0, 1)); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5">
                                    <?php if($item->rating >= 4): ?>
                                        <i class="ph-fill ph-star text-amber-400 text-sm drop-shadow-sm"></i>
                                    <?php else: ?>
                                        <i class="ph-fill ph-star text-slate-300 text-sm"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-800 text-sm truncate" title="<?php echo e($item->student->name ?? 'Alumni'); ?>">
                                    <?php echo e($item->student->name ?? 'Data Siswa Terhapus'); ?>

                                </h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500">
                                        Lulus <?php echo e($item->student->graduation_year ?? '-'); ?>

                                    </span>
                                    <span class="text-[10px] text-slate-400 truncate max-w-[100px]">
                                        <?php echo e($item->activity_status); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="relative flex-1 mb-4">
                            <i class="ph-fill ph-quotes text-4xl text-slate-100 absolute -top-2 -left-2 -z-10"></i>
                            <p class="text-sm text-slate-600 leading-relaxed italic relative z-10">
                                "<?php echo e(Str::limit($item->testimony, 150)); ?>"
                            </p>
                            <?php if(strlen($item->testimony) > 150): ?>
                                
                                <button type="button" onclick="alert(<?php echo e(json_encode($item->testimony)); ?>)" class="text-xs font-bold text-blue-500 hover:text-blue-700 mt-1 cursor-pointer">
                                    Baca selengkapnya
                                </button>
                            <?php endif; ?>
                        </div>

                        
                        <div class="mt-auto pt-4 border-t border-slate-50 flex justify-between items-center text-xs">
                            <div class="text-slate-400 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-calendar-blank"></i>
                                <?php echo e($item->updated_at->format('d M Y')); ?>

                            </div>
                            <a href="<?php echo e(route('admin.alumni.show', $item->student_id)); ?>" class="font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                                Detail Profil <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-16 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="ph-duotone ph-chat-teardrop-slash text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Belum Ada Testimoni</h3>
                        <p class="text-slate-500 text-sm">Belum ada alumni yang mengisi kolom testimoni pada tracer study.</p>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="mt-8">
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\admin\alumni\testimonials.blade.php ENDPATH**/ ?>