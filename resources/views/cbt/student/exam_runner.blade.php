<!DOCTYPE html>
<html lang="id" oncontextmenu="return false">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian Online - {{ $exam->title }}</title>
    
    {{-- 
      PENTING UNTUK SEB:
      Jangan gunakan 'npm run dev' saat testing dengan SEB. 
      Vite server (port 5173) sering diblokir oleh SEB.
      Gunakan 'npm run build' agar asset menjadi file statis (.css/.js) biasa.
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- CDN Libraries --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { 
            user-select: none; 
            -webkit-user-select: none; 
        }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Emergency Error Message (Hidden by default) */
        #fatal-error { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased"
    x-data="examApp(@json($questions), {{ $timeLeft }}, {{ $sessionId }}, {{ $exam->id }})"
    x-init="initData(); startTimer(); initSecurity(); checkPendingAnswers()"
    @online.window="isOnline = true; syncPendingAnswers()"
    @offline.window="isOnline = false">

    <!-- DETEKSI JS ERROR (Emergency Fallback) -->
    <!-- Pesan ini akan muncul jika Alpine.js GAGAL dimuat (misal karena npm run dev di SEB) -->
    <div id="fatal-error" class="fixed inset-0 bg-red-900 z-[10000] flex flex-col items-center justify-center text-white p-8 text-center">
        <h1 class="text-3xl font-bold mb-4">Sistem Gagal Dimuat</h1>
        <p class="mb-2">Aplikasi mendeteksi Javascript tidak berjalan.</p>
        <div class="bg-black/30 p-4 rounded text-left font-mono text-xs mb-6 max-w-lg">
            <strong>Penyebab Umum:</strong><br>
            1. Anda menjalankan <code>npm run dev</code> di SEB (Gunakan <code>npm run build</code>).<br>
            2. CDN (SweetAlert/Phosphor) terblokir firewall.<br>
            3. Browser memblokir script eksternal.
        </div>
        <button onclick="window.location.reload()" class="px-6 py-2 bg-white text-red-900 font-bold rounded">Muat Ulang</button>
    </div>

    <!-- OVERLAY LOADING (Default State) -->
    <!-- Diberi ID untuk kontrol manual jika Alpine mati -->
    <div id="loading-overlay" x-show="!initComplete" 
         class="fixed inset-0 bg-white z-[9000] flex items-center justify-center flex-col transition-opacity duration-300">
        <div class="w-10 h-10 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
        <span class="text-sm text-slate-500 font-medium">Sedang Menyiapkan Ujian...</span>
    </div>

    <!-- OVERLAY SECURITY -->
    <div x-show="showSecurityOverlay" x-transition.opacity
         class="fixed inset-0 bg-slate-900/95 z-[9999] flex items-center justify-center text-center px-4"
         x-cloak>
        <div class="max-w-md w-full bg-white rounded-2xl p-8 shadow-2xl">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 animate-pulse">
                <i class="ph-duotone ph-warning-octagon text-5xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">PERINGATAN SISTEM!</h2>
            <p class="text-slate-600 mb-6">
                Anda terdeteksi meninggalkan halaman ujian. <br>
                Sisa toleransi pelanggaran: <span class="font-bold text-red-600" x-text="maxViolations - violationCount"></span> kali.
            </p>
            <button @click="resumeExam()" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition transform active:scale-95">
                Kembali ke Ujian
            </button>
        </div>
    </div>

    <!-- BANNER OFFLINE -->
    <div x-show="!isOnline" x-transition 
         class="fixed top-16 left-0 w-full bg-rose-500 text-white text-center text-xs font-bold py-1 z-40 shadow-md" style="display: none;">
        <i class="ph-bold ph-wifi-slash"></i> Koneksi Terputus - Jawaban disimpan sementara di perangkat
    </div>

    <!-- HEADER -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md border-b border-slate-200 z-50 h-16 flex items-center justify-between px-4 lg:px-8 shadow-sm">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="h-9 w-9 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                <i class="ph-bold ph-student text-xl"></i>
            </div>
            <div class="hidden md:block truncate">
                <h1 class="text-sm font-extrabold text-slate-900 leading-tight truncate max-w-[200px]">{{ $exam->title }}</h1>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500">{{ $exam->subject_name }}</p>
            </div>
        </div>

        <!-- TIMER & TOOLS -->
        <div class="flex items-center gap-3">
            <div class="hidden md:flex bg-red-50 text-red-600 px-3 py-1.5 rounded-lg border border-red-100 text-xs font-bold items-center gap-2" 
                 x-show="violationCount > 0" x-cloak>
                <i class="ph-fill ph-warning-circle"></i>
                <span x-text="violationCount + '/' + maxViolations"></span>
            </div>

            <div class="bg-white text-slate-700 px-4 py-1.5 rounded-lg border border-slate-200 shadow-sm flex items-center gap-2"
                 :class="timeLeft < 300 ? 'border-red-500 text-red-600 bg-red-50 animate-pulse' : ''">
                <i class="ph-duotone ph-timer text-xl"></i>
                <span x-text="formattedTime" class="font-mono font-bold text-lg tracking-widest">00:00:00</span>
            </div>

            <button @click="showMobileMap = !showMobileMap" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                <i class="ph-bold ph-squares-four text-xl"></i>
            </button>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="pt-24 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6 h-[calc(100vh-6rem)]">
        
        <!-- KOLOM KIRI: Area Soal -->
        <div class="lg:w-3/4 w-full flex flex-col h-full">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-full overflow-hidden relative">
                
                <!-- Header Soal -->
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <div class="flex items-center gap-3">
                        <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-md">
                            NO. <span x-text="currentQuestion + 1"></span>
                        </span>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" class="form-checkbox w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500" 
                                   x-model="markedQuestions[questions[currentQuestion]?.id]">
                            <span class="text-sm font-bold text-amber-600">Ragu-ragu</span>
                        </label>
                    </div>
                    
                    <div class="text-[10px] font-bold uppercase tracking-wider">
                        <span x-show="saveStatus === 'saving'" class="text-blue-500 flex items-center gap-1">
                            <i class="ph-bold ph-spinner animate-spin"></i> Menyimpan
                        </span>
                        <span x-show="saveStatus === 'saved'" class="text-emerald-500 flex items-center gap-1">
                            <i class="ph-bold ph-check-circle"></i> Tersimpan
                        </span>
                        <span x-show="saveStatus === 'pending'" class="text-amber-500 flex items-center gap-1">
                            <i class="ph-bold ph-cloud-warning"></i> Pending
                        </span>
                    </div>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto custom-scroll p-6 lg:p-8">
                    <template x-if="questions.length > 0 && questions[currentQuestion]">
                        <div class="max-w-3xl mx-auto">
                            <!-- Soal Text/Image -->
                            <div class="prose prose-lg max-w-none text-slate-800 mb-8">
                                <template x-if="questions[currentQuestion].image">
                                    <div class="mb-6 bg-slate-50 p-2 rounded-xl border border-slate-100 inline-block">
                                        <img :src="questions[currentQuestion].image" 
                                             class="max-h-[300px] w-auto rounded-lg object-contain hover:scale-105 transition-transform cursor-zoom-in"
                                             onclick="window.open(this.src, '_blank')">
                                    </div>
                                </template>
                                <div class="font-medium leading-loose text-lg" x-html="questions[currentQuestion].text"></div>
                            </div>

                            <!-- Pilihan Jawaban -->
                            <div class="space-y-3">
                                <template x-for="(optionText, optionKey) in questions[currentQuestion].options" :key="optionKey">
                                    <label class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                        :class="answers[questions[currentQuestion].id] === optionKey 
                                            ? 'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500' 
                                            : 'border-slate-200 hover:border-blue-300 hover:bg-slate-50'">
                                        
                                        <input type="radio" :name="'q_' + questions[currentQuestion].id" :value="optionKey" 
                                            @change="selectAnswer(questions[currentQuestion].id, optionKey)"
                                            x-model="answers[questions[currentQuestion].id]" 
                                            class="peer sr-only">
                                        
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-base shrink-0 transition-all shadow-sm border"
                                            :class="answers[questions[currentQuestion].id] === optionKey 
                                                ? 'bg-blue-600 text-white border-blue-600 scale-110' 
                                                : 'bg-white text-slate-500 border-slate-200 group-hover:border-blue-300 group-hover:text-blue-600'">
                                            <span x-text="optionKey"></span>
                                        </div>
                                        
                                        <span class="text-slate-700 font-medium peer-checked:text-slate-900" x-text="optionText"></span>
                                        
                                        <div x-show="answers[questions[currentQuestion].id] === optionKey" class="absolute right-4 text-blue-600">
                                            <i class="ph-fill ph-check-circle text-xl"></i>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer Navigasi -->
                <div class="px-6 py-4 bg-white border-t border-slate-200 flex justify-between items-center shrink-0">
                    <button @click="prevQuestion" :disabled="currentQuestion === 0" 
                        class="px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed text-slate-600 hover:bg-slate-100 border border-transparent hover:border-slate-200">
                        <i class="ph-bold ph-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                    </button>

                    <button @click="nextQuestion" x-show="currentQuestion < totalQuestions - 1"
                        class="px-6 py-2.5 rounded-xl font-bold bg-slate-900 text-white hover:bg-blue-600 flex items-center gap-2 shadow-lg shadow-slate-900/10 hover:shadow-blue-600/20 transition-all">
                        <span class="hidden sm:inline">Selanjutnya</span> <i class="ph-bold ph-arrow-right"></i>
                    </button>
                    
                    <button @click="finishExam" x-show="currentQuestion === totalQuestions - 1"
                        class="px-6 py-2.5 rounded-xl font-bold bg-emerald-600 text-white hover:bg-emerald-700 flex items-center gap-2 shadow-lg shadow-emerald-500/30 transition-all">
                        <i class="ph-bold ph-check-circle"></i> Selesai
                    </button>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Navigasi Nomor -->
        <div class="lg:w-1/4 w-full fixed lg:static inset-0 z-40 lg:z-auto bg-slate-900/50 lg:bg-transparent backdrop-blur-sm lg:backdrop-blur-none"
             x-show="showMobileMap || window.innerWidth >= 1024"
             @click.self="showMobileMap = false"
             x-transition.opacity
             style="display: none;" 
             :style="{'display': (showMobileMap || window.innerWidth >= 1024) ? 'block' : 'none'}">
            
            <div class="bg-white h-full lg:h-auto lg:rounded-2xl shadow-xl lg:shadow-sm border-l lg:border border-slate-200 p-5 w-3/4 max-w-xs ml-auto lg:w-full lg:ml-0 overflow-y-auto custom-scroll flex flex-col">
                <div class="flex justify-between items-center mb-4 lg:hidden">
                    <h3 class="font-bold text-slate-800">Daftar Soal</h3>
                    <button @click="showMobileMap = false" class="text-slate-500"><i class="ph-bold ph-x text-xl"></i></button>
                </div>

                <div class="bg-blue-50/50 rounded-xl p-4 mb-4 border border-blue-100 hidden lg:block">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-squares-four text-blue-600"></i> Navigasi Soal
                    </h3>
                </div>
                
                <div class="grid grid-cols-5 gap-2 content-start">
                    <template x-for="(q, index) in questions" :key="index">
                        <button @click="currentQuestion = index; showMobileMap = false"
                            class="aspect-square rounded-lg flex items-center justify-center font-bold text-sm transition-all border-2 relative"
                            :class="{
                                'ring-2 ring-offset-1 ring-blue-500 z-10': currentQuestion === index,
                                'bg-blue-600 text-white border-blue-600': answers[q.id] && !markedQuestions[q.id],
                                'bg-amber-400 text-white border-amber-400': markedQuestions[q.id],
                                'bg-white text-slate-600 border-slate-200 hover:border-blue-300': !answers[q.id] && !markedQuestions[q.id]
                            }">
                            <span x-text="index + 1"></span>
                        </button>
                    </template>
                </div>
                
                <div class="mt-auto lg:mt-6 pt-6 border-t border-slate-100">
                    <button @click="finishExam()" class="w-full py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition flex items-center justify-center gap-2">
                         Kumpulkan Jawaban
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIC SYSTEM -->
    <script>
        // SCRIPT CHECKER: Mendeteksi apakah Alpine/Vite berhasil load
        setTimeout(function() {
            // Cek apakah Alpine sudah terdefinisi (artinya app.js sukses load)
            const isAlpineLoaded = typeof Alpine !== 'undefined' || (document.querySelector('[x-data]') && document.querySelector('[x-data]').__x);
            
            if (!isAlpineLoaded) {
                console.error("FATAL: Alpine.js tidak terdeteksi. Kemungkinan blokir SEB pada 'npm run dev' atau CDN.");
                document.getElementById('loading-overlay').style.display = 'none'; // Matikan spinner
                document.getElementById('fatal-error').style.display = 'flex'; // Munculkan pesan error
            }
        }, 3000); // Tunggu 3 detik

        function examApp(initialData, initialTimeLeft, sessionId, examId) {
            return {
                rawQuestions: initialData || [],
                questions: [],
                currentQuestion: 0,
                totalQuestions: 0,
                timeLeft: initialTimeLeft,
                formattedTime: '00:00:00',
                answers: {}, 
                markedQuestions: {},
                sessionId: sessionId,
                examId: examId,
                
                // State UI
                initComplete: false,
                isOnline: navigator.onLine,
                saveStatus: 'idle',
                showMobileMap: false,
                
                // Security State
                violationCount: 0,
                maxViolations: 3,
                showSecurityOverlay: false,

                initData() {
                    try {
                        if (!this.rawQuestions || !Array.isArray(this.rawQuestions) || this.rawQuestions.length === 0) {
                            console.error('Data soal kosong!');
                        }
                        this.questions = this.rawQuestions;
                        this.totalQuestions = this.questions.length;

                        try { this.loadLocalProgress(); } catch (e) { console.warn('LS Error'); }
                        
                        this.questions.forEach(q => {
                            if(q.saved_answer && !this.answers[q.id]) {
                                this.answers[q.id] = q.saved_answer;
                            }
                        });

                    } catch (error) {
                        console.error('Critical Error in initData:', error);
                    } finally {
                        this.initComplete = true; 
                    }
                },

                nextQuestion() { if (this.currentQuestion < this.totalQuestions - 1) this.currentQuestion++; },
                prevQuestion() { if (this.currentQuestion > 0) this.currentQuestion--; },

                async selectAnswer(questionId, answer) {
                    this.answers[questionId] = answer;
                    try { this.saveToLocal(); } catch(e){}
                    
                    if (!this.isOnline) {
                        this.saveStatus = 'pending';
                        return;
                    }

                    this.saveStatus = 'saving';
                    try {
                        const response = await fetch("{{ route('student.exam.saveAnswer') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                session_id: this.sessionId,
                                question_id: questionId,
                                answer: answer
                            })
                        });

                        if (!response.ok) throw new Error('Server reject');
                        setTimeout(() => this.saveStatus = 'saved', 300);
                        
                    } catch (error) {
                        this.saveStatus = 'pending';
                    }
                },

                saveToLocal() {
                    try {
                        const progress = {
                            answers: this.answers,
                            marked: this.markedQuestions,
                            timestamp: new Date().getTime()
                        };
                        localStorage.setItem(`exam_${this.sessionId}`, JSON.stringify(progress));
                    } catch (e) {}
                },

                loadLocalProgress() {
                    try {
                        const saved = localStorage.getItem(`exam_${this.sessionId}`);
                        if (saved) {
                            const data = JSON.parse(saved);
                            this.answers = data.answers || {};
                            this.markedQuestions = data.marked || {};
                        }
                    } catch (e) {}
                },

                async syncPendingAnswers() {
                    if(this.saveStatus !== 'pending') return;
                    for (const [qId, ans] of Object.entries(this.answers)) {
                        await this.selectAnswer(qId, ans);
                    }
                },
                
                checkPendingAnswers() {
                   if(Object.keys(this.answers).length > 0) {
                       this.saveStatus = 'saved';
                   }
                },

                initSecurity() {
                    const self = this;
                    if (typeof document.hidden !== "undefined") {
                        document.addEventListener("visibilitychange", () => {
                            if (document.hidden) self.triggerViolation('Meninggalkan Tab');
                        });
                    }
                    
                    window.addEventListener("blur", () => {
                       setTimeout(() => {
                           if(document.activeElement && document.activeElement.tagName === 'IFRAME') return; 
                           self.triggerViolation('Kehilangan Fokus');
                       }, 1000); 
                    });

                    window.addEventListener('keydown', (e) => {
                        if (e.ctrlKey && (e.key === 'u' || e.key === 'U')) e.preventDefault();
                    });
                },

                triggerViolation(reason) {
                    if (this.showSecurityOverlay) return; 

                    this.violationCount++;
                    this.showSecurityOverlay = true;

                    if(this.violationCount >= this.maxViolations) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'DISKUALIFIKASI',
                                text: 'Pelanggaran batas maksimal.',
                                allowOutsideClick: false,
                                confirmButtonText: 'Keluar',
                                confirmButtonColor: '#d33'
                            }).then(() => this.submitExam(true));
                        } else {
                            alert('DISKUALIFIKASI: Anda melanggar aturan.');
                            this.submitExam(true);
                        }
                    }
                },

                resumeExam() {
                    this.showSecurityOverlay = false;
                    try {
                        if (document.documentElement.requestFullscreen) {
                            document.documentElement.requestFullscreen().catch(() => {});
                        }
                    } catch(e){}
                },

                startTimer() {
                    const timerInterval = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                            this.formatTime();
                            
                            if(this.timeLeft === 300) {
                                if (typeof Swal !== 'undefined') {
                                    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                                    Toast.fire({ icon: 'warning', title: 'Waktu tinggal 5 menit!' });
                                }
                            }
                        } else {
                            clearInterval(timerInterval);
                            this.submitExam(true);
                        }
                    }, 1000);
                },

                formatTime() {
                    let h = Math.floor(this.timeLeft / 3600);
                    let m = Math.floor((this.timeLeft % 3600) / 60);
                    let s = this.timeLeft % 60;
                    this.formattedTime = 
                        (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
                },

                finishExam() {
                    const answeredCount = Object.keys(this.answers).length;
                    const remaining = this.totalQuestions - answeredCount;
                    
                    if (typeof Swal === 'undefined') {
                        if(confirm('Kumpulkan Jawaban? Masih ada ' + remaining + ' soal kosong.')) {
                            this.submitExam();
                        }
                        return;
                    }

                    Swal.fire({
                        title: 'Kumpulkan Jawaban?',
                        html: remaining > 0 ? `Masih ada <b>${remaining}</b> soal kosong.` : "Pastikan semua jawaban benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Kumpulkan'
                    }).then((result) => {
                        if (result.isConfirmed) this.submitExam();
                    });
                },

                submitExam(forced = false) {
                    if(this.saveStatus === 'pending') {
                        if(typeof Swal !== 'undefined') {
                            Swal.fire({ title: 'Sinkronisasi...', didOpen: () => Swal.showLoading() });
                        }
                        this.syncPendingAnswers().then(() => this.doSubmit(forced));
                    } else {
                        this.doSubmit(forced);
                    }
                },

                doSubmit(forced) {
                    if(typeof Swal !== 'undefined') {
                        Swal.fire({ title: 'Menyimpan Ujian...', didOpen: () => Swal.showLoading() });
                    }

                    try { localStorage.removeItem(`exam_${this.sessionId}`); } catch(e){}

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('student.exam.finish', ':id') }}".replace(':id', this.examId);
                    
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(tokenInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>