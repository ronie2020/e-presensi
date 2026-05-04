<div x-data="{
        showDetail: false,
    }" class="space-y-8 animate-in fade-in duration-500 font-sans">
    
    
    <?php
        // Menggunakan Config atau Default
        $ramadanStartStr = config('school.ramadan_start', '2026-02-19');
        $startRamadan = \Carbon\Carbon::parse($ramadanStartStr); 
        $currentDate = \Carbon\Carbon::parse($today); 
        
        // Hitung Hari ke-berapa (Hijriyah Logic Sederhana)
        $ramadanDay = intval($startRamadan->diffInDays($currentDate)) + 1;
        $isBeforeRamadan = $currentDate->lt($startRamadan);
        
        // Menggunakan var dari controller
        $isRamadanEnded = $isRamadanEnded ?? false;
        
        // --- LOGIKA PROGRESS HARIAN (ASLI) ---
        $totalTarget = 13; 
        $currentScore = 0;
        $isFriday = $currentDate->isFriday();
        
        if ($isFriday) {
            $totalTarget = 14; // Tambahan poin untuk Jumat
        }

        // Cek log dan hitung skor
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
            
            // 5. Jumat (Logika Jumat dipertahankan)
            if ($isFriday && !empty($todayRamadanLog->friday_khotib)) {
                $currentScore++;
            }

            // 6. KULTUM
            if (!empty($todayRamadanLog->kultum_summary)) {
                $currentScore++;
            }
        }

        $progressPercent = $totalTarget > 0 ? ($currentScore / $totalTarget) * 100 : 0;
        
        // Warna Progress (Elevate Theme)
        $progressColor = 'text-elevate-primary';
        $barColor = 'bg-elevate-primary';
        if($progressPercent < 30) { $progressColor = 'text-rose-500'; $barColor = 'bg-rose-500'; }
        elseif($progressPercent < 70) { $progressColor = 'text-elevate-peach'; $barColor = 'bg-elevate-peach'; }
    ?>

    <?php if(!$isRamadanEnded): ?>
    
    <div x-data="portalPrayerWidget()" x-init="init()" class="relative">
        
        
        <template x-if="isLoading">
            <div class="bg-slate-100 rounded-[2rem] p-6 shadow-sm animate-pulse h-48 w-full relative overflow-hidden border border-slate-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative z-10 h-full">
                    <div class="space-y-3 w-full md:w-1/3">
                        <div class="h-6 bg-slate-300 rounded-full w-32"></div>
                        <div class="h-10 bg-slate-300 rounded-lg w-48"></div>
                    </div>
                </div>
            </div>
        </template>

        
        <div x-show="!isLoading" 
             class="bg-gradient-to-b from-elevate-dark to-elevate-primary rounded-t-[3rem] rounded-b-[2rem] p-6 text-white shadow-xl shadow-elevate-dark/20 relative overflow-hidden group border-b-4 border-elevate-peach"
             style="display: none;">
            
            <!-- Ornament Background (Islamic Pattern) -->
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            
            <!-- Mosque Silhouette (Bottom) -->
            <div class="absolute bottom-0 left-0 right-0 h-24 bg-repeat-x opacity-10 pointer-events-none" 
                 style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNDQwIDMyMCI+PHBhdGggZmlsbD0iI2ZmZmZmZiIgZmlsbC1vcGFjaXR5PSIxIiBkPSJNMCAyMjR4NDggMjEzLjN4OTYgMjAyLjd4MTQ0IDE5MnMxOTIgMzIgMjQwIDMyIDI0MC0zMiAyNDAtMzJzMTkyIDMyIDI0MCAzMiAyNDAtMzIgMjQwLTMyVjMyMEgwWiIvPjwvc3ZnPg=='); background-position: bottom;">
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-elevate-peach/20 border border-elevate-peach/30 text-elevate-peach text-[10px] font-bold uppercase tracking-wider mb-2 backdrop-blur-sm cursor-pointer hover:bg-elevate-peach/30 transition-colors"
                         @click="checkLocation()" 
                         title="Klik untuk refresh lokasi">
                        <i class="ph-fill ph-map-pin"></i> 
                        <span x-text="locationName">...</span>
                        <!-- Indikator GPS Aktif -->
                        <template x-if="usingGeolocation">
                            <span class="flex items-center gap-1 ml-1 text-elevate-accent">
                                <i class="ph-bold ph-crosshair text-[10px]"></i> GPS
                            </span>
                        </template>
                        <!-- Indikator Manual/Fallback -->
                        <template x-if="!usingGeolocation && !isLoading">
                            <span class="flex items-center gap-1 ml-1 text-slate-300" title="Lokasi Default">
                                (Manual)
                            </span>
                        </template>
                    </div>
                    <h3 class="text-2xl md:text-4xl font-serif text-white tracking-wide mb-1" x-text="nextEventName">...</h3>
                    <p class="text-slate-300 text-xs font-medium font-mono">
                        <i class="ph-bold ph-hourglass-medium text-elevate-peach"></i> <span x-text="countdown">00:00:00</span>
                    </p>
                </div>

                
                <div class="w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                    <div class="flex md:grid md:grid-cols-6 gap-3 min-w-max px-2">
                        <template x-for="(time, name) in schedule" :key="name">
                            <div class="flex flex-col items-center group/item">
                                <!-- Arch Shape Container -->
                                <div class="w-14 h-20 rounded-t-full flex flex-col items-center justify-end pb-2 transition-all duration-300 relative overflow-hidden border-b-2"
                                     :class="currentEvent === name ? 'bg-elevate-peach-light/20 border-elevate-peach shadow-[0_0_15px_rgba(249,162,130,0.5)] -translate-y-1' : 'bg-white/5 border-white/10 hover:bg-white/10'">
                                    
                                    <span class="text-[9px] uppercase tracking-wider mb-1" 
                                          :class="currentEvent === name ? 'text-elevate-peach font-bold' : 'text-slate-300'" x-text="name"></span>
                                    
                                    <span class="text-xs font-mono" 
                                          :class="currentEvent === name ? 'text-white font-black' : 'text-white font-medium'" x-text="time"></span>
                                          
                                    <!-- Active Dot -->
                                    <div x-show="currentEvent === name" class="absolute top-2 w-1.5 h-1.5 rounded-full bg-elevate-peach shadow-sm animate-pulse"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-sm border border-slate-100 hover:shadow-md hover:border-elevate-accent/30 transition-colors relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-24 h-24 border-l-4 border-t-4 border-elevate-soft rounded-tl-[2rem]"></div>
        <div class="absolute bottom-0 right-0 w-24 h-24 border-r-4 border-b-4 border-elevate-soft rounded-br-[2rem]"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 md:gap-12">
            
            
            <div class="shrink-0 relative group">
                <!-- Frame Kalender -->
                <div class="w-40 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform group-hover:rotate-2 transition-transform duration-500">
                    <!-- Bagian Atas -->
                    <div class="bg-elevate-dark h-12 flex items-center justify-center relative">
                        <div class="absolute top-[-10px] w-4 h-4 rounded-full bg-slate-800 border-2 border-white z-20"></div> <!-- Lubang paku -->
                        <span class="text-elevate-peach font-black uppercase tracking-[0.2em] text-[10px] mt-2">RAMADHAN</span>
                    </div>
                    
                    <!-- Bagian Tengah (Angka Tanggal) -->
                    <div class="h-32 flex flex-col items-center justify-center bg-white relative">
                        <span class="text-7xl font-serif font-black text-elevate-dark leading-none tracking-tighter">
                            <?php echo e($isBeforeRamadan ? '-' : ($isRamadanEnded ? '✓' : $ramadanDay)); ?>

                        </span>
                        <span class="text-xs font-serif italic text-slate-400 mt-1">
                            <?php echo e($isRamadanEnded ? 'Telah Usai' : '1447 Hijriyah'); ?>

                        </span>
                        
                        <!-- Watermark -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                            <i class="ph-fill ph-moon-stars text-8xl text-elevate-primary"></i>
                        </div>
                    </div>

                    <!-- Bagian Bawah (Tanggal Masehi) -->
                    <div class="bg-slate-50 border-t border-slate-100 py-2 text-center">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">
                            <?php echo e(\Carbon\Carbon::parse($today)->translatedFormat('l, d F Y')); ?>

                        </span>
                    </div>
                </div>

                <!-- Shadow effect beneath calendar -->
                <div class="absolute -bottom-4 -right-4 w-40 h-40 bg-black/5 rounded-2xl -z-10 rotate-3"></div>
            </div>

            
            <div class="flex-1 text-center md:text-left space-y-4">
                <?php if($isRamadanEnded): ?>
                    <div>
                        <h2 class="text-3xl font-serif font-bold text-elevate-dark mb-2">
                            Sampai Jumpa, <span class="text-elevate-primary"><?php echo e($student->name); ?></span>
                        </h2>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-lg font-serif">
                            <i class="ph-fill ph-check-circle text-elevate-primary"></i>
                            Bulan suci Ramadhan telah berlalu. Terima kasih telah mengisi jurnal mutabaah dengan tekun. Semoga istiqomah selalu.
                        </p>
                    </div>
                    
                    
                    <?php if(isset($topRamadanStudents) && $topRamadanStudents->isNotEmpty()): ?>
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 max-w-lg mt-4 shadow-inner">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="ph-fill ph-medal text-elevate-peach text-lg"></i> Top 3 Pahlawan Kebaikan
                        </h4>
                        <div class="space-y-2">
                            <?php $__currentLoopData = $topRamadanStudents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $topStudent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm transition hover:-translate-y-0.5 hover:border-elevate-accent/30">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs
                                            <?php echo e($index == 0 ? 'bg-elevate-peach-light/30 text-elevate-peach-dark' : ($index == 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-600')); ?>">
                                            #<?php echo e($index + 1); ?>

                                        </div>
                                        <div class="text-sm font-bold text-elevate-dark capitalize"><?php echo e(strtolower($topStudent->name)); ?></div>
                                    </div>
                                    <div class="text-[10px] font-black text-elevate-primary bg-elevate-soft px-2.5 py-1 rounded-lg border border-elevate-primary/10">
                                        <?php echo e(number_format($topStudent->ramadan_points ?? 0)); ?> Pts
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="pt-4">
                        <a href="<?php echo e(route('portal.show', Auth::guard('student')->id() ?? $student->id)); ?>?tab=ramadan_rank" class="inline-flex items-center gap-2 px-6 py-3 bg-elevate-dark hover:bg-elevate-primary text-white rounded-xl font-bold transition-all shadow-lg shadow-elevate-dark/20 hover:-translate-y-0.5">
                            <i class="ph-bold ph-trophy text-elevate-peach"></i>
                            <span>Lihat Papan Peringkat Lengkap</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div>
                        <h2 class="text-3xl font-serif font-bold text-elevate-dark mb-2">
                            Ahlan Wa Sahlan, <span class="text-elevate-primary"><?php echo e($student->name); ?></span>
                        </h2>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-lg font-serif">
                            <i class="ph-fill ph-quotes text-elevate-accent"></i>
                            Semoga Ramadhan ini menjadi ladang pahala. Jangan lupa isi jurnal ibadahmu hari ini untuk mencatat setiap kebaikan.
                        </p>
                    </div>

                    
                    <div class="space-y-2 max-w-md">
                        <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-400">
                            <span>Kelengkapan Ibadah Hari Ini</span>
                            <span class="<?php echo e($progressColor); ?>"><?php echo e(round($progressPercent)); ?>%</span>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden <?php echo e($barColor); ?>"
                                 style="width: <?php echo e($progressPercent); ?>%">
                                 <div class="absolute inset-0 bg-white/20 animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <?php if(!$todayRamadanLog): ?>
                            <a href="<?php echo e(route('student.ramadan.index')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-elevate-primary hover:bg-elevate-dark text-white rounded-xl font-bold transition-all shadow-lg shadow-elevate-primary/20 hover:-translate-y-0.5">
                                <i class="ph-bold ph-pencil-simple text-elevate-accent"></i>
                                <span>Isi Jurnal Hari Ini</span>
                            </a>
                        <?php else: ?>
                             <a href="<?php echo e(route('student.ramadan.index')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 hover:text-elevate-primary hover:border-elevate-primary/50 rounded-xl font-bold transition-all">
                                <i class="ph-bold ph-check-circle text-elevate-primary"></i>
                                <span>Sudah Diisi (Edit)</span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(!$isRamadanEnded): ?>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <?php
            $log = $todayRamadanLog;

            // Helper untuk status UI menggunakan warna Elevate Primary (selesai) & Elevate Soft (belum)
            $getStatusUI = function($condition) {
                return $condition 
                    ? ['class' => 'border-elevate-primary/30 bg-elevate-soft/50 hover:bg-elevate-soft', 'icon_bg' => 'bg-elevate-primary text-white', 'text' => 'text-elevate-primary', 'check' => true]
                    : ['class' => 'border-slate-100 bg-white hover:border-elevate-accent/30', 'icon_bg' => 'bg-slate-100 text-slate-400', 'text' => 'text-slate-500', 'check' => false];
            };

            $gridItems = [];

            // ITEM 1: JUMAT (Hanya muncul jika hari Jumat)
            if ($isFriday) {
                $fridayFilled = !empty($log->friday_khotib);
                $ui = $getStatusUI($fridayFilled);
                // Override khusus Jumat jika belum diisi (Peach warning)
                if(!$fridayFilled) {
                    $ui['class'] = 'border-elevate-peach/30 bg-elevate-peach-light/10';
                    $ui['icon_bg'] = 'bg-elevate-peach-light/50 text-elevate-peach-dark';
                    $ui['text'] = 'text-elevate-peach-dark font-bold';
                }
                
                $gridItems[] = [
                    'label' => 'Laporan Jumat',
                    'icon' => 'mosque',
                    'sub' => $fridayFilled ? 'Terlaksana' : 'Wajib Diisi!',
                    'ui' => $ui
                ];
            }

            // ITEM 2: PUASA
            $isFasting = optional($log)->is_fasting;
            $gridItems[] = [
                'label' => 'Puasa',
                'icon' => 'bowl-food',
                'sub' => $isFasting ? 'Alhamdulillah' : 'Belum',
                'ui' => $getStatusUI($isFasting)
            ];

            // ITEM 3: 5 WAKTU
            $prayerCount = $log ? count(array_filter($log->prayers ?? [])) : 0;
            $gridItems[] = [
                'label' => 'Shalat Wajib',
                'icon' => 'clock-afternoon',
                'sub' => $prayerCount . '/5 Waktu',
                'ui' => $getStatusUI($prayerCount == 5)
            ];

            // ITEM 4: TARAWIH
            $isTarawih = $log->sunnah_deeds['tarawih'] ?? false;
            $gridItems[] = [
                'label' => 'Tarawih',
                'icon' => 'moon-stars',
                'sub' => $isTarawih ? 'Tercatat' : 'Belum',
                'ui' => $getStatusUI($isTarawih)
            ];

            // ITEM 5: TILAWAH
            $isTilawah = !empty($log->tadarus_surah);
            $gridItems[] = [
                'label' => 'Tilawah',
                'icon' => 'book-open-text',
                'sub' => Str::limit($log->tadarus_surah ?? '-', 10),
                'ui' => $getStatusUI($isTilawah)
            ];

            // ITEM 6: KULTUM
            $kultumFilled = !empty($log->kultum_summary);
            $gridItems[] = [
                'label' => 'Kultum',
                'icon' => 'microphone-stage',
                'sub' => $kultumFilled ? 'Ada Ringkasan' : 'Kosong',
                'ui' => $getStatusUI($kultumFilled)
            ];

            // ITEM 7: SUNNAH LAINNYA (Aggregate)
            $sunnahCount = 0;
            if ($log && isset($log->sunnah_deeds)) {
                 $sunnahCount = ($log->sunnah_deeds['sedekah']??0) + ($log->sunnah_deeds['dhuha']??0) + ($log->sunnah_deeds['witir']??0) + ($log->sunnah_deeds['rawatib']??0);
            }
            $gridItems[] = [
                'label' => 'Sunnah Lain',
                'icon' => 'sparkle',
                'sub' => $sunnahCount . ' Amalan',
                'ui' => [
                    'class' => 'border-elevate-accent/20 bg-elevate-soft/30 hover:bg-elevate-soft/50',
                    'icon_bg' => 'bg-elevate-accent/20 text-elevate-primary',
                    'text' => 'text-elevate-dark',
                    'check' => false
                ]
            ];

        ?>

        <?php $__currentLoopData = $gridItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 rounded-2xl border flex flex-col items-center text-center justify-center gap-2 transition-all hover:shadow-sm relative <?php echo e($item['ui']['class']); ?>">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm <?php echo e($item['ui']['icon_bg']); ?>">
                    <i class="ph-fill ph-<?php echo e($item['icon']); ?> text-xl"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-slate-500"><?php echo e($item['label']); ?></h4>
                    <p class="text-xs font-bold <?php echo e($item['ui']['text']); ?>">
                        <?php echo e($item['sub']); ?>

                    </p>
                </div>
                <?php if($item['ui']['check']): ?>
                    <div class="absolute top-2 right-2 w-2 h-2 bg-elevate-primary rounded-full shadow-sm"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if(isset($lastVerifiedLog) && $lastVerifiedLog && $lastVerifiedLog->teacher_verified_at): ?>
    <div class="bg-elevate-soft/50 rounded-2xl border border-elevate-primary/20 p-6 flex gap-4 items-start">
        <div class="shrink-0">
            <div class="w-12 h-12 bg-white rounded-full border-2 border-elevate-accent/30 flex items-center justify-center text-elevate-primary text-2xl shadow-sm">
                <i class="ph-fill ph-star"></i>
            </div>
        </div>
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h3 class="font-bold text-elevate-dark">Catatan Guru</h3>
                <span class="px-2 py-0.5 bg-elevate-primary text-white text-[10px] font-bold rounded">Nilai: <?php echo e($lastVerifiedLog->teacher_score); ?></span>
            </div>
            <p class="text-sm text-slate-600 italic font-serif">"<?php echo e($lastVerifiedLog->teacher_note); ?>"</p>
            <div class="mt-2 text-[10px] text-slate-400 uppercase font-bold tracking-wide">
                Diverifikasi: <?php echo e(\Carbon\Carbon::parse($lastVerifiedLog->teacher_verified_at)->diffForHumans()); ?>

            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Notifikasi SweetAlert2 jika ada session success
        <?php if(session('success')): ?>
            Swal.fire({
                title: 'Alhamdulillah!',
                text: "<?php echo session('success'); ?>",
                icon: 'success',
                confirmButtonText: 'Lanjutkan untuk melihat Peringkat',
                confirmButtonColor: '#0d52a1', // elevate-primary
                background: '#ffffff', 
                color: '#2c3f61', // elevate-dark
                iconColor: '#56bbf1', // elevate-accent
                customClass: {
                    popup: 'rounded-[2rem] border-2 border-elevate-soft font-sans shadow-2xl',
                    title: 'font-serif text-2xl font-black'
                }
            });
        <?php endif; ?>
    });

    function portalPrayerWidget() {
        return {
            isLoading: true,
            usingGeolocation: false,
            city: null, 
            latitude: null,
            longitude: null,
            schedule: {},
            nextEventName: 'Memuat...',
            countdown: '00:00:00',
            locationName: 'Mencari Lokasi...',
            currentEvent: '',
            
            async init() {
                // Memberikan jeda agar Alpine initialized penuh
                setTimeout(() => { this.checkLocation(); }, 500);
                setInterval(() => this.updateCountdown(), 1000);
            },

            checkLocation() {
                console.log("Memulai pengecekan lokasi...");
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            console.log("GPS Ditemukan:", position.coords);
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.usingGeolocation = true;
                            this.locationName = "Lokasi Saat Ini"; 
                            this.fetchTimesByCoords();
                        },
                        (error) => { 
                            console.warn("GPS Ditolak/Error:", error.message);
                            this.useFallbackCity(); 
                        }
                    );
                } else {
                    console.warn("Browser tidak support Geolocation");
                    this.useFallbackCity();
                }
            },

            useFallbackCity() {
                this.usingGeolocation = false;
                // Menggunakan PHP untuk fallback value
                this.city = '<?php echo e($student->city ?? "Jakarta"); ?>'; 
                this.locationName = this.city;
                console.log("Menggunakan Fallback City:", this.city);
                this.fetchTimesByCity();
            },

            async fetchTimesByCoords() {
                try {
                    const date = new Date();
                    const timestamp = Math.floor(date.getTime() / 1000);
                    const url = `https://api.aladhan.com/v1/timings/${timestamp}?latitude=${this.latitude}&longitude=${this.longitude}&method=20`;
                    console.log("Fetch by Coords:", url);
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    this.processData(data);
                } catch (e) { 
                    console.error("Gagal Fetch Coords:", e);
                    this.useFallbackCity(); 
                }
            },

            async fetchTimesByCity() {
                try {
                    const date = new Date();
                    const url = `https://api.aladhan.com/v1/timingsByCity/${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}?city=${this.city}&country=Indonesia&method=20`;
                    console.log("Fetch by City:", url);
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    this.processData(data);
                } catch (e) {
                    console.error("Gagal Fetch City:", e);
                    this.nextEventName = "Offline";
                    this.isLoading = false;
                }
            },

            processData(data) {
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
                    this.isLoading = false;
                }
            },

            updateCountdown() {
                if(this.isLoading || !this.schedule['Subuh']) return;
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
                    if(nextName === 'Maghrib') this.nextEventName = 'Menuju Berbuka';
                    else if (nextName.includes('Imsak')) this.nextEventName = 'Menuju Imsak';
                    else this.nextEventName = `Menuju ${nextName}`;
                    this.currentEvent = nextName.replace(' (Besok)', '');
                }
            }
        }
    }
</script><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-ramadan-jurnal.blade.php ENDPATH**/ ?>