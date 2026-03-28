<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500 font-sans" x-data="{ showHistory: false }">
    
    
    <?php
        $stats = [
            'present' => 0,
            'sick' => 0,
            'permission' => 0,
            'alpha' => 0,
            'total' => isset($teaching_journals) ? $teaching_journals->count() : 0
        ];

        if(isset($teaching_journals)) {
            foreach($teaching_journals as $journal) {
                // Gunakan pencarian strict jika tipe ID berbeda (string vs int)
                $attendance = $journal->attendances->first(function ($att) use ($student) {
                    return (string)$att->student_id === (string)$student->id;
                });
                
                $status = $attendance ? $attendance->status : null;
                
                // Logika Auto-Alpha jika closed
                if($journal->status == 'closed' && !$status) $status = 'alpha';

                if($status == 'present') $stats['present']++;
                elseif($status == 'sick') $stats['sick']++;
                elseif($status == 'permission') $stats['permission']++;
                elseif($status == 'alpha') $stats['alpha']++;
            }
        }
        
        // Menghitung persentase untuk tinggi diagram (dengan number_format agar CSS valid)
        $total = $stats['total'] > 0 ? $stats['total'] : 1;
        $pctPresent = number_format(($stats['present'] / $total) * 100, 1);
        $pctSickPermit = number_format((($stats['sick'] + $stats['permission']) / $total) * 100, 1);
        $pctAlpha = number_format(($stats['alpha'] / $total) * 100, 1);
    ?>

    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24 group overflow-hidden">
            
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                <i class="ph-duotone ph-chalkboard-teacher text-9xl text-blue-600"></i>
            </div>

            <div class="relative z-10">
                <h3 class="text-lg font-black text-slate-800 mb-1">Pantauan KBM</h3>
                <p class="text-slate-400 text-xs mb-6 font-medium">Ringkasan kehadiran di kelas (<?php echo e($stats['total']); ?> Sesi Terakhir).</p>

                
                <div class="flex items-end gap-3 h-32 mb-6 px-4 pb-2 border-b border-slate-50">
                    
                    <div class="flex-1 flex flex-col items-center gap-2 group/bar h-full justify-end">
                        <div class="w-full bg-emerald-50 rounded-t-lg relative h-full flex items-end overflow-hidden">
                            <div style="height: <?php echo e($pctPresent); ?>%" class="w-full bg-emerald-500 transition-all duration-1000 group-hover/bar:bg-emerald-400 relative">
                                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-emerald-600 opacity-0 group-hover/bar:opacity-100 transition-opacity"><?php echo e($pctPresent); ?>%</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Hadir</span>
                    </div>
                    
                    <div class="flex-1 flex flex-col items-center gap-2 group/bar h-full justify-end">
                        <div class="w-full bg-blue-50 rounded-t-lg relative h-full flex items-end overflow-hidden">
                            <div style="height: <?php echo e($pctSickPermit); ?>%" class="w-full bg-blue-500 transition-all duration-1000 group-hover/bar:bg-blue-400 relative">
                                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-blue-600 opacity-0 group-hover/bar:opacity-100 transition-opacity"><?php echo e($pctSickPermit); ?>%</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Izin</span>
                    </div>
                    
                    <div class="flex-1 flex flex-col items-center gap-2 group/bar h-full justify-end">
                        <div class="w-full bg-rose-50 rounded-t-lg relative h-full flex items-end overflow-hidden">
                            <div style="height: <?php echo e($pctAlpha); ?>%" class="w-full bg-rose-500 transition-all duration-1000 group-hover/bar:bg-rose-400 relative">
                                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-rose-600 opacity-0 group-hover/bar:opacity-100 transition-opacity"><?php echo e($pctAlpha); ?>%</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Alpha</span>
                    </div>
                </div>

                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-200 text-emerald-700 flex items-center justify-center">
                                <i class="ph-bold ph-check"></i>
                            </div>
                            <span class="text-xs font-bold text-emerald-800">Mengikuti Kelas</span>
                        </div>
                        <span class="text-lg font-black text-emerald-600"><?php echo e($stats['present']); ?></span>
                    </div>
                    
                    <?php if($stats['alpha'] > 0): ?>
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-rose-50 border border-rose-100 animate-pulse">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-rose-200 text-rose-700 flex items-center justify-center">
                                <i class="ph-bold ph-x"></i>
                            </div>
                            <span class="text-xs font-bold text-rose-800">Tidak Hadir (Alpha)</span>
                        </div>
                        <span class="text-lg font-black text-rose-600"><?php echo e($stats['alpha']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="mt-6 pt-4 border-t border-slate-50 text-center">
                    <button @click="showHistory = true" class="w-full py-3 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition-all flex items-center justify-center gap-2 shadow-lg">
                        <i class="ph-bold ph-list-dashes"></i> Lihat Riwayat Lengkap
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="lg:col-span-2 space-y-6">
        
        <?php if(isset($teaching_journals) && count($teaching_journals) > 0): ?>
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                    <h4 class="font-black text-slate-800 flex items-center gap-2 text-lg">
                        <i class="ph-duotone ph-notebook text-blue-600 text-xl"></i> 
                        Riwayat Pembelajaran
                    </h4>
                    <span class="text-[10px] font-bold bg-slate-50 border border-slate-200 px-3 py-1 rounded-full text-slate-500">
                        Terbaru
                    </span>
                </div>

                <div class="relative space-y-8 pl-4">
                    
                    <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-slate-100 -ml-[0.5px]"></div>

                    
                    <?php $__currentLoopData = $teaching_journals->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php
                            $attendance = $journal->attendances->first(function ($att) use ($student) {
                                return (string)$att->student_id === (string)$student->id;
                            });
                            $status = $attendance ? $attendance->status : null;
                            
                            // PERBAIKAN: Gunakan class CSS secara eksplisit/utuh agar dibaca PurgeCSS
                            $statusConfig = [
                                'present' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'label' => 'Hadir', 'icon' => 'ph-check-circle'],
                                'sick' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'label' => 'Sakit', 'icon' => 'ph-thermometer'],
                                'permission' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'label' => 'Izin', 'icon' => 'ph-hand-waving'],
                                'alpha' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'label' => 'Alpha', 'icon' => 'ph-x-circle'],
                                'default' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-100', 'label' => 'Belum Absen', 'icon' => 'ph-question'],
                            ];

                            if ($journal->status == 'closed' && !$status) $status = 'alpha';
                            $config = $statusConfig[$status] ?? $statusConfig['default'];
                        ?>

                        <div class="relative pl-10 group">
                            
                            <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-white border-2 border-slate-100 shadow-sm flex items-center justify-center z-10 group-hover:scale-110 transition-transform">
                                <i class="ph-bold ph-book-bookmark text-slate-400"></i>
                            </div>

                            
                            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 relative overflow-hidden">
                                
                                
                                <div class="absolute top-0 right-0">
                                    <div class="<?php echo e($config['bg']); ?> <?php echo e($config['text']); ?> px-4 py-1.5 rounded-bl-2xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 border-b border-l <?php echo e($config['border']); ?>">
                                        <i class="ph-bold <?php echo e($config['icon']); ?>"></i> <?php echo e($config['label']); ?>

                                    </div>
                                </div>

                                <div class="mb-4 pr-20">
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wide border border-blue-100">
                                            <?php echo e($journal->schedule?->subject?->name ?? 'Mata Pelajaran'); ?>

                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                            <i class="ph-fill ph-clock"></i>
                                            <?php echo e(\Carbon\Carbon::parse($journal->started_at)->format('H:i')); ?>

                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                            <i class="ph-fill ph-calendar-blank"></i>
                                            <?php echo e(\Carbon\Carbon::parse($journal->date)->translatedFormat('d M Y')); ?>

                                        </span>
                                    </div>
                                    <h3 class="text-base font-black text-slate-800 leading-snug group-hover:text-blue-700 transition-colors">
                                        <?php echo e($journal->topic ?? 'Topik Pembelajaran'); ?>

                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1.5">
                                        <i class="ph-fill ph-chalkboard-teacher text-slate-300"></i>
                                        <?php echo e($journal->schedule?->teacher?->name ?? 'Guru Pengajar'); ?>

                                    </p>
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 relative mb-4">
                                    <i class="ph-fill ph-quotes text-slate-200 text-2xl absolute top-2 right-2"></i>
                                    <p class="text-xs text-slate-600 leading-relaxed relative z-10 whitespace-pre-line">
                                        <?php echo e($journal->activities ?? 'Tidak ada catatan aktivitas khusus.'); ?>

                                    </p>
                                </div>

                                <?php if($journal->photo_proof): ?>
                                    <div x-data="{ open: false }">
                                        <button @click="open = true" class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-2 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors w-full sm:w-auto shadow-sm">
                                            <i class="ph-bold ph-image text-blue-500"></i> Lihat Dokumentasi Kelas
                                        </button>
                                        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
                                            <div @click.away="open = false" class="relative max-w-3xl w-full">
                                                <button @click="open = false" class="absolute -top-10 right-0 text-white hover:text-rose-400"><i class="ph-bold ph-x text-2xl"></i></button>
                                                <img src="<?php echo e(asset('storage/' . $journal->photo_proof)); ?>" class="w-full h-auto rounded-xl shadow-2xl border-2 border-white/20">
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-[3rem] border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-200 transition-colors h-full flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                    <i class="ph-duotone ph-notebook text-5xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                </div>
                <h3 class="font-black text-slate-800 text-xl">Belum Ada Riwayat</h3>
                <p class="text-sm text-slate-400 mt-2 max-w-xs mx-auto leading-relaxed">
                    Jurnal kegiatan belajar mengajar belum tersedia saat ini. Data akan muncul setelah guru mengisi jurnal kelas.
                </p>
            </div>
        <?php endif; ?>
    </div>

    
    <div x-show="showHistory" 
         x-transition.opacity
         class="fixed inset-0 z-[60] flex items-center justify-center px-4"
         style="display: none;">
        
        
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showHistory = false"></div>

        
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden animate-in zoom-in-95 duration-300">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <i class="ph-duotone ph-list-checks text-blue-600"></i> Rekapitulasi Kehadiran KBM
                </h3>
                <button @click="showHistory = false" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-rose-100 hover:text-rose-600 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>

            
            <div class="overflow-y-auto p-0">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Mata Pelajaran</th>
                            <th class="px-6 py-4">Guru</th>
                            <th class="px-6 py-4 text-center">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(isset($teaching_journals)): ?>
                            <?php $__currentLoopData = $teaching_journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $attendance = $journal->attendances->first(function ($att) use ($student) {
                                        return (string)$att->student_id === (string)$student->id;
                                    });
                                    $status = $attendance ? $attendance->status : null;
                                    if ($journal->status == 'closed' && !$status) $status = 'alpha';
                                    
                                    $badge = match($status) {
                                        'present' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'sick' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'permission' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'alpha' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-500 border-slate-200'
                                    };
                                    $label = match($status) {
                                        'present' => 'Hadir', 'sick' => 'Sakit', 'permission' => 'Izin', 'alpha' => 'Alpha', default => 'Belum Ada'
                                    };
                                ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-700">
                                        <?php echo e(\Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y')); ?>

                                        <span class="block text-[10px] text-slate-400 font-normal">
                                            <?php echo e(\Carbon\Carbon::parse($journal->started_at)->format('H:i')); ?> WIB
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-blue-600">
                                        <?php echo e($journal->schedule?->subject?->name ?? '-'); ?>

                                        <span class="block text-[10px] text-slate-500 font-normal truncate max-w-[200px]">
                                            Topik: <?php echo e($journal->topic ?? '-'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <?php echo e($journal->schedule?->teacher?->name ?? '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border <?php echo e($badge); ?>">
                                            <?php echo e($label); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-kbm.blade.php ENDPATH**/ ?>