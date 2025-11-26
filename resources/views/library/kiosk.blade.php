@extends('layouts.kiosk-layout')

@section('content')
{{-- Ganti background jadi nuansa Emerald/Hijau Tenang khas Perpustakaan --}}
<div class="min-h-screen bg-gray-900 flex flex-col items-center justify-center relative overflow-hidden">
    
    <!-- Background Accent -->
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>
    <div class="absolute bottom-0 w-full text-center py-4 text-gray-600 text-xs">
        Perpustakaan Digital &copy; {{ date('Y') }} SMP Negeri 3 Lakbok
    </div>

    <div class="w-full max-w-3xl mx-auto text-center p-8 z-10">
        
        <!-- Header -->
        <div class="mb-8 animate-fade-in-down">
            <div class="inline-block p-4 rounded-full bg-gray-800/50 mb-4 border border-gray-700 shadow-lg">
                <x-application-logo class="w-20 h-20 mx-auto" />
            </div>
            <h1 class="text-4xl font-black text-white tracking-tight">BUKU TAMU PERPUSTAKAAN</h1>
            <p class="text-lg text-emerald-400 mt-2 font-medium uppercase tracking-widest">Silakan Scan Kartu Anggota</p>
        </div>

        <!-- Jam Digital -->
        <div class="my-8 bg-gray-800/50 rounded-3xl p-6 border border-emerald-500/30 shadow-[0_0_30px_rgba(16,185,129,0.15)] backdrop-blur-sm">
            <div id="kiosk-clock" class="text-7xl font-black text-white tracking-widest font-mono" style="text-shadow: 0 0 20px rgba(16, 185, 129, 0.5);">
                00:00:00
            </div>
            <div id="kiosk-date" class="text-xl text-gray-400 mt-2 font-light">Loading...</div>
        </div>

        <!-- Status Box -->
        <div id="status-box" class="w-full h-64 bg-gray-800 rounded-3xl p-8 border-2 border-dashed border-gray-600 flex flex-col items-center justify-center transition-all duration-300 relative overflow-hidden">
            
            <!-- Standby -->
            <div id="state-standby" class="flex flex-col items-center transition-opacity duration-300">
                <div class="relative mb-4">
                    <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-20 rounded-full animate-pulse"></div>
                    <svg class="w-20 h-20 text-gray-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg>
                </div>
                <p class="text-2xl font-bold text-gray-300">Tap Kartu / Scan QR</p>
            </div>

            <!-- Result -->
            <div id="state-result" class="hidden flex-col items-center justify-center absolute inset-0 z-30 w-full h-full bg-gray-800">
                <!-- Content injected via JS -->
            </div>
        </div>
        
        <div class="mt-8 relative z-50">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-white transition-colors">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <input type="text" id="scan-input" class="absolute opacity-0 -top-full" autocomplete="off" autofocus>
    </div>
</div>

{{-- SCRIPT SAMA SEPERTI KIOSK ABSENSI, HANYA BEDA URL ENDPOINT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clockEl = document.getElementById('kiosk-clock');
        const dateEl = document.getElementById('kiosk-date');
        const scanInput = document.getElementById('scan-input');
        const statusBox = document.getElementById('status-box');
        const stateResult = document.getElementById('state-result');
        const stateStandby = document.getElementById('state-standby');

        const processUrl = '{{ route('library.kiosk.process') }}'; // URL PROSES PERPUSTAKAAN
        const csrfToken = '{{ csrf_token() }}';
        let isProcessing = false;

        // Audio Context (Sama seperti Kiosk Absen)
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            
            if (type === 'success') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(1200, audioCtx.currentTime); // Nada lebih tinggi untuk perpus
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.3);
            } else {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(150, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.5);
            }
        }

        // Jam Digital
        function updateTime() {
            const now = new Date();
            clockEl.innerText = now.toLocaleTimeString('id-ID', {hour12:false});
            dateEl.innerText = now.toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Focus Keeper
        document.addEventListener('click', (e) => {
            if(!e.target.closest('a')) scanInput.focus();
        });
        scanInput.addEventListener('blur', () => setTimeout(() => scanInput.focus(), 10));

        // Scan Listener
        scanInput.addEventListener('change', async (e) => {
            const code = e.target.value.trim();
            e.target.value = '';
            if(!code || isProcessing) return;

            isProcessing = true;
            
            // UI Loading
            stateStandby.classList.add('hidden');
            statusBox.className = "w-full h-64 rounded-3xl p-8 border-4 border-emerald-500 flex flex-col items-center justify-center bg-gray-800";
            stateResult.classList.remove('hidden');
            stateResult.classList.add('flex');
            stateResult.innerHTML = '<p class="text-xl text-emerald-400 animate-pulse">Memproses...</p>';

            try {
                const res = await fetch(processUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ scan_data: code })
                });
                const data = await res.json();

                if(data.success) {
                    playBeep('success');
                    statusBox.className = "w-full h-64 rounded-3xl p-8 border-none bg-emerald-600 flex flex-col items-center justify-center shadow-2xl transition-all";
                    stateResult.innerHTML = `
                        <svg class="w-20 h-20 text-white mb-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <h2 class="text-3xl font-black text-white text-center">${data.student_name}</h2>
                        <p class="text-lg text-white/90 mt-1">${data.message}</p>
                    `;
                } else {
                    playBeep('error');
                    statusBox.className = "w-full h-64 rounded-3xl p-8 border-none bg-rose-600 flex flex-col items-center justify-center shadow-2xl transition-all";
                    stateResult.innerHTML = `
                        <svg class="w-20 h-20 text-white mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <h2 class="text-2xl font-bold text-white text-center">Gagal</h2>
                        <p class="text-white/90 mt-1">${data.message}</p>
                    `;
                }
            } catch (err) {
                console.error(err);
            }

            setTimeout(() => {
                stateResult.classList.add('hidden');
                stateResult.classList.remove('flex');
                stateStandby.classList.remove('hidden');
                statusBox.className = "w-full h-64 bg-gray-800 rounded-3xl p-8 border-2 border-dashed border-gray-600 flex flex-col items-center justify-center relative overflow-hidden";
                isProcessing = false;
            }, 3000);
        });
    });
</script>
@endsection