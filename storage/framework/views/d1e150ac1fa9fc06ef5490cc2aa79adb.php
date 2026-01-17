<div class="space-y-6">
    <!-- Header Tab -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="ph-duotone ph-heart-beat text-pink-500 text-2xl"></i>
                Layanan Bimbingan Konseling
            </h3>
            <p class="text-slate-500 text-sm mt-1">Ruang aman untuk bercerita, konsultasi, dan pengembangan diri.</p>
        </div>
        <a href="<?php echo e(route('student.bk.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-pink-500/30 group">
            <i class="ph-bold ph-plus-circle text-lg group-hover:scale-110 transition-transform"></i>
            Ajukan Konseling
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sesi</p>
            <p class="text-2xl font-black text-slate-800 mt-1"><?php echo e($bkSessions->count()); ?></p>
        </div>
        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 shadow-sm">
            <p class="text-xs font-bold text-blue-400 uppercase tracking-wider">Akan Datang</p>
            <p class="text-2xl font-black text-blue-600 mt-1"><?php echo e($bkSessions->where('status', 'approved')->count()); ?></p>
        </div>
        <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100 shadow-sm">
            <p class="text-xs font-bold text-amber-400 uppercase tracking-wider">Menunggu</p>
            <p class="text-2xl font-black text-amber-600 mt-1"><?php echo e($bkSessions->where('status', 'pending')->count()); ?></p>
        </div>
        <div class="bg-green-50 p-4 rounded-2xl border border-green-100 shadow-sm">
            <p class="text-xs font-bold text-green-400 uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-black text-green-600 mt-1"><?php echo e($bkSessions->where('status', 'finished')->count()); ?></p>
        </div>
    </div>

    <!-- Daftar Riwayat -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h4 class="font-bold text-slate-800">Riwayat Konsultasi</h4>
        </div>
        
        <?php if($bkSessions->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal & Topik</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jadwal / Guru</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $bkSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0
                                        <?php echo e($session->category->color == 'red' ? 'bg-red-100 text-red-600' : 
                                          ($session->category->color == 'yellow' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600')); ?>">
                                        <?php if($session->method == 'online'): ?> 
                                            <i class="ph-duotone ph-chat-text"></i>
                                        <?php else: ?>
                                            <i class="ph-duotone ph-users-three"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm"><?php echo e($session->category->name); ?></p>
                                        <p class="text-xs text-slate-500 mt-0.5">Diajukan: <?php echo e($session->created_at->format('d M Y')); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($session->scheduled_at): ?>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700"><?php echo e($session->scheduled_at->format('d M, H:i')); ?></span>
                                        <span class="text-xs text-slate-500"><?php echo e($session->teacher->name ?? 'Guru BK'); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400 italic">Belum dijadwalkan</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusStyle = match($session->status) {
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'approved' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'ongoing' => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'finished' => 'bg-green-50 text-green-600 border-green-100',
                                        'rejected' => 'bg-red-50 text-red-600 border-red-100',
                                        default => 'bg-slate-50 text-slate-600'
                                    };
                                    $statusLabel = match($session->status) {
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'ongoing' => 'Berlangsung',
                                        'finished' => 'Selesai',
                                        'rejected' => 'Ditolak',
                                        default => '-'
                                    };
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border <?php echo e($statusStyle); ?>">
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?php echo e(route('student.bk.show', $session->id)); ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200 transition-all">
                                    <i class="ph-bold ph-caret-right"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4 text-pink-300">
                    <i class="ph-duotone ph-chats-teardrop text-4xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-800">Belum Ada Riwayat</h4>
                <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">Jangan ragu untuk berkonsultasi mengenai masalah akademik maupun non-akademik.</p>
                <a href="<?php echo e(route('student.bk.create')); ?>" class="inline-block mt-6 px-6 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:border-pink-500 hover:text-pink-600 transition-colors">
                    Mulai Konsultasi
                </a>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-bk.blade.php ENDPATH**/ ?>