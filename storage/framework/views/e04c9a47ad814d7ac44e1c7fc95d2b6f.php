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
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-6 md:p-8 flex flex-col xl:flex-row items-center justify-between gap-6 overflow-hidden border border-white/10 shadow-2xl shadow-blue-900/40 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>

                
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <i class="ph-fill ph-chalkboard-teacher text-9xl text-white"></i>                    
                </div>

                
                <div class="relative z-10 w-full xl:w-auto text-center xl:text-left">
                     <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <h1 class="text-2xl md:text-3xl font-black text-white flex items-center justify-center xl:justify-start gap-3">
                        <i class="ph-duotone ph-chart-bar text-blue-400"></i> Rekapitulasi Per Kelas
                    </h1>
                    <p class="text-blue-200 text-sm mt-2 font-medium max-w-lg mx-auto xl:mx-0 leading-relaxed">
                        Analisis tingkat kedisiplinan dan kehadiran siswa berdasarkan rombongan belajar.
                    </p>
                </div>

                
                <div class="relative z-10 flex flex-col sm:flex-row gap-3 w-full xl:w-auto items-center xl:items-end">
                    
                    
                    <form action="<?php echo e(route('reports.class')); ?>" method="GET" class="flex flex-col sm:flex-row gap-2 bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20 w-full sm:w-auto shadow-lg">
                        <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-xl border border-blue-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Dari</span>
                            <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="border-none p-0 text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer w-32 bg-transparent">
                        </div>
                        <div class="hidden sm:flex items-center text-blue-300/50"><i class="ph-bold ph-arrow-right"></i></div>
                        <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-xl border border-blue-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Sampai</span>
                            <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="border-none p-0 text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer w-32 bg-transparent">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-xl font-bold text-sm transition shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-funnel"></i>
                        </button>
                    </form>

                    
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('reports.class.excel', request()->all())); ?>" target="_blank" class="bg-white hover:bg-emerald-50 text-emerald-600 border border-white/20 px-4 py-3 rounded-2xl font-bold text-sm transition flex items-center gap-2 shadow-lg" title="Download Excel">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> <span class="hidden sm:inline">Excel</span>
                        </a>
                        <a href="<?php echo e(route('reports.class.print', request()->all())); ?>" target="_blank" class="bg-white hover:bg-rose-50 text-rose-600 border border-white/20 px-4 py-3 rounded-2xl font-bold text-sm transition flex items-center gap-2 shadow-lg" title="Cetak PDF">
                            <i class="ph-bold ph-printer text-lg"></i> <span class="hidden sm:inline">Print / PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-slate-700">Data Statistik Kehadiran</h3>
                    <div class="flex gap-2">
                        
                        <div class="flex items-center gap-1 text-[10px] font-bold uppercase text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> >90%
                        </div>
                        <div class="flex items-center gap-1 text-[10px] font-bold uppercase text-amber-600 bg-amber-50 px-2 py-1 rounded border border-amber-100">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> 70-90%
                        </div>
                        <div class="flex items-center gap-1 text-[10px] font-bold uppercase text-rose-600 bg-rose-50 px-2 py-1 rounded border border-rose-100">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> <70%
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Nama Kelas</th>
                                <th class="px-6 py-4 text-center">Jml Siswa</th>
                                <th class="px-6 py-4 w-1/3">Tingkat Kehadiran</th>
                                <th class="px-6 py-4 text-center text-emerald-600">Hadir</th>
                                <th class="px-6 py-4 text-center text-amber-600">Telat</th>
                                <th class="px-6 py-4 text-center text-blue-600">Izin/Sakit</th>
                                <th class="px-6 py-4 text-center text-rose-600">Alpha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="group hover:bg-slate-50 transition-colors">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 font-bold text-sm flex items-center justify-center border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                                <?php echo e(substr($data->name, 0, 2)); ?>

                                            </div>
                                            <span class="font-bold text-slate-700"><?php echo e($data->name); ?></span>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-center font-mono text-slate-500 font-bold">
                                        <?php echo e($data->total_students); ?>

                                    </td>

                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs font-bold <?php echo e($data->rate >= 90 ? 'text-emerald-600' : ($data->rate >= 70 ? 'text-amber-600' : 'text-rose-600')); ?>">
                                                <?php echo e($data->rate); ?>%
                                            </span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 <?php echo e($data->rate >= 90 ? 'bg-emerald-500' : ($data->rate >= 70 ? 'bg-amber-500' : 'bg-rose-500')); ?>" 
                                                 style="width: <?php echo e($data->rate); ?>%"></div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 bg-emerald-50/30">
                                        <?php echo e($data->hadir); ?>

                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 bg-amber-50/30">
                                        <?php echo e($data->telat); ?>

                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 bg-blue-50/30">
                                        <?php echo e($data->izin_sakit); ?>

                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 bg-rose-50/30">
                                        <?php echo e($data->alpha); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="ph-duotone ph-chalkboard-teacher text-4xl mb-2 opacity-50"></i>
                                            <p class="font-bold">Belum ada data kelas atau absensi pada periode ini.</p>
                                        </div>
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/reports/class_attendance.blade.php ENDPATH**/ ?>