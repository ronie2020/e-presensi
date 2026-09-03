@extends('layouts.kiosk-layout')

@section('content')
@php
    $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
    $scheduleJson = json_encode($safeSchedule);
    
    // Data Ekstrakurikuler untuk Modal/Dropdown
    $extracurriculars = isset($extracurriculars) ? $extracurriculars : [];
    $extraJson = json_encode($extracurriculars);

    // Data Jadwal Bel Otomatis
    $learningSchedules = isset($learningSchedules) ? $learningSchedules : [];
    $bellJson = json_encode($learningSchedules);

    // MENGAMBIL STATUS MASTER BEL DARI CACHE (Aktif/Mati)
    $isBellActive = \Illuminate\Support\Facades\Cache::get('is_bell_active', true);
@endphp

<!-- LAYER START KIOSK -->
<div id="start-overlay" class="fixed inset-0 z-[100] bg-slate-950 flex flex-col items-center justify-center cursor-pointer transition-opacity duration-500" onclick="startKiosk()">
    <div class="relative mb-8 group">
        <div class="absolute inset-0 bg-blue-500/30 blur-[50px] rounded-full animate-pulse"></div>
        <div class="w-32 h-32 bg-slate-900/80 backdrop-blur-md rounded-full border border-blue-500/50 flex items-center justify-center relative z-10 shadow-[0_0_30px_rgba(59,130,246,0.3)] group-hover:scale-105 transition-transform">
            <i class="ph-bold ph-power text-6xl text-blue-400 drop-shadow-[0_0_10px_rgba(59,130,246,0.8)]"></i>
        </div>
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-4 uppercase drop-shadow-lg">SISTEM KIOSK</h1>
    <p class="text-blue-300 font-bold uppercase tracking-widest text-xs bg-blue-900/30 px-6 py-2.5 rounded-full border border-blue-500/30 backdrop-blur-sm animate-pulse">Ketuk Layar Untuk Memulai</p>
</div>

