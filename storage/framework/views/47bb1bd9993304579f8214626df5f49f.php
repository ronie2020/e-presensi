<div x-data="{ lmsTab: 'assignments' }" class="space-y-8">

    
    <div class="flex justify-center">
        <div class="bg-slate-100 p-1.5 rounded-2xl inline-flex items-center gap-1 border border-slate-200 shadow-inner">
            <button @click="lmsTab = 'assignments'" 
                :class="lmsTab === 'assignments' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-clipboard-text text-lg"></i> Tugas & Kuis
            </button>
            <button @click="lmsTab = 'materials'" 
                :class="lmsTab === 'materials' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-book-open-text text-lg"></i> Materi Belajar
            </button>
        </div>
    </div>

    
    <div x-show="lmsTab === 'assignments'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <?php if(isset($lms_assignments_grouped) && count($lms_assignments_grouped) > 0): ?>
            <?php $__currentLoopData = $lms_assignments_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $assignments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php
                    $sName = strtolower($subjectName);
                    
                    // Default Theme
                    $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100', 'icon' => 'ph-book-bookmark'];

                    if (str_contains($sName, 'informatika') || str_contains($sName, 'tik') || str_contains($sName, 'komputer')) {
                        $theme = ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200', 'light' => 'bg-cyan-100', 'ring' => 'ring-cyan-100', 'icon' => 'ph-desktop'];
                    } elseif (str_contains($sName, 'seni') || str_contains($sName, 'budaya') || str_contains($sName, 'musik')) {
                        $theme = ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'light' => 'bg-purple-100', 'ring' => 'ring-purple-100', 'icon' => 'ph-palette'];
                    } elseif (str_contains($sName, 'matematika') || str_contains($sName, 'kalkulus') || str_contains($sName, 'olahraga')) {
                        $theme = ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'light' => 'bg-orange-100', 'ring' => 'ring-orange-100', 'icon' => 'ph-calculator'];
                    } elseif (str_contains($sName, 'ipa') || str_contains($sName, 'fisika') || str_contains($sName, 'agama')) {
                        $theme = ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'light' => 'bg-emerald-100', 'ring' => 'ring-emerald-100', 'icon' => 'ph-flask'];
                    } elseif (str_contains($sName, 'bahasa') || str_contains($sName, 'inggris') || str_contains($sName, 'indonesia')) {
                        $theme = ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'light' => 'bg-rose-100', 'ring' => 'ring-rose-100', 'icon' => 'ph-translate'];
                    } elseif (str_contains($sName, 'ips') || str_contains($sName, 'sejarah') || str_contains($sName, 'pkn')) {
                        $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100', 'icon' => 'ph-globe-hemisphere-west'];
                    }
                ?>

                <div class="mb-10 animate-enter">
                    
                    <div class="flex items-center gap-4 mb-5">
                        <div class="h-10 w-1.5 rounded-full <?php echo e(str_replace('text-', 'bg-', $theme['text'])); ?> opacity-50"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <?php echo e($subjectName); ?>

                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> border <?php echo e($theme['border']); ?>">
                                <?php echo e(count($assignments)); ?> Tugas
                            </span>
                        </h3>
                    </div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $score = $lms_grades[$task->id] ?? null;
                                $isGraded = $score !== null;
                                $isQuiz = $task->assignment_type == 'quiz';
                                
                                $typeConfig = $isQuiz 
                                    ? ['icon' => 'ph-brain', 'label' => 'Kuis Online', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50', 'border' => 'border-purple-200']
                                    : ['icon' => 'ph-clipboard-text', 'label' => 'Tugas Rumah', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200'];
                                
                                $isExpired = \Carbon\Carbon::now() > \Carbon\Carbon::parse($task->deadline);
                            ?>

                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-1 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 <?php echo e($theme['ring']); ?>">
                                <div class="bg-white rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden">
                                    
                                    
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full <?php echo e($theme['bg']); ?> opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                    
                                    <div class="flex justify-between items-start mb-4 relative z-10">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border <?php echo e($theme['border']); ?> <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?>">
                                            <i class="ph-duotone <?php echo e($theme['icon']); ?>"></i>
                                        </div>
                                        
                                        <?php if($isGraded): ?>
                                            <div class="flex flex-col items-end">
                                                <span class="text-2xl font-black <?php echo e($score < 75 ? 'text-rose-500' : 'text-emerald-500'); ?>"><?php echo e($score); ?></span>
                                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Nilai</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex flex-col items-end gap-1">
                                                <?php if($isExpired): ?>
                                                    <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1">
                                                        <i class="ph-bold ph-lock-key"></i> Tutup
                                                    </span>
                                                <?php else: ?>
                                                    <span class="bg-rose-50 text-rose-600 border border-rose-100 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1 animate-pulse">
                                                        <i class="ph-bold ph-clock"></i> Aktif
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug" title="<?php echo e($task->title); ?>">
                                            <?php echo e($task->title); ?>

                                        </h4>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-xs font-bold <?php echo e($typeConfig['color']); ?>"><?php echo e($typeConfig['label']); ?></span>
                                            <span class="text-slate-300">•</span>
                                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                                <i class="ph-fill ph-calendar-blank"></i> 
                                                <?php echo e(\Carbon\Carbon::parse($task->deadline)->translatedFormat('d M, H:i')); ?>

                                            </span>
                                        </div>
                                    </div>

                                    
                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10">
                                        <?php if($isGraded): ?>
                                            <button disabled class="w-full py-3 rounded-xl bg-slate-50 text-slate-400 font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed border border-slate-100">
                                                <i class="ph-bold ph-check-circle"></i> Selesai
                                            </button>
                                        <?php else: ?>
                                            
                                            <a href="<?php echo e($isQuiz ? route('students.learning.assignment.quiz', $task->id) : route('students.learning.subject.show', $task->subject_id) . '#tugas'); ?>" 
                                               class="w-full py-3 rounded-xl <?php echo e($isQuiz ? 'bg-purple-600 hover:bg-purple-700 shadow-purple-200' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-200'); ?> text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 transition-all">
                                                <span>Kerjakan</span>
                                                <i class="ph-bold ph-arrow-right"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            
            <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 text-center group hover:border-blue-300 transition-colors">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors shadow-inner">
                    <i class="ph-duotone ph-clipboard-text text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                </div>
                <h3 class="font-black text-slate-800 text-lg mb-1">Tidak Ada Tugas Aktif</h3>
                <p class="text-slate-500 text-sm max-w-xs mx-auto">Saat ini belum ada tugas atau kuis baru.</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div x-show="lmsTab === 'materials'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <?php if(isset($lms_materials_grouped) && count($lms_materials_grouped) > 0): ?>
            <?php $__currentLoopData = $lms_materials_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $materials): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // THEME LOGIC yang sama dengan Assignments
                    $sName = strtolower($subjectName);
                    $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100', 'icon' => 'ph-book-bookmark'];

                    if (str_contains($sName, 'informatika') || str_contains($sName, 'tik') || str_contains($sName, 'komputer')) {
                        $theme = ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200', 'light' => 'bg-cyan-100', 'ring' => 'ring-cyan-100', 'icon' => 'ph-desktop'];
                    } elseif (str_contains($sName, 'seni') || str_contains($sName, 'budaya') || str_contains($sName, 'musik')) {
                        $theme = ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'light' => 'bg-purple-100', 'ring' => 'ring-purple-100', 'icon' => 'ph-palette'];
                    } elseif (str_contains($sName, 'matematika') || str_contains($sName, 'kalkulus') || str_contains($sName, 'aljabar')) {
                        $theme = ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'light' => 'bg-orange-100', 'ring' => 'ring-orange-100', 'icon' => 'ph-calculator'];
                    } elseif (str_contains($sName, 'ipa') || str_contains($sName, 'fisika') || str_contains($sName, 'agama')) {
                        $theme = ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'light' => 'bg-emerald-100', 'ring' => 'ring-emerald-100', 'icon' => 'ph-flask'];
                    } elseif (str_contains($sName, 'bahasa') || str_contains($sName, 'inggris') || str_contains($sName, 'indonesia')) {
                        $theme = ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'light' => 'bg-rose-100', 'ring' => 'ring-rose-100', 'icon' => 'ph-translate'];
                    } elseif (str_contains($sName, 'ips') || str_contains($sName, 'sejarah') || str_contains($sName, 'pkn')) {
                        $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100', 'icon' => 'ph-globe-hemisphere-west'];
                    }
                ?>

                <div class="mb-10 animate-enter">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="h-10 w-1.5 rounded-full <?php echo e(str_replace('text-', 'bg-', $theme['text'])); ?> opacity-50"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <?php echo e($subjectName); ?>

                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> border <?php echo e($theme['border']); ?>">
                                <?php echo e(count($materials)); ?> Materi
                            </span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-1 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 <?php echo e($theme['ring']); ?>">
                                <div class="bg-white rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden">
                                    
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full <?php echo e($theme['bg']); ?> opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                    <div class="flex justify-between items-start mb-4 relative z-10">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border <?php echo e($theme['border']); ?> <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?>">
                                            <i class="ph-duotone <?php echo e($theme['icon']); ?>"></i>
                                        </div>
                                    </div>

                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                            <?php echo e($material->title); ?>

                                        </h4>
                                        <p class="text-xs text-slate-500 mt-2 line-clamp-2"><?php echo e($material->description ?? 'Tidak ada deskripsi.'); ?></p>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10 flex gap-2">
                                        <?php if($material->file_path): ?>
                                            <a href="<?php echo e(route('students.learning.material.download', $material->id)); ?>" class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                                                <i class="ph-bold ph-download-simple"></i> Unduh
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo e(route('students.learning.subject.show', $material->subject_id)); ?>" class="flex-1 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                                            <i class="ph-bold ph-eye"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            
            <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 text-center group hover:border-indigo-300 transition-colors">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-50 transition-colors shadow-inner">
                    <i class="ph-duotone ph-books text-4xl text-slate-300 group-hover:text-indigo-400 transition-colors"></i>
                </div>
                <h3 class="font-black text-slate-800 text-lg mb-1">Belum Ada Materi</h3>
                <p class="text-slate-500 text-sm max-w-xs mx-auto">Guru belum mengunggah materi pelajaran untuk kelas ini.</p>
            </div>
        <?php endif; ?>
    </div>

</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\tab-lms.blade.php ENDPATH**/ ?>