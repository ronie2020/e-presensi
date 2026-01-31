<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500">
    
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-emerald-100 sticky top-24">
            
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-700 p-6 text-white shadow-lg shadow-emerald-500/20">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="ph-fill ph-trophy text-8xl"></i>
                </div>
                
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-100 mb-1">Total Poin Kebaikan</p>
                    <h2 class="text-5xl font-black tracking-tight">+<?php echo e($total_merit_points ?? 0); ?></h2>
                    <div class="mt-4 flex items-center gap-2 text-xs font-medium bg-white/20 w-fit px-3 py-1.5 rounded-lg backdrop-blur-sm">
                        <i class="ph-bold ph-trend-up"></i> Terus Meningkat
                    </div>
                </div>
            </div>

            
            <div class="mt-6 bg-slate-50 p-6 rounded-3xl border border-slate-100 relative">
                <i class="ph-fill ph-quotes text-slate-200 text-4xl absolute top-4 left-4"></i>
                <p class="text-sm text-slate-600 italic text-center relative z-10 leading-relaxed pt-2">
                    "Prestasi bukanlah kebetulan, melainkan hasil dari kerja keras, ketekunan, dan doa yang konsisten."
                </p>
                <div class="mt-4 flex justify-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                    <div class="w-8 h-1 rounded-full bg-emerald-400"></div>
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="lg:col-span-2 space-y-6">
        
        
        <div class="flex items-center justify-between bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div>
                <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-star text-yellow-400"></i> Jejak Prestasi
                </h3>
                <p class="text-slate-400 text-sm mt-1">Riwayat pencapaian dan perilaku positifmu.</p>
            </div>
            <div class="hidden sm:block">
                <span class="px-4 py-2 bg-slate-50 rounded-xl text-xs font-bold text-slate-500 border border-slate-200">
                    Total: <?php echo e(isset($achievements) ? count($achievements) : 0); ?> Catatan
                </span>
            </div>
        </div>

        
        <?php if(isset($achievements) && count($achievements) > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Cek Tipe: Apakah Prestasi Besar (Lomba) atau Poin Harian
                        $isMajorAchievement = isset($record->type) && $record->type === 'achievement_record';
                    ?>

                    <?php if($isMajorAchievement): ?>
                        
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-100 to-amber-50 rounded-3xl transform translate-y-2 translate-x-2 transition-transform group-hover:translate-x-3 group-hover:translate-y-3"></div>
                            <div class="relative bg-white p-6 rounded-3xl border border-amber-100 shadow-sm flex flex-col sm:flex-row gap-5 overflow-hidden">
                                
                                <div class="shrink-0">
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-amber-600 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                                        <i class="ph-duotone ph-trophy text-3xl"></i>
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wider border border-amber-200">
                                                    <?php echo e($record->level ?? 'PENGHARGAAN'); ?>

                                                </span>
                                                <span class="text-xs text-slate-400 font-medium">
                                                    <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('d F Y')); ?>

                                                </span>
                                            </div>
                                            <h4 class="text-lg font-black text-slate-800 leading-snug group-hover:text-amber-600 transition-colors">
                                                <?php echo e($record->title); ?>

                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <?php if($record->notes): ?>
                                        <p class="text-sm text-slate-600 mt-2 line-clamp-2"><?php echo e($record->notes); ?></p>
                                    <?php endif; ?>

                                    
                                    <div class="mt-4 flex items-center gap-3">
                                        <?php if(isset($record->photo) && $record->photo): ?>
                                            <a href="<?php echo e(asset('storage/' . $record->photo)); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-700 transition-all shadow-md">
                                                <i class="ph-bold ph-image"></i> Lihat Bukti
                                            </a>
                                        <?php endif; ?>
                                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 bg-slate-50 px-3 py-2 rounded-xl">
                                            <i class="ph-bold ph-user"></i> <?php echo e($record->recorder->name ?? 'Admin Sekolah'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        
                        <div class="flex gap-4 group">
                            
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                    <i class="ph-bold ph-plus"></i>
                                </div>
                                <div class="w-0.5 h-full bg-slate-100 my-2 group-last:hidden"></div>
                            </div>

                            <div class="flex-1 pb-6">
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm group-hover:border-emerald-200 transition-colors relative">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h5 class="font-bold text-slate-800 text-sm">
                                                <?php echo e($record->disciplineType->name ?? 'Kebaikan Harian'); ?>

                                            </h5>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y')); ?>

                                            </p>
                                        </div>
                                        <?php if(isset($record->disciplineType->point_value) && $record->disciplineType->point_value > 0): ?>
                                            <span class="text-emerald-600 font-black text-sm bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                                                +<?php echo e($record->disciplineType->point_value); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if($record->notes): ?>
                                        <div class="mt-2 text-xs text-slate-600 bg-slate-50 p-2 rounded-lg italic">
                                            "<?php echo e($record->notes); ?>"
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            
            <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-subtle">
                    <i class="ph-duotone ph-medal text-5xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Belum Ada Catatan</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                    Setiap langkah kecil menuju kebaikan adalah prestasi. Ayo mulai kumpulkan poin kebaikanmu!
                </p>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-prestasi.blade.php ENDPATH**/ ?>