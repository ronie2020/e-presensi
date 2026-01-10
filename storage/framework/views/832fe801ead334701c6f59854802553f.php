<?php $__env->startSection('content'); ?>
    
    <?php \Carbon\Carbon::setLocale('id'); ?>

    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="font-jakarta max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        <div class="space-y-8">
            
            
            <div class="animate-enter relative rounded-[3rem] bg-gradient-to-br from-rose-900 via-slate-900 to-black p-8 md:p-12 mb-8 text-white shadow-2xl shadow-rose-900/30 overflow-hidden border border-white/10">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[400px] h-[400px] bg-rose-500/20 rounded-full blur-[100px] opacity-40"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <a href="<?php echo e(route('portal.index')); ?>" class="inline-flex items-center gap-2 text-rose-300 hover:text-white transition-colors mb-6 text-[10px] font-black uppercase tracking-[0.2em]">
                            <i class="ph-bold ph-arrow-left"></i> Dashboard Utama
                        </a>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/20 border border-rose-400/30 text-rose-100 text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-md">
                            <i class="ph-fill ph-shield-warning text-sm"></i> Zona Aman Bercerita
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter mb-4 leading-none">Layanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-300 to-pink-200">Pengaduan</span></h1>
                        <p class="text-rose-100/60 text-sm md:text-base leading-relaxed font-medium">
                            Suaramu sangat berharga. Kami siap mendengarkan dan membantu menyelesaikan masalahmu di sekolah dengan aman dan rahasia.
                        </p>
                    </div>
                    
                    <a href="<?php echo e(route('student.complaints.create')); ?>" class="group bg-white text-rose-600 px-8 py-4 rounded-[1.5rem] font-black shadow-xl shadow-rose-900/20 hover:bg-rose-50 transition-all flex items-center gap-3 shrink-0 active:scale-95 text-xs uppercase tracking-widest">
                        <i class="ph-bold ph-megaphone text-xl group-hover:rotate-12 transition-transform"></i>
                        Buat Laporan
                    </a>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-enter" style="animation-delay: 100ms">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-blue-100 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Laporan</p>
                        <p class="text-3xl font-black text-slate-800 tracking-tight"><?php echo e($complaints->count()); ?></p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-amber-100 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-hourglass-high"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Diproses</p>
                        <p class="text-3xl font-black text-slate-800 tracking-tight"><?php echo e($complaints->where('status', 'pending')->count()); ?></p>
                    </div>
                </div>

                <div class="bg-emerald-600 p-6 rounded-[2rem] shadow-xl shadow-emerald-900/10 flex items-center gap-5 group hover:bg-emerald-700 transition-all border border-emerald-500/20 text-white">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl group-hover:rotate-12 transition-transform shadow-inner">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-200 uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-3xl font-black tracking-tight"><?php echo e($complaints->where('status', 'resolved')->count()); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-enter" style="animation-delay: 200ms">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-3 tracking-tight">
                        <i class="ph-bold ph-list-dashes text-blue-600"></i> Riwayat Laporan Kamu
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
                                            default => 'bg-blue-50 text-blue-600 border-blue-100'
                                        };
                                        $catIcon = match($item->category) {
                                            'Bullying' => 'ph-mask-sad',
                                            'Fasilitas' => 'ph-wrench',
                                            'Kehilangan' => 'ph-magnifying-glass',
                                            default => 'ph-megaphone'
                                        };
                                    ?>
                                    <div class="shrink-0 w-16 h-16 rounded-[1.5rem] flex items-center justify-center text-3xl border <?php echo e($catColor); ?> shadow-inner transition-transform group-hover:rotate-6">
                                        <i class="ph-fill <?php echo e($catIcon); ?>"></i>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-3 mb-2">
                                            <span class="text-sm font-black text-slate-800 uppercase tracking-tight"><?php echo e($item->category); ?></span>
                                            <span class="text-[9px] font-black px-3 py-1 rounded-full bg-slate-100 text-slate-400 uppercase tracking-widest">
                                                <?php echo e($item->created_at->translatedFormat('d F Y')); ?>

                                            </span>
                                            <?php if($item->is_anonymous): ?>
                                                <span class="text-[9px] font-black px-3 py-1 rounded-full bg-slate-900 text-white flex items-center gap-1.5 uppercase tracking-widest shadow-sm">
                                                    <i class="ph-fill ph-spy"></i> Anonim
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 font-medium leading-relaxed italic">"<?php echo e($item->description); ?>"</p>
                                        
                                        
                                        <?php
                                            $statusStyle = match($item->status) {
                                                'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                'investigating' => 'bg-blue-50 text-blue-600 border-blue-200',
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
                                    <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] group-hover:text-blue-600 transition-colors">
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
                            <i class="ph-duotone ph-shield-check text-5xl text-slate-200"></i>
                        </div>
                        <h3 class="text-slate-800 font-black text-xl tracking-tight">Sekolah Aman & Kondusif</h3>
                        <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto font-medium">Belum ada laporan yang kamu buat. Terima kasih telah menjaga kedamaian di sekolah!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/complaints/index.blade.php ENDPATH**/ ?>