<?php $__env->startSection('content'); ?>
    <?php 
        \Carbon\Carbon::setLocale('id'); 
        $user = Auth::guard('student')->user();
        
        $todayHabit = $todayHabit ?? \App\Models\StudentHabit::where('student_id', $user->id)
                        ->whereDate('report_date', now())
                        ->first();
        
        $totalPoints = $totalPoints ?? 0;
        $monthlyCount = $monthlyCount ?? 0;
        $recentActivities = $recentActivities ?? collect([]);

        // --- LOGIKA CEK MISI SELESAI ---
        $isMissionComplete = false;
        if($todayHabit) {
            $isMissionComplete = $todayHabit->habit_1 && 
                                 $todayHabit->habit_2 && 
                                 ($todayHabit->prayer_subuh || $todayHabit->prayer_dzuhur || $todayHabit->prayer_ashar || $todayHabit->prayer_maghrib || $todayHabit->prayer_isya || $todayHabit->prayer_dhuha) &&
                                 $todayHabit->habit_3 && 
                                 $todayHabit->habit_4 && 
                                 $todayHabit->habit_5 && 
                                 $todayHabit->habit_6 && 
                                 $todayHabit->habit_7;   
        }
    ?>

    
    <style>
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
    </style>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-20 pt-24 font-sans text-[#2A3B52] bg-[#f8fafc] min-h-screen">
        
        
        <div class="relative bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] rounded-[2.5rem] p-8 md:p-12 overflow-hidden shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] mb-10 text-[#2A3B52] border border-white/40 group">
            
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/30 rounded-full blur-[80px] group-hover:opacity-70 transition-all duration-1000 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white/20 rounded-full blur-[60px] pointer-events-none"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left flex-1">
                    <a href="<?php echo e(route('portal.show', Auth::guard('student')->id() ?? 0)); ?>" class="inline-flex items-center gap-2 text-[#2A3B52] hover:bg-white/60 transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em] bg-white/40 px-4 py-1.5 rounded-full border border-white/50 backdrop-blur-sm shadow-sm">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke profil
                    </a>
                    <h1 class="text-3xl md:text-5xl font-black mb-4 leading-tight tracking-tight text-[#2A3B52]">
                        Halo, <span><?php echo e($user->name ?? 'Sahabat'); ?>!</span> 👋
                    </h1>
                    <p class="text-[#2A3B52]/80 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Sudahkah kamu melakukan kebiasaan baik hari ini? Terus tingkatkan poinmu menjadi <span class="font-bold text-[#2A3B52]">Siswa Berkarakter!</span>
                    </p>
                    
                    
                    <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                        <?php if(!$todayHabit): ?>
                            <a href="<?php echo e(route('student.habits.index')); ?>" class="group relative px-6 py-3.5 bg-[#5295FF] hover:bg-[#3b7ee6] text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-3">
                                <i class="ph-bold ph-rocket-launch text-xl group-hover:rotate-12 transition-transform"></i>
                                Isi Jurnal Harian
                            </a>
                        <?php elseif(!$isMissionComplete): ?>
                            <a href="<?php echo e(route('student.habits.index')); ?>" class="group relative px-6 py-3.5 bg-[#D83B01] hover:bg-[#b53201] text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-3">
                                <i class="ph-bold ph-pencil-simple text-xl group-hover:rotate-12 transition-transform"></i>
                                Lanjutkan Misi
                                <span class="bg-white/20 px-2 py-0.5 rounded text-[10px] ml-1">Draft</span>
                            </a>
                        <?php else: ?>
                            <button disabled class="px-6 py-3.5 bg-[#DFF6DD] text-[#107C10] font-bold rounded-xl shadow-sm flex items-center gap-3 cursor-not-allowed border border-[#B7DFB9]">
                                <i class="ph-fill ph-check-circle text-2xl text-[#107C10]"></i>
                                Misi Hari Ini Tuntas!
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="shrink-0 relative group">
                    <div class="absolute inset-0 bg-white/40 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                    <div class="relative w-40 h-40 md:w-52 md:h-52 bg-white/30 rounded-full flex flex-col items-center justify-center backdrop-blur-md border border-white/50 shadow-sm">
                        <p class="text-[#2A3B52] text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1">Total Poin</p>
                        <h2 class="text-5xl md:text-6xl font-black text-[#2A3B52] tracking-tighter"><?php echo e(number_format($totalPoints)); ?></h2>
                    </div>
                    
                    <?php if($totalPoints > 100): ?>
                    <div class="absolute -bottom-2 -right-2 bg-[#D83B01] text-white p-3 rounded-2xl shadow-sm border-2 border-white animate-bounce">
                        <i class="ph-fill ph-trophy text-2xl"></i>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
          
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 fluent-card relative overflow-hidden group hover:border-[#5295FF]">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform duration-500 text-[#5295FF]">
                        <i class="ph-fill ph-calendar-check text-8xl"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Konsistensi Bulan Ini</p>
                        <div class="flex items-baseline gap-2 mb-4">
                            <h3 class="text-4xl font-black text-[#2A3B52]"><?php echo e($monthlyCount); ?></h3>
                            <span class="text-sm text-slate-500 font-bold">Hari Lapor</span>
                        </div>
                        <div class="w-full bg-[#F3F9FD] h-2 rounded-full overflow-hidden border border-[#D0E7F8]">
                            <div class="bg-[#5295FF] h-full rounded-full transition-all duration-1000" style="width: <?php echo e(min(($monthlyCount/30)*100, 100)); ?>%"></div>
                        </div>
                    </div>
                </div>

                
                <?php $lastFeedback = $recentActivities->whereNotNull('teacher_feedback')->first(); ?>
                <div class="bg-[#F3F9FD] p-6 rounded-2xl border border-[#D0E7F8] text-[#2A3B52] shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 opacity-10 text-[#5295FF] group-hover:rotate-12 transition-transform duration-700"><i class="ph-fill ph-chat-circle-dots text-9xl"></i></div>
                    <h4 class="font-bold text-[#5295FF] text-[10px] uppercase tracking-widest mb-4 border-b border-[#D0E7F8] pb-2">Pesan Guru Terakhir</h4>
                    <?php if($lastFeedback): ?>
                        <p class="font-medium text-sm leading-relaxed mb-4 italic text-[#2A3B52] relative z-10">"<?php echo e($lastFeedback->teacher_feedback); ?>"</p>
                        <p class="text-[10px] font-bold uppercase text-slate-500"><?php echo e(\Carbon\Carbon::parse($lastFeedback->report_date)->translatedFormat('d M Y')); ?></p>
                    <?php else: ?>
                        <p class="text-xs text-slate-500 text-center py-4 font-medium">Belum ada pesan baru.</p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 md:p-8 fluent-card relative">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-[#2A3B52] flex items-center gap-2">
                            <i class="ph-fill ph-star text-[#D83B01]"></i> Misi Harian
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Selesaikan 7 kebiasaan baik ini.</p>
                    </div>
                    <div class="px-3 py-1 bg-slate-50 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-500"><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php
                        $habits = [
                            ['id' => 'habit_1', 'icon' => 'sun-horizon', 'theme' => 'blue', 'label' => 'Bangun & Mandi'],
                            ['id' => 'prayer_check', 'icon' => 'mosque', 'theme' => 'green', 'label' => 'Shalat Tepat Waktu'],
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

                    <?php $__currentLoopData = $habits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isDone = false;
                            if ($h['id'] == 'prayer_check') {
                                $isDone = $todayHabit && ($todayHabit->prayer_subuh || $todayHabit->prayer_dhuha || $todayHabit->prayer_dzuhur || $todayHabit->prayer_ashar || $todayHabit->prayer_maghrib || $todayHabit->prayer_isya);
                            } elseif ($h['id'] == 'habit_1') {
                                $isDone = $todayHabit && $todayHabit->habit_1 && $todayHabit->habit_2;
                            } else {
                                $isDone = $todayHabit && $todayHabit->{$h['id']} == true;
                            }
                            
                            $t = $themeMap[$h['theme']];
                            $bgColor = $isDone ? $t['done_bg'] : $t['bg'];
                            $borderColor = $isDone ? $t['done_border'] : $t['border'];
                            $textColor = $isDone ? 'text-white' : $t['text'];
                            $iconColor = $isDone ? 'text-white' : $t['text'];
                            $shadow = $isDone ? "shadow-sm shadow-{$t['done_bg']}/30" : "hover:shadow-sm";
                        ?>

                        <div class="relative p-5 rounded-2xl border transition-all duration-300 group overflow-hidden <?php echo e($bgColor); ?> <?php echo e($borderColor); ?> <?php echo e($shadow); ?>">
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

                
                <?php $isTidurDone = $todayHabit && $todayHabit->habit_7; ?>
                <div class="mt-4 p-4 rounded-xl border flex items-center gap-4 relative overflow-hidden transition-all <?php echo e($isTidurDone ? 'bg-[#5295FF] border-[#5295FF] text-white shadow-sm' : 'bg-slate-50 border-slate-200 text-[#2A3B52]'); ?>">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl shrink-0 <?php echo e($isTidurDone ? 'bg-white/20 text-white' : 'bg-white text-slate-500 border border-slate-200 shadow-sm'); ?>">
                        <i class="ph-duotone ph-moon-stars"></i>
                    </div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-sm">7. Tidur Cepat</h4>
                        <p class="text-xs <?php echo e($isTidurDone ? 'text-white/80' : 'text-slate-500'); ?> mt-0.5 font-medium">
                            <?php echo e($isTidurDone ? 'Tercatat tidur pukul ' . $todayHabit->habit_7_time : 'Maksimal istirahat jam 22:00.'); ?>

                        </p>
                    </div>
                    <?php if($isTidurDone): ?>
                        <div class="absolute top-4 right-4 bg-white/20 p-1.5 rounded-full"><i class="ph-bold ph-check text-white text-xs"></i></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="mt-10">
            <div class="flex items-center justify-between mb-4 px-1">
                <h3 class="text-lg font-bold text-[#2A3B52] flex items-center gap-2">
                    <i class="ph-bold ph-clock-counter-clockwise text-[#5295FF]"></i> Riwayat Aktivitas
                </h3>
            </div>
            
            <div class="bg-white rounded-2xl fluent-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="py-4 px-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tanggal</th>
                                <th class="py-4 px-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Capaian Misi</th>
                                <th class="py-4 px-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Catatan Guru</th>
                                <th class="py-4 px-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[#2A3B52] font-bold text-xs group-hover:bg-[#F3F9FD] group-hover:text-[#5295FF] group-hover:border-[#D0E7F8] transition-colors shadow-sm">
                                                <?php echo e(\Carbon\Carbon::parse($activity->report_date)->format('d')); ?>

                                            </div>
                                            <div>
                                                <span class="font-bold text-[#2A3B52] block text-sm">
                                                    <?php echo e(\Carbon\Carbon::parse($activity->report_date)->translatedFormat('F Y')); ?>

                                                </span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                                    <?php echo e(\Carbon\Carbon::parse($activity->report_date)->translatedFormat('l')); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="flex -space-x-2">
                                            <?php if($activity->habit_1): ?> <div title="Bangun Pagi" class="w-8 h-8 rounded-full bg-[#F3F9FD] border-2 border-white flex items-center justify-center text-[#5295FF] shadow-sm"><i class="ph-fill ph-sun-horizon text-xs"></i></div> <?php endif; ?>
                                            <?php if($activity->habit_3): ?> <div title="Olahraga" class="w-8 h-8 rounded-full bg-[#FFEFD6] border-2 border-white flex items-center justify-center text-[#D83B01] shadow-sm"><i class="ph-fill ph-sneaker-move text-xs"></i></div> <?php endif; ?>
                                            <?php if($activity->habit_5): ?> <div title="Makan Sehat" class="w-8 h-8 rounded-full bg-[#FDE7E9] border-2 border-white flex items-center justify-center text-[#D13438] shadow-sm"><i class="ph-fill ph-carrot text-xs"></i></div> <?php endif; ?>
                                            
                                            <?php 
                                                $totalDone = collect(['habit_1','habit_2','habit_3','habit_4','habit_5','habit_6'])->filter(fn($h) => $activity->$h)->count(); 
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
                                    <td class="py-4 px-5">
                                        <?php if($activity->teacher_feedback): ?>
                                            <div class="flex items-start gap-2 bg-[#F3F9FD] p-2.5 rounded-lg border border-[#D0E7F8] max-w-xs shadow-sm">
                                                <i class="ph-fill ph-chat-circle-text text-[#5295FF] mt-0.5"></i>
                                                <p class="text-xs text-[#2A3B52] font-medium leading-snug">
                                                    "<?php echo e(Str::limit($activity->teacher_feedback, 60)); ?>"
                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase italic">-- Menunggu Review --</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <span class="inline-flex items-center gap-1.5 text-[#107C10] font-bold text-[10px] bg-[#DFF6DD] px-2.5 py-1 rounded-md border border-[#B7DFB9] uppercase tracking-wide shadow-sm">
                                            <i class="ph-fill ph-check-circle"></i> Tercatat
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="py-16 text-center">
                                        <div class="w-16 h-16 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 shadow-sm">
                                            <i class="ph-duotone ph-notebook text-3xl"></i>
                                        </div>
                                        <h4 class="text-[#2A3B52] font-bold text-sm">Belum ada riwayat</h4>
                                        <p class="text-slate-500 text-xs mt-1">Mulailah mengisi jurnal kebiasaan baikmu!</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success', title: 'Hebat!', text: "<?php echo e(session('success')); ?>",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 5000,
                    customClass: { popup: 'fluent-modal rounded-xl border border-[#B7DFB9] bg-white', title: 'text-[#107C10] font-bold' }
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "<?php echo e(session('error')); ?>",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 5000,
                    customClass: { popup: 'fluent-modal rounded-xl border border-[#F4C3C9] bg-white' }
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/student_dashboard.blade.php ENDPATH**/ ?>