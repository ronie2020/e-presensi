<?php $__env->startSection('content'); ?>
<?php
    $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
    $scheduleJson = json_encode($safeSchedule);
    
    // Data Ekstrakurikuler untuk Modal/Dropdown
    $extracurriculars = isset($extracurriculars) ? $extracurriculars : [];
    $extraJson = json_encode($extracurriculars);
?>

<!-- LAYER START KIOSK (Untuk trigger Fullscreen & Audio) -->
<div id="start-overlay" class="fixed inset-0 z-[100] bg-[#020b18] flex flex-col items-center justify-center cursor-pointer transition-opacity duration-500" onclick="startKiosk()">
    <div class="relative mb-6 group">
        <div class="absolute inset-0 bg-cyan-500/40 blur-3xl rounded-full animate-pulse"></div>
        <div class="w-32 h-32 bg-[#06142e] rounded-full border-2 border-cyan-400 flex items-center justify-center relative z-10 shadow-[0_0_30px_#22d3ee] group-hover:scale-105 transition-transform">
            <i class="ph-bold ph-power text-6xl text-cyan-400"></i>
        </div>
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-white tracking-widest mb-3 uppercase text-shadow-glow">SISTEM KIOSK</h1>
    <p class="text-cyan-400 font-bold uppercase tracking-widest text-sm md:text-base bg-cyan-900/50 px-6 py-2 rounded-md border border-cyan-500/30">Ketuk Untuk Memulai</p>
</div>

