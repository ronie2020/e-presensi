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
        
        // Penentuan Warna Progress (Elevate Adaptation)
        $progressColor = 'from-rose-400 to-rose-600';
        $iconColor = 'text-rose-500';
        $statusText = 'Belum Maksimal';
        if ($progressPercent == 100) { 
            // 100% menggunakan warna Elevate Primary-Accent
            $progressColor = 'from-[#5295FF] to-[#25D0FF]'; 
            $iconColor = 'text-[#5295FF]';
            $statusText = 'Luar Biasa!';
        }
        elseif ($progressPercent >= 50) { 
            $progressColor = 'from-amber-400 to-orange-500'; 
            $iconColor = 'text-orange-500';
            $statusText = 'Cukup Baik';
        }
    ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-center relative overflow-hidden group hover:border-[#5295FF]/30 transition-colors">
            <div class="absolute top-0 right-0 p-6 opacity-[0.03] group-hover:opacity-[0.08] transition transform group-hover:scale-110 pointer-events-none">
                <i class="ph-fill ph-check-square-offset text-9xl text-[#5295FF]"></i>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 relative z-10">
                <div>
                    <h3 class="text-2xl font-black text-[#2A3B52]">Jurnal Hari Ini</h3>
                    <p class="text-sm font-bold text-slate-500 flex items-center gap-2 mt-1">
                        <i class="ph-bold ph-calendar-blank text-[#5295FF]"></i> <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                    </p>
                </div>
                <div class="bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100 flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</p>
                        <p class="font-black <?php echo e($iconColor); ?>"><?php echo e($statusText); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-xl border border-slate-100">
                        <i class="ph-fill ph-target <?php echo e($iconColor); ?>"></i>
                    </div>
                </div>
            </div>

            <div class="relative z-10 mt-2">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-sm font-bold text-slate-600">Progress Penyelesaian</span>
                    <span class="text-lg font-black text-[#2A3B52]"><?php echo e($completedHabits); ?> <span class="text-sm text-slate-400">/ 7 Selesai</span></span>
                </div>
                <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full rounded-full bg-gradient-to-r <?php echo e($progressColor); ?> transition-all duration-1000 relative" style="width: <?php echo e($progressPercent); ?>%">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-[#5295FF]/30 transition-colors flex flex-col justify-center">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform duration-500 text-[#5295FF]">
                <i class="ph-fill ph-calendar-check text-8xl"></i>
            </div>
            <div class="relative z-10">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Konsistensi Bulan Ini</p>
                <div class="flex items-baseline gap-2 mb-4">
                    <h3 class="text-4xl font-black text-[#2A3B52]"><?php echo e($monthlyCount ?? 0); ?></h3>
                    <span class="text-sm text-slate-500 font-bold">Hari Lapor</span>
                </div>
                <div class="w-full bg-[#F3F9FD] h-2 rounded-full overflow-hidden border border-[#D0E7F8]">
                    <div class="bg-[#5295FF] h-full rounded-full transition-all duration-1000" style="width: <?php echo e(min((($monthlyCount ?? 0)/30)*100, 100)); ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 sm:p-8 hover:shadow-md transition-shadow mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-black text-[#2A3B52] flex items-center gap-2">
                    <i class="ph-fill ph-list-checks text-[#D83B01]"></i> Misi Kebiasaan
                </h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Lengkapi kebiasaan harianmu di bawah ini.</p>
            </div>
            
            <a href="<?php echo e(route('student.habits.index')); ?>" class="px-5 py-2.5 bg-[#2A3B52] text-white rounded-xl text-xs font-bold hover:bg-[#5295FF] transition shadow-lg shadow-slate-200 flex items-center gap-2">
                <i class="ph-bold ph-pencil-simple"></i> <span class="hidden sm:inline">Isi Jurnal Habit</span><span class="sm:hidden">Isi</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <?php
                $habitsGrid = [
                    ['id' => 'habit_1', 'icon' => 'sun-horizon', 'theme' => 'blue', 'label' => 'Bangun & Mandi'],
                    ['id' => 'prayer_check', 'icon' => 'mosque', 'theme' => 'green', 'label' => 'Shalat Waktu'],
                    ['id' => 'habit_3', 'icon' => 'sneaker-move', 'theme' => 'orange', 'label' => 'Berolahraga'],
                    ['id' => 'habit_5', 'icon' => 'carrot', 'theme' => 'red', 'label' => 'Makan Bergizi'],
                    ['id' => 'habit_4', 'icon' => 'book-open-text', 'theme' => 'navy', 'label' => 'Gemar Belajar'],
                    ['id' => 'habit_6', 'icon' => 'users-three', 'theme' => 'blue', 'label' => 'Bantu Orang Tua'],
                ];

                $themeMap = [
                    'blue' =>   ['bg' => 'bg-[#F3F9FD]', 'text' => 'text-[#5295FF]', 'border' => 'border-[#D0E7F8]', 'done_bg' => 'bg-[#5295FF]', 'done_border' => 'border-[#5295FF]'],
                    'green' =>  ['bg' => 'bg-[#DFF6DD]', 'text' => 'text-[#107C10]', 'border' => 'border-[#B7DFB9]', 'done_bg' => 'bg-[#107C10]', 'done_border' => 'border-[#107C10]'],
                    'orange' => ['bg' => 'bg-[#FFEFD6]', 'text' => 'text-[#D83B01]', 'border' => 'border-[#FFD8A8]', 'done_bg' => 'bg-[#D83B01]', 'done_border' => 'border-[#D83B01]'],
                    'red' =>    ['bg' => 'bg-[#FDE7E9]', 'text' => 'text-[#D13438]', 'border' => 'border-[#F4C3C9]', 'done_bg' => 'bg-[#D13438]', 'done_border' => 'border-[#D13438]'],
                    'navy' =>   ['bg' => 'bg-slate-100', 'text' => 'text-[#2A3B52]', 'border' => 'border-slate-200', 'done_bg' => 'bg-[#2A3B52]', 'done_border' => 'border-[#2A3B52]'],
                ];
            ?>

            <?php $__currentLoopData = $habitsGrid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isDone = false;
                    if ($h['id'] == 'prayer_check') {
                        $isDone = $todayEntry && ($todayEntry->prayer_subuh || $todayEntry->prayer_dhuha || $todayEntry->prayer_dzuhur || $todayEntry->prayer_ashar || $todayEntry->prayer_maghrib || $todayEntry->prayer_isya);
                    } elseif ($h['id'] == 'habit_1') {
                        $isDone = $todayEntry && $todayEntry->habit_1 && $todayEntry->habit_2;
                    } else {
                        $isDone = $todayEntry && $todayEntry->{$h['id']} == true;
                    }
                    
                    $t = $themeMap[$h['theme']];
                    $bgColor = $isDone ? $t['done_bg'] : $t['bg'];
                    $borderColor = $isDone ? $t['done_border'] : $t['border'];
                    $textColor = $isDone ? 'text-white' : $t['text'];
                    $iconColor = $isDone ? 'text-white' : $t['text'];
                ?>

                <div class="relative p-5 rounded-2xl border transition-all duration-300 overflow-hidden <?php echo e($bgColor); ?> <?php echo e($borderColor); ?>">
                    <div class="relative z-10 flex flex-col items-center text-center h-full justify-center">
                        <?php if($isDone): ?>
                            <div class="absolute top-0 right-0 bg-white/20 backdrop-blur-sm rounded-bl-xl p-1.5">
                                <i class="ph-bold ph-check text-white text-sm"></i>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3 p-3 rounded-full <?php echo e($isDone ? 'bg-white/20' : 'bg-white shadow-sm border border-white'); ?>">
                            <i class="ph-duotone ph-<?php echo e($h['icon']); ?> text-2xl <?php echo e($iconColor); ?>"></i>
                        </div>
                        
                        <h4 class="font-bold text-xs md:text-sm <?php echo e($textColor); ?>"><?php echo e($h['label']); ?></h4>
                        <?php if(!$isDone): ?>
                            <p class="text-[9px] <?php echo e($textColor); ?> opacity-70 mt-1 font-medium">Belum tuntas</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php $isTidurDone = $todayEntry && $todayEntry->habit_7; ?>
        <div class="mt-4 p-4 rounded-xl border flex items-center gap-4 relative overflow-hidden transition-all <?php echo e($isTidurDone ? 'bg-[#5295FF] border-[#5295FF] text-white shadow-sm' : 'bg-slate-50 border-slate-200 text-[#2A3B52]'); ?>">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl shrink-0 <?php echo e($isTidurDone ? 'bg-white/20 text-white' : 'bg-white text-slate-500 border border-slate-200 shadow-sm'); ?>">
                <i class="ph-duotone ph-moon-stars"></i>
            </div>
            <div class="relative z-10">
                <h4 class="font-bold text-sm">7. Tidur Cepat</h4>
                <p class="text-xs <?php echo e($isTidurDone ? 'text-white/80' : 'text-slate-500'); ?> mt-0.5 font-medium">
                    <?php echo e($isTidurDone ? 'Tercatat tidur pukul ' . $todayEntry->habit_7_time : 'Maksimal istirahat jam 22:00 malam.'); ?>

                </p>
            </div>
            <?php if($isTidurDone): ?>
                <div class="absolute top-4 right-4 bg-white/20 p-1.5 rounded-full"><i class="ph-bold ph-check text-white text-xs"></i></div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#2A3B52] flex items-center gap-2">
                    <i class="ph-bold ph-clock-counter-clockwise text-[#5295FF]"></i> Riwayat Jurnal Habit
                </h3>
                <p class="text-xs text-slate-500 mt-1">Catatan 5 hari terakhir dan pesan apresiasi dari guru.</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6 sm:px-8 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tanggal</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Capaian Misi</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Catatan Guru Wali</th>
                        <th class="py-4 px-6 sm:px-8 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $__empty_1 = true; $__currentLoopData = $recentActivities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-6 sm:px-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[#2A3B52] font-bold text-xs group-hover:bg-[#F3F9FD] group-hover:text-[#5295FF] group-hover:border-[#D0E7F8] transition-colors shadow-sm shrink-0">
                                        <?php echo e(\Carbon\Carbon::parse($activity->report_date)->format('d')); ?>

                                    </div>
                                    <div>
                                        <span class="font-bold text-[#2A3B52] block text-sm whitespace-nowrap">
                                            <?php echo e(\Carbon\Carbon::parse($activity->report_date)->translatedFormat('F Y')); ?>

                                        </span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                            <?php echo e(\Carbon\Carbon::parse($activity->report_date)->translatedFormat('l')); ?>

                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex -space-x-2">
                                    <?php if($activity->habit_1): ?> <div title="Bangun Pagi" class="w-8 h-8 rounded-full bg-[#F3F9FD] border-2 border-white flex items-center justify-center text-[#5295FF] shadow-sm"><i class="ph-fill ph-sun-horizon text-xs"></i></div> <?php endif; ?>
                                    <?php if($activity->habit_3): ?> <div title="Olahraga" class="w-8 h-8 rounded-full bg-[#FFEFD6] border-2 border-white flex items-center justify-center text-[#D83B01] shadow-sm"><i class="ph-fill ph-sneaker-move text-xs"></i></div> <?php endif; ?>
                                    <?php if($activity->habit_5): ?> <div title="Makan Sehat" class="w-8 h-8 rounded-full bg-[#FDE7E9] border-2 border-white flex items-center justify-center text-[#D13438] shadow-sm"><i class="ph-fill ph-carrot text-xs"></i></div> <?php endif; ?>
                                    
                                    <?php 
                                        $totalDone = collect(['habit_1','habit_2','habit_3','habit_4','habit_5','habit_6','habit_7'])->filter(fn($h) => $activity->$h)->count(); 
                                        $shown = ($activity->habit_1 ? 1:0) + ($activity->habit_3 ? 1:0) + ($activity->habit_5 ? 1:0);
                                        $remaining = $totalDone - $shown;
                                    ?>
                                    
                                    <?php if($remaining > 0): ?>
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-[#2A3B52] shadow-sm">
                                        +<?php echo e($remaining); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 max-w-[250px]">
                                <?php if($activity->teacher_feedback): ?>
                                    <div class="flex items-start gap-2 bg-[#F3F9FD] p-2.5 rounded-lg border border-[#D0E7F8] shadow-sm">
                                        <i class="ph-fill ph-chat-circle-text text-[#5295FF] mt-0.5 shrink-0"></i>
                                        <p class="text-[11px] text-[#2A3B52] font-medium leading-relaxed italic break-words">
                                            "<?php echo e(Str::limit($activity->teacher_feedback, 80)); ?>"
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase italic">-- Menunggu Review --</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 sm:px-8 text-right">
                                <span class="inline-flex items-center gap-1.5 text-[#107C10] font-bold text-[10px] bg-[#DFF6DD] px-2.5 py-1.5 rounded-lg border border-[#B7DFB9] uppercase tracking-wide shadow-sm">
                                    <i class="ph-fill ph-check-circle text-sm"></i> Tercatat
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 shadow-sm">
                                    <i class="ph-duotone ph-notebook text-3xl"></i>
                                </div>
                                <h4 class="text-[#2A3B52] font-bold text-sm">Belum ada riwayat jurnal</h4>
                                <p class="text-slate-500 text-xs mt-1">Mulailah mengisi jurnal kebiasaan baikmu hari ini!</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-kebiasaan.blade.php ENDPATH**/ ?>