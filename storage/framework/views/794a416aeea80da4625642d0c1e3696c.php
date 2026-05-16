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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    
    <div class="py-8 bg-slate-900 min-h-screen font-sans"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/20 text-[10px] font-bold uppercase tracking-widest text-rose-400 border border-rose-500/30 mb-3 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse shadow-[0_0_8px_rgba(244,63,94,0.8)]"></span> Live Radar Sistem
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight flex items-center gap-3">
                        <i class="ph-fill ph-radar text-elevate-accent"></i> Early Warning System
                    </h1>
                    <p class="text-slate-400 text-sm mt-2 max-w-2xl">Daftar kasus siswa yang ditangkap otomatis oleh sistem berdasarkan akumulasi absen dan poin kedisiplinan. Segera tindak lanjuti.</p>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.bk.index')); ?>" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-bold rounded-xl border border-slate-700 transition-all flex items-center gap-2 shadow-lg">
                        <i class="ph-bold ph-list-dashes"></i> Lihat Semua Antrean BK
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                
                <div class="space-y-4">
                    <h3 class="text-lg md:text-xl font-bold text-rose-400 flex items-center justify-between border-b border-rose-900/50 pb-3">
                        <span class="flex items-center gap-2"><i class="ph-fill ph-warning text-2xl"></i> Kasus Kritis (Butuh Tindakan)</span>
                        <span class="bg-rose-500 text-white text-xs px-2 py-0.5 rounded-md"><?php echo e($criticalViolations->count()); ?></span>
                    </h3>
                    
                    <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2 custom-scrollbar">
                        <?php $__empty_1 = true; $__currentLoopData = $criticalViolations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="bg-slate-800 rounded-[2rem] p-6 border border-rose-900/50 hover:border-rose-500/50 transition-colors shadow-lg relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity">
                                    <i class="ph-fill ph-siren text-8xl text-rose-500"></i>
                                </div>

                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 font-bold text-xl border-2 border-slate-600">
                                            <?php echo e(substr($cv->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white text-lg"><?php echo e($cv->student->name); ?></h4>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                <span class="bg-slate-700 px-2 py-0.5 rounded text-slate-300 mr-1">Kelas <?php echo e($cv->student->schoolClass->name ?? '-'); ?></span> 
                                                NISN: <?php echo e($cv->student->student_id); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <span class="bg-rose-500/20 text-rose-400 px-3 py-1.5 rounded-xl text-[10px] font-black border border-rose-500/30 uppercase tracking-widest animate-pulse shadow-sm">
                                        Urgent
                                    </span>
                                </div>
                                
                                <div class="bg-slate-900/80 p-4 rounded-2xl border border-slate-700/50 text-sm text-rose-200 font-mono mb-5 shadow-inner relative z-10">
                                    <i class="ph-fill ph-robot text-rose-500 mr-1"></i> <?php echo nl2br(e($cv->initial_message)); ?>

                                </div>

                                <div class="relative z-10">
                                    <a href="<?php echo e(route('admin.bk.show', $cv->id)); ?>" class="w-full flex items-center justify-center gap-2 py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-rose-900/50 active:scale-95">
                                        <i class="ph-bold ph-shield-check text-lg"></i> Proses Kasus Ini
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="bg-slate-800/50 rounded-[2.5rem] p-10 border border-slate-700/50 text-center border-dashed">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-500/10 rounded-full mb-4">
                                    <i class="ph-duotone ph-shield-check text-5xl text-emerald-500"></i>
                                </div>
                                <p class="text-white font-bold text-lg">Aman Terkendali</p>
                                <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto">Sistem tidak mendeteksi adanya siswa dengan pelanggaran ekstrem (Minus > 200) saat ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="space-y-4">
                    <h3 class="text-lg md:text-xl font-bold text-emerald-400 flex items-center justify-between border-b border-emerald-900/50 pb-3">
                        <span class="flex items-center gap-2"><i class="ph-fill ph-medal text-2xl"></i> Layak Diapresiasi</span>
                        <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-md"><?php echo e($meritsToAppreciate->count()); ?></span>
                    </h3>
                    
                    <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2 custom-scrollbar">
                        <?php $__empty_1 = true; $__currentLoopData = $meritsToAppreciate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="bg-slate-800 rounded-[2rem] p-6 border border-emerald-900/50 hover:border-emerald-500/50 transition-colors shadow-lg relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity">
                                    <i class="ph-fill ph-star text-8xl text-emerald-500"></i>
                                </div>

                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 font-bold text-xl border-2 border-slate-600">
                                            <?php echo e(substr($ma->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white text-lg"><?php echo e($ma->student->name); ?></h4>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                <span class="bg-slate-700 px-2 py-0.5 rounded text-slate-300 mr-1">Kelas <?php echo e($ma->student->schoolClass->name ?? '-'); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded-xl text-[10px] font-black border border-emerald-500/30 uppercase tracking-widest shadow-sm">
                                        Reward
                                    </span>
                                </div>
                                
                                <div class="bg-slate-900/80 p-4 rounded-2xl border border-slate-700/50 text-sm text-emerald-200 font-mono mb-5 shadow-inner relative z-10">
                                    <i class="ph-fill ph-robot text-emerald-500 mr-1"></i> <?php echo nl2br(e($ma->initial_message)); ?>

                                </div>

                                <div class="relative z-10">
                                    <a href="<?php echo e(route('admin.bk.show', $ma->id)); ?>" class="w-full flex items-center justify-center gap-2 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-emerald-900/50 active:scale-95">
                                        <i class="ph-bold ph-gift text-lg"></i> Berikan Reward / Selesai
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="bg-slate-800/50 rounded-[2.5rem] p-10 border border-slate-700/50 text-center border-dashed">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-700/50 rounded-full mb-4">
                                    <i class="ph-duotone ph-star text-5xl text-slate-500"></i>
                                </div>
                                <p class="text-white font-bold text-lg">Belum Ada Target</p>
                                <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto">Belum ada siswa yang mencapai batas poin prestasi (+100) untuk diapresiasi otomatis oleh sistem.</p>
                            </div>
                        <?php endif; ?>
                    </div>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/bk/early_warning.blade.php ENDPATH**/ ?>