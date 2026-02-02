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
    ?>

    <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden mb-8">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 rounded-full blur-[80px] -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-purple-500/20 rounded-full blur-[60px] -ml-20 -mb-20"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
            
            <div class="relative w-32 h-32 shrink-0">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-800"></circle>
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" 
                            class="text-emerald-400 transition-all duration-1000 ease-out"
                            stroke-dasharray="351.8"
                            stroke-dashoffset="<?php echo e(351.8 - (351.8 * $progressPercent / 100)); ?>"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-white"><?php echo e(round($progressPercent)); ?>%</span>
                    <span class="text-[10px] uppercase text-slate-400 font-bold tracking-widest">Selesai</span>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left">
                 <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="inline-flex items-center gap-2 text-blue-300 hover:text-white transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em]">
                    <i class="ph-bold ph-arrow-left"></i> Pantau Dashboard Siswa
                </a>
                <h2 class="text-2xl font-black mb-2">Pantauan Karakter Hari Ini</h2>
                <p class="text-blue-200 text-sm leading-relaxed mb-4">
                    Lihat perkembangan kebiasaan baik siswa secara real-time. Data ini disinkronkan langsung dari dashboard siswa.
                </p>
                
                
                <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-5">
                    <span class="px-3 py-1 rounded-full text-xs font-bold border <?php echo e($completedHabits == 7 ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-200' : 'bg-slate-700 border-slate-600 text-slate-300'); ?>">
                        <?php echo e($completedHabits); ?>/7 Misi Tuntas
                    </span>
                    <?php if(isset($todayEntry) && $todayEntry->habit_photo): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 border border-blue-500/50 text-blue-200 flex items-center gap-1">
                            <i class="ph-fill ph-camera"></i> Ada Dokumentasi
                        </span>
                    <?php endif; ?>
                </div>

                
                <?php if($completedHabits < 7): ?>
                    <div class="pt-5 border-t border-white/10 flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                        <div class="flex items-center gap-2 text-orange-300 animate-pulse">
                            <i class="ph-fill ph-warning-circle"></i>
                            <span class="text-xs font-bold">Data Belum Lengkap</span>
                        </div>
                        <a href="<?php echo e(route('student.habits.index')); ?>" class="group relative inline-flex items-center gap-2 px-6 py-2.5 bg-white text-slate-900 hover:bg-blue-50 hover:text-blue-700 font-black rounded-xl transition-all shadow-lg shadow-white/10 ring-2 ring-white/50 hover:ring-blue-400">
                            <span>Lengkapi Formulir Sekarang</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="pt-4 border-t border-white/10">
                        <span class="inline-flex items-center gap-2 text-emerald-400 font-bold text-sm">
                            <i class="ph-fill ph-seal-check text-xl"></i> Luar Biasa! Semua misi hari ini sudah tercatat.
                        </span>                        
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <?php
            $habits = [
                // 1. Bangun & Mandi (Habit 1 & 2)
                ['label' => 'Bangun & Mandi', 'icon' => 'sun-horizon', 'color' => 'blue', 'done' => ($todayEntry && $todayEntry->habit_1 && $todayEntry->habit_2), 'detail' => $todayEntry->habit_1_time ?? null],
                
                // 2. Shalat (Cek detail prayer)
                ['label' => 'Shalat Tepat Waktu', 'icon' => 'mosque', 'color' => 'emerald', 'done' => ($todayEntry && ($todayEntry->prayer_subuh || $todayEntry->prayer_dzuhur || $todayEntry->prayer_ashar || $todayEntry->prayer_maghrib || $todayEntry->prayer_isya || $todayEntry->prayer_dhuha)), 'detail' => 'Cek Detail'],
                
                // 3. Olahraga (Habit 3)
                ['label' => 'Berolahraga', 'icon' => 'sneaker-move', 'color' => 'indigo', 'done' => ($todayEntry && $todayEntry->habit_3), 'detail' => $todayEntry->habit_3_activity ?? null],
                
                // 4. Makan Bergizi (Habit 5 - Orange)
                ['label' => 'Makan Bergizi', 'icon' => 'carrot', 'color' => 'orange', 'done' => ($todayEntry && $todayEntry->habit_5), 'detail' => $todayEntry->habit_5_menu ?? null],
                
                // 5. Belajar (Habit 4 - Blue)
                ['label' => 'Gemar Belajar', 'icon' => 'book-open-text', 'color' => 'blue', 'done' => ($todayEntry && $todayEntry->habit_4), 'detail' => $todayEntry->habit_4_subject ?? null],
                
                // 6. Sosial (Habit 6 - Purple)
                ['label' => 'Bantu Orang Tua', 'icon' => 'users-three', 'color' => 'purple', 'done' => ($todayEntry && $todayEntry->habit_6), 'detail' => $todayEntry->habit_6_activity ?? null],
            ];
        ?>

        <?php $__currentLoopData = $habits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $bgColor = $h['done'] ? "bg-{$h['color']}-50" : "bg-slate-50";
                $borderColor = $h['done'] ? "border-{$h['color']}-200" : "border-slate-100";
                $iconColor = $h['done'] ? "text-{$h['color']}-500" : "text-slate-300";
                $textColor = $h['done'] ? "text-slate-800" : "text-slate-400";
            ?>
            <div class="p-5 rounded-2xl border <?php echo e($bgColor); ?> <?php echo e($borderColor); ?> flex flex-col items-center text-center justify-center relative group transition-all hover:shadow-md">
                <?php if($h['done']): ?>
                    <div class="absolute top-2 right-2 text-emerald-500 bg-white rounded-full p-0.5 shadow-sm">
                        <i class="ph-bold ph-check-circle text-lg"></i>
                    </div>
                <?php endif; ?>

                <i class="ph-duotone ph-<?php echo e($h['icon']); ?> text-3xl mb-3 <?php echo e($iconColor); ?>"></i>
                <h4 class="font-bold text-xs md:text-sm <?php echo e($textColor); ?> mb-1"><?php echo e($h['label']); ?></h4>
                
                <?php if($h['done'] && $h['detail'] && $h['detail'] !== 'Cek Detail'): ?>
                    <p class="text-[10px] text-slate-500 truncate max-w-full px-2">"<?php echo e(Str::limit($h['detail'], 15)); ?>"</p>
                <?php elseif(!$h['done']): ?>
                    <span class="text-[10px] text-slate-400 italic">Belum tercatat</span>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php $isTidurDone = $todayEntry && $todayEntry->habit_7; ?>
    <div class="mb-8 p-5 rounded-2xl border <?php echo e($isTidurDone ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-white border-slate-100 text-slate-400'); ?> flex items-center gap-5 shadow-sm relative overflow-hidden">
        <?php if($isTidurDone): ?>
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
        <?php endif; ?>
        
        <div class="w-12 h-12 rounded-2xl <?php echo e($isTidurDone ? 'bg-white/20 text-white' : 'bg-slate-50 text-slate-300'); ?> flex items-center justify-center text-2xl shrink-0">
            <i class="ph-duotone ph-moon-stars"></i>
        </div>
        <div>
            <h4 class="font-bold text-sm md:text-base <?php echo e($isTidurDone ? 'text-white' : 'text-slate-700'); ?>">7. Tidur Cepat</h4>
            <p class="text-xs <?php echo e($isTidurDone ? 'text-indigo-100' : 'text-slate-400'); ?> mt-0.5">
                <?php echo e($isTidurDone ? 'Tercatat tidur pukul ' . ($todayEntry->habit_7_time ?? '--:--') : 'Belum ada data tidur.'); ?>

            </p>
        </div>
        <?php if($isTidurDone): ?>
            <div class="absolute top-1/2 -translate-y-1/2 right-6 bg-white/20 p-2 rounded-full"><i class="ph-bold ph-check text-white"></i></div>
        <?php endif; ?>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-slate-700 text-sm mb-3 px-2 flex items-center gap-2">
                <i class="ph-fill ph-image text-blue-500"></i> Dokumentasi Hari Ini
            </h3>
            <div class="aspect-video w-full rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden relative">
                <?php if(isset($todayEntry) && $todayEntry->photo_path): ?>
                    <img src="<?php echo e(asset('storage/' . $todayEntry->photo_path)); ?>" class="w-full h-full object-cover transition-transform hover:scale-105 duration-500 cursor-pointer" onclick="window.open(this.src)">
                    <div class="absolute bottom-2 right-2 bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-sm">Klik untuk perbesar</div>
                <?php else: ?>
                    <div class="text-center text-slate-400">
                        <i class="ph-duotone ph-camera-slash text-3xl mb-2"></i>
                        <p class="text-xs">Belum ada foto</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-indigo-50 p-6 rounded-3xl border border-indigo-100 flex flex-col justify-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5"><i class="ph-fill ph-chat-centered-text text-8xl text-indigo-600"></i></div>
            <h3 class="font-bold text-indigo-800 text-sm mb-3 relative z-10 flex items-center gap-2">
                <i class="ph-fill ph-chalkboard-teacher"></i> Catatan Guru Wali
            </h3>
            
            <?php if(isset($todayEntry) && $todayEntry->teacher_feedback): ?>
                <div class="relative z-10">
                    <i class="ph-fill ph-quotes text-indigo-200 text-3xl absolute -top-2 -left-2"></i>
                    <p class="text-sm text-indigo-700 italic leading-relaxed pl-6 relative z-10">
                        "<?php echo e($todayEntry->teacher_feedback); ?>"
                    </p>
                    <p class="text-[10px] font-bold text-indigo-400 uppercase mt-3 text-right">
                        — <?php echo e(\Carbon\Carbon::parse($todayEntry->report_date)->translatedFormat('d F Y')); ?>

                    </p>
                </div>
            <?php else: ?>
                <div class="text-center text-indigo-300 relative z-10 py-4">
                    <p class="text-xs italic">Belum ada catatan dari guru untuk hari ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-kebiasaan.blade.php ENDPATH**/ ?>