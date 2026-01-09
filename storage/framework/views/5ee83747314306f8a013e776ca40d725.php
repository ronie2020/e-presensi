<?php $__env->startSection('content'); ?>

<?php
    \Carbon\Carbon::setLocale('id');
    
    // Cek apakah siswa ini ALUMNI
    $isAlumni = $student->status === 'graduated';
?>

<!-- X-DATA: Menangani Tab & Resize Chart -->
<div class="w-full max-w-6xl mx-auto pb-20 px-4 sm:px-6"
     x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'ringkasan',
        updateTab(val) {
            this.activeTab = val;
            const url = new URL(window.location);
            url.searchParams.set('tab', val);
            window.history.pushState({}, '', url);
            
            if(val === 'akademik' || val === 'kehadiran') {
                setTimeout(() => { 
                    window.dispatchEvent(new Event('resize')); 
                }, 100);
            }
        }
     }">
    
    <!-- HEADER PROFIL -->
    <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden mb-6 border border-gray-100 relative group">
        <!-- Background Banner -->
        <div class="absolute top-0 left-0 w-full h-40 sm:h-52 z-0 overflow-hidden bg-slate-900">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
            
            
            <div class="absolute inset-0 bg-gradient-to-r <?php echo e($isAlumni ? 'from-slate-900 via-amber-900/80 to-slate-900' : 'from-slate-900 via-blue-900/80 to-slate-900'); ?>"></div>
            
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            
            <!-- Dekorasi Blur -->
            <div class="absolute top-0 right-0 w-[200px] sm:w-[400px] h-[200px] sm:h-[400px] <?php echo e($isAlumni ? 'bg-amber-600' : 'bg-blue-600'); ?> rounded-full mix-blend-overlay filter blur-[60px] sm:blur-[80px] opacity-20 -mr-10 -mt-10"></div>
        </div>
        
        <!-- Content Container -->
        <div class="relative z-10 px-6 sm:px-10 pt-20 sm:pt-28 pb-6 flex flex-col md:flex-row items-center md:items-end text-center md:text-left gap-4 sm:gap-6">
            <!-- Foto Profil -->
            <div class="relative group shrink-0 mx-auto md:mx-0 -mb-2">
                <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full bg-white p-1 shadow-2xl relative z-10 transform group-hover:scale-105 transition-transform duration-300 ring-4 ring-white/20 backdrop-blur-sm">
                    <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-white relative">
                        <?php if($student->photo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" alt="<?php echo e($student->name); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-4xl sm:text-5xl font-black text-slate-400 select-none">
                                <?php echo e(substr(trim($student->name), 0, 1)); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                
                <?php if($isAlumni): ?>
                    <div class="absolute bottom-1 right-1 z-20 bg-amber-500 text-slate-900 text-[10px] font-black px-3 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1.5">
                        <i class="ph-fill ph-graduation-cap"></i> ALUMNI <?php echo e($student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year ?? ''); ?>

                    </div>
                <?php else: ?>
                    <div class="absolute bottom-1 right-1 z-20 bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div> AKTIF
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Detail Siswa -->
            <div class="flex-1 min-w-0 w-full md:pb-3">
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight mb-2 sm:mb-3 break-words capitalize drop-shadow-lg">
                    <?php echo e(strtolower($student->name)); ?>

                </h1>
                <div class="flex flex-wrap justify-center md:justify-start gap-2 text-xs sm:text-sm font-medium">
                    <?php if(!$isAlumni): ?>
                    <span class="flex items-center bg-blue-600 px-3 sm:px-4 py-1.5 rounded-full text-white shadow-lg shadow-blue-900/30 border border-blue-500 transition hover:bg-blue-500 hover:scale-105">
                        <i class="ph-fill ph-chalkboard-teacher mr-2 text-base sm:text-lg text-blue-200"></i>
                        <span>Kelas <strong class="font-bold text-white"><?php echo e($student->schoolClass->name ?? 'Unassigned'); ?></strong></span>
                    </span>
                    <?php endif; ?>
                    
                    <span x-data="{ copied: false }" 
                          @click="navigator.clipboard.writeText('<?php echo e($student->student_id); ?>'); copied = true; setTimeout(() => copied = false, 2000)" 
                          class="flex items-center bg-blue-600 px-3 sm:px-4 py-1.5 rounded-full text-white shadow-lg shadow-blue-900/30 border border-blue-500 font-mono transition hover:bg-blue-500 hover:scale-105 cursor-pointer select-none relative" 
                          title="Klik untuk salin">
                        <i class="ph-fill mr-2 text-base sm:text-lg text-blue-200" :class="copied ? 'ph-check' : 'ph-identification-card'"></i>
                        <span x-text="copied ? 'Tersalin!' : '<?php echo e($student->student_id); ?>'"></span>
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2 mt-2 md:mt-0 md:pb-4">
                <?php if(!$isAlumni): ?>
                <a href="<?php echo e(route('portal.card', $student->id)); ?>" target="_blank" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 bg-emerald-500/80 backdrop-blur-md border border-emerald-400/30 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-emerald-500 transition-all shadow-lg hover:shadow-emerald-500/20 group">
                    <i class="ph-bold ph-identification-card mr-2 group-hover:animate-bounce"></i> Kartu OSIS
                </a>
                <?php endif; ?>

                <button onclick="window.print()" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-white hover:text-slate-900 transition-all shadow-lg">
                    <i class="ph-bold ph-printer mr-2"></i> Biodata
                </button>
                <a href="<?php echo e(route('portal.index')); ?>" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-white hover:text-slate-900 transition-all shadow-lg">
                    <i class="ph-bold ph-magnifying-glass mr-2"></i> Cari Lain
                </a>
            </div>
        </div>
    </div>

    <!-- NAVIGATION TABS -->
    <div class="mb-8 sticky top-4 z-40 transition-all duration-300" id="sticky-nav">
        <div class="bg-white/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-lg border border-gray-100/50 relative group">
            <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden z-10 rounded-r-2xl"></div>
            
            <div class="overflow-x-auto custom-scrollbar w-full pb-0.5 md:pb-0 scroll-smooth px-1 md:overflow-visible">
                <div class="flex items-center gap-1 w-max md:w-full md:flex-wrap md:justify-center"> 
                    <?php
                        // Filter Tab berdasarkan Status Siswa
                        $tabs = [
                            'ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan'],
                        ];

                        if ($isAlumni) {
                            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
                            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
                        } else {
                            $tabs['kebiasaan'] = ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan'];
                            $tabs['penghubung'] = ['icon' => 'notebook', 'label' => 'Buku Penghubung'];
                            $tabs['pengaduan'] = ['icon' => 'megaphone', 'label' => 'Pengaduan'];
                            $tabs['jadwal'] = ['icon' => 'calendar-blank', 'label' => 'Jadwal']; 
                            $tabs['lms'] = ['icon' => 'clipboard-text', 'label' => 'Tugas & Kuis'];
                            $tabs['kbm'] = ['icon' => 'chalkboard-teacher', 'label' => 'Jurnal KBM'];
                            $tabs['akademik'] = ['icon' => 'exam', 'label' => 'Nilai Rapor'];
                            $tabs['kehadiran'] = ['icon' => 'calendar-check', 'label' => 'Kehadiran'];
                            $tabs['disiplin'] = ['icon' => 'warning-circle', 'label' => 'Disiplin'];
                            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Prestasi'];
                            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Pustaka'];
                        }
                    ?>

                    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button @click="updateTab('<?php echo e($key); ?>')" 
                            :class="activeTab === '<?php echo e($key); ?>' ? 'bg-slate-900 text-white shadow-lg shadow-slate-300 transform scale-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap flex-shrink-0 outline-none focus:ring-2 focus:ring-slate-200 mb-1">
                            <i class="ph-bold ph-<?php echo e($tab['icon']); ?> text-lg"></i> <?php echo e($tab['label']); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT AREAS -->
    <div class="min-h-[400px]">
        
        <!-- 1. TAB RINGKASAN -->
        <div x-show="activeTab === 'ringkasan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <?php if($isAlumni): ?>
                    <div class="md:col-span-3 bg-amber-50 border border-amber-200 rounded-3xl p-6 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16"></div>
                        <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-4xl shrink-0 z-10 shadow-inner">
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
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i></div>
                        <div class="relative z-10">
                            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Persentase Kehadiran</h3>
                            <div class="flex items-baseline gap-2 mb-4">
                                <?php 
                                    $total_hari = ($hadir ?? 0) + ($sakit ?? 0) + ($izin ?? 0) + ($alpa ?? 0); 
                                    $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; 
                                ?>
                                <span class="text-5xl font-black text-slate-800"><?php echo e($persen); ?><span class="text-2xl text-slate-400">%</span></span>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Hadir: <?php echo e($hadir ?? 0); ?></span>
                                <span class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div> Alpa: <?php echo e($alpa ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Card Poin -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-star text-9xl text-yellow-500"></i></div>
                    <div class="relative z-10">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Poin Karakter <?php echo e($isAlumni ? '(Akhir)' : ''); ?></h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-50/50 p-3 rounded-xl border border-green-100/50">
                                <p class="text-[10px] text-green-600 font-bold mb-1 uppercase">Kebaikan</p>
                                <p class="text-3xl font-black text-green-600">+<?php echo e($total_merit_points ?? 0); ?></p>
                            </div>
                            <div class="bg-rose-50/50 p-3 rounded-xl border border-rose-100/50">
                                <p class="text-[10px] text-rose-600 font-bold mb-1 uppercase">Pelanggaran</p>
                                <p class="text-3xl font-black text-rose-600">-<?php echo e($total_violation_points ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Literasi -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-books text-9xl text-purple-500"></i></div>
                    <div class="relative z-10">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Literasi</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black text-slate-800"><?php echo e($library_visits ?? 0); ?></span>
                            <span class="text-sm text-slate-400 font-bold bg-slate-100 px-2 py-1 rounded-md">Kunjungan</span>
                        </div>
                        <p class="mt-4 text-sm text-slate-500 font-medium">"Buku adalah jendela dunia."</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!$isAlumni): ?>

        
        <div x-show="activeTab === 'kebiasaan'" x-cloak x-transition:enter="transition ease-out duration-300"
             x-data="{
                habitsChecked: {
                    h1: <?php echo e(isset($todayEntry) && $todayEntry->check_1 ? 'true' : 'false'); ?>,
                    h2: <?php echo e(isset($todayEntry) && $todayEntry->check_2 ? 'true' : 'false'); ?>,
                    h3: <?php echo e(isset($todayEntry) && $todayEntry->check_3 ? 'true' : 'false'); ?>,
                    h4: <?php echo e(isset($todayEntry) && $todayEntry->check_4 ? 'true' : 'false'); ?>,
                    h5: <?php echo e(isset($todayEntry) && $todayEntry->check_5 ? 'true' : 'false'); ?>,
                    h6: <?php echo e(isset($todayEntry) && $todayEntry->check_6 ? 'true' : 'false'); ?>,
                    h7: <?php echo e(isset($todayEntry) && $todayEntry->check_7 ? 'true' : 'false'); ?>

                },
                get totalDone() {
                    return Object.values(this.habitsChecked).filter(v => v === true).length;
                },
                get progress() {
                    return Math.round((this.totalDone / 7) * 100);
                }
             }">
            <div class="space-y-6">
                
                <!-- 1. DASHBOARD SUMMARY: Visual Progress -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Progress Card -->
                    <div class="md:col-span-2 bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/20 rounded-full blur-[80px] -mr-32 -mt-32"></div>
                        <div class="relative z-10">
                             <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="inline-flex items-center gap-2 text-blue-300 hover:text-white transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em]">
                                <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                            </a>
                            <h2 class="text-2xl font-black tracking-tight mb-1">Misi Karakter Hari Ini</h2>
                            <p class="text-slate-400 text-sm mb-6">"Karaktermu dibentuk oleh apa yang kamu lakukan hari ini."</p>
                            
                            <div class="flex items-center gap-6">
                                <div class="relative w-24 h-24 shrink-0">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-800"></circle>
                                        <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" 
                                                class="text-emerald-500 transition-all duration-1000 ease-out"
                                                :stroke-dasharray="251.2"
                                                :stroke-dashoffset="251.2 - (251.2 * progress / 100)"></circle>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-xl font-black text-white" x-text="progress + '%'"></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Status Misi</p>
                                    <p class="text-lg font-black" x-text="totalDone === 7 ? 'Sempurna! ✨' : (totalDone >= 4 ? 'Hampir Selesai!' : 'Ayo Mulai Misi!')"></p>
                                    <p class="text-sm text-slate-500 mt-1"><span x-text="totalDone"></span> dari 7 kebiasaan tercatat.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Poin Merit Summary -->
                    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-3xl mb-3 shadow-inner">
                            <i class="ph-fill ph-shield-check"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Poin Merit</h4>
                        <span class="text-4xl font-black text-slate-800">+<?php echo e($total_merit_points ?? 0); ?></span>
                    </div>

                    <!-- Documentation Summary -->
                    <div class="bg-white rounded-[2.5rem] p-2 border border-slate-100 shadow-sm relative overflow-hidden group">
                        <?php if(isset($todayEntry) && $todayEntry->habit_photo): ?>
                            <img src="<?php echo e(asset('storage/' . $todayEntry->habit_photo)); ?>" class="w-full h-full object-cover rounded-[1.8rem] group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-[10px] font-bold text-white uppercase tracking-wider">Dokumentasi Hari Ini</span>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50 rounded-[1.8rem] border-2 border-dashed border-slate-200">
                                <i class="ph-duotone ph-camera text-4xl text-slate-300"></i>
                                <span class="text-[10px] font-bold text-slate-400 uppercase mt-2">Belum Ada Bukti</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 2. BENTO GRID: Checklist Habits -->
                <form action="<?php echo e(route('student.habits.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4" x-data="{ openDetail: null }">
                        
                        <!-- 1. BANGUN PAGI -->
                        <label :class="habitsChecked.h1 ? 'bg-orange-50 border-orange-200' : 'bg-white border-slate-100'"
                               class="relative flex flex-col items-center justify-center p-6 rounded-[2.2rem] border-2 cursor-pointer transition-all hover:shadow-md group">
                            <input type="checkbox" name="check_1" x-model="habitsChecked.h1" class="peer hidden" <?php echo e(isset($todayEntry) && $todayEntry->check_1 ? 'disabled' : ''); ?>>
                            <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-3xl mb-3 transition-transform group-hover:rotate-6">
                                <i class="ph-duotone ph-sun-horizon"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 text-center leading-tight">1. Bangun Pagi & Ibadah</span>
                            <div class="mt-2" x-show="habitsChecked.h1" x-collapse>
                                <input type="time" name="habit_1_time" value="<?php echo e($todayEntry->habit_1_time ?? ''); ?>" 
                                       class="text-[10px] bg-white/50 border-none rounded-lg p-1 w-20 text-center focus:ring-orange-500" 
                                       placeholder="Jam" <?php echo e(isset($todayEntry) ? 'readonly' : ''); ?>>
                            </div>
                            <div x-show="habitsChecked.h1" class="absolute top-3 right-3 text-orange-500"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        </label>

                        <!-- 2. MANDI & RAPI -->
                        <label :class="habitsChecked.h2 ? 'bg-cyan-50 border-cyan-200' : 'bg-white border-slate-100'"
                               class="relative flex flex-col items-center justify-center p-6 rounded-[2.2rem] border-2 cursor-pointer transition-all hover:shadow-md group">
                            <input type="checkbox" name="check_2" x-model="habitsChecked.h2" class="peer hidden" <?php echo e(isset($todayEntry) && $todayEntry->check_2 ? 'disabled' : ''); ?>>
                            <div class="w-14 h-14 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-3xl mb-3 transition-transform group-hover:rotate-6">
                                <i class="ph-duotone ph-drop"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 text-center leading-tight">2. Mandi & Rapi</span>
                            <div x-show="habitsChecked.h2" class="absolute top-3 right-3 text-cyan-500"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        </label>

                        <!-- 3. OLAHRAGA -->
                        <label :class="habitsChecked.h3 ? 'bg-rose-50 border-rose-200' : 'bg-white border-slate-100'"
                               class="relative flex flex-col items-center justify-center p-6 rounded-[2.2rem] border-2 cursor-pointer transition-all hover:shadow-md group">
                            <input type="checkbox" name="check_3" x-model="habitsChecked.h3" class="peer hidden" <?php echo e(isset($todayEntry) && $todayEntry->check_3 ? 'disabled' : ''); ?>>
                            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl mb-3 transition-transform group-hover:rotate-6">
                                <i class="ph-duotone ph-sneaker-move"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 text-center leading-tight">3. Olahraga</span>
                            <div class="mt-2 w-full" x-show="habitsChecked.h3" x-collapse>
                                <input type="text" name="habit_3_activity" value="<?php echo e($todayEntry->habit_3_activity ?? ''); ?>" 
                                       class="text-[10px] bg-white/50 border-none rounded-lg p-2 w-full text-center focus:ring-rose-500" 
                                       placeholder="Jenis (Cth: Jogging)" <?php echo e(isset($todayEntry) ? 'readonly' : ''); ?>>
                            </div>
                            <div x-show="habitsChecked.h3" class="absolute top-3 right-3 text-rose-500"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        </label>

                        <!-- 4. BELAJAR -->
                        <label :class="habitsChecked.h4 ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-slate-100'"
                               class="relative flex flex-col items-center justify-center p-6 rounded-[2.2rem] border-2 cursor-pointer transition-all hover:shadow-md group">
                            <input type="checkbox" name="check_4" x-model="habitsChecked.h4" class="peer hidden" <?php echo e(isset($todayEntry) && $todayEntry->check_4 ? 'disabled' : ''); ?>>
                            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-3xl mb-3 transition-transform group-hover:rotate-6">
                                <i class="ph-duotone ph-book-open-text"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 text-center leading-tight">4. Belajar Mandiri</span>
                            <div class="mt-2 w-full" x-show="habitsChecked.h4" x-collapse>
                                <input type="text" name="habit_4_subject" value="<?php echo e($todayEntry->habit_4_subject ?? ''); ?>" 
                                       class="text-[10px] bg-white/50 border-none rounded-lg p-2 w-full text-center focus:ring-indigo-500" 
                                       placeholder="Mata Pelajaran" <?php echo e(isset($todayEntry) ? 'readonly' : ''); ?>>
                            </div>
                            <div x-show="habitsChecked.h4" class="absolute top-3 right-3 text-indigo-500"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        </label>

                        <!-- 5. MAKAN SEHAT -->
                        <label :class="habitsChecked.h5 ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-slate-100'"
                               class="relative flex flex-col items-center justify-center p-6 rounded-[2.2rem] border-2 cursor-pointer transition-all hover:shadow-md group">
                            <input type="checkbox" name="check_5" x-model="habitsChecked.h5" class="peer hidden" <?php echo e(isset($todayEntry) && $todayEntry->check_5 ? 'disabled' : ''); ?>>
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mb-3 transition-transform group-hover:rotate-6">
                                <i class="ph-duotone ph-carrot"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 text-center leading-tight">5. Makan Sehat</span>
                            <div x-show="habitsChecked.h5" class="absolute top-3 right-3 text-emerald-500"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        </label>

                        <!-- 6. BERMASYARAKAT -->
                        <label :class="habitsChecked.h6 ? 'bg-purple-50 border-purple-200' : 'bg-white border-slate-100'"
                               class="relative flex flex-col items-center justify-center p-6 rounded-[2.2rem] border-2 cursor-pointer transition-all hover:shadow-md group">
                            <input type="checkbox" name="check_6" x-model="habitsChecked.h6" class="peer hidden" <?php echo e(isset($todayEntry) && $todayEntry->check_6 ? 'disabled' : ''); ?>>
                            <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-3xl mb-3 transition-transform group-hover:rotate-6">
                                <i class="ph-duotone ph-users-three"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 text-center leading-tight">6. Bermasyarakat</span>
                            <div x-show="habitsChecked.h6" class="absolute top-3 right-3 text-purple-500"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        </label>

                        <!-- 7. TIDUR CUKUP -->
                        <label :class="habitsChecked.h7 ? 'bg-slate-800 border-slate-900 text-slate-100' : 'bg-white border-slate-100'"
                               class="relative flex flex-col items-center justify-center p-6 rounded-[2.2rem] border-2 cursor-pointer transition-all hover:shadow-md group col-span-2">
                            <input type="checkbox" name="check_7" x-model="habitsChecked.h7" class="peer hidden" <?php echo e(isset($todayEntry) && $todayEntry->check_7 ? 'disabled' : ''); ?>>
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center text-3xl mb-3 transition-transform group-hover:rotate-6">
                                <i class="ph-duotone ph-moon-stars"></i>
                            </div>
                            <span class="text-xs font-black text-center leading-tight" :class="habitsChecked.h7 ? 'text-white' : 'text-slate-700'">7. Tidur Cukup (Istirahat Berkualitas)</span>
                            <div class="mt-2" x-show="habitsChecked.h7" x-collapse>
                                <input type="time" name="habit_7_time" value="<?php echo e($todayEntry->habit_7_time ?? ''); ?>" 
                                       class="text-[10px] bg-white text-slate-900 border-none rounded-lg p-1 w-24 text-center focus:ring-indigo-500" 
                                       placeholder="Jam Tidur" <?php echo e(isset($todayEntry) ? 'readonly' : ''); ?>>
                            </div>
                            <div x-show="habitsChecked.h7" class="absolute top-3 right-3 text-emerald-400"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        </label>
                    </div>

                    <?php if(!isset($todayEntry)): ?>
                        <!-- UPLOAD BUKTI (FOOTER) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 text-center" x-data="{ preview: null }">
                            <h3 class="font-black text-slate-800 text-lg mb-4">Lengkapi Dokumentasi Misi</h3>
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <label class="w-full md:w-1/2 h-40 border-2 border-dashed border-slate-300 rounded-[2rem] flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 transition relative overflow-hidden group">
                                    <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-cover">
                                    <div class="relative z-10 flex flex-col items-center" :class="preview ? 'bg-white/80 p-4 rounded-xl backdrop-blur-sm' : ''">
                                        <i class="ph-duotone ph-camera-plus text-3xl text-slate-400 mb-2"></i>
                                        <span class="text-xs font-bold text-slate-500">Ambil Foto Kegiatan</span>
                                    </div>
                                    <input type="file" name="habit_photo" class="hidden" accept="image/*" required
                                           @change="const file = $event.target.files[0]; if (file) { preview = URL.createObjectURL(file); }">
                                </label>
                                <div class="w-full md:w-1/2 text-left">
                                    <p class="text-sm text-slate-600 leading-relaxed mb-4 italic">
                                        "Unggah kolase foto saat kamu melakukan kebiasaan baik hari ini untuk mendapatkan poin merit tambahan."
                                    </p>
                                    <button type="submit" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl shadow-xl hover:bg-emerald-600 transition-all flex items-center justify-center gap-3">
                                        <i class="ph-bold ph-paper-plane-right"></i>
                                        Selesaikan Misi Hari Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-emerald-50 p-6 rounded-[2.5rem] border border-emerald-100 text-center">
                            <p class="text-emerald-800 font-bold text-sm">
                                <i class="ph-fill ph-seal-check text-xl mr-2"></i> Kamu telah menyelesaikan semua misi untuk hari ini. Luar biasa!
                            </p>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        
        <div x-show="activeTab === 'penghubung'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div x-data="studentLiaisonHandler()" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden min-h-[600px] flex flex-col">
                <div class="bg-indigo-600 pt-8 pb-12 px-8 relative overflow-hidden shrink-0">
                    <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <h2 class="text-2xl font-black text-white tracking-tight">Buku Penghubung</h2>
                            <p class="text-indigo-200 text-sm font-medium">Komunikasi Orang Tua & Wali Kelas</p>
                        </div>
                        <div class="flex bg-white/10 backdrop-blur-md p-1 rounded-xl border border-white/10">
                            <button @click="mode = 'note'" 
                                :class="mode === 'note' ? 'bg-white text-indigo-600 shadow-lg' : 'text-indigo-100 hover:bg-white/10'"
                                class="px-6 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2">
                                <i class="ph-fill ph-notebook"></i> Catatan Guru
                            </button>
                            <button @click="mode = 'chat'; fetchMessages()" 
                                :class="mode === 'chat' ? 'bg-emerald-400 text-white shadow-lg' : 'text-indigo-100 hover:bg-white/10'"
                                class="px-6 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2">
                                <i class="ph-fill ph-chat-circle-text"></i> Chat
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1 bg-slate-50 relative">
                    <div x-show="mode === 'note'" class="p-6 md:p-8" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <?php if(isset($liaison_messages) && $liaison_messages->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $liaison_messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $style = match($msg->type ?? 'info') {
                                            'warning' => ['icon' => 'ph-warning', 'border' => 'border-l-amber-500', 'bg' => 'bg-white', 'icon_color' => 'text-amber-500'],
                                            'achievement' => ['icon' => 'ph-trophy', 'border' => 'border-l-emerald-500', 'bg' => 'bg-white', 'icon_color' => 'text-emerald-500'],
                                            'call' => ['icon' => 'ph-phone-call', 'border' => 'border-l-rose-500', 'bg' => 'bg-rose-50', 'icon_color' => 'text-rose-500'],
                                            default => ['icon' => 'ph-info', 'border' => 'border-l-blue-500', 'bg' => 'bg-white', 'icon_color' => 'text-blue-500'],
                                        };
                                    ?>
                                    <div class="rounded-xl shadow-sm border border-slate-100 overflow-hidden <?php echo e($style['bg']); ?> border-l-4 <?php echo e($style['border']); ?>">
                                        <div class="p-5">
                                            <div class="flex items-start justify-between mb-2">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-1 rounded">
                                                    <?php echo e($msg->created_at->format('d M Y')); ?>

                                                </span>
                                                <i class="ph-fill <?php echo e($style['icon']); ?> text-xl <?php echo e($style['icon_color']); ?>"></i>
                                            </div>
                                            <h3 class="font-bold text-slate-800 text-lg mb-1"><?php echo e($msg->title); ?></h3>
                                            <p class="text-sm text-slate-600 leading-relaxed"><?php echo e($msg->message); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4 text-slate-300 shadow-sm border border-slate-100">
                                    <i class="ph-duotone ph-notebook text-4xl"></i>
                                </div>
                                <h3 class="font-bold text-slate-800">Belum Ada Catatan</h3>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div x-show="mode === 'chat'" x-cloak class="absolute inset-0 flex flex-col bg-slate-50">
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar" x-ref="chatBox">
                            <template x-for="msg in messages" :key="msg.id">
                                <div class="flex w-full" :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-[85%]">
                                        <div class="p-3 rounded-2xl text-sm leading-relaxed shadow-sm"
                                             :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-slate-700 border border-slate-200 rounded-bl-none'">
                                            <p x-text="msg.message"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="p-4 bg-white border-t border-slate-200">
                            <form @submit.prevent="sendMessage()" class="flex items-center gap-3">
                                <input x-model="newMessage" type="text" placeholder="Tulis pesan..." class="flex-1 bg-slate-100 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500">
                                <button type="submit" class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-lg"><i class="ph-bold ph-paper-plane-right"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'pengaduan'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="space-y-6">
                <div class="bg-gradient-to-r from-rose-600 to-pink-600 rounded-[2.5rem] p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-black mb-2">Layanan Pengaduan</h2>
                        <p class="text-rose-100 text-sm">Privasi kamu terjamin aman. Laporkan perundungan atau masalah fasilitas.</p>
                    </div>
                    <a href="<?php echo e(route('student.complaints.create')); ?>" class="bg-white text-rose-600 px-6 py-3 rounded-xl font-bold shadow-lg">Buat Laporan</a>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'jadwal'" x-cloak x-transition:enter="transition ease-out duration-300">
            <?php
                $classSchedules = $student->schoolClass ? $student->schoolClass->schedules->sortBy('start_time')->groupBy('day') : collect();
                $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $daysOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($classSchedules[$day])): ?>
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 font-bold text-slate-800"><?php echo e($day); ?></div>
                        <div class="divide-y divide-gray-50">
                            <?php $__currentLoopData = $classSchedules[$day]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-5 flex gap-4">
                                <div class="text-xs font-bold text-slate-400 shrink-0"><?php echo e(\Carbon\Carbon::parse($sched->start_time)->format('H:i')); ?></div>
                                <div class="font-bold text-slate-700"><?php echo e($sched->subject->name ?? 'Mapel'); ?></div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

               
        <div x-show="activeTab === 'jadwal'" x-cloak x-transition:enter="transition ease-out duration-300">
            <?php
                $classSchedules = $student->schoolClass ? $student->schoolClass->schedules->sortBy('start_time')->groupBy('day') : collect();
                $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            ?>

            <?php if($classSchedules->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $daysOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($classSchedules[$day])): ?>
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                            <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex justify-between items-center">
                                <h3 class="font-bold text-slate-800 text-lg"><?php echo e($day); ?></h3>
                                <span class="text-xs font-bold px-2 py-1 rounded bg-white border border-slate-200 text-slate-500">
                                    <?php echo e($classSchedules[$day]->count()); ?> Mapel
                                </span>
                            </div>
                            <div class="divide-y divide-gray-50">
                                <?php $__currentLoopData = $classSchedules[$day]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-5 flex gap-4 group hover:bg-blue-50/30 transition-colors">
                                    <div class="flex flex-col items-center justify-center w-14 shrink-0 text-slate-400">
                                        <span class="text-xs font-bold"><?php echo e(\Carbon\Carbon::parse($sched->start_time)->format('H:i')); ?></span>
                                        <div class="h-4 w-px bg-slate-200 my-0.5"></div>
                                        <span class="text-xs font-bold"><?php echo e(\Carbon\Carbon::parse($sched->end_time)->format('H:i')); ?></span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                            <?php echo e($sched->subject->name ?? 'Mata Pelajaran'); ?>

                                        </h4>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                            <i class="ph-fill ph-user-circle"></i> 
                                            <?php echo e($sched->teacher->name ?? 'Guru Pengampu'); ?>

                                        </p>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                        <i class="ph-duotone ph-calendar-slash text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Jadwal Belum Tersedia</h3>
                    <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">
                        Jadwal pelajaran untuk kelas <strong class="text-slate-700"><?php echo e($student->schoolClass->name ?? ''); ?></strong> belum diatur oleh admin.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'lms'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="space-y-6">
                <?php if(isset($lms_assignments_grouped) && count($lms_assignments_grouped) > 0): ?>
                    <?php $__currentLoopData = $lms_assignments_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $assignments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-5 border-b border-gray-100 bg-slate-50 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                    <i class="ph-fill ph-book-bookmark text-blue-600"></i> <?php echo e($subjectName); ?>

                                </h3>
                                <span class="text-xs font-bold bg-white px-3 py-1 rounded-full border border-gray-200 text-slate-500">
                                    <?php echo e(count($assignments)); ?> Tugas
                                </span>
                            </div>
                            <div class="overflow-x-auto w-full"> 
                                <table class="w-full text-left min-w-[500px]"> 
                                    <tbody class="divide-y divide-gray-50">
                                        <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $score = $lms_grades[$task->id] ?? null;
                                                $isGraded = $score !== null;
                                            ?>
                                            <tr class="group hover:bg-slate-50/50 transition">
                                                <td class="p-5">
                                                    <div class="flex items-start gap-4">
                                                        <div class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center text-xl
                                                            <?php echo e($task->assignment_type == 'quiz' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600'); ?>">
                                                            <i class="ph-duotone <?php echo e($task->assignment_type == 'quiz' ? 'ph-exam' : 'ph-clipboard-text'); ?>"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-bold text-slate-700 text-sm group-hover:text-blue-600 transition"><?php echo e($task->title); ?></h4>
                                                            <div class="flex gap-3 mt-1 text-xs text-slate-400 font-medium">
                                                                <span class="uppercase tracking-wider"><?php echo e($task->assignment_type == 'quiz' ? 'Kuis Online' : 'Tugas Rumah'); ?></span>
                                                                <span>&bull;</span>
                                                                <span><?php echo e(\Carbon\Carbon::parse($task->created_at)->translatedFormat('d F Y')); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-5 text-right whitespace-nowrap">
                                                    <?php if($isGraded): ?>
                                                        <div class="flex flex-col items-end">
                                                            <span class="text-2xl font-black <?php echo e($score < 70 ? 'text-rose-500' : 'text-emerald-600'); ?>">
                                                                <?php echo e($score); ?>

                                                            </span>
                                                            <span class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Nilai</span>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="inline-block px-3 py-1 rounded-lg bg-slate-100 text-slate-400 text-xs font-bold">
                                                            Belum Dinilai
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                            <i class="ph-duotone ph-clipboard-text text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg">Belum Ada Tugas</h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">Saat ini belum ada data tugas atau kuis yang tersedia untuk kelas ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div x-show="activeTab === 'kbm'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 gap-6">
                <?php if(isset($teaching_journals) && count($teaching_journals) > 0): ?>
                    <?php $__currentLoopData = $teaching_journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wide border border-blue-100">
                                            <?php echo e($journal->schedule?->subject?->name ?? 'Mapel'); ?>

                                        </span>
                                        <span class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                            <i class="ph-fill ph-clock"></i>
                                            <?php echo e(\Carbon\Carbon::parse($journal->started_at)->format('H:i')); ?>

                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800"><?php echo e($journal->topic ?? 'Tanpa Topik'); ?></h3>
                                    <p class="text-sm text-slate-500">Pengajar: <?php echo e($journal->schedule?->teacher?->name ?? 'Guru'); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-black text-slate-200"><?php echo e(\Carbon\Carbon::parse($journal->date)->format('d')); ?></p>
                                    <p class="text-xs font-bold text-slate-400 uppercase"><?php echo e(\Carbon\Carbon::parse($journal->date)->translatedFormat('M Y')); ?></p>
                                </div>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100">
                                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Aktivitas / Tugas:</p>
                                <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line"><?php echo e($journal->activities ?? '-'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                            <i class="ph-duotone ph-notebook text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg">Belum Ada Riwayat KBM</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div x-show="activeTab === 'akademik'" x-cloak x-transition:enter="transition ease-out duration-300">
            <?php if($academic_record): ?>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6 relative overflow-hidden">
                    <div class="h-72 w-full relative">
                        <canvas id="academicChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead class="bg-slate-50/50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 rounded-tl-2xl">Mata Pelajaran</th>
                                    <th class="px-6 py-4 text-center">Nilai</th>
                                    <th class="px-6 py-4 text-center">Predikat</th>
                                    <th class="px-6 py-4 hidden md:table-cell rounded-tr-2xl">Deskripsi Capaian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                <?php $__currentLoopData = $academic_record->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-4 font-bold text-slate-700"><?php echo e($item->subject->name ?? 'Mapel Dihapus'); ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block font-black text-slate-700 text-lg"><?php echo e($item->score); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <?php 
                                                $gradeColor = match($item->predicate) { 
                                                    'A' => 'bg-emerald-100 text-emerald-700 ring-emerald-200', 
                                                    'B' => 'bg-blue-100 text-blue-700 ring-blue-200', 
                                                    'C' => 'bg-amber-100 text-amber-700 ring-amber-200', 
                                                    default => 'bg-rose-100 text-rose-700 ring-rose-200' 
                                                }; 
                                            ?>
                                            <span class="px-3 py-1 rounded-lg text-xs font-bold ring-1 <?php echo e($gradeColor); ?>"><?php echo e($item->predicate); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 hidden md:table-cell max-w-sm leading-relaxed text-xs">
                                            <?php echo e(Str::limit($item->description, 100) ?? '-'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
                     <h3 class="font-bold text-slate-800 text-lg">Belum Ada Data Nilai</h3>
                </div>
            <?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'kehadiran'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col justify-center items-center relative">
                    <div class="h-56 w-full relative mt-2">
                        <canvas id="attendanceChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pt-4">
                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">TOTAL HARI</span>
                            <span class="text-4xl font-black text-slate-800"><?php echo e(($attendanceChart['hadir'] ?? 0) + ($attendanceChart['sakit'] ?? 0) + ($attendanceChart['izin'] ?? 0) + ($attendanceChart['alpa'] ?? 0)); ?></span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-emerald-50 to-white p-5 rounded-2xl border border-emerald-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-emerald-600 mb-1"><?php echo e($hadir ?? 0); ?></div>
                        <div class="text-xs font-bold text-emerald-600/70 uppercase tracking-widest">Hadir</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-2xl border border-blue-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-blue-600 mb-1"><?php echo e($sakit ?? 0); ?></div>
                        <div class="text-xs font-bold text-blue-600/70 uppercase tracking-widest">Sakit</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-white p-5 rounded-2xl border border-amber-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-amber-600 mb-1"><?php echo e($izin ?? 0); ?></div>
                        <div class="text-xs font-bold text-amber-600/70 uppercase tracking-widest">Izin</div>
                    </div>
                    <div class="bg-gradient-to-br from-rose-50 to-white p-5 rounded-2xl border border-rose-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-rose-600 mb-1"><?php echo e($alpa ?? 0); ?></div>
                        <div class="text-xs font-bold text-rose-600/70 uppercase tracking-widest">Alpa</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $attendance_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-slate-50 transition gap-3">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                                <?php echo e(($log->status == 'Hadir') ? 'bg-emerald-100 text-emerald-600' : 
                                   (($log->status == 'Sakit') ? 'bg-blue-100 text-blue-600' : 
                                   (($log->status == 'Izin') ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600'))); ?>">
                                <i class="ph-fill 
                                    <?php echo e(($log->status == 'Hadir') ? 'ph-check' : 
                                       (($log->status == 'Sakit') ? 'ph-thermometer' : 
                                       (($log->status == 'Izin') ? 'ph-file-text' : 'ph-x'))); ?>"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800"><?php echo e(\Carbon\Carbon::parse($log->attendance_date)->translatedFormat('l, d F Y')); ?></p>
                                <p class="text-xs text-slate-500 font-mono">
                                    IN: <span class="font-bold text-slate-700"><?php echo e($log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '--:--'); ?></span>
                                    | OUT: <span class="font-bold text-slate-700"><?php echo e($log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '--:--'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-12 text-center text-slate-400">Belum ada data kehadiran bulan ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'disiplin'" x-cloak x-transition:enter="transition ease-out duration-300">
             <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-rose-100 sticky top-24">
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Catatan Indisipliner</h3>
                        <div class="bg-rose-50 rounded-2xl p-5 border border-rose-100 text-center mt-4">
                            <p class="text-5xl font-black text-rose-600"><?php echo e($total_violation_points ?? 0); ?></p>
                            <p class="text-xs text-rose-400 mt-2 font-medium">Total Poin</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="divide-y divide-gray-50">
                            <?php if(isset($violations) && count($violations) > 0): ?>
                                <?php $__currentLoopData = $violations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-6 hover:bg-rose-50/30 transition-colors group flex gap-4 items-start">
                                    <div class="flex-shrink-0 w-16 text-center">
                                        <div class="text-2xl font-black text-slate-300 group-hover:text-rose-400 transition-colors"><?php echo e(\Carbon\Carbon::parse($record->date)->format('d')); ?></div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase"><?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('M Y')); ?></div>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-slate-800 text-lg"><?php echo e($record->disciplineType->name ?? 'Pelanggaran Dihapus'); ?></h4>
                                        <?php if($record->notes): ?>
                                            <div class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 italic">"<?php echo e($record->notes); ?>"</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="p-12 text-center">
                                    <h3 class="font-bold text-slate-800">Siswa Teladan!</h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'prestasi'" x-cloak x-transition:enter="transition ease-out duration-300">
             <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100 sticky top-24">
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Catatan Prestasi</h3>
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100 text-center mt-4">
                            <p class="text-5xl font-black text-emerald-600">+<?php echo e($total_merit_points ?? 0); ?></p>
                            <p class="text-xs text-emerald-500 mt-2 font-medium">Total Poin</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="divide-y divide-gray-50">
                             <?php if(isset($achievements) && count($achievements) > 0): ?>
                                <?php $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-6 hover:bg-emerald-50/30 transition-colors group flex gap-4 items-start">
                                    <div class="flex-shrink-0 w-16 text-center">
                                        <div class="text-2xl font-black text-slate-300 group-hover:text-emerald-500 transition-colors"><?php echo e(\Carbon\Carbon::parse($record->date)->format('d')); ?></div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase"><?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('M Y')); ?></div>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-slate-800 text-lg"><?php echo e($record->disciplineType->name ?? 'Data Dihapus'); ?></h4>
                                        <?php if($record->notes): ?>
                                            <div class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100"><?php echo e($record->notes); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="p-12 text-center">
                                    <h3 class="font-bold text-slate-800">Ayo Berprestasi!</h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'perpustakaan'" x-cloak x-transition:enter="transition ease-out duration-300">
             <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-indigo-100 sticky top-24 h-fit">
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Perpustakaan</h3>
                        <div class="space-y-3 mt-4">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <span class="text-xs font-bold text-slate-500 uppercase">Total Bacaan</span>
                                <span class="text-xl font-black text-slate-800"><?php echo e($library_visits ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="divide-y divide-gray-50">
                            <?php if(isset($library_history) && count($library_history) > 0): ?>
                                <?php $__currentLoopData = $library_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-5 hover:bg-indigo-50/30 transition-colors flex items-center gap-4">
                                    <div class="w-12 h-16 bg-slate-200 rounded flex-shrink-0 flex items-center justify-center text-slate-400 shadow-sm">
                                        <i class="ph-fill ph-book-open text-2xl"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-bold text-slate-800 truncate" title="<?php echo e($book->title); ?>"><?php echo e($book->title); ?></h4>
                                        <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500">
                                            <span class="flex items-center gap-1">Pinjam: <?php echo e(\Carbon\Carbon::parse($book->borrow_date)->translatedFormat('d M Y')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="p-12 text-center">
                                    <h3 class="font-bold text-slate-800">Ayo Membaca!</h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
             </div>
        </div>

        
        <div x-show="activeTab === 'keagamaan'" x-cloak>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-teal-100 shadow-sm flex items-center gap-6 group hover:border-teal-200 transition-colors">
                    <div class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform"><i class="ph-duotone ph-sun-horizon text-3xl"></i></div>
                    <div><h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sholat Dhuha</h4><p class="text-4xl font-black text-slate-800"><?php echo e($sholat_dhuha ?? 0); ?> <span class="text-sm font-bold text-slate-400">Kali</span></p></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm flex items-center gap-6 group hover:border-orange-200 transition-colors">
                    <div class="w-16 h-16 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform"><i class="ph-duotone ph-clock-afternoon text-3xl"></i></div>
                    <div><h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sholat Dhuhur</h4><p class="text-4xl font-black text-slate-800"><?php echo e($sholat_dhuhur ?? 0); ?> <span class="text-sm font-bold text-slate-400">Kali</span></p></div>
                </div>
             </div>
        </div>
        <?php endif; ?>
        
    </div>
</div>
<style>
    .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
    .ph-fill, .ph-duotone, .ph-bold { vertical-align: middle; }
</style>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function studentLiaisonHandler() {
        return {
            mode: 'note',
            messages: [],
            newMessage: '',
            loading: false,
            fetchMessages() {
                const url = "<?php echo e(route('student.liaison.chat.messages')); ?>?student_id=<?php echo e($student->id); ?>";
                fetch(url).then(res => res.json()).then(data => { this.messages = data; this.scrollToBottom(); });
            },
            sendMessage() {
                if (!this.newMessage.trim()) return;
                const payload = { message: this.newMessage, student_id: "<?php echo e($student->id); ?>" };
                this.messages.push({ message: this.newMessage, sender_type: 'student' });
                this.newMessage = '';
                fetch("<?php echo e(route('student.liaison.chat.send')); ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
                    body: JSON.stringify(payload)
                });
            },
            scrollToBottom() { setTimeout(() => { const b = this.$refs.chatBox; if(b) b.scrollTop = b.scrollHeight; }, 100); }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const academicCanvas = document.getElementById('academicChart');
        const academicData = <?php echo json_encode($chartData ?? null, 15, 512) ?>;
        if (academicCanvas && academicData) {
            new Chart(academicCanvas, {
                type: 'bar',
                data: {
                    labels: academicData.labels,
                    datasets: [{
                        label: 'Nilai',
                        data: academicData.scores,
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/show.blade.php ENDPATH**/ ?>