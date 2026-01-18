<?php $__env->startSection('content'); ?>
<?php
    $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
    $scheduleJson = json_encode($safeSchedule);
?>


<div class="min-h-screen w-full bg-slate-900 relative overflow-x-hidden font-sans selection:bg-cyan-500 selection:text-white">
    
    <!-- Background FX (Fixed agar tidak ikut scroll) -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-cyan-400 to-indigo-500 shadow-[0_0_20px_rgba(56,189,248,0.5)]"></div>
        <div class="absolute -top-[20%] -left-[10%] w-[800px] h-[800px] bg-blue-600/20 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5"></div>
    </div>

    <!-- Tombol Kembali -->
    <a href="<?php echo e(route('landing')); ?>" class="absolute top-6 left-6 md:top-8 md:left-8 z-50 flex items-center gap-3 px-4 py-2 md:px-5 md:py-2.5 bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white rounded-full transition-all border border-slate-700 hover:border-slate-500 shadow-xl group backdrop-blur-md">
        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-bold text-[10px] md:text-xs uppercase tracking-wider">Kembali</span>
    </a>

    <!-- CONTAINER UTAMA -->
    
    <div class="flex flex-col lg:flex-row w-full min-h-screen p-4 md:p-8 gap-8 lg:gap-10 pt-24 md:pt-12 relative z-10">
        
        <!-- BAGIAN KIRI: SCANNER UTAMA -->
        
        <div class="flex-1 flex flex-col items-center justify-start lg:justify-center w-full">
            
            <!-- Header -->
            <div class="text-center mb-8 lg:mb-10 w-full flex flex-col items-center shrink-0 animate-fade-in-down">
                <div class="inline-flex items-center justify-center p-3 mb-4 md:mb-6 bg-slate-800/50 rounded-2xl border border-slate-700/50 shadow-2xl backdrop-blur-sm">
                    <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-12 h-12 md:w-16 md:h-16 text-white fill-current drop-shadow-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 md:w-16 md:h-16 text-white fill-current drop-shadow-lg']); ?>
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
                
                <h1 class="text-3xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-white tracking-tight uppercase leading-tight drop-shadow-sm text-center">
                    Station Absensi Mandiri
                </h1>
                
                <!-- INDIKATOR MODE AKTIF -->
                <div class="mt-4 flex flex-col items-center gap-2">
                    <div id="active-mode-badge" class="px-6 py-2 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 font-bold tracking-widest uppercase text-xs md:text-sm shadow-[0_0_15px_rgba(59,130,246,0.3)] backdrop-blur-md transition-all duration-500">
                        <i class="ph-fill ph-circle-notch animate-spin mr-2"></i> Memuat Mode...
                    </div>
                    <p class="text-[10px] text-slate-500 font-mono uppercase tracking-widest">Auto Mode Active</p>
                </div>
                
                <!-- Jam Digital -->
                <div class="mt-6 px-6 py-2 md:px-8 md:py-3 rounded-full bg-slate-800/30 border border-slate-700/30 backdrop-blur-md shadow-lg">
                    <span id="kiosk-clock" class="text-3xl md:text-4xl font-black text-slate-200 font-mono tracking-widest text-shadow-glow">00:00:00</span>
                </div>
            </div>

            <!-- BOX SCANNER (Style Cyber) -->
            <div id="status-box" class="w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] bg-slate-800/40 backdrop-blur-md rounded-[2rem] md:rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 hover:border-cyan-500/50 shadow-2xl">
                
                <!-- Sudut Cyber -->
                <div class="absolute -top-0.5 -left-0.5 w-8 h-8 md:w-12 md:h-12 border-t-4 border-l-4 border-cyan-400 rounded-tl-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>
                <div class="absolute -top-0.5 -right-0.5 w-8 h-8 md:w-12 md:h-12 border-t-4 border-r-4 border-cyan-400 rounded-tr-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>
                <div class="absolute -bottom-0.5 -left-0.5 w-8 h-8 md:w-12 md:h-12 border-b-4 border-l-4 border-cyan-400 rounded-bl-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>
                <div class="absolute -bottom-0.5 -right-0.5 w-8 h-8 md:w-12 md:h-12 border-b-4 border-r-4 border-cyan-400 rounded-br-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)] transition-opacity duration-300 corner-accent"></div>

                <!-- Laser Animation -->
                <div id="scan-laser" class="absolute top-0 left-8 right-8 h-1.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-[0_0_20px_#22d3ee] z-20 animate-scan-y opacity-70"></div>

                <!-- State: Standby -->
                <div id="state-standby" class="flex flex-col items-center z-10 transition-transform duration-300 group-hover:scale-105 p-4 text-center">
                    <div class="relative mb-4 md:mb-6">
                         <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full animate-pulse"></div>
                         <i class="ph-duotone ph-barcode text-6xl md:text-8xl text-cyan-400 relative z-10 drop-shadow-[0_0_15px_rgba(34,211,238,0.5)]"></i>
                    </div>
                    <p class="text-2xl md:text-4xl font-black text-white tracking-wide">SIAP SCAN</p>
                    <p class="text-cyan-300/70 mt-2 font-bold text-xs md:text-sm tracking-widest uppercase">Gunakan Scanner USB / Tap Kartu</p>
                </div>

                <!-- State: Result (Hidden by default) -->
                <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full bg-slate-900 rounded-[2rem] md:rounded-[2.5rem] flex-col items-center justify-center border border-slate-700 overflow-hidden p-4">
                    <!-- Content injected by JS -->
                </div>
            </div>

            <!-- Footer Date -->
             <div class="mt-6 md:mt-8 text-center pb-8 lg:pb-0">
                <p class="text-slate-500 text-xs md:text-sm font-bold tracking-wider uppercase" id="kiosk-date">Memuat Tanggal...</p>
                <div class="mt-2 flex gap-4 justify-center">
                    <p class="text-[10px] text-slate-600"><span class="text-slate-400 font-mono bg-slate-800 px-1.5 py-0.5 rounded border border-slate-700">F1</span> Masuk</p>
                    <p class="text-[10px] text-slate-600"><span class="text-slate-400 font-mono bg-slate-800 px-1.5 py-0.5 rounded border border-slate-700">F2</span> Pulang</p>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: LIST KEHADIRAN (LOG) -->
        
        <div class="w-full lg:w-[420px] h-[500px] lg:h-[calc(100vh-4rem)] flex flex-col bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 shadow-2xl rounded-[2rem] md:rounded-[2.5rem] overflow-hidden relative z-20 shrink-0 lg:sticky lg:top-8">
            
            <div class="p-6 md:p-8 bg-slate-900/50 border-b border-slate-700/50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg md:text-xl font-black text-white flex items-center gap-2">
                        <i class="ph-fill ph-clock-user text-blue-500"></i> Log Aktivitas
                    </h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Harian (Masuk/Pulang)</p>
                </div>
                <div class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-6 custom-scrollbar relative">
                <ul id="scan-log-list" class="space-y-3 md:space-y-4 pb-20">
                    <li id="empty-log" class="flex flex-col items-center justify-center py-24 opacity-30">
                        <i class="ph-duotone ph-fingerprint text-6xl text-slate-400 mb-4"></i>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-wide">Menunggu Scan...</p>
                    </li>
                </ul>
                <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-900/90 to-transparent pointer-events-none"></div>
            </div>
        </div>
    </div>

    <!-- Hidden Input Trap -->
    <input type="text" id="scan-input" class="absolute opacity-0 -top-[9999px]" autocomplete="off" autofocus>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const SCHEDULE_DATA = <?php echo $scheduleJson; ?>;
        let currentScanMode = 'Masuk';
        let manualOverride = false;
        
        // UI Elements
        const clockEl = document.getElementById('kiosk-clock');
        const dateEl = document.getElementById('kiosk-date');
        const scanInput = document.getElementById('scan-input');
        const modeBadge = document.getElementById('active-mode-badge');
        const statusBox = document.getElementById('status-box');
        const stateResult = document.getElementById('state-result');
        const laser = document.getElementById('scan-laser');
        const corners = document.querySelectorAll('.corner-accent');
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');

        const csrfToken = '<?php echo e(csrf_token()); ?>';
        const processUrl = '<?php echo e(route("scan.process")); ?>'; 
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        let isProcessing = false;

        const MODE_CONFIG = {
            'Masuk': { color: 'cyan', icon: 'ph-sun-dim', label: 'ABSEN MASUK' },
            'Pulang': { color: 'purple', icon: 'ph-moon-stars', label: 'ABSEN PULANG' }
        };

        // --- AUDIO ---
        function unlockAudio() { if (audioCtx.state === 'suspended') { audioCtx.resume(); } }
        document.body.addEventListener('click', unlockAudio);
        document.body.addEventListener('keydown', unlockAudio);

        function playBeep(type) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            
            if (type === 'success') {
                osc.type = 'sine'; osc.frequency.setValueAtTime(880, audioCtx.currentTime); 
            } else if (type === 'warning') { 
                osc.type = 'triangle'; osc.frequency.setValueAtTime(440, audioCtx.currentTime); 
            } else {
                osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, audioCtx.currentTime);
            }
            
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
            osc.start(); osc.stop(audioCtx.currentTime + 0.5);
        }

        // --- AUTO MODE ---
        function autoSelectMode() {
            if (manualOverride) return;
            const now = new Date(); 
            const currentMinutes = now.getHours() * 60 + now.getMinutes();
            let switchMinutes = 12 * 60; 
            
            if (SCHEDULE_DATA && SCHEDULE_DATA.start_out) {
                const parts = SCHEDULE_DATA.start_out.split(':');
                switchMinutes = (parseInt(parts[0]) * 60) + parseInt(parts[1]);
            }
            setMode(currentMinutes < switchMinutes ? 'Masuk' : 'Pulang');
        }

        function setMode(mode, isManual = false) {
            currentScanMode = mode;
            if(isManual) manualOverride = true;
            const config = MODE_CONFIG[mode] || MODE_CONFIG['Masuk'];
            const manualIndicator = isManual ? '<span class="ml-2 text-[9px] bg-slate-700 px-1 rounded">MANUAL</span>' : '';
            modeBadge.innerHTML = `<i class="ph-fill ${config.icon} mr-2"></i> ${config.label} ${manualIndicator}`;
            modeBadge.className = `px-6 py-2 rounded-full bg-${config.color}-500/20 border border-${config.color}-400/30 text-${config.color}-200 font-bold tracking-widest uppercase text-xs md:text-sm shadow-[0_0_15px_rgba(var(--color-${config.color}-500),0.3)] backdrop-blur-md transition-all duration-500`;
        }

        // --- CLOCK & LOOP ---
        function updateTime() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            dateEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            if (now.getHours() === 0 && now.getMinutes() === 0 && now.getSeconds() === 0) window.location.reload();
        }
        setInterval(updateTime, 1000);
        setInterval(autoSelectMode, 30000); 
        updateTime();
        autoSelectMode();

        // --- LOG DISPLAY (DENGAN FOTO) ---
        function addToLog(name, type, message, photoUrl = null) {
            if(emptyLogMsg) emptyLogMsg.style.display = 'none';
            
            const li = document.createElement('li');
            const time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
            const initial = name ? name.charAt(0).toUpperCase() : '?';
            
            let cardClass = "";
            let avatarHtml = "";

            if (type === 'success') {
                cardClass = "bg-slate-800/50 border-slate-700 hover:border-emerald-500/50";
            } else if (type === 'warning') { 
                cardClass = "bg-slate-800/50 border-slate-700 hover:border-amber-500/50";
            } else { 
                cardClass = "bg-rose-900/20 border-rose-500/20 hover:border-rose-500/50";
            }

            if (photoUrl) {
                let borderStatus = type === 'success' ? 'border-emerald-500' : (type === 'warning' ? 'border-amber-500' : 'border-rose-500');
                avatarHtml = `
                    <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl border-2 ${borderStatus} overflow-hidden bg-slate-800 shadow-lg">
                        <img src="${photoUrl}" class="w-full h-full object-cover" alt="Foto Siswa">
                    </div>
                `;
            } else {
                let avatarColor = type === 'success' ? "bg-gradient-to-br from-emerald-500 to-green-500 shadow-emerald-500/20" : 
                                 (type === 'warning' ? "bg-gradient-to-br from-amber-500 to-orange-500 shadow-amber-500/20" : 
                                 "bg-rose-600 shadow-rose-600/20");
                avatarHtml = `
                    <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl ${avatarColor} flex items-center justify-center text-base md:text-lg font-black text-white border border-white/10 shadow-lg">
                        ${initial}
                    </div>
                `;
            }

            li.className = `flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl border ${cardClass} shadow-md backdrop-blur-sm animate-fade-in-left transition-all`;
            li.innerHTML = `
                ${avatarHtml}
                <div class="flex-1 min-w-0">
                    <p class="text-white font-bold truncate text-sm md:text-base mb-1">${name}</p>
                    <div class="flex justify-between items-center">
                        <p class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase tracking-wider">${message}</p>
                        <span class="text-[9px] md:text-[10px] font-mono font-bold text-cyan-400 bg-cyan-950/50 px-2 py-0.5 rounded border border-cyan-500/20">${time}</span>
                    </div>
                </div>
            `;
            logList.prepend(li);
        }

        // --- SCANNER INPUT ---
        function focusInput() { if (!isProcessing) scanInput.focus(); }
        document.addEventListener('click', (e) => { if (!e.target.closest('a')) focusInput(); });
        scanInput.addEventListener('blur', () => setTimeout(focusInput, 50));

        document.addEventListener('keydown', (e) => {
            if(e.key === 'F1') { e.preventDefault(); setMode('Masuk', true); }
            if(e.key === 'F2') { e.preventDefault(); setMode('Pulang', true); }
            if(e.key === 'Escape') { e.preventDefault(); manualOverride = false; autoSelectMode(); }
        });

        scanInput.addEventListener('change', async function(e) {
            const scanData = e.target.value.trim();
            e.target.value = '';
            if (!scanData || isProcessing) return;
            processScan(scanData);
        });

        // --- PROCESS SCAN ---
        async function processScan(data) {
            isProcessing = true;
            scanInput.blur();
            
            laser.style.display = 'none'; 
            corners.forEach(c => c.classList.add('opacity-0'));
            
            stateResult.classList.remove('hidden'); 
            stateResult.classList.add('flex');
            
            const modeColor = currentScanMode === 'Masuk' ? 'text-cyan-200' : 'text-purple-200';
            const spinnerBorder = currentScanMode === 'Masuk' ? 'border-cyan-400' : 'border-purple-400';

            stateResult.innerHTML = `
                <div class="w-20 h-20 border-4 ${spinnerBorder} border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-6 text-xl ${modeColor} font-bold animate-pulse tracking-widest uppercase">Absen ${currentScanMode}...</p>
            `;

            try {
                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ student_id: data, type: currentScanMode, lat: null, long: null })
                });

                const result = await response.json();
                const isLate = String(result.message || '').toUpperCase().includes('TERLAMBAT');
                const photoUrl = result.student_photo || (result.scan ? result.scan.student_photo : null);

                if (response.ok) {
                    const statusType = isLate ? 'warning' : 'success';
                    playBeep(statusType);
                    
                    let bgClass = isLate ? "bg-amber-500" : "bg-emerald-600";
                    let shadowClass = isLate ? "shadow-[0_0_80px_rgba(245,158,11,0.5)]" : "shadow-[0_0_80px_rgba(16,185,129,0.5)]";
                    let iconClass = isLate ? "ph-warning" : "ph-check";
                    let textClass = isLate ? "text-amber-100" : "text-emerald-100";
                    let badgeBg = isLate ? "bg-amber-800/30 border-amber-400/30" : "bg-emerald-800/30 border-emerald-400/30";

                    statusBox.className = `w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] ${bgClass} rounded-[2rem] md:rounded-[2.5rem] flex flex-col items-center justify-center ${shadowClass} transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden border-none`;
                    
                    let mainVisualHtml = '';
                    if (photoUrl) {
                        mainVisualHtml = `
                            <div class="relative mb-4 group">
                                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/30 shadow-2xl overflow-hidden relative z-10">
                                     <img src="${photoUrl}" class="w-full h-full object-cover" alt="Siswa">
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-white text-${isLate ? 'amber-500' : 'emerald-600'} rounded-full p-2 border-4 border-${isLate ? 'amber-500' : 'emerald-600'} shadow-lg z-20">
                                    <i class="ph-bold ${iconClass} text-2xl"></i>
                                </div>
                            </div>
                        `;
                    } else {
                        mainVisualHtml = `
                            <div class="bg-white/20 p-4 rounded-full mb-4 backdrop-blur-md border border-white/20">
                                <i class="ph-bold ${iconClass} text-5xl text-white"></i>
                            </div>
                        `;
                    }

                    stateResult.innerHTML = `
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
                        <div class="relative z-10 flex flex-col items-center animate-bounce-in p-4 text-center">
                            ${mainVisualHtml}
                            <h2 class="text-3xl md:text-5xl font-black text-white text-center leading-none tracking-tight drop-shadow-lg mb-2">${result.scan?.student_name || 'Siswa'}</h2>
                            <p class="text-base md:text-xl ${textClass} font-bold ${badgeBg} px-4 py-2 md:px-6 rounded-full border uppercase tracking-widest mt-2">${result.message}</p>
                        </div>
                    `;
                    
                    addToLog(result.scan?.student_name, statusType, result.message, photoUrl);

                } else {
                    playBeep('error');
                    statusBox.className = "w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] bg-rose-600 rounded-[2rem] md:rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(225,29,72,0.5)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden border-none";
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    stateResult.innerHTML = `
                         <div class="bg-white/20 p-4 rounded-full mb-4 backdrop-blur-md border border-white/20">
                            <i class="ph-bold ph-x text-5xl text-white"></i>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black text-white text-center drop-shadow-lg mb-2">GAGAL</h2>
                        <p class="text-base md:text-lg text-rose-100 bg-rose-800/30 px-6 py-2 rounded-full border border-rose-400/30 font-bold uppercase tracking-widest mt-2">${errorMsg}</p>
                    `;
                    addToLog("Scan Gagal", 'error', errorMsg, null);
                }

            } catch (error) {
                console.error(error);
                playBeep('error');
                statusBox.className = "w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] bg-slate-800 rounded-[2rem] md:rounded-[2.5rem] border-4 border-rose-500 flex flex-col items-center justify-center relative overflow-hidden";
                stateResult.innerHTML = `<p class="text-rose-400 font-bold text-xl md:text-2xl uppercase animate-pulse">Koneksi Server Error</p>`;
            } finally {
                setTimeout(() => {
                    stateResult.classList.add('hidden'); 
                    stateResult.classList.remove('flex');
                    statusBox.className = "w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] bg-slate-800/40 backdrop-blur-md rounded-[2rem] md:rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 hover:border-cyan-500/50 shadow-2xl";
                    corners.forEach(c => c.classList.remove('opacity-0'));
                    laser.style.display = 'block'; 
                    isProcessing = false; 
                    scanInput.focus();
                }, 2500);
            }
        }
        
        setTimeout(focusInput, 500);
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
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kiosk-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\kiosk\index.blade.php ENDPATH**/ ?>