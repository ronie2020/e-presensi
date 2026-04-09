<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    
    <style>
        /* CSS untuk menyembunyikan scrollbar pada menu filter tab tapi tetap bisa digeser dengan rapi */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                            <i class="ph-fill ph-hand-heart"></i> Student Care Center
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                            E-Counseling & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-emerald-200">Bimbingan</span>
                        </h1>
                        <p class="text-blue-200/80 text-sm sm:text-base max-w-xl font-medium">
                            Kelola antrian konseling, jadwalkan pertemuan, dan pantau perkembangan siswa secara real-time.
                        </p>
                    </div>

                    
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 flex items-center gap-4">
                            <div class="p-3 bg-blue-500 rounded-xl text-white shadow-lg shadow-blue-500/30">
                                <i class="ph-duotone ph-calendar-check text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-blue-200 font-bold uppercase">Hari Ini</div>
                                <div class="text-lg font-bold text-white"><?php echo e(now()->translatedFormat('l, d F Y')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Pending -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'pending'])); ?>" class="bg-white p-5 rounded-2xl shadow-sm border border-amber-100 hover:border-amber-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600 group-hover:bg-amber-100 transition">
                            <i class="ph-bold ph-hourglass text-xl"></i>
                        </div>
                        <span class="bg-amber-100 text-amber-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Pending</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?php echo e($stats['pending']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Menunggu Respon</div>
                </a>

                <!-- Approved -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'approved'])); ?>" class="bg-white p-5 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-100 transition">
                            <i class="ph-bold ph-calendar-check text-xl"></i>
                        </div>
                        <span class="bg-blue-100 text-blue-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Terjadwal</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?php echo e($stats['approved']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Akan Datang</div>
                </a>

                <!-- Finished -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'finished'])); ?>" class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100 hover:border-emerald-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600 group-hover:bg-emerald-100 transition">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="bg-emerald-100 text-emerald-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Selesai</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?php echo e($stats['finished']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>

                <!-- Rejected -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'rejected'])); ?>" class="bg-white p-5 rounded-2xl shadow-sm border border-rose-100 hover:border-rose-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-rose-50 rounded-lg text-rose-600 group-hover:bg-rose-100 transition">
                            <i class="ph-bold ph-x-circle text-xl"></i>
                        </div>
                        <span class="bg-rose-100 text-rose-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Ditolak</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?php echo e($stats['rejected']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col gap-5">
                
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 w-full">
                    <div class="flex items-center gap-3 text-sm font-bold text-slate-600">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <i class="ph-bold ph-funnel text-lg"></i>
                        </div>
                        Filter & Pencarian Sesi
                    </div>

                    
                    <form method="GET" action="<?php echo e(route('admin.bk.index')); ?>" class="w-full md:w-auto shrink-0 flex flex-col sm:flex-row gap-2">
                        <?php if(request('status')): ?> <input type="hidden" name="status" value="<?php echo e(request('status')); ?>"> <?php endif; ?>
                        <?php if(request('type')): ?> <input type="hidden" name="type" value="<?php echo e(request('type')); ?>"> <?php endif; ?>
                        
                        <div class="relative w-full sm:w-64 lg:w-72">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Siswa / Topik..." 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors text-center">
                                Cari
                            </button>
                            <?php if(request('search') || request('status') || request('type')): ?>
                                <a href="<?php echo e(route('admin.bk.index')); ?>" class="flex-1 sm:flex-none bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center justify-center">
                                    Reset
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                
                <div class="w-full h-px bg-slate-100"></div>

                
                <div class="w-full overflow-x-auto hide-scrollbar">
                    <div class="flex items-center gap-4 w-max pb-1">
                        
                        
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1">
                                <?php $__currentLoopData = ['pending' => 'Pending', 'approved' => 'Terjadwal', 'all' => 'Semua Status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['status' => $key, 'page' => 1])); ?>" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       <?php echo e((request('status') == $key || ($key == 'all' && !request('status'))) 
                                            ? 'bg-white text-blue-600 shadow-sm border border-slate-200/60' 
                                            : 'text-slate-500 hover:text-slate-700'); ?>">
                                       <?php echo e($label); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <div class="w-px h-8 bg-slate-200 mx-2"></div>

                        
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tipe:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1">
                                <?php $__currentLoopData = ['all' => 'Semua Tipe', 'bermasalah' => 'Bermasalah', 'berprestasi' => 'Berprestasi', 'mandiri' => 'Pengajuan Siswa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $activeClass = 'bg-white text-slate-800 shadow-sm border border-slate-200/60';
                                        if($key == 'bermasalah') $activeClass = 'bg-rose-50 text-rose-600 shadow-sm border border-rose-200/60';
                                        if($key == 'berprestasi') $activeClass = 'bg-blue-50 text-blue-600 shadow-sm border border-blue-200/60';
                                        if($key == 'mandiri') $activeClass = 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-200/60';
                                    ?>
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['type' => $key, 'page' => 1])); ?>" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       <?php echo e((request('type') == $key || ($key == 'all' && !request('type'))) 
                                            ? $activeClass 
                                            : 'text-slate-500 hover:text-slate-700'); ?>">
                                       <?php echo e($label); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                
                
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="ph-duotone ph-list-dashes text-blue-500 text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">Daftar Antrian & Riwayat</span>
                    </div>
                </div>

                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 rounded-tl-2xl">Siswa</th>
                                <th class="px-6 py-4">Topik & Pesan</th>
                                <th class="px-6 py-4">Metode</th>
                                <th class="px-6 py-4">Status & Jadwal</th>
                                <th class="px-6 py-4 rounded-tr-2xl text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar -->
                                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0 overflow-hidden border-2 border-white shadow-sm">
                                            <?php if($session->student && $session->student->photo_path): ?>
                                                <img src="<?php echo e(asset('storage/' . $session->student->photo_path)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <?php echo e(substr($session->student->name ?? '?', 0, 1)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 group-hover:text-blue-600 transition"><?php echo e($session->student->name ?? 'Data Terhapus'); ?></div>
                                            <div class="text-xs text-slate-400 font-medium"><?php echo e($session->student->schoolClass->name ?? '-'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold uppercase">
                                                <?php echo e($session->category->name ?? 'Umum'); ?>

                                            </span>
                                            
                                            <?php if($session->is_system_generated ?? false): ?>
                                                <?php if(str_contains($session->initial_message, 'PELANGGARAN')): ?>
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-50 text-rose-700 text-[10px] font-black rounded-lg border border-rose-200 uppercase tracking-widest animate-pulse">
                                                        <i class="ph-bold ph-warning"></i> Urgent: Sistem
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-lg border border-blue-200 uppercase tracking-widest">
                                                        <i class="ph-bold ph-medal"></i> Apresiasi Sistem
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg border border-slate-200 uppercase tracking-widest">
                                                    <i class="ph-bold ph-user"></i> Pengajuan Siswa
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm text-slate-600 truncate w-48 italic" title="<?php echo e($session->initial_message); ?>">
                                            "<?php echo e($session->initial_message); ?>"
                                        </p>
                                        <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1 font-medium">
                                            <i class="ph-bold ph-clock"></i> <?php echo e($session->created_at->diffForHumans()); ?>

                                        </div>
                                    </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <?php if($session->method == 'online'): ?>
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-full bg-purple-100 text-purple-600">
                                                <i class="ph-bold ph-globe"></i>
                                            </div>
                                            <span class="font-bold text-xs text-slate-600">Online</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-full bg-teal-100 text-teal-600">
                                                <i class="ph-bold ph-users"></i>
                                            </div>
                                            <span class="font-bold text-xs text-slate-600">Tatap Muka</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                        $colors = [
                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'approved' => 'bg-blue-100 text-blue-700 border-blue-200', 
                                            'ongoing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'finished' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        ];
                                        $statusClass = $colors[$session->status] ?? 'bg-slate-100 text-slate-700';
                                    ?>
                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black uppercase tracking-wide rounded-lg border <?php echo e($statusClass); ?>">
                                        <?php echo e(ucfirst($session->status == 'approved' ? 'Terjadwal' : $session->status)); ?>

                                    </span>
                                    
                                    <?php if($session->scheduled_at && $session->status == 'approved'): ?>
                                        <div class="text-xs text-blue-600 font-bold mt-1.5 flex items-center gap-1.5 bg-blue-50 px-2 py-1 rounded-md w-fit">
                                            <i class="ph-bold ph-calendar-check"></i> <?php echo e($session->scheduled_at->format('d M, H:i')); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="<?php echo e(route('admin.bk.show', $session->id)); ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300" title="Proses">
                                        <i class="ph-bold ph-caret-right text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-4 bg-slate-50 rounded-full">
                                            <?php if(request('type') == 'berprestasi'): ?>
                                                <i class="ph-duotone ph-medal text-3xl text-slate-300"></i>
                                            <?php elseif(request('type') == 'bermasalah'): ?>
                                                <i class="ph-duotone ph-warning-octagon text-3xl text-slate-300"></i>
                                            <?php else: ?>
                                                <i class="ph-duotone ph-clipboard-text text-3xl text-slate-300"></i>
                                            <?php endif; ?>
                                        </div>
                                        <span class="font-medium">
                                            <?php if(request('type') == 'berprestasi'): ?>
                                                Belum ada data siswa berprestasi.
                                            <?php elseif(request('type') == 'bermasalah'): ?>
                                                Belum ada data siswa bermasalah.
                                            <?php elseif(request('search')): ?>
                                                Tidak ada data yang cocok dengan pencarian Anda.
                                            <?php else: ?>
                                                Belum ada data pengajuan konseling.
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    <?php echo e($sessions->links()); ?>

                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/bk/index.blade.php ENDPATH**/ ?>