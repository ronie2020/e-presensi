<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php if($isAlumni): ?>
        <div class="md:col-span-3 bg-amber-50 border border-amber-200 rounded-[2rem] p-6 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden shadow-sm">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16"></div>
            <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-4xl shrink-0 z-10 shadow-inner border-2 border-white">
                <i class="ph-duotone ph-graduation-cap"></i>
            </div>
            <div class="flex-1 text-center md:text-left z-10 w-full">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-black text-amber-900 mb-1">Status Alumni</h3>
                        <p class="text-amber-800/80 text-sm">
                            Siswa ini dinyatakan <strong>LULUS</strong> pada tahun <?php echo e($student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year); ?>.
                        </p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-3">
                        <?php if($student->alumniProfile): ?>
                            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white rounded-xl border border-amber-200 shadow-sm">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Saat ini:</span>
                                <span class="font-bold text-amber-600"><?php echo e($student->alumniProfile->activity_status); ?></span>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('alumni.tracer')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-xl shadow-amber-600/30 transition-all animate-bounce hover:animate-none">
                                <i class="ph-bold ph-clipboard-text"></i> Isi Tracer Study
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i></div>
            
            <div class="relative z-10">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-1"><i class="ph-bold ph-calendar-check"></i> Kehadiran</h3>
                <div class="flex items-baseline gap-2 mb-4">
                    <?php 
                        $total_hari = ($hadir ?? 0) + ($sakit ?? 0) + ($izin ?? 0) + ($alpa ?? 0); 
                        $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; 
                    ?>
                    <span class="text-5xl font-black text-slate-800 tracking-tight"><?php echo e($persen); ?><span class="text-2xl text-slate-400">%</span></span>
                </div>
                
                <div class="w-full bg-slate-100 rounded-full h-2 mb-4 overflow-hidden">
                    <div class="h-full rounded-full <?php echo e($persen >= 90 ? 'bg-emerald-500' : 'bg-amber-500'); ?>" style="width: <?php echo e($persen); ?>%"></div>
                </div>

                <div class="flex gap-2">
                    <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Hadir: <?php echo e($hadir ?? 0); ?></span>
                    <span class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div> Alpa: <?php echo e($alpa ?? 0); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Card Poin Karakter -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-star text-9xl text-amber-400"></i></div>
        <div class="relative z-10 w-full">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1"><i class="ph-bold ph-medal"></i> Poin Karakter</h3>
                
                
                <?php 
                    $behaviorScore = 200 - ($total_violation_points ?? 0) + ($total_merit_points ?? 0);
                ?>
                <span class="px-2 py-1 rounded-lg text-xs font-black <?php echo e($behaviorScore >= 180 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'); ?>">
                    Skor: <?php echo e($behaviorScore); ?>

                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-emerald-50/80 p-4 rounded-2xl border border-emerald-100 text-center">
                    <p class="text-[10px] text-emerald-600 font-bold mb-1 uppercase tracking-wider">Kebaikan</p>
                    <p class="text-2xl font-black text-emerald-600">+<?php echo e($total_merit_points ?? 0); ?></p>
                </div>
                <div class="bg-rose-50/80 p-4 rounded-2xl border border-rose-100 text-center">
                    <p class="text-[10px] text-rose-600 font-bold mb-1 uppercase tracking-wider">Pelanggaran</p>
                    <p class="text-2xl font-black text-rose-600">-<?php echo e($total_violation_points ?? 0); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Literasi -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-books text-9xl text-purple-500"></i></div>
        <div class="relative z-10">
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-1"><i class="ph-bold ph-book-open"></i> Literasi</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl font-black text-slate-800 tracking-tight"><?php echo e($library_visits ?? 0); ?></span>
                <span class="text-xs text-slate-500 font-bold bg-slate-100 px-2 py-1 rounded-lg border border-slate-200">Kunjungan</span>
            </div>
            
            <div class="mt-6 bg-purple-50 p-3 rounded-xl border border-purple-100">
                <p class="text-xs text-purple-700 font-medium italic text-center">"Buku adalah jendela dunia."</p>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\tab-ringkasan.blade.php ENDPATH**/ ?>