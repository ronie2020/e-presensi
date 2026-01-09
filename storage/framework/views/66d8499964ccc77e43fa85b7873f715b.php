<?php $__env->startSection('content'); ?>
    <?php 
        \Carbon\Carbon::setLocale('id'); 
        $user = Auth::guard('student')->user();
        
        // Data Fallback jika controller tidak mengirimkan variabel tertentu
        $todayHabit = $todayHabit ?? \App\Models\StudentHabit::where('student_id', $user->id)
                        ->whereDate('report_date', now())
                        ->first();
        
        $totalPoints = $totalPoints ?? 0;
        $monthlyCount = $monthlyCount ?? 0;
        $recentActivities = $recentActivities ?? collect([]);
    ?>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-20 pt-24 font-sans">
        
        
        <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 rounded-[2.5rem] p-8 md:p-12 overflow-hidden shadow-2xl shadow-blue-900/30 mb-10 text-white border border-white/10 group">
            
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all duration-1000"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all duration-1000"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left flex-1">
                    <span class="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold tracking-wider uppercase mb-3 border border-white/10 text-blue-200">
                        Dashboard Siswa
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black mb-4 leading-tight tracking-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-200"><?php echo e($user->name ?? 'Sahabat'); ?>!</span> 👋
                    </h1>
                    <p class="text-blue-100/80 text-lg max-w-xl leading-relaxed">
                        Sudahkah kamu melakukan kebiasaan baik hari ini? Terus tingkatkan poinmu menjadi <span class="font-bold text-white">Siswa Berkarakter!</span>
                    </p>
                    
                    <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                        <?php if(!$todayHabit): ?>
                            <a href="<?php echo e(route('student.habits.index')); ?>" class="group relative px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-400 hover:to-blue-500 text-white font-black rounded-2xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-1 hover:shadow-xl flex items-center gap-3 border border-blue-400">
                                <i class="ph-bold ph-rocket-launch text-xl group-hover:rotate-12 transition-transform"></i>
                                Isi Jurnal Harian
                            </a>
                        <?php else: ?>
                            <button disabled class="px-8 py-4 bg-slate-800/50 backdrop-blur-sm text-blue-200 font-bold rounded-2xl shadow-lg flex items-center gap-3 cursor-not-allowed border border-white/10">
                                <i class="ph-fill ph-check-circle text-2xl text-emerald-400"></i>
                                Laporan Hari Ini Aktif
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="shrink-0 relative group">
                    <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                    <div class="relative w-40 h-40 md:w-52 md:h-52 bg-slate-900/60 rounded-full flex flex-col items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                        <p class="text-blue-300 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1">Total Poin</p>
                        <h2 class="text-5xl md:text-6xl font-black text-white tracking-tighter"><?php echo e(number_format($totalPoints)); ?></h2>
                        <div class="mt-2 px-3 py-1 bg-white/10 rounded-full text-[10px] font-bold text-blue-200 border border-white/5">LEVEL 1</div>
                    </div>
                    
                    
                    <?php if($totalPoints > 100): ?>
                    <div class="absolute -bottom-2 -right-2 bg-gradient-to-br from-yellow-400 to-orange-500 text-white p-3 rounded-2xl shadow-lg border-2 border-slate-900 animate-bounce">
                        <i class="ph-fill ph-trophy text-2xl"></i>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            
            <div class="space-y-6">
                
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-calendar-check text-8xl text-blue-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Konsistensi Bulan Ini</p>
                        <div class="flex items-baseline gap-2 mb-4">
                            <h3 class="text-4xl font-black text-slate-800"><?php echo e($monthlyCount); ?></h3>
                            <span class="text-sm text-slate-500 font-bold">Hari Lapor</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-400 h-full rounded-full transition-all duration-1000" style="width: <?php echo e(min(($monthlyCount/30)*100, 100)); ?>%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 text-right font-medium">Target: 30 Hari</p>
                    </div>
                </div>

                
                <?php $lastFeedback = $recentActivities->whereNotNull('teacher_feedback')->first(); ?>
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-[2rem] text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 opacity-10 group-hover:rotate-12 transition-transform duration-700">
                        <i class="ph-fill ph-chat-circle-dots text-9xl"></i>
                    </div>
                    <h4 class="font-bold text-indigo-200 text-[10px] uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Pesan Guru Terakhir</h4>
                    <?php if($lastFeedback): ?>
                        <div class="relative">
                            <i class="ph-fill ph-quotes text-4xl text-white/20 absolute -top-2 -left-2"></i>
                            <p class="font-medium text-sm leading-relaxed mb-4 italic text-white/90 pl-6 relative z-10">
                                "<?php echo e($lastFeedback->teacher_feedback); ?>"
                            </p>
                        </div>
                        <div class="flex items-center gap-2 mt-4 opacity-80">
                            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs"><i class="ph-bold ph-user"></i></div>
                            <p class="text-[10px] font-bold uppercase"><?php echo e(\Carbon\Carbon::parse($lastFeedback->report_date)->translatedFormat('d M Y')); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6 opacity-60">
                            <i class="ph-duotone ph-chat-slash text-3xl mb-2"></i>
                            <p class="text-xs">Belum ada pesan baru.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-6 md:p-8 border border-slate-100 shadow-sm relative">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                            <i class="ph-fill ph-star text-yellow-400"></i> 
                            Misi Kebiasaan Baik
                        </h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Selesaikan misi harianmu untuk mendapatkan poin.</p>
                    </div>
                    <div class="px-3 py-1 bg-slate-50 rounded-lg border border-slate-100 text-[10px] font-bold text-slate-500">
                        <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php
                        $habits = [
                            ['id' => 'habit_1', 'icon' => 'sun-horizon', 'color' => 'blue', 'label' => 'Bangun Pagi'],
                            ['id' => 'habit_2', 'icon' => 'drop', 'color' => 'cyan', 'label' => 'Mandi Rapi'],
                            ['id' => 'habit_3', 'icon' => 'sneaker-move', 'color' => 'indigo', 'label' => 'Olahraga'],
                            ['id' => 'habit_4', 'icon' => 'book-open-text', 'color' => 'blue', 'label' => 'Belajar'],
                            // HABIT 5: MAKAN SEHAT (TERINTEGRASI SCAN)
                            ['id' => 'habit_5', 'icon' => 'carrot', 'color' => 'orange', 'label' => 'Makan Sehat'], 
                            ['id' => 'habit_6', 'icon' => 'users-three', 'color' => 'indigo', 'label' => 'Sosial'],
                        ];
                    ?>

                    <?php $__currentLoopData = $habits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Logic Cek Status
                            $isDone = $todayHabit && $todayHabit->{$h['id']} == true;
                            
                            // Visual Logic
                            $bgColor = $isDone ? "bg-{$h['color']}-500" : "bg-{$h['color']}-50";
                            $borderColor = $isDone ? "border-{$h['color']}-600" : "border-{$h['color']}-100";
                            $textColor = $isDone ? "text-white" : "text-slate-600";
                            $iconColor = $isDone ? "text-white" : "text-{$h['color']}-500";
                            $shadow = $isDone ? "shadow-lg shadow-{$h['color']}-500/30" : "hover:bg-{$h['color']}-100";
                        ?>

                        <div class="relative p-5 rounded-2xl border transition-all duration-300 group overflow-hidden <?php echo e($bgColor); ?> <?php echo e($borderColor); ?> <?php echo e($shadow); ?>">
                            
                            
                            <i class="ph-duotone ph-<?php echo e($h['icon']); ?> absolute -right-4 -bottom-4 text-6xl opacity-10 rotate-12 group-hover:scale-125 transition-transform duration-500"></i>

                            <div class="relative z-10 flex flex-col items-center text-center h-full justify-center">
                                
                                <?php if($isDone): ?>
                                    <div class="absolute top-0 right-0 bg-white/20 backdrop-blur-sm rounded-bl-xl p-1.5 animate-enter">
                                        <i class="ph-bold ph-check text-white text-sm"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3 p-3 rounded-full <?php echo e($isDone ? 'bg-white/20' : 'bg-white shadow-sm'); ?>">
                                    <i class="ph-duotone ph-<?php echo e($h['icon']); ?> text-2xl md:text-3xl <?php echo e($iconColor); ?>"></i>
                                </div>
                                
                                <h4 class="font-bold text-xs md:text-sm <?php echo e($textColor); ?>"><?php echo e($h['label']); ?></h4>
                                
                                
                                <?php if($h['id'] == 'habit_5' && !$isDone): ?>
                                    <div class="mt-2 px-2 py-1 bg-white/60 rounded-md border border-orange-200/50 backdrop-blur-sm">
                                        <p class="text-[9px] text-orange-600 font-bold animate-pulse flex items-center justify-center gap-1">
                                            <i class="ph-bold ph-qr-code"></i> Scan di Kantin
                                        </p>
                                    </div>
                                <?php elseif(!$isDone): ?>
                                    <p class="text-[9px] <?php echo e($textColor); ?> opacity-60 mt-1">Belum tercatat</p>
                                <?php else: ?>
                                    <p class="text-[9px] text-white/80 mt-1 font-medium">Tuntas!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="mt-4 p-5 rounded-2xl bg-slate-900 border border-slate-800 flex items-center gap-5 text-white relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-3xl text-indigo-400 shrink-0 border border-white/5 shadow-inner">
                        <i class="ph-duotone ph-moon-stars"></i>
                    </div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-sm md:text-base">Istirahat Cukup</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Jangan begadang ya! Tidur minimal 8 jam agar besok segar.</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-12">
            <div class="flex items-center justify-between mb-6 px-2">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-bold ph-clock-counter-clockwise text-blue-600"></i> Riwayat Aktivitas
                </h3>
                <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">Lihat Semua</a>
            </div>
            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Capaian Misi</th>
                                <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Catatan Guru</th>
                                <th class="py-5 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/20 transition-colors group">
                                    <td class="py-5 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                                <?php echo e(\Carbon\Carbon::parse($activity->report_date)->format('d')); ?>

                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-700 block text-xs md:text-sm">
                                                    <?php echo e(\Carbon\Carbon::parse($activity->report_date)->translatedFormat('F Y')); ?>

                                                </span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                                    <?php echo e(\Carbon\Carbon::parse($activity->report_date)->translatedFormat('l')); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div class="flex -space-x-2">
                                            
                                            <?php if($activity->habit_1): ?> <div title="Bangun Pagi" class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-blue-600"><i class="ph-fill ph-sun-horizon text-xs"></i></div> <?php endif; ?>
                                            <?php if($activity->habit_3): ?> <div title="Olahraga" class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600"><i class="ph-fill ph-sneaker-move text-xs"></i></div> <?php endif; ?>
                                            <?php if($activity->habit_5): ?> <div title="Makan Sehat" class="w-8 h-8 rounded-full bg-orange-100 border-2 border-white flex items-center justify-center text-orange-600"><i class="ph-fill ph-carrot text-xs"></i></div> <?php endif; ?>
                                            
                                            
                                            <?php 
                                                $totalDone = collect(['habit_1','habit_2','habit_3','habit_4','habit_5','habit_6'])->filter(fn($h) => $activity->$h)->count(); 
                                                $shown = ($activity->habit_1 ? 1:0) + ($activity->habit_3 ? 1:0) + ($activity->habit_5 ? 1:0);
                                                $remaining = $totalDone - $shown;
                                            ?>
                                            
                                            <?php if($remaining > 0): ?>
                                            <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                +<?php echo e($remaining); ?>

                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <?php if($activity->teacher_feedback): ?>
                                            <div class="flex items-start gap-3 bg-indigo-50 p-3 rounded-xl border border-indigo-100 max-w-xs">
                                                <i class="ph-fill ph-chat-circle-text text-indigo-400 mt-0.5"></i>
                                                <p class="text-xs text-indigo-800 italic leading-snug">
                                                    "<?php echo e(Str::limit($activity->teacher_feedback, 60)); ?>"
                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[10px] font-bold text-slate-300 uppercase italic px-2">-- Menunggu Review --</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-5 px-6 text-right">
                                        <span class="inline-flex items-center gap-1.5 text-emerald-600 font-bold text-[10px] bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100 uppercase tracking-wide">
                                            <i class="ph-fill ph-check-circle"></i> Tercatat
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="ph-duotone ph-notebook text-4xl"></i>
                                        </div>
                                        <h4 class="text-slate-600 font-bold text-sm">Belum ada riwayat</h4>
                                        <p class="text-slate-400 text-xs mt-1">Mulailah mengisi jurnal kebiasaan baikmu!</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/student_dashboard.blade.php ENDPATH**/ ?>