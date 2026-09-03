@extends('layouts.kiosk-layout')

@section('content')
@php
    $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
    $scheduleJson = json_encode($safeSchedule);
    
    // Data Ekstrakurikuler untuk Modal/Dropdown
    $extracurriculars = isset($extracurriculars) ? $extracurriculars : [];
    $extraJson = json_encode($extracurriculars);

    // --- TAMBAHAN: Data Jadwal Bel Otomatis ---
    $learningSchedules = isset($learningSchedules) ? $learningSchedules : [];
    $bellJson = json_encode($learningSchedules);
@endphp

<!-- LAYER START KIOSK -->
<div id="start-overlay" class="fixed inset-0 z-[100] bg-slate-900 flex flex-col items-center justify-center cursor-pointer transition-opacity duration-500" onclick="startKiosk()">
    <div class="relative mb-8 group">
        <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full animate-pulse"></div>
        <div class="w-32 h-32 bg-slate-800 rounded-full border-4 border-blue-400 flex items-center justify-center relative z-10 shadow-2xl group-hover:scale-105 transition-transform">
            <i class="ph-bold ph-power text-6xl text-blue-400"></i>
        </div>
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-slate-100 tracking-tight mb-4 uppercase">SISTEM KIOSK</h1>
    <p class="text-blue-400 font-bold uppercase tracking-widest text-sm bg-blue-500/10 px-6 py-2 rounded-full border border-blue-500/30">Ketuk Untuk Memulai</p>
</div>

