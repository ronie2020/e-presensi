<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500">
    
    
    <div class="lg:col-span-1 space-y-6">
        
        
        <?php 
            $score = $finalScore ?? 100;
            // Tentukan warna berdasarkan skor
            if($score >= 90) {
                $theme = 'emerald';
                $label = 'Sangat Baik';
                $icon = 'ph-shield-check';
                $msg = 'Pertahankan sikap disiplinmu!';
            } elseif($score >= 75) {
                $theme = 'blue';
                $label = 'Baik';
                $icon = 'ph-shield';
                $msg = 'Tingkatkan lagi kedisiplinan.';
            } elseif($score >= 60) {
                $theme = 'amber';
                $label = 'Cukup';
                $icon = 'ph-shield-warning';
                $msg = 'Hati-hati, poinmu mulai rendah.';
            } else {
                $theme = 'rose';
                $label = 'Kurang';
                $icon = 'ph-warning-octagon';
                $msg = 'Segera perbaiki sikap & perilaku!';
            }
        ?>

        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden text-center">
            
            <div class="absolute inset-0 bg-gradient-to-b from-<?php echo e($theme); ?>-50/50 to-transparent pointer-events-none"></div>
            
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-6 relative z-10">Skor Perilaku</h3>
            
            
            <div class="relative w-48 h-48 mx-auto mb-6 flex items-center justify-center">
                
                <div class="absolute inset-0 rounded-full border-[12px] border-slate-50"></div>
                
                <svg class="absolute inset-0 w-full h-full -rotate-90 transform">
                    <circle cx="96" cy="96" r="84" stroke="currentColor" stroke-width="12" fill="transparent" 
                        class="text-<?php echo e($theme); ?>-500 transition-all duration-1000 ease-out" 
                        stroke-dasharray="527" 
                        stroke-dashoffset="<?php echo e(527 - (527 * $score / 100)); ?>"
                        stroke-linecap="round">
                    </circle>
                </svg>
                
                <div class="flex flex-col items-center relative z-10">
                    <span class="text-6xl font-black text-slate-800 tracking-tighter"><?php echo e($score); ?></span>
                    <span class="text-xs font-bold text-<?php echo e($theme); ?>-600 bg-<?php echo e($theme); ?>-100 px-2 py-0.5 rounded-md mt-1 border border-<?php echo e($theme); ?>-200">
                        <?php echo e($label); ?>

                    </span>
                </div>
            </div>

            <p class="text-sm font-medium text-slate-500 relative z-10 px-4">
                <i class="ph-fill <?php echo e($icon); ?> text-<?php echo e($theme); ?>-500 mr-1"></i> <?php echo e($msg); ?>

            </p>
        </div>

        
        <div class="grid grid-cols-2 gap-3">
            
            <div class="bg-rose-50 p-4 rounded-3xl border border-rose-100 text-center group hover:bg-rose-100 transition-colors">
                <div class="w-8 h-8 mx-auto bg-white rounded-full flex items-center justify-center text-rose-500 shadow-sm mb-2 group-hover:scale-110 transition-transform">
                    <i class="ph-bold ph-minus"></i>
                </div>
                <p class="text-2xl font-black text-rose-700"><?php echo e($total_violation_points ?? 0); ?></p>
                <p class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Minus</p>
            </div>

            
            <div class="bg-emerald-50 p-4 rounded-3xl border border-emerald-100 text-center group hover:bg-emerald-100 transition-colors">
                <div class="w-8 h-8 mx-auto bg-white rounded-full flex items-center justify-center text-emerald-500 shadow-sm mb-2 group-hover:scale-110 transition-transform">
                    <i class="ph-bold ph-plus"></i>
                </div>
                <p class="text-2xl font-black text-emerald-700"><?php echo e($total_merit_points ?? 0); ?></p>
                <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Plus</p>
            </div>
        </div>
    </div>

    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 h-full">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                <h4 class="font-black text-slate-800 flex items-center gap-3 text-lg">
                    <span class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                        <i class="ph-duotone ph-warning-octagon text-xl"></i>
                    </span>
                    Catatan Indisipliner
                </h4>
                <div class="bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                    <span class="text-xs font-bold text-slate-500">
                        <?php echo e(isset($violations) ? count($violations) : 0); ?> Kasus
                    </span>
                </div>
            </div>

            <?php if(isset($violations) && count($violations) > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $violations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group relative bg-white border border-slate-100 rounded-2xl p-5 hover:shadow-md hover:border-rose-100 transition-all duration-300">
                            
                            <div class="absolute left-0 top-4 bottom-4 w-1 bg-rose-500 rounded-r-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-rose-50 text-rose-700 text-[10px] font-black px-2 py-0.5 rounded border border-rose-100 uppercase">
                                            -<?php echo e($record->disciplineType->point_value ?? ($record->point ?? 0)); ?> Poin
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                                            <i class="ph-bold ph-calendar-blank"></i>
                                            <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('d F Y')); ?>

                                        </span>
                                    </div>
                                    
                                    <h4 class="font-bold text-slate-800 text-base group-hover:text-rose-600 transition-colors">
                                        <?php echo e($record->disciplineType->name ?? 'Pelanggaran Tata Tertib'); ?>

                                    </h4>
                                    
                                    <?php if(isset($record->notes) && $record->notes): ?>
                                        <p class="text-sm text-slate-500 mt-2 bg-slate-50 p-3 rounded-xl border border-slate-100 italic leading-relaxed">
                                            "<?php echo e($record->notes); ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>

                                
                                <?php if(isset($record->recorder)): ?>
                                    <div class="sm:text-right flex flex-row sm:flex-col items-center sm:items-end gap-2 sm:gap-0 mt-2 sm:mt-0 pt-2 sm:pt-0 border-t sm:border-0 border-slate-50">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Pelapor</p>
                                        <div class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                                            <i class="ph-fill ph-user-circle text-slate-400"></i>
                                            <span class="text-xs font-bold text-slate-600"><?php echo e($record->recorder->name ?? 'Guru Piket'); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                
                <div class="flex flex-col items-center justify-center py-16 text-center h-full">
                    <div class="w-32 h-32 bg-gradient-to-b from-emerald-50 to-white rounded-full flex items-center justify-center mb-6 shadow-sm border border-emerald-50 animate-bounce-subtle">
                        <i class="ph-duotone ph-shield-check text-6xl text-emerald-400"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">Bersih & Teladan!</h3>
                    <p class="text-slate-500 text-sm max-w-sm leading-relaxed">
                        Tidak ada catatan pelanggaran hingga saat ini. Kamu adalah contoh siswa yang luar biasa. Pertahankan!
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-disiplin.blade.php ENDPATH**/ ?>