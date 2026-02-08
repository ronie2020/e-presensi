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
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-6 md:p-8 flex flex-col xl:flex-row items-center justify-between gap-6 overflow-hidden border border-white/10 shadow-2xl shadow-blue-900/40 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>

                
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <i class="ph-fill ph-chalkboard-teacher text-9xl text-white"></i>                    
                </div>

                
                <div class="relative z-10 w-full xl:w-auto text-center xl:text-left">
                     <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <h1 class="text-2xl md:text-3xl font-black text-white flex items-center justify-center xl:justify-start gap-3">
                        <i class="ph-duotone ph-chart-bar text-blue-400"></i> Rekapitulasi Per Kelas
                    </h1>
                    <p class="text-blue-200 text-sm mt-2 font-medium max-w-lg mx-auto xl:mx-0 leading-relaxed">
                        Analisis komposisi kehadiran (Hadir, Telat, Alpha) dan monitoring harian.
                    </p>
                </div>

                
                <div class="relative z-10 flex flex-wrap gap-3 w-full xl:w-auto items-center justify-center xl:justify-end">
                    
                    
                    <form action="<?php echo e(route('reports.class')); ?>" method="GET" class="flex flex-col sm:flex-row gap-2 bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20 w-full sm:w-auto shadow-lg">
                        <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-xl border border-blue-100 shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Dari</span>
                            <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="border-none p-0 text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer w-full sm:w-32 bg-transparent">
                        </div>
                        <div class="hidden sm:flex items-center text-blue-300/50"><i class="ph-bold ph-arrow-right"></i></div>
                        <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-xl border border-blue-100 shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Sampai</span>
                            <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="border-none p-0 text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer w-full sm:w-32 bg-transparent">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-xl font-bold text-sm transition shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 w-full sm:w-auto">
                            <i class="ph-bold ph-funnel"></i>
                        </button>
                    </form>

                   
                    
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="<?php echo e(route('reports.class.excel', request()->all())); ?>" target="_blank" class="bg-white hover:bg-emerald-50 text-emerald-600 border border-white/20 px-4 py-3 rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2 shadow-lg flex-1 sm:flex-none" title="Download Excel">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> 
                            <span>Excel</span>
                        </a>
                        <a href="<?php echo e(route('reports.class.print', request()->all())); ?>" target="_blank" class="bg-white hover:bg-rose-50 text-rose-600 border border-white/20 px-4 py-3 rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2 shadow-lg flex-1 sm:flex-none" title="Cetak PDF">
                            <i class="ph-bold ph-printer text-lg"></i> 
                            <span>Print / PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-slate-700">Komposisi Kehadiran</h3>
                    
                    
                    <div class="flex flex-wrap gap-3 justify-center">
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500">
                            <span class="w-3 h-3 rounded bg-emerald-500"></span> Hadir
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500">
                            <span class="w-3 h-3 rounded bg-amber-500"></span> Telat
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500">
                            <span class="w-3 h-3 rounded bg-blue-500"></span> Izin/Skt
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500">
                            <span class="w-3 h-3 rounded bg-rose-500"></span> Alpha
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase text-slate-500">
                            <span class="w-3 h-3 rounded bg-slate-300"></span> Tdk Absen
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-48">Nama Kelas</th>
                                <th class="px-6 py-4 w-1/4">Grafik Komposisi</th>
                                <th class="px-2 py-4 text-center text-emerald-600">Hadir</th>
                                <th class="px-2 py-4 text-center text-amber-600">Telat</th>
                                <th class="px-2 py-4 text-center text-blue-600">Izin</th>
                                <th class="px-2 py-4 text-center text-rose-600">Alpha</th>
                                <th class="px-2 py-4 text-center text-slate-400">N/A</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    // Hitung Total Log (Hadir + Telat + Izin/Sakit + Alpha)
                                    // Asumsi: Jika ada field 'total_sesi' di $data, gunakan itu sebagai pembagi.
                                    // Jika tidak, gunakan sum dari log yang ada.
                                    $logsCount = $data->hadir + $data->telat + $data->izin_sakit + $data->alpha;
                                    
                                    // Gunakan total siswa * sesi jika tersedia, atau logsCount sebagai fallback
                                    // Disini kita gunakan logsCount agar persentase total 100% berdasarkan data yang masuk
                                    $divider = $logsCount > 0 ? $logsCount : 1; 

                                    // Persentase
                                    $pctHadir = round(($data->hadir / $divider) * 100, 1);
                                    $pctTelat = round(($data->telat / $divider) * 100, 1);
                                    $pctIzin  = round(($data->izin_sakit / $divider) * 100, 1);
                                    $pctAlpha = round(($data->alpha / $divider) * 100, 1);
                                    
                                    // Logic 'Tidak Absen' (Sisa dari 100% jika pembagi menggunakan total ekspektasi)
                                    // Jika menggunakan logsCount murni, ini akan 0. 
                                    // Mari kita buat visualisasi menarik: Anggap sisa yang "Belum tercatat"
                                    $pctNA = 100 - ($pctHadir + $pctTelat + $pctIzin + $pctAlpha);
                                    if($pctNA < 0) $pctNA = 0;
                                ?>

                                <tr class="group hover:bg-slate-50 transition-colors">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm flex items-center justify-center border border-slate-200 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-sm">
                                                <?php echo e(substr($data->name, 0, 2)); ?>

                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-700"><?php echo e($data->name); ?></div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wide"><?php echo e($data->total_students); ?> Siswa</div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex w-full h-3 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                            <?php if($pctHadir > 0): ?>
                                                <div style="width: <?php echo e($pctHadir); ?>%" class="bg-emerald-500 hover:bg-emerald-400 transition-all" title="Hadir: <?php echo e($pctHadir); ?>%"></div>
                                            <?php endif; ?>
                                            <?php if($pctTelat > 0): ?>
                                                <div style="width: <?php echo e($pctTelat); ?>%" class="bg-amber-500 hover:bg-amber-400 transition-all" title="Telat: <?php echo e($pctTelat); ?>%"></div>
                                            <?php endif; ?>
                                            <?php if($pctIzin > 0): ?>
                                                <div style="width: <?php echo e($pctIzin); ?>%" class="bg-blue-500 hover:bg-blue-400 transition-all" title="Izin/Sakit: <?php echo e($pctIzin); ?>%"></div>
                                            <?php endif; ?>
                                            <?php if($pctAlpha > 0): ?>
                                                <div style="width: <?php echo e($pctAlpha); ?>%" class="bg-rose-500 hover:bg-rose-400 transition-all" title="Alpha: <?php echo e($pctAlpha); ?>%"></div>
                                            <?php endif; ?>
                                             <?php if($pctNA > 1): ?> 
                                                <div style="width: <?php echo e($pctNA); ?>%" class="bg-slate-300 hover:bg-slate-200 transition-all pattern-diagonal-lines" title="Tidak Absen / Data Kosong"></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex justify-between mt-1 text-[10px] font-bold text-slate-400">
                                            <span>0%</span>
                                            <span>50%</span>
                                            <span>100%</span>
                                        </div>
                                    </td>

                                    
                                    <td class="px-2 py-4 text-center">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-emerald-600"><?php echo e($pctHadir); ?>%</span>
                                            <span class="text-[10px] text-slate-400"><?php echo e($data->hadir); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-amber-600"><?php echo e($pctTelat); ?>%</span>
                                            <span class="text-[10px] text-slate-400"><?php echo e($data->telat); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-blue-600"><?php echo e($pctIzin); ?>%</span>
                                            <span class="text-[10px] text-slate-400"><?php echo e($data->izin_sakit); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-rose-600"><?php echo e($pctAlpha); ?>%</span>
                                            <span class="text-[10px] text-slate-400"><?php echo e($data->alpha); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 text-center border-l border-slate-100 border-dashed">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-400"><?php echo e($pctNA > 0 ? $pctNA.'%' : '-'); ?></span>
                                            <span class="text-[10px] text-slate-300">N/A</span>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-right">
                                        <a href="<?php echo e(route('reports.class.detail', ['class_id' => $data->id, 'month' => \Carbon\Carbon::parse($startDate)->format('Y-m')])); ?>" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm group-hover:shadow-md">
                                            <span>Lihat Harian</span>
                                            <i class="ph-bold ph-caret-right"></i>
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