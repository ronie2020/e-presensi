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
        .status-border-left { border-left: 4px solid transparent; transition: border-color 0.2s; }
        .tr-active:hover .status-border-left { border-left-color: #f9a282; } /* elevate-peach */
        .tr-returned:hover .status-border-left { border-left-color: #10b981; } /* emerald-500 */
        .tr-overdue:hover .status-border-left { border-left-color: #e11d48; } /* rose-600 */
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden min-h-screen">
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">

            
            <div class="animate-enter relative rounded-[2.5rem] bg-elevate-gradient-main p-8 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden group border border-white/60 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-[80px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col justify-between items-start gap-6 w-full">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/50 border border-white/60 text-elevate-primary text-[10px] font-bold uppercase tracking-wider mb-3 backdrop-blur-sm shadow-sm">
                            <i class="ph-bold ph-archive"></i> Arsip Digital
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 leading-tight">
                            Riwayat Perizinan
                        </h2>
                        <p class="text-elevate-dark/80 text-sm max-w-xl leading-relaxed font-semibold">
                            Pantau jejak aktivitas keluar-masuk siswa secara lengkap dan terperinci.
                        </p>
                    </div>
                    
                    
                    <div class="bg-white/40 backdrop-blur-md p-2 rounded-2xl border border-white/60 shadow-sm flex gap-2 w-full sm:w-auto mt-2">
                        <a href="<?php echo e(route('permit.export', request()->all())); ?>" target="_blank" class="flex-1 sm:flex-none justify-center group flex items-center gap-2 px-5 py-3.5 bg-emerald-50 hover:bg-emerald-600 border border-emerald-200 rounded-xl text-sm font-bold transition-all cursor-pointer text-emerald-700 hover:text-white shadow-sm hover:shadow-lg hover:shadow-emerald-600/30">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                            <span>Excel</span>
                        </a>
                        <a href="<?php echo e(route('permit.print', request()->all())); ?>" target="_blank" class="flex-1 sm:flex-none justify-center group flex items-center gap-2 px-5 py-3.5 bg-white hover:bg-elevate-dark border border-slate-200 rounded-xl text-sm font-bold transition-all cursor-pointer text-elevate-dark hover:text-white shadow-sm hover:shadow-lg hover:shadow-elevate-dark/30">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span>Print</span>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-enter delay-100">
                <div class="group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/30 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-elevate-dark/50 font-bold uppercase tracking-widest group-hover:text-elevate-primary transition-colors mt-1">Total Izin</div>
                        <div class="w-12 h-12 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-elevate-accent/20">
                            <i class="ph-duotone ph-files text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($permits->total()); ?></div>
                    <div class="text-[10px] text-elevate-dark/40 mt-1 font-bold uppercase tracking-wider">Data Sesuai Filter</div>
                </div>

                <div class="group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-elevate-peach/10 hover:border-elevate-peach/30 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-elevate-dark/50 font-bold uppercase tracking-widest group-hover:text-elevate-peach-dark transition-colors mt-1">Sedang Keluar</div>
                        <div class="w-12 h-12 rounded-2xl bg-elevate-peach-light/40 text-elevate-peach-dark flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-elevate-peach/30">
                            <i class="ph-duotone ph-timer text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($permits->whereNull('time_in')->count()); ?></div>
                    <div class="text-[10px] text-elevate-dark/40 mt-1 font-bold uppercase tracking-wider">Belum Kembali</div>
                </div>

                <div class="group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:border-emerald-200 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-elevate-dark/50 font-bold uppercase tracking-widest group-hover:text-emerald-600 transition-colors mt-1">Sudah Kembali</div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-emerald-100">
                            <i class="ph-duotone ph-check-circle text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($permits->whereNotNull('time_in')->count()); ?></div>
                    <div class="text-[10px] text-elevate-dark/40 mt-1 font-bold uppercase tracking-wider">Proses Selesai</div>
                </div>

                <div class="group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/30 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-elevate-dark/50 font-bold uppercase tracking-widest group-hover:text-elevate-primary transition-colors mt-1">Tanggal Data</div>
                        <div class="w-12 h-12 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-elevate-accent/20">
                            <i class="ph-duotone ph-calendar-blank text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-lg font-black text-elevate-dark mt-1 truncate">
                        <?php echo e(request('date') ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Semua Waktu'); ?>

                    </div>
                    <div class="text-[10px] text-elevate-dark/40 mt-1 font-bold uppercase tracking-wider">Filter Terpilih</div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden animate-enter delay-200 flex flex-col min-h-[500px]">
                
                
                <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
                    <form action="<?php echo e(route('permit.history')); ?>" method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                        
                        
                        <div class="md:col-span-4 relative group">
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-wide mb-2 ml-1">Cari Siswa</label>
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold placeholder:text-slate-400 shadow-sm transition-all text-elevate-dark" 
                                    placeholder="Nama atau NIS...">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:col-span-5">
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-wide mb-2 ml-1">Tanggal</label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <div class="relative flex-1 group">
                                    <input type="date" name="date" id="dateInput" value="<?php echo e(request('date', date('Y-m-d'))); ?>" 
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark shadow-sm transition-all cursor-pointer">
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="button" onclick="setDate('<?php echo e(date('Y-m-d')); ?>')" class="px-5 py-3.5 bg-elevate-soft hover:bg-elevate-primary hover:text-white text-elevate-primary border border-elevate-accent/20 rounded-2xl text-xs font-bold transition-all shadow-sm">Hari Ini</button>
                                    <button type="button" onclick="setDate('<?php echo e(date('Y-m-d', strtotime('-1 days'))); ?>')" class="px-5 py-3.5 bg-slate-100 hover:bg-elevate-primary hover:text-white text-slate-500 hover:border-elevate-accent/20 border border-slate-200 rounded-2xl text-xs font-bold transition-all shadow-sm">Kemarin</button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-wide mb-2 ml-1">Status</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1 group">
                                    <select name="status" class="w-full appearance-none pl-4 pr-10 py-3.5 rounded-2xl border border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark shadow-sm cursor-pointer transition-all">
                                        <option value="">Semua Status</option>
                                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Di Luar</option>
                                        <option value="returned" <?php echo e(request('status') == 'returned' ? 'selected' : ''); ?>>Kembali</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                                <button type="submit" class="bg-elevate-dark hover:bg-elevate-primary text-white px-5 rounded-2xl transition-all shadow-lg shadow-elevate-dark/30 active:scale-95 flex items-center justify-center border border-transparent">
                                    <i class="ph-bold ph-funnel text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="overflow-x-auto custom-scrollbar flex-1">
                    <table class="w-full whitespace-nowrap text-left text-sm">
                        <thead class="bg-elevate-soft/50 backdrop-blur-sm border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-[10px] font-black text-elevate-primary uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-5 text-[10px] font-black text-elevate-primary uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-5 text-[10px] font-black text-elevate-primary uppercase tracking-widest">Keperluan</th>
                                <th class="px-6 py-5 text-[10px] font-black text-elevate-primary uppercase tracking-widest">Durasi</th>
                                <th class="px-6 py-5 text-[10px] font-black text-elevate-primary uppercase tracking-widest">Status</th>
                                <th class="px-6 py-5 text-[10px] font-black text-elevate-primary uppercase tracking-widest text-right">Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $isReturned = $permit->time_in != null;
                                    $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                    $isOverdue = $duration > 15 && !$isReturned;
                                    
                                    $rowClass = $isReturned ? 'tr-returned' : ($isOverdue ? 'tr-overdue' : 'tr-active');
                                ?>
                            <tr class="transition-colors group <?php echo e($rowClass); ?> status-border-left hover:bg-elevate-soft/30">
                                
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-black text-elevate-dark"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?></span>
                                        <span class="text-[10px] text-elevate-dark/50 font-bold uppercase tracking-wide"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('d M')); ?></span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-base shadow-sm transition-transform group-hover:scale-105 border
                                            <?php echo e($isOverdue ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-elevate-soft text-elevate-primary border-elevate-accent/20'); ?>">
                                            <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors"><?php echo e($permit->student->name); ?></div>
                                            <div class="text-xs text-elevate-dark/60 font-medium flex items-center gap-1.5 mt-0.5">
                                                <span><?php echo e($permit->student->schoolClass->name ?? '-'); ?></span>
                                                <span class="text-slate-300">•</span> 
                                                <span class="font-mono"><?php echo e($permit->student->student_id); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-5">
                                    <div class="flex flex-col items-start gap-1.5">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-white text-elevate-dark border border-slate-200 shadow-sm">
                                            <?php echo e($permit->reason_category); ?>

                                        </span>
                                        <?php if($permit->notes): ?>
                                            <span class="text-xs text-elevate-dark/50 italic max-w-[150px] truncate font-medium" title="<?php echo e($permit->notes); ?>">
                                                "<?php echo e($permit->notes); ?>"
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-5">
                                    <div class="font-mono font-bold text-lg <?php echo e($isOverdue ? 'text-rose-600 animate-pulse' : 'text-elevate-dark/80'); ?>">
                                        <?php echo e($duration); ?><span class="text-[10px] text-elevate-dark/40 ml-0.5 font-sans font-bold">mnt</span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-5">
                                    <?php if($isReturned): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm">
                                            <i class="ph-bold ph-check"></i> Kembali
                                        </span>
                                    <?php else: ?>
                                        <?php if($isOverdue): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase bg-rose-50 text-rose-600 border border-rose-200 shadow-sm animate-pulse">
                                                <i class="ph-bold ph-warning"></i> Telat
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase bg-elevate-peach-light/40 text-elevate-peach-dark border border-elevate-peach/30 shadow-sm">
                                                <i class="ph-bold ph-timer"></i> Di Luar
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="px-6 py-5 text-right">
                                    <?php if($permit->time_in): ?>
                                        <span class="font-black text-elevate-dark bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200 shadow-sm inline-block"><?php echo e(\Carbon\Carbon::parse($permit->time_in)->format('H:i')); ?></span>
                                    <?php else: ?>
                                        <span class="text-slate-300 italic text-xl px-2">...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center text-elevate-dark/50">
                                        <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mb-4 border border-elevate-accent/20 shadow-inner">
                                            <i class="ph-duotone ph-magnifying-glass text-5xl text-elevate-primary opacity-80"></i>
                                        </div>
                                        <p class="font-black text-elevate-dark text-xl mb-1">Data tidak ditemukan</p>
                                        <p class="text-sm font-medium">Coba sesuaikan filter tanggal atau kata kunci pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <?php if($permits->hasPages()): ?>
                <div class="bg-slate-50/50 px-8 py-6 border-t border-slate-100">
                    <?php echo e($permits->withQueryString()->links()); ?>

                </div>
                <?php endif; ?>
            </div>

            
            <div class="md:hidden space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isReturned = $permit->time_in != null;
                        $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                        $isOverdue = $duration > 15 && !$isReturned;
                        $borderColor = $isReturned ? 'border-emerald-500' : ($isOverdue ? 'border-rose-500' : 'border-elevate-peach');
                    ?>
                    <div class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-slate-100 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($borderColor); ?>"></div>
                        
                        <div class="flex justify-between items-start mb-4 pl-2">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-elevate-soft flex items-center justify-center font-black text-elevate-primary text-base shadow-sm border border-elevate-accent/20">
                                    <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="font-bold text-elevate-dark text-sm"><?php echo e($permit->student->name); ?></div>
                                    <div class="text-[10px] text-elevate-dark/60 font-mono mt-0.5 font-bold"><?php echo e($permit->student->student_id); ?></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-black text-elevate-dark text-lg leading-none"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?></div>
                                <div class="text-[10px] text-elevate-dark/50 uppercase font-bold mt-1"><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('d M')); ?></div>
                            </div>
                        </div>
                        
                        <div class="pl-2 space-y-3">
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <div>
                                    <div class="text-[9px] uppercase font-bold text-elevate-dark/50 mb-0.5">Keperluan</div>
                                    <div class="text-xs font-black text-elevate-dark"><?php echo e($permit->reason_category); ?></div>
                                </div>
                                <?php if($permit->notes): ?>
                                <div class="text-right max-w-[50%]">
                                    <div class="text-[9px] uppercase font-bold text-elevate-dark/50 mb-0.5">Catatan</div>
                                    <div class="text-[10px] italic text-elevate-dark/70 truncate font-medium"><?php echo e($permit->notes); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="font-bold text-xs text-elevate-dark/60">
                                    Durasi: <span class="<?php echo e($isOverdue ? 'text-rose-600' : 'text-elevate-dark'); ?> font-mono font-black text-sm ml-1"><?php echo e($duration); ?>m</span>
                                </div>
                                <div>
                                    <?php if($isReturned): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                            Kembali <?php echo e(\Carbon\Carbon::parse($permit->time_in)->format('H:i')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl <?php echo e($isOverdue ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-elevate-peach-light/40 text-elevate-peach-dark border-elevate-peach/30'); ?> text-[10px] font-bold border">
                                            <?php echo e($isOverdue ? 'Telat' : 'Di Luar'); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-10 text-center text-elevate-dark/50 bg-white rounded-[2rem] border border-slate-200 border-dashed">
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/permit/history.blade.php ENDPATH**/ ?>