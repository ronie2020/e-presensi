<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    
    <?php $__env->startPush('styles'); ?>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body, .font-sans { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        @keyframes scanMove { 0% { top: 0; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 100%; opacity: 0; } }
        
        /* --- PERBAIKAN KAMERA (MENGATASI GETAR/MELOMPAT DI HP) --- */
        #qr-reader { 
            width: 100% !important; 
            border: none !important; 
            border-radius: 1.5rem; 
            overflow: hidden; 
            background: #0f172a; 
            position: relative;
        }

        #qr-reader video { 
            width: 100% !important; 
            object-fit: cover !important; 
            border-radius: 1.5rem;
            display: block !important;
        }

        /* Sembunyikan elemen bawaan library yang tidak diperlukan */
        #qr-reader__dashboard_section_csr span, 
        #qr-reader__dashboard_section_swaplink,
        #qr-reader__dashboard_section_csr div { display: none !important; }
        
        /* Scanner Styles */
        .scanner-container { position: relative; overflow: hidden; border-radius: 1.5rem; transform: translateZ(0); }
        .scanner-overlay {
            position: absolute; inset: 0; pointer-events: none;
            border: 0px solid transparent; border-radius: 1.5rem;
            transition: all 0.3s ease; z-index: 20;
        }
        
        /* Scan Effects (Microsoft Semantic Colors - Tailored for Elevate) */
        .scan-success-effect { box-shadow: inset 0 0 40px rgba(16, 185, 129, 0.5); border: 2px solid #10b981; } /* Emerald */
        .scan-warning-effect { box-shadow: inset 0 0 40px rgba(245, 158, 11, 0.5); border: 2px solid #f59e0b; } /* Amber */
        .scan-error-effect { box-shadow: inset 0 0 40px rgba(225, 29, 72, 0.5); border: 2px solid #e11d48; } /* Rose */
        .scan-makan-effect { box-shadow: inset 0 0 40px rgba(245, 158, 11, 0.5); border: 2px solid #f59e0b; }

        /* TEMA ELEVATE: Garis Scan diubah menjadi warna Elevate Accent (#38bdf8) */
        .scanner-line {
            position: absolute; width: 100%; height: 3px;
            background: #38bdf8; box-shadow: 0 0 15px #38bdf8;
            top: 0; animation: scanMove 2.5s infinite linear;
            z-index: 10; opacity: 0.8;
        }

        /* Utility */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .hidden-col { display: none !important; }
        .hidden-row { display: none !important; }
        
        /* Efek highlight baris baru dengan warna Elevate Peach Light */
        @keyframes highlightRow { 0% { background-color: #fff0e8; } 100% { background-color: transparent; } } 
        .new-row-entry { animation: highlightRow 2s ease-out; }
        
        .scan-type-btn.ring-2 .indicator-dot { transform: scale(1.2); background-color: currentColor; }
    </style>    
    <?php $__env->stopPush(); ?>

    <?php
        $safeSchedule = $scheduleConfig ?? [];
        $scheduleJson = json_encode($safeSchedule);
        $totalTarget = $statsConfig['total_target'] ?? 0;
        $currentTaken = $statsConfig['current_taken'] ?? 0;

        // MENGAMBIL DATA SISWA UNTUK DROPDOWN PENCARIAN (NAMA, KELAS, NISN)
        $allStudents = \App\Models\Student::with('schoolClass')
            ->where('status', 'active')
            ->get()
            ->map(function($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'nisn' => $s->nisn ?? $s->student_id ?? '-',
                    'class_name' => $s->schoolClass->name ?? '-'
                ];
            })->toArray();
    ?>

    <div class="py-6 sm:py-8 font-sans text-elevate-text bg-elevate-surface min-h-screen relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
            
            
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                
                
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex flex-col md:flex-row items-center gap-6 w-full md:w-auto text-center md:text-left">
                        <div class="w-20 h-20 rounded-[1.5rem] bg-white/40 backdrop-blur-md flex items-center justify-center border border-white/50 shadow-sm shrink-0">
                            <i class="ph-duotone ph-scan text-5xl text-elevate-primary"></i>
                        </div>
                        <div>
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                                <?php if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false)): ?>
                                    <span class="px-3 py-1 rounded-full bg-rose-500 text-white border border-rose-600 text-[10px] font-bold uppercase tracking-wider shadow-sm">Libur: <?php echo e($scheduleConfig['description']); ?></span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full bg-white/60 text-elevate-dark border border-white/50 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">
                                        <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-black tracking-tight leading-tight text-elevate-dark">
                                Mesin Kiosk <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-dark">Absensi</span>
                            </h2>
                            <p class="text-elevate-dark/80 text-sm mt-1 font-bold">Monitoring kehadiran, makan siang, dan ibadah terintegrasi.</p>
                        </div>
                    </div>
                    
                    
                    <div class="bg-white/60 backdrop-blur-md border border-white/50 px-6 py-4 rounded-[1.5rem] flex items-center gap-5 shadow-sm w-full md:w-auto justify-between md:justify-start">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-1">Waktu Server</p>
                            <div id="clock" class="text-3xl font-black text-elevate-dark font-mono leading-none tracking-widest">00:00:00</div>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-elevate-dark flex items-center justify-center text-elevate-peach-light shadow-inner animate-pulse shrink-0">
                            <i class="ph-bold ph-clock text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                
                <div class="lg:col-span-5 flex flex-col gap-6 animate-enter delay-100">
                    
                    
                    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-black text-elevate-dark flex items-center gap-2 text-lg">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-elevate-accent"></span>
                                </span>
                                Kamera Aktif
                            </h3>
                            <div class="flex items-center gap-2">
                                
                                <div id="gps-badge" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200 flex items-center gap-1.5 transition-all" title="Mencari Lokasi">
                                    <i class="ph-bold ph-map-pin animate-pulse"></i> <span class="hidden sm:inline">Mencari GPS</span>
                                </div>

                                <div id="mode-badge" class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200 flex items-center gap-2 transition-all">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    <span id="mode-text">Standby</span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="scanner-container relative bg-slate-900 w-full rounded-[1.5rem] border-4 border-slate-900 shadow-inner overflow-hidden min-h-[300px]">
                            <div id="qr-reader" class="w-full"></div>
                            
                            
                            <div id="scanner-overlay-el" class="scanner-overlay">
                                <div class="scanner-line"></div>
                            </div>

                            
                            <div class="absolute top-4 inset-x-0 flex justify-center z-30 pointer-events-none">
                                <div id="scan-status" class="bg-elevate-dark/80 backdrop-blur-md text-white text-xs py-2 px-5 rounded-xl font-bold border border-white/10 shadow-sm flex items-center gap-2 transition-all">
                                    <i class="ph-bold ph-circle-notch animate-spin text-elevate-accent"></i> Memuat Kamera...
                                </div>
                            </div>
                        </div>

                        
                        <div id="scan-result" class="mt-4 p-4 rounded-xl font-bold text-sm text-center hidden transition-all duration-300 transform scale-95 opacity-0 border border-transparent shadow-sm"></div>
                        
                        
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <!-- 1. Tombol Ketik NISN -->
                            <button onclick="showManualInput()" class="py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-[10px] uppercase tracking-wider hover:bg-elevate-peach-light hover:text-elevate-primary hover:border-elevate-peach/50 transition-all flex items-center justify-center gap-2 shadow-sm bg-white active:scale-95">
                                <i class="ph-bold ph-keyboard text-lg"></i> Ketik NISN
                            </button>
                            
                            <!-- 2. Tombol Upload Gambar -->
                            <button onclick="document.getElementById('qr-upload').click()" class="py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-[10px] uppercase tracking-wider hover:bg-elevate-peach-light hover:text-elevate-primary hover:border-elevate-peach/50 transition-all flex items-center justify-center gap-2 shadow-sm bg-white active:scale-95">
                                <i class="ph-bold ph-image text-lg"></i> Baca Gambar
                            </button>
                            <input type="file" id="qr-upload" class="hidden" accept="image/*">

                            <!-- 3. Tombol Balik Kamera -->
                            <button onclick="switchCamera()" class="py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-[10px] uppercase tracking-wider hover:bg-elevate-peach-light hover:text-elevate-primary hover:border-elevate-peach/50 transition-all flex items-center justify-center gap-2 shadow-sm bg-white active:scale-95">
                                <i class="ph-bold ph-camera-rotate text-lg"></i> Putar Kamera
                            </button>

                            <!-- 4. Tombol Izin / Sakit -->
                            <button onclick="document.getElementById('absen-manual-modal').classList.remove('hidden');" class="py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-[10px] uppercase tracking-wider hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all flex items-center justify-center gap-2 shadow-sm bg-white active:scale-95">
                                <i class="ph-bold ph-user-focus text-lg"></i> Form Izin
                            </button>

                            <!-- Tombol Auto Mode (Full Width if visible) -->
                            <button id="btn-reset-auto" class="hidden col-span-2 py-3 rounded-xl border-2 border-dashed border-elevate-accent/50 text-elevate-primary font-bold text-[10px] uppercase tracking-wider hover:bg-elevate-peach-light hover:border-elevate-primary transition-all flex items-center justify-center gap-2 bg-white active:scale-95" onclick="resetAutoMode()">
                                <i class="ph-bold ph-arrows-clockwise animate-spin-slow"></i> Kembali ke Mode Auto
                            </button>
                        </div>
                    </div>

                    
                    <div id="makan-stats-panel" class="hidden grid-cols-2 gap-4">
                        <div class="bg-elevate-primary p-6 rounded-[1.5rem] text-white shadow-lg shadow-elevate-primary/20 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="ph-fill ph-check-circle text-6xl"></i>
                            </div>
                            <p class="text-[10px] font-bold text-elevate-peach-light uppercase tracking-widest mb-1">Sudah Ambil</p>
                            <h3 class="text-4xl font-black tracking-tight" id="stat-taken"><?php echo e($currentTaken); ?></h3>
                        </div>
                        <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <i class="ph-duotone ph-users text-6xl text-elevate-dark"></i>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Belum Ambil</p>
                            <h3 class="text-4xl font-black text-elevate-dark tracking-tight" id="stat-remaining"><?php echo e($totalTarget - $currentTaken); ?></h3>
                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Pilih Mode Manual</h4>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <?php $__currentLoopData = [
                                ['id'=>'harian', 'label'=>'Absen Harian', 'sub'=>'Masuk/Pulang', 'icon'=>'calendar-check', 'color_text'=>'text-elevate-primary', 'color_bg'=>'bg-elevate-primary/10', 'border_class'=>'border-elevate-primary/30', 'hover_border'=>'hover:border-elevate-primary', 'type'=>'Harian'],
                                ['id'=>'makan', 'label'=>'Makan Siang', 'sub'=>'Scan Gizi', 'icon'=>'bowl-food', 'color_text'=>'text-amber-600', 'color_bg'=>'bg-amber-50', 'border_class'=>'border-amber-200', 'hover_border'=>'hover:border-amber-500', 'type'=>'Makan'],
                                ['id'=>'dhuha', 'label'=>'Sholat Dhuha', 'sub'=>'Ibadah Pagi', 'icon'=>'sun-horizon', 'color_text'=>'text-emerald-600', 'color_bg'=>'bg-emerald-50', 'border_class'=>'border-emerald-200', 'hover_border'=>'hover:border-emerald-500', 'type'=>'Dhuha'],
                                ['id'=>'dhuhur', 'label'=>'Sholat Dhuhur', 'sub'=>'Ibadah Siang', 'icon'=>'moon-stars', 'color_text'=>'text-elevate-dark', 'color_bg'=>'bg-slate-100', 'border_class'=>'border-slate-200', 'hover_border'=>'hover:border-elevate-dark', 'type'=>'Dhuhur'],
                                ['id'=>'ekskul', 'label'=>'Ekstrakurikuler', 'sub'=>'Kegiatan Sore', 'icon'=>'basketball', 'color_text'=>'text-rose-600', 'color_bg'=>'bg-rose-50', 'border_class'=>'border-rose-200', 'hover_border'=>'hover:border-rose-500', 'type'=>'Ekstrakurikuler']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button id="btn-<?php echo e($mode['id']); ?>" data-type="<?php echo e($mode['type']); ?>" data-color-class="<?php echo e($mode['color_text']); ?>" data-border-class="<?php echo e(str_replace('hover:', '', $mode['hover_border'])); ?>" class="scan-type-btn bg-white p-4 rounded-2xl border border-slate-100 <?php echo e($mode['hover_border']); ?> hover:shadow-md transition-all duration-300 text-left group active:scale-95 relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-10 transition-opacity">
                                    <i class="ph-fill ph-<?php echo e($mode['icon']); ?> text-4xl <?php echo e($mode['color_text']); ?>"></i>
                                </div>
                                <div class="w-10 h-10 rounded-xl <?php echo e($mode['color_bg']); ?> <?php echo e($mode['color_text']); ?> flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-<?php echo e($mode['icon']); ?>"></i>
                                </div>
                                <h3 class="font-bold text-elevate-dark text-xs leading-tight mb-1"><?php echo e($mode['label']); ?></h3>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] text-slate-400 font-medium"><?php echo e($mode['sub']); ?></p>
                                    <div class="w-2 h-2 rounded-full border border-slate-300 indicator-dot transition-all"></div>
                                </div>
                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div id="extra-selector-container" class="hidden bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mt-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3 px-1">Pilih Kegiatan Ekskul</label>
                        <div class="relative">
                            <select id="extra-activity-select" class="w-full rounded-xl border-slate-200 focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 pl-4 pr-10 text-sm bg-slate-50 cursor-pointer hover:bg-white transition-colors">
                                <option value="">-- Pilih Ekstrakurikuler --</option>
                                <?php if(isset($extracurriculars)): ?>
                                    <?php $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ekskul->id); ?>"><?php echo e($ekskul->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i class="ph-bold ph-caret-down"></i>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-7 flex flex-col h-full animate-enter delay-200">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col min-h-[600px] overflow-hidden">
                        
                        
                        <div class="p-6 md:p-8 border-b border-slate-100 bg-elevate-peach-light/30 sticky top-0 z-20 flex justify-between items-center">
                            <div>
                                <h3 class="font-black text-elevate-dark text-xl flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center border border-elevate-peach/50 shadow-sm">
                                        <i class="ph-bold ph-list-dashes text-xl"></i>
                                    </div>
                                    Log Aktivitas
                                </h3>
                                <p class="text-xs text-elevate-text/60 font-medium mt-1 ml-14">Monitoring kehadiran realtime</p>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
                                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Live</span>
                            </div>
                        </div>
                        
                        
                        <div class="flex-1 overflow-hidden relative bg-white">
                            <div class="absolute inset-0 overflow-auto custom-scrollbar p-0 sm:p-4">
                                <table class="w-full text-left border-collapse">
                                    <thead class="sticky top-0 z-10">
                                        <tr>
                                            <th class="px-6 py-4 text-[10px] font-black text-elevate-primary uppercase tracking-widest bg-slate-50/90 backdrop-blur rounded-l-xl">Siswa</th>
                                            <th class="col-harian px-4 py-4 text-center text-[10px] font-black text-elevate-primary uppercase tracking-widest bg-slate-50/90 backdrop-blur">Masuk</th>
                                            <th class="col-harian px-4 py-4 text-center text-[10px] font-black text-elevate-primary uppercase tracking-widest bg-slate-50/90 backdrop-blur">Pulang</th>
                                            <th class="col-waktu hidden-col px-4 py-4 text-center text-[10px] font-black text-elevate-primary uppercase tracking-widest bg-slate-50/90 backdrop-blur">Waktu</th>
                                            <th class="col-kegiatan hidden-col px-4 py-4 text-center text-[10px] font-black text-elevate-primary uppercase tracking-widest bg-slate-50/90 backdrop-blur">Kegiatan</th>
                                            <th class="px-6 py-4 text-right text-[10px] font-black text-elevate-primary uppercase tracking-widest bg-slate-50/90 backdrop-blur rounded-r-xl">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scan-log" class="text-sm">
                                        <?php if(isset($recentScans)): ?>
                                            <?php $__currentLoopData = $recentScans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="log-entry group hover:bg-slate-50 transition-all duration-300 border-b border-slate-50 last:border-0"
                                                    data-type-raw="<?php echo e($scan['type_raw']); ?>">
                                                    
                                                    <td class="px-6 py-4 rounded-l-xl">
                                                        <div class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors"><?php echo e($scan['student_name']); ?></div>
                                                        <div class="text-[10px] text-slate-400 font-mono font-bold"><?php echo e($scan['student_id']); ?></div>
                                                    </td>
                                                    
                                                    
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        <?php if($scan['time_in']): ?> <span class="font-mono font-bold text-elevate-text bg-white border border-slate-200 px-2 py-1 rounded-md text-xs shadow-sm"><?php echo e($scan['time_in']); ?></span>
                                                        <?php else: ?> <span class="text-slate-300 font-bold">-</span> <?php endif; ?>
                                                    </td>
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        <?php if($scan['time_out']): ?> <span class="font-mono font-bold text-elevate-text bg-white border border-slate-200 px-2 py-1 rounded-md text-xs shadow-sm"><?php echo e($scan['time_out']); ?></span>
                                                        <?php else: ?> <span class="text-slate-300 font-bold">-</span> <?php endif; ?>
                                                    </td>

                                                    
                                                    <td class="col-waktu hidden-col px-4 py-4 text-center">
                                                         <span class="font-mono font-bold text-elevate-text bg-white border border-slate-200 px-2 py-1 rounded-md text-xs shadow-sm"><?php echo e($scan['time_in'] ?? now()->format('H:i')); ?></span>
                                                    </td>

                                                    
                                                    <td class="col-kegiatan hidden-col px-4 py-4 text-center text-elevate-dark font-bold text-xs">
                                                        <?php echo e($scan['ekskul_name'] ?? $scan['type_raw']); ?>

                                                    </td>

                                                    <td class="px-6 py-4 text-right rounded-r-xl">
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wide border shadow-sm
                                                            <?php echo e(Str::contains($scan['status'], 'Terlambat') ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200'); ?>">
                                                            <?php echo e($scan['status']); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                
                                
                                <div id="no-log-entry" class="<?php echo e(count($recentScans ?? []) > 0 ? 'hidden' : ''); ?> flex flex-col items-center justify-center py-24 text-center">
                                    <div class="w-24 h-24 bg-elevate-peach-light rounded-[1.5rem] flex items-center justify-center mb-5 border border-elevate-peach/30 shadow-sm text-elevate-primary">
                                        <i class="ph-duotone ph-qr-code text-5xl"></i>
                                    </div>
                                    <p class="text-elevate-dark font-black text-lg mb-1">Belum ada data scan.</p>
                                    <p class="text-xs text-elevate-text/60 font-medium">Data kehadiran akan muncul otomatis di sini.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        
        
        <div id="absen-manual-modal" class="fixed inset-0 bg-elevate-dark/70 backdrop-blur-md overflow-y-auto h-full w-full hidden z-[100] transition-opacity" x-data="manualAbsen">
            <div class="relative top-10 md:top-20 mx-auto p-0 border border-slate-100 w-full max-w-md rounded-[2rem] bg-white overflow-hidden shadow-2xl shadow-elevate-accent/20">
                
                
                <div class="bg-elevate-dark px-8 py-6 flex justify-between items-center">
                    <h3 class="font-black text-white text-lg flex items-center gap-3"><i class="ph-bold ph-keyboard text-elevate-accent"></i> Input Form Manual</h3>
                    <button type="button" @click="closeManualModal()" class="text-slate-400 hover:text-white transition-colors bg-white/10 hover:bg-white/20 w-8 h-8 rounded-full flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
                </div>

                <form action="<?php echo e(route('reports.storeManual')); ?>" method="POST" class="p-8 space-y-5" @submit="submitForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="attendance_type" id="modal-attendance-type" value="Harian">
                    
                    
                    <div class="relative">
                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Cari & Pilih Siswa</label>
                        
                        <!-- Kolom Pencarian (Sembunyi Jika Siswa Sudah Terpilih) -->
                        <div x-show="!selectedStudent">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-magnifying-glass text-slate-400"></i>
                                </div>
                                <input type="text" x-model="searchQuery" @input="searchStudents" @focus="showDropdown = true" @click.away="showDropdown = false" class="w-full pl-11 pr-4 py-3.5 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-elevate-accent focus:border-elevate-accent font-bold text-elevate-dark text-sm transition-colors" placeholder="Ketik Nama, NISN...">
                            </div>
                            
                            <!-- Dropdown Hasil Pencarian -->
                            <div x-show="showDropdown && searchQuery.length > 0" x-transition.opacity class="absolute z-50 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                <template x-if="filteredStudents.length > 0">
                                    <ul class="py-2 text-sm text-elevate-dark divide-y divide-slate-50">
                                        <template x-for="student in filteredStudents" :key="student.id">
                                            <li>
                                                <button type="button" @click="selectStudent(student)" class="w-full text-left px-5 py-2.5 hover:bg-elevate-peach-light hover:text-elevate-primary focus:bg-elevate-peach-light transition-colors flex flex-col">
                                                    <span class="font-bold" x-text="student.name"></span>
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5" x-text="student.nisn + ' • ' + student.class_name"></span>
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <template x-if="filteredStudents.length === 0">
                                    <div class="px-5 py-4 text-sm text-slate-500 text-center font-medium">Siswa tidak ditemukan.</div>
                                </template>
                            </div>
                        </div>

                        <!-- Card Info Siswa Terpilih -->
                        <div x-show="selectedStudent" class="p-4 bg-elevate-peach-light/50 border border-elevate-peach/30 rounded-xl flex justify-between items-center" style="display: none;">
                            <div>
                                <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-wider mb-1">Siswa Terpilih</p>
                                <p class="font-black text-elevate-dark leading-tight" x-text="selectedStudent?.name"></p>
                                <p class="text-xs text-elevate-text/70 font-bold mt-0.5" x-text="selectedStudent?.nisn + ' • ' + selectedStudent?.class_name"></p>
                            </div>
                            <button type="button" @click="clearStudent()" class="w-10 h-10 rounded-xl bg-white border border-elevate-peach/30 text-elevate-primary flex items-center justify-center hover:bg-elevate-primary hover:text-white transition-colors shrink-0 shadow-sm" title="Ganti Siswa">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                        
                        <!-- ID asli siswa yang akan dikirim ke Backend -->
                        <input type="hidden" name="student_id" :value="selectedStudent?.id">
                    </div>

                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Tanggal</label>
                            <input type="date" name="date" required value="<?php echo e(date('Y-m-d')); ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-elevate-accent focus:border-elevate-accent font-bold text-elevate-dark py-3.5 text-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Status</label>
                            <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-elevate-accent focus:border-elevate-accent font-bold text-elevate-dark py-3.5 text-sm cursor-pointer transition-colors">
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Alfa">Alfa</option>
                                <option value="Hadir">Hadir (Manual)</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Masuk</label>
                            <input type="time" name="time_in" value="<?php echo e(now()->format('H:i')); ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-elevate-accent focus:border-elevate-accent text-center font-mono font-bold text-elevate-dark py-3.5 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Pulang</label>
                            <input type="time" name="time_out" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-elevate-accent focus:border-elevate-accent text-center font-mono font-bold text-elevate-dark py-3.5 transition-colors">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Keterangan (Opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Contoh: Surat dokter menyusul..." class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-elevate-accent focus:border-elevate-accent text-sm font-medium resize-none p-4 transition-colors"></textarea>
                    </div>

                    <button type="submit" class="w-full mt-4 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Data Manual
                    </button>
                </form>
            </div>
        </div>

    </div>

    
    <script>
        const CONFIG = {
            schedule: <?php echo $scheduleJson; ?>,
            routes: { process: '<?php echo e(route('scan.process')); ?>' },
            token: '<?php echo e(csrf_token()); ?>'
        };

        // State Management
        let state = {
            mode: 'Harian',
            extraId: '',
            isProcessing: false,
            processedQr: new Set(),
            manualOverride: false,
            audioCtx: null,
            lat: null,
            long: null,
            gpsActive: false
        };

        function initAudio() {
            if (!state.audioCtx) {
                state.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (state.audioCtx.state === 'suspended') {
                state.audioCtx.resume();
            }
        }

        document.body.addEventListener('click', initAudio, { once: true });
        document.body.addEventListener('touchstart', initAudio, { once: true });

        function playBeep(type = 'success') {
            if (!state.audioCtx) return;
            const osc = state.audioCtx.createOscillator();
            const gain = state.audioCtx.createGain();
            osc.connect(gain);
            gain.connect(state.audioCtx.destination);
            
            osc.type = type === 'error' ? 'sawtooth' : 'sine';
            const freqs = { success: 880, warning: 440, error: 150, makan: 600 };
            const freq = freqs[type] || 880;

            osc.frequency.setValueAtTime(freq, state.audioCtx.currentTime);
            if(type === 'success') {
                osc.frequency.exponentialRampToValueAtTime(freq * 2, state.audioCtx.currentTime + 0.1);
            }
            
            gain.gain.setValueAtTime(0.1, state.audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, state.audioCtx.currentTime + 0.3);
            
            osc.start(state.audioCtx.currentTime);
            osc.stop(state.audioCtx.currentTime + 0.3);
        }

        const toMinutes = (s) => { const [h, m] = s.split(':'); return h * 60 + +m; };
        
        document.addEventListener('DOMContentLoaded', () => {
            const dom = {
                clock: document.getElementById('clock'),
                scanStatus: document.getElementById('scan-status'),
                scanResult: document.getElementById('scan-result'),
                modeText: document.getElementById('mode-text'),
                modeBadge: document.getElementById('mode-badge'),
                overlay: document.getElementById('scanner-overlay-el'),
                tableBody: document.getElementById('scan-log'),
                extraContainer: document.getElementById('extra-selector-container'),
                extraSelect: document.getElementById('extra-activity-select'),
                btnReset: document.getElementById('btn-reset-auto'),
                statTaken: document.getElementById('stat-taken'),
                statRemaining: document.getElementById('stat-remaining'),
                makanPanel: document.getElementById('makan-stats-panel'),
                gpsBadge: document.getElementById('gps-badge')
            };

            if ("geolocation" in navigator) {
                navigator.geolocation.watchPosition(
                    (position) => {
                        state.lat = position.coords.latitude;
                        state.long = position.coords.longitude;
                        state.gpsActive = true;
                        if(dom.gpsBadge) {
                            dom.gpsBadge.className = "px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1.5 transition-all";
                            dom.gpsBadge.innerHTML = `<i class="ph-fill ph-map-pin"></i> <span class="hidden sm:inline">GPS Aktif</span>`;
                        }
                    },
                    (error) => {
                        state.gpsActive = false;
                        if(dom.gpsBadge) {
                            dom.gpsBadge.className = "px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200 flex items-center gap-1.5 transition-all";
                            dom.gpsBadge.innerHTML = `<i class="ph-bold ph-map-pin-line"></i> <span class="hidden sm:inline">GPS Nonaktif</span>`;
                        }
                        console.warn("Kamera GPS Alert:", error.message);
                    },
                    { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
                );
            } else if (dom.gpsBadge) {
                dom.gpsBadge.style.display = 'none';
            }

            setInterval(() => {
                const now = new Date();
                dom.clock.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
                if (!state.manualOverride) checkAutoMode(now);
            }, 1000);

            function checkAutoMode(now) {
                const mins = now.getHours() * 60 + now.getMinutes();
                let nextMode = 'Harian';

                if (inRange(mins, CONFIG.schedule.makan_start, CONFIG.schedule.makan_end)) nextMode = 'Makan';
                else if (inRange(mins, CONFIG.schedule.dhuha_start, CONFIG.schedule.dhuha_end)) nextMode = 'Dhuha';
                else if (inRange(mins, CONFIG.schedule.dhuhur_start, CONFIG.schedule.dhuhur_end)) nextMode = 'Dhuhur';

                if (state.mode !== nextMode && state.mode !== 'Ekstrakurikuler') {
                    setMode(nextMode, true);
                }
            }

            function inRange(val, start, end) {
                return val >= toMinutes(start || '00:00') && val < toMinutes(end || '00:00');
            }

            window.setMode = function(mode, isAuto = false) {
                state.mode = mode;
                state.manualOverride = !isAuto;
                
                dom.btnReset.classList.toggle('hidden', isAuto);
                dom.extraContainer.classList.toggle('hidden', mode !== 'Ekstrakurikuler');
                dom.makanPanel.classList.toggle('hidden', mode !== 'Makan');
                dom.makanPanel.classList.toggle('grid', mode === 'Makan');
                
                document.querySelectorAll('.scan-type-btn').forEach(btn => {
                    const isActive = btn.dataset.type === mode;
                    const borderClass = btn.dataset.borderClass;
                    
                    if (isActive) {
                        btn.classList.add('ring-2', 'ring-slate-200');
                        btn.classList.replace('border-slate-100', borderClass);
                    } else {
                        btn.classList.remove('ring-2', 'ring-slate-200');
                        btn.className = btn.className.replace(/border-\[[^\]]+\]|border-amber-200|border-emerald-200|border-rose-200|border-elevate-primary\/30/, 'border-slate-100');
                    }
                    
                    const dot = btn.querySelector('.indicator-dot');
                    if(dot) {
                        if (isActive) {
                            dot.className = `w-2 h-2 rounded-full indicator-dot transition-all scale-125`;
                            dot.style.backgroundColor = 'currentColor';
                        } else {
                            dot.className = `w-2 h-2 rounded-full indicator-dot transition-all border border-slate-300`;
                            dot.style.backgroundColor = 'transparent';
                        }
                    }
                });

                const labels = { Harian: 'Absen Harian', Makan: 'Makan Siang', Dhuha: 'Sholat Dhuha', Dhuhur: 'Sholat Dhuhur', Ekstrakurikuler: 'Ekstrakurikuler' };
                dom.modeText.innerText = isAuto ? `Auto: ${labels[mode]}` : labels[mode];
                dom.scanStatus.innerHTML = mode === 'Ekstrakurikuler' 
                    ? `<i class="ph-bold ph-warning text-amber-500"></i> Pilih Kegiatan Dulu` 
                    : `<i class="ph-bold ph-qr-code text-elevate-accent"></i> Siap Scan ${labels[mode]}`;
                
                filterLogTable(mode);
            };

            document.querySelectorAll('.scan-type-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    initAudio(); 
                    setMode(btn.dataset.type);
                });
            });

            window.resetAutoMode = () => {
                state.manualOverride = false;
                checkAutoMode(new Date());
            };

            dom.extraSelect.addEventListener('change', (e) => {
                state.extraId = e.target.value;
                const text = e.target.options[e.target.selectedIndex].text;
                dom.scanStatus.innerHTML = state.extraId 
                    ? `<i class="ph-bold ph-check text-emerald-500"></i> Siap: ${text}` 
                    : `<i class="ph-bold ph-warning text-amber-500"></i> Pilih Kegiatan Dulu`;
            });

            function filterLogTable(mode) {
                const rows = dom.tableBody.querySelectorAll('tr');
                const colsHarian = document.querySelectorAll('.col-harian');
                const colsWaktu = document.querySelectorAll('.col-waktu');
                const colsKegiatan = document.querySelectorAll('.col-kegiatan');

                const isHarian = mode === 'Harian';
                const isExtra = mode === 'Ekstrakurikuler';

                colsHarian.forEach(el => el.classList.toggle('hidden-col', !isHarian));
                colsWaktu.forEach(el => el.classList.toggle('hidden-col', isHarian));
                colsKegiatan.forEach(el => el.classList.toggle('hidden-col', !isExtra));

                let count = 0;
                rows.forEach(row => {
                    const rowType = row.dataset.typeRaw; 
                    let match = false;

                    if (mode === 'Harian') match = ['Harian', 'Masuk', 'Pulang'].includes(rowType);
                    else if (mode === 'Makan') match = ['Meal', 'Makan'].includes(rowType);
                    else if (mode === 'Dhuha' || mode === 'Dhuhur') match = rowType === 'Keagamaan'; 
                    else if (mode === 'Ekstrakurikuler') match = rowType === 'Extracurricular';

                    row.classList.toggle('hidden-row', !match);
                    if(match) count++;
                });
                
                document.getElementById('no-log-entry').classList.toggle('hidden', count > 0);
            }

            const processAttendanceData = async (studentId) => {
                if (state.isProcessing) return;
                if (state.processedQr.has(studentId)) return; 

                if (state.mode === 'Ekstrakurikuler' && !state.extraId) {
                    playBeep('warning');
                    Swal.fire({ toast: true, position: 'top', icon: 'warning', title: 'Pilih Ekskul dulu!', showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-xl border border-slate-100 shadow-xl' } });
                    return;
                }

                state.isProcessing = true;
                state.processedQr.add(studentId);
                dom.scanStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-elevate-accent"></i> Memproses...`;

                try {
                    let finalType = state.mode;
                    if (state.mode === 'Harian') {
                        const nowMin = new Date().getHours() * 60 + new Date().getMinutes();
                        const switchMin = toMinutes(CONFIG.schedule.start_out || '12:00');
                        finalType = nowMin < switchMin ? 'Masuk' : 'Pulang';
                    }

                    const res = await fetch(CONFIG.routes.process, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CONFIG.token, 'Accept': 'application/json' },
                        body: JSON.stringify({ 
                            student_id: studentId, 
                            type: finalType, 
                            extra_id: state.extraId ? state.extraId : null, 
                            lat: state.lat,   
                            long: state.long  
                        })
                    });

                    const data = await res.json();

                    if (res.ok) {
                        handleSuccess(data, finalType);
                    } else {
                        let errorMsg = data.message || 'Error Server';
                        if (data.errors) {
                            errorMsg = Object.values(data.errors)[0][0];
                        }
                        handleError(errorMsg);
                    }
                } catch (e) {
                    handleError('Koneksi Gagal / Server Error');
                } finally {
                    setTimeout(() => {
                        state.isProcessing = false;
                        state.processedQr.delete(studentId);
                        setMode(state.mode, !state.manualOverride); 
                    }, 2000);
                }
            };

            const onScanSuccess = async (decodedText) => {
                await processAttendanceData(decodedText);
            };

            window.showManualInput = () => {
                initAudio(); 
                Swal.fire({
                    title: 'Input Manual',
                    html: '<p class="text-sm text-slate-500 mb-4 font-medium">Masukkan NISN atau ID Siswa</p>',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocorrect: 'off',
                        placeholder: 'Contoh: 0012345678'
                    },
                    showCancelButton: true,
                    confirmButtonText: '<i class="ph-bold ph-paper-plane-right"></i> Kirim',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    customClass: {
                        popup: 'rounded-[2rem] border border-slate-100 shadow-2xl font-sans',
                        confirmButton: 'bg-elevate-dark text-white px-6 py-3 rounded-xl font-bold hover:bg-elevate-primary transition-colors mx-2 shadow-lg flex items-center gap-2',
                        cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2',
                        input: 'rounded-xl border-slate-200 focus:border-elevate-accent focus:ring-elevate-accent text-center text-lg font-mono font-black w-4/5 mx-auto py-3'
                    },
                    buttonsStyling: false,
                    preConfirm: (inputValue) => {
                        if (!inputValue) {
                            Swal.showValidationMessage('ID tidak boleh kosong!');
                            return false;
                        }
                        return processAttendanceData(inputValue);
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            };

            function handleSuccess(data, type) {
                const isLate = (data.scan?.status || '').includes('Terlambat');
                
                playBeep(type === 'Makan' ? 'makan' : (isLate ? 'warning' : 'success'));
                triggerOverlay(type === 'Makan' ? 'makan' : (isLate ? 'warning' : 'success'));
                
                dom.scanResult.innerHTML = `<span class="${isLate ? 'text-amber-600' : 'text-emerald-600'} flex items-center justify-center gap-2"><i class="ph-fill ph-check-circle"></i> ${data.message}</span>`;
                dom.scanResult.classList.remove('hidden', 'opacity-0', 'scale-95');
                dom.scanResult.className = `mt-4 p-4 rounded-xl font-bold text-sm text-center transition-all duration-300 transform scale-100 border ${isLate ? 'bg-amber-50 border-amber-200' : 'bg-emerald-50 border-emerald-200'} shadow-sm`;
                
                setTimeout(() => {
                    dom.scanResult.classList.add('opacity-0', 'scale-95');
                }, 3000);

                if (data.stats) {
                    dom.statTaken.innerText = data.stats.taken;
                    dom.statRemaining.innerText = <?php echo e($totalTarget); ?> - data.stats.taken;
                }

                if (data.scan) addTableRow(data.scan);
            }

            function handleError(msg) {
                playBeep('error');
                triggerOverlay('error');
                dom.scanResult.innerHTML = `<span class="text-rose-600 flex items-center justify-center gap-2"><i class="ph-fill ph-x-circle"></i> ${msg}</span>`;
                dom.scanResult.className = `mt-4 p-4 rounded-xl font-bold text-sm text-center transition-all duration-300 transform scale-100 border bg-rose-50 border-rose-200 shadow-sm`;
                dom.scanResult.classList.remove('hidden', 'opacity-0', 'scale-95');
                setTimeout(() => dom.scanResult.classList.add('opacity-0', 'scale-95'), 3000);
            }

            function triggerOverlay(type) {
                dom.overlay.className = `scanner-overlay scan-${type}-effect`;
                setTimeout(() => dom.overlay.className = 'scanner-overlay', 500);
            }

            function addTableRow(scan) {
                document.getElementById('no-log-entry').classList.add('hidden');
                
                const row = document.createElement('tr');
                row.className = 'new-row-entry border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors group';
                row.dataset.typeRaw = scan.type_raw;

                row.innerHTML = `
                    <td class="px-6 py-4 rounded-l-xl">
                        <div class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors">${scan.student_name}</div>
                        <div class="text-[10px] text-slate-400 font-mono font-bold">${scan.student_id || '-'}</div>
                    </td>
                    <td class="col-harian px-4 py-4 text-center">
                        ${(scan.type_raw === 'Masuk' || scan.type_raw === 'Harian' || scan.time_in) ? `<span class="font-mono font-bold text-elevate-text bg-white border border-slate-200 px-2 py-1 rounded-md text-xs shadow-sm">${scan.time_in || '-'}</span>` : '<span class="text-slate-300 font-bold">-</span>'}
                    </td>
                    <td class="col-harian px-4 py-4 text-center">
                        ${(scan.type_raw === 'Pulang' || scan.time_out) ? `<span class="font-mono font-bold text-elevate-text bg-white border border-slate-200 px-2 py-1 rounded-md text-xs shadow-sm">${scan.time_out || scan.time_in}</span>` : '<span class="text-slate-300 font-bold">-</span>'}
                    </td>
                    <td class="col-waktu hidden-col px-4 py-4 text-center">
                         <span class="font-mono font-bold text-elevate-text bg-white border border-slate-200 px-2 py-1 rounded-md text-xs shadow-sm">${scan.time_in || '-'}</span>
                    </td>
                    <td class="col-kegiatan hidden-col px-4 py-4 text-center text-elevate-dark font-bold text-xs">${scan.ekskul_name || '-'}</td>
                    <td class="px-6 py-4 text-right rounded-r-xl"><span class="${scan.status.includes('Terlambat') ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200'} border px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wide shadow-sm">${scan.status}</span></td>
                `;

                dom.tableBody.prepend(row);
                filterLogTable(state.mode); 
            }

            const qrScanner = new Html5Qrcode("qr-reader");
            
            const config = { 
                fps: 15, 
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    let minEdgePercentage = 0.65; 
                    let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                    let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                    return { width: qrboxSize, height: qrboxSize };
                } 
            };

            const cameraConstraints = { facingMode: "environment" };

            qrScanner.start(cameraConstraints, config, onScanSuccess)
                .then(() => {
                    dom.scanStatus.innerHTML = `<i class="ph-bold ph-qr-code text-elevate-accent"></i> Siap Scan`;
                })
                .catch(err => {
                    dom.scanStatus.innerHTML = `<span class="text-rose-500"><i class="ph-bold ph-warning"></i> Kamera Ditolak / Error</span>`;
                    console.warn(err);
                });

            document.getElementById('qr-upload').addEventListener('change', async (e) => {
                if (e.target.files.length === 0) return;
                const file = e.target.files[0];
                
                initAudio();
                dom.scanStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-elevate-accent"></i> Membaca Gambar...`;

                try {
                    const decodedText = await qrScanner.scanFile(file, true);
                    onScanSuccess(decodedText);
                } catch (err) {
                    handleError('QR Code tidak valid / tidak terbaca di gambar ini.');
                    dom.scanStatus.innerHTML = `<i class="ph-bold ph-warning text-rose-500"></i> Gagal Membaca QR`;
                    setTimeout(() => setMode(state.mode, !state.manualOverride), 2000);
                } finally {
                    e.target.value = ''; 
                }
            });

            let currentFacingMode = "environment"; 
            
            window.switchCamera = () => {
                initAudio();
                currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
                dom.scanStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-elevate-accent"></i> Memutar Kamera...`;

                qrScanner.stop().then(() => {
                    qrScanner.start(
                        { facingMode: currentFacingMode }, 
                        config, 
                        onScanSuccess
                    ).then(() => {
                        dom.scanStatus.innerHTML = `<i class="ph-bold ph-qr-code text-elevate-accent"></i> Siap Scan`;
                    }).catch(err => {
                        handleError('Gagal memuat kamera tujuan.');
                        console.warn(err);
                    });
                }).catch(err => {
                    console.error("Gagal menghentikan kamera saat ini:", err);
                });
            };
            
            checkAutoMode(new Date());
        });

        // ==============================================================
        // LOGIKA ALPINE JS UNTUK MODAL PENCARIAN SISWA
        // ==============================================================
        document.addEventListener('alpine:init', () => {
            Alpine.data('manualAbsen', () => ({
                searchQuery: '',
                showDropdown: false,
                students: <?php echo json_encode($allStudents, 15, 512) ?>,
                filteredStudents: [],
                selectedStudent: null,

                searchStudents() {
                    if (this.searchQuery.trim() === '') {
                        this.filteredStudents = [];
                        return;
                    }
                    const q = this.searchQuery.toLowerCase();
                    this.filteredStudents = this.students.filter(s => {
                        const name = (s.name || '').toLowerCase();
                        const nisn = (s.nisn || '').toLowerCase();
                        const className = (s.class_name || '').toLowerCase();
                        
                        return name.includes(q) || nisn.includes(q) || className.includes(q);
                    }).slice(0, 10);
                    
                    this.showDropdown = true;
                },

                selectStudent(student) {
                    this.selectedStudent = student;
                    this.searchQuery = '';
                    this.showDropdown = false;
                },

                clearStudent() {
                    this.selectedStudent = null;
                    this.searchQuery = '';
                    this.filteredStudents = [];
                },

                closeManualModal() {
                    document.getElementById('absen-manual-modal').classList.add('hidden');
                    this.clearStudent(); 
                },

                submitForm(e) {
                    if (!this.selectedStudent) {
                        e.preventDefault();
                        Swal.fire({
                            toast: true, position: 'top', icon: 'warning', 
                            title: 'Pilih siswa terlebih dahulu!', 
                            showConfirmButton: false, timer: 2500, 
                            customClass: { popup: 'rounded-xl border border-slate-100 font-sans shadow-xl' }
                        });
                    } else {
                        const btn = e.target.querySelector('button[type="submit"]');
                        btn.disabled = true;
                        btn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-lg"></i> Menyimpan...`;
                    }
                }
            }));
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/scan/index.blade.php ENDPATH**/ ?>