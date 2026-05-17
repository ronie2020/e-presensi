<!DOCTYPE html>
<html lang="id" oncontextmenu="return false" oncopy="return false" oncut="return false" onpaste="return false">
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
                    window.renderMath = () => { 
                        if(window.MathJax) {
                            MathJax.typesetClear();
                            MathJax.typesetPromise(); 
                        }
                    };
                }
            }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <style>
        /* Base Reset */
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; user-select: none; -webkit-user-select: none; overflow: hidden; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar - Disesuaikan dengan tema Cyan/Blue */
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #06b6d4; }
        
        /* Security */
        @media print { body { display: none; } }

        /* Loader - Menggunakan warna blue-950 */
        #loading-overlay { position: fixed; inset: 0; background: #ffffff; z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.3s ease; }
        .spinner { width: 40px; height: 40px; border: 4px solid #e2e8f0; border-top: 4px solid #172554; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 1rem; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Trix Editor Content Style */
        .trix-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
        .trix-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
        .trix-content blockquote { border-left: 4px solid #cbd5e1; padding-left: 1rem; color: #64748b; font-style: italic; }
        .trix-content img { max-width: 100%; height: auto; border-radius: 0.5rem; }
    </style>

    <script>
        // Seeded Random Generator
        function mulberry32(a) {
            return function() {
              var t = a += 0x6D2B79F5;
              t = Math.imul(t ^ t >>> 15, t | 1);
              t ^= t + Math.imul(t ^ t >>> 7, t | 61);
              return ((t ^ t >>> 14) >>> 0) / 4294967296;
            }
        }

        // Algoritma Fisher-Yates Shuffle
        function shuffleArray(array, randFunc) {
            let curId = array.length;
            while (0 !== curId) {
                let randId = Math.floor(randFunc() * curId);
                curId -= 1;
                let tmp = array[curId];
                array[curId] = array[randId];
                array[randId] = tmp;
            }
            return array;
        }

        window.examData = { 
            questions: <?php echo json_encode($questions, 15, 512) ?>, 
            timeLeft: <?php echo e($timeLeft ?? 0); ?>, 
            effectiveDuration: <?php echo e($effectiveDuration ?? (($exam->duration_minutes ?? 0) * 60)); ?>,
            sessionId: <?php echo e($sessionId); ?>, 
            examId: <?php echo e($exam->id); ?>,
            examType: '<?php echo e($exam->exam_type ?? 'cbt'); ?>', 
            randomizeQuestions: <?php echo e($exam->randomize_questions ? 'true' : 'false'); ?>,
            randomizeOptions: <?php echo e($exam->randomize_options ? 'true' : 'false'); ?>

        };

        window.examApp = function() {
            return {
                // --- STATE VARIABLES ---
                questions: [],
                timeLeft: window.examData.timeLeft,
                effectiveDuration: window.examData.effectiveDuration,
                sessionId: window.examData.sessionId,
                examId: window.examData.examId,
                examType: window.examData.examType,
                currentQuestion: 0,
                totalQuestions: 0,
                formattedTime: '00:00:00',
                answers: {}, 
                markedQuestions: {},
                unsavedQuestions: new Set(), 
                initComplete: false,
                isOnline: navigator.onLine,
                saveStatus: 'idle', 
                savingQuestionId: null, 
                saveQueue: {}, 
                showMobileMap: false,
                zoomedImage: null,
                
                // Security & Settings
                violationCount: 0,
                maxViolations: 3,
                showSecurityOverlay: false,
                blurTimeout: null, 
                endTimeTarget: null,
                timerInterval: null,
                fontSize: 1, 
                answeredCount: 0,
                
                // Camera State
                cameraActive: false,
                backgroundSyncInterval: null,

                // --- INISIALISASI ---
                initData() {
                    try {
                        let rand = mulberry32(this.sessionId); 

                        let rawQuestions = JSON.parse(JSON.stringify(window.examData.questions || []));

                        // 1. Acak Urutan Soal (Jika diaktifkan di pengaturan)
                        if (window.examData.randomizeQuestions) {
                            rawQuestions = shuffleArray(rawQuestions, rand);
                        }

                        // 2. Persiapkan Opsi & Acak Pilihan
                        rawQuestions.forEach(q => {
                            if (!q.question_type || q.question_type === 'choice') {
                                try {
                                    let parsedOpts = typeof q.options === 'string' ? JSON.parse(q.options) : (q.options || {});
                                    let keys = Object.keys(parsedOpts).filter(k => k.length === 1 && parsedOpts[k] != null && parsedOpts[k] !== '');
                                    
                                    if(keys.length === 0) {
                                        keys = ['A', 'B', 'C', 'D', 'E'].filter(k => parsedOpts['image_'+k] != null);
                                    }

                                    // Fallback aman jika kosong
                                    if(keys.length === 0) keys = ['A', 'B', 'C', 'D', 'E'];

                                    // Acak posisi opsi (hanya menyusun ulang referensi kunci datanya)
                                    if (window.examData.randomizeOptions && keys.length > 0) {
                                        keys = shuffleArray(keys, rand);
                                    }
                                    q.displayKeys = keys; 
                                } catch (e) {
                                    q.displayKeys = ['A', 'B', 'C', 'D', 'E']; 
                                }
                            }
                        });

                        this.questions = rawQuestions;
                        this.totalQuestions = this.questions.length;
                        
                        // Set Timer
                        const now = new Date().getTime();
                        this.endTimeTarget = now + (this.timeLeft * 1000);
                        
                        try { this.loadLocalProgress(); } catch (e) {}
                        
                        // Jika saat memuat ulang halaman pelanggaran sudah >= batas, usir langsung
                        if (this.violationCount >= this.maxViolations) {
                            this.showSecurityOverlay = true;
                            setTimeout(() => {
                                if(this.saveStatus !== 'finished' && this.saveStatus !== 'submitting') {
                                    this.submitExam(true);
                                }
                            }, 3000);
                            return;
                        }
                        
                        if (this.examType !== 'google_form') {
                            this.questions.forEach(q => { 
                                if(q.saved_answer && !this.answers[q.id]) {
                                    this.answers[q.id] = q.saved_answer; 
                                }
                            });
                        }
                        this.updateProgress();
                        this.checkPendingAnswers();

                        // Background Sync - Diperkuat
                        this.backgroundSyncInterval = setInterval(() => {
                            if(this.isOnline && this.unsavedQuestions.size > 0 && this.saveStatus !== 'saving' && this.saveStatus !== 'submitting') {
                                this.syncPendingAnswers();
                            }
                        }, 5000);

                        // Render MathJax
                        setTimeout(() => { if(window.renderMath) window.renderMath(); }, 500);
                        this.$watch('currentQuestion', () => {
                            setTimeout(() => { if(window.renderMath) window.renderMath(); }, 100);
                        });

                        setTimeout(() => { this.initCamera(); }, 2000);

                    } catch (error) { 
                        console.error('System Error:', error);
                    } finally { 
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

                updateProgress() {
                    this.answeredCount = Object.keys(this.answers).length;
                },

                // --- LOGIKA JAWABAN (CBT INTERNAL) ---
                selectAnswer(questionId, answer) {
                    this.answers[questionId] = answer;
                    this.unsavedQuestions.add(questionId); 
                    this.saveStatus = 'pending';
                    this.updateProgress();
                    
                    try { this.saveToLocal(); } catch(e){}
                    
                    if (this.saveQueue[questionId]) clearTimeout(this.saveQueue[questionId]);
                    this.saveQueue[questionId] = setTimeout(() => {
                        this.pushAnswerToServer(questionId, answer);
                        delete this.saveQueue[questionId];
                    }, 1000); 
                },

                updateMatching(questionId, leftKey, rightValue) {
                    let currentAns = this.answers[questionId] || {};
                    if (typeof currentAns !== 'object') currentAns = {};
                    currentAns[leftKey] = rightValue;
                    this.selectAnswer(questionId, currentAns);
                },

                async pushAnswerToServer(questionId, answer) {
                    if (!this.isOnline) { this.saveStatus = 'pending'; return; }
                    this.saveStatus = 'saving';
                    this.savingQuestionId = questionId; 
                    
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const response = await fetch("<?php echo e(route('student.exam.saveAnswer')); ?>", {
                            method: 'POST', 
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify({ 
                                session_id: this.sessionId, 
                                question_id: questionId, 
                                answer: answer,                                
                                question_type: this.questions.find(q => q.id == questionId)?.question_type 
                            })
                        });

                        if (response.status === 419 || response.status === 401) {
                            this.handleSessionExpired();
                            throw new Error('SESSION_EXPIRED');
                        }

                        if (!response.ok) throw new Error('Network response was not ok');
                        
                        this.unsavedQuestions.delete(questionId);
                        try { this.saveToLocal(); } catch(e){}
                        
                        if (this.unsavedQuestions.size === 0 && Object.keys(this.saveQueue).length === 0) {
                            setTimeout(() => this.saveStatus = 'saved', 300);
                        }
                    } catch (error) { 
                        if(error.message !== 'SESSION_EXPIRED') {
                            console.error('Save failed:', error);
                            this.saveStatus = 'error'; 
                        }
                    } finally {
                        this.savingQuestionId = null;
                    }
                },

                handleSessionExpired() {
                    this.saveStatus = 'error';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesi Terputus / Habis!',
                        html: 'Sesi login Anda telah berakhir. Jawaban gagal disimpan ke server.<br><br><b>JANGAN TUTUP HALAMAN INI!</b><br>Silakan buka tab baru dan login kembali, lalu kembali ke tab ini dan sistem akan mencoba menyimpan ulang otomatis.',
                        showCancelButton: true,
                        confirmButtonText: '<i class="ph-bold ph-arrow-square-out"></i> Login di Tab Baru',
                        cancelButtonText: 'Tutup Peringatan',
                        confirmButtonColor: '#172554', /* Warna Blue-950 */
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open("/login", "_blank");
                        }
                    });
                },

                saveToLocal() { 
                    localStorage.setItem(`exam_${this.sessionId}`, JSON.stringify({ 
                        answers: this.answers, 
                        marked: this.markedQuestions, 
                        unsaved: Array.from(this.unsavedQuestions), 
                        violationCount: this.violationCount,
                        timestamp: new Date().getTime()
                    })); 
                },

                loadLocalProgress() { 
                    const saved = localStorage.getItem(`exam_${this.sessionId}`);
                    if (saved) { 
                        const data = JSON.parse(saved); 
                        this.answers = { ...data.answers, ...this.answers }; 
                        this.markedQuestions = data.marked || {}; 
                        if(data.unsaved) data.unsaved.forEach(id => this.unsavedQuestions.add(id)); 
                        if(data.violationCount !== undefined) this.violationCount = data.violationCount;
                        this.updateProgress();
                    }
                },

                // LOGIKA SINKRONISASI DIPERKUAT (Return Boolean)
                async syncPendingAnswers() {
                    if (this.unsavedQuestions.size === 0) { 
                        this.saveStatus = 'saved'; 
                        this.checkAutoSubmitAfterSync();
                        return true; 
                    }
                    
                    this.saveStatus = 'saving';
                    const pendingIds = Array.from(this.unsavedQuestions);
                    let allSuccess = true;

                    for (const qId of pendingIds) { 
                        if (this.answers[qId]) {
                            await this.pushAnswerToServer(qId, this.answers[qId]);
                            if(this.unsavedQuestions.has(qId)) allSuccess = false; 
                        } else {
                            this.unsavedQuestions.delete(qId);
                        }
                    }
                    
                    if(allSuccess) {
                        Swal.fire({ toast: true, position: 'bottom-end', icon: 'success', title: 'Seluruh Data Tersinkron!', showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-xl mb-4' }});
                        this.checkAutoSubmitAfterSync();
                        return true;
                    }

                    this.saveStatus = 'error';
                    return false;
                },

                checkAutoSubmitAfterSync() {
                    // Jika waktu habis atau pelanggaran max, submit otomatis saat internet kembali jalan
                    if (this.saveStatus !== 'submitting' && this.saveStatus !== 'finished') {
                        if (this.timeLeft <= 0 || this.violationCount >= this.maxViolations) {
                            this.submitExam(true);
                        }
                    }
                },

                checkPendingAnswers() { 
                    if(this.unsavedQuestions.size > 0) this.saveStatus = 'pending';
                    else if (Object.keys(this.answers).length > 0) this.saveStatus = 'saved';
                },

                // --- KEAMANAN & TIMER ---
                 initSecurity() {
                    window.addEventListener('beforeunload', (e) => {
                        if (this.saveStatus !== 'finished') { e.preventDefault(); e.returnValue = ''; }
                    });
                    
                    if (typeof document.hidden !== "undefined") {
                        document.addEventListener("visibilitychange", () => { 
                            if (document.hidden && this.saveStatus !== 'finished') this.triggerViolation(); 
                        });
                    }
                    
                    window.addEventListener("blur", () => {
                        this.blurTimeout = setTimeout(() => { 
                            if(document.activeElement?.tagName !== 'IFRAME' && this.saveStatus !== 'finished') {
                                this.triggerViolation(); 
                            }
                        }, 3000); 
                    });

                    window.addEventListener("focus", () => {
                        if (this.blurTimeout) clearTimeout(this.blurTimeout);
                    });
                    
                    window.addEventListener('keydown', (e) => { 
                        if ((e.ctrlKey && ['u','U','s','S','p','P'].includes(e.key)) || e.key === 'F12') e.preventDefault(); 
                    });
                },

                 triggerViolation() {
                    if (this.showSecurityOverlay || this.timeLeft <= 0) return; 
                    this.violationCount++; 
                    try { this.saveToLocal(); } catch(e){} 
                    
                    // Tampilkan Overlay Biru Keamanan
                    this.showSecurityOverlay = true;

                    // Jika Pelanggaran Habis, usir otomatis
                    if(this.violationCount >= this.maxViolations) {
                        setTimeout(() => {
                            if(this.saveStatus !== 'finished' && this.saveStatus !== 'submitting') {
                                this.submitExam(true);
                            }
                        }, 3000);
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
                            
                            if(this.timeLeft === 300) Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Waktu tinggal 5 menit!' });
                        } else { 
                            clearInterval(this.timerInterval); 
                            this.timeLeft = 0; 
                            this.formattedTime = "00:00:00"; 
                            if(this.saveStatus !== 'finished' && this.saveStatus !== 'submitting') { 
                                Swal.fire({ icon: 'info', title: 'Waktu Habis!', text: 'Sistem sedang menyimpan dan menyelesaikan sesi otomatis.', showConfirmButton: false, allowOutsideClick: false }); 
                                this.submitExam(true); 
                            } 
                        }
                    }, 1000);
                },

                finishExam() {
                    // LOGIKA 75%
                    const waitThreshold = this.effectiveDuration * 0.25; 

                    if (this.timeLeft > waitThreshold) {
                        const waitSec = Math.floor(this.timeLeft - waitThreshold);
                        const m = Math.floor(waitSec / 60);
                        const s = waitSec % 60;
                        let waitText = (m > 0 ? m + " menit " : "") + s + " detik";
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Waktu Minimal Belum Tercapai!',
                            html: `Sistem mengunci pengumpulan karena Anda harus mengerjakan minimal <b>75%</b> dari sisa waktu efektif ujian Anda.<br><br>Sisa waktu tunggu agar bisa dikumpulkan: <b class='text-rose-600'>${waitText}</b>.`,
                            confirmButtonText: '<i class="ph-bold ph-arrow-u-up-left"></i> Kembali Mengerjakan',
                            confirmButtonColor: '#172554',
                            customClass: { popup: 'rounded-[2rem]' }
                        });
                        return; 
                    }

                    let htmlContent = "";
                    if (this.examType === 'google_form') {
                        htmlContent = "Apakah Anda sudah menekan tombol <b class='text-cyan-600'>Kirim / Submit</b> pada Google Form di atas?<br><br><span class='text-xs text-slate-500 font-bold'>Penting: Menekan tombol selesai di sini hanya merekam waktu keluar Anda di sistem sekolah.</span>";
                    } else {
                        const remaining = this.totalQuestions - this.answeredCount;
                        htmlContent = remaining > 0 ? `Masih ada <b class='text-rose-600'>${remaining}</b> soal kosong.` : "Pastikan semua jawaban sudah benar.";
                        
                        if(this.saveStatus === 'pending' || this.saveStatus === 'error') {
                            htmlContent += "<br><br><span class='text-amber-600 font-bold text-xs'><i class='ph-bold ph-warning'></i> Ada jawaban belum tersinkron ke server! Pastikan Anda memulihkan koneksi terlebih dahulu.</span>";
                        }
                    }

                    Swal.fire({
                        title: 'Selesaikan Ujian?', 
                        html: htmlContent, 
                        icon: 'question', 
                        showCancelButton: true, 
                        confirmButtonText: 'Ya, Selesaikan', 
                        confirmButtonColor: '#172554',
                        cancelButtonText: 'Batal', 
                        customClass: { popup: 'rounded-[2rem]' }
                    }).then((result) => { if (result.isConfirmed) this.submitExam(); });
                },

                // PENGAMANAN SUBMIT TOTAL (GATEKEEPER)
                async submitExam(forced = false) {
                    if (this.saveStatus === 'submitting' || this.saveStatus === 'finished') return;

                    // 1. CEK INTERNET DULUAN: Jika offline, Tahan Siswa!
                    if (!this.isOnline && this.examType !== 'google_form') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Internet Terputus!',
                            html: 'Sistem mendeteksi Anda sedang offline.<br>Jawaban Anda <b>Aman Tersimpan di Perangkat Ini</b>.<br><br>Mohon hubungkan kembali perangkat Anda ke internet. Sistem akan <b>otomatis mengumpulkan jawaban</b> ketika koneksi stabil.',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        return; // Hentikan di sini. Jangan biarkan local storage dihapus!
                    }

                    const prevStatus = this.saveStatus;
                    this.saveStatus = 'submitting';

                    Swal.fire({ title: 'Menyelesaikan Sesi...', html: 'Sistem sedang memastikan 100% jawaban Anda tersimpan ke server. Mohon tunggu.', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2rem]' } });

                    // 2. PAKSA SINKRONISASI JIKA ADA YANG TERTINGGAL
                    if(this.examType !== 'google_form' && this.unsavedQuestions.size > 0) {
                        const syncSuccess = await this.syncPendingAnswers();
                        
                        // Jika ternyata koneksi buruk dan server gagal membalas saat disinkron:
                        if (!syncSuccess) {
                            this.saveStatus = prevStatus; // Kembalikan statusnya
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal Sinkronisasi!',
                                html: 'Server gagal merespon. Jawaban Anda <b>MASIH AMAN</b> di browser ini.<br><br>Jangan tutup halaman ini, periksa koneksi Anda dan coba kumpulkan lagi.',
                                confirmButtonText: 'Saya Mengerti',
                                confirmButtonColor: '#172554'
                            });
                            return; // Hentikan di sini. Data tidak hilang!
                        }
                    }

                    // 3. AMAN. DATA 100% SUDAH DI SERVER.
                    this.saveStatus = 'finished';
                    this.showSecurityOverlay = false; // Boleh hilangkan layar blokir pelanggaran
                    
                    clearInterval(this.timerInterval);
                    if(this.backgroundSyncInterval) clearInterval(this.backgroundSyncInterval);

                    // BARULAH KITA MENGHAPUS LOKAL STORAGE
                    localStorage.removeItem(`exam_${this.sessionId}`);

                    // 4. SUBMIT FORM KE BACKEND
                    const form = document.createElement('form'); 
                    form.method = 'POST'; 
                    form.action = "<?php echo e(route('student.exam.finish', ':id')); ?>".replace(':id', this.examId);
                    
                    const t = document.createElement('input'); 
                    t.type = 'hidden'; t.name = '_token'; t.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(t); 

                    if (forced) {
                        const f = document.createElement('input'); 
                        f.type = 'hidden'; f.name = 'forced'; f.value = '1';
                        form.appendChild(f); 
                    }

                    document.body.appendChild(form); 
                    form.submit();
                },

                // --- KAMERA PROCTORING ---
                async initCamera() {
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.handleCameraFailure('Perangkat/Browser Anda tidak mendukung akses kamera.');
                        return;
                    }

                    try {
                        const video = document.getElementById('webcam-video');
                        if (!video) return;

                        const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } });
                        video.srcObject = stream;
                        
                        video.onloadedmetadata = () => {
                            video.play();
                            this.cameraActive = true;
                        };
                        
                        setTimeout(() => this.capturePhoto(), 5000);

                        const scheduleNextPhoto = () => {
                            if (!this.cameraActive) return;
                            const minTime = 180000; 
                            const maxTime = 300000; 
                            const randomInterval = Math.floor(Math.random() * (maxTime - minTime + 1) + minTime);
                            
                            setTimeout(() => {
                                this.capturePhoto();
                                scheduleNextPhoto(); 
                            }, randomInterval);
                        };
                        scheduleNextPhoto();

                    } catch (err) {
                        this.handleCameraFailure('Akses kamera ditolak atau tidak ada kamera fisik.');
                    }
                },

                handleCameraFailure(reason) {
                    this.cameraActive = false;
                    console.warn("Camera Init Info:", reason);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Info Pengawasan',
                        text: 'Kamera tidak terdeteksi. Ujian tetap dilanjutkan tanpa pengawasan kamera.',
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-2xl border-2 border-cyan-100 shadow-xl' }
                    });
                },

                async capturePhoto() {
                    if (!this.cameraActive || !this.isOnline) return;

                    try {
                        const video = document.getElementById('webcam-video');                        
                        if (video.readyState !== 4) return;

                        const canvas = document.createElement('canvas');
                        canvas.width = 320;
                        canvas.height = 240;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(video, 0, 0, 320, 240);
                        
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.3); 
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        fetch("<?php echo e(route('student.exam.photo')); ?>", { 
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                            body: JSON.stringify({ session_id: this.sessionId, photo: dataUrl })
                        }).catch(e => console.warn("Upload foto gagal", e));

                    } catch (e) {
                        console.error("Error capture photo", e);
                    }
                },

                // --- NAVIGASI UI ---
                nextQuestion() { if (this.currentQuestion < this.totalQuestions - 1) this.currentQuestion++; },
                prevQuestion() { if (this.currentQuestion > 0) this.currentQuestion--; },
                jumpTo(index) { this.currentQuestion = index; this.showMobileMap = false; },
            }
        }
        
        // Timeout darurat hilangkan loader
        setTimeout(() => {
            const overlay = document.getElementById('loading-overlay');
            if(overlay && overlay.style.display !== 'none') overlay.style.display = 'none';
        }, 10000);
    </script>
