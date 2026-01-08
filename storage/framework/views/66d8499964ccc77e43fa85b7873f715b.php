<?php $__env->startSection('content'); ?>
    <?php \Carbon\Carbon::setLocale('id'); ?>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        
        <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 rounded-[2.5rem] p-8 md:p-12 overflow-hidden shadow-2xl shadow-blue-900/30 mb-10 text-white border border-white/10">
            
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left">
                    <span class="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold tracking-wider uppercase mb-3 border border-white/10 text-blue-200">
                        Dashboard Siswa
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black mb-4 leading-tight tracking-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-200"><?php echo e(Auth::guard('student')->user()->name ?? 'Sahabat'); ?>!</span> 👋
                    </h1>
                    <p class="text-blue-100/80 text-lg max-w-xl">
                        Ayo teruskan langkahmu menjadi <span class="font-bold text-white">Anak Indonesia Hebat</span>. Pantau aktivitasmu di sini setiap hari!
                    </p>
                    
                    <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                        <?php if(!$todayEntry): ?>
                            <a href="<?php echo e(route('student.habits.index')); ?>" class="group relative px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-1 hover:shadow-xl flex items-center gap-3 border border-blue-400">
                                <i class="ph-bold ph-rocket-launch text-xl group-hover:rotate-12 transition-transform"></i>
                                Isi Jurnal Hari Ini
                            </a>
                        <?php else: ?>
                            <button disabled class="px-8 py-4 bg-slate-800 text-blue-400 font-bold rounded-2xl shadow-lg flex items-center gap-3 opacity-90 cursor-not-allowed border border-blue-500/30">
                                <i class="ph-fill ph-check-circle text-2xl"></i>
                                Sudah Lapor Hari Ini
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="shrink-0 relative">
                    <div class="w-48 h-48 md:w-64 md:h-64 bg-slate-900/50 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/10 shadow-inner">
                        <div class="text-center">
                            <p class="text-blue-300 text-sm font-bold uppercase mb-1">Total Poin</p>
                            <h2 class="text-6xl font-black text-white tracking-tighter"><?php echo e(number_format($totalPoints ?? 0)); ?></h2>
                            <p class="text-xs text-blue-400 mt-2 font-bold">LEVEL 1</p>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-4 -right-4 bg-blue-600 text-white px-4 py-2 rounded-xl shadow-lg font-bold text-sm flex items-center gap-2 animate-bounce border border-blue-400">
                        <i class="ph-fill ph-trophy text-yellow-400 text-lg"></i>
                        Hebat!
                    </div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="space-y-6">
                
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-calendar-check text-8xl text-blue-600"></i>
                    </div>
                    <p class="text-slate-500 text-sm font-bold uppercase mb-1">Laporan Bulan Ini</p>
                    <h3 class="text-4xl font-black text-slate-800 mb-2"><?php echo e($monthlyCount ?? 0); ?> <span class="text-lg text-slate-400 font-medium">Hari</span></h3>
                    <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full" style="width: <?php echo e(min((($monthlyCount ?? 0)/30)*100, 100)); ?>%"></div>
                    </div>
                </div>

                
                <?php $lastFeedback = $recentActivities->whereNotNull('teacher_feedback')->first(); ?>
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-3xl text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-10"><i class="ph-fill ph-chat-circle-dots text-8xl"></i></div>
                    <h4 class="font-bold text-blue-100 text-xs uppercase tracking-widest mb-3">Pesan Guru Terakhir</h4>
                    <?php if($lastFeedback): ?>
                        <p class="font-medium text-sm leading-relaxed mb-4 italic line-clamp-3">
                            "<?php echo e($lastFeedback->teacher_feedback); ?>"
                        </p>
                        <p class="text-[10px] font-bold text-blue-200 uppercase"><?php echo e(\Carbon\Carbon::parse($lastFeedback->report_date)->translatedFormat('d M Y')); ?></p>
                    <?php else: ?>
                        <p class="text-sm opacity-70 italic mb-4">Belum ada feedback terbaru dari bapak/ibu guru.</p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-star text-blue-500"></i> 
                        Misi 7 Kebiasaan Baik
                    </h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php
                        $habits = [
                            ['icon' => 'sun-horizon', 'color' => 'blue', 'label' => 'Bangun Pagi'],
                            ['icon' => 'drop', 'color' => 'cyan', 'label' => 'Mandi Rapi'],
                            ['icon' => 'sneaker-move', 'color' => 'indigo', 'label' => 'Olahraga'],
                            ['icon' => 'book-open-text', 'color' => 'blue', 'label' => 'Belajar'],
                            ['icon' => 'carrot', 'color' => 'cyan', 'label' => 'Makan Sehat'],
                            ['icon' => 'users-three', 'color' => 'indigo', 'label' => 'Sosial'],
                        ];
                    ?>
                    <?php $__currentLoopData = $habits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 rounded-2xl bg-<?php echo e($h['color']); ?>-50 border border-<?php echo e($h['color']); ?>-100 text-center hover:bg-<?php echo e($h['color']); ?>-100 transition-colors group">
                            <i class="ph-duotone ph-<?php echo e($h['icon']); ?> text-3xl text-<?php echo e($h['color']); ?>-600 mb-2 block group-hover:scale-110 transition-transform"></i>
                            <h4 class="font-bold text-slate-700 text-xs"><?php echo e($h['label']); ?></h4>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 md:col-span-2 flex items-center gap-4 text-white">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-3xl text-blue-400 shrink-0">
                            <i class="ph-duotone ph-moon-stars"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Tidur Cukup</h4>
                            <p class="text-[10px] text-slate-400">Istirahat tepat waktu agar bugar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-10">
            <h3 class="text-lg font-bold text-slate-700 mb-6 flex items-center gap-2 px-2">
                <i class="ph-bold ph-clock-counter-clockwise text-blue-600"></i> Riwayat & Feedback Guru
            </h3>
            
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aktivitas</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pesan Dari Guru</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-slate-700 block text-sm">
                                            <?php echo e(\Carbon\Carbon::parse($activity->report_date)->translatedFormat('d F Y')); ?>

                                        </span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">DIKIRIM <?php echo e($activity->created_at->format('H:i')); ?></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <div class="flex -space-x-2">
                                                <?php if($activity->habit_1): ?> <div class="w-5 h-5 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center" title="Ibadah"><i class="ph-fill ph-sun-horizon text-[10px] text-blue-600"></i></div> <?php endif; ?>
                                                <?php if($activity->habit_3): ?> <div class="w-5 h-5 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center" title="Olahraga"><i class="ph-fill ph-sneaker-move text-[10px] text-indigo-600"></i></div> <?php endif; ?>
                                                <?php if($activity->habit_4): ?> <div class="w-5 h-5 rounded-full bg-cyan-100 border-2 border-white flex items-center justify-center" title="Belajar"><i class="ph-fill ph-book-open-text text-[10px] text-cyan-600"></i></div> <?php endif; ?>
                                            </div>
                                            <span class="text-xs text-slate-500 font-medium">Lengkap</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <?php if($activity->teacher_feedback): ?>
                                            <div class="flex items-start gap-3 bg-blue-50/50 p-3 rounded-xl border border-blue-100 group-hover:bg-white group-hover:border-blue-300 transition-all">
                                                <i class="ph-fill ph-chat-circle-dots text-blue-500 text-lg shrink-0 mt-0.5"></i>
                                                <p class="text-xs text-blue-700 italic leading-relaxed">
                                                    "<?php echo e($activity->teacher_feedback); ?>"
                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[10px] font-bold text-slate-300 uppercase italic px-3">Belum ada feedback</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="inline-flex items-center gap-1 text-blue-600 font-bold text-[10px] bg-blue-50 px-3 py-1 rounded-full border border-blue-100 uppercase tracking-wide">
                                            <i class="ph-fill ph-check-circle"></i> Berhasil
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="py-20 text-center">
                                        <i class="ph-duotone ph-notebook text-4xl text-slate-200 mb-2"></i>
                                        <p class="text-slate-400 text-sm">Belum ada riwayat jurnal.</p>
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