<div x-data="{
        showDetail: false,
        todayData: <?php echo e(json_encode($todayEntry ?? null)); ?>

     }">
    
    
    <?php
        // LOGIKA HITUNG PROGRESS (SAMA DENGAN DASHBOARD)
        $totalHabits = 7;
        $completedHabits = 0;
        
        if($todayEntry) {
            // 1. Bangun & Mandi (Cek keduanya)
            if($todayEntry->habit_1 && $todayEntry->habit_2) $completedHabits++;
            
            // 2. Shalat (Minimal 1 shalat tercatat)
            if($todayEntry->prayer_subuh || $todayEntry->prayer_dzuhur || $todayEntry->prayer_ashar || 
               $todayEntry->prayer_maghrib || $todayEntry->prayer_isya || $todayEntry->prayer_dhuha) $completedHabits++;
            
            // 3. Olahraga
            if($todayEntry->habit_3) $completedHabits++;
            
            // 4. Makan (Habit 5 di DB, tapi urutan 4 di UI)
            if($todayEntry->habit_5) $completedHabits++;
            
            // 5. Belajar (Habit 4 di DB, urutan 5 di UI)
            if($todayEntry->habit_4) $completedHabits++;
            
            // 6. Sosial
            if($todayEntry->habit_6) $completedHabits++;
            
            // 7. Tidur
            if($todayEntry->habit_7) $completedHabits++;
        }

        $progressPercent = ($completedHabits / $totalHabits) * 100;
        
        // Penentuan Warna Progress
        $progressColor = 'from-rose-400 to-rose-600';
        $iconColor = 'text-rose-500';
        $statusText = 'Belum Maksimal';
        if ($progressPercent == 100) { 
            $progressColor = 'from-emerald-400 to-emerald-600'; 
            $iconColor = 'text-emerald-500';
            $statusText = 'Luar Biasa!';
        }
        elseif ($progressPercent >= 50) { 
            $progressColor = 'from-amber-400 to-amber-600'; 
            $iconColor = 'text-amber-500';
            $statusText = 'Cukup Baik';
        }
    ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-800/80 p-6 sm:p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition transform group-hover:scale-110">
                <i class="ph-fill ph-check-square-offset text-9xl text-cyan-500"></i>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 relative z-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100">Jurnal Hari Ini</h3>
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2 mt-1">
                        <i class="ph-bold ph-calendar-blank"></i> <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-2 rounded-2xl border border-slate-100 dark:border-slate-600 flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status</p>
                        <p class="font-black <?php echo e($iconColor); ?>"><?php echo e($statusText); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-xl border border-slate-100 dark:border-slate-700">
                        <i class="ph-fill ph-target <?php echo e($iconColor); ?>"></i>
                    </div>
                </div>
            </div>

            <div class="relative z-10 mt-2">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Progress Penyelesaian</span>
                    <span class="text-lg font-black text-slate-800 dark:text-slate-100"><?php echo e($completedHabits); ?> <span class="text-sm text-slate-400 dark:text-slate-500">/ 7 Selesai</span></span>
                </div>
                <div class="w-full h-4 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full rounded-full bg-gradient-to-r <?php echo e($progressColor); ?> transition-all duration-1000 relative" style="width: <?php echo e($progressPercent); ?>%">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-[2.5rem] border border-indigo-100 dark:border-indigo-800/50 flex flex-col justify-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5"><i class="ph-fill ph-chat-centered-text text-8xl text-indigo-600 dark:text-indigo-400"></i></div>
            <h3 class="font-bold text-indigo-800 dark:text-indigo-300 text-sm mb-3 relative z-10 flex items-center gap-2">
                <i class="ph-fill ph-chalkboard-teacher"></i> Catatan Guru Wali
            </h3>
            
            <?php if(isset($todayEntry) && $todayEntry->teacher_feedback): ?>
                <div class="relative z-10">
                    <i class="ph-fill ph-quotes text-indigo-200 dark:text-indigo-700 text-3xl absolute -top-2 -left-2"></i>
                    <p class="text-sm text-indigo-700 dark:text-indigo-200 italic leading-relaxed pl-6 relative z-10">
                        "<?php echo e($todayEntry->teacher_feedback); ?>"
                    </p>
                    <p class="text-[10px] font-bold text-indigo-400 dark:text-indigo-500 uppercase mt-3 text-right">
                        — <?php echo e(\Carbon\Carbon::parse($todayEntry->report_date)->translatedFormat('d F Y')); ?>

                    </p>
                </div>
            <?php else: ?>
                <div class="text-center text-indigo-300 dark:text-indigo-600 relative z-10 py-4">
                    <p class="text-xs font-medium">Belum ada catatan dari guru wali untuk hari ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white dark:bg-slate-800/80 rounded-[2.5rem] border border-slate-100 dark:border-slate-700/50 shadow-sm p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="ph-fill ph-list-checks text-cyan-500"></i> Detail Kebiasaan
            </h3>
            
            <a href="<?php echo e(route('student.habits.index')); ?>" class="px-5 py-2 bg-slate-800 dark:bg-cyan-600 text-white rounded-xl text-xs font-bold hover:bg-slate-900 dark:hover:bg-cyan-700 transition shadow-lg flex items-center gap-2">
                <i class="ph-bold ph-pencil-simple"></i> <span class="hidden sm:inline">Isi Jurnal Sekarang</span><span class="sm:hidden">Isi</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            
            
            <?php $isDone = isset($todayEntry) && $todayEntry->habit_1 && $todayEntry->habit_2; ?>
            <div class="p-4 rounded-2xl border transition-all flex items-center gap-4 <?php echo e($isDone ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700'); ?>">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl shrink-0 <?php echo e($isDone ? 'bg-emerald-500 text-white shadow-md' : 'bg-white dark:bg-slate-700 text-slate-300 dark:text-slate-500 border border-slate-200 dark:border-slate-600'); ?>">
                    <i class="ph-fill ph-sun-horizon"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-sm truncate <?php echo e($isDone ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-slate-300'); ?>">Bangun Awal & Mandi</h4>
                    <p class="text-[10px] font-bold uppercase tracking-wider <?php echo e($isDone ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'); ?>">
                        <?php echo e($isDone ? 'Selesai' : 'Belum'); ?>

                    </p>
                </div>
            </div>

            
            <?php 
                $shalatCount = 0;
                if(isset($todayEntry)) {
                    if($todayEntry->prayer_subuh) $shalatCount++;
                    if($todayEntry->prayer_dzuhur) $shalatCount++;
                    if($todayEntry->prayer_ashar) $shalatCount++;
                    if($todayEntry->prayer_maghrib) $shalatCount++;
                    if($todayEntry->prayer_isya) $shalatCount++;
                }
                $isDone = $shalatCount > 0;
            ?>
            <div class="p-4 rounded-2xl border transition-all flex items-center gap-4 <?php echo e($isDone ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700'); ?>">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl shrink-0 <?php echo e($isDone ? 'bg-emerald-500 text-white shadow-md' : 'bg-white dark:bg-slate-700 text-slate-300 dark:text-slate-500 border border-slate-200 dark:border-slate-600'); ?>">
                    <i class="ph-fill ph-hands-praying"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-sm truncate <?php echo e($isDone ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-slate-300'); ?>">Shalat Wajib</h4>
                    <p class="text-[10px] font-bold uppercase tracking-wider <?php echo e($isDone ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'); ?>">
                        <?php echo e($shalatCount); ?> Waktu Terisi
                    </p>
                </div>
            </div>

            
            <?php $isDone = isset($todayEntry) && $todayEntry->habit_3; ?>
            <div class="p-4 rounded-2xl border transition-all flex items-center gap-4 <?php echo e($isDone ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700'); ?>">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl shrink-0 <?php echo e($isDone ? 'bg-emerald-500 text-white shadow-md' : 'bg-white dark:bg-slate-700 text-slate-300 dark:text-slate-500 border border-slate-200 dark:border-slate-600'); ?>">
                    <i class="ph-fill ph-sneaker"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-sm truncate <?php echo e($isDone ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-slate-300'); ?>">Olahraga 15 Menit</h4>
                    <p class="text-[10px] font-bold uppercase tracking-wider <?php echo e($isDone ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'); ?>">
                        <?php echo e($isDone ? 'Selesai' : 'Belum'); ?>

                    </p>
                </div>
            </div>

            
            <?php $isDone = isset($todayEntry) && $todayEntry->habit_5; ?>
            <div class="p-4 rounded-2xl border transition-all flex items-center gap-4 <?php echo e($isDone ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700'); ?>">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl shrink-0 <?php echo e($isDone ? 'bg-emerald-500 text-white shadow-md' : 'bg-white dark:bg-slate-700 text-slate-300 dark:text-slate-500 border border-slate-200 dark:border-slate-600'); ?>">
                    <i class="ph-fill ph-fork-knife"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-sm truncate <?php echo e($isDone ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-slate-300'); ?>">Makan Bersama Keluarga</h4>
                    <p class="text-[10px] font-bold uppercase tracking-wider <?php echo e($isDone ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'); ?>">
                        <?php echo e($isDone ? 'Selesai' : 'Belum'); ?>

                    </p>
                </div>
            </div>

            
            <?php $isDone = isset($todayEntry) && $todayEntry->habit_4; ?>
            <div class="p-4 rounded-2xl border transition-all flex items-center gap-4 <?php echo e($isDone ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700'); ?>">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl shrink-0 <?php echo e($isDone ? 'bg-emerald-500 text-white shadow-md' : 'bg-white dark:bg-slate-700 text-slate-300 dark:text-slate-500 border border-slate-200 dark:border-slate-600'); ?>">
                    <i class="ph-fill ph-books"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-sm truncate <?php echo e($isDone ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-slate-300'); ?>">Mengulang Pelajaran</h4>
                    <p class="text-[10px] font-bold uppercase tracking-wider <?php echo e($isDone ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'); ?>">
                        <?php echo e($isDone ? 'Selesai' : 'Belum'); ?>

                    </p>
                </div>
            </div>

            
            <div class="grid grid-rows-2 gap-4">
                <?php $isSocialDone = isset($todayEntry) && $todayEntry->habit_6; ?>
                <div class="p-3 rounded-2xl border transition-all flex items-center gap-3 <?php echo e($isSocialDone ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700'); ?>">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0 <?php echo e($isSocialDone ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-slate-700 text-slate-300 dark:text-slate-500 border border-slate-200 dark:border-slate-600'); ?>">
                        <i class="ph-fill ph-users"></i>
                    </div>
                    <h4 class="font-bold text-xs flex-1 truncate <?php echo e($isSocialDone ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-slate-300'); ?>">Batasi Gadget</h4>
                    <?php if($isSocialDone): ?> <i class="ph-bold ph-check text-emerald-500"></i> <?php endif; ?>
                </div>

                <?php $isSleepDone = isset($todayEntry) && $todayEntry->habit_7; ?>
                <div class="p-3 rounded-2xl border transition-all flex items-center gap-3 <?php echo e($isSleepDone ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700'); ?>">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0 <?php echo e($isSleepDone ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-slate-700 text-slate-300 dark:text-slate-500 border border-slate-200 dark:border-slate-600'); ?>">
                        <i class="ph-fill ph-moon-stars"></i>
                    </div>
                    <h4 class="font-bold text-xs flex-1 truncate <?php echo e($isSleepDone ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-slate-300'); ?>">Tidur Maks 21:30</h4>
                    <?php if($isSleepDone): ?> <i class="ph-bold ph-check text-emerald-500"></i> <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-kebiasaan.blade.php ENDPATH**/ ?>