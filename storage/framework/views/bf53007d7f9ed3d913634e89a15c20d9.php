<div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-in fade-in duration-500">
    
    
    <?php if($isAlumni): ?>
        <div class="md:col-span-3 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-[2.5rem] p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden shadow-sm">
            
            <div class="absolute top-0 right-0 w-48 h-48 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16"></div>
            
            <div class="w-24 h-24 bg-white text-amber-600 rounded-full flex items-center justify-center text-5xl shrink-0 z-10 shadow-lg border-4 border-amber-100">
                <i class="ph-duotone ph-graduation-cap"></i>
            </div>
            
            <div class="flex-1 text-center md:text-left z-10 w-full">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest mb-2">
                            Alumni Sekolah
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-1">Selamat Mengabdi di Masyarakat!</h3>
                        <p class="text-slate-600 text-sm max-w-xl">
                            Siswa ini dinyatakan <strong>LULUS</strong> pada tahun <?php echo e($student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year); ?>. Tetap jaga nama baik almamater.
                        </p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-3">
                        <?php if($student->alumniProfile): ?>
                            <div class="flex flex-col items-end">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Status Saat Ini</span>
                                <span class="font-black text-amber-600 text-lg"><?php echo e($student->alumniProfile->activity_status); ?></span>
                            </div>
                        <?php else: ?>
                            
                            <a href="<?php echo e(Route::has('alumni.tracer') ? route('alumni.tracer') : '#'); ?>" class="group inline-flex items-center gap-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-xl shadow-amber-600/30 transition-all">
                                <i class="ph-bold ph-clipboard-text"></i> Isi Tracer Study
                                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        
        
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                <i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i>
            </div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="ph-bold ph-calendar-check"></i> Kehadiran
                    </h3>
                    <div class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold text-slate-500">Semester Ini</div>
                </div>

                <div class="flex items-baseline gap-2 mb-4">
                    <?php 
                        $total_hari = ($hadir ?? 0) + ($sakit ?? 0) + ($izin ?? 0) + ($alpa ?? 0); 
                        $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; 
                    ?>
                    <span class="text-5xl font-black text-slate-800 tracking-tight"><?php echo e($persen); ?><span class="text-2xl text-slate-400">%</span></span>
                </div>
                
                
                <div class="w-full bg-slate-100 rounded-full h-3 mb-4 overflow-hidden flex">
                    <div class="h-full bg-emerald-500" style="width: <?php echo e($persen); ?>%"></div>
                    <?php
                        $persenSakitIzin = $total_hari > 0 ? round((($sakit+$izin)/$total_hari)*100) : 0;
                    ?>
                    <div class="h-full bg-blue-400" style="width: <?php echo e($persenSakitIzin); ?>%"></div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="px-3 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-xs font-bold flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div> 
                        Hadir: <?php echo e($hadir ?? 0); ?>

                    </div>
                    <div class="px-3 py-2 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl text-xs font-bold flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-rose-500"></div> 
                        Alpa: <?php echo e($alpa ?? 0); ?>

                    </div>
                    
                    <div class="px-3 py-2 bg-blue-50 text-blue-700 border border-blue-100 rounded-xl text-xs font-bold flex items-center gap-2 col-span-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div> 
                        Sakit / Izin: <?php echo e(($sakit ?? 0) + ($izin ?? 0)); ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    
    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
            <i class="ph-fill ph-star text-9xl text-amber-400"></i>
        </div>
        <div class="relative z-10 w-full">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="ph-bold ph-medal"></i> Poin Karakter
                </h3>
                
                
                <?php 
                    $behaviorScore = 200 - ($total_violation_points ?? 0) + ($total_merit_points ?? 0);
                    $scoreColor = $behaviorScore >= 180 ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : ($behaviorScore >= 150 ? 'text-amber-600 bg-amber-50 border-amber-100' : 'text-rose-600 bg-rose-50 border-rose-100');
                ?>
                <span class="px-2 py-1 rounded-lg text-xs font-black border <?php echo e($scoreColor); ?>">
                    Total: <?php echo e($behaviorScore); ?>

                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 h-full">
                <div class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100 text-center flex flex-col justify-center hover:bg-emerald-50 transition-colors cursor-default">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-2 text-lg shadow-sm">
                        <i class="ph-bold ph-plus"></i>
                    </div>
                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider">Prestasi</p>
                    <p class="text-2xl font-black text-emerald-700"><?php echo e($total_merit_points ?? 0); ?></p>
                </div>
                <div class="bg-rose-50/50 p-4 rounded-2xl border border-rose-100 text-center flex flex-col justify-center hover:bg-rose-50 transition-colors cursor-default">
                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-2 text-lg shadow-sm">
                        <i class="ph-bold ph-minus"></i>
                    </div>
                    <p class="text-[10px] text-rose-600 font-bold uppercase tracking-wider">Pelanggaran</p>
                    <p class="text-2xl font-black text-rose-700"><?php echo e($total_violation_points ?? 0); ?></p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
            <i class="ph-fill ph-books text-9xl text-purple-500"></i>
        </div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="ph-bold ph-book-open"></i> Literasi
                </h3>
                
                <a href="<?php echo e(Route::has('student.library.index') ? route('student.library.index') : '#'); ?>" class="text-[10px] font-bold text-purple-600 hover:underline">
                    Lihat Katalog
                </a>
            </div>

            <?php
                // Gabungkan Kunjungan Fisik & E-Book Read
                // Pastikan variabel ebookHistory ada (dikirim dari controller)
                $ebookCount = isset($ebookHistory) ? $ebookHistory->count() : 0;
                $totalLiterasi = ($library_visits ?? 0) + $ebookCount;
            ?>

            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-5xl font-black text-slate-800 tracking-tight"><?php echo e($totalLiterasi); ?></span>
                <span class="text-xs text-slate-500 font-bold bg-slate-100 px-2 py-1 rounded-lg border border-slate-200">Aktivitas</span>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center p-3 rounded-xl bg-purple-50 border border-purple-100 hover:bg-purple-100 transition-colors">
                    <span class="text-xs font-bold text-purple-700 flex items-center gap-2">
                        <i class="ph-bold ph-book"></i> Pinjam Fisik
                    </span>
                    <span class="font-black text-purple-800"><?php echo e($library_visits ?? 0); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-xl bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-colors">
                    <span class="text-xs font-bold text-blue-700 flex items-center gap-2">
                        <i class="ph-bold ph-device-tablet-camera"></i> E-Book
                    </span>
                    <span class="font-black text-blue-800"><?php echo e($ebookCount); ?></span>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-ringkasan.blade.php ENDPATH**/ ?>