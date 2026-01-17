

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-6xl mx-auto pb-20 px-4 sm:px-6 pt-6 md:pt-10">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Riwayat Konseling</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar konsultasi yang pernah kamu ajukan.</p>
        </div>
        <a href="<?php echo e(route('student.bk.create')); ?>" class="inline-flex items-center px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-pink-500/30 hover:-translate-y-1">
            <i class="ph-bold ph-plus mr-2"></i> Buat Baru
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-start gap-3">
            <i class="ph-fill ph-check-circle text-green-500 text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold text-green-800 text-sm">Berhasil!</h4>
                <p class="text-green-600 text-sm"><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <?php if($histories->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Topik</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jadwal / Guru</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 mb-1">
                                    <?php echo e($item->category->name); ?>

                                </span>
                                <p class="text-sm text-slate-500 truncate max-w-[200px]" title="<?php echo e($item->initial_message); ?>">
                                    <?php echo e(Str::limit($item->initial_message, 40)); ?>

                                </p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                <?php echo e($item->created_at->translatedFormat('d F Y')); ?>

                                <span class="block text-xs text-slate-400"><?php echo e($item->created_at->format('H:i')); ?> WIB</span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($item->scheduled_at): ?>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-blue-600"><?php echo e($item->scheduled_at->translatedFormat('d M, H:i')); ?></span>
                                        <span class="text-xs text-slate-500 mt-0.5">Oleh: <?php echo e($item->teacher->name ?? 'Guru BK'); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400 italic bg-slate-100 px-2 py-1 rounded">Belum ditentukan</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusClass = match($item->status) {
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'approved' => 'bg-blue-100 text-blue-700',
                                        'ongoing' => 'bg-purple-100 text-purple-700',
                                        'finished' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-600'
                                    };
                                    $statusLabel = match($item->status) {
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'ongoing' => 'Berlangsung',
                                        'finished' => 'Selesai',
                                        'rejected' => 'Ditolak',
                                        default => $item->status
                                    };
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo e($statusClass); ?>">
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?php echo e(route('student.bk.show', $item->id)); ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-pink-600 hover:border-pink-200 hover:bg-pink-50 transition-all shadow-sm">
                                    <i class="ph-bold ph-caret-right"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                <?php echo e($histories->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-20 px-4">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="ph-duotone ph-chats-teardrop text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat</h3>
                <p class="mt-2 text-slate-500 text-sm max-w-sm mx-auto">Kamu belum pernah mengajukan sesi konseling. Jangan ragu untuk bercerita jika ada masalah.</p>
                <a href="<?php echo e(route('student.bk.create')); ?>" class="inline-block mt-6 px-6 py-2.5 bg-slate-800 text-white font-bold rounded-xl text-sm hover:bg-slate-700 transition-colors shadow-lg shadow-slate-200">
                    Mulai Konsultasi
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/bk/index.blade.php ENDPATH**/ ?>