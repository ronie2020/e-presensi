<div class="space-y-8 font-sans text-slate-800 animate-in fade-in duration-500">
    
    <!-- 1. HEADER VIBRANT (Redesigned) -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-600 rounded-[2.5rem] p-8 md:p-10 text-white shadow-xl shadow-blue-900/10 relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
            <i class="ph-fill ph-heart-beat text-[200px] transform translate-x-10 -translate-y-10"></i>
        </div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-20 -mb-20 pointer-events-none"></div>

        <!-- Konten Kiri -->
        <div class="relative z-10 max-w-2xl">
            <div class="flex items-center gap-5 mb-4">
                <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl shadow-inner border border-white/20">
                    <i class="ph-duotone ph-chats-circle text-white"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-black tracking-tight mb-1">Layanan BK Digital</h3>
                    <div class="flex items-center gap-2 text-blue-100 text-xs font-bold uppercase tracking-widest opacity-80">
                        <span class="w-2 h-2 rounded-full bg-blue-300"></span>
                        Bimbingan & Konseling
                    </div>
                </div>
            </div>
            <p class="text-blue-50/90 text-sm md:text-base leading-relaxed pl-1">
                "Setiap masalah punya solusi. Kami di sini untuk mendengarkan tanpa menghakimi. Ceritakan apa yang kamu rasakan di ruang aman ini."
            </p>
        </div>
        
        <!-- Tombol Aksi -->
        <div class="relative z-10 flex-shrink-0 w-full md:w-auto">
            <a href="<?php echo e(route('student.bk.create')); ?>" class="flex items-center justify-center w-full md:w-auto gap-3 px-8 py-4 bg-white text-blue-700 hover:bg-blue-50 text-sm font-black rounded-2xl transition-all shadow-lg shadow-blue-900/20 group hover:-translate-y-1 hover:shadow-xl">
                <div class="p-1 rounded-full bg-blue-100 group-hover:bg-blue-200 transition-colors">
                    <i class="ph-bold ph-plus text-blue-700"></i>
                </div>
                <span>Ajukan Konseling</span>
            </a>
        </div>
    </div>

    <!-- 2. STATISTIK RINGKAS (Updated Icons & Colors) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total -->
        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-slate-50 rounded-2xl text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-600 transition-colors">
                    <i class="ph-bold ph-files text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</span>
            </div>
            <p class="text-3xl font-black text-slate-800"><?php echo e($bkSessions->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1">Sesi Diajukan</p>
        </div>

        <!-- Disetujui / Akan Datang -->
        <div class="bg-white p-5 rounded-[2rem] border border-blue-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 shadow-sm">
                    <i class="ph-bold ph-calendar-check text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-400">Jadwal</span>
            </div>
            <p class="text-3xl font-black text-slate-800 relative z-10"><?php echo e($bkSessions->where('status', 'approved')->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Akan Datang</p>
        </div>

        <!-- Menunggu -->
        <div class="bg-white p-5 rounded-[2rem] border border-amber-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 rounded-2xl text-amber-600 shadow-sm">
                    <i class="ph-bold ph-hourglass text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Proses</span>
            </div>
            <p class="text-3xl font-black text-slate-800 relative z-10"><?php echo e($bkSessions->where('status', 'pending')->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Menunggu Respon</p>
        </div>

        <!-- Selesai -->
        <div class="bg-white p-5 rounded-[2rem] border border-emerald-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 shadow-sm">
                    <i class="ph-bold ph-check-circle text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Selesai</span>
            </div>
            <p class="text-3xl font-black text-slate-800 relative z-10"><?php echo e($bkSessions->where('status', 'finished')->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Masalah Teratasi</p>
        </div>
    </div>

    <!-- 3. DAFTAR RIWAYAT -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h4 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="ph-duotone ph-list-dashes text-blue-500 text-xl"></i>
                    Riwayat Konsultasi
                </h4>
                <p class="text-xs text-slate-400 mt-1">Daftar sesi konseling yang pernah kamu ajukan</p>
            </div>
            <a href="<?php echo e(route('student.bk.index')); ?>" class="group flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition-all">
                Lihat Semua
                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <?php if($bkSessions->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest">
                        <tr>
                            <th class="px-6 py-5 rounded-tl-2xl">Topik & Pesan</th>
                            <th class="px-6 py-5">Jadwal</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-6 py-5 rounded-tr-2xl text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php $__currentLoopData = $bkSessions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg shrink-0 border border-slate-100 shadow-sm
                                        <?php echo e($session->method == 'online' ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600'); ?>">
                                        <?php if($session->method == 'online'): ?> 
                                            <i class="ph-duotone ph-chat-text"></i>
                                        <?php else: ?>
                                            <i class="ph-duotone ph-users-three"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors"><?php echo e($session->category->name); ?></span>
                                            <?php if($session->method == 'online'): ?>
                                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-600 border border-indigo-200 uppercase">Online</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 max-w-[200px]"><?php echo e($session->initial_message); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Diajukan</span>
                                    <span class="text-sm font-bold text-slate-700"><?php echo e($session->created_at->translatedFormat('d M Y')); ?></span>
                                    
                                    <?php if($session->scheduled_at): ?>
                                        <div class="mt-2 flex items-center gap-1.5 text-blue-600 bg-blue-50 w-fit px-2 py-1 rounded-lg border border-blue-100">
                                            <i class="ph-bold ph-clock text-xs"></i> 
                                            <span class="text-xs font-bold"><?php echo e($session->scheduled_at->format('H:i')); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <?php
                                    $statusStyle = match($session->status) {
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'ongoing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'finished' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600'
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
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wide border <?php echo e($statusStyle); ?> inline-flex items-center gap-1.5 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="<?php echo e(route('student.bk.show', $session->id)); ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-white border-2 border-slate-100 text-slate-400 hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300">
                                    <i class="ph-bold ph-caret-right text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-20 px-4 bg-slate-50/50">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 border-4 border-slate-50 shadow-sm">
                    <i class="ph-duotone ph-chats-teardrop text-5xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800">Belum Ada Riwayat</h4>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto mb-8 leading-relaxed">Jangan ragu untuk berkonsultasi mengenai masalah akademik maupun non-akademik. Kami siap membantu.</p>
                <a href="<?php echo e(route('student.bk.create')); ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-white border-2 border-slate-200 text-slate-700 font-bold rounded-2xl text-sm hover:border-blue-500 hover:text-blue-600 hover:shadow-lg transition-all">
                    <i class="ph-bold ph-plus-circle"></i>
                    Mulai Konsultasi Pertama
                </a>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-bk.blade.php ENDPATH**/ ?>