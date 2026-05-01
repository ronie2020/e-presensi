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
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900&display=swap');
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
    </style>

    <div class="min-h-screen bg-[#f8fafc] font-jakarta p-4 md:p-8 pb-20 text-[#2A3B52]">
        
        
        <div class="relative rounded-2xl md:rounded-[3rem] bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 md:p-12 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden text-center mb-10 md:mb-16 border border-white/40">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay pointer-events-none z-0"></div>
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-[300px] h-[300px] bg-white/40 rounded-full blur-[80px] pointer-events-none z-0"></div>

            <div class="relative z-10">
                <a href="<?php echo e(route('teacher.habits.index')); ?>" class="absolute top-0 left-0 bg-white/40 hover:bg-white/60 px-4 py-2.5 rounded-xl text-xs font-bold backdrop-blur-md transition-colors flex items-center gap-2 border border-white/50 text-[#2A3B52] shadow-sm">
                    <i class="ph-bold ph-arrow-left"></i> Kembali
                </a>
                
                <div class="w-20 h-20 bg-white/40 rounded-2xl flex items-center justify-center mx-auto mb-6 backdrop-blur-md shadow-sm border border-white/50 text-[#D83B01]">
                    <i class="ph-fill ph-crown text-4xl"></i>
                </div>
                <h1 class="text-3xl md:text-5xl font-black mb-3 tracking-tight text-[#2A3B52]">Papan Kehormatan Siswa</h1>
                
                <p class="text-[#2A3B52]/80 font-medium text-sm md:text-base max-w-xl mx-auto leading-relaxed mb-6">
                    Daftar apresiasi siswa paling konsisten dalam membangun kebiasaan baik pada bulan <span class="font-bold text-[#2A3B52]"><?php echo e(\Carbon\Carbon::parse($filterMonth . '-01')->translatedFormat('F Y')); ?></span>.
                </p>

                
                <form action="<?php echo e(route('teacher.habits.leaderboard')); ?>" method="GET" class="flex justify-center">
                    <div class="relative bg-white/50 hover:bg-white/70 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/60 shadow-sm flex items-center gap-3 transition-all cursor-pointer group">
                        <i class="ph-bold ph-calendar text-[#2A3B52]"></i>
                        <input type="month" name="month" value="<?php echo e($filterMonth); ?>" 
                               onchange="this.form.submit()" 
                               class="bg-transparent border-none text-sm font-black text-[#2A3B52] focus:ring-0 p-0 m-0 cursor-pointer uppercase tracking-wider">
                        <i class="ph-bold ph-caret-down text-[#2A3B52]/50 group-hover:text-[#2A3B52]"></i>
                    </div>
                </form>
            </div>
        </div>

        <?php if($leaderboard->isEmpty()): ?>
            <div class="text-center py-20 bg-white rounded-[2rem] border border-slate-100 shadow-sm max-w-3xl mx-auto fluent-card">
                <div class="w-20 h-20 bg-slate-50 text-[#5295FF] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100"><i class="ph-duotone ph-ghost text-4xl"></i></div>
                <h3 class="text-lg font-bold text-[#2A3B52]">Belum Ada Data</h3>
                <p class="text-slate-500 text-sm">Belum ada siswa yang terekam pada bulan <span class="font-bold"><?php echo e(\Carbon\Carbon::parse($filterMonth . '-01')->translatedFormat('F Y')); ?></span>.</p>
            </div>
        <?php else: ?>
            <div class="max-w-4xl mx-auto">
                
                
                <div class="flex flex-col md:flex-row items-end justify-center gap-4 md:gap-6 mb-12 px-2">
                    <?php $__currentLoopData = $leaderboard->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $top): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $rank = $index + 1;
                            $orderClass = $rank == 1 ? 'order-1 md:order-2 z-20' : ($rank == 2 ? 'order-2 md:order-1 z-10' : 'order-3 md:order-3 z-0');
                            $heightClass = $rank == 1 ? 'md:h-64 h-56' : ($rank == 2 ? 'md:h-56 h-48' : 'md:h-48 h-40');
                            
                            $ringColor = $rank == 1 ? 'border-[#FFD8A8] bg-[#FFEFD6]' : ($rank == 2 ? 'border-slate-300 bg-slate-50' : 'border-[#D0E7F8] bg-[#F3F9FD]');
                            $medalColor = $rank == 1 ? 'text-[#D83B01]' : ($rank == 2 ? 'text-slate-500' : 'text-[#5295FF]');
                            $avatarBg = $rank == 1 ? 'D83B01' : ($rank == 2 ? 'cbd5e1' : '5295FF');
                        ?>
                        
                        <div class="w-full md:w-1/3 flex flex-col items-center <?php echo e($orderClass); ?> transform transition-transform hover:-translate-y-2">
                            <div class="relative mb-4 group cursor-pointer">
                                <?php if($rank == 1): ?>
                                    <i class="ph-fill ph-crown absolute -top-6 left-1/2 -translate-x-1/2 text-3xl text-[#D83B01] drop-shadow-md animate-bounce"></i>
                                <?php endif; ?>
                                
                                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center font-black border-4 <?php echo e($ringColor); ?> relative z-10 overflow-hidden p-1 shadow-sm bg-white">
                                    <?php if($top->student->photo_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $top->student->photo_path)); ?>" class="w-full h-full object-cover rounded-full">
                                    <?php else: ?>
                                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($top->student->name)); ?>&background=<?php echo e($avatarBg); ?>&color=fff&bold=true" class="w-full h-full object-cover rounded-full">
                                    <?php endif; ?>
                                </div>
                                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full text-[10px] font-bold shadow-sm border border-slate-200 flex items-center gap-1 <?php echo e($medalColor); ?> whitespace-nowrap">
                                    <i class="ph-fill ph-medal"></i> Rank <?php echo e($rank); ?>

                                </div>
                            </div>

                            <div class="w-full rounded-2xl md:rounded-[2rem] rounded-b-xl border border-slate-100 shadow-sm bg-white <?php echo e($heightClass); ?> p-4 flex flex-col items-center justify-start pt-8 text-center fluent-card relative group hover:border-[#5295FF]">
                                <h3 class="font-black text-sm text-[#2A3B52] leading-tight mb-1 group-hover:text-[#5295FF] transition-colors"><?php echo e($top->student->name); ?></h3>
                                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest"><?php echo e($top->student->schoolClass->name ?? '-'); ?></p>
                                
                                <div class="mt-auto bg-[#F3F9FD] rounded-xl px-4 py-2 w-full border border-[#D0E7F8]">
                                    <p class="text-[10px] font-bold text-[#5295FF] uppercase tracking-widest mb-0.5">Konsisten</p>
                                    <p class="text-xl font-black text-[#2A3B52] leading-none"><?php echo e($top->total_days); ?> <span class="text-[10px] font-bold text-slate-500">Hari</span></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <?php if($leaderboard->count() > 3): ?>
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 fluent-card">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-[#2A3B52] flex items-center gap-2"><i class="ph-bold ph-list-numbers text-[#5295FF]"></i> Peringkat Lainnya</h3>
                            <span class="text-[10px] font-bold text-[#5295FF] bg-[#F3F9FD] border border-[#D0E7F8] px-2 py-1 rounded-md">Top 50</span>
                        </div>

                        <div class="space-y-3">
                            <?php $__currentLoopData = $leaderboard->skip(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200 group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-500 font-bold flex items-center justify-center text-xs group-hover:bg-[#F3F9FD] group-hover:text-[#5295FF] transition-colors border border-slate-100 shadow-sm">
                                            #<?php echo e($index + 4); ?>

                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#2A3B52] text-sm group-hover:text-[#5295FF] transition-colors"><?php echo e($row->student->name); ?></h4>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($row->student->schoolClass->name ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#FFEFD6] border border-[#FFD8A8]">
                                            <i class="ph-fill ph-fire text-[#D83B01]"></i>
                                            <span class="font-black text-[#D83B01] text-sm"><?php echo e($row->total_days); ?> <span class="text-[9px] font-bold text-[#D83B01]/70">Hari</span></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        <?php endif; ?>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/teacher_leaderboard.blade.php ENDPATH**/ ?>