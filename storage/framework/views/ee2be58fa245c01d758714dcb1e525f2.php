<?php $__env->startSection('content'); ?>
<div class="h-screen w-full bg-slate-900 flex relative overflow-hidden font-sans selection:bg-cyan-500 selection:text-white">
    
    <!-- OVERLAY START (Untuk Audio Context Browser) -->
    <div id="start-overlay" class="fixed inset-0 z-[100] bg-slate-900/90 backdrop-blur-xl flex flex-col items-center justify-center transition-opacity duration-500">
        <div class="text-center space-y-8 animate-enter">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-blue-500 blur-3xl opacity-20 animate-pulse"></div>
                <i class="ph-duotone ph-desktop-tower text-9xl text-white relative z-10"></i>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">KIOSK PERPUSTAKAAN</h1>
                <p class="text-slate-400 mt-2 font-medium">Klik tombol di bawah untuk mengaktifkan sistem suara & scanner.</p>
            </div>
            <button onclick="startKiosk()" class="group relative px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl text-xl shadow-lg shadow-blue-500/30 transition-all hover:scale-105 active:scale-95 overflow-hidden">
                <span class="relative z-10 flex items-center gap-3">
                    <i class="ph-bold ph-power"></i> AKTIFKAN SISTEM
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
        </div>
    </div>

    <!-- Background FX -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-cyan-400 to-indigo-500 z-50 shadow-[0_0_20px_rgba(56,189,248,0.5)]"></div>
    <div class="absolute -top-[20%] -left-[10%] w-[800px] h-[800px] bg-blue-600/20 rounded-full blur-[150px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5 pointer-events-none"></div>

    <!-- Tombol Kembali -->
    <a href="<?php echo e(route('library.dashboard')); ?>" class="absolute top-8 left-8 z-50 flex items-center gap-3 px-5 py-2.5 bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white rounded-full transition-all border border-slate-700 hover:border-slate-500 shadow-xl group backdrop-blur-md">
        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-bold text-xs uppercase tracking-wider">Dashboard</span>
    </a>

    <!-- CONTAINER UTAMA -->
    <div class="flex w-full h-full p-8 gap-10 pt-12 relative z-10">
        
        <!-- BAGIAN KIRI: SCANNER -->
        <div class="flex-1 flex flex-col items-center justify-center h-full">
            
            <!-- Header -->
            <div class="text-center mb-6 w-full flex flex-col items-center shrink-0">
                <div class="inline-flex items-center justify-center p-3 mb-4 bg-slate-800/50 rounded-2xl border border-slate-700/50 shadow-2xl backdrop-blur-sm">
                    <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" onerror="this.style.display='none';" alt="Logo" class="w-14 h-14 object-contain">
                </div>
                
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-white tracking-tight uppercase leading-tight drop-shadow-sm">
                    Selamat Datang
                </h1>
                
                <!-- Jam Digital -->
                <div class="mt-4 px-6 py-2 rounded-full bg-slate-800/30 border border-slate-700/30 backdrop-blur-md">
                    <span id="kiosk-clock" class="text-3xl font-black text-slate-200 font-mono tracking-widest">00:00:00</span>
                </div>
            </div>

            <!-- MODE SWITCHER -->
            <div class="flex bg-slate-800/50 p-1.5 rounded-2xl border border-slate-700 mb-6 backdrop-blur-md">
                <button onclick="setMode('attendance')" id="btn-mode-attendance" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 bg-blue-600 text-white shadow-lg shadow-blue-900/50 interactive-btn">
                    <i class="ph-bold ph-user-check"></i> Absensi Masuk
                </button>
                <button onclick="setMode('check')" id="btn-mode-check" class="px-6 py-2.5 rounded-xl font-bold text-sm text-slate-400 hover:text-white transition-all flex items-center gap-2 interactive-btn">
                    <i class="ph-bold ph-info"></i> Cek Status
                </button>
            </div>

            <!-- BOX SCANNER -->
            <div id="status-box" class="w-full max-w-2xl aspect-[16/7] bg-slate-800/40 backdrop-blur-md rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 hover:border-blue-500/50 shadow-2xl">
                
                <!-- Laser Animation -->
                <div id="scan-laser" class="absolute top-0 left-8 right-8 h-1.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-[0_0_20px_#22d3ee] z-20 animate-scan-y opacity-70"></div>

                <!-- State: Standby -->
                <div id="state-standby" class="flex flex-col items-center z-10 transition-transform duration-300 group-hover:scale-105">
                    <div class="relative mb-4">
                         <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full animate-pulse"></div>
                         <i class="ph-duotone ph-scan text-7xl text-cyan-400 relative z-10 drop-shadow-[0_0_15px_rgba(34,211,238,0.5)]"></i>
                    </div>
                    <p class="text-3xl font-black text-white tracking-wide">TEMPEL KARTU</p>
                    <p class="text-cyan-300/70 mt-1 font-bold text-sm tracking-widest uppercase" id="instruction-text">Untuk Absensi Perpustakaan</p>
                </div>

                <!-- State: Result -->
                <div id="state-result" class="hidden absolute inset-0 z-30 w-full h-full bg-slate-900 rounded-[2.5rem] flex-col items-center justify-center border border-slate-700 overflow-hidden p-6 text-center">
                    <!-- Injected via JS -->
                </div>
            </div>
            
            <!-- OVERDUE ALERT BANNER -->
            <div id="overdue-alert" class="hidden mt-6 w-full max-w-2xl bg-rose-500/10 border border-rose-500/50 rounded-2xl p-4 flex items-center gap-4 animate-bounce-in">
                <div class="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center shrink-0 animate-pulse">
                    <i class="ph-bold ph-warning text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <h4 class="text-rose-400 font-bold text-sm uppercase">Peringatan Keterlambatan</h4>
                    <p class="text-rose-200 text-xs mt-0.5">Harap segera kembalikan buku: <span id="overdue-titles" class="font-bold text-white"></span></p>
                </div>
            </div>

        </div>

        <!-- BAGIAN KANAN: SIDEBAR -->
        <div class="hidden lg:flex w-[380px] h-full flex-col gap-6 shrink-0">
            
            <!-- 1. List Pengunjung -->
            <div class="flex-1 bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 shadow-2xl rounded-[2.5rem] overflow-hidden flex flex-col relative z-20">
                <div class="p-6 bg-slate-900/50 border-b border-slate-700/50 flex justify-between items-center">
                    <h2 class="text-lg font-black text-white flex items-center gap-2">
                        <i class="ph-fill ph-users-three text-blue-500"></i> Pengunjung
                    </h2>
                    <div class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto p-5 custom-scrollbar relative">
                    <ul id="scan-log-list" class="space-y-3 pb-10">
                        <li id="empty-log" class="flex flex-col items-center justify-center py-20 opacity-30">
                            <i class="ph-duotone ph-ghost text-5xl text-slate-400 mb-2"></i>
                            <p class="text-slate-400 text-xs font-bold uppercase">Belum ada data</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 2. Rekomendasi Buku (FIXED IMAGE LOGIC) -->
            <div class="h-1/3 bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 shadow-xl rounded-[2.5rem] p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ph-duotone ph-books text-6xl text-purple-400"></i>
                </div>
                <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider mb-4">Rekomendasi Hari Ini</h3>
                
                <div class="relative h-full overflow-hidden" id="book-slider">
                    <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="book-slide absolute inset-0 transition-opacity duration-1000 <?php echo e($index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'); ?>">
                        <div class="flex gap-4 items-start">
                            
                            
                            <div class="w-16 h-24 bg-slate-700 rounded-lg shadow-lg shrink-0 overflow-hidden relative">
                                <?php
                                    // Cek apakah data gambar adalah URL (http/https) atau Path Lokal
                                    $imageSrc = null;
                                    if (!empty($book->cover_path)) {
                                        if (filter_var($book->cover_path, FILTER_VALIDATE_URL)) {
                                            $imageSrc = $book->cover_path; // Gunakan langsung jika URL
                                        } else {
                                            $imageSrc = asset('storage/' . $book->cover_path); // Tambah storage/ jika path lokal
                                        }
                                    }
                                ?>

                                <?php if($imageSrc): ?>
                                    
                                    <img src="<?php echo e($imageSrc); ?>" 
                                         class="w-full h-full object-cover" 
                                         alt="<?php echo e($book->title); ?>"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    
                                    <div class="w-full h-full bg-gradient-to-br from-purple-900 to-slate-900 hidden flex-col items-center justify-center p-1 text-center absolute inset-0">
                                        <i class="ph-duotone ph-book-open text-white/30 text-xl mb-1"></i>
                                        <span class="text-[8px] text-white/50 leading-tight"><?php echo e(substr($book->title, 0, 15)); ?>...</span>
                                    </div>
                                <?php else: ?>
                                    
                                    <div class="w-full h-full bg-gradient-to-br from-purple-900 to-slate-900 flex flex-col items-center justify-center p-1 text-center">
                                        <i class="ph-duotone ph-book-open text-white/30 text-xl mb-1"></i>
                                        <span class="text-[8px] text-white/50 leading-tight"><?php echo e(substr($book->title, 0, 15)); ?>...</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <h4 class="text-white font-bold text-sm line-clamp-2 leading-tight"><?php echo e($book->title); ?></h4>
                                <p class="text-slate-400 text-xs mt-1"><?php echo e($book->author); ?></p>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <span class="px-2 py-0.5 bg-purple-500/20 text-purple-300 text-[10px] font-bold rounded border border-purple-500/30">
                                        <?php echo e($book->category->name ?? 'Umum'); ?>

                                    </span>
                                    <?php if(!empty($book->location)): ?>
                                        <span class="px-2 py-0.5 bg-slate-700/50 text-slate-400 text-[10px] font-bold rounded border border-slate-600/30">
                                            Rak: <?php echo e($book->location); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>
    </div>

    
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
        
        const activeClass = "bg-blue-600 text-white shadow-lg shadow-blue-900/50";
        const inactiveClass = "text-slate-400 hover:text-white";

        if(mode === 'attendance') {
            btnAtt.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 ${activeClass} interactive-btn`;
            btnChk.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 ${inactiveClass} interactive-btn`;
            instruct.innerText = "Untuk Absensi Perpustakaan";
        } else {
            btnAtt.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 ${inactiveClass} interactive-btn`;
            btnChk.className = `px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 bg-purple-600 text-white shadow-lg shadow-purple-900/50 interactive-btn`;
            instruct.innerText = "Untuk Cek Status Peminjaman";
        }
        document.getElementById('scan-input').focus();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const scanInput = document.getElementById('scan-input');
        const statusBox = document.getElementById('status-box');
        const stateResult = document.getElementById('state-result');
        const logList = document.getElementById('scan-log-list');
        const emptyLogMsg = document.getElementById('empty-log');
        const laser = document.getElementById('scan-laser');
        const overdueAlert = document.getElementById('overdue-alert');

        // OPTIMISASI FOCUS KEEPER (AGRESIF)
        const keepFocus = () => {
            const active = document.activeElement;
            // Jangan rebut fokus jika user sedang menekan tombol mode/link
            if (active && (active.tagName === 'BUTTON' || active.tagName === 'A' || active.classList.contains('interactive-btn'))) return;
            scanInput.focus();
        };

        document.addEventListener('click', keepFocus);
        scanInput.addEventListener('blur', () => { setTimeout(keepFocus, 100); });

          // Event Keydown untuk mendeteksi Enter dari scanner lebih akurat
        scanInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault(); // Mencegah submit default
                const code = scanInput.value.trim();
                if(code) {
                    scanInput.value = ''; // Bersihkan input segera
                    processScanData(code);
                }
            }
        });

        // Initial Data
        const initialData = <?php echo json_encode($recentVisits ?? [], 15, 512) ?>;
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
            
            // UI Loading
            laser.style.display = 'none'; 
            stateResult.classList.remove('hidden'); stateResult.classList.add('flex');
            stateResult.innerHTML = '<div class="w-12 h-12 border-4 border-cyan-400 border-t-transparent rounded-full animate-spin mx-auto"></div><p class="mt-4 text-lg text-cyan-200 font-bold animate-pulse uppercase">Memproses...</p>';
            overdueAlert.classList.add('hidden'); // Reset alert

            try {
                const res = await fetch('<?php echo e(route('library.kiosk.process')); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ scan_data: code, mode: currentMode })
                });
                
                const data = await res.json();
                
                if(data.success) {
                    playBeep('success');
                    
                    // GAMIFIKASI: ULANG TAHUN
                    let specialEffect = '';
                    let titleText = data.student_name;
                    let subtitleText = data.message;
                    let boxColorClass = "bg-emerald-600 shadow-[0_0_80px_rgba(16,185,129,0.5)]";

                    if(data.is_birthday) {
                        playBeep('birthday'); // Logic sound ultah
                        titleText = `🎉 ${data.student_name} 🎉`;
                        boxColorClass = "bg-pink-600 shadow-[0_0_80px_rgba(219,39,119,0.5)]";
                        specialEffect = '<div class="absolute inset-0 animate-ping opacity-20 bg-white rounded-[2.5rem]"></div>';
                    }

                    if(currentMode === 'check') {
                         statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-purple-600 rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(147,51,234,0.5)] transform scale-[1.02] transition-all z-50 relative overflow-hidden";
                         stateResult.innerHTML = `
                            <div class="flex flex-col items-center animate-bounce-in">
                                <h2 class="text-3xl font-black text-white text-center leading-none mb-4">${data.student_name}</h2>
                                <div class="flex gap-4">
                                    <div class="bg-purple-800/50 p-3 rounded-xl border border-purple-400/30 text-center">
                                        <span class="text-2xl font-black text-white block">${data.active_loans}</span>
                                        <span class="text-[10px] text-purple-200 uppercase font-bold">Dipinjam</span>
                                    </div>
                                    <div class="bg-${data.has_overdue ? 'rose' : 'emerald'}-800/50 p-3 rounded-xl border border-${data.has_overdue ? 'rose' : 'emerald'}-400/30 text-center">
                                        <span class="text-2xl font-black text-white block"><i class="ph-bold ${data.has_overdue ? 'ph-warning' : 'ph-check'}"></i></span>
                                        <span class="text-[10px] text-white uppercase font-bold">${data.has_overdue ? 'Denda' : 'Aman'}</span>
                                    </div>
                                </div>
                            </div>`;
                    } else {
                        // Attendance Mode
                        statusBox.className = `w-full max-w-2xl aspect-[16/7] ${boxColorClass} rounded-[2.5rem] flex flex-col items-center justify-center transform scale-[1.02] transition-all z-50 relative overflow-hidden`;
                        
                        stateResult.innerHTML = `
                            ${specialEffect}
                            <div class="relative z-10 flex flex-col items-center animate-bounce-in">
                                <div class="bg-white/20 p-3 rounded-full mb-3 backdrop-blur-md border border-white/20">
                                    <i class="ph-bold ph-check text-3xl text-white"></i>
                                </div>
                                <h2 class="text-4xl font-black text-white text-center leading-none tracking-tight mb-2">${data.message}</h2>
                                <p class="text-lg text-white/90 font-bold px-4 py-1 rounded-full uppercase tracking-widest text-center">
                                    ${data.sub_message || 'Kunjungan Tercatat'}
                                </p>
                                ${data.visit_count ? `<p class="text-[10px] text-white/70 mt-2 font-mono">Total Kunjungan #${data.visit_count}</p>` : ''}
                            </div>`;
                        
                        addToLog(data.student_name, true, data.mode === 'check' ? 'Cek Status' : 'Kunjungan');
                    }

                    // OVERDUE WARNING LOGIC
                    if(data.has_overdue) {
                        document.getElementById('overdue-titles').innerText = data.overdue_titles;
                        overdueAlert.classList.remove('hidden');
                        playBeep('warning');
                    }

                } else {
                    playBeep('error');
                    statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-rose-600 rounded-[2.5rem] flex flex-col items-center justify-center shadow-[0_0_80px_rgba(225,29,72,0.5)] transform scale-[1.02] transition-all z-50 relative overflow-hidden";
                    stateResult.innerHTML = `
                         <div class="bg-white/20 p-3 rounded-full mb-3 backdrop-blur-md border border-white/20">
                            <i class="ph-bold ph-x text-3xl text-white"></i>
                        </div>
                        <h2 class="text-4xl font-black text-white text-center drop-shadow-lg mb-2">GAGAL</h2>
                        <p class="text-sm text-rose-100 bg-rose-800/30 px-4 py-1.5 rounded-full border border-rose-400/30 font-bold">${data.message}</p>`;
                }
            } catch (err) {
                playBeep('error');
                statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-slate-800 rounded-[2.5rem] border-4 border-rose-500 flex flex-col items-center justify-center shadow-[0_0_50px_rgba(225,29,72,0.3)]";
                stateResult.innerHTML = `<p class="text-rose-400 font-bold text-xl uppercase tracking-widest">ERROR KONEKSI</p>`;
            }

            setTimeout(() => {
                stateResult.classList.add('hidden'); stateResult.classList.remove('flex');
                statusBox.className = "w-full max-w-2xl aspect-[16/7] bg-slate-800/40 backdrop-blur-md rounded-[2.5rem] flex flex-col items-center justify-center relative transition-all duration-500 group overflow-visible border border-slate-700 hover:border-blue-500/50 shadow-2xl";
                laser.style.display = 'block'; 
                overdueAlert.classList.add('hidden'); // Hide alert on reset
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
    
    // Sound Engine
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
        
        const colors = status ? "border-slate-700 bg-slate-800/50" : "border-rose-500/30 bg-rose-900/20";
        const avatar = status ? "bg-gradient-to-br from-blue-500 to-cyan-500 text-white" : "bg-rose-600 text-white";

        li.className = `flex items-center gap-3 p-3 rounded-2xl border ${colors} shadow-sm animate-fade-in-left`;
        li.innerHTML = `
            <div class="w-10 h-10 rounded-xl ${avatar} flex items-center justify-center font-black text-sm shrink-0 shadow-lg">
                ${initial}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white font-bold text-sm truncate">${name}</p>
                <div class="flex justify-between items-center">
                    <p class="text-[10px] text-slate-400 uppercase font-bold">${message}</p>
                    <span class="text-[9px] font-mono text-cyan-400 bg-cyan-950 px-1.5 py-0.5 rounded">${time}</span>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kiosk-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/kiosk.blade.php ENDPATH**/ ?>