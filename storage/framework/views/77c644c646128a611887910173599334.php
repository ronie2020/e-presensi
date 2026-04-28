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
        /* Sembunyikan scrollbar pada menu filter tab */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* CSS Khusus untuk Mode Cetak (Print / Save as PDF) */
        @media print {
            body { background-color: white !important; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .shadow-xl, .shadow-sm, .shadow-md, .shadow-lg { box-shadow: none !important; border: none !important; }
            .bg-white, .bg-slate-50 { background-color: white !important; }
            .table-container { overflow: visible !important; }
            table { width: 100% !important; border-collapse: collapse !important; }
            th, td { border: 1px solid #cbd5e1 !important; padding: 12px !important; }
            @page { margin: 1.5cm; size: landscape; } /* Landscape agar kolom tabel muat */
        }
    </style>

     <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        
        
        
        
        <div class="hidden print:block w-full border-b-4 border-double border-slate-800 pb-4 mb-8 text-center">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-600 mb-1">Pemerintah Provinsi Daerah</h3>
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900 mb-1">Nama Sekolah Anda</h1>
            <p class="text-xs font-medium text-slate-700">Jl. Contoh Alamat Sekolah No. 123, Kota/Kabupaten, Kode Pos 12345</p>
            <h2 class="text-lg font-bold uppercase tracking-widest text-slate-800 mt-6 underline decoration-2 underline-offset-4">Rekapitulasi Data Bimbingan Konseling</h2>
            <p class="text-xs font-bold text-slate-500 mt-2">Dicetak pada: <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIB</p>
        </div>

       
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 print:hidden">
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60 group">
                
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="space-y-2 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-hand-heart text-elevate-primary"></i> Student Care Center
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight text-elevate-dark">
                            E-Counseling & Bimbingan
                        </h1>
                        <p class="text-elevate-dark/80 text-sm sm:text-base font-medium leading-relaxed">
                            Kelola antrian konseling, jadwalkan pertemuan, dan pantau perkembangan siswa secara real-time.
                        </p>
                    </div>

                    
                    <div class="hidden md:block">
                        <div class="bg-white/60 backdrop-blur-md border border-white rounded-2xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="p-3 bg-elevate-accent/20 rounded-xl text-elevate-primary shadow-inner">
                                <i class="ph-duotone ph-calendar-check text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-elevate-primary font-bold uppercase tracking-wider">Hari Ini</div>
                                <div class="text-lg font-black text-elevate-dark"><?php echo e(now()->translatedFormat('l, d F Y')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 print:hidden">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Pending -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'pending'])); ?>" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-amber-100 hover:border-amber-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-amber-50 rounded-xl text-amber-600 group-hover:bg-amber-100 transition-colors shadow-sm border border-amber-100">
                            <i class="ph-bold ph-hourglass text-xl"></i>
                        </div>
                        <span class="bg-amber-100 text-amber-700 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Pending</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($stats['pending']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Menunggu Respon</div>
                </a>

                <!-- Approved (Terjadwal) -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'approved'])); ?>" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-elevate-primary/20 hover:border-elevate-primary/50 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-elevate-accent/10 rounded-xl text-elevate-primary group-hover:bg-elevate-primary group-hover:text-white transition-colors shadow-sm border border-elevate-accent/20">
                            <i class="ph-bold ph-calendar-check text-xl"></i>
                        </div>
                        <span class="bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Terjadwal</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($stats['approved']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Akan Datang</div>
                </a>

                <!-- Finished -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'finished'])); ?>" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-emerald-100 hover:border-emerald-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-100 transition-colors shadow-sm border border-emerald-100">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="bg-emerald-100 text-emerald-700 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Selesai</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($stats['finished']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>

                <!-- Rejected -->
                <a href="<?php echo e(route('admin.bk.index', ['status' => 'rejected'])); ?>" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-rose-100 hover:border-rose-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-rose-50 rounded-xl text-rose-600 group-hover:bg-rose-100 transition-colors shadow-sm border border-rose-100">
                            <i class="ph-bold ph-x-circle text-xl"></i>
                        </div>
                        <span class="bg-rose-100 text-rose-700 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Ditolak</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($stats['rejected']); ?></div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 print:hidden">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col gap-5">
                
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 w-full">
                    <div class="flex items-center gap-3 text-sm font-black text-elevate-dark uppercase tracking-wider">
                        <div class="p-2 bg-elevate-accent/10 text-elevate-primary rounded-xl border border-elevate-accent/20">
                            <i class="ph-bold ph-funnel text-lg"></i>
                        </div>
                        Filter & Pencarian
                    </div>

                    
                    <form method="GET" action="<?php echo e(route('admin.bk.index')); ?>" class="w-full md:w-auto shrink-0 flex flex-col sm:flex-row gap-2">
                        <?php if(request('status')): ?> <input type="hidden" name="status" value="<?php echo e(request('status')); ?>"> <?php endif; ?>
                        <?php if(request('type')): ?> <input type="hidden" name="type" value="<?php echo e(request('type')); ?>"> <?php endif; ?>
                        
                        <div class="relative w-full sm:w-64 lg:w-72">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Siswa / Topik..." 
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:bg-white focus:ring-elevate-primary focus:border-elevate-primary transition-all shadow-sm">
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none bg-elevate-dark hover:bg-elevate-primary text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-elevate-dark/20 transition-all text-center active:scale-95">
                                Cari
                            </button>
                            <?php if(request('search') || request('status') || request('type')): ?>
                                <a href="<?php echo e(route('admin.bk.index')); ?>" class="flex-1 sm:flex-none bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-500 border border-slate-200 hover:border-rose-200 px-5 py-3 rounded-2xl text-sm font-bold transition-all flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                
                <div class="w-full h-px bg-slate-100"></div>

                
                <div class="w-full overflow-x-auto hide-scrollbar">
                    <div class="flex items-center gap-4 w-max pb-1">
                        
                        
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1 shadow-inner">
                                <?php $__currentLoopData = ['pending' => 'Pending', 'approved' => 'Terjadwal', 'all' => 'Semua']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['status' => $key, 'page' => 1])); ?>" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       <?php echo e((request('status') == $key || ($key == 'all' && !request('status'))) 
                                            ? 'bg-white text-elevate-primary shadow-sm border border-slate-200/60' 
                                            : 'text-slate-500 hover:text-elevate-dark'); ?>">
                                       <?php echo e($label); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <div class="w-px h-8 bg-slate-200 mx-2"></div>

                        
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tipe:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1 shadow-inner">
                                <?php $__currentLoopData = ['all' => 'Semua Tipe', 'bermasalah' => 'Bermasalah', 'berprestasi' => 'Berprestasi', 'mandiri' => 'Pengajuan Siswa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $activeClass = 'bg-white text-elevate-dark shadow-sm border border-slate-200/60';
                                        if($key == 'bermasalah') $activeClass = 'bg-rose-50 text-rose-600 shadow-sm border border-rose-200/60';
                                        if($key == 'berprestasi') $activeClass = 'bg-elevate-accent/10 text-elevate-primary shadow-sm border border-elevate-accent/20';
                                        if($key == 'mandiri') $activeClass = 'bg-emerald-50 text-emerald-600 shadow-sm border border-emerald-200/60';
                                    ?>
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['type' => $key, 'page' => 1])); ?>" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       <?php echo e((request('type') == $key || ($key == 'all' && !request('type'))) 
                                            ? $activeClass 
                                            : 'text-slate-500 hover:text-elevate-dark'); ?>">
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
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-elevate-primary text-xl"></i>
                        <span class="text-sm font-black text-elevate-dark">
                            <?php if(request('status') || request('type') || request('search')): ?>
                                Hasil Pencarian Sesi
                            <?php else: ?>
                                Daftar Antrian & Riwayat Terbaru
                            <?php endif; ?>
                        </span>
                    </div>                    
                     
                    <div class="flex flex-wrap items-center gap-2 print:hidden w-full sm:w-auto">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['export' => 'excel'])); ?>" class="flex-1 sm:flex-none text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-600 hover:text-white px-5 py-2.5 rounded-xl border border-emerald-200 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-file-csv text-base"></i> Unduh Excel
                        </a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['export' => 'pdf'])); ?>" target="_blank" class="flex-1 sm:flex-none text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white px-5 py-2.5 rounded-xl border border-rose-200 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-printer text-base"></i> Cetak Laporan
                        </a>
                    </div>
                </div>

                
                <div class="overflow-x-auto table-container">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest">
                            <tr>
                                <th class="px-6 py-5 pl-8">Identitas Siswa</th>
                                <th class="px-6 py-5">Topik & Pesan</th>
                                <th class="px-6 py-5 print:hidden">Metode</th>
                                <th class="px-6 py-5">Status & Jadwal</th>
                                <th class="px-6 py-5 text-right pr-8 print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            
                            
                            <?php
                                $isOverdue = $session->status == 'pending' && $session->created_at->diffInHours(now()) > 48;
                            ?>

                            
                            <tr class="hover:bg-slate-50/80 transition-colors group cursor-pointer" onclick="window.location.href='<?php echo e(route('admin.bk.show', $session->id)); ?>'">
                                <td class="px-6 py-5 pl-8 align-top">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar -->
                                        <div class="w-10 h-10 rounded-[1rem] bg-slate-100 flex items-center justify-center text-elevate-primary font-black text-xs shrink-0 overflow-hidden border border-slate-200 shadow-sm print:hidden group-hover:border-elevate-primary/30 transition-colors">
                                            <?php if($session->student && $session->student->photo_path): ?>
                                                <img src="<?php echo e(asset('storage/' . $session->student->photo_path)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <?php echo e(substr($session->student->name ?? '?', 0, 1)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-black text-elevate-dark group-hover:text-elevate-primary transition-colors leading-tight line-clamp-1"><?php echo e($session->student->name ?? 'Data Terhapus'); ?></div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5"><?php echo e($session->student->schoolClass->name ?? '-'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-[9px] font-black uppercase tracking-wider print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                                <i class="ph-bold ph-tag print:hidden text-elevate-primary"></i> <?php echo e($session->category->name ?? 'Umum'); ?>

                                            </span>
                                            
                                            <?php if($session->is_system_generated ?? false): ?>
                                                <?php if(str_contains($session->initial_message, 'PELANGGARAN')): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 text-[9px] font-black rounded-lg border border-rose-200 uppercase tracking-widest animate-pulse print:border-none print:px-0 print:py-0 print:bg-transparent print:text-rose-600 shadow-sm">
                                                        <i class="ph-bold ph-warning print:hidden"></i> Panggilan Sistem
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 text-[9px] font-black rounded-lg uppercase tracking-widest print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                                        <i class="ph-bold ph-medal print:hidden"></i> Apresiasi Sistem
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 text-[9px] font-black rounded-lg border border-slate-200 uppercase tracking-widest print:hidden shadow-sm">
                                                    <i class="ph-bold ph-user text-slate-400"></i> Pengajuan Siswa
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm text-slate-600 font-medium leading-relaxed truncate w-48 italic print:w-auto print:whitespace-normal" title="<?php echo e($session->initial_message); ?>">
                                            "<?php echo e($session->initial_message); ?>"
                                        </p>
                                        
                                        <div class="text-[10px] mt-2 flex items-center gap-1.5 font-bold uppercase tracking-widest">
                                            <span class="text-slate-400 flex items-center gap-1"><i class="ph-bold ph-clock"></i> <?php echo e($session->created_at->diffForHumans()); ?></span>
                                            
                                            
                                            <?php if($isOverdue): ?>
                                                <span class="ml-2 bg-rose-100 text-rose-700 border border-rose-200 px-1.5 py-0.5 rounded font-black tracking-wider uppercase animate-pulse print:hidden">
                                                    > 48 Jam
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 print:hidden align-top">
                                    <?php if($session->method == 'online'): ?>
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-lg bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20">
                                                <i class="ph-bold ph-globe text-base"></i>
                                            </div>
                                            <span class="font-bold text-xs text-elevate-dark uppercase tracking-wider">Online</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                <i class="ph-bold ph-users text-base"></i>
                                            </div>
                                            <span class="font-bold text-xs text-elevate-dark uppercase tracking-wider">Tatap Muka</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap align-top">
                                    <?php
                                        $colors = [
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'approved' => 'bg-elevate-accent/10 text-elevate-primary border-elevate-accent/20', 
                                            'ongoing' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                            'finished' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'rejected' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        ];
                                        $statusClass = $colors[$session->status] ?? 'bg-slate-50 text-slate-500 border-slate-200';
                                    ?>
                                    <span class="px-3 py-1.5 inline-flex text-[9px] font-black uppercase tracking-widest rounded-lg border <?php echo e($statusClass); ?> print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                        <?php echo e(ucfirst($session->status == 'approved' ? 'Terjadwal' : $session->status)); ?>

                                    </span>
                                    
                                    <?php if($session->scheduled_at && $session->status == 'approved'): ?>
                                        <div class="text-[10px] font-black uppercase tracking-widest text-elevate-primary mt-2 flex items-center gap-1.5 bg-elevate-accent/5 px-2.5 py-1.5 rounded-lg border border-elevate-accent/20 w-fit print:bg-transparent print:p-0">
                                            <i class="ph-bold ph-calendar-check print:hidden"></i> <?php echo e($session->scheduled_at->format('d M Y, H:i')); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center print:hidden pr-8 align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        
                                        <?php if($session->student && $session->student->parent_wa_number): ?>
                                            <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', $session->student->parent_wa_number)); ?>" 
                                               target="_blank" 
                                               onclick="event.stopPropagation();" 
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-slate-200 text-emerald-500 hover:bg-emerald-500 hover:border-emerald-500 hover:text-white transition-all shadow-sm" title="WA Orang Tua">
                                                <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        
                                        <div class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-elevate-dark text-white group-hover:bg-elevate-primary transition-all duration-300 shadow-md shadow-elevate-dark/20" title="Buka Detail">
                                            <i class="ph-bold ph-caret-right text-lg"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center shadow-inner">
                                            <?php if(request('type') == 'berprestasi'): ?>
                                                <i class="ph-duotone ph-medal text-4xl text-slate-300"></i>
                                            <?php elseif(request('type') == 'bermasalah'): ?>
                                                <i class="ph-duotone ph-warning-octagon text-4xl text-slate-300"></i>
                                            <?php else: ?>
                                                <i class="ph-duotone ph-clipboard-text text-4xl text-slate-300"></i>
                                            <?php endif; ?>
                                        </div>
                                        <span class="font-bold text-slate-500 text-base">
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
                
                
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 print:hidden">
                    <?php echo e($sessions->links()); ?>

                </div>
            </div>
                        
              
            <div class="hidden print:flex justify-between items-end mt-12 px-8 break-inside-avoid">
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Mengetahui,<br>Kepala Sekolah</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2">_________________________</p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Kota/Kabupaten, <?php echo e(now()->translatedFormat('d F Y')); ?><br>Guru Bimbingan Konseling</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2"><?php echo e(Auth::user()->name ?? '_________________________'); ?></p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
            </div>

        </div>
    </div>

    
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo session('success'); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans'
                    }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo session('error'); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans'
                    }
                });
            <?php endif; ?>
        });
    </script>
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