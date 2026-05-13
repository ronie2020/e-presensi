@extends('layouts.kiosk-layout')

@section('content')
@php
    $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
    $scheduleJson = json_encode($safeSchedule);
    
    // Data Ekstrakurikuler untuk Modal/Dropdown
    $extracurriculars = isset($extracurriculars) ? $extracurriculars : [];
    $extraJson = json_encode($extracurriculars);
@endphp

<!-- PERBAIKAN 1: Tambahkan @open-ekskul-modal.window -->
<div class="min-h-screen w-full bg-slate-900 relative overflow-x-hidden font-sans selection:bg-cyan-500 selection:text-white" x-data="kioskData()" @open-ekskul-modal.window="openExtraModal()">
    
    <!-- Background FX -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-cyan-400 to-indigo-500 shadow-[0_0_20px_rgba(56,189,248,0.5)]"></div>
        <div class="absolute -top-[20%] -left-[10%] w-[800px] h-[800px] bg-blue-600/20 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5"></div>
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ route('landing') }}" class="absolute top-6 left-6 md:top-8 md:left-8 z-50 flex items-center gap-3 px-4 py-2 md:px-5 md:py-2.5 bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white rounded-full transition-all border border-slate-700 hover:border-slate-500 shadow-xl group backdrop-blur-md">
        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-bold text-[10px] md:text-xs uppercase tracking-wider">Kembali</span>
    </a>

    <!-- CONTAINER UTAMA -->
    <div class="flex flex-col lg:flex-row w-full min-h-screen p-4 md:p-8 gap-8 lg:gap-10 pt-24 md:pt-12 relative z-10">
        
        <!-- BAGIAN KIRI: SCANNER UTAMA -->
        <div class="flex-1 flex flex-col items-center justify-start lg:justify-center w-full">
            
            <!-- Header -->
            <div class="text-center mb-6 w-full flex flex-col items-center shrink-0 animate-fade-in-down">
                
                <div ondblclick="toggleFullScreen()" title="Double-click untuk Mode Kiosk (Fullscreen)" class="inline-flex items-center justify-center p-3 mb-4 bg-slate-800/50 rounded-2xl border border-slate-700/50 shadow-2xl backdrop-blur-sm cursor-pointer hover:bg-slate-700 transition-colors">
                    <x-application-logo class="w-12 h-12 md:w-16 md:h-16 text-white fill-current drop-shadow-lg" />
                </div>
                
                <h1 class="text-3xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-white tracking-tight uppercase leading-tight drop-shadow-sm text-center">
                    Station Absensi
                </h1>
                
                <!-- INDIKATOR MODE AKTIF -->
                <div class="mt-4 flex flex-col items-center gap-2">
                    <div id="active-mode-badge" class="px-6 py-2 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 font-bold tracking-widest uppercase text-xs md:text-sm shadow-[0_0_15px_rgba(59,130,246,0.3)] backdrop-blur-md transition-all duration-500 flex items-center gap-2">
                        <i class="ph-fill ph-circle-notch animate-spin"></i> <span>Memuat...</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-mono uppercase tracking-widest" id="manual-indicator">Auto Mode Active</p>
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
                    <p id="ekskul-name-display" class="hidden text-purple-300 mt-1 font-bold text-lg animate-pulse"></p>
                    <p class="text-cyan-300/70 mt-2 font-bold text-xs md:text-sm tracking-widest uppercase">Tap Kartu / Scan RFID</p>
                </div>

                <!-- State: Result (Hidden by default) -->
                <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full bg-slate-900 rounded-[2rem] md:rounded-[2.5rem] flex-col items-center justify-center border border-slate-700 overflow-hidden p-4">
                    <!-- Content injected by JS -->
                </div>
            </div>

            <!-- MODE SELECTOR (Tombol Cepat) -->
             <div class="mt-6 w-full max-w-3xl">
                <div class="grid grid-cols-3 md:grid-cols-6 gap-2 md:gap-3">
                    <button type="button" onclick="window.setMode('Masuk', true)" class="bg-slate-800/60 hover:bg-cyan-900/40 border border-slate-700 hover:border-cyan-500 text-slate-300 hover:text-cyan-400 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center gap-1 group">
                        <i class="ph-bold ph-sun-dim text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Masuk (F1)</span>
                    </button>
                    <button type="button" onclick="window.setMode('Pulang', true)" class="bg-slate-800/60 hover:bg-purple-900/40 border border-slate-700 hover:border-purple-500 text-slate-300 hover:text-purple-400 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center gap-1 group">
                        <i class="ph-bold ph-moon-stars text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Pulang (F2)</span>
                    </button>
                    <button type="button" onclick="window.setMode('Makan', true)" class="bg-slate-800/60 hover:bg-orange-900/40 border border-slate-700 hover:border-orange-500 text-slate-300 hover:text-orange-400 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center gap-1 group">
                        <i class="ph-bold ph-bowl-food text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Makan (F3)</span>
                    </button>
                    <button type="button" onclick="window.setMode('Dhuha', true)" class="bg-slate-800/60 hover:bg-emerald-900/40 border border-slate-700 hover:border-emerald-500 text-slate-300 hover:text-emerald-400 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center gap-1 group">
                        <i class="ph-bold ph-sun-horizon text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Dhuha (F4)</span>
                    </button>
                    <button type="button" onclick="window.setMode('Dhuhur', true)" class="bg-slate-800/60 hover:bg-emerald-900/40 border border-slate-700 hover:border-emerald-500 text-slate-300 hover:text-emerald-400 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center gap-1 group">
                        <i class="ph-bold ph-mosque text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Dhuhur (F5)</span>
                    </button>
                    <button type="button" @click="openExtraModal" class="bg-slate-800/60 hover:bg-pink-900/40 border border-slate-700 hover:border-pink-500 text-slate-300 hover:text-pink-400 py-3 rounded-xl backdrop-blur-sm transition-all flex flex-col items-center gap-1 group">
                        <i class="ph-bold ph-basketball text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Ekskul (F6)</span>
                    </button>
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
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Real-time Feed</p>
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

    <!-- PERBAIKAN 2: Modal Ekskul (Menggunakan AlpineJS dengan handler @click.away diperbaiki) -->
    <div x-show="showExtraModal" x-transition class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak>
        <div class="bg-slate-800 rounded-3xl border border-slate-700 p-6 w-full max-w-md shadow-2xl" @click.away="closeModal()">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><i class="ph-fill ph-trophy text-pink-500"></i> Pilih Kegiatan</h3>
            <div class="grid grid-cols-2 gap-3 max-h-[60vh] overflow-y-auto custom-scrollbar">
                @forelse($extracurriculars as $ex)
                    <button type="button" @click="selectExtra('{{ $ex->id }}', '{{ $ex->name }}')" class="p-3 bg-slate-700/50 hover:bg-pink-900/30 border border-slate-600 hover:border-pink-500 rounded-xl text-left transition-all group">
                        <span class="font-bold text-slate-300 group-hover:text-pink-300 text-sm block">{{ $ex->name }}</span>
                    </button>
                @empty
                    <div class="col-span-2 text-center py-6">
                        <i class="ph-duotone ph-warning-circle text-4xl text-slate-500 mb-2"></i>
                        <p class="text-slate-400 text-sm font-bold">Data ekstrakurikuler kosong.</p>
                    </div>
                @endforelse
            </div>
            <button type="button" @click="closeModal()" class="mt-4 w-full py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-xl transition-colors">Batal</button>
        </div>
    </div>

