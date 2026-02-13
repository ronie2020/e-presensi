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
        /* Animasi Scanner */
        .scanner-container { position: relative; overflow: hidden; border-radius: 1.5rem; }
        .scanner-overlay {
            position: absolute; inset: 0; pointer-events: none;
            border: 4px solid transparent; border-radius: 1.5rem;
            transition: all 0.3s ease; z-index: 20;
        }
        
        .scan-success-effect { box-shadow: inset 0 0 60px rgba(34, 197, 94, 0.6); border-color: #22c55e; }
        .scan-warning-effect { box-shadow: inset 0 0 60px rgba(245, 158, 11, 0.6); border-color: #f59e0b; }
        .scan-error-effect { box-shadow: inset 0 0 60px rgba(239, 68, 68, 0.6); border-color: #ef4444; }
        .scan-makan-effect { box-shadow: inset 0 0 60px rgba(249, 115, 22, 0.6); border-color: #f97316; }

        .scanner-line {
            position: absolute; width: 100%; height: 3px;
            background: #3b82f6; box-shadow: 0 0 10px #3b82f6;
            top: 0; animation: scanMove 2.5s infinite linear;
            z-index: 10;
        }
        @keyframes scanMove { 0% { top: 0; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 100%; opacity: 0; } }
        
        /* Utility */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; } 
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .hidden-col { display: none !important; }
        .hidden-row { display: none !important; }
        
        @keyframes highlightRow { 0% { background-color: #dbeafe; } 100% { background-color: transparent; } }
        .new-row-entry { animation: highlightRow 3s ease-out; }
        
        /* Layout Improvements */
        .scan-type-btn.active .indicator-dot { transform: scale(1.2); }
    </style>
    <?php $__env->stopPush(); ?>

    <?php
        $safeSchedule = $scheduleConfig ?? [];
        $scheduleJson = json_encode($safeSchedule);
        $totalTarget = $statsConfig['total_target'] ?? 0;
        $currentTaken = $statsConfig['current_taken'] ?? 0;
    ?>

    
    <div class="py-4 md:py-6 font-sans text-slate-800 select-none"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-8">
                
                
                <div class="lg:col-span-12 order-1">
                    <div class="relative rounded-3xl bg-slate-900 overflow-hidden shadow-xl border border-slate-800">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-900 to-slate-900 opacity-90"></div>
                        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center p-6 gap-6">
                            <div class="text-center md:text-left">
                                <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-2">
                                  Aktifitas Siswa  <span class="text-blue-400">Scanner</span>
                                </h2>
                                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                                    <?php if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false)): ?>
                                        <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-200 border border-rose-500/30 text-xs font-bold uppercase tracking-wider">Libur: <?php echo e($scheduleConfig['description']); ?></span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-200 border border-blue-500/30 text-xs font-bold uppercase tracking-wider">
                                            <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            
                            <div class="bg-white/10 backdrop-blur-md border border-white/10 px-6 py-3 rounded-2xl flex items-center gap-4 shadow-lg">
                                <div class="p-3 bg-blue-600 rounded-xl text-white shadow-lg shadow-blue-600/30"><i class="ph-bold ph-clock text-2xl"></i></div>
                                <div>
                                    <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-0.5">Waktu Server</p>
                                    <div id="clock" class="text-3xl font-black text-white font-mono leading-none tracking-widest">00:00:00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-5 flex flex-col gap-5 order-2 lg:order-2">
                    
                    
                    <div class="bg-white rounded-3xl p-4 shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                        <div class="flex justify-between items-center mb-4 px-2">
                            <h3 class="font-bold text-slate-700 flex items-center gap-2">
                                <div class="w-2 h-6 bg-blue-500 rounded-full"></div> Kamera Aktif
                            </h3>
                            <div id="mode-badge" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-slate-100 text-slate-500 border border-slate-200 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span id="mode-text">Standby</span>
                            </div>
                        </div>

                        
                        <div class="scanner-container relative bg-slate-950 aspect-[4/3] w-full group">
                            <div id="qr-reader" class="w-full h-full object-cover"></div>
                            
                            
                            <div id="scanner-overlay-el" class="scanner-overlay">
                                <div class="scanner-line"></div>
                                
                                <div class="absolute top-4 left-4 w-8 h-8 border-t-4 border-l-4 border-white/50 rounded-tl-xl"></div>
                                <div class="absolute top-4 right-4 w-8 h-8 border-t-4 border-r-4 border-white/50 rounded-tr-xl"></div>
                                <div class="absolute bottom-4 left-4 w-8 h-8 border-b-4 border-l-4 border-white/50 rounded-bl-xl"></div>
                                <div class="absolute bottom-4 right-4 w-8 h-8 border-b-4 border-r-4 border-white/50 rounded-br-xl"></div>
                            </div>

                            
                            <div class="absolute bottom-6 inset-x-0 flex justify-center z-30 pointer-events-none">
                                <div id="scan-status" class="bg-black/60 backdrop-blur-md text-white text-xs py-2 px-6 rounded-full font-bold border border-white/10 shadow-lg flex items-center gap-2 transition-all">
                                    <i class="ph-bold ph-circle-notch animate-spin text-blue-400"></i> Memuat Kamera...
                                </div>
                            </div>
                        </div>

                        
                        <div id="scan-result" class="mt-4 p-4 rounded-2xl font-bold text-sm text-center hidden transition-all duration-300 transform scale-95 opacity-0 border border-transparent"></div>
                        
                        <button id="btn-reset-auto" class="hidden w-full mt-3 py-3 rounded-xl border-2 border-dashed border-blue-300 text-blue-600 font-bold text-sm hover:bg-blue-50 transition-all flex items-center justify-center gap-2" onclick="resetAutoMode()">
                            <i class="ph-bold ph-arrows-clockwise"></i> Kembali ke Mode Otomatis
                        </button>
                    </div>

                    
                    <div id="makan-stats-panel" class="hidden grid-cols-2 gap-4 animate-fade-in-up">
                        <div class="bg-orange-500 p-5 rounded-3xl text-white shadow-lg shadow-orange-500/20 relative overflow-hidden">
                            <i class="ph-fill ph-check-circle absolute -right-2 -bottom-2 text-6xl text-white/20"></i>
                            <p class="text-[10px] font-bold text-orange-100 uppercase tracking-widest mb-1">Sudah Ambil</p>
                            <h3 class="text-4xl font-black tracking-tight" id="stat-taken"><?php echo e($currentTaken); ?></h3>
                        </div>
                        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                            <i class="ph-duotone ph-users absolute -right-2 -bottom-2 text-6xl text-slate-100"></i>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Belum Ambil</p>
                            <h3 class="text-4xl font-black text-slate-800 tracking-tight" id="stat-remaining"><?php echo e($totalTarget - $currentTaken); ?></h3>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php $__currentLoopData = [
                            ['id'=>'harian', 'label'=>'Absen Harian', 'sub'=>'Masuk/Pulang', 'icon'=>'calendar-check', 'color'=>'blue', 'type'=>'Harian'],
                            ['id'=>'makan', 'label'=>'Makan Siang', 'sub'=>'Scan Gizi', 'icon'=>'bowl-food', 'color'=>'orange', 'type'=>'Makan'],
                            ['id'=>'dhuha', 'label'=>'Sholat Dhuha', 'sub'=>'Ibadah Pagi', 'icon'=>'sun-horizon', 'color'=>'emerald', 'type'=>'Dhuha'],
                            ['id'=>'dhuhur', 'label'=>'Sholat Dhuhur', 'sub'=>'Ibadah Siang', 'icon'=>'moon-stars', 'color'=>'amber', 'type'=>'Dhuhur'],
                            ['id'=>'ekskul', 'label'=>'Ekstrakurikuler', 'sub'=>'Kegiatan Sore', 'icon'=>'basketball', 'color'=>'purple', 'type'=>'Ekstrakurikuler']
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button id="btn-<?php echo e($mode['id']); ?>" data-type="<?php echo e($mode['type']); ?>" class="scan-type-btn bg-white p-3 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-<?php echo e($mode['color']); ?>-200 transition-all text-left group">
                            <div class="w-10 h-10 rounded-xl bg-<?php echo e($mode['color']); ?>-50 text-<?php echo e($mode['color']); ?>-600 flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="ph-bold ph-<?php echo e($mode['icon']); ?>"></i>
                            </div>
                            <h3 class="font-bold text-slate-700 text-xs md:text-sm"><?php echo e($mode['label']); ?></h3>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-[10px] text-slate-400"><?php echo e($mode['sub']); ?></p>
                                <div class="w-2 h-2 rounded-full border border-slate-200 indicator-dot transition-colors"></div>
                            </div>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div id="extra-selector-container" class="hidden bg-white p-4 rounded-3xl border border-slate-200 shadow-sm animate-fade-in-down">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-2 px-1">Pilih Kegiatan Ekskul</label>
                        <select id="extra-activity-select" class="w-full rounded-xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 h-12 text-sm bg-slate-50">
                            <option value="">-- Pilih Ekstrakurikuler --</option>
                            <?php if(isset($extracurriculars)): ?>
                                <?php $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ekskul->id); ?>"><?php echo e($ekskul->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                
                <div class="lg:col-span-7 order-3 lg:order-3">
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col h-[600px] lg:h-full lg:min-h-[600px] overflow-hidden">
                        
                        
                        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                    <i class="ph-duotone ph-list-dashes text-blue-600"></i> Log Aktivitas
                                </h3>
                                <p class="text-xs text-slate-400 font-medium">Monitoring kehadiran realtime</p>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full">
                                <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span></span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Live</span>
                            </div>
                        </div>
                        
                        
                        <div class="flex-1 overflow-hidden relative">
                            <div class="absolute inset-0 overflow-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-white sticky top-0 z-10 shadow-sm text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-6 py-4">Siswa</th>
                                            <th class="col-harian px-4 py-4 text-center">Masuk</th>
                                            <th class="col-harian px-4 py-4 text-center">Pulang</th>
                                            <th class="col-waktu hidden-col px-4 py-4 text-center">Waktu Scan</th>
                                            <th class="col-kegiatan hidden-col px-4 py-4 text-center">Kegiatan</th>
                                            <th class="px-6 py-4 text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scan-log" class="text-sm divide-y divide-slate-50">
                                        <?php if(isset($recentScans)): ?>
                                            <?php $__currentLoopData = $recentScans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <tr class="log-entry group hover:bg-slate-50 transition-colors"
                                                    data-type-raw="<?php echo e($scan['type_raw']); ?>">
                                                    
                                                    <td class="px-6 py-4">
                                                        <div class="font-bold text-slate-800"><?php echo e($scan['student_name']); ?></div>
                                                        <div class="text-[10px] text-slate-400 font-mono"><?php echo e($scan['student_id']); ?></div>
                                                    </td>
                                                    
                                                    
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        <?php if($scan['time_in']): ?> <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded text-xs"><?php echo e($scan['time_in']); ?></span>
                                                        <?php else: ?> <span class="text-slate-300">-</span> <?php endif; ?>
                                                    </td>
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        <?php if($scan['time_out']): ?> <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded text-xs"><?php echo e($scan['time_out']); ?></span>
                                                        <?php else: ?> <span class="text-slate-300">-</span> <?php endif; ?>
                                                    </td>

                                                    
                                                    <td class="col-waktu hidden-col px-4 py-4 text-center">
                                                         <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded text-xs"><?php echo e($scan['time_in'] ?? now()->format('H:i')); ?></span>
                                                    </td>

                                                    
                                                    <td class="col-kegiatan hidden-col px-4 py-4 text-center text-slate-600 font-medium text-xs">
                                                        <?php echo e($scan['ekskul_name'] ?? $scan['type_raw']); ?>

                                                    </td>

                                                    <td class="px-6 py-4 text-right">
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide 
                                                            <?php echo e(Str::contains($scan['status'], 'Terlambat') ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'); ?>">
                                                            <?php echo e($scan['status']); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                
                                
                                <div id="no-log-entry" class="<?php echo e(count($recentScans) > 0 ? 'hidden' : ''); ?> flex flex-col items-center justify-center py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4"><i class="ph-duotone ph-qr-code text-4xl text-slate-300"></i></div>
                                    <p class="text-slate-400 font-medium">Belum ada data scan hari ini.</p>
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

        // Audio System (Lebih Robust)
        function initAudio() {
            if (!state.audioCtx) {
                state.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (state.audioCtx.state === 'suspended') {
                state.audioCtx.resume();
            }
        }

        // Trigger initAudio saat user klik di mana saja pertama kali
        document.body.addEventListener('click', initAudio, { once: true });
        document.body.addEventListener('touchstart', initAudio, { once: true });

        function playBeep(type = 'success') {
            if (!state.audioCtx) return;
            
            const osc = state.audioCtx.createOscillator();
            const gain = state.audioCtx.createGain();
            osc.connect(gain);
            gain.connect(state.audioCtx.destination);
            
            osc.type = type === 'error' ? 'sawtooth' : 'sine';
            
            // Frequency map
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

        // Time Helpers
        const toMinutes = (s) => { const [h, m] = s.split(':'); return h * 60 + +m; };
        
        // --- CORE LOGIC ---
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
                
                // UI Updates
                dom.btnReset.classList.toggle('hidden', isAuto);
                dom.extraContainer.classList.toggle('hidden', mode !== 'Ekstrakurikuler');
                dom.makanPanel.classList.toggle('hidden', mode !== 'Makan');
                dom.makanPanel.classList.toggle('grid', mode === 'Makan');
                
                // Active Button State
                document.querySelectorAll('.scan-type-btn').forEach(btn => {
                    const isActive = btn.dataset.type === mode;
                    btn.classList.toggle('ring-2', isActive);
                    btn.classList.toggle('ring-blue-500', isActive);
                    btn.querySelector('.indicator-dot').className = `w-2 h-2 rounded-full indicator-dot transition-colors ${isActive ? 'bg-blue-500' : 'border border-slate-200'}`;
                });

                // Badge & Status Text
                const labels = { Harian: 'Absen Harian', Makan: 'Makan Siang', Dhuha: 'Sholat Dhuha', Dhuhur: 'Sholat Dhuhur', Ekstrakurikuler: 'Ekstrakurikuler' };
                dom.modeText.innerText = isAuto ? `Auto: ${labels[mode]}` : labels[mode];
                dom.scanStatus.innerHTML = mode === 'Ekstrakurikuler' 
                    ? `<i class="ph-bold ph-warning text-amber-400"></i> Pilih Kegiatan Dulu` 
                    : `<i class="ph-bold ph-qr-code"></i> Siap Scan ${labels[mode]}`;
                
                filterLogTable(mode);
            };

            // Event Listeners for Buttons
            document.querySelectorAll('.scan-type-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    initAudio(); // Ensure audio context ready
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
                    ? `<i class="ph-bold ph-check text-green-400"></i> Siap: ${text}` 
                    : `<i class="ph-bold ph-warning text-amber-400"></i> Pilih Kegiatan Dulu`;
            });

            // 3. Table Logic
            function filterLogTable(mode) {
                const rows = dom.tableBody.querySelectorAll('tr');
                const colsHarian = document.querySelectorAll('.col-harian');
                const colsWaktu = document.querySelectorAll('.col-waktu');
                const colsKegiatan = document.querySelectorAll('.col-kegiatan');

                // Column Visibility
                const isHarian = mode === 'Harian';
                const isExtra = mode === 'Ekstrakurikuler';

                colsHarian.forEach(el => el.classList.toggle('hidden-col', !isHarian));
                colsWaktu.forEach(el => el.classList.toggle('hidden-col', isHarian));
                colsKegiatan.forEach(el => el.classList.toggle('hidden-col', !isExtra));

                // Row Filtering
                let count = 0;
                rows.forEach(row => {
                    const rowType = row.dataset.typeRaw; // "Harian", "Meal", "Keagamaan", "Extracurricular"
                    let match = false;

                    if (mode === 'Harian') match = ['Harian', 'Masuk', 'Pulang'].includes(rowType);
                    else if (mode === 'Makan') match = ['Meal', 'Makan'].includes(rowType);
                    else if (mode === 'Dhuha' || mode === 'Dhuhur') match = rowType === 'Keagamaan'; // Bisa diperjelas jika ada data activity di tr
                    else if (mode === 'Ekstrakurikuler') match = rowType === 'Extracurricular';

                    row.classList.toggle('hidden-row', !match);
                    if(match) count++;
                });
                
                document.getElementById('no-log-entry').classList.toggle('hidden', count > 0);
            }

            // 4. Scanner Logic
            const onScanSuccess = async (decodedText) => {
                if (state.isProcessing) return;
                if (state.processedQr.has(decodedText)) return; // Debounce client side

                // Validation Extras
                if (state.mode === 'Ekstrakurikuler' && !state.extraId) {
                    playBeep('warning');
                    Swal.fire({ toast: true, position: 'top', icon: 'warning', title: 'Pilih Ekskul dulu!', showConfirmButton: false, timer: 1500 });
                    return;
                }

                state.isProcessing = true;
                state.processedQr.add(decodedText);
                dom.scanStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin"></i> Memproses...`;

                try {
                    // Determine Harian Sub-type (Masuk/Pulang)
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
                    // Cooldown 2 detik sebelum bisa scan lagi
                    setTimeout(() => {
                        state.isProcessing = false;
                        state.processedQr.delete(decodedText);
                        setMode(state.mode, !state.manualOverride); // Reset text status
                    }, 2000);
                }
            };

            function handleSuccess(data, type) {
                const isLate = (data.scan?.status || '').includes('Terlambat');
                const flavor = type === 'Makan' ? 'makan' : (isLate ? 'warning' : 'success');
                
                playBeep(type === 'Makan' ? 'makan' : (isLate ? 'warning' : 'success'));
                triggerOverlay(flavor);
                
                // Show floating result
                dom.scanResult.innerHTML = `<span class="${isLate ? 'text-amber-600' : 'text-emerald-600'}">${data.message}</span>`;
                dom.scanResult.classList.remove('hidden', 'opacity-0', 'scale-95');
                
                setTimeout(() => dom.scanResult.classList.add('opacity-0', 'scale-95'), 3000);

                // Update Stats if Makan
                if (data.stats) {
                    dom.statTaken.innerText = data.stats.taken;
                    dom.statRemaining.innerText = <?php echo e($totalTarget); ?> - data.stats.taken;
                }

                // Add to Table
                if (data.scan) addTableRow(data.scan);
            }

            function handleError(msg) {
                playBeep('error');
                triggerOverlay('error');
                dom.scanResult.innerHTML = `<span class="text-rose-600">${msg}</span>`;
                dom.scanResult.classList.remove('hidden', 'opacity-0', 'scale-95');
                setTimeout(() => dom.scanResult.classList.add('opacity-0', 'scale-95'), 3000);
            }

            function triggerOverlay(type) {
                dom.overlay.className = `scanner-overlay scan-${type}-effect`;
                setTimeout(() => dom.overlay.className = 'scanner-overlay', 500);
            }

            function addTableRow(scan) {
                // Remove empty state
                document.getElementById('no-log-entry').classList.add('hidden');
                
                const row = document.createElement('tr');
                row.className = 'new-row-entry border-b border-slate-50';
                row.dataset.typeRaw = scan.type_raw;

                // Simple template (simplified for brevity)
                row.innerHTML = `
                    <td class="px-6 py-4 font-bold text-slate-800">${scan.student_name}</td>
                    <td class="col-harian px-4 py-4 text-center">${scan.time_in || '-'}</td>
                    <td class="col-harian px-4 py-4 text-center">-</td>
                    <td class="col-waktu hidden-col px-4 py-4 text-center">${scan.time_in}</td>
                    <td class="col-kegiatan hidden-col px-4 py-4 text-center">${scan.ekskul_name || '-'}</td>
                    <td class="px-6 py-4 text-right"><span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold">${scan.status}</span></td>
                `;

                dom.tableBody.prepend(row);
                filterLogTable(state.mode); // Re-apply filter to show/hide columns correctly
            }

            // Init Scanner
            const qrScanner = new Html5Qrcode("qr-reader");
            qrScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess)
                .catch(err => {
                    dom.scanStatus.innerHTML = `<span class="text-rose-400">Kamera Error: ${err}</span>`;
                });
            
            // Initial call
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