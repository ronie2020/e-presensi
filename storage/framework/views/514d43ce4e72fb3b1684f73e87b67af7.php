<!DOCTYPE html>
<html lang="id" oncontextmenu="return false">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ujian - <?php echo e($exam->title); ?></title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <script src="https://unpkg.com/@phosphor-icons/web" async></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" async></script>

    
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' },
            startup: {
                ready: () => {
                    MathJax.startup.defaultReady();
                    window.renderMath = () => { if(window.MathJax) MathJax.typesetPromise(); };
                }
            }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; color: #1e293b; user-select: none; -webkit-user-select: none; overflow: hidden; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Mencegah Print ke PDF */
        @media print { body { display: none; } }

        /* Loader Style */
        #loading-overlay { position: fixed; inset: 0; background: #ffffff; z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.3s ease; }
        .spinner { width: 40px; height: 40px; border: 4px solid #e2e8f0; border-top: 4px solid #0f172a; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 1rem; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Trix Fix */
        .trix-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
        .trix-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
        .trix-content blockquote { border-left: 4px solid #cbd5e1; padding-left: 1rem; color: #64748b; font-style: italic; }
    </style>

    <script>
        // Data dari Controller
        window.examData = { 
            questions: <?php echo json_encode($questions, 15, 512) ?>, 
            timeLeft: <?php echo e($timeLeft ?? 0); ?>, 
            sessionId: <?php echo e($sessionId); ?>, 
            examId: <?php echo e($exam->id); ?> 
        };

        window.examApp = function() {
            return {
                // State
                questions: window.examData.questions || [],
                timeLeft: window.examData.timeLeft,
                sessionId: window.examData.sessionId,
                examId: window.examData.examId,
                currentQuestion: 0,
                totalQuestions: 0,
                formattedTime: '00:00:00',
                answers: {}, 
                markedQuestions: {},
                unsavedQuestions: new Set(), 
                initComplete: false,
                isOnline: navigator.onLine,
                saveStatus: 'idle', 
                saveQueue: {}, 
                showMobileMap: false,
                zoomedImage: null,
                violationCount: 0,
                maxViolations: 3,
                showSecurityOverlay: false,
                endTimeTarget: null,
                timerInterval: null,
                
                // Kamera State
                cameraActive: false,
                cameraInterval: null,
                captureIntervalTime: 300000, // 5 menit

                // INIT UTAMA
                initData() {
                    console.log("Memulai inisialisasi ujian...");
                    
                    try {
                        this.totalQuestions = this.questions.length;
                        
                        // Set Timer (Menggunakan sisa waktu dari server agar sync)
                        const now = new Date().getTime();
                        this.endTimeTarget = now + (this.timeLeft * 1000);
                        
                        // Load Jawaban Lokal (Backup jika koneksi putus)
                        try { this.loadLocalProgress(); } catch (e) { console.warn("Local storage issue", e); }
                        
                        // Mapping jawaban tersimpan dari database
                        this.questions.forEach(q => { 
                            if(q.saved_answer && !this.answers[q.id]) {
                                this.answers[q.id] = q.saved_answer; 
                            }
                        });
                        this.checkPendingAnswers();

                        // Render MathJax
                        setTimeout(() => { if(window.renderMath) window.renderMath(); }, 500);
                        this.$watch('currentQuestion', () => {
                            setTimeout(() => { if(window.renderMath) window.renderMath(); }, 100);
                        });

                        // Jalankan Kamera (Non-blocking: Jika gagal, ujian tetap jalan)
                        setTimeout(() => { this.initCamera(); }, 1000);

                    } catch (error) { 
                        console.error('System Error:', error);
                        // Jangan alert error sistem ke siswa, cukup log console agar tidak panik
                    } finally { 
                        // [CRITICAL] Pastikan loading hilang apapun yang terjadi
                        console.log("Inisialisasi selesai. Menutup loader.");
                        this.hideLoader();
                    }
                },

                hideLoader() {
                    this.initComplete = true; 
                    const overlay = document.getElementById('loading-overlay');
                    if(overlay) {
                        overlay.style.opacity = '0';
                        setTimeout(() => overlay.style.display = 'none', 300);
                    }
                },

                // --- FUNGSI KAMERA (PROCTORING) ---
                async initCamera() {
                    // Cek apakah browser support mediaDevices
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        console.warn("Browser tidak mendukung akses kamera.");
                        return;
                    }

                    try {
                        const video = document.getElementById('webcam-video');
                        if (!video) return;

                        // Minta izin kamera
                        const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } });
                        video.srcObject = stream;
                        this.cameraActive = true;
                        console.log("Kamera aktif.");
                        
                        // Foto pertama setelah 5 detik
                        setTimeout(() => this.capturePhoto(), 5000);

                        // Foto rutin setiap 5 menit
                        this.cameraInterval = setInterval(() => {
                            this.capturePhoto();
                        }, this.captureIntervalTime);

                    } catch (err) {
                        console.warn("Akses kamera ditolak/gagal:", err);
                        // Ujian tetap lanjut tanpa kamera
                    }
                },

                async capturePhoto() {
                    if (!this.cameraActive || !this.isOnline) return;

                    try {
                        const video = document.getElementById('webcam-video');
                        const canvas = document.createElement('canvas');
                        canvas.width = 320;
                        canvas.height = 240;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(video, 0, 0, 320, 240);
                        
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.5); // Kompresi 0.5

                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        // Upload background ke server
                        fetch("<?php echo e(route('student.exam.photo')); ?>", { 
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                            body: JSON.stringify({ session_id: this.sessionId, photo: dataUrl })
                        }).catch(e => console.warn("Gagal upload foto (background)", e));

                    } catch (e) {
                        console.error("Error capture photo", e);
                    }
                },

                // --- NAVIGASI & JAWAB ---
                nextQuestion() { if (this.currentQuestion < this.totalQuestions - 1) this.currentQuestion++; },
                prevQuestion() { if (this.currentQuestion > 0) this.currentQuestion--; },
                jumpTo(index) { this.currentQuestion = index; this.showMobileMap = false; },

                selectAnswer(questionId, answer) {
                    this.answers[questionId] = answer;
                    this.unsavedQuestions.add(questionId); 
                    this.saveStatus = 'pending';
                    try { this.saveToLocal(); } catch(e){}
                    
                    // Debounce save (Tunggu 1 detik setelah klik terakhir baru kirim)
                    if (this.saveQueue[questionId]) clearTimeout(this.saveQueue[questionId]);
                    this.saveQueue[questionId] = setTimeout(() => {
                        this.pushAnswerToServer(questionId, answer);
                        delete this.saveQueue[questionId];
                    }, 1000);
                },

                async pushAnswerToServer(questionId, answer) {
                    if (!this.isOnline) { this.saveStatus = 'pending'; return; }
                    this.saveStatus = 'saving';
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const response = await fetch("<?php echo e(route('student.exam.saveAnswer')); ?>", {
                            method: 'POST', 
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify({ session_id: this.sessionId, question_id: questionId, answer: answer })
                        });
                        if (!response.ok) throw new Error('Network response was not ok');
                        this.unsavedQuestions.delete(questionId);
                        try { this.saveToLocal(); } catch(e){}
                        if (this.unsavedQuestions.size === 0 && Object.keys(this.saveQueue).length === 0) {
                            setTimeout(() => this.saveStatus = 'saved', 300);
                        }
                    } catch (error) { 
                        console.error('Save failed:', error);
                        this.saveStatus = 'error'; 
                    }
                },

                saveToLocal() { 
                    localStorage.setItem(`exam_${this.sessionId}`, JSON.stringify({ 
                        answers: this.answers, marked: this.markedQuestions, unsaved: Array.from(this.unsavedQuestions), timestamp: new Date().getTime()
                    })); 
                },

                loadLocalProgress() { 
                    const saved = localStorage.getItem(`exam_${this.sessionId}`);
                    if (saved) { 
                        const data = JSON.parse(saved); 
                        this.answers = { ...data.answers, ...this.answers }; 
                        this.markedQuestions = data.marked || {}; 
                        if(data.unsaved) data.unsaved.forEach(id => this.unsavedQuestions.add(id)); 
                    }
                },

                async syncPendingAnswers() {
                    if (this.unsavedQuestions.size === 0) { this.saveStatus = 'saved'; return; }
                    this.saveStatus = 'saving';
                    const pendingIds = Array.from(this.unsavedQuestions);
                    for (const qId of pendingIds) { 
                        if (this.answers[qId]) await this.pushAnswerToServer(qId, this.answers[qId]); 
                        else this.unsavedQuestions.delete(qId);
                    }
                },

                checkPendingAnswers() { 
                    if(this.unsavedQuestions.size > 0) this.saveStatus = 'pending';
                    else if (Object.keys(this.answers).length > 0) this.saveStatus = 'saved';
                },

                // --- SECURITY & TIMER ---
                initSecurity() {
                    // Cegah refresh tidak sengaja
                    window.addEventListener('beforeunload', (e) => {
                        if (this.saveStatus !== 'finished') { e.preventDefault(); e.returnValue = ''; }
                    });
                    
                    // Deteksi Tab Switching (Hanya jika browser support Page Visibility API)
                    if (typeof document.hidden !== "undefined") {
                        document.addEventListener("visibilitychange", () => { if (document.hidden && this.saveStatus !== 'finished') this.triggerViolation(); });
                    }
                    
                    // Deteksi Blur (Klik di luar window ujian)
                    window.addEventListener("blur", () => setTimeout(() => { if(document.activeElement?.tagName !== 'IFRAME' && this.saveStatus !== 'finished') this.triggerViolation(); }, 1000));
                    
                    // Blokir Tombol Terlarang
                    window.addEventListener('keydown', (e) => { if ((e.ctrlKey && ['u','U','s','S','p','P'].includes(e.key)) || e.key === 'F12') e.preventDefault(); });
                },

                triggerViolation() {
                    if (this.showSecurityOverlay || this.timeLeft <= 0) return; 
                    this.violationCount++; 
                    this.showSecurityOverlay = true;
                    if(this.violationCount >= this.maxViolations) { 
                        Swal.fire({
                            icon: 'error',
                            title: 'DISKUALIFIKASI',
                            text: 'Anda telah melanggar batas aturan keamanan ujian (Pindah Tab/Window). Jawaban akan dikumpulkan otomatis.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#e11d48'
                        }).then(() => {
                            this.submitExam(true); 
                        });
                    }
                },

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        const now = new Date().getTime(); 
                        const distance = this.endTimeTarget - now;
                        this.timeLeft = Math.floor(distance / 1000);

                        if (distance > 0) {
                            let h = Math.floor(this.timeLeft / 3600);
                            let m = Math.floor((this.timeLeft % 3600) / 60);
                            let s = this.timeLeft % 60;
                            this.formattedTime = (h<10?"0"+h:h) + ":" + (m<10?"0"+m:m) + ":" + (s<10?"0"+s:s);
                            
                            // Warning 5 menit terakhir
                            if(this.timeLeft === 300) Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Waktu tinggal 5 menit!' });
                        } else { 
                            clearInterval(this.timerInterval); 
                            this.timeLeft = 0; this.formattedTime = "00:00:00"; 
                            if(this.saveStatus !== 'finished') { 
                                Swal.fire({ icon: 'info', title: 'Waktu Habis!', text: 'Jawaban akan dikumpulkan otomatis.', allowOutsideClick: false }); 
                                this.submitExam(true); 
                            } 
                        }
                    }, 1000);
                },

                finishExam() {
                    const totalAnswered = Object.keys(this.answers).length;
                    const remaining = this.totalQuestions - totalAnswered;
                    let htmlContent = remaining > 0 ? `Masih ada <b class='text-rose-600'>${remaining}</b> soal kosong.` : "Pastikan semua jawaban sudah benar.";
                    if(this.saveStatus === 'pending' || this.saveStatus === 'error') htmlContent += "<br><br><span class='text-amber-600 font-bold text-xs'><i class='ph-bold ph-warning'></i> Ada jawaban belum tersinkron ke server!</span>";

                    Swal.fire({
                        title: 'Kumpulkan Jawaban?', html: htmlContent, icon: remaining > 0 ? 'warning' : 'question', 
                        showCancelButton: true, confirmButtonText: 'Ya, Kumpulkan', confirmButtonColor: '#0f172a', 
                        cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' }
                    }).then((result) => { if (result.isConfirmed) this.submitExam(); });
                },

                async submitExam(forced = false) {
                    clearInterval(this.timerInterval);
                    Swal.fire({ title: 'Menyimpan Ujian...', html: 'Mohon tunggu, sedang menyinkronkan jawaban.', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2rem]' } });

                    if(this.unsavedQuestions.size > 0) await this.syncPendingAnswers();

                    this.saveStatus = 'finished';
                    // Bersihkan progress lokal agar aman untuk siswa berikutnya
                    localStorage.removeItem(`exam_${this.sessionId}`);

                    const form = document.createElement('form'); 
                    form.method = 'POST'; 
                    form.action = "<?php echo e(route('student.exam.finish', ':id')); ?>".replace(':id', this.examId);
                    const t = document.createElement('input'); 
                    t.type = 'hidden'; t.name = '_token'; t.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(t); 
                    document.body.appendChild(form); form.submit();
                }
            }
        }
        
        // [FAIL-SAFE] Paksa hapus loader setelah 10 detik jika script macet parah
        setTimeout(() => {
            const overlay = document.getElementById('loading-overlay');
            if(overlay && overlay.style.display !== 'none') {
                console.warn("Loader forced close by Fail-Safe.");
                overlay.style.display = 'none';
            }
        }, 10000);
    </script>
