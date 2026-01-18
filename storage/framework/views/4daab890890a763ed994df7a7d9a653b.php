
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 lg:col-span-1 flex flex-col justify-center items-center relative overflow-hidden group">
        
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
            <i class="ph-duotone ph-chart-pie-slice text-9xl text-blue-500"></i>
        </div>

        <div class="h-48 w-full relative mt-2 z-10">
            <canvas id="attendanceChart"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pt-4">
                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">KEHADIRAN</span>
                <span class="text-4xl font-black text-slate-800"><?php echo e($attendancePercentage); ?><span class="text-lg text-slate-400">%</span></span>
            </div>
        </div>

        
        <div class="w-full mt-6 space-y-2 relative z-10">
            <div class="flex justify-between text-xs font-bold text-slate-500">
                <span>Target Sekolah</span>
                <span>Min. 80%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000 <?php echo e($attendancePercentage >= 90 ? 'bg-emerald-500' : ($attendancePercentage >= 80 ? 'bg-amber-500' : 'bg-rose-500')); ?>" 
                     style="width: <?php echo e($attendancePercentage); ?>%"></div>
            </div>
            <p class="text-[10px] text-center text-slate-400 mt-1 font-medium">
                <?php if($attendancePercentage >= 90): ?>
                    <span class="text-emerald-600"><i class="ph-fill ph-check-circle"></i> Sangat Baik! Pertahankan.</span>
                <?php elseif($attendancePercentage >= 80): ?>
                    <span class="text-amber-600"><i class="ph-fill ph-warning"></i> Hati-hati, jangan sering bolos.</span>
                <?php else: ?>
                    <span class="text-rose-600"><i class="ph-fill ph-warning-octagon"></i> Bahaya! Segera perbaiki kehadiran.</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    
    <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-3 gap-4">
        
        
        <div class="bg-gradient-to-br from-emerald-50 to-white p-5 rounded-[2rem] border border-emerald-100 flex flex-col justify-center items-center text-center h-full hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-emerald-500 mb-3 group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-check-circle text-2xl"></i>
            </div>
            <div class="text-3xl font-black text-emerald-700 mb-0.5"><?php echo e($hadir - $terlambat); ?></div>
            <div class="text-[10px] font-bold text-emerald-600/70 uppercase tracking-wider">Tepat Waktu</div>
        </div>

        
        <div class="bg-gradient-to-br from-amber-50 to-white p-5 rounded-[2rem] border border-amber-100 flex flex-col justify-center items-center text-center h-full hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-amber-500 mb-3 group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-clock-countdown text-2xl"></i>
            </div>
            <div class="text-3xl font-black text-amber-700 mb-0.5"><?php echo e($terlambat); ?></div>
            <div class="text-[10px] font-bold text-amber-600/70 uppercase tracking-wider">Terlambat</div>
        </div>

        
        <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-[2rem] border border-blue-100 flex flex-col justify-center items-center text-center h-full hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-blue-500 mb-3 group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-thermometer text-2xl"></i>
            </div>
            <div class="text-3xl font-black text-blue-700 mb-0.5"><?php echo e($sakit); ?></div>
            <div class="text-[10px] font-bold text-blue-600/70 uppercase tracking-wider">Sakit</div>
        </div>

        
        <div class="bg-gradient-to-br from-purple-50 to-white p-5 rounded-[2rem] border border-purple-100 flex flex-col justify-center items-center text-center h-full hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-purple-500 mb-3 group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-envelope-open text-2xl"></i>
            </div>
            <div class="text-3xl font-black text-purple-700 mb-0.5"><?php echo e($izin); ?></div>
            <div class="text-[10px] font-bold text-purple-600/70 uppercase tracking-wider">Izin</div>
        </div>

        
        <div class="col-span-2 sm:col-span-1 bg-gradient-to-br from-rose-50 to-white p-5 rounded-[2rem] border border-rose-100 flex flex-col justify-center items-center text-center h-full hover:shadow-md transition-all group relative overflow-hidden">
            
            <div class="absolute top-2 right-2 text-rose-200">
                <i class="ph-fill ph-warning text-xl"></i>
            </div>
            
            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-rose-500 mb-3 group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-x-circle text-2xl"></i>
            </div>
            <div class="text-3xl font-black text-rose-700 mb-0.5"><?php echo e($alpa); ?></div>
            <div class="text-[10px] font-bold text-rose-600/70 uppercase tracking-wider">Tanpa Ket.</div>
            
            
            <?php if($alpa > 0): ?>
                <div class="mt-2 px-2 py-0.5 bg-rose-100 rounded text-[9px] font-bold text-rose-600">
                    -<?php echo e($alpa * 10); ?> Poin
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
        <h3 class="font-bold text-slate-800 flex items-center gap-2">
            <i class="ph-fill ph-clock-counter-clockwise text-blue-600 text-lg"></i> Riwayat Terakhir
        </h3>
        <span class="text-[10px] font-bold bg-white border border-slate-200 px-3 py-1 rounded-full text-slate-500">
            5 Hari Terakhir
        </span>
    </div>

    <div class="p-6">
        <?php $__empty_1 = true; $__currentLoopData = $attendance_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            
            <div class="relative pl-8 pb-8 last:pb-0 group">
                
                <div class="absolute left-0 top-2 bottom-0 w-0.5 bg-slate-100 group-last:bg-transparent ml-[5px]"></div>
                
                
                <div class="absolute -left-[3px] top-2 w-5 h-5 rounded-full border-4 border-white shadow-sm z-10
                    <?php echo e(($log->status == 'Hadir') ? 'bg-emerald-500' : 
                       (($log->status == 'Terlambat') ? 'bg-amber-500' : 
                       (($log->status == 'Sakit') ? 'bg-blue-500' : 
                       (($log->status == 'Izin') ? 'bg-purple-500' : 'bg-rose-500')))); ?>">
                </div>

                <div class="bg-white rounded-2xl p-4 border border-slate-100 hover:border-blue-200 transition-all shadow-sm hover:shadow-md">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-slate-800 text-sm">
                                <?php echo e(\Carbon\Carbon::parse($log->attendance_date)->translatedFormat('l, d F Y')); ?>

                            </p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                <?php echo e(($log->status == 'Hadir') ? 'bg-emerald-100 text-emerald-700' : 
                                   (($log->status == 'Terlambat') ? 'bg-amber-100 text-amber-700' : 
                                   (($log->status == 'Sakit') ? 'bg-blue-100 text-blue-700' : 
                                   (($log->status == 'Izin') ? 'bg-purple-100 text-purple-700' : 'bg-rose-100 text-rose-700')))); ?>">
                                <?php echo e($log->status); ?>

                            </span>
                        </div>
                        
                        
                        <i class="ph-duotone text-3xl opacity-20 group-hover:opacity-100 transition-opacity
                            <?php echo e(($log->status == 'Hadir') ? 'ph-check-circle text-emerald-600' : 
                               (($log->status == 'Terlambat') ? 'ph-clock-countdown text-amber-600' : 
                               (($log->status == 'Sakit') ? 'ph-thermometer text-blue-600' : 
                               (($log->status == 'Izin') ? 'ph-envelope-open text-purple-600' : 'ph-x-circle text-rose-600')))); ?>">
                        </i>
                    </div>

                    
                    <?php if($log->status == 'Hadir' || $log->status == 'Terlambat'): ?>
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 text-center">
                            <span class="block text-[9px] text-slate-400 font-bold uppercase">Masuk</span>
                            <span class="text-sm font-black <?php echo e($log->status == 'Terlambat' ? 'text-amber-600' : 'text-slate-700'); ?>">
                                <?php echo e($log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '--:--'); ?>

                            </span>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 text-center">
                            <span class="block text-[9px] text-slate-400 font-bold uppercase">Pulang</span>
                            <span class="text-sm font-black text-slate-700">
                                <?php echo e($log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '--:--'); ?>

                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($log->notes): ?>
                        <div class="mt-3 text-xs text-slate-500 italic bg-slate-50/50 px-3 py-2 rounded-lg border border-slate-100 relative">
                             <i class="ph-fill ph-quotes text-slate-300 absolute top-1 right-2"></i>
                            "<?php echo e(Str::limit($log->notes, 60)); ?>"
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                    <i class="ph-duotone ph-calendar-slash text-3xl"></i>
                </div>
                <p class="text-sm font-medium">Belum ada data kehadiran.</p>
            </div>
        <?php endif; ?>
        
        <?php if($attendance_history->count() >= 5): ?>
            <div class="text-center pt-4 border-t border-slate-50 mt-2">
                <button class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center justify-center gap-1 mx-auto bg-blue-50 px-4 py-2 rounded-full">
                    Lihat Selengkapnya <i class="ph-bold ph-caret-down"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\tab-kehadiran.blade.php ENDPATH**/ ?>