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
    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ searchQuery: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 text-blue-300 text-sm font-bold mb-2">
                            <a href="<?php echo e(route('grades.index')); ?>" class="hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span>Daftar Siswa</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none mb-1">Cetak E-Rapor</h1>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                            <span class="bg-white/10 px-3 py-1 rounded-lg text-xs font-bold border border-white/10"><?php echo e($class->name); ?></span>
                            <span class="text-blue-200 text-xs">●</span>
                            <span class="text-blue-200 text-sm font-medium">TA <?php echo e($academic_year); ?> (<?php echo e($semester); ?>)</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-center md:items-end gap-2">
                        <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center shadow-lg">
                            <span class="block text-2xl font-black text-white"><?php echo e($students->count()); ?></span>
                            <span class="text-[9px] uppercase font-bold text-blue-300 tracking-wider">Total Siswa</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
               <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-96">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="text" 
                               x-model="searchQuery" 
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold shadow-sm placeholder:font-medium placeholder:text-slate-400"
                               placeholder="Cari nama siswa atau NISN...">
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('grades.template_leger', ['class_id' => $class->id])); ?>" class="px-5 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 hover:text-blue-600 transition shadow-sm text-sm flex items-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-file-csv text-lg"></i>
                            <span>Leger Nilai</span>
                        </a>
                        <a href="<?php echo e(route('grades.print_all', ['class_id' => $class->id, 'year' => $academic_year, 'semester' => $semester])); ?>" target="_blank" class="px-5 py-3 bg-blue-600 border border-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 text-sm flex items-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span>Cetak Semua</span>
                        </a>
                    </div>
                </div>

                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider">Identitas Siswa</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-1/3">Progres Penilaian</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php 
                                    $completedSubjects = $progress[$student->id] ?? 0; 
                                    // PERBAIKAN: Sebaiknya $totalSubjects dikirim dari controller.
                                    // Jika tidak ada, fallback ke 12.
                                    $maxSubjects = $totalSubjects ?? 12; 
                                    $percentage = $maxSubjects > 0 ? min(100, round(($completedSubjects / $maxSubjects) * 100)) : 0;
                                    
                                    $barColor = $percentage == 100 ? 'bg-emerald-500' : ($percentage > 50 ? 'bg-blue-500' : 'bg-amber-500');
                                    $textColor = $percentage == 100 ? 'text-emerald-600' : 'text-slate-500';
                                ?>
                                
                                
                                <tr class="hover:bg-blue-50/20 transition-colors group"
                                    x-show="searchQuery === '' || String(<?php echo \Illuminate\Support\Js::from(strtolower($student->name))->toHtml() ?>).includes(searchQuery.toLowerCase()) || String(<?php echo \Illuminate\Support\Js::from($student->student_id)->toHtml() ?>).includes(searchQuery)"
                                    x-transition.opacity>
                                    
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold text-sm"><?php echo e($index + 1); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-blue-500/20 shrink-0">
                                                <?php echo e(substr($student->name, 0, 2)); ?>

                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors"><?php echo e($student->name); ?></div>
                                                <div class="text-xs text-slate-400 font-mono font-medium mt-0.5 tracking-wide">NISN: <?php echo e($student->student_id); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="w-full">
                                            <div class="flex justify-between items-end mb-1">
                                                <span class="text-xs font-bold <?php echo e($textColor); ?>">
                                                    <?php echo e($completedSubjects); ?> / <?php echo e($maxSubjects); ?> Mapel
                                                </span>
                                                <span class="text-[10px] font-black text-slate-400"><?php echo e($percentage); ?>%</span>
                                            </div>
                                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full <?php echo e($barColor); ?> rounded-full transition-all duration-500" style="width: <?php echo e($percentage); ?>%"></div>
                                            </div>
                                            <?php if($percentage < 100): ?>
                                                <p class="text-[10px] text-rose-400 mt-1 italic">Belum Lengkap</p>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="<?php echo e(route('grades.report', ['student_id' => $student->id, 'year' => $academic_year, 'semester' => $semester])); ?>" 
                                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:border-blue-500 hover:text-blue-600 text-sm font-bold rounded-xl shadow-sm transition-all duration-200 transform hover:-translate-y-0.5 group/btn">
                                            <i class="ph-bold ph-eye text-lg text-slate-400 group-hover/btn:text-blue-500"></i> 
                                            <span>Lihat</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4" class="p-12 text-center text-slate-400 font-medium">Data siswa kosong.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <div class="p-16 text-center" x-show="searchQuery !== '' && $el.previousElementSibling.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                        </div>
                        <p class="text-slate-500 font-bold">Tidak ditemukan siswa dengan pencarian tersebut.</p>
                    </div>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\grades\list.blade.php ENDPATH**/ ?>