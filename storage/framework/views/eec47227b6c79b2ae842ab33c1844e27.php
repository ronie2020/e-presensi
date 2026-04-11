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
        
        /* Animations from PPDB Style */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        
        /* Glass & Scrollbar */
        .glass-panel { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .glass-card-light { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        
        /* Scanner Specific */
        #reader { width: 100%; border-radius: 1.5rem; overflow: hidden; position: relative; }
        #reader video { object-fit: cover; border-radius: 1.5rem; }
        
        .input-glow:focus { box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2); }
        .digital-clock { font-feature-settings: "tnum"; font-variant-numeric: tabular-nums; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-8 sm:py-10 relative min-h-screen font-sans text-slate-800 bg-slate-50/50">
        
        
        <div id="offlineIndicator" class="fixed bottom-6 right-6 z-50 hidden animate-bounce">
            <div class="bg-rose-600 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 border-2 border-rose-400">
                <i class="ph-bold ph-wifi-slash text-xl"></i>
                <div>
                    <div class="font-bold text-sm">Koneksi Terputus</div>
                    <div class="text-[10px] opacity-90">Menunggu sambungan...</div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8">
            
            
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-6 md:p-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden group border border-white/10">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row gap-8 items-start lg:items-center justify-between">
                    
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 w-full lg:w-auto">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                            <i class="ph-duotone ph-shield-check text-4xl text-blue-300"></i>
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-blue-200 text-[10px] font-bold uppercase tracking-wider mb-2 backdrop-blur-sm shadow-sm">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-400"></span>
                                </span>
                                Sistem Monitoring Realtime
                            </div>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-none">
                                Pos Guru <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Piket</span>
                            </h1>
                            <p class="text-blue-100/80 text-sm mt-2 max-w-md">
                                Kelola izin keluar masuk siswa dengan cepat dan akurat.
                            </p>
                        </div>
                    </div>

                    
                    <div class="bg-slate-900/50 backdrop-blur-sm border border-white/10 p-5 rounded-[2rem] relative overflow-hidden flex items-center justify-between gap-6 w-full lg:w-auto shrink-0 mt-4 lg:mt-0">
                        <div class="absolute top-0 right-0 p-4 opacity-10 text-white pointer-events-none">
                            <i class="ph-fill ph-clock text-7xl"></i>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-blue-200 uppercase tracking-widest mb-1 flex items-center gap-2 relative z-10">
                                <i class="ph-bold ph-calendar-blank"></i> Waktu Sekarang
                            </h3>
                            <div id="clockDate" class="text-white text-sm font-medium relative z-10 opacity-90">...</div>
                        </div>

                        <div class="text-right relative z-10 bg-black/20 px-4 py-2 rounded-xl border border-white/5 shrink-0">
                            <div id="clockTime" class="text-3xl sm:text-4xl font-black text-white digital-clock tracking-tight leading-none">00:00:00</div>
                            <div class="text-[10px] font-bold text-emerald-400 mt-1 uppercase tracking-wider text-right">WIB / GMT+7</div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
                
                
                <div class="lg:col-span-5 space-y-6 md:space-y-8 lg:sticky lg:top-6">
                    
                    
                    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden animate-enter delay-100 group hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500">
                        
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-800 flex items-center gap-3 text-lg">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="ph-bold ph-qr-code"></i>
                                </div>
                                Scan / Input
                            </h3>
                            
                            
                            <div class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100 shadow-inner" title="Auto Focus RFID">
                                <label class="flex items-center cursor-pointer relative">
                                    <input type="checkbox" id="kioskModeToggle" class="sr-only peer" checked>
                                    <div class="w-7 h-4 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-indigo-600"></div>
                                    <span class="ml-2 text-[10px] font-black text-slate-500 uppercase tracking-wide">RFID Mode</span>
                                </label>
                            </div>
                        </div>

                        
                        <div class="space-y-4">
                            <div id="cameraContainer" class="hidden mb-4 relative bg-slate-900 rounded-[1.5rem] overflow-hidden shadow-inner border-[6px] border-slate-900 ring-1 ring-white/20">
                                <div id="reader" class="w-full h-64 bg-black"></div>
                                <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none z-10">
                                    <span class="bg-white/10 text-white text-[10px] px-3 py-1.5 rounded-full backdrop-blur-md border border-white/20 font-bold shadow-lg">
                                        Arahkan QR Code ke Kamera
                                    </span>
                                </div>
                                <!-- Scan line animation -->
                                <div class="absolute top-0 left-0 w-full h-1 bg-red-500 shadow-[0_0_20px_rgba(239,68,68,0.8)] animate-[scan_2s_infinite] z-0 opacity-50"></div>
                            </div>

                            
                            <div class="relative group/input">
                                <input type="text" id="scannerInput" 
                                    class="w-full pl-14 pr-12 py-4 md:py-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-0 font-mono text-lg md:text-xl font-bold text-slate-800 transition-all placeholder:text-slate-400 placeholder:font-sans placeholder:font-medium input-glow shadow-sm group-hover/input:border-slate-200" 
                                    placeholder="Tempel Kartu / NIS..." autofocus autocomplete="off">
                                
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within/input:text-indigo-600 transition-colors">
                                    <i class="ph-duotone ph-barcode text-2xl"></i>
                                </div>
                                
                                <div id="inputSpinner" class="hidden absolute right-5 top-1/2 -translate-y-1/2 text-indigo-500">
                                    <i class="ph-bold ph-spinner animate-spin text-xl"></i>
                                </div>
                                
                                <button id="btnSearch" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white shadow-sm border border-slate-200 text-indigo-600 p-2 md:p-2.5 rounded-xl hover:bg-indigo-50 transition cursor-pointer active:scale-95">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </button>
                            </div>

                            
                            <div class="grid grid-cols-2 gap-3">
                                <button onclick="PiketApp.toggleCamera()" id="btnCamera" class="col-span-1 text-xs font-bold px-4 py-3.5 bg-white hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-xl transition flex items-center justify-center gap-2 border border-slate-200 shadow-sm active:translate-y-0.5">
                                    <i class="ph-bold ph-camera text-lg"></i> <span id="cameraText">Buka Kamera</span>
                                </button>
                                <button onclick="PiketApp.openModalManual()" class="col-span-1 text-xs font-bold px-4 py-3.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 hover:text-indigo-700 rounded-xl transition flex items-center justify-center gap-2 border border-indigo-100 shadow-sm active:translate-y-0.5">
                                    <i class="ph-bold ph-keyboard text-lg"></i> Input Manual
                                </button>
                            </div>
                        </div>

                        
                        <div id="scanFeedback" class="hidden mt-4 p-4 rounded-xl text-center text-sm font-bold animate-pulse transition-all shadow-sm"></div>
                        
                        <div class="mt-4 flex justify-between items-center px-1">
                             <span id="focusStatus" class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 hidden uppercase tracking-wider items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span> Ready
                            </span>
                            <div class="text-[10px] text-slate-400 font-medium ml-auto">
                                Petugas: <span class="text-slate-600 font-bold"><?php echo e(Auth::user()->name ?? 'Admin'); ?></span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 animate-enter delay-200">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <i class="ph-duotone ph-clock-counter-clockwise text-indigo-500 text-lg"></i> Baru Saja Kembali
                        </h3>
                        
                        <div id="historyContainer" class="space-y-3 max-h-[250px] overflow-y-auto custom-scrollbar pr-2">
                            <?php $__empty_1 = true; $__currentLoopData = $todayHistory ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 hover:border-emerald-100 hover:bg-emerald-50/30 transition-all duration-300 group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center font-black text-slate-500 text-xs shadow-sm border border-slate-200 group-hover:scale-105 transition-transform shrink-0">
                                        <?php echo e(substr($history->student->name, 0, 1)); ?>

                                    </div>
                                    <div class="min-w-0 pr-2">
                                        <div class="text-sm font-bold text-slate-700 line-clamp-1 group-hover:text-emerald-700 transition-colors"><?php echo e($history->student->name); ?></div>
                                        <div class="text-[10px] text-slate-500 font-medium truncate">
                                            <?php echo e($history->reason_category); ?> <span class="mx-1 text-slate-300">•</span> <?php echo e($history->duration_minutes); ?> m
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                        <i class="ph-bold ph-check"></i> <?php echo e($history->time_in->format('H:i')); ?>

                                    </span>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="flex flex-col items-center justify-center py-8 text-slate-400 border-2 border-dashed border-slate-100 rounded-2xl">
                                <i class="ph-duotone ph-coffee text-2xl mb-1 opacity-50"></i>
                                <span class="text-xs font-medium">Belum ada riwayat.</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-7 animate-enter delay-200 h-full flex flex-col">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col min-h-[500px] lg:h-full relative">
                        
                        
                        <div class="p-6 md:p-8 border-b border-slate-50 bg-white/80 backdrop-blur-md sticky top-0 z-20 flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-lg sm:text-xl flex items-center gap-2">
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                                    </span>
                                    Sedang Di Luar
                                </h3>
                                <p class="text-xs text-slate-500 font-medium mt-1 ml-5">Siswa yang belum kembali ke kelas.</p>
                            </div>
                            
                            <div id="activeCountBadge" class="bg-slate-900 text-white px-4 sm:px-5 py-2 rounded-xl shadow-lg shadow-slate-900/20 text-center min-w-[70px] sm:min-w-[80px] shrink-0">
                                <span class="block text-xl sm:text-2xl font-black leading-none"><?php echo e(collect($activePermits ?? [])->count()); ?></span>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Siswa</span>
                            </div>
                        </div>
                        
                        
                        <div id="activePermitsContainer" class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-slate-50/50">
                            <?php if(collect($activePermits ?? [])->count() > 0): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <?php $__currentLoopData = $activePermits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="permit-card group relative bg-white p-5 rounded-3xl border transition-all duration-300 flex flex-col justify-between hover:scale-[1.02]
                                        <?php echo e($permit->is_overdue 
                                            ? 'border-rose-100 shadow-[0_4px_20px_-4px_rgba(244,63,94,0.15)] hover:border-rose-300' 
                                            : 'border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:border-blue-300 hover:shadow-blue-100/50'); ?>">
                                        
                                        <?php if($permit->is_overdue): ?>
                                            <div class="absolute -top-2 -right-2 bg-rose-500 text-white text-[10px] font-bold px-3 py-1 rounded-lg shadow-md animate-pulse z-10 flex items-center gap-1 border border-white">
                                                <i class="ph-bold ph-warning"></i> TELAT
                                            </div>
                                        <?php endif; ?>

                                        <div class="flex items-start gap-4 mb-4">
                                            <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center text-lg font-bold shadow-sm transition-colors
                                                <?php echo e($permit->is_overdue ? 'bg-rose-50 text-rose-600' : 'bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white'); ?>">
                                                <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                            </div>
                                            <div class="min-w-0 pr-2">
                                                <h4 class="font-bold text-slate-800 leading-snug truncate text-sm md:text-base"><?php echo e($permit->student->name); ?></h4>
                                                <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1">
                                                    <i class="ph-bold ph-student"></i> <span class="truncate"><?php echo e($permit->student->schoolClass->name ?? 'Kelas -'); ?></span>
                                                    <span class="text-slate-300 mx-0.5">|</span>
                                                    <span class="font-mono"><?php echo e($permit->student->student_id); ?></span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            
                                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100 group-hover:bg-white transition-colors">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Keperluan</span>
                                                    <span class="text-[10px] font-bold text-slate-700 px-2 py-0.5 bg-white rounded-lg border border-slate-200 shadow-sm"><?php echo e($permit->reason_category); ?></span>
                                                </div>
                                                <?php if($permit->notes): ?>
                                                <p class="text-xs text-slate-500 italic truncate mt-1">"<?php echo e($permit->notes); ?>"</p>
                                                <?php endif; ?>
                                            </div>

                                            <div class="flex items-end justify-between pt-2 border-t border-slate-50">
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                                    Keluar <span class="text-slate-700 font-mono text-xs ml-1"><?php echo e($permit->time_out->format('H:i')); ?></span>
                                                </div>
                                                <div class="live-timer text-right" data-start="<?php echo e($permit->time_out); ?>">
                                                    <span class="text-2xl font-black font-mono leading-none tracking-tight <?php echo e($permit->is_overdue ? 'text-rose-500' : 'text-slate-700'); ?>">
                                                        <span class="timer-number"><?php echo e($permit->minutes_elapsed); ?></span><span class="text-sm font-bold opacity-50 ml-0.5">m</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="flex flex-col items-center justify-center h-full text-slate-400 py-16 lg:py-24">
                                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-full flex items-center justify-center mb-6 shadow-sm border border-slate-100 group">
                                        <i class="ph-duotone ph-student text-5xl md:text-6xl text-slate-300 group-hover:scale-110 transition-transform duration-500"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-600">Kelas Kondusif</h4>
                                    <p class="text-sm max-w-xs text-center mt-2 opacity-70">Semua siswa berada di dalam kelas saat ini.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="permitModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="PiketApp.closeModal()"></div>
        
        <!-- Modal Content (Safe Overflow for Mobile) -->
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative border border-white/20 flex flex-col max-h-[90vh] overflow-hidden z-10 animate-enter">
            
            <button type="button" onclick="PiketApp.closeModal()" class="absolute top-4 right-4 sm:top-6 sm:right-6 text-slate-400 hover:text-rose-500 transition cursor-pointer z-20 bg-white shadow-sm border border-slate-100 hover:bg-rose-50 p-2 rounded-full">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
            
            <div class="overflow-y-auto custom-scrollbar p-6 sm:p-8 flex-1">
                <div class="text-center mb-6 sm:mb-8 mt-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-50 to-blue-100 text-indigo-600 rounded-2xl rotate-3 flex items-center justify-center mx-auto mb-4 text-3xl shadow-lg shadow-indigo-100/50 border border-white">
                        <i class="ph-duotone ph-door-open"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Izin Keluar Kelas</h3>
                    <div class="mt-4 bg-slate-50 rounded-2xl p-4 border border-slate-100 inline-block w-full">
                        <p id="modalStudentName" class="text-indigo-600 font-black text-lg sm:text-xl leading-tight">Nama Siswa</p>
                        <p id="modalStudentClass" class="text-xs text-slate-500 font-mono mt-1 font-bold uppercase tracking-wider">Kelas Siswa</p>
                    </div>
                </div>

                <form id="permitForm" onsubmit="event.preventDefault(); PiketApp.submitPermitManual();">
                    <input type="hidden" id="modalStudentId" name="student_id">
                    
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <?php $__currentLoopData = ['Toilet', 'UKS', 'Barang Tertinggal', 'Panggilan Guru', 'Dispensasi', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="reason_category" value="<?php echo e($reason); ?>" class="peer sr-only">
                            <div class="p-3.5 rounded-2xl border-2 border-slate-100 text-center text-xs font-bold text-slate-600 
                                        group-hover:bg-slate-50 group-hover:border-slate-300
                                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 
                                        transition-all duration-200 shadow-sm flex items-center justify-center h-full">
                                <?php echo e($reason); ?>

                            </div>
                            <div class="absolute -top-2 -right-2 bg-indigo-600 text-white rounded-full p-1 opacity-0 peer-checked:opacity-100 transition-all scale-0 peer-checked:scale-100 transform duration-200 shadow-md ring-2 ring-white">
                                <i class="ph-bold ph-check text-xs"></i>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Catatan Tambahan</label>
                        <input type="text" name="notes" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm py-3 px-4 bg-slate-50 focus:bg-white transition-colors placeholder:text-slate-300 font-medium" placeholder="Contoh: Sakit perut, dipanggil Bu Ani...">
                    </div>
                    
                    <button type="submit" id="btnSubmitPermit" class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span>Berikan Izin</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div id="manualSearchModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('manualSearchModal').classList.add('hidden')"></div>
        
        <!-- Modal Content -->
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-6 sm:p-8 relative z-10 animate-enter border border-white/20">
            <h3 class="font-extrabold text-xl mb-2 text-slate-800">Input Manual</h3>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">Masukkan NIS atau Nama siswa jika kartu tertinggal atau rusak.</p>
            
            <div class="relative mb-6">
                <input type="text" id="manualInputBox" class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-0 font-bold text-slate-700 bg-slate-50 focus:bg-white transition-colors" placeholder="Ketik Nama / NIS...">
                <i class="ph-bold ph-keyboard absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-4">
                <button onclick="document.getElementById('manualSearchModal').classList.add('hidden')" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-100 transition-colors">Batal</button>
                <button onclick="PiketApp.submitManualSearch()" class="w-full sm:w-auto px-6 py-3 sm:py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95 text-center">Cari Data</button>
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

            // --- AUDIO UTILS ---
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

            // --- UI HELPERS ---
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
                    Swal.fire({ icon: 'success', title: 'Terhubung Kembali', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-2xl border border-emerald-100 shadow-lg' } });
                });
            },

            showFeedback(msg, type) {
                const el = this.elements.scanFeedback;
                el.className = 'mt-4 p-4 rounded-xl text-center text-sm font-bold animate-pulse transition-all shadow-sm ' + 
                    (type === 'success' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 
                    (type === 'error' ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-blue-100 text-blue-700 border border-blue-200'));
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

            // --- CAMERA LOGIC ---
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
                     .catch(err => { Swal.fire({title: "Error Kamera", text: "Izin kamera diperlukan.", icon: "error", customClass: {popup: 'rounded-3xl'}}); container.classList.add('hidden'); });
                }
            },

            // --- CORE LOGIC (TERMASUK RED ZONE) ---
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
                        Swal.fire({ icon: 'success', title: 'Selamat Datang Kembali', text: `${data.data.student.name} (${data.data.duration} menit)`, timer: 2000, showConfirmButton: false, backdrop: `rgba(0,0,0,0.4)`, customClass: { popup: 'rounded-3xl' } });
                        this.elements.scannerInput.value = '';
                    } else {
                        // ---- AWAL LOGIKA RED ZONE ----
                        this.playAudio('notification');
                        
                        const limitIzinHarian = 3; // Batas maksimal izin per hari
                        const countHariIni = data.data.student.today_permit_count || 0;

                        if (countHariIni >= limitIzinHarian) {
                            // Tampilkan peringatan keras jika izin sudah mencapai batas
                            this.playAudio('error'); 
                            
                            Swal.fire({
                                icon: 'warning',
                                title: '⚠️ Red Zone Peringatan!',
                                html: `<div class="mt-2 text-sm text-slate-600">
                                        Siswa <b>${data.data.student.name}</b> sudah izin keluar kelas sebanyak 
                                        <span class="text-rose-600 font-black text-lg mx-1">${countHariIni} KALI</span> hari ini.
                                       </div>
                                       <div class="mt-3 text-xs text-slate-400">Apakah Anda yakin tetap ingin memberikan izin?</div>`,
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444', 
                                cancelButtonColor: '#94a3b8', 
                                confirmButtonText: '<i class="ph-bold ph-warning"></i> Tetap Izinkan',
                                cancelButtonText: 'Batalkan',
                                reverseButtons: true,
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'rounded-xl font-bold px-6 py-3 flex items-center gap-2',
                                    cancelButton: 'rounded-xl font-bold px-6 py-3'
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
                            // Alur Normal jika belum mencapai batas
                            this.showFeedback('Silakan pilih alasan...', 'info');
                            this.openModal(data.data.student);
                        }
                        // ---- AKHIR LOGIKA RED ZONE ----
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
                if (!reason) { Swal.fire({ icon: 'warning', title: 'Pilih Alasan!', timer: 2000, customClass: { popup: 'rounded-3xl' } }); return; }

                const btn = document.getElementById('btnSubmitPermit');
                btn.disabled = true; btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

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
                    Swal.fire({ icon: 'success', title: 'Izin Tercatat', text: `${data.data.student.name} - ${data.data.reason}`, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl' } });
                } catch (err) {
                    this.playAudio('error'); Swal.fire({ icon: 'error', title: 'Gagal', text: err.message, customClass: { popup: 'rounded-3xl' } });
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
                            card.classList.add('border-rose-100', 'shadow-[0_4px_20px_-4px_rgba(244,63,94,0.15)]');
                            card.classList.remove('border-slate-100', 'shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]');
                        }
                        const timerText = numberDisplay.closest('span');
                        if(timerText) {
                            timerText.classList.remove('text-slate-700');
                            timerText.classList.add('text-rose-500');
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\permit\index.blade.php ENDPATH**/ ?>