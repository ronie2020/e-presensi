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
    <div class="p-6 md:p-8 space-y-8 min-h-screen bg-slate-50">
        
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="ph-fill ph-book-open text-emerald-600"></i> Rekap Mutabaah Ramadhan
                </h1>
                <p class="text-sm text-slate-500 mt-1">Monitoring kedisiplinan ibadah harian siswa selama bulan Ramadhan.</p>
            </div>
            
            
            <form action="<?php echo e(route('admin.ramadan.reports')); ?>" method="GET" class="flex flex-wrap gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center px-3 gap-2 border-r border-slate-100">
                    <i class="ph-bold ph-chalkboard text-slate-400"></i>
                    <select name="class_id" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer min-w-[140px]">
                        <option value="">Pilih Kelas</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php echo e($selectedClass == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-center px-3 gap-2">
                    <i class="ph-bold ph-calendar text-slate-400"></i>
                    <input type="date" name="date" value="<?php echo e($date); ?>" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer">
                </div>
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                    <i class="ph-bold ph-magnifying-glass"></i> Filter
                </button>
            </form>
        </div>

        
        <?php if($selectedClass): ?>
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-in fade-in duration-500">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Puasa</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Shalat 5W</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Sunnah</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Tilawah / Murojaah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $log = $student->ramadanLogs->first(); ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="font-black text-slate-800"><?php echo e($student->name); ?></div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-0.5 tracking-wider"><?php echo e($student->student_id); ?></div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if($log): ?>
                                        <?php if($log->is_fasting): ?>
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 shadow-sm">
                                                <i class="ph-fill ph-check-circle text-xl"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-100 text-rose-600 shadow-sm">
                                                <i class="ph-fill ph-x-circle text-xl"></i>
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="w-2 h-2 rounded-full bg-slate-200 mx-auto"></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if($log && is_array($log->prayers)): ?>
                                        <?php $count = count(array_filter($log->prayers)); ?>
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-sm font-black <?php echo e($count == 5 ? 'text-emerald-600' : ($count > 0 ? 'text-amber-500' : 'text-slate-300')); ?>">
                                                <?php echo e($count); ?>/5
                                            </span>
                                            <div class="flex gap-0.5">
                                                <?php $__currentLoopData = ['subuh','dzuhur','ashar','maghrib','isya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="w-1.5 h-1.5 rounded-full <?php echo e(($log->prayers[$p] ?? false) ? 'bg-emerald-500' : 'bg-slate-200'); ?>"></div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-[10px] font-bold text-slate-300">BELUM ISI</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if($log && is_array($log->sunnah_deeds)): ?>
                                        <?php $countS = count(array_filter($log->sunnah_deeds)); ?>
                                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-black border border-amber-100">
                                            <?php echo e($countS); ?> AMALAN
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px]">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5">
                                    <?php if($log && ($log->tadarus_surah || $log->murojaah_surah)): ?>
                                        <?php if($log->tadarus_surah): ?>
                                            <div class="flex items-center gap-2 mb-1">
                                                <i class="ph-fill ph-book-open text-blue-500"></i>
                                                <span class="text-xs font-black text-slate-700"><?php echo e($log->tadarus_surah); ?> (<?php echo e($log->tadarus_ayah); ?>)</span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($log->murojaah_surah): ?>
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-headset text-purple-500"></i>
                                                <span class="text-[10px] font-bold text-slate-500">Murojaah: <?php echo e($log->murojaah_surah); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px] italic">Tidak ada data</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <p class="text-slate-400 font-bold">Tidak ada siswa ditemukan di kelas ini.</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            
            <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-selection-all text-5xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-black text-slate-600">Pilih Kelas Monitoring</h3>
                <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto">Gunakan filter di pojok kanan atas untuk melihat laporan mutabaah siswa.</p>
            </div>
        <?php endif; ?>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-info"></i></div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data dihitung secara real-time dari input jurnal siswa.</div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-printer"></i></div>
                <button onclick="window.print()" class="text-xs font-bold text-slate-800 hover:text-emerald-600 transition uppercase tracking-wider">Cetak Laporan Harian Kelas</button>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center"><i class="ph-bold ph-export"></i></div>
                <button class="text-xs font-bold text-slate-800 hover:text-emerald-600 transition uppercase tracking-wider">Export ke Excel (Coming Soon)</button>
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/ramadan/admin_report.blade.php ENDPATH**/ ?>