</head>

<body class="h-screen flex flex-col"
    x-data="window.examApp()"
    x-init="initData(); startTimer(); initSecurity();"
    @online.window="isOnline = true; syncPendingAnswers()"
    @offline.window="isOnline = false">

    
    <video id="webcam-video" autoplay playsinline muted style="position: absolute; top: -9999px; left: -9999px; width: 1px; height: 1px;"></video>

    
    <div id="loading-overlay">
        <div class="spinner"></div>
        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Memuat Ujian...</span>
    </div>
    
    
    <div x-show="zoomedImage" x-transition.opacity class="fixed inset-0 z-[10000] bg-black/95 flex items-center justify-center p-4 cursor-zoom-out" style="display: none;" @click="zoomedImage = null">
        <img :src="zoomedImage" class="max-w-full max-h-full rounded-lg shadow-2xl scale-100 transition-transform">
    </div>

    
    <div x-show="showSecurityOverlay" x-transition.opacity class="fixed inset-0 bg-slate-900/95 z-[9000] flex items-center justify-center text-center px-6" style="display: none;" x-cloak>
        <div class="max-w-md w-full bg-white rounded-[2rem] p-8 shadow-2xl">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-600 text-3xl"><i class="ph-fill ph-warning-octagon"></i></div>
            <h2 class="text-xl font-black text-slate-900 mb-2 uppercase">Pelanggaran Terdeteksi</h2>
            <p class="text-slate-600 mb-6 text-sm font-medium leading-relaxed">Anda meninggalkan halaman ujian. <br>Sisa toleransi: <span class="font-black text-rose-600" x-text="Math.max(0, maxViolations - violationCount)"></span> kali lagi sebelum diskualifikasi.</p>
            <button @click="showSecurityOverlay = false" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3.5 rounded-xl font-bold transition shadow-lg">Lanjutkan Mengerjakan</button>
        </div>
    </div>

    
    <nav class="bg-slate-900 text-white h-16 shrink-0 flex items-center justify-between px-4 lg:px-8 shadow-lg z-50 relative">
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white border border-white/10 shrink-0"><i class="ph-bold ph-graduation-cap text-xl"></i></div>
            <div class="truncate"><h1 class="font-bold text-sm lg:text-base truncate"><?php echo e($exam->title); ?></h1><p class="text-[10px] lg:text-xs text-slate-400 font-bold uppercase tracking-wider truncate"><?php echo e($exam->subject_name); ?></p></div>
        </div>
        <div class="flex items-center gap-3 md:gap-4">
            <div x-show="!isOnline" x-cloak class="hidden md:flex items-center gap-2 bg-rose-500/20 text-rose-300 px-3 py-1.5 rounded-lg border border-rose-500/30 text-xs font-bold animate-pulse"><i class="ph-fill ph-wifi-slash"></i> Offline</div>
            <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10" :class="timeLeft < 300 ? 'bg-rose-500/20 border-rose-500/50 text-rose-300 animate-pulse' : 'text-slate-200'"><i class="ph-bold ph-timer text-lg"></i><span x-text="formattedTime" class="font-mono font-bold text-lg"></span></div>
            <button @click="showMobileMap = !showMobileMap" class="lg:hidden w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center hover:bg-white/20 transition"><i class="ph-bold" :class="showMobileMap ? 'ph-x' : 'ph-squares-four'"></i></button>
        </div>
    </nav>

    <div class="flex-1 flex overflow-hidden relative">
        
        <main class="flex-1 flex flex-col h-full bg-slate-50 relative z-0 overflow-y-auto custom-scroll">
            <div class="w-full max-w-4xl mx-auto p-4 md:p-6 lg:p-8 flex-1 flex flex-col">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 flex-1 flex flex-col overflow-hidden relative">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">Soal No. <span x-text="currentQuestion + 1"></span></span>
                        <div class="flex items-center gap-4">
                            <span x-show="saveStatus === 'saving'" class="text-[10px] font-bold text-blue-500 uppercase tracking-wider flex items-center gap-1"><i class="ph-bold ph-spinner animate-spin"></i> Saving...</span>
                            <span x-show="saveStatus === 'saved'" class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider flex items-center gap-1"><i class="ph-fill ph-cloud-check"></i> Saved</span>
                            <span x-show="saveStatus === 'pending'" class="text-[10px] font-bold text-amber-500 uppercase tracking-wider flex items-center gap-1"><i class="ph-fill ph-cloud-warning"></i> Pending</span>
                            <span x-show="saveStatus === 'error'" class="text-[10px] font-bold text-rose-500 uppercase tracking-wider flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Error</span>
                            <label class="flex items-center gap-2 cursor-pointer select-none group">
                                <div class="relative"><input type="checkbox" class="peer sr-only" x-model="markedQuestions[questions[currentQuestion]?.id]" @change="saveToLocal()"><div class="w-5 h-5 rounded border-2 border-slate-300 peer-checked:bg-amber-400 peer-checked:border-amber-400 transition-colors"></div><i class="ph-bold ph-check text-white text-xs absolute top-0.5 left-0.5 opacity-0 peer-checked:opacity-100"></i></div>
                                <span class="text-xs font-bold text-slate-500 group-hover:text-amber-500 transition-colors uppercase tracking-wide">Ragu-ragu</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex-1 p-6 md:p-8 overflow-y-auto custom-scroll">
                        <template x-if="questions.length > 0 && questions[currentQuestion]">
                            <div>
                                
                                <template x-if="questions[currentQuestion].image">
                                    <div class="mb-6 relative group w-fit">
                                        <img :src="'/storage/' + questions[currentQuestion].image" onerror="this.style.display='none'" @click="zoomedImage = '/storage/' + questions[currentQuestion].image" class="max-h-[300px] w-auto rounded-2xl border border-slate-200 shadow-sm cursor-zoom-in transition group-hover:brightness-90">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition pointer-events-none"><span class="bg-black/50 text-white p-2 rounded-full backdrop-blur-sm"><i class="ph-bold ph-magnifying-glass-plus"></i></span></div>
                                    </div>
                                </template>
                                
                                
                                <div class="prose prose-slate max-w-none mb-8 select-none trix-content">
                                    <div class="text-lg md:text-xl font-medium text-slate-800 leading-relaxed" x-html="questions[currentQuestion].text"></div>
                                </div>

                                
                                <div class="space-y-3">
                                    <template x-for="(optionText, optionKey) in questions[currentQuestion].options" :key="optionKey">
                                        <label class="relative flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 group active:scale-[0.99]" :class="answers[questions[currentQuestion].id] === optionKey ? 'border-slate-900 bg-slate-900 shadow-lg' : 'border-slate-100 bg-white hover:border-blue-200 hover:bg-blue-50/50'">
                                            <input type="radio" :name="'q_' + questions[currentQuestion].id" :value="optionKey" @change="selectAnswer(questions[currentQuestion].id, optionKey)" x-model="answers[questions[currentQuestion].id]" class="peer sr-only">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0 transition-colors shadow-sm" :class="answers[questions[currentQuestion].id] === optionKey ? 'bg-white text-slate-900' : 'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-blue-600'"><span x-text="optionKey"></span></div>
                                            <div class="flex-1 select-none"><span class="text-sm md:text-base font-medium transition-colors" :class="answers[questions[currentQuestion].id] === optionKey ? 'text-white' : 'text-slate-700'"><span x-text="optionText"></span></span></div>
                                            <div x-show="answers[questions[currentQuestion].id] === optionKey" class="text-white"><i class="ph-bold ph-check-circle text-xl"></i></div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    
                    <div class="p-4 md:p-6 bg-white border-t border-slate-100 flex justify-between items-center relative z-10">
                        <button @click="prevQuestion" :disabled="currentQuestion === 0" class="px-5 py-3 rounded-xl font-bold flex items-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed text-slate-500 hover:bg-slate-100 hover:text-slate-900"><i class="ph-bold ph-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span></button>
                        <button @click="nextQuestion" x-show="currentQuestion < totalQuestions - 1" class="px-6 py-3 rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-600/30 flex items-center gap-2 transition-all active:scale-95"><span class="hidden sm:inline">Selanjutnya</span> <i class="ph-bold ph-arrow-right"></i></button>
                        <button @click="finishExam" x-show="currentQuestion === totalQuestions - 1" class="px-6 py-3 rounded-xl font-bold bg-slate-900 text-white hover:bg-slate-800 shadow-lg shadow-slate-900/30 flex items-center gap-2 transition-all active:scale-95"><span>Selesai</span> <i class="ph-bold ph-check-circle"></i></button>
                    </div>
                </div>
            </div>
        </main>

        
        <aside class="fixed inset-y-0 right-0 w-80 bg-white shadow-2xl z-40 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:w-80 lg:shadow-none lg:border-l border-slate-200 flex flex-col" :class="showMobileMap ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50"><h3 class="font-black text-slate-800 text-lg flex items-center gap-2"><i class="ph-fill ph-squares-four text-blue-500"></i> Navigasi Soal</h3><button @click="showMobileMap = false" class="lg:hidden text-slate-400 hover:text-slate-700"><i class="ph-bold ph-x text-xl"></i></button></div>
            <div class="flex-1 overflow-y-auto custom-scroll p-6">
                <div class="grid grid-cols-5 gap-3">
                    <template x-for="(q, index) in questions" :key="index">
                        <button @click="jumpTo(index)" class="aspect-square rounded-xl flex items-center justify-center font-bold text-sm transition-all shadow-sm border-2 relative" :class="{ 'border-slate-900 bg-white ring-2 ring-slate-900 ring-offset-2 z-10': currentQuestion === index, 'bg-slate-900 text-white border-slate-900': answers[q.id] && !markedQuestions[q.id], 'bg-amber-400 text-white border-amber-400': markedQuestions[q.id], 'bg-white text-slate-600 border-slate-100 hover:border-blue-300': !answers[q.id] && !markedQuestions[q.id] && currentQuestion !== index }"><span x-text="index + 1"></span><span x-show="markedQuestions[q.id]" class="absolute -top-1 -right-1 w-3 h-3 bg-amber-500 rounded-full border-2 border-white"></span></button>
                    </template>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 bg-slate-50">
                <div class="grid grid-cols-2 gap-4 mb-4 text-[10px] uppercase font-bold text-slate-500"><div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-slate-900"></span> Dijawab</div><div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-white border border-slate-300"></span> Kosong</div><div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-400"></span> Ragu</div><div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full border-2 border-slate-900"></span> Aktif</div></div>
                <button @click="finishExam()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 text-white font-bold shadow-lg hover:shadow-xl transition-all active:scale-95 flex items-center justify-center gap-2"><i class="ph-bold ph-paper-plane-right"></i> Kumpulkan Jawaban</button>
            </div>
        </aside>
        <div x-show="showMobileMap" @click="showMobileMap = false" x-transition.opacity class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden"></div>
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/student/exam_runner.blade.php ENDPATH**/ ?>