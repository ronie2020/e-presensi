<?php $__env->startSection('content'); ?>
    
    <?php \Carbon\Carbon::setLocale('id'); ?>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24 relative z-10">
        
        <div class="space-y-8">
            
            
            <div class="animate-enter relative rounded-[3rem] bg-elevate-gradient-main p-8 md:p-12 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[400px] h-[400px] bg-white/40 rounded-full blur-[80px] opacity-60"></div>
                <div class="absolute -bottom-20 -left-10 w-64 h-64 bg-elevate-peach/30 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">                        
                        <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>" class="inline-flex items-center gap-2 text-slate-500 hover:text-elevate-primary transition-colors mb-6 text-[10px] font-black uppercase tracking-[0.2em]">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke profil
                        </a>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/60 border border-white text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-md shadow-sm">
                            <i class="ph-fill ph-shield-check text-elevate-primary text-sm"></i> Zona Aman Bercerita
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter mb-4 leading-tight text-elevate-dark">
                            Layanan <br class="md:hidden"><span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-accent">Pengaduan Siswa</span>
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base leading-relaxed font-medium">
                            Suaramu sangat berharga. Kami siap mendengarkan dan membantu menyelesaikan masalahmu di sekolah dengan aman dan rahasia.
                        </p>
                    </div>
                    
                    <a href="<?php echo e(route('student.complaints.create')); ?>" class="group bg-elevate-dark text-white px-8 py-4 rounded-[1.5rem] font-black shadow-xl shadow-elevate-dark/20 hover:bg-elevate-primary transition-all flex items-center gap-3 shrink-0 active:scale-95 text-xs uppercase tracking-widest">
                        <i class="ph-bold ph-megaphone text-xl group-hover:scale-110 transition-transform"></i>
                        Buat Laporan
                    </a>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-enter" style="animation-delay: 100ms">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-elevate-accent/50 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-elevate-accent/10 text-elevate-primary flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Laporan</p>
                        <p class="text-3xl font-black text-elevate-dark tracking-tight"><?php echo e($complaints->count()); ?></p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-amber-200 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-hourglass-high"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Diproses</p>
                        <p class="text-3xl font-black text-elevate-dark tracking-tight"><?php echo e($complaints->where('status', 'pending')->count()); ?></p>
                    </div>
                </div>

                <div class="bg-emerald-500 p-6 rounded-[2rem] shadow-xl shadow-emerald-500/20 flex items-center gap-5 group hover:bg-emerald-600 transition-all border border-emerald-400 text-white">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-3xl font-black tracking-tight"><?php echo e($complaints->where('status', 'resolved')->count()); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-enter" style="animation-delay: 200ms">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-3 tracking-tight">
                        <i class="ph-bold ph-list-dashes text-elevate-primary"></i> Riwayat Laporan Kamu
                    </h3>
                </div>
                
                <?php if($complaints->count() > 0): ?>
                    <div class="divide-y divide-slate-50">
                        <?php $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-8 hover:bg-slate-50/50 transition-all group relative">
                            <div class="flex flex-col md:flex-row justify-between gap-6">
                                <div class="flex gap-6">
                                    
                                    <?php
                                        $catColor = match($item->category) {
                                            'Bullying' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            'Fasilitas' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            'Kehilangan' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            default => 'bg-elevate-accent/10 text-elevate-primary border-elevate-accent/20'
                                        };
                                        $catIcon = match($item->category) {
                                            'Bullying' => 'ph-mask-sad',
                                            'Fasilitas' => 'ph-wrench',
                                            'Kehilangan' => 'ph-magnifying-glass',
                                            default => 'ph-megaphone'
                                        };
                                    ?>
                                    <div class="shrink-0 w-16 h-16 rounded-[1.5rem] flex items-center justify-center text-3xl border <?php echo e($catColor); ?> shadow-inner transition-transform group-hover:scale-110">
                                        <i class="ph-fill <?php echo e($catIcon); ?>"></i>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-3 mb-2">
                                            <span class="text-sm font-black text-elevate-dark uppercase tracking-tight"><?php echo e($item->category); ?></span>
                                            <span class="text-[9px] font-black px-3 py-1 rounded-full bg-slate-100 text-slate-500 uppercase tracking-widest">
                                                <?php echo e($item->created_at->translatedFormat('d F Y')); ?>

                                            </span>
                                            <?php if($item->is_anonymous): ?>
                                                <span class="text-[9px] font-black px-3 py-1 rounded-full bg-elevate-dark text-white flex items-center gap-1.5 uppercase tracking-widest shadow-sm">
                                                    <i class="ph-fill ph-spy"></i> Anonim
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 font-medium leading-relaxed italic">"<?php echo e($item->description); ?>"</p>
                                        
                                        
                                        <?php
                                            $statusStyle = match($item->status) {
                                                'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                'investigating' => 'bg-elevate-accent/10 text-elevate-primary border-elevate-accent/20',
                                                'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                default => 'bg-slate-50 text-slate-600 border-slate-200'
                                            };
                                            $statusText = match($item->status) {
                                                'pending' => 'Menunggu Respon',
                                                'investigating' => 'Sedang Diproses',
                                                'resolved' => 'Masalah Selesai',
                                                default => 'Dibatalkan'
                                            };
                                        ?>
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[9px] font-black border uppercase tracking-widest <?php echo e($statusStyle); ?>">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span> <?php echo e($statusText); ?>

                                        </span>
                                    </div>
                                </div>

                                
                                <div class="flex items-center justify-end">
                                    <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] group-hover:text-elevate-primary transition-colors">
                                        ID #<?php echo e(str_pad($item->id, 5, '0', STR_PAD_LEFT)); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="p-24 text-center flex flex-col items-center">
                        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-100 shadow-inner">
                            <i class="ph-duotone ph-shield-check text-5xl text-slate-300"></i>
                        </div>
                        <h3 class="text-elevate-dark font-black text-xl tracking-tight">Sekolah Aman & Kondusif</h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto font-medium">Belum ada laporan yang kamu buat. Terima kasih telah menjaga kedamaian di sekolah!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/complaints/index.blade.php ENDPATH**/ ?>