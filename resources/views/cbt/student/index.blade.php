<!DOCTYPE html>
<html lang="id" oncontextmenu="return false">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian Online - {{ $exam->title }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/@phosphor-icons/web" onerror="console.error('Gagal load Icons')"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" onerror="console.error('Gagal load SweetAlert')"></script>

    <style>
        body { font-family: sans-serif; margin: 0; background-color: #f8fafc; color: #1e293b; user-select: none; -webkit-user-select: none; }
        [x-cloak] { display: none !important; }
        
        #loading-overlay {
            position: fixed; inset: 0; background: white; z-index: 9999;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .spinner {
            width: 40px; height: 40px; border: 4px solid #e2e8f0;
            border-top: 4px solid #2563eb; border-radius: 50%;
            animation: spin 1s linear infinite; margin-bottom: 15px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="antialiased"
    x-data="examApp(@json($questions), {{ $timeLeft }}, {{ $sessionId }}, {{ $exam->id }})"
    x-init="initData(); startTimer(); initSecurity(); checkPendingAnswers()"
    @online.window="isOnline = true; syncPendingAnswers()"
    @offline.window="isOnline = false">

    <!-- MODAL ZOOM GAMBAR (PENGGANTI WINDOW.OPEN) -->
    <div x-show="zoomedImage" x-transition.opacity 
         class="fixed inset-0 z-[10000] bg-black/90 flex items-center justify-center p-4 cursor-zoom-out"
         @click="zoomedImage = null" x-cloak>
        <img :src="zoomedImage" class="max-w-full max-h-full rounded shadow-2xl scale-100 transition-transform duration-300">
        <button class="absolute top-4 right-4 text-white bg-white/10 hover:bg-white/20 rounded-full p-2">
            <i class="ph-bold ph-x text-2xl"></i>
        </button>
    </div>

    <!-- LOADING & ERROR SCREENS (Sama seperti sebelumnya) -->
    <noscript>
        <div style="position:fixed; inset:0; background:white; z-index:99999; display:flex; justify-content:center; align-items:center; text-align:center; padding:20px;">
            <h1 style="color:red; font-size:24px;">Javascript Tidak Aktif</h1>
        </div>
    </noscript>

    <div id="loading-overlay" x-show="!initComplete" x-transition.opacity.duration.500ms>
        <div class="spinner"></div>
        <span style="font-size:14px; font-weight:bold; color:#64748b;">Menyiapkan Lembar Ujian...</span>
    </div>

    <div x-show="showSecurityOverlay" x-transition.opacity
         class="fixed inset-0 bg-slate-900/95 z-[9000] flex items-center justify-center text-center px-4"
         x-cloak>
        <div class="max-w-md w-full bg-white rounded-2xl p-8 shadow-2xl">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                <i class="ph-duotone ph-warning-octagon text-4xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">PERINGATAN PELANGGARAN!</h2>
            <p class="text-slate-600 mb-6 text-sm">
                Anda terdeteksi meninggalkan halaman ujian.<br>
                Sisa toleransi: <span class="font-black text-red-600 text-lg" x-text="maxViolations - violationCount"></span> kali.
            </p>
            <button @click="resumeExam()" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition">
                Saya Mengerti, Kembali ke Ujian
            </button>
        </div>
    </div>

    <div x-show="!isOnline" x-transition 
         class="fixed top-16 left-0 w-full bg-rose-600 text-white text-center text-xs font-bold py-2 z-40 shadow-md flex justify-center items-center gap-2" 
         x-cloak>
        <i class="ph-bold ph-wifi-slash"></i> KONEKSI TERPUTUS - Jawaban disimpan di perangkat Anda
    </div>

    <!-- NAVBAR -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-md border-b border-slate-200 z-50 h-16 flex items-center justify-between px-4 shadow-sm">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="h-9 w-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                <i class="ph-bold ph-student text-xl"></i>
            </div>
            <div class="hidden md:block truncate">
                <h1 class="text-sm font-extrabold text-slate-900 leading-tight truncate max-w-[200px]">{{ $exam->title }}</h1>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500">{{ $exam->subject_name }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden md:flex bg-red-50 text-red-600 px-3 py-1.5 rounded-lg border border-red-100 text-xs font-bold items-center gap-2" 
                 x-show="violationCount > 0" x-cloak>
                <i class="ph-fill ph-warning-circle"></i>
                <span x-text="violationCount + '/' + maxViolations"></span>
            </div>

            <div class="bg-white text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm flex items-center gap-2"
                 :class="timeLeft < 300 ? 'border-red-500 text-red-600 bg-red-50 animate-pulse' : ''">
                <i class="ph-duotone ph-timer text-xl"></i>
                <span x-text="formattedTime" class="font-mono font-bold text-lg tracking-widest">00:00:00</span>
            </div>

            <button @click="showMobileMap = !showMobileMap" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                <i class="ph-bold ph-squares-four text-xl"></i>
            </button>
        </div>
    </nav>

    <!-- MAIN LAYOUT -->
    <div class="pt-20 pb-6 max-w-7xl mx-auto px-4 flex flex-col lg:flex-row gap-6 h-[calc(100vh-1rem)]">
        
        <!-- KOLOM KIRI: SOAL -->
        <div class="lg:w-3/4 w-full flex flex-col h-full pb-16 lg:pb-0">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-full overflow-hidden relative">
                
                <!-- Header Soal -->
                <div class="bg-slate-50 px-6 py-3 border-b border-slate-200 flex justify-between items-center shrink-0">
                    <div class="flex items-center gap-3">
                        <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                            SOAL NO. <span x-text="currentQuestion + 1"></span>
                        </span>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500" 
                                   x-model="markedQuestions[questions[currentQuestion]?.id]">
                            <span class="text-xs font-bold text-amber-600 uppercase tracking-wide">Ragu-ragu</span>
                        </label>
                    </div>
                    
                    <div class="text-[10px] font-bold uppercase tracking-wider">
                        <span x-show="saveStatus === 'saving'" class="text-blue-500 flex items-center gap-1">
                            <i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...
                        </span>
                        <span x-show="saveStatus === 'saved'" class="text-emerald-500 flex items-center gap-1">
                            <i class="ph-bold ph-check-circle"></i> Tersimpan
                        </span>
                        <span x-show="saveStatus === 'pending'" class="text-amber-500 flex items-center gap-1">
                            <i class="ph-bold ph-cloud-warning"></i> Pending (Offline)
                        </span>
                    </div>
                </div>

                <!-- Konten Soal -->
                <div class="flex-1 overflow-y-auto custom-scroll p-6 lg:p-8">
                    <template x-if="questions.length > 0 && questions[currentQuestion]">
                        <div class="max-w-3xl mx-auto">
                            <!-- Soal Text/Image -->
                            <div class="mb-8">
                                <template x-if="questions[currentQuestion].image">
                                    <div class="mb-6">
                                        <!-- FIX: Gunakan @click untuk modal zoom, jangan window.open -->
                                        <img :src="questions[currentQuestion].image" 
                                             @click="zoomedImage = questions[currentQuestion].image"
                                             class="max-h-[300px] w-auto rounded-lg border border-slate-200 shadow-sm object-contain cursor-zoom-in hover:opacity-95 transition">
                                        <p class="text-[10px] text-slate-400 mt-1 italic text-center">Ketuk gambar untuk memperbesar</p>
                                    </div>
                                </template>
                                <div class="text-lg text-slate-800 font-medium leading-loose" x-html="questions[currentQuestion].text"></div>
                            </div>

                            <!-- Pilihan Jawaban -->
                            <div class="space-y-3">
                                <template x-for="(optionText, optionKey) in questions[currentQuestion].options" :key="optionKey">
                                    <label class="relative flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                        :class="answers[questions[currentQuestion].id] === optionKey 
                                            ? 'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500' 
                                            : 'border-slate-200 hover:border-blue-300 hover:bg-slate-50'">
                                        
                                        <input type="radio" :name="'q_' + questions[currentQuestion].id" :value="optionKey" 
                                            @change="selectAnswer(questions[currentQuestion].id, optionKey)"
                                            x-model="answers[questions[currentQuestion].id]" 
                                            class="peer sr-only">
                                        
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 transition-all border mt-0.5"
                                            :class="answers[questions[currentQuestion].id] === optionKey 
                                                ? 'bg-blue-600 text-white border-blue-600' 
                                                : 'bg-white text-slate-500 border-slate-200 group-hover:border-blue-300 group-hover:text-blue-600'">
                                            <span x-text="optionKey"></span>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <span class="text-slate-700 font-medium peer-checked:text-slate-900 text-sm md:text-base" x-text="optionText"></span>
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
                        class="px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed text-slate-600 hover:bg-slate-100 border border-slate-200 hover:border-slate-300 text-sm">
                        <i class="ph-bold ph-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                    </button>

                    <button @click="nextQuestion" x-show="currentQuestion < totalQuestions - 1"
                        class="px-6 py-2.5 rounded-xl font-bold bg-slate-900 text-white hover:bg-blue-600 flex items-center gap-2 shadow-lg shadow-slate-900/10 hover:shadow-blue-600/20 transition-all text-sm">
                        <span class="hidden sm:inline">Selanjutnya</span> <i class="ph-bold ph-arrow-right"></i>
                    </button>
                    
                    <button @click="finishExam" x-show="currentQuestion === totalQuestions - 1"
                        class="px-6 py-2.5 rounded-xl font-bold bg-emerald-600 text-white hover:bg-emerald-700 flex items-center gap-2 shadow-lg shadow-emerald-500/30 transition-all text-sm">
                        <i class="ph-bold ph-check-circle"></i> Selesai
                    </button>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: NAVIGASI NOMOR -->
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

                <div class="bg-blue-50/50 rounded-xl p-3 mb-4 border border-blue-100 hidden lg:block text-center">
                    <h3 class="font-bold text-blue-800 text-xs uppercase tracking-wide">Navigasi Soal</h3>
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
                    <button @click="finishExam()" class="w-full py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition flex items-center justify-center gap-2 border border-slate-200">
                         Kumpulkan Jawaban
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIC SYSTEM -->
    <script>
        setTimeout(function() {
            const isAlpineLoaded = typeof Alpine !== 'undefined';
            if (!isAlpineLoaded) {
                console.error("FATAL: Alpine.js gagal dimuat.");
                document.getElementById('loading-overlay').style.display = 'none';
                // Alert manual karena elemen fatal-error tidak ada di HTML ini (optional)
                alert("Gagal memuat aplikasi ujian. Harap muat ulang halaman.");
            }
        }, 3000);

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
                unsavedQuestions: new Set(), // FIX: Track ID soal yang belum tersimpan
                sessionId: sessionId,
                examId: examId,
                
                initComplete: false,
                isOnline: navigator.onLine,
                saveStatus: 'idle',
                showMobileMap: false,
                zoomedImage: null, // FIX: State untuk modal gambar
                
                violationCount: 0,
                maxViolations: 3,
                showSecurityOverlay: false,

                initData() {
                    try {
                        this.questions = this.rawQuestions;
                        this.totalQuestions = this.questions.length;

                        try { this.loadLocalProgress(); } catch (e) { console.warn('LS Error'); }
                        
                        this.questions.forEach(q => {
                            if(q.saved_answer && !this.answers[q.id]) {
                                this.answers[q.id] = q.saved_answer;
                            }
                        });

                    } catch (error) {
                        alert('Gagal memproses data soal. Silakan refresh.');
                    } finally {
                        this.initComplete = true;
                    }
                },

                nextQuestion() { if (this.currentQuestion < this.totalQuestions - 1) this.currentQuestion++; },
                prevQuestion() { if (this.currentQuestion > 0) this.currentQuestion--; },

                async selectAnswer(questionId, answer) {
                    this.answers[questionId] = answer;
                    this.unsavedQuestions.add(questionId); // FIX: Tandai sebagai belum tersimpan
                    
                    try { this.saveToLocal(); } catch(e){}
                    
                    if (!this.isOnline) {
                        this.saveStatus = 'pending';
                        return;
                    }

                    this.saveStatus = 'saving';
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        const response = await fetch("{{ route('student.exam.saveAnswer') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                session_id: this.sessionId,
                                question_id: questionId,
                                answer: answer
                            })
                        });

                        if (!response.ok) throw new Error('Server reject');
                        
                        // FIX: Hapus dari daftar unsaved jika sukses
                        this.unsavedQuestions.delete(questionId);
                        
                        // Cek apakah masih ada antrian lain
                        if (this.unsavedQuestions.size === 0) {
                            setTimeout(() => this.saveStatus = 'saved', 300);
                        }
                        
                    } catch (error) {
                        console.error('Gagal simpan:', error);
                        this.saveStatus = 'pending';
                    }
                },

                saveToLocal() {
                    try {
                        const progress = {
                            answers: this.answers,
                            marked: this.markedQuestions,
                            unsaved: Array.from(this.unsavedQuestions), // Simpan status unsaved juga
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
                            this.answers = { ...data.answers, ...this.answers };
                            this.markedQuestions = data.marked || {};
                            // Restore unsaved queue jika ada
                            if(data.unsaved && Array.isArray(data.unsaved)){
                                data.unsaved.forEach(id => this.unsavedQuestions.add(id));
                            }
                        }
                    } catch (e) {}
                },

                // FIX: Sinkronisasi Cerdas (Hanya yang belum tersimpan)
                async syncPendingAnswers() {
                    if (this.unsavedQuestions.size === 0) {
                        this.saveStatus = 'saved';
                        return;
                    }

                    this.saveStatus = 'saving';
                    
                    // Kita clone Set agar iterasi aman
                    const pendingIds = Array.from(this.unsavedQuestions);
                    
                    for (const qId of pendingIds) {
                        const ans = this.answers[qId];
                        if (ans) {
                            // Panggil selectAnswer kembali untuk mencoba simpan
                            // selectAnswer akan menghapus ID dari unsavedQuestions jika sukses
                            await this.selectAnswer(qId, ans);
                        }
                    }
                },
                
                checkPendingAnswers() {
                   if(this.unsavedQuestions.size === 0 && Object.keys(this.answers).length > 0) {
                       this.saveStatus = 'saved';
                   } else if (this.unsavedQuestions.size > 0) {
                       this.saveStatus = 'pending';
                   }
                },

                initSecurity() {
                    const self = this;
                    if (typeof document.hidden !== "undefined") {
                        document.addEventListener("visibilitychange", () => {
                            if (document.hidden) self.triggerViolation();
                        });
                    }
                    window.addEventListener("blur", () => {
                       setTimeout(() => {
                           if(document.activeElement && document.activeElement.tagName === 'IFRAME') return; 
                           self.triggerViolation();
                       }, 1000); 
                    });
                    window.addEventListener('keydown', (e) => {
                        if (e.ctrlKey && (e.key === 'u' || e.key === 'U' || e.key === 's' || e.key === 'S')) e.preventDefault();
                        if (e.key === 'F12') e.preventDefault();
                    });
                },

                triggerViolation() {
                    if (this.showSecurityOverlay) return; 
                    this.violationCount++;
                    this.showSecurityOverlay = true;
                    if(this.violationCount >= this.maxViolations) {
                        alert('DISKUALIFIKASI: Pelanggaran batas aturan. Ujian dikumpulkan.');
                        this.submitExam(true);
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
                            if(this.timeLeft === 300 && typeof Swal !== 'undefined') {
                                Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'warning', title: 'Waktu tinggal 5 menit!' });
                            }
                        } else {
                            clearInterval(timerInterval);
                            alert("Waktu Habis!");
                            this.submitExam(true);
                        }
                    }, 1000);
                },

                formatTime() {
                    let h = Math.floor(this.timeLeft / 3600);
                    let m = Math.floor((this.timeLeft % 3600) / 60);
                    let s = this.timeLeft % 60;
                    this.formattedTime = (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
                },

                finishExam() {
                    const answeredCount = Object.keys(this.answers).length;
                    const remaining = this.totalQuestions - answeredCount;
                    
                    if (typeof Swal === 'undefined') {
                        if(confirm('Kumpulkan Jawaban?')) this.submitExam();
                        return;
                    }

                    Swal.fire({
                        title: 'Kumpulkan Jawaban?',
                        html: remaining > 0 ? `Masih ada <b>${remaining}</b> soal kosong.` : "Pastikan semua jawaban benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Kumpulkan',
                        confirmButtonColor: '#059669'
                    }).then((result) => {
                        if (result.isConfirmed) this.submitExam();
                    });
                },

                submitExam(forced = false) {
                    if(this.unsavedQuestions.size > 0) {
                        if(typeof Swal !== 'undefined') {
                            Swal.fire({ title: 'Sinkronisasi...', didOpen: () => Swal.showLoading() });
                        }
                        this.syncPendingAnswers().then(() => this.doSubmit(forced));
                    } else {
                        this.doSubmit(forced);
                    }
                },

                doSubmit(forced) {
                    if(typeof Swal !== 'undefined') Swal.fire({ title: 'Menyimpan Ujian...', didOpen: () => Swal.showLoading() });
                    try { localStorage.removeItem(`exam_${this.sessionId}`); } catch(e){}

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('student.exam.finish', ':id') }}".replace(':id', this.examId);
                    
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(tokenInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>