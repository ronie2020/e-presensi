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
        @keyframes bounce-slow { 0%, 100% { transform: translateY(-5%); } 50% { transform: translateY(0); } }
        .animate-podium { animation: bounce-slow 3s ease-in-out infinite; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
    </style>

    <div class="p-6 md:p-10 space-y-8 min-h-screen bg-slate-50 font-sans text-slate-800">
        
        
        <div class="relative rounded-[3rem] bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-600 p-8 md:p-12 text-white shadow-2xl overflow-hidden border border-white/10">
            <div class="absolute -top-10 -right-10 opacity-10 rotate-12">
                <i class="ph-fill ph-trophy text-[200px]"></i>
            </div>            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 border border-white/10 text-emerald-50 text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                        <i class="ph-fill ph-star"></i> Fastabiqul Khairat
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black mb-3 tracking-tight">Papan Peringkat Kebaikan</h1>
                    <p class="text-emerald-50/80 max-w-xl text-sm md:text-base leading-relaxed">
                        Daftar siswa paling aktif yang menginspirasi dalam menjalankan ibadah harian selama bulan suci Ramadhan.
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/10 text-center min-w-[120px]">
                        <div class="text-2xl font-black"><?php echo e($topStudents->count()); ?></div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-emerald-200">Peserta Aktif</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/10 text-center min-w-[120px]">
                        <div class="text-2xl font-black"><?php echo e(number_format($topStudents->sum('points'), 0, ',', '.')); ?></div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-emerald-200">Total Poin</div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($topStudents->isNotEmpty()): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end max-w-5xl mx-auto py-10 min-h-[350px]">
            
            
            <div class="order-2 md:order-1 flex flex-col items-center <?php echo e(!isset($topStudents[1]) ? 'invisible md:visible opacity-0' : ''); ?>">
                <?php if(isset($topStudents[1])): ?>
                <div class="relative mb-4">
                    <div class="w-20 h-20 rounded-full border-4 border-slate-300 overflow-hidden shadow-lg bg-white">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topStudents[1]->name)); ?>&background=cbd5e1&color=64748b" alt="Runner up" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-300 text-slate-700 w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md border-2 border-white">2</div>
                </div>
                <div class="bg-white glass-card p-6 rounded-t-[2.5rem] w-full text-center border-x border-t border-slate-100 shadow-xl h-40 flex flex-col justify-center">
                    <h4 class="font-black text-slate-800 line-clamp-1"><?php echo e($topStudents[1]->name); ?></h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2"><?php echo e($topStudents[1]->schoolClass->name ?? 'Kelas'); ?></p>
                    <div class="text-emerald-600 font-black text-xl"><?php echo e(number_format($topStudents[1]->points, 0, ',', '.')); ?> pts</div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="order-1 md:order-2 flex flex-col items-center scale-110 md:-translate-y-4">
                <?php if(isset($topStudents[0])): ?>
                <div class="relative mb-6">
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 animate-podium">
                        <i class="ph-fill ph-crown text-amber-400 text-5xl drop-shadow-lg"></i>
                    </div>
                    <div class="w-28 h-28 rounded-full border-4 border-amber-400 overflow-hidden shadow-2xl bg-white ring-8 ring-amber-400/20">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topStudents[0]->name)); ?>&background=fbbf24&color=78350f" alt="Winner" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-amber-400 text-white w-10 h-10 rounded-full flex items-center justify-center font-black shadow-lg border-2 border-white text-lg">1</div>
                </div>
                <div class="bg-white glass-card p-8 rounded-t-[3rem] w-full text-center border-x border-t border-amber-100 shadow-2xl h-52 flex flex-col justify-center ring-1 ring-amber-100">
                    <h4 class="font-black text-slate-900 text-lg line-clamp-1"><?php echo e($topStudents[0]->name); ?></h4>
                    <p class="text-[10px] font-bold text-amber-500 uppercase mb-3 tracking-widest">Sultan Ibadah</p>
                    <div class="bg-emerald-600 text-white px-4 py-1.5 rounded-full inline-block text-xl font-black shadow-lg shadow-emerald-200">
                        <?php echo e(number_format($topStudents[0]->points, 0, ',', '.')); ?> pts
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="order-3 flex flex-col items-center <?php echo e(!isset($topStudents[2]) ? 'invisible md:visible opacity-0' : ''); ?>">
                <?php if(isset($topStudents[2])): ?>
                <div class="relative mb-4">
                    <div class="w-20 h-20 rounded-full border-4 border-amber-600/30 overflow-hidden shadow-lg bg-white">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topStudents[2]->name)); ?>&background=d97706&color=ffffff" alt="3rd place" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-amber-700 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md border-2 border-white">3</div>
                </div>
                <div class="bg-white glass-card p-6 rounded-t-[2.5rem] w-full text-center border-x border-t border-slate-100 shadow-xl h-32 flex flex-col justify-center">
                    <h4 class="font-black text-slate-800 line-clamp-1"><?php echo e($topStudents[2]->name); ?></h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2"><?php echo e($topStudents[2]->schoolClass->name ?? 'Kelas'); ?></p>
                    <div class="text-emerald-600 font-black text-xl"><?php echo e(number_format($topStudents[2]->points, 0, ',', '.')); ?> pts</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
            
            <div class="text-center py-24">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph-duotone ph-users text-4xl text-slate-400"></i>
                </div>
                <h3 class="font-bold text-slate-600">Belum ada data peringkat</h3>
                <p class="text-slate-400 text-sm">Data akan muncul setelah siswa mengisi jurnal.</p>
            </div>
        <?php endif; ?>

        
        <div class="max-w-4xl mx-auto bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-black text-slate-800 uppercase tracking-wider text-xs flex items-center gap-2">
                    <i class="ph-bold ph-list-numbers text-emerald-600"></i> Daftar Peringkat Lainnya
                </h3>
            </div>
            
            <div class="divide-y divide-slate-50">
                <?php $__empty_1 = true; $__currentLoopData = $topStudents->slice(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group flex items-center justify-between p-5 hover:bg-emerald-50/30 transition-all cursor-default">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 font-black text-sm flex items-center justify-center border border-slate-200 group-hover:bg-white group-hover:text-emerald-600 group-hover:border-emerald-200 transition-colors">
                            #<?php echo e($index + 4); ?>

                        </div>
                        <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($student->name)); ?>&background=f1f5f9&color=64748b" alt="avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="text-sm font-black text-slate-800 group-hover:text-emerald-700 transition-colors"><?php echo e($student->name); ?></div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase"><?php echo e($student->schoolClass->name ?? 'Tanpa Kelas'); ?></div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-sm font-black text-emerald-600"><?php echo e(number_format($student->points, 0, ',', '.')); ?> pts</div>
                        <div class="w-24 bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                            <?php $percent = ($student->points / ($topStudents[0]->points ?: 1)) * 100; ?>
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000" style="width: <?php echo e($percent); ?>%"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-20 text-center text-slate-300">
                    <i class="ph-duotone ph-magnifying-glass text-6xl mb-4 opacity-20"></i>
                    <p class="font-bold">Belum ada peringkat tambahan.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-6">
            <div class="flex-1 bg-blue-50 p-6 rounded-3xl border border-blue-100 flex items-start gap-4">
                <div class="p-3 bg-white rounded-2xl text-blue-600 shadow-sm"><i class="ph-fill ph-lightning text-xl"></i></div>
                <div>
                    <h5 class="font-black text-blue-900 text-sm mb-1">Cara Mendapat Poin?</h5>
                    <p class="text-xs text-blue-700/70 leading-relaxed">
                        Poin dihitung dari setiap jurnal harian yang kamu simpan. Pastikan semua ibadah wajib dan sunnah terisi setiap harinya!
                    </p>
                </div>
            </div>
            <div class="flex-1 bg-purple-50 p-6 rounded-3xl border border-purple-100 flex items-start gap-4">
                <div class="p-3 bg-white rounded-2xl text-purple-600 shadow-sm"><i class="ph-fill ph-gift text-xl"></i></div>
                <div>
                    <h5 class="font-black text-purple-900 text-sm mb-1">Hadiah Menanti!</h5>
                    <p class="text-xs text-purple-700/70 leading-relaxed">
                        Tiga peringkat teratas di akhir Ramadhan akan mendapatkan apresiasi khusus dari sekolah sebagai apresiasi ketaqwaan.
                    </p>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\ramadan\leaderboard.blade.php ENDPATH**/ ?>