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
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-6 md:p-10 flex flex-col xl:flex-row items-center justify-between gap-6 overflow-hidden border border-white/60 shadow-xl shadow-elevate-accent/20 group">
                
                
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute top-10 right-10 w-28 h-28 bg-white/40 rounded-[2rem] rotate-45 pointer-events-none shadow-sm backdrop-blur-md border border-white/50"></div>

                
                <div class="relative z-10 w-full xl:w-auto text-center xl:text-left">
                     <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/60 hover:bg-white text-elevate-dark px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-elevate-dark flex items-center justify-center xl:justify-start gap-3">
                        <i class="ph-duotone ph-chart-bar text-elevate-primary"></i> Rekapitulasi Per Kelas
                    </h1>
                    <p class="text-elevate-dark/80 text-sm mt-3 font-semibold max-w-lg mx-auto xl:mx-0 leading-relaxed">
                        Analisis komposisi kehadiran (Hadir, Telat, Alpha) dan monitoring harian.
                    </p>
                </div>

               
                <div class="relative z-10 flex flex-wrap gap-3 w-full xl:w-auto items-center justify-center xl:justify-end">
                    <form action="<?php echo e(route('reports.class')); ?>" method="GET" class="flex flex-col sm:flex-row gap-2 bg-white/60 backdrop-blur-md p-2.5 rounded-2xl border border-white/60 w-full sm:w-auto shadow-sm">
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-white rounded-xl border border-elevate-soft shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Dari</span>
                            <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="border-none p-0 text-sm font-bold text-elevate-dark focus:ring-0 cursor-pointer w-full sm:w-32 bg-transparent">
                        </div>
                        <div class="hidden sm:flex items-center text-elevate-dark/50"><i class="ph-bold ph-arrow-right"></i></div>
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-white rounded-xl border border-elevate-soft shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Sampai</span>
                            <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="border-none p-0 text-sm font-bold text-elevate-dark focus:ring-0 cursor-pointer w-full sm:w-32 bg-transparent">
                        </div>
                        <button type="submit" class="bg-elevate-dark hover:bg-elevate-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 w-full sm:w-auto active:scale-95">
                            <i class="ph-bold ph-funnel"></i>
                        </button>
                    </form>
                   
                   
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="<?php echo e(route('reports.class.excel', ['start_date' => $startDate, 'end_date' => $endDate])); ?>" target="_blank" class="bg-white hover:bg-[#DFF6DD] text-[#107C10] border border-white/60 px-4 py-3.5 rounded-xl font-bold text-sm transition-colors flex items-center justify-center gap-2 shadow-sm flex-1 sm:flex-none" title="Download Excel">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> <span>Excel</span>
                        </a>
                        <a href="<?php echo e(route('reports.class.print', request()->all())); ?>" target="_blank" class="bg-white hover:bg-[#FDE7E9] text-[#D13438] border border-white/60 px-4 py-3.5 rounded-xl font-bold text-sm transition-colors flex items-center justify-center gap-2 shadow-sm flex-1 sm:flex-none" title="Cetak PDF">
                            <i class="ph-bold ph-printer text-lg"></i> <span>Print</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-elevate-gradient-card border border-slate-100 rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/40">
                <div class="p-6 md:p-8 border-b border-slate-100 bg-white/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-black text-xl text-elevate-dark">Komposisi Kehadiran</h3>
                    <div class="flex flex-wrap gap-4 justify-center bg-white px-4 py-2.5 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-[#107C10]"></span> Hadir</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-[#D83B01]"></span> Telat</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-elevate-primary"></span> Izin/Skt</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-[#D13438]"></span> Alpha</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-slate-300"></span> Tdk Absen</div>
                    </div>
                </div>

              <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse bg-white">
                        <thead class="bg-elevate-soft/50 text-xs font-bold text-elevate-primary uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 w-48 rounded-tl-3xl">Nama Kelas</th>
                                <th class="px-6 py-5 w-1/4">Grafik Komposisi</th>
                                <th class="px-2 py-5 text-center text-[#107C10]">Hadir</th>
                                <th class="px-2 py-5 text-center text-[#D83B01]">Telat</th>
                                <th class="px-2 py-5 text-center text-elevate-primary">Izin</th>
                                <th class="px-2 py-5 text-center text-[#D13438]">Alpha</th>
                                <th class="px-2 py-5 text-center text-slate-400">N/A</th>
                                <th class="px-8 py-5 text-right rounded-tr-3xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $hadir = data_get($data, 'hadir', 0);
                                    $telat = data_get($data, 'telat', 0);
                                    $izin  = data_get($data, 'izin_sakit', 0);
                                    $alpha = data_get($data, 'alpha', 0);
                                    
                                    $logsCount = $hadir + $telat + $izin + $alpha;
                                    $divider = $logsCount > 0 ? $logsCount : 1; 

                                    $pctHadir = round(($hadir / $divider) * 100, 1);
                                    $pctTelat = round(($telat / $divider) * 100, 1);
                                    $pctIzin  = round(($izin / $divider) * 100, 1);
                                    $pctAlpha = round(($alpha / $divider) * 100, 1);
                                    
                                    $pctNA = 0; 
                                ?>

                                <tr class="group hover:bg-elevate-soft/30 transition-colors">
                                    
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-elevate-peach-light/50 text-elevate-peach-dark font-black text-sm flex items-center justify-center border border-elevate-peach group-hover:bg-elevate-primary group-hover:text-white group-hover:border-elevate-primary transition-colors shadow-sm">
                                                <?php echo e(substr(data_get($data, 'name', '??'), 0, 2)); ?>

                                            </div>
                                           <div>
                                                <div class="font-black text-elevate-dark text-lg"><?php echo e(data_get($data, 'name', 'Kelas ?')); ?></div>
                                                <div class="text-[10px] text-elevate-primary font-bold uppercase tracking-wide"><?php echo e(data_get($data, 'total_students', 0)); ?> Siswa</div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-5 align-middle">
                                       <div class="flex w-full h-3 bg-elevate-soft rounded-full overflow-hidden shadow-inner">
                                            <?php if($pctHadir > 0): ?><div style="width: <?php echo e($pctHadir); ?>%" class="bg-[#107C10] hover:opacity-80 transition-all" title="Hadir: <?php echo e($pctHadir); ?>%"></div><?php endif; ?>
                                            <?php if($pctTelat > 0): ?><div style="width: <?php echo e($pctTelat); ?>%" class="bg-[#D83B01] hover:opacity-80 transition-all" title="Telat: <?php echo e($pctTelat); ?>%"></div><?php endif; ?>
                                            <?php if($pctIzin > 0): ?><div style="width: <?php echo e($pctIzin); ?>%" class="bg-elevate-primary hover:opacity-80 transition-all" title="Izin/Sakit: <?php echo e($pctIzin); ?>%"></div><?php endif; ?>
                                            <?php if($pctAlpha > 0): ?><div style="width: <?php echo e($pctAlpha); ?>%" class="bg-[#D13438] hover:opacity-80 transition-all" title="Alpha: <?php echo e($pctAlpha); ?>%"></div><?php endif; ?>
                                        </div>
                                        <div class="flex justify-between mt-2 text-[10px] font-bold text-slate-400">
                                            <span>0%</span>
                                            <span>50%</span>
                                            <span>100%</span>
                                        </div>
                                    </td>

                                    
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-[#107C10]"><?php echo e($pctHadir); ?>%</span><span class="text-[10px] font-bold text-slate-400"><?php echo e($hadir); ?></span></div></td>
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-[#D83B01]"><?php echo e($pctTelat); ?>%</span><span class="text-[10px] font-bold text-slate-400"><?php echo e($telat); ?></span></div></td>
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-elevate-primary"><?php echo e($pctIzin); ?>%</span><span class="text-[10px] font-bold text-slate-400"><?php echo e($izin); ?></span></div></td>
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-[#D13438]"><?php echo e($pctAlpha); ?>%</span><span class="text-[10px] font-bold text-slate-400"><?php echo e($alpha); ?></span></div></td>
                                    <td class="px-2 py-5 text-center border-l border-slate-100 border-dashed"><div class="flex flex-col"><span class="font-black text-lg text-slate-400"><?php echo e($pctNA > 0 ? $pctNA.'%' : '-'); ?></span><span class="text-[10px] font-bold text-slate-300">N/A</span></div></td>

                                    
                                    <td class="px-8 py-5 text-right">
                                        <a href="<?php echo e(route('reports.class.detail', ['class_id' => data_get($data, 'id'), 'month' => \Carbon\Carbon::parse($startDate)->format('Y-m')])); ?>" 
                                           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-elevate-dark hover:text-white hover:border-elevate-primary hover:bg-elevate-primary transition-all shadow-sm group-hover:shadow-md">
                                            <span>Lihat Harian</span><i class="ph-bold ph-caret-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan=\"8\" class=\"px-6 py-16 text-center text-slate-400\">
                                        <div class=\"flex flex-col items-center justify-center\">
                                            <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-primary"><i class=\"ph-duotone ph-chalkboard-teacher text-4xl\"></i></div>
                                            <p class=\"font-bold\">Belum ada data kelas atau absensi pada periode ini.</p>\n                                        </div>\n                                    </td>\n                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                
                <div class="bg-white/50 p-6 border-t border-slate-100 text-center rounded-b-[2.5rem]">
                    <p class="text-sm font-semibold text-slate-400">
                        <i class="ph-bold ph-info"></i> Data ditampilkan berdasarkan rekapitulasi kehadiran siswa.
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/reports/class_attendance.blade.php ENDPATH**/ ?>