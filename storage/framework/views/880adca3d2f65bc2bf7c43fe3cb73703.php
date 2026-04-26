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
        
        /* Microsoft Fluent Elevation Shadows */
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
    </style>

    <div class="p-6 md:p-10 space-y-8 min-h-screen bg-[#f8fafc] font-sans text-[#2A3B52]">
        
        
        <div class="relative rounded-[2rem] md:rounded-[3rem] bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 md:p-12 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none z-0"></div>
            
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-[400px] h-[400px] bg-white/30 rounded-full blur-[80px] z-0"></div>
            <div class="absolute -top-10 -right-10 opacity-10 rotate-12 z-0 pointer-events-none">
                <i class="ph-fill ph-trophy text-[200px] text-[#2A3B52]"></i>
            </div>            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                        <i class="ph-fill ph-star text-[#D83B01]"></i> Fastabiqul Khairat
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight text-[#2A3B52]">Papan Peringkat Kebaikan</h1>
                    <p class="text-[#2A3B52]/80 max-w-xl text-sm md:text-base font-medium leading-relaxed">
                        Daftar siswa paling aktif yang menginspirasi dalam menjalankan ibadah harian selama bulan suci Ramadhan.
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="bg-white/40 backdrop-blur-md p-5 rounded-3xl border border-white/50 text-center min-w-[130px] shadow-sm">
                        <div class="text-3xl font-black text-[#2A3B52]"><?php echo e($topStudents->count()); ?></div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-[#2A3B52]/70 mt-1">Peserta Aktif</div>
                    </div>
                    <div class="bg-white/40 backdrop-blur-md p-5 rounded-3xl border border-white/50 text-center min-w-[130px] shadow-sm">
                        <div class="text-3xl font-black text-[#2A3B52]"><?php echo e(number_format($topStudents->sum('points'), 0, ',', '.')); ?></div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-[#2A3B52]/70 mt-1">Total Poin</div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($topStudents->isNotEmpty()): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end max-w-5xl mx-auto py-10 min-h-[350px]">
            
            
            <div class="order-2 md:order-1 flex flex-col items-center <?php echo e(!isset($topStudents[1]) ? 'invisible md:visible opacity-0' : ''); ?>">
                <?php if(isset($topStudents[1])): ?>
                <div class="relative mb-5">
                    <div class="w-24 h-24 rounded-full border-4 border-[#D0E7F8] overflow-hidden shadow-lg bg-white p-1">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topStudents[1]->name)); ?>&background=5295FF&color=ffffff&bold=true" alt="Runner up" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-[#5295FF] text-white w-8 h-8 rounded-full flex items-center justify-center font-black shadow-md border-2 border-white text-sm">2</div>
                </div>
                <div class="bg-white fluent-card p-6 rounded-[2rem] rounded-b-xl w-full text-center h-44 flex flex-col justify-center">
                    <h4 class="font-bold text-[#2A3B52] text-lg line-clamp-1"><?php echo e($topStudents[1]->name); ?></h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 mt-1"><?php echo e($topStudents[1]->schoolClass->name ?? 'Kelas'); ?></p>
                    <div class="text-[#5295FF] font-black text-2xl"><?php echo e(number_format($topStudents[1]->points, 0, ',', '.')); ?> <span class="text-xs font-bold text-slate-400">pts</span></div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="order-1 md:order-2 flex flex-col items-center scale-110 md:-translate-y-6 z-10">
                <?php if(isset($topStudents[0])): ?>
                <div class="relative mb-6">
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 animate-podium">
                        <i class="ph-fill ph-crown text-[#D83B01] text-6xl drop-shadow-md"></i>
                    </div>
                    <div class="w-32 h-32 rounded-full border-4 border-[#FFD8A8] overflow-hidden shadow-xl bg-white ring-8 ring-[#FFEFD6] p-1.5">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topStudents[0]->name)); ?>&background=D83B01&color=ffffff&bold=true" alt="Winner" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-[#D83B01] text-white w-10 h-10 rounded-full flex items-center justify-center font-black shadow-lg border-2 border-white text-lg">1</div>
                </div>
                <div class="bg-white fluent-card p-8 rounded-[2.5rem] rounded-b-xl w-full text-center h-56 flex flex-col justify-center border-t border-[#FFD8A8]">
                    <h4 class="font-black text-[#2A3B52] text-xl line-clamp-1"><?php echo e($topStudents[0]->name); ?></h4>
                    <p class="text-[10px] font-bold text-[#D83B01] uppercase mb-4 mt-1 tracking-widest">Sultan Ibadah</p>
                    <div class="bg-[#D83B01] text-white px-5 py-2 rounded-xl inline-block text-2xl font-black shadow-md">
                        <?php echo e(number_format($topStudents[0]->points, 0, ',', '.')); ?> <span class="text-xs font-bold text-orange-200">pts</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="order-3 flex flex-col items-center <?php echo e(!isset($topStudents[2]) ? 'invisible md:visible opacity-0' : ''); ?>">
                <?php if(isset($topStudents[2])): ?>
                <div class="relative mb-5">
                    <div class="w-24 h-24 rounded-full border-4 border-[#B7DFB9] overflow-hidden shadow-lg bg-white p-1">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topStudents[2]->name)); ?>&background=107C10&color=ffffff&bold=true" alt="3rd place" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-[#107C10] text-white w-8 h-8 rounded-full flex items-center justify-center font-black shadow-md border-2 border-white text-sm">3</div>
                </div>
                <div class="bg-white fluent-card p-6 rounded-[2rem] rounded-b-xl w-full text-center h-44 flex flex-col justify-center">
                    <h4 class="font-bold text-[#2A3B52] text-lg line-clamp-1"><?php echo e($topStudents[2]->name); ?></h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 mt-1"><?php echo e($topStudents[2]->schoolClass->name ?? 'Kelas'); ?></p>
                    <div class="text-[#107C10] font-black text-2xl"><?php echo e(number_format($topStudents[2]->points, 0, ',', '.')); ?> <span class="text-xs font-bold text-slate-400">pts</span></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
            
            <div class="text-center py-24 bg-white fluent-card rounded-[2.5rem] max-w-4xl mx-auto">
                <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-sm">
                    <i class="ph-duotone ph-users text-4xl text-slate-400"></i>
                </div>
                <h3 class="font-bold text-[#2A3B52] text-lg">Belum ada data peringkat</h3>
                <p class="text-slate-500 text-sm mt-1">Data akan muncul setelah siswa mengisi jurnal secara aktif.</p>
            </div>
        <?php endif; ?>

        
        <div class="max-w-4xl mx-auto bg-white rounded-[2rem] fluent-card overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
                <h3 class="font-black text-[#2A3B52] uppercase tracking-wider text-xs flex items-center gap-2">
                    <i class="ph-bold ph-list-numbers text-[#5295FF]"></i> Daftar Peringkat Lainnya
                </h3>
            </div>
            
            <div class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $topStudents->slice(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group flex items-center justify-between p-5 hover:bg-slate-50/50 transition-colors cursor-default">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 font-black text-sm flex items-center justify-center border border-slate-200 group-hover:bg-[#F3F9FD] group-hover:text-[#5295FF] group-hover:border-[#D0E7F8] transition-colors shadow-sm">
                            #<?php echo e($index + 4); ?>

                        </div>
                        <div class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 shadow-sm">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($student->name)); ?>&background=f1f5f9&color=2A3B52&bold=true" alt="avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="text-sm font-bold text-[#2A3B52] group-hover:text-[#5295FF] transition-colors"><?php echo e($student->name); ?></div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"><?php echo e($student->schoolClass->name ?? 'Tanpa Kelas'); ?></div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-base font-black text-[#5295FF]"><?php echo e(number_format($student->points, 0, ',', '.')); ?> <span class="text-[10px] text-slate-400 font-bold">pts</span></div>
                        <div class="w-24 bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden shadow-inner border border-slate-200/50">
                            <?php $percent = ($student->points / ($topStudents[0]->points ?: 1)) * 100; ?>
                            <div class="bg-[#5295FF] h-full rounded-full transition-all duration-1000" style="width: <?php echo e($percent); ?>%"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-20 text-center text-slate-400">
                    <i class="ph-duotone ph-magnifying-glass text-5xl mb-4 opacity-50"></i>
                    <p class="font-bold text-sm">Belum ada peringkat tambahan.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-6">
            <div class="flex-1 bg-[#F3F9FD] p-6 rounded-[2rem] border border-[#D0E7F8] fluent-card flex items-start gap-4">
                <div class="p-3 bg-white rounded-xl text-[#5295FF] shadow-sm border border-[#D0E7F8]"><i class="ph-fill ph-lightning text-xl"></i></div>
                <div>
                    <h5 class="font-bold text-[#2A3B52] text-sm mb-1">Cara Mendapat Poin?</h5>
                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                        Poin dihitung dari setiap jurnal harian yang dinilai oleh guru. Pastikan semua ibadah wajib dan sunnah terisi dengan lengkap dan jujur!
                    </p>
                </div>
            </div>
            <div class="flex-1 bg-[#FFEFD6] p-6 rounded-[2rem] border border-[#FFD8A8] fluent-card flex items-start gap-4">
                <div class="p-3 bg-white rounded-xl text-[#D83B01] shadow-sm border border-[#FFD8A8]"><i class="ph-fill ph-gift text-xl"></i></div>
                <div>
                    <h5 class="font-bold text-[#2A3B52] text-sm mb-1">Apresiasi Kebaikan!</h5>
                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                        Tiga peringkat teratas di akhir Ramadhan akan mendapatkan apresiasi khusus dari sekolah sebagai bentuk penghargaan ketaqwaan.
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ramadan/leaderboard.blade.php ENDPATH**/ ?>