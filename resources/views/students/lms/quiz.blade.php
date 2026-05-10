<x-student-learning-layout>
    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Pulse Animation for Low Time Warning */
        @keyframes pulse-red {
            0%, 100% { background-color: #c86845; box-shadow: 0 0 0 0 rgba(200, 104, 69, 0.7); }
            50% { background-color: #f9a282; box-shadow: 0 0 0 10px rgba(200, 104, 69, 0); }
        }
        .timer-warning {
            animation: pulse-red 1.5s infinite;
            border-color: #f4d1c0 !important;
        }
    </style>

    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   {{-- TIMER & ANTI-CHEAT LOGIC WRAPPER --}}
    <div x-data="examController({{ $assignment->duration_minutes * 60 }}, '{{ $assignment->id }}')" 
         x-init="startTimer()" 
         @visibilitychange.document="handleVisibilityChange" 
         @contextmenu.prevent="preventAction('Klik Kanan')" 
         @copy.prevent="preventAction('Copy')" 
         @paste.prevent="preventAction('Paste')"
         class="relative select-none">

        {{-- FLOATING TIMER BADGE --}}
        <div class="fixed top-4 right-4 z-[100] transition-all duration-300 transform flex flex-col items-end gap-2"
             :class="timeLeft < 300 ? 'scale-110' : 'scale-100'"> 
            <div class="flex items-center gap-2 px-5 py-2.5 rounded-full shadow-2xl border-2 backdrop-blur-md transition-colors duration-500"
                 :class="timeLeft < 300 ? 'bg-elevate-peach-dark border-elevate-peach text-white timer-warning' : 'bg-elevate-dark/95 border-elevate-primary text-white'">
                
                <i class="ph-bold ph-timer text-xl" :class="timeLeft < 300 ? 'text-white' : 'text-elevate-accent animate-pulse'"></i>
                <div class="flex flex-col items-start leading-none">
                    <span class="text-[10px] font-bold opacity-70 uppercase tracking-wider">Sisa Waktu</span>
                    <span class="font-mono text-lg font-black tracking-widest" x-text="formattedTime">00:00:00</span>
                </div>
            </div>

            {{-- INDIKATOR AUTOSAVE --}}
            <div x-show="saveStatus !== ''" x-transition
                 class="px-3 py-1.5 rounded-full bg-elevate-dark/90 border border-elevate-primary text-white text-[10px] font-bold shadow-lg flex items-center gap-1.5 backdrop-blur-md" style="display: none;">
                <i class="ph-bold" :class="saveStatus === 'saving' ? 'ph-spinner animate-spin text-elevate-accent' : (saveStatus === 'saved' ? 'ph-check-circle text-emerald-400' : 'ph-warning-circle text-elevate-peach')"></i>
                <span x-text="saveStatus === 'saving' ? 'Menyimpan...' : (saveStatus === 'saved' ? 'Jawaban Tersimpan' : 'Gagal Menyimpan')"></span>
            </div>
        </div>

        <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto min-h-screen pb-40 md:pb-32">
            
            {{-- QUIZ HEADER ELEVATE THEME --}}
            <div class="animate-enter bg-elevate-dark bg-gradient-to-br from-elevate-dark via-elevate-primary to-elevate-dark rounded-[2.5rem] shadow-2xl shadow-elevate-primary/20 border border-white/10 p-8 md:p-10 mb-8 relative overflow-hidden text-white group">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-elevate-accent/20 border border-elevate-accent/30 text-elevate-accent text-[10px] font-black uppercase tracking-widest shadow-sm backdrop-blur-md">
                            <i class="ph-fill ph-monitor-play"></i> Kuis Online
                        </span>
                    </div>
                    
                    <h1 class="text-2xl md:text-4xl font-black text-white mb-3 tracking-tight leading-tight">{{ $assignment->title }}</h1>
                    <p class="text-elevate-soft/80 leading-relaxed max-w-2xl text-sm md:text-base font-medium">{{ $assignment->description }}</p>
                    
                    <div class="flex flex-wrap items-center gap-4 md:gap-6 mt-8 pt-6 border-t border-white/10">
                        <div class="flex items-center gap-2 text-sm font-bold text-elevate-soft bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                            <i class="ph-fill ph-clock text-elevate-peach-light text-lg"></i> 
                            <span>{{ $assignment->duration_minutes }} Menit</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm font-bold text-elevate-soft bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                            <i class="ph-fill ph-list-numbers text-elevate-accent text-lg"></i> 
                            <span>{{ $assignment->questions->count() }} Soal</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('students.learning.assignment.quiz.submit', $assignment->id) }}" method="POST" id="quizForm">
                @csrf
                
                <div class="space-y-6 md:space-y-8">
                    @foreach($assignment->questions as $index => $q)
                        <div class="animate-enter bg-elevate-surface rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden relative hover:shadow-xl hover:shadow-elevate-primary/10 transition-all duration-500 group" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            
                            {{-- Nomor Soal --}}
                            <div class="absolute top-0 left-0 bg-elevate-soft text-elevate-primary font-black text-xs px-4 py-2 rounded-br-2xl border-r border-b border-elevate-soft z-10">
                                NO. {{ $index + 1 }}
                            </div>
                            
                            <div class="p-6 md:p-8 pt-12 md:pt-8">
                                {{-- Teks Soal --}}
                                <div class="text-lg md:text-xl font-bold text-elevate-dark mb-8 leading-relaxed pl-2 border-l-4 border-elevate-primary rounded-sm">
                                    {!! nl2br(e($q->question_text)) !!}
                                </div>

                                <div class="space-y-3">
                                    @if($q->question_type == 'multiple_choice')
                                        @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                                            @if(isset($q->options[$opt]) && $q->options[$opt])
                                                <label class="relative flex items-center gap-4 p-4 md:p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 group/opt select-none bg-elevate-soft/30 hover:bg-elevate-soft border-elevate-soft hover:border-elevate-accent/50 active:scale-[0.99]">
                                                     
                                                    <input type="radio" 
                                                       name="answers[{{ $q->id }}]" 
                                                       value="{{ $opt }}" 
                                                       class="peer sr-only"
                                                       @change="saveAnswer('{{ $q->id }}', $event.target.value)"
                                                       {{ old("answers.{$q->id}") == $opt ? 'checked' : '' }}>
                                                
                                                    {{-- Indikator Huruf --}}
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0 transition-all border-2
                                                        bg-white border-elevate-soft text-elevate-dark/50 
                                                        peer-checked:bg-elevate-primary peer-checked:border-elevate-primary peer-checked:text-white
                                                        group-hover/opt:border-elevate-accent group-hover/opt:text-elevate-primary">
                                                        {{ $opt }}
                                                    </div>
                                                    
                                                    {{-- Teks Jawaban --}}
                                                    <span class="text-base font-medium text-elevate-dark/80 peer-checked:text-elevate-dark peer-checked:font-bold transition-colors">
                                                        {{ $q->options[$opt] }}
                                                    </span>
                                                    
                                                    {{-- Checkmark Icon --}}
                                                    <div class="absolute right-4 text-elevate-primary opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100">
                                                        <i class="ph-fill ph-check-circle text-2xl"></i>
                                                    </div>

                                                    {{-- Active Ring --}}
                                                    <div class="absolute inset-0 rounded-2xl ring-2 ring-elevate-accent opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                                </label>
                                            @endif
                                        @endforeach
                                    @else
                                         <div class="relative">
                                            <textarea name="answers[{{ $q->id }}]" rows="5" 
                                                class="w-full rounded-2xl border-elevate-soft bg-elevate-soft/50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary/30 text-elevate-dark placeholder:text-elevate-dark/40 p-4 font-medium transition-all resize-none shadow-inner" 
                                                placeholder="Ketik jawaban uraian Anda di sini..."
                                                @input.debounce.1000ms="saveAnswer('{{ $q->id }}', $event.target.value)">{{ old("answers.{$q->id}") }}</textarea>
                                            <div class="absolute bottom-3 right-3 text-elevate-dark/30 pointer-events-none"><i class="ph-bold ph-pencil-simple text-xl"></i></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Floating Submit Button --}}
                <div class="fixed bottom-[80px] md:bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-lg border-t border-elevate-soft flex justify-center z-[60] animate-enter" style="animation-delay: 800ms">
                    <div class="w-full max-w-4xl flex justify-end">
                        <button type="button" onclick="confirmSubmit()"
                            class="w-full md:w-auto px-8 py-4 bg-elevate-primary text-white font-bold rounded-2xl shadow-xl shadow-elevate-primary/30 hover:bg-elevate-dark hover:-translate-y-1 transition-all flex items-center justify-center gap-3 active:scale-95 group">
                            <span>Kumpulkan Jawaban</span>
                            <div class="bg-white/20 rounded-full p-1 group-hover:translate-x-1 transition-transform">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> 
                            </div>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- SCRIPTS (TIDAK ADA PERUBAHAN LOGIC JS) --}}
    <script>
       document.addEventListener('alpine:init', () => {
            Alpine.data('examController', (durationInSeconds, assignmentId) => ({
                timeLeft: durationInSeconds,
                formattedTime: '00:00:00',
                violationCount: 0,
                saveStatus: '', 
                assignmentId: assignmentId,
                
                startTimer() {
                    const interval = setInterval(() => {
                        this.timeLeft--;
                        const h = Math.floor(this.timeLeft / 3600);
                        const m = Math.floor((this.timeLeft % 3600) / 60);
                        const s = this.timeLeft % 60;
                        this.formattedTime = (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
                        if (this.timeLeft <= 0) {
                            clearInterval(interval);
                            this.forceSubmit();
                        }
                    }, 1000);
                },

                 handleVisibilityChange() {
                    if (document.hidden) {
                        this.violationCount++;

                        if (this.violationCount >= 3) {
                            Swal.fire({
                                icon: 'error',
                                title: 'PELANGGARAN FATAL!',
                                text: 'Anda telah meninggalkan halaman ujian sebanyak 3 kali. Sistem akan mengumpulkan ujian Anda secara paksa!',
                                confirmButtonText: 'Tutup',
                                confirmButtonColor: '#c86845',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                document.getElementById('quizForm').submit();
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan Anti-Cheat!',
                                html: `Anda terdeteksi berpindah tab atau meninggalkan halaman ujian.<br><br><strong class="text-rose-600">Peringatan ke-${this.violationCount} dari 3</strong>`,
                                confirmButtonText: 'Saya Mengerti',
                                confirmButtonColor: '#0d52a1',
                                allowOutsideClick: false,
                                backdrop: `rgba(200, 104, 69, 0.4)`
                            });
                        }
                    }
                },

                preventAction(actionName) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: `Tindakan ${actionName} dilarang!`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                },

                saveAnswer(questionId, answer) {
                    this.saveStatus = 'saving';
                    const url = `/student/learning/assignment/${this.assignmentId}/quiz/autosave`;
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ question_id: questionId, answer: answer })
                    })
                    .then(response => {
                        if(response.ok) { this.saveStatus = 'saved'; } 
                        else { this.saveStatus = 'error'; }
                        setTimeout(() => { if(this.saveStatus === 'saved') this.saveStatus = ''; }, 2000);
                    })
                    .catch(error => {
                        console.error('Autosave Error:', error);
                        this.saveStatus = 'error';
                    });
                },

                forceSubmit() {
                    Swal.fire({
                        title: 'WAKTU HABIS!',
                        html: 'Sistem akan mengumpulkan jawaban Anda secara otomatis.',
                        icon: 'warning',
                        timer: 3000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => { Swal.showLoading(); }
                    }).then(() => {
                        document.getElementById('quizForm').submit();
                    });
                }
            }));
        });

        function confirmSubmit() {
            Swal.fire({
                title: 'Sudah Yakin?',
                text: "Pastikan semua soal sudah terjawab. Jawaban tidak bisa diubah setelah dikumpulkan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d52a1',
                cancelButtonColor: '#2c3f61',
                confirmButtonText: 'Ya, Kumpulkan!',
                cancelButtonText: 'Periksa Lagi',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold shadow-lg shadow-elevate-primary/20',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold hover:bg-elevate-soft text-elevate-dark/80'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Jawaban...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans' }
                    });
                    document.getElementById('quizForm').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim!',
                    text: {!! json_encode(session('error')) !!},
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#c86845',
                    customClass: { popup: 'rounded-[2rem] font-sans' }
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!}, 
                    confirmButtonText: 'Lanjut',
                    confirmButtonColor: '#0d52a1',
                    customClass: { popup: 'rounded-[2rem] font-sans' }
                }).then(() => {
                    window.location.href = "{{ route('students.learning.index') }}";
                });
            @endif
        });
    </script>
</x-student-learning-layout>