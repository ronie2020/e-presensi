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
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .animate-enter { animation: fadeUp 0.3s ease-out; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Style untuk Kamera */
        #reader { width: 100%; border-radius: 1rem; overflow: hidden; }
        #reader video { object-fit: cover; border-radius: 1rem; }

        /* Transisi Smooth untuk update data tanpa reload */
        .updating-content { opacity: 0.5; pointer-events: none; transition: opacity 0.2s; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-6 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="relative rounded-[2rem] bg-gradient-to-r from-indigo-900 to-slate-900 p-6 mb-8 text-white shadow-xl overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight mb-1 flex items-center gap-3">
                            <i class="ph-duotone ph-shield-check text-indigo-400"></i>
                            Pos Guru Piket
                        </h2>
                        <p class="text-indigo-200 text-sm">Monitoring perizinan siswa keluar kelas real-time.</p>
                    </div>
                    <div class="text-center md:text-right bg-white/5 md:bg-transparent p-3 md:p-0 rounded-xl w-full md:w-auto">
                        <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Petugas Jaga</div>
                        <div class="font-bold text-lg"><?php echo e(Auth::user()->name ?? 'Administrator'); ?></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-700 flex items-center gap-2">
                                <i class="ph-bold ph-qr-code text-indigo-600"></i> Scan Kartu
                            </h3>
                            
                            
                            <div class="flex items-center gap-2">
                                <label class="flex items-center cursor-pointer relative">
                                    <input type="checkbox" id="kioskModeToggle" class="sr-only peer" checked>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                    <span class="ml-2 text-[10px] font-bold text-slate-400 uppercase">Auto Focus</span>
                                </label>
                            </div>
                        </div>

                        
                        <div class="mb-4">
                            <button onclick="toggleCamera()" id="btnCamera" class="w-full text-xs font-bold px-3 py-3 bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-xl transition flex items-center justify-center gap-2 border border-slate-200">
                                <i class="ph-bold ph-camera text-lg"></i> <span id="cameraText">Buka Kamera QR</span>
                            </button>
                        </div>

                        
                        <div id="cameraContainer" class="hidden mb-4 relative bg-slate-900 rounded-2xl overflow-hidden shadow-inner border-4 border-slate-900">
                            <div id="reader" class="w-full"></div>
                            <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none">
                                <span class="bg-black/60 text-white text-[10px] px-3 py-1 rounded-full backdrop-blur-md border border-white/20">Arahkan QR Code ke kamera</span>
                            </div>
                        </div>
                        
                        
                        <div class="relative group">
                            <input type="text" id="scannerInput" 
                                class="w-full pl-12 pr-12 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-mono text-lg font-bold text-slate-700 transition-all placeholder:text-slate-300" 
                                placeholder="Scan Kartu / Ketik NIS..." autofocus autocomplete="off">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <i class="ph-bold ph-scan text-xl"></i>
                            </div>
                            
                            
                            <div id="inputSpinner" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-indigo-500">
                                <i class="ph-bold ph-spinner animate-spin text-xl"></i>
                            </div>
                            
                            
                            <button id="btnSearch" class="absolute right-3 top-1/2 -translate-y-1/2 bg-indigo-100 text-indigo-700 p-2 rounded-lg hover:bg-indigo-200 transition cursor-pointer">
                                <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 ml-1 flex items-center gap-1">
                            <i class="ph-fill ph-info"></i> Pastikan kursor aktif di kolom ini.
                        </p>
                        
                        <!-- Feedback Status -->
                        <div id="scanFeedback" class="hidden mt-3 p-3 rounded-xl text-center text-sm font-bold animate-pulse transition-all"></div>
                    </div>

                    <!-- Riwayat Singkat (Target Update AJAX: #historyContainer) -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100 h-fit">
                        <h3 class="font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <i class="ph-duotone ph-clock-counter-clockwise text-indigo-600"></i> Baru Saja Kembali
                        </h3>
                        <div id="historyContainer" class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-1">
                            <?php $__empty_1 = true; $__currentLoopData = $todayHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                        <i class="ph-bold ph-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700 line-clamp-1"><?php echo e($history->student->name); ?></div>
                                        <div class="text-[10px] text-slate-500"><?php echo e($history->reason_category); ?> • <span class="font-bold text-slate-600"><?php echo e($history->duration_minutes); ?> m</span></div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-mono text-slate-400">
                                        <?php echo e(\Carbon\Carbon::parse($history->time_in)->format('H:i')); ?>

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
                    <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden min-h-[600px] flex flex-col relative">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center sticky top-0 z-10 backdrop-blur-sm">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                    <i class="ph-duotone ph-timer text-orange-500 text-xl"></i> Sedang Di Luar
                                </h3>
                                <p class="text-xs text-slate-500">Timer berjalan otomatis.</p>
                            </div>
                            
                            
                            <span id="activeCountBadge" class="bg-orange-100 text-orange-600 py-1 px-3 rounded-full text-xs font-bold shadow-sm border border-orange-200">
                                <?php echo e($activePermits->count()); ?> Siswa
                            </span>
                        </div>
                        
                        <div id="activePermitsContainer" class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3 bg-slate-50/30">
                            <?php $__empty_1 = true; $__currentLoopData = $activePermits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="permit-card group relative bg-white p-4 rounded-2xl border-2 <?php echo e($permit->is_overdue ? 'border-rose-100 bg-rose-50/30' : 'border-slate-100'); ?> hover:shadow-md transition-all animate-enter">
                                <div class="flex justify-between items-start">
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 rounded-xl <?php echo e($permit->is_overdue ? 'bg-rose-100 text-rose-600' : 'bg-indigo-50 text-indigo-600'); ?> flex items-center justify-center text-xl font-bold">
                                            <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-base leading-tight"><?php echo e($permit->student->name); ?></h4>
                                            <p class="text-xs text-slate-500 font-medium mb-1 mt-0.5">
                                                <?php echo e($permit->student->schoolClass->name ?? 'Kelas -'); ?> • <?php echo e($permit->student->student_id); ?>

                                            </p>
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                                <?php echo e($permit->reason_category); ?>

                                            </div>
                                            <?php if($permit->notes): ?>
                                                <span class="text-[10px] text-slate-400 italic ml-2 block sm:inline mt-1 sm:mt-0">"<?php echo e(Str::limit($permit->notes, 30)); ?>"</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="text-right live-timer" data-start="<?php echo e($permit->time_out); ?>">
                                        <div class="text-xs text-slate-400 font-medium mb-1">Durasi</div>
                                        <div class="text-2xl font-black font-mono <?php echo e($permit->is_overdue ? 'text-rose-600 animate-pulse' : 'text-slate-700'); ?>">
                                            <span class="timer-number"><?php echo e($permit->minutes_elapsed); ?></span><span class="text-sm text-slate-400 font-normal">m</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-1">
                                            Keluar: <?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="flex flex-col items-center justify-center h-64 text-slate-400">
                                <i class="ph-duotone ph-student text-6xl mb-4 opacity-30"></i>
                                <p class="font-medium">Semua siswa ada di kelas.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="permitModal" class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl p-6 md:p-8 animate-enter relative transform scale-100 transition-transform duration-300">
            <button type="button" onclick="closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition cursor-pointer z-10 bg-slate-100 hover:bg-rose-50 p-2 rounded-full">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
            
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm border border-indigo-100">
                    <i class="ph-duotone ph-door-open"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Izin Keluar Kelas</h3>
                <div class="mt-2 bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <p id="modalStudentName" class="text-indigo-600 font-bold text-lg leading-tight">Nama Siswa</p>
                    <p id="modalStudentClass" class="text-xs text-slate-500 font-mono mt-1">Kelas Siswa</p>
                </div>
            </div>

            <form id="permitForm" onsubmit="event.preventDefault(); submitPermitManual();">
                <input type="hidden" id="modalStudentId" name="student_id">
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <?php $__currentLoopData = ['Toilet', 'UKS', 'Barang Tertinggal', 'Panggilan Guru', 'Dispensasi', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="cursor-pointer relative group">
                        <input type="radio" name="reason_category" value="<?php echo e($reason); ?>" class="peer sr-only">
                        <div class="p-3 rounded-xl border-2 border-slate-100 text-center text-sm font-bold text-slate-600 
                                    group-hover:bg-slate-50 group-hover:border-slate-300
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 
                                    transition-all duration-200 shadow-sm">
                            <?php echo e($reason); ?>

                        </div>
                        <div class="absolute top-2 right-2 text-indigo-500 opacity-0 peer-checked:opacity-100 transition-opacity scale-0 peer-checked:scale-100 transform duration-200">
                            <i class="ph-fill ph-check-circle"></i>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Catatan Tambahan (Opsional)</label>
                    <input type="text" name="notes" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm py-3" placeholder="Contoh: Sakit perut, dipanggil Bu Siti...">
                </div>

                <button type="submit" id="btnSubmitPermit" class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>Izinkan Keluar</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    
    <script>
        const scannerInput = document.getElementById('scannerInput');
        const modal = document.getElementById('permitModal');
        const scanFeedback = document.getElementById('scanFeedback');
        const kioskModeToggle = document.getElementById('kioskModeToggle');
        const csrfToken = '<?php echo e(csrf_token()); ?>';
        let isProcessing = false; // Flag untuk mencegah double submit
        
        // --- 0. WEB AUDIO API (Suara tanpa file) ---
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
            
            if (type === 'success') {
                // Suara "Ding!" (High Pitch Sine)
                playTone(800, 'sine', 0.1);
                setTimeout(() => playTone(1200, 'sine', 0.3), 100);
            } else if (type === 'error') {
                // Suara "Tet!" (Low Pitch Sawtooth)
                playTone(150, 'sawtooth', 0.3);
            } else if (type === 'notification') {
                // Suara "Pop"
                playTone(500, 'triangle', 0.1);
            }
        }

        // --- 1. LOGIKA SCANNER KAMERA ---
        let html5QrCode;
        let isCameraRunning = false;
        
        function toggleCamera() {
            const container = document.getElementById('cameraContainer');
            const btnText = document.getElementById('cameraText');
            
            if (isCameraRunning) {
                html5QrCode.stop().then(() => {
                    container.classList.add('hidden');
                    btnText.textContent = "Buka Kamera QR";
                    isCameraRunning = false;
                    html5QrCode = null;
                }).catch(err => console.error(err));
            } else {
                container.classList.remove('hidden');
                btnText.textContent = "Tutup Kamera";
                
                html5QrCode = new Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
                
                html5QrCode.start({ facingMode: "environment" }, config, onCameraSuccess)
                .then(() => { isCameraRunning = true; })
                .catch(err => {
                    Swal.fire("Error Kamera", "Pastikan izin kamera aktif.", "error");
                    container.classList.add('hidden');
                });
            }
        }
        
        const onCameraSuccess = (decodedText) => {
            if(isProcessing) return; // Cegah scan saat sedang loading
            
            if(isCameraRunning) {
                html5QrCode.pause(); // Pause kamera
                handleScan(decodedText).then(() => {
                    setTimeout(() => { if(isCameraRunning) html5QrCode.resume(); }, 2000);
                });
            }
        };

        // --- 2. LOGIKA UTAMA & FOCUS HANDLING ---
        
        // Timer Interval untuk Update Menit Real-time
        setInterval(updateRealtimeTimers, 60000); // Jalan setiap 1 menit

        document.addEventListener('click', (e) => {
            // Hanya auto focus jika Kiosk Mode ON dan bukan sedang mengetik di input lain
            if (kioskModeToggle.checked) {
                const isInteractive = e.target.closest('input, button, a, #permitModal');
                if (!isInteractive && modal.classList.contains('hidden')) {
                    scannerInput.focus();
                }
            }
        });

        // Event Listener untuk Scanner Tembak (biasanya diakhiri Enter)
        scannerInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleScan(this.value);
            }
        });

        document.getElementById('btnSearch').addEventListener('click', () => {
            handleScan(scannerInput.value);
        });

        // --- 3. CORE LOGIC (SCAN & SUBMIT) ---

        async function handleScan(code) {
            if(!code || isProcessing) return;
            
            setProcessingState(true);
            showFeedback('Memproses...', 'info');

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
                    
                    // Update tampilan tanpa reload
                    await refreshDashboardData(); 
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Selamat Datang Kembali',
                        text: `${data.data.student.name} (${data.data.duration} menit)`,
                        timer: 2000,
                        showConfirmButton: false,
                        backdrop: `rgba(0,0,0,0.4)`
                    });
                    scannerInput.value = '';
                } else {
                    // Mode CHECK_OUT (Buka Modal)
                    playAudio('notification');
                    showFeedback('Silakan pilih alasan...', 'info');
                    openModal(data.data.student);
                }

            } catch (err) {
                playAudio('error');
                showFeedback(err.message, 'error');
                scannerInput.value = ''; // Clear input on error
                scannerInput.focus();
            } finally {
                setProcessingState(false);
            }
        }

        async function submitPermitManual() {
            const form = document.getElementById('permitForm');
            const formData = new FormData(form);
            const reason = formData.get('reason_category');
            
            if (!reason) {
                Swal.fire({ icon: 'warning', title: 'Pilih Alasan!', text: 'Wajib memilih alasan izin.', timer: 2000 });
                return;
            }

            const btn = document.getElementById('btnSubmitPermit');
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

            try {
                const payload = Object.fromEntries(formData.entries());
                const res = await fetch('<?php echo e(route("permit.store")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if(!res.ok) throw new Error(data.message);

                closeModal();
                playAudio('success');
                scannerInput.value = '';
                
                // Update tampilan tanpa reload
                await refreshDashboardData();

                Swal.fire({
                    icon: 'success',
                    title: 'Izin Tercatat',
                    text: `${data.data.student.name} - ${data.data.reason}`,
                    timer: 1500,
                    showConfirmButton: false
                });

            } catch (err) {
                playAudio('error');
                Swal.fire('Gagal', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>Izinkan Keluar</span> <i class="ph-bold ph-arrow-right"></i>';
            }
        }

        // --- 4. HELPER FUNCTIONS ---

        function setProcessingState(loading) {
            isProcessing = loading;
            const spinner = document.getElementById('inputSpinner');
            const btn = document.getElementById('btnSearch');
            
            if(loading) {
                scannerInput.disabled = true;
                spinner.classList.remove('hidden');
                btn.classList.add('hidden');
            } else {
                scannerInput.disabled = false;
                scannerInput.focus();
                spinner.classList.add('hidden');
                btn.classList.remove('hidden');
            }
        }

        function showFeedback(msg, type) {
            scanFeedback.className = 'mt-3 p-3 rounded-xl text-center text-sm font-bold animate-pulse transition-all';
            if(type === 'success') scanFeedback.classList.add('bg-emerald-100', 'text-emerald-700', 'border', 'border-emerald-200');
            else if(type === 'error') scanFeedback.classList.add('bg-rose-100', 'text-rose-700', 'border', 'border-rose-200');
            else scanFeedback.classList.add('bg-blue-100', 'text-blue-700', 'border', 'border-blue-200');
            
            scanFeedback.innerHTML = msg;
            scanFeedback.classList.remove('hidden');
            
            // Auto hide setelah 3 detik
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

        function closeModal() {
            modal.classList.add('hidden');
            document.getElementById('permitForm').reset();
            scannerInput.focus();
        }

        // --- 5. HOT SWAP / AJAX RELOAD LOGIC ---
        // Fungsi ini mengambil HTML halaman saat ini di background, dan menukar isinya.
        // Ini mensimulasikan "SPA" tanpa mengubah struktur backend Laravel.
        async function refreshDashboardData() {
            const container1 = document.getElementById('activePermitsContainer');
            const container2 = document.getElementById('historyContainer');
            const badge = document.getElementById('activeCountBadge');

            // Efek visual loading
            container1.classList.add('updating-content');
            container2.classList.add('updating-content');

            try {
                // Fetch halaman saat ini (GET request biasa)
                const response = await fetch(window.location.href);
                const text = await response.text();
                
                // Parse HTML string menjadi DOM Document
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');

                // Swap isi container dengan yang baru
                if(container1 && doc.getElementById('activePermitsContainer')) {
                    container1.innerHTML = doc.getElementById('activePermitsContainer').innerHTML;
                }
                
                if(container2 && doc.getElementById('historyContainer')) {
                    container2.innerHTML = doc.getElementById('historyContainer').innerHTML;
                }
                
                // Update badge count
                if(badge && doc.getElementById('activeCountBadge')) {
                    badge.innerHTML = doc.getElementById('activeCountBadge').innerHTML;
                }

                // Re-apply event listeners or logic if needed
                
            } catch (error) {
                console.error('Gagal refresh data:', error);
            } finally {
                container1.classList.remove('updating-content');
                container2.classList.remove('updating-content');
            }
        }

        // --- 6. REALTIME TIMER LOGIC ---
        function updateRealtimeTimers() {
            const timerElements = document.querySelectorAll('.live-timer');
            
            timerElements.forEach(el => {
                const startTime = new Date(el.dataset.start).getTime();
                const now = new Date().getTime();
                const diffMs = now - startTime;
                const diffMins = Math.floor(diffMs / 60000);
                
                // Update Angka
                const numberDisplay = el.querySelector('.timer-number');
                if(numberDisplay) numberDisplay.textContent = diffMins;

                // Update Style jika Overdue (> 10 menit misal)
                if(diffMins > 10) { 
                    el.closest('.permit-card').classList.add('border-rose-200', 'bg-rose-50');
                    if(numberDisplay) numberDisplay.classList.add('text-rose-600');
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/permit/index.blade.php ENDPATH**/ ?>