<!-- CONTAINER UTAMA - DEEP SCI-FI HUD -->
<div class="min-h-screen w-full bg-[#030f26] relative overflow-hidden font-sans selection:bg-cyan-500 selection:text-white" x-data="kioskData()" @open-ekskul-modal.window="openExtraModal()">
    
    <!-- Background FX -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <!-- Grid/Circuit Pattern -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/circuit-board.png')] opacity-[0.03] mix-blend-screen"></div>
        <!-- Deep Radial Gradients -->
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-blue-900/20 rounded-full blur-[150px] pointer-events-none"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-cyan-900/10 rounded-full blur-[120px] pointer-events-none"></div>
    </div>

    <!-- METALLIC SIDEBAR (Pilar Kiri seperti di gambar) -->
    <div class="fixed left-0 top-0 bottom-0 w-12 md:w-16 bg-gradient-to-r from-slate-300 via-slate-100 to-slate-400 border-r border-slate-500 z-50 flex flex-col shadow-[10px_0_30px_rgba(0,0,0,0.5)]">
        <!-- Bevel Effects -->
        <div class="absolute left-1 md:left-2 top-2 bottom-2 w-2 md:w-3 bg-gradient-to-b from-white/60 to-transparent rounded-full shadow-inner opacity-80"></div>
        <div class="absolute right-1 md:right-2 top-2 bottom-2 w-1 md:w-2 bg-gradient-to-b from-black/20 to-transparent rounded-full shadow-inner opacity-50"></div>
        <!-- Joint Details -->
        <div class="absolute top-1/4 left-0 right-0 h-4 bg-slate-500/50 border-y border-slate-600 shadow-inner"></div>
        <div class="absolute top-2/4 left-0 right-0 h-4 bg-slate-500/50 border-y border-slate-600 shadow-inner"></div>
        <div class="absolute top-3/4 left-0 right-0 h-4 bg-slate-500/50 border-y border-slate-600 shadow-inner"></div>
    </div>

    <!-- MAIN WRAPPER (Margin Left untuk menghindari pilar) -->
    <div class="ml-12 md:ml-16 flex flex-col lg:flex-row w-[calc(100%-3rem)] md:w-[calc(100%-4rem)] min-h-screen p-4 md:p-6 gap-6 relative z-10">
        
        <!-- BAGIAN KIRI: KONTROL & SCANNER -->
        <div class="flex-1 flex flex-col w-full relative">
            
            <!-- Header (Logo & Sekolah) -->
            <div class="flex items-center gap-4 mb-6 relative">
                <div class="p-2 bg-[#0a1930] rounded-xl border border-cyan-500/40 shadow-[0_0_20px_rgba(34,211,238,0.2)]">
                    <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-12 h-12 text-white fill-current drop-shadow-md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 text-white fill-current drop-shadow-md']); ?>
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
                    <h1 class="text-2xl md:text-3xl font-black text-white tracking-widest uppercase drop-shadow-lg">SMP Negeri 3 Lakbok</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-cyan-400 font-bold tracking-widest uppercase">Absensi Station</p>
                        <span class="text-[10px] bg-cyan-900/60 text-cyan-300 border border-cyan-500/40 px-2 py-0.5 rounded shadow-sm" id="manual-indicator">Auto Mode Active</span>
                    </div>
                </div>
            </div>

            <!-- GLASS PANEL SCANNER UTAMA -->
            <div class="w-full bg-[#0a1930]/80 backdrop-blur-xl border border-cyan-500/30 rounded-3xl p-6 shadow-[inset_0_0_50px_rgba(34,211,238,0.05)] relative flex-1 flex flex-col">
                
                <!-- Active Mode Badge (Terselip di dalam box sesuai gambar) -->
                <div class="absolute -top-4 left-6 z-20">
                    <div id="active-mode-badge" class="px-6 py-2 rounded-full bg-cyan-900/90 border-2 border-cyan-400 text-cyan-200 font-black tracking-widest uppercase text-base shadow-[0_0_20px_rgba(34,211,238,0.4)] flex items-center gap-2 backdrop-blur-md">
                        <i class="ph-fill ph-sun-dim animate-pulse"></i> <span>ABSEN MASUK</span>
                    </div>
                </div>

                <!-- BOX SCANNER TARGET -->
                <div id="status-box" class="w-full flex-1 mt-6 min-h-[350px] flex flex-col relative transition-all duration-500 group">
                    
                    <!-- Brackets Kiri Atas & Kanan Bawah -->
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-slate-300/80 rounded-tl-lg z-10"></div>
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-slate-300/80 rounded-tr-lg z-10"></div>
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-slate-300/80 rounded-bl-lg z-10"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-slate-300/80 rounded-br-lg z-10"></div>

                    <!-- Inner Box Content -->
                    <div class="absolute inset-2 bg-[#061022] rounded-xl border border-cyan-500/20 shadow-inner flex overflow-hidden">
                        
                        <!-- State: Standby (Visual Barcode & Hexagon) -->
                        <div id="state-standby" class="w-full h-full flex flex-col md:flex-row items-center justify-center p-6 relative z-10">
                            
                            <!-- Bagian Barcode (Kiri) -->
                            <div class="flex flex-col items-center justify-center z-20 w-full md:w-1/2">
                                <div class="bg-gradient-to-b from-white/5 to-transparent p-6 rounded-t-xl border-x border-t border-white/10 backdrop-blur-sm relative">
                                     <!-- Spotlight ke atas -->
                                     <div class="absolute top-[-50px] left-1/2 -translate-x-1/2 w-[150px] h-[100px] bg-white/10 blur-2xl"></div>
                                     <i class="ph-duotone ph-barcode text-8xl text-slate-800 drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"></i>
                                </div>
                                <h2 class="text-3xl font-black text-slate-200 tracking-widest mt-4">SIAP SCAN</h2>
                                <p id="ekskul-name-display" class="hidden text-pink-400 mt-1 font-bold text-sm animate-pulse uppercase tracking-widest"></p>
                            </div>

                            <!-- Visual "Sorotan/Beam" Hologram (Tengah) -->
                            <div class="hidden md:block absolute left-[45%] top-1/2 -translate-y-1/2 w-[15vw] h-24 bg-gradient-to-r from-cyan-500/0 via-cyan-500/20 to-cyan-400/50 z-10" style="clip-path: polygon(0 30%, 100% 0%, 100% 100%, 0 70%);"></div>

                            <!-- Bagian Hexagon (Kanan) -->
                            <div class="flex items-center justify-center z-20 w-full md:w-1/2 mt-8 md:mt-0 relative">
                                <!-- Hexagon Border Frame (Menggunakan padding trick) -->
                                <div class="w-48 h-48 bg-cyan-400 p-[3px] shadow-[0_0_30px_#22d3ee] flex items-center justify-center animate-pulse-slow" style="clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);">
                                    <!-- Hexagon Inner -->
                                    <div class="w-full h-full bg-[#0a1930] flex items-center justify-center relative overflow-hidden" style="clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);">
                                        <!-- Glow Center -->
                                        <div class="absolute inset-0 bg-cyan-500/20 blur-xl"></div>
                                        <!-- Icons in Hexagon -->
                                        <div class="flex gap-2 items-center relative z-10 text-cyan-100">
                                            <i class="ph-duotone ph-radio-button text-4xl"></i>
                                            <div class="w-px h-8 bg-cyan-500/50"></div>
                                            <i class="ph-duotone ph-barcode text-5xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- State: Result (Processing / Success / Error) -->
                        <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full rounded-xl flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300">
                            <!-- Injected by JS -->
                        </div>
                    </div>
                </div>

                <!-- MODE SELECTOR (Tombol Grid di Bawah) -->
                <div class="mt-6 w-full relative z-20">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                        <?php $__currentLoopData = [
                            ['label' => 'Masuk (F1)', 'type' => 'Masuk', 'bg' => 'bg-cyan-900/60', 'border' => 'border-cyan-500/50', 'text' => 'text-cyan-300'],
                            ['label' => 'Pulang (F2)', 'type' => 'Pulang', 'bg' => 'bg-purple-900/60', 'border' => 'border-purple-500/50', 'text' => 'text-purple-300'],
                            ['label' => 'Makan (F3)', 'type' => 'Makan', 'bg' => 'bg-orange-900/60', 'border' => 'border-orange-500/50', 'text' => 'text-orange-300'],
                            ['label' => 'Dhuha (F4)', 'type' => 'Dhuha', 'bg' => 'bg-emerald-900/60', 'border' => 'border-emerald-500/50', 'text' => 'text-emerald-300'],
                            ['label' => 'Dhuhur (F5)', 'type' => 'Dhuhur', 'bg' => 'bg-emerald-900/60', 'border' => 'border-emerald-500/50', 'text' => 'text-emerald-300'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" onclick="window.setMode('<?php echo e($btn['type']); ?>', true)" class="<?php echo e($btn['bg']); ?> border <?php echo e($btn['border']); ?> hover:bg-opacity-80 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center justify-center gap-1 group shadow-lg active:scale-95 text-center px-1">
                            <span class="text-xs lg:text-sm font-bold uppercase tracking-wider <?php echo e($btn['text']); ?> drop-shadow-md group-hover:scale-105 transition-transform"><?php echo e($btn['label']); ?></span>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <button type="button" @click="openExtraModal" class="bg-pink-900/60 border border-pink-500/50 hover:bg-opacity-80 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center justify-center gap-1 group shadow-lg active:scale-95 text-center px-1">
                            <span class="text-xs lg:text-sm font-bold uppercase tracking-wider text-pink-300 drop-shadow-md group-hover:scale-105 transition-transform">Ekskul (F6)</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <button type="button" onclick="exitKiosk()" class="mt-4 text-[10px] text-slate-500 hover:text-rose-400 uppercase tracking-widest text-left w-max transition-colors flex items-center gap-1"><i class="ph-bold ph-sign-out"></i> KELUAR KIOSK</button>
        </div>

        <!-- BAGIAN KANAN: JAM & LOG -->
        <div class="w-full lg:w-[420px] flex flex-col relative z-20 shrink-0">
            
            <!-- Digital Clock (Boxed Style) -->
            <div class="w-full bg-[#061022]/80 backdrop-blur-md border border-cyan-500/40 rounded-xl p-4 shadow-[0_0_20px_rgba(34,211,238,0.15)] flex justify-center items-center relative overflow-hidden">
                <div class="absolute top-0 w-1/2 h-1 bg-cyan-400 shadow-[0_0_15px_#22d3ee]"></div>
                <span id="kiosk-clock" class="text-5xl lg:text-6xl font-black text-cyan-300 font-mono tracking-widest text-shadow-glow">00:00:00</span>
            </div>

            <!-- "Shelf" Design di bawah jam -->
            <div class="relative w-[90%] mx-auto h-8 mb-4 flex flex-col items-center justify-end">
                <!-- Platform Atas -->
                <div class="w-full h-1 bg-cyan-500/50 shadow-[0_-5px_10px_#22d3ee]"></div>
                <!-- Efek 3D Trapezoid (Kemiringan) -->
                <div class="w-full h-full bg-gradient-to-b from-cyan-900/40 to-transparent" style="clip-path: polygon(5% 0, 95% 0, 100% 100%, 0 100%);"></div>
                <div class="absolute bottom-1 w-full text-center">
                    <p class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest">Absensi Station <span class="text-cyan-600">|</span> Auto Mode</p>
                </div>
            </div>

            <!-- List Aktivitas Feed -->
            <div class="flex-1 w-full flex flex-col bg-transparent relative mt-2">
                
                <div class="pb-3 border-b border-cyan-800/50 mb-3">
                    <h2 class="text-lg font-bold text-slate-200 tracking-wide">Profil & Aktifitas Siswa </h2>
                    <p class="text-[9px] font-mono text-cyan-500 uppercase tracking-widest mt-1">Real-time Synchronization</p>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar relative h-[400px]">
                    <ul id="scan-log-list" class="space-y-3 pb-20 pr-2">
                        <li id="empty-log" class="flex flex-col items-center justify-center py-20 opacity-40">
                            <i class="ph-duotone ph-list-magnifying-glass text-5xl text-cyan-500 mb-4 animate-pulse"></i>
                            <p class="text-cyan-400 text-xs font-bold uppercase tracking-widest">Antrean Kosong</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Input Trap untuk Scanner -->
    <input type="text" id="scan-input" class="absolute opacity-0 -top-[9999px]" autocomplete="off" autofocus>

    <!-- Modal Ekskul AlpineJS -->
    <div x-show="showExtraModal" x-transition class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-[#030f26]/95 backdrop-blur-md" x-cloak>
        <div class="bg-[#0a1930] rounded-2xl border-2 border-pink-500 p-8 w-full max-w-lg shadow-[0_0_40px_rgba(244,114,182,0.3)] relative" @click.away="closeModal()">
            <!-- Tech Details -->
            <div class="absolute top-0 left-4 w-12 h-1 bg-pink-500 shadow-[0_0_10px_#ec4899]"></div>
            
            <h3 class="text-2xl font-black text-white mb-6 flex items-center gap-3 uppercase tracking-widest"><i class="ph-fill ph-trophy text-pink-400"></i> Pilih Ekskul</h3>
            <div class="grid grid-cols-2 gap-4 max-h-[50vh] overflow-y-auto custom-scrollbar pr-2">
                <?php $__empty_1 = true; $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <button type="button" @click="selectExtra('<?php echo e($ex->id); ?>', '<?php echo e($ex->name); ?>')" class="p-4 bg-pink-950/30 hover:bg-pink-900/60 border border-pink-900 hover:border-pink-500 rounded-xl text-left transition-all group">
                        <span class="font-bold text-slate-200 group-hover:text-pink-300 text-base block"><?php echo e($ex->name); ?></span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-2 text-center py-8">
                        <i class="ph-duotone ph-warning-circle text-5xl text-slate-500 mb-3"></i>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">Data Kosong</p>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" @click="closeModal()" class="mt-6 w-full py-3 bg-slate-800 hover:bg-rose-600 text-white font-bold rounded-xl transition-colors uppercase tracking-widest text-sm shadow-md">Batal / Tutup</button>
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
        const stateStandby = document.getElementById('state-standby');
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');
        const ekskulDisplay = document.getElementById('ekskul-name-display');

        const processUrl = '<?php echo e(route("kiosk.process")); ?>';
        let csrfToken = '<?php echo e(csrf_token()); ?>';

        const MODE_CONFIG = {
            'Masuk': { border: 'border-cyan-400', bg: 'bg-cyan-900/90', text: 'text-cyan-200', glow: 'rgba(34,211,238,0.4)', icon: 'ph-sun-dim', label: 'ABSEN MASUK' },
            'Pulang': { border: 'border-purple-400', bg: 'bg-purple-900/90', text: 'text-purple-200', glow: 'rgba(192,132,252,0.4)', icon: 'ph-moon-stars', label: 'ABSEN PULANG' },
            'Makan': { border: 'border-orange-400', bg: 'bg-orange-900/90', text: 'text-orange-200', glow: 'rgba(251,146,60,0.4)', icon: 'ph-bowl-food', label: 'AMBIL MAKAN' },
            'Dhuha': { border: 'border-emerald-400', bg: 'bg-emerald-900/90', text: 'text-emerald-200', glow: 'rgba(52,211,153,0.4)', icon: 'ph-sun-horizon', label: 'SHOLAT DHUHA' },
            'Dhuhur': { border: 'border-emerald-400', bg: 'bg-emerald-900/90', text: 'text-emerald-200', glow: 'rgba(52,211,153,0.4)', icon: 'ph-mosque', label: 'SHOLAT DHUHUR' },
            'Ekstrakurikuler': { border: 'border-pink-400', bg: 'bg-pink-900/90', text: 'text-pink-200', glow: 'rgba(244,114,182,0.4)', icon: 'ph-basketball', label: 'EKSKUL' }
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
            const manualText = isManual ? 'MANUAL MODE' : 'Auto Mode Active';
            
            modeBadge.innerHTML = `<i class="ph-fill ${config.icon} animate-pulse"></i> <span>${config.label}</span>`;
            modeBadge.className = `px-6 py-2 rounded-full ${config.bg} border-2 ${config.border} ${config.text} font-black tracking-widest uppercase text-base flex items-center gap-2 backdrop-blur-md transition-all`;
            modeBadge.style.boxShadow = `0 0 20px ${config.glow}`;

            manualIndicator.textContent = manualText;
            if(isManual) manualIndicator.classList.add('text-yellow-400', 'border-yellow-500/50', 'bg-yellow-900/50');
            else manualIndicator.classList.remove('text-yellow-400', 'border-yellow-500/50', 'bg-yellow-900/50');
            
            focusInput();
        }

        function updateTime() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            if (now.getHours() === 0 && now.getMinutes() === 0 && now.getSeconds() === 0) window.location.reload();
        }
        setInterval(updateTime, 1000);
        setInterval(autoSelectMode, 30000); 
        updateTime();
        autoSelectMode();

        setInterval(() => {
            fetch(window.location.href, { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).catch(err => console.log('Ping keep-alive session'));
        }, 5 * 60 * 1000); 

        // Fungsi modifikasi Card untuk menyerupai gambar UI yang baru
        function addToLog(name, type, message, time, mode, photoPath = null) {
            if(emptyLogMsg) emptyLogMsg.style.display = 'none';
            
            const li = document.createElement('li');
            const initial = name ? name.charAt(0).toUpperCase() : '?';
            
            let borderColor, badgeBg, badgeText, iconName;
            
            if (type === 'error') {
                borderColor = 'border-rose-500';
                badgeBg = 'bg-rose-900/80';
                badgeText = 'text-rose-300';
                iconName = 'ph-x-circle';
            } 
            else if (type === 'warning') {
                borderColor = 'border-amber-500';
                badgeBg = 'bg-amber-900/80';
                badgeText = 'text-amber-300';
                iconName = 'ph-clock';
                message = message || 'Terlambat';
            } 
            else if (mode === 'Pulang') {
                borderColor = 'border-purple-500';
                badgeBg = 'bg-purple-900/80';
                badgeText = 'text-purple-300';
                iconName = 'ph-moon-stars';
                message = 'Pulang Sukses'; 
            } 
            else if (type === 'makan') {
                borderColor = 'border-orange-500';
                badgeBg = 'bg-orange-900/80';
                badgeText = 'text-orange-300';
                iconName = 'ph-bowl-food';
                message = message || 'Ambil Makan';
            } 
            else {
                borderColor = 'border-emerald-500';
                badgeBg = 'bg-emerald-900/80';
                badgeText = 'text-emerald-300';
                iconName = 'ph-check-circle';
                message = 'Tepat Waktu';
            }

            let simulatedClass = "Siswa Aktif"; 
            
            let avatarContent = `<span class="text-2xl font-black text-slate-300">${initial}</span>`;
            if (photoPath) {
                let fullUrl = photoPath.startsWith('http') ? photoPath : `/storage/${photoPath}`;
                avatarContent = `<img src="${fullUrl}" alt="${name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='<span class=\\'text-2xl font-black text-slate-300\\'>${initial}</span>';">`;
            }

            li.className = `flex p-3 rounded-xl border-2 ${borderColor} bg-[#0a1930]/90 shadow-[0_4px_15px_rgba(0,0,0,0.3)] backdrop-blur-sm animate-fade-in-left transition-all justify-between items-center`;
            li.innerHTML = `
                <div class="flex items-center gap-4 min-w-0">
                    <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-slate-800 overflow-hidden flex items-center justify-center border border-slate-600 shadow-inner">
                        ${avatarContent}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <p class="text-slate-100 font-bold truncate text-base leading-tight">${name}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-[10px] text-slate-400 font-mono truncate hidden sm:block">${simulatedClass}</p>
                            <span class="text-[10px] font-bold ${badgeBg} ${badgeText} px-2 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                ${message} <i class="ph-fill ${iconName}"></i>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex-shrink-0 pl-2">
                    <div class="px-2 py-1 rounded border border-cyan-500/30 bg-transparent text-cyan-300 text-sm font-mono tracking-widest text-shadow-glow">
                        ${time}
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

        let lastScannedBarcode = '';
        let lastScanTimestamp = 0;

        scanInput.addEventListener('change', async function(e) {
            const scanData = e.target.value.trim();
            e.target.value = ''; 
            
            if (!scanData || window.isProcessing) return;
            window.isProcessing = true; 

            const currentTime = Date.now();
            if (scanData === lastScannedBarcode && (currentTime - lastScanTimestamp) < 5000) {
                console.log("Spam scan dicegah!");
                window.isProcessing = false; 
                return; 
            }

            lastScannedBarcode = scanData;
            lastScanTimestamp = currentTime;

            processScan(scanData, false);
        });

        async function processScan(data, isRetry = false) {
            window.isProcessing = true;
            scanInput.blur();
            
            // Sembunyikan Standby, Tampilkan Loading Result
            stateStandby.classList.add('hidden');
            stateResult.classList.remove('hidden'); 
            stateResult.classList.add('flex');
            
            const config = MODE_CONFIG[currentScanMode] || MODE_CONFIG['Masuk'];
            
            stateResult.innerHTML = `
                <div class="relative w-24 h-24 flex items-center justify-center">
                    <div class="absolute inset-0 border-4 ${config.border} opacity-20 rounded-full"></div>
                    <div class="absolute inset-0 border-4 ${config.border} border-t-transparent rounded-full animate-spin shadow-[0_0_15px_${config.glow}]"></div>
                    <i class="ph-duotone ${config.icon} text-4xl ${config.text} animate-pulse"></i>
                </div>
                <p class="mt-6 text-xl ${config.text} font-black tracking-widest uppercase animate-pulse drop-shadow-md">MEMPROSES DATA...</p>
            `;
            stateResult.className = `absolute inset-0 z-30 w-full h-full rounded-xl flex flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 bg-[#061022]`;

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

                if (response.status === 419 && !isRetry) {
                    try {
                        const tokenRes = await fetch(window.location.href);
                        const text = await tokenRes.text();
                        const match = text.match(/name="csrf-token" content="(.*?)"/);
                        if (match && match[1]) {
                            csrfToken = match[1]; 
                            return processScan(data, true); 
                        }
                    } catch(e) { console.log('Gagal auto-recovery token'); }
                }

                if (response.status === 419 || response.status === 401) {
                    playBeep('error');
                    stateResult.innerHTML = `
                        <div class="bg-rose-900/40 p-5 rounded-full mb-4 border border-rose-500/50 shadow-[0_0_30px_rgba(225,29,72,0.5)]"><i class="ph-bold ph-warning-circle text-6xl text-rose-400"></i></div>
                        <h2 class="text-3xl font-black text-rose-400 text-center mb-2 tracking-widest uppercase">SESI BERAKHIR</h2>
                        <p class="text-slate-300 text-center mb-6 font-bold uppercase text-sm tracking-widest">Koneksi Kemanan Terputus</p>
                        <button onclick="window.location.reload()" class="px-8 py-3 bg-rose-600 hover:bg-rose-500 text-white font-black tracking-widest rounded-lg shadow-lg transition-colors">
                            <i class="ph-bold ph-arrows-clockwise"></i> MUAT ULANG
                        </button>
                    `;
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
                    
                    let bgClass = isLate ? "bg-amber-950/80" : (currentScanMode === 'Pulang' ? "bg-purple-950/80" : "bg-emerald-950/80");
                    let borderClass = isLate ? "border-amber-500" : (currentScanMode === 'Pulang' ? "border-purple-500" : "border-emerald-500");
                    let textClass = isLate ? "text-amber-400" : (currentScanMode === 'Pulang' ? "text-purple-400" : "text-emerald-400");
                    let glowClass = isLate ? "shadow-[inset_0_0_50px_rgba(245,158,11,0.2)]" : (currentScanMode === 'Pulang' ? "shadow-[inset_0_0_50px_rgba(168,85,247,0.2)]" : "shadow-[inset_0_0_50px_rgba(16,185,129,0.2)]");
                    let iconClass = isLate ? "ph-warning" : "ph-check";
                    
                    showResultUI(bgClass, borderClass, glowClass, textClass, iconClass, result.student_name, result.message);
                    addToLog(result.student_name, statusType, result.message, displayTime, currentScanMode, result.photo_path);

                } else {
                    playBeep('error');
                    speakSapaan(response.status === 404 ? 'Kartu tidak terdaftar.' : `Maaf, ${result.message}`);
                    
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    showResultUI("bg-rose-950/80", "border-rose-500", "shadow-[inset_0_0_50px_rgba(225,29,72,0.2)]", "text-rose-400", "ph-x", result.student_name || "Siswa Tidak Dikenal", errorMsg);
                    addToLog(result.student_name || "Gagal Scan", 'error', errorMsg, displayTime, currentScanMode, result.photo_path);
                }

            } catch (error) {
                console.error(error);
                playBeep('error');
                showResultUI("bg-rose-950/80", "border-rose-500", "shadow-[inset_0_0_50px_rgba(225,29,72,0.2)]", "text-rose-400", "ph-warning-octagon", "SYSTEM ERROR", "Gagal Menghubungi Server");
            } finally {
                setTimeout(() => {
                    stateResult.classList.add('hidden'); 
                    stateResult.classList.remove('flex');
                    stateStandby.classList.remove('hidden');
                    window.isProcessing = false; 
                    focusInput();
                }, 2500);
            }
        }

        function showResultUI(bgClass, borderClass, glowClass, textClass, iconClass, name, message) {
            stateResult.className = `absolute inset-0 z-30 w-full h-full flex flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 ${bgClass} border-2 ${borderClass} ${glowClass}`;
            
            stateResult.innerHTML = `
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
                <div class="relative z-10 flex flex-col items-center animate-bounce-in p-4 text-center w-full">
                    <div class="bg-black/40 p-4 rounded-full mb-6 border border-white/10 backdrop-blur-md shadow-lg"><i class="ph-bold ${iconClass} text-6xl ${textClass} drop-shadow-md"></i></div>
                    <h2 class="text-3xl md:text-5xl font-black text-white text-center leading-none tracking-widest drop-shadow-lg mb-4 w-full truncate px-4">${name || 'Siswa'}</h2>
                    <p class="text-sm md:text-lg font-bold bg-black/50 ${textClass} px-8 py-3 rounded-md border border-white/10 uppercase tracking-widest shadow-inner">${message}</p>
                </div>
            `;
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        setInterval(attemptSyncOfflineQueue, 5000);

        window.sendScanToServer = async function(qrData, scanType = 'Harian', extraId = null) {
            const scanPayload = {
                student_id: qrData,
                type: scanType,
                extra_id: extraId,
                time: new Date().toISOString(), 
                _token: csrfToken
            };

            try {
                let response = await fetch('/kiosk/process', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(scanPayload)
                });

                if (!response.ok) {
                    if(response.status >= 500) throw new Error("Server Error");
                    let data = await response.json();
                    showErrorUI(data.message); 
                    return;
                }

                let data = await response.json();
                showSuccessUI(data); 

            } catch (error) {
                console.warn("Jaringan Terputus! Beralih ke Mode Offline...");
                saveToOfflineQueue(scanPayload);
                showSuccessUI({ message: "TERSAVE OFFLINE: Menunggu Sinyal...", student_name: "Antrean " + qrData, note: "Sedang Sinkronisasi..." });
            }
        }

        function saveToOfflineQueue(payload) {
            let queue = JSON.parse(localStorage.getItem('kiosk_offline_queue')) || [];
            queue.push(payload);
            localStorage.setItem('kiosk_offline_queue', JSON.stringify(queue));
        }

        async function attemptSyncOfflineQueue() {
            if (!navigator.onLine) return; 
            let queue = JSON.parse(localStorage.getItem('kiosk_offline_queue')) || [];
            if (queue.length === 0) return; 

            try {
                let response = await fetch('/kiosk/sync-batch', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ scans: queue })
                });

                if (response.ok) {
                    console.log(`Berhasil sinkronisasi ${queue.length} antrean offline!`);
                    localStorage.removeItem('kiosk_offline_queue'); 
                }
            } catch (error) {
                console.log("Menunggu jaringan stabil untuk sinkronisasi antrean...");
            }
        }
    });
