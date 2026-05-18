<?php $__env->startSection('content'); ?>
<?php
    $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
    $scheduleJson = json_encode($safeSchedule);
    
    // Data Ekstrakurikuler untuk Modal/Dropdown
    $extracurriculars = isset($extracurriculars) ? $extracurriculars : [];
    $extraJson = json_encode($extracurriculars);
?>

<!-- LAYER START KIOSK (Untuk trigger Fullscreen & Audio) -->
<div id="start-overlay" class="fixed inset-0 z-[100] bg-slate-900 flex flex-col items-center justify-center cursor-pointer transition-opacity duration-500" onclick="startKiosk()">
    <div class="relative mb-6 group">
        <div class="absolute inset-0 bg-cyan-500/30 blur-3xl rounded-full animate-pulse"></div>
        <div class="w-32 h-32 bg-slate-800 rounded-full border-4 border-cyan-500 flex items-center justify-center relative z-10 group-hover:scale-105 transition-transform">
            <i class="ph-bold ph-power text-6xl text-cyan-400"></i>
        </div>
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-3">KLIK UNTUK MULAI</h1>
    <p class="text-slate-400 font-bold uppercase tracking-widest text-sm md:text-base bg-slate-800 px-6 py-2 rounded-full border border-slate-700">Otomatis Fullscreen & Kunci Scanner</p>
</div>

