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
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .animate-enter { animation: fadeUp 0.3s ease-out; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        #reader { width: 100%; border-radius: 1rem; overflow: hidden; }
        #reader video { object-fit: cover; border-radius: 1rem; }
        .updating-content { opacity: 0.5; pointer-events: none; transition: opacity 0.2s; }
        .focus-indicator { transition: all 0.3s; }
        .input-focused { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2); }
        
        /* New: Clock Style */
        .digital-clock { font-feature-settings: "tnum"; font-variant-numeric: tabular-nums; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-6 font-sans text-slate-800 relative min-h-screen bg-slate-50/50">
        
        
        <div id="offlineIndicator" class="fixed bottom-6 right-6 z-50 hidden animate-bounce">
            <div class="bg-rose-600 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 border-2 border-rose-400">
                <i class="ph-bold ph-wifi-slash text-xl"></i>
                <div>
                    <div class="font-bold text-sm">Koneksi Terputus</div>
                    <div class="text-[10px] opacity-90">Menunggu sambungan...</div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="relative rounded-[2.5rem] bg-slate-900 p-8 mb-8 text-white shadow-2xl shadow-slate-900/20 overflow-hidden border border-white/10 group">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600 rounded-full mix-blend-overlay filter blur-[120px] opacity-30 group-hover:opacity-40 transition-opacity duration-1000 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="ph-duotone ph-shield-check text-4xl text-blue-400"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold tracking-tight mb-1">Pos Guru Piket</h2>
                            <p class="text-blue-200 text-sm font-medium">Monitoring izin keluar masuk siswa real-time.</p>
                        </div>
                    </div>

                    
                    <div class="flex items-center gap-4">
                        <div class="hidden lg:block text-right mr-4">
                            <div id="clockTime" class="text-3xl font-black digital-clock leading-none">00:00:00</div>
                            <div id="clockDate" class="text-xs text-blue-300 font-medium uppercase tracking-widest mt-1">...</div>
                        </div>
                        <div class="h-10 w-px bg-white/20 hidden lg:block"></div>
                        <div class="text-center md:text-right bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-white/10">
                            <div class="text-[10px] font-bold text-blue-300 uppercase tracking-widest mb-0.5">Petugas Jaga</div>
                            <div class="font-bold text-base text-white"><?php echo e(Auth::user()->name ?? 'Administrator'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
                
                
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <i class="ph-duotone ph-qr-code text-8xl text-indigo-900"></i>
                        </div>

                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <h3 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                                <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                                Scan Kartu / Input
                            </h3>
                            
                            
                            <div class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100" title="Auto Focus RFID">
                                <label class="flex items-center cursor-pointer relative">
                                    <input type="checkbox" id="kioskModeToggle" class="sr-only peer" checked>
                                    <div class="w-7 h-4 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-indigo-600"></div>
                                    <span class="ml-2 text-[10px] font-bold text-slate-500 uppercase">RFID Mode</span>
                                </label>
                            </div>
                        </div>

                        
                        <div class="space-y-4 relative z-10">
                            
                            <div id="cameraContainer" class="hidden mb-4 relative bg-slate-900 rounded-2xl overflow-hidden shadow-inner border-4 border-slate-900">
                                <div id="reader" class="w-full"></div>
                                <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none">
                                    <span class="bg-black/60 text-white text-[10px] px-3 py-1 rounded-full backdrop-blur-md border border-white/20">Arahkan QR Code</span>
                                </div>
                            </div>

                            <div class="relative group">
                                <input type="text" id="scannerInput" 
                                    class="w-full pl-12 pr-12 py-5 rounded-2xl border-2 border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-mono text-lg font-bold text-slate-700 transition-all placeholder:text-slate-400 focus-indicator" 
                                    placeholder="Tempel Kartu atau Ketik NIS..." autofocus autocomplete="off">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <i class="ph-bold ph-scan text-2xl"></i>
                                </div>
                                
                                
                                <div id="inputSpinner" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-indigo-500">
                                    <i class="ph-bold ph-spinner animate-spin text-xl"></i>
                                </div>
                                
                                <button id="btnSearch" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white shadow-sm border border-slate-200 text-indigo-600 p-2 rounded-xl hover:bg-indigo-50 transition cursor-pointer">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button onclick="toggleCamera()" id="btnCamera" class="col-span-1 text-xs font-bold px-4 py-3 bg-white hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-xl transition flex items-center justify-center gap-2 border border-slate-200 shadow-sm">
                                    <i class="ph-bold ph-camera text-lg"></i> <span id="cameraText">Buka Kamera</span>
                                </button>
                                <button onclick="openModalManual()" class="col-span-1 text-xs font-bold px-4 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl transition flex items-center justify-center gap-2 border border-indigo-100 shadow-sm">
                                    <i class="ph-bold ph-keyboard text-lg"></i> Input Manual
                                </button>
                            </div>
                        </div>

                        <!-- Feedback Status -->
                        <div id="scanFeedback" class="hidden mt-4 p-3 rounded-xl text-center text-sm font-bold animate-pulse transition-all"></div>
                        
                        
                        <div class="mt-4 flex justify-between items-center">
                             <p class="text-[10px] text-slate-400">Pastikan kursor aktif di kolom input untuk RFID.</p>
                             <span id="focusStatus" class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 hidden uppercase tracking-wider">
                                ● Reader Ready
                            </span>
                        </div>
                    </div>

                    <!-- Riwayat Singkat -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100">
                        <h3 class="font-bold text-slate-700 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <i class="ph-duotone ph-clock-counter-clockwise text-indigo-600 text-lg"></i> Baru Saja Kembali
                        </h3>
                        <div id="historyContainer" class="space-y-3 max-h-[250px] overflow-y-auto custom-scrollbar pr-1">
                            <?php $__empty_1 = true; $__currentLoopData = $todayHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors animate-enter group">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-white text-emerald-600 flex items-center justify-center font-bold text-xs shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">
                                        <i class="ph-fill ph-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700 line-clamp-1"><?php echo e($history->student->name); ?></div>
                                        <div class="text-[10px] text-slate-500"><?php echo e($history->reason_category); ?> • <span class="font-bold text-slate-600"><?php echo e($history->duration_minutes); ?> m</span></div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-mono text-slate-400 bg-white px-2 py-1 rounded-lg border border-slate-100">
                                        <?php echo e($history->time_in->format('H:i')); ?>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-8 text-slate-400 text-sm border-2 border-dashed border-slate-100 rounded-xl">
                                Belum ada riwayat hari ini.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden min-h-[600px] flex flex-col relative h-full">
                        <div class="p-6 border-b border-slate-100 bg-white/80 backdrop-blur-md flex justify-between items-center sticky top-0 z-20">
                            <div>
                                <h3 class="font-bold text-slate-800 text-xl flex items-center gap-2">
                                    <i class="ph-duotone ph-timer text-orange-500 text-2xl"></i> Sedang Di Luar
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">Siswa yang belum kembali ke kelas.</p>
                            </div>
                            
                            
                            <div id="activeCountBadge" class="flex flex-col items-end">
                                <span class="text-3xl font-black text-slate-800 leading-none"><?php echo e($activePermits->count()); ?></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa Aktif</span>
                            </div>
                        </div>
                        
                        <div id="activePermitsContainer" class="flex-1 overflow-y-auto custom-scrollbar p-5 bg-slate-50/50">
                            <?php if($activePermits->count() > 0): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php $__currentLoopData = $activePermits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="permit-card group relative bg-white p-5 rounded-2xl border transition-all animate-enter flex flex-col justify-between
                                        <?php echo e($permit->is_overdue ? 'border-rose-300 shadow-lg shadow-rose-100' : 'border-slate-200 hover:border-indigo-300 hover:shadow-md'); ?>">
                                        
                                        <?php if($permit->is_overdue): ?>
                                            <div class="absolute -top-3 -right-3 bg-rose-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md animate-pulse z-10">
                                                <i class="ph-bold ph-warning"></i> TELAT
                                            </div>
                                        <?php endif; ?>

                                        <div class="flex items-start gap-4 mb-4">
                                            <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center text-lg font-bold shadow-sm
                                                <?php echo e($permit->is_overdue ? 'bg-rose-50 text-rose-600' : 'bg-indigo-50 text-indigo-600'); ?>">
                                                <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 leading-snug line-clamp-2"><?php echo e($permit->student->name); ?></h4>
                                                <p class="text-xs text-slate-500 font-medium mt-1">
                                                    <?php echo e($permit->student->schoolClass->name ?? 'Kelas -'); ?>

                                                </p>
                                                <p class="text-[10px] font-mono text-slate-400 mt-0.5"><?php echo e($permit->student->student_id); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase">Keperluan</span>
                                                    <span class="text-[10px] font-bold text-slate-600 px-2 py-0.5 bg-white rounded border border-slate-200"><?php echo e($permit->reason_category); ?></span>
                                                </div>
                                                <?php if($permit->notes): ?>
                                                <p class="text-xs text-slate-500 italic line-clamp-2 leading-relaxed">"<?php echo e($permit->notes); ?>"</p>
                                                <?php else: ?>
                                                <p class="text-xs text-slate-300 italic">- Tidak ada catatan -</p>
                                                <?php endif; ?>
                                            </div>

                                            <div class="flex items-end justify-between pt-2 border-t border-slate-50">
                                                <div class="text-xs text-slate-400">
                                                    Keluar: <span class="font-mono font-bold text-slate-600"><?php echo e($permit->time_out->format('H:i')); ?></span>
                                                </div>
                                                <div class="live-timer text-right" data-start="<?php echo e($permit->time_out); ?>">
                                                    <span class="text-2xl font-black font-mono leading-none <?php echo e($permit->is_overdue ? 'text-rose-600' : 'text-slate-700'); ?>">
                                                        <span class="timer-number"><?php echo e($permit->minutes_elapsed); ?></span><span class="text-sm font-bold opacity-50 ml-0.5">m</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                
                                <div class="flex flex-col items-center justify-center h-full text-slate-400 py-20">
                                    <div class="w-40 h-40 bg-slate-50 rounded-full flex items-center justify-center mb-6 shadow-inner border border-slate-100">
                                        <i class="ph-duotone ph-student text-7xl text-slate-300"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-600">Semua Siswa di Kelas</h4>
                                    <p class="text-sm max-w-xs text-center mt-2 opacity-70">Tidak ada siswa yang sedang izin keluar saat ini. Kelas kondusif.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="permitModal" class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl p-6 md:p-8 animate-enter relative">
            <button type="button" onclick="closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition cursor-pointer z-10 bg-slate-100 hover:bg-rose-50 p-2 rounded-full">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl rotate-3 flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm border border-indigo-100">
                    <i class="ph-duotone ph-door-open"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Izin Keluar Kelas</h3>
                <div class="mt-4 bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p id="modalStudentName" class="text-indigo-600 font-black text-lg leading-tight">Nama Siswa</p>
                    <p id="modalStudentClass" class="text-xs text-slate-500 font-mono mt-1 font-bold">Kelas Siswa</p>
                </div>
            </div>

            <form id="permitForm" onsubmit="event.preventDefault(); submitPermitManual();">
                <input type="hidden" id="modalStudentId" name="student_id">
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <?php $__currentLoopData = ['Toilet', 'UKS', 'Barang Tertinggal', 'Panggilan Guru', 'Dispensasi', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="cursor-pointer relative group">
                        <input type="radio" name="reason_category" value="<?php echo e($reason); ?>" class="peer sr-only">
                        <div class="p-3 rounded-xl border-2 border-slate-100 text-center text-xs font-bold text-slate-600 
                                    group-hover:bg-slate-50 group-hover:border-slate-300
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 
                                    transition-all duration-200 shadow-sm">
                            <?php echo e($reason); ?>

                        </div>
                        <div class="absolute -top-2 -right-2 bg-indigo-600 text-white rounded-full p-0.5 opacity-0 peer-checked:opacity-100 transition-opacity scale-0 peer-checked:scale-100 transform duration-200 shadow-md">
                            <i class="ph-bold ph-check text-xs"></i>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Catatan Tambahan</label>
                    <input type="text" name="notes" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm py-3 px-4 bg-slate-50 focus:bg-white transition-colors" placeholder="Keterangan mendetail...">
                </div>

                <button type="submit" id="btnSubmitPermit" class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>Berikan Izin</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    
    <div id="manualSearchModal" class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-6 animate-enter">
            <h3 class="font-bold text-lg mb-4">Input Siswa Manual</h3>
            <p class="text-sm text-slate-500 mb-4">Masukkan NIS atau Nama siswa jika kartu tertinggal.</p>
            <input type="text" id="manualInputBox" class="w-full rounded-xl border-slate-300 mb-4 py-3" placeholder="Ketik Nama / NIS...">
            <div class="flex justify-end gap-2">
                <button onclick="document.getElementById('manualSearchModal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-slate-500 font-bold hover:bg-slate-100">Batal</button>
                <button onclick="submitManualSearch()" class="px-6 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">Cari</button>
            </div>
        </div>
    </div>

    <script>
        const scannerInput = document.getElementById('scannerInput');
        const modal = document.getElementById('permitModal');
        const scanFeedback = document.getElementById('scanFeedback');
        const kioskModeToggle = document.getElementById('kioskModeToggle');
        const focusStatus = document.getElementById('focusStatus');
        const csrfToken = '<?php echo e(csrf_token()); ?>';
        let isProcessing = false; 
        
        // --- JAM DIGITAL ---
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
            const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('clockTime').innerText = timeString;
            document.getElementById('clockDate').innerText = dateString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- INDIKATOR OFFLINE ---
        window.addEventListener('offline', () => document.getElementById('offlineIndicator').classList.remove('hidden'));
        window.addEventListener('online', () => {
            document.getElementById('offlineIndicator').classList.add('hidden');
            playAudio('notification');
            Swal.fire({ icon: 'success', title: 'Terhubung Kembali', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        });

        // --- AUDIO ---
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playTone(freq, type, duration) {
            const osc = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            osc.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            osc.type = type;
            osc.frequency.value = freq;
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        }
        function playAudio(type) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            if (type === 'success') { playTone(800, 'sine', 0.1); setTimeout(() => playTone(1200, 'sine', 0.3), 100); }
            else if (type === 'error') { playTone(150, 'sawtooth', 0.3); }
            else if (type === 'notification') { playTone(500, 'triangle', 0.1); }
        }

        // --- CAMERA LOGIC ---
        let html5QrCode;
        let isCameraRunning = false;
        function toggleCamera() {
            const container = document.getElementById('cameraContainer');
            const btnText = document.getElementById('cameraText');
            if (isCameraRunning) {
                html5QrCode.stop().then(() => {
                    container.classList.add('hidden');
                    btnText.textContent = "Buka Kamera";
                    isCameraRunning = false;
                    html5QrCode = null;
                }).catch(err => console.error(err));
            } else {
                container.classList.remove('hidden');
                btnText.textContent = "Tutup Kamera";
                html5QrCode = new Html5Qrcode("reader");
                html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onCameraSuccess)
                .then(() => { isCameraRunning = true; })
                .catch(err => { Swal.fire("Error Kamera", "Izin kamera diperlukan.", "error"); container.classList.add('hidden'); });
            }
        }
        const onCameraSuccess = (decodedText) => {
            if(isProcessing) return;
            if(isCameraRunning) {
                html5QrCode.pause(); 
                handleScan(decodedText).then(() => { setTimeout(() => { if(isCameraRunning) html5QrCode.resume(); }, 2000); });
            }
        };

        // --- INPUT MANUAL LOGIC ---
        function openModalManual() {
            document.getElementById('manualSearchModal').classList.remove('hidden');
            document.getElementById('manualInputBox').focus();
        }
        function submitManualSearch() {
            const val = document.getElementById('manualInputBox').value;
            if(val) {
                handleScan(val);
                document.getElementById('manualSearchModal').classList.add('hidden');
                document.getElementById('manualInputBox').value = '';
            }
        }

        // --- SCAN & FOCUS LOGIC ---
        setInterval(updateRealtimeTimers, 30000); 
        setInterval(refreshDashboardData, 60000);

        scannerInput.addEventListener('focus', () => { scannerInput.classList.add('input-focused'); focusStatus.classList.remove('hidden'); });
        scannerInput.addEventListener('blur', () => {
            scannerInput.classList.remove('input-focused');
            focusStatus.classList.add('hidden');
            if (kioskModeToggle.checked && modal.classList.contains('hidden') && document.getElementById('manualSearchModal').classList.contains('hidden')) {
                setTimeout(() => { if(document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "TEXTAREA") scannerInput.focus(); }, 200); 
            }
        });
        document.addEventListener('click', (e) => {
            if (kioskModeToggle.checked) {
                const isInteractive = e.target.closest('input, button, a, #permitModal, #manualSearchModal, label');
                if (!isInteractive && modal.classList.contains('hidden') && document.getElementById('manualSearchModal').classList.contains('hidden')) scannerInput.focus();
            }
        });
        scannerInput.addEventListener('keypress', function (e) { if (e.key === 'Enter') { e.preventDefault(); handleScan(this.value); } });
        document.getElementById('btnSearch').addEventListener('click', () => handleScan(scannerInput.value));

        async function handleScan(code) {
            if(!code || isProcessing) return;
            setProcessingState(true);
            showFeedback('Memproses data...', 'info');

            try {
                const res = await fetch('<?php echo e(route("permit.scan")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ identifier: code })
                });
                const data = await res.json();
                if(!res.ok) throw new Error(data.message || 'Data tidak ditemukan');

                if(data.mode === 'CHECK_IN') {
                    playAudio('success');
                    showFeedback(`✅ ${data.data.student.name} KEMBALI`, 'success');
                    await refreshDashboardData(); 
                    Swal.fire({ icon: 'success', title: 'Selamat Datang Kembali', text: `${data.data.student.name} (${data.data.duration} menit)`, timer: 2000, showConfirmButton: false, backdrop: `rgba(0,0,0,0.4)` });
                    scannerInput.value = '';
                } else {
                    playAudio('notification');
                    showFeedback('Silakan pilih alasan...', 'info');
                    openModal(data.data.student);
                }
            } catch (err) {
                playAudio('error');
                showFeedback(err.message, 'error');
                scannerInput.value = ''; 
                scannerInput.focus();
            } finally {
                setProcessingState(false);
            }
        }

        async function submitPermitManual() {
            const form = document.getElementById('permitForm');
            const formData = new FormData(form);
            const reason = formData.get('reason_category');
            if (!reason) { Swal.fire({ icon: 'warning', title: 'Pilih Alasan!', timer: 2000 }); return; }

            const btn = document.getElementById('btnSubmitPermit');
            btn.disabled = true; btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

            try {
                const payload = Object.fromEntries(formData.entries());
                const res = await fetch('<?php echo e(route("permit.store")); ?>', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload)
                });
                const data = await res.json();
                if(!res.ok) throw new Error(data.message);

                closeModal(); playAudio('success'); scannerInput.value = '';
                await refreshDashboardData();
                Swal.fire({ icon: 'success', title: 'Izin Tercatat', text: `${data.data.student.name} - ${data.data.reason}`, timer: 1500, showConfirmButton: false });
            } catch (err) {
                playAudio('error'); Swal.fire('Gagal', err.message, 'error');
            } finally {
                btn.disabled = false; btn.innerHTML = '<span>Berikan Izin</span> <i class="ph-bold ph-arrow-right"></i>';
                setTimeout(() => scannerInput.focus(), 100);
            }
        }

        function setProcessingState(loading) {
            isProcessing = loading;
            const spinner = document.getElementById('inputSpinner');
            if(loading) { scannerInput.disabled = true; spinner.classList.remove('hidden'); } 
            else { scannerInput.disabled = false; scannerInput.focus(); spinner.classList.add('hidden'); }
        }
        function showFeedback(msg, type) {
            scanFeedback.className = 'mt-4 p-3 rounded-xl text-center text-sm font-bold animate-pulse transition-all ' + 
                (type === 'success' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                (type === 'error' ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-blue-100 text-blue-700 border-blue-200'));
            scanFeedback.innerHTML = msg; scanFeedback.classList.remove('hidden');
            setTimeout(() => { scanFeedback.classList.add('hidden'); }, 3000);
        }
        function openModal(student) {
            document.getElementById('modalStudentName').textContent = student.name;
            document.getElementById('modalStudentClass').textContent = student.school_class?.name || 'Kelas Tidak Diketahui';
            document.getElementById('modalStudentId').value = student.id;
            document.querySelectorAll('input[name="reason_category"]').forEach(el => el.checked = false);
            document.querySelector('input[name="notes"]').value = '';
            modal.classList.remove('hidden');
        }
        function closeModal() { modal.classList.add('hidden'); document.getElementById('permitForm').reset(); scannerInput.focus(); }

        async function refreshDashboardData() {
            const container1 = document.getElementById('activePermitsContainer');
            const container2 = document.getElementById('historyContainer');
            const badge = document.getElementById('activeCountBadge');
            if(navigator.onLine === false) return; 

            container1.classList.add('updating-content');
            try {
                const response = await fetch(window.location.href);
                const text = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                if(container1) container1.innerHTML = doc.getElementById('activePermitsContainer').innerHTML;
                if(container2) container2.innerHTML = doc.getElementById('historyContainer').innerHTML;
                if(badge) badge.innerHTML = doc.getElementById('activeCountBadge').innerHTML;
            } catch (error) { console.error('Refresh failed', error); } 
            finally { container1.classList.remove('updating-content'); }
        }

        function updateRealtimeTimers() {
            document.querySelectorAll('.live-timer').forEach(el => {
                const diffMins = Math.floor((new Date().getTime() - new Date(el.dataset.start).getTime()) / 60000);
                const numberDisplay = el.querySelector('.timer-number');
                if(numberDisplay) numberDisplay.textContent = diffMins;
                if(diffMins > 15) { 
                    const card = el.closest('.permit-card');
                    if(card) {
                        card.classList.add('border-rose-300', 'shadow-lg', 'shadow-rose-100');
                        card.classList.remove('border-slate-200');
                    }
                    if(numberDisplay) numberDisplay.closest('span').classList.add('text-rose-600');
                }
            });
        }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/permit/index.blade.php ENDPATH**/ ?>