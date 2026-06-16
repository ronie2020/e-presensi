@extends('layouts.kiosk-layout')

@section('content')
<div class="h-screen w-full flex relative overflow-hidden font-sans selection:bg-elevate-primary selection:text-white bg-elevate-dark">
    
    <!-- BACKGROUND IMAGE & OVERLAY (ELEVATE NAVY TINT) -->
    <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/netila.jpg') }}');">
        <!-- Overlay Biru Navy: Mengatasi overexposed agar gambar redup elegan dan UI di depannya stand-out -->
        <div class="absolute inset-0 bg-elevate-dark/70 backdrop-blur-[3px]"></div>
    </div>

    <!-- OVERLAY START (Untuk Audio Context Browser) -->
    <div id="start-overlay" class="fixed inset-0 z-[100] bg-elevate-dark/95 backdrop-blur-xl flex flex-col items-center justify-center transition-opacity duration-500">
        <div class="text-center space-y-8 animate-enter">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-elevate-primary blur-3xl opacity-30 animate-pulse"></div>
                <i class="ph-duotone ph-desktop-tower text-9xl text-elevate-accent relative z-10"></i>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">KIOSK PERPUSTAKAAN</h1>
                <p class="text-elevate-soft mt-2 font-medium">Klik tombol di bawah untuk mengaktifkan sistem suara & scanner.</p>
            </div>
            <button onclick="startKiosk()" class="group relative px-8 py-4 bg-elevate-primary hover:bg-elevate-accent text-white font-bold rounded-2xl text-xl shadow-lg shadow-elevate-primary/30 transition-all hover:scale-105 active:scale-95 overflow-hidden">
                <span class="relative z-10 flex items-center gap-3">
                    <i class="ph-bold ph-power"></i> AKTIFKAN SISTEM
                </span>
            </button>
        </div>
    </div>

   <!-- Background FX (Gradient dari tema) -->
    <div class="absolute top-0 left-0 w-full h-1.5 bg-elevate-gradient-main z-50 shadow-[0_0_15px_rgba(86,187,241,0.6)]"></div>
    <div class="absolute -top-[20%] -left-[10%] w-[800px] h-[800px] bg-elevate-primary/20 rounded-full blur-[150px] animate-pulse pointer-events-none"></div>

    <!-- Tombol Kembali (Di atas background gelap) -->
    <a href="{{ route('library.dashboard') }}" class="absolute top-8 left-8 z-50 flex items-center gap-3 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-full transition-all border border-white/20 shadow-lg group backdrop-blur-md">
        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-bold text-xs uppercase tracking-wider">Dashboard</span>
    </a>

    <!-- CONTAINER UTAMA -->
    <div class="flex w-full h-full p-8 gap-10 pt-12 relative z-10">
        
        <!-- BAGIAN KIRI: SCANNER -->
        <div class="flex-1 flex flex-col items-center justify-center h-full">
            
            <!-- Header -->
            <div class="text-center mb-6 w-full flex flex-col items-center shrink-0">
                <div class="inline-flex items-center justify-center p-3 mb-4 bg-white/10 rounded-2xl border border-white/20 shadow-lg backdrop-blur-md">
                    <img src="{{ asset('img/logo_sekolah.png') }}" onerror="this.style.display='none';" alt="Logo" class="w-14 h-14 object-contain">
                </div>
                
                <h1 class="text-4xl font-black text-white tracking-tight uppercase leading-tight drop-shadow-md">
                    Selamat Datang
                </h1>
                
                <!-- Jam Digital -->
                <div class="mt-4 px-6 py-2 rounded-full bg-white/20 border border-white/30 shadow-lg backdrop-blur-xl">
                    <span id="kiosk-clock" class="text-3xl font-black text-white font-mono tracking-widest drop-shadow-md">00:00:00</span>
                </div>
            </div>

            <!-- MODE SWITCHER -->
            <div class="flex bg-elevate-dark/40 p-1.5 rounded-2xl border border-white/20 mb-6 backdrop-blur-xl shadow-lg">
                <button onclick="setMode('attendance')" id="btn-mode-attendance" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 bg-elevate-primary text-white shadow-md shadow-elevate-primary/30 interactive-btn">
                    <i class="ph-bold ph-user-check"></i> Absensi Masuk
                </button>
                <button onclick="setMode('check')" id="btn-mode-check" class="px-6 py-2.5 rounded-xl font-bold text-sm text-white/70 hover:text-white transition-all flex items-center gap-2 interactive-btn bg-transparent">
                    <i class="ph-bold ph-info"></i> Cek Status
                </button>
            </div>

            <!-- BOX SCANNER (Warna Putih menonjol di atas navy) -->
            <div id="status-box" class="w-full max-w-2xl aspect-[16/7] bg-white/95 backdrop-blur-xl rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-elevate-soft hover:border-elevate-accent/50 shadow-2xl">
                
                <!-- Laser Animation -->
                <div id="scan-laser" class="absolute top-0 left-8 right-8 h-1.5 bg-gradient-to-r from-transparent via-elevate-accent to-transparent shadow-[0_0_20px_theme(colors.elevate.accent)] z-20 animate-scan-y opacity-80"></div>

                <!-- State: Standby -->
                <div id="state-standby" class="flex flex-col items-center z-10 transition-transform duration-300 group-hover:scale-105">
                    <div class="relative mb-4">
                         <div class="absolute inset-0 bg-elevate-primary/10 blur-2xl rounded-full animate-pulse"></div>
                         <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center relative z-10 border-4 border-white shadow-inner">
                             <i class="ph-bold ph-scan text-5xl text-elevate-primary drop-shadow-md"></i>
                         </div>
                    </div>
                    <p class="text-3xl font-black text-elevate-dark tracking-wide">TEMPEL KARTU</p>
                    <p class="text-elevate-primary mt-1 font-bold text-sm tracking-widest uppercase" id="instruction-text">Untuk Absensi Perpustakaan</p>
                </div>

                <!-- State: Result -->
                <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full bg-elevate-surface rounded-[2.5rem] flex-col items-center justify-center border border-elevate-soft overflow-hidden p-6 text-center shadow-inner">
                    <!-- Injected via JS -->
                </div>
            </div>
            
            <!-- OVERDUE ALERT BANNER -->
            <div id="overdue-alert" class="hidden mt-6 w-full max-w-2xl bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center gap-4 animate-bounce-in shadow-lg">
                <div class="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center shrink-0 animate-pulse shadow-md shadow-rose-500/30">
                    <i class="ph-bold ph-warning text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <h4 class="text-rose-600 font-bold text-sm uppercase">Peringatan Keterlambatan</h4>
                    <p class="text-rose-800 text-xs mt-0.5 font-medium">Harap segera kembalikan buku: <span id="overdue-titles" class="font-bold"></span></p>
                </div>
            </div>

        </div>

        <!-- BAGIAN KANAN: SIDEBAR -->
        <div class="hidden lg:flex w-[380px] h-full flex-col gap-6 shrink-0">
            
            <!-- 1. List Pengunjung -->
            <div class="flex-1 bg-white/95 backdrop-blur-xl border border-elevate-soft shadow-2xl rounded-[2.5rem] overflow-hidden flex flex-col relative z-20">
                <div class="p-6 bg-elevate-soft/50 border-b border-elevate-soft flex justify-between items-center">
                    <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                        <i class="ph-fill ph-users-three text-elevate-primary"></i> Pengunjung
                    </h2>
                    <div class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto p-5 custom-scrollbar relative">
                    <ul id="scan-log-list" class="space-y-3 pb-10">
                        <li id="empty-log" class="flex flex-col items-center justify-center py-20 opacity-60">
                            <i class="ph-duotone ph-ghost text-5xl text-elevate-primary/30 mb-2"></i>
                            <p class="text-elevate-dark/40 text-xs font-bold uppercase">Belum ada data</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 2. Rekomendasi Buku -->
            <div class="h-1/3 bg-white/95 backdrop-blur-xl border border-elevate-soft shadow-2xl rounded-[2.5rem] p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="ph-duotone ph-books text-6xl text-elevate-primary"></i>
                </div>
                <h3 class="text-sm font-bold text-elevate-primary uppercase tracking-wider mb-4">Rekomendasi Hari Ini</h3>                
                <div class="relative h-full overflow-hidden" id="book-slider">
                    @forelse($recommendations as $index => $book)
                    <div class="book-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                        <div class="flex gap-4 items-start">
                            
                            <!-- LOGIC GAMBAR BUKU CERDAS -->
                            <div class="w-16 h-24 bg-elevate-soft rounded-lg shadow-sm shrink-0 overflow-hidden relative border border-elevate-primary/10">
                                @php
                                    $imageSrc = null;
                                    if (!empty($book->cover_path)) {
                                        if (filter_var($book->cover_path, FILTER_VALIDATE_URL)) {
                                            $imageSrc = $book->cover_path;
                                        } else {
                                            $imageSrc = asset('storage/' . $book->cover_path);
                                        }
                                    }
                                @endphp

                                @if($imageSrc)
                                    <img src="{{ $imageSrc }}" 
                                         class="w-full h-full object-cover" 
                                         alt="{{ $book->title }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    <div class="w-full h-full bg-gradient-to-br from-elevate-soft to-white hidden flex-col items-center justify-center p-1 text-center absolute inset-0">
                                        <i class="ph-duotone ph-book-open text-elevate-primary/50 text-xl mb-1"></i>
                                        <span class="text-[8px] text-elevate-dark/60 font-bold leading-tight">{{ substr($book->title, 0, 15) }}...</span>
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-elevate-soft to-white flex flex-col items-center justify-center p-1 text-center">
                                        <i class="ph-duotone ph-book-open text-elevate-primary/50 text-xl mb-1"></i>
                                        <span class="text-[8px] text-elevate-dark/60 font-bold leading-tight">{{ substr($book->title, 0, 15) }}...</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div>
                                <h4 class="text-elevate-dark font-bold text-sm line-clamp-2 leading-tight">{{ $book->title }}</h4>
                                <p class="text-slate-500 text-xs mt-1 font-medium">{{ $book->author }}</p>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <span class="px-2 py-0.5 bg-elevate-soft text-elevate-primary text-[10px] font-bold rounded border border-elevate-primary/10">
                                        {{ $book->category->name ?? 'Umum' }}
                                    </span>
                                   
                                    @if(!empty($book->location))
                                        <span class="px-2 py-0.5 bg-white text-slate-500 text-[10px] font-bold rounded border border-slate-200">
                                            Rak: {{ $book->location }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- EMPTY STATE REKOMENDASI -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center opacity-80">
                        <i class="ph-duotone ph-books text-5xl text-elevate-primary/30 mb-3"></i>
                        <p class="text-elevate-dark/50 text-xs font-bold uppercase text-center tracking-widest">Belum ada rekomendasi<br>buku hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- OPTIMIZED INPUT: inputmode="none" mencegah keyboard muncul di layar sentuh --}}
    <input type="text" id="scan-input" class="absolute opacity-0 -top-full" autocomplete="off" inputmode="none">
