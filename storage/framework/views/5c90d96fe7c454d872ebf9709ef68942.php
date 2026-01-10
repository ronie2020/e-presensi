<div class="space-y-6">
    <?php if(isset($lms_assignments_grouped) && count($lms_assignments_grouped) > 0): ?>
        <?php $__currentLoopData = $lms_assignments_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $assignments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="ph-fill ph-book-bookmark text-blue-600"></i> <?php echo e($subjectName); ?>

                    </h3>
                    <span class="text-xs font-bold bg-white px-3 py-1 rounded-full border border-gray-200 text-slate-500">
                        <?php echo e(count($assignments)); ?> Tugas
                    </span>
                </div>
                
                
                <div class="hidden md:block overflow-x-auto w-full"> 
                    <table class="w-full text-left min-w-[500px]"> 
                        <tbody class="divide-y divide-gray-50">
                            <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $score = $lms_grades[$task->id] ?? null;
                                    $isGraded = $score !== null;
                                ?>
                                <tr class="group hover:bg-slate-50/50 transition">
                                    <td class="p-5">
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center text-xl
                                                <?php echo e($task->assignment_type == 'quiz' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600'); ?>">
                                                <i class="ph-duotone <?php echo e($task->assignment_type == 'quiz' ? 'ph-exam' : 'ph-clipboard-text'); ?>"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-700 text-sm group-hover:text-blue-600 transition"><?php echo e($task->title); ?></h4>
                                                <div class="flex gap-3 mt-1 text-xs text-slate-400 font-medium">
                                                    <span class="uppercase tracking-wider"><?php echo e($task->assignment_type == 'quiz' ? 'Kuis Online' : 'Tugas Rumah'); ?></span>
                                                    <span>&bull;</span>
                                                    <span><?php echo e(\Carbon\Carbon::parse($task->created_at)->translatedFormat('d F Y')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right whitespace-nowrap">
                                        <?php if($isGraded): ?>
                                            <div class="flex flex-col items-end">
                                                <span class="text-2xl font-black <?php echo e($score < 70 ? 'text-rose-500' : 'text-emerald-600'); ?>">
                                                    <?php echo e($score); ?>

                                                </span>
                                                <span class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Nilai</span>
                                            </div>
                                        <?php else: ?>
                                            <span class="inline-block px-3 py-1 rounded-lg bg-slate-100 text-slate-400 text-xs font-bold">
                                                Belum Dinilai
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="md:hidden divide-y divide-gray-50">
                     <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $score = $lms_grades[$task->id] ?? null;
                            $isGraded = $score !== null;
                        ?>
                        <div class="p-4 flex flex-col gap-3">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex-shrink-0 flex items-center justify-center text-lg
                                        <?php echo e($task->assignment_type == 'quiz' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600'); ?>">
                                        <i class="ph-duotone <?php echo e($task->assignment_type == 'quiz' ? 'ph-exam' : 'ph-clipboard-text'); ?>"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-slate-800 text-sm truncate"><?php echo e($task->title); ?></h4>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wider"><?php echo e($task->assignment_type == 'quiz' ? 'Kuis' : 'PR'); ?></p>
                                    </div>
                                </div>
                                <?php if($isGraded): ?>
                                    <span class="text-xl font-black <?php echo e($score < 70 ? 'text-rose-500' : 'text-emerald-600'); ?>">
                                        <?php echo e($score); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-xs font-bold text-slate-300">--</span>
                                <?php endif; ?>
                            </div>
                        </div>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                <i class="ph-duotone ph-clipboard-text text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Belum Ada Tugas</h3>
            <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">Saat ini belum ada data tugas atau kuis yang tersedia untuk kelas ini.</p>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/students/portal/partials/tab-lms.blade.php ENDPATH**/ ?>