<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    
    <div class="lg:col-span-1 space-y-6">
        
        
        <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>
            <p class="text-xs text-slate-400 font-bold mb-2 uppercase tracking-widest">SKOR PERILAKU ANDA</p>
            
            <?php 
                $score = $finalScore ?? 100; 
                $scoreColor = $score >= 100 ? 'text-emerald-600' : ($score >= 80 ? 'text-blue-600' : 'text-rose-600');
            ?>
            
            <p class="text-6xl font-black <?php echo e($scoreColor); ?> tracking-tighter">
                <?php echo e($score); ?>

            </p>
            
            <div class="mt-4 flex justify-center gap-2">
                <?php if($score >= 100): ?>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">Sangat Baik</span>
                <?php elseif($score >= 80): ?>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase">Cukup Baik</span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-rose-100 text-rose-700 text-[10px] font-bold rounded-full uppercase animate-pulse">Perlu Perhatian</span>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-rose-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-warning-octagon text-rose-500 text-xl"></i> Poin Minus
                </h3>
                <span class="bg-rose-50 text-rose-600 text-[10px] font-black px-2 py-1 rounded-lg">INDISIPLINER</span>
            </div>
            <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100 text-center relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-16 h-16 bg-rose-200/50 rounded-full blur-2xl -mr-8 -mt-8"></div>
                <p class="text-4xl font-black text-rose-600 relative z-10"><?php echo e($total_violation_points ?? 0); ?></p>
                <p class="text-[10px] text-rose-400 mt-1 font-bold uppercase tracking-wider relative z-10">Total Pengurangan</p>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-emerald-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-medal text-emerald-500 text-xl"></i> Poin Plus
                </h3>
                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black px-2 py-1 rounded-lg">PRESTASI</span>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 text-center relative overflow-hidden group mb-3">
                <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-200/50 rounded-full blur-2xl -mr-8 -mt-8"></div>
                <p class="text-4xl font-black text-emerald-600 relative z-10">+<?php echo e($total_merit_points ?? 0); ?></p>
                <p class="text-[10px] text-emerald-500 mt-1 font-bold uppercase tracking-wider relative z-10">Total Penambahan</p>
            </div>
            
            
            <button @click="activeTab = 'prestasi'" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2">
                <i class="ph-bold ph-list-magnifying-glass"></i> Lihat Detail Prestasi
            </button>
        </div>

    </div>

    
    <div class="lg:col-span-2">
        
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 md:p-8 h-full">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                <h4 class="font-black text-slate-800 flex items-center gap-3 text-xl">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="ph-duotone ph-clock-counter-clockwise text-2xl"></i>
                    </div>
                    Riwayat Pelanggaran
                </h4>
                <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-200">
                    Tercatat: <?php echo e(isset($violations) ? count($violations) : 0); ?>

                </span>
            </div>

            <?php if(isset($violations) && count($violations) > 0): ?>
                <div class="relative border-l-2 border-slate-100 ml-3 space-y-8 pb-4">
                    <?php $__currentLoopData = $violations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative pl-8 group">
                            <div class="absolute -left-[9px] top-0 w-5 h-5 bg-white border-4 border-rose-500 rounded-full group-hover:scale-110 transition-transform duration-300 shadow-sm"></div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-1.5">
                                <h4 class="font-bold text-slate-800 text-base sm:text-lg group-hover:text-rose-600 transition-colors">
                                    <?php echo e($record->disciplineType->name ?? 'Pelanggaran Umum'); ?>

                                </h4>
                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-1 bg-rose-50 text-rose-600 rounded-lg border border-rose-100 whitespace-nowrap w-fit">
                                    <i class="ph-bold ph-minus"></i> <?php echo e($record->disciplineType->point_value ?? ($record->point ?? 0)); ?> Poin
                                </span>
                            </div>
                            
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                                <i class="ph-fill ph-calendar-blank"></i>
                                <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y')); ?>

                            </p>
                            
                            <div class="bg-rose-50/50 p-4 rounded-2xl border border-rose-100 text-sm text-slate-600 relative group-hover:bg-rose-50 transition-colors">
                                <?php if(isset($record->notes) && $record->notes): ?>
                                    <div class="flex gap-3">
                                        <i class="ph-fill ph-warning-circle text-rose-300 text-xl shrink-0 mt-0.5"></i>
                                        <span class="italic">"<?php echo e($record->notes); ?>"</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400 italic text-xs">Tidak ada catatan tambahan.</span>
                                <?php endif; ?>
                            </div>

                             <?php if(isset($record->recorder)): ?>
                                <div class="mt-2 flex items-center gap-1.5 text-[10px] text-slate-400 font-medium ml-1">
                                    <i class="ph-fill ph-user-circle text-slate-300"></i> 
                                    Dicatat oleh: <span class="text-slate-500 font-bold"><?php echo e($record->recorder->name ?? 'Sistem'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mb-4 animate-bounce">
                        <i class="ph-duotone ph-shield-check text-5xl text-emerald-400"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Bersih & Disiplin!</h3>
                    <p class="text-slate-500 text-sm mt-2 max-w-xs">Tidak ada catatan pelanggaran sejauh ini. Pertahankan sikap baikmu!</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\tab-disiplin.blade.php ENDPATH**/ ?>