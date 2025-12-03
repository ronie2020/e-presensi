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
        .scan-success-effect {
            box-shadow: inset 0 0 50px rgba(34, 197, 94, 0.8);
            border-color: #22c55e;
        }
        .scan-error-effect {
            box-shadow: inset 0 0 50px rgba(239, 68, 68, 0.8);
            border-color: #ef4444;
        }

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
        
        /* Scrollbar Halus */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; } 
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Utility */
        .hidden-col { display: none !important; }
        .hidden-row { display: none !important; }
        
        /* Animasi Baris Baru */
        @keyframes highlightRow {
            0% { background-color: #dcfce7; }
            100% { background-color: transparent; }
        }
        .new-row-entry {
            animation: highlightRow 2s ease-out;
        }

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

    {{-- 
        PERBAIKAN UTAMA: 
        Menyiapkan variabel JSON di blok PHP terpisah agar Blade parser tidak bingung.
        Ini mencegah error "ArgumentCounterError" dan "ParseError".
    --}}
    @php
        // Ambil data dari controller, jika null gunakan array kosong default
        $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
        
        // Encode ke JSON di server-side
        $scheduleJson = json_encode($safeSchedule);
    @endphp

    <div class="py-6 sm:py-12" onclick="initAudio()"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                        Scan Aktifitas
                    </h2>
                    <p class="text-gray-500 mt-1">
                        {{-- Tampilkan Status Jadwal Hari Ini --}}
                        @if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                {{ $scheduleConfig['description'] ?? 'Libur' }}
                            </span>
                        @elseif(isset($scheduleConfig) && ($scheduleConfig['type'] ?? '') == 'Special')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                Jadwal Khusus: {{ $scheduleConfig['description'] ?? 'Kegiatan' }}
                            </span>
                        @else
                            Portal pencatatan kehadiran dan ibadah siswa secara real-time.
                        @endif
                    </p>
                </div>

                {{-- JAM DIGITAL --}}
                <div class="bg-white p-4 pr-6 pl-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Waktu Terkini</p>
                        <div id="clock" class="text-2xl font-extrabold text-gray-800 font-mono tracking-widest leading-none mt-1">
                            00:00:00
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl shadow-gray-200/40 rounded-[2rem] border border-gray-100">
                <div class="p-6 lg:p-8">

                    {{-- TAB NAVIGASI --}}
                    <div class="mb-8 bg-gray-50/50 p-2 rounded-2xl border border-gray-100">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button id="btn-harian" data-type="Harian" class="scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300 bg-white text-gray-500 hover:bg-gray-100">
                                <span class="relative z-10 font-bold text-sm sm:text-base transition-colors duration-300">Absen Harian</span>
                            </button>
                            <button id="btn-dhuha" data-type="Dhuha" class="scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300 bg-white text-gray-500 hover:bg-gray-100">
                                <span class="relative z-10 font-bold text-sm sm:text-base transition-colors duration-300">Sholat Dhuha</span>
                            </button>
                            <button id="btn-dhuhur" data-type="Dhuhur" class="scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300 bg-white text-gray-500 hover:bg-gray-100">
                                <span class="relative z-10 font-bold text-sm sm:text-base transition-colors duration-300">Sholat Dhuhur</span>
                            </button>
                            <button id="btn-ekskul" data-type="Ekstrakurikuler" class="scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300 bg-white text-gray-500 hover:bg-gray-100">
                                <span class="relative z-10 font-bold text-sm sm:text-base transition-colors duration-300">Ekstrakurikuler</span>
                            </button>
                        </div>
                        
                        {{-- DROPDOWN EKSKUL --}}
                        <div id="extra-selector-container" class="hidden mt-4 animate-fade-in-down">
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex flex-col md:flex-row items-center gap-4">
                                <label class="text-xs font-bold text-blue-800 uppercase tracking-wide whitespace-nowrap">
                                    Pilih Kegiatan:
                                </label>
                                <select id="extra-activity-select" class="w-full md:w-auto flex-1 rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                                    <option value="">-- Pilih Ekstrakurikuler --</option>
                                    @if(isset($extracurriculars))
                                        @foreach($extracurriculars as $ekskul)
                                            <option value="{{ $ekskul->name }}">{{ $ekskul->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- INDICATOR --}}
                        <div id="mode-indicator" class="mt-3 mx-1 p-2.5 rounded-lg text-center text-xs font-bold uppercase tracking-wide transition-all duration-300 bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                            <span id="mode-text">Mode Aktif: Absensi Harian</span>
                            {{-- Button reset auto mode --}}
                            <button id="btn-reset-auto" class="hidden ml-2 px-2 py-0.5 bg-white border border-blue-200 text-[10px] rounded hover:bg-gray-50 text-gray-500" onclick="resetAutoMode()">
                                Auto
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        
                        {{-- AREA KAMERA --}}
                        <div class="lg:col-span-5 flex flex-col">
                            <div class="relative bg-gray-900 rounded-3xl overflow-hidden shadow-lg border-4 border-gray-100 aspect-square sm:aspect-auto sm:h-[400px]" id="camera-container">
                                
                                {{-- OVERLAY JIKA HARI LIBUR --}}
                                @if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false))
                                <div class="holiday-overlay text-center p-6">
                                    <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-800 mb-2">Scanner Nonaktif</h3>
                                    <p class="text-gray-500 font-medium text-sm">{{ $scheduleConfig['description'] ?? 'Hari Libur' }}</p>
                                    <p class="text-gray-400 text-xs mt-2">Tidak ada aktivitas scan hari ini.</p>
                                </div>
                                @endif

                                <div id="qr-reader" class="w-full h-full object-cover relative z-10"></div>
                                <div id="scanner-overlay-el" class="scanner-overlay z-20"><div class="scanner-line"></div></div>
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center z-30 px-4">
                                    <div id="scan-status" class="bg-black/60 backdrop-blur-md text-white text-xs py-1.5 px-4 rounded-full font-mono border border-white/10">
                                        Menunggu Input...
                                    </div>
                                </div>
                            </div>
                            
                            {{-- HASIL SCAN --}}
                            <div id="scan-result" class="mt-4 p-4 rounded-2xl font-bold text-sm text-center hidden transition-all duration-500 shadow-md transform scale-95 opacity-0"></div>
                            <p class="mt-4 text-center text-xs text-gray-400">Pastikan QR Code berada di dalam bingkai.</p>
                        </div>

                        {{-- AREA TABEL RIWAYAT --}}
                        <div class="lg:col-span-7 flex flex-col h-full min-h-[400px] bg-gray-50/50 rounded-3xl border border-gray-100 p-1">
                            <div class="p-4 flex justify-between items-center border-b border-gray-100 bg-white rounded-t-[1.3rem]">
                                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                    <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    Riwayat Live
                                </h3>
                                <div class="flex items-center gap-2">
                                    <span class="flex h-2 w-2"><span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Real-time</span>
                                </div>
                            </div>
                            
                            <div class="flex-1 overflow-hidden relative">
                                <div class="absolute inset-0 overflow-auto custom-scrollbar p-2">
                                    <table class="w-full border-collapse" style="min-width: 600px;">
                                        <thead class="bg-gray-100/50 text-gray-500 text-xs uppercase tracking-wider font-bold sticky top-0 z-10 backdrop-blur-sm">
                                            <tr>
                                                <th class="px-4 py-3 text-left rounded-l-xl">Siswa</th>
                                                <th class="col-harian px-2 py-3 text-center">Masuk</th>
                                                <th class="col-harian px-2 py-3 text-center">Pulang</th>
                                                <th class="col-waktu hidden-col px-2 py-3 text-center">Waktu</th>
                                                <th class="col-kegiatan hidden-col px-2 py-3 text-center">Kegiatan</th>
                                                <th class="px-4 py-3 text-right rounded-r-xl">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="scan-log" class="text-sm divide-y divide-gray-100">
                                            @if(isset($recentScans))
                                                @foreach($recentScans as $scan)
                                                    <tr class="log-entry group hover:bg-white transition-colors rounded-xl" 
                                                        data-harian="{{ $scan['data_harian'] ? 'true' : 'false' }}"
                                                        data-dhuha="{{ $scan['data_dhuha'] ? 'true' : 'false' }}"
                                                        data-dhuhur="{{ $scan['data_dhuhur'] ? 'true' : 'false' }}"
                                                        data-ekskul="{{ ($scan['data_ekskul'] ?? false) ? 'true' : 'false' }}">
                                                        
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $scan['student_name'] }}</div>
                                                            <div class="text-[10px] text-gray-400 font-mono bg-gray-100 inline-block px-1.5 rounded">{{ $scan['student_id'] }}</div>
                                                        </td>
                                                        <td class="col-harian px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center">
                                                            {{ $scan['time_in'] ? \Carbon\Carbon::parse($scan['time_in'])->format('H:i') : '-' }}
                                                        </td>
                                                        <td class="col-harian px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center">
                                                            {{ $scan['time_out'] ? \Carbon\Carbon::parse($scan['time_out'])->format('H:i') : '-' }}
                                                        </td>
                                                        <td class="col-waktu hidden-col px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center">
                                                            <span class="time-dhuha {{ $scan['dhuha_time'] ? '' : 'hidden' }}">{{ $scan['dhuha_time'] ? \Carbon\Carbon::parse($scan['dhuha_time'])->format('H:i') : '-' }}</span>
                                                            <span class="time-dhuhur {{ $scan['dhuhur_time'] ? '' : 'hidden' }}">{{ $scan['dhuhur_time'] ? \Carbon\Carbon::parse($scan['dhuhur_time'])->format('H:i') : '-' }}</span>
                                                            <span class="time-ekskul {{ ($scan['ekskul_time'] ?? false) ? '' : 'hidden' }}">{{ ($scan['ekskul_time'] ?? false) ? \Carbon\Carbon::parse($scan['ekskul_time'])->format('H:i') : '-' }}</span>
                                                        </td>
                                                        <td class="col-kegiatan hidden-col px-2 py-3 whitespace-nowrap text-gray-600 font-medium text-center">
                                                            {{ $scan['ekskul_name'] ?? '-' }}
                                                        </td>
                                                        <td class="log-status px-4 py-3 whitespace-nowrap text-right">
                                                            <span class="badge-harian status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg {{ $scan['status'] == 'Masuk' ? 'bg-green-100 text-green-700' : ($scan['status'] == 'Terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                                                {{ $scan['status'] }}
                                                            </span>
                                                            <span class="badge-dhuha status-badge hidden px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-emerald-100 text-emerald-700">Dhuha</span>
                                                            <span class="badge-dhuhur status-badge hidden px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-orange-100 text-orange-700">Dhuhur</span>
                                                            <span class="badge-ekskul status-badge hidden px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-purple-100 text-purple-700">Hadir</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                    <div id="no-log-entry" class="{{ (isset($recentScans) && count($recentScans) > 0) ? 'hidden' : '' }} py-8 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-400 font-medium">Belum ada data untuk kategori ini.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. JAVASCRIPT LOGIC --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        // --- DATA JADWAL DINAMIS DARI CONTROLLER ---
        // Kita menggunakan variabel PHP yang sudah di-encode sebelumnya
        // Teknik ini 100% aman dari error parsing Blade
        const SCHEDULE_DATA = {!! $scheduleJson !!};

        console.log("Active Schedule Configuration:", SCHEDULE_DATA);

        // --- GLOBAL & UTILITY ---
        let audioCtx;
        function initAudio() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
        }

        // FUNGSI PENTING: Mencegah XSS (Cross Site Scripting)
        function escapeHtml(text) {
            if (!text) return text;
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            
            // JIKA HARI LIBUR, STOP PROSES
            if(SCHEDULE_DATA.is_holiday) {
                console.log("Hari ini libur. Scanner tidak diinisialisasi.");
                return; // Jangan jalankan sisa script
            }

            // KONFIGURASI JAM (Menggunakan data dari Controller)
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

            // STATE VARIABLES
            let currentScanMode = 'Harian';
            let selectedExtra = ''; 
            let manualOverride = false;
            let resultTimeout; 
            let isProcessing = false;
            
            const csrfToken = '{{ csrf_token() }}';
            const scanProcessUrl = '{{ route('scan.process') }}'; // Pastikan route ini ada

            // DOM ELEMENTS
            const logTableBody = document.getElementById('scan-log');
            const scanStatus = document.getElementById('scan-status');
            const scanResult = document.getElementById('scan-result');
            const modeIndicator = document.getElementById('mode-indicator');
            const modeText = document.getElementById('mode-text');
            const extraContainer = document.getElementById('extra-selector-container');
            const extraSelect = document.getElementById('extra-activity-select');
            const btnResetAuto = document.getElementById('btn-reset-auto');
            const scannerOverlay = document.getElementById('scanner-overlay-el');

            // --- AUDIO FEEDBACK ---
            function playBeep(type = 'success') {
                try {
                    initAudio(); 
                    const oscillator = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);
                    oscillator.type = 'sine';
                    let freq = type === 'success' ? 880 : (type === 'warning' ? 500 : 200);
                    oscillator.frequency.setValueAtTime(freq, audioCtx.currentTime);
                    gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.3);
                    oscillator.start(audioCtx.currentTime);
                    oscillator.stop(audioCtx.currentTime + 0.3);
                } catch (e) { console.log("Audio belum siap."); }
            }

            // --- UI CONFIG ---
            const typeConfig = {
                'Harian': { activeClass: 'bg-blue-600 text-white shadow-lg', inactiveClass: 'bg-white text-gray-500 hover:bg-gray-100', indicatorClass: 'bg-blue-50 text-blue-600 border-blue-100' },
                'Dhuha': { activeClass: 'bg-emerald-600 text-white shadow-lg', inactiveClass: 'bg-white text-gray-500 hover:bg-gray-100', indicatorClass: 'bg-emerald-50 text-emerald-600 border-emerald-100' },
                'Dhuhur': { activeClass: 'bg-orange-500 text-white shadow-lg', inactiveClass: 'bg-white text-gray-500 hover:bg-gray-100', indicatorClass: 'bg-orange-50 text-orange-600 border-orange-100' },
                'Ekstrakurikuler': { activeClass: 'bg-purple-600 text-white shadow-lg', inactiveClass: 'bg-white text-gray-500 hover:bg-gray-100', indicatorClass: 'bg-purple-50 text-purple-600 border-purple-100' }
            };

            // --- CLOCK & AUTO MODE ---
            const clockElement = document.getElementById('clock');
            if(clockElement) {
                setInterval(() => { clockElement.textContent = new Date().toLocaleTimeString('id-ID', { hour12: false }); }, 1000);
            }

            function autoSelectMode() {
                if (manualOverride) return;
                const now = new Date();
                const currentMinutes = now.getHours() * 60 + now.getMinutes();
                let newMode = 'Harian';
                
                // Gunakan MODE_TIMES yang sudah dinamis
                if (currentMinutes >= MODE_TIMES.DHUHA_START && currentMinutes < MODE_TIMES.DHUHA_END) newMode = 'Dhuha';
                else if (currentMinutes >= MODE_TIMES.DHUHUR_START && currentMinutes < MODE_TIMES.DHUHUR_END) newMode = 'Dhuhur';

                if (currentScanMode !== newMode && currentScanMode !== 'Ekstrakurikuler') selectScanMode(newMode, true);
            }

            window.resetAutoMode = function() {
                manualOverride = false;
                btnResetAuto.classList.add('hidden');
                autoSelectMode();
            }

            function selectScanMode(type, isAuto = false) {
                if (!isAuto) { 
                    manualOverride = true; 
                    btnResetAuto.classList.remove('hidden');
                    initAudio(); 
                } else {
                    btnResetAuto.classList.add('hidden');
                }

                currentScanMode = type;
                const config = typeConfig[type];

                document.querySelectorAll('.scan-type-btn').forEach(btn => {
                    const btnType = btn.getAttribute('data-type');
                    if (btnType === type) btn.className = `scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300 transform scale-105 ${config.activeClass}`;
                    else btn.className = `scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300 ${config.inactiveClass}`;
                });

                let indicatorText = type === 'Harian' ? 'Absensi Harian' : (type === 'Ekstrakurikuler' ? 'Kegiatan Ekstrakurikuler' : 'Sholat ' + type);
                modeText.innerText = `Mode Aktif: ${indicatorText}`;
                modeIndicator.className = `mt-3 mx-1 p-2.5 rounded-lg text-center text-xs font-bold uppercase tracking-wide transition-all duration-300 flex items-center justify-center gap-2 ${config.indicatorClass}`;
                
                if (type === 'Ekstrakurikuler') {
                    extraContainer.classList.remove('hidden');
                    scanStatus.textContent = selectedExtra ? `Siap Scan: ${selectedExtra}` : `Pilih Kegiatan Dulu`;
                } else {
                    extraContainer.classList.add('hidden');
                    scanStatus.textContent = `Siap Scan: ${type}`;
                }
                updateTableLayout(type); 
                filterLogs(type);
            }

            // Event Listeners
            document.querySelectorAll('.scan-type-btn').forEach(btn => {
                btn.addEventListener('click', () => selectScanMode(btn.getAttribute('data-type')));
            });
            
            extraSelect.addEventListener('change', (e) => {
                selectedExtra = e.target.value; 
                if (selectedExtra) scanStatus.textContent = `Siap Scan: ${selectedExtra}`;
                else scanStatus.textContent = `Pilih Kegiatan Dulu`;
            });

            // Init Auto Mode
            autoSelectMode();
            setInterval(autoSelectMode, 60000); 

            // --- TABLE MANAGEMENT ---
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
                row.querySelectorAll('.status-badge').forEach(el => el.classList.add('hidden'));
                row.querySelectorAll('.col-waktu span').forEach(el => el.classList.add('hidden'));
                
                let badgeClass = `.badge-${activeType}`;
                if(row.querySelector(badgeClass)) row.querySelector(badgeClass).classList.remove('hidden');
                
                if (activeType !== 'harian') {
                    let timeClass = `.time-${activeType}`;
                    if(row.querySelector(timeClass)) row.querySelector(timeClass).classList.remove('hidden');
                }
            }

            // Fungsi Helper dengan SECURITY FIX (escapeHtml)
            function addNewRowToTable(scanData, scanType) {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                
                const name = scanData.student?.name || scanData.student_name || 'Siswa';
                const id = scanData.student?.id || scanData.student_id || 'ID';
                const status = scanData.status || 'Hadir';
                
                const isHarian = scanType === 'Harian' ? 'true' : 'false';
                const isDhuha = scanType === 'Dhuha' ? 'true' : 'false';
                const isDhuhur = scanType === 'Dhuhur' ? 'true' : 'false';
                const isEkskul = scanType === 'Ekstrakurikuler' ? 'true' : 'false';

                const row = document.createElement('tr');
                row.className = 'log-entry group hover:bg-white transition-colors rounded-xl new-row-entry';
                row.setAttribute('data-harian', isHarian);
                row.setAttribute('data-dhuha', isDhuha);
                row.setAttribute('data-dhuhur', isDhuhur);
                row.setAttribute('data-ekskul', isEkskul);

                let statusBadgeClass = 'bg-gray-100 text-gray-600';
                if(status === 'Masuk' || status === 'Hadir') statusBadgeClass = 'bg-green-100 text-green-700';
                else if(status.includes('Terlambat')) statusBadgeClass = 'bg-amber-100 text-amber-700';

                const timeIn = scanType === 'Harian' ? timeString : '-';
                const timeOut = '-'; 

                row.innerHTML = `
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">${escapeHtml(name)}</div>
                        <div class="text-[10px] text-gray-400 font-mono bg-gray-100 inline-block px-1.5 rounded">${escapeHtml(id)}</div>
                    </td>
                    <td class="col-harian px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center ${scanType !== 'Harian' ? 'hidden-col' : ''}">
                        ${timeIn}
                    </td>
                    <td class="col-harian px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center ${scanType !== 'Harian' ? 'hidden-col' : ''}">
                        ${timeOut}
                    </td>
                    <td class="col-waktu px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center ${scanType === 'Harian' ? 'hidden-col' : ''}">
                        <span class="time-dhuha ${scanType !== 'Dhuha' ? 'hidden' : ''}">${scanType === 'Dhuha' ? timeString : '-'}</span>
                        <span class="time-dhuhur ${scanType !== 'Dhuhur' ? 'hidden' : ''}">${scanType === 'Dhuhur' ? timeString : '-'}</span>
                        <span class="time-ekskul ${scanType !== 'Ekstrakurikuler' ? 'hidden' : ''}">${scanType === 'Ekstrakurikuler' ? timeString : '-'}</span>
                    </td>
                    <td class="col-kegiatan px-2 py-3 whitespace-nowrap text-gray-600 font-medium text-center ${scanType === 'Harian' ? 'hidden-col' : ''}">
                        ${scanType === 'Ekstrakurikuler' ? escapeHtml(selectedExtra || '-') : '-'}
                    </td>
                    <td class="log-status px-4 py-3 whitespace-nowrap text-right">
                        <span class="badge-harian status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg ${statusBadgeClass} ${scanType !== 'Harian' ? 'hidden' : ''}">
                            ${escapeHtml(status)}
                        </span>
                        <span class="badge-dhuha status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-emerald-100 text-emerald-700 ${scanType !== 'Dhuha' ? 'hidden' : ''}">Dhuha</span>
                        <span class="badge-dhuhur status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-orange-100 text-orange-700 ${scanType !== 'Dhuhur' ? 'hidden' : ''}">Dhuhur</span>
                        <span class="badge-ekskul status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-purple-100 text-purple-700 ${scanType !== 'Ekstrakurikuler' ? 'hidden' : ''}">Hadir</span>
                    </td>
                `;

                logTableBody.insertBefore(row, logTableBody.firstChild);
                filterLogs(currentScanMode);
            }

            // --- SCANNER LOGIC ---
            const html5QrCode = new Html5Qrcode("qr-reader");
            const onScanSuccess = (decodedText, decodedResult) => {
                if (isProcessing) return;
                
                if (currentScanMode === 'Ekstrakurikuler' && !selectedExtra) {
                    Swal.fire({ icon: 'warning', title: 'Pilih Kegiatan!', text: 'Silakan pilih jenis ekstrakurikuler terlebih dahulu.', timer: 2000, showConfirmButton: false });
                    return;
                }

                isProcessing = true; 
                html5QrCode.pause(); 
                scanStatus.textContent = `Memproses Data...`;
                
                if (decodedText.length < 3 || decodedText.length > 50) {
                     showScanResult('error', 'Format QR Code tidak valid.'); 
                     triggerScanEffect('error');
                     playBeep('error'); 
                     resumeScanner(); 
                     return;
                }
                
                if (currentScanMode === 'Harian') handleScanHarian(decodedText);
                else handleScanKegiatan(decodedText, currentScanMode, selectedExtra);
            };

            function triggerScanEffect(type) {
                scannerOverlay.classList.remove('scan-success-effect', 'scan-error-effect');
                if(type === 'success') scannerOverlay.classList.add('scan-success-effect');
                else scannerOverlay.classList.add('scan-error-effect');
                
                setTimeout(() => {
                    scannerOverlay.classList.remove('scan-success-effect', 'scan-error-effect');
                }, 500);
            }

            async function handleScanHarian(studentId) {
                try {
                    const response = await fetch(scanProcessUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ student_id: studentId, type: 'Harian' })
                    });
                    const result = await response.json();
                    
                    if (response.ok) {
                        triggerScanEffect('success');
                        playBeep('success');
                        if (result.message.toUpperCase().includes('TERLAMBAT')) showScanResult('warning', result.message); 
                        else showScanResult('success', result.message); 
                        
                        if(result.scan) addNewRowToTable(result.scan, 'Harian');

                    } else if (response.status === 409) { 
                        triggerScanEffect('error');
                        showScanResult('warning', result.message); 
                        playBeep('warning'); 
                    } else { 
                        triggerScanEffect('error');
                        showScanResult('error', result.message || 'Error Server'); 
                        playBeep('error'); 
                    }
                } catch (error) { 
                    console.error(error); 
                    triggerScanEffect('error');
                    showScanResult('error', 'Gagal koneksi server.'); 
                    playBeep('error'); 
                } 
                finally { resumeScanner(); }
            }

            async function handleScanKegiatan(studentId, type, extraName) {
                try {
                    const response = await fetch(scanProcessUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ student_id: studentId, type: type, activity: extraName })
                    });
                    const result = await response.json();
                    
                    if (response.ok) {
                        triggerScanEffect('success');
                        playBeep('success');
                        let titleText = type === 'Ekstrakurikuler' ? 'Absen Ekskul Berhasil' : `Absen ${type} Berhasil`;
                        let pointsText = type === 'Ekstrakurikuler' ? '+10 Poin Keaktifan' : '+5 Poin Kebaikan';
                        
                        // Gunakan escapeHtml juga di Swal untuk keamanan tambahan
                        Swal.fire({
                            title: 'Alhamdulillah!',
                            html: `<p class="text-xl text-gray-700">Selamat <b>${escapeHtml(result.scan?.student?.name || 'Siswa')}</b></p><p class="text-gray-500 mt-1 mb-4">${titleText}</p><div class="inline-flex items-center gap-2 px-4 py-3 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 shadow-sm"><span class="font-bold text-lg">${pointsText}</span></div>`,
                            icon: 'success', timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-[2rem]' }
                        });

                        if(result.scan) addNewRowToTable(result.scan, type);

                    } else if (response.status === 409) { 
                        triggerScanEffect('error');
                        Swal.fire({ title: 'Sudah Absen', text: result.message, icon: 'info', timer: 2500, showConfirmButton: false }); 
                        playBeep('warning'); 
                    } else { 
                        triggerScanEffect('error');
                        showScanResult('error', result.message || 'Error Server'); 
                        playBeep('error'); 
                    }
                } catch (error) { 
                    console.error(error); 
                    triggerScanEffect('error');
                    showScanResult('error', 'Gagal koneksi server.'); 
                    playBeep('error'); 
                } 
                finally { resumeScanner(); }
            }

            const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    const rearCamera = cameras.find(c => c.label.toLowerCase().includes('back')) || cameras[0];
                    html5QrCode.start(rearCamera.id, config, onScanSuccess).catch(() => { html5QrCode.start(cameras[0].id, config, onScanSuccess); });
                } else { scanStatus.textContent = "Kamera tidak ditemukan"; Swal.fire('Kamera Error', 'Kamera tidak ditemukan pada perangkat ini.', 'error'); }
            }).catch(err => { scanStatus.textContent = "Izin kamera ditolak"; Swal.fire('Izin Ditolak', 'Mohon izinkan akses kamera di browser Anda.', 'error'); });

            function resumeScanner() {
                setTimeout(() => {
                    html5QrCode.resume();
                    const statusText = currentScanMode === 'Ekstrakurikuler' ? (selectedExtra || 'Pilih Kegiatan') : currentScanMode;
                    scanStatus.textContent = `Siap Scan: ${statusText}`;
                    isProcessing = false; 
                }, 2000); 
            }

            function showScanResult(type, message) {
                if (resultTimeout) clearTimeout(resultTimeout);
                scanResult.className = 'mt-4 p-4 rounded-2xl font-bold text-sm text-center transition-all duration-500 shadow-md transform scale-100 opacity-100';
                if (type === 'success') scanResult.classList.add('bg-green-100', 'text-green-800', 'border', 'border-green-200');
                else if (type === 'warning') scanResult.classList.add('bg-yellow-50', 'text-yellow-700', 'border', 'border-yellow-200');
                else scanResult.classList.add('bg-red-100', 'text-red-800', 'border', 'border-red-200');
                
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