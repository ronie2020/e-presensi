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
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        
        /* =========================================================
           PERBAIKAN KAMERA RESPONSIVE & ANTI GEPENG (MOBILE FIX)
           ========================================================= */
        #reader { 
            width: 100% !important; 
            min-height: 350px !important; 
            border: none !important; 
            border-radius: 1rem; /* rounded-2xl */
            overflow: hidden; 
            position: relative; 
            background: #0f172a; 
        }
        
        #reader__scan_region { 
            width: 100% !important; 
            min-height: 350px !important;
            background: transparent !important; 
        }

        #reader video, 
        #reader canvas { 
            width: 100% !important; 
            height: 100% !important; 
            min-height: 350px !important;
            object-fit: cover !important; /* Memaksa kamera memenuhi container tanpa gepeng */
            display: block !important;
            border-radius: 1rem;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
        }
        
        #reader__dashboard_section_csr span, #reader__dashboard_section_swaplink { display: none !important; }
        
        .digital-clock { font-feature-settings: "tnum"; font-variant-numeric: tabular-nums; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-8 sm:py-10 relative min-h-screen font-sans text-elevate-dark pb-32 overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        
        <div id="offlineIndicator" class="fixed bottom-6 right-6 z-50 hidden animate-bounce">
            <div class="bg-rose-600 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-rose-200">
                <i class="ph-bold ph-wifi-slash text-2xl"></i>
                <div>
                    <div class="font-bold text-sm">Koneksi Terputus</div>
                    <div class="text-[10px] opacity-90 uppercase tracking-widest font-bold mt-0.5">Menunggu sambungan...</div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">
            
            
            <div class="animate-enter relative rounded-[2.5rem] bg-elevate-gradient-main p-8 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden group border border-white/60">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row gap-8 items-start lg:items-center justify-between">
                    
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 w-full lg:w-auto">
                        <div class="w-16 h-16 rounded-2xl bg-white/50 backdrop-blur-md flex items-center justify-center border border-white/60 shadow-sm shrink-0 text-elevate-primary">
                            <i class="ph-duotone ph-shield-check text-4xl"></i>
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft/80 border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-3 backdrop-blur-sm shadow-sm">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Sistem Monitoring Realtime
                            </div>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-elevate-dark tracking-tight leading-none mb-2">
                                Pos Guru Piket
                            </h1>
                            <p class="text-elevate-dark/80 text-sm max-w-md font-medium">
                                Kelola izin keluar masuk siswa dengan cepat dan akurat.
                            </p>
                        </div>
                    </div>

                    
                    <div class="bg-white/60 backdrop-blur-md border border-white/80 p-6 rounded-[2rem] relative overflow-hidden flex items-center justify-between gap-6 w-full lg:w-auto shrink-0 mt-4 lg:mt-0 shadow-sm hover:bg-white transition-colors">
                        <div class="absolute top-0 right-0 p-4 opacity-5 text-elevate-dark pointer-events-none">
                            <i class="ph-fill ph-clock text-7xl"></i>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-elevate-dark uppercase tracking-widest mb-1 flex items-center gap-2 relative z-10">
                                <i class="ph-bold ph-calendar-blank text-elevate-primary"></i> Waktu Sekarang
                            </h3>
                            <div id="clockDate" class="text-elevate-dark text-sm font-bold relative z-10 opacity-80">...</div>
                        </div>

                        <div class="text-right relative z-10 bg-white/80 px-5 py-3 rounded-2xl border border-slate-100 shrink-0 shadow-sm">
                            <div id="clockTime" class="text-3xl sm:text-4xl font-black text-elevate-dark digital-clock tracking-tight leading-none">00:00:00</div>
                            <div class="text-[10px] font-black text-elevate-primary mt-1.5 uppercase tracking-widest text-right">WIB / GMT+7</div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
                
                
                <div class="lg:col-span-5 space-y-6 md:space-y-8 lg:sticky lg:top-6">
                    
                    
                    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden animate-enter delay-100 group">
                        
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="font-black text-elevate-dark flex items-center gap-3 text-lg">
                                <div class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary border border-elevate-accent/20 flex items-center justify-center">
                                    <i class="ph-bold ph-qr-code text-xl"></i>
                                </div>
                                Scan / Input
                            </h3>
                            
                            
                            <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl border border-slate-200 shadow-inner" title="Auto Focus RFID">
                                <label class="flex items-center cursor-pointer relative">
                                    <input type="checkbox" id="kioskModeToggle" class="sr-only peer" checked>
                                    <div class="w-8 h-4.5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-elevate-primary shadow-inner"></div>
                                    <span class="ml-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">RFID Mode</span>
                                </label>
                            </div>
                        </div>

                        
                        <div class="space-y-5">
                            <div id="cameraContainer" class="hidden mb-4 relative bg-slate-900 rounded-2xl overflow-hidden shadow-inner border-4 border-slate-900 ring-1 ring-white/20">
                                <div id="reader" class="w-full bg-black"></div>
                                <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none z-10">
                                    <span class="bg-elevate-dark/80 text-white text-xs px-4 py-2 rounded-xl backdrop-blur-md border border-white/10 font-bold shadow-sm inline-block">
                                        Arahkan QR Code ke Kamera
                                    </span>
                                </div>
                                <!-- Scan line animation -->
                                <div class="absolute top-0 left-0 w-full h-1 bg-elevate-accent shadow-[0_0_20px_rgba(86,187,241,0.8)] animate-[scan_2s_infinite] z-0 opacity-80"></div>
                            </div>

                            
                            <div class="relative group/input">
                                <input type="text" id="scannerInput" 
                                    class="w-full pl-14 pr-16 py-4 md:py-5 rounded-2xl border-2 border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-mono text-lg md:text-xl font-bold text-elevate-dark transition-all placeholder:text-slate-400 placeholder:font-sans placeholder:font-medium shadow-sm group-hover/input:border-elevate-accent/50 outline-none" 
                                    placeholder="Tempel Kartu / NIS..." autofocus autocomplete="off">
                                
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within/input:text-elevate-primary transition-colors">
                                    <i class="ph-duotone ph-barcode text-2xl"></i>
                                </div>
                                
                                <div id="inputSpinner" class="hidden absolute right-5 top-1/2 -translate-y-1/2 text-elevate-primary">
                                    <i class="ph-bold ph-spinner animate-spin text-2xl"></i>
                                </div>
                                
                                <button id="btnSearch" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white shadow-sm border border-slate-200 text-elevate-primary p-2 md:p-3 rounded-xl hover:bg-elevate-soft hover:border-elevate-accent transition cursor-pointer active:scale-95">
                                    <i class="ph-bold ph-arrow-right text-lg"></i>
                                </button>
                            </div>

                            
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <button onclick="PiketApp.toggleCamera()" id="btnCamera" class="col-span-1 text-xs font-bold px-4 py-4 bg-white hover:bg-elevate-soft text-elevate-dark hover:text-elevate-primary hover:border-elevate-accent/50 rounded-2xl transition-all flex items-center justify-center gap-2 border border-slate-200 shadow-sm active:scale-95">
                                    <i class="ph-bold ph-camera text-xl"></i> <span id="cameraText">Buka Kamera</span>
                                </button>
                                <button onclick="PiketApp.openModalManual()" class="col-span-1 text-xs font-bold px-4 py-4 bg-elevate-soft hover:bg-elevate-primary hover:text-white text-elevate-primary border border-elevate-accent/30 rounded-2xl transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                                    <i class="ph-bold ph-keyboard text-xl"></i> Input Manual
                                </button>
                            </div>
                        </div>

                        
                        <div id="scanFeedback" class="hidden mt-6 p-4 rounded-xl text-center text-sm font-bold animate-pulse transition-all shadow-sm"></div>
                        
                        <div class="mt-6 pt-4 flex justify-between items-center border-t border-slate-100">
                             <span id="focusStatus" class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl hidden uppercase tracking-widest items-center gap-2 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse inline-block"></span> Ready
                            </span>
                            <div class="text-[10px] text-elevate-dark/50 font-bold uppercase tracking-widest ml-auto flex items-center gap-2">
                                Petugas: <span class="text-elevate-dark font-black px-2 py-1 bg-slate-50 rounded-md border border-slate-200"><?php echo e(Auth::user()->name ?? 'Admin'); ?></span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 animate-enter delay-200">
                        <h3 class="font-black text-elevate-dark mb-6 flex items-center gap-2 text-base uppercase tracking-wider">
                            <i class="ph-duotone ph-clock-counter-clockwise text-elevate-primary text-xl"></i> Baru Saja Kembali
                        </h3>
                        
                        <div id="historyContainer" class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                            <?php $__empty_1 = true; $__currentLoopData = $todayHistory ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all duration-300 group shadow-sm hover:shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center font-black text-elevate-dark text-sm shadow-sm border border-slate-200 group-hover:bg-emerald-100 group-hover:text-emerald-700 group-hover:border-emerald-200 transition-colors shrink-0">
                                        <?php echo e(substr($history->student->name, 0, 1)); ?>

                                    </div>
                                    <div class="min-w-0 pr-2">
                                        <div class="text-sm font-bold text-elevate-dark line-clamp-1 group-hover:text-emerald-700 transition-colors"><?php echo e($history->student->name); ?></div>
                                        <div class="text-[10px] text-elevate-dark/60 font-bold uppercase tracking-wider mt-1 truncate">
                                            <?php echo e($history->reason_category); ?> <span class="mx-1 text-slate-300">•</span> <?php echo e($history->duration_minutes); ?> mnt
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black border border-emerald-100 shadow-sm uppercase tracking-wider">
                                        <i class="ph-bold ph-check"></i> <?php echo e($history->time_in->format('H:i')); ?>

                                    </span>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="flex flex-col items-center justify-center py-10 text-elevate-dark/40 border border-dashed border-slate-200 rounded-[2rem] bg-slate-50/50">
                                <i class="ph-duotone ph-coffee text-4xl mb-3 opacity-50 text-elevate-primary"></i>
                                <span class="text-xs font-bold uppercase tracking-widest">Belum ada riwayat.</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-7 animate-enter delay-200 h-full flex flex-col">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col min-h-[500px] lg:h-full relative overflow-hidden">
                        
                        
                        <div class="p-6 md:p-8 border-b border-slate-100 bg-white/90 backdrop-blur-md sticky top-0 z-20 flex justify-between items-center">
                            <div>
                                <h3 class="font-black text-elevate-dark text-lg sm:text-xl flex items-center gap-3">
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-peach-dark opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-elevate-peach-dark"></span>
                                    </span>
                                    Sedang Di Luar
                                </h3>
                                <p class="text-xs text-elevate-dark/60 font-medium mt-1.5 ml-6">Siswa yang belum kembali ke kelas.</p>
                            </div>
                            
                            <div id="activeCountBadge" class="bg-elevate-dark text-white px-5 sm:px-6 py-3 rounded-2xl shadow-lg shadow-elevate-dark/30 text-center min-w-[80px] sm:min-w-[90px] shrink-0 border border-transparent">
                                <span class="block text-2xl sm:text-3xl font-black leading-none"><?php echo e(collect($activePermits ?? [])->count()); ?></span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-elevate-accent mt-1 block">Siswa</span>
                            </div>
                        </div>
                        
                        
                        <div id="activePermitsContainer" class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 bg-slate-50/50">
                            <?php if(collect($activePermits ?? [])->count() > 0): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <?php $__currentLoopData = $activePermits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="permit-card group relative bg-white p-6 rounded-2xl border transition-all duration-300 flex flex-col justify-between shadow-sm hover:-translate-y-1
                                        <?php echo e($permit->is_overdue 
                                            ? 'border-rose-200 hover:border-rose-500 hover:shadow-xl hover:shadow-rose-500/10' 
                                            : 'border-slate-200 hover:border-elevate-accent hover:shadow-xl hover:shadow-elevate-accent/10'); ?>">
                                        
                                        <?php if($permit->is_overdue): ?>
                                            <div class="absolute -top-3 -right-3 bg-rose-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-xl shadow-md animate-pulse z-10 flex items-center gap-1.5 border border-white">
                                                <i class="ph-bold ph-warning"></i> TELAT
                                            </div>
                                        <?php endif; ?>

                                        <div class="flex items-start gap-4 mb-5">
                                            <div class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl font-black shadow-sm transition-colors border
                                                <?php echo e($permit->is_overdue ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-elevate-soft text-elevate-primary border-elevate-accent/20 group-hover:bg-elevate-primary group-hover:text-white'); ?>">
                                                <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                            </div>
                                            <div class="min-w-0 pr-2 pt-1">
                                                <h4 class="font-black text-elevate-dark leading-snug truncate text-sm md:text-base"><?php echo e($permit->student->name); ?></h4>
                                                <p class="text-xs text-elevate-dark/60 font-bold mt-1 flex items-center gap-1.5">
                                                    <i class="ph-bold ph-student"></i> <span class="truncate"><?php echo e($permit->student->schoolClass->name ?? 'Kelas -'); ?></span>
                                                    <span class="text-slate-300 mx-0.5">•</span>
                                                    <span class="font-mono"><?php echo e($permit->student->student_id); ?></span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            
                                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 group-hover:bg-white transition-colors">
                                                <div class="flex justify-between items-center mb-1.5">
                                                    <span class="text-[9px] text-elevate-dark/50 font-black uppercase tracking-widest">Keperluan</span>
                                                    <span class="text-[10px] font-bold text-elevate-dark px-2.5 py-1 bg-white rounded-lg border border-slate-200 shadow-sm"><?php echo e($permit->reason_category); ?></span>
                                                </div>
                                                <?php if($permit->notes): ?>
                                                <p class="text-xs text-elevate-dark/70 italic truncate mt-2 font-medium">"<?php echo e($permit->notes); ?>"</p>
                                                <?php endif; ?>
                                            </div>

                                            <div class="flex items-end justify-between pt-3 border-t border-slate-100">
                                                <div class="text-[10px] font-bold text-elevate-dark/50 uppercase tracking-widest">
                                                    Keluar <span class="text-elevate-dark font-mono font-black text-sm ml-1 bg-slate-50 px-2 py-1 rounded border border-slate-200"><?php echo e($permit->time_out->format('H:i')); ?></span>
                                                </div>
                                                <div class="live-timer text-right" data-start="<?php echo e($permit->time_out); ?>">
                                                    <span class="text-3xl font-black font-mono leading-none tracking-tight <?php echo e($permit->is_overdue ? 'text-rose-600' : 'text-elevate-dark'); ?>">
                                                        <span class="timer-number"><?php echo e($permit->minutes_elapsed); ?></span><span class="text-sm font-bold opacity-50 ml-1 font-sans">mnt</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="flex flex-col items-center justify-center h-full text-elevate-dark/40 py-16 lg:py-24">
                                    <div class="w-32 h-32 md:w-40 md:h-40 bg-white rounded-full flex items-center justify-center mb-8 shadow-sm border border-slate-200 group">
                                        <i class="ph-duotone ph-student text-6xl md:text-7xl text-elevate-primary opacity-50 group-hover:scale-110 group-hover:opacity-100 transition-all duration-500"></i>
                                    </div>
                                    <h4 class="text-xl font-black text-elevate-dark">Kelas Kondusif</h4>
                                    <p class="text-sm max-w-xs text-center mt-2 font-medium text-elevate-dark/60">Semua siswa berada di dalam kelas saat ini. Data siswa yang izin akan muncul di sini.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="permitModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-elevate-dark/80 backdrop-blur-sm transition-opacity" onclick="PiketApp.closeModal()"></div>
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden z-10 animate-enter">
            
            <button type="button" onclick="PiketApp.closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-600 transition cursor-pointer z-20 bg-white shadow-sm border border-slate-200 hover:bg-rose-50 hover:border-rose-200 p-2.5 rounded-full flex items-center justify-center">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
            
            <div class="overflow-y-auto custom-scrollbar p-8 md:p-10 flex-1">
                <div class="text-center mb-8 mt-2">
                    <div class="w-20 h-20 bg-elevate-soft border border-elevate-accent/20 text-elevate-primary rounded-2xl rotate-3 flex items-center justify-center mx-auto mb-6 text-4xl shadow-sm">
                        <i class="ph-duotone ph-door-open"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-elevate-dark tracking-tight">Izin Keluar Kelas</h3>
                    <div class="mt-6 bg-slate-50 rounded-2xl p-5 border border-slate-200 inline-block w-full shadow-inner">
                        <p id="modalStudentName" class="text-elevate-primary font-black text-xl sm:text-2xl leading-tight truncate">Nama Siswa</p>
                        <p id="modalStudentClass" class="text-xs text-elevate-dark/60 font-mono mt-1.5 font-bold uppercase tracking-widest">Kelas Siswa</p>
                    </div>
                </div>

                <form id="permitForm" onsubmit="event.preventDefault(); PiketApp.submitPermitManual();">
                    <input type="hidden" id="modalStudentId" name="student_id">
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <?php $__currentLoopData = ['Toilet', 'UKS', 'Barang Tertinggal', 'Panggilan Guru', 'Dispensasi', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="reason_category" value="<?php echo e($reason); ?>" class="peer sr-only">
                            <div class="p-4 rounded-2xl border border-slate-200 text-center text-xs font-bold text-elevate-dark/70 
                                        group-hover:bg-slate-50 group-hover:border-slate-300
                                        peer-checked:border-elevate-accent peer-checked:bg-elevate-soft peer-checked:text-elevate-primary 
                                        transition-all duration-200 shadow-sm flex items-center justify-center h-full">
                                <?php echo e($reason); ?>

                            </div>
                            <div class="absolute -top-2 -right-2 bg-elevate-dark text-white rounded-full p-1.5 opacity-0 peer-checked:opacity-100 transition-all scale-0 peer-checked:scale-100 transform duration-200 shadow-md ring-2 ring-white">
                                <i class="ph-bold ph-check text-xs"></i>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-xs font-black text-elevate-primary uppercase tracking-widest mb-3 ml-1">Catatan Tambahan</label>
                        <input type="text" name="notes" class="w-full rounded-2xl border-slate-200 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm py-4 px-5 bg-elevate-soft focus:bg-white transition-all placeholder:text-slate-400 font-bold text-elevate-dark shadow-sm outline-none" placeholder="Contoh: Sakit perut, dipanggil Bu Ani...">
                    </div>
                    
                    <button type="submit" id="btnSubmitPermit" class="w-full py-4.5 rounded-2xl bg-elevate-dark text-white font-bold text-lg hover:bg-elevate-primary active:scale-95 transition-all shadow-xl shadow-elevate-dark/30 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent">
                        <span>Berikan Izin</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div id="manualSearchModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-elevate-dark/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('manualSearchModal').classList.add('hidden')"></div>
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 relative z-10 animate-enter border border-slate-100">
            <h3 class="font-black text-2xl mb-2 text-elevate-dark">Input Manual</h3>
            <p class="text-sm text-elevate-dark/60 mb-8 font-medium leading-relaxed">Masukkan NIS atau Nama siswa jika kartu tertinggal atau rusak.</p>
            
            <div class="relative mb-8">
                <input type="text" id="manualInputBox" class="w-full pl-14 pr-4 py-4 rounded-2xl border-slate-200 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark bg-elevate-soft focus:bg-white transition-all shadow-sm outline-none placeholder:text-slate-400" placeholder="Ketik Nama / NIS...">
                <i class="ph-bold ph-keyboard absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-2xl"></i>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-4 border-t border-slate-100 pt-6">
                <button onclick="document.getElementById('manualSearchModal').classList.add('hidden')" class="w-full sm:w-auto px-6 py-4 sm:py-3.5 rounded-2xl text-elevate-dark/60 font-bold hover:bg-slate-100 transition-colors border border-transparent">Batal</button>
                <button onclick="PiketApp.submitManualSearch()" class="w-full sm:w-auto px-8 py-4 sm:py-3.5 rounded-2xl bg-elevate-dark text-white font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 text-center border border-transparent">Cari Data</button>
            </div>
        </div>
    </div>

    
    <script>
        const PiketApp = {
            csrfToken: '<?php echo e(csrf_token()); ?>',
            isProcessing: false,
            isCameraRunning: false,
            html5QrCode: null,
            audioCtx: new (window.AudioContext || window.webkitAudioContext)(),
            
            elements: {
                scannerInput: document.getElementById('scannerInput'),
                scanFeedback: document.getElementById('scanFeedback'),
                kioskModeToggle: document.getElementById('kioskModeToggle'),
                focusStatus: document.getElementById('focusStatus'),
                modal: document.getElementById('permitModal'),
            },

            init() {
                this.startClock();
                this.setupOfflineListener();
                this.setupEventListeners();
                
                setInterval(() => this.updateRealtimeTimers(), 30000); 
                setInterval(() => this.refreshDashboardData(), 60000); 
                
                document.addEventListener('click', () => {
                    if (this.audioCtx.state === 'suspended') this.audioCtx.resume();
                }, { once: true });
            },

            playTone(freq, type, duration) {
                const osc = this.audioCtx.createOscillator();
                const gainNode = this.audioCtx.createGain();
                osc.connect(gainNode);
                gainNode.connect(this.audioCtx.destination);
                osc.type = type;
                osc.frequency.value = freq;
                gainNode.gain.setValueAtTime(0.1, this.audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.0001, this.audioCtx.currentTime + duration);
                osc.start();
                osc.stop(this.audioCtx.currentTime + duration);
            },

            playAudio(type) {
                if (type === 'success') { this.playTone(800, 'sine', 0.1); setTimeout(() => this.playTone(1200, 'sine', 0.3), 100); }
                else if (type === 'error') { this.playTone(150, 'sawtooth', 0.3); }
                else if (type === 'notification') { this.playTone(500, 'triangle', 0.1); }
            },

            startClock() {
                const update = () => {
                    const now = new Date();
                    document.getElementById('clockTime').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
                    document.getElementById('clockDate').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                };
                setInterval(update, 1000);
                update();
            },

            setupOfflineListener() {
                window.addEventListener('offline', () => document.getElementById('offlineIndicator').classList.remove('hidden'));
                window.addEventListener('online', () => {
                    document.getElementById('offlineIndicator').classList.add('hidden');
                    this.playAudio('notification');
                    Swal.fire({ icon: 'success', title: 'Terhubung Kembali', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-2xl border border-emerald-200 shadow-lg bg-emerald-50 text-emerald-700 font-sans' } });
                });
            },

            showFeedback(msg, type) {
                const el = this.elements.scanFeedback;
                el.className = 'mt-6 p-4 rounded-2xl text-center text-sm font-bold animate-pulse transition-all shadow-sm ' + 
                    (type === 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 
                    (type === 'error' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-elevate-soft text-elevate-primary border border-elevate-accent/30'));
                el.innerHTML = msg; el.classList.remove('hidden');
                setTimeout(() => el.classList.add('hidden'), 3000);
            },

            setProcessingState(loading) {
                this.isProcessing = loading;
                const spinner = document.getElementById('inputSpinner');
                if(loading) { 
                    this.elements.scannerInput.disabled = true; 
                    spinner.classList.remove('hidden'); 
                } else { 
                    this.elements.scannerInput.disabled = false; 
                    this.elements.scannerInput.focus(); 
                    spinner.classList.add('hidden'); 
                }
            },

            toggleCamera() {
                const container = document.getElementById('cameraContainer');
                const btnText = document.getElementById('cameraText');
                
                if (this.isCameraRunning) {
                    this.html5QrCode.stop().then(() => {
                        container.classList.add('hidden');
                        btnText.textContent = "Buka Kamera";
                        this.isCameraRunning = false;
                        this.html5QrCode = null;
                    });
                } else {
                    container.classList.remove('hidden');
                    btnText.textContent = "Tutup Kamera";
                    this.html5QrCode = new Html5Qrcode("reader");
                    this.html5QrCode.start(
                        { facingMode: "environment" }, 
                        { fps: 10, qrbox: { width: 250, height: 250 } }, 
                        (decodedText) => {
                            if(this.isProcessing) return;
                            this.html5QrCode.pause(); 
                            this.handleScan(decodedText).then(() => { 
                                setTimeout(() => { if(this.isCameraRunning) this.html5QrCode.resume(); }, 2000); 
                            });
                        }
                    ).then(() => { this.isCameraRunning = true; })
                     .catch(err => { Swal.fire({title: "Error Kamera", text: "Izin kamera diperlukan.", icon: "error", customClass: {popup: 'rounded-[2rem]'}}); container.classList.add('hidden'); });
                }
            },

            async handleScan(code) {
                if(!code || this.isProcessing) return;
                this.setProcessingState(true);
                this.showFeedback('Memproses data...', 'info');

                try {
                    const res = await fetch('<?php echo e(route("permit.scan")); ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                        body: JSON.stringify({ identifier: code })
                    });
                    const data = await res.json();
                    
                    if(!res.ok) throw new Error(data.message || 'Data tidak ditemukan');

                    if(data.mode === 'CHECK_IN') {
                        this.playAudio('success');
                        this.showFeedback(`✅ ${data.data.student.name} KEMBALI`, 'success');
                        await this.refreshDashboardData(); 
                        Swal.fire({ icon: 'success', title: 'Selamat Datang Kembali', text: `${data.data.student.name} (${data.data.duration} menit)`, timer: 2000, showConfirmButton: false, backdrop: `rgba(0,0,0,0.4)`, customClass: { popup: 'rounded-[2.5rem]' } });
                        this.elements.scannerInput.value = '';
                    } else {
                        this.playAudio('notification');
                        
                        const limitIzinHarian = 3; 
                        const countHariIni = data.data.student.today_permit_count || 0;

                        if (countHariIni >= limitIzinHarian) {
                            this.playAudio('error'); 
                            
                            Swal.fire({
                                icon: 'warning',
                                title: '⚠️ Red Zone Peringatan!',
                                html: `<div class="mt-4 text-sm text-elevate-dark font-medium">
                                        Siswa <b class="font-black">${data.data.student.name}</b> sudah izin keluar kelas sebanyak 
                                        <span class="text-rose-600 font-black text-xl mx-1">${countHariIni} KALI</span> hari ini.
                                       </div>
                                       <div class="mt-4 text-xs text-elevate-dark/60 font-bold">Apakah Anda yakin tetap ingin memberikan izin?</div>`,
                                showCancelButton: true,
                                confirmButtonColor: '#e11d48', 
                                cancelButtonColor: '#94a3b8', 
                                confirmButtonText: '<i class="ph-bold ph-warning"></i> Tetap Izinkan',
                                cancelButtonText: 'Batalkan',
                                reverseButtons: true,
                                customClass: {
                                    popup: 'rounded-[2.5rem] shadow-2xl',
                                    confirmButton: 'rounded-2xl font-bold px-8 py-3.5 flex items-center gap-2 border border-transparent shadow-lg shadow-rose-600/30 active:scale-95 transition-all',
                                    cancelButton: 'rounded-2xl font-bold px-8 py-3.5 border border-transparent'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.showFeedback('Silakan pilih alasan...', 'info');
                                    this.openModal(data.data.student);
                                } else {
                                    this.showFeedback('Izin Dibatalkan.', 'error');
                                    this.elements.scannerInput.value = '';
                                    setTimeout(() => this.elements.scannerInput.focus(), 100);
                                }
                            });

                        } else {
                            this.showFeedback('Silakan pilih alasan...', 'info');
                            this.openModal(data.data.student);
                        }
                    }
                } catch (err) {
                    this.playAudio('error');
                    this.showFeedback(err.message, 'error');
                    this.elements.scannerInput.value = ''; 
                    this.elements.scannerInput.focus();
                } finally {
                    this.setProcessingState(false);
                }
            },

            openModalManual() {
                document.getElementById('manualSearchModal').classList.remove('hidden');
                document.getElementById('manualInputBox').focus();
            },

            submitManualSearch() {
                const val = document.getElementById('manualInputBox').value;
                if(val) {
                    this.handleScan(val);
                    document.getElementById('manualSearchModal').classList.add('hidden');
                    document.getElementById('manualInputBox').value = '';
                }
            },

            openModal(student) {
                document.getElementById('modalStudentName').textContent = student.name;
                document.getElementById('modalStudentClass').textContent = student.school_class?.name || 'Kelas Tidak Diketahui';
                document.getElementById('modalStudentId').value = student.id;
                document.querySelectorAll('input[name="reason_category"]').forEach(el => el.checked = false);
                document.querySelector('input[name="notes"]').value = '';
                this.elements.modal.classList.remove('hidden');
            },

            closeModal() {
                this.elements.modal.classList.add('hidden');
                document.getElementById('permitForm').reset();
                this.elements.scannerInput.focus();
            },

            async submitPermitManual() {
                const form = document.getElementById('permitForm');
                const formData = new FormData(form);
                const reason = formData.get('reason_category');
                if (!reason) { Swal.fire({ icon: 'warning', title: 'Pilih Alasan!', timer: 2000, customClass: { popup: 'rounded-[2rem]' } }); return; }

                const btn = document.getElementById('btnSubmitPermit');
                btn.disabled = true; btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-xl"></i> Menyimpan...';

                try {
                    const payload = Object.fromEntries(formData.entries());
                    const res = await fetch('<?php echo e(route("permit.store")); ?>', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken }, body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if(!res.ok) throw new Error(data.message);

                    this.closeModal(); 
                    this.playAudio('success'); 
                    this.elements.scannerInput.value = '';
                    await this.refreshDashboardData();
                    Swal.fire({ icon: 'success', title: 'Izin Tercatat', text: `${data.data.student.name} - ${data.data.reason}`, timer: 2000, showConfirmButton: false, customClass: { popup: 'rounded-[2.5rem]' } });
                } catch (err) {
                    this.playAudio('error'); Swal.fire({ icon: 'error', title: 'Gagal', text: err.message, customClass: { popup: 'rounded-[2.5rem]' } });
                } finally {
                    btn.disabled = false; btn.innerHTML = '<span>Berikan Izin</span> <i class="ph-bold ph-arrow-right"></i>';
                    setTimeout(() => this.elements.scannerInput.focus(), 100);
                }
            },

            async refreshDashboardData() {
                if(navigator.onLine === false) return; 

                const container1 = document.getElementById('activePermitsContainer');
                const container2 = document.getElementById('historyContainer');
                const badge = document.getElementById('activeCountBadge');

                try {
                    const response = await fetch(window.location.href);
                    const text = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(text, 'text/html');
                    if(container1) container1.innerHTML = doc.getElementById('activePermitsContainer').innerHTML;
                    if(container2) container2.innerHTML = doc.getElementById('historyContainer').innerHTML;
                    if(badge) badge.innerHTML = doc.getElementById('activeCountBadge').innerHTML;
                } catch (error) { 
                    console.warn('Silent Refresh failed', error); 
                }
            },

            updateRealtimeTimers() {
                document.querySelectorAll('.live-timer').forEach(el => {
                    const diffMins = Math.floor((new Date().getTime() - new Date(el.dataset.start).getTime()) / 60000);
                    const numberDisplay = el.querySelector('.timer-number');
                    if(numberDisplay) numberDisplay.textContent = diffMins;
                    if(diffMins > 15) { 
                        const card = el.closest('.permit-card');
                        if(card) {
                            card.classList.add('border-rose-200', 'shadow-[0_4px_20px_-4px_rgba(225,29,72,0.15)]');
                            card.classList.remove('border-slate-200', 'shadow-sm');
                        }
                        const timerText = numberDisplay.closest('span');
                        if(timerText) {
                            timerText.classList.remove('text-elevate-dark');
                            timerText.classList.add('text-rose-600');
                        }
                    }
                });
            },
            
            setupEventListeners() {
                const { scannerInput, kioskModeToggle, focusStatus, modal } = this.elements;
                
                scannerInput.addEventListener('focus', () => { 
                    focusStatus.classList.remove('hidden');
                    focusStatus.classList.add('flex');
                });
                
                scannerInput.addEventListener('blur', () => {
                    focusStatus.classList.add('hidden');
                    focusStatus.classList.remove('flex');
                    if (kioskModeToggle.checked && modal.classList.contains('hidden') && document.getElementById('manualSearchModal').classList.contains('hidden')) {
                        setTimeout(() => { 
                            if(document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "TEXTAREA") scannerInput.focus(); 
                        }, 200); 
                    }
                });

                document.addEventListener('click', (e) => {
                    if (kioskModeToggle.checked) {
                        const isInteractive = e.target.closest('input, button, a, #permitModal, #manualSearchModal, label');
                        if (!isInteractive && modal.classList.contains('hidden') && document.getElementById('manualSearchModal').classList.contains('hidden')) {
                            scannerInput.focus();
                        }
                    }
                });

                scannerInput.addEventListener('keypress', (e) => { 
                    if (e.key === 'Enter') { e.preventDefault(); this.handleScan(scannerInput.value); } 
                });

                document.getElementById('btnSearch').addEventListener('click', () => this.handleScan(scannerInput.value));
            }
        };

        document.addEventListener('DOMContentLoaded', () => PiketApp.init());
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/permit/index.blade.php ENDPATH**/ ?>