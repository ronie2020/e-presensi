<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500 mb-8">
    
    
    <div class="lg:col-span-1 space-y-6">
        
        
        <?php 
            $score = $finalScore ?? 100;
            // Tentukan warna berdasarkan skor
            if($score >= 90) {
                $theme = 'emerald';
                $label = 'Sangat Baik';
                $icon = 'ph-shield-check';
                $msg = 'Pertahankan sikap disiplinmu!';
                $themeBg = 'bg-emerald-50'; $themeText = 'text-emerald-600'; $themeBorder = 'border-emerald-200';
            } elseif($score >= 75) {
                $theme = 'blue';
                $label = 'Baik';
                $icon = 'ph-shield';
                $msg = 'Tingkatkan lagi kedisiplinan.';
                $themeBg = 'bg-blue-50'; $themeText = 'text-blue-600'; $themeBorder = 'border-blue-200';
            } elseif($score >= 60) {
                $theme = 'amber';
                $label = 'Cukup';
                $icon = 'ph-shield-warning';
                $msg = 'Hati-hati, poinmu mulai rendah.';
                $themeBg = 'bg-amber-50'; $themeText = 'text-amber-600'; $themeBorder = 'border-amber-200';
            } else {
                $theme = 'rose';
                $label = 'Kurang';
                $icon = 'ph-warning-octagon';
                $msg = 'Segera perbaiki sikap & perilaku!';
                $themeBg = 'bg-rose-50'; $themeText = 'text-rose-600'; $themeBorder = 'border-rose-200';
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
                    <span class="text-xs font-bold <?php echo e($themeText); ?> <?php echo e($themeBg); ?> px-2 py-0.5 rounded-md mt-1 border <?php echo e($themeBorder); ?>">
                        <?php echo e($label); ?>

                    </span>
                </div>
            </div>

            <p class="text-sm font-medium text-slate-500 relative z-10 px-4">
                <i class="ph-fill <?php echo e($icon); ?> text-<?php echo e($theme); ?>-500 mr-1"></i> <?php echo e($msg); ?>

            </p>
        </div>

        
        <?php if($score < 100): ?>
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h4 class="text-sm font-black text-slate-800 mb-4 flex items-center gap-2">
                <i class="ph-fill ph-leaf text-emerald-500"></i> Program Pemulihan
            </h4>
            <div class="space-y-3">
                <?php if(isset($amnestyTasks)): ?>
                    <?php $__currentLoopData = $amnestyTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between group hover:border-emerald-200 transition-all cursor-default">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                <i class="<?php echo e($task['icon']); ?>"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-600 leading-tight max-w-[120px]"><?php echo e($task['title']); ?></span>
                        </div>
                        <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">-<?php echo e($task['points']); ?> Poin</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <p class="text-[9px] text-slate-400 mt-4 text-center italic">Hubungi Guru BK untuk mengambil tugas pemulihan.</p>
        </div>
        <?php endif; ?>

        
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

    
    
    <div class="lg:col-span-2 flex flex-col gap-6">
        
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
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
                        <?php
                            // LOGIKA UNTUK MENANGANI DATA MANUAL VS OTOMATIS
                            $pointVal = $record->disciplineType->point_value ?? abs($record->point_earned ?? 0);
                            $title = $record->disciplineType->name ?? ($record->activity_name ?? 'Pelanggaran');
                            $desc = $record->notes ?? ($record->description ?? null);
                            $date = \Carbon\Carbon::parse($record->date ?? $record->created_at);
                        ?>

                        <div class="group relative bg-white border border-slate-100 rounded-2xl p-5 hover:shadow-md hover:border-rose-100 transition-all duration-300">
                            
                            <div class="absolute left-0 top-4 bottom-4 w-1 bg-rose-500 rounded-r-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-rose-50 text-rose-700 text-[10px] font-black px-2 py-0.5 rounded border border-rose-100 uppercase">
                                            -<?php echo e($pointVal); ?> Poin
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                                            <i class="ph-bold ph-calendar-blank"></i>
                                            <?php echo e($date->translatedFormat('d F Y')); ?>

                                        </span>
                                        <?php if(!$record->disciplineType): ?>
                                            <span class="bg-slate-100 text-slate-500 text-[9px] font-bold px-1.5 py-0.5 rounded uppercase">
                                                System
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h4 class="font-bold text-slate-800 text-base group-hover:text-rose-600 transition-colors">
                                        <?php echo e($title); ?>

                                    </h4>
                                    
                                    <?php if($desc): ?>
                                        <p class="text-sm text-slate-500 mt-2 bg-slate-50 p-3 rounded-xl border border-slate-100 italic leading-relaxed">
                                            "<?php echo e($desc); ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="sm:text-right flex flex-row sm:flex-col items-center sm:items-end gap-2 sm:gap-0 mt-2 sm:mt-0 pt-2 sm:pt-0 border-t sm:border-0 border-slate-50">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Pelapor</p>
                                    <div class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                                        <?php if(isset($record->recorder) && $record->recorder): ?>
                                            <i class="ph-fill ph-user-circle text-slate-400"></i>
                                            <span class="text-xs font-bold text-slate-600"><?php echo e($record->recorder->name); ?></span>
                                        <?php else: ?>
                                            <i class="ph-fill ph-robot text-blue-400"></i>
                                            <span class="text-xs font-bold text-slate-600">Sistem Otomatis</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                
                <div class="flex flex-col items-center justify-center py-12 text-center h-full min-h-[300px]">
                    <div class="w-24 h-24 bg-gradient-to-b from-emerald-50 to-white rounded-full flex items-center justify-center mb-6 shadow-sm border border-emerald-50 animate-bounce-subtle">
                        <i class="ph-duotone ph-shield-check text-5xl text-emerald-400"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">Bersih & Teladan!</h3>
                    <p class="text-slate-500 text-sm max-w-sm leading-relaxed">
                        Tidak ada catatan pelanggaran hingga saat ini. Kamu adalah contoh siswa yang luar biasa.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        
        <?php if(isset($student->pointHistories) && count($student->pointHistories) > 0): ?>
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
            <h4 class="font-black text-slate-800 flex items-center gap-3 text-lg mb-6 pb-4 border-b border-slate-50">
                <span class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-600 flex items-center justify-center border border-slate-100">
                    <i class="ph-duotone ph-archive text-xl"></i>
                </span>
                Arsip Poin Tahun Sebelumnya
            </h4>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Tahun Ajaran</th>
                            <th class="px-6 py-4">Kelas Saat Itu</th>
                            <th class="px-6 py-4 text-center">Skor Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php $__currentLoopData = $student->pointHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700"><?php echo e($history->academic_year); ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-500"><?php echo e($history->class_name); ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                    $histTheme = $history->final_score >= 90 ? 'emerald' : ($history->final_score >= 75 ? 'blue' : ($history->final_score >= 60 ? 'amber' : 'rose'));
                                ?>
                                <span class="inline-flex items-center justify-center px-3 py-1.5 bg-<?php echo e($histTheme); ?>-50 text-<?php echo e($histTheme); ?>-600 font-black rounded-xl border border-<?php echo e($histTheme); ?>-100 text-xs shadow-sm">
                                    <?php echo e($history->final_score); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-disiplin.blade.php ENDPATH**/ ?>