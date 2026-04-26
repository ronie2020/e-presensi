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
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108); transform: translateY(-2px); }
    </style>
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-6 md:p-8 flex flex-col xl:flex-row items-center justify-between gap-6 overflow-hidden border border-white/40 shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] group">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/30 rounded-full blur-3xl pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>

                
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <i class="ph-fill ph-chalkboard-teacher text-9xl text-[#2A3B52]"></i>                    
                </div>

                
                <div class="relative z-10 w-full xl:w-auto text-center xl:text-left">
                     <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/40 hover:bg-white/60 text-[#2A3B52] px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <h1 class="text-2xl md:text-3xl font-black text-[#2A3B52] flex items-center justify-center xl:justify-start gap-3">
                        <i class="ph-duotone ph-chart-bar text-[#5295FF]"></i> Rekapitulasi Per Kelas
                    </h1>
                    <p class="text-[#2A3B52]/80 text-sm mt-2 font-medium max-w-lg mx-auto xl:mx-0 leading-relaxed">
                        Analisis komposisi kehadiran (Hadir, Telat, Alpha) dan monitoring harian.
                    </p>
                </div>

               
                <div class="relative z-10 flex flex-wrap gap-3 w-full xl:w-auto items-center justify-center xl:justify-end">
                    <form action="<?php echo e(route('reports.class')); ?>" method="GET" class="flex flex-col sm:flex-row gap-2 bg-white/30 backdrop-blur-md p-2 rounded-xl border border-white/40 w-full sm:w-auto shadow-sm">
                        <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-[#D0E7F8] shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Dari</span>
                            <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="border-none p-0 text-sm font-bold text-[#2A3B52] focus:ring-0 cursor-pointer w-full sm:w-32 bg-transparent">
                        </div>
                        <div class="hidden sm:flex items-center text-[#2A3B52]/50"><i class="ph-bold ph-arrow-right"></i></div>
                        <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-[#D0E7F8] shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Sampai</span>
                            <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="border-none p-0 text-sm font-bold text-[#2A3B52] focus:ring-0 cursor-pointer w-full sm:w-32 bg-transparent">
                        </div>
                        <button type="submit" class="bg-[#2A3B52] hover:bg-[#182436] text-white px-4 py-2 rounded-lg font-bold text-sm transition shadow-md flex items-center justify-center gap-2 w-full sm:w-auto active:scale-95">
                            <i class="ph-bold ph-funnel"></i>
                        </button>
                    </form>
                   
                   
                    <a href="<?php echo e(route('reports.class.excel', ['start_date' => $startDate, 'end_date' => $endDate])); ?>" target="_blank" class="bg-white hover:bg-[#DFF6DD] text-[#107C10] border border-white/40 px-4 py-3 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 shadow-sm flex-1 sm:flex-none" title="Download Excel">
                        <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> 
                        <span>Excel</span>
                    </a>
                    <a href="<?php echo e(route('reports.class.print', request()->all())); ?>" target="_blank" class="bg-white hover:bg-[#FDE7E9] text-[#D13438] border border-white/40 px-4 py-3 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 shadow-sm flex-1 sm:flex-none" title="Cetak PDF">
                        <i class="ph-bold ph-printer text-lg"></i> 
                        <span>Print / PDF</span>
                    </a>
                    </div>
                </div>
            </div>
        </div>

        
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl fluent-card overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-[#2A3B52]">Komposisi Kehadiran</h3>
                    <div class="flex flex-wrap gap-3 justify-center">
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded bg-[#107C10]"></span> Hadir</div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded bg-[#D83B01]"></span> Telat</div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded bg-[#5295FF]"></span> Izin/Skt</div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded bg-[#D13438]"></span> Alpha</div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded bg-slate-300"></span> Tdk Absen</div>
                    </div>
                </div>

              <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 w-48">Nama Kelas</th>
                                <th class="px-6 py-4 w-1/4">Grafik Komposisi</th>
                                <th class="px-2 py-4 text-center text-[#107C10]">Hadir</th>
                                <th class="px-2 py-4 text-center text-[#D83B01]">Telat</th>
                                <th class="px-2 py-4 text-center text-[#5295FF]">Izin</th>
                                <th class="px-2 py-4 text-center text-[#D13438]">Alpha</th>
                                <th class="px-2 py-4 text-center text-slate-400">N/A</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    // REVISI: Menggunakan data_get() untuk mengakses properti dengan aman
                                    // Jika $data->hadir tidak ada (misal stdClass kosong), akan default ke 0
                                    $hadir = data_get($data, 'hadir', 0);
                                    $telat = data_get($data, 'telat', 0);
                                    $izin  = data_get($data, 'izin_sakit', 0);
                                    $alpha = data_get($data, 'alpha', 0);
                                    
                                    // Hitung Total Log
                                    $logsCount = $hadir + $telat + $izin + $alpha;
                                    
                                    // Gunakan total log sebagai pembagi agar 100% mewakili data yang masuk
                                    $divider = $logsCount > 0 ? $logsCount : 1; 

                                    // Persentase
                                    $pctHadir = round(($hadir / $divider) * 100, 1);
                                    $pctTelat = round(($telat / $divider) * 100, 1);
                                    $pctIzin  = round(($izin / $divider) * 100, 1);
                                    $pctAlpha = round(($alpha / $divider) * 100, 1);
                                    
                                    $pctNA = 0; 
                                ?>

                                <tr class="group hover:bg-slate-50 transition-colors">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 text-[#2A3B52] font-bold text-sm flex items-center justify-center border border-slate-200 group-hover:bg-[#5295FF] group-hover:text-white transition-colors shadow-sm">
                                                <?php echo e(substr(data_get($data, 'name', '??'), 0, 2)); ?>

                                            </div>
                                           <div>
                                                <div class="font-bold text-[#2A3B52]"><?php echo e(data_get($data, 'name', 'Kelas ?')); ?></div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wide"><?php echo e(data_get($data, 'total_students', 0)); ?> Siswa</div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 align-middle">
                                       <div class="flex w-full h-3 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                            <?php if($pctHadir > 0): ?><div style="width: <?php echo e($pctHadir); ?>%" class="bg-[#107C10] hover:opacity-80 transition-all" title="Hadir: <?php echo e($pctHadir); ?>%"></div><?php endif; ?>
                                            <?php if($pctTelat > 0): ?><div style="width: <?php echo e($pctTelat); ?>%" class="bg-[#D83B01] hover:opacity-80 transition-all" title="Telat: <?php echo e($pctTelat); ?>%"></div><?php endif; ?>
                                            <?php if($pctIzin > 0): ?><div style="width: <?php echo e($pctIzin); ?>%" class="bg-[#5295FF] hover:opacity-80 transition-all" title="Izin/Sakit: <?php echo e($pctIzin); ?>%"></div><?php endif; ?>
                                            <?php if($pctAlpha > 0): ?><div style="width: <?php echo e($pctAlpha); ?>%" class="bg-[#D13438] hover:opacity-80 transition-all" title="Alpha: <?php echo e($pctAlpha); ?>%"></div><?php endif; ?>
                                        </div>
                                        <div class="flex justify-between mt-1 text-[10px] font-bold text-slate-400">
                                            <span>0%</span>
                                            <span>50%</span>
                                            <span>100%</span>
                                        </div>
                                    </td>

                                    
                                    <td class="px-2 py-4 text-center"><div class="flex flex-col"><span class="font-bold text-[#107C10]"><?php echo e($pctHadir); ?>%</span><span class="text-[10px] text-slate-400"><?php echo e($hadir); ?></span></div></td>
                                    <td class="px-2 py-4 text-center"><div class="flex flex-col"><span class="font-bold text-[#D83B01]"><?php echo e($pctTelat); ?>%</span><span class="text-[10px] text-slate-400"><?php echo e($telat); ?></span></div></td>
                                    <td class="px-2 py-4 text-center"><div class="flex flex-col"><span class="font-bold text-[#5295FF]"><?php echo e($pctIzin); ?>%</span><span class="text-[10px] text-slate-400"><?php echo e($izin); ?></span></div></td>
                                    <td class="px-2 py-4 text-center"><div class="flex flex-col"><span class="font-bold text-[#D13438]"><?php echo e($pctAlpha); ?>%</span><span class="text-[10px] text-slate-400"><?php echo e($alpha); ?></span></div></td>
                                    <td class="px-2 py-4 text-center border-l border-slate-100 border-dashed"><div class="flex flex-col"><span class="font-bold text-slate-400"><?php echo e($pctNA > 0 ? $pctNA.'%' : '-'); ?></span><span class="text-[10px] text-slate-300">N/A</span></div></td>
                                    <td class="px-6 py-4 text-right">

                                    
                                    <td class="px-6 py-4 text-right">
                                        <a href="<?php echo e(route('reports.class.detail', ['class_id' => data_get($data, 'id'), 'month' => \Carbon\Carbon::parse($startDate)->format('Y-m')])); ?>" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:text-[#5295FF] hover:border-[#D0E7F8] hover:bg-[#F3F9FD] transition-all shadow-sm">
                                            <span>Lihat Harian</span><i class="ph-bold ph-caret-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="ph-duotone ph-chalkboard-teacher text-4xl mb-2 opacity-50"></i>
                                            <p class="font-bold">Belum ada data kelas atau absensi pada periode ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                
                <div class="bg-slate-50 p-4 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-400">
                        <i class="ph-bold ph-info"></i> Data ditampilkan berdasarkan Rekapitulasi kehadiran siswa.
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/reports/class_attendance.blade.php ENDPATH**/ ?>