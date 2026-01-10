<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-indigo-100 sticky top-24 h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Perpustakaan</h3>
            <div class="space-y-3 mt-4">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-xs font-bold text-slate-500 uppercase">Total Bacaan</span>
                    <span class="text-xl font-black text-slate-800"><?php echo e($library_visits ?? 0); ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-50">
                <?php if(isset($library_history) && count($library_history) > 0): ?>
                    <?php $__currentLoopData = $library_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-5 hover:bg-indigo-50/30 transition-colors flex items-center gap-4">
                        <div class="w-12 h-16 bg-slate-200 rounded flex-shrink-0 flex items-center justify-center text-slate-400 shadow-sm">
                            <i class="ph-fill ph-book-open text-2xl"></i>
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-slate-800 truncate" title="<?php echo e($book->title); ?>"><?php echo e($book->title); ?></h4>
                            <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500">
                                <span class="flex items-center gap-1">Pinjam: <?php echo e(\Carbon\Carbon::parse($book->borrow_date)->translatedFormat('d M Y')); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="p-12 text-center">
                        <h3 class="font-bold text-slate-800">Ayo Membaca!</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/students/portal/partials/tab-perpustakaan.blade.php ENDPATH**/ ?>