</div>

<script>
    // GLOBAL VARS
    let currentMode = 'attendance'; // 'attendance' | 'check'
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

    // START KIOSK & AUDIO FIX
    function startKiosk() {
        if (audioCtx.state === 'suspended') audioCtx.resume();
        document.documentElement.requestFullscreen().catch((e) => {});
        document.getElementById('start-overlay').style.opacity = '0';
        setTimeout(() => document.getElementById('start-overlay').style.display = 'none', 500);
        document.getElementById('scan-input').focus();
    }

    // MODE SWITCHER
    function setMode(mode) {
        currentMode = mode;
        const btnAtt = document.getElementById('btn-mode-attendance');
        const btnChk = document.getElementById('btn-mode-check');
        const instruct = document.getElementById('instruction-text');
        
        // Perubahan class untuk tema Elevate
        const activeClass = "bg-elevate-primary text-white shadow-md shadow-elevate-primary/30";
        const inactiveClass = "text-white/70 hover:text-white bg-transparent";

        if(mode === 'attendance') {
            btnAtt.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 ${activeClass} interactive-btn`;
            btnChk.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 ${inactiveClass} interactive-btn`;
            instruct.innerText = "Untuk Absensi Perpustakaan";
        } else {
            btnAtt.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 ${inactiveClass} interactive-btn`;
            btnChk.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 bg-elevate-dark text-white shadow-md shadow-elevate-dark/30 interactive-btn border border-white/20`;
            instruct.innerText = "Untuk Cek Status Peminjaman";
        }
        document.getElementById('scan-input').focus();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const scanInput = document.getElementById('scan-input');
        const statusBox = document.getElementById('status-box');
        const stateResult = document.getElementById('state-result');
        const logList = document.getElementById('scan-log-list');
        const laser = document.getElementById('scan-laser');
        const overdueAlert = document.getElementById('overdue-alert');

        // OPTIMISASI FOCUS KEEPER (AGRESIF)
        const keepFocus = () => {
            const active = document.activeElement;
            if (active && (active.tagName === 'BUTTON' || active.tagName === 'A' || active.classList.contains('interactive-btn'))) return;
            scanInput.focus();
        };

        document.addEventListener('click', keepFocus);
        scanInput.addEventListener('blur', () => { setTimeout(keepFocus, 100); });

        // Event Keydown untuk mendeteksi Enter dari scanner
        scanInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const code = scanInput.value.trim();
                if(code) {
                    scanInput.value = ''; 
                    processScanData(code);
                }
            }
        });

        // Initial Data
        const initialData = @json($recentVisits ?? []);
        if(initialData && initialData.length > 0) {
            [...initialData].reverse().forEach(v => addToLog(v.name, v.status, v.message));
        }

        // Clock
        setInterval(() => {
            const now = new Date();
            document.getElementById('kiosk-clock').innerText = now.toLocaleTimeString('id-ID', {hour12:false});
        }, 1000);

         // Book Slider Animation
        let currentSlide = 0;
        const slides = document.querySelectorAll('.book-slide');
        if(slides.length > 1) {
            setInterval(() => {
                slides[currentSlide].classList.remove('opacity-100', 'z-10');
                slides[currentSlide].classList.add('opacity-0', 'z-0');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.remove('opacity-0', 'z-0');
                slides[currentSlide].classList.add('opacity-100', 'z-10');
            }, 5000);
        }

        // Scan Event Logic
        let isProcessing = false;

        async function processScanData(code) {
            if(!code || isProcessing) return;
            isProcessing = true;
            
            // UI Loading (Elevate Mode)
            laser.style.display = 'none'; 
            stateResult.classList.remove('hidden'); stateResult.classList.add('flex');
            stateResult.className = "absolute inset-0 z-30 w-full h-full bg-white/95 backdrop-blur-md rounded-[2.5rem] flex flex-col items-center justify-center border border-elevate-soft overflow-hidden p-6 text-center shadow-inner";
            stateResult.innerHTML = '<div class="w-12 h-12 border-4 border-elevate-primary border-t-transparent rounded-full animate-spin mx-auto"></div><p class="mt-4 text-lg text-elevate-primary font-bold animate-pulse uppercase">Memproses...</p>';
            overdueAlert.classList.add('hidden'); 

            try {
                const res = await fetch('{{ route('library.kiosk.process') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ scan_data: code, mode: currentMode })
                });
                
                const data = await res.json();
                
                if(data.success) {
                    playBeep('success');
                    
                    // GAMIFIKASI: ULANG TAHUN & THEME DYNAMIC
                    let specialEffect = '';
                    let innerTextColor = "text-elevate-dark";
                    let boxColorClass = "bg-elevate-soft border-2 border-elevate-primary/20 shadow-2xl shadow-elevate-primary/10";

                    if(data.is_birthday) {
                        playBeep('birthday'); 
                        boxColorClass = "bg-pink-50 border-2 border-pink-200 shadow-2xl shadow-pink-500/20";
                        innerTextColor = "text-pink-900";
                        specialEffect = '<div class="absolute inset-0 animate-ping opacity-20 bg-pink-300 rounded-[2.5rem]"></div>';
                    }

                    if(currentMode === 'check') {
                         statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-white rounded-[2.5rem] flex flex-col items-center justify-center border-2 border-elevate-primary shadow-2xl shadow-elevate-primary/20 transform scale-[1.02] transition-all z-50 relative overflow-hidden";
                         stateResult.innerHTML = `
                            <div class="flex flex-col items-center animate-bounce-in bg-white w-full h-full justify-center">
                                <h2 class="text-3xl font-black text-elevate-dark text-center leading-none mb-6">${data.student_name}</h2>
                                <div class="flex gap-4">
                                    <div class="bg-elevate-soft p-4 rounded-2xl border border-elevate-primary/20 text-center min-w-[120px]">
                                        <span class="text-3xl font-black text-elevate-primary block mb-1">${data.active_loans}</span>
                                        <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Dipinjam</span>
                                    </div>
                                    <div class="bg-${data.has_overdue ? 'rose' : 'emerald'}-50 p-4 rounded-2xl border border-${data.has_overdue ? 'rose' : 'emerald'}-200 text-center min-w-[120px]">
                                        <span class="text-3xl font-black text-${data.has_overdue ? 'rose' : 'emerald'}-600 block mb-1"><i class="ph-bold ${data.has_overdue ? 'ph-warning' : 'ph-check'}"></i></span>
                                        <span class="text-xs text-${data.has_overdue ? 'rose' : 'emerald'}-700 uppercase font-bold tracking-wider">${data.has_overdue ? 'Denda' : 'Aman'}</span>
                                    </div>
                                </div>
                            </div>`;
                    } else {
                        // Attendance Mode (Elevate Theme)
                        statusBox.className = `w-full max-w-2xl aspect-[16/7] ${boxColorClass} rounded-[2.5rem] flex flex-col items-center justify-center transform scale-[1.02] transition-all z-50 relative overflow-hidden`;
                        
                        stateResult.innerHTML = `
                            ${specialEffect}
                            <div class="relative z-10 flex flex-col items-center animate-bounce-in w-full h-full justify-center ${data.is_birthday ? 'bg-pink-50' : 'bg-elevate-soft'}">
                                <div class="${data.is_birthday ? 'bg-pink-100 text-pink-600' : 'bg-elevate-primary text-white shadow-md shadow-elevate-primary/30'} p-4 rounded-full mb-4">
                                    <i class="ph-bold ph-check text-4xl"></i>
                                </div>
                                <h2 class="text-4xl font-black ${innerTextColor} text-center leading-none tracking-tight mb-3">${data.message}</h2>
                                <p class="text-sm font-bold px-5 py-1.5 rounded-full uppercase tracking-widest text-center ${data.is_birthday ? 'bg-pink-100 text-pink-700' : 'bg-white text-elevate-primary shadow-sm'}">
                                    ${data.sub_message || 'Kunjungan Tercatat'}
                                </p>
                                ${data.visit_count ? `<p class="text-[11px] ${data.is_birthday ? 'text-pink-600' : 'text-elevate-primary/70'} mt-3 font-mono font-bold">Total Kunjungan #${data.visit_count}</p>` : ''}
                            </div>`;
                        
                        addToLog(data.student_name, true, data.mode === 'check' ? 'Cek Status' : 'Kunjungan');
                    }

                    // OVERDUE WARNING
                    if(data.has_overdue) {
                        document.getElementById('overdue-titles').innerText = data.overdue_titles;
                        overdueAlert.classList.remove('hidden');
                        playBeep('warning');
                    }

                } else {
                    // Error Mode
                    playBeep('error');
                    statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-rose-50 rounded-[2.5rem] flex flex-col items-center justify-center border-2 border-rose-200 shadow-2xl shadow-rose-500/20 transform scale-[1.02] transition-all z-50 relative overflow-hidden";
                    stateResult.innerHTML = `
                         <div class="bg-rose-50 w-full h-full flex flex-col items-center justify-center">
                             <div class="bg-rose-100 p-4 rounded-full mb-4 shadow-sm text-rose-600">
                                <i class="ph-bold ph-x text-4xl"></i>
                            </div>
                            <h2 class="text-4xl font-black text-rose-900 text-center mb-3">GAGAL</h2>
                            <p class="text-sm text-rose-800 bg-rose-100 px-5 py-2 rounded-full font-bold border border-rose-200">${data.message}</p>
                        </div>`;
                }
            } catch (err) {
                playBeep('error');
                statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-white rounded-[2.5rem] border-4 border-rose-500 flex flex-col items-center justify-center shadow-2xl shadow-rose-500/20 z-50 relative overflow-hidden";
                stateResult.innerHTML = `<div class="bg-white w-full h-full flex items-center justify-center"><p class="text-rose-600 font-bold text-xl uppercase tracking-widest">ERROR KONEKSI</p></div>`;
            }

            setTimeout(() => {
                stateResult.classList.add('hidden'); stateResult.classList.remove('flex');
                statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-white/95 backdrop-blur-xl rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-elevate-soft hover:border-elevate-accent/50 shadow-2xl";
                laser.style.display = 'block'; 
                overdueAlert.classList.add('hidden');
                isProcessing = false; scanInput.focus();
            }, 3000);
        }

        scanInput.addEventListener('change', (e) => {
            const code = e.target.value.trim();
            if(code) {
                e.target.value = '';
                processScanData(code);
            }
        });
    });
    
    function playBeep(type) {
        if (audioCtx.state === 'suspended') audioCtx.resume();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        
        const now = audioCtx.currentTime;
        if (type === 'success') {
            osc.type = 'sine'; osc.frequency.setValueAtTime(800, now); osc.frequency.exponentialRampToValueAtTime(1200, now + 0.1);
            gain.gain.setValueAtTime(0.3, now); gain.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
            osc.start(now); osc.stop(now + 0.3);
        } else if(type === 'birthday') {
            [523.25, 659.25, 783.99].forEach((freq, i) => {
                const o = audioCtx.createOscillator(); const g = audioCtx.createGain();
                o.connect(g); g.connect(audioCtx.destination);
                o.type='triangle'; o.frequency.value = freq;
                g.gain.setValueAtTime(0.2, now + i*0.1); g.gain.exponentialRampToValueAtTime(0.001, now + i*0.1 + 0.4);
                o.start(now + i*0.1); o.stop(now + i*0.1 + 0.4);
            });
        } else if (type === 'warning') {
            osc.type = 'square'; osc.frequency.setValueAtTime(300, now);
            gain.gain.setValueAtTime(0.2, now); gain.gain.linearRampToValueAtTime(0.001, now + 0.4);
            osc.start(now); osc.stop(now + 0.4);
        } else {
            osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, now);
            gain.gain.setValueAtTime(0.3, now); gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
            osc.start(now); osc.stop(now + 0.4);
        }
    }

    function addToLog(name, status, message) {
        const list = document.getElementById('scan-log-list');
        document.getElementById('empty-log').style.display = 'none';
        
        const li = document.createElement('li');
        const initial = name.charAt(0).toUpperCase();
        const time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        
        const colors = status ? "border-elevate-soft bg-white" : "border-rose-200 bg-rose-50";
        const avatar = status ? "bg-elevate-soft text-elevate-primary" : "bg-rose-100 text-rose-600";
        const textNameColor = status ? "text-elevate-dark" : "text-rose-900";
        const textMsgColor = status ? "text-slate-500" : "text-rose-600";

        li.className = `flex items-center gap-3 p-3 rounded-2xl border ${colors} shadow-sm animate-fade-in-left`;
        li.innerHTML = `
            <div class="w-10 h-10 rounded-xl ${avatar} flex items-center justify-center font-black text-sm shrink-0">
                ${initial}
            </div>
            <div class="min-w-0 flex-1">
                <p class="${textNameColor} font-bold text-sm truncate">${name}</p>
                <div class="flex justify-between items-center">
                    <p class="text-[10px] ${textMsgColor} uppercase font-bold tracking-wider">${message}</p>
                    <span class="text-[9px] font-mono text-elevate-primary bg-elevate-soft px-1.5 py-0.5 rounded font-bold">${time}</span>
                </div>
            </div>`;
        list.prepend(li);
        if(list.children.length > 20) list.lastElementChild.remove();
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 0px; } 
    @keyframes scanY { 0% { top: 0%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 100%; opacity: 0; } }
    .animate-scan-y { animation: scanY 2.5s ease-in-out infinite; }
    @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); opacity: 1; } 100% { transform: scale(1); } }
    .animate-bounce-in { animation: bounceIn 0.6s cubic-bezier(0.2, 0.6, 0.3, 1); }
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in-left { animation: fadeInLeft 0.4s ease-out forwards; }
    @keyframes enter { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    .animate-enter { animation: enter 0.6s ease-out forwards; }
</style>
@endsection