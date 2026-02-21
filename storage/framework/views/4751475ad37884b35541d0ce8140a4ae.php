<div class="space-y-8 animate-in fade-in duration-500 font-sans">

    
    <?php
        $habits = $habits ?? collect([]);
        $totalEntries = $habits->count();
        
        // 1. HITUNG UDZUR SYAR'I
        // Filter hari dimana siswa mencentang udzur atau menulis catatan 'haid'
        $udzurCount = $habits->filter(function($h) {
            return ($h->is_udzur_syar_i ?? false) || (strtolower($h->notes ?? '') == 'haid');
        })->count();

        // 2. TENTUKAN PEMBAGI (DIVIDER)
        // Total hari wajib = Total Entry dikurangi Hari Udzur
        $obligatedEntries = $totalEntries - $udzurCount;
        $divider = $obligatedEntries > 0 ? $obligatedEntries : 1; 

        // 3. CONFIG ICON SHALAT
        $prayerConfig = [
            ['key' => 'prayer_subuh',   'label' => 'Subuh',   'icon' => 'ph-cloud-sun',      'color' => 'blue'],
            ['key' => 'prayer_dhuha',   'label' => 'Dhuha',   'icon' => 'ph-sun',            'color' => 'teal'],
            ['key' => 'prayer_dzuhur',  'label' => 'Dzuhur',  'icon' => 'ph-sun-dim',        'color' => 'orange'],
            ['key' => 'prayer_ashar',   'label' => 'Ashar',   'icon' => 'ph-cloud-fog',      'color' => 'amber'],
            ['key' => 'prayer_maghrib', 'label' => 'Maghrib', 'icon' => 'ph-moon-stars',     'color' => 'indigo'],
            ['key' => 'prayer_isya',    'label' => 'Isya',    'icon' => 'ph-moon',           'color' => 'slate'],
        ];
    ?>

    
    <?php if(isset($topRamadanStudents) && $topRamadanStudents->isNotEmpty()): ?>
        
        
        <div class="bg-gradient-to-br from-emerald-900 via-teal-800 to-emerald-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="ph-fill ph-mosque text-[150px]"></i>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 backdrop-blur-sm mb-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-100">Edisi Ramadhan</span>
                    </div>
                    <h2 class="text-3xl font-black mb-1">Papan Keagamaan</h2>
                    <p class="text-emerald-100/80 text-sm max-w-md">
                        Pantau terus ibadah harianmu, kumpulkan poin kebaikan, dan raih predikat siswa paling istiqomah.
                    </p>
                    
                    
                    <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3">
                        <a href="<?php echo e(route('student.ramadan.index')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-400 text-amber-900 font-black rounded-xl hover:bg-amber-300 transition shadow-lg shadow-amber-900/20 group">
                            <i class="ph-bold ph-pencil-simple"></i> Isi Jurnal Ramadhan
                        </a>
                        <?php if(isset($todayRamadanLog) && $todayRamadanLog): ?>
                            <div class="inline-flex items-center gap-2 px-4 py-3 bg-emerald-800/50 text-emerald-100 font-bold rounded-xl border border-emerald-700">
                                <i class="ph-fill ph-check-circle text-emerald-400"></i> Sudah Mengisi
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php
                    $myRank = $topRamadanStudents->search(function($s) {
                        return $s->id == Auth::guard('student')->id();
                    });
                    $myScore = $topRamadanStudents->where('id', Auth::guard('student')->id())->first()->ramadan_points ?? 0;
                    
                    // Ambil kembali Poin Kebiasaan Lama (Jika $totalPoints tidak ada di compact, hitung dari jumlah habit)
                    $regularPoints = isset($totalPoints) ? $totalPoints : ($habits->count() * 100);
                ?>
                <div class="flex flex-col sm:flex-row gap-3">
                    
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 p-4 rounded-2xl min-w-[130px] text-center">
                        <p class="text-[10px] font-bold text-amber-300 uppercase tracking-wider mb-1">Poin Ramadhan</p>
                        <div class="text-3xl font-black text-white mb-1"><?php echo e(number_format($myScore)); ?></div>
                        <?php if($myRank !== false): ?>
                            <div class="inline-block px-2 py-0.5 rounded bg-amber-500 text-amber-900 text-[10px] font-bold">
                                Peringkat #<?php echo e($myRank + 1); ?>

                            </div>
                        <?php else: ?>
                            <div class="text-[10px] text-white/60">Belum masuk Top 10</div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="bg-black/20 backdrop-blur-md border border-white/5 p-4 rounded-2xl min-w-[130px] text-center">
                        <p class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider mb-1">Total Kebaikan</p>
                        <div class="text-3xl font-black text-white mb-1"><?php echo e(number_format($regularPoints)); ?></div>
                        <div class="text-[10px] text-emerald-100/70 mt-1">Akumulasi Selamanya</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php $__currentLoopData = $topRamadanStudents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $winner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-5 -mr-4 -mt-4">
                    <i class="ph-fill ph-crown text-6xl text-slate-800"></i>
                </div>
                <div class="w-12 h-12 rounded-full border-2 <?php echo e($index == 0 ? 'border-amber-400 p-0.5' : 'border-slate-200'); ?> shrink-0">
                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($winner->name)); ?>&background=random" class="w-full h-full rounded-full object-cover">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black w-5 h-5 rounded-full flex items-center justify-center <?php echo e($index == 0 ? 'bg-amber-400 text-white' : 'bg-slate-100 text-slate-500'); ?>">
                            <?php echo e($index + 1); ?>

                        </span>
                        <p class="text-sm font-bold text-slate-800 truncate max-w-[120px]"><?php echo e(strtok($winner->name, ' ')); ?></p>
                    </div>
                    <p class="text-xs text-emerald-600 font-bold mt-0.5"><?php echo e(number_format($winner->ramadan_points)); ?> Poin</p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

     <?php else: ?>
        
        <div class="flex items-center justify-between bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-slate-800">Jurnal Kebiasaan Baik</h2>
                <p class="text-slate-400 text-sm">Bangun karakter positif dengan 7 kebiasaan harian.</p>
            </div>
            <a href="<?php echo e(route('student.habits.index')); ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-lg shadow-blue-200">
                <i class="ph-bold ph-plus-circle"></i> Isi Jurnal
            </a>
        </div>
    <?php endif; ?>


    
    
    <?php
        $chartData = [];
        $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $todayJakarta = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();

        for ($i = 6; $i >= 0; $i--) {
            $date = $todayJakarta->copy()->subDays($i);
            $dayName = $days[$date->dayOfWeek];
            
            $entry = $habits->first(function($h) use ($date) {
                return \Carbon\Carbon::parse($h->report_date)->isSameDay($date);
            });
            
            // Cek Udzur per hari
            $isUdzur = $entry ? (($entry->is_udzur_syar_i ?? false) || (strtolower($entry->notes ?? '') == 'haid')) : false;

            $score = 0;
            if($entry && !$isUdzur) {
                if($entry->prayer_subuh) $score++;
                if($entry->prayer_dhuha) $score++;
                if($entry->prayer_dzuhur) $score++;
                if($entry->prayer_ashar) $score++;
                if($entry->prayer_maghrib) $score++;
                if($entry->prayer_isya) $score++;
            } elseif ($isUdzur) {
                $score = 6; // Poin penuh visual untuk Udzur
            }
            
            $chartData[] = [
                'day' => $dayName,
                'score' => $score,
                'height' => ($score / 6) * 100,
                'is_today' => $i === 0,
                'is_udzur' => $isUdzur,
                'has_entry' => !is_null($entry)
            ];
        }
    ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                        <i class="ph-duotone ph-chart-bar text-blue-600"></i> Konsistensi Shalat
                    </h3>
                    <p class="text-xs text-slate-400">7 Hari Terakhir</p>
                </div>
                
                <?php if(collect($chartData)->where('is_udzur', true)->count() > 0): ?>
                <div class="flex items-center gap-2 px-3 py-1 bg-pink-50 text-pink-600 rounded-full text-[10px] font-bold border border-pink-100">
                    <span class="w-2 h-2 rounded-full bg-pink-500"></span> Masa Udzur
                </div>
                <?php endif; ?>
            </div>

            <div class="flex items-end justify-between gap-3 h-40 w-full px-2">
                <?php $__currentLoopData = $chartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-col items-center gap-2 flex-1 group relative h-full justify-end">
                        <div class="w-full max-w-[40px] bg-slate-50 rounded-t-xl relative overflow-hidden h-full flex items-end transition-all hover:bg-slate-100">
                            <?php
                                $barColor = 'bg-slate-200';
                                if ($data['has_entry']) {
                                    $barColor = $data['is_udzur'] ? 'bg-pink-400' : 
                                        ($data['score'] >= 5 ? 'bg-emerald-400' : 
                                        ($data['score'] >= 3 ? 'bg-amber-400' : 'bg-rose-300'));
                                }
                            ?>
                            <div style="height: <?php echo e($data['height']); ?>%" class="w-full rounded-t-xl transition-all duration-1000 ease-out <?php echo e($barColor); ?>"></div>
                        </div>
                        <p class="text-[10px] font-bold <?php echo e($data['is_today'] ? 'text-blue-600' : 'text-slate-500'); ?>"><?php echo e($data['day']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="bg-gradient-to-b from-slate-900 to-slate-800 rounded-[2rem] p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="ph-fill ph-medal text-8xl"></i>
            </div>
            <h3 class="font-black text-xl mb-4 relative z-10">Lencana</h3>
            
            <?php
                $badges = [
                    ['icon' => 'ph-sun-horizon', 'bg' => 'bg-amber-500', 'active' => $habits->where('prayer_dhuha', 1)->count() >= 10, 'label' => 'Ahli Dhuha'],
                    ['icon' => 'ph-book-open', 'bg' => 'bg-emerald-500', 'active' => $habits->whereNotNull('odoa_audio_path')->count() >= 5, 'label' => 'Qari'],
                    ['icon' => 'ph-fire', 'bg' => 'bg-rose-500', 'active' => $habits->count() >= 30, 'label' => 'Istiqomah'],
                ];
            ?>

            <div class="grid grid-cols-3 gap-3 relative z-10">
                <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-col items-center gap-2 p-3 rounded-xl <?php echo e($badge['active'] ? 'bg-white/10 border border-white/20' : 'bg-white/5 border border-white/5 opacity-50 grayscale'); ?>">
                        <div class="w-10 h-10 rounded-full <?php echo e($badge['active'] ? $badge['bg'] : 'bg-slate-700'); ?> flex items-center justify-center text-white shadow-lg">
                            <i class="ph-fill <?php echo e($badge['icon']); ?> text-lg"></i>
                        </div>
                        <p class="text-[9px] font-bold text-center"><?php echo e($badge['label']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-6 pt-4 border-t border-white/10 text-center relative z-10">
                <p class="text-[10px] text-slate-400">Konsisten adalah kunci keberhasilan.</p>
            </div>
        </div>
    </div>

    
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                <i class="ph-duotone ph-chart-pie-slice text-blue-600"></i> Rekapitulasi Total
            </h3>
            
            
            <?php if($udzurCount > 0): ?>
                <span class="text-xs font-bold text-pink-500 bg-pink-50 px-3 py-1.5 rounded-xl border border-pink-100 flex items-center gap-1.5 animate-pulse">
                    <i class="ph-fill ph-info"></i> <?php echo e($udzurCount); ?> Hari Udzur (Dikecualikan)
                </span>
            <?php endif; ?>
        </div>

        <?php if($totalEntries > 0): ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php $__currentLoopData = $prayerConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prayer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $count = $habits->where($prayer['key'], 1)->count();
                        // Gunakan $divider yang sudah dikurangi udzur
                        $percentage = $divider > 0 ? round(($count / $divider) * 100) : 0;
                        if($percentage > 100) $percentage = 100; // Cap at 100%

                        $strokeColor = match($prayer['color']) {
                            'blue' => 'text-blue-500', 'teal' => 'text-teal-500', 'orange' => 'text-orange-500',
                            'amber' => 'text-amber-500', 'indigo' => 'text-indigo-500', 'slate' => 'text-slate-500',
                            default => 'text-gray-500'
                        };
                        $iconColor = match($prayer['color']) {
                            'blue' => 'text-blue-600', 'teal' => 'text-teal-600', 'orange' => 'text-orange-600',
                            'amber' => 'text-amber-600', 'indigo' => 'text-indigo-600', 'slate' => 'text-slate-600',
                            default => 'text-gray-600'
                        };
                    ?>

                    <div class="bg-white p-4 rounded-[1.5rem] border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center gap-2 hover:shadow-md transition-all group relative overflow-hidden">
                        <div class="relative w-16 h-16 group-hover:scale-105 transition-transform">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                <path class="text-slate-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                                <path class="<?php echo e($strokeColor); ?> transition-all duration-1000 ease-out" stroke-dasharray="<?php echo e($percentage); ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-[10px] font-black text-slate-800"><?php echo e($percentage); ?>%</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-700 flex items-center justify-center gap-1">
                                <i class="ph-fill <?php echo e($prayer['icon']); ?> <?php echo e($iconColor); ?>"></i> <?php echo e($prayer['label']); ?>

                            </h4>
                            <p class="text-[10px] text-slate-400 font-medium mt-1">
                                <?php echo e($count); ?> / <?php echo e($divider); ?> Hari
                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 rounded-2xl p-8 text-center border-2 border-dashed border-slate-200">
                <i class="ph-duotone ph-notebook text-4xl text-slate-300 mb-2"></i>
                <p class="text-slate-500 text-sm font-bold">Belum ada data statistik.</p>
                <p class="text-slate-400 text-xs">Mulai isi jurnal untuk melihat perkembanganmu.</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                <i class="ph-duotone ph-microphone-stage text-emerald-600"></i> Riwayat Hafalan (ODOA)
            </h3>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <?php
                $odoaEntries = $habits->whereNotNull('odoa_audio_path')->filter(fn($item) => !empty($item->odoa_audio_path));
            ?>

            <?php if($odoaEntries->count() > 0): ?>
                <div class="divide-y divide-slate-50 max-h-[400px] overflow-y-auto custom-scrollbar">
                    <?php $__currentLoopData = $odoaEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $habit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 hover:bg-emerald-50/30 transition-colors flex flex-col md:flex-row md:items-center gap-4 group">
                            <div class="flex items-center gap-3 md:w-1/4">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:bg-emerald-100 transition-colors">
                                    <i class="ph-fill ph-play-circle text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">
                                        <?php echo e(\Carbon\Carbon::parse($habit->created_at)->translatedFormat('d M Y')); ?>

                                    </p>
                                    <p class="text-[10px] text-slate-400 font-mono">
                                        <?php echo e(\Carbon\Carbon::parse($habit->created_at)->format('H:i')); ?> WIB
                                    </p>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <?php echo e($habit->odoa_surah ?? 'Tanpa Judul'); ?>

                                    <span class="px-2 py-0.5 rounded text-[9px] bg-slate-100 text-slate-500 border border-slate-200">Ayat: <?php echo e($habit->odoa_ayat ?? '-'); ?></span>
                                </h4>
                            </div>
                            <div class="md:w-1/3">
                                <audio controls class="w-full h-8 rounded-lg shadow-sm border border-slate-100 bg-slate-50/50">
                                    <source src="<?php echo e(asset('storage/'.$habit->odoa_audio_path)); ?>" type="audio/mpeg">
                                    Browser tidak mendukung audio.
                                </audio>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="p-10 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                        <i class="ph-duotone ph-microphone-slash text-3xl text-slate-300"></i>
                    </div>
                    <p class="text-slate-600 font-bold mb-1">Belum Ada Rekaman Hafalan</p>
                    <p class="text-slate-400 text-xs">Setorkan hafalan melalui menu 7 Kebiasaan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-keagamaan.blade.php ENDPATH**/ ?>