</div>

<script>
    // Fungsi untuk Kiosk Fullscreen
    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    // Fungsi Text-to-Speech (Suara Robot)
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

    // PERBAIKAN 3: Perbaikan AlpineJS Logic
    function kioskData() {
        return {
            showExtraModal: false,
            openExtraModal() {
                this.showExtraModal = true;
                window.isProcessing = true; // BUG FIXED: menggunakan 'window.'
            },
            selectExtra(id, name) {
                window.selectedExtraId = id;
                window.selectedExtraName = name;
                window.setMode('Ekstrakurikuler', true);
                this.showExtraModal = false;
                
                const display = document.getElementById('ekskul-name-display');
                display.textContent = `Kegiatan: ${name}`;
                display.classList.remove('hidden');
                
                // Beri jeda kecil sebelum mengizinkan scan dan memfokuskan kursor kembali
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
        
        let currentScanMode = 'Masuk';
        let manualOverride = false;
        window.selectedExtraId = null;
        window.selectedExtraName = null;
        window.isProcessing = false; 
        
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

        const csrfToken = '{{ csrf_token() }}';
        const processUrl = '{{ route("scan.process") }}'; 
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

        // KONFIGURASI MODE LENGKAP
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

        // --- AUDIO ---
        function unlockAudio() { 
            if (audioCtx.state === 'suspended') { audioCtx.resume(); } 
            if ('speechSynthesis' in window) { window.speechSynthesis.getVoices(); }
        }
        document.body.addEventListener('click', unlockAudio);
        document.body.addEventListener('keydown', unlockAudio);

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

        // --- AUTO MODE ---
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
            
            modeBadge.innerHTML = `<i class="ph-fill ${config.icon} text-lg"></i> <span>${config.label}</span>`;
            modeBadge.className = `px-6 py-2 rounded-full bg-${config.color}-500/20 border border-${config.color}-400/30 text-${config.color}-200 font-bold tracking-widest uppercase text-xs md:text-sm shadow-[0_0_15px_rgba(var(--color-${config.color}-500),0.3)] backdrop-blur-md transition-all duration-500 flex items-center gap-2`;
            
            document.querySelectorAll('.corner-accent').forEach(el => {
                el.className = `absolute w-8 h-8 md:w-12 md:h-12 border-${config.color}-400 rounded-none shadow-[0_0_15px_rgba(var(--color-${config.color}-500),0.5)] transition-colors duration-300 corner-accent ` + el.className.split(' ').filter(c => c.includes('top') || c.includes('bottom') || c.includes('left') || c.includes('right') || c.includes('border-t') || c.includes('border-b') || c.includes('border-l') || c.includes('border-r')).join(' ') + (el.className.includes('rounded-tl') ? ' rounded-tl-3xl' : '') + (el.className.includes('rounded-tr') ? ' rounded-tr-3xl' : '') + (el.className.includes('rounded-bl') ? ' rounded-bl-3xl' : '') + (el.className.includes('rounded-br') ? ' rounded-br-3xl' : '');
            });

            manualIndicator.textContent = manualText;
            if(isManual) manualIndicator.classList.add('text-yellow-400');
            else manualIndicator.classList.remove('text-yellow-400');
            
            focusInput();
        }

        // --- CLOCK & LOOP ---
        function updateTime() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            // Refresh halaman otomatis setiap jam 00:00 untuk mereset session
            if (now.getHours() === 0 && now.getMinutes() === 0 && now.getSeconds() === 0) window.location.reload();
        }
        setInterval(updateTime, 1000);
        setInterval(autoSelectMode, 30000); 
        updateTime();
        autoSelectMode();

        function addToLog(name, type, message, photoUrl = null) {
            if(emptyLogMsg) emptyLogMsg.style.display = 'none';
            
            const li = document.createElement('li');
            const time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
            const initial = name ? name.charAt(0).toUpperCase() : '?';
            
            let cardClass = "";
            let avatarHtml = "";

            if (type === 'success') cardClass = "bg-slate-800/50 border-slate-700 hover:border-emerald-500/50";
            else if (type === 'warning') cardClass = "bg-slate-800/50 border-slate-700 hover:border-amber-500/50"; 
            else if (type === 'inactive') cardClass = "bg-slate-900 border-slate-700 hover:border-slate-500"; 
            else cardClass = "bg-rose-900/20 border-rose-500/20 hover:border-rose-500/50"; 

            if (photoUrl) {
                let borderStatus = type === 'success' ? 'border-emerald-500' : (type === 'warning' ? 'border-amber-500' : 'border-rose-500');
                avatarHtml = `<div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl border-2 ${borderStatus} overflow-hidden bg-slate-800 shadow-lg"><img src="${photoUrl}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${name}&background=0f172a&color=fff';"></div>`;
            } else {
                let avatarColor = type === 'success' ? "bg-gradient-to-br from-emerald-500 to-green-500 shadow-emerald-500/20" : 
                                 (type === 'warning' ? "bg-gradient-to-br from-amber-500 to-orange-500 shadow-amber-500/20" : 
                                 (type === 'inactive' ? "bg-slate-600 shadow-slate-600/20" : "bg-rose-600 shadow-rose-600/20"));
                avatarHtml = `<div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl ${avatarColor} flex items-center justify-center text-base md:text-lg font-black text-white border border-white/10 shadow-lg">${initial}</div>`;
            }

            li.className = `flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl border ${cardClass} shadow-md backdrop-blur-sm animate-fade-in-left transition-all`;
            li.innerHTML = `
                ${avatarHtml}
                <div class="flex-1 min-w-0">
                    <p class="text-white font-bold truncate text-sm md:text-base mb-1">${name}</p>
                    <div class="flex justify-between items-center">
                        <p class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase tracking-wider truncate mr-2">${message}</p>
                        <span class="text-[9px] md:text-[10px] font-mono font-bold text-cyan-400 bg-cyan-950/50 px-2 py-0.5 rounded border border-cyan-500/20 shrink-0">${time}</span>
                    </div>
                </div>
            `;
            
            logList.prepend(li);
            if (logList.children.length > 20) {
                logList.removeChild(logList.lastElementChild);
            }
        }

        // --- SCANNER INPUT ---
        function focusInput() { if (!window.isProcessing) scanInput.focus(); }
        document.addEventListener('click', (e) => { if (!e.target.closest('a') && !e.target.closest('button')) focusInput(); });
        scanInput.addEventListener('blur', () => setTimeout(focusInput, 50));

        // PERBAIKAN 4: Ubah logika pemanggilan Shortcut Tombol F6 untuk Modal
        document.addEventListener('keydown', (e) => {
            if(e.key === 'F1') { e.preventDefault(); window.setMode('Masuk', true); }
            if(e.key === 'F2') { e.preventDefault(); window.setMode('Pulang', true); }
            if(e.key === 'F3') { e.preventDefault(); window.setMode('Makan', true); }
            if(e.key === 'F4') { e.preventDefault(); window.setMode('Dhuha', true); }
            if(e.key === 'F5') { e.preventDefault(); window.setMode('Dhuhur', true); }
            if(e.key === 'F6') { 
                e.preventDefault(); 
                // Me-lempar custom event yang akan ditangkap oleh "@open-ekskul-modal.window"
                window.dispatchEvent(new Event('open-ekskul-modal')); 
            }
            if(e.key === 'Escape') { e.preventDefault(); manualOverride = false; autoSelectMode(); }
        });

        scanInput.addEventListener('change', async function(e) {
            const scanData = e.target.value.trim();
            e.target.value = '';
            if (!scanData || window.isProcessing) return;
            processScan(scanData);
        });

        // --- PROCESS SCAN LOGIC UPDATE ---
        async function processScan(data) {
            window.isProcessing = true;
            scanInput.blur();
            
            laser.style.display = 'none'; 
            corners.forEach(c => c.classList.add('opacity-0'));
            
            stateResult.classList.remove('hidden'); 
            stateResult.classList.add('flex');
            
            const config = MODE_CONFIG[currentScanMode] || MODE_CONFIG['Masuk'];
            stateResult.innerHTML = `
                <div class="w-20 h-20 border-4 border-${config.color}-400 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-6 text-xl text-${config.color}-200 font-bold animate-pulse tracking-widest uppercase">Absen ${currentScanMode}...</p>
            `;

            try {
                const body = { 
                    student_id: data, 
                    type: currentScanMode, 
                    lat: null, 
                    long: null,
                    extra_id: window.selectedExtraId 
                };

                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });

                // --- PERBAIKAN: TANGKAP ERROR 419 (CSRF) & 401 (UNAUTHORIZED) ---
                if (response.status === 419 || response.status === 401) {
                    playBeep('error');
                    stateResult.innerHTML = `
                        <h2 class="text-2xl font-black text-rose-500 text-center mb-2">SESI BERAKHIR</h2>
                        <p class="text-sm text-white text-center">Memuat ulang sistem...</p>
                    `;
                    // Refresh halaman untuk memperbarui token dan session
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                    return; // Stop eksekusi agar tidak lanjut membaca JSON (yang akan gagal)
                }

                const result = await response.json();
                const isLate = String(result.message || '').toUpperCase().includes('TERLAMBAT');
                const photoUrl = result.student_photo || (result.scan ? result.scan.student_photo : null);
                
                let shortName = "Siswa";
                if (result.scan?.student_name) {
                    shortName = result.scan.student_name.split(' ')[0]; 
                }

                // LOGIKA UI BERDASARKAN STATUS CODE BACKEND
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
                    
                    showResultUI(bgClass, shadowClass, iconClass, result.scan?.student_name, result.message, photoUrl);
                    addToLog(result.scan?.student_name, statusType, result.message, photoUrl);

                } else if (response.status === 422) {
                    playBeep('warning');
                    speakSapaan(`Maaf ${shortName}, Anda sudah absen.`);
                    
                    showResultUI("bg-amber-500", "shadow-[0_0_80px_rgba(245,158,11,0.5)]", "ph-info", "SUDAH ABSEN", result.message, photoUrl);
                    addToLog(result.scan?.student_name || 'Info', 'warning', result.message, photoUrl);

                } else if (response.status === 403) {
                    playBeep('error');
                    speakSapaan(`Maaf, status siswa tidak aktif.`);
                    
                    statusBox.className = "w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] bg-slate-900 rounded-[2rem] md:rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(15,23,42,0.8)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden border-2 border-slate-600";
                    stateResult.innerHTML = `
                         <div class="bg-slate-700/50 p-4 rounded-full mb-4 backdrop-blur-md border border-slate-600"><i class="ph-bold ph-user-minus text-5xl text-slate-300"></i></div>
                        <h2 class="text-3xl md:text-5xl font-black text-slate-300 text-center drop-shadow-lg mb-2">NON-AKTIF</h2>
                        <p class="text-base md:text-lg text-slate-300 bg-slate-800 px-6 py-2 rounded-full border border-slate-600 font-bold uppercase tracking-widest mt-2">${result.message}</p>
                    `;
                    addToLog("Siswa Non-Aktif", 'inactive', result.message, null);

                } else {
                    playBeep('error');
                    speakSapaan(`Kartu tidak terdaftar.`);
                    
                    statusBox.className = "w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] bg-rose-600 rounded-[2rem] md:rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(225,29,72,0.5)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden border-none";
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    stateResult.innerHTML = `
                         <div class="bg-white/20 p-4 rounded-full mb-4 backdrop-blur-md border border-white/20"><i class="ph-bold ph-x text-5xl text-white"></i></div>
                        <h2 class="text-3xl md:text-5xl font-black text-white text-center drop-shadow-lg mb-2">GAGAL</h2>
                        <p class="text-base md:text-lg text-rose-100 bg-rose-800/30 px-6 py-2 rounded-full border border-rose-400/30 font-bold uppercase tracking-widest mt-2">${errorMsg}</p>
                    `;
                    addToLog("Gagal", 'error', errorMsg, null);
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
                    window.isProcessing = false; 
                    focusInput();
                }, 2500);
            }
        }

        function showResultUI(bgClass, shadowClass, iconClass, name, message, photoUrl) {
            statusBox.className = `w-full max-w-3xl aspect-[16/8] md:aspect-[16/7] ${bgClass} rounded-[2rem] md:rounded-[2.5rem] flex flex-col items-center justify-center ${shadowClass} transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden border-none`;
            
            let mainVisualHtml = photoUrl 
                ? `<div class="relative mb-4 group"><div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/30 shadow-2xl overflow-hidden relative z-10 bg-slate-800"><img src="${photoUrl}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${name}&background=0f172a&color=fff';"></div><div class="absolute -bottom-2 -right-2 bg-white text-emerald-600 rounded-full p-2 border-4 border-emerald-600 shadow-lg z-20"><i class="ph-bold ${iconClass} text-2xl"></i></div></div>`
                : `<div class="bg-white/20 p-4 rounded-full mb-4 backdrop-blur-md border border-white/20"><i class="ph-bold ${iconClass} text-5xl text-white"></i></div>`;

            stateResult.innerHTML = `
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
                <div class="relative z-10 flex flex-col items-center animate-bounce-in p-4 text-center">
                    ${mainVisualHtml}
                    <h2 class="text-3xl md:text-5xl font-black text-white text-center leading-none tracking-tight drop-shadow-lg mb-2">${name || 'Siswa'}</h2>
                    <p class="text-base md:text-xl text-white/90 font-bold bg-black/20 px-4 py-2 md:px-6 rounded-full border border-white/20 uppercase tracking-widest mt-2">${message}</p>
                </div>
            `;
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
    
    [x-cloak] { display: none !important; }
</style>
@endsection