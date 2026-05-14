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
        <div class="bg-slate-100 p-1.5 rounded-[1.5rem] inline-flex shadow-inner border border-slate-200">
            <button @click="viewMode = 'mingguan'" 
                class="px-6 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 flex items-center gap-2"
                :class="viewMode === 'mingguan' ? 'bg-white shadow-md text-elevate-primary ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-list-numbers text-lg"></i> Mingguan
            </button>
            <button @click="viewMode = 'kalender'; triggerCalendarResize()" 
                class="px-6 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 flex items-center gap-2"
                :class="viewMode === 'kalender' ? 'bg-white shadow-md text-elevate-primary ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-calendar-blank text-lg"></i> Kalender
            </button>
        </div>
    </div>

    
    <div x-show="viewMode === 'mingguan'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-8">
         
        
        <div class="sticky top-20 z-30 bg-slate-50/90 backdrop-blur-md py-2 -mx-4 px-4 sm:mx-0 sm:px-0 sm:bg-transparent sm:backdrop-filter-none transition-all">
            <div class="flex overflow-x-auto gap-3 no-scrollbar snap-x py-2 custom-scrollbar">
                <?php $__currentLoopData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeDay = '<?php echo e($day); ?>'" 
                        class="snap-start shrink-0 relative px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-300 border overflow-hidden group"
                        :class="activeDay === '<?php echo e($day); ?>' 
                            ? 'bg-elevate-dark text-white border-elevate-dark shadow-lg shadow-elevate-dark/30 scale-105' 
                            : 'bg-white text-slate-500 border-slate-200 hover:border-elevate-accent/50 hover:text-elevate-primary'">
                        
                        <?php if($day == $todayName): ?>
                            <span class="absolute top-2 right-2 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-peach opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-peach-dark border border-white"></span>
                            </span>
                        <?php endif; ?>
                        <span class="relative z-10"><?php echo e($day); ?></span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 min-h-[500px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-elevate-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

            <?php $__currentLoopData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div x-show="activeDay === '<?php echo e($day); ?>'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="p-6 sm:p-10 relative z-10"
                     style="display: none;">
                    
                    
                    <div class="flex items-end justify-between mb-10 border-b border-slate-50 pb-4">
                        <div>
                            <h2 class="text-4xl font-black text-elevate-dark tracking-tighter mb-1"><?php echo e($day); ?></h2>
                            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                                <i class="ph-bold ph-chalkboard-teacher text-elevate-primary"></i>
                                <span>Kelas <?php echo e($student->schoolClass->name ?? '-'); ?></span>
                            </div>
                        </div>
                        <?php if($day == $todayName): ?>
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="text-[10px] font-black uppercase tracking-widest text-elevate-peach-dark mb-1">Status</span>
                                <span class="px-4 py-1.5 bg-elevate-peach-light/20 text-elevate-peach-dark text-xs font-black rounded-full border border-elevate-peach/30 shadow-sm flex items-center gap-1">
                                    <i class="ph-fill ph-sun"></i> Hari Ini
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="relative space-y-0">
                        <div class="absolute left-[2.25rem] top-4 bottom-4 w-0.5 bg-slate-100"></div>

                        <?php $daySchedules = $schedules->where('day', $day)->sortBy('start_time'); ?>

                        <?php $__empty_1 = true; $__currentLoopData = $daySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $startJP = str_contains($sched->start_time, ':') ? \Carbon\Carbon::parse($sched->start_time)->second : intval($sched->start_time);
                                $endJP = str_contains($sched->end_time, ':') ? \Carbon\Carbon::parse($sched->end_time)->second : intval($sched->end_time);
                                if($startJP == 0) $startJP = intval($sched->start_time);
                                if($endJP == 0) $endJP = intval($sched->end_time);
                                
                                // Tema Statis Tailwind Aman (Elevate Palette)
                                $colorThemes = [
                                    ['bg' => 'bg-elevate-soft/50', 'border' => 'border-elevate-accent/30', 'text' => 'text-elevate-primary', 'line' => 'bg-elevate-accent', 'hover' => 'hover:border-elevate-primary/40'],
                                    ['bg' => 'bg-elevate-peach-light/10', 'border' => 'border-elevate-peach/30', 'text' => 'text-elevate-peach-dark', 'line' => 'bg-elevate-peach', 'hover' => 'hover:border-elevate-peach/60'],
                                    ['bg' => 'bg-emerald-50/50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-600', 'line' => 'bg-emerald-400', 'hover' => 'hover:border-emerald-400']
                                ];
                                $t = $colorThemes[crc32($sched->subject->name ?? 'X') % count($colorThemes)];
                            ?>

                            <div class="relative pl-24 py-4 group">
                                <div class="absolute left-2 top-4 w-16 h-16 rounded-2xl bg-white border-2 border-slate-100 shadow-sm flex flex-col items-center justify-center z-10 group-hover:scale-110 transition-transform duration-300 <?php echo e($t['hover']); ?>">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">Jam Ke</span>
                                    <div class="flex items-center text-lg font-black text-elevate-dark">
                                        <?php echo e($startJP); ?><span class="text-slate-300 mx-0.5 text-xs">-</span><?php echo e($endJP); ?>

                                    </div>
                                </div>

                                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden group-hover:-translate-y-1 <?php echo e($t['hover']); ?>">
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($t['line']); ?>"></div>
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pl-2">
                                        <div>
                                            <h3 class="text-lg font-black text-elevate-dark mb-2 <?php echo e("group-hover:{$t['text']}"); ?> transition-colors line-clamp-1">
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
                                    <div class="absolute left-[2.25rem] top-1/2 -translate-y-1/2 w-4 h-4 bg-elevate-peach rounded-full border-4 border-white shadow-sm z-10"></div>
                                    <div class="bg-elevate-peach-light/10 rounded-2xl p-4 border border-elevate-peach/30 border-dashed flex items-center gap-4 opacity-80 hover:opacity-100 transition-opacity">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-elevate-peach-dark shadow-sm shrink-0 border border-elevate-peach/20">
                                            <i class="ph-fill <?php echo e($endJP == 6 ? 'ph-mosque' : 'ph-coffee'); ?> text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-elevate-peach-dark text-sm"><?php echo e($endJP == 6 ? 'Istirahat Sholat & Makan' : 'Istirahat Pertama'); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-24">
                                <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="ph-duotone ph-coffee text-5xl text-elevate-primary"></i>
                                </div>
                                <h3 class="text-xl font-black text-elevate-dark">Libur / Bebas Pelajaran</h3>
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
         
         <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl border border-elevate-accent/20">
                    <i class="ph-duotone ph-calendar-check"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-elevate-dark">Kalender Pendidikan</h2>
                    <p class="text-sm text-slate-500">Jadwal kegiatan akademik dan libur.</p>
                </div>
            </div>

            <div id="calendar" class="min-h-[600px] fc-theme-standard"></div>
            
            
            <div class="mt-6 pt-6 border-t border-slate-100 flex flex-wrap gap-4">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-elevate-peach"></div> Ujian / Assesmen
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div> Libur
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-elevate-primary"></div> Kegiatan Sekolah
                </div>
            </div>
         </div>
    </div>

    
    <div class="bg-elevate-dark rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-elevate-dark/10 border border-elevate-primary/30">
        <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-primary/40 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-2xl backdrop-blur-md border border-white/20">
                <i class="ph-fill ph-info text-elevate-accent"></i>
            </div>
            <div>
                <h4 class="font-black text-lg mb-1">Catatan</h4>
                <p class="text-white/70 text-sm leading-relaxed max-w-xl">
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
    .fc-col-header-cell-cushion { color: #2c3f61; font-weight: 900; text-transform: uppercase; font-size: 0.75rem; text-decoration: none;}
    .fc-daygrid-day-number { color: #2c3f61; font-weight: 700; font-size: 0.875rem; text-decoration: none; padding: 8px !important; }
    .fc-day-today { background-color: #e5eff5 !important; }
    .fc-event { border-radius: 6px; padding: 2px 4px; font-size: 0.7rem; font-weight: bold; border: none; cursor: pointer; }
    .fc .fc-button-primary { background-color: #fff; border-color: #e2e8f0; color: #2c3f61; font-weight: 700; text-transform: capitalize; border-radius: 0.75rem; }
    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #e5eff5; border-color: #56bbf1; color: #0d52a1; }
    .fc .fc-button-primary:hover { background-color: #f8fafc; color: #0d52a1; }
</style><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-jadwal.blade.php ENDPATH**/ ?>