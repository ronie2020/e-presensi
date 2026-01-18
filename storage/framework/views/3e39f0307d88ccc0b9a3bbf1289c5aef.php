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
    
    <?php $__env->startPush('styles'); ?>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .table-row-hover:hover td { background-color: #f8fafc; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-6 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="relative rounded-[2rem] bg-gradient-to-r from-indigo-900 to-slate-900 p-8 mb-8 text-white shadow-xl overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center gap-3">
                            <i class="ph-duotone ph-scroll text-indigo-400"></i>
                            Riwayat Perizinan
                        </h2>
                        <p class="text-indigo-200 text-sm max-w-xl">
                            Rekap data siswa yang meninggalkan kelas. Gunakan filter di bawah untuk mencari data spesifik atau mencetak laporan.
                        </p>
                    </div>
                    
                    
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('permit.export', request()->all())); ?>" target="_blank" class="group flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-sm font-bold transition border border-white/10 cursor-pointer text-indigo-100 hover:text-white">
                            <i class="ph-bold ph-microsoft-excel-logo text-emerald-400 group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline">Export Excel</span>
                        </a>
                        <a href="<?php echo e(route('permit.print', request()->all())); ?>" target="_blank" class="group flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-sm font-bold transition border border-white/10 cursor-pointer text-indigo-100 hover:text-white">
                            <i class="ph-bold ph-printer text-rose-400 group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline">Print / PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <!-- Card 1: Total Data -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-files"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Total Izin</div>
                        <div class="text-2xl font-black text-slate-800"><?php echo e($permits->total()); ?></div>
                    </div>
                </div>

                <!-- Card 2: Sedang Keluar (Estimasi Visual) -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-timer"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Status Keluar</div>
                        
                        <div class="text-xl font-bold text-slate-800">
                             <?php echo e($permits->whereNull('time_in')->count()); ?> <span class="text-xs font-normal text-slate-400">(Hlm ini)</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Sudah Kembali -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Sudah Kembali</div>
                        <div class="text-xl font-bold text-slate-800">
                            <?php echo e($permits->whereNotNull('time_in')->count()); ?> <span class="text-xs font-normal text-slate-400">(Hlm ini)</span>
                        </div>
                    </div>
                </div>
                
                 <!-- Card 4: Info Tanggal -->
                 <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-calendar-blank"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Filter Tanggal</div>
                        <div class="text-sm font-bold text-slate-800">
                            <?php echo e(request('date') ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Semua Waktu'); ?>

                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100 mb-6">
                <form action="<?php echo e(route('permit.history')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cari Siswa</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-medium placeholder:text-slate-400" 
                                placeholder="Nama atau NIS siswa...">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </div>
                        </div>
                    </div>

                    
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tanggal</label>
                        <div class="flex gap-1">
                            
                            <?php
                                $currentDate = request('date', date('Y-m-d'));
                                $prevDate = \Carbon\Carbon::parse($currentDate)->subDay()->format('Y-m-d');
                                $nextDate = \Carbon\Carbon::parse($currentDate)->addDay()->format('Y-m-d');
                            ?>
                            
                            <a href="<?php echo e(route('permit.history', array_merge(request()->all(), ['date' => $prevDate]))); ?>" 
                               class="px-3 py-2.5 bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 rounded-xl transition border border-slate-200">
                                <i class="ph-bold ph-caret-left"></i>
                            </a>

                            <input type="date" name="date" value="<?php echo e($currentDate); ?>" 
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-medium text-slate-600">

                            
                            <a href="<?php echo e(route('permit.history', array_merge(request()->all(), ['date' => $nextDate]))); ?>" 
                               class="px-3 py-2.5 bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 rounded-xl transition border border-slate-200">
                                <i class="ph-bold ph-caret-right"></i>
                            </a>
                        </div>
                    </div>

                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-medium text-slate-600">
                            <option value="">Semua</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Di Luar</option>
                            <option value="returned" <?php echo e(request('status') == 'returned' ? 'selected' : ''); ?>>Kembali</option>
                            <option value="overdue" <?php echo e(request('status') == 'overdue' ? 'selected' : ''); ?>>Telat</option>
                        </select>
                    </div>

                    
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-funnel"></i> Filter
                        </button>
                        <a href="<?php echo e(route('permit.history')); ?>" class="px-3 py-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition flex items-center justify-center" title="Reset Filter">
                            <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                        </a>
                    </div>
                </form>
            </div>

            
            <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-left">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu Keluar</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Keperluan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Waktu Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="table-row-hover transition-colors">
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?></span>
                                        <span class="text-[10px] text-slate-400 font-mono"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('d M Y')); ?></span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100">
                                            <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 text-sm"><?php echo e($permit->student->name); ?></div>
                                            <div class="text-xs text-slate-500"><?php echo e($permit->student->schoolClass->name ?? '-'); ?> • <?php echo e($permit->student->student_id); ?></div>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                            <?php echo e($permit->reason_category); ?>

                                        </span>
                                        <?php if($permit->notes): ?>
                                            <span class="text-xs text-slate-500 italic max-w-[200px] truncate" title="<?php echo e($permit->notes); ?>">
                                                "<?php echo e($permit->notes); ?>"
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <?php
                                        // Hitung durasi: Jika sudah kembali pakai data DB, jika belum hitung selisih real-time
                                        $duration = $permit->time_in 
                                            ? $permit->duration_minutes 
                                            : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                        
                                        $isLongDuration = $duration > 15; // Threshold 15 menit
                                    ?>

                                    <div class="font-mono font-bold <?php echo e($isLongDuration && !$permit->time_in ? 'text-rose-500 animate-pulse' : 'text-slate-600'); ?>">
                                        <?php echo e($duration); ?> <span class="text-xs font-normal text-slate-400">menit</span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <?php if($permit->time_in): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <i class="ph-bold ph-check"></i> Kembali
                                        </span>
                                    <?php else: ?>
                                        <?php if($isLongDuration): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200 shadow-sm animate-pulse">
                                                <i class="ph-bold ph-warning"></i> Telat
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                                <i class="ph-bold ph-timer"></i> Di Luar
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="px-6 py-4 text-right">
                                    <?php if($permit->time_in): ?>
                                        <span class="font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($permit->time_in)->format('H:i')); ?></span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400 italic">Belum kembali</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="ph-duotone ph-magnifying-glass text-3xl"></i>
                                        </div>
                                        <p class="font-bold text-slate-600">Data tidak ditemukan</p>
                                        <p class="text-sm">Coba ubah filter pencarian atau tanggal.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
                    <?php echo e($permits->withQueryString()->links()); ?>

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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\permit\history.blade.php ENDPATH**/ ?>