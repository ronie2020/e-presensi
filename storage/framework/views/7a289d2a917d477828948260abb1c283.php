
<?php
    $date = \Carbon\Carbon::now();
    
    // --- PENGATURAN MANUAL TANGGAL HIJRIYAH ---
    // Ubah angka ini jika tanggal Hijriyah selisih dengan ketetapan pemerintah.
    // Contoh: 
    // 0  = Sesuai algoritma komputer
    // 1  = Ditambah 1 hari (Maju)
    // -1 = Dikurang 1 hari (Mundur)
    $hijriOffset = -1; 

    // Default Fallback
    $hijriString = 'Tanggal Hijriyah';
    $hijriDateFull = '-';

    // Cek apakah server mendukung Intl Calendar Islamic
    if(extension_loaded('intl')) {
        try {
            $fmt = new IntlDateFormatter(
                'id_ID@calendar=islamic', 
                IntlDateFormatter::FULL, 
                IntlDateFormatter::NONE, 
                'Asia/Jakarta', 
                IntlDateFormatter::TRADITIONAL
            );
            
            // Terapkan Offset pada timestamp untuk Hijriyah saja
            // Kita copy object date agar tanggal Masehi utama tidak ikut berubah
            $hijriTimestamp = $date->copy()->addDays($hijriOffset)->getTimestamp();
            
            $hijriString = $fmt->format($hijriTimestamp);
            // Hasil biasanya: "Selasa, 1 Ramadan 1447 AH"
            
            // Bersihkan format string
            $hijriString = str_replace([' AH', ' H'], '', $hijriString);
            $parts = explode(',', $hijriString);
            $hijriDateFull = trim(end($parts)); // Ambil bagian "1 Ramadan 1447"
            
        } catch (\Exception $e) {
            // Fallback jika error
        }
    }
?>