<!-- CONTAINER UTAMA -->
<div class="h-screen w-full bg-slate-900 relative overflow-hidden font-sans text-slate-800 selection:bg-blue-500 selection:text-white flex flex-col" x-data="kioskData()" @open-ekskul-modal.window="openExtraModal()">
    
    <!-- Background Texture & Image -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[url('{{ asset('images/netila.jpg') }}')] bg-cover bg-center bg-no-repeat"></div>
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
        <div class="absolute -top-[10%] -left-[5%] w-[60%] h-[50%] bg-blue-500/20 rounded-br-full blur-[100px] mix-blend-screen"></div>
        <div class="absolute top-[20%] right-[-10%] w-[50%] h-[80%] bg-cyan-400/10 rounded-tl-[100px] blur-[100px] mix-blend-screen"></div>
    </div>

    <!-- MAIN WRAPPER -->
    <div class="max-w-[1400px] w-full mx-auto flex flex-col lg:flex-row flex-1 min-h-0 p-4 md:p-8 gap-8 relative z-10">
        
        <!-- BAGIAN KIRI: KONTROL & SCANNER -->
        <div class="flex-1 flex flex-col w-full relative min-h-0 gap-6">
            
            <!-- Header (Logo & Sekolah) -->
            <div class="flex items-center gap-5 bg-white/80 backdrop-blur-xl p-4 rounded-[2rem] border border-white/50 shadow-xl shadow-slate-900/10 shrink-0">
                <div class="p-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <x-application-logo class="w-10 h-10 md:w-12 md:h-12 text-slate-800 fill-current" />
                </div>
                <div class="flex-1 flex justify-between items-center">
                    <div>
                        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">SMP Negeri 3 Lakbok</h1>
                        <p class="text-blue-600 font-bold tracking-wide text-xs">Absensi Station</p>
                    </div>
                    <button type="button" onclick="exitKiosk()" class="px-4 py-2 bg-white hover:bg-rose-50 text-slate-500 hover:text-rose-600 rounded-xl font-bold text-xs transition-colors flex items-center gap-2 border border-slate-200 shadow-sm">
                        <i class="ph-bold ph-sign-out text-lg"></i> <span class="hidden md:inline">Keluar</span>
                    </button>
                </div>
            </div>

            <!-- PANEL SCANNER UTAMA -->
            <div class="w-full bg-white/80 backdrop-blur-xl border border-white/50 rounded-[2.5rem] p-6 shadow-2xl shadow-slate-900/10 relative flex-1 flex flex-col min-h-0">
                
                <div class="flex justify-between items-center mb-5 px-2 shrink-0">
                    <div id="active-mode-badge" class="px-5 py-2 rounded-xl bg-slate-800 text-slate-100 font-bold tracking-wider uppercase text-xs shadow-md flex items-center gap-2 transition-all">
                        <i class="ph-fill ph-sun-dim text-lg text-blue-400"></i> <span>ABSEN MASUK</span>
                    </div>
                    <span class="text-[10px] bg-slate-100 text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm font-bold uppercase tracking-wider transition-colors" id="manual-indicator">Auto Mode</span>
                </div>

                <!-- BOX SCANNER TARGET -->
                <div id="status-box" class="w-full flex-1 min-h-0 flex flex-col relative transition-all duration-500 group">
                    <div class="absolute inset-0 bg-slate-50/50 rounded-[2rem] border-2 border-dashed border-slate-300 flex overflow-hidden">
                        
                        <div id="state-standby" class="w-full h-full flex flex-col items-center justify-center p-6 relative z-10 transition-all duration-300">
                            <div class="w-20 h-20 bg-white rounded-[1.5rem] shadow-sm border border-slate-200 flex items-center justify-center mb-4 relative group-hover:scale-105 transition-transform">
                                <i class="ph-duotone ph-barcode text-5xl text-blue-500 relative z-10"></i>
                            </div>
                            <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight text-center">SIAP SCAN BARCODE</h2>
                            <p class="text-slate-500 text-xs mt-2 font-bold text-center">Dekatkan kartu identitas Anda ke scanner</p>
                            <p id="ekskul-name-display" class="hidden text-rose-600 mt-4 font-bold text-xs bg-rose-50 border border-rose-200 px-5 py-2 rounded-full uppercase tracking-wider shadow-sm"></p>
                        </div>

                        <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full rounded-[2rem] flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 bg-white/95 backdrop-blur-md">
                            <!-- Injected by JS -->
                        </div>
                    </div>
                </div>

                <!-- MODE SELECTOR -->
                <div class="mt-5 w-full relative z-20 shrink-0">
                    <div class="grid grid-cols-3 lg:grid-cols-6 gap-2">
                        @foreach([
                            ['label' => 'Masuk (F1)', 'type' => 'Masuk', 'bg' => 'bg-slate-800', 'hover' => 'hover:bg-slate-900', 'text' => 'text-slate-100'],
                            ['label' => 'Pulang (F2)', 'type' => 'Pulang', 'bg' => 'bg-blue-600', 'hover' => 'hover:bg-blue-700', 'text' => 'text-slate-100'],
                            ['label' => 'Makan (F3)', 'type' => 'Makan', 'bg' => 'bg-orange-500', 'hover' => 'hover:bg-orange-600', 'text' => 'text-slate-100'],
                            ['label' => 'Dhuha (F4)', 'type' => 'Dhuha', 'bg' => 'bg-emerald-600', 'hover' => 'hover:bg-emerald-700', 'text' => 'text-slate-100'],
                            ['label' => 'Dhuhur (F5)', 'type' => 'Dhuhur', 'bg' => 'bg-emerald-600', 'hover' => 'hover:bg-emerald-700', 'text' => 'text-slate-100'],
                        ] as $btn)
                        <button type="button" data-mode="{{ $btn['type'] }}" class="mode-btn {{ $btn['bg'] }} {{ $btn['hover'] }} py-2.5 md:py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center shadow-md active:scale-95 text-center px-1 border-2 border-transparent">
                            <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider {{ $btn['text'] }}">{{ $btn['label'] }}</span>
                        </button>
                        @endforeach
                        
                        <button type="button" id="btn-ekskul" class="bg-rose-500 hover:bg-rose-600 py-2.5 md:py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center shadow-md active:scale-95 text-center px-1 border-2 border-transparent">
                            <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider text-slate-100">Ekskul (F6)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: JAM & LOG -->
    <div class="w-full lg:w-[400px] flex flex-col relative z-20 shrink-0 min-h-0 h-full gap-6">
            
            <!-- Digital Clock -->
            <div class="w-full bg-gradient-to-br from-blue-500 to-blue-700 rounded-[2.5rem] p-5 shadow-2xl shadow-blue-900/20 flex flex-col justify-center items-center relative overflow-hidden shrink-0 border border-blue-400/30">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
                <span id="kiosk-clock" class="text-5xl lg:text-6xl font-black text-white font-mono tracking-tighter drop-shadow-md relative z-10">00:00:00</span>
                <div class="mt-2 inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-3 py-1 rounded-full relative z-10">
                    <span class="w-2 h-2 rounded-full bg-cyan-300 animate-pulse shadow-[0_0_8px_rgba(103,232,249,0.8)]"></span>
                    <p class="text-[9px] font-bold text-cyan-50 uppercase tracking-widest">Sistem Aktif</p>
                </div>
            </div>

            <!-- List Aktivitas Feed -->
            <div class="flex-1 w-full flex flex-col bg-white/80 backdrop-blur-xl border border-white/50 rounded-[2.5rem] p-5 shadow-2xl shadow-slate-900/10 min-h-0">
                
                <div class="pb-3 border-b border-slate-200 mb-4 flex items-center gap-3 shrink-0">
                     <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                        <i class="ph-bold ph-list-dashes text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-800 leading-tight">Aktivitas Siswa</h2>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Sinkronisasi Real-Time</p>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar relative pr-2">
                    <ul id="scan-log-list" class="space-y-3 pb-4">
                        <li id="empty-log" class="flex flex-col items-center justify-center py-20 opacity-80 h-full">
                            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-3 text-slate-400 border border-slate-200 shadow-sm">
                                <i class="ph-duotone ph-list-magnifying-glass text-3xl"></i>
                            </div>
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Antrean Kosong</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Input Trap untuk Scanner -->
    <input type="text" id="scan-input" class="absolute opacity-0 -top-[9999px]" autocomplete="off" autofocus>

    <!-- ELEMEN AUDIO BEL SEKOLAH -->
    <audio id="school-bell" src="{{ asset('sounds/school-bell.mp3') }}" preload="auto"></audio>

    <!-- Modal Ekskul AlpineJS -->
    <div x-show="showExtraModal" x-transition class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" x-cloak>
        <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 w-full max-w-lg shadow-2xl relative" @click.away="closeModal()">
            <h3 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
                    <i class="ph-bold ph-trophy text-xl"></i>
                </div> 
                Pilih Ekstrakurikuler
            </h3>
            <div class="grid grid-cols-2 gap-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-2">
                @forelse($extracurriculars as $ex)
                    <button type="button" @click="selectExtra('{{ $ex->id }}', '{{ $ex->name }}')" class="p-3 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 rounded-xl text-left transition-all group shadow-sm">
                        <span class="font-bold text-slate-700 group-hover:text-rose-700 text-xs block">{{ $ex->name }}</span>
                    </button>
                @empty
                    <div class="col-span-2 text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                        <i class="ph-duotone ph-warning-circle text-4xl text-slate-400 mb-2"></i>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Data Kosong</p>
                    </div>
                @endforelse
            </div>
            <button type="button" @click="closeModal()" class="mt-6 w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors uppercase tracking-widest text-[10px] shadow-sm border border-slate-200">Batal / Tutup</button>
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
                display.textContent = `Kegiatan Terpilih: ${name}`;
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
        const BELL_SCHEDULES = {!! $bellJson !!}; // Data bel dari backend
        const bellAudio = document.getElementById('school-bell');
        let playedBells = {}; // Mencegah bunyi spam berulang di menit yang sama
        
        let currentScanMode = 'Masuk';
        let manualOverride = false;
        window.selectedExtraId = null;
        window.selectedExtraName = null;
        window.isProcessing = true; 
        
        const clockEl = document.getElementById('kiosk-clock');
        const scanInput = document.getElementById('scan-input');
        const modeBadge = document.getElementById('active-mode-badge');
        const manualIndicator = document.getElementById('manual-indicator');
        const stateResult = document.getElementById('state-result');
        const stateStandby = document.getElementById('state-standby');
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');
        const ekskulDisplay = document.getElementById('ekskul-name-display');

        const processUrl = '{{ route("kiosk.process") }}';
        let csrfToken = '{{ csrf_token() }}';

        const MODE_CONFIG = {
            'Masuk': { bg: 'bg-slate-800', text: 'text-slate-100', icon: 'ph-sun-dim', label: 'ABSEN MASUK' },
            'Pulang': { bg: 'bg-blue-600', text: 'text-slate-100', icon: 'ph-moon-stars', label: 'ABSEN PULANG' },
            'Makan': { bg: 'bg-orange-500', text: 'text-slate-100', icon: 'ph-bowl-food', label: 'AMBIL MAKAN' },
            'Dhuha': { bg: 'bg-emerald-600', text: 'text-slate-100', icon: 'ph-sun-horizon', label: 'SHOLAT DHUHA' },
            'Dhuhur': { bg: 'bg-emerald-600', text: 'text-slate-100', icon: 'ph-mosque', label: 'SHOLAT DHUHUR' },
            'Ekstrakurikuler': { bg: 'bg-rose-500', text: 'text-slate-100', icon: 'ph-basketball', label: 'EKSKUL' }
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
            
            let badgeIconColor = mode === 'Masuk' ? 'text-blue-400' : 'text-slate-200';
            modeBadge.innerHTML = `<i class="ph-fill ${config.icon} text-lg ${badgeIconColor}"></i> <span>${config.label}</span>`;
            modeBadge.className = `px-4 py-2 rounded-xl ${config.bg} ${config.text} font-bold tracking-wider uppercase text-xs shadow-md flex items-center gap-2 transition-all`;

            if(isManual) {
                manualIndicator.textContent = manualText;
                manualIndicator.classList.remove('bg-slate-100', 'text-slate-500', 'border-slate-200');
                manualIndicator.classList.add('bg-rose-50', 'text-rose-600', 'border-rose-200');
            } else {
                manualIndicator.textContent = manualText;
                manualIndicator.classList.add('bg-slate-100', 'text-slate-500', 'border-slate-200');
                manualIndicator.classList.remove('bg-rose-50', 'text-rose-600', 'border-rose-200');
            }

            document.querySelectorAll('.mode-btn, #btn-ekskul').forEach(btn => {
                if(btn.getAttribute('data-mode') === mode || (mode === 'Ekstrakurikuler' && btn.id === 'btn-ekskul')) {
                    btn.classList.remove('opacity-50', 'scale-95');
                    btn.classList.add('border-slate-900/10', 'scale-105', 'shadow-lg'); 
                } else {
                    btn.classList.add('opacity-50'); 
                    btn.classList.remove('border-slate-900/10', 'scale-105', 'shadow-lg');
                }
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

        function updateTime() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            
            // Format jam dan menit saat ini (HH:MM)
            const currentTimeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            // --- LOGIKA BEL OTOMATIS ---
            if (BELL_SCHEDULES && BELL_SCHEDULES.length > 0) {
                BELL_SCHEDULES.forEach(bell => {
                    const bellTime = bell.trigger_time.substring(0, 5); 
                    
                    if (bellTime === currentTimeStr && !playedBells[bellTime]) {
                        playedBells[bellTime] = true; 
                        
                        // Cek apakah guru mengupload audio custom
                        if (bell.audio_file) {
                            bellAudio.src = '/storage/' + bell.audio_file;
                        } else {
                            bellAudio.src = '{{ asset("sounds/school-bell.mp3") }}'; // Kembali ke default
                        }

                        bellAudio.currentTime = 0;
                        bellAudio.play().catch(e => console.log("Gagal memutar bel:", e));
                        
                        // Pengumuman suara otomatis setelah bel berbunyi (Jeda 5 detik)
                        setTimeout(() => {
                            speakSapaan(`Perhatian. Waktunya ${bell.activity_name}.`);
                        }, 5000); 
                    }
                });
            }

            // Reset reload jam 00:00 (Pergantian hari, reset juga riwayat bel)
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
            
            let borderColor, badgeBg, badgeText, iconName;
            if (type === 'error') {
                borderColor = 'border-rose-200'; badgeBg = 'bg-rose-100'; badgeText = 'text-rose-700'; iconName = 'ph-x-circle';
            } 
            else if (type === 'warning') {
                borderColor = 'border-amber-200'; badgeBg = 'bg-amber-100'; badgeText = 'text-amber-700'; iconName = 'ph-clock'; message = message || 'Terlambat';
            } 
            else if (mode === 'Pulang') {
                borderColor = 'border-blue-200'; badgeBg = 'bg-blue-50'; badgeText = 'text-blue-700'; iconName = 'ph-moon-stars'; message = 'Pulang Sukses'; 
            } 
            else if (type === 'makan') {
                borderColor = 'border-orange-200'; badgeBg = 'bg-orange-50'; badgeText = 'text-orange-700'; iconName = 'ph-bowl-food'; message = message || 'Ambil Makan';
            } 
            else {
                borderColor = 'border-emerald-200'; badgeBg = 'bg-emerald-50'; badgeText = 'text-emerald-700'; iconName = 'ph-check-circle'; message = 'Tepat Waktu';
            }

            let avatarContent = `<span class="text-xl font-black text-slate-400">${initial}</span>`;
            if (photoPath) {
                let fullUrl = photoPath.startsWith('http') ? photoPath : `/storage/${photoPath}`;
                avatarContent = `<img src="${fullUrl}" alt="${name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='<span class=\\'text-xl font-black text-slate-400\\'>${initial}</span>';">`;
            }

            li.className = `flex p-3 rounded-xl border ${borderColor} bg-white shadow-sm animate-fade-in-left transition-all justify-between items-center`;
            li.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-50 overflow-hidden flex items-center justify-center border border-slate-200">
                        ${avatarContent}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <p class="text-slate-800 font-bold truncate text-xs leading-tight">${name}</p>
                        <div class="flex items-center mt-1">
                            <span class="text-[9px] font-bold ${badgeBg} ${badgeText} px-2 py-0.5 rounded flex items-center gap-1 uppercase tracking-wide">
                                ${message} <i class="ph-fill ${iconName}"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 pl-2">
                    <div class="text-slate-500 text-[9px] font-bold tracking-widest bg-slate-50 px-2 py-1 rounded border border-slate-200">
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
            
            stateResult.innerHTML = `
                <div class="relative w-20 h-20 md:w-24 md:h-24 flex items-center justify-center mb-6">
                    <div class="absolute inset-0 border-4 border-slate-200 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <i class="ph-duotone ${config.icon} text-2xl md:text-3xl text-blue-600"></i>
                    </div>
                </div>
                <p class="text-sm md:text-base font-black tracking-widest uppercase text-slate-800 animate-pulse">MEMPROSES DATA...</p>
            `;
            stateResult.className = `absolute inset-0 z-30 w-full h-full rounded-[2rem] flex flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 bg-white/95 backdrop-blur-md`;

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
                        <div class="bg-rose-50 p-4 md:p-5 rounded-[2rem] mb-4 border border-rose-200"><i class="ph-bold ph-warning-circle text-5xl md:text-6xl text-rose-500"></i></div>
                        <h2 class="text-xl md:text-2xl font-black text-rose-700 text-center mb-2 tracking-tight uppercase">SESI BERAKHIR</h2>
                        <p class="text-slate-500 text-xs md:text-sm mb-6 font-bold">Harap muat ulang halaman</p>
                        <button onclick="window.location.reload()" class="px-6 md:px-8 py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl shadow-lg transition-colors uppercase tracking-widest text-[10px] md:text-xs">
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
                    
                    let bgClass = isLate ? "bg-amber-50" : (currentScanMode === 'Pulang' ? "bg-blue-50" : "bg-emerald-50");
                    let borderClass = isLate ? "border-amber-200" : (currentScanMode === 'Pulang' ? "border-blue-200" : "border-emerald-200");
                    let textClass = isLate ? "text-amber-700" : (currentScanMode === 'Pulang' ? "text-blue-700" : "text-emerald-700");
                    let iconClass = isLate ? "ph-warning-circle" : "ph-check-circle";
                    let iconBgClass = isLate ? "bg-amber-100" : (currentScanMode === 'Pulang' ? "bg-blue-100" : "bg-emerald-100");
                    
                    showResultUI(bgClass, borderClass, textClass, iconClass, iconBgClass, result.student_name, result.message);
                    addToLog(result.student_name, statusType, result.message, displayTime, currentScanMode, result.photo_path);

                } else {
                    playBeep('error');
                    speakSapaan(response.status === 404 ? 'Kartu tidak terdaftar.' : `Maaf, ${result.message}`);
                    
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    showResultUI("bg-rose-50", "border-rose-200", "text-rose-700", "ph-x-circle", "bg-rose-100", result.student_name || "Siswa Tidak Dikenal", errorMsg);
                    addToLog(result.student_name || "Gagal Scan", 'error', errorMsg, displayTime, currentScanMode, result.photo_path);
                }

            } catch (error) {
                console.error(error);
                playBeep('error');
                showResultUI("bg-rose-50", "border-rose-200", "text-rose-700", "ph-warning-octagon", "bg-rose-100", "SYSTEM ERROR", "Gagal Menghubungi Server");
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
            stateResult.className = `absolute inset-0 z-30 w-full h-full flex flex-col items-center justify-center p-6 transition-all duration-300 ${bgClass} rounded-[2rem] border-2 ${borderClass}`;
            
            stateResult.innerHTML = `
                <div class="flex flex-col items-center animate-bounce-in text-center w-full">
                    <div class="w-24 h-24 md:w-28 md:h-28 ${iconBgClass} rounded-full flex items-center justify-center mb-6 shadow-sm border border-white/50">
                        <i class="ph-fill ${iconClass} text-5xl md:text-6xl ${textClass}"></i>
                    </div>
                    <h2 class="text-2xl md:text-4xl font-black text-slate-800 leading-tight mb-3 w-full truncate px-4">${name || 'Siswa'}</h2>
                    <p class="text-[10px] md:text-xs font-bold ${textClass} px-5 py-2 bg-white/60 backdrop-blur-sm rounded-full shadow-sm border border-white/50 uppercase tracking-widest">${message}</p>
                </div>
            `;
        }
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    @keyframes bounceIn { 0% { transform: scale(0.9); opacity: 0; } 50% { transform: scale(1.02); opacity: 1; } 100% { transform: scale(1); } }
    .animate-bounce-in { animation: bounceIn 0.3s cubic-bezier(0.215, 0.610, 0.355, 1.000); }
    
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in-left { animation: fadeInLeft 0.3s ease-out forwards; }
    
    [x-cloak] { display: none !important; }
    :fullscreen header, :fullscreen nav { display: none !important; }
</style>
@endsection