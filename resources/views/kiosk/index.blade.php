@extends('layouts.kiosk-layout')

@section('content')
@php
    $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
    $scheduleJson = json_encode($safeSchedule);
    
    $extracurriculars = isset($extracurriculars) ? $extracurriculars : [];
    $extraJson = json_encode($extracurriculars);

    $learningSchedules = isset($learningSchedules) ? $learningSchedules : [];
    $bellJson = json_encode($learningSchedules);

    $isBellActive = \Illuminate\Support\Facades\Cache::get('is_bell_active', true);
    $isHoliday = isset($isHoliday) ? $isHoliday : false;
    $holidayReason = isset($holidayReason) ? $holidayReason : null;
@endphp

<!-- LAYER START KIOSK -->
<div id="start-overlay" class="fixed inset-0 z-[100] bg-slate-950 flex flex-col items-center justify-center cursor-pointer transition-all duration-700 backdrop-blur-md" onclick="startKiosk()">
    <div class="relative mb-8 group animate-float">
        <div class="absolute inset-0 bg-blue-500/30 blur-[60px] rounded-full animate-pulse transition-all duration-500 group-hover:bg-blue-500/50 group-hover:scale-125"></div>
        <div class="w-36 h-36 bg-slate-900/90 backdrop-blur-xl rounded-full border border-blue-500/40 flex items-center justify-center relative z-10 shadow-[0_0_40px_rgba(59,130,246,0.3)] group-hover:scale-110 transition-transform duration-500">
            <i class="ph-bold ph-power text-7xl text-blue-400 drop-shadow-[0_0_15px_rgba(59,130,246,0.8)] group-hover:text-white transition-colors"></i>
        </div>
    </div>
    <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-5 uppercase drop-shadow-2xl">SISTEM KIOSK</h1>
    <p class="text-blue-300 font-bold uppercase tracking-widest text-xs bg-blue-900/40 px-8 py-3.5 rounded-full border border-blue-500/30 shadow-[0_0_20px_rgba(59,130,246,0.2)] animate-pulse hover:scale-105 transition-transform cursor-pointer">Ketuk Layar Untuk Memulai</p>
</div>

