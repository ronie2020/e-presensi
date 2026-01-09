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
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div class="py-6 sm:py-8">
        
        
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                
                <i class="ph-duotone ph-files text-blue-900"></i> Rekap Kehadiran
            </h1>
            <p class="text-slate-500 mt-2 text-lg">
                Pantau histori kehadiran siswa dalam kegiatan ekstrakurikuler.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mx-4 sm:mx-0">
            
            
            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                <form method="GET" action="<?php echo e(route('extracurriculars.reports')); ?>" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    <!-- Pilih Kegiatan (UPGRADED: Searchable) -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Kegiatan Ekskul</label>
                        <select id="filter-ekskul" name="ekskul_id" class="w-full" placeholder="Cari kegiatan..." autocomplete="off">
                            <option value="">-- Tampilkan Semua --</option>
                            <?php $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ekskul->id); ?>" <?php echo e($selectedEkskulId == $ekskul->id ? 'selected' : ''); ?>><?php echo e($ekskul->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Periode Tanggal -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Dari Tanggal</label>
                        
                        <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-sm py-2 text-slate-600 font-bold shadow-sm">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Sampai Tanggal</label>
                        
                        <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-sm py-2 text-slate-600 font-bold shadow-sm">
                    </div>

                    <!-- Action Buttons -->
                    <div class="md:col-span-2 flex gap-2">
                        
                        <button type="submit" class="flex-1 bg-blue-900 text-white px-4 py-2 rounded-xl hover:bg-blue-800 font-bold text-sm shadow-lg shadow-blue-900/20 transition-all h-[42px] flex items-center justify-center gap-2">
                            <i class="ph-bold ph-funnel"></i> Filter
                        </button>
                        <?php if($selectedEkskulId): ?>
                            <a href="<?php echo e(route('extracurriculars.reports.export', request()->query())); ?>" target="_blank" class="px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 hover:text-blue-900 hover:border-blue-300 transition-colors h-[42px] flex items-center justify-center shadow-sm" title="Cetak PDF">
                                <i class="ph-bold ph-printer text-xl"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                        <tr>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">Kegiatan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700 text-sm"><?php echo e(\Carbon\Carbon::parse($log->date)->format('d M Y')); ?></span>
                                        <span class="text-xs text-slate-400 font-mono bg-slate-100 px-1.5 py-0.5 rounded w-fit mt-1"><?php echo e($log->time_in); ?> WIB</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                            <?php echo e(substr($log->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 text-sm"><?php echo e($log->student->name); ?></div>
                                            <div class="text-xs text-slate-500"><?php echo e($log->student->schoolClass->name ?? '-'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-xs font-bold shadow-sm group-hover:border-blue-200 group-hover:text-blue-900 transition-colors">
                                        <?php echo e($log->extracurricular->name); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 mx-auto shadow-sm shadow-emerald-500/20">
                                        <i class="ph-bold ph-check"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                        <i class="ph-duotone ph-clipboard-text text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Tidak ada data kehadiran.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba ubah filter tanggal atau pilih kegiatan lain.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            
            <?php if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                <div class="p-4 border-t border-slate-50">
                    <?php echo e($attendances->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Init TomSelect
            new TomSelect('#filter-ekskul', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Cari kegiatan...",
                plugins: ['dropdown_input'],
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/extracurriculars/reports.blade.php ENDPATH**/ ?>