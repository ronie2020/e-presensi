@extends('layouts.kiosk-layout')

@section('content')
{{-- 
    LAYOUT: FULL SCREEN SPLIT + MAXIMIZED SCANNER
    - Header (Jam, Logo, Judul) diperkecil.
    - Kotak Scan dibuat 'flex-1' agar mengisi seluruh sisa ruang vertikal (menjadi sangat besar).
--}}

<div class="h-screen w-full bg-gray-900 flex relative overflow-hidden font-sans">
    
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 z-50"></div>
    <div class="absolute top-10 left-10 w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-10 right-1/3 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px] animate-pulse"></div>

    <!-- Tombol Kembali -->
    <a href="{{ route('dashboard') }}" class="absolute top-6 left-6 z-50 flex items-center gap-2 px-4 py-2 bg-gray-800/80 hover:bg-emerald-600 text-white rounded-full transition-all border border-gray-700 hover:border-emerald-500 shadow-lg group backdrop-blur-md">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span class="font-medium text-xs">Kembali</span>
    </a>

    <!-- CONTAINER UTAMA -->
    <div class="flex w-full h-full p-6 gap-6 pt-10">
        
        <!-- BAGIAN KIRI: SCANNER (Area Utama) -->
        <div class="flex-1 flex flex-col items-center relative z-10 h-full">
            
            <!-- 1. HEADER (Diperkecil agar hemat tempat) -->
            <div class="text-center mb-6 animate-fade-in-down w-full flex flex-col items-center shrink-0">
                
                <!-- Logo & Jam Baris Sejajar (Opsional) atau Tumpuk Compact -->
                <div class="flex items-center gap-6 mb-2">
                     <!-- Logo Kecil -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-emerald-500 blur-lg opacity-20 group-hover:opacity-40 rounded-full"></div>
                        <div class="relative p-2 bg-gray-800/80 rounded-full border border-gray-700/50 shadow-lg backdrop-blur-sm">
                            <img src="{{ asset('images/logo.png') }}" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" 
                                 alt="Logo" 
                                 class="w-12 h-12 object-contain">
                            <svg class="w-12 h-12 text-emerald-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>

                    <!-- Jam Digital (Ukuran Sedang) -->
                    <div id="kiosk-clock" class="text-6xl font-black text-white tracking-tighter font-mono leading-none drop-shadow-md">
                        00.00.00
                    </div>
                </div>
                
                <!-- Judul (Ukuran Sedang) -->
                <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 tracking-tight uppercase">
                    ABSENSI PERPUSTAKAAN
                </h1>
                <p class="text-sm text-gray-400 font-medium tracking-[0.3em] uppercase mt-1">SMP Negeri 3 Lakbok</p>
            </div>

            <!-- 2. BOX SCANNER (Memenuhi Sisa Ruang / Flex-1) -->
            <div id="status-box" class="w-full max-w-4xl flex-1 bg-gray-900/40 backdrop-blur-sm rounded-3xl flex flex-col items-center justify-center relative transition-all duration-300 group overflow-visible border border-white/5 mb-8">
                
                <!-- Siku Viewfinder (Tetap Ada) -->
                <div class="absolute -top-1 -left-1 w-16 h-16 border-t-4 border-l-4 border-emerald-500 rounded-tl-3xl z-20 shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                <div class="absolute -top-1 -right-1 w-16 h-16 border-t-4 border-r-4 border-emerald-500 rounded-tr-3xl z-20 shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                <div class="absolute -bottom-1 -left-1 w-16 h-16 border-b-4 border-l-4 border-emerald-500 rounded-bl-3xl z-20 shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                <div class="absolute -bottom-1 -right-1 w-16 h-16 border-b-4 border-r-4 border-emerald-500 rounded-br-3xl z-20 shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>

                <!-- Laser Scan (Full Height Animation) -->
                <div id="scan-laser" class="absolute top-0 left-6 right-6 h-2 bg-gradient-to-r from-transparent via-emerald-400 to-transparent shadow-[0_0_30px_#34d399] z-20 animate-scan-y opacity-80 rounded-full"></div>

                <!-- State: Standby -->
                <div id="state-standby" class="flex flex-col items-center z-10 transition-transform duration-300 group-hover:scale-110">
                    <div class="relative mb-6">
                         <div class="absolute inset-0 bg-emerald-500/20 blur-2xl rounded-full animate-pulse"></div>
                         <!-- Icon Scan Lebih Besar -->
                         <svg class="w-32 h-32 text-emerald-400 relative z-10 drop-shadow-2xl" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg>
                    </div>
                    <p class="text-5xl font-black text-white tracking-wide drop-shadow-lg">SCAN KARTU</p>
                    <p class="text-emerald-300/80 mt-2 font-mono text-lg tracking-widest uppercase">Tempelkan Kartu Anggota</p>
                </div>

                <!-- State: Result -->
                <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full bg-gray-900 rounded-3xl flex-col items-center justify-center border border-gray-700">
                    <!-- Injected via JS -->
                </div>
            </div>

            <!-- Footer Info -->
             <div class="text-center w-full pb-2">
                <p class="text-gray-500 text-xs font-mono tracking-wider" id="kiosk-date">Loading Tanggal...</p>
            </div>
        </div>

        <!-- BAGIAN KANAN: LIST PENGUNJUNG (Sidebar) -->
        <div class="w-[400px] h-full flex flex-col bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 shadow-2xl rounded-3xl overflow-hidden relative z-20 shrink-0">
            
            <div class="p-5 bg-gray-800/80 border-b border-gray-700 shadow-md z-10 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-white">Pengunjung Terakhir</h2>
                    <p class="text-[10px] text-gray-400">Real-time update</p>
                </div>
                <div class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar relative">
                <ul id="scan-log-list" class="space-y-3 pb-20">
                    <li id="empty-log" class="flex flex-col items-center justify-center py-20 opacity-40">
                        <svg class="w-12 h-12 text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="text-gray-400 text-sm font-medium">Belum ada data</p>
                    </li>
                </ul>
                <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-gray-900/80 to-transparent pointer-events-none"></div>
            </div>
        </div>
    </div>

    <input type="text" id="scan-input" class="absolute opacity-0 -top-full" autocomplete="off" autofocus>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clockEl = document.getElementById('kiosk-clock');
        const dateEl = document.getElementById('kiosk-date');
        const scanInput = document.getElementById('scan-input');
        const statusBox = document.getElementById('status-box');
        const stateResult = document.getElementById('state-result');
        const stateStandby = document.getElementById('state-standby');
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');
        const laser = document.getElementById('scan-laser');

        const processUrl = '{{ route('library.kiosk.process') }}'; 
        const csrfToken = '{{ csrf_token() }}';
        let isProcessing = false;

        const initialData = @json($recentVisits ?? []);

        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            if (type === 'success') {
                osc.type = 'sine'; osc.frequency.setValueAtTime(1000, audioCtx.currentTime); 
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                osc.start(); osc.stop(audioCtx.currentTime + 0.3);
            } else {
                osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
                osc.start(); osc.stop(audioCtx.currentTime + 0.5);
            }
        }

        function addToLog(name, status, message) {
            if(emptyLogMsg) emptyLogMsg.style.display = 'none';
            const li = document.createElement('li');
            const time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
            const initial = name.charAt(0).toUpperCase();
            
            const cardClass = status 
                ? "bg-gray-700/40 border-gray-600 hover:bg-gray-700/60" 
                : "bg-rose-900/20 border-rose-500/30 hover:bg-rose-900/30";
            
            const avatarClass = status 
                ? "bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-emerald-500/20" 
                : "bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-rose-500/20";

            li.className = `flex items-center gap-3 p-3 rounded-xl border ${cardClass} shadow-lg backdrop-blur-sm animate-fade-in-left transition-all transform hover:scale-[1.02]`;
            li.innerHTML = `
                <div class="flex-shrink-0 w-10 h-10 rounded-full ${avatarClass} flex items-center justify-center text-lg font-black shadow-lg border border-white/10">
                    ${initial}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-bold truncate text-base leading-tight tracking-tight">${name}</p>
                    <div class="flex justify-between items-center mt-0.5">
                        <p class="text-[10px] text-gray-400 truncate font-medium uppercase">${message}</p>
                        <span class="text-[10px] font-mono font-bold text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded ml-2 border border-emerald-500/20">${time}</span>
                    </div>
                </div>
            `;
            logList.prepend(li);
        }

        if(initialData && initialData.length > 0) {
            [...initialData].reverse().forEach(v => addToLog(v.name, v.status, v.message));
        }

        function updateTime() {
            const now = new Date();
            clockEl.innerText = now.toLocaleTimeString('id-ID', {hour12:false}).replace(/:/g, '.');
            dateEl.innerText = now.toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});
        }
        setInterval(updateTime, 1000);
        updateTime();

        document.addEventListener('click', (e) => { if(!e.target.closest('a')) scanInput.focus(); });
        scanInput.addEventListener('blur', () => setTimeout(() => scanInput.focus(), 10));

        scanInput.addEventListener('change', async (e) => {
            const code = e.target.value.trim();
            e.target.value = '';
            if(!code || isProcessing) return;
            isProcessing = true;
            
            laser.style.display = 'none'; 
            const corners = statusBox.querySelectorAll('.absolute.w-16'); // Select corner size yg baru
            corners.forEach(c => c.classList.add('opacity-0'));

            stateResult.classList.remove('hidden'); stateResult.classList.add('flex');
            stateResult.innerHTML = '<div class="w-20 h-20 border-8 border-emerald-500 border-t-transparent rounded-full animate-spin"></div><p class="mt-6 text-2xl text-emerald-400 font-bold animate-pulse">Memproses...</p>';

            try {
                const res = await fetch(processUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ scan_data: code })
                });
                const data = await res.json();
                
                if(data.success) {
                    playBeep('success');
                    statusBox.className = "w-full max-w-4xl flex-1 bg-emerald-600 rounded-3xl flex flex-col items-center justify-center shadow-[0_0_100px_rgba(16,185,129,0.6)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden mb-8";
                    
                    stateResult.innerHTML = `
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                        <div class="relative z-10 flex flex-col items-center animate-bounce-in">
                            <div class="bg-white p-5 rounded-full mb-4 animate-bounce shadow-2xl">
                                <svg class="w-16 h-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h2 class="text-6xl font-black text-white text-center leading-none tracking-tight drop-shadow-lg">${data.student_name}</h2>
                            <p class="text-3xl text-emerald-100 font-bold mt-4 bg-emerald-700/40 px-6 py-2 rounded-full border border-emerald-400/30">SILAKAN MASUK</p>
                        </div>`;
                    addToLog(data.student_name, true, 'Masuk');
                } else {
                    playBeep('error');
                    statusBox.className = "w-full max-w-4xl flex-1 bg-rose-600 rounded-3xl flex flex-col items-center justify-center shadow-[0_0_100px_rgba(225,29,72,0.6)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden mb-8";
                    stateResult.innerHTML = `
                         <div class="bg-white p-5 rounded-full mb-4 animate-pulse shadow-2xl">
                            <svg class="w-16 h-16 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <h2 class="text-6xl font-black text-white text-center drop-shadow-lg">GAGAL</h2>
                        <p class="text-rose-100 mt-4 text-2xl bg-rose-700/40 px-6 py-2 rounded-full border border-rose-400/30 font-bold">${data.message}</p>`;
                    addToLog('Scan Gagal', false, data.message);
                }
            } catch (err) {
                playBeep('error');
                statusBox.className = "w-full max-w-4xl flex-1 bg-gray-800 rounded-3xl border-4 border-rose-500 flex flex-col items-center justify-center mb-8";
                stateResult.innerHTML = `<p class="text-rose-400 font-bold text-2xl">Koneksi Terputus</p>`;
            }

            setTimeout(() => {
                stateResult.classList.add('hidden'); stateResult.classList.remove('flex');
                statusBox.className = "w-full max-w-4xl flex-1 bg-gray-900/40 backdrop-blur-sm rounded-3xl flex flex-col items-center justify-center relative transition-all duration-300 group overflow-visible border border-white/5 mb-8";
                corners.forEach(c => c.classList.remove('opacity-0'));
                laser.style.display = 'block'; 
                isProcessing = false; scanInput.focus();
            }, 3000);
        });
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(52, 211, 153, 0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(52, 211, 153, 0.5); }
    
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
</style>
@endsection