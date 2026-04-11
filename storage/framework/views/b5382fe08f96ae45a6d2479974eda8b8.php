

<?php
    \Carbon\Carbon::setLocale('id');
    $schedules = $student->schoolClass->schedules ?? collect([]);
    $todayName = \Carbon\Carbon::now()->translatedFormat('l');
    $defaultDay = ($todayName == 'Minggu') ? 'Senin' : $todayName;
?>

<div x-data="{ 
        viewMode: 'mingguan', 
        activeDay: '<?php echo e($defaultDay); ?>',
        triggerCalendarResize() {
            setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 150);
        }
    }" class="space-y-8 animate-in fade-in duration-500 font-sans">

    
    <div class="flex justify-center mb-4">
        <div class="bg-slate-100 p-1.5 rounded-2xl inline-flex shadow-inner">
            <button @click="viewMode = 'mingguan'" 
                class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2"
                :class="viewMode === 'mingguan' ? 'bg-white shadow-md text-blue-600' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-list-numbers"></i> Jadwal Mingguan
            </button>
            <button @click="viewMode = 'kalender'; triggerCalendarResize()" 
                class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2"
                :class="viewMode === 'kalender' ? 'bg-white shadow-md text-blue-600' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-calendar-blank"></i> Kalender Sekolah
            </button>
        </div>
    </div>

    
    
    
    <div x-show="viewMode === 'mingguan'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-8">
         
        
        <div class="sticky top-20 z-30 bg-slate-50/90 backdrop-blur-md py-2 -mx-4 px-4 sm:mx-0 sm:px-0 sm:bg-transparent sm:backdrop-filter-none transition-all">
            <div class="flex overflow-x-auto gap-3 no-scrollbar snap-x py-2">
                <?php $__currentLoopData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeDay = '<?php echo e($day); ?>'" 
                        class="snap-start shrink-0 relative px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-300 border overflow-hidden group"
                        :class="activeDay === '<?php echo e($day); ?>' 
                            ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/30 scale-105' 
                            : 'bg-white text-slate-500 border-slate-200 hover:border-blue-300 hover:text-blue-600'">
                        
                        
                        <?php if($day == $todayName): ?>
                            <span class="absolute top-2 right-2 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                        <?php endif; ?>
                        
                        <span class="relative z-10 flex items-center gap-2">
                            <?php echo e($day); ?>

                        </span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 min-h-[500px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

            <?php $__currentLoopData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div x-show="activeDay === '<?php echo e($day); ?>'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="p-6 sm:p-10 relative z-10"
                     style="display: none;">
                    
                    
                    <div class="flex items-end justify-between mb-10 border-b border-slate-50 pb-4">
                        <div>
                            <h2 class="text-4xl font-black text-slate-800 tracking-tighter mb-1"><?php echo e($day); ?></h2>
                            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                                <i class="ph-bold ph-chalkboard-teacher text-blue-500"></i>
                                <span>Kelas <?php echo e($student->schoolClass->name ?? '-'); ?></span>
                            </div>
                        </div>
                        <?php if($day == $todayName): ?>
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-1">Status</span>
                                <span class="px-4 py-1.5 bg-amber-100 text-amber-700 text-xs font-black rounded-full border border-amber-200 shadow-sm flex items-center gap-1">
                                    <i class="ph-fill ph-sun"></i> Hari Ini
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="relative space-y-0">
                        <div class="absolute left-[2.25rem] top-4 bottom-4 w-0.5 bg-gradient-to-b from-slate-200 via-slate-100 to-transparent"></div>

                        <?php
                            $daySchedules = $schedules->where('day', $day)->sortBy('start_time');
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $daySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $startJP = str_contains($sched->start_time, ':') ? \Carbon\Carbon::parse($sched->start_time)->second : intval($sched->start_time);
                                $endJP = str_contains($sched->end_time, ':') ? \Carbon\Carbon::parse($sched->end_time)->second : intval($sched->end_time);
                                if($startJP == 0) $startJP = intval($sched->start_time);
                                if($endJP == 0) $endJP = intval($sched->end_time);
                                
                                $colors = ['blue', 'indigo', 'violet', 'emerald', 'teal', 'rose', 'orange'];
                                $colorIndex = crc32($sched->subject->name ?? 'X') % count($colors);
                                $color = $colors[$colorIndex];
                            ?>

                            <div class="relative pl-24 py-4 group">
                                <div class="absolute left-2 top-4 w-16 h-16 rounded-2xl bg-white border-2 border-slate-100 shadow-sm flex flex-col items-center justify-center z-10 group-hover:border-<?php echo e($color); ?>-200 group-hover:scale-110 transition-all duration-300">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Jam Ke</span>
                                    <div class="flex items-center text-lg font-black text-slate-700">
                                        <?php echo e($startJP); ?><span class="text-slate-300 mx-0.5 text-xs">-</span><?php echo e($endJP); ?>

                                    </div>
                                </div>

                                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-<?php echo e($color); ?>-500/10 hover:border-<?php echo e($color); ?>-200 transition-all duration-300 relative overflow-hidden group-hover:-translate-y-1">
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-<?php echo e($color); ?>-500"></div>
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                        <div>
                                            <h3 class="text-lg font-black text-slate-800 mb-2 group-hover:text-<?php echo e($color); ?>-600 transition-colors line-clamp-1">
                                                <?php echo e($sched->subject->name ?? 'Mata Pelajaran'); ?>

                                            </h3>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                                    <i class="ph-fill ph-user-circle text-slate-400"></i>
                                                    <span class="text-xs font-bold text-slate-600"><?php echo e($sched->teacher->name ?? 'Guru'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($endJP == 3 || $endJP == 6): ?>
                                <div class="relative pl-24 py-6">
                                    <div class="absolute left-[2.25rem] top-1/2 -translate-y-1/2 w-4 h-4 bg-amber-400 rounded-full border-4 border-white shadow z-10"></div>
                                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-4 border border-amber-100/50 border-dashed flex items-center gap-4 opacity-80 hover:opacity-100 transition-opacity">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-amber-500 shadow-sm shrink-0">
                                            <i class="ph-fill <?php echo e($endJP == 6 ? 'ph-mosque' : 'ph-coffee'); ?> text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-amber-900 text-sm"><?php echo e($endJP == 6 ? 'Istirahat Sholat & Makan' : 'Istirahat Pertama'); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-24">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-dashed border-slate-200">
                                    <i class="ph-duotone ph-coffee text-5xl text-slate-300"></i>
                                </div>
                                <h3 class="text-xl font-black text-slate-700">Libur / Bebas Pelajaran</h3>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    
    
    <div x-show="viewMode === 'kalender'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display: none;">
         
         <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">
                    <i class="ph-duotone ph-calendar-check"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Kalender Pendidikan</h2>
                    <p class="text-sm text-slate-500">Jadwal kegiatan akademik dan hari libur sekolah.</p>
                </div>
            </div>

            
            <div id="calendar" class="min-h-[600px] fc-theme-standard"></div>
            
            
            <div class="mt-6 pt-6 border-t border-slate-100 flex flex-wrap gap-4">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div> Ujian / Assesmen
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div> Libur
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div> Kegiatan Sekolah
                </div>
            </div>
         </div>
    </div>

    
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl backdrop-blur-md shadow-inner border border-white/20">
                <i class="ph-fill ph-info"></i>
            </div>
            <div>
                <h4 class="font-black text-lg mb-1">Catatan</h4>
                <p class="text-blue-100 text-sm leading-relaxed max-w-xl">
                    Jadwal mingguan dan kalender akademik dapat berubah sewaktu-waktu sesuai kebijakan sekolah.
                </p>
            </div>
        </div>
    </div>
</div>


<style>
    .fc-theme-standard .fc-scrollgrid { border-color: #f1f5f9; border-radius: 1rem; overflow: hidden; }
    .fc-theme-standard th, .fc-theme-standard td { border-color: #f1f5f9; }
    .fc-col-header-cell { padding: 12px 0; background-color: #f8fafc; }
    .fc-col-header-cell-cushion { color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; text-decoration: none;}
    .fc-daygrid-day-number { color: #334155; font-weight: 700; font-size: 0.875rem; text-decoration: none; padding: 8px !important; }
    .fc-day-today { background-color: #eff6ff !important; }
    .fc-event { border-radius: 6px; padding: 2px 4px; font-size: 0.7rem; font-weight: bold; border: none; cursor: pointer; }
    .fc .fc-button-primary { background-color: #fff; border-color: #e2e8f0; color: #475569; font-weight: 600; text-transform: capitalize; border-radius: 0.5rem; }
    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #eff6ff; border-color: #bfdbfe; color: #2563eb; }
    .fc .fc-button-primary:hover { background-color: #f8fafc; color: #0f172a; }
</style><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\tab-jadwal.blade.php ENDPATH**/ ?>