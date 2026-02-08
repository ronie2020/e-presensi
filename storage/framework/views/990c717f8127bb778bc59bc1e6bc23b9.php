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
            <?php echo e(__('Rekapitulasi Nilai')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 p-8 text-white shadow-xl shadow-indigo-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="<?php echo e(route('cbt.index')); ?>" class="text-xs font-bold text-indigo-300 hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-white/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Laporan Hasil</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-1"><?php echo e($exam->title); ?></h1>
                        <p class="text-indigo-200 text-sm font-medium">Mapel: <?php echo e($exam->subject_name); ?> • Kelas <?php echo e($exam->class_level); ?></p>
                    </div>
                    
                    <div class="flex gap-3">
                        
                        <a href="<?php echo e(route('cbt.export', ['id' => $exam->id, 'type' => 'excel'])); ?>" target="_blank" class="group px-5 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-500 transition flex items-center gap-2 shadow-lg shadow-emerald-900/20">
                            <i class="ph-duotone ph-microsoft-excel-logo text-xl group-hover:scale-110 transition-transform"></i> 
                            <span>Excel</span>
                        </a>
                        
                        <a href="<?php echo e(route('cbt.export', ['id' => $exam->id, 'type' => 'pdf'])); ?>" target="_blank" class="group px-5 py-3 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-500 transition flex items-center gap-2 shadow-lg shadow-rose-900/20">
                            <i class="ph-duotone ph-file-pdf text-xl group-hover:scale-110 transition-transform"></i> 
                            <span>PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-chart-line-up"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Rata-rata</span>
                    </div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['average'], 1)); ?></p>
                </div>

                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-crown"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Tertinggi</span>
                    </div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($stats['max_score']); ?></p>
                </div>

                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center"><i class="ph-bold ph-trend-down"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Terendah</span>
                    </div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($stats['min_score']); ?></p>
                </div>

                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center"><i class="ph-bold ph-users"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Peserta</span>
                    </div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($results->count()); ?> <span class="text-sm text-slate-400 font-bold">Siswa</span></p>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-trophy text-amber-500"></i> Peringkat Hasil
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-shadow">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-center w-16">Rank</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4 text-center">Benar / Salah</th>
                                <th class="px-6 py-4 text-center">Nilai Akhir</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr x-show="search === '' || '<?php echo e(strtolower($res->student_name)); ?>'.includes(search.toLowerCase())" 
                                    class="hover:bg-indigo-50/30 transition group">
                                    
                                    
                                    <td class="px-6 py-4 text-center">
                                        <?php if($index == 0): ?>
                                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-sm"><i class="ph-fill ph-crown"></i></div>
                                        <?php elseif($index == 1): ?>
                                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center mx-auto shadow-sm font-bold">2</div>
                                        <?php elseif($index == 2): ?>
                                            <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center mx-auto shadow-sm font-bold">3</div>
                                        <?php else: ?>
                                            <span class="font-bold text-slate-400"><?php echo e($index + 1); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800 text-base"><?php echo e($res->student_name); ?></p>
                                        <p class="text-xs text-slate-400 font-mono mt-0.5"><?php echo e($res->student_nisn ?? 'NISN Tidak Ada'); ?></p>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center gap-2 bg-slate-100 rounded-lg p-1.5">
                                            
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold" title="Benar"><?php echo e($res->correct_answers ?? 0); ?></span>
                                            <span class="text-slate-300">/</span>
                                            <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs font-bold" title="Salah"><?php echo e($res->wrong_answers ?? 0); ?></span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xl font-black <?php echo e($res->total_score >= $exam->passing_grade ? 'text-emerald-600' : 'text-rose-500'); ?>">
                                            <?php echo e($res->total_score ?? 0); ?>

                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <?php if(($res->total_score ?? 0) >= $exam->passing_grade): ?>
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-wider">
                                                Lulus
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-black uppercase tracking-wider">
                                                Remedial
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <button class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition flex items-center justify-center shadow-sm" title="Lihat Jawaban">
                                            <i class="ph-bold ph-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                            <i class="ph-duotone ph-file-x text-3xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold">Belum ada data nilai masuk.</p>
                                    </td>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\cbt\recap.blade.php ENDPATH**/ ?>