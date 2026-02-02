<div x-data="{
        showDetail: false,
    }" class="space-y-8 animate-in fade-in duration-500">
    
    
    <?php
        // Total Poin Ideal Harian Dasar:
        // 1 (Puasa) + 5 (Wajib) + 5 (Sunnah) + 1 (Tilawah) = 12 Poin
        $totalTarget = 12; 
        $currentScore = 0;
        
        // Cek Hari Jumat
        $isFriday = \Carbon\Carbon::parse($today)->isFriday();
        
        // Jika Jumat, Target nambah 1 (Laporan Jumat)
        if ($isFriday) {
            $totalTarget = 13;
        }

        if($todayRamadanLog) {
            // 1. Puasa
            if($todayRamadanLog->is_fasting) $currentScore++;
            
            // 2. Shalat Wajib (Loop 5 waktu)
            foreach(['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $p) {
                if($todayRamadanLog->prayers[$p] ?? false) $currentScore++;
            }

            // 3. Sunnah (Loop 5 item)
            foreach(['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah'] as $s) {
                if($todayRamadanLog->sunnah_deeds[$s] ?? false) $currentScore++;
            }

            // 4. Tilawah
            if($todayRamadanLog->tadarus_surah) $currentScore++;

            // 5. KHUSUS JUMAT: Cek apakah Khotib sudah diisi
            if ($isFriday && !empty($todayRamadanLog->friday_khotib)) {
                $currentScore++;
            }
        }

        // Hindari pembagian dengan nol
        $progressPercent = $totalTarget > 0 ? ($currentScore / $totalTarget) * 100 : 0;
        
        // Warna progress bar
        $progressColor = 'text-emerald-400';
        if($progressPercent < 30) $progressColor = 'text-rose-400';
        elseif($progressPercent < 70) $progressColor = 'text-amber-400';
    ?>

    
    <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-600/20 rounded-full blur-[80px] -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-teal-600/20 rounded-full blur-[60px] -ml-20 -mb-20"></div>
        <div class="absolute top-4 right-4 opacity-10"><i class="ph-fill ph-moon-stars text-8xl"></i></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
            
            <div class="relative w-32 h-32 shrink-0 group">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-800"></circle>
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" 
                            class="<?php echo e($progressColor); ?> transition-all duration-1000 ease-out shadow-[0_0_15px_currentColor]"
                            stroke-dasharray="351.8"
                            stroke-dashoffset="<?php echo e(351.8 - (351.8 * $progressPercent / 100)); ?>"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-white tracking-tight"><?php echo e(round($progressPercent)); ?>%</span>
                    <span class="text-[9px] uppercase text-slate-400 font-bold tracking-widest">Tuntas</span>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black mb-1">Jurnal Ibadah Ramadhan</h2>
                        <p class="text-emerald-100/70 text-sm leading-relaxed max-w-lg">
                            "Barangsiapa berpuasa Ramadhan atas dasar iman dan mengharap pahala dari Allah, maka dosanya yang telah lalu akan diampuni."
                        </p>
                    </div>
                </div>
                
                
                <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold border <?php echo e($todayRamadanLog && $todayRamadanLog->is_fasting ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-200' : 'bg-slate-700 border-slate-600 text-slate-300'); ?>">
                        <?php echo e($todayRamadanLog && $todayRamadanLog->is_fasting ? 'Berpuasa Hari Ini' : 'Belum Puasa'); ?>

                    </span>
                    <?php if($isFriday): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 border border-amber-500/50 text-amber-200 flex items-center gap-1 animate-pulse">
                            <i class="ph-fill ph-star"></i> Jumat Berkah: Jangan Lupa Laporan Jumat!
                        </span>
                    <?php endif; ?>
                </div>

                
                <div class="pt-6 mt-2 border-t border-white/10 flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                    <?php if(!$todayRamadanLog): ?>
                        <div class="flex items-center gap-2 text-amber-400 animate-pulse">
                            <i class="ph-fill ph-warning-circle"></i>
                            <span class="text-xs font-bold">Jurnal Kosong</span>
                        </div>
                        <a href="<?php echo e(route('student.ramadan.index')); ?>" class="group relative inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white hover:from-emerald-400 hover:to-teal-400 font-black rounded-xl transition-all shadow-lg shadow-emerald-900/50 ring-2 ring-emerald-500/50 hover:ring-white/50">
                            <span>Isi Jurnal Sekarang</span>
                            <i class="ph-bold ph-pencil-simple group-hover:rotate-12 transition-transform"></i>
                        </a>
                    <?php else: ?>
                         <a href="<?php echo e(route('student.ramadan.index')); ?>" class="group relative inline-flex items-center gap-2 px-6 py-2 bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white font-bold text-xs rounded-xl transition-all border border-slate-700">
                            <span>Update / Edit Data</span>
                            <i class="ph-bold ph-pencil-simple"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <?php
            // Helper untuk status icon & warna
            $getStatus = function($condition) {
                return $condition 
                    ? ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'icon_color' => 'text-emerald-500', 'text' => 'text-slate-800', 'status' => 'Tercatat', 'check' => true]
                    : ['bg' => 'bg-slate-50', 'border' => 'border-slate-100', 'icon_color' => 'text-slate-300', 'text' => 'text-slate-400', 'status' => 'Belum', 'check' => false];
            };

            $gridItems = [];

            // [LOGIKA BARU] JIKA HARI JUMAT, TAMPILKAN KARTU KHUSUS DI URUTAN PERTAMA
            if ($isFriday) {
                $fridayFilled = !empty($todayRamadanLog->friday_khotib);
                $gridItems[] = [
                    'label' => 'Laporan Jumat',
                    'icon' => 'mosque',
                    'bg' => $fridayFilled ? 'bg-emerald-50' : 'bg-amber-50', // Kuning jika belum diisi agar eye-catching
                    'border' => $fridayFilled ? 'border-emerald-200' : 'border-amber-200',
                    'icon_color' => $fridayFilled ? 'text-emerald-500' : 'text-amber-500',
                    'text' => 'text-slate-800',
                    'status' => $fridayFilled ? 'Sudah Diisi' : 'Wajib Diisi!',
                    'check' => $fridayFilled
                ];
            }

            // 1. PUASA
            $gridItems[] = array_merge(['label' => 'Puasa Hari Ini', 'icon' => 'bowl-food'], $getStatus($todayRamadanLog->is_fasting ?? false));
            
            // 2. SHALAT 5 WAKTU
            $gridItems[] = [
                'label' => 'Shalat Wajib', 
                'icon' => 'clock-afternoon',
                'bg' => ($todayRamadanLog && count(array_filter($todayRamadanLog->prayers)) == 5) ? 'bg-blue-50' : 'bg-slate-50',
                'border' => ($todayRamadanLog && count(array_filter($todayRamadanLog->prayers)) == 5) ? 'border-blue-200' : 'border-slate-100',
                'icon_color' => ($todayRamadanLog && count(array_filter($todayRamadanLog->prayers)) >= 1) ? 'text-blue-500' : 'text-slate-300',
                'text' => 'text-slate-800',
                'status' => ($todayRamadanLog ? count(array_filter($todayRamadanLog->prayers)) : 0) . '/5 Waktu',
                'check' => ($todayRamadanLog && count(array_filter($todayRamadanLog->prayers)) == 5)
            ];

            // 3. TARAWIH
            $gridItems[] = array_merge(['label' => 'Shalat Tarawih', 'icon' => 'moon-stars'], $getStatus($todayRamadanLog->sunnah_deeds['tarawih'] ?? false));

            // 4. TILAWAH
            $gridItems[] = [
                'label' => 'Tilawah Quran', 
                'icon' => 'book-open-text',
                'bg' => ($todayRamadanLog->tadarus_surah ?? false) ? 'bg-amber-50' : 'bg-slate-50',
                'border' => ($todayRamadanLog->tadarus_surah ?? false) ? 'border-amber-200' : 'border-slate-100',
                'icon_color' => ($todayRamadanLog->tadarus_surah ?? false) ? 'text-amber-500' : 'text-slate-300',
                'text' => 'text-slate-800',
                'status' => $todayRamadanLog->tadarus_surah ?? 'Belum ada',
                'check' => ($todayRamadanLog->tadarus_surah ?? false)
            ];

            // 5. SUNNAH LAINNYA (Summary)
            $gridItems[] = [
                'label' => 'Sunnah Lainnya', 
                'icon' => 'sparkle',
                'bg' => 'bg-purple-50',
                'border' => 'border-purple-100',
                'icon_color' => 'text-purple-500',
                'text' => 'text-slate-800',
                'status' => ($todayRamadanLog ? 
                    ( ($todayRamadanLog->sunnah_deeds['sedekah']??0) + ($todayRamadanLog->sunnah_deeds['dhuha']??0) + ($todayRamadanLog->sunnah_deeds['witir']??0) + ($todayRamadanLog->sunnah_deeds['rawatib']??0) ) 
                    : 0) . ' Amalan',
                'check' => false
            ];
        ?>

        <?php $__currentLoopData = $gridItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-5 rounded-2xl border <?php echo e($item['bg']); ?> <?php echo e($item['border']); ?> flex flex-col items-center text-center justify-center relative group transition-all hover:shadow-md h-full">
                <?php if($item['check']): ?>
                    <div class="absolute top-2 right-2 bg-white rounded-full p-0.5 shadow-sm text-emerald-500">
                        <i class="ph-fill ph-check-circle text-lg"></i>
                    </div>
                <?php endif; ?>

                <i class="ph-duotone ph-<?php echo e($item['icon']); ?> text-3xl mb-3 <?php echo e($item['icon_color']); ?>"></i>
                <h4 class="font-bold text-xs <?php echo e($item['text']); ?> mb-1 uppercase tracking-wider"><?php echo e($item['label']); ?></h4>
                <p class="text-xs font-bold <?php echo e($item['check'] ? 'text-slate-600' : 'text-slate-400'); ?>">
                    <?php echo e(Str::limit($item['status'], 15)); ?>

                </p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if(isset($lastVerifiedLog) && $lastVerifiedLog && $lastVerifiedLog->teacher_verified_at): ?>
    <div class="bg-gradient-to-br from-white to-emerald-50 p-6 rounded-[2rem] border border-emerald-100 shadow-sm relative overflow-hidden">
        
        <div class="absolute top-0 right-0 p-4 opacity-5"><i class="ph-fill ph-quotes text-8xl text-emerald-800"></i></div>
        
        <div class="flex flex-col sm:flex-row items-start gap-4 relative z-10">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm">
                <i class="ph-fill ph-chalkboard-teacher text-2xl"></i>
            </div>
            <div class="flex-1 w-full">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-bold text-emerald-900 text-sm">Feedback Guru Pembimbing</h3>
                        <p class="text-[10px] text-emerald-600 font-bold uppercase">
                            Menilai Jurnal Tanggal: <span class="text-slate-600"><?php echo e(\Carbon\Carbon::parse($lastVerifiedLog->date)->isoFormat('dd MMMM')); ?></span>
                        </p>
                    </div>
                    <div class="flex flex-col items-end">
                        <div class="bg-emerald-600 text-white px-3 py-1 rounded-lg text-lg font-black shadow-lg shadow-emerald-200">
                            <?php echo e($lastVerifiedLog->teacher_score); ?>

                        </div>
                        <div class="text-[9px] text-slate-400 font-bold uppercase mt-1">Nilai Guru</div>
                    </div>
                </div>
                
                <div class="bg-white/60 p-4 rounded-xl border border-emerald-100/50 backdrop-blur-sm mt-2">
                    <p class="text-sm text-slate-700 italic leading-relaxed">
                        "<?php echo e($lastVerifiedLog->teacher_note ?? 'Terus tingkatkan ibadahnya ya!'); ?>"
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
        
        <div class="text-center py-8 border border-dashed border-slate-200 rounded-[2rem] bg-slate-50/50">
            <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-400">
                <i class="ph-bold ph-chat-slash"></i>
            </div>
            <p class="text-xs text-slate-400 italic font-medium">Belum ada nilai atau catatan baru dari guru untuk jurnal terakhirmu.</p>
        </div>
    <?php endif; ?>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-ramadan-jurnal.blade.php ENDPATH**/ ?>