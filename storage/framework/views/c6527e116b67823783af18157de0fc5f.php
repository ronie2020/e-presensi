<div x-data="{ lmsTab: 'assignments' }" class="space-y-8 animate-in fade-in duration-500 font-sans">

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php
            $totalAssign = 0; $submittedCount = 0;
            if(isset($lms_assignments_grouped)) {
                $studentId = Auth::guard('student')->id();
                foreach($lms_assignments_grouped as $group) { 
                    $totalAssign += $group->count(); 
                    foreach($group as $task) {
                        if($task->submissions && $task->submissions->where('student_id', $studentId)->first()) $submittedCount++;
                    }
                }
            }
            $pendingCount = max(0, $totalAssign - $submittedCount); 
            $gradedScores = array_filter($lms_grades ?? [], fn($v) => !is_null($v) && is_numeric($v));
            $avgScore = count($gradedScores) > 0 ? round(array_sum($gradedScores) / count($gradedScores)) : 0;
            $totalMateri = 0; 
            if(isset($lms_materials_grouped)) { foreach($lms_materials_grouped as $g) { $totalMateri += $g->count(); } }
        ?>

        <div class="bg-elevate-dark rounded-[2.5rem] p-6 text-white shadow-xl shadow-elevate-dark/10 relative overflow-hidden group border border-elevate-primary/30">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-clipboard-text text-8xl text-elevate-peach"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-1">Tugas Pending</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black"><?php echo e($pendingCount); ?></h3>
                    <span class="text-sm font-medium text-slate-300">Belum Selesai</span>
                </div>
                <?php if($pendingCount > 0): ?>
                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-elevate-peach-light/20 text-elevate-peach-light text-xs font-bold border border-elevate-peach/30 backdrop-blur-sm">
                        <i class="ph-bold ph-clock-countdown"></i> Segera Kerjakan
                    </div>
                <?php else: ?>
                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-400/30 backdrop-blur-sm">
                        <i class="ph-bold ph-check-circle"></i> Semua Beres!
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-elevate-accent/50 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <i class="ph-duotone ph-exam text-8xl text-elevate-primary"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rata-rata Nilai</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-elevate-dark"><?php echo e($avgScore); ?></h3>
                <span class="text-[10px] font-black px-2.5 py-1 rounded-md border shadow-sm <?php echo e($avgScore >= 80 ? 'bg-elevate-soft text-elevate-primary border-elevate-accent/30' : 'bg-elevate-peach-light/20 text-elevate-peach-dark border-elevate-peach/30'); ?>">
                    <?php echo e($avgScore >= 90 ? 'A' : ($avgScore >= 80 ? 'B' : 'C')); ?>

                </span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium">Dari <?php echo e(count($gradedScores)); ?> tugas yang dinilai.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-elevate-primary/30 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <i class="ph-duotone ph-books text-8xl text-elevate-accent"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Materi</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-elevate-dark"><?php echo e($totalMateri); ?></h3>
                <span class="text-sm font-medium text-slate-400">Modul</span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium">Siap untuk dipelajari.</p>
        </div>
    </div>

    
    <div class="flex justify-center">
        <div class="bg-slate-100 p-1.5 rounded-2xl inline-flex items-center gap-1 border border-slate-200 shadow-inner">
            <button @click="lmsTab = 'assignments'" 
                :class="lmsTab === 'assignments' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-clipboard-text text-lg"></i> Tugas & Kuis
            </button>
            <button @click="lmsTab = 'materials'" 
                :class="lmsTab === 'materials' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-book-open-text text-lg"></i> Materi Belajar
            </button>
        </div>
    </div>

    
    <div x-show="lmsTab === 'assignments'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <?php if(isset($lms_assignments_grouped) && count($lms_assignments_grouped) > 0): ?>
            <?php $__currentLoopData = $lms_assignments_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $assignments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $sName = strtolower($subjectName);
                    // Unified Elevate Sub-Theme Logic
                    $themeOptions = [
                        ['bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-accent/30', 'ring' => 'ring-elevate-accent/20'],
                        ['bg' => 'bg-elevate-peach-light/20', 'text' => 'text-elevate-peach-dark', 'border' => 'border-elevate-peach/30', 'ring' => 'ring-elevate-peach/20'],
                        ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'ring' => 'ring-emerald-100']
                    ];
                    $t = $themeOptions[crc32($sName) % count($themeOptions)];
                ?>

                <div class="mb-10 animate-enter">
                    <div class="flex items-center gap-4 mb-5 pl-2">
                        <div class="h-10 w-1.5 rounded-full <?php echo e($t['bg']); ?> border <?php echo e($t['border']); ?>"></div>
                        <h3 class="text-xl font-black text-elevate-dark tracking-tight flex items-center gap-2">
                            <?php echo e($subjectName); ?>

                            <span class="text-[10px] uppercase tracking-widest font-black px-2.5 py-1 rounded-lg <?php echo e($t['bg']); ?> <?php echo e($t['text']); ?> border <?php echo e($t['border']); ?>">
                                <?php echo e(count($assignments)); ?> Tugas
                            </span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $mySubmission = $task->submissions->where('student_id', Auth::guard('student')->id())->first();
                                $score = $mySubmission->grade ?? null;
                                $isGraded = $score !== null;
                                $isSubmitted = $mySubmission !== null;
                                $isQuiz = $task->assignment_type == 'quiz';
                                $isLink = $task->assignment_type == 'link';
                                $isExpired = \Carbon\Carbon::now() > \Carbon\Carbon::parse($task->deadline);
                                $deadlineFormatted = \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M, H:i');
                            ?>

                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-1 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 <?php echo e($t['ring']); ?>"
                                 x-data="{ openUpload: false, submissionType: '<?php echo e(($mySubmission && $mySubmission->link_url) ? 'link' : 'file'); ?>' }">
                                
                                <div class="bg-white rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden">
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full <?php echo e($t['bg']); ?> opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                    <div class="flex justify-between items-start mb-4 relative z-10">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border <?php echo e($t['border']); ?> <?php echo e($t['bg']); ?> <?php echo e($t['text']); ?>">
                                            <i class="ph-duotone <?php echo e($isQuiz ? 'ph-brain' : ($isLink ? 'ph-link' : 'ph-clipboard-text')); ?>"></i>
                                        </div>
                                        
                                        <?php if($isGraded): ?>
                                            <div class="flex flex-col items-end">
                                                <span class="text-2xl font-black <?php echo e($score < 75 ? 'text-rose-500' : 'text-elevate-primary'); ?>"><?php echo e($score); ?></span>
                                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Nilai</span>
                                            </div>
                                        <?php elseif($isSubmitted): ?>
                                            <span class="bg-elevate-soft text-elevate-primary border border-elevate-accent/30 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                                <i class="ph-bold ph-check"></i> Terkirim
                                            </span>
                                        <?php else: ?>
                                            <?php if($isExpired && !$task->allow_late_submission): ?>
                                                <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                                    <i class="ph-bold ph-lock-key"></i> Tutup
                                                </span>
                                            <?php else: ?>
                                                <span class="bg-elevate-peach-light/20 text-elevate-peach-dark border border-elevate-peach/30 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm animate-pulse">
                                                    <i class="ph-bold ph-clock"></i> Aktif
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-lg text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-2 leading-snug">
                                            <?php echo e($task->title); ?>

                                        </h4>
                                        <p class="text-xs text-slate-500 mt-2 line-clamp-2 font-medium">
                                            Deadline: <?php echo e($deadlineFormatted); ?>

                                        </p>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10">
                                        <?php if($isGraded): ?>
                                            <button disabled class="w-full py-3 rounded-xl bg-slate-50 text-slate-400 font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed border border-slate-100">
                                                <i class="ph-bold ph-check-circle"></i> Selesai
                                            </button>
                                        <?php else: ?>
                                            <?php if($isExpired && !$task->allow_late_submission): ?>
                                                <button disabled class="w-full py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed">
                                                    <i class="ph-bold ph-lock-key"></i> Waktu Habis
                                                </button>
                                            <?php elseif($isQuiz): ?>
                                                <a href="<?php echo e(route('students.learning.assignment.quiz', $task->id)); ?>" 
                                                   class="w-full py-3 rounded-xl bg-elevate-peach-dark hover:bg-elevate-peach text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-elevate-peach/20 hover:-translate-y-0.5 transition-all">
                                                    <span>Mulai Kuis</span>
                                                    <i class="ph-bold ph-play-circle"></i>
                                                </a>
                                            <?php elseif($isLink): ?>
                                                <div class="flex gap-2">
                                                    <a href="<?php echo e($task->link_url); ?>" target="_blank"
                                                       class="flex-1 py-3 rounded-xl bg-white hover:bg-elevate-soft border border-elevate-accent/50 text-elevate-primary font-bold text-xs flex items-center justify-center gap-2 transition-all">
                                                        <i class="ph-bold ph-link"></i> Buka Link
                                                    </a>
                                                    <form action="<?php echo e(route('students.learning.assignment.submit', $task->id)); ?>" method="POST" class="flex-1" id="form-link-<?php echo e($task->id); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="submission_type" value="link"> 
                                                        <button type="button" onclick="confirmTaskSubmit('<?php echo e($task->id); ?>')" 
                                                            class="w-full py-3 rounded-xl bg-elevate-primary hover:bg-elevate-dark text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md shadow-elevate-primary/20 hover:-translate-y-0.5 transition-all">
                                                            <i class="ph-bold ph-check"></i> Selesai
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?> 
                                                <button @click="openUpload = !openUpload" 
                                                    class="w-full py-3 rounded-xl bg-elevate-primary hover:bg-elevate-dark text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-elevate-primary/20 hover:-translate-y-0.5 transition-all">
                                                    <span x-text="openUpload ? 'Tutup Form' : '<?php echo e($isSubmitted ? 'Edit Jawaban' : 'Kerjakan Tugas'); ?>'"></span>
                                                    <i class="ph-bold" :class="openUpload ? 'ph-x' : 'ph-upload-simple'"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <?php if($task->assignment_type == 'file_upload' && !$isGraded): ?>
                                        <div x-show="openUpload" x-transition class="mt-4 pt-4 border-t border-dashed border-elevate-accent/40">
                                            <form action="<?php echo e(route('students.learning.assignment.submit', $task->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                <?php echo csrf_field(); ?>
                                                <div class="flex p-1 bg-slate-50 rounded-lg mb-3 border border-slate-200 w-full">
                                                    <label class="flex-1 cursor-pointer text-center">
                                                        <input type="radio" name="submission_type" value="file" class="sr-only" x-model="submissionType">
                                                        <div class="py-1.5 rounded-md text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5"
                                                             :class="submissionType === 'file' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'">
                                                            <i class="ph-bold ph-file-text"></i> Upload File
                                                        </div>
                                                    </label>
                                                    <label class="flex-1 cursor-pointer text-center">
                                                        <input type="radio" name="submission_type" value="link" class="sr-only" x-model="submissionType">
                                                        <div class="py-1.5 rounded-md text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5"
                                                             :class="submissionType === 'link' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'">
                                                            <i class="ph-bold ph-link"></i> Link Tugas
                                                        </div>
                                                    </label>
                                                </div>

                                                <div x-show="submissionType === 'file'">
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">File Jawaban</label>
                                                    <input type="file" name="file" :required="submissionType === 'file'" class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-accent/20 border border-slate-200 rounded-xl">
                                                </div>

                                                <div x-show="submissionType === 'link'" style="display: none;">
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">URL Link</label>
                                                    <input type="url" name="link_url" :required="submissionType === 'link'" placeholder="https://..." value="<?php echo e($mySubmission->link_url ?? ''); ?>" class="block w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-elevate-accent focus:border-elevate-accent text-elevate-dark font-medium">
                                                </div>

                                                <div>
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Catatan</label>
                                                    <textarea name="student_note" rows="2" class="w-full rounded-xl border-slate-200 text-xs focus:ring-elevate-accent focus:border-elevate-accent p-2.5 placeholder:text-slate-300" placeholder="Pesan untuk guru..."><?php echo e($mySubmission->student_note ?? ''); ?></textarea>
                                                </div>

                                                <button type="submit" class="w-full py-2.5 bg-elevate-dark text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                                                    <i class="ph-bold ph-paper-plane-right"></i> <?php echo e($isSubmitted ? 'Update Jawaban' : 'Kirim Jawaban'); ?>

                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center">
                <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary">
                    <i class="ph-duotone ph-confetti text-5xl"></i>
                </div>
                <h3 class="text-xl font-black text-elevate-dark">Tidak Ada Tugas Aktif</h3>
                <p class="text-slate-400 text-sm mt-1">Kamu sudah menyelesaikan semuanya. Hebat!</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div x-show="lmsTab === 'materials'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <?php if(isset($lms_materials_grouped) && count($lms_materials_grouped) > 0): ?>
            <?php $__currentLoopData = $lms_materials_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $materials): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $sName = strtolower($subjectName);
                    $themeOptions = [
                        ['bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-accent/30', 'ring' => 'ring-elevate-accent/20', 'icon' => 'ph-book-bookmark'],
                        ['bg' => 'bg-elevate-peach-light/20', 'text' => 'text-elevate-peach-dark', 'border' => 'border-elevate-peach/30', 'ring' => 'ring-elevate-peach/20', 'icon' => 'ph-notebook'],
                        ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'ring' => 'ring-emerald-100', 'icon' => 'ph-flask']
                    ];
                    $t = $themeOptions[crc32($sName) % count($themeOptions)];
                ?>

                <div class="mb-10 animate-enter">
                    <div class="flex items-center gap-4 mb-5 pl-2">
                        <div class="h-10 w-1.5 rounded-full <?php echo e($t['bg']); ?> border <?php echo e($t['border']); ?>"></div>
                        <h3 class="text-xl font-black text-elevate-dark tracking-tight flex items-center gap-2">
                            <?php echo e($subjectName); ?>

                            <span class="text-[10px] uppercase tracking-widest font-black px-2.5 py-1 rounded-lg <?php echo e($t['bg']); ?> <?php echo e($t['text']); ?> border <?php echo e($t['border']); ?>">
                                <?php echo e(count($materials)); ?> Materi
                            </span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $mainAttachment = $material->attachments->first();
                                $hasFile = $mainAttachment && $mainAttachment->file_type == 'file';
                                $hasLink = $mainAttachment && ($mainAttachment->file_type == 'link' || $mainAttachment->file_type == 'video');
                                $fileUrl = '#'; $btnText = 'Lihat Detail'; $btnIcon = 'ph-list-magnifying-glass';
                                if($hasFile) { $fileUrl = asset('storage/' . $mainAttachment->file_path); $btnText = 'Buka Materi'; $btnIcon = 'ph-book-open-text'; } 
                                elseif($hasLink) { $fileUrl = $mainAttachment->file_path; $btnText = 'Buka Link'; $btnIcon = 'ph-link'; }
                            ?>

                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-1 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 <?php echo e($t['ring']); ?>">
                                <div class="bg-white rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden">
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full <?php echo e($t['bg']); ?> opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                    <div class="flex justify-between items-start mb-4 relative z-10">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border <?php echo e($t['border']); ?> <?php echo e($t['bg']); ?> <?php echo e($t['text']); ?>">
                                            <i class="ph-duotone <?php echo e($t['icon']); ?>"></i>
                                        </div>
                                    </div>

                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-lg text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-2 leading-snug">
                                            <?php echo e($material->title); ?>

                                        </h4>
                                        <p class="text-xs text-slate-500 mt-2 line-clamp-2 font-medium"><?php echo e($material->resume ?? 'Tidak ada deskripsi.'); ?></p>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10 flex gap-2">
                                        <?php if($hasFile || $hasLink): ?>
                                            <a href="<?php echo e($fileUrl); ?>" target="_blank" class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                                                <i class="ph-bold <?php echo e($btnIcon); ?>"></i> <?php echo e($btnText); ?>

                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('students.learning.subject.show', $material->subject_id)); ?>" class="flex-1 py-2.5 rounded-xl bg-elevate-soft/50 hover:bg-elevate-soft text-elevate-primary border border-elevate-accent/30 font-bold text-xs flex items-center justify-center gap-2 transition-colors shadow-sm">
                                            <i class="ph-bold ph-eye"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center">
                <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary">
                    <i class="ph-duotone ph-files text-5xl"></i>
                </div>
                <h3 class="text-xl font-black text-elevate-dark">Belum Ada Materi</h3>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function confirmTaskSubmit(taskId) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Pastikan Anda sudah mengerjakan soal di link yang tersedia sebelum menandai ini selesai.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d52a1', // elevate-primary
                cancelButtonColor: '#64748b', // slate-500
                confirmButtonText: 'Ya, Selesai',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl', confirmButton: 'px-4 py-2 rounded-xl font-bold', cancelButton: 'px-4 py-2 rounded-xl font-bold' }
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('form-link-' + taskId).submit(); }
            });
        }
    </script>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-lms.blade.php ENDPATH**/ ?>