<div class="min-h-screen w-full bg-slate-900 relative overflow-x-hidden font-sans selection:bg-cyan-500 selection:text-white" x-data="kioskData()" @open-ekskul-modal.window="openExtraModal()">
    
    <!-- Background FX -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-cyan-400 to-indigo-500 shadow-[0_0_20px_rgba(56,189,248,0.5)]"></div>
        <div class="absolute -top-[20%] -left-[10%] w-[800px] h-[800px] bg-blue-600/20 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5"></div>
    </div>

    <!-- CONTAINER UTAMA -->
    <div class="flex flex-col lg:flex-row w-full min-h-screen p-4 md:p-8 gap-8 lg:gap-10 relative z-10">
        
        <!-- BAGIAN KIRI: SCANNER UTAMA -->
        <div class="flex-1 flex flex-col items-center justify-center w-full relative">
            
            <!-- Header (Logo & Jam) -->
            <div class="absolute top-0 left-0 w-full flex justify-between items-start pt-2">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-slate-800/50 rounded-2xl border border-slate-700/50 shadow-2xl backdrop-blur-sm">
                        <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-12 h-12 text-white fill-current drop-shadow-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 text-white fill-current drop-shadow-lg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-white tracking-tight uppercase">
                            Station Absensi
                        </h1>
                        <p class="text-[10px] text-slate-500 font-mono uppercase tracking-widest mt-1" id="manual-indicator">Auto Mode Active</p>
                    </div>
                </div>

                <div class="text-right">
                    <div class="px-6 py-2 rounded-full bg-slate-800/50 border border-slate-700/50 backdrop-blur-md shadow-lg inline-block">
                        <span id="kiosk-clock" class="text-2xl font-black text-slate-200 font-mono tracking-widest text-shadow-glow">00:00:00</span>
                    </div>
                    <button type="button" onclick="exitKiosk()" class="mt-2 text-[10px] text-slate-500 hover:text-rose-400 uppercase tracking-widest block w-full text-right transition-colors">Keluar Kiosk <i class="ph-bold ph-sign-out"></i></button>
                </div>
            </div>

            <!-- INDIKATOR MODE AKTIF -->
            <div class="mb-6 flex flex-col items-center gap-2 mt-20">
                <div id="active-mode-badge" class="px-8 py-3 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 font-black tracking-widest uppercase text-lg shadow-[0_0_15px_rgba(59,130,246,0.3)] backdrop-blur-md transition-all duration-500 flex items-center gap-3">
                    <i class="ph-fill ph-circle-notch animate-spin"></i> <span>Memuat...</span>
                </div>
            </div>

            <!-- BOX SCANNER (Style Cyber) -->
            <div id="status-box" class="w-full max-w-3xl aspect-[16/8] bg-slate-800/40 backdrop-blur-md rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 shadow-2xl mt-4">
                
                <!-- Sudut Cyber -->
                <div class="absolute -top-0.5 -left-0.5 w-12 h-12 border-t-4 border-l-4 border-cyan-400 rounded-tl-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>
                <div class="absolute -top-0.5 -right-0.5 w-12 h-12 border-t-4 border-r-4 border-cyan-400 rounded-tr-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>
                <div class="absolute -bottom-0.5 -left-0.5 w-12 h-12 border-b-4 border-l-4 border-cyan-400 rounded-bl-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>
                <div class="absolute -bottom-0.5 -right-0.5 w-12 h-12 border-b-4 border-r-4 border-cyan-400 rounded-br-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>

                <!-- Laser Animation -->
                <div id="scan-laser" class="absolute top-0 left-8 right-8 h-1.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-[0_0_20px_#22d3ee] z-20 animate-scan-y opacity-70"></div>

                <!-- State: Standby -->
                <div id="state-standby" class="flex flex-col items-center z-10 transition-transform duration-300 group-hover:scale-105 p-4 text-center">
                    <div class="relative mb-6">
                         <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full animate-pulse"></div>
                         <i class="ph-duotone ph-barcode text-8xl text-cyan-400 relative z-10 drop-shadow-[0_0_15px_rgba(34,211,238,0.5)]"></i>
                    </div>
                    <p class="text-4xl font-black text-white tracking-wide">SIAP SCAN</p>
                    <p id="ekskul-name-display" class="hidden text-purple-300 mt-2 font-bold text-xl animate-pulse"></p>
                    <p class="text-cyan-300/70 mt-3 font-bold text-sm tracking-widest uppercase">Gunakan Barcode Scanner</p>
                </div>

                <!-- State: Result (Hidden by default) -->
                <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full bg-slate-900 rounded-[2.5rem] flex-col items-center justify-center border border-slate-700 overflow-hidden p-4">
                    <!-- Content injected by JS -->
                </div>
            </div>

            <!-- MODE SELECTOR (Tombol Cepat - F1 s/d F6) -->
             <div class="mt-8 w-full max-w-3xl">
                <div class="grid grid-cols-6 gap-3">
                    <?php $__currentLoopData = [
                        ['label' => 'Masuk (F1)', 'type' => 'Masuk', 'icon' => 'sun-dim', 'color' => 'cyan'],
                        ['label' => 'Pulang (F2)', 'type' => 'Pulang', 'icon' => 'moon-stars', 'color' => 'purple'],
                        ['label' => 'Makan (F3)', 'type' => 'Makan', 'icon' => 'bowl-food', 'color' => 'orange'],
                        ['label' => 'Dhuha (F4)', 'type' => 'Dhuha', 'icon' => 'sun-horizon', 'color' => 'emerald'],
                        ['label' => 'Dhuhur (F5)', 'type' => 'Dhuhur', 'icon' => 'mosque', 'color' => 'emerald']
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" onclick="window.setMode('<?php echo e($btn['type']); ?>', true)" class="bg-slate-800/60 hover:bg-<?php echo e($btn['color']); ?>-900/40 border border-slate-700 hover:border-<?php echo e($btn['color']); ?>-500 text-slate-300 hover:text-<?php echo e($btn['color']); ?>-400 py-4 rounded-2xl backdrop-blur-sm transition-all flex flex-col items-center gap-2 group shadow-sm hover:shadow-<?php echo e($btn['color']); ?>-500/20 active:scale-95">
                        <i class="ph-bold ph-<?php echo e($btn['icon']); ?> text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider"><?php echo e($btn['label']); ?></span>
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <button type="button" @click="openExtraModal" class="bg-slate-800/60 hover:bg-pink-900/40 border border-slate-700 hover:border-pink-500 text-slate-300 hover:text-pink-400 py-4 rounded-2xl backdrop-blur-sm transition-all flex flex-col items-center gap-2 group shadow-sm hover:shadow-pink-500/20 active:scale-95">
                        <i class="ph-bold ph-basketball text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Ekskul (F6)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: LIST KEHADIRAN (LOG) -->
        <div class="w-full lg:w-[450px] h-[calc(100vh-4rem)] flex flex-col bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 shadow-2xl rounded-[2.5rem] overflow-hidden relative z-20 shrink-0 mt-4 lg:mt-0">
            
            <div class="p-8 bg-slate-900/50 border-b border-slate-700/50 flex justify-between items-center z-10">
                <div>
                    <h2 class="text-xl font-black text-white flex items-center gap-2">
                        <i class="ph-fill ph-clock-user text-blue-500"></i> Log Aktivitas
                    </h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Real-time Feed Kiosk</p>
                </div>
                <div class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar relative">
                <ul id="scan-log-list" class="space-y-4 pb-20">
                    <li id="empty-log" class="flex flex-col items-center justify-center py-24 opacity-30">
                        <i class="ph-duotone ph-fingerprint text-6xl text-slate-400 mb-4"></i>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-wide">Menunggu Scan...</p>
                    </li>
                </ul>
                <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-900/90 to-transparent pointer-events-none"></div>
            </div>
        </div>
    </div>

    <!-- Hidden Input Trap untuk Barcode Scanner Fisik -->
    <input type="text" id="scan-input" class="absolute opacity-0 -top-[9999px]" autocomplete="off" autofocus>

    <!-- Modal Ekskul AlpineJS -->
    <div x-show="showExtraModal" x-transition class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md" x-cloak>
        <div class="bg-slate-800 rounded-3xl border border-slate-700 p-8 w-full max-w-lg shadow-2xl" @click.away="closeModal()">
            <h3 class="text-2xl font-black text-white mb-6 flex items-center gap-3"><i class="ph-fill ph-trophy text-pink-500"></i> Pilih Kegiatan Ekskul</h3>
            <div class="grid grid-cols-2 gap-4 max-h-[50vh] overflow-y-auto custom-scrollbar pr-2">
                <?php $__empty_1 = true; $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <button type="button" @click="selectExtra('<?php echo e($ex->id); ?>', '<?php echo e($ex->name); ?>')" class="p-4 bg-slate-700/50 hover:bg-pink-900/30 border border-slate-600 hover:border-pink-500 rounded-xl text-left transition-all group active:scale-95">
                        <span class="font-bold text-slate-300 group-hover:text-pink-300 text-base block"><?php echo e($ex->name); ?></span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-2 text-center py-8">
                        <i class="ph-duotone ph-warning-circle text-5xl text-slate-500 mb-3"></i>
                        <p class="text-slate-400 text-base font-bold">Data ekstrakurikuler kosong.</p>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" @click="closeModal()" class="mt-6 w-full py-4 bg-slate-700 hover:bg-rose-600 text-white font-bold rounded-xl transition-colors uppercase tracking-widest text-sm">Batal / Tutup</button>
        </div>
    </div>

</div>

<script>
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

    function startKiosk() {
        const overlay = document.getElementById('start-overlay');
        overlay.style.opacity = '0';
        setTimeout(() => overlay.style.display = 'none', 500);

        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen().catch(err => console.log(err));
        }

        if (audioCtx.state === 'suspended') audioCtx.resume();
        window.isProcessing = false;
        document.getElementById('scan-input').focus();
    }

    function exitKiosk() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
        window.location.href = "<?php echo e(route('landing')); ?>"; 
    }

    function speakSapaan(message) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); 
            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = 'id-ID'; 
            utterance.rate = 1.0;     
            utterance.pitch = 1.1;    
            window.speechSynthesis.speak(utterance);
        }
    }

    function kioskData() {
        return {
            showExtraModal: false,
            openExtraModal() {
                this.showExtraModal = true;
                window.isProcessing = true; 
            },
            selectExtra(id, name) {
                window.selectedExtraId = id;
                window.selectedExtraName = name;
                window.setMode('Ekstrakurikuler', true);
                this.showExtraModal = false;
                
                const display = document.getElementById('ekskul-name-display');
                display.textContent = `Kegiatan: ${name}`;
                display.classList.remove('hidden');
                
                setTimeout(() => { 
                    window.isProcessing = false; 
                    document.getElementById('scan-input').focus(); 
                }, 300);
            },
            closeModal() {
                this.showExtraModal = false;
                setTimeout(() => { 
                    window.isProcessing = false; 
                    document.getElementById('scan-input').focus(); 
                }, 300);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const SCHEDULE_DATA = <?php echo $scheduleJson; ?>;
        
        let currentScanMode = 'Masuk';
        let manualOverride = false;
        window.selectedExtraId = null;
        window.selectedExtraName = null;
        window.isProcessing = true; 
        
        const clockEl = document.getElementById('kiosk-clock');
        const scanInput = document.getElementById('scan-input');
        const modeBadge = document.getElementById('active-mode-badge');
        const manualIndicator = document.getElementById('manual-indicator');
        const statusBox = document.getElementById('status-box');
        const stateResult = document.getElementById('state-result');
        const laser = document.getElementById('scan-laser');
        const corners = document.querySelectorAll('.corner-accent');
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');
        const ekskulDisplay = document.getElementById('ekskul-name-display');

        const processUrl = '<?php echo e(route("kiosk.process")); ?>';
        let csrfToken = '<?php echo e(csrf_token()); ?>'; // DIUBAH JADI LET AGAR BISA DI-UPDATE OTOMATIS

        const MODE_CONFIG = {
            'Masuk': { color: 'cyan', icon: 'ph-sun-dim', label: 'ABSEN MASUK' },
            'Pulang': { color: 'purple', icon: 'ph-moon-stars', label: 'ABSEN PULANG' },
            'Makan': { color: 'orange', icon: 'ph-bowl-food', label: 'AMBIL MAKAN' },
            'Dhuha': { color: 'emerald', icon: 'ph-sun-horizon', label: 'SHOLAT DHUHA' },
            'Dhuhur': { color: 'emerald', icon: 'ph-mosque', label: 'SHOLAT DHUHUR' },
            'Ekstrakurikuler': { color: 'pink', icon: 'ph-basketball', label: 'EKSKUL' }
        };

        const toMinutes = (str) => { if(!str) return 0; const [h,m] = str.split(':'); return parseInt(h)*60 + parseInt(m); };
        
        const MODE_TIMES = {
            MAKAN_START: toMinutes(SCHEDULE_DATA.makan_start || '10:00'), 
            MAKAN_END: toMinutes(SCHEDULE_DATA.makan_end || '10:30'),
            DHUHA_START: toMinutes(SCHEDULE_DATA.dhuha_start || '07:30'), 
            DHUHA_END: toMinutes(SCHEDULE_DATA.dhuha_end || '08:00'),
            DHUHUR_START: toMinutes(SCHEDULE_DATA.dhuhur_start || '11:45'), 
            DHUHUR_END: toMinutes(SCHEDULE_DATA.dhuhur_end || '13:30'),
            PULANG_START: toMinutes(SCHEDULE_DATA.start_out || '14:00')
        };

        function playBeep(type) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            
            if (type === 'success') { osc.type = 'sine'; osc.frequency.setValueAtTime(880, audioCtx.currentTime); } 
            else if (type === 'warning') { osc.type = 'triangle'; osc.frequency.setValueAtTime(440, audioCtx.currentTime); } 
            else if (type === 'makan') { osc.type = 'sine'; osc.frequency.setValueAtTime(600, audioCtx.currentTime); }
            else { osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, audioCtx.currentTime); } 
            
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
            osc.start(); osc.stop(audioCtx.currentTime + 0.5);
        }

        function autoSelectMode() {
            if (manualOverride) return;
            const now = new Date(); 
            const cur = now.getHours() * 60 + now.getMinutes();
            let newMode = 'Masuk';
            
            if (cur >= MODE_TIMES.PULANG_START) newMode = 'Pulang';
            else if (cur >= MODE_TIMES.MAKAN_START && cur < MODE_TIMES.MAKAN_END) newMode = 'Makan';
            else if (cur >= MODE_TIMES.DHUHUR_START && cur < MODE_TIMES.DHUHUR_END) newMode = 'Dhuhur';
            else if (cur >= MODE_TIMES.DHUHA_START && cur < MODE_TIMES.DHUHA_END) newMode = 'Dhuha';
            
            if(currentScanMode !== newMode) window.setMode(newMode);
        }

        window.setMode = function(mode, isManual = false) {
            if(mode !== 'Ekstrakurikuler') {
                ekskulDisplay.classList.add('hidden');
                window.selectedExtraId = null;
            }
            
            currentScanMode = mode;
            if(isManual) manualOverride = true;
            
            const config = MODE_CONFIG[mode] || MODE_CONFIG['Masuk'];
            const manualText = isManual ? 'MANUAL MODE' : 'AUTO MODE ACTIVE';
            
            modeBadge.innerHTML = `<i class="ph-fill ${config.icon} text-xl"></i> <span>${config.label}</span>`;
            modeBadge.className = `px-8 py-3 rounded-full bg-${config.color}-500/20 border border-${config.color}-400/30 text-${config.color}-200 font-black tracking-widest uppercase text-lg shadow-[0_0_15px_rgba(var(--color-${config.color}-500),0.3)] backdrop-blur-md transition-all duration-500 flex items-center gap-3`;
            
            document.querySelectorAll('.corner-accent').forEach(el => {
                el.className = `absolute w-12 h-12 border-${config.color}-400 rounded-none shadow-[0_0_15px_rgba(var(--color-${config.color}-500),0.5)] transition-colors duration-300 corner-accent ` + el.className.split(' ').filter(c => c.includes('top') || c.includes('bottom') || c.includes('left') || c.includes('right') || c.includes('border-t') || c.includes('border-b') || c.includes('border-l') || c.includes('border-r')).join(' ') + (el.className.includes('rounded-tl') ? ' rounded-tl-3xl' : '') + (el.className.includes('rounded-tr') ? ' rounded-tr-3xl' : '') + (el.className.includes('rounded-bl') ? ' rounded-bl-3xl' : '') + (el.className.includes('rounded-br') ? ' rounded-br-3xl' : '');
            });

            manualIndicator.textContent = manualText;
            if(isManual) manualIndicator.classList.add('text-yellow-400');
            else manualIndicator.classList.remove('text-yellow-400');
            
            focusInput();
        }

        function updateTime() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            // Refresh otomatis dibiarkan saat tengah malam saja untuk update tanggal/jadwal baru besoknya.
            if (now.getHours() === 0 && now.getMinutes() === 0 && now.getSeconds() === 0) window.location.reload();
        }
        setInterval(updateTime, 1000);
        setInterval(autoSelectMode, 30000); 
        updateTime();
        autoSelectMode();

        // --- FITUR ANTI EXPIRED (KEEP-ALIVE SESSION) ---
        // Dipercepat menjadi setiap 5 menit agar sesi backend tidak gampang mati
        setInterval(() => {
            fetch(window.location.href, { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).catch(err => console.log('Ping keep-alive session'));
        }, 5 * 60 * 1000); 

        function addToLog(name, type, message, time) {
            if(emptyLogMsg) emptyLogMsg.style.display = 'none';
            
            const li = document.createElement('li');
            const initial = name ? name.charAt(0).toUpperCase() : '?';
            
            let cardClass = "";
            let avatarHtml = "";

            if (type === 'success') cardClass = "bg-slate-800/50 border-slate-700";
            else if (type === 'warning') cardClass = "bg-slate-800/50 border-slate-700"; 
            else cardClass = "bg-rose-900/20 border-rose-500/20"; 

            let avatarColor = type === 'success' ? "bg-gradient-to-br from-emerald-500 to-green-500 shadow-emerald-500/20" : 
                             (type === 'warning' ? "bg-gradient-to-br from-amber-500 to-orange-500 shadow-amber-500/20" : 
                             "bg-rose-600 shadow-rose-600/20");
                             
            avatarHtml = `<div class="flex-shrink-0 w-12 h-12 rounded-xl ${avatarColor} flex items-center justify-center text-xl font-black text-white border border-white/10 shadow-lg">${initial}</div>`;

            li.className = `flex items-center gap-4 p-4 rounded-2xl border ${cardClass} shadow-md backdrop-blur-sm animate-fade-in-left transition-all`;
            li.innerHTML = `
                ${avatarHtml}
                <div class="flex-1 min-w-0">
                    <p class="text-white font-bold truncate text-base mb-1">${name}</p>
                    <div class="flex justify-between items-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider truncate mr-2">${message}</p>
                        <span class="text-[10px] font-mono font-bold text-cyan-400 bg-cyan-950/50 px-2 py-0.5 rounded border border-cyan-500/20 shrink-0">${time}</span>
                    </div>
                </div>
            `;
            
            logList.prepend(li);
            if (logList.children.length > 20) {
                logList.removeChild(logList.lastElementChild);
            }
        }

        function focusInput() { 
            if (!window.isProcessing) scanInput.focus(); 
        }
        document.addEventListener('click', (e) => { 
            if (!e.target.closest('button') && e.target.id !== 'start-overlay') {
                focusInput(); 
            }
        });
        scanInput.addEventListener('blur', () => setTimeout(focusInput, 50));

        document.addEventListener('keydown', (e) => {
            if(e.key === 'F1') { e.preventDefault(); window.setMode('Masuk', true); }
            if(e.key === 'F2') { e.preventDefault(); window.setMode('Pulang', true); }
            if(e.key === 'F3') { e.preventDefault(); window.setMode('Makan', true); }
            if(e.key === 'F4') { e.preventDefault(); window.setMode('Dhuha', true); }
            if(e.key === 'F5') { e.preventDefault(); window.setMode('Dhuhur', true); }
            if(e.key === 'F6') { e.preventDefault(); window.dispatchEvent(new Event('open-ekskul-modal')); }
            if(e.key === 'Escape') { e.preventDefault(); manualOverride = false; autoSelectMode(); }
        });

        scanInput.addEventListener('change', async function(e) {
            const scanData = e.target.value.trim();
            e.target.value = '';
            if (!scanData || window.isProcessing) return;
            // Panggil proses scan dengan isRetry = false di awal
            processScan(scanData, false);
        });

        // --- PENAMBAHAN PARAMETER IS_RETRY UNTUK AUTO-RECOVERY TOKEN ---
        async function processScan(data, isRetry = false) {
            window.isProcessing = true;
            scanInput.blur();
            
            laser.style.display = 'none'; 
            corners.forEach(c => c.classList.add('opacity-0'));
            
            stateResult.classList.remove('hidden'); 
            stateResult.classList.add('flex');
            
            const config = MODE_CONFIG[currentScanMode] || MODE_CONFIG['Masuk'];
            stateResult.innerHTML = `
                <div class="w-24 h-24 border-4 border-${config.color}-400 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-6 text-2xl text-${config.color}-200 font-bold animate-pulse tracking-widest uppercase">Absen ${currentScanMode}...</p>
            `;

            try {
                const body = { 
                    student_id: data, 
                    type: currentScanMode, 
                    extra_id: window.selectedExtraId,
                    lat: null, 
                    long: null
                };

                let response = await fetch(processUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });

                // --- FITUR AUTO RECOVERY TOKEN (TANPA RELOAD) ---
                // Jika error 419 (Token Mismatch) terjadi, jangan langsung gagal.
                // Kiosk akan mencuri token baru dari server, lalu mencoba absen lagi secara diam-diam.
                if (response.status === 419 && !isRetry) {
                    try {
                        const tokenRes = await fetch(window.location.href);
                        const text = await tokenRes.text();
                        const match = text.match(/name="csrf-token" content="(.*?)"/);
                        if (match && match[1]) {
                            csrfToken = match[1]; // Perbarui token di memori
                            return processScan(data, true); // Coba absen lagi secara diam-diam
                        }
                    } catch(e) { console.log('Gagal auto-recovery token'); }
                }

                // JIKA TER-LOGOUT (401) ATAU RECOVERY GAGAL
                if (response.status === 419 || response.status === 401) {
                    playBeep('error');
                    stateResult.innerHTML = `
                        <div class="bg-rose-500/20 p-4 rounded-full mb-4 border border-rose-500/50"><i class="ph-bold ph-warning-circle text-6xl text-rose-500"></i></div>
                        <h2 class="text-3xl font-black text-rose-500 text-center mb-2">AKSES TERPUTUS</h2>
                        <p class="text-white text-center mb-6">Sesi login Kiosk berakhir atau ter-logout.</p>
                        <button onclick="window.location.reload()" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg transition-colors">
                            <i class="ph-bold ph-arrows-clockwise"></i> Muat Ulang Kiosk
                        </button>
                    `;
                    // Dibiarkan berhenti di sini agar TIDAK keluar dari Fullscreen secara otomatis.
                    // Satpam harus klik tombol di atas jika sudah siap memuat ulang.
                    return; 
                }

                const result = await response.json();
                const isLate = String(result.message || '').toUpperCase().includes('TERLAMBAT');
                let shortName = (result.student_name || "Siswa").split(' ')[0];
                let displayTime = result.time || new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});

                if (response.ok) {
                    const statusType = (currentScanMode === 'Makan') ? 'makan' : (isLate ? 'warning' : 'success');
                    playBeep(statusType);
                    
                    if (isLate) speakSapaan(`${shortName}, Anda Terlambat.`);
                    else if (currentScanMode === 'Pulang') speakSapaan(`Hati-hati di jalan, ${shortName}.`);
                    else if (currentScanMode === 'Makan') speakSapaan(`Selamat makan, ${shortName}.`);
                    else speakSapaan(`Selamat datang, ${shortName}.`);
                    
                    let bgClass = isLate ? "bg-amber-500" : (currentScanMode === 'Makan' ? "bg-orange-600" : "bg-emerald-600");
                    let shadowClass = isLate ? "shadow-[0_0_80px_rgba(245,158,11,0.5)]" : (currentScanMode === 'Makan' ? "shadow-[0_0_80px_rgba(234,88,12,0.5)]" : "shadow-[0_0_80px_rgba(16,185,129,0.5)]");
                    let iconClass = isLate ? "ph-warning" : "ph-check";
                    
                    showResultUI(bgClass, shadowClass, iconClass, result.student_name, result.message);
                    addToLog(result.student_name, statusType, result.message, displayTime);

                } else {
                    playBeep('error');
                    speakSapaan(response.status === 404 ? 'Kartu tidak terdaftar.' : `Maaf, ${result.message}`);
                    
                    statusBox.className = "w-full max-w-3xl aspect-[16/8] bg-rose-600 rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(225,29,72,0.5)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden border-none mt-4";
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    stateResult.innerHTML = `
                         <div class="bg-white/20 p-4 rounded-full mb-4 backdrop-blur-md border border-white/20"><i class="ph-bold ph-x text-6xl text-white"></i></div>
                        <h2 class="text-4xl md:text-5xl font-black text-white text-center drop-shadow-lg mb-2">GAGAL</h2>
                        <p class="text-lg md:text-xl text-rose-100 bg-rose-800/30 px-6 py-2 rounded-full border border-rose-400/30 font-bold uppercase tracking-widest mt-2">${errorMsg}</p>
                    `;
                    addToLog(result.student_name || "Gagal Scan", 'error', errorMsg, displayTime);
                }

            } catch (error) {
                console.error(error);
                playBeep('error');
                statusBox.className = "w-full max-w-3xl aspect-[16/8] bg-slate-800 rounded-[2.5rem] border-4 border-rose-500 flex flex-col items-center justify-center relative overflow-hidden mt-4";
                stateResult.innerHTML = `<p class="text-rose-400 font-bold text-2xl uppercase animate-pulse">Koneksi Server Error</p>`;
            } finally {
                setTimeout(() => {
                    stateResult.classList.add('hidden'); 
                    stateResult.classList.remove('flex');
                    statusBox.className = "w-full max-w-3xl aspect-[16/8] bg-slate-800/40 backdrop-blur-md rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 shadow-2xl mt-4";
                    corners.forEach(c => c.classList.remove('opacity-0'));
                    laser.style.display = 'block'; 
                    window.isProcessing = false; 
                    focusInput();
                }, 2500);
            }
        }

        function showResultUI(bgClass, shadowClass, iconClass, name, message) {
            statusBox.className = `w-full max-w-3xl aspect-[16/8] ${bgClass} rounded-[2.5rem] flex flex-col items-center justify-center ${shadowClass} transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden border-none mt-4`;
            
            stateResult.innerHTML = `
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
                <div class="relative z-10 flex flex-col items-center animate-bounce-in p-4 text-center">
                    <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-md border border-white/20"><i class="ph-bold ${iconClass} text-6xl text-white"></i></div>
                    <h2 class="text-4xl md:text-5xl font-black text-white text-center leading-none tracking-tight drop-shadow-lg mb-4">${name || 'Siswa'}</h2>
                    <p class="text-lg md:text-xl text-white/90 font-bold bg-black/20 px-6 py-2 rounded-full border border-white/20 uppercase tracking-widest">${message}</p>
                </div>
            `;
        }
    });

    <!-- ============================================================== -->
    <!-- // SCRIPT TAMBAHAN: SISTEM OFFLINE QUEUE (ANTI WIFI MATI)      -->   
    <!-- ============================================================== -->

    document.addEventListener("DOMContentLoaded", function() {
    // 1. Inisialisasi Pengecekan Antrean (Interval Sync Setiap 5 Detik)
    setInterval(attemptSyncOfflineQueue, 5000);

    // 2. Fungsi Modifikasi Pengiriman Data (Bisa Anda sesuaikan dengan fungsi fetch bawaan Anda)
    // Gunakan fungsi ini untuk menggantikan `fetch('/api/kiosk/scan')` milik Anda yang lama.
    window.sendScanToServer = async function(qrData, scanType = 'Harian', extraId = null) {
        
        // Payload mencatat waktu akurat sekian detik yang lalu (Penting saat offline)
        const scanPayload = {
            student_id: qrData,
            type: scanType,
            extra_id: extraId,
            time: new Date().toISOString(), 
            _token: csrfToken
        };

        try {
            // Coba tembak ke server
            let response = await fetch('/kiosk/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(scanPayload)
            });

            if (!response.ok) {
                // Jika error 500 (Server Mati/Putus Database), kita lempar ke error network
                if(response.status >= 500) throw new Error("Server Error");
                
                // Jika error 400 (Ditolak Sistem Kiosk, Misal: Telat/Kepagian), lempar sebagai Validasi
                let data = await response.json();
                showErrorUI(data.message); // Panggil fungsi UI Error Anda
                return;
            }

            // SUKSES ONLINE
            let data = await response.json();
            showSuccessUI(data); // Panggil fungsi UI Sukses Anda

        } catch (error) {
            // [MODE OFFLINE AKTIF] JARINGAN MATI / SERVER DOWN
            console.warn("Jaringan Terputus! Beralih ke Mode Offline...");
            saveToOfflineQueue(scanPayload);
            
            // Tetap berikan feedback sukses di layar agar antrean siswa tidak menumpuk
            showSuccessUI({
                message: "TERSAVE OFFLINE: Menunggu Sinyal...",
                student_name: "Antrean " + qrData,
                note: "Sedang Sinkronisasi...",
            });
        }
    }

    // 3. Simpan ke Local Storage Browser
    function saveToOfflineQueue(payload) {
        let queue = JSON.parse(localStorage.getItem('kiosk_offline_queue')) || [];
        queue.push(payload);
        localStorage.setItem('kiosk_offline_queue', JSON.stringify(queue));
    }

    // 4. Background Sync (Menembak Endpoint Batch)
    async function attemptSyncOfflineQueue() {
        if (!navigator.onLine) return; // Jika WiFi laptop masih mati, skip.

        let queue = JSON.parse(localStorage.getItem('kiosk_offline_queue')) || [];
        if (queue.length === 0) return; // Antrean kosong

        try {
            let response = await fetch('/kiosk/sync-batch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ scans: queue })
            });

            if (response.ok) {
                console.log(`Berhasil sinkronisasi ${queue.length} antrean offline!`);
                localStorage.removeItem('kiosk_offline_queue'); // Bersihkan antrean!
                
                // Opsional: Bunyikan suara khusus saat sinkronisasi selesai
                // new Audio('/sounds/sync-success.mp3').play();
            }
        } catch (error) {
            console.log("Menunggu jaringan stabil untuk sinkronisasi antrean...");
        }
    }
});

</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(34, 211, 238, 0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(34, 211, 238, 0.5); }
    
    @keyframes scanY { 
        0% { top: 0%; opacity: 0; } 
        10% { opacity: 1; } 
        90% { opacity: 1; } 
        100% { top: 100%; opacity: 0; } 
    }
    .animate-scan-y { animation: scanY 3s ease-in-out infinite; }
    
    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); opacity: 1; }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .animate-bounce-in { animation: bounceIn 0.5s cubic-bezier(0.215, 0.610, 0.355, 1.000); }
    
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in-left { animation: fadeInLeft 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    .text-shadow-glow { text-shadow: 0 0 15px rgba(56,189,248,0.5); }
    
    [x-cloak] { display: none !important; }

    /* Agar saat Fullscreen header/sidebar bawaan layout utama tersembunyi */
    :fullscreen header, :fullscreen nav { display: none !important; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kiosk-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/kiosk/index.blade.php ENDPATH**/ ?>