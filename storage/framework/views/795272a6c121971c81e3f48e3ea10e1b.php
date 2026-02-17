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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Analisis Butir Soal')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <a href="<?php echo e(route('cbt.recap', $exam->id)); ?>" class="text-xs font-bold text-slate-400 hover:text-blue-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap
                        </a>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800"><?php echo e($exam->title); ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Analisis Kualitas Soal • Sampel: <?php echo e($totalStudents); ?> Siswa</p>
                </div>
                
                
                <div class="flex gap-3 text-[10px] uppercase font-bold text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Mudah (>75%)</div>
                    <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Sedang</div>
                    <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Sukar (<30%)</div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4 w-1/3">Cuplikan Soal</th>
                                <th class="px-6 py-4 text-center">Kunci</th>
                                <th class="px-6 py-4 text-center">Tingkat Kesukaran</th>
                                <th class="px-6 py-4 w-1/3">Distribusi Jawaban Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $analysis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-center font-black text-slate-400"><?php echo e($index + 1); ?></td>
                                    
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-700 line-clamp-2" title="<?php echo e($item->text); ?>"><?php echo e($item->text); ?></p>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 font-black flex items-center justify-center mx-auto border border-slate-200">
                                            <?php echo e($item->correct_key); ?>

                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-2 <?php echo e($item->difficulty_badge); ?>">
                                            <?php echo e($item->difficulty_label); ?>

                                        </span>
                                        
                                        
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden flex">
                                            <div class="h-full <?php echo e(str_contains($item->difficulty_badge, 'emerald') ? 'bg-emerald-400' : (str_contains($item->difficulty_badge, 'rose') ? 'bg-rose-400' : 'bg-blue-400')); ?>" 
                                                 style="width: <?php echo e($item->difficulty_index); ?>%"></div>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1"><?php echo e($item->difficulty_index); ?>% Siswa Benar</p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-end gap-2 h-16 w-full pb-1 border-b border-slate-200">
                                            <?php $__currentLoopData = ['A','B','C','D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php 
                                                    $count = $item->options[$opt] ?? 0;
                                                    $percent = $totalStudents > 0 ? ($count / $totalStudents) * 100 : 0;
                                                    $isKey = $opt == $item->correct_key;
                                                    $color = $isKey ? 'bg-emerald-400' : 'bg-slate-300';
                                                    if(!$isKey && $percent > 20) $color = 'bg-amber-400'; // Distractor kuat
                                                ?>
                                                <div class="flex-1 flex flex-col justify-end items-center group relative">
                                                    
                                                    <div class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 transition text-[10px] font-bold bg-slate-800 text-white px-2 py-1 rounded">
                                                        <?php echo e($count); ?> Siswa
                                                    </div>
                                                    
                                                    <div class="w-full rounded-t-sm transition-all duration-500 <?php echo e($color); ?>" 
                                                         style="height: <?php echo e($percent > 0 ? $percent : 2); ?>%"></div>
                                                    <span class="text-[10px] font-bold <?php echo e($isKey ? 'text-emerald-600' : 'text-slate-400'); ?> mt-1"><?php echo e($opt); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data analisis.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/cbt/analysis.blade.php ENDPATH**/ ?>