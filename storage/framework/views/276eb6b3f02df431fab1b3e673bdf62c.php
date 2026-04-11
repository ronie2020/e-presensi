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
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>

    <?php
        // LOGIKA QUERY MENGAMBIL DATA SISWA PALING RAJIN
        // Menghitung berapa hari (total_days) siswa mengisi jurnal di bulan ini
        //$leaderboard = \App\Models\StudentHabit::with(['student', 'student.schoolClass'])
        //    ->selectRaw('student_id, count(*) as total_days')
         //   ->whereMonth('report_date', \Carbon\Carbon::now()->month)
         //  ->whereYear('report_date', \Carbon\Carbon::now()->year)
          //  ->groupBy('student_id')
          //  ->orderByDesc('total_days')
           // ->take(50) // Ambil Top 50 besar
          //  ->get();
   ?>

    <div class="min-h-screen bg-slate-50 font-jakarta p-4 md:p-8 pb-20">
        
        
        <div class="relative rounded-[3rem] bg-gradient-to-b from-amber-500 to-orange-600 p-8 md:p-12 text-white shadow-2xl shadow-orange-500/20 overflow-hidden text-center mb-16">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20 mix-blend-overlay"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>

            <div class="relative z-10">
                <a href="<?php echo e(route('teacher.habits.index')); ?>" class="absolute top-0 left-0 bg-white/20 hover:bg-white/30 px-5 py-2.5 rounded-2xl text-xs font-bold backdrop-blur transition-colors flex items-center gap-2">
                    <i class="ph-bold ph-arrow-left text-lg"></i> Kembali
                </a>
                
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6 backdrop-blur shadow-inner border border-white/30">
                    <i class="ph-fill ph-crown text-4xl text-yellow-300"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-black mb-3 tracking-tight">Papan Kehormatan Siswa</h1>
                <p class="text-orange-100 font-medium text-sm md:text-base max-w-xl mx-auto">
                    Daftar apresiasi siswa paling konsisten dalam membangun kebiasaan baik pada bulan <span class="font-bold border-b border-white/50"><?php echo e(\Carbon\Carbon::now()->translatedFormat('F Y')); ?></span>.
                </p>
            </div>
        </div>

        <?php if($leaderboard->isEmpty()): ?>
            <div class="text-center py-20 bg-white rounded-[3rem] border border-slate-100">
                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4"><i class="ph-duotone ph-ghost text-5xl"></i></div>
                <h3 class="text-xl font-black text-slate-800">Belum Ada Data</h3>
                <p class="text-slate-500 text-sm">Belum ada siswa yang mengisi jurnal di bulan ini.</p>
            </div>
        <?php else: ?>
            <div class="max-w-4xl mx-auto">
                
                
                <div class="flex flex-col md:flex-row items-end justify-center gap-4 md:gap-8 mb-16 px-4">
                    <?php $__currentLoopData = $leaderboard->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $top): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $rank = $index + 1;
                            $orderClass = $rank == 1 ? 'order-1 md:order-2 z-20' : ($rank == 2 ? 'order-2 md:order-1 z-10' : 'order-3 md:order-3 z-0');
                            $heightClass = $rank == 1 ? 'md:h-64 h-56' : ($rank == 2 ? 'md:h-56 h-48' : 'md:h-48 h-40');
                            $bgClass = $rank == 1 ? 'bg-gradient-to-t from-yellow-400 to-yellow-300 border-yellow-200' : 
                                      ($rank == 2 ? 'bg-gradient-to-t from-slate-300 to-slate-200 border-slate-100' : 
                                      'bg-gradient-to-t from-amber-700 to-amber-600 border-amber-500');
                            $textColor = $rank == 1 ? 'text-yellow-800' : ($rank == 2 ? 'text-slate-700' : 'text-amber-100');
                            $medalColor = $rank == 1 ? 'text-yellow-500' : ($rank == 2 ? 'text-slate-400' : 'text-amber-600');
                        ?>
                        
                        <div class="w-full md:w-1/3 flex flex-col items-center <?php echo e($orderClass); ?> transform transition-transform hover:-translate-y-2">
                            <div class="relative mb-4 group cursor-pointer">
                                
                                <?php if($rank == 1): ?>
                                    <i class="ph-fill ph-crown absolute -top-8 left-1/2 -translate-x-1/2 text-4xl text-yellow-500 drop-shadow-md animate-bounce"></i>
                                <?php endif; ?>
                                
                                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-xl flex items-center justify-center text-2xl font-black text-slate-700 border-4 <?php echo e($rank==1 ? 'border-yellow-400' : ($rank==2 ? 'border-slate-300' : 'border-amber-600')); ?> relative z-10 overflow-hidden">
                                    <?php if($top->student->photo_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $top->student->photo_path)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?php echo e(substr($top->student->name, 0, 1)); ?>

                                    <?php endif; ?>
                                </div>
                                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full text-[10px] font-black shadow-sm border border-slate-100 flex items-center gap-1 <?php echo e($medalColor); ?>">
                                    <i class="ph-fill ph-medal"></i> Rank <?php echo e($rank); ?>

                                </div>
                            </div>

                            <div class="w-full rounded-t-3xl border shadow-lg <?php echo e($bgClass); ?> <?php echo e($heightClass); ?> p-4 flex flex-col items-center justify-start pt-8 relative overflow-hidden text-center">
                                <div class="absolute inset-0 bg-white opacity-20 mix-blend-overlay"></div>
                                <h3 class="font-black text-sm md:text-base <?php echo e($textColor); ?> relative z-10 leading-tight mb-1"><?php echo e($top->student->name); ?></h3>
                                <p class="<?php echo e($textColor); ?> opacity-80 text-[10px] font-bold uppercase tracking-widest relative z-10"><?php echo e($top->student->schoolClass->name ?? '-'); ?></p>
                                
                                <div class="mt-auto bg-black/10 backdrop-blur rounded-2xl px-4 py-3 relative z-10 w-full">
                                    <p class="text-xs font-black <?php echo e($textColor); ?> uppercase tracking-widest mb-0.5">Konsistensi</p>
                                    <p class="text-2xl font-black <?php echo e($textColor); ?> leading-none"><?php echo e($top->total_days); ?> <span class="text-xs font-bold">Hari</span></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <?php if($leaderboard->count() > 3): ?>
                    <div class="bg-white rounded-[3rem] p-6 md:p-10 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2"><i class="ph-bold ph-list-numbers text-blue-500"></i> Peringkat Lainnya</h3>
                            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-lg">Top 50</span>
                        </div>

                        <div class="space-y-3">
                            <?php $__currentLoopData = $leaderboard->skip(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 group">
                                    <div class="flex items-center gap-5">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 font-black flex items-center justify-center text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                            #<?php echo e($index + 4); ?>

                                        </div>
                                        <div>
                                            <h4 class="font-black text-slate-800 uppercase tracking-tight text-sm"><?php echo e($row->student->name); ?></h4>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($row->student->schoolClass->name ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 border border-blue-100">
                                            <i class="ph-bold ph-fire text-orange-500"></i>
                                            <span class="font-black text-blue-700 text-sm"><?php echo e($row->total_days); ?> <span class="text-[10px] text-blue-400">Hari</span></span>
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