<div class="space-y-8">
    
    
    <div class="relative rounded-[2.5rem] bg-elevate-dark p-8 md:p-10 mb-8 text-white shadow-xl shadow-elevate-dark/10 overflow-hidden border border-elevate-peach/30 group">
        
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[400px] h-[400px] bg-elevate-peach rounded-full blur-[100px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-elevate-primary rounded-full blur-[80px] opacity-20 pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-peach-light/20 border border-elevate-peach/30 text-elevate-peach-light text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-md shadow-sm">
                    <i class="ph-fill ph-shield-warning text-sm"></i> Zona Aman Bercerita
                </div>
                <h2 class="text-3xl md:text-4xl font-black tracking-tighter mb-4 leading-none">Layanan <span class="text-elevate-accent">Pengaduan</span></h2>
                <p class="text-white/70 text-sm leading-relaxed font-medium max-w-lg">
                    Suaramu sangat berharga. Kami siap mendengarkan dan membantu menyelesaikan masalahmu di sekolah dengan aman dan rahasia.
                </p>
            </div>
            
            <a href="<?php echo e(route('student.complaints.create')); ?>" class="group bg-white text-elevate-peach-dark px-8 py-4 rounded-[1.5rem] font-black shadow-xl shadow-white/10 hover:bg-elevate-peach-light/20 hover:text-white transition-all flex items-center gap-3 shrink-0 active:scale-95 text-xs uppercase tracking-widest border border-white hover:border-elevate-peach">
                <i class="ph-bold ph-megaphone text-xl group-hover:rotate-12 transition-transform"></i>
                Buat Laporan
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-elevate-accent/40 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-paper-plane-tilt"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Laporan</p>
                <p class="text-3xl font-black text-elevate-dark tracking-tight"><?php echo e($complaints->count()); ?></p>
            </div>
        </div>
        
        
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-elevate-peach/40 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-elevate-peach-light/20 text-elevate-peach-dark flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-hourglass-high"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Diproses</p>
                <p class="text-3xl font-black text-elevate-dark tracking-tight"><?php echo e($complaints->where('status', 'pending')->count() + $complaints->where('status', 'investigating')->count()); ?></p>
            </div>
        </div>

        
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-6 rounded-[2rem] shadow-lg shadow-emerald-500/20 flex items-center gap-5 group hover:from-emerald-600 hover:to-teal-600 transition-all text-white border border-emerald-400/50">
            <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl group-hover:rotate-12 transition-transform shadow-inner border border-white/20">
                <i class="ph-fill ph-check-circle"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-1">Selesai</p>
                <p class="text-3xl font-black tracking-tight"><?php echo e($complaints->where('status', 'resolved')->count()); ?></p>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-black text-elevate-dark text-lg flex items-center gap-3 tracking-tight">
                <i class="ph-bold ph-list-dashes text-elevate-primary"></i> Riwayat Laporan Kamu
            </h3>
        </div>
        
        <?php if($complaints->count() > 0): ?>
            <div class="divide-y divide-slate-50">
                <?php $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-8 hover:bg-elevate-soft/30 transition-all group relative">
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <div class="flex gap-6">
                            
                            <?php
                                $catConfig = match($item->category) {
                                    'Bullying' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'icon' => 'ph-mask-sad'],
                                    'Fasilitas' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'icon' => 'ph-wrench'],
                                    'Kehilangan' => ['bg' => 'bg-elevate-peach-light/20', 'text' => 'text-elevate-peach-dark', 'border' => 'border-elevate-peach/30', 'icon' => 'ph-magnifying-glass'],
                                    default => ['bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-accent/30', 'icon' => 'ph-megaphone']
                                };
                            ?>
                            <div class="shrink-0 w-16 h-16 rounded-[1.5rem] flex items-center justify-center text-3xl border shadow-sm transition-transform group-hover:rotate-6 <?php echo e($catConfig['bg']); ?> <?php echo e($catConfig['text']); ?> <?php echo e($catConfig['border']); ?>">
                                <i class="ph-fill <?php echo e($catConfig['icon']); ?>"></i>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3 mb-2">
                                    <span class="text-sm font-black text-elevate-dark uppercase tracking-tight group-hover:text-elevate-primary transition-colors"><?php echo e($item->category); ?></span>
                                    <span class="text-[9px] font-black px-3 py-1 rounded-full bg-slate-50 text-slate-400 border border-slate-100 uppercase tracking-widest">
                                        <?php echo e($item->created_at->translatedFormat('d F Y')); ?>

                                    </span>
                                    <?php if($item->is_anonymous): ?>
                                        <span class="text-[9px] font-black px-3 py-1 rounded-full bg-elevate-dark text-white flex items-center gap-1.5 uppercase tracking-widest shadow-sm border border-slate-600">
                                            <i class="ph-fill ph-spy"></i> Anonim
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-slate-500 line-clamp-2 mb-4 font-medium leading-relaxed italic">"<?php echo e($item->description); ?>"</p>
                                
                                
                                <?php
                                    $statusStyle = match($item->status) {
                                        'pending' => 'bg-elevate-peach-light/20 text-elevate-peach-dark border-elevate-peach/30',
                                        'investigating' => 'bg-elevate-soft text-elevate-primary border-elevate-accent/30',
                                        'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        default => 'bg-slate-50 text-slate-500 border-slate-200'
                                    };
                                    $statusText = match($item->status) {
                                        'pending' => 'Menunggu Respon',
                                        'investigating' => 'Sedang Diproses',
                                        'resolved' => 'Masalah Selesai',
                                        default => 'Dibatalkan'
                                    };
                                ?>
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[9px] font-black border uppercase tracking-widest <?php echo e($statusStyle); ?> shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span> <?php echo e($statusText); ?>

                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] group-hover:text-elevate-accent transition-colors">
                                ID #<?php echo e(str_pad($item->id, 5, '0', STR_PAD_LEFT)); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="p-24 text-center flex flex-col items-center">
                <div class="w-24 h-24 bg-elevate-soft rounded-[2rem] flex items-center justify-center mb-6 border border-elevate-accent/20 shadow-sm">
                    <i class="ph-duotone ph-shield-check text-5xl text-elevate-primary"></i>
                </div>
                <h3 class="text-elevate-dark font-black text-xl tracking-tight">Sekolah Aman & Kondusif</h3>
                <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto font-medium">Belum ada laporan yang kamu buat. Terima kasih telah menjaga kedamaian di sekolah!</p>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-pengaduan.blade.php ENDPATH**/ ?>