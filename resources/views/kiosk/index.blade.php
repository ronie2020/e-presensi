@extends('layouts.kiosk-layout')

@section('content')
<div class="min-h-screen bg-gray-900 flex flex-col items-center justify-center relative overflow-hidden">
    
    <!-- Background Accent (Hiasan) -->
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
    
    <!-- Footer / Copyright (Diperbaiki) -->
    <div class="absolute bottom-0 w-full text-center py-4 text-gray-600 text-xs z-10">
        Sistem Absensi Terpadu &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. All Rights Reserved.
    </div>

    <div class="w-full max-w-3xl mx-auto text-center p-8 z-10">
        
        <!-- Header & Logo -->
        <div class="mb-8 animate-fade-in-down">
            <div class="inline-block p-4 rounded-full bg-gray-800/50 mb-4 border border-gray-700 shadow-lg">
                <x-application-logo class="w-20 h-20 mx-auto" />
            </div>
            <h1 class="text-5xl font-black text-white tracking-tight">SMP NEGERI 3 LAKBOK</h1>
            <p class="text-xl text-blue-400 mt-2 font-medium uppercase tracking-widest">Station Absensi Mandiri</p>
        </div>

        <!-- Jam Besar -->
        <div class="my-8 bg-gray-800/50 rounded-3xl p-8 border border-gray-700/50 shadow-2xl backdrop-blur-sm">
            <div id="kiosk-clock" class="text-8xl font-black text-white tracking-widest font-mono" style="text-shadow: 0 0 20px rgba(59, 130, 246, 0.5);">
                00:00:00
            </div>
            <div id="kiosk-date" class="text-2xl text-gray-400 mt-2 font-light">
                Memuat Tanggal...
            </div>
        </div>

        <!-- Status Box (Tempat Feedback Muncul) -->
        <div id="status-box" class="w-full h-64 bg-gray-800 rounded-3xl p-8 border-2 border-dashed border-gray-600 flex flex-col items-center justify-center transition-all duration-300 relative overflow-hidden group">
            
            <!-- Tampilan Default (Standby) -->
            <div id="state-standby" class="flex flex-col items-center transition-opacity duration-300">
                <div class="relative mb-4">
                    <div class="absolute inset-0 bg-blue-500 blur-xl opacity-20 rounded-full animate-pulse"></div>
                    <svg class="w-20 h-20 text-gray-400 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg>
                </div>
                <p class="text-2xl font-bold text-gray-300">Silakan Tap Kartu atau Scan QR</p>
                <p class="text-gray-500 mt-2 animate-pulse">Menunggu input...</p>
            </div>

            <!-- Tampilan Loading (Sedang Memproses) -->
            <div id="state-loading" class="hidden flex-col items-center absolute inset-0 justify-center bg-gray-800 z-20">
                <svg class="animate-spin h-16 w-16 text-blue-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-xl font-bold text-blue-400">Memproses Data...</p>
            </div>

            <!-- Tampilan Hasil (Sukses/Gagal) -->
            <div id="state-result" class="hidden flex-col items-center justify-center absolute inset-0 z-30 w-full h-full">
                <!-- Content injected by JS -->
            </div>

        </div>
        
        <!-- Tombol Kembali -->
        <div class="mt-8 relative z-50">
            <a href="{{ route('landing') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-white transition-colors group px-4 py-2 rounded-lg hover:bg-gray-800">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Halaman Depan
            </a>
        </div>

        {{-- INPUT TERSEMBUNYI (PERANGKAP SCANNER) --}}
        <input type="text" id="scan-input" class="absolute opacity-0 -top-full" autocomplete="off" autofocus>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- VARIABLE INITIALIZATION ---
        const clockElement = document.getElementById('kiosk-clock');
        const dateElement = document.getElementById('kiosk-date');
        const scanInput = document.getElementById('scan-input');
        const statusBox = document.getElementById('status-box');
        
        // States Elements
        const stateStandby = document.getElementById('state-standby');
        const stateLoading = document.getElementById('state-loading');
        const stateResult = document.getElementById('state-result');

        const csrfToken = '{{ csrf_token() }}';
        const processUrl = '{{ route('kiosk.process') }}';

        // Inisialisasi Audio Context
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        const audioContext = new AudioContext();
        let isProcessing = false;

        // --- 0. AUDIO UNLOCKER (PENTING UNTUK BROWSER MODERN) ---
        // Browser memblokir audio sampai user berinteraksi. Kita 'pancing' resume saat ada interaksi apapun.
        function unlockAudio() {
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
        }
        document.body.addEventListener('click', unlockAudio);
        document.body.addEventListener('keydown', unlockAudio); // Scanner dianggap keyboard, ini akan memicu audio resume
        document.body.addEventListener('touchstart', unlockAudio);

        // --- 1. FUNGSI AUDIO BEEP ---
        function playBeep(type) {
            // Pastikan context aktif sebelum mainkan suara
            if (audioContext.state === 'suspended') audioContext.resume();
            
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            if (type === 'success') {
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioContext.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(1760, audioContext.currentTime + 0.1);
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.5);
                oscillator.start();
                oscillator.stop(audioContext.currentTime + 0.5);
            } else {
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(150, audioContext.currentTime);
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.001, audioContext.currentTime + 0.3);
                oscillator.start();
                oscillator.stop(audioContext.currentTime + 0.3);
            }
        }


        // --- 2. FUNGSI JAM DIGITAL ---
        function updateTime() {
            const now = new Date();
            clockElement.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            dateElement.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

            // Refresh halaman otomatis setiap jam 12 malam untuk reset cache/memory
            if (now.getHours() === 0 && now.getMinutes() === 0 && now.getSeconds() === 0) {
                window.location.reload();
            }
        }
        setInterval(updateTime, 1000);
        updateTime();


        // --- 3. EVENT LISTENER SCANNER ---
        
        function focusInput() {
            if (!isProcessing) scanInput.focus();
        }
        
        // Logic Focus Trap agar scanner selalu aktif
        document.addEventListener('click', function(e) {
            // Jika klik pada link/button, biarkan. Jika klik area kosong, fokuskan ke scanner
            if (e.target.closest('a') || e.target.closest('button')) {
                return; 
            }
            focusInput();
        });
        
        scanInput.addEventListener('blur', () => {
            setTimeout(() => {
                const activeEl = document.activeElement;
                if (activeEl === document.body || !activeEl) {
                    focusInput();
                }
            }, 50); // Timeout sedikit diperlambat agar lebih toleran
        });

        // Listener Input dari Scanner
        scanInput.addEventListener('change', function(e) {
            const scanData = e.target.value.trim();
            e.target.value = ''; // Langsung bersihkan input

            if (!scanData || isProcessing) return;

            processScan(scanData);
        });


        // --- 4. LOGIKA PEMROSESAN DATA ---
        async function processScan(data) {
            isProcessing = true;
            scanInput.blur(); // Hilangkan fokus agar user tidak scan double saat loading
            showUIState('loading');

            try {
                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ scan_data: data })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    playBeep('success');
                    showResultUI('success', result.student_name, result.message);
                } else {
                    playBeep('error');
                    const errorMsg = result.message || 'Data tidak ditemukan';
                    const studentName = result.student_name || 'Tidak Dikenal';
                    showResultUI('error', studentName, errorMsg);
                }

            } catch (error) {
                console.error('Fetch Error:', error);
                playBeep('error');
                
                // Cek status koneksi internet
                let errorTitle = 'Kesalahan Sistem';
                let errorDesc = 'Gagal menghubungi server.';
                
                if (!navigator.onLine) {
                    errorTitle = 'Koneksi Terputus';
                    errorDesc = 'Mohon periksa koneksi internet / WiFi.';
                }

                showResultUI('error', errorTitle, errorDesc);
            } finally {
                setTimeout(() => {
                    showUIState('standby');
                    isProcessing = false;
                    scanInput.focus(); // Kembalikan fokus ke scanner
                }, 3000); // Tampilkan hasil selama 3 detik
            }
        }


        // --- 5. MANAJEMEN UI ---
        function showUIState(state) {
            stateStandby.classList.add('hidden');
            stateLoading.classList.add('hidden');
            stateLoading.classList.remove('flex');
            stateResult.classList.add('hidden');
            stateResult.classList.remove('flex');
            
            // Reset style border
            statusBox.className = "w-full h-64 bg-gray-800 rounded-3xl p-8 border-2 border-dashed border-gray-600 flex flex-col items-center justify-center transition-all duration-300 relative overflow-hidden";

            if (state === 'standby') {
                stateStandby.classList.remove('hidden');
            } else if (state === 'loading') {
                stateLoading.classList.remove('hidden');
                stateLoading.classList.add('flex');
                statusBox.classList.remove('border-gray-600');
                statusBox.classList.add('border-blue-500');
            }
        }

        function showResultUI(type, title, message) {
            stateStandby.classList.add('hidden');
            stateLoading.classList.add('hidden');
            stateResult.classList.remove('hidden');
            stateResult.classList.add('flex');

            const bgColor = type === 'success' ? 'bg-emerald-600' : 'bg-red-600';
            const icon = type === 'success' 
                ? '<svg class="w-24 h-24 text-white mb-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                : '<svg class="w-24 h-24 text-white mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';

            statusBox.className = `w-full h-64 rounded-3xl p-8 border-4 border-white/20 flex flex-col items-center justify-center transition-all duration-300 relative overflow-hidden shadow-2xl ${bgColor}`;

            stateResult.innerHTML = `
                ${icon}
                <h2 class="text-3xl font-black text-white mb-2 text-center leading-none tracking-tight">${title}</h2>
                <p class="text-xl text-white/90 font-medium text-center">${message}</p>
            `;
        }

    });
</script>
@endsection