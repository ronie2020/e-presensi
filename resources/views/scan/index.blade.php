{{-- Halaman ini adalah tampilan untuk resources/views/scan/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Aktifitas Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- JAM DIGITAL --}}
                    <div class="text-center mb-8">
                        <div id="clock" class="text-5xl font-extrabold text-gray-800 tracking-widest font-mono">
                            00:00:00
                        </div>
                        <p class="text-gray-500 mt-2 text-sm font-medium uppercase tracking-wide">
                            Waktu Terkini
                        </p>
                    </div>

                    {{-- Tombol Pilihan Mode --}}
                    <div class="mb-6">
                        <div class="flex space-x-2 border-b border-gray-200 pb-1">
                            <button data-type="Harian" class="scan-type-btn w-1/3 py-4 px-6 font-bold text-lg rounded-t-lg transition-all duration-300 border-b-4">
                                Absen Harian
                            </button>
                            <button data-type="Dhuha" class="scan-type-btn w-1/3 py-4 px-6 font-bold text-lg rounded-t-lg transition-all duration-300 border-b-4">
                                Sholat Dhuha
                            </button>
                            {{-- PASTIKAN TULISAN DATA-TYPE KONSISTEN 'Dhuhur' --}}
                            <button data-type="Dhuhur" class="scan-type-btn w-1/3 py-4 px-6 font-bold text-lg rounded-t-lg transition-all duration-300 border-b-4">
                                Sholat Dhuhur
                            </button>
                        </div>
                        
                        <div id="mode-indicator" class="mt-4 p-3 rounded-lg text-center font-semibold text-sm transition-colors duration-300">
                            Mode Aktif: Absen Harian
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- Kamera --}}
                        <div class="flex flex-col items-center justify-center bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-300" id="camera-container">
                            <div id="qr-reader" class="w-full rounded-lg overflow-hidden shadow-sm" style="max-width: 400px;"></div>
                            <p class="mt-4 text-sm text-gray-500 text-center font-medium animate-pulse">
                                Arahkan Kartu / QR Code ke Kamera
                            </p>
                            <div id="scan-result" class="mt-4 p-4 w-full text-center rounded-lg font-bold text-lg hidden transition-all duration-500 shadow-md"></div>
                            <div id="scan-status" class="mt-2 text-xs text-gray-400">Kamera Siap...</div>
                        </div>

                        {{-- List Riwayat / Status Siswa --}}
                        <div class="flex flex-col h-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Riwayat Scan Hari Ini
                                </h3>
                                <span class="text-[10px] bg-gray-100 px-2 py-1 rounded-md text-gray-500 font-bold uppercase">Live Update</span>
                            </div>
                            
                            <div class="overflow-x-auto border border-gray-100 rounded-xl flex-1 bg-white shadow-sm max-h-[400px] overflow-y-auto relative custom-scrollbar">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                                            <th class="col-harian px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Masuk</th>
                                            <th class="col-harian px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Pulang</th>
                                            <th class="col-prayer px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider hidden">Waktu</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scan-log" class="bg-white divide-y divide-gray-50">
                                        {{-- Iterasi data scan terbaru --}}
                                        @foreach($recentScans as $scan)
                                            <tr class="log-entry hover:bg-blue-50/50 transition-colors" id="log-row-{{ $scan['student_id'] }}" 
                                                data-harian="{{ $scan['data_harian'] ? 'true' : 'false' }}"
                                                data-dhuha="{{ $scan['data_dhuha'] ? 'true' : 'false' }}"
                                                data-dhuhur="{{ $scan['data_dhuhur'] ? 'true' : 'false' }}">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-800">{{ $scan['student_name'] }}</div>
                                                    <div class="text-xs text-gray-400 font-mono">{{ $scan['student_id'] }}</div>
                                                </td>
                                                
                                                <td class="col-harian log-time-in px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-mono text-center">
                                                    {{ $scan['time_in'] ? \Carbon\Carbon::parse($scan['time_in'])->format('H:i') : '-' }}
                                                </td>
                                                <td class="col-harian log-time-out px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-mono text-center">
                                                    {{ $scan['time_out'] ? \Carbon\Carbon::parse($scan['time_out'])->format('H:i') : '-' }}
                                                </td>

                                                <td class="col-prayer px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-mono text-center hidden">
                                                    <span class="time-dhuha {{ $scan['dhuha_time'] ? '' : 'hidden' }}">
                                                        {{ $scan['dhuha_time'] ? \Carbon\Carbon::parse($scan['dhuha_time'])->format('H:i') : '-' }}
                                                    </span>
                                                    <span class="time-dhuhur {{ $scan['dhuhur_time'] ? '' : 'hidden' }}">
                                                        {{ $scan['dhuhur_time'] ? \Carbon\Carbon::parse($scan['dhuhur_time'])->format('H:i') : '-' }}
                                                    </span>
                                                </td>

                                                <td class="log-status px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                    {{-- Logic Badge Server Side --}}
                                                    <span class="status-badge px-2 py-1 inline-flex text-[10px] leading-tight font-bold uppercase rounded-md 
                                                        {{ $scan['status'] == 'Masuk' ? 'bg-green-100 text-green-700' : 
                                                           ($scan['status'] == 'Pulang' ? 'bg-indigo-100 text-indigo-700' : 
                                                           ($scan['status'] == 'Dhuha' ? 'bg-emerald-100 text-emerald-700' : 
                                                           ($scan['status'] == 'Dhuhur' || $scan['status'] == 'Duhur' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600'))) }}">
                                                        {{ $scan['status'] == 'Duhur' ? 'Dhuhur' : $scan['status'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
            const logTableBody = document.getElementById('scan-log');
            let noLogEntry = document.getElementById('no-log-entry'); 
            const scanStatus = document.getElementById('scan-status');
            const scanResult = document.getElementById('scan-result');
            const modeIndicator = document.getElementById('mode-indicator');
            const cameraContainer = document.getElementById('camera-container');

            // --- KONFIGURASI WARNA TAB ---
            const typeColors = {
                'Harian': { text: 'text-blue-600', border: 'border-blue-600', bg: 'bg-blue-50', indicatorBg: 'bg-blue-100', indicatorText: 'text-blue-800', borderColor: 'border-blue-300' },
                'Dhuha': { text: 'text-emerald-600', border: 'border-emerald-600', bg: 'bg-emerald-50', indicatorBg: 'bg-emerald-100', indicatorText: 'text-emerald-800', borderColor: 'border-emerald-300' },
                'Dhuhur': { text: 'text-orange-600', border: 'border-orange-600', bg: 'bg-orange-50', indicatorBg: 'bg-orange-100', indicatorText: 'text-orange-800', borderColor: 'border-orange-300' }
            };

            const clockElement = document.getElementById('clock');
            setInterval(() => {
                clockElement.textContent = new Date().toLocaleTimeString('id-ID', { hour12: false });
            }, 1000);

            const buttons = document.querySelectorAll('.scan-type-btn');
            
            function setActiveTab(type) {
                selectedType = type;
                const colors = typeColors[type];

                buttons.forEach(btn => {
                    btn.className = 'scan-type-btn w-1/3 py-4 px-6 font-medium text-lg rounded-t-lg transition-all duration-300 border-b-4 border-transparent text-gray-400 hover:text-gray-600 hover:bg-gray-50';
                    if (btn.getAttribute('data-type') === type) {
                        btn.className = `scan-type-btn w-1/3 py-4 px-6 font-bold text-lg rounded-t-lg transition-all duration-300 border-b-4 ${colors.border} ${colors.text} ${colors.bg}`;
                    }
                });

                modeIndicator.textContent = `Mode Aktif: ${type === 'Harian' ? 'Absensi Harian' : 'Sholat ' + type}`;
                modeIndicator.className = `mt-4 p-3 rounded-lg text-center font-bold text-sm transition-colors duration-300 ${colors.indicatorBg} ${colors.indicatorText}`;
                cameraContainer.className = `flex flex-col items-center justify-center p-4 rounded-lg border-4 border-dashed transition-colors duration-500 ${colors.borderColor} bg-gray-50`;
                scanStatus.textContent = `Mode: ${selectedType}. Arahkan QR Code.`;
                
                updateTableLayout(selectedType);
                filterLogs(selectedType);
            }

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    setActiveTab(button.getAttribute('data-type'));
                });
            });
            
            setActiveTab('Harian');

            function updateTableLayout(type) {
                const harianCols = document.querySelectorAll('.col-harian');
                const prayerCols = document.querySelectorAll('.col-prayer');
                const thElements = document.querySelectorAll('thead th');

                if (type === 'Harian') {
                    harianCols.forEach(el => el.style.display = '');
                    prayerCols.forEach(el => el.style.display = 'none');
                    if(thElements[4]) thElements[4].textContent = 'Status'; 
                } else {
                    harianCols.forEach(el => el.style.display = 'none');
                    prayerCols.forEach(el => el.style.display = '');
                    if(thElements[3]) thElements[3].textContent = 'Waktu Sholat'; 
                }
            }

            const html5QrCode = new Html5Qrcode("qr-reader");
            
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                html5QrCode.pause();
                scanStatus.textContent = `Memproses ID: ${decodedText}...`;
                scanResult.classList.add('hidden');
                
                if (decodedText.length < 3 || decodedText.length > 20) {
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
                }
            });

            function resumeScanner() {
                setTimeout(() => {
                    html5QrCode.resume();
                    scanStatus.textContent = `Mode: ${selectedType}. Arahkan QR Code.`;
                }, 2000);
            }

            async function processScanData(studentId, scanType) {
                try {
                    const response = await fetch(scanProcessUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ student_id: studentId, type: scanType })
                    });
                    const result = await response.json();

                    if (response.ok || response.status === 409) {
                        showScanResult(response.ok ? 'success' : 'warning', result.message);
                        if (result.scan) {
                            // Deteksi tipe yang benar (Harian atau Keagamaan)
                            const realType = result.scan.type === 'Harian' ? 'Harian' : (result.scan.activity || scanType);
                            updateOrCreateScanLog(result.scan, realType);
                        }
                    } else {
                        showScanResult('error', result.message || `Error ${response.status}`);
                    }
                } catch (error) {
                    showScanResult('error', 'Gagal terhubung ke server.');
                } finally {
                    resumeScanner();
                }
            }

            function updateOrCreateScanLog(scan, scanTypeProcessed) { 
                if (document.getElementById('no-log-entry')) document.getElementById('no-log-entry').remove(); 
                if (document.getElementById('empty-filter-msg')) document.getElementById('empty-filter-msg').remove();

                if (!scan.student) return;

                const rowId = `log-row-${scan.student.student_id}`; // Pastikan ID ini match
                let row = document.getElementById(rowId);

                // Buat row baru jika belum ada
                if (!row) {
                    row = document.createElement('tr');
                    row.className = 'log-entry hover:bg-blue-50/50 transition-colors';
                    row.id = rowId;
                    row.setAttribute('data-student-id', scan.student.student_id);
                    row.innerHTML = `
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-800">${scan.student.name}</div>
                            <div class="text-xs text-gray-400 font-mono">${scan.student.student_id}</div>
                        </td>
                        <td class="col-harian log-time-in px-4 py-3 text-center text-sm">-</td>
                        <td class="col-harian log-time-out px-4 py-3 text-center text-sm">-</td>
                        <td class="col-prayer px-4 py-3 text-center text-sm hidden">
                            <span class="time-dhuha hidden">-</span>
                            <span class="time-dhuhur hidden">-</span>
                        </td>
                        <td class="log-status px-4 py-3 text-right text-sm">
                            <span class="status-badge px-2 py-1 inline-flex text-[10px] font-bold uppercase rounded-md bg-gray-100 text-gray-800">Baru</span>
                        </td>
                    `;
                    logTableBody.prepend(row);
                }

                // Update Data Attribute untuk Filter
                if (scanTypeProcessed === 'Harian') row.setAttribute('data-harian', 'true');
                else if (scanTypeProcessed === 'Dhuha') row.setAttribute('data-dhuha', 'true');
                else if (scanTypeProcessed === 'Dhuhur' || scanTypeProcessed === 'Duhur') row.setAttribute('data-dhuhur', 'true');

                const timeStr = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                const badge = row.querySelector('.status-badge');
                const dhuhaSpan = row.querySelector('.time-dhuha');
                const dhuhurSpan = row.querySelector('.time-dhuhur');
                
                // === LOGIKA BADGE (PERBAIKAN UTAMA) ===
                if (scan.type === 'Harian') {
                    if (scan.status === 'Masuk') {
                        row.querySelector('.log-time-in').textContent = timeStr;
                        badge.className = 'status-badge px-2 py-1 inline-flex text-[10px] leading-tight font-bold uppercase rounded-md bg-green-100 text-green-700';
                        badge.textContent = 'Masuk';
                    } else if (scan.status === 'Pulang') {
                        row.querySelector('.log-time-out').textContent = timeStr;
                        badge.className = 'status-badge px-2 py-1 inline-flex text-[10px] leading-tight font-bold uppercase rounded-md bg-indigo-100 text-indigo-700';
                        badge.textContent = 'Pulang';
                    }
                } else {
                    // KEAGAMAAN (DHUHA / DHUHUR)
                    // Kita paksa baca dari scanTypeProcessed jika scan.activity kosong/typo
                    let activityName = scan.activity || scanTypeProcessed;
                    
                    // Normalisasi teks (Duhur -> Dhuhur)
                    if (activityName === 'Duhur') activityName = 'Dhuhur';

                    if (activityName === 'Dhuha') {
                        if(dhuhaSpan) { dhuhaSpan.textContent = timeStr; dhuhaSpan.classList.remove('hidden'); }
                        badge.className = 'status-badge px-2 py-1 inline-flex text-[10px] leading-tight font-bold uppercase rounded-md bg-emerald-100 text-emerald-700';
                    } else if (activityName === 'Dhuhur') {
                        if(dhuhurSpan) { dhuhurSpan.textContent = timeStr; dhuhurSpan.classList.remove('hidden'); }
                        badge.className = 'status-badge px-2 py-1 inline-flex text-[10px] leading-tight font-bold uppercase rounded-md bg-orange-100 text-orange-700';
                    }
                    
                    // Paksa teks badge berubah (overwrite "Belum Absen")
                    badge.textContent = activityName;
                }

                updateTableLayout(selectedType);
                filterLogs(selectedType);
            }

            function filterLogs(type) {
                const rows = logTableBody.querySelectorAll('.log-entry');
                let visibleCount = 0;

                rows.forEach(row => {
                    let showRow = false;
                    // Reset visibility span dulu
                    const dhuhaSpan = row.querySelector('.time-dhuha');
                    const dhuhurSpan = row.querySelector('.time-dhuhur');
                    if(dhuhaSpan) dhuhaSpan.classList.add('hidden');
                    if(dhuhurSpan) dhuhurSpan.classList.add('hidden');

                    if (type === 'Harian') {
                        showRow = row.getAttribute('data-harian') === 'true';
                    } else if (type === 'Dhuha') {
                        showRow = row.getAttribute('data-dhuha') === 'true';
                        // Tampilkan jam jika baris ini punya data dhuha
                        if (showRow && dhuhaSpan) dhuhaSpan.classList.remove('hidden');
                    } else if (type === 'Dhuhur') {
                        showRow = row.getAttribute('data-dhuhur') === 'true';
                        // Tampilkan jam jika baris ini punya data dhuhur
                        if (showRow && dhuhurSpan) dhuhurSpan.classList.remove('hidden');
                    }

                    row.style.display = showRow ? '' : 'none';
                    if (showRow) visibleCount++;
                });

                if (rows.length > 0 && visibleCount === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.id = 'empty-filter-msg';
                    emptyRow.innerHTML = `<td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400 italic">Belum ada data untuk ${type}</td>`;
                    logTableBody.appendChild(emptyRow);
                }
            }

            function showScanResult(type, message) {
                scanResult.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800', 'bg-yellow-100', 'text-yellow-800');
                if (type === 'success') scanResult.classList.add('bg-green-100', 'text-green-800');
                else if (type === 'warning') scanResult.classList.add('bg-yellow-100', 'text-yellow-800');
                else scanResult.classList.add('bg-red-100', 'text-red-800');
                scanResult.textContent = message;
            }
        });
    </script>
</x-app-layout>