<!-- CONTAINER UTAMA -->
<div class="h-screen w-full bg-slate-950 relative overflow-hidden font-sans text-slate-300 selection:bg-blue-500 selection:text-white flex flex-col" x-data="kioskData()" @open-ekskul-modal.window="openExtraModal()">
    
    <!-- Background Texture & Glow -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[url('{{ asset('images/netila.jpg') }}')] bg-cover bg-center bg-no-repeat opacity-10 mix-blend-luminosity"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900/95 to-slate-950 backdrop-blur-[4px]"></div>
        
        <!-- Glowing Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-600/10 rounded-full blur-[120px] mix-blend-screen animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[60%] bg-indigo-600/10 rounded-full blur-[120px] mix-blend-screen animate-pulse-slow delay-1000"></div>
        
        <!-- Grid Pattern overlay -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>
    </div>

    <!-- MAIN WRAPPER -->
    <div class="max-w-[1500px] w-full mx-auto flex flex-col lg:flex-row flex-1 min-h-0 p-4 md:p-6 lg:p-8 gap-6 lg:gap-8 relative z-10">
        
        <!-- BAGIAN KIRI: KONTROL & SCANNER -->
        <div class="flex-1 flex flex-col w-full relative min-h-0 bg-slate-900/60 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] shadow-2xl shadow-black/50 overflow-hidden p-6 lg:p-8 gap-6 animate-enter-left">
            
            <!-- Header -->
            <div class="flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 shadow-inner">
                        <x-application-logo class="w-12 h-12 text-white drop-shadow-md fill-current" />
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight drop-shadow-sm">SMP Negeri 3 Lakbok</h1>
                        <div class="flex items-center gap-2 mt-1 bg-slate-800/50 border border-slate-700/50 w-fit px-3 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse shadow-[0_0_8px_rgba(59,130,246,0.8)]"></span>
                            <p class="text-blue-400 font-bold tracking-widest text-[9px] uppercase">Smart Attendance Kiosk</p>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="exitKiosk()" class="w-12 h-12 flex items-center justify-center bg-white/5 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 rounded-2xl border border-white/10 hover:border-rose-500/50 hover:scale-105 hover:shadow-lg hover:shadow-rose-500/20 transition-all duration-300" title="Keluar Kiosk">
                    <i class="ph-bold ph-power text-xl"></i>
                </button>
            </div>

            <!-- PANEL SCANNER -->
            <div class="w-full flex-1 min-h-0 flex flex-col relative">
                
                <div class="flex justify-between items-center mb-4 px-1 shrink-0">
                    <div id="active-mode-badge" class="px-5 py-2.5 rounded-xl bg-blue-500/20 border border-blue-500/40 text-blue-400 font-black tracking-widest uppercase text-xs flex items-center gap-2 shadow-[0_0_20px_rgba(59,130,246,0.2)] transition-all">
                        <i class="ph-fill ph-sun-dim text-lg"></i> <span>ABSEN MASUK</span>
                    </div>
                    <span class="text-[9px] bg-slate-800 text-slate-400 border border-slate-700 px-3 py-1.5 rounded-lg font-bold uppercase tracking-widest transition-colors shadow-inner" id="manual-indicator">Auto Mode</span>
                </div>

                <!-- BOX SCANNER TARGET -->
                <div id="status-box" class="w-full flex-1 min-h-0 relative bg-slate-950/70 rounded-[2rem] border border-slate-700/50 shadow-inner overflow-hidden flex flex-col justify-center items-center group">
                    
                    <!-- Scanner decorative corners (Thicker & brighter) -->
                    <div class="absolute top-6 left-6 w-10 h-10 border-t-4 border-l-4 border-blue-500/60 rounded-tl-2xl transition-colors duration-300 group-hover:border-blue-400"></div>
                    <div class="absolute top-6 right-6 w-10 h-10 border-t-4 border-r-4 border-blue-500/60 rounded-tr-2xl transition-colors duration-300 group-hover:border-blue-400"></div>
                    <div class="absolute bottom-6 left-6 w-10 h-10 border-b-4 border-l-4 border-blue-500/60 rounded-bl-2xl transition-colors duration-300 group-hover:border-blue-400"></div>
                    <div class="absolute bottom-6 right-6 w-10 h-10 border-b-4 border-r-4 border-blue-500/60 rounded-br-2xl transition-colors duration-300 group-hover:border-blue-400"></div>
                    
                    <!-- Scanning Laser Line Animation -->
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-blue-400/90 shadow-[0_0_20px_2px_#60a5fa] animate-scan-line z-0"></div>

                    <!-- STANDBY STATE -->
                    <div id="state-standby" class="flex flex-col items-center justify-center p-6 relative z-10 transition-all duration-300 w-full">
                        <div class="w-28 h-28 mb-8 relative group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-blue-500/30 blur-2xl rounded-full group-hover:bg-blue-500/40 transition-colors"></div>
                            <div class="w-full h-full bg-slate-800/80 backdrop-blur-md rounded-3xl border-2 border-slate-600 shadow-2xl flex items-center justify-center relative">
                                <i class="ph-duotone ph-scan text-6xl text-blue-400 drop-shadow-md group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black text-white tracking-widest text-center drop-shadow-lg group-hover:text-blue-200 transition-colors">SIAP SCAN KARTU</h2>
                        <p class="text-slate-400 text-sm mt-3 font-bold tracking-wide text-center bg-slate-900/50 px-4 py-1.5 rounded-full border border-slate-800">Tempelkan kartu identitas (RFID) pada area scanner</p>
                        <p id="ekskul-name-display" class="hidden text-rose-400 mt-5 font-black text-xs bg-rose-500/10 border border-rose-500/30 px-6 py-2.5 rounded-full uppercase tracking-widest shadow-[0_0_20px_rgba(225,29,72,0.15)]"></p>
                    </div>

                    <!-- RESULT STATE -->
                    <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full rounded-[2rem] flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 bg-slate-900/95 backdrop-blur-2xl border border-slate-700/50">
                        <!-- Injected by JS -->
                    </div>
                </div>

                <!-- MODE SELECTOR -->
                <div class="mt-6 w-full relative z-20 shrink-0">
                    <div class="grid grid-cols-3 lg:grid-cols-6 gap-3">
                        @foreach([
                            ['label' => 'Masuk (F1)', 'type' => 'Masuk', 'color' => 'blue'],
                            ['label' => 'Pulang (F2)', 'type' => 'Pulang', 'color' => 'indigo'],
                            ['label' => 'Makan (F3)', 'type' => 'Makan', 'color' => 'orange'],
                            ['label' => 'Dhuha (F4)', 'type' => 'Dhuha', 'color' => 'emerald'],
                            ['label' => 'Dhuhur (F5)', 'type' => 'Dhuhur', 'color' => 'teal'],
                        ] as $btn)
                        <button type="button" data-mode="{{ $btn['type'] }}" data-color="{{ $btn['color'] }}" 
                                class="mode-btn group bg-slate-800/60 border border-slate-700 hover:bg-slate-700 hover:border-{{ $btn['color'] }}-500/50 py-3.5 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1 focus:outline-none focus:ring-2 focus:ring-{{ $btn['color'] }}-500/40">
                            <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-{{ $btn['color'] }}-300 transition-colors">{{ $btn['label'] }}</span>
                        </button>
                        @endforeach
                        
                        <button type="button" id="btn-ekskul" data-color="rose"
                                class="group bg-slate-800/60 border border-slate-700 hover:bg-slate-700 hover:border-rose-500/50 py-3.5 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1 focus:outline-none focus:ring-2 focus:ring-rose-500/40">
                            <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-rose-300 transition-colors">Ekskul (F6)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: JAM & LOG -->
        <div class="w-full lg:w-[480px] flex flex-col shrink-0 min-h-0 h-full gap-6 animate-enter-right">
            
            <!-- Digital Clock -->
            <div class="w-full bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 rounded-[2.5rem] p-8 shadow-[0_20px_50px_rgba(30,58,138,0.5)] flex flex-col justify-center items-center relative overflow-hidden shrink-0 border border-blue-400/30">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.1] mix-blend-overlay"></div>
                <!-- Animated Background Glow -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/15 rounded-full blur-3xl transform translate-x-1/3 -translate-y-1/3"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-400/20 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>
                
                <span id="kiosk-clock" class="text-7xl lg:text-8xl font-black text-white font-mono tracking-tighter drop-shadow-2xl relative z-10">00:00:00</span>
                <p id="kiosk-date" class="text-blue-200 font-bold tracking-widest text-xs uppercase mt-3 relative z-10 bg-black/20 px-4 py-1.5 rounded-full backdrop-blur-sm border border-blue-400/20"></p>
            </div>

            <!-- Unified Panel: Jadwal & Log -->
            <div class="flex-1 w-full flex flex-col bg-slate-900/60 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-6 lg:p-8 shadow-2xl shadow-black/50 min-h-0 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/5 rounded-full blur-3xl transform translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>

                <!-- Info Jadwal Bel -->
                <div class="shrink-0 mb-6 relative z-10">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="ph-fill ph-bell-ringing text-lg text-emerald-500"></i> Informasi Jadwal
                    </h3>
                    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 md:p-5 flex flex-col gap-4 shadow-inner">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1 drop-shadow-sm">Sedang Berlangsung</p>
                                <h4 id="current-period-name" class="text-base font-black text-white truncate drop-shadow-md">Memuat...</h4>
                            </div>
                        </div>
                        <div class="w-full h-[1px] bg-gradient-to-r from-slate-700/20 via-slate-700 to-slate-700/20"></div>
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Selanjutnya</p>
                                <h4 id="next-period-name" class="text-sm font-bold text-slate-300 truncate">Memuat...</h4>
                            </div>
                            <div id="next-period-countdown" class="text-[11px] font-bold text-blue-300 bg-blue-900/40 px-3 py-1.5 rounded-xl border border-blue-500/30 shrink-0 shadow-[0_0_10px_rgba(59,130,246,0.1)]">--:--</div>
                        </div>
                    </div>
                </div>

                <div class="w-full h-[1px] bg-white/10 shrink-0 mb-6"></div>

                <!-- List Aktivitas Feed -->
                <div class="flex-1 flex flex-col min-h-0 relative z-10">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center justify-between">
                        <span class="flex items-center gap-2 text-white"><i class="ph-bold ph-list-dashes text-lg text-blue-400"></i> Live Activity Log</span>
                        <span class="flex items-center gap-2 bg-slate-800 px-2.5 py-1 rounded-md border border-slate-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span> <span class="text-[9px]">Sinkron</span></span>
                    </h3>

                    <div class="flex-1 overflow-y-auto custom-scrollbar relative pr-3">
                        <ul id="scan-log-list" class="space-y-3 pb-4">
                            <li id="empty-log" class="flex flex-col items-center justify-center py-12 opacity-60 h-full">
                                <div class="w-16 h-16 bg-slate-800/80 rounded-full flex items-center justify-center mb-4 text-slate-500 border border-slate-700 shadow-inner">
                                    <i class="ph-duotone ph-ghost text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Belum ada aktivitas</p>
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

    <!-- Modal Ekskul AlpineJS -->
    <div x-show="showExtraModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 backdrop-blur-none" x-transition:enter-end="opacity-100 backdrop-blur-md" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 backdrop-blur-md" x-transition:leave-end="opacity-0 backdrop-blur-none" class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-slate-950/80" x-cloak>
        <div x-show="showExtraModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95" class="bg-slate-900 rounded-[2.5rem] border border-slate-700 p-8 w-full max-w-lg shadow-2xl shadow-black/80 relative overflow-hidden" @click.away="closeModal()">
            
            <div class="absolute top-0 right-0 w-40 h-40 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
            
            <h3 class="text-2xl font-black text-white mb-6 flex items-center gap-3 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/30 shadow-[0_0_15px_rgba(225,29,72,0.2)]">
                    <i class="ph-bold ph-trophy text-2xl"></i>
                </div> 
                Pilih Ekstrakurikuler
            </h3>
            
            <div class="grid grid-cols-2 gap-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 relative z-10">
                @forelse($extracurriculars as $ex)
                    <button type="button" @click="selectExtra('{{ $ex->id }}', '{{ $ex->name }}')" class="p-4 bg-slate-800/80 hover:bg-rose-500/10 border border-slate-700 hover:border-rose-500/50 rounded-xl text-left transition-all duration-300 group shadow-sm hover:shadow-[0_0_20px_rgba(225,29,72,0.15)] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-rose-500/50">
                        <span class="font-bold text-slate-300 group-hover:text-rose-400 text-xs block transition-colors">{{ $ex->name }}</span>
                    </button>
                @empty
                    <div class="col-span-2 text-center py-12 bg-slate-800/50 rounded-2xl border border-dashed border-slate-700 shadow-inner">
                        <i class="ph-duotone ph-warning-circle text-5xl text-slate-500 mb-3"></i>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Data Kosong</p>
                    </div>
                @endforelse
            </div>
            
            <button type="button" @click="closeModal()" class="mt-8 w-full py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl transition-all duration-300 uppercase tracking-widest text-[10px] shadow-md border border-slate-600 relative z-10 focus:outline-none focus:ring-2 focus:ring-slate-500">
                Batal / Tutup
            </button>
        </div>
    </div>

