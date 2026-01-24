<?php
    // --- 1. PRE-PROCESSING DATA ---
    $habits = $habits ?? collect([]);
    $totalEntries = $habits->count();
    
    $udzurCount = $habits->filter(function($h) {
        return ($h->is_udzur_syar_i ?? false) || (strtolower($h->notes ?? '') == 'haid');
    })->count();

    // Total hari WAJIB shalat
    $obligatedEntries = $totalEntries - $udzurCount;
    $divider = $obligatedEntries > 0 ? $obligatedEntries : 1; 

    // --- A. LOGIKA BADGES / LENCANA ---
    $badges = [
        [
            'id' => 'dhuha_starter',
            'label' => 'Pejuang Dhuha',
            'icon' => 'ph-sun-horizon', 
            'bg_class' => 'from-amber-400 to-amber-600 shadow-amber-500/30',
            'condition' => $habits->where('prayer_dhuha', 1)->count() >= 10,
            'desc' => 'Melakukan Shalat Dhuha 10x'
        ],
        [
            'id' => 'odoa_lover',
            'label' => 'Cinta Qur\'an',
            'icon' => 'ph-book-open', 
            'bg_class' => 'from-emerald-400 to-emerald-600 shadow-emerald-500/30',
            'condition' => $habits->whereNotNull('odoa_audio_path')->count() >= 5,
            'desc' => 'Merekam ODOA 5x'
        ],
        [
            'id' => 'early_bird',
            'label' => 'Bangun Fajar',
            'icon' => 'ph-alarm', 
            'bg_class' => 'from-rose-400 to-rose-600 shadow-rose-500/30',
            'condition' => $habits->where('habit_1', 1)->count() >= 7,
            'desc' => 'Bangun pagi 7 hari'
        ],
        [
            'id' => 'discipline_master',
            'label' => 'Istiqomah',
            'icon' => 'ph-medal', 
            'bg_class' => 'from-blue-400 to-blue-600 shadow-blue-500/30',
            'condition' => $totalEntries >= 30,
            'desc' => 'Mengisi jurnal 30 hari'
        ],
    ];

    $nextBadge = collect($badges)->reject(fn($b) => $b['condition'])->first();

    // --- B. DATA GRAFIK MINGGUAN (PERBAIKAN TIMEZONE) ---
    $chartData = [];
    $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    
    // Gunakan Timezone Jakarta agar sinkron dengan Controller
    $todayJakarta = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();

    for ($i = 6; $i >= 0; $i--) {
        $date = $todayJakarta->copy()->subDays($i);
        $dayName = $days[$date->dayOfWeek];
        
        // Pencarian data yang lebih robust (Tahan format date)
        $entry = $habits->first(function($h) use ($date) {
            return \Carbon\Carbon::parse($h->report_date)->isSameDay($date);
        });
        
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
            'date' => $date->format('d/m'),
            'score' => $score,
            'height' => ($score / 6) * 100, // Persentase tinggi (0 - 100%)
            'is_today' => $i === 0,
            'is_udzur' => $isUdzur,
            'has_entry' => !is_null($entry) // Flag penanda ada data atau tidak
        ];
    }
    
    // Konfigurasi Visual Shalat
    $prayerConfig = [
        ['key' => 'prayer_subuh',   'label' => 'Subuh',   'icon' => 'ph-cloud-sun',      'color' => 'blue'],
        ['key' => 'prayer_dhuha',   'label' => 'Dhuha',   'icon' => 'ph-sun',            'color' => 'teal'],
        ['key' => 'prayer_dzuhur',  'label' => 'Dzuhur',  'icon' => 'ph-sun-dim',        'color' => 'orange'],
        ['key' => 'prayer_ashar',   'label' => 'Ashar',   'icon' => 'ph-cloud-fog',      'color' => 'amber'],
        ['key' => 'prayer_maghrib', 'label' => 'Maghrib', 'icon' => 'ph-moon-stars',     'color' => 'indigo'],
        ['key' => 'prayer_isya',    'label' => 'Isya',    'icon' => 'ph-moon',           'color' => 'slate'],
    ];
?>

