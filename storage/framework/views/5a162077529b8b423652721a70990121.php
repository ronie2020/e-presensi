<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    
    <div class="lg:col-span-1 space-y-6">
        
        
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-rose-100">
            <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                <i class="ph-fill ph-warning-octagon text-rose-500"></i> Indisipliner
            </h3>
            <div class="bg-rose-50 rounded-2xl p-5 border border-rose-100 text-center mt-4 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-20 h-20 bg-rose-200/50 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-rose-300/50 transition-all"></div>
                
                <p class="text-5xl font-black text-rose-600 relative z-10"><?php echo e($total_violation_points ?? 0); ?></p>
                <p class="text-xs text-rose-400 mt-2 font-bold uppercase tracking-widest relative z-10">Total Poin (-)</p>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100">
            <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                <i class="ph-fill ph-medal text-emerald-500"></i> Prestasi
            </h3>
            <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100 text-center mt-4 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-200/50 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-emerald-300/50 transition-all"></div>
                
                <p class="text-5xl font-black text-emerald-600 relative z-10"><?php echo e($total_merit_points ?? 0); ?></p>
                <p class="text-xs text-emerald-500 mt-2 font-bold uppercase tracking-widest relative z-10">Total Poin (+)</p>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-center">
            <p class="text-xs text-slate-400 font-bold mb-1">SKOR PERILAKU SAAT INI</p>
            
            <?php $score = 200 - ($total_violation_points ?? 0) + ($total_merit_points ?? 0); ?>
            <p class="text-3xl font-black <?php echo e($score >= 180 ? 'text-emerald-600' : ($score >= 140 ? 'text-blue-600' : 'text-rose-600')); ?>">
                <?php echo e($score); ?>

            </p>
        </div>
    </div>

    
    <div class="lg:col-span-2 space-y-8">
        
        
        <div class="bg-white rounded-3xl shadow-sm border border-rose-100 p-6 md:p-8">
            <h4 class="font-black text-rose-800 mb-6 flex items-center gap-2 text-lg pb-4 border-b border-rose-50">
                <i class="ph-duotone ph-clock-counter-clockwise"></i> Riwayat Pelanggaran
            </h4>

            <?php if(isset($violations) && count($violations) > 0): ?>
                <div class="relative border-l-2 border-slate-200 ml-3 space-y-8">
                    <?php $__currentLoopData = $violations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative pl-8 group">
                            <!-- Dot Timeline (Red) -->
                            <div class="absolute -left-[9px] top-0 w-5 h-5 bg-rose-100 border-2 border-rose-500 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
                            
                            <!-- Content Header -->
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-1">
                                <h4 class="font-bold text-slate-800 text-lg group-hover:text-rose-600 transition-colors">
                                    <?php echo e($record->disciplineType->name ?? 'Jenis Pelanggaran Dihapus'); ?>

                                </h4>
                                <span class="text-xs font-bold px-2 py-1 bg-rose-50 text-rose-600 rounded-lg border border-rose-100 whitespace-nowrap">
                                    -<?php echo e($record->disciplineType->point_value ?? 0); ?> Poin
                                </span>
                            </div>
                            
                            <!-- Tanggal -->
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-calendar-blank"></i>
                                <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y')); ?>

                            </p>
                            
                            <!-- Catatan -->
                            <?php if($record->notes): ?>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-600 italic relative">
                                    <i class="ph-fill ph-warning-circle text-rose-200 text-2xl absolute top-2 right-2"></i>
                                    "<?php echo e($record->notes); ?>"
                                </div>
                            <?php endif; ?>

                             <!-- Guru Pencatat -->
                             <?php if($record->recorder): ?>
                                <div class="mt-2 flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                    <i class="ph-fill ph-user-circle"></i> Dicatat oleh: <?php echo e($record->recorder->name ?? 'Guru'); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-8 text-center bg-rose-50/30 rounded-2xl border border-dashed border-rose-200">
                    <div class="text-rose-300 text-sm font-bold">Tidak ada catatan pelanggaran. Bagus!</div>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-3xl shadow-sm border border-emerald-100 p-6 md:p-8">
            <h4 class="font-black text-emerald-800 mb-6 flex items-center gap-2 text-lg pb-4 border-b border-emerald-50">
                <i class="ph-duotone ph-star"></i> Riwayat Prestasi & Kebaikan
            </h4>

            <?php if(isset($merits) && count($merits) > 0): ?>
                <div class="relative border-l-2 border-slate-200 ml-3 space-y-8">
                    <?php $__currentLoopData = $merits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative pl-8 group">
                            <!-- Dot Timeline (Green) -->
                            <div class="absolute -left-[9px] top-0 w-5 h-5 bg-emerald-100 border-2 border-emerald-500 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-1">
                                <h4 class="font-bold text-slate-800 text-lg group-hover:text-emerald-600 transition-colors">
                                    <?php echo e($record->disciplineType->name ?? 'Jenis Dihapus'); ?>

                                </h4>
                                <span class="text-xs font-bold px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-100 whitespace-nowrap">
                                    +<?php echo e($record->disciplineType->point_value ?? 0); ?> Poin
                                </span>
                            </div>
                            
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-calendar-blank"></i>
                                <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y')); ?>

                            </p>
                            
                            <?php if($record->notes): ?>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-600 italic relative">
                                    <i class="ph-fill ph-thumbs-up text-emerald-200 text-2xl absolute top-2 right-2"></i>
                                    "<?php echo e($record->notes); ?>"
                                </div>
                            <?php endif; ?>

                             <?php if($record->recorder): ?>
                                <div class="mt-2 flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                    <i class="ph-fill ph-user-circle"></i> Dicatat oleh: <?php echo e($record->recorder->name ?? 'Guru'); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                 <div class="flex flex-col items-center justify-center py-8 text-center bg-emerald-50/30 rounded-2xl border border-dashed border-emerald-200">
                    <div class="text-emerald-400 text-sm font-bold">Belum ada catatan prestasi. Ayo tingkatkan!</div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-disiplin.blade.php ENDPATH**/ ?>