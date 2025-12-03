<!DOCTYPE html>
<html lang="id" oncontextmenu="return false"> <!-- Disable Klik Kanan -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian Online - {{ $exam->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Mencegah seleksi teks (Copy Paste) */
        body { 
            user-select: none; 
            -webkit-user-select: none; 
            -moz-user-select: none; 
            -ms-user-select: none; 
        }
        /* Overlay Peringatan */
        #security-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95);
            z-index: 9999;
            color: white;
            text-align: center;
            padding-top: 20%;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800"
    x-data="examApp(@json($questions), {{ $timeLeft }}, {{ $sessionId }}, {{ $exam->id }})"
    x-init="startTimer(); initSecurity()">

    <!-- Overlay Peringatan Pelanggaran -->
    <div id="security-overlay">
        <div class="max-w-md mx-auto px-4">
            <i class="ph-duotone ph-warning-octagon text-6xl text-red-500 mb-4"></i>
            <h2 class="text-2xl font-bold mb-2">PELANGGARAN TERDETEKSI!</h2>
            <p class="mb-6">Anda terdeteksi meninggalkan halaman ujian atau membuka aplikasi lain.</p>
            <button onclick="resumeExam()" class="bg-blue-600 px-6 py-2 rounded-lg font-bold">Kembali ke Ujian</button>
        </div>
    </div>

    <!-- HEADER: Informasi & Timer -->
    <nav class="fixed top-0 w-full bg-white border-b border-slate-200 z-50 h-16 flex items-center justify-between px-4 lg:px-8 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" class="h-8 w-8" alt="Logo" onerror="this.style.display='none'">
            <div class="hidden md:block">
                <h1 class="text-sm font-bold text-slate-900 leading-tight">{{ $exam->title }}</h1>
                <p class="text-xs text-slate-500">{{ $exam->subject_name }}</p>
            </div>
        </div>

        <!-- TIMER -->
        <div class="flex items-center gap-4">
            <!-- Indikator Pelanggaran -->
            <div class="hidden md:flex bg-red-50 text-red-600 px-3 py-1 rounded border border-red-100 text-xs font-bold items-center gap-1" x-show="violationCount > 0">
                <i class="ph-bold ph-warning"></i>
                <span x-text="violationCount + ' Pelanggaran'"></span>
            </div>

            <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-mono font-bold text-lg border border-blue-100 shadow-sm flex items-center gap-2">
                <i class="ph-fill ph-clock"></i>
                <span x-text="formattedTime">00:00:00</span>
            </div>
            <button @click="finishExam()" class="md:hidden bg-red-600 text-white p-2 rounded-lg">
                <i class="ph-bold ph-check-square"></i>
            </button>
        </div>
    </nav>

    <!-- MAIN CONTENT (Sama seperti sebelumnya) -->
    <div class="pt-20 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6">
        
        <!-- KOLOM KIRI: Soal -->
        <div class="lg:w-3/4 w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[60vh] flex flex-col">
                <!-- Header Soal -->
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <span class="font-bold text-lg text-slate-700">Soal No. <span x-text="currentQuestion + 1"></span></span>
                    <!-- Status Koneksi -->
                    <span x-show="isSaving" class="text-xs text-blue-600 font-bold flex items-center gap-1 animate-pulse"><i class="ph-bold ph-arrows-clockwise"></i> Menyimpan...</span>
                    <span x-show="!isSaving" class="text-xs text-green-600 font-bold flex items-center gap-1"><i class="ph-bold ph-cloud-check"></i> Tersimpan</span>
                </div>

                <!-- Isi Soal -->
                <div class="p-6 flex-1">
                    <template x-if="questions.length > 0">
                        <div>
                            <div class="prose max-w-none text-slate-800 text-lg mb-8">
                                <template x-if="questions[currentQuestion].image">
                                    <img :src="questions[currentQuestion].image" class="max-w-md rounded-lg mb-4 border border-slate-200">
                                </template>
                                <p x-html="questions[currentQuestion].text"></p>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(optionText, optionKey) in questions[currentQuestion].options" :key="optionKey">
                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group hover:bg-blue-50"
                                        :class="answers[questions[currentQuestion].id] === optionKey ? 'border-blue-500 bg-blue-50' : 'border-slate-100 bg-white'">
                                        
                                        <input type="radio" :name="'q_' + questions[currentQuestion].id" :value="optionKey" 
                                            @change="selectAnswer(questions[currentQuestion].id, optionKey)"
                                            x-model="answers[questions[currentQuestion].id]" 
                                            class="hidden">
                                        
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors"
                                            :class="answers[questions[currentQuestion].id] === optionKey ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600 group-hover:bg-blue-200'">
                                            <span x-text="optionKey"></span>
                                        </div>
                                        <span class="text-slate-700 pt-1" x-text="optionText"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer Navigasi Soal -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <button @click="prevQuestion" :disabled="currentQuestion === 0" 
                        class="px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition"
                        :class="currentQuestion === 0 ? 'text-slate-300 cursor-not-allowed' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'">
                        <i class="ph-bold ph-arrow-left"></i> Sebelumnya
                    </button>

                    <button @click="nextQuestion" x-show="currentQuestion < totalQuestions - 1"
                        class="px-5 py-2.5 rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 flex items-center gap-2 shadow-lg shadow-blue-500/30 transition">
                        Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                    </button>
                    
                    <button @click="finishExam" x-show="currentQuestion === totalQuestions - 1"
                        class="px-5 py-2.5 rounded-xl font-bold bg-green-600 text-white hover:bg-green-700 flex items-center gap-2 shadow-lg shadow-green-500/30 transition">
                        <i class="ph-bold ph-check-circle"></i> Selesai Ujian
                    </button>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Peta Soal -->
        <div class="lg:w-1/4 w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-squares-four text-blue-600"></i> Navigasi Soal
                </h3>
                
                <div class="grid grid-cols-5 gap-2">
                    <template x-for="(q, index) in questions" :key="index">
                        <button @click="currentQuestion = index"
                            class="aspect-square rounded-lg flex items-center justify-center font-bold text-sm transition-all border-2"
                            :class="{
                                'bg-blue-600 text-white border-blue-600': currentQuestion === index,
                                'bg-green-100 text-green-700 border-green-200': answers[q.id] && currentQuestion !== index,
                                'bg-white text-slate-600 border-slate-200 hover:border-blue-300': !answers[q.id] && currentQuestion !== index
                            }">
                            <span x-text="index + 1"></span>
                        </button>
                    </template>
                </div>
                
                <hr class="my-4 border-slate-100">
                <p class="text-xs text-slate-500 text-center">
                    Jangan keluar dari halaman ini.<br>Sistem akan mencatat pelanggaran.
                </p>
            </div>
        </div>

    </div>

    <!-- LOGIC JAVASCRIPT -->
    <script>
        // Fungsi Global untuk Resume dari Overlay
        function resumeExam() {
            document.getElementById('security-overlay').style.display = 'none';
            // Masuk Fullscreen lagi jika memungkinkan
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(() => {});
            }
        }

        function examApp(initialQuestions, initialTimeLeft, sessionId, examId) {
            return {
                questions: initialQuestions,
                currentQuestion: 0,
                totalQuestions: initialQuestions.length,
                timeLeft: initialTimeLeft,
                formattedTime: '00:00:00',
                answers: {}, 
                sessionId: sessionId,
                examId: examId,
                isSaving: false,
                violationCount: 0, // Counter Pelanggaran

                init() {
                    // Load saved answers from DB
                    this.questions.forEach(q => {
                        if(q.saved_answer) {
                            this.answers[q.id] = q.saved_answer;
                        }
                    });
                },

                // ----------------------------------------------------
                // SECURITY INIT
                // ----------------------------------------------------
                initSecurity() {
                    const self = this;

                    // 1. Paksa Fullscreen saat mulai (Opsional, kadang diblok browser)
                    // document.documentElement.requestFullscreen().catch(() => {});

                    // 2. Deteksi Pindah Tab / Minimize Window
                    document.addEventListener("visibilitychange", function() {
                        if (document.hidden) {
                            self.recordViolation('Meninggalkan Tab Ujian');
                        }
                    });

                    // 3. Deteksi Kehilangan Fokus (Klik aplikasi lain di Desktop)
                    window.addEventListener("blur", function() {
                        self.recordViolation('Kehilangan Fokus Layar');
                    });

                    // 4. Mencegah Klik Kanan
                    document.addEventListener('contextmenu', event => event.preventDefault());

                    // 5. Mencegah Tombol Keyboard Tertentu (Inspect Element, Copy, Paste)
                    document.onkeydown = function(e) {
                        // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
                        if(e.keyCode == 123) return false;
                        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) return false;
                        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) return false;
                        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) return false;
                        // Copy Paste Cut
                        if(e.ctrlKey && e.keyCode == 'C'.charCodeAt(0)) return false;
                        if(e.ctrlKey && e.keyCode == 'V'.charCodeAt(0)) return false;
                    }
                },

                recordViolation(reason) {
                    this.violationCount++;
                    document.getElementById('security-overlay').style.display = 'block';

                    // Opsional: Kirim log pelanggaran ke server
                    // fetch('/exam/log-violation', { ... }) 

                    // Jika pelanggaran terlalu banyak, otomatis submit
                    if(this.violationCount >= 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Diskualifikasi',
                            text: 'Anda terdeteksi melakukan kecurangan berulang kali. Ujian dihentikan otomatis.',
                            allowOutsideClick: false,
                            confirmButtonText: 'Tutup'
                        }).then(() => {
                            this.submitExam(true);
                        });
                    }
                },
                // ----------------------------------------------------
                // END SECURITY
                // ----------------------------------------------------

                startTimer() {
                    setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                            this.formatTime();
                        } else {
                            this.submitExam(true);
                        }
                    }, 1000);
                },

                formatTime() {
                    let h = Math.floor(this.timeLeft / 3600);
                    let m = Math.floor((this.timeLeft % 3600) / 60);
                    let s = this.timeLeft % 60;
                    this.formattedTime = 
                        (h < 10 ? "0" + h : h) + ":" + 
                        (m < 10 ? "0" + m : m) + ":" + 
                        (s < 10 ? "0" + s : s);
                },

                nextQuestion() {
                    if (this.currentQuestion < this.totalQuestions - 1) {
                        this.currentQuestion++;
                    }
                },

                prevQuestion() {
                    if (this.currentQuestion > 0) {
                        this.currentQuestion--;
                    }
                },

                async selectAnswer(questionId, answer) {
                    this.answers[questionId] = answer;
                    this.isSaving = true;

                    try {
                        const response = await fetch("{{ route('student.exam.saveAnswer') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                session_id: this.sessionId,
                                question_id: questionId,
                                answer: answer
                            })
                        });
                        
                        if(!response.ok) throw new Error('Network error');
                        setTimeout(() => this.isSaving = false, 500);

                    } catch (error) {
                        console.error('Gagal menyimpan jawaban', error);
                        this.isSaving = false;
                    }
                },

                finishExam() {
                    let answeredCount = Object.keys(this.answers).length;
                    let remaining = this.totalQuestions - answeredCount;

                    if (remaining > 0) {
                        Swal.fire({
                            title: 'Masih ada soal kosong!',
                            text: `Anda belum menjawab ${remaining} soal. Yakin ingin mengumpulkan?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Kumpulkan',
                            cancelButtonText: 'Lanjut Mengerjakan'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submitExam();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Konfirmasi Selesai',
                            text: "Jawaban akan dikirim dan tidak bisa diubah lagi.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Kumpulkan Jawaban'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submitExam();
                            }
                        });
                    }
                },

                submitExam(forced = false) {
                    Swal.fire({
                        title: 'Menyimpan Jawaban...',
                        timer: 2000,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    const finishUrl = "{{ route('student.exam.finish', ':id') }}".replace(':id', this.examId);
                    form.action = finishUrl;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>