<div class="space-y-8 animate-enter">

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-[2rem] p-6 text-white shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div>
                    <h3 class="font-black text-xl tracking-tight">Pencapaianmu</h3>
                    <p class="text-indigo-200 text-xs mt-1">Kumpulkan semua lencana!</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <i class="ph-fill ph-trophy text-yellow-400 text-xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-2 mb-4 relative z-10">
                <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-col items-center gap-1 group/badge cursor-help relative">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl transition-all duration-300
                            <?php echo e($badge['condition'] 
                                ? 'bg-gradient-to-br ' . $badge['bg_class'] . ' shadow-lg text-white scale-100' 
                                : 'bg-white/5 text-white/20 grayscale scale-90 border border-white/5'); ?>">
                            <i class="ph-duotone <?php echo e($badge['icon']); ?>"></i>
                        </div>
                        
                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 w-max max-w-[120px] bg-black/80 backdrop-blur text-white text-[10px] p-2 rounded-lg opacity-0 group-hover/badge:opacity-100 transition-opacity pointer-events-none text-center z-20 shadow-xl border border-white/10">
                            <p class="font-bold"><?php echo e($badge['label']); ?></p>
                            <p class="text-white/70"><?php echo e($badge['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($nextBadge): ?>
                <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm border border-white/10 flex items-center gap-3 relative z-10">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                        <i class="ph-bold ph-lock-key text-white/70"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-indigo-200 uppercase tracking-wider font-bold">Target Selanjutnya</p>
                        <p class="text-xs font-bold text-white"><?php echo e($nextBadge['label']); ?> <span class="font-normal text-indigo-200">(<?php echo e($nextBadge['desc']); ?>)</span></p>
                    </div>
                </div>
            <?php else: ?>
                 <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm border border-white/10 text-center relative z-10">
                    <p class="text-xs font-bold text-emerald-300 flex items-center justify-center gap-2">
                        <i class="ph-fill ph-crown"></i> Semua Lencana Terbuka!
                    </p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-slate-700 text-lg">Konsistensi Ibadah</h3>
                    <p class="text-xs text-slate-400">7 Hari Terakhir</p>
                </div>
                
                <div class="flex gap-2">
                    <div class="px-3 py-1 bg-pink-50 text-pink-600 rounded-full text-xs font-bold border border-pink-100 flex items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-pink-500"></div> Udzur
                    </div>
                    <div class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-100">
                        Target: 6 Waktu
                    </div>
                </div>
            </div>

            
            <div class="flex items-end justify-between gap-2 h-32 md:h-40 w-full px-2">
                <?php $__currentLoopData = $chartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-col items-center gap-2 flex-1 group relative h-full justify-end">
                        
                        
                        <div class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-[10px] py-1 px-2 rounded z-10 whitespace-nowrap">
                            <?php if($data['is_udzur']): ?>
                                Sedang Udzur
                            <?php elseif($data['has_entry']): ?>
                                <?php echo e($data['score']); ?> Waktu Shalat
                            <?php else: ?>
                                Belum Mengisi
                            <?php endif; ?>
                        </div>

                        
                        <div class="w-full max-w-[40px] bg-slate-50 rounded-t-xl relative overflow-hidden h-full flex items-end transition-all hover:bg-slate-100 border border-slate-100 border-b-0">
                            
                            <?php
                                $barColor = 'bg-slate-200'; // Default kosong
                                
                                if ($data['has_entry']) {
                                    if ($data['is_udzur']) {
                                        $barColor = 'bg-pink-400'; 
                                    } elseif ($data['is_today']) {
                                        $barColor = 'bg-blue-500';
                                    } else {
                                        $barColor = match(true) {
                                            $data['score'] >= 5 => 'bg-emerald-400',
                                            $data['score'] >= 3 => 'bg-amber-400',
                                            default => 'bg-rose-300'
                                        };
                                    }
                                }
                            ?>

                            <div style="height: <?php echo e($data['height']); ?>%" 
                                 class="w-full rounded-t-xl transition-all duration-1000 ease-out relative group-hover:opacity-90 <?php echo e($barColor); ?>">
                                 
                                 <?php if($data['is_udzur']): ?>
                                    <div class="absolute bottom-2 left-0 right-0 text-center text-white/80 text-xs animate-pulse">
                                        <i class="ph-bold ph-flower-lotus"></i>
                                    </div>
                                 <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-center h-6">
                            <p class="text-[10px] font-bold <?php echo e($data['is_today'] ? 'text-blue-600' : 'text-slate-500'); ?>"><?php echo e($data['day']); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                <i class="ph-duotone ph-chart-pie-slice text-blue-600"></i> Rekapitulasi Total
            </h3>
            <?php if($udzurCount > 0): ?>
                <span class="text-xs font-bold text-pink-500 bg-pink-50 px-2 py-1 rounded-lg border border-pink-100">
                    <i class="ph-fill ph-info"></i> <?php echo e($udzurCount); ?> Hari Udzur (Dikecualikan)
                </span>
            <?php endif; ?>
        </div>

        <?php if($totalEntries > 0): ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php $__currentLoopData = $prayerConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prayer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $count = $habits->where($prayer['key'], 1)->count();
                        $percentage = $divider > 0 ? round(($count / $divider) * 100) : 0;
                        
                        $colorClass = match($prayer['color']) {
                            'blue' => 'text-blue-600', 'teal' => 'text-teal-600', 'orange' => 'text-orange-600',
                            'amber' => 'text-amber-600', 'indigo' => 'text-indigo-600', 'slate' => 'text-slate-600',
                            default => 'text-gray-600'
                        };
                        $strokeColor = match($prayer['color']) {
                            'blue' => 'stroke-blue-500', 'teal' => 'stroke-teal-500', 'orange' => 'stroke-orange-500',
                            'amber' => 'stroke-amber-500', 'indigo' => 'stroke-indigo-500', 'slate' => 'stroke-slate-500',
                            default => 'stroke-gray-500'
                        };
                    ?>

                    <div class="bg-white p-4 rounded-[1.5rem] border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center gap-2 hover:shadow-md transition-all group">
                        <div class="relative w-16 h-16 group-hover:scale-105 transition-transform">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                <path class="text-slate-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                                <path class="<?php echo e($strokeColor); ?> transition-all duration-1000 ease-out" stroke-dasharray="<?php echo e($percentage); ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="3" stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="ph-fill <?php echo e($prayer['icon']); ?> <?php echo e($colorClass); ?> text-xl"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-700"><?php echo e($prayer['label']); ?></h4>
                            <p class="text-[10px] text-slate-400 font-medium">
                                <?php echo e($count); ?>x / <?php echo e($divider); ?> Wajib
                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 rounded-2xl p-8 text-center border-2 border-dashed border-slate-200">
                <i class="ph-duotone ph-notebook text-4xl text-slate-300 mb-2"></i>
                <p class="text-slate-500 text-sm font-bold">Belum ada data jurnal keagamaan.</p>
                <p class="text-slate-400 text-xs">Isi jurnal harianmu untuk melihat statistik.</p>
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