<!-- CONTAINER UTAMA -->
<div class="h-screen w-full bg-slate-950 relative overflow-hidden font-sans text-slate-300 selection:bg-blue-500 selection:text-white flex flex-col" x-data="kioskData()" @open-ekskul-modal.window="openExtraModal()">
    
    <!-- Background Texture & Glow (Modern Dark Theme) -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[url('{{ asset('images/netila.jpg') }}')] bg-cover bg-center bg-no-repeat opacity-20 mix-blend-luminosity"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900/95 to-slate-950 backdrop-blur-[2px]"></div>
        
        <!-- Glowing Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-600/10 rounded-full blur-[120px] mix-blend-screen"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[60%] bg-indigo-600/10 rounded-full blur-[120px] mix-blend-screen"></div>
        
        <!-- Grid Pattern overlay -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>
    </div>

    <!-- MAIN WRAPPER -->
    <div class="max-w-[1500px] w-full mx-auto flex flex-col lg:flex-row flex-1 min-h-0 p-4 md:p-6 lg:p-8 gap-6 lg:gap-8 relative z-10">
        
        <!-- BAGIAN KIRI: KONTROL & SCANNER (Unified Panel) -->
        <div class="flex-1 flex flex-col w-full relative min-h-0 bg-slate-900/40 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden p-6 lg:p-8 gap-6">
            
            <!-- Header (Logo & Sekolah) -->
            <div class="flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 shadow-inner">
                        <x-application-logo class="w-10 h-10 md:w-12 md:h-12 text-white drop-shadow-md fill-current" />
                    </div>
                    <div>
                        <h1 class="text-xl md:text-3xl font-black text-white tracking-tight drop-shadow-sm">SMP Negeri 3 Lakbok</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse shadow-[0_0_8px_rgba(59,130,246,0.8)]"></span>
                            <p class="text-blue-400 font-bold tracking-widest text-[10px] uppercase">Smart Attendance Kiosk</p>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="exitKiosk()" class="w-12 h-12 flex items-center justify-center bg-white/5 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 rounded-2xl border border-white/10 hover:border-rose-500/30 transition-all duration-300">
                    <i class="ph-bold ph-power text-xl"></i>
                </button>
            </div>

            <!-- PANEL SCANNER (The Viewfinder) -->
            <div class="w-full flex-1 min-h-0 flex flex-col relative">
                
                <div class="flex justify-between items-center mb-4 px-1 shrink-0">
                    <div id="active-mode-badge" class="px-5 py-2.5 rounded-xl bg-blue-500/20 border border-blue-500/30 text-blue-400 font-black tracking-widest uppercase text-xs flex items-center gap-2 shadow-[0_0_15px_rgba(59,130,246,0.15)] transition-all">
                        <i class="ph-fill ph-sun-dim text-lg"></i> <span>ABSEN MASUK</span>
                    </div>
                    <span class="text-[9px] bg-slate-800 text-slate-400 border border-slate-700 px-3 py-1.5 rounded-lg font-bold uppercase tracking-widest transition-colors" id="manual-indicator">Auto Mode</span>
                </div>

                <!-- BOX SCANNER TARGET (Redesigned as Digital Screen) -->
                <div id="status-box" class="w-full flex-1 min-h-0 relative bg-slate-950/50 rounded-[2rem] border border-slate-700/50 shadow-inner overflow-hidden flex flex-col justify-center items-center group">
                    
                    <!-- Scanner decorative corners -->
                    <div class="absolute top-6 left-6 w-8 h-8 border-t-4 border-l-4 border-blue-500/40 rounded-tl-xl"></div>
                    <div class="absolute top-6 right-6 w-8 h-8 border-t-4 border-r-4 border-blue-500/40 rounded-tr-xl"></div>
                    <div class="absolute bottom-6 left-6 w-8 h-8 border-b-4 border-l-4 border-blue-500/40 rounded-bl-xl"></div>
                    <div class="absolute bottom-6 right-6 w-8 h-8 border-b-4 border-r-4 border-blue-500/40 rounded-br-xl"></div>
                    
                    <!-- Scanning Laser Line Animation -->
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-blue-400/80 shadow-[0_0_15px_#60a5fa] animate-scan-line z-0"></div>

                    <!-- STANDBY STATE -->
                    <div id="state-standby" class="flex flex-col items-center justify-center p-6 relative z-10 transition-all duration-300 w-full">
                        <div class="w-24 h-24 mb-6 relative group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-blue-500/20 blur-xl rounded-full"></div>
                            <div class="w-full h-full bg-slate-800/80 backdrop-blur-sm rounded-2xl border border-slate-600 shadow-xl flex items-center justify-center relative">
                                <i class="ph-duotone ph-scan text-5xl text-blue-400 drop-shadow-md"></i>
                            </div>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black text-white tracking-widest text-center drop-shadow-md">SIAP SCAN KARTU</h2>
                        <p class="text-slate-400 text-xs mt-3 font-bold tracking-wide text-center">Tempelkan kartu identitas (RFID) pada area scanner</p>
                        <p id="ekskul-name-display" class="hidden text-rose-400 mt-5 font-black text-[10px] bg-rose-500/10 border border-rose-500/20 px-6 py-2.5 rounded-full uppercase tracking-widest shadow-[0_0_15px_rgba(225,29,72,0.1)]"></p>
                    </div>

                    <!-- RESULT STATE -->
                    <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full rounded-[2rem] flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 bg-slate-900/90 backdrop-blur-xl border border-slate-700/50">
                        <!-- Injected by JS -->
                    </div>
                </div>

                <!-- MODE SELECTOR (Sleek Ghost Buttons) -->
                <div class="mt-6 w-full relative z-20 shrink-0">
                    <div class="grid grid-cols-3 lg:grid-cols-6 gap-2 md:gap-3">
                        @foreach([
                            ['label' => 'Masuk (F1)', 'type' => 'Masuk', 'color' => 'blue'],
                            ['label' => 'Pulang (F2)', 'type' => 'Pulang', 'color' => 'indigo'],
                            ['label' => 'Makan (F3)', 'type' => 'Makan', 'color' => 'orange'],
                            ['label' => 'Dhuha (F4)', 'type' => 'Dhuha', 'color' => 'emerald'],
                            ['label' => 'Dhuhur (F5)', 'type' => 'Dhuhur', 'color' => 'teal'],
                        ] as $btn)
                        <button type="button" data-mode="{{ $btn['type'] }}" data-color="{{ $btn['color'] }}" 
                                class="mode-btn bg-slate-800/50 border border-slate-700 hover:bg-slate-700 hover:border-slate-500 py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1">
                            <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $btn['label'] }}</span>
                        </button>
                        @endforeach
                        
                        <button type="button" id="btn-ekskul" data-color="rose"
                                class="bg-slate-800/50 border border-slate-700 hover:bg-slate-700 hover:border-slate-500 py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1">
                            <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400">Ekskul (F6)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: JAM & LOG (Unified Panel) -->
        <div class="w-full lg:w-[450px] flex flex-col shrink-0 min-h-0 h-full gap-6">
            
            <!-- Digital Clock (Prominent) -->
            <div class="w-full bg-gradient-to-br from-blue-600 to-indigo-800 rounded-[2.5rem] p-6 shadow-[0_15px_40px_rgba(30,58,138,0.4)] flex flex-col justify-center items-center relative overflow-hidden shrink-0 border border-blue-400/20">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.05] mix-blend-overlay"></div>
                <!-- Animated Background Glow -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2"></div>
                
                <span id="kiosk-clock" class="text-6xl lg:text-7xl font-black text-white font-mono tracking-tighter drop-shadow-xl relative z-10">00:00:00</span>
                <p id="kiosk-date" class="text-blue-200 font-bold tracking-widest text-xs uppercase mt-2 relative z-10"></p>
            </div>

            <!-- Unified Panel: Jadwal & Log -->
            <div class="flex-1 w-full flex flex-col bg-slate-900/40 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-6 shadow-2xl min-h-0 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

                <!-- Info Jadwal Bel -->
                <div class="shrink-0 mb-5 relative z-10">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="ph-fill ph-bell-ringing"></i> Informasi Jadwal
                    </h3>
                    <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-4 flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[9px] font-bold text-emerald-400 uppercase tracking-widest mb-0.5">Sedang Berlangsung</p>
                                <h4 id="current-period-name" class="text-sm font-black text-white truncate">Memuat...</h4>
                            </div>
                        </div>
                        <div class="w-full h-[1px] bg-slate-700/50"></div>
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Selanjutnya</p>
                                <h4 id="next-period-name" class="text-sm font-bold text-slate-300 truncate">Memuat...</h4>
                            </div>
                            <div id="next-period-countdown" class="text-[10px] font-bold text-blue-300 bg-blue-900/40 px-3 py-1.5 rounded-lg border border-blue-500/20 shrink-0">--:--</div>
                        </div>
                    </div>
                </div>

                <div class="w-full h-[1px] bg-white/10 shrink-0 mb-5"></div>

                <!-- List Aktivitas Feed -->
                <div class="flex-1 flex flex-col min-h-0 relative z-10">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-between">
                        <span class="flex items-center gap-2"><i class="ph-bold ph-list-dashes"></i> Live Activity Log</span>
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Sinkron</span>
                    </h3>

                    <div class="flex-1 overflow-y-auto custom-scrollbar relative pr-2">
                        <ul id="scan-log-list" class="space-y-3 pb-4">
                            <li id="empty-log" class="flex flex-col items-center justify-center py-10 opacity-60 h-full">
                                <div class="w-12 h-12 bg-slate-800/50 rounded-full flex items-center justify-center mb-3 text-slate-500 border border-slate-700 shadow-sm">
                                    <i class="ph-duotone ph-ghost text-2xl"></i>
                                </div>
                                <p class="text-slate-500 text-[9px] font-bold uppercase tracking-widest">Belum ada aktivitas</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Input Trap untuk Scanner -->
    <input type="text" id="scan-input" class="absolute opacity-0 -top-[9999px]" autocomplete="off" autofocus>

    <!-- ELEMEN AUDIO BEL SEKOLAH -->
    <audio id="school-bell" src="{{ asset('sounds/school-bell.mp3') }}" preload="auto"></audio>

    <!-- Modal Ekskul AlpineJS (Dark Theme Mode) -->
    <div x-show="showExtraModal" x-transition class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
        <div class="bg-slate-900 rounded-[2.5rem] border border-slate-700 p-8 w-full max-w-lg shadow-2xl relative overflow-hidden" @click.away="closeModal()">
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl"></div>
            
            <h3 class="text-2xl font-black text-white mb-6 flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/30">
                    <i class="ph-bold ph-trophy text-xl"></i>
                </div> 
                Pilih Ekstrakurikuler
            </h3>
            
            <div class="grid grid-cols-2 gap-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 relative z-10">
                @forelse($extracurriculars as $ex)
                    <button type="button" @click="selectExtra('{{ $ex->id }}', '{{ $ex->name }}')" class="p-4 bg-slate-800/80 hover:bg-rose-500/10 border border-slate-700 hover:border-rose-500/50 rounded-xl text-left transition-all duration-300 group shadow-sm hover:shadow-[0_0_15px_rgba(225,29,72,0.15)]">
                        <span class="font-bold text-slate-300 group-hover:text-rose-400 text-xs block">{{ $ex->name }}</span>
                    </button>
                @empty
                    <div class="col-span-2 text-center py-10 bg-slate-800/50 rounded-2xl border border-dashed border-slate-700">
                        <i class="ph-duotone ph-warning-circle text-4xl text-slate-500 mb-2"></i>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Data Kosong</p>
                    </div>
                @endforelse
            </div>
            
            <button type="button" @click="closeModal()" class="mt-6 w-full py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition-colors uppercase tracking-widest text-[10px] shadow-sm border border-slate-700 relative z-10">
                Batal / Tutup
            </button>
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
        if (document.exitFullscreen) document.exitFullscreen();
        window.location.href = "{{ route('landing') }}"; 
    }

    function speakSapaan(message) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); 
            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = 'id-ID'; utterance.rate = 1.0; utterance.pitch = 1.1;    
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
                display.textContent = `Target: ${name}`;
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
        const SCHEDULE_DATA = {!! $scheduleJson !!};
        const BELL_SCHEDULES = {!! $bellJson !!};
        const IS_BELL_ACTIVE = {{ $isBellActive ? 'true' : 'false' }};
        
        const bellAudio = document.getElementById('school-bell');
        let playedBells = {}; 
        
        let currentScanMode = 'Masuk';
        let manualOverride = false;
        window.selectedExtraId = null;
        window.selectedExtraName = null;
        window.isProcessing = true; 
        
        const clockEl = document.getElementById('kiosk-clock');
        const dateEl = document.getElementById('kiosk-date');
        const scanInput = document.getElementById('scan-input');
        const modeBadge = document.getElementById('active-mode-badge');
        const manualIndicator = document.getElementById('manual-indicator');
        const stateResult = document.getElementById('state-result');
        const stateStandby = document.getElementById('state-standby');
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');
        const ekskulDisplay = document.getElementById('ekskul-name-display');
        const currentPeriodEl = document.getElementById('current-period-name');
        const nextPeriodEl = document.getElementById('next-period-name');
        const nextCountdownEl = document.getElementById('next-period-countdown');

        const processUrl = '{{ route("kiosk.process") }}';
        let csrfToken = '{{ csrf_token() }}';

        // Konfigurasi Tema Mode Berdasarkan Tailwind Colors (Dark Theme Adjustments)
        const MODE_CONFIG = {
            'Masuk': { colorTheme: 'blue', icon: 'ph-sun-dim', label: 'ABSEN MASUK' },
            'Pulang': { colorTheme: 'indigo', icon: 'ph-moon-stars', label: 'ABSEN PULANG' },
            'Makan': { colorTheme: 'orange', icon: 'ph-bowl-food', label: 'AMBIL MAKAN' },
            'Dhuha': { colorTheme: 'emerald', icon: 'ph-sun-horizon', label: 'SHOLAT DHUHA' },
            'Dhuhur': { colorTheme: 'teal', icon: 'ph-mosque', label: 'SHOLAT DHUHUR' },
            'Ekstrakurikuler': { colorTheme: 'rose', icon: 'ph-basketball', label: 'EKSKUL' }
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
            osc.connect(gain); gain.connect(audioCtx.destination);
            
            if (type === 'success') { osc.type = 'sine'; osc.frequency.setValueAtTime(880, audioCtx.currentTime); } 
            else if (type === 'warning') { osc.type = 'triangle'; osc.frequency.setValueAtTime(440, audioCtx.currentTime); } 
            else if (type === 'makan') { osc.type = 'sine'; osc.frequency.setValueAtTime(600, audioCtx.currentTime); }
            else { osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, audioCtx.currentTime); } 
            
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
            osc.start(); osc.stop(audioCtx.currentTime + 0.5);
        }

        window.setMode = function(mode, isManual = false) {
            if(mode !== 'Ekstrakurikuler') {
                ekskulDisplay.classList.add('hidden');
                window.selectedExtraId = null;
            }
            
            currentScanMode = mode;
            if(isManual) manualOverride = true;
            
            const config = MODE_CONFIG[mode] || MODE_CONFIG['Masuk'];
            const manualText = isManual ? 'Mode Manual' : 'Auto Mode';
            const color = config.colorTheme;
            
            // Perbarui Badge Scanner
            modeBadge.innerHTML = `<i class="ph-fill ${config.icon} text-lg"></i> <span>${config.label}</span>`;
            modeBadge.className = `px-5 py-2.5 rounded-xl bg-${color}-500/20 border border-${color}-500/30 text-${color}-400 font-black tracking-widest uppercase text-xs flex items-center gap-2 shadow-[0_0_15px_rgba(var(--tw-colors-${color}-500),0.15)] transition-all`;

            if(isManual) {
                manualIndicator.textContent = manualText;
                manualIndicator.className = 'text-[9px] bg-rose-500/20 text-rose-400 border border-rose-500/30 px-3 py-1.5 rounded-lg font-bold uppercase tracking-widest transition-colors shadow-[0_0_10px_rgba(225,29,72,0.2)]';
            } else {
                manualIndicator.textContent = manualText;
                manualIndicator.className = 'text-[9px] bg-slate-800 text-slate-400 border border-slate-700 px-3 py-1.5 rounded-lg font-bold uppercase tracking-widest transition-colors';
            }

            // Perbarui Style Tombol (Sleek Mode)
            document.querySelectorAll('.mode-btn, #btn-ekskul').forEach(btn => {
                const btnColor = btn.getAttribute('data-color');
                const btnMode = btn.getAttribute('data-mode');
                
                if(btnMode === mode || (mode === 'Ekstrakurikuler' && btn.id === 'btn-ekskul')) {
                    // Tombol Aktif (Menyala Solid)
                    btn.className = `mode-btn bg-${btnColor}-600 border border-${btnColor}-500 hover:bg-${btnColor}-500 py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1 shadow-[0_0_15px_rgba(var(--tw-colors-${btnColor}-500),0.4)] transform scale-105`;
                    btn.querySelector('span').className = `text-[9px] md:text-[10px] font-black uppercase tracking-widest text-white`;
                } else {
                    // Tombol Non-Aktif (Outline/Ghost)
                    btn.className = `mode-btn bg-slate-800/50 border border-slate-700 hover:bg-slate-700 hover:border-slate-500 py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1 opacity-70`;
                    btn.querySelector('span').className = `text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400`;
                }
                
                if(btn.id === 'btn-ekskul') btn.classList.remove('mode-btn'); // Jaga agar id ekskul gak error selector
            });
            
            focusInput();
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

        document.querySelectorAll('.mode-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                window.setMode(this.getAttribute('data-mode'), true);
            });
        });

        document.getElementById('btn-ekskul').addEventListener('click', function() {
            window.dispatchEvent(new Event('open-ekskul-modal'));
        });

        // --- INFO JAM PELAJARAN SAAT INI & SELANJUTNYA ---
        function updateLearningPeriodInfo(nowMinutes) {
            if (!currentPeriodEl || !nextPeriodEl) return;

            if (!BELL_SCHEDULES || BELL_SCHEDULES.length === 0) {
                currentPeriodEl.textContent = 'Jadwal Kosong';
                nextPeriodEl.textContent = '-';
                nextCountdownEl.textContent = '--:--';
                return;
            }

            let current = null;
            let next = null;

            for (let i = 0; i < BELL_SCHEDULES.length; i++) {
                const item = BELL_SCHEDULES[i];
                const itemMinutes = toMinutes(item.trigger_time.substring(0, 5));

                if (itemMinutes <= nowMinutes) {
                    current = item;
                } else {
                    next = item;
                    break;
                }
            }

            currentPeriodEl.textContent = current ? current.activity_name : 'Menunggu Bel';

            if (next) {
                nextPeriodEl.textContent = next.activity_name;
                const nextMinutes = toMinutes(next.trigger_time.substring(0, 5));
                const diff = nextMinutes - nowMinutes;
                const jam = Math.floor(diff / 60);
                const menit = diff % 60;
                nextCountdownEl.textContent = jam > 0 ? `${jam}j ${menit}m lagi` : `${menit}m lagi`;
            } else {
                nextPeriodEl.textContent = 'Selesai';
                nextCountdownEl.textContent = '-';
            }
        }

        function updateTime() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            
            // Format Tanggal
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.textContent = now.toLocaleDateString('id-ID', options);
            
            // Format jam dan menit saat ini (HH:MM)
            const currentTimeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            const nowMinutes = now.getHours() * 60 + now.getMinutes();
            updateLearningPeriodInfo(nowMinutes);
            
            // --- LOGIKA BEL OTOMATIS ---
            if (BELL_SCHEDULES && BELL_SCHEDULES.length > 0) {
                BELL_SCHEDULES.forEach(bell => {
                    const bellTime = bell.trigger_time.substring(0, 5); 
                    
                    if (bellTime === currentTimeStr && !playedBells[bellTime]) {
                        playedBells[bellTime] = true; 
                        
                        if (IS_BELL_ACTIVE) {
                            if (bell.audio_file) {
                                bellAudio.src = '{{ asset("storage") }}/' + bell.audio_file;
                            } else {
                                bellAudio.src = '{{ asset("sounds/school-bell.mp3") }}'; 
                            }

                            bellAudio.load();
                            bellAudio.currentTime = 0;
                            
                            let playPromise = bellAudio.play();
                            if (playPromise !== undefined) {
                                playPromise.catch(e => console.log("Gagal memutar bel:", e));
                            }
                            
                            setTimeout(() => {
                                speakSapaan(`Perhatian. Waktunya ${bell.activity_name}.`);
                            }, 5000); 
                        }
                    }
                });
            }

            if (now.getHours() === 0 && now.getMinutes() === 0 && now.getSeconds() === 0) {
                playedBells = {};
                window.location.reload();
            }
        }
        
        setInterval(updateTime, 1000);
        setInterval(autoSelectMode, 30000); 
        updateTime();
        autoSelectMode();

        setInterval(() => {
            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(err => null);
        }, 5 * 60 * 1000); 

        function addToLog(name, type, message, time, mode, photoPath = null) {
            if(emptyLogMsg) emptyLogMsg.style.display = 'none';
            
            const li = document.createElement('li');
            const initial = name ? name.charAt(0).toUpperCase() : '?';
            
            // Penyesuaian Style Log untuk Dark Mode
            let borderColor, badgeBg, badgeText, iconName;
            if (type === 'error') {
                borderColor = 'border-rose-500/30'; badgeBg = 'bg-rose-500/20'; badgeText = 'text-rose-400'; iconName = 'ph-x-circle';
            } 
            else if (type === 'warning') {
                borderColor = 'border-amber-500/30'; badgeBg = 'bg-amber-500/20'; badgeText = 'text-amber-400'; iconName = 'ph-clock'; message = message || 'Terlambat';
            } 
            else if (mode === 'Pulang') {
                borderColor = 'border-indigo-500/30'; badgeBg = 'bg-indigo-500/20'; badgeText = 'text-indigo-400'; iconName = 'ph-moon-stars'; message = 'Pulang Sukses'; 
            } 
            else if (type === 'makan') {
                borderColor = 'border-orange-500/30'; badgeBg = 'bg-orange-500/20'; badgeText = 'text-orange-400'; iconName = 'ph-bowl-food'; message = message || 'Ambil Makan';
            } 
            else {
                borderColor = 'border-emerald-500/30'; badgeBg = 'bg-emerald-500/20'; badgeText = 'text-emerald-400'; iconName = 'ph-check-circle'; message = 'Tepat Waktu';
            }

            let avatarContent = `<span class="text-xl font-black text-slate-500">${initial}</span>`;
            if (photoPath) {
                let fullUrl = photoPath.startsWith('http') ? photoPath : `/storage/${photoPath}`;
                avatarContent = `<img src="${fullUrl}" alt="${name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='<span class=\\'text-xl font-black text-slate-500\\'>${initial}</span>';">`;
            }

            li.className = `flex p-3 rounded-xl border ${borderColor} bg-slate-800/60 shadow-sm animate-fade-in-left transition-all justify-between items-center backdrop-blur-sm`;
            li.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-700 overflow-hidden flex items-center justify-center border border-slate-600">
                        ${avatarContent}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <p class="text-white font-bold truncate text-xs leading-tight">${name}</p>
                        <div class="flex items-center mt-1">
                            <span class="text-[9px] font-bold ${badgeBg} ${badgeText} px-2 py-0.5 rounded flex items-center gap-1 uppercase tracking-wider">
                                ${message} <i class="ph-fill ${iconName}"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 pl-2">
                    <div class="text-slate-400 text-[9px] font-bold tracking-widest bg-slate-900/50 px-2 py-1 rounded border border-slate-700">
                        ${time}
                    </div>
                </div>
            `;
            
            logList.prepend(li);
            if (logList.children.length > 50) logList.removeChild(logList.lastElementChild);
        }

        function focusInput() { if (!window.isProcessing) scanInput.focus(); }
        document.addEventListener('click', (e) => { 
            if (!e.target.closest('button') && e.target.id !== 'start-overlay') focusInput(); 
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

        let lastScannedBarcode = ''; let lastScanTimestamp = 0;

        scanInput.addEventListener('change', async function(e) {
            const scanData = e.target.value.trim();
            e.target.value = ''; 
            
            if (!scanData || window.isProcessing) return;
            window.isProcessing = true; 

            const currentTime = Date.now();
            if (scanData === lastScannedBarcode && (currentTime - lastScanTimestamp) < 5000) {
                window.isProcessing = false; return; 
            }

            lastScannedBarcode = scanData;
            lastScanTimestamp = currentTime;

            processScan(scanData, false);
        });

         async function processScan(data, isRetry = false) {
            window.isProcessing = true; scanInput.blur();
            
            stateStandby.classList.add('hidden');
            stateResult.classList.remove('hidden'); stateResult.classList.add('flex');
            
            const config = MODE_CONFIG[currentScanMode] || MODE_CONFIG['Masuk'];
            const clr = config.colorTheme;
            
            // Tampilan Loading Process (Dark Theme)
            stateResult.innerHTML = `
                <div class="relative w-24 h-24 flex items-center justify-center mb-6">
                    <div class="absolute inset-0 border-4 border-slate-700 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-${clr}-500 border-t-transparent rounded-full animate-spin"></div>
                    <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center shadow-lg border border-slate-600">
                        <i class="ph-duotone ${config.icon} text-3xl text-${clr}-400"></i>
                    </div>
                </div>
                <p class="text-sm font-black tracking-widest uppercase text-${clr}-300 animate-pulse drop-shadow-md">MEMPROSES DATA...</p>
            `;
            stateResult.className = `absolute inset-0 z-30 w-full h-full flex flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 bg-slate-900/95 backdrop-blur-md`;

            try {
                const body = { student_id: data, type: currentScanMode, extra_id: window.selectedExtraId, lat: null, long: null };

                let response = await fetch(processUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });

                if (response.status === 419 && !isRetry) {
                    try {
                        const tokenRes = await fetch(window.location.href);
                        const match = (await tokenRes.text()).match(/name="csrf-token" content="(.*?)"/);
                        if (match && match[1]) { csrfToken = match[1]; return processScan(data, true); }
                    } catch(e) { }
                }

                 if (response.status === 419 || response.status === 401) {
                    playBeep('error');
                    stateResult.innerHTML = `
                        <div class="bg-rose-500/20 p-5 rounded-full mb-4 border border-rose-500/30 shadow-[0_0_30px_rgba(225,29,72,0.3)]">
                            <i class="ph-bold ph-warning-circle text-6xl text-rose-500"></i>
                        </div>
                        <h2 class="text-2xl font-black text-rose-400 text-center mb-2 tracking-widest uppercase drop-shadow-md">SESI BERAKHIR</h2>
                        <p class="text-slate-400 text-sm mb-8 font-bold">Harap muat ulang halaman</p>
                        <button onclick="window.location.reload()" class="px-8 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(225,29,72,0.4)] transition-colors uppercase tracking-widest text-[10px]">
                            <i class="ph-bold ph-arrows-clockwise mr-1"></i> Muat Ulang
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
                    
                    // Dark Theme Result Classes
                    let bgClass = isLate ? "bg-amber-900/60" : (currentScanMode === 'Pulang' ? "bg-indigo-900/60" : "bg-emerald-900/60");
                    let borderClass = isLate ? "border-amber-500/50 shadow-[0_0_40px_rgba(245,158,11,0.2)]" : (currentScanMode === 'Pulang' ? "border-indigo-500/50 shadow-[0_0_40px_rgba(99,102,241,0.2)]" : "border-emerald-500/50 shadow-[0_0_40px_rgba(16,185,129,0.2)]");
                    let textClass = isLate ? "text-amber-400" : (currentScanMode === 'Pulang' ? "text-indigo-400" : "text-emerald-400");
                    let iconClass = isLate ? "ph-warning-circle" : "ph-check-circle";
                    let iconBgClass = isLate ? "bg-amber-500/20 border-amber-500/30" : (currentScanMode === 'Pulang' ? "bg-indigo-500/20 border-indigo-500/30" : "bg-emerald-500/20 border-emerald-500/30");
                    
                    showResultUI(bgClass, borderClass, textClass, iconClass, iconBgClass, result.student_name, result.message);
                    addToLog(result.student_name, statusType, result.message, displayTime, currentScanMode, result.photo_path);

                } else {
                    playBeep('error');
                    speakSapaan(response.status === 404 ? 'Kartu tidak terdaftar.' : `Maaf, ${result.message}`);
                    
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    showResultUI("bg-rose-900/60", "border-rose-500/50 shadow-[0_0_40px_rgba(225,29,72,0.2)]", "text-rose-400", "ph-x-circle", "bg-rose-500/20 border-rose-500/30", result.student_name || "Siswa Tidak Dikenal", errorMsg);
                    addToLog(result.student_name || "Gagal Scan", 'error', errorMsg, displayTime, currentScanMode, result.photo_path);
                }

            } catch (error) {
                console.error(error);
                playBeep('error');
                showResultUI("bg-rose-900/60", "border-rose-500/50", "text-rose-400", "ph-warning-octagon", "bg-rose-500/20 border-rose-500/30", "SYSTEM ERROR", "Gagal Menghubungi Server");
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

        function showResultUI(bgClass, borderClass, textClass, iconClass, iconBgClass, name, message) {
            stateResult.className = `absolute inset-0 z-30 w-full h-full flex flex-col items-center justify-center p-6 transition-all duration-300 ${bgClass} rounded-[2rem] border-2 ${borderClass} backdrop-blur-xl`;
            
            stateResult.innerHTML = `
                <div class="flex flex-col items-center animate-bounce-in text-center w-full">
                    <div class="w-28 h-28 ${iconBgClass} rounded-full flex items-center justify-center mb-6 border">
                        <i class="ph-fill ${iconClass} text-6xl ${textClass} drop-shadow-md"></i>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4 w-full truncate px-4 drop-shadow-lg">${name || 'Siswa'}</h2>
                    <p class="text-[10px] md:text-xs font-bold ${textClass} px-6 py-2.5 bg-slate-950/50 rounded-full border border-current uppercase tracking-widest shadow-inner">${message}</p>
                </div>
            `;
        }
    });
</script>

<style>
    /* Scrollbar minimalis untuk Dark Theme */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
    
    /* Animasi Laser Scanner Naik-Turun */
    @keyframes scanLine {
        0%, 100% { top: 10%; opacity: 0; }
        10%, 90% { opacity: 1; }
        50% { top: 90%; }
    }
    .animate-scan-line { animation: scanLine 3s ease-in-out infinite; }
    
    @keyframes bounceIn { 0% { transform: scale(0.9); opacity: 0; } 50% { transform: scale(1.02); opacity: 1; } 100% { transform: scale(1); } }
    .animate-bounce-in { animation: bounceIn 0.3s cubic-bezier(0.215, 0.610, 0.355, 1.000); }
    
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in-left { animation: fadeInLeft 0.3s ease-out forwards; }
    
    [x-cloak] { display: none !important; }
    :fullscreen header, :fullscreen nav { display: none !important; }
</style>
@endsection