<div class="grid grid-cols-1 gap-6">
    <?php if(isset($teaching_journals) && count($teaching_journals) > 0): ?>
        <?php $__currentLoopData = $teaching_journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wide border border-blue-100">
                                <?php echo e($journal->schedule?->subject?->name ?? 'Mapel'); ?>

                            </span>
                            <span class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                <i class="ph-fill ph-clock"></i>
                                <?php echo e(\Carbon\Carbon::parse($journal->started_at)->format('H:i')); ?>

                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800"><?php echo e($journal->topic ?? 'Tanpa Topik'); ?></h3>
                        <p class="text-sm text-slate-500">Pengajar: <?php echo e($journal->schedule?->teacher?->name ?? 'Guru'); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-slate-200"><?php echo e(\Carbon\Carbon::parse($journal->date)->format('d')); ?></p>
                        <p class="text-xs font-bold text-slate-400 uppercase"><?php echo e(\Carbon\Carbon::parse($journal->date)->translatedFormat('M Y')); ?></p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-2">Aktivitas / Tugas:</p>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line"><?php echo e($journal->activities ?? '-'); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                <i class="ph-duotone ph-notebook text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Belum Ada Riwayat KBM</h3>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/students/portal/partials/tab-kbm.blade.php ENDPATH**/ ?>