

<?php
    \Carbon\Carbon::setLocale('id');
    $schedules = $student->schoolClass->schedules ?? collect([]);
    $todayName = \Carbon\Carbon::now()->translatedFormat('l');
    
    // Default tab: Jika Minggu, ke Senin. Jika tidak, ke Hari Ini.
    $defaultDay = ($todayName == 'Minggu') ? 'Senin' : $todayName;
?>

<div x-data="{ activeDay: '<?php echo e($defaultDay); ?>' }" class="space-y-6">

    
    <div class="flex overflow-x-auto pb-4 gap-3 no-scrollbar snap-x mb-2">
        <?php $__currentLoopData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button @click="activeDay = '<?php echo e($day); ?>'" 
                class="snap-start shrink-0 px-8 py-3.5 rounded-2xl font-bold text-sm transition-all duration-300 border relative overflow-hidden group"
                :class="activeDay === '<?php echo e($day); ?>' 
                    ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/30 scale-105' 
                    : 'bg-white text-slate-500 border-slate-200 hover:border-blue-300 hover:text-blue-600'">
                
                
                <?php if($day == $todayName): ?>
                    <span class="absolute top-2 right-2 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                <?php endif; ?>
                <span class="relative z-10"><?php echo e($day); ?></span>
            </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-10 min-h-[450px] relative overflow-hidden">
        
        
        <div class="absolute top-0 right-0 w-80 h-80 bg-blue-50 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 pointer-events-none opacity-50"></div>

        <?php $__currentLoopData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div x-show="activeDay === '<?php echo e($day); ?>'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                
                <div class="flex items-center justify-between mb-10 relative z-10">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight"><?php echo e($day); ?></h2>
                        <p class="text-slate-400 text-sm font-medium mt-1">Mata pelajaran kelas <?php echo e($student->schoolClass->name ?? '-'); ?></p>
                    </div>
                    <?php if($day == $todayName): ?>
                        <span class="px-4 py-1.5 bg-amber-100 text-amber-700 text-xs font-black rounded-full border border-amber-200 uppercase tracking-widest shadow-sm">
                            Hari Ini
                        </span>
                    <?php endif; ?>
                </div>

                <div class="relative z-10 space-y-4">
                    <?php
                        $daySchedules = $schedules->where('day', $day)->sortBy('start_time');
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $daySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // Fix format jam 00:00 -> integer JP
                            $startJP = str_contains($sched->start_time, ':') 
                                        ? \Carbon\Carbon::parse($sched->start_time)->second 
                                        : intval($sched->start_time);
                                        
                            $endJP = str_contains($sched->end_time, ':') 
                                        ? \Carbon\Carbon::parse($sched->end_time)->second 
                                        : intval($sched->end_time);

                            if($startJP == 0) $startJP = intval($sched->start_time);
                            if($endJP == 0) $endJP = intval($sched->end_time);
                        ?>

                        
                        <div class="flex group relative">
                             
                             <div class="flex flex-col items-center mr-6 md:mr-10">
                                <div class="w-20 py-3 rounded-2xl bg-slate-100 text-slate-600 font-black text-xs text-center border border-slate-200 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all duration-300 shadow-sm flex flex-col justify-center items-center gap-1">
                                    <span class="text-[10px] uppercase font-bold opacity-60">Jam Ke</span>
                                    <div class="text-xl leading-none"><?php echo e($startJP); ?></div>
                                    <?php if($startJP != $endJP): ?>
                                        <div class="w-8 h-[2px] bg-slate-300 mx-auto opacity-50 group-hover:bg-white"></div>
                                        <div class="text-xl leading-none"><?php echo e($endJP); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php if(!$loop->last): ?>
                                    <div class="w-1 h-full bg-slate-100 my-3 rounded-full group-hover:bg-blue-50 transition-colors"></div>
                                <?php endif; ?>
                            </div>

                            
                            <div class="flex-1 pb-4">
                                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 group-hover:-translate-y-1 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full -mr-16 -mt-16 group-hover:bg-blue-50 transition-colors"></div>
                                    <div class="relative z-10">
                                        <h3 class="font-black text-xl text-slate-800 mb-2 group-hover:text-blue-600 transition-colors">
                                            <?php echo e($sched->subject->name ?? 'Mata Pelajaran'); ?>

                                        </h3>
                                        <div class="flex flex-wrap items-center gap-4 mt-4">
                                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-100">
                                                <div class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-black">
                                                    <?php echo e(substr($sched->teacher->name ?? 'G', 0, 1)); ?>

                                                </div>
                                                <span class="text-xs font-bold text-slate-600"><?php echo e($sched->teacher->name ?? 'Guru Pengampu'); ?></span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-slate-400 text-xs font-bold">
                                                <i class="ph-bold ph-door-open text-lg"></i>
                                                R. Kelas <?php echo e($student->schoolClass->name ?? '-'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <?php if($endJP == 3): ?>
                            <div class="flex mb-4 opacity-80">
                                <div class="w-20 mr-6 md:mr-10 flex justify-center">
                                    <div class="w-1 h-full bg-slate-100 rounded-full"></div>
                                </div>
                                <div class="flex-1 bg-orange-50 rounded-2xl p-4 border border-orange-100 border-dashed flex items-center gap-4">
                                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-500 shadow-sm">
                                        <i class="ph-duotone ph-coffee text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-orange-900 text-sm">Istirahat Pertama</h4>
                                        <p class="text-xs text-orange-700">Waktunya menyegarkan pikiran sejenak.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($endJP == 6): ?>
                            <div class="flex mb-4 opacity-80">
                                <div class="w-20 mr-6 md:mr-10 flex justify-center">
                                    <div class="w-1 h-full bg-slate-100 rounded-full"></div>
                                </div>
                                <div class="flex-1 bg-blue-50 rounded-2xl p-4 border border-blue-100 border-dashed flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-500 shadow-sm">
                                        <i class="ph-duotone ph-mosque text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-blue-900 text-sm">Istirahat Kedua / ISOMA</h4>
                                        <p class="text-xs text-blue-700">Istirahat, Sholat, dan Makan Siang.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-20">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 border border-dashed border-slate-200">
                                <i class="ph-duotone ph-coffee text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-700">Tidak ada pelajaran</h3>
                            <p class="text-slate-400 text-sm mt-2">Waktunya istirahat atau belajar mandiri!</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-blue-600 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-600/20">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-xl text-white">
                <i class="ph-fill ph-info"></i>
            </div>
            <h4 class="font-black text-lg">Informasi Jadwal</h4>
        </div>
        <p class="text-sm font-medium leading-relaxed opacity-90 pl-14">
            Jadwal istirahat menyesuaikan dengan bel sekolah.
        </p>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\tab-jadwal.blade.php ENDPATH**/ ?>