</div>

<!-- LOGIKA JAVASCRIPT & ALPINE.JS (TETAP SAMA 100%) -->
<script>
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

    function startKiosk() {
        const overlay = document.getElementById('start-overlay');
        overlay.style.opacity = '0';
        setTimeout(() => overlay.style.display = 'none', 700);

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
        const IS_HOLIDAY = {{ $isHoliday ? 'true' : 'false' }};
        const HOLIDAY_REASON = {!! json_encode($holidayReason) !!};

        let serverTimeOffset = 0; 
        async function syncServerTime() {
            try {
                const t0 = Date.now();
                const res = await fetch('{{ route('kiosk.server-time') }}', { cache: 'no-store' });
                const data = await res.json();
                const t1 = Date.now();
                const serverTime = new Date(data.time).getTime();
                const roundTrip = t1 - t0;
                serverTimeOffset = (serverTime + roundTrip / 2) - t1;
            } catch (e) {
                console.warn('Gagal sinkron jam server, pakai jam device.', e);
            }
        }
        function getServerNow() {
            return new Date(Date.now() + serverTimeOffset);
        }
        
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
            
            modeBadge.innerHTML = `<i class="ph-fill ${config.icon} text-lg"></i> <span>${config.label}</span>`;
            modeBadge.className = `px-5 py-2.5 rounded-xl bg-${color}-500/20 border border-${color}-500/40 text-${color}-400 font-black tracking-widest uppercase text-xs flex items-center gap-2 shadow-[0_0_20px_rgba(var(--tw-colors-${color}-500),0.2)] transition-all`;

            if(isManual) {
                manualIndicator.textContent = manualText;
                manualIndicator.className = 'text-[9px] bg-rose-500/20 text-rose-400 border border-rose-500/40 px-3 py-1.5 rounded-lg font-bold uppercase tracking-widest transition-colors shadow-[0_0_15px_rgba(225,29,72,0.2)]';
            } else {
                manualIndicator.textContent = manualText;
                manualIndicator.className = 'text-[9px] bg-slate-800 text-slate-400 border border-slate-700 px-3 py-1.5 rounded-lg font-bold uppercase tracking-widest transition-colors shadow-inner';
            }

            document.querySelectorAll('.mode-btn, #btn-ekskul').forEach(btn => {
                const btnColor = btn.getAttribute('data-color');
                const btnMode = btn.getAttribute('data-mode');
                
                if(btnMode === mode || (mode === 'Ekstrakurikuler' && btn.id === 'btn-ekskul')) {
                    btn.className = `mode-btn group bg-${btnColor}-600 border border-${btnColor}-400 hover:bg-${btnColor}-500 py-3.5 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1 shadow-[0_0_25px_rgba(var(--tw-colors-${btnColor}-500),0.5)] transform scale-[1.03] z-10 focus:outline-none focus:ring-2 focus:ring-${btnColor}-400`;
                    btn.querySelector('span').className = `text-[9px] md:text-[10px] font-black uppercase tracking-widest text-white drop-shadow-md`;
                } else {
                    btn.className = `mode-btn group bg-slate-800/60 border border-slate-700 hover:bg-slate-700 hover:border-${btnColor}-500/50 py-3.5 rounded-xl transition-all duration-300 flex flex-col items-center justify-center text-center px-1 opacity-70 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-${btnColor}-500/40`;
                    btn.querySelector('span').className = `text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-${btnColor}-300 transition-colors`;
                }
                
                if(btn.id === 'btn-ekskul') btn.classList.remove('mode-btn'); 
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

        function updateLearningPeriodInfo(nowMinutes) {
            if (!currentPeriodEl || !nextPeriodEl) return;

            if (IS_HOLIDAY) {
                currentPeriodEl.textContent = 'Hari Libur';
                nextPeriodEl.textContent = HOLIDAY_REASON || 'Tidak ada jadwal hari ini';
                nextCountdownEl.textContent = '-';
                return;
            }

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
            const now = getServerNow();
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.textContent = now.toLocaleDateString('id-ID', options);
            
            const currentTimeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            const nowMinutes = now.getHours() * 60 + now.getMinutes();
            updateLearningPeriodInfo(nowMinutes);
            
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
        
        syncServerTime().then(() => {
            updateTime();
        });
        setInterval(updateTime, 1000);
        setInterval(syncServerTime, 5 * 60 * 1000); 
        setInterval(autoSelectMode, 30000); 
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
                borderColor = 'border-rose-500/40'; badgeBg = 'bg-rose-500/20'; badgeText = 'text-rose-400'; iconName = 'ph-x-circle';
            } 
            else if (type === 'warning') {
                borderColor = 'border-amber-500/40'; badgeBg = 'bg-amber-500/20'; badgeText = 'text-amber-400'; iconName = 'ph-clock'; message = message || 'Terlambat';
            } 
            else if (mode === 'Pulang') {
                borderColor = 'border-indigo-500/40'; badgeBg = 'bg-indigo-500/20'; badgeText = 'text-indigo-400'; iconName = 'ph-moon-stars'; message = 'Pulang Sukses'; 
            } 
            else if (type === 'makan') {
                borderColor = 'border-orange-500/40'; badgeBg = 'bg-orange-500/20'; badgeText = 'text-orange-400'; iconName = 'ph-bowl-food'; message = message || 'Ambil Makan';
            } 
            else {
                borderColor = 'border-emerald-500/40'; badgeBg = 'bg-emerald-500/20'; badgeText = 'text-emerald-400'; iconName = 'ph-check-circle'; message = 'Tepat Waktu';
            }

            let avatarContent = `<span class="text-xl font-black text-slate-500">${initial}</span>`;
            if (photoPath) {
                let fullUrl = photoPath.startsWith('http') ? photoPath : `/storage/${photoPath}`;
                avatarContent = `<img src="${fullUrl}" alt="${name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='<span class=\\'text-xl font-black text-slate-500\\'>${initial}</span>';">`;
            }

            li.className = `flex p-3 rounded-2xl border ${borderColor} bg-slate-800/80 shadow-md animate-fade-in-left transition-all justify-between items-center backdrop-blur-md`;
            li.innerHTML = `
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-slate-700 overflow-hidden flex items-center justify-center border-2 border-slate-600 shadow-inner">
                        ${avatarContent}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <p class="text-white font-bold truncate text-sm leading-tight">${name}</p>
                        <div class="flex items-center mt-1.5">
                            <span class="text-[9px] font-black ${badgeBg} ${badgeText} px-2.5 py-1 rounded-md border border-current flex items-center gap-1.5 uppercase tracking-wider shadow-sm">
                                ${message} <i class="ph-fill ${iconName}"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 pl-2">
                    <div class="text-slate-400 text-[10px] font-bold tracking-widest bg-slate-900/80 px-2.5 py-1.5 rounded-lg border border-slate-700 shadow-inner">
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
            
            stateResult.innerHTML = `
                <div class="relative w-28 h-28 flex items-center justify-center mb-6">
                    <div class="absolute inset-0 border-4 border-slate-700/50 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-${clr}-500 border-t-transparent rounded-full animate-spin"></div>
                    <div class="absolute inset-0 bg-${clr}-500/10 blur-xl rounded-full animate-pulse"></div>
                    <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center shadow-xl border border-slate-600 relative z-10">
                        <i class="ph-duotone ${config.icon} text-4xl text-${clr}-400"></i>
                    </div>
                </div>
                <p class="text-sm font-black tracking-widest uppercase text-${clr}-300 animate-pulse drop-shadow-md">MEMPROSES DATA...</p>
            `;
            stateResult.className = `absolute inset-0 z-30 w-full h-full flex flex-col items-center justify-center overflow-hidden p-6 transition-all duration-300 bg-slate-900/95 backdrop-blur-xl`;

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
                        <div class="bg-rose-500/20 p-6 rounded-full mb-5 border border-rose-500/40 shadow-[0_0_40px_rgba(225,29,72,0.4)]">
                            <i class="ph-bold ph-warning-circle text-7xl text-rose-500"></i>
                        </div>
                        <h2 class="text-3xl font-black text-rose-400 text-center mb-3 tracking-widest uppercase drop-shadow-md">SESI BERAKHIR</h2>
                        <p class="text-slate-300 text-sm mb-8 font-bold">Harap muat ulang halaman Kiosk</p>
                        <button onclick="window.location.reload()" class="px-10 py-4 bg-rose-600 hover:bg-rose-500 text-white font-black rounded-xl shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all hover:scale-105 uppercase tracking-widest text-xs">
                            <i class="ph-bold ph-arrows-clockwise mr-2"></i> Muat Ulang Sistem
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
                    
                    let bgClass = isLate ? "bg-amber-900/70" : (currentScanMode === 'Pulang' ? "bg-indigo-900/70" : "bg-emerald-900/70");
                    let borderClass = isLate ? "border-amber-500/60 shadow-[0_0_50px_rgba(245,158,11,0.3)]" : (currentScanMode === 'Pulang' ? "border-indigo-500/60 shadow-[0_0_50px_rgba(99,102,241,0.3)]" : "border-emerald-500/60 shadow-[0_0_50px_rgba(16,185,129,0.3)]");
                    let textClass = isLate ? "text-amber-400" : (currentScanMode === 'Pulang' ? "text-indigo-400" : "text-emerald-400");
                    let iconClass = isLate ? "ph-warning-circle" : "ph-check-circle";
                    let iconBgClass = isLate ? "bg-amber-500/20 border-amber-500/40" : (currentScanMode === 'Pulang' ? "bg-indigo-500/20 border-indigo-500/40" : "bg-emerald-500/20 border-emerald-500/40");
                    
                    showResultUI(bgClass, borderClass, textClass, iconClass, iconBgClass, result.student_name, result.message);
                    addToLog(result.student_name, statusType, result.message, displayTime, currentScanMode, result.photo_path);

                } else {
                    playBeep('error');
                    speakSapaan(response.status === 404 ? 'Kartu tidak terdaftar.' : `Maaf, ${result.message}`);
                    
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    showResultUI("bg-rose-900/70", "border-rose-500/60 shadow-[0_0_50px_rgba(225,29,72,0.3)]", "text-rose-400", "ph-x-circle", "bg-rose-500/20 border-rose-500/40", result.student_name || "Siswa Tidak Dikenal", errorMsg);
                    addToLog(result.student_name || "Gagal Scan", 'error', errorMsg, displayTime, currentScanMode, result.photo_path);
                }

            } catch (error) {
                console.error(error);
                playBeep('error');
                showResultUI("bg-rose-900/70", "border-rose-500/60", "text-rose-400", "ph-warning-octagon", "bg-rose-500/20 border-rose-500/40", "SYSTEM ERROR", "Gagal Menghubungi Server");
            } finally {
                setTimeout(() => {
                    stateResult.classList.add('hidden'); 
                    stateResult.classList.remove('flex');
                    stateStandby.classList.remove('hidden');
                    window.isProcessing = false; 
                    focusInput();
                }, 2800);
            }
        }

        function showResultUI(bgClass, borderClass, textClass, iconClass, iconBgClass, name, message) {
            stateResult.className = `absolute inset-0 z-30 w-full h-full flex flex-col items-center justify-center p-6 transition-all duration-300 ${bgClass} rounded-[2rem] border-2 ${borderClass} backdrop-blur-2xl`;
            
            stateResult.innerHTML = `
                <div class="flex flex-col items-center animate-bounce-in text-center w-full">
                    <div class="relative w-32 h-32 mb-8">
                        <div class="absolute inset-0 ${iconBgClass} rounded-full animate-pulse opacity-50 blur-xl"></div>
                        <div class="w-full h-full ${iconBgClass} rounded-full flex items-center justify-center border-2 relative z-10 shadow-lg">
                            <i class="ph-fill ${iconClass} text-7xl ${textClass} drop-shadow-md"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-5 w-full truncate px-4 drop-shadow-xl tracking-tight">${name || 'Siswa'}</h2>
                    <p class="text-xs md:text-sm font-black ${textClass} px-8 py-3 bg-slate-950/70 rounded-full border-2 border-current uppercase tracking-widest shadow-inner">${message}</p>
                </div>
            `;
        }
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(71, 85, 105, 0.6); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.9); }
    
    @keyframes scanLine {
        0%, 100% { top: 5%; opacity: 0; }
        10%, 90% { opacity: 1; }
        50% { top: 95%; }
    }
    .animate-scan-line { animation: scanLine 3.5s ease-in-out infinite; }
    
    @keyframes bounceIn { 0% { transform: scale(0.85); opacity: 0; } 60% { transform: scale(1.05); opacity: 1; } 100% { transform: scale(1); } }
    .animate-bounce-in { animation: bounceIn 0.4s cubic-bezier(0.215, 0.610, 0.355, 1.000) forwards; }
    
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in-left { animation: fadeInLeft 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes enterLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
    .animate-enter-left { animation: enterLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    @keyframes enterRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    .animate-enter-right { animation: enterRight 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    @keyframes pulseSlow { 0%, 100% { opacity: 0.3; transform: scale(1); } 50% { opacity: 0.6; transform: scale(1.05); } }
    .animate-pulse-slow { animation: pulseSlow 4s ease-in-out infinite; }
    
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .animate-float { animation: float 3s ease-in-out infinite; }

    [x-cloak] { display: none !important; }
    :fullscreen header, :fullscreen nav { display: none !important; }
</style>
@endsection