<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500 font-sans">
    
    
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
                $attendance = $journal->attendances->where('student_id', $student->id)->first();
                $status = $attendance ? $attendance->status : null;
                
                // Logika Auto-Alpha jika closed
                if($journal->status == 'closed' && !$status) $status = 'alpha';

                if($status == 'present') $stats['present']++;
                elseif($status == 'sick') $stats['sick']++;
                elseif($status == 'permission') $stats['permission']++;
                elseif($status == 'alpha') $stats['alpha']++;
            }
        }
    ?>

    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24 group overflow-hidden">
            
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                <i class="ph-duotone ph-chalkboard-teacher text-9xl text-blue-600"></i>
            </div>

            <div class="relative z-10">
                <h3 class="text-lg font-black text-slate-800 mb-1">Pantauan KBM</h3>
                <p class="text-slate-400 text-xs mb-6 font-medium">Ringkasan kehadiran di kelas (<?php echo e($stats['total']); ?> Sesi Terakhir).</p>

                
                <div class="flex items-end gap-2 h-24 mb-6 px-2">
                    
                    <div class="flex-1 flex flex-col items-center gap-1 group/bar">
                        <div class="w-full bg-emerald-100 rounded-t-lg relative h-full overflow-hidden">
                            <div style="height: <?php echo e($stats['total'] > 0 ? ($stats['present']/$stats['total'])*100 : 0); ?>%" class="absolute bottom-0 w-full bg-emerald-500 transition-all duration-1000 group-hover/bar:bg-emerald-400"></div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500">Hadir</span>
                    </div>
                    
                    <div class="flex-1 flex flex-col items-center gap-1 group/bar">
                        <div class="w-full bg-blue-100 rounded-t-lg relative h-full overflow-hidden">
                            <div style="height: <?php echo e($stats['total'] > 0 ? (($stats['sick']+$stats['permission'])/$stats['total'])*100 : 0); ?>%" class="absolute bottom-0 w-full bg-blue-500 transition-all duration-1000 group-hover/bar:bg-blue-400"></div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500">Izin</span>
                    </div>
                    
                    <div class="flex-1 flex flex-col items-center gap-1 group/bar">
                        <div class="w-full bg-rose-100 rounded-t-lg relative h-full overflow-hidden">
                            <div style="height: <?php echo e($stats['total'] > 0 ? ($stats['alpha']/$stats['total'])*100 : 0); ?>%" class="absolute bottom-0 w-full bg-rose-500 transition-all duration-1000 group-hover/bar:bg-rose-400"></div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500">Alpha</span>
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
                    <p class="text-[10px] text-slate-400 italic">
                        "Kehadiran di kelas adalah langkah awal menuju pemahaman materi yang sempurna."
                    </p>
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

                    <?php $__currentLoopData = $teaching_journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php
                            $attendance = $journal->attendances->where('student_id', $student->id)->first();
                            $status = $attendance ? $attendance->status : null;
                            
                            $statusConfig = [
                                'present' => ['color' => 'emerald', 'label' => 'Hadir', 'icon' => 'ph-check-circle'],
                                'sick' => ['color' => 'blue', 'label' => 'Sakit', 'icon' => 'ph-thermometer'],
                                'permission' => ['color' => 'amber', 'label' => 'Izin', 'icon' => 'ph-hand-waving'],
                                'alpha' => ['color' => 'rose', 'label' => 'Alpha', 'icon' => 'ph-x-circle'],
                                'default' => ['color' => 'slate', 'label' => 'Belum Absen', 'icon' => 'ph-question'],
                            ];

                            // Cek Alpha Otomatis
                            if ($journal->status == 'closed' && !$status) $status = 'alpha';
                            
                            $config = $statusConfig[$status] ?? $statusConfig['default'];
                        ?>

                        <div class="relative pl-10 group">
                            
                            <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-white border-2 border-slate-100 shadow-sm flex items-center justify-center z-10 group-hover:scale-110 transition-transform">
                                <i class="ph-bold ph-book-bookmark text-slate-400"></i>
                            </div>

                            
                            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 relative overflow-hidden">
                                
                                
                                <div class="absolute top-0 right-0">
                                    <div class="bg-<?php echo e($config['color']); ?>-50 text-<?php echo e($config['color']); ?>-600 px-4 py-1.5 rounded-bl-2xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 border-b border-l border-<?php echo e($config['color']); ?>-100">
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

                                        
                                        <div x-show="open" 
                                             x-transition.opacity
                                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
                                             style="display: none;">
                                            <div @click.away="open = false" class="relative max-w-3xl w-full">
                                                <button @click="open = false" class="absolute -top-10 right-0 text-white hover:text-rose-400">
                                                    <i class="ph-bold ph-x text-2xl"></i>
                                                </button>
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
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-kbm.blade.php ENDPATH**/ ?>