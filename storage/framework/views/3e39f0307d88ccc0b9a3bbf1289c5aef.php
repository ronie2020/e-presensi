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
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>        
        body, .font-sans { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Table Styles */
        .table-row-hover:hover td { background-color: #f8fafc; }
        .status-border-left { border-left: 3px solid transparent; transition: border-color 0.2s; }
        .tr-active:hover .status-border-left { border-left-color: #f97316; } 
        .tr-returned:hover .status-border-left { border-left-color: #10b981; } 
        .tr-overdue:hover .status-border-left { border-left-color: #f43f5e; } 

        /* Glass Utility */
        .glass-panel { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-6 font-sans text-slate-800 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-slate-800 to-slate-900 p-6 md:p-10 text-white shadow-2xl shadow-blue-900/20 overflow-hidden group border border-white/10">
                
                
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-blue-200 text-[10px] font-bold uppercase tracking-wider mb-3 backdrop-blur-sm shadow-sm">
                            <i class="ph-bold ph-archive"></i> Arsip Digital
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 leading-tight">
                            Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Perizinan</span>
                        </h2>
                        <p class="text-blue-100/80 text-sm max-w-xl leading-relaxed">
                            Pantau jejak aktivitas keluar-masuk siswa secara lengkap dan terperinci.
                        </p>
                    </div>
                    
                    
                    <div class="glass-panel p-2 rounded-2xl flex gap-2 w-full md:w-auto">
                        <a href="<?php echo e(route('permit.export', request()->all())); ?>" target="_blank" class="flex-1 md:flex-none justify-center group flex items-center gap-2 px-5 py-3 bg-emerald-500/20 hover:bg-emerald-500 border border-emerald-400/30 rounded-xl text-sm font-bold transition-all cursor-pointer text-emerald-300 hover:text-white hover:shadow-lg hover:shadow-emerald-500/30">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                            <span>Excel</span>
                        </a>
                        <a href="<?php echo e(route('permit.print', request()->all())); ?>" target="_blank" class="flex-1 md:flex-none justify-center group flex items-center gap-2 px-5 py-3 bg-white/10 hover:bg-white border border-white/20 rounded-xl text-sm font-bold transition-all cursor-pointer text-white hover:text-slate-900 hover:shadow-lg hover:shadow-white/20">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span>Print</span>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-enter delay-100">
                <!-- Card 1 -->
                <div class="group bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Total Izin</div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-files text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?php echo e($permits->total()); ?></div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Data sesuai filter</div>
                </div>

                <!-- Card 2 -->
                <div class="group bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-orange-600 transition-colors">Sedang Keluar</div>
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-timer text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?php echo e($permits->whereNull('time_in')->count()); ?></div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Siswa belum kembali</div>
                </div>

                <!-- Card 3 -->
                <div class="group bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-emerald-600 transition-colors">Sudah Kembali</div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-check-circle text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?php echo e($permits->whereNotNull('time_in')->count()); ?></div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Proses selesai</div>
                </div>

                <!-- Card 4 -->
                <div class="group bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-blue-600 transition-colors">Tanggal Data</div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-calendar-blank text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-base font-bold text-slate-800 mt-1 truncate">
                        <?php echo e(request('date') ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Semua Waktu'); ?>

                    </div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Filter terpilih</div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden animate-enter delay-200">
                
                
                <div class="p-6 md:p-8 border-b border-slate-50 bg-slate-50/30">
                    <form action="<?php echo e(route('permit.history')); ?>" method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                        
                        
                        <div class="md:col-span-4 relative group">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Cari Siswa</label>
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-indigo-500 focus:ring-0 text-sm font-bold placeholder:text-slate-300 shadow-sm group-hover:border-indigo-300 transition-colors" 
                                    placeholder="Nama atau NIS...">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:col-span-5">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Tanggal</label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <div class="relative flex-1 group">
                                    <input type="date" name="date" id="dateInput" value="<?php echo e(request('date', date('Y-m-d'))); ?>" 
                                        class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-indigo-500 focus:ring-0 text-sm font-bold text-slate-600 shadow-sm group-hover:border-indigo-300 transition-colors cursor-pointer">
                                </div>
                                <div class="flex gap-1.5">
                                    <button type="button" onclick="setDate('<?php echo e(date('Y-m-d')); ?>')" class="px-4 py-2 bg-slate-100 hover:bg-indigo-100 text-slate-500 hover:text-indigo-600 border border-slate-200 hover:border-indigo-200 rounded-xl text-xs font-bold transition-all shadow-sm">Hari Ini</button>
                                    <button type="button" onclick="setDate('<?php echo e(date('Y-m-d', strtotime('-1 days'))); ?>')" class="px-4 py-2 bg-slate-100 hover:bg-indigo-100 text-slate-500 hover:text-indigo-600 border border-slate-200 hover:border-indigo-200 rounded-xl text-xs font-bold transition-all shadow-sm">Kemarin</button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Status</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <select name="status" class="w-full appearance-none px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-indigo-500 focus:ring-0 text-sm font-bold text-slate-600 shadow-sm cursor-pointer">
                                        <option value="">Semua Status</option>
                                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Di Luar</option>
                                        <option value="returned" <?php echo e(request('status') == 'returned' ? 'selected' : ''); ?>>Kembali</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                                <button type="submit" class="bg-indigo-600 text-white px-4 rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 active:scale-95 flex items-center justify-center">
                                    <i class="ph-bold ph-funnel text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full whitespace-nowrap text-left text-sm">
                        <thead class="bg-slate-50/80 backdrop-blur-sm border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keperluan</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $isReturned = $permit->time_in != null;
                                    $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                    $isOverdue = $duration > 15 && !$isReturned;
                                    
                                    // Tentukan class baris berdasarkan status untuk styling border kiri
                                    $rowClass = $isReturned ? 'tr-returned' : ($isOverdue ? 'tr-overdue' : 'tr-active');
                                ?>
                            <tr class="table-row-hover transition-colors group <?php echo e($rowClass); ?> status-border-left hover:bg-slate-50">
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?></span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('d M')); ?></span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm border shadow-sm transition-transform group-hover:scale-105
                                            <?php echo e($isOverdue ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 border-slate-200'); ?>">
                                            <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors"><?php echo e($permit->student->name); ?></div>
                                            <div class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                                <span><?php echo e($permit->student->schoolClass->name ?? '-'); ?></span>
                                                <span class="text-slate-300">•</span> 
                                                <span class="font-mono"><?php echo e($permit->student->student_id); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-white text-slate-600 border border-slate-200 shadow-sm">
                                            <?php echo e($permit->reason_category); ?>

                                        </span>
                                        <?php if($permit->notes): ?>
                                            <span class="text-xs text-slate-400 italic max-w-[150px] truncate" title="<?php echo e($permit->notes); ?>">
                                                "<?php echo e($permit->notes); ?>"
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <div class="font-mono font-bold text-base <?php echo e($isOverdue ? 'text-rose-500 animate-pulse' : 'text-slate-600'); ?>">
                                        <?php echo e($duration); ?><span class="text-[10px] text-slate-400 ml-0.5 font-sans font-bold">m</span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4">
                                    <?php if($isReturned): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-200 shadow-sm">
                                            <i class="ph-bold ph-check"></i> Kembali
                                        </span>
                                    <?php else: ?>
                                        <?php if($isOverdue): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-600 border border-rose-200 shadow-sm animate-pulse">
                                                <i class="ph-bold ph-warning"></i> Telat
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase bg-orange-50 text-orange-600 border border-orange-200 shadow-sm">
                                                <i class="ph-bold ph-timer"></i> Di Luar
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="px-6 py-4 text-right">
                                    <?php if($permit->time_in): ?>
                                        <span class="font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-lg border border-slate-200"><?php echo e(\Carbon\Carbon::parse($permit->time_in)->format('H:i')); ?></span>
                                    <?php else: ?>
                                        <span class="text-slate-300 italic text-xl px-2">...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="ph-duotone ph-magnifying-glass text-4xl opacity-50"></i>
                                        </div>
                                        <p class="font-bold text-slate-600 text-lg">Data tidak ditemukan</p>
                                        <p class="text-sm opacity-70 mt-1">Coba sesuaikan filter tanggal atau kata kunci.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="bg-slate-50/50 px-6 py-6 border-t border-slate-100">
                    <?php echo e($permits->withQueryString()->links()); ?>

                </div>
            </div>

            
            <div class="md:hidden space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isReturned = $permit->time_in != null;
                        $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                        $isOverdue = $duration > 15 && !$isReturned;
                        $borderColor = $isReturned ? 'border-emerald-500' : ($isOverdue ? 'border-rose-500' : 'border-orange-500');
                    ?>
                    <div class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-slate-100 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($borderColor); ?>"></div>
                        
                        <div class="flex justify-between items-start mb-3 pl-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-sm">
                                    <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="font-bold text-slate-800"><?php echo e($permit->student->name); ?></div>
                                    <div class="text-xs text-slate-500 font-mono"><?php echo e($permit->student->student_id); ?></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold text-slate-700 text-lg"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?></div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('d M')); ?></div>
                            </div>
                        </div>
                        
                        <div class="pl-2 space-y-3">
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <div>
                                    <div class="text-[10px] uppercase font-bold text-slate-400">Keperluan</div>
                                    <div class="text-sm font-bold text-slate-700"><?php echo e($permit->reason_category); ?></div>
                                </div>
                                <?php if($permit->notes): ?>
                                <div class="text-right max-w-[50%]">
                                    <div class="text-[10px] uppercase font-bold text-slate-400">Catatan</div>
                                    <div class="text-xs italic text-slate-500 truncate"><?php echo e($permit->notes); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="font-bold text-xs text-slate-500">
                                    Durasi: <span class="<?php echo e($isOverdue ? 'text-rose-600' : 'text-slate-800'); ?> font-mono text-sm"><?php echo e($duration); ?>m</span>
                                </div>
                                <div>
                                    <?php if($isReturned): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                            Kembali <?php echo e(\Carbon\Carbon::parse($permit->time_in)->format('H:i')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg <?php echo e($isOverdue ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-orange-100 text-orange-700 border-orange-200'); ?> text-[10px] font-bold border">
                                            <?php echo e($isOverdue ? 'Telat' : 'Di Luar'); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-10 text-center text-slate-400 bg-white rounded-[2rem] border border-slate-100 border-dashed">
                        <i class="ph-duotone ph-magnifying-glass text-4xl opacity-50 mb-3"></i>
                        <p class="font-bold text-sm">Tidak ada data ditemukan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function setDate(dateStr) {
            document.getElementById('dateInput').value = dateStr;
            document.getElementById('filterForm').submit();
        }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\permit\history.blade.php ENDPATH**/ ?>