</head>

<body class="h-[100dvh] flex flex-col"
    x-data="window.examApp()"
    x-init="initData(); startTimer(); initSecurity();"
    @online.window="isOnline = true; syncPendingAnswers()"
    @offline.window="isOnline = false; Swal.fire({toast: true, position: 'bottom-end', icon: 'error', title: 'Koneksi Terputus!', text: 'Jawaban disimpan di browser.', showConfirmButton: false, timer: 4000, customClass: {popup: 'rounded-xl mb-4'} })"
    @focus.window="syncPendingAnswers()">

    
    <video id="webcam-video" autoplay playsinline muted style="position: fixed; top: 0; left: 0; width: 320px; height: 240px; opacity: 0; pointer-events: none; z-index: -100;"></video>

    
    <div id="loading-overlay">
        <div class="spinner"></div>
        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Memuat Ruangan...</span>
    </div>
    
    
    <div x-show="zoomedImage" x-transition.opacity class="fixed inset-0 z-[10000] bg-blue-950/95 flex items-center justify-center p-4 cursor-zoom-out" style="display: none;" @click="zoomedImage = null">
        <img :src="zoomedImage" class="max-w-full max-h-full rounded-lg shadow-2xl scale-100 transition-transform">
    </div>

    
    <div x-show="showSecurityOverlay" x-transition.opacity class="fixed inset-0 bg-blue-950/95 z-[9000] flex items-center justify-center text-center px-6" style="display: none;" x-cloak>
        <div class="max-w-md w-full bg-white rounded-[2rem] p-8 shadow-2xl">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-600 text-3xl"><i class="ph-fill ph-warning-octagon"></i></div>
            <h2 class="text-xl font-black text-slate-900 mb-2 uppercase">Pelanggaran Terdeteksi</h2>
            
            
            <template x-if="violationCount < maxViolations">
                <div>
                    <p class="text-slate-600 mb-6 text-sm font-medium leading-relaxed">Anda terdeteksi meninggalkan halaman ujian (pindah tab atau membuka aplikasi lain). <br>Sisa toleransi: <span class="font-black text-rose-600" x-text="Math.max(0, maxViolations - violationCount)"></span> kali.</p>
                    <button @click="showSecurityOverlay = false" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-cyan-600/30">Lanjutkan Mengerjakan</button>
                </div>
            </template>

            
            <template x-if="violationCount >= maxViolations">
                <div>
                    <p class="text-slate-600 mb-6 text-sm font-medium leading-relaxed">Anda telah melewati batas maksimal pelanggaran.<br><b class="text-rose-600">Sistem menghentikan ujian Anda secara otomatis.</b></p>
                    <button @click="submitExam(true)" class="w-full bg-rose-600 hover:bg-rose-500 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-rose-600/30">
                        Kumpulkan Sekarang
                    </button>
                </div>
            </template>
        </div>
    </div>

    
    <nav class="bg-blue-950 text-white h-16 shrink-0 flex items-center justify-between px-4 lg:px-8 shadow-xl border-b border-blue-900/50 z-50 relative">
        <div class="flex items-center gap-4 min-w-0 flex-1">
            <div class="flex items-center gap-3 shrink-0">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-cyan-400 border border-white/10 shrink-0">
                    <i class="ph-bold ph-graduation-cap text-xl"></i>
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-bold text-sm lg:text-base truncate max-w-[200px] text-white"><?php echo e($exam->title); ?></h1>
                    <p class="text-[10px] lg:text-xs text-cyan-300 font-bold uppercase tracking-wider truncate"><?php echo e($exam->subject_name); ?></p>
                </div>
            </div>

            <div class="hidden md:block w-px h-8 bg-white/10 mx-2"></div>

            <div class="hidden md:flex items-center gap-3 min-w-0">
                 <div class="w-8 h-8 rounded-full bg-cyan-600 flex items-center justify-center text-white text-xs font-bold border border-cyan-400/50 shrink-0 shadow-sm">
                     <?php echo e(substr($student->name ?? 'S', 0, 1)); ?>

                 </div>
                 <div class="truncate">
                     <p class="font-bold text-sm text-white truncate max-w-[150px]">
                         <?php echo e($student->name ?? 'Peserta'); ?>

                     </p>
                     <p class="text-[10px] text-cyan-200 font-bold truncate">
                         <?php echo e($student->nis ?? $student->username ?? ''); ?>

                     </p>
                 </div>
            </div>
        </div>

        <div class="flex items-center gap-3 md:gap-4">
            
            <div x-show="examType !== 'google_form'" class="hidden md:flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg mr-2">
                <span class="text-[10px] text-cyan-200/70 font-bold uppercase tracking-wider mr-1">Teks:</span>
                <button @click="fontSize = 1" class="font-medium hover:text-white transition px-1" :class="fontSize === 1 ? 'text-white font-black' : 'text-cyan-200/70'">A</button>
                <button @click="fontSize = 2" class="font-medium text-lg hover:text-white transition px-1" :class="fontSize === 2 ? 'text-white font-black' : 'text-cyan-200/70'">A</button>
                <button @click="fontSize = 3" class="font-medium text-xl hover:text-white transition px-1" :class="fontSize === 3 ? 'text-white font-black' : 'text-cyan-200/70'">A</button>
            </div>

            
            <div x-show="!isOnline" x-cloak class="hidden md:flex items-center gap-2 bg-rose-500/20 text-rose-300 px-3 py-1.5 rounded-lg border border-rose-500/30 text-xs font-bold animate-pulse"><i class="ph-fill ph-wifi-slash"></i> Offline</div>
            
            
            <div x-show="!cameraActive" x-cloak class="hidden md:flex items-center gap-2 bg-white/10 text-cyan-200 px-3 py-1.5 rounded-lg text-xs font-bold" title="Kamera Tidak Aktif">
                <i class="ph-fill ph-video-camera-slash"></i>
            </div>

            
            <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 transition-colors" :class="timeLeft < 300 ? 'bg-rose-500/20 border-rose-500/50 text-rose-300 animate-pulse' : 'text-cyan-50'">
                <i class="ph-bold ph-timer text-lg"></i>
                <span x-text="formattedTime" class="font-mono font-bold text-lg"></span>
            </div>
            
            
            <button x-show="examType !== 'google_form'" @click="showMobileMap = !showMobileMap" class="lg:hidden w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition">
                <i class="ph-bold" :class="showMobileMap ? 'ph-x' : 'ph-squares-four'"></i>
            </button>
        </div>
    </nav>

    
    <div x-show="examType !== 'google_form'" class="h-1.5 w-full bg-blue-900 shrink-0 relative z-40 overflow-hidden">
        <div class="h-full bg-cyan-400 transition-all duration-500 ease-out shadow-[0_0_10px_rgba(34,211,238,0.8)]" :style="`width: ${(answeredCount / totalQuestions) * 100}%`"></div>
    </div>

    <div class="flex-1 flex overflow-hidden relative min-h-0">
        
        
        <?php if(isset($exam->exam_type) && $exam->exam_type == 'google_form'): ?>
            <main class="flex-1 flex flex-col h-full bg-slate-100 relative z-0">
                <div class="bg-cyan-50/80 backdrop-blur-sm border-b border-cyan-100 p-3 flex items-center justify-center shrink-0 z-10 shadow-sm">
                    <p class="text-xs font-bold text-cyan-800 flex items-center gap-2">
                        <i class="ph-fill ph-warning-circle text-cyan-600 text-lg"></i> 
                        Pastikan Anda menekan tombol "Kirim / Submit" pada formulir di bawah sebelum mengklik tombol selesai.
                    </p>
                </div>
                
                <div class="flex-1 w-full h-full relative">
                    <iframe src="<?php echo e($exam->google_form_url); ?>" class="absolute inset-0 w-full h-full border-0" allowfullscreen></iframe>
                </div>
                
                <div class="p-4 bg-white border-t border-slate-200 flex justify-between items-center relative z-10 shrink-0 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
                    <div class="hidden sm:block">
                        <p class="text-xs font-bold text-slate-500">Ujian Berbasis Google Form</p>
                    </div>
                    <button @click="finishExam" 
                            class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all active:scale-95"
                            :class="timeLeft > (effectiveDuration * 0.25) ? 'bg-slate-200 text-slate-400 hover:bg-slate-300' : 'bg-blue-950 text-white hover:bg-blue-900 shadow-lg shadow-blue-950/20'">
                        <i class="ph-bold" :class="timeLeft > (effectiveDuration * 0.25) ? 'ph-lock' : 'ph-check-circle'"></i>
                        <span x-text="timeLeft > (effectiveDuration * 0.25) ? 'Waktu Tunggu...' : 'Selesai & Keluar'"></span> 
                    </button>
                </div>
            </main>
        <?php else: ?>

        
        <main class="flex-1 flex flex-col bg-slate-50 relative z-0 min-h-0">
            <div class="w-full max-w-4xl mx-auto p-0 sm:p-4 md:p-6 lg:p-8 flex-1 flex flex-col min-h-0">
                <div class="bg-white sm:rounded-[2rem] shadow-sm border-0 sm:border border-slate-200 flex-1 flex flex-col overflow-hidden relative min-h-0">
                    
                    
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                        <span class="bg-blue-950 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">Soal No. <span x-text="currentQuestion + 1"></span></span>
                        
                        
                        <div class="flex items-center gap-4">
                            <span x-show="saveStatus === 'saving'" class="text-[10px] font-bold text-cyan-600 uppercase flex items-center gap-1"><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan</span>
                            <span x-show="saveStatus === 'saved'" class="text-[10px] font-bold text-emerald-500 uppercase flex items-center gap-1"><i class="ph-fill ph-cloud-check"></i> Tersimpan</span>
                            <span x-show="saveStatus === 'error'" class="text-[10px] font-bold text-rose-500 uppercase flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Error / Offline</span>
                            
                            
                            <label class="flex items-center gap-2 cursor-pointer select-none group">
                                <div class="relative">
                                    <input type="checkbox" class="peer sr-only" x-model="markedQuestions[questions[currentQuestion]?.id]" @change="saveToLocal()">
                                    <div class="w-5 h-5 rounded border-2 border-slate-300 peer-checked:bg-amber-400 peer-checked:border-amber-400 transition-colors"></div>
                                    <i class="ph-bold ph-check text-white text-xs absolute top-0.5 left-0.5 opacity-0 peer-checked:opacity-100"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-500 uppercase group-hover:text-amber-500 transition">Ragu</span>
                            </label>
                        </div>
                    </div>

                    
                    <div class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto custom-scroll relative">
                        <template x-if="questions.length > 0 && questions[currentQuestion]">
                            <div>
                                
                                <template x-if="questions[currentQuestion].question_image">
                                    <div class="mb-6 relative group w-fit">
                                        <img :src="'/storage/' + questions[currentQuestion].question_image" 
                                             onerror="this.style.display='none'" 
                                             @click="zoomedImage = '/storage/' + questions[currentQuestion].question_image"
                                             class="max-h-[300px] w-auto rounded-2xl border border-slate-200 shadow-sm cursor-zoom-in transition group-hover:brightness-90">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition pointer-events-none">
                                            <span class="bg-black/50 text-white p-2 rounded-full backdrop-blur-sm"><i class="ph-bold ph-magnifying-glass-plus"></i></span>
                                        </div>
                                    </div>
                                </template>
                                
                                
                                <div class="prose prose-slate max-w-none mb-8 select-none trix-content">
                                    <div class="font-medium text-slate-800 leading-relaxed transition-all duration-300" 
                                         :class="{'text-base': fontSize === 1, 'text-xl': fontSize === 2, 'text-2xl': fontSize === 3}"
                                         x-html="questions[currentQuestion].question_text"></div>
                                </div>

                                
                                
                                
                                <template x-if="!questions[currentQuestion].question_type || questions[currentQuestion].question_type === 'choice'">
                                    <div class="space-y-3">
                                        <template x-for="(optionKey, optIndex) in questions[currentQuestion].displayKeys" :key="optionKey">
                                            
                                            <label class="relative flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 group active:scale-[0.99]" 
                                                   :class="answers[questions[currentQuestion].id] === optionKey ? 'border-cyan-500 bg-cyan-50 shadow-md' : 'border-slate-100 bg-white hover:border-cyan-200 hover:bg-cyan-50/30'">
                                                
                                                <input type="radio" :name="'q_' + questions[currentQuestion].id" :value="optionKey" 
                                                       @change="selectAnswer(questions[currentQuestion].id, optionKey)" 
                                                       x-model="answers[questions[currentQuestion].id]" class="peer sr-only">
                                                
                                                <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center font-black text-sm shrink-0 transition-colors shadow-sm relative" 
                                                     :class="answers[questions[currentQuestion].id] === optionKey ? 'bg-cyan-500 border-cyan-500 text-white' : 'bg-white border-slate-300 text-slate-500 group-hover:border-cyan-400 group-hover:text-cyan-600'">
                                                    
                                                    
                                                    <span x-text="['A', 'B', 'C', 'D', 'E'][optIndex]" 
                                                          x-show="savingQuestionId !== questions[currentQuestion].id || answers[questions[currentQuestion].id] !== optionKey"></span>
                                                    
                                                    <i class="ph-bold ph-spinner animate-spin text-lg" 
                                                       x-show="savingQuestionId === questions[currentQuestion].id && answers[questions[currentQuestion].id] === optionKey" style="display: none;"></i>
                                                </div>
                                                
                                                <div class="flex-1 select-none" x-data="{ 
                                                    get parsedOptions() { 
                                                        try { return typeof questions[currentQuestion].options === 'string' ? JSON.parse(questions[currentQuestion].options) : (questions[currentQuestion].options || {}); } 
                                                        catch(e) { return {}; } 
                                                    } 
                                                }">
                                                    <span class="font-medium transition-colors block" 
                                                          :class="{'text-sm md:text-base': fontSize === 1, 'text-base md:text-lg': fontSize === 2, 'text-lg md:text-xl': fontSize === 3, 'text-cyan-900': answers[questions[currentQuestion].id] === optionKey, 'text-slate-700': answers[questions[currentQuestion].id] !== optionKey}">
                                                        
                                                        
                                                        <span x-text="parsedOptions[optionKey]"></span>
                                                    </span>
                                                    
                                                    <template x-if="parsedOptions['image_' + optionKey]">
                                                        <img :src="'/storage/' + parsedOptions['image_' + optionKey]" class="mt-3 max-h-32 rounded-xl border border-slate-200 shadow-sm object-contain">
                                                    </template>
                                                </div>
                                            </label>
                                        </template>
                                    </div>
                                </template>

                                
                                <template x-if="questions[currentQuestion].question_type === 'true_false'">
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="relative flex flex-col items-center justify-center gap-3 p-6 rounded-2xl border-2 cursor-pointer transition-all duration-200 active:scale-[0.99]" 
                                               :class="answers[questions[currentQuestion].id] === 'A' ? 'border-emerald-500 bg-emerald-50 shadow-lg' : 'border-slate-100 bg-white hover:border-emerald-200'">
                                            <input type="radio" :name="'q_' + questions[currentQuestion].id" value="A" 
                                                    @change="selectAnswer(questions[currentQuestion].id, 'A')" 
                                                    x-model="answers[questions[currentQuestion].id]" class="peer sr-only">
                                            <i class="ph-fill ph-check-circle text-4xl" :class="answers[questions[currentQuestion].id] === 'A' ? 'text-emerald-500' : 'text-slate-300'"></i>
                                            <span class="font-black text-lg" :class="answers[questions[currentQuestion].id] === 'A' ? 'text-emerald-700' : 'text-slate-500'">BENAR</span>
                                        </label>
                                        
                                        <label class="relative flex flex-col items-center justify-center gap-3 p-6 rounded-2xl border-2 cursor-pointer transition-all duration-200 active:scale-[0.99]" 
                                               :class="answers[questions[currentQuestion].id] === 'B' ? 'border-rose-500 bg-rose-50 shadow-lg' : 'border-slate-100 bg-white hover:border-rose-200'">
                                            <input type="radio" :name="'q_' + questions[currentQuestion].id" value="B" 
                                                    @change="selectAnswer(questions[currentQuestion].id, 'B')" 
                                                    x-model="answers[questions[currentQuestion].id]" class="peer sr-only">
                                            <i class="ph-fill ph-x-circle text-4xl" :class="answers[questions[currentQuestion].id] === 'B' ? 'text-rose-500' : 'text-slate-300'"></i>
                                            <span class="font-black text-lg" :class="answers[questions[currentQuestion].id] === 'B' ? 'text-rose-700' : 'text-slate-500'">SALAH</span>
                                        </label>
                                    </div>
                                </template>

                                
                                <template x-if="questions[currentQuestion].question_type === 'essay'">
                                    <div class="relative">
                                        <textarea x-model="answers[questions[currentQuestion].id]" 
                                            @input.debounce.1000ms="selectAnswer(questions[currentQuestion].id, $event.target.value)" 
                                            class="w-full h-40 p-4 rounded-2xl border-2 border-slate-200 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 transition-all font-medium text-slate-700 leading-relaxed text-lg" 
                                            placeholder="Ketik jawaban Anda di sini..."></textarea>
                                        
                                        <div class="absolute top-4 right-4 text-cyan-600" x-show="savingQuestionId === questions[currentQuestion].id">
                                            <i class="ph-bold ph-spinner animate-spin text-xl"></i>
                                        </div>

                                        <div class="flex items-center gap-2 mt-3 text-slate-400">
                                            <i class="ph-fill ph-info text-cyan-500"></i>
                                            <p class="text-xs font-bold">Jawaban tersimpan otomatis saat Anda berhenti mengetik.</p>
                                        </div>
                                    </div>
                                </template>

                                
                                <template x-if="questions[currentQuestion].question_type === 'matching'">
                                    <div class="space-y-4">
                                        <p class="text-sm text-slate-500 font-bold mb-2">Pasangkan pernyataan di kiri dengan jawaban di kanan:</p>
                                        <template x-for="(pair, idx) in questions[currentQuestion].options.pairs" :key="idx">
                                            <div class="flex flex-col sm:flex-row items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                                                <div class="flex-1 font-medium text-slate-800 bg-white p-3 rounded-lg border border-slate-100 shadow-sm w-full text-center sm:text-left" x-text="pair.left"></div>
                                                <i class="ph-bold ph-arrows-left-right sm:ph-arrow-right text-cyan-400 text-xl font-bold"></i>
                                                <div class="flex-1 w-full relative">
                                                    <select class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-700 focus:ring-cyan-500 py-3 px-4 shadow-sm"
                                                            @change="updateMatching(questions[currentQuestion].id, pair.left, $event.target.value)">
                                                        <option value="" selected disabled>-- Pilih Pasangan --</option>
                                                        <template x-for="pOption in questions[currentQuestion].options.pairs" :key="pOption.right">
                                                            <option :value="pOption.right" x-text="pOption.right" 
                                                                    :selected="answers[questions[currentQuestion].id] && answers[questions[currentQuestion].id][pair.left] === pOption.right">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                            </div>
                        </template>
                    </div>

                    
                    <div class="p-4 md:p-6 bg-white border-t border-slate-100 flex justify-between items-center relative z-10 shrink-0">
                        <button @click="prevQuestion" :disabled="currentQuestion === 0" 
                                class="px-5 py-3 rounded-xl font-bold flex items-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed text-slate-500 hover:bg-slate-100 hover:text-slate-900">
                            <i class="ph-bold ph-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                        </button>
                        
                        <button @click="nextQuestion" x-show="currentQuestion < totalQuestions - 1" 
                                class="px-6 py-3 rounded-xl font-bold bg-cyan-600 text-white hover:bg-cyan-500 shadow-lg shadow-cyan-600/30 flex items-center gap-2 transition-all active:scale-95">
                            <span class="hidden sm:inline">Selanjutnya</span> <i class="ph-bold ph-arrow-right"></i>
                        </button>
                        
                        <button @click="finishExam" x-show="currentQuestion === totalQuestions - 1" 
                                class="px-6 py-3 rounded-xl font-bold shadow-lg flex items-center gap-2 transition-all active:scale-95"
                                :class="timeLeft > (effectiveDuration * 0.25) ? 'bg-slate-200 text-slate-400 hover:bg-slate-300' : 'bg-blue-950 text-white hover:bg-blue-900 shadow-blue-950/30'">
                            <span>Kumpulkan</span> 
                            <i class="ph-bold" :class="timeLeft > (effectiveDuration * 0.25) ? 'ph-lock' : 'ph-check-circle'"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>

        
        <aside class="fixed inset-y-0 right-0 w-80 bg-white shadow-2xl z-40 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:w-80 lg:shadow-none lg:border-l border-slate-200 flex flex-col" 
               :class="showMobileMap ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                    <i class="ph-fill ph-squares-four text-cyan-500"></i> Navigasi Soal
                </h3>
                <button @click="showMobileMap = false" class="lg:hidden text-slate-400 hover:text-slate-700">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>

            <div class="p-4 bg-blue-950/5 border-b border-blue-900/10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center text-cyan-100 font-bold shadow-md border border-blue-800">
                        <?php echo e(substr($student->name ?? 'S', 0, 1)); ?>

                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate"><?php echo e($student->name ?? 'Peserta'); ?></p>
                        <p class="text-[10px] text-cyan-600 font-bold uppercase tracking-wider">Progress: <span x-text="answeredCount"></span>/<span x-text="totalQuestions"></span></p>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scroll p-6">
                <div class="grid grid-cols-5 gap-3">
                    <template x-for="(q, index) in questions" :key="index">
                        <button @click="jumpTo(index)" 
                                class="aspect-square rounded-xl flex items-center justify-center font-bold text-sm transition-all shadow-sm border-2 relative" 
                                :class="{ 
                                    'border-blue-950 bg-white ring-2 ring-blue-950 ring-offset-2 z-10': currentQuestion === index, 
                                    'bg-cyan-500 text-white border-cyan-500': (answers[q.id] && Object.keys(answers[q.id] || {}).length > 0) && !markedQuestions[q.id] && currentQuestion !== index, 
                                    'bg-amber-400 text-white border-amber-400': markedQuestions[q.id], 
                                    'bg-white text-slate-600 border-slate-200 hover:border-cyan-300': (!answers[q.id] || Object.keys(answers[q.id] || {}).length === 0) && !markedQuestions[q.id] && currentQuestion !== index 
                                }">
                            <span x-text="index + 1"></span>
                            <span x-show="markedQuestions[q.id]" class="absolute -top-1 -right-1 w-3 h-3 bg-amber-500 rounded-full border-2 border-white"></span>
                        </button>
                    </template>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-100 bg-slate-50">
                <div class="grid grid-cols-2 gap-4 mb-4 text-[10px] uppercase font-bold text-slate-500">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-cyan-500"></span> Dijawab</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-white border-2 border-slate-300"></span> Kosong</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-400"></span> Ragu</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full border-2 border-blue-950"></span> Aktif</div>
                </div>
                
                <button @click="finishExam()" 
                        class="w-full py-3.5 rounded-xl font-bold shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2"
                        :class="timeLeft > (effectiveDuration * 0.25) ? 'bg-slate-200 text-slate-400 hover:bg-slate-300' : 'bg-blue-950 text-white hover:shadow-xl hover:bg-blue-900'">
                    <i class="ph-bold" :class="timeLeft > (effectiveDuration * 0.25) ? 'ph-lock' : 'ph-paper-plane-right'"></i> 
                    <span x-text="timeLeft > (effectiveDuration * 0.25) ? 'Terkunci (Waktu Minimal)' : 'Kumpulkan Jawaban'"></span>
                </button>
            </div>
        </aside>
        
        <div x-show="showMobileMap" @click="showMobileMap = false" x-transition.opacity class="fixed inset-0 bg-blue-950/50 z-30 lg:hidden"></div>
        
        <?php endif; ?>
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/student/exam_runner.blade.php ENDPATH**/ ?>