<div class="space-y-8 animate-in fade-in duration-500 font-sans">
    
    
    <?php if(isset($priorityExams) && $priorityExams->isNotEmpty()): ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $priorityExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priorityExam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $existingSession = \App\Models\CbtStudentExam::where('student_id', $student->id)
                                        ->where('cbt_exam_id', $priorityExam->id)
                                        ->first();
                    $isOngoing = $existingSession && $existingSession->status == 'ongoing';
                ?>

                <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl shadow-slate-900/20 lg:p-10 border border-slate-800">
                    <!-- Background -->
                    <?php if($loop->iteration % 2 == 0): ?>
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 h-80 w-80 rounded-full bg-indigo-600 blur-[100px] opacity-40 animate-pulse"></div>
                    <?php else: ?>
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 h-80 w-80 rounded-full bg-rose-600 blur-[100px] opacity-40 animate-pulse"></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <span class="inline-flex items-center gap-2 rounded-full bg-rose-500/20 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-rose-300 border border-rose-500/30 animate-pulse">
                                    <i class="ph-fill ph-broadcast"></i> Ujian Aktif
                                </span>
                                <?php if($isOngoing): ?>
                                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-amber-300 border border-amber-500/30">
                                        <i class="ph-fill ph-clock-countdown"></i> Lanjutkan
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h2 class="text-2xl md:text-3xl font-black leading-tight mb-2 text-white">
                                <?php echo e($priorityExam->title); ?>

                            </h2>
                            
                            <div class="flex flex-wrap items-center gap-3 md:gap-4 text-sm font-medium text-slate-300 mt-4">
                                
                                <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5">
                                    <i class="ph-fill ph-book-open text-blue-400"></i> <?php echo e($priorityExam->subject_name); ?>

                                </span>
                                
                                
                                <?php if(isset($priorityExam->start_time)): ?>
                                    <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5" title="Tanggal Ujian">
                                        <i class="ph-fill ph-calendar-blank text-purple-400"></i> 
                                        <?php echo e(\Carbon\Carbon::parse($priorityExam->start_time)->translatedFormat('l, d M Y')); ?>

                                    </span>
                                    
                                    
                                    <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5" title="Waktu Ujian">
                                        <i class="ph-fill ph-clock text-emerald-400"></i> 
                                        <?php echo e(\Carbon\Carbon::parse($priorityExam->start_time)->format('H:i')); ?> 
                                        <?php if(isset($priorityExam->end_time)): ?>
                                            - <?php echo e(\Carbon\Carbon::parse($priorityExam->end_time)->format('H:i')); ?>

                                        <?php endif; ?>
                                        WIB
                                    </span>
                                <?php endif; ?>

                                
                                <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5">
                                    <i class="ph-fill ph-timer text-amber-400"></i> <?php echo e($priorityExam->duration_minutes); ?> Menit
                                </span>
                                
                                
                                <?php if(isset($priorityExam->exam_type) && $priorityExam->exam_type == 'google_form'): ?>
                                    <span class="flex items-center gap-1.5 bg-emerald-500/20 px-3 py-1.5 rounded-lg border border-emerald-500/30 text-emerald-200">
                                        <i class="ph-bold ph-google-logo text-emerald-400"></i> Google Form
                                    </span>
                                <?php else: ?>
                                    <span class="flex items-center gap-1.5 bg-blue-500/20 px-3 py-1.5 rounded-lg border border-blue-500/30 text-blue-200">
                                        <i class="ph-bold ph-desktop text-blue-400"></i> CBT Internal
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="shrink-0">
                            <?php if($isOngoing): ?>
                                <a href="<?php echo e(route('student.exam.run', $priorityExam->id)); ?>" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-amber-500 px-8 py-4 text-sm font-bold text-white transition-all hover:bg-amber-600 hover:scale-105 shadow-lg shadow-amber-500/30">
                                    <span class="relative z-10">Lanjutkan Mengerjakan</span>
                                    <i class="ph-bold ph-arrow-right relative z-10 transition-transform group-hover:translate-x-1"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('student.exam.show', $priorityExam->id)); ?>" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white px-8 py-4 text-sm font-bold text-slate-900 transition-all hover:bg-blue-50 hover:text-blue-600 hover:scale-105 shadow-lg">
                                    <span class="relative z-10">Masuk Ruang Ujian</span>
                                    <i class="ph-bold ph-sign-in relative z-10 transition-transform group-hover:translate-x-1"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <?php if(!$isAlumni): ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col h-full relative overflow-hidden group hover:border-emerald-100 transition-colors">
            
            <div class="flex flex-col sm:flex-row gap-6 h-full">
                
                <div class="shrink-0 flex flex-col items-center sm:w-36">
                    
                    
                    <div class="w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform group-hover:rotate-1 transition-transform duration-500 mb-3">
                        <div class="bg-emerald-700 h-7 flex items-center justify-center relative">
                            <div class="absolute top-[-6px] w-2 h-2 rounded-full bg-slate-800 border border-white z-20"></div> 
                            <span class="text-amber-50 font-black uppercase tracking-wider text-[8px] mt-1">KALENDER</span>
                        </div>
                        
                        <div class="h-16 flex flex-col items-center justify-center bg-white relative">
                            <span class="text-4xl font-serif font-black text-slate-800 leading-none tracking-tighter">
                                <?php echo e($date->format('d')); ?>

                            </span>
                            <span class="text-[9px] font-serif italic text-slate-400 mt-0.5 text-center px-1 leading-tight line-clamp-1 uppercase">
                                <?php echo e($date->translatedFormat('F Y')); ?>

                            </span>
                        </div>
                        
                        <div class="bg-slate-50 border-t border-slate-100 py-1.5 text-center px-1">
                            <span class="text-[9px] font-bold text-emerald-600 uppercase block mb-0.5">
                                <?php echo e($date->translatedFormat('l')); ?>

                            </span>
                            <span class="text-[8px] font-bold text-slate-400 block border-t border-slate-200 pt-0.5 mt-0.5">
                                <i class="ph-bold ph-moon-stars text-amber-500 mr-0.5"></i> <?php echo e($hijriDateFull); ?>

                            </span>
                        </div>
                    </div>
                    
                    
                    <div class="text-center w-full mb-3">
                        <span class="inline-flex items-center justify-center gap-1 w-full px-2 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black border border-emerald-100">
                            <i class="ph-bold ph-clock"></i> <span x-text="new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})"></span> WIB
                        </span>
                    </div>

                    
                    <div class="w-full space-y-2">
                        <button @click="updateTab('ramadan_jurnal')" 
                                class="w-full py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-[10px] font-bold shadow-md transition-all flex items-center justify-center gap-1.5 group/btn border border-emerald-500/50 hover:-translate-y-0.5">
                            <i class="ph-fill ph-moon-stars text-xs text-amber-300"></i>
                            <span><?php echo e(isset($todayRamadanLog) && $todayRamadanLog ? 'Lihat Ramadhan' : 'Jurnal Ramadhan'); ?></span>
                        </button>

                        <button @click="updateTab('kebiasaan')" 
                                class="w-full py-2 rounded-xl bg-white border border-slate-200 hover:border-blue-300 hover:bg-blue-50 text-slate-600 hover:text-blue-700 text-[10px] font-bold shadow-sm transition-all flex items-center justify-center gap-1.5 hover:-translate-y-0.5">
                            <i class="ph-fill ph-sun-horizon text-xs <?php echo e(isset($todayEntry) && $todayEntry ? 'text-blue-500' : 'text-slate-400'); ?>"></i>
                            <span><?php echo e(isset($todayEntry) && $todayEntry ? 'Lihat Kebiasaan' : 'Isi 7 Kebiasaan'); ?></span>
                        </button>

                        <button @click="updateTab('literasi_mandiri')" 
                                class="w-full py-2 rounded-xl bg-white border border-slate-200 hover:border-purple-300 hover:bg-purple-50 text-slate-600 hover:text-purple-700 text-[10px] font-bold shadow-sm transition-all flex items-center justify-center gap-1.5 hover:-translate-y-0.5">
                            <i class="ph-fill ph-book-open text-xs text-purple-400"></i>
                            <span>Catat Literasi</span>
                        </button>
                    </div>
                </div>

                
                <div class="flex-1 min-w-0 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="ph-fill ph-chalkboard-teacher text-emerald-600 text-lg"></i>
                            Jadwal Hari Ini
                        </h3>
                    </div>

                    <div class="flex-1 space-y-3 relative z-10 overflow-y-auto custom-scrollbar max-h-[350px] pr-1">
                        <?php $__empty_1 = true; $__currentLoopData = $todaysSchedule ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $now = \Carbon\Carbon::now()->format('H:i:s');
                                $isActive = $now >= $schedule->start_time && $now <= $schedule->end_time;
                                $isPast = $now > $schedule->end_time;
                            ?>
                            <div class="flex items-center gap-3 p-3 rounded-2xl transition-all border <?php echo e($isActive ? 'bg-emerald-50 border-emerald-200 shadow-sm' : ($isPast ? 'bg-slate-50 border-transparent opacity-60' : 'bg-white border-slate-100 hover:border-emerald-200')); ?>">
                                
                                <div class="w-12 text-center shrink-0">
                                    <p class="text-[10px] font-black <?php echo e($isActive ? 'text-emerald-700' : 'text-slate-700'); ?>">
                                        <?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format('H:i')); ?>

                                    </p>
                                    <div class="w-0.5 h-2 bg-slate-200 mx-auto my-0.5"></div>
                                    <p class="text-[10px] font-bold text-slate-400">
                                        <?php echo e(\Carbon\Carbon::parse($schedule->end_time)->format('H:i')); ?>

                                    </p>
                                </div>
                                
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-xs truncate <?php echo e($isActive ? 'text-emerald-900' : 'text-slate-800'); ?>">
                                        <?php echo e($schedule->subject->name ?? 'Mapel Umum'); ?>

                                    </h4>
                                    <div class="flex items-center gap-1 text-[10px] text-slate-500 mt-0.5 truncate">
                                        <i class="ph-fill ph-user <?php echo e($isActive ? 'text-emerald-500' : 'text-slate-400'); ?>"></i>
                                        <?php echo e(Str::limit($schedule->teacher->name ?? '-', 15)); ?>

                                    </div>
                                </div>

                                
                                <?php if($isActive): ?>
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.6)]"></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-8">
                                <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-300">
                                    <i class="ph-duotone ph-coffee text-xl"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-500">Tidak ada KBM.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-slate-50 text-center">
                        <button @click="updateTab('jadwal')" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 transition flex items-center justify-center gap-1">
                            Lihat Jadwal Lengkap <i class="ph-bold ph-caret-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col h-full relative overflow-hidden group hover:border-orange-100 transition-colors">
            <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-clipboard-text text-8xl text-orange-500"></i>
            </div>

            <div class="flex justify-between items-center mb-6 relative z-10">
                <h3 class="font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-100">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                    </span>
                    Tugas Belum Selesai
                </h3>
                <?php if(($pendingTasks->count() ?? 0) > 0): ?>
                    <span class="px-2 py-1 rounded-lg bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-black uppercase tracking-wider">
                        <?php echo e($pendingTasks->count()); ?> Pending
                    </span>
                <?php endif; ?>
            </div>

            <div class="flex-1 space-y-3 relative z-10">
                <?php $__empty_1 = true; $__currentLoopData = $pendingTasks ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $deadline = \Carbon\Carbon::parse($task->deadline);
                        $isUrgent = $deadline->isToday() || $deadline->isPast();
                    ?>
                    <div class="p-4 rounded-2xl border <?php echo e($isUrgent ? 'bg-rose-50 border-rose-100' : 'bg-white border-slate-100'); ?> hover:shadow-md transition-all group/task">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider <?php echo e($isUrgent ? 'text-rose-600' : 'text-blue-600'); ?>">
                                <?php echo e($task->subject->name ?? 'Tugas'); ?>

                            </span>
                            <span class="text-[10px] font-bold flex items-center gap-1 <?php echo e($isUrgent ? 'text-rose-500' : 'text-slate-400'); ?>">
                                <i class="ph-bold ph-clock"></i> <?php echo e($deadline->diffForHumans()); ?>

                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-3 line-clamp-1 group-hover/task:text-orange-600 transition-colors">
                            <?php echo e($task->title); ?>

                        </h4>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <?php if($task->assignment_type == 'file_upload'): ?>
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Upload</span>
                                <?php elseif($task->assignment_type == 'quiz'): ?>
                                    <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded border border-purple-100">Kuis</span>
                                <?php else: ?>
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Link</span>
                                <?php endif; ?>
                            </div>

                            <button @click="updateTab('lms')" class="px-4 py-1.5 rounded-lg bg-white border border-slate-200 text-[10px] font-bold text-slate-600 hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-all shadow-sm flex items-center gap-1">
                                Kerjakan <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-3 text-emerald-300">
                            <i class="ph-duotone ph-check-fat text-2xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-500">Hebat! Semua tugas beres.</p>
                        <p class="text-xs text-slate-400">Tidak ada tanggungan tugas saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-50 text-center relative z-10">
                <button @click="updateTab('lms')" class="text-xs font-bold text-orange-600 hover:text-orange-700 transition flex items-center justify-center gap-1">
                    Lihat Semua Tugas <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(isset($upcomingAgendas) && $upcomingAgendas->isNotEmpty() && !$isAlumni): ?>
    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800 flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                    <i class="ph-fill ph-calendar-star text-xl"></i>
                </span>
                Agenda Sekolah Terdekat
            </h3>
            <button @click="updateTab('jadwal'); setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 100);" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition flex items-center gap-1">
                Lihat Kalender <i class="ph-bold ph-caret-right"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php $__currentLoopData = $upcomingAgendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $start = \Carbon\Carbon::parse($agenda->start_date)->startOfDay();
                    $end = $agenda->end_date ? \Carbon\Carbon::parse($agenda->end_date)->endOfDay() : $start->copy()->endOfDay();
                    $isOngoing = \Carbon\Carbon::now()->between($start, $end);
                    
                    // Style dinamis manual agar aman dari PurgeCSS Tailwind
                    $bgIcon = 'bg-blue-100 text-blue-600';
                    $bgBadge = 'bg-blue-50 text-blue-600 border-blue-200';
                    $borderHover = 'hover:border-blue-200';
                    $icon = 'ph-calendar-check';

                    if($agenda->type == 'libur' || $agenda->type == 'nasional') { 
                        $icon = 'ph-tent'; 
                        $bgIcon = 'bg-rose-100 text-rose-600';
                        $bgBadge = 'bg-rose-50 text-rose-600 border-rose-200';
                        $borderHover = 'hover:border-rose-200';
                    } elseif($agenda->type == 'ujian') { 
                        $icon = 'ph-pencil-simple'; 
                        $bgIcon = 'bg-amber-100 text-amber-600';
                        $bgBadge = 'bg-amber-50 text-amber-600 border-amber-200';
                        $borderHover = 'hover:border-amber-200';
                    }
                ?>
                <div class="flex items-start gap-4 p-4 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-sm <?php echo e($borderHover); ?> transition-all relative overflow-hidden">
                    <div class="w-12 h-12 rounded-xl <?php echo e($bgIcon); ?> flex items-center justify-center text-xl shrink-0">
                        <i class="ph-duotone <?php echo e($icon); ?>"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded <?php echo e($bgBadge); ?> border">
                                <?php echo e($agenda->type); ?>

                            </span>
                            <?php if($isOngoing): ?>
                                <span class="text-[9px] font-black uppercase tracking-wider text-rose-500 animate-pulse flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Sedang Berlangsung
                                </span>
                            <?php endif; ?>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1 line-clamp-1" title="<?php echo e($agenda->title); ?>"><?php echo e($agenda->title); ?></h4>
                        <p class="text-xs font-bold text-slate-500 flex items-center gap-1.5">
                            <i class="ph-bold ph-clock"></i> 
                            <?php echo e(\Carbon\Carbon::parse($agenda->start_date)->translatedFormat('d M Y')); ?>

                            <?php if($agenda->end_date && \Carbon\Carbon::parse($agenda->end_date)->toDateString() != \Carbon\Carbon::parse($agenda->start_date)->toDateString()): ?>
                                - <?php echo e(\Carbon\Carbon::parse($agenda->end_date)->translatedFormat('d M Y')); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        
        <?php if($isAlumni): ?>
            <div class="md:col-span-3 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-[2.5rem] p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden shadow-sm">
                <div class="absolute top-0 right-0 w-48 h-48 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16"></div>
                <div class="w-24 h-24 bg-white text-amber-600 rounded-full flex items-center justify-center text-5xl shrink-0 z-10 shadow-lg border-4 border-amber-100">
                    <i class="ph-duotone ph-graduation-cap"></i>
                </div>
                <div class="flex-1 text-center md:text-left z-10 w-full">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest mb-2">
                                Alumni Sekolah
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 mb-1">Selamat Mengabdi di Masyarakat!</h3>
                            <p class="text-slate-600 text-sm max-w-xl">
                                Siswa ini dinyatakan <strong>LULUS</strong> pada tahun <?php echo e($student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year); ?>. Tetap jaga nama baik almamater.
                            </p>
                        </div>
                        <div class="flex flex-wrap justify-center gap-3">
                            <?php if($student->alumniProfile): ?>
                                <div class="flex flex-col items-end">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Status Saat Ini</span>
                                    <span class="font-black text-amber-600 text-lg"><?php echo e($student->alumniProfile->activity_status); ?></span>
                                </div>
                            <?php else: ?>
                                <a href="<?php echo e(Route::has('alumni.tracer') ? route('alumni.tracer') : '#'); ?>" class="group inline-flex items-center gap-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-xl shadow-amber-600/30 transition-all">
                                    <i class="ph-bold ph-clipboard-text"></i> Isi Tracer Study
                                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            
            
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                    <i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                            <i class="ph-bold ph-calendar-check"></i> Kehadiran
                        </h3>
                        <div class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold text-slate-500">Semester Ini</div>
                    </div>

                    <div class="flex items-baseline gap-2 mb-4">
                        <?php 
                            $total_hari = ($hadir ?? 0) + ($sakit ?? 0) + ($izin ?? 0) + ($alpa ?? 0); 
                            $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; 
                        ?>
                        <span class="text-5xl font-black text-slate-800 tracking-tight"><?php echo e($persen); ?><span class="text-2xl text-slate-400">%</span></span>
                    </div>
                    
                    <div class="w-full bg-slate-100 rounded-full h-3 mb-4 overflow-hidden flex">
                        <div class="h-full bg-emerald-500" style="width: <?php echo e($persen); ?>%"></div>
                        <?php
                            $persenSakitIzin = $total_hari > 0 ? round((($sakit+$izin)/$total_hari)*100) : 0;
                        ?>
                        <div class="h-full bg-blue-400" style="width: <?php echo e($persenSakitIzin); ?>%"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="px-3 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-xs font-bold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div> 
                            Hadir: <?php echo e($hadir ?? 0); ?>

                        </div>
                        <div class="px-3 py-2 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl text-xs font-bold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-rose-500"></div> 
                            Alpa: <?php echo e($alpa ?? 0); ?>

                        </div>
                        <div class="px-3 py-2 bg-blue-50 text-blue-700 border border-blue-100 rounded-xl text-xs font-bold flex items-center gap-2 col-span-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div> 
                            Sakit / Izin: <?php echo e(($sakit ?? 0) + ($izin ?? 0)); ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col h-full">
            <!-- Ikon Latar Belakang -->
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                <i class="ph-fill ph-star text-9xl text-amber-400"></i>
            </div>
            
            <div class="relative z-10 w-full flex flex-col h-full">
                <!-- Pengepala (Header) & Indikator Streak -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex flex-col">
                        <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1 mb-1">
                            <i class="ph-bold ph-medal"></i> Poin Karakter
                        </h3>
                        <!-- LOGIK STREAK: Boleh disambungkan dengan pangkalan data anda kelak (contoh $login_streak) -->
                        <?php $streak = $login_streak ?? 5; ?>
                        <span class="text-[10px] font-black text-orange-500 flex items-center gap-1">
                            <i class="ph-fill ph-fire text-orange-500 animate-pulse"></i> <?php echo e($streak); ?> Hari Streak!
                        </span>
                    </div>
                    
                    <!-- Logik Pengiraan Tahap (Level) berasaskan markah -->
                    <?php 
                        $behaviorScore = 200 - ($total_violation_points ?? 0) + ($total_merit_points ?? 0);
                        $scoreColor = $behaviorScore >= 180 ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : ($behaviorScore >= 150 ? 'text-amber-600 bg-amber-50 border-amber-100' : 'text-rose-600 bg-rose-50 border-rose-100');
                        
                        // Sistem Tahap Gamifikasi
                        $levelTitles = [
                            1 => 'Pemula',
                            2 => 'Pelajar Biasa',
                            3 => 'Pelajar Baik',
                            4 => 'Pelajar Teladan',
                            5 => 'Legend'
                        ];
                        
                        if ($behaviorScore >= 300) { $currentLevel = 5; $minScore = 300; $maxScore = 400; $colorLevel = 'from-purple-500 to-indigo-500'; }
                        elseif ($behaviorScore >= 250) { $currentLevel = 4; $minScore = 250; $maxScore = 300; $colorLevel = 'from-blue-500 to-cyan-500'; }
                        elseif ($behaviorScore >= 200) { $currentLevel = 3; $minScore = 200; $maxScore = 250; $colorLevel = 'from-emerald-500 to-teal-500'; }
                        elseif ($behaviorScore >= 150) { $currentLevel = 2; $minScore = 150; $maxScore = 200; $colorLevel = 'from-amber-400 to-orange-500'; }
                        else { $currentLevel = 1; $minScore = 0; $maxScore = 150; $colorLevel = 'from-rose-500 to-red-600'; }

                        $levelName = $levelTitles[$currentLevel];
                        $progress = $currentLevel < 5 ? (($behaviorScore - $minScore) / ($maxScore - $minScore)) * 100 : 100;
                    ?>
                    <span class="px-2 py-1 rounded-lg text-xs font-black border <?php echo e($scoreColor); ?> shadow-sm">
                        Skor: <?php echo e($behaviorScore); ?>

                    </span>
                </div>

                <!-- Palang Kemajuan (Progress Bar) -->
                <div class="mb-4 mt-2">
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-sm font-black text-slate-800 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-full bg-gradient-to-br <?php echo e($colorLevel); ?> text-white flex items-center justify-center text-[10px] shadow-sm">
                                <?php echo e($currentLevel); ?>

                            </span>
                            <?php echo e($levelName); ?>

                        </span>
                        <?php if($currentLevel < 5): ?>
                            <span class="text-[9px] font-bold text-slate-400">
                                <?php echo e($maxScore - $behaviorScore); ?> poin ke Lv <?php echo e($currentLevel + 1); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-[9px] font-bold text-amber-500 flex items-center gap-0.5">
                                <i class="ph-fill ph-crown"></i> Max Level
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden shadow-inner relative">
                        <div class="h-full rounded-full bg-gradient-to-r <?php echo e($colorLevel); ?> relative transition-all duration-1000" style="width: <?php echo e($progress); ?>%">
                            <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <!-- Kotak Statistik Bawah -->
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <div class="bg-emerald-50/50 p-3 rounded-2xl border border-emerald-100 text-center flex flex-col justify-center hover:bg-emerald-50 transition-colors cursor-default">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-1.5 text-sm shadow-sm">
                            <i class="ph-bold ph-plus"></i>
                        </div>
                        <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-wider">Prestasi</p>
                        <p class="text-xl font-black text-emerald-700"><?php echo e($total_merit_points ?? 0); ?></p>
                    </div>
                    <div class="bg-rose-50/50 p-3 rounded-2xl border border-rose-100 text-center flex flex-col justify-center hover:bg-rose-50 transition-colors cursor-default">
                        <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-1.5 text-sm shadow-sm">
                            <i class="ph-bold ph-minus"></i>
                        </div>
                        <p class="text-[9px] text-rose-600 font-bold uppercase tracking-wider">Pelanggaran</p>
                        <p class="text-xl font-black text-rose-700"><?php echo e($total_violation_points ?? 0); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                <i class="ph-fill ph-books text-9xl text-purple-500"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="ph-bold ph-book-open"></i> Literasi
                    </h3>
                    <a href="<?php echo e(Route::has('student.library.index') ? route('student.library.index') : '#'); ?>" class="text-[10px] font-bold text-purple-600 hover:underline">
                        Lihat Katalog
                    </a>
                </div>

                <?php
                    $ebookCount = isset($ebookHistory) ? $ebookHistory->count() : 0;
                    $totalLiterasi = ($library_visits ?? 0) + $ebookCount;
                ?>

                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-5xl font-black text-slate-800 tracking-tight"><?php echo e($totalLiterasi); ?></span>
                    <span class="text-xs text-slate-500 font-bold bg-slate-100 px-2 py-1 rounded-lg border border-slate-200">Aktivitas</span>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-3 rounded-xl bg-purple-50 border border-purple-100 hover:bg-purple-100 transition-colors">
                        <span class="text-xs font-bold text-purple-700 flex items-center gap-2">
                            <i class="ph-bold ph-book"></i> Pinjam Fisik
                        </span>
                        <span class="font-black text-purple-800"><?php echo e($library_visits ?? 0); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-colors">
                        <span class="text-xs font-bold text-blue-700 flex items-center gap-2">
                            <i class="ph-bold ph-device-tablet-camera"></i> E-Book
                        </span>
                        <span class="font-black text-blue-800"><?php echo e($ebookCount); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-ringkasan.blade.php ENDPATH**/ ?>