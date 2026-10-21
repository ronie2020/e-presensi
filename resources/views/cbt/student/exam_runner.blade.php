<!DOCTYPE html>
<html lang="id" oncontextmenu="return false" oncopy="return false" oncut="return false" onpaste="return false">
<head>
    <meta charset="utf-8">
    {{-- PERBAIKAN MOBILE META: Mencegah zoom in otomatis di HP saat klik input/textarea --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian - {{ $exam->title }}</title>
    
    {{-- CSS & JS Utama --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Icons & Alerts --}}
    <script src="https://unpkg.com/@phosphor-icons/web" async></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" async></script>

    {{-- KONFIGURASI MATHJAX (Rumus Matematika) --}}
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
        /* Base Reset Anti-Potong: Menjamin tinggi absolut di semua browser HP */
        body, html { 
            height: 100%; 
            margin: 0; 
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #020b18; 
            color: #f1f5f9; 
            user-select: none; 
            -webkit-user-select: none; 
            overflow: hidden; 
            overscroll-behavior-y: none; 
        }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(56, 189, 248, 0.3); border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #38bdf8; }
        
        /* Security */
        @media print { body { display: none; } }

        /* Loader */
        #loading-overlay { position: fixed; inset: 0; background: #020b18; z-index: 99999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.3s ease; }
        .spinner { width: 44px; height: 44px; border: 4px solid rgba(255, 255, 255, 0.1); border-top: 4px solid #38bdf8; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 1rem; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Trix Editor & Question Content Style in Dark Theme */
        .exam-question-body,
        .exam-question-body * {
            color: #f1f5f9 !important;
        }
        .exam-question-body strong,
        .exam-question-body b {
            color: #ffffff !important;
            font-weight: 700;
        }
        .exam-question-body p {
            margin-bottom: 0.75rem;
            line-height: 1.75;
        }
        .exam-question-body ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: #f1f5f9; }
        .exam-question-body ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; color: #f1f5f9; }
        .exam-question-body blockquote { border-left: 4px solid #38bdf8; padding-left: 1rem; color: #94a3b8; font-style: italic; }
        .exam-question-body img { max-width: 100%; height: auto; border-radius: 0.75rem; border: 1px solid rgba(255, 255, 255, 0.1); margin: 0.75rem 0; }
        .exam-question-body table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .exam-question-body table th,
        .exam-question-body table td { border: 1px solid rgba(255, 255, 255, 0.15); padding: 8px 12px; color: #f1f5f9; }
        .exam-question-body table th { background-color: rgba(255, 255, 255, 0.08); font-weight: 700; }
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

        // PERBAIKAN: Fetch Timeout untuk mengatasi koneksi gantung
        async function fetchWithTimeout(resource, options = {}) {
            const { timeout = 8000 } = options; 
            const controller = new AbortController();
            const id = setTimeout(() => controller.abort(), timeout);
            
            const response = await fetch(resource, {
                ...options,
                signal: controller.signal  
            });
            clearTimeout(id);
            return response;
        }

        window.examData = { 
            questions: @json($questions), 
            timeLeft: {{ $timeLeft ?? 0 }}, 
            effectiveDuration: {{ $effectiveDuration ?? (($exam->duration_minutes ?? 0) * 60) }},
            sessionId: {{ $sessionId }}, 
            examId: {{ $exam->id }},
            violationCount: {{ $violationCount ?? 0 }},
            examType: '{{ $exam->exam_type ?? 'cbt' }}', 
            randomizeQuestions: {{ $exam->randomize_questions ? 'true' : 'false' }},
            randomizeOptions: {{ $exam->randomize_options ? 'true' : 'false' }}
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
                
                // Network Latency & Heartbeat
                pingMs: 35,
                pingStatus: 'good', // 'good' (<200ms), 'fair' (<600ms), 'poor' (>=600ms), 'offline'
                heartbeatInterval: null,

                // Audio / Listening State
                audioPlays: {},
                activeAudio: null,
                activeAudioQuestionId: null,
                audioPlaying: false,
                audioProgress: 0,
                audioCurrentTime: '00:00',
                audioDuration: '00:00',

                // Scratchpad (Papan Coretan Digital)
                showScratchpad: false,
                isDrawing: false,
                scratchColor: '#38bdf8',
                scratchLineWidth: 3,
                scratchMode: 'draw',
                _scratchpadInited: false,

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

                        if (window.examData.randomizeQuestions) {
                            rawQuestions = shuffleArray(rawQuestions, rand);
                        }

                        rawQuestions.forEach(q => {
                            if (!q.question_type || q.question_type === 'choice' || q.question_type === 'complex' || q.question_type === 'multiple_choice') {
                                try {
                                    let parsedOpts = typeof q.options === 'string' ? JSON.parse(q.options) : (q.options || {});
                                    let keys = Object.keys(parsedOpts).filter(k => k.length === 1 && parsedOpts[k] != null && String(parsedOpts[k]).trim() !== '');
                                    let hasImages = ['A', 'B', 'C', 'D', 'E'].some(k => parsedOpts['image_'+k] != null && parsedOpts['image_'+k] !== '');

                                    if (keys.length === 0 && !hasImages && (!q.question_type || q.question_type === 'choice')) {
                                        // Tidak ada opsi teks maupun gambar -> Soal ini adalah Esai/Uraian!
                                        q.question_type = 'essay';
                                    } else {
                                        if (keys.length === 0) {
                                            keys = ['A', 'B', 'C', 'D', 'E'].filter(k => parsedOpts['image_'+k] != null);
                                        }
                                        if (window.examData.randomizeOptions && keys.length > 0) {
                                            keys = shuffleArray(keys, rand);
                                        }
                                        q.displayKeys = keys;
                                    }
                                } catch (e) {
                                    q.displayKeys = ['A', 'B', 'C', 'D', 'E']; 
                                }
                            } else if (q.question_type === 'matching') {
                                try {
                                    let mOpts = typeof q.options === 'string' ? JSON.parse(q.options) : (q.options || {});
                                    let pairs = mOpts.pairs || [];
                                    let rightChoices = pairs.map(p => p.right).filter(Boolean);
                                    q.shuffledRights = shuffleArray([...rightChoices], rand);
                                } catch(e) {
                                    q.shuffledRights = [];
                                }
                            }
                        });

                        this.questions = rawQuestions;
                        this.totalQuestions = this.questions.length;
                        
                        const now = new Date().getTime();
                        this.endTimeTarget = now + (this.timeLeft * 1000);
                        
                        try { this.loadLocalProgress(); } catch (e) {}
                        try { this.loadAudioPlays(); } catch (e) {}
                        
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
                                    let ans = q.saved_answer;
                                    if ((q.question_type === 'complex' || q.question_type === 'multiple_choice') && typeof ans === 'string') {
                                        try { ans = JSON.parse(ans); } catch(e) {}
                                    }
                                    this.answers[q.id] = ans; 
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

                        // Heartbeat & Proctor Message Polling (tiap 10 detik)
                        this.startHeartbeat();

                        setTimeout(() => { if(window.renderMath) window.renderMath(); }, 500);
                        this.$watch('currentQuestion', () => {
                            // Hentikan audio jika berpindah soal
                            if (this.activeAudio) {
                                this.activeAudio.pause();
                                this.audioPlaying = false;
                            }
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
                    this.answeredCount = Object.values(this.answers).filter(v => {
                        if (v === null || v === undefined) return false;
                        if (Array.isArray(v)) return v.length > 0;
                        if (typeof v === 'object') return Object.keys(v).length > 0;
                        return String(v).trim() !== '';
                    }).length;
                },

                // --- LOGIKA JAWABAN ---
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

                // --- PILIHAN GANDA KOMPLEKS (Multi-Centang ala ANBK) ---
                toggleComplexAnswer(questionId, optionKey) {
                    let current = this.answers[questionId];
                    if (!Array.isArray(current)) {
                        current = current ? [current] : [];
                    } else {
                        current = [...current];
                    }
                    let idx = current.indexOf(optionKey);
                    if (idx > -1) {
                        current.splice(idx, 1);
                    } else {
                        current.push(optionKey);
                    }
                    current.sort();
                    this.selectAnswer(questionId, current);
                },

                isComplexSelected(questionId, optionKey) {
                    let ans = this.answers[questionId];
                    if (Array.isArray(ans)) return ans.includes(optionKey);
                    if (typeof ans === 'string') {
                        try {
                            let arr = JSON.parse(ans);
                            return Array.isArray(arr) && arr.includes(optionKey);
                        } catch(e) {
                            return ans === optionKey;
                        }
                    }
                    return false;
                },

                // --- SOAL AUDIO / LISTENING (Play Limit) ---
                playAudio(questionId, audioUrl, limit = 2) {
                    if (!audioUrl) return;
                    let currentPlays = this.audioPlays[questionId] || 0;
                    
                    // Jika audio aktif adalah soal ini, toggle play / pause
                    if (this.activeAudio && this.activeAudioQuestionId === questionId) {
                        if (this.audioPlaying) {
                            this.activeAudio.pause();
                            this.audioPlaying = false;
                        } else {
                            this.activeAudio.play();
                            this.audioPlaying = true;
                        }
                        return;
                    }

                    // Cek batas limit pemutaran
                    if (currentPlays >= limit) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Batas Pemutaran Habis',
                            text: `Audio untuk soal ini hanya dapat diputar maksimal ${limit} kali.`,
                            confirmButtonColor: '#0284c7',
                            customClass: { popup: 'rounded-2xl bg-[#031d3d] text-white border border-white/10' }
                        });
                        return;
                    }

                    // Hentikan audio sebelumnya jika ada
                    if (this.activeAudio) {
                        this.activeAudio.pause();
                        this.activeAudio = null;
                    }

                    let fullUrl = this.getImageUrl(audioUrl);
                    let audio = new Audio(fullUrl);
                    this.activeAudio = audio;
                    this.activeAudioQuestionId = questionId;
                    this.audioPlays[questionId] = currentPlays + 1;
                    this.saveAudioPlays();

                    audio.addEventListener('loadedmetadata', () => {
                        this.audioDuration = this.formatDuration(audio.duration);
                    });

                    audio.addEventListener('timeupdate', () => {
                        if (audio.duration) {
                            this.audioProgress = (audio.currentTime / audio.duration) * 100;
                            this.audioCurrentTime = this.formatDuration(audio.currentTime);
                        }
                    });

                    audio.addEventListener('ended', () => {
                        this.audioPlaying = false;
                        this.audioProgress = 100;
                    });

                    audio.addEventListener('error', () => {
                        this.audioPlaying = false;
                        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'File audio gagal dimuat.' });
                    });

                    audio.play().then(() => {
                        this.audioPlaying = true;
                    }).catch(err => {
                        console.error('Audio play error:', err);
                        this.audioPlaying = false;
                    });
                },

                getAudioRemainingPlays(questionId, limit = 2) {
                    let used = this.audioPlays[questionId] || 0;
                    return Math.max(0, limit - used);
                },

                saveAudioPlays() {
                    try {
                        localStorage.setItem(`exam_${this.sessionId}_audio`, JSON.stringify(this.audioPlays));
                    } catch(e) {}
                },

                loadAudioPlays() {
                    try {
                        let saved = localStorage.getItem(`exam_${this.sessionId}_audio`);
                        if (saved) this.audioPlays = JSON.parse(saved);
                    } catch(e) {}
                },

                formatDuration(sec) {
                    if (isNaN(sec)) return '00:00';
                    let m = Math.floor(sec / 60);
                    let s = Math.floor(sec % 60);
                    return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                },

                // --- HEARTBEAT & LATENSI JARINGAN ---
                startHeartbeat() {
                    this.sendHeartbeat();
                    this.heartbeatInterval = setInterval(() => {
                        if (this.isOnline && this.saveStatus !== 'finished' && this.saveStatus !== 'submitting') {
                            this.sendHeartbeat();
                        }
                    }, 10000);
                },

                async sendHeartbeat() {
                    if (!this.isOnline) {
                        this.pingStatus = 'offline';
                        return;
                    }
                    const t0 = performance.now();
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch("{{ route('student.exam.ping') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify({ session_id: this.sessionId, latency_ms: this.pingMs })
                        });
                        const t1 = performance.now();
                        const rtt = Math.round(t1 - t0);
                        this.pingMs = rtt;
                        if (rtt < 200) this.pingStatus = 'good';
                        else if (rtt < 600) this.pingStatus = 'fair';
                        else this.pingStatus = 'poor';

                        if (res.ok) {
                            const data = await res.json();
                            if (data.messages && data.messages.length > 0) {
                                this.handleIncomingMessages(data.messages);
                            }
                        }
                    } catch (e) {
                        this.pingStatus = 'poor';
                    }
                },

                handleIncomingMessages(messages) {
                    messages.forEach(msg => {
                        if (msg.type === 'warning' || msg.type === 'urgent') {
                            Swal.fire({
                                icon: 'warning',
                                title: '<span class="text-rose-400 font-black">TEGURAN DARI PENGAWAS</span>',
                                html: `<div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-left my-2">
                                         <p class="text-white text-base font-semibold leading-relaxed">${msg.message}</p>
                                         <div class="mt-3 text-[11px] text-rose-300 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                             <i class="ph-fill ph-warning-octagon text-sm"></i> Harap patuhi tata tertib ujian demi kelancaran sesi Anda!
                                         </div>
                                       </div>`,
                                confirmButtonText: 'Saya Mengerti & Akan Tertib',
                                confirmButtonColor: '#e11d48',
                                allowOutsideClick: false,
                                customClass: { popup: 'rounded-[2rem] bg-[#031d3d] text-white border border-rose-500/30 shadow-2xl' }
                            }).then(() => {
                                // Tandai pesan sudah dibaca
                                try {
                                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                    fetch("{{ route('student.exam.message_read') }}", {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                        body: JSON.stringify({ message_id: msg.id })
                                    }).catch(() => {});
                                } catch(e) {}
                            });
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: '<span class="text-sky-400 font-black">PENGUMUMAN PROKTOR</span>',
                                html: `<div class="p-4 bg-sky-500/10 border border-sky-400/30 rounded-2xl text-left my-2">
                                         <p class="text-white text-base font-semibold leading-relaxed">${msg.message}</p>
                                       </div>`,
                                confirmButtonText: 'Baik, Terimakasih',
                                confirmButtonColor: '#0284c7',
                                customClass: { popup: 'rounded-[2rem] bg-[#031d3d] text-white border border-sky-500/30 shadow-2xl' }
                            });
                        }
                    });
                },

                // --- PAPAN CORETAN DIGITAL (SCRATCHPAD) ---
                initScratchpad() {
                    this.showScratchpad = true;
                    this.$nextTick(() => {
                        const canvas = document.getElementById('scratchpad-canvas');
                        if (!canvas) return;
                        if (!this._scratchpadInited) {
                            canvas.width = canvas.parentElement.clientWidth;
                            canvas.height = canvas.parentElement.clientHeight;
                            const ctx = canvas.getContext('2d');
                            ctx.lineCap = 'round';
                            ctx.lineJoin = 'round';
                            this._scratchpadInited = true;
                        }
                    });
                },

                startDraw(e) {
                    this.isDrawing = true;
                    const canvas = document.getElementById('scratchpad-canvas');
                    if (!canvas) return;
                    const rect = canvas.getBoundingClientRect();
                    const ctx = canvas.getContext('2d');
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    ctx.beginPath();
                    ctx.moveTo(clientX - rect.left, clientY - rect.top);
                },

                draw(e) {
                    if (!this.isDrawing) return;
                    const canvas = document.getElementById('scratchpad-canvas');
                    if (!canvas) return;
                    const rect = canvas.getBoundingClientRect();
                    const ctx = canvas.getContext('2d');
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    
                    if (this.scratchMode === 'erase') {
                        ctx.globalCompositeOperation = 'destination-out';
                        ctx.lineWidth = 24;
                    } else {
                        ctx.globalCompositeOperation = 'source-over';
                        ctx.strokeStyle = this.scratchColor;
                        ctx.lineWidth = this.scratchLineWidth;
                    }
                    ctx.lineTo(clientX - rect.left, clientY - rect.top);
                    ctx.stroke();
                },

                stopDraw() {
                    if (this.isDrawing) {
                        this.isDrawing = false;
                        const canvas = document.getElementById('scratchpad-canvas');
                        if (canvas) {
                            const ctx = canvas.getContext('2d');
                            ctx.closePath();
                        }
                    }
                },

                clearScratchpad() {
                    const canvas = document.getElementById('scratchpad-canvas');
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                },

                async pushAnswerToServer(questionId, answer) {
                    if (!this.isOnline) { this.saveStatus = 'pending'; return; }
                    this.saveStatus = 'saving';
                    this.savingQuestionId = questionId; 
                    
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        // PERBAIKAN: Gunakan fungsi fetchWithTimeout
                        const response = await fetchWithTimeout("{{ route('student.exam.saveAnswer') }}", {
                            method: 'POST', 
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify({ 
                                session_id: this.sessionId, 
                                question_id: questionId, 
                                answer: answer,                                
                                question_type: this.questions.find(q => q.id == questionId)?.question_type 
                            }),
                            timeout: 8000 // 8 detik toleransi
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
                        if(error.name === 'AbortError') {
                            console.warn('Network timeout, saved locally');
                            this.saveStatus = 'error'; // Aktifkan indikator Offline
                        } else if(error.message !== 'SESSION_EXPIRED') {
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
                        confirmButtonColor: '#172554',
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
                        if(data.violationCount !== undefined) this.violationCount = Math.max(this.violationCount, data.violationCount);
                        this.updateProgress();
                    }
                    if (window.examData.violationCount) {
                        this.violationCount = Math.max(this.violationCount, window.examData.violationCount);
                    }
                },

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
                    
                    this.showSecurityOverlay = true;

                    // Kirim log pelanggaran langsung ke server untuk proktor
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        fetch("{{ route('student.exam.violation') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify({ session_id: this.sessionId })
                        }).catch(e => console.warn('Violation log failed:', e));
                    } catch(e) {}

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
                    this.flushCurrentInput();
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

                async submitExam(forced = false) {
                    if (this.saveStatus === 'submitting' || this.saveStatus === 'finished') return;

                    if (!this.isOnline && this.examType !== 'google_form') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Internet Terputus!',
                            html: 'Sistem mendeteksi Anda sedang offline.<br>Jawaban Anda <b>Aman Tersimpan di Perangkat Ini</b>.<br><br>Mohon hubungkan kembali perangkat Anda ke internet. Sistem akan <b>otomatis mengumpulkan jawaban</b> ketika koneksi stabil.',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        return; 
                    }

                    const prevStatus = this.saveStatus;
                    this.saveStatus = 'submitting';

                    Swal.fire({ title: 'Menyelesaikan Sesi...', html: 'Sistem sedang memastikan 100% jawaban Anda tersimpan ke server. Mohon tunggu.', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2rem]' } });

                    if(this.examType !== 'google_form' && this.unsavedQuestions.size > 0) {
                        const syncSuccess = await this.syncPendingAnswers();
                        
                        if (!syncSuccess) {
                            this.saveStatus = prevStatus; 
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal Sinkronisasi!',
                                html: 'Server gagal merespon. Jawaban Anda <b>MASIH AMAN</b> di browser ini.<br><br>Jangan tutup halaman ini, periksa koneksi Anda dan coba kumpulkan lagi.',
                                confirmButtonText: 'Saya Mengerti',
                                confirmButtonColor: '#172554'
                            });
                            return; 
                        }
                    }

                    this.saveStatus = 'finished';
                    this.showSecurityOverlay = false; 
                    
                    clearInterval(this.timerInterval);
                    if(this.backgroundSyncInterval) clearInterval(this.backgroundSyncInterval);
                    if(this.heartbeatInterval) clearInterval(this.heartbeatInterval);
                    if(this.activeAudio) { this.activeAudio.pause(); this.activeAudio = null; }

                    localStorage.removeItem(`exam_${this.sessionId}`);

                    const form = document.createElement('form'); 
                    form.method = 'POST'; 
                    form.action = "{{ route('student.exam.finish', ':id') }}".replace(':id', this.examId);
                    
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
                        
                        fetch("{{ route('student.exam.photo') }}", { 
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                            body: JSON.stringify({ session_id: this.sessionId, photo: dataUrl })
                        }).catch(e => console.warn("Upload foto gagal", e));

                    } catch (e) {
                        console.error("Error capture photo", e);
                    }
                },

                // --- MEDIA & GAMBAR ---
                getImageUrl(path) {
                    if (!path) return '';
                    let cleanPath = path.toString().replace(/\\/g, '/');
                    if (cleanPath.startsWith('http://') || cleanPath.startsWith('https://')) return cleanPath;
                    if (cleanPath.startsWith('/storage/')) return cleanPath;
                    if (cleanPath.startsWith('storage/')) return '/' + cleanPath;
                    if (cleanPath.startsWith('/')) return cleanPath;
                    return '/storage/' + cleanPath;
                },

                // --- NAVIGASI UI ---
                flushCurrentInput() {
                    const activeEl = document.activeElement;
                    if (activeEl && (activeEl.tagName === 'TEXTAREA' || activeEl.tagName === 'INPUT')) {
                        activeEl.blur();
                    }
                },
                nextQuestion() { 
                    this.flushCurrentInput();
                    if (this.currentQuestion < this.totalQuestions - 1) this.currentQuestion++; 
                },
                prevQuestion() { 
                    this.flushCurrentInput();
                    if (this.currentQuestion > 0) this.currentQuestion--; 
                },
                jumpTo(index) { 
                    this.flushCurrentInput();
                    this.currentQuestion = index; 
                    this.showMobileMap = false; 
                },
            }
        }
        
        setTimeout(() => {
            const overlay = document.getElementById('loading-overlay');
            if(overlay && overlay.style.display !== 'none') overlay.style.display = 'none';
        }, 10000);
    </script>
