@extends('layouts.kiosk-layout')

@section('content')
<div class="h-screen w-full bg-slate-900 flex relative overflow-hidden font-sans selection:bg-cyan-500 selection:text-white">
    
    <!-- Background FX -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-cyan-400 to-indigo-500 z-50 shadow-[0_0_20px_rgba(56,189,248,0.5)]"></div>
    <div class="absolute -top-[20%] -left-[10%] w-[800px] h-[800px] bg-blue-600/20 rounded-full blur-[150px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5 pointer-events-none"></div>

    <!-- Tombol Kembali -->
    <a href="{{ route('library.dashboard') }}" class="absolute top-8 left-8 z-50 flex items-center gap-3 px-5 py-2.5 bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white rounded-full transition-all border border-slate-700 hover:border-slate-500 shadow-xl group backdrop-blur-md">
        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-bold text-xs uppercase tracking-wider">Dashboard Pustaka</span>
    </a>

    <!-- CONTAINER UTAMA -->
    <div class="flex w-full h-full p-8 gap-10 pt-12 relative z-10">
        
        <!-- BAGIAN KIRI: SCANNER -->
        <div class="flex-1 flex flex-col items-center justify-center h-full">
            
            <!-- Header -->
            <div class="text-center mb-10 w-full flex flex-col items-center shrink-0">
                <div class="inline-flex items-center justify-center p-3 mb-6 bg-slate-800/50 rounded-2xl border border-slate-700/50 shadow-2xl backdrop-blur-sm">
                    <img src="{{ asset('img/logo_sekolah.png') }}" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" 
                         alt="Logo" 
                         class="w-16 h-16 object-contain">
                    <i class="ph-fill ph-books text-5xl text-blue-500 hidden"></i>
                </div>
                
                <h1 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-white tracking-tight uppercase leading-tight drop-shadow-sm">
                    Absensi Perpustakaan
                </h1>
                <p class="text-sm text-slate-400 font-bold tracking-[0.4em] uppercase mt-3">SMP Negeri 3 Lakbok</p>
                
                <!-- Jam Digital -->
                <div class="mt-8 px-8 py-3 rounded-full bg-slate-800/30 border border-slate-700/30 backdrop-blur-md">
                    <span id="kiosk-clock" class="text-4xl font-black text-slate-200 font-mono tracking-widest">00:00:00</span>
                </div>
            </div>

            <!-- BOX SCANNER (Cyber Style) -->
            <div id="status-box" class="w-full max-w-3xl aspect-[16/7] bg-slate-800/40 backdrop-blur-md rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 hover:border-blue-500/50 shadow-2xl">
                
                <!-- Sudut Cyber -->
                <div class="absolute -top-0.5 -left-0.5 w-12 h-12 border-t-4 border-l-4 border-cyan-400 rounded-tl-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)]"></div>
                <div class="absolute -top-0.5 -right-0.5 w-12 h-12 border-t-4 border-r-4 border-cyan-400 rounded-tr-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)]"></div>
                <div class="absolute -bottom-0.5 -left-0.5 w-12 h-12 border-b-4 border-l-4 border-cyan-400 rounded-bl-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)]"></div>
                <div class="absolute -bottom-0.5 -right-0.5 w-12 h-12 border-b-4 border-r-4 border-cyan-400 rounded-br-3xl shadow-[0_0_15px_rgba(34,211,238,0.5)]"></div>

                <!-- Laser Animation -->
                <div id="scan-laser" class="absolute top-0 left-8 right-8 h-1.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-[0_0_20px_#22d3ee] z-20 animate-scan-y opacity-70"></div>

                <!-- State: Standby -->
                <div id="state-standby" class="flex flex-col items-center z-10 transition-transform duration-300 group-hover:scale-105">
                    <div class="relative mb-6">
                         <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full animate-pulse"></div>
                         <i class="ph-duotone ph-scan text-8xl text-cyan-400 relative z-10 drop-shadow-[0_0_15px_rgba(34,211,238,0.5)]"></i>
                    </div>
                    <p class="text-4xl font-black text-white tracking-wide">SIAP MEMINDAI</p>
                    <p class="text-cyan-300/70 mt-2 font-bold text-sm tracking-widest uppercase">Tempelkan Kartu Anggota Anda</p>
                </div>

                <!-- State: Result -->
                <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full bg-slate-900 rounded-[2.5rem] flex-col items-center justify-center border border-slate-700 overflow-hidden">
                    <!-- Injected via JS -->
                </div>
            </div>

            <!-- Footer Date -->
             <div class="mt-8">
                <p class="text-slate-500 text-sm font-bold tracking-wider uppercase" id="kiosk-date">...</p>
            </div>
        </div>

        <!-- BAGIAN KANAN: LIST PENGUNJUNG -->
        <div class="w-[420px] h-full flex flex-col bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 shadow-2xl rounded-[2.5rem] overflow-hidden relative z-20 shrink-0">
            
            <div class="p-8 bg-slate-900/50 border-b border-slate-700/50 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black text-white flex items-center gap-2">
                        <i class="ph-fill ph-users-three text-blue-500"></i> Pengunjung
                    </h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Real-time Log</p>
                </div>
                <div class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar relative">
                <ul id="scan-log-list" class="space-y-4 pb-20">
                    <li id="empty-log" class="flex flex-col items-center justify-center py-24 opacity-30">
                        <i class="ph-duotone ph-ghost text-6xl text-slate-400 mb-4"></i>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-wide">Belum ada data</p>
                    </li>
                </ul>
                {{-- Fade Out Bottom --}}
                <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-900/90 to-transparent pointer-events-none"></div>
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
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');
        const laser = document.getElementById('scan-laser');

        const processUrl = '{{ route('library.kiosk.process') }}'; 
        const csrfToken = '{{ csrf_token() }}';
        let isProcessing = false;

        const initialData = @json($recentVisits ?? []);

        // Audio Logic
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
                ? "bg-slate-800/50 border-slate-700" 
                : "bg-rose-900/20 border-rose-500/20";
            
            const avatarClass = status 
                ? "bg-gradient-to-br from-blue-500 to-cyan-500 text-white shadow-lg shadow-cyan-500/20" 
                : "bg-rose-600 text-white shadow-lg shadow-rose-600/20";

            li.className = `flex items-center gap-4 p-4 rounded-2xl border ${cardClass} shadow-md backdrop-blur-sm animate-fade-in-left transition-all`;
            li.innerHTML = `
                <div class="flex-shrink-0 w-12 h-12 rounded-xl ${avatarClass} flex items-center justify-center text-lg font-black border border-white/10">
                    ${initial}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-bold truncate text-base mb-1">${name}</p>
                    <div class="flex justify-between items-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">${message}</p>
                        <span class="text-[10px] font-mono font-bold text-cyan-400 bg-cyan-950/50 px-2 py-0.5 rounded border border-cyan-500/20">${time}</span>
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
            clockEl.innerText = now.toLocaleTimeString('id-ID', {hour12:false});
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
            const corners = statusBox.querySelectorAll('.absolute.w-12');
            corners.forEach(c => c.classList.add('opacity-0'));

            stateResult.classList.remove('hidden'); stateResult.classList.add('flex');
            stateResult.innerHTML = '<div class="w-16 h-16 border-4 border-cyan-400 border-t-transparent rounded-full animate-spin"></div><p class="mt-6 text-xl text-cyan-200 font-bold animate-pulse tracking-widest uppercase">Memverifikasi...</p>';

            try {
                const res = await fetch(processUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ scan_data: code })
                });
                
                // Cek status HTTP sebelum parse JSON
                if (!res.ok) throw new Error('HTTP Status ' + res.status);
                
                const data = await res.json();
                
                if(data.success) {
                    playBeep('success');
                    // SUCCESS STATE UI
                    statusBox.className = "w-full max-w-3xl aspect-[16/7] bg-emerald-600 rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(16,185,129,0.5)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden";
                    
                    stateResult.innerHTML = `
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
                        <div class="relative z-10 flex flex-col items-center animate-bounce-in">
                            <div class="bg-white/20 p-4 rounded-full mb-4 backdrop-blur-md border border-white/20">
                                <i class="ph-bold ph-check text-4xl text-white"></i>
                            </div>
                            <h2 class="text-5xl font-black text-white text-center leading-none tracking-tight drop-shadow-lg mb-2">${data.student_name}</h2>
                            <p class="text-xl text-emerald-100 font-bold bg-emerald-800/30 px-6 py-2 rounded-full border border-emerald-400/30 uppercase tracking-widest">Berhasil Masuk</p>
                        </div>`;
                    addToLog(data.student_name, true, 'Kunjungan');
                } else {
                    playBeep('error');
                    // ERROR STATE UI (Scan Gagal / Siswa Tidak Ditemukan)
                    statusBox.className = "w-full max-w-3xl aspect-[16/7] bg-rose-600 rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(225,29,72,0.5)] transform scale-[1.02] transition-all duration-300 z-50 relative overflow-hidden";
                    stateResult.innerHTML = `
                         <div class="bg-white/20 p-4 rounded-full mb-4 backdrop-blur-md border border-white/20">
                            <i class="ph-bold ph-x text-4xl text-white"></i>
                        </div>
                        <h2 class="text-5xl font-black text-white text-center drop-shadow-lg mb-2">GAGAL</h2>
                        <p class="text-lg text-rose-100 bg-rose-800/30 px-6 py-2 rounded-full border border-rose-400/30 font-bold">${data.message}</p>`;
                    addToLog('Scan Gagal', false, data.message);
                }
            } catch (err) {
                // SYSTEM / NETWORK ERROR HANDLING
                console.error(err);
                playBeep('error');
                statusBox.className = "w-full max-w-3xl aspect-[16/7] bg-slate-800 rounded-[2.5rem] border-4 border-rose-500 flex flex-col items-center justify-center shadow-[0_0_50px_rgba(225,29,72,0.3)]";
                stateResult.innerHTML = `
                    <i class="ph-duotone ph-wifi-slash text-6xl text-rose-500 mb-4 animate-pulse"></i>
                    <p class="text-rose-400 font-bold text-2xl uppercase tracking-widest">Koneksi Terputus</p>
                    <p class="text-rose-500/50 text-sm font-mono mt-1">Gagal menghubungi server</p>
                `;
            }

            setTimeout(() => {
                stateResult.classList.add('hidden'); stateResult.classList.remove('flex');
                // RESET TO STANDBY UI
                statusBox.className = "w-full max-w-3xl aspect-[16/7] bg-slate-800/40 backdrop-blur-md rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 hover:border-blue-500/50 shadow-2xl";
                corners.forEach(c => c.classList.remove('opacity-0'));
                laser.style.display = 'block'; 
                isProcessing = false; scanInput.focus();
            }, 2500);
        });
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
</style>
@endsection