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
        
        /* Scanner Styles */
        .scanner-container { position: relative; overflow: hidden; border-radius: 1.5rem; transform: translateZ(0); }
        .scanner-overlay {
            position: absolute; inset: 0; pointer-events: none;
            border: 0px solid transparent; border-radius: 1.5rem;
            transition: all 0.3s ease; z-index: 20;
        }
        
        /* Scan Effects (Glow borders) */
        .scan-success-effect { box-shadow: inset 0 0 40px rgba(34, 197, 94, 0.5); border: 2px solid #22c55e; }
        .scan-warning-effect { box-shadow: inset 0 0 40px rgba(245, 158, 11, 0.5); border: 2px solid #f59e0b; }
        .scan-error-effect { box-shadow: inset 0 0 40px rgba(239, 68, 68, 0.5); border: 2px solid #ef4444; }
        .scan-makan-effect { box-shadow: inset 0 0 40px rgba(249, 115, 22, 0.5); border: 2px solid #f97316; }

        .scanner-line {
            position: absolute; width: 100%; height: 3px;
            background: #6366f1; box-shadow: 0 0 15px #6366f1;
            top: 0; animation: scanMove 2.5s infinite linear;
            z-index: 10; opacity: 0.8;
        }

        /* Utility */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .hidden-col { display: none !important; }
        .hidden-row { display: none !important; }
        
        @keyframes highlightRow { 0% { background-color: #e0e7ff; } 100% { background-color: transparent; } }
        .new-row-entry { animation: highlightRow 2s ease-out; }
        
        .glass-panel { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .scan-type-btn.ring-2 .indicator-dot { transform: scale(1.2); background-color: currentColor; }
    </style>
    <?php $__env->stopPush(); ?>

    <?php
        $safeSchedule = $scheduleConfig ?? [];
        $scheduleJson = json_encode($safeSchedule);
        $totalTarget = $statsConfig['total_target'] ?? 0;
        $currentTaken = $statsConfig['current_taken'] ?? 0;
    ?>

    <div class="py-6 font-sans text-slate-800 bg-slate-50/50 min-h-screen selection:bg-indigo-100 selection:text-indigo-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-indigo-900 via-slate-800 to-slate-900 p-6 md:p-8 text-white shadow-2xl shadow-indigo-900/20 overflow-hidden group border border-white/10">
                
                
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-5 w-full md:w-auto">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner flex-shrink-0">
                            <i class="ph-duotone ph-scan text-4xl text-blue-300"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <?php if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false)): ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-200 border border-rose-500/30 text-[10px] font-bold uppercase tracking-wider">Libur: <?php echo e($scheduleConfig['description']); ?></span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-500/30 text-[10px] font-bold uppercase tracking-wider">
                                        <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight leading-none">
                                Scanner <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Aktivitas</span>
                            </h2>
                            <p class="text-blue-100/70 text-sm mt-1">Monitoring kehadiran, makan siang, dan ibadah.</p>
                        </div>
                    </div>
                    
                    
                    <div class="glass-panel px-6 py-3 rounded-2xl flex items-center gap-4 shadow-lg w-full md:w-auto justify-between md:justify-start">
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-0.5">Waktu Server</p>
                            <div id="clock" class="text-3xl font-black text-white font-mono leading-none tracking-widest digital-clock">00:00:00</div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/40 animate-pulse">
                            <i class="ph-bold ph-clock text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                
                <div class="lg:col-span-5 flex flex-col gap-6 animate-enter delay-100">
                    
                    
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
                        <div class="flex justify-between items-center mb-5 px-1">
                            <h3 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                                </span>
                                Kamera Aktif
                            </h3>
                            <div id="mode-badge" class="pl-2 pr-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 border border-slate-200 flex items-center gap-2 transition-all">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                <span id="mode-text">Standby</span>
                            </div>
                        </div>

                        
                        <div class="scanner-container relative bg-slate-900 aspect-[4/3] w-full rounded-2xl border-4 border-slate-900 shadow-inner">
                            <div id="qr-reader" class="w-full h-full object-cover rounded-xl overflow-hidden"></div>
                            
                            
                            <div id="scanner-overlay-el" class="scanner-overlay">
                                <div class="scanner-line"></div>
                                
                                <div class="absolute top-4 left-4 w-10 h-10 border-t-4 border-l-4 border-white/30 rounded-tl-2xl"></div>
                                <div class="absolute top-4 right-4 w-10 h-10 border-t-4 border-r-4 border-white/30 rounded-tr-2xl"></div>
                                <div class="absolute bottom-4 left-4 w-10 h-10 border-b-4 border-l-4 border-white/30 rounded-bl-2xl"></div>
                                <div class="absolute bottom-4 right-4 w-10 h-10 border-b-4 border-r-4 border-white/30 rounded-br-2xl"></div>
                            </div>

                            
                            <div class="absolute bottom-6 inset-x-0 flex justify-center z-30 pointer-events-none">
                                <div id="scan-status" class="bg-white/10 backdrop-blur-md text-white text-xs py-2 px-5 rounded-full font-bold border border-white/20 shadow-lg flex items-center gap-2 transition-all">
                                    <i class="ph-bold ph-circle-notch animate-spin text-indigo-300"></i> Memuat Kamera...
                                </div>
                            </div>
                        </div>

                        
                        <div id="scan-result" class="mt-4 p-4 rounded-2xl font-bold text-sm text-center hidden transition-all duration-300 transform scale-95 opacity-0 border border-transparent shadow-sm"></div>
                        
                        <button id="btn-reset-auto" class="hidden w-full mt-4 py-3.5 rounded-2xl border-2 border-dashed border-indigo-200 text-indigo-600 font-bold text-xs uppercase tracking-wider hover:bg-indigo-50 hover:border-indigo-300 transition-all flex items-center justify-center gap-2 group/btn" onclick="resetAutoMode()">
                            <i class="ph-bold ph-arrows-clockwise group-hover/btn:rotate-180 transition-transform duration-500"></i> Kembali ke Mode Otomatis
                        </button>
                    </div>

                    
                    <div id="makan-stats-panel" class="hidden grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-[2rem] text-white shadow-lg shadow-orange-500/20 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="ph-fill ph-check-circle text-6xl"></i>
                            </div>
                            <p class="text-[10px] font-bold text-orange-100 uppercase tracking-widest mb-1">Sudah Ambil</p>
                            <h3 class="text-4xl font-black tracking-tight" id="stat-taken"><?php echo e($currentTaken); ?></h3>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <i class="ph-duotone ph-users text-6xl"></i>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Belum Ambil</p>
                            <h3 class="text-4xl font-black text-slate-800 tracking-tight" id="stat-remaining"><?php echo e($totalTarget - $currentTaken); ?></h3>
                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-3 px-2">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pilih Mode Manual</h4>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <?php $__currentLoopData = [
                                ['id'=>'harian', 'label'=>'Absen Harian', 'sub'=>'Masuk/Pulang', 'icon'=>'calendar-check', 'color'=>'blue', 'type'=>'Harian'],
                                ['id'=>'makan', 'label'=>'Makan Siang', 'sub'=>'Scan Gizi', 'icon'=>'bowl-food', 'color'=>'orange', 'type'=>'Makan'],
                                ['id'=>'dhuha', 'label'=>'Sholat Dhuha', 'sub'=>'Ibadah Pagi', 'icon'=>'sun-horizon', 'color'=>'emerald', 'type'=>'Dhuha'],
                                ['id'=>'dhuhur', 'label'=>'Sholat Dhuhur', 'sub'=>'Ibadah Siang', 'icon'=>'moon-stars', 'color'=>'amber', 'type'=>'Dhuhur'],
                                ['id'=>'ekskul', 'label'=>'Ekstrakurikuler', 'sub'=>'Kegiatan Sore', 'icon'=>'basketball', 'color'=>'purple', 'type'=>'Ekstrakurikuler']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button id="btn-<?php echo e($mode['id']); ?>" data-type="<?php echo e($mode['type']); ?>" class="scan-type-btn bg-white p-4 rounded-[1.5rem] shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-<?php echo e($mode['color']); ?>-100/50 hover:border-<?php echo e($mode['color']); ?>-200 transition-all duration-300 text-left group active:scale-95 relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-10 transition-opacity">
                                    <i class="ph-fill ph-<?php echo e($mode['icon']); ?> text-4xl text-<?php echo e($mode['color']); ?>-500"></i>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-<?php echo e($mode['color']); ?>-50 text-<?php echo e($mode['color']); ?>-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-<?php echo e($mode['icon']); ?>"></i>
                                </div>
                                <h3 class="font-bold text-slate-700 text-xs leading-tight mb-1"><?php echo e($mode['label']); ?></h3>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] text-slate-400 font-medium"><?php echo e($mode['sub']); ?></p>
                                    <div class="w-1.5 h-1.5 rounded-full border border-slate-300 indicator-dot transition-all"></div>
                                </div>
                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div id="extra-selector-container" class="hidden bg-white p-5 rounded-[2rem] border border-slate-100 shadow-lg shadow-purple-100/50">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block mb-2 px-1">Pilih Kegiatan Ekskul</label>
                        <div class="relative">
                            <select id="extra-activity-select" class="w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-0 font-bold text-slate-700 py-3 pl-4 pr-10 text-sm bg-slate-50 cursor-pointer hover:bg-white transition-colors">
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
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col min-h-[600px] overflow-hidden">
                        
                        
                        <div class="p-6 md:p-8 border-b border-slate-50 bg-white/80 backdrop-blur-md sticky top-0 z-20 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-slate-800 text-xl flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <i class="ph-duotone ph-list-dashes text-lg"></i>
                                    </div>
                                    Log Aktivitas
                                </h3>
                                <p class="text-xs text-slate-500 font-medium mt-1 ml-10">Monitoring kehadiran realtime</p>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full shadow-sm">
                                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Live</span>
                            </div>
                        </div>
                        
                        
                        <div class="flex-1 overflow-hidden relative bg-slate-50/30">
                            <div class="absolute inset-0 overflow-auto custom-scrollbar p-2">
                                <table class="w-full text-left border-collapse">
                                    <thead class="sticky top-0 z-10">
                                        <tr>
                                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/90 backdrop-blur rounded-l-xl">Siswa</th>
                                            <th class="col-harian px-4 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/90 backdrop-blur">Masuk</th>
                                            <th class="col-harian px-4 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/90 backdrop-blur">Pulang</th>
                                            <th class="col-waktu hidden-col px-4 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/90 backdrop-blur">Waktu</th>
                                            <th class="col-kegiatan hidden-col px-4 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/90 backdrop-blur">Kegiatan</th>
                                            <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/90 backdrop-blur rounded-r-xl">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scan-log" class="text-sm">
                                        <?php if(isset($recentScans)): ?>
                                            <?php $__currentLoopData = $recentScans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="log-entry group hover:bg-white transition-all duration-300 rounded-xl border-b border-slate-50 last:border-0"
                                                    data-type-raw="<?php echo e($scan['type_raw']); ?>">
                                                    
                                                    <td class="px-6 py-4 rounded-l-xl">
                                                        <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors"><?php echo e($scan['student_name']); ?></div>
                                                        <div class="text-[10px] text-slate-400 font-mono font-bold"><?php echo e($scan['student_id']); ?></div>
                                                    </td>
                                                    
                                                    
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        <?php if($scan['time_in']): ?> <span class="font-mono font-bold text-slate-600 bg-white border border-slate-100 px-2 py-1 rounded-lg text-xs shadow-sm"><?php echo e($scan['time_in']); ?></span>
                                                        <?php else: ?> <span class="text-slate-300 font-bold">-</span> <?php endif; ?>
                                                    </td>
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        <?php if($scan['time_out']): ?> <span class="font-mono font-bold text-slate-600 bg-white border border-slate-100 px-2 py-1 rounded-lg text-xs shadow-sm"><?php echo e($scan['time_out']); ?></span>
                                                        <?php else: ?> <span class="text-slate-300 font-bold">-</span> <?php endif; ?>
                                                    </td>

                                                    
                                                    <td class="col-waktu hidden-col px-4 py-4 text-center">
                                                         <span class="font-mono font-bold text-slate-600 bg-white border border-slate-100 px-2 py-1 rounded-lg text-xs shadow-sm"><?php echo e($scan['time_in'] ?? now()->format('H:i')); ?></span>
                                                    </td>

                                                    
                                                    <td class="col-kegiatan hidden-col px-4 py-4 text-center text-slate-600 font-bold text-xs">
                                                        <?php echo e($scan['ekskul_name'] ?? $scan['type_raw']); ?>

                                                    </td>

                                                    <td class="px-6 py-4 text-right rounded-r-xl">
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border shadow-sm
                                                            <?php echo e(Str::contains($scan['status'], 'Terlambat') ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100'); ?>">
                                                            <?php echo e($scan['status']); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                
                                
                                <div id="no-log-entry" class="<?php echo e(count($recentScans) > 0 ? 'hidden' : ''); ?> flex flex-col items-center justify-center py-20 text-center">
                                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-inner">
                                        <i class="ph-duotone ph-qr-code text-4xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 font-bold text-sm">Belum ada data scan hari ini.</p>
                                    <p class="text-[10px] text-slate-400 mt-1">Data akan muncul otomatis setelah scan berhasil.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            audioCtx: null
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
                makanPanel: document.getElementById('makan-stats-panel')
            };

            // 1. Clock & Auto Mode
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

            // 2. Mode Switching
            window.setMode = function(mode, isAuto = false) {
                state.mode = mode;
                state.manualOverride = !isAuto;
                
                dom.btnReset.classList.toggle('hidden', isAuto);
                dom.extraContainer.classList.toggle('hidden', mode !== 'Ekstrakurikuler');
                dom.makanPanel.classList.toggle('hidden', mode !== 'Makan');
                dom.makanPanel.classList.toggle('grid', mode === 'Makan');
                
                document.querySelectorAll('.scan-type-btn').forEach(btn => {
                    const isActive = btn.dataset.type === mode;
                    btn.classList.toggle('ring-2', isActive);
                    btn.classList.toggle('ring-indigo-500', isActive);
                    btn.classList.toggle('border-indigo-500', isActive);
                    
                    const dot = btn.querySelector('.indicator-dot');
                    if(dot) {
                        dot.className = `w-1.5 h-1.5 rounded-full indicator-dot transition-all ${isActive ? 'bg-indigo-500 scale-125' : 'border border-slate-300'}`;
                    }
                });

                const labels = { Harian: 'Absen Harian', Makan: 'Makan Siang', Dhuha: 'Sholat Dhuha', Dhuhur: 'Sholat Dhuhur', Ekstrakurikuler: 'Ekstrakurikuler' };
                dom.modeText.innerText = isAuto ? `Auto: ${labels[mode]}` : labels[mode];
                dom.scanStatus.innerHTML = mode === 'Ekstrakurikuler' 
                    ? `<i class="ph-bold ph-warning text-amber-400"></i> Pilih Kegiatan Dulu` 
                    : `<i class="ph-bold ph-qr-code text-indigo-300"></i> Siap Scan ${labels[mode]}`;
                
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
                    ? `<i class="ph-bold ph-check text-emerald-400"></i> Siap: ${text}` 
                    : `<i class="ph-bold ph-warning text-amber-400"></i> Pilih Kegiatan Dulu`;
            });

            // 3. Table Logic
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

            // 4. Scanner Logic
            const onScanSuccess = async (decodedText) => {
                if (state.isProcessing) return;
                if (state.processedQr.has(decodedText)) return; 

                if (state.mode === 'Ekstrakurikuler' && !state.extraId) {
                    playBeep('warning');
                    Swal.fire({ toast: true, position: 'top', icon: 'warning', title: 'Pilih Ekskul dulu!', showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-[2rem]' } });
                    return;
                }

                state.isProcessing = true;
                state.processedQr.add(decodedText);
                dom.scanStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-indigo-300"></i> Memproses...`;

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
                        body: JSON.stringify({ student_id: decodedText, type: finalType, extra_id: state.extraId })
                    });

                    const data = await res.json();

                    if (res.ok) {
                        handleSuccess(data, finalType);
                    } else {
                        handleError(data.message || 'Error Server');
                    }
                } catch (e) {
                    handleError('Koneksi Gagal');
                } finally {
                    setTimeout(() => {
                        state.isProcessing = false;
                        state.processedQr.delete(decodedText);
                        setMode(state.mode, !state.manualOverride); 
                    }, 2000);
                }
            };

            function handleSuccess(data, type) {
                const isLate = (data.scan?.status || '').includes('Terlambat');
                
                playBeep(type === 'Makan' ? 'makan' : (isLate ? 'warning' : 'success'));
                triggerOverlay(type === 'Makan' ? 'makan' : (isLate ? 'warning' : 'success'));
                
                dom.scanResult.innerHTML = `<span class="${isLate ? 'text-amber-600' : 'text-emerald-600'} flex items-center justify-center gap-2"><i class="ph-fill ph-check-circle"></i> ${data.message}</span>`;
                dom.scanResult.classList.remove('hidden', 'opacity-0', 'scale-95');
                dom.scanResult.className = `mt-4 p-4 rounded-2xl font-bold text-sm text-center transition-all duration-300 transform scale-100 border ${isLate ? 'bg-amber-50 border-amber-200' : 'bg-emerald-50 border-emerald-200'} shadow-sm`;
                
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
                dom.scanResult.className = `mt-4 p-4 rounded-2xl font-bold text-sm text-center transition-all duration-300 transform scale-100 border bg-rose-50 border-rose-200 shadow-sm`;
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
                row.className = 'new-row-entry border-b border-slate-50 last:border-0 hover:bg-white transition-colors group';
                row.dataset.typeRaw = scan.type_raw;

                row.innerHTML = `
                    <td class="px-6 py-4 rounded-l-xl">
                        <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">${scan.student_name}</div>
                        <div class="text-[10px] text-slate-400 font-mono font-bold">Unknown ID</div>
                    </td>
                    <td class="col-harian px-4 py-4 text-center">
                        ${scan.time_in ? `<span class="font-mono font-bold text-slate-600 bg-white border border-slate-100 px-2 py-1 rounded-lg text-xs shadow-sm">${scan.time_in}</span>` : '<span class="text-slate-300 font-bold">-</span>'}
                    </td>
                    <td class="col-harian px-4 py-4 text-center">-</td>
                    <td class="col-waktu hidden-col px-4 py-4 text-center">
                         <span class="font-mono font-bold text-slate-600 bg-white border border-slate-100 px-2 py-1 rounded-lg text-xs shadow-sm">${scan.time_in}</span>
                    </td>
                    <td class="col-kegiatan hidden-col px-4 py-4 text-center text-slate-600 font-bold text-xs">${scan.ekskul_name || '-'}</td>
                    <td class="px-6 py-4 text-right rounded-r-xl"><span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide shadow-sm">${scan.status}</span></td>
                `;

                dom.tableBody.prepend(row);
                filterLogTable(state.mode); 
            }

            const qrScanner = new Html5Qrcode("qr-reader");
            qrScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess)
                .catch(err => {
                    dom.scanStatus.innerHTML = `<span class="text-rose-300">Kamera Error: ${err}</span>`;
                });
            
            checkAutoMode(new Date());
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/scan/index.blade.php ENDPATH**/ ?>