</script>

<style>
    /* Custom Scrollbar Cyber Style */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(34,211,238,0.05); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(34,211,238,0.4); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(34,211,238,0.8); }
    
    @keyframes pulseSlow {
        0% { filter: drop-shadow(0 0 15px rgba(34,211,238,0.4)); }
        50% { filter: drop-shadow(0 0 30px rgba(34,211,238,0.8)); }
        100% { filter: drop-shadow(0 0 15px rgba(34,211,238,0.4)); }
    }
    .animate-pulse-slow { animation: pulseSlow 3s infinite; }
    
    @keyframes bounceIn {
        0% { transform: scale(0.9); opacity: 0; }
        50% { transform: scale(1.02); opacity: 1; }
        100% { transform: scale(1); }
    }
    .animate-bounce-in { animation: bounceIn 0.3s cubic-bezier(0.215, 0.610, 0.355, 1.000); }
    
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in-left { animation: fadeInLeft 0.3s ease-out forwards; }
    
    .text-shadow-glow { text-shadow: 0 0 15px rgba(34,211,238,0.7), 0 0 30px rgba(34,211,238,0.4); }
    
    [x-cloak] { display: none !important; }
    :fullscreen header, :fullscreen nav { display: none !important; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kiosk-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/kiosk/index.blade.php ENDPATH**/ ?>