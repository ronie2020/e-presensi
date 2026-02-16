<div x-data="{
        showDetail: false,
    }" class="space-y-6 animate-in fade-in duration-500">
    
    
    <div x-data="portalPrayerWidget()" x-init="init()" class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[2rem] p-6 text-white shadow-lg relative overflow-hidden group">
        <!-- Background Decoration -->
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-1000"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-black/10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            
            <div class="text-center md:text-left">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/20 text-[10px] font-bold uppercase tracking-wider mb-2 backdrop-blur-sm">
                    <i class="ph-fill ph-map-pin"></i> <span x-text="locationName">Memuat...</span>
                </div>
                <h3 class="text-2xl md:text-3xl font-black tracking-tight mb-0.5" x-text="nextEventName">...</h3>
                <p class="text-emerald-100 text-xs font-medium opacity-90">
                    <span x-text="countdown" class="font-mono font-bold">00:00:00</span> Menuju Waktu Berikutnya
                </p>
            </div>

            
            <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                <div class="flex md:grid md:grid-cols-6 gap-2 min-w-max">
                    <template x-for="(time, name) in schedule" :key="name">
                        <div class="flex flex-col items-center justify-center p-2.5 rounded-xl border transition-all min-w-[80px]"
                            :class="currentEvent === name ? 'bg-white text-emerald-700 border-white shadow-md scale-105 font-bold' : 'bg-white/10 text-white border-white/10'">
                            <span class="text-[9px] uppercase tracking-wider opacity-80 mb-0.5" x-text="name"></span>
                            <span class="text-xs" :class="currentEvent === name ? 'font-black' : 'font-medium'" x-text="time"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    
    <?php
        // --- KONFIGURASI TANGGAL 1 RAMADHAN ---
        // (Sesuaikan dengan tahun berjalan jika perlu)
        $startRamadan = \Carbon\Carbon::parse('2026-02-18'); 
        $currentDate = \Carbon\Carbon::parse($today);
        
        // Hitung Hari ke-berapa
        $ramadanDay = intval($startRamadan->diffInDays($currentDate)) + 1;
        $isBeforeRamadan = $currentDate->lt($startRamadan);
        
        // --- LOGIKA PROGRESS HARIAN ---
        $totalTarget = 13; 
        $currentScore = 0;
        $isFriday = $currentDate->isFriday();
        
        if ($isFriday) {
            $totalTarget = 14; 
        }

        // Cek log
        if($todayRamadanLog) {
            // 1. Puasa
            if($todayRamadanLog->is_fasting) $currentScore++;
            
            // 2. Shalat Wajib
            foreach(['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $p) {
                if($todayRamadanLog->prayers[$p] ?? false) $currentScore++;
            }
            
            // 3. Sunnah
            foreach(['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah'] as $s) {
                if($todayRamadanLog->sunnah_deeds[$s] ?? false) $currentScore++;
            }
            
            // 4. Tilawah
            if($todayRamadanLog->tadarus_surah) $currentScore++;
            
            // 5. Jumat
            if ($isFriday && !empty($todayRamadanLog->friday_khotib)) {
                $currentScore++;
            }

            // 6. KULTUM
            if (!empty($todayRamadanLog->kultum_summary)) {
                $currentScore++;
            }
        }

        $progressPercent = $totalTarget > 0 ? ($currentScore / $totalTarget) * 100 : 0;
        
        $progressColor = 'text-emerald-400';
        if($progressPercent < 30) $progressColor = 'text-rose-400';
        elseif($progressPercent < 70) $progressColor = 'text-amber-400';
    ?>

    
    <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/20 rounded-full blur-[80px] -mr-32 -mt-32 group-hover:bg-indigo-600/30 transition-all duration-1000"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-600/20 rounded-full blur-[60px] -ml-20 -mb-20"></div>
        <div class="absolute top-4 right-4 opacity-10"><i class="ph-fill ph-moon-stars text-8xl"></i></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
            
            <div class="relative w-32 h-32 shrink-0 group/circle">
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
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div>
                        
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-800 border border-slate-700 rounded-lg text-slate-300 text-[10px] font-black uppercase tracking-widest mb-2 shadow-sm">
                            <i class="ph-fill ph-calendar-check"></i>
                            <?php if($isBeforeRamadan): ?>
                                Menuju Ramadhan
                            <?php else: ?>
                                Ramadhan Hari Ke-<?php echo e($ramadanDay); ?>

                            <?php endif; ?>
                        </div>

                        <h2 class="text-2xl font-black mb-1 leading-tight">Jurnal Ibadah</h2>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-lg">
                            "Barangsiapa berpuasa Ramadhan atas dasar iman dan mengharap pahala dari Allah, maka dosanya yang telah lalu akan diampuni."
                        </p>
                    </div>
                    
                    
                    <div class="hidden md:block text-right">
                        <div class="text-3xl font-black text-slate-700/30 select-none"><?php echo e(\Carbon\Carbon::parse($today)->format('d')); ?></div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest -mt-1"><?php echo e(\Carbon\Carbon::parse($today)->format('M Y')); ?></div>
                    </div>
                </div>
                
                
                <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-6">
                    <span class="px-3 py-1 rounded-full text-xs font-bold border <?php echo e(optional($todayRamadanLog)->is_fasting ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-200' : 'bg-slate-700 border-slate-600 text-slate-300'); ?>">
                        <?php echo e(optional($todayRamadanLog)->is_fasting ? 'Berpuasa Hari Ini' : 'Belum Puasa'); ?>

                    </span>
                    <?php if($isFriday): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 border border-amber-500/50 text-amber-200 flex items-center gap-1 animate-pulse">
                            <i class="ph-fill ph-star"></i> Jumat Berkah: Laporan Jumat!
                        </span>
                    <?php endif; ?>
                </div>

                
                <div class="pt-6 mt-4 border-t border-white/10 flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                    <?php if(!$todayRamadanLog): ?>
                        <div class="flex items-center gap-2 text-amber-400 animate-pulse">
                            <i class="ph-fill ph-warning-circle"></i>
                            <span class="text-xs font-bold">Jurnal Kosong</span>
                        </div>
                        <a href="<?php echo e(route('student.ramadan.index')); ?>" class="group relative inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white hover:from-blue-400 hover:to-indigo-400 font-black rounded-xl transition-all shadow-lg shadow-blue-900/50 ring-2 ring-blue-500/50 hover:ring-white/50 active:scale-95">
                            <span>Isi Jurnal Sekarang</span>
                            <i class="ph-bold ph-pencil-simple group-hover:rotate-12 transition-transform"></i>
                        </a>
                    <?php else: ?>
                         <a href="<?php echo e(route('student.ramadan.index')); ?>" class="group relative inline-flex items-center gap-2 px-6 py-2.5 bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white font-bold text-xs rounded-xl transition-all border border-slate-700 hover:border-slate-500 active:scale-95">
                            <span>Update Data</span>
                            <i class="ph-bold ph-pencil-simple"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <?php
            $getStatus = function($condition) {
                return $condition 
                    ? ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'icon_color' => 'text-emerald-500', 'text' => 'text-slate-800', 'status' => 'Tercatat', 'check' => true]
                    : ['bg' => 'bg-slate-50', 'border' => 'border-slate-100', 'icon_color' => 'text-slate-300', 'text' => 'text-slate-400', 'status' => 'Belum', 'check' => false];
            };

            $gridItems = [];
            $log = $todayRamadanLog; 

            if ($isFriday) {
                $fridayFilled = !empty($log->friday_khotib);
                $gridItems[] = [
                    'label' => 'Laporan Jumat',
                    'icon' => 'mosque',
                    'bg' => $fridayFilled ? 'bg-emerald-50' : 'bg-amber-50', 
                    'border' => $fridayFilled ? 'border-emerald-200' : 'border-amber-200',
                    'icon_color' => $fridayFilled ? 'text-emerald-500' : 'text-amber-500',
                    'text' => 'text-slate-800',
                    'status' => $fridayFilled ? 'Sudah Diisi' : 'Wajib Diisi!',
                    'check' => $fridayFilled
                ];
            }

            // 1. PUASA 
            $gridItems[] = array_merge(['label' => 'Puasa Hari Ini', 'icon' => 'bowl-food'], $getStatus(optional($log)->is_fasting));
            
            // 2. SHALAT 5 WAKTU 
            $prayerCount = 0;
            if ($log && isset($log->prayers)) {
                $prayerCount = count(array_filter($log->prayers));
            }

            $gridItems[] = [
                'label' => 'Shalat Wajib', 
                'icon' => 'clock-afternoon',
                'bg' => ($prayerCount == 5) ? 'bg-blue-50' : 'bg-slate-50',
                'border' => ($prayerCount == 5) ? 'border-blue-200' : 'border-slate-100',
                'icon_color' => ($prayerCount >= 1) ? 'text-blue-500' : 'text-slate-300',
                'text' => 'text-slate-800',
                'status' => $prayerCount . '/5 Waktu',
                'check' => ($prayerCount == 5)
            ];

            // 3. TARAWIH 
            $isTarawih = $log && isset($log->sunnah_deeds['tarawih']) && $log->sunnah_deeds['tarawih'];
            $gridItems[] = array_merge(['label' => 'Shalat Tarawih', 'icon' => 'moon-stars'], $getStatus($isTarawih));

            // 4. TILAWAH 
            $isTilawah = !empty($log->tadarus_surah);
            $gridItems[] = [
                'label' => 'Tilawah Quran', 
                'icon' => 'book-open-text',
                'bg' => $isTilawah ? 'bg-amber-50' : 'bg-slate-50',
                'border' => $isTilawah ? 'border-amber-200' : 'border-slate-100',
                'icon_color' => $isTilawah ? 'text-amber-500' : 'text-slate-300',
                'text' => 'text-slate-800',
                'status' => $log->tadarus_surah ?? 'Belum ada',
                'check' => $isTilawah
            ];

            // 5. KULTUM 
            $kultumFilled = !empty($log->kultum_summary);
            $gridItems[] = [
                'label' => 'Laporan Kultum', 
                'icon' => 'microphone-stage',
                'bg' => $kultumFilled ? 'bg-purple-50' : 'bg-slate-50',
                'border' => $kultumFilled ? 'border-purple-200' : 'border-slate-100',
                'icon_color' => $kultumFilled ? 'text-purple-500' : 'text-slate-300',
                'text' => 'text-slate-800',
                'status' => $kultumFilled ? 'Tercatat' : 'Belum',
                'check' => $kultumFilled
            ];

            // 6. SUNNAH LAINNYA
            $sunnahCount = 0;
            if ($log && isset($log->sunnah_deeds)) {
                 $sunnahCount = ($log->sunnah_deeds['sedekah']??0) + ($log->sunnah_deeds['dhuha']??0) + ($log->sunnah_deeds['witir']??0) + ($log->sunnah_deeds['rawatib']??0);
            }

            $gridItems[] = [
                'label' => 'Sunnah Lainnya', 
                'icon' => 'sparkle',
                'bg' => 'bg-teal-50',
                'border' => 'border-teal-100',
                'icon_color' => 'text-teal-500',
                'text' => 'text-slate-800',
                'status' => $sunnahCount . ' Amalan',
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
            <p class="text-xs text-slate-400 italic font-medium">Belum ada nilai atau catatan baru dari guru.</p>
        </div>
    <?php endif; ?>
</div>


<script>
    function portalPrayerWidget() {
        return {
            city: 'Ciamis', // Ubah sesuai lokasi default sekolah
            country: 'Indonesia',
            
            schedule: {},
            nextEventName: 'Memuat...',
            countdown: '00:00:00',
            locationName: 'Memuat...',
            currentEvent: '',
            
            async init() {
                this.locationName = `${this.city}, ${this.country}`;
                await this.fetchTimes();
                setInterval(() => this.updateCountdown(), 1000);
            },

            async fetchTimes() {
                try {
                    const date = new Date();
                    const url = `https://api.aladhan.com/v1/timingsByCity/${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}?city=${this.city}&country=${this.country}&method=20`;
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    
                    if(data.code === 200) {
                        const timings = data.data.timings;
                        this.schedule = {
                            'Imsak': timings.Imsak,
                            'Subuh': timings.Fajr,
                            'Dzuhur': timings.Dhuhr,
                            'Ashar': timings.Asr,
                            'Maghrib': timings.Maghrib,
                            'Isya': timings.Isha
                        };
                        this.updateCountdown();
                    }
                } catch (e) {
                    console.error("Gagal mengambil jadwal shalat", e);
                    this.nextEventName = "Offline";
                }
            },

            updateCountdown() {
                const now = new Date();
                let nextTime = null;
                let nextName = '';
                let minDiff = Infinity;

                for (const [name, timeStr] of Object.entries(this.schedule)) {
                    const [hours, minutes] = timeStr.split(':');
                    const timeDate = new Date();
                    timeDate.setHours(hours, minutes, 0);

                    if (timeDate < now) continue;

                    const diff = timeDate - now;
                    if (diff < minDiff) {
                        minDiff = diff;
                        nextTime = timeDate;
                        nextName = name;
                    }
                }

                if (!nextTime && this.schedule['Imsak']) {
                    const [hours, minutes] = this.schedule['Imsak'].split(':');
                    nextTime = new Date();
                    nextTime.setDate(nextTime.getDate() + 1);
                    nextTime.setHours(hours, minutes, 0);
                    nextName = 'Imsak (Besok)';
                    minDiff = nextTime - now;
                }

                if (nextTime) {
                    const h = Math.floor(minDiff / (1000 * 60 * 60));
                    const m = Math.floor((minDiff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((minDiff % (1000 * 60)) / 1000);
                    
                    this.countdown = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    
                    if(nextName === 'Maghrib') {
                        this.nextEventName = 'Menuju Berbuka';
                    } else if (nextName === 'Imsak' || nextName === 'Imsak (Besok)') {
                        this.nextEventName = 'Menuju Imsak';
                    } else {
                        this.nextEventName = `Menuju ${nextName}`;
                    }
                    
                    this.currentEvent = nextName.replace(' (Besok)', '');
                }
            }
        }
    }
</script><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-ramadan-jurnal.blade.php ENDPATH**/ ?>