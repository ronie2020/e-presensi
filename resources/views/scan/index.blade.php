<x-app-layout>
    {{-- 1. LIBRARY PENDUKUNG --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 2. CUSTOM CSS --}}
    @push('styles')
    <style>
        /* Animasi Scanner */
        .scanner-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            border: 2px solid rgba(59, 130, 246, 0.5); border-radius: 1.5rem; 
            pointer-events: none; overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        
        /* Efek Flash saat Scan */
        .scan-success-effect { box-shadow: inset 0 0 50px rgba(34, 197, 94, 0.8); border-color: #22c55e; }
        .scan-warning-effect { box-shadow: inset 0 0 50px rgba(245, 158, 11, 0.8); border-color: #f59e0b; }
        .scan-error-effect { box-shadow: inset 0 0 50px rgba(239, 68, 68, 0.8); border-color: #ef4444; }

        .scanner-line {
            position: absolute; width: 100%; height: 4px;
            background: linear-gradient(to right, transparent, #3b82f6, transparent);
            top: 0; animation: scanMove 2s infinite linear;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.8);
        }
        @keyframes scanMove {
            0% { top: 0; opacity: 0; }
            50% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        
        /* Utility */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; } 
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .hidden-col { display: none !important; }
        .hidden-row { display: none !important; }
        
        @keyframes highlightRow { 0% { background-color: #dcfce7; } 100% { background-color: transparent; } }
        .new-row-entry { animation: highlightRow 2s ease-out; }

        /* Holiday Overlay */
        .holiday-overlay {
            position: absolute; inset: 0; z-index: 50;
            background: rgba(255, 255, 255, 0.95);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            border-radius: 1.5rem;
        }
    </style>
    @endpush

    @php
        // Ambil data jadwal dari controller
        $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
        $scheduleJson = json_encode($safeSchedule);
    @endphp

    <div class="py-8 font-sans text-slate-800" onclick="initAudio()"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO BANNER & CLOCK (Updated Style) --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                {{-- Dekorasi Konsisten --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-2">
                            Scan QR Aktifitas <span class="animate-pulse">📸</span>
                        </h2>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 items-center text-blue-300 text-sm font-medium">
                            @if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-200 border border-rose-500/30">
                                    <span class="w-2 h-2 bg-rose-400 rounded-full mr-2 animate-pulse"></span>
                                    {{ $scheduleConfig['description'] ?? 'Libur' }}
                                </span>
                            @else
                                <span class="opacity-90">Sistem pencatatan kehadiran & ibadah otomatis.</span>
                            @endif
                        </div>
                    </div>

                    {{-- JAM DIGITAL MODERN --}}
                    <div class="bg-slate-900/40 backdrop-blur-md border border-white/10 px-6 py-4 rounded-2xl flex items-center gap-4 shadow-lg">
                        <div class="p-3 bg-blue-600 rounded-xl text-white shadow-lg shadow-blue-900/50">
                            <i class="ph-bold ph-clock text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-300 uppercase tracking-widest">Waktu Server</p>
                            <div id="clock" class="text-3xl font-black text-white font-mono tracking-widest leading-none mt-1">
                                00:00:00
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 
                MODE SELECTION GRID (REPLACEMENT FOR TABS)
                Konsep: Grid Card seperti LMS
            --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                
                {{-- Card 1: Harian --}}
                <button id="btn-harian" data-type="Harian" class="scan-type-btn group relative bg-white p-4 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-left overflow-hidden ring-2 ring-transparent">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-calendar-check text-2xl"></i>
                        </div>
                        <div class="w-3 h-3 rounded-full border-2 border-slate-200 indicator-dot transition-all duration-300"></div>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm group-hover:text-blue-700 transition-colors">Absen Harian</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Masuk & Pulang</p>
                    <div class="absolute inset-0 border-2 border-blue-500 rounded-[2rem] opacity-0 scale-95 transition-all duration-300 active-border"></div>
                </button>

                {{-- Card 2: Dhuha --}}
                <button id="btn-dhuha" data-type="Dhuha" class="scan-type-btn group relative bg-white p-4 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-left overflow-hidden ring-2 ring-transparent">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-sun-horizon text-2xl"></i>
                        </div>
                        <div class="w-3 h-3 rounded-full border-2 border-slate-200 indicator-dot transition-all duration-300"></div>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm group-hover:text-emerald-700 transition-colors">Sholat Dhuha</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Ibadah Pagi</p>
                    <div class="absolute inset-0 border-2 border-emerald-500 rounded-[2rem] opacity-0 scale-95 transition-all duration-300 active-border"></div>
                </button>

                {{-- Card 3: Dhuhur --}}
                <button id="btn-dhuhur" data-type="Dhuhur" class="scan-type-btn group relative bg-white p-4 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-left overflow-hidden ring-2 ring-transparent">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-moon-stars text-2xl"></i>
                        </div>
                        <div class="w-3 h-3 rounded-full border-2 border-slate-200 indicator-dot transition-all duration-300"></div>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm group-hover:text-amber-700 transition-colors">Sholat Dhuhur</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Ibadah Siang</p>
                    <div class="absolute inset-0 border-2 border-amber-500 rounded-[2rem] opacity-0 scale-95 transition-all duration-300 active-border"></div>
                </button>

                {{-- Card 4: Ekskul --}}
                <button id="btn-ekskul" data-type="Ekstrakurikuler" class="scan-type-btn group relative bg-white p-4 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-left overflow-hidden ring-2 ring-transparent">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 rounded-2xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-basketball text-2xl"></i>
                        </div>
                        <div class="w-3 h-3 rounded-full border-2 border-slate-200 indicator-dot transition-all duration-300"></div>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm group-hover:text-purple-700 transition-colors">Ekstrakurikuler</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Kegiatan Sore</p>
                    <div class="absolute inset-0 border-2 border-purple-500 rounded-[2rem] opacity-0 scale-95 transition-all duration-300 active-border"></div>
                </button>
            </div>

            {{-- DROPDOWN EKSKUL (Hidden by default) --}}
            <div id="extra-selector-container" class="hidden mb-8 animate-fade-in-down">
                <div class="bg-white p-4 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col md:flex-row items-center gap-4">
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-xl">
                        <i class="ph-fill ph-trophy text-2xl"></i>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1">
                            Pilih Jenis Kegiatan
                        </label>
                        <select id="extra-activity-select" class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition duration-200 font-bold text-slate-700 h-12">
                            <option value="">-- Pilih Ekstrakurikuler --</option>
                            @if(isset($extracurriculars))
                                @foreach($extracurriculars as $ekskul)
                                    <option value="{{ $ekskul->name }}">{{ $ekskul->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- LEFT: CAMERA CARD --}}
                <div class="lg:col-span-5 flex flex-col">
                    <div class="bg-white rounded-[2rem] p-4 shadow-xl shadow-slate-200/60 border border-slate-100 h-fit">
                        
                        {{-- Status Header --}}
                        <div class="flex justify-between items-center mb-4 px-2">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-camera text-blue-600 text-xl"></i> Kamera
                            </h3>
                            
                            {{-- Indikator Mode Aktif --}}
                            <div id="mode-badge" class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span id="mode-text">Standby</span>
                            </div>
                        </div>

                        {{-- Camera Viewport --}}
                        <div class="relative bg-slate-900 rounded-[1.5rem] overflow-hidden aspect-square border-[4px] border-slate-900 shadow-inner group">
                            
                            {{-- Holiday Overlay --}}
                            @if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false))
                            <div class="holiday-overlay text-center p-6">
                                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-4">
                                    <i class="ph-duotone ph-prohibit text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-black text-slate-800 mb-2">Scanner Nonaktif</h3>
                                <p class="text-slate-500 font-medium text-sm">{{ $scheduleConfig['description'] ?? 'Hari Libur' }}</p>
                            </div>
                            @endif

                            <div id="qr-reader" class="w-full h-full object-cover"></div>
                            <div id="scanner-overlay-el" class="scanner-overlay z-20"><div class="scanner-line"></div></div>
                            
                            {{-- Status Text Floating --}}
                            <div class="absolute bottom-6 left-0 right-0 flex justify-center z-30 px-6">
                                <div id="scan-status" class="bg-white/90 backdrop-blur-md text-slate-800 text-xs py-2.5 px-6 rounded-full font-bold border border-white/20 shadow-lg flex items-center gap-2">
                                    <i class="ph-bold ph-circle-notch animate-spin text-blue-600"></i>
                                    Menunggu Kamera...
                                </div>
                            </div>
                        </div>

                        {{-- Scan Result Feedback --}}
                        <div id="scan-result" class="mt-4 p-4 rounded-2xl font-bold text-sm text-center hidden transition-all duration-500 shadow-sm transform scale-95 opacity-0 border border-transparent"></div>
                        
                        {{-- Reset Auto Button --}}
                        <button id="btn-reset-auto" class="hidden w-full mt-4 py-3 rounded-xl border-2 border-dashed border-blue-200 text-blue-600 font-bold text-sm hover:bg-blue-50 transition-colors flex items-center justify-center gap-2" onclick="resetAutoMode()">
                            <i class="ph-bold ph-arrows-clockwise"></i> Kembali ke Mode Otomatis
                        </button>
                    </div>
                </div>

                {{-- RIGHT: LOG CARD --}}
                <div class="lg:col-span-7 flex flex-col">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 flex flex-col h-full min-h-[500px] relative overflow-hidden">
                        
                        {{-- Header Log --}}
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                    <i class="ph-duotone ph-list-dashes text-blue-600 text-xl"></i> Riwayat Live
                                </h3>
                                <p class="text-xs text-slate-400 font-medium mt-1">Data masuk secara real-time.</p>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Online</span>
                            </div>
                        </div>
                        
                        {{-- Table Container --}}
                        <div class="flex-1 overflow-hidden relative">
                            <div class="absolute inset-0 overflow-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-white sticky top-0 z-10 shadow-sm text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-6 py-4 rounded-tl-2xl">Siswa</th>
                                            <th class="col-harian px-4 py-4 text-center">Masuk</th>
                                            <th class="col-harian px-4 py-4 text-center">Pulang</th>
                                            <th class="col-waktu hidden-col px-4 py-4 text-center">Waktu Scan</th>
                                            <th class="col-kegiatan hidden-col px-4 py-4 text-center">Kegiatan</th>
                                            <th class="px-6 py-4 text-right rounded-tr-2xl">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scan-log" class="text-sm divide-y divide-slate-50">
                                        @if(isset($recentScans))
                                            @foreach($recentScans as $scan)
                                                <tr class="log-entry group hover:bg-slate-50/80 transition-colors" 
                                                    data-harian="{{ $scan['data_harian'] ? 'true' : 'false' }}"
                                                    data-dhuha="{{ $scan['data_dhuha'] ? 'true' : 'false' }}"
                                                    data-dhuhur="{{ $scan['data_dhuhur'] ? 'true' : 'false' }}"
                                                    data-ekskul="{{ ($scan['data_ekskul'] ?? false) ? 'true' : 'false' }}">
                                                    
                                                    <td class="px-6 py-4">
                                                        <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $scan['student_name'] }}</div>
                                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $scan['student_id'] }}</div>
                                                    </td>
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        @if($scan['time_in'])
                                                            <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($scan['time_in'])->format('H:i') }}</span>
                                                        @else <span class="text-slate-300">-</span> @endif
                                                    </td>
                                                    <td class="col-harian px-4 py-4 text-center">
                                                        @if($scan['time_out'])
                                                            <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($scan['time_out'])->format('H:i') }}</span>
                                                        @else <span class="text-slate-300">-</span> @endif
                                                    </td>
                                                    <td class="col-waktu hidden-col px-4 py-4 text-center">
                                                        <span class="time-dhuha font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded {{ $scan['dhuha_time'] ? '' : 'hidden' }}">{{ $scan['dhuha_time'] ? \Carbon\Carbon::parse($scan['dhuha_time'])->format('H:i') : '-' }}</span>
                                                        <span class="time-dhuhur font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded {{ $scan['dhuhur_time'] ? '' : 'hidden' }}">{{ $scan['dhuhur_time'] ? \Carbon\Carbon::parse($scan['dhuhur_time'])->format('H:i') : '-' }}</span>
                                                        <span class="time-ekskul font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded {{ ($scan['ekskul_time'] ?? false) ? '' : 'hidden' }}">{{ ($scan['ekskul_time'] ?? false) ? \Carbon\Carbon::parse($scan['ekskul_time'])->format('H:i') : '-' }}</span>
                                                    </td>
                                                    <td class="col-kegiatan hidden-col px-4 py-4 text-center text-slate-600 font-medium">
                                                        {{ $scan['ekskul_name'] ?? '-' }}
                                                    </td>
                                                    <td class="log-status px-6 py-4 text-right">
                                                        <span class="badge-harian inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide {{ $scan['status'] == 'Masuk' ? 'bg-emerald-100 text-emerald-700' : ($scan['status'] == 'Terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500') }}">
                                                            {{ $scan['status'] }}
                                                        </span>
                                                        <span class="badge-dhuha hidden inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-emerald-100 text-emerald-700">Selesai</span>
                                                        <span class="badge-dhuhur hidden inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-amber-100 text-amber-700">Selesai</span>
                                                        <span class="badge-ekskul hidden inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-purple-100 text-purple-700">Hadir</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                
                                {{-- Empty State --}}
                                <div id="no-log-entry" class="{{ (isset($recentScans) && count($recentScans) > 0) ? 'hidden' : '' }} flex flex-col items-center justify-center py-20 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="ph-duotone ph-qr-code text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-400 font-medium text-sm">Belum ada data scan masuk.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. JAVASCRIPT LOGIC (REWIRED FOR NEW LAYOUT) --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const SCHEDULE_DATA = {!! $scheduleJson !!};
        
        let audioCtx;
        function initAudio() {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
        }

        function escapeHtml(text) {
            if (!text) return text;
            return String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            if(SCHEDULE_DATA.is_holiday) return;

            const toMinutes = (timeStr) => {
                if(!timeStr) return 0;
                const [h, m] = timeStr.split(':').map(Number);
                return h * 60 + m;
            };

            const MODE_TIMES = {
                DHUHA_START: toMinutes(SCHEDULE_DATA.dhuha_start || '07:30'), 
                DHUHA_END:   toMinutes(SCHEDULE_DATA.dhuha_end || '09:30'),
                DHUHUR_START: toMinutes(SCHEDULE_DATA.dhuhur_start || '11:45'),
                DHUHUR_END:   toMinutes(SCHEDULE_DATA.dhuhur_end || '12:30')
            };

            let currentScanMode = 'Harian';
            let selectedExtra = ''; 
            let manualOverride = false;
            let resultTimeout; 
            let isProcessing = false;
            
            const csrfToken = '{{ csrf_token() }}';
            const scanProcessUrl = '{{ route('scan.process') }}'; 

            // DOM Elements
            const logTableBody = document.getElementById('scan-log');
            const scanStatus = document.getElementById('scan-status');
            const scanResult = document.getElementById('scan-result');
            const modeBadgeText = document.getElementById('mode-text');
            const modeBadge = document.getElementById('mode-badge');
            const extraContainer = document.getElementById('extra-selector-container');
            const extraSelect = document.getElementById('extra-activity-select');
            const btnResetAuto = document.getElementById('btn-reset-auto');
            const scannerOverlay = document.getElementById('scanner-overlay-el');

            // Audio Logic
            function playBeep(type = 'success') {
                try {
                    initAudio(); 
                    const oscillator = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);
                    oscillator.type = 'sine';
                    let freq = 880; 
                    if(type === 'warning') freq = 440; else if(type === 'error') freq = 200; 
                    oscillator.frequency.setValueAtTime(freq, audioCtx.currentTime);
                    gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.5); 
                    oscillator.start(audioCtx.currentTime);
                    oscillator.stop(audioCtx.currentTime + 0.5);
                } catch (e) { console.log("Audio belum siap."); }
            }

            // Visual Config for Modes
            const visualConfig = {
                'Harian': { color: 'blue', label: 'Absen Harian' },
                'Dhuha': { color: 'emerald', label: 'Sholat Dhuha' },
                'Dhuhur': { color: 'amber', label: 'Sholat Dhuhur' },
                'Ekstrakurikuler': { color: 'purple', label: 'Ekstrakurikuler' }
            };

            // Clock
            const clockElement = document.getElementById('clock');
            if(clockElement) {
                setInterval(() => { clockElement.textContent = new Date().toLocaleTimeString('id-ID', { hour12: false }); }, 1000);
            }

            // Auto Select Mode Logic
            function autoSelectMode() {
                if (manualOverride) return;
                const now = new Date();
                const currentMinutes = now.getHours() * 60 + now.getMinutes();
                let newMode = 'Harian';
                
                if (currentMinutes >= MODE_TIMES.DHUHA_START && currentMinutes < MODE_TIMES.DHUHA_END) newMode = 'Dhuha';
                else if (currentMinutes >= MODE_TIMES.DHUHUR_START && currentMinutes < MODE_TIMES.DHUHUR_END) newMode = 'Dhuhur';

                if (currentScanMode !== newMode && currentScanMode !== 'Ekstrakurikuler') selectScanMode(newMode, true);
            }

            window.resetAutoMode = function() {
                manualOverride = false;
                btnResetAuto.classList.add('hidden');
                autoSelectMode();
            }

            // Switch Mode Function
            function selectScanMode(type, isAuto = false) {
                if (!isAuto) { 
                    manualOverride = true; 
                    btnResetAuto.classList.remove('hidden');
                    initAudio(); 
                } else {
                    btnResetAuto.classList.add('hidden');
                }

                currentScanMode = type;
                const config = visualConfig[type];

                // Update Button Styles
                document.querySelectorAll('.scan-type-btn').forEach(btn => {
                    const btnType = btn.getAttribute('data-type');
                    const activeBorder = btn.querySelector('.active-border');
                    const indicator = btn.querySelector('.indicator-dot');
                    
                    if (btnType === type) {
                        activeBorder.classList.remove('opacity-0', 'scale-95');
                        indicator.className = `w-3 h-3 rounded-full indicator-dot bg-${config.color}-500 shadow-[0_0_8px_rgba(0,0,0,0.2)]`;
                    } else {
                        activeBorder.classList.add('opacity-0', 'scale-95');
                        indicator.className = `w-3 h-3 rounded-full border-2 border-slate-200 indicator-dot`;
                    }
                });

                // Update Badges & Status
                modeBadgeText.innerText = config.label;
                modeBadge.className = `px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider border flex items-center gap-2 bg-${config.color}-50 text-${config.color}-600 border-${config.color}-100`;
                modeBadge.querySelector('span').className = `w-2 h-2 rounded-full bg-${config.color}-500 animate-pulse`;

                if (type === 'Ekstrakurikuler') {
                    extraContainer.classList.remove('hidden');
                    scanStatus.innerHTML = selectedExtra 
                        ? `<i class="ph-bold ph-check text-purple-500"></i> Siap: ${selectedExtra}` 
                        : `<i class="ph-bold ph-warning text-amber-500"></i> Pilih Kegiatan!`;
                } else {
                    extraContainer.classList.add('hidden');
                    scanStatus.innerHTML = `<i class="ph-bold ph-qr-code text-slate-500"></i> Scan QR ${type}`;
                }
                
                updateTableLayout(type); 
                filterLogs(type);
            }

            document.querySelectorAll('.scan-type-btn').forEach(btn => {
                btn.addEventListener('click', () => selectScanMode(btn.getAttribute('data-type')));
            });
            
            extraSelect.addEventListener('change', (e) => {
                selectedExtra = e.target.value; 
                if (selectedExtra) scanStatus.innerHTML = `<i class="ph-bold ph-check text-purple-500"></i> Siap: ${selectedExtra}`;
                else scanStatus.innerHTML = `<i class="ph-bold ph-warning text-amber-500"></i> Pilih Kegiatan!`;
            });

            autoSelectMode();
            setInterval(autoSelectMode, 60000); 

            function updateTableLayout(type) {
                const harianCols = document.querySelectorAll('.col-harian');
                const waktuCols = document.querySelectorAll('.col-waktu');
                const kegiatanCols = document.querySelectorAll('.col-kegiatan');
                
                harianCols.forEach(el => el.classList.add('hidden-col'));
                waktuCols.forEach(el => el.classList.add('hidden-col'));
                kegiatanCols.forEach(el => el.classList.add('hidden-col'));

                if (type === 'Harian') harianCols.forEach(el => el.classList.remove('hidden-col'));
                else if (type === 'Ekstrakurikuler') { waktuCols.forEach(el => el.classList.remove('hidden-col')); kegiatanCols.forEach(el => el.classList.remove('hidden-col')); }
                else waktuCols.forEach(el => el.classList.remove('hidden-col'));
            }

            function filterLogs(type) {
                const rows = logTableBody.querySelectorAll('.log-entry');
                let visibleCount = 0;
                rows.forEach(row => {
                    let shouldShow = false;
                    if (type === 'Harian') shouldShow = row.getAttribute('data-harian') === 'true';
                    else if (type === 'Dhuha') shouldShow = row.getAttribute('data-dhuha') === 'true';
                    else if (type === 'Dhuhur') shouldShow = row.getAttribute('data-dhuhur') === 'true';
                    else if (type === 'Ekstrakurikuler') shouldShow = row.getAttribute('data-ekskul') === 'true';

                    if (shouldShow) { row.classList.remove('hidden-row'); toggleCells(row, type.toLowerCase()); visibleCount++; }
                    else row.classList.add('hidden-row');
                });
                const noLogEntry = document.getElementById('no-log-entry');
                if (visibleCount === 0) noLogEntry.classList.remove('hidden'); else noLogEntry.classList.add('hidden');
            }

            function toggleCells(row, activeType) {
                row.querySelectorAll('.badge-harian, .badge-dhuha, .badge-dhuhur, .badge-ekskul').forEach(el => el.classList.add('hidden'));
                row.querySelectorAll('.col-waktu span').forEach(el => el.classList.add('hidden'));
                
                let badgeClass = `.badge-${activeType}`;
                if(row.querySelector(badgeClass)) row.querySelector(badgeClass).classList.remove('hidden');
                
                if (activeType !== 'harian') {
                    let timeClass = `.time-${activeType}`;
                    if(row.querySelector(timeClass)) row.querySelector(timeClass).classList.remove('hidden');
                }
            }

            function addNewRowToTable(scanData, scanType) {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                const name = scanData.student?.name || scanData.student_name || 'Siswa';
                const id = scanData.student?.id || scanData.student_id || 'ID';
                const status = scanData.status || 'Hadir';
                
                const row = document.createElement('tr');
                row.className = 'log-entry group hover:bg-slate-50/80 transition-colors new-row-entry';
                row.setAttribute('data-harian', scanType === 'Harian');
                row.setAttribute('data-dhuha', scanType === 'Dhuha');
                row.setAttribute('data-dhuhur', scanType === 'Dhuhur');
                row.setAttribute('data-ekskul', scanType === 'Ekstrakurikuler');

                let statusClass = 'bg-slate-100 text-slate-500';
                if(status === 'Masuk' || status === 'Hadir') statusClass = 'bg-emerald-100 text-emerald-700';
                else if(status.includes('Terlambat')) statusClass = 'bg-amber-100 text-amber-700';

                row.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">${escapeHtml(name)}</div>
                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">${escapeHtml(id)}</div>
                    </td>
                    <td class="col-harian px-4 py-4 text-center ${scanType !== 'Harian' ? 'hidden-col' : ''}">
                        <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded">${scanType === 'Harian' ? timeString : '-'}</span>
                    </td>
                    <td class="col-harian px-4 py-4 text-center ${scanType !== 'Harian' ? 'hidden-col' : ''}">-</td>
                    <td class="col-waktu px-4 py-4 text-center ${scanType === 'Harian' ? 'hidden-col' : ''}">
                        <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded">${scanType !== 'Harian' ? timeString : '-'}</span>
                    </td>
                    <td class="col-kegiatan px-4 py-4 text-center text-slate-600 font-medium ${scanType === 'Harian' ? 'hidden-col' : ''}">
                        ${scanType === 'Ekstrakurikuler' ? escapeHtml(selectedExtra) : '-'}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide ${statusClass}">
                            ${escapeHtml(status)}
                        </span>
                    </td>
                `;

                logTableBody.insertBefore(row, logTableBody.firstChild);
                filterLogs(currentScanMode);
            }

            // Scanner Logic
            const html5QrCode = new Html5Qrcode("qr-reader");
            const onScanSuccess = (decodedText, decodedResult) => {
                if (isProcessing) return;
                
                if (currentScanMode === 'Ekstrakurikuler' && !selectedExtra) {
                    Swal.fire({ icon: 'warning', title: 'Pilih Kegiatan!', text: 'Silakan pilih jenis ekstrakurikuler terlebih dahulu.', timer: 2000, showConfirmButton: false });
                    return;
                }

                isProcessing = true; 
                html5QrCode.pause(); 
                scanStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-blue-600"></i> Memproses...`;
                
                if (decodedText.length < 3 || decodedText.length > 50) {
                     showScanResult('error', 'Format QR Code tidak valid.'); 
                     triggerScanEffect('error'); playBeep('error'); resumeScanner(); return;
                }
                
                const handler = currentScanMode === 'Harian' ? handleScanHarian : (id) => handleScanKegiatan(id, currentScanMode, selectedExtra);
                handler(decodedText);
            };

            function triggerScanEffect(type) {
                scannerOverlay.classList.remove('scan-success-effect', 'scan-error-effect', 'scan-warning-effect');
                scannerOverlay.classList.add(`scan-${type}-effect`);
                setTimeout(() => scannerOverlay.classList.remove(`scan-${type}-effect`), 500);
            }

            async function handleScanHarian(studentId) {
                await processScan(studentId, 'Harian');
            }

            async function handleScanKegiatan(studentId, type, extraName) {
                await processScan(studentId, type, extraName);
            }

            async function processScan(studentId, type, activity = null) {
                try {
                    const body = { student_id: studentId, type: type };
                    if(activity) body.activity = activity;

                    const response = await fetch(scanProcessUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify(body)
                    });
                    const result = await response.json();
                    
                    if (response.ok) {
                        const isLate = result.message.toUpperCase().includes('TERLAMBAT');
                        triggerScanEffect(isLate ? 'warning' : 'success');
                        playBeep(isLate ? 'warning' : 'success');
                        showScanResult(isLate ? 'warning' : 'success', result.message);
                        if(result.scan) addNewRowToTable(result.scan, type);
                    } else { 
                        triggerScanEffect('error');
                        showScanResult(response.status === 409 ? 'warning' : 'error', result.message || 'Error Server'); 
                        playBeep('error'); 
                    }
                } catch (error) { 
                    triggerScanEffect('error'); showScanResult('error', 'Gagal koneksi server.'); playBeep('error'); 
                } finally { resumeScanner(); }
            }

            const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    const rearCamera = cameras.find(c => c.label.toLowerCase().includes('back')) || cameras[0];
                    html5QrCode.start(rearCamera.id, config, onScanSuccess).catch(() => html5QrCode.start(cameras[0].id, config, onScanSuccess));
                } else { scanStatus.textContent = "Kamera tidak ditemukan"; Swal.fire('Error', 'Kamera tidak ditemukan.', 'error'); }
            }).catch(err => { scanStatus.textContent = "Izin kamera ditolak"; Swal.fire('Error', 'Izin kamera ditolak.', 'error'); });

            function resumeScanner() {
                setTimeout(() => {
                    html5QrCode.resume();
                    const statusText = currentScanMode === 'Ekstrakurikuler' ? (selectedExtra || 'Pilih Kegiatan') : currentScanMode;
                    scanStatus.innerHTML = `<i class="ph-bold ph-qr-code text-slate-500"></i> Scan QR ${statusText}`;
                    isProcessing = false; 
                }, 2000); 
            }

            function showScanResult(type, message) {
                if (resultTimeout) clearTimeout(resultTimeout);
                scanResult.className = 'mt-4 p-4 rounded-xl font-bold text-sm text-center transition-all duration-500 shadow-sm transform scale-100 opacity-100 border';
                
                const colors = {
                    success: { bg: 'bg-emerald-50', text: 'text-emerald-700', border: 'border-emerald-200' },
                    warning: { bg: 'bg-amber-50', text: 'text-amber-700', border: 'border-amber-200' },
                    error: { bg: 'bg-rose-50', text: 'text-rose-700', border: 'border-rose-200' }
                };
                const c = colors[type] || colors.error;
                
                scanResult.classList.add(c.bg, c.text, c.border);
                scanResult.innerHTML = `<span>${escapeHtml(message)}</span>`;
                scanResult.classList.remove('hidden');
                
                resultTimeout = setTimeout(() => {
                    scanResult.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => scanResult.classList.add('hidden'), 300);
                }, 4000); 
            }
        });
    </script>
</x-app-layout>