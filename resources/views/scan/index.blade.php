{{-- Halaman ini adalah tampilan untuk resources/views/scan/index.blade.php --}}
<x-app-layout>
    {{-- Custom Style untuk Animasi Scanner --}}
    @push('styles')
    <style>
        .scanner-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border: 2px solid rgba(59, 130, 246, 0.5);
            border-radius: 0.5rem;
            pointer-events: none;
            overflow: hidden;
        }
        .scanner-line {
            position: absolute;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, transparent, #3b82f6, transparent);
            top: 0;
            animation: scanMove 2s infinite linear;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.8);
        }
        @keyframes scanMove {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; } 
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @endpush

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                        Scan Aktifitas
                    </h2>
                    <p class="text-gray-500 mt-1">
                        Portal pencatatan kehadiran dan ibadah siswa secara real-time.
                    </p>
                </div>

                {{-- JAM DIGITAL WIDGET --}}
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

                    {{-- TAB NAVIGASI MODERN --}}
                    <div class="mb-8 bg-gray-50/50 p-2 rounded-2xl border border-gray-100">
                        <div class="grid grid-cols-3 gap-2">
                            <button data-type="Harian" class="scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300">
                                <span class="relative z-10 font-bold text-sm sm:text-base transition-colors duration-300">Absen Harian</span>
                            </button>
                            <button data-type="Dhuha" class="scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300">
                                <span class="relative z-10 font-bold text-sm sm:text-base transition-colors duration-300">Sholat Dhuha</span>
                            </button>
                            <button data-type="Dhuhur" class="scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300">
                                <span class="relative z-10 font-bold text-sm sm:text-base transition-colors duration-300">Sholat Dhuhur</span>
                            </button>
                        </div>
                        
                        {{-- Indikator Mode Aktif --}}
                        <div id="mode-indicator" class="mt-3 mx-1 p-2.5 rounded-lg text-center text-xs font-bold uppercase tracking-wide transition-all duration-300 bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                            <span>Mode Aktif: Absensi Harian</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        
                        {{-- AREA KAMERA (KIRI) --}}
                        <div class="lg:col-span-5 flex flex-col">
                            <div class="relative bg-gray-900 rounded-3xl overflow-hidden shadow-lg border-4 border-gray-100 aspect-square sm:aspect-auto sm:h-[400px]" id="camera-container">
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 z-0">
                                    <svg class="w-12 h-12 mb-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-xs font-medium uppercase tracking-widest">Memuat Kamera...</span>
                                </div>
                                <div id="qr-reader" class="w-full h-full object-cover relative z-10"></div>
                                <div class="scanner-overlay z-20"><div class="scanner-line"></div></div>
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center z-30 px-4">
                                    <div id="scan-status" class="bg-black/60 backdrop-blur-md text-white text-xs py-1.5 px-4 rounded-full font-mono border border-white/10">
                                        Menunggu Input...
                                    </div>
                                </div>
                            </div>
                            <div id="scan-result" class="mt-4 p-4 rounded-2xl font-bold text-sm text-center hidden transition-all duration-500 shadow-md transform scale-95 opacity-0"></div>
                            <p class="mt-4 text-center text-xs text-gray-400">Pastikan QR Code berada di dalam bingkai dan memiliki pencahayaan yang cukup.</p>
                        </div>

                        {{-- AREA TABEL RIWAYAT (KANAN) --}}
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
                                                <th class="col-prayer px-2 py-3 text-center hidden">Waktu</th>
                                                <th class="px-4 py-3 text-right rounded-r-xl">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="scan-log" class="text-sm divide-y divide-gray-100">
                                            @foreach($recentScans as $scan)
                                                <tr class="log-entry group hover:bg-white transition-colors rounded-xl" id="log-row-{{ $scan['student_id'] }}" 
                                                    data-harian="{{ $scan['data_harian'] ? 'true' : 'false' }}"
                                                    data-dhuha="{{ $scan['data_dhuha'] ? 'true' : 'false' }}"
                                                    data-dhuhur="{{ $scan['data_dhuhur'] ? 'true' : 'false' }}">
                                                    
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $scan['student_name'] }}</div>
                                                        <div class="text-[10px] text-gray-400 font-mono bg-gray-100 inline-block px-1.5 rounded">{{ $scan['student_id'] }}</div>
                                                    </td>
                                                    
                                                    <td class="col-harian log-time-in px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center">
                                                        {{ $scan['time_in'] ? \Carbon\Carbon::parse($scan['time_in'])->format('H:i') : '-' }}
                                                    </td>
                                                    <td class="col-harian log-time-out px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center">
                                                        {{ $scan['time_out'] ? \Carbon\Carbon::parse($scan['time_out'])->format('H:i') : '-' }}
                                                    </td>

                                                    <td class="col-prayer px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center hidden">
                                                        <span class="time-dhuha {{ $scan['dhuha_time'] ? '' : 'hidden' }}">
                                                            {{ $scan['dhuha_time'] ? \Carbon\Carbon::parse($scan['dhuha_time'])->format('H:i') : '-' }}
                                                        </span>
                                                        <span class="time-dhuhur {{ $scan['dhuhur_time'] ? '' : 'hidden' }}">
                                                            {{ $scan['dhuhur_time'] ? \Carbon\Carbon::parse($scan['dhuhur_time'])->format('H:i') : '-' }}
                                                        </span>
                                                    </td>

                                                    <td class="log-status px-4 py-3 whitespace-nowrap text-right">
                                                        {{-- BADGE: HARIAN (Default) --}}
                                                        <span class="badge-harian status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg
                                                            {{ $scan['status'] == 'Masuk' ? 'bg-green-100 text-green-700' : 
                                                               ($scan['status'] == 'Terlambat' ? 'bg-amber-100 text-amber-700' :
                                                               ($scan['status'] == 'Pulang' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600')) }}">
                                                            {{ $scan['status'] }}
                                                        </span>

                                                        {{-- BADGE: DHUHA (Hidden by Default) --}}
                                                        <span class="badge-dhuha status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-emerald-100 text-emerald-700 hidden">
                                                            Sholat Dhuha
                                                        </span>

                                                        {{-- BADGE: DHUHUR (Hidden by Default) --}}
                                                        <span class="badge-dhuhur status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-orange-100 text-orange-700 hidden">
                                                            Sholat Dhuhur
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    {{-- Empty State --}}
                                    <div id="no-log-entry" class="hidden py-8 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-400 font-medium">Belum ada data scan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            let selectedType = 'Harian'; 
            const csrfToken = '{{ csrf_token() }}';
            const scanProcessUrl = '{{ route('scan.process') }}';
            
            const beepSound = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU"); 
            function playBeep() {
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);
                    oscillator.type = 'sine'; oscillator.frequency.value = 1000; 
                    gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                    oscillator.start(); oscillator.stop(audioCtx.currentTime + 0.15); 
                } catch (e) {}
            }

            const logTableBody = document.getElementById('scan-log');
            const scanStatus = document.getElementById('scan-status');
            const scanResult = document.getElementById('scan-result');
            const modeIndicator = document.getElementById('mode-indicator');
            let resultTimeout; 
            let isProcessing = false;

            const typeConfig = {
                'Harian': { activeClass: 'bg-blue-600 text-white shadow-lg shadow-blue-200', inactiveClass: 'bg-white text-gray-500 hover:bg-gray-100', indicatorClass: 'bg-blue-50 text-blue-600 border-blue-100' },
                'Dhuha': { activeClass: 'bg-emerald-600 text-white shadow-lg shadow-emerald-200', inactiveClass: 'bg-white text-gray-500 hover:bg-gray-100', indicatorClass: 'bg-emerald-50 text-emerald-600 border-emerald-100' },
                'Dhuhur': { activeClass: 'bg-orange-500 text-white shadow-lg shadow-orange-200', inactiveClass: 'bg-white text-gray-500 hover:bg-gray-100', indicatorClass: 'bg-orange-50 text-orange-600 border-orange-100' }
            };

            const clockElement = document.getElementById('clock');
            if(clockElement) {
                setInterval(() => { clockElement.textContent = new Date().toLocaleTimeString('id-ID', { hour12: false }); }, 1000);
            }

            const buttons = document.querySelectorAll('.scan-type-btn');
            function setActiveTab(type) {
                selectedType = type;
                const config = typeConfig[type];

                buttons.forEach(btn => {
                    btn.className = 'scan-type-btn relative group overflow-hidden rounded-xl py-3 px-4 transition-all duration-300 transform';
                    if (btn.getAttribute('data-type') === type) {
                        btn.classList.add(...config.activeClass.split(' '));
                        btn.classList.add('scale-105');
                    } else {
                        btn.classList.add(...config.inactiveClass.split(' '));
                    }
                });

                modeIndicator.innerHTML = `<span class="w-2 h-2 rounded-full bg-current animate-pulse"></span> Mode Aktif: ${type === 'Harian' ? 'Absensi Harian' : 'Sholat ' + type}`;
                modeIndicator.className = `mt-3 mx-1 p-2.5 rounded-lg text-center text-xs font-bold uppercase tracking-wide transition-all duration-300 flex items-center justify-center gap-2 ${config.indicatorClass}`;
                
                scanStatus.textContent = `Siap Scan: ${selectedType}`;
                updateTableLayout(selectedType);
                filterLogs(selectedType);
            }

            buttons.forEach(button => button.addEventListener('click', () => setActiveTab(button.getAttribute('data-type'))));
            setActiveTab('Harian');

            function updateTableLayout(type) {
                const harianCols = document.querySelectorAll('.col-harian');
                const prayerCols = document.querySelectorAll('.col-prayer');
                const thElements = document.querySelectorAll('thead th');
                
                // Toggle Badges
                const harianBadges = document.querySelectorAll('.badge-harian');
                const dhuhaBadges = document.querySelectorAll('.badge-dhuha');
                const dhuhurBadges = document.querySelectorAll('.badge-dhuhur');

                if (type === 'Harian') {
                    harianCols.forEach(el => el.style.display = '');
                    prayerCols.forEach(el => el.style.display = 'none');
                    if(thElements[4]) thElements[4].textContent = 'Status';
                    
                    harianBadges.forEach(el => el.classList.remove('hidden'));
                    dhuhaBadges.forEach(el => el.classList.add('hidden'));
                    dhuhurBadges.forEach(el => el.classList.add('hidden'));
                } else if (type === 'Dhuha') {
                    harianCols.forEach(el => el.style.display = 'none');
                    prayerCols.forEach(el => el.style.display = '');
                    if(thElements[3]) thElements[3].textContent = 'Waktu Sholat';
                    
                    harianBadges.forEach(el => el.classList.add('hidden'));
                    dhuhaBadges.forEach(el => el.classList.remove('hidden'));
                    dhuhurBadges.forEach(el => el.classList.add('hidden'));
                } else if (type === 'Dhuhur') {
                    harianCols.forEach(el => el.style.display = 'none');
                    prayerCols.forEach(el => el.style.display = '');
                    if(thElements[3]) thElements[3].textContent = 'Waktu Sholat';
                    
                    harianBadges.forEach(el => el.classList.add('hidden'));
                    dhuhaBadges.forEach(el => el.classList.add('hidden'));
                    dhuhurBadges.forEach(el => el.classList.remove('hidden'));
                }
            }

            const html5QrCode = new Html5Qrcode("qr-reader");
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                if (isProcessing) return;
                isProcessing = true;
                html5QrCode.pause();
                playBeep();
                scanStatus.textContent = `Memproses Data...`;
                if (decodedText.length < 3 || decodedText.length > 50) {
                     showScanResult('error', 'Format QR Code tidak valid.');
                     resumeScanner();
                     return;
                }
                processScanData(decodedText, selectedType);
            };

            const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    const rearCamera = cameras.find(camera => camera.label.toLowerCase().includes('back')) || cameras[0];
                    html5QrCode.start(rearCamera.id, config, qrCodeSuccessCallback).catch(() => {
                         html5QrCode.start(cameras[0].id, config, qrCodeSuccessCallback);
                    });
                } else { scanStatus.textContent = "Kamera tidak ditemukan"; }
            }).catch(err => { scanStatus.textContent = "Izin kamera ditolak"; });

            function resumeScanner() {
                setTimeout(() => {
                    html5QrCode.resume();
                    scanStatus.textContent = `Siap Scan: ${selectedType}`;
                    isProcessing = false; 
                }, 1500); 
            }

            async function processScanData(studentId, scanType) {
                try {
                    const response = await fetch(scanProcessUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ student_id: studentId, type: scanType })
                    });
                    const result = await response.json();
                    if (response.ok || response.status === 409 || response.status === 200) {
                        const msgType = response.status === 409 ? 'warning' : 'success';
                        showScanResult(msgType, result.message);
                        if (result.scan) {
                            const realType = result.scan.type === 'Harian' ? 'Harian' : (result.scan.activity || scanType);
                            updateOrCreateScanLog(result.scan, realType);
                        }
                    } else { showScanResult('error', result.message || `Error ${response.status}`); }
                } catch (error) { showScanResult('error', 'Gagal terhubung ke server.'); } finally { resumeScanner(); }
            }

            function showScanResult(type, message) {
                if (resultTimeout) clearTimeout(resultTimeout);
                scanResult.className = 'mt-4 p-4 rounded-2xl font-bold text-sm text-center transition-all duration-500 shadow-md transform scale-100 opacity-100';
                if (type === 'success') scanResult.classList.add('bg-green-100', 'text-green-800', 'border', 'border-green-200');
                else if (type === 'warning') scanResult.classList.add('bg-yellow-50', 'text-yellow-700', 'border', 'border-yellow-200');
                else scanResult.classList.add('bg-red-100', 'text-red-800', 'border', 'border-red-200');
                scanResult.innerHTML = `<span>${message}</span>`;
                scanResult.classList.remove('hidden');
                resultTimeout = setTimeout(() => {
                    scanResult.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => scanResult.classList.add('hidden'), 300);
                }, 5000); 
            }

            function updateOrCreateScanLog(scan, scanTypeProcessed) { 
                if (document.getElementById('no-log-entry')) document.getElementById('no-log-entry').classList.add('hidden'); 
                if (document.getElementById('empty-filter-msg')) document.getElementById('empty-filter-msg').remove();
                if (!scan.student) return;

                const rowId = `log-row-${scan.student.student_id}`; 
                let row = document.getElementById(rowId);

                if (!row) {
                    row = document.createElement('tr');
                    row.className = 'log-entry group hover:bg-white transition-colors rounded-xl animate-pulse'; 
                    row.id = rowId;
                    row.innerHTML = `
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">${scan.student.name}</div>
                            <div class="text-[10px] text-gray-400 font-mono bg-gray-100 inline-block px-1.5 rounded">${scan.student.student_id}</div>
                        </td>
                        <td class="col-harian log-time-in px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center">-</td>
                        <td class="col-harian log-time-out px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center">-</td>
                        <td class="col-prayer px-2 py-3 whitespace-nowrap text-gray-600 font-mono font-medium text-center hidden">
                            <span class="time-dhuha hidden">-</span>
                            <span class="time-dhuhur hidden">-</span>
                        </td>
                        <td class="log-status px-4 py-3 whitespace-nowrap text-right">
                            <span class="badge-harian status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-gray-100 text-gray-800">Baru</span>
                            <span class="badge-dhuha status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-emerald-100 text-emerald-700 hidden">Sholat Dhuha</span>
                            <span class="badge-dhuhur status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-orange-100 text-orange-700 hidden">Sholat Dhuhur</span>
                        </td>
                    `;
                    logTableBody.prepend(row);
                    setTimeout(() => row.classList.remove('animate-pulse'), 1000);
                }

                if (scanTypeProcessed === 'Harian') row.setAttribute('data-harian', 'true');
                else if (scanTypeProcessed === 'Dhuha') row.setAttribute('data-dhuha', 'true');
                else if (scanTypeProcessed === 'Dhuhur' || scanTypeProcessed === 'Duhur') row.setAttribute('data-dhuhur', 'true');

                const timeStr = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                const badgeHarian = row.querySelector('.badge-harian');
                const dhuhaSpan = row.querySelector('.time-dhuha');
                const dhuhurSpan = row.querySelector('.time-dhuhur');
                
                if (scan.type === 'Harian') {
                    if (scan.status === 'Masuk' || scan.status === 'Hadir') { 
                        row.querySelector('.log-time-in').textContent = timeStr;
                        badgeHarian.className = 'badge-harian status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-green-100 text-green-700';
                        badgeHarian.textContent = 'Masuk';
                    } else if (scan.status === 'Terlambat') { 
                        row.querySelector('.log-time-in').textContent = timeStr;
                        badgeHarian.className = 'badge-harian status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-amber-100 text-amber-700';
                        badgeHarian.textContent = 'Terlambat';
                    } else if (scan.status === 'Pulang') {
                        row.querySelector('.log-time-out').textContent = timeStr;
                        badgeHarian.className = 'badge-harian status-badge px-2.5 py-1 inline-flex text-[10px] leading-tight font-black uppercase tracking-wide rounded-lg bg-indigo-100 text-indigo-700';
                        badgeHarian.textContent = 'Pulang';
                    }
                } else {
                    let activityName = scan.activity || scanTypeProcessed;
                    if (activityName === 'Duhur') activityName = 'Dhuhur';

                    if (activityName === 'Dhuha') {
                        if(dhuhaSpan) { dhuhaSpan.textContent = timeStr; dhuhaSpan.classList.remove('hidden'); }
                    } else if (activityName === 'Dhuhur') {
                        if(dhuhurSpan) { dhuhurSpan.textContent = timeStr; dhuhurSpan.classList.remove('hidden'); }
                    }
                }

                updateTableLayout(selectedType);
                filterLogs(selectedType);
            }

            function filterLogs(type) {
                const existingMsg = document.getElementById('empty-filter-msg');
                if (existingMsg) existingMsg.remove();

                const rows = logTableBody.querySelectorAll('.log-entry');
                let visibleCount = 0;

                rows.forEach(row => {
                    let showRow = false;
                    const dhuhaSpan = row.querySelector('.time-dhuha');
                    const dhuhurSpan = row.querySelector('.time-dhuhur');
                    if(dhuhaSpan) dhuhaSpan.classList.add('hidden');
                    if(dhuhurSpan) dhuhurSpan.classList.add('hidden');

                    if (type === 'Harian') {
                        showRow = row.getAttribute('data-harian') === 'true';
                    } else if (type === 'Dhuha') {
                        showRow = row.getAttribute('data-dhuha') === 'true';
                        if (showRow && dhuhaSpan) dhuhaSpan.classList.remove('hidden');
                    } else if (type === 'Dhuhur') {
                        showRow = row.getAttribute('data-dhuhur') === 'true';
                        if (showRow && dhuhurSpan) dhuhurSpan.classList.remove('hidden');
                    }

                    row.style.display = showRow ? '' : 'none';
                    if (showRow) visibleCount++;
                });

                if (rows.length > 0 && visibleCount === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.id = 'empty-filter-msg';
                    emptyRow.innerHTML = `<td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium italic">Belum ada data untuk ${type === 'Harian' ? 'Absensi Harian' : 'Sholat ' + type}</td>`;
                    logTableBody.appendChild(emptyRow);
                }
                const noLogEntry = document.getElementById('no-log-entry');
                if (noLogEntry) noLogEntry.classList.toggle('hidden', rows.length > 0);
            }
        });
    </script>
</x-app-layout>