</head>

{{-- PERBAIKAN TOTAL LAYOUT: Mencegah tombol bawah terpotong atau navbar hilang di HP Tiongkok --}}
<body class="bg-[#020b18] text-slate-100 antialiased overflow-hidden flex flex-col relative select-none" style="height: 100vh; height: 100dvh;"
    x-data="window.examApp()"
    x-init="initData(); startTimer(); initSecurity();"
    @online.window="isOnline = true; syncPendingAnswers()"
    @offline.window="isOnline = false; Swal.fire({toast: true, position: 'bottom-end', icon: 'error', title: 'Koneksi Terputus!', text: 'Jawaban disimpan di browser.', showConfirmButton: false, timer: 4000, customClass: {popup: 'rounded-2xl mb-4 bg-[#031d3d] text-white border border-white/10'} })"
    @focus.window="syncPendingAnswers()">

    {{-- AMBIENT GLOW EFFECTS --}}
    <div class="fixed top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-10 right-1/4 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    {{-- VIDEO KAMERA HIDDEN --}}
    <video id="webcam-video" autoplay playsinline muted style="position: fixed; top: 0; left: 0; width: 320px; height: 240px; opacity: 0; pointer-events: none; z-index: -100;"></video>

    {{-- LOADING OVERLAY --}}
    <div id="loading-overlay">
        <div class="spinner"></div>
        <span class="text-xs font-bold text-sky-300 uppercase tracking-widest mt-2">Memuat Ruangan Ujian...</span>
    </div>
    
    {{-- MODAL ZOOM GAMBAR --}}
    <div x-show="zoomedImage" x-transition.opacity class="fixed inset-0 z-[10000] bg-[#020b18]/95 backdrop-blur-md flex items-center justify-center p-4 cursor-zoom-out" style="display: none;" @click="zoomedImage = null">
        <img :src="zoomedImage" class="max-w-full max-h-full rounded-2xl shadow-2xl scale-100 transition-transform border border-white/10">
    </div>

    {{-- OVERLAY PELANGGARAN --}}
    <div x-show="showSecurityOverlay" x-transition.opacity class="fixed inset-0 bg-[#020b18]/95 backdrop-blur-xl z-[9000] flex items-center justify-center text-center px-6" style="display: none;" x-cloak>
        <div class="max-w-md w-full bg-[#031d3d] border border-white/10 rounded-[2.5rem] p-8 shadow-2xl text-white">
            <div class="w-16 h-16 bg-rose-500/20 border border-rose-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4 text-rose-400 text-3xl shadow-inner"><i class="ph-fill ph-warning-octagon"></i></div>
            <h2 class="text-xl font-black text-white mb-2 uppercase tracking-wide">Pelanggaran Terdeteksi</h2>
            
            <template x-if="violationCount < maxViolations">
                <div>
                    <p class="text-slate-300 mb-6 text-sm font-medium leading-relaxed">Anda terdeteksi meninggalkan halaman ujian (pindah tab atau membuka aplikasi lain). <br>Sisa toleransi: <span class="font-black text-rose-400" x-text="Math.max(0, maxViolations - violationCount)"></span> kali.</p>
                    <button @click="showSecurityOverlay = false" class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-sky-500/25">Lanjutkan Mengerjakan</button>
                </div>
            </template>

            <template x-if="violationCount >= maxViolations">
                <div>
                    <p class="text-slate-300 mb-6 text-sm font-medium leading-relaxed">Anda telah melewati batas maksimal pelanggaran.<br><b class="text-rose-400">Sistem menghentikan ujian Anda secara otomatis.</b></p>
                    <button @click="submitExam(true)" class="w-full bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-rose-600/30">
                        Kumpulkan Sekarang
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- NAVBAR ATAS --}}
    <nav class="shrink-0 bg-[#020b18]/90 backdrop-blur-xl text-white h-16 flex items-center justify-between px-4 lg:px-8 shadow-xl border-b border-white/10 z-50 relative">
        <div class="flex items-center gap-4 min-w-0 flex-1">
            <div class="flex items-center gap-3 shrink-0">
                <div class="w-10 h-10 bg-sky-500/10 rounded-xl flex items-center justify-center text-sky-400 border border-sky-400/20 shrink-0 overflow-hidden shadow-inner">
                    @if(!empty($student->photo_path))
                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto" class="w-full h-full object-cover">
                    @else
                        <i class="ph-bold ph-graduation-cap text-xl"></i>
                    @endif
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-bold text-sm lg:text-base truncate max-w-[200px] text-white">{{ $exam->title }}</h1>
                    <p class="text-[10px] lg:text-xs text-sky-300 font-bold uppercase tracking-wider truncate">{{ $exam->subject_name }}</p>
                </div>
            </div>

            <div class="hidden md:block w-px h-8 bg-white/10 mx-2"></div>

            <div class="hidden md:flex items-center gap-3 min-w-0">
                 <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold border border-sky-400/30 shrink-0 shadow-sm overflow-hidden">
                     @if(!empty($student->photo_path))
                         <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Avatar" class="w-full h-full object-cover">
                     @else
                         {{ substr($student->name ?? 'S', 0, 1) }}
                     @endif
                 </div>
                 <div class="truncate">
                     <p class="font-bold text-sm text-white truncate max-w-[150px]">
                         {{ $student->name ?? 'Peserta' }}
                     </p>
                     <p class="text-[10px] text-sky-200/80 font-bold truncate">
                         {{ $student->nis ?? $student->username ?? '' }}
                     </p>
                 </div>
            </div>
        </div>

        <div class="flex items-center gap-3 md:gap-4 shrink-0">
            {{-- Tombol Ukuran Font --}}
            <div x-show="examType !== 'google_form'" class="hidden md:flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-xl mr-1">
                <span class="text-[10px] text-sky-200/70 font-bold uppercase tracking-wider mr-1">Teks:</span>
                <button @click="fontSize = 1" class="font-medium hover:text-white transition px-1.5 py-0.5 rounded" :class="fontSize === 1 ? 'text-white bg-sky-500/20 border border-sky-400/30 font-black' : 'text-slate-400'">A</button>
                <button @click="fontSize = 2" class="font-medium text-lg hover:text-white transition px-1.5 py-0.5 rounded" :class="fontSize === 2 ? 'text-white bg-sky-500/20 border border-sky-400/30 font-black' : 'text-slate-400'">A</button>
                <button @click="fontSize = 3" class="font-medium text-xl hover:text-white transition px-1.5 py-0.5 rounded" :class="fontSize === 3 ? 'text-white bg-sky-500/20 border border-sky-400/30 font-black' : 'text-slate-400'">A</button>
            </div>

            {{-- Tombol Papan Coretan Digital --}}
            <button x-show="examType !== 'google_form'" type="button" @click="initScratchpad()" 
                    class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-white/10 bg-white/5 hover:bg-sky-500/20 hover:border-sky-400/40 text-sky-300 text-xs font-bold transition shadow-sm active:scale-95" 
                    title="Buka Papan Coretan / Buram Matematika">
                <i class="ph-bold ph-pencil-simple text-sm"></i>
                <span class="hidden md:inline">Coretan</span>
            </button>

            {{-- Indikator Ping & Latensi Real-Time --}}
            <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-mono font-bold transition-all shadow-sm"
                 :class="{
                     'bg-emerald-500/10 border-emerald-500/30 text-emerald-300': isOnline && pingStatus === 'good',
                     'bg-amber-500/10 border-amber-500/30 text-amber-300': isOnline && pingStatus === 'fair',
                     'bg-rose-500/10 border-rose-500/30 text-rose-300': isOnline && pingStatus === 'poor',
                     'bg-rose-500/20 border-rose-500/40 text-rose-300 animate-pulse': !isOnline
                 }"
                 :title="isOnline ? ('Latensi ke server: ' + pingMs + ' ms') : 'Koneksi Terputus'">
                <span class="w-2 h-2 rounded-full"
                      :class="{
                          'bg-emerald-400 animate-pulse': isOnline && pingStatus === 'good',
                          'bg-amber-400': isOnline && pingStatus === 'fair',
                          'bg-rose-400': isOnline && pingStatus === 'poor',
                          'bg-rose-500': !isOnline
                      }"></span>
                <span x-text="isOnline ? (pingMs + ' ms') : 'Offline'" class="text-[11px]"></span>
            </div>
            
            {{-- Indikator No Camera --}}
            <div x-show="!cameraActive" x-cloak class="hidden md:flex items-center gap-2 bg-white/5 border border-white/10 text-sky-300 px-3 py-1.5 rounded-xl text-xs font-bold" title="Kamera Tidak Aktif">
                <i class="ph-fill ph-video-camera-slash"></i>
            </div>

            {{-- Timer --}}
            <div class="flex items-center gap-2 bg-white/5 px-3.5 py-1.5 rounded-xl border border-white/10 transition-colors shadow-sm" :class="timeLeft < 300 ? 'bg-rose-500/20 border-rose-500/50 text-rose-300 animate-pulse' : 'text-sky-300'">
                <i class="ph-bold ph-timer text-lg text-sky-400"></i>
                <span x-text="formattedTime" class="font-mono font-bold text-lg text-white"></span>
            </div>
            
            {{-- Tombol Hamburger Map Soal --}}
            <button x-show="examType !== 'google_form'" @click="showMobileMap = !showMobileMap" class="lg:hidden w-10 h-10 bg-white/10 border border-white/10 rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition active:scale-95">
                <i class="ph-bold" :class="showMobileMap ? 'ph-x' : 'ph-squares-four'"></i>
            </button>
        </div>
    </nav>

    {{-- PROGRESS BAR HORIZONTAL --}}
    <div x-show="examType !== 'google_form'" class="shrink-0 h-1.5 w-full bg-[#020b18] border-b border-white/5 relative z-40 overflow-hidden">
        <div class="h-full bg-gradient-to-r from-sky-400 to-blue-500 transition-all duration-500 ease-out shadow-[0_0_12px_rgba(56,189,248,0.8)]" :style="`width: ${(answeredCount / totalQuestions) * 100}%`"></div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="flex-1 min-h-0 relative flex w-full">
        
        @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
            <main class="flex-1 flex flex-col h-full bg-[#020b18] relative z-0">
                <div class="bg-sky-500/10 backdrop-blur-sm border-b border-sky-400/20 p-3 flex items-center justify-center shrink-0 z-10 shadow-sm">
                    <p class="text-xs font-bold text-sky-200 flex items-center gap-2">
                        <i class="ph-fill ph-warning-circle text-sky-400 text-lg"></i> 
                        Pastikan Anda menekan tombol "Kirim / Submit" pada formulir di bawah sebelum mengklik tombol selesai.
                    </p>
                </div>
                
                <div class="flex-1 w-full h-full relative">
                    <iframe src="{{ $exam->google_form_url }}" class="absolute inset-0 w-full h-full border-0" allowfullscreen></iframe>
                </div>
                
                <div class="shrink-0 p-4 bg-[#031d3d]/90 backdrop-blur-xl border-t border-white/10 flex justify-between items-center relative z-10 shadow-2xl pb-[calc(1rem+env(safe-area-inset-bottom))]">
                    <div class="hidden sm:block">
                        <p class="text-xs font-bold text-slate-400">Ujian Berbasis Google Form</p>
                    </div>
                    <button @click="finishExam" 
                            class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all active:scale-95"
                            :class="timeLeft > (effectiveDuration * 0.25) ? 'bg-white/10 text-slate-400 border border-white/10 hover:bg-white/15' : 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/25'">
                        <i class="ph-bold" :class="timeLeft > (effectiveDuration * 0.25) ? 'ph-lock' : 'ph-check-circle'"></i>
                        <span x-text="timeLeft > (effectiveDuration * 0.25) ? 'Waktu Tunggu...' : 'Selesai & Keluar'"></span> 
                    </button>
                </div>
            </main>
        @else

        <main class="flex-1 flex flex-col w-full max-w-4xl mx-auto p-0 sm:p-4 md:p-6 lg:p-8 h-full relative z-10 min-h-0">
            <div class="bg-[#031d3d]/75 backdrop-blur-2xl sm:rounded-[2.5rem] shadow-2xl border border-white/10 flex-1 flex flex-col w-full h-full overflow-hidden relative min-h-0">
                
                {{-- Judul Bar Soal (shrink-0) --}}
                <div class="shrink-0 px-4 sm:px-6 py-3 sm:py-4 border-b border-white/10 flex justify-between items-center bg-white/[0.03]">
                    <span class="bg-gradient-to-r from-sky-500 to-blue-600 text-white text-xs font-black px-3.5 py-1.5 rounded-xl shadow-lg shadow-sky-500/20 flex items-center gap-1.5">
                        <i class="ph-bold ph-article"></i> Soal No. <span x-text="currentQuestion + 1"></span>
                    </span>
                    
                    <div class="flex items-center gap-4">
                        <span x-show="saveStatus === 'saving'" class="text-[10px] font-bold text-sky-400 uppercase flex items-center gap-1"><i class="ph-bold ph-spinner animate-spin"></i> <span class="hidden sm:inline">Menyimpan</span></span>
                        <span x-show="saveStatus === 'saved'" class="text-[10px] font-bold text-emerald-400 uppercase flex items-center gap-1"><i class="ph-fill ph-cloud-check text-base"></i> <span class="hidden sm:inline">Tersimpan</span></span>
                        <span x-show="saveStatus === 'error'" class="text-[10px] font-bold text-rose-400 uppercase flex items-center gap-1"><i class="ph-fill ph-warning-circle text-base"></i> <span class="hidden sm:inline">Error / Offline</span></span>
                        
                        <label class="flex items-center gap-2 cursor-pointer select-none group">
                            <div class="relative shrink-0">
                                <input type="checkbox" class="peer sr-only" x-model="markedQuestions[questions[currentQuestion]?.id]" @change="saveToLocal()">
                                <div class="w-5 h-5 rounded-lg border-2 border-white/20 bg-white/5 peer-checked:bg-amber-500 peer-checked:border-amber-400 transition-all shadow-sm"></div>
                                <i class="ph-bold ph-check text-slate-950 text-xs absolute top-0.5 left-0.5 opacity-0 peer-checked:opacity-100 font-black"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-400 uppercase group-hover:text-amber-400 transition">Ragu</span>
                        </label>
                    </div>
                </div>

                {{-- Area Teks Soal & Jawaban (Scrollable) --}}
                <div class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto overscroll-contain custom-scroll relative">
                    <template x-if="questions.length > 0 && questions[currentQuestion]">
                        <div>
                            {{-- AUDIO LISTENING WIDGET --}}
                            <template x-if="questions[currentQuestion].audio_path">
                                <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-sky-500/15 via-blue-500/10 to-transparent border border-sky-400/30 backdrop-blur-md shadow-lg">
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                        <div class="flex items-center gap-3.5 w-full sm:w-auto">
                                            <button type="button" 
                                                    @click="playAudio(questions[currentQuestion].id, questions[currentQuestion].audio_path, questions[currentQuestion].audio_play_limit || 2)"
                                                    :disabled="getAudioRemainingPlays(questions[currentQuestion].id, questions[currentQuestion].audio_play_limit || 2) === 0 && !(activeAudio && activeAudioQuestionId === questions[currentQuestion].id)"
                                                    class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-lg transition transform active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed shrink-0"
                                                    :class="activeAudio && activeAudioQuestionId === questions[currentQuestion].id && audioPlaying ? 'bg-amber-500 shadow-amber-500/30 ring-2 ring-amber-300' : 'bg-gradient-to-tr from-sky-500 to-blue-600 shadow-sky-500/30 hover:brightness-110'">
                                                <i class="ph-fill text-2xl" :class="activeAudio && activeAudioQuestionId === questions[currentQuestion].id && audioPlaying ? 'ph-pause' : 'ph-play'"></i>
                                            </button>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-black uppercase tracking-wider text-sky-300 flex items-center gap-1.5">
                                                        <i class="ph-fill ph-headphones text-sm"></i> Soal Audio (Listening)
                                                    </span>
                                                    <span x-show="activeAudio && activeAudioQuestionId === questions[currentQuestion].id && audioPlaying" 
                                                          class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 animate-pulse">
                                                        Memutar
                                                    </span>
                                                </div>
                                                <p class="text-[11px] text-slate-300 font-medium mt-0.5">
                                                    Sisa putar: <b class="text-sky-400 font-black" x-text="getAudioRemainingPlays(questions[currentQuestion].id, questions[currentQuestion].audio_play_limit || 2)"></b> dari <span x-text="questions[currentQuestion].audio_play_limit || 2"></span>x kesempatan
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="w-full sm:w-56 flex items-center gap-3">
                                            <div class="flex-1 bg-white/10 rounded-full h-2 overflow-hidden border border-white/5">
                                                <div class="bg-gradient-to-r from-sky-400 to-blue-500 h-full rounded-full transition-all duration-200" 
                                                     :style="'width: ' + (activeAudio && activeAudioQuestionId === questions[currentQuestion].id ? audioProgress : 0) + '%'"></div>
                                            </div>
                                            <span class="text-[10px] font-mono text-slate-400 shrink-0" 
                                                  x-text="activeAudio && activeAudioQuestionId === questions[currentQuestion].id ? (audioCurrentTime + '/' + audioDuration) : '00:00'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- Gambar Soal --}}
                            <template x-if="questions[currentQuestion].question_image">
                                <div class="mb-6 relative group w-fit">
                                    <img :src="getImageUrl(questions[currentQuestion].question_image)" 
                                         onerror="this.classList.add('opacity-40'); this.title='Gambar tidak dapat dimuat';" 
                                         @click="zoomedImage = getImageUrl(questions[currentQuestion].question_image)"
                                         class="max-h-[350px] w-auto rounded-2xl border border-white/10 shadow-lg cursor-zoom-in transition group-hover:brightness-110 object-contain">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition pointer-events-none">
                                        <span class="bg-black/60 text-white p-2.5 rounded-full backdrop-blur-md border border-white/20"><i class="ph-bold ph-magnifying-glass-plus text-lg"></i></span>
                                    </div>
                                </div>
                            </template>
                            
                            {{-- Teks Soal --}}
                            <div class="prose prose-invert max-w-none mb-8 select-text trix-content">
                                <div class="exam-question-body font-normal text-slate-100 leading-relaxed transition-all duration-300" 
                                     :class="{'text-base': fontSize === 1, 'text-xl': fontSize === 2, 'text-2xl': fontSize === 3}"
                                     @click="$event.target.tagName === 'IMG' ? (zoomedImage = $event.target.src) : null"
                                     x-html="questions[currentQuestion].question_text"></div>
                            </div>

                            {{-- TIPE 1: PILIHAN GANDA --}}
                            <template x-if="!questions[currentQuestion].question_type || questions[currentQuestion].question_type === 'choice'">
                                <div class="space-y-3">
                                    <template x-for="(optionKey, optIndex) in questions[currentQuestion].displayKeys" :key="optionKey">
                                        
                                        <label class="relative flex items-center gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-200 group active:scale-[0.99] touch-manipulation" 
                                               :class="answers[questions[currentQuestion].id] === optionKey ? 'border-sky-400 bg-sky-500/20 shadow-lg shadow-sky-500/10 ring-1 ring-sky-400/50' : 'border-white/10 bg-white/[0.03] hover:border-sky-400/40 hover:bg-white/[0.07]'">
                                            
                                            <input type="radio" :name="'q_' + questions[currentQuestion].id" :value="optionKey" 
                                                   @change="selectAnswer(questions[currentQuestion].id, optionKey)" 
                                                   x-model="answers[questions[currentQuestion].id]" class="peer sr-only">
                                            
                                            <div class="w-10 h-10 rounded-full border flex items-center justify-center font-black text-sm shrink-0 transition-all shadow-sm relative" 
                                                 :class="answers[questions[currentQuestion].id] === optionKey ? 'bg-gradient-to-tr from-sky-500 to-blue-600 border-sky-300 text-white shadow-sky-500/30 ring-2 ring-sky-400/40' : 'bg-white/10 border-white/20 text-slate-300 group-hover:border-sky-400 group-hover:text-white'">
                                                
                                                <span x-text="['A', 'B', 'C', 'D', 'E'][optIndex]" 
                                                      x-show="savingQuestionId !== questions[currentQuestion].id || answers[questions[currentQuestion].id] !== optionKey"></span>
                                                
                                                <i class="ph-bold ph-spinner animate-spin text-lg text-white" 
                                                   x-show="savingQuestionId === questions[currentQuestion].id && answers[questions[currentQuestion].id] === optionKey" style="display: none;"></i>
                                            </div>
                                            
                                            <div class="flex-1 select-text" x-data="{ 
                                                get parsedOptions() { 
                                                    try { return typeof questions[currentQuestion].options === 'string' ? JSON.parse(questions[currentQuestion].options) : (questions[currentQuestion].options || {}); } 
                                                    catch(e) { return {}; } 
                                                } 
                                            }">
                                                <span class="font-medium transition-colors block leading-relaxed" 
                                                      :class="{'text-sm md:text-base': fontSize === 1, 'text-base md:text-lg': fontSize === 2, 'text-lg md:text-xl': fontSize === 3, 'text-white font-semibold': answers[questions[currentQuestion].id] === optionKey, 'text-slate-200': answers[questions[currentQuestion].id] !== optionKey}">
                                                    <span x-text="parsedOptions[optionKey]"></span>
                                                </span>
                                                
                                                <template x-if="parsedOptions['image_' + optionKey]">
                                                    <img :src="getImageUrl(parsedOptions['image_' + optionKey])" 
                                                         @click.stop="zoomedImage = getImageUrl(parsedOptions['image_' + optionKey])"
                                                         class="mt-3 max-h-36 rounded-xl border border-white/10 shadow-md object-contain bg-black/20 p-1 cursor-zoom-in hover:brightness-110 transition">
                                                </template>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </template>

                            {{-- TIPE 1.5: PILIHAN GANDA KOMPLEKS (Multi-Centang ala ANBK) --}}
                            <template x-if="questions[currentQuestion].question_type === 'complex' || questions[currentQuestion].question_type === 'multiple_choice'">
                                <div class="space-y-3">
                                    <div class="p-3 rounded-2xl bg-indigo-500/10 border border-indigo-400/20 text-indigo-300 text-xs font-bold flex items-center gap-2">
                                        <i class="ph-fill ph-check-square-offset text-indigo-400 text-base"></i>
                                        <span>Pilihan Ganda Kompleks: Anda dapat memilih lebih dari satu jawaban yang benar.</span>
                                    </div>

                                    <template x-for="(optionKey, optIndex) in questions[currentQuestion].displayKeys" :key="optionKey">
                                        <label class="relative flex items-center gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-200 group active:scale-[0.99] touch-manipulation" 
                                               :class="isComplexSelected(questions[currentQuestion].id, optionKey) ? 'border-indigo-400 bg-indigo-500/20 shadow-lg shadow-indigo-500/10 ring-1 ring-indigo-400/50' : 'border-white/10 bg-white/[0.03] hover:border-indigo-400/40 hover:bg-white/[0.07]'">
                                            
                                            <input type="checkbox" :value="optionKey" 
                                                   @change="toggleComplexAnswer(questions[currentQuestion].id, optionKey)"
                                                   :checked="isComplexSelected(questions[currentQuestion].id, optionKey)"
                                                   class="peer sr-only">
                                            
                                            <div class="w-10 h-10 rounded-xl border flex items-center justify-center font-black text-sm shrink-0 transition-all shadow-sm relative" 
                                                 :class="isComplexSelected(questions[currentQuestion].id, optionKey) ? 'bg-gradient-to-tr from-indigo-500 to-sky-500 border-indigo-300 text-white shadow-indigo-500/30 ring-2 ring-indigo-400/40' : 'bg-white/10 border-white/20 text-slate-300 group-hover:border-indigo-400 group-hover:text-white'">
                                                
                                                <template x-if="isComplexSelected(questions[currentQuestion].id, optionKey)">
                                                    <i class="ph-bold ph-check text-base"></i>
                                                </template>
                                                <template x-if="!isComplexSelected(questions[currentQuestion].id, optionKey)">
                                                    <span x-text="['A', 'B', 'C', 'D', 'E'][optIndex]"></span>
                                                </template>
                                            </div>
                                            
                                            <div class="flex-1 select-text" x-data="{ 
                                                get parsedOptions() { 
                                                    try { return typeof questions[currentQuestion].options === 'string' ? JSON.parse(questions[currentQuestion].options) : (questions[currentQuestion].options || {}); } 
                                                    catch(e) { return {}; } 
                                                } 
                                            }">
                                                <span class="font-medium transition-colors block leading-relaxed" 
                                                      :class="{'text-sm md:text-base': fontSize === 1, 'text-base md:text-lg': fontSize === 2, 'text-lg md:text-xl': fontSize === 3, 'text-white font-semibold': isComplexSelected(questions[currentQuestion].id, optionKey), 'text-slate-200': !isComplexSelected(questions[currentQuestion].id, optionKey)}">
                                                    <span x-text="parsedOptions[optionKey]"></span>
                                                </span>
                                                
                                                <template x-if="parsedOptions['image_' + optionKey]">
                                                    <img :src="getImageUrl(parsedOptions['image_' + optionKey])" 
                                                         @click.stop="zoomedImage = getImageUrl(parsedOptions['image_' + optionKey])"
                                                         class="mt-3 max-h-36 rounded-xl border border-white/10 shadow-md object-contain bg-black/20 p-1 cursor-zoom-in hover:brightness-110 transition">
                                                </template>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </template>

                            {{-- TIPE 2: BENAR / SALAH --}}
                            <template x-if="questions[currentQuestion].question_type === 'true_false'">
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative flex flex-col items-center justify-center gap-3 p-6 rounded-2xl border cursor-pointer transition-all duration-200 active:scale-[0.99] touch-manipulation" 
                                           :class="answers[questions[currentQuestion].id] === 'A' ? 'border-emerald-400 bg-emerald-500/25 shadow-lg shadow-emerald-500/20 ring-1 ring-emerald-400' : 'border-white/10 bg-white/[0.03] hover:border-emerald-400/40 hover:bg-emerald-500/10'">
                                        <input type="radio" :name="'q_' + questions[currentQuestion].id" value="A" 
                                                @change="selectAnswer(questions[currentQuestion].id, 'A')" 
                                                x-model="answers[questions[currentQuestion].id]" class="peer sr-only">
                                        <i class="ph-fill ph-check-circle text-4xl" :class="answers[questions[currentQuestion].id] === 'A' ? 'text-emerald-400' : 'text-slate-500'"></i>
                                        <span class="font-black text-lg" :class="answers[questions[currentQuestion].id] === 'A' ? 'text-emerald-300' : 'text-slate-300'">BENAR</span>
                                    </label>
                                    
                                    <label class="relative flex flex-col items-center justify-center gap-3 p-6 rounded-2xl border cursor-pointer transition-all duration-200 active:scale-[0.99] touch-manipulation" 
                                           :class="answers[questions[currentQuestion].id] === 'B' ? 'border-rose-400 bg-rose-500/25 shadow-lg shadow-rose-500/20 ring-1 ring-rose-400' : 'border-white/10 bg-white/[0.03] hover:border-rose-400/40 hover:bg-rose-500/10'">
                                        <input type="radio" :name="'q_' + questions[currentQuestion].id" value="B" 
                                                @change="selectAnswer(questions[currentQuestion].id, 'B')" 
                                                x-model="answers[questions[currentQuestion].id]" class="peer sr-only">
                                        <i class="ph-fill ph-x-circle text-4xl" :class="answers[questions[currentQuestion].id] === 'B' ? 'text-rose-400' : 'text-slate-500'"></i>
                                        <span class="font-black text-lg" :class="answers[questions[currentQuestion].id] === 'B' ? 'text-rose-300' : 'text-slate-300'">SALAH</span>
                                    </label>
                                </div>
                            </template>

                            {{-- TIPE 3: ESSAY --}}
                            <template x-if="questions[currentQuestion].question_type === 'essay'">
                                <div class="relative">
                                    <textarea x-model="answers[questions[currentQuestion].id]" 
                                        @input.debounce.1000ms="selectAnswer(questions[currentQuestion].id, $event.target.value)" 
                                        @blur="selectAnswer(questions[currentQuestion].id, $event.target.value)"
                                        @change="selectAnswer(questions[currentQuestion].id, $event.target.value)"
                                        class="w-full h-44 p-4 rounded-2xl border border-white/15 bg-white/5 focus:border-sky-400 focus:ring-4 focus:ring-sky-500/20 transition-all font-medium text-white placeholder-slate-400 leading-relaxed text-lg" 
                                        placeholder="Ketik jawaban Anda di sini..."></textarea>
                                    
                                    <div class="absolute top-4 right-4 text-sky-400" x-show="savingQuestionId === questions[currentQuestion].id">
                                        <i class="ph-bold ph-spinner animate-spin text-xl"></i>
                                    </div>

                                    <div class="flex items-center gap-2 mt-3 text-slate-400">
                                        <i class="ph-fill ph-info text-sky-400"></i>
                                        <p class="text-xs font-bold text-slate-400">Jawaban tersimpan otomatis saat Anda berhenti mengetik atau berpindah nomor.</p>
                                    </div>
                                </div>
                            </template>

                            {{-- TIPE 4: MENJODOHKAN --}}
                            <template x-if="questions[currentQuestion].question_type === 'matching'">
                                <div class="space-y-4">
                                    <p class="text-sm text-slate-300 font-bold mb-2">Pasangkan pernyataan di kiri dengan jawaban di kanan:</p>
                                    <template x-for="(pair, idx) in questions[currentQuestion].options.pairs" :key="idx">
                                        <div class="flex flex-col sm:flex-row items-center gap-3 p-4 bg-white/5 rounded-2xl border border-white/10">
                                            <div class="flex-1 font-semibold text-white bg-white/10 p-3.5 rounded-xl border border-white/10 shadow-sm w-full text-center sm:text-left" x-text="pair.left"></div>
                                            <i class="ph-bold ph-arrows-left-right sm:ph-arrow-right text-sky-400 text-xl font-bold"></i>
                                            <div class="flex-1 w-full relative">
                                                <select class="w-full rounded-xl border-white/20 bg-[#021124] text-sm font-bold text-white focus:ring-sky-500 py-3 px-4 shadow-sm touch-manipulation"
                                                        @change="updateMatching(questions[currentQuestion].id, pair.left, $event.target.value)">
                                                    <option value="" selected disabled>-- Pilih Pasangan --</option>
                                                    <template x-for="pOption in (questions[currentQuestion].shuffledRights && questions[currentQuestion].shuffledRights.length > 0 ? questions[currentQuestion].shuffledRights : (questions[currentQuestion].options?.pairs?.map(p => p.right) || []))" :key="pOption">
                                                        <option :value="pOption" x-text="pOption" 
                                                                :selected="answers[questions[currentQuestion].id] && answers[questions[currentQuestion].id][pair.left] === pOption">
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

                {{-- Tombol Bawah --}}
                <div class="shrink-0 p-4 md:p-6 bg-[#031d3d]/90 backdrop-blur-xl border-t border-white/10 flex justify-between items-center relative z-10 pb-[calc(1rem+env(safe-area-inset-bottom))] md:pb-6">
                    <button @click="prevQuestion" :disabled="currentQuestion === 0" 
                            class="px-5 py-3 rounded-xl font-bold flex items-center gap-2 transition disabled:opacity-40 disabled:cursor-not-allowed bg-white/10 hover:bg-white/15 text-slate-200 border border-white/10 touch-manipulation">
                        <i class="ph-bold ph-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                    </button>
                    
                    <button @click="nextQuestion" x-show="currentQuestion < totalQuestions - 1" 
                            class="px-6 py-3 rounded-xl font-bold bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-lg shadow-sky-500/25 flex items-center gap-2 transition-all active:scale-95 touch-manipulation">
                        <span class="hidden sm:inline">Selanjutnya</span> <i class="ph-bold ph-arrow-right"></i>
                    </button>
                    
                    <button @click="finishExam" x-show="currentQuestion === totalQuestions - 1" 
                            class="px-6 py-3 rounded-xl font-bold shadow-lg flex items-center gap-2 transition-all active:scale-95 touch-manipulation"
                            :class="timeLeft > (effectiveDuration * 0.25) ? 'bg-white/10 text-slate-400 border border-white/10 hover:bg-white/15' : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/30'">
                        <span>Kumpulkan</span> 
                        <i class="ph-bold" :class="timeLeft > (effectiveDuration * 0.25) ? 'ph-lock' : 'ph-check-circle'"></i>
                    </button>
                </div>
            </div>
        </main>

        {{-- SIDEBAR NAVIGASI SOAL --}}
        <aside class="fixed inset-y-0 right-0 w-80 bg-[#020b18]/95 backdrop-blur-2xl shadow-2xl z-40 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:w-80 lg:shadow-none lg:border-l border-white/10 flex flex-col max-h-[100dvh]" 
               :class="showMobileMap ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'">
            
            <div class="shrink-0 p-4 lg:p-6 border-b border-white/10 flex justify-between items-center bg-white/[0.03]">
                <h3 class="font-black text-white text-lg flex items-center gap-2">
                    <i class="ph-fill ph-squares-four text-sky-400"></i> Navigasi Soal
                </h3>
                <button @click="showMobileMap = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>

            <div class="shrink-0 p-4 bg-white/[0.02] border-b border-white/10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-md border border-sky-400/30 overflow-hidden">
                        @if(!empty($student->photo_path))
                            <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            {{ substr($student->name ?? 'S', 0, 1) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $student->name ?? 'Peserta' }}</p>
                        <p class="text-[10px] text-sky-300 font-bold uppercase tracking-wider">Progress: <span x-text="answeredCount"></span>/<span x-text="totalQuestions"></span></p>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 min-h-0 overflow-y-auto custom-scroll p-4 lg:p-6 overscroll-contain">
                <div class="grid grid-cols-5 gap-3">
                    <template x-for="(q, index) in questions" :key="index">
                        <button @click="jumpTo(index)" 
                                class="aspect-square rounded-xl flex items-center justify-center font-bold text-sm transition-all shadow-sm border relative touch-manipulation" 
                                :class="{ 
                                    'border-sky-400 bg-sky-500/30 text-white ring-2 ring-sky-400 ring-offset-2 ring-offset-[#020b18] z-10 scale-110 shadow-lg shadow-sky-500/30': currentQuestion === index, 
                                    'bg-sky-500/20 text-sky-200 border-sky-400/50 hover:bg-sky-500/30': (answers[q.id] && Object.keys(answers[q.id] || {}).length > 0) && !markedQuestions[q.id] && currentQuestion !== index, 
                                    'bg-amber-500/25 text-amber-200 border-amber-400/60 hover:bg-amber-500/35': markedQuestions[q.id] && currentQuestion !== index, 
                                    'bg-white/5 text-slate-300 border-white/10 hover:border-sky-400/40 hover:bg-white/10': (!answers[q.id] || Object.keys(answers[q.id] || {}).length === 0) && !markedQuestions[q.id] && currentQuestion !== index 
                                }">
                            <span x-text="index + 1"></span>
                            <span x-show="markedQuestions[q.id]" class="absolute -top-1 -right-1 w-3 h-3 bg-amber-400 rounded-full border-2 border-[#020b18]"></span>
                        </button>
                    </template>
                </div>
            </div>
            
            <div class="shrink-0 p-4 lg:p-6 border-t border-white/10 bg-white/[0.02] pb-[calc(1rem+env(safe-area-inset-bottom))]">
                <div class="grid grid-cols-2 gap-4 mb-4 text-[10px] uppercase font-bold text-slate-400">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-sky-400/80 border border-sky-300"></span> Dijawab</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-white/10 border border-white/20"></span> Kosong</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-400 border border-amber-300"></span> Ragu</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full border-2 border-sky-400 bg-sky-500/30"></span> Aktif</div>
                </div>
                
                <button @click="finishExam()" 
                        class="w-full py-3.5 rounded-xl font-bold shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2 touch-manipulation"
                        :class="timeLeft > (effectiveDuration * 0.25) ? 'bg-white/10 text-slate-400 border border-white/10' : 'bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-sky-500/25'">
                    <i class="ph-bold" :class="timeLeft > (effectiveDuration * 0.25) ? 'ph-lock' : 'ph-paper-plane-right'"></i> 
                    <span x-text="timeLeft > (effectiveDuration * 0.25) ? 'Terkunci (Waktu Minimal)' : 'Kumpulkan Jawaban'"></span>
                </button>
            </div>
        </aside>
        
        <div x-show="showMobileMap" @click="showMobileMap = false" x-transition.opacity class="fixed inset-0 bg-[#020b18]/70 z-30 lg:hidden backdrop-blur-sm"></div>
        
        @endif
    </div>

    {{-- MODAL PAPAN CORETAN DIGITAL (SCRATCHPAD) --}}
    <div x-show="showScratchpad" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-2 sm:inset-8 z-[10000] bg-[#020b18]/95 backdrop-blur-2xl rounded-[2.5rem] border border-sky-400/30 shadow-2xl flex flex-col overflow-hidden" 
         style="display: none;" x-cloak>
        
        {{-- Toolbar Coretan --}}
        <div class="shrink-0 p-4 border-b border-white/10 bg-white/5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-sky-400">
                    <i class="ph-bold ph-pencil-simple text-xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-white text-base">Papan Coretan Digital</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Gunakan untuk coret-coret hitungan / buram soal.</p>
                </div>
            </div>

            {{-- Tool controls --}}
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Warna Pen --}}
                <div class="flex items-center gap-1.5 bg-white/5 p-1 rounded-xl border border-white/10">
                    <button type="button" @click="scratchColor = '#38bdf8'; scratchMode = 'draw'" 
                            class="w-6 h-6 rounded-lg bg-sky-400 transition" :class="scratchColor === '#38bdf8' && scratchMode === 'draw' ? 'ring-2 ring-white scale-110' : 'opacity-70 hover:opacity-100'"></button>
                    <button type="button" @click="scratchColor = '#fbbf24'; scratchMode = 'draw'" 
                            class="w-6 h-6 rounded-lg bg-amber-400 transition" :class="scratchColor === '#fbbf24' && scratchMode === 'draw' ? 'ring-2 ring-white scale-110' : 'opacity-70 hover:opacity-100'"></button>
                    <button type="button" @click="scratchColor = '#34d399'; scratchMode = 'draw'" 
                            class="w-6 h-6 rounded-lg bg-emerald-400 transition" :class="scratchColor === '#34d399' && scratchMode === 'draw' ? 'ring-2 ring-white scale-110' : 'opacity-70 hover:opacity-100'"></button>
                    <button type="button" @click="scratchColor = '#f87171'; scratchMode = 'draw'" 
                            class="w-6 h-6 rounded-lg bg-rose-400 transition" :class="scratchColor === '#f87171' && scratchMode === 'draw' ? 'ring-2 ring-white scale-110' : 'opacity-70 hover:opacity-100'"></button>
                    <button type="button" @click="scratchColor = '#ffffff'; scratchMode = 'draw'" 
                            class="w-6 h-6 rounded-lg bg-white transition" :class="scratchColor === '#ffffff' && scratchMode === 'draw' ? 'ring-2 ring-sky-400 scale-110' : 'opacity-70 hover:opacity-100'"></button>
                </div>

                {{-- Mode: Pen vs Eraser --}}
                <div class="flex items-center gap-1 bg-white/5 p-1 rounded-xl border border-white/10 text-xs font-bold">
                    <button type="button" @click="scratchMode = 'draw'" 
                            class="px-2.5 py-1.5 rounded-lg transition" 
                            :class="scratchMode === 'draw' ? 'bg-sky-500 text-white shadow-sm' : 'text-slate-400 hover:text-white'">
                        <i class="ph-bold ph-pencil"></i> Pen
                    </button>
                    <button type="button" @click="scratchMode = 'erase'" 
                            class="px-2.5 py-1.5 rounded-lg transition" 
                            :class="scratchMode === 'erase' ? 'bg-rose-500 text-white shadow-sm' : 'text-slate-400 hover:text-white'">
                        <i class="ph-bold ph-eraser"></i> Hapus
                    </button>
                </div>

                {{-- Tombol Bersihkan --}}
                <button type="button" @click="clearScratchpad()" class="px-3 py-1.5 rounded-xl border border-rose-500/30 bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 text-xs font-bold transition flex items-center gap-1">
                    <i class="ph-bold ph-trash"></i> Bersihkan Semua
                </button>

                {{-- Tombol Tutup --}}
                <button type="button" @click="showScratchpad = false" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/10" title="Tutup / Simpan Coretan">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
        </div>

        {{-- Area Canvas --}}
        <div class="flex-1 w-full h-full relative bg-[#020b18]/60 cursor-crosshair touch-none select-none overflow-hidden">
            <canvas id="scratchpad-canvas" 
                    class="absolute inset-0 w-full h-full"
                    @mousedown="startDraw($event)"
                    @mousemove="draw($event)"
                    @mouseup="stopDraw()"
                    @mouseleave="stopDraw()"
                    @touchstart.prevent="startDraw($event)"
                    @touchmove.prevent="draw($event)"
                    @touchend.prevent="stopDraw()"></canvas>
        </div>
    </div>
</body>
</html>