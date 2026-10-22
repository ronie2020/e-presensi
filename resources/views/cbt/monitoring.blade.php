<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Monitoring Live') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('monitoringData', () => ({
                students: @json($monitoringData),
                stats: @json($stats),
                search: '', 
                timer: 10,
                isUpdating: false,
                
                // PERBAIKAN: Tambahkan variabel examType ke Alpine state
                examType: '{{ $exam->exam_type ?? "cbt" }}',
                
                currentToken: '{{ $exam->token }}',
                autoRotate: false,
                intervalMinutes: 15,
                tokenIsLoading: false,
                tokenTimeLeft: 0,
                tokenProgress: 100,
                tokenTimer: null,

                showPhotoModal: false,
                activeStudentName: '',
                studentPhotos: [],
                loadingPhotos: false,

                // Proctor Message State
                showMessageModal: false,
                targetStudentId: null,
                targetStudentName: '',
                messageText: '',
                messageType: 'warning', // 'info', 'warning', 'urgent'
                isSendingMessage: false,
                
                urls: {
                    monitoring: '{{ route('cbt.monitoring_data', $exam->id) }}',
                    autoToken: '{{ route('cbt.auto_token', $exam->id) }}',
                    resetBase: '{{ route('cbt.reset', ['exam' => $exam->id, 'student' => 'ID_PLACEHOLDER']) }}',
                    photosBase: '{{ route("cbt.monitoring.photos", ["exam" => $exam->id, "student" => "ID_PLACEHOLDER"]) }}',
                    sendMessage: '{{ route("cbt.monitoring.send_message", $exam->id) }}',
                    extraTimeBase: '{{ route("cbt.monitoring.extra_time", ["exam" => $exam->id, "student" => "ID_PLACEHOLDER"]) }}',
                    forceSubmitBase: '{{ route("cbt.monitoring.force_submit", ["exam" => $exam->id, "student" => "ID_PLACEHOLDER"]) }}',
                    forceSubmitAll: '{{ route("cbt.monitoring.force_submit_all", $exam->id) }}'
                },

                init() {
                    setInterval(() => {
                        if (this.timer > 0) {
                            this.timer--;
                        } else {
                            this.fetchStudentData();
                            this.timer = 10;
                        }
                    }, 1000);

                    this.loadTokenState();
                    this.$watch('autoRotate', val => { 
                        if(val) this.startTokenTimer(); else this.stopTokenTimer();
                        this.saveTokenState(); 
                    });
                    this.$watch('intervalMinutes', () => { 
                        if(this.autoRotate) this.startTokenTimer();
                        this.saveTokenState(); 
                    });
                },

                fetchStudentData() {
                    this.isUpdating = true;
                    fetch(this.urls.monitoring)
                        .then(res => res.json())
                        .then(data => {
                            this.students = data.monitoringData;
                            this.stats = data.stats;
                            if (data.exam && data.exam.token) {
                                this.currentToken = data.exam.token;
                            }
                        })
                        .catch(err => console.error('Error auto update:', err))
                        .finally(() => {
                            this.isUpdating = false;
                        });
                },

                get filteredStudents() {
                    if (!this.search) return this.students;
                    return this.students.filter(s => 
                        s.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                getResetUrl(studentId) {
                    return this.urls.resetBase.replace('ID_PLACEHOLDER', studentId);
                },

                loadTokenState() {
                    let saved = localStorage.getItem('token_monitor_{{ $exam->id }}');
                    if (saved) {
                        try {
                            let parsed = JSON.parse(saved);
                            this.autoRotate = parsed.autoRotate;
                            this.intervalMinutes = parsed.intervalMinutes;
                            let now = Math.floor(Date.now() / 1000);
                            let remaining = parsed.targetTime - now;
                            if (remaining > 0 && this.autoRotate) {
                                this.tokenTimeLeft = remaining;
                                this.startTokenTimer(false);
                            } else if (this.autoRotate) {
                                this.rotateTokenNow();
                                this.startTokenTimer(true);
                            }
                        } catch(e) {}
                    }
                },

                saveTokenState() {
                    let now = Math.floor(Date.now() / 1000);
                    localStorage.setItem('token_monitor_{{ $exam->id }}', JSON.stringify({
                        autoRotate: this.autoRotate, 
                        intervalMinutes: this.intervalMinutes, 
                        targetTime: now + this.tokenTimeLeft
                    }));
                },

                startTokenTimer(reset = true) {
                    this.stopTokenTimer();
                    let total = this.intervalMinutes * 60;
                    if (reset) this.tokenTimeLeft = total;
                    
                    this.tokenTimer = setInterval(() => {
                        this.tokenTimeLeft--;
                        this.tokenProgress = (this.tokenTimeLeft / total) * 100;
                        this.saveTokenState();
                        if (this.tokenTimeLeft <= 0) { 
                            this.rotateTokenNow(); 
                            this.tokenTimeLeft = total; 
                        }
                    }, 1000);
                },

                stopTokenTimer() { 
                    clearInterval(this.tokenTimer); 
                    this.tokenProgress = 100; 
                    localStorage.removeItem('token_monitor_{{ $exam->id }}'); 
                },

                rotateTokenNow() {
                    this.tokenIsLoading = true;
                    fetch(this.urls.autoToken, {
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(r => r.json())
                    .then(d => { 
                        if (d.status === 'success') {
                            this.currentToken = d.token;
                            Swal.fire({
                                toast: true, position: 'top-end', icon: 'success', 
                                title: 'Token Diperbarui!', text: d.token,
                                showConfirmButton: false, timer: 3000,
                                customClass: { popup: 'rounded-2xl shadow-xl border border-white/10 bg-slate-900 text-white' }
                            });
                        }
                    })
                    .finally(() => this.tokenIsLoading = false);
                },

                openPhotoModal(studentId, studentName) {
                    this.activeStudentName = studentName;
                    this.showPhotoModal = true;
                    this.loadingPhotos = true;
                    this.studentPhotos = [];

                    const url = this.urls.photosBase.replace('ID_PLACEHOLDER', studentId);

                    fetch(url)
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal memuat foto');
                            return res.json();
                        })
                        .then(data => { this.studentPhotos = data; })
                        .catch(err => { console.error(err); this.studentPhotos = []; })
                        .finally(() => { this.loadingPhotos = false; });
                },

                openMessageModal(studentId = null, studentName = '') {
                    this.targetStudentId = studentId;
                    this.targetStudentName = studentName;
                    this.messageType = studentId ? 'warning' : 'info';
                    this.messageText = '';
                    this.showMessageModal = true;
                },

                sendMessageToServer() {
                    if (!this.messageText || !this.messageText.trim()) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Pesan belum diisi!',
                            showConfirmButton: false,
                            timer: 2000,
                            customClass: { popup: 'rounded-2xl shadow-xl border border-white/10 bg-slate-900 text-white' }
                        });
                        return;
                    }
                    this.isSendingMessage = true;
                    fetch(this.urls.sendMessage, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            student_id: this.targetStudentId,
                            message: this.messageText,
                            type: this.messageType
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            this.showMessageModal = false;
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: this.targetStudentId ? 'Teguran Berhasil Dikirim!' : 'Pesan Siaran Terkirim!',
                                text: 'Pesan akan tampil di layar siswa secara langsung.',
                                showConfirmButton: false,
                                timer: 3500,
                                customClass: { popup: 'rounded-2xl shadow-xl border border-white/10 bg-slate-900 text-white' }
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal Mengirim', text: data.message || 'Terjadi kesalahan sistem' });
                        }
                    })
                    .catch(err => {
                        console.error('Error sending message:', err);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi gagal atau server bermasalah.' });
                    })
                    .finally(() => {
                        this.isSendingMessage = false;
                    });
                },

                giveExtraTime(studentId, studentName) {
                    Swal.fire({
                        title: 'Tambah Waktu Ujian',
                        html: `Pilih durasi tambahan waktu untuk siswa <b class="text-sky-400">${studentName}</b>:`,
                        icon: 'question',
                        input: 'select',
                        inputOptions: {
                            '5': '+5 Menit',
                            '10': '+10 Menit',
                            '15': '+15 Menit',
                            '30': '+30 Menit',
                            '45': '+45 Menit',
                            '60': '+60 Menit (1 Jam)'
                        },
                        inputValue: '10',
                        showCancelButton: true,
                        confirmButtonText: 'Berikan Tambahan Waktu',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#0284c7',
                        cancelButtonColor: '#475569',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const minutes = result.value;
                            const url = this.urls.extraTimeBase.replace('ID_PLACEHOLDER', studentId);
                            
                            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), background: '#0f172a', color: '#f8fafc' });

                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ minutes: parseInt(minutes) })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Waktu Ditambahkan!',
                                        text: data.message,
                                        showConfirmButton: false,
                                        timer: 3500,
                                        customClass: { popup: 'rounded-2xl border border-white/10 bg-slate-900 text-white' }
                                    });
                                    this.fetchStudentData();
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan sistem' });
                                }
                            })
                            .catch(err => {
                                Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi gagal atau server bermasalah.' });
                            });
                        }
                    });
                },

                confirmForceSubmitStudent(studentId, studentName) {
                    Swal.fire({
                        title: 'Selesaikan Ujian Siswa?',
                        html: `Jawaban siswa <b class="text-amber-400">${studentName}</b> akan langsung dikumpulkan dan dinilai sekarang juga. Siswa tidak dapat melanjutkan ujian lagi.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d97706',
                        cancelButtonColor: '#475569',
                        confirmButtonText: 'Ya, Selesaikan & Nilai!',
                        cancelButtonText: 'Batal',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = this.urls.forceSubmitBase.replace('ID_PLACEHOLDER', studentId);
                            Swal.fire({ title: 'Menyelesaikan ujian...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), background: '#0f172a', color: '#f8fafc' });

                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Ujian Diselesaikan!',
                                        text: 'Lembar ujian telah dikumpulkan dan nilai berhasil dihitung.',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        customClass: { popup: 'rounded-2xl border border-white/10 bg-slate-900 text-white' }
                                    });
                                    this.fetchStudentData();
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                                }
                            })
                            .catch(err => {
                                Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi gagal atau server bermasalah.' });
                            });
                        }
                    });
                },

                confirmForceSubmitAll() {
                    if (this.stats.working === 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Tidak Ada Siswa Aktif',
                            text: 'Semua siswa telah selesai atau belum login.',
                            showConfirmButton: false,
                            timer: 2500,
                            customClass: { popup: 'rounded-2xl border border-white/10 bg-slate-900 text-white' }
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Selesaikan Semua Siswa?',
                        html: `Apakah Anda yakin ingin mengumpulkan ujian untuk <b>${this.stats.working} siswa</b> yang masih berstatus proses? Tindakan ini tidak dapat dibatalkan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#475569',
                        confirmButtonText: 'Ya, Kumpulkan Semua!',
                        cancelButtonText: 'Batal',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({ title: 'Mengumpulkan semua lembar jawaban...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), background: '#0f172a', color: '#f8fafc' });

                            fetch(this.urls.forceSubmitAll, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Selesai Massal!',
                                    text: data.message,
                                    background: '#0f172a',
                                    color: '#f8fafc',
                                    customClass: { popup: 'rounded-2xl border border-white/10' }
                                });
                                this.fetchStudentData();
                            })
                            .catch(err => {
                                Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi gagal atau server bermasalah.' });
                            });
                        }
                    });
                }
            }));
        });

        function confirmResetLogin(url, studentName) {
            Swal.fire({
                title: 'Keluarkan Siswa?',
                html: `Siswa <b class="text-rose-400">${studentName}</b> akan dipaksa keluar (Logout) dan harus memasukkan token baru jika ingin masuk lagi.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Keluarkan!',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#f8fafc',
                customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), background: '#0f172a', color: '#f8fafc', customClass: { popup: 'rounded-[2rem] border border-white/10' } });
                    form.submit();
                }
            });
        }
    </script>

    <div class="min-h-screen bg-[#020b18] text-slate-100 relative overflow-hidden py-8 sm:py-10 font-sans" x-data="monitoringData">
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">

            {{-- HEADER INFO & TOKEN --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Info Ujian (ELEVATE DARK HERO STYLE) --}}
                <div class="md:col-span-2 relative rounded-[2.5rem] bg-gradient-to-r from-[#031d3d]/90 via-[#021124]/90 to-[#020b18]/90 p-8 text-white shadow-2xl backdrop-blur-xl border border-white/10 overflow-hidden">
                    <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#56bbf1]/10 rounded-3xl rotate-12 pointer-events-none blur-2xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-blue-600/10 rounded-[3rem] -rotate-12 pointer-events-none blur-2xl"></div>

                    <div class="relative z-10">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            @if($exam->is_active)
                                <span class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider animate-pulse flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Live Active
                                </span>
                            @else
                                <span class="bg-white/5 border border-white/10 text-slate-400 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">Non-Aktif</span>
                            @endif
                            <span class="text-sky-400 text-xs font-black uppercase tracking-wider bg-white/5 px-2 py-0.5 rounded border border-white/10">{{ in_array($exam->class_level, ['7', '8', '9']) ? 'Kelas ' . $exam->class_level : ($exam->class_level ?: 'Semua Kelas') }}</span>
                            
                            {{-- PERBAIKAN: BADGE GOOGLE FORM --}}
                            @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                                <span class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm" title="Ujian Terintegrasi Google Form">
                                    <i class="ph-bold ph-google-logo"></i> G-Form
                                </span>
                            @endif
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none mb-4 text-white">{{ $exam->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="bg-white/5 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center min-w-[90px] shadow-sm">
                                <h4 class="text-2xl font-black text-sky-400 leading-none" x-text="stats.working">{{ $stats['working'] }}</h4>
                                <p class="text-[9px] uppercase font-black text-slate-400 mt-1">Proses</p>
                            </div>
                            <div class="bg-emerald-500/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-emerald-500/20 text-center min-w-[90px] shadow-sm">
                                <h4 class="text-2xl font-black text-emerald-400 leading-none" x-text="stats.finished">{{ $stats['finished'] }}</h4>
                                <p class="text-[9px] uppercase font-black text-emerald-300/70 mt-1">Selesai</p>
                            </div>
                            <button type="button" @click="confirmForceSubmitAll()"
                                    :disabled="stats.working === 0"
                                    class="px-4 py-3 rounded-2xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 font-bold text-xs flex items-center gap-2 transition disabled:opacity-30 disabled:cursor-not-allowed shadow-sm backdrop-blur-md active:scale-95"
                                    title="Paksa Kumpulkan Jawaban Semua Siswa yang Masih Mengerjakan">
                                <i class="ph-bold ph-stop-circle text-lg text-rose-400"></i>
                                <span>Kumpulkan Semua (<span x-text="stats.working"></span>)</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Token Card --}}
                <div class="md:col-span-1 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2.5rem] shadow-2xl backdrop-blur-xl border border-white/10 flex flex-col justify-between relative overflow-hidden">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5"><i class="ph-fill ph-key text-[#56bbf1]"></i> Token Akses</p>
                            <div class="flex items-center gap-2">
                                <span x-show="autoRotate" class="animate-pulse w-2 h-2 bg-emerald-400 rounded-full"></span>
                                <span x-text="autoRotate ? 'Auto: ' + intervalMinutes + 'm' : 'Manual'" class="text-[10px] font-bold text-slate-400"></span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-5xl font-mono font-black tracking-widest text-[#56bbf1]" x-text="currentToken">{{ $exam->token ?? '-----' }}</h2>
                            <button @click="rotateTokenNow()" :disabled="tokenIsLoading" class="p-3 rounded-xl bg-white/5 hover:bg-white/10 text-sky-400 transition disabled:opacity-50 border border-white/10">
                                <i class="ph-bold ph-arrows-clockwise text-xl" :class="tokenIsLoading ? 'animate-spin' : ''"></i>
                            </button>
                        </div>
                        <div x-show="autoRotate" class="w-full bg-slate-900 h-1.5 rounded-full overflow-hidden mb-4 border border-white/5">
                            <div class="bg-[#56bbf1] h-full transition-all duration-1000 ease-linear" :style="'width: ' + tokenProgress + '%'"></div>
                        </div>
                    </div>
                    <div class="bg-white/5 p-3.5 rounded-2xl border border-white/10 flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" x-model="autoRotate" class="rounded border-white/20 bg-slate-900 text-[#56bbf1] focus:ring-[#56bbf1]">
                            <span class="text-xs font-bold text-slate-300">Auto Ganti</span>
                        </label>
                        <select x-model="intervalMinutes" :disabled="!autoRotate" class="text-xs font-bold text-slate-300 bg-slate-900 border border-white/10 rounded-lg focus:ring-0 p-1 cursor-pointer text-right disabled:text-slate-600 [color-scheme:dark]">
                            <option value="5" class="bg-slate-900 text-white">5 Menit</option>
                            <option value="10" class="bg-slate-900 text-white">10 Menit</option>
                            <option value="15" class="bg-slate-900 text-white">15 Menit</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- TOOLBAR BAWAH --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('cbt.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-white transition">
                        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke List
                    </a>

                    <div class="w-px h-5 bg-white/10 hidden sm:block"></div>

                    <button type="button" @click="openMessageModal()" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-sky-500/25 transition active:scale-95">
                        <i class="ph-bold ph-megaphone text-sm"></i>
                        <span>Kirim Pesan Siaran</span>
                    </button>
                </div>

                <div class="text-[10px] font-bold px-3 py-1.5 rounded-full border shadow-sm flex items-center gap-2 transition-colors duration-300"
                     :class="isUpdating ? 'bg-sky-500/10 text-sky-400 border-sky-500/30' : 'bg-white/5 text-slate-400 border-white/10'">
                    <i class="ph-bold ph-arrows-clockwise text-[#56bbf1]" :class="isUpdating ? 'animate-spin' : ''"></i>
                    <span x-text="isUpdating ? 'Mengambil Data...' : 'Auto Update: ' + timer + 's'"></span>
                </div>
            </div>

            <!-- TABEL PESERTA -->
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl overflow-hidden min-h-[500px]">
                <div class="p-6 border-b border-white/10 bg-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h4 class="font-bold text-white flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-users-three text-[#56bbf1]"></i> Peserta Ujian 
                        <span class="bg-white/10 text-sky-400 shadow-sm text-xs px-2.5 py-0.5 rounded-full border border-white/10" x-text="students.length"></span>
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-white/10 rounded-xl focus:ring-[#56bbf1] focus:border-[#56bbf1] bg-slate-900/90 text-white placeholder:text-slate-500 shadow-sm transition [color-scheme:dark]">
                    </div>
                </div>
                
                {{-- TABLE DESKTOP --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-white/5 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4 text-center">Keamanan & Perangkat</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Mulai</th>
                                <th class="px-6 py-4 text-center">Skor</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <template x-for="(student, index) in filteredStudents" :key="student.id">
                                <tr class="hover:bg-white/5 transition group" :class="student.is_active ? 'bg-sky-500/5' : ''">
                                    <td class="px-6 py-4 text-center font-bold text-slate-500" x-text="index + 1"></td>
                                    <td class="px-6 py-4 font-black text-white" x-text="student.name"></td>
                                    
                                    {{-- Kolom Keamanan (SEB & Pelanggaran) --}}
                                    <td class="px-6 py-4 text-center">
                                        <template x-if="student.status !== 'Belum Mengerjakan'">
                                            <div class="flex items-center justify-center gap-2">
                                                <template x-if="student.is_seb">
                                                    <span title="Menggunakan SEB (Aman)" class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                                        <i class="ph-fill ph-shield-check text-lg"></i>
                                                    </span>
                                                </template>
                                                <template x-if="!student.is_seb">
                                                    <span title="Browser Biasa (Mode Darurat)" class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 animate-pulse border border-amber-500/30">
                                                        <i class="ph-fill ph-warning-circle text-lg"></i>
                                                    </span>
                                                </template>
                                                <template x-if="student.device === 'Mobile'">
                                                    <span title="Menggunakan HP" class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 text-slate-400 border border-white/10">
                                                        <i class="ph-bold ph-device-mobile text-lg"></i>
                                                    </span>
                                                </template>
                                                <template x-if="student.device === 'Desktop'">
                                                    <span title="Menggunakan Laptop/PC" class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 text-slate-400 border border-white/10">
                                                        <i class="ph-bold ph-laptop text-lg"></i>
                                                    </span>
                                                </template>
                                                <template x-if="student.violations > 0">
                                                    <span :title="student.violations + ' Kali Pelanggaran (Pindah Tab / Minimize)'" class="flex items-center justify-center px-2 h-8 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/40 text-xs font-bold gap-1 animate-pulse">
                                                        <i class="ph-fill ph-warning-octagon text-sm text-rose-400"></i>
                                                        <span x-text="student.violations"></span>x
                                                    </span>
                                                </template>
                                                <template x-if="student.latency_ms">
                                                    <span :title="'Ping: ' + student.latency_ms + ' ms (Aktif: ' + student.last_seen + ')'" 
                                                          class="flex items-center justify-center px-2 h-8 rounded-lg text-xs font-mono font-bold gap-1 border transition-all"
                                                          :class="{
                                                              'bg-emerald-500/15 text-emerald-300 border-emerald-500/30': student.latency_ms < 200,
                                                              'bg-amber-500/15 text-amber-300 border-amber-500/30': student.latency_ms >= 200 && student.latency_ms < 600,
                                                              'bg-rose-500/15 text-rose-300 border-rose-500/30 animate-pulse': student.latency_ms >= 600
                                                          }">
                                                        <i class="ph-bold ph-wifi-high text-xs"></i>
                                                        <span x-text="student.latency_ms + 'ms'"></span>
                                                    </span>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="student.status === 'Belum Mengerjakan'">
                                            <span class="text-slate-600 text-xs">-</span>
                                        </template>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 text-center">
                                        <template x-if="student.status === 'Sedang Mengerjakan'">
                                            <div class="flex flex-col items-center justify-center gap-1">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-sky-500/20 text-sky-300 border border-sky-500/30 uppercase tracking-wide">
                                                    <span class="block w-1.5 h-1.5 rounded-full bg-[#56bbf1] animate-pulse"></span> Proses
                                                </span>
                                                <template x-if="student.extra_time > 0">
                                                    <span :title="'Mendapatkan tambahan waktu ' + student.extra_time + ' menit'" 
                                                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                                        <i class="ph-bold ph-clock-plus"></i> +<span x-text="student.extra_time"></span>m
                                                    </span>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="student.status === 'Selesai'">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wide">
                                                <i class="ph-bold ph-check"></i> Selesai
                                            </span>
                                        </template>
                                        <template x-if="student.status !== 'Sedang Mengerjakan' && student.status !== 'Selesai'">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-white/5 text-slate-500 border border-white/10 uppercase tracking-wide">
                                                Belum
                                            </span>
                                        </template>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center font-mono text-xs font-medium text-slate-400" x-text="student.start_time"></td>
                                    
                                    {{-- PERBAIKAN: Kolom Skor Dinamis (Sembunyikan untuk G-Form) --}}
                                    <td class="px-6 py-4 text-center">
                                        <template x-if="examType === 'google_form'">
                                            <span class="text-[10px] font-bold text-slate-400 bg-white/5 border border-white/10 px-2 py-1 rounded-lg" title="Nilai ada di platform Google">Cek G-Form</span>
                                        </template>
                                        <template x-if="examType !== 'google_form'">
                                            <span class="font-black text-lg" 
                                                :class="student.status === 'Selesai' ? (student.score > 0 ? 'text-[#56bbf1]' : 'text-white') : 'text-slate-600'" 
                                                x-text="student.score"></span>
                                        </template>
                                    </td>
                                    
                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-right flex items-center justify-end gap-1.5">
                                        <template x-if="student.is_active || student.status === 'Selesai'">
                                            <button @click="openPhotoModal(student.id, student.name)" class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 text-sky-400 hover:bg-white/10 flex items-center justify-center transition shadow-sm" title="Lihat Foto Pengawasan">
                                                <i class="ph-bold ph-camera"></i>
                                            </button>
                                        </template>
                                        <template x-if="student.is_active">
                                            <button @click="giveExtraTime(student.id, student.name)" class="w-8 h-8 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 hover:bg-indigo-500/20 flex items-center justify-center transition shadow-sm" title="Beri Tambahan Waktu (+Menit)">
                                                <i class="ph-bold ph-clock-plus"></i>
                                            </button>
                                        </template>
                                        <template x-if="student.is_active">
                                            <button @click="openMessageModal(student.id, student.name)" class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 flex items-center justify-center transition shadow-sm" title="Tegur Siswa Ini">
                                                <i class="ph-bold ph-chat-centered-dots"></i>
                                            </button>
                                        </template>
                                        <template x-if="student.is_active">
                                            <button @click="confirmForceSubmitStudent(student.id, student.name)" class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20 flex items-center justify-center transition shadow-sm" title="Paksa Selesai & Hitung Nilai">
                                                <i class="ph-bold ph-check-circle"></i>
                                            </button>
                                        </template>
                                        <template x-if="student.is_active">
                                            <button @click="confirmResetLogin(getResetUrl(student.id), student.name)" class="w-8 h-8 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/20 flex items-center justify-center transition shadow-sm" title="Keluarkan Siswa (Reset)">
                                                <i class="ph-bold ph-power"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            
                            <tr x-show="filteredStudents.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">Tidak ada siswa yang ditemukan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE LIST --}}
                <div class="md:hidden p-4 space-y-3 bg-white/5">
                    <template x-for="student in filteredStudents" :key="'m-' + student.id">
                        <div class="bg-slate-900/80 p-4 rounded-2xl border border-white/10 shadow-sm relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5" 
                                 :class="student.status == 'Sedang Mengerjakan' ? 'bg-[#56bbf1]' : (student.status == 'Selesai' ? 'bg-emerald-400' : 'bg-slate-700')"></div>
                            
                            <div class="pl-3 flex justify-between items-start">
                                <div>
                                    <h5 class="font-bold text-white text-sm mb-1" x-text="student.name"></h5>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border"
                                              :class="student.status == 'Sedang Mengerjakan' ? 'bg-sky-500/20 text-sky-300 border-sky-500/30' : (student.status == 'Selesai' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 'text-slate-500 border-white/10')"
                                              x-text="student.status == 'Sedang Mengerjakan' ? 'Sedang Ujian' : (student.status == 'Selesai' ? 'Selesai' : 'Belum Login')">
                                        </span>
                                    </div>
                                    <template x-if="student.status !== 'Belum Mengerjakan'">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span x-show="student.is_seb" class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1"><i class="ph-fill ph-shield-check"></i> SEB</span>
                                            <span x-show="!student.is_seb" class="text-[10px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Browser</span>
                                            <span x-show="student.latency_ms" class="text-[10px] font-mono font-bold px-2 py-0.5 rounded border border-white/10 bg-white/5 text-slate-300" x-text="student.latency_ms + 'ms'"></span>
                                            <template x-if="student.extra_time > 0">
                                                <span class="text-[10px] font-bold text-indigo-300 bg-indigo-500/15 border border-indigo-500/30 px-2 py-0.5 rounded flex items-center gap-1">
                                                    <i class="ph-bold ph-clock-plus"></i> +<span x-text="student.extra_time"></span>m
                                                </span>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] text-slate-500 font-bold uppercase mb-0.5">Skor</span>
                                    {{-- PERBAIKAN: Skor Mobile --}}
                                    <template x-if="examType === 'google_form'">
                                        <span class="block text-[10px] font-bold text-slate-400 bg-white/5 border border-white/10 px-2 py-1 rounded mt-1">Cek G-Form</span>
                                    </template>
                                    <template x-if="examType !== 'google_form'">
                                        <span class="block text-xl font-black text-[#56bbf1] leading-none" x-text="student.score"></span>
                                    </template>
                                </div>
                            </div>
                            
                            <template x-if="student.is_active">
                                <div class="mt-3 pt-3 border-t border-white/10 flex flex-wrap justify-end gap-1.5">
                                    <button @click="giveExtraTime(student.id, student.name)" class="text-xs font-bold text-indigo-400 bg-indigo-500/10 px-2.5 py-1.5 rounded-lg border border-indigo-500/20 flex items-center gap-1">
                                        <i class="ph-bold ph-clock-plus"></i> +Waktu
                                    </button>
                                    <button @click="confirmForceSubmitStudent(student.id, student.name)" class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1.5 rounded-lg border border-emerald-500/20 flex items-center gap-1">
                                        <i class="ph-bold ph-check-circle"></i> Selesai
                                    </button>
                                    <button @click="openMessageModal(student.id, student.name)" class="text-xs font-bold text-amber-400 bg-amber-500/10 px-2.5 py-1.5 rounded-lg border border-amber-500/20 flex items-center gap-1">
                                        <i class="ph-bold ph-chat-centered-dots"></i> Tegur
                                    </button>
                                    <button @click="openPhotoModal(student.id, student.name)" class="text-xs font-bold text-sky-400 bg-white/5 px-2.5 py-1.5 rounded-lg border border-white/10 flex items-center gap-1">
                                        <i class="ph-bold ph-camera"></i> Foto
                                    </button>
                                    <button @click="confirmResetLogin(getResetUrl(student.id), student.name)" class="text-xs font-bold text-rose-400 bg-rose-500/10 px-2.5 py-1.5 rounded-lg border border-rose-500/20 flex items-center gap-1">
                                        <i class="ph-bold ph-power"></i> Reset
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- MODAL FOTO PROCTORING --}}
        <template x-teleport="body">
            <div x-show="showPhotoModal" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto" @keydown.escape.window="showPhotoModal = false">
                <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="showPhotoModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-[#031d3d] rounded-[2.5rem] shadow-2xl max-w-3xl w-full p-8 overflow-hidden transform transition-all border border-white/10 text-white">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-xl font-black text-white flex items-center gap-2">
                                    <i class="ph-fill ph-camera text-[#56bbf1]"></i> Log Foto Pengawasan
                                </h3>
                                <p class="text-sm text-slate-400 font-bold" x-text="activeStudentName"></p>
                            </div>
                            <button @click="showPhotoModal = false" class="w-10 h-10 rounded-xl bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white flex items-center justify-center transition border border-white/10">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        <div class="bg-slate-900/80 rounded-2xl p-6 min-h-[300px] max-h-[60vh] overflow-y-auto custom-scroll border border-white/10">
                            {{-- Loading State --}}
                            <div x-show="loadingPhotos" class="flex flex-col items-center justify-center h-48 text-[#56bbf1]">
                                <i class="ph-bold ph-spinner animate-spin text-3xl mb-2"></i>
                                <span class="text-xs font-black uppercase tracking-wider text-sky-400">Memuat Foto...</span>
                            </div>

                            {{-- Empty State --}}
                            <div x-show="!loadingPhotos && studentPhotos.length === 0" class="flex flex-col items-center justify-center h-48 text-slate-500">
                                <i class="ph-duotone ph-image-broken text-4xl mb-2 text-[#56bbf1]/40"></i>
                                <span class="text-xs font-bold">Tidak ada foto terekam.</span>
                            </div>

                            {{-- Photos Grid --}}
                            <div x-show="!loadingPhotos && studentPhotos.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <template x-for="photo in studentPhotos">
                                    <div class="bg-slate-900 p-2 rounded-xl shadow-sm border border-white/10 group hover:border-[#56bbf1]/50 transition">
                                        <div class="relative overflow-hidden rounded-lg aspect-video bg-slate-950">
                                            <img :src="photo.url" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                        </div>
                                        <div class="mt-2 flex justify-between items-center px-1">
                                            <span class="text-[10px] font-black text-sky-400 bg-white/5 px-2 py-0.5 rounded border border-white/10" x-text="photo.time"></span>
                                            <span class="text-[10px] text-slate-500 font-bold" x-text="photo.ago"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- MODAL KIRIM PESAN SIARAN / TEGURAN --}}
        <template x-teleport="body">
            <div x-show="showMessageModal" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto" @keydown.escape.window="showMessageModal = false" x-cloak>
                <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="showMessageModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-[#031d3d] rounded-[2.5rem] shadow-2xl max-w-lg w-full p-8 overflow-hidden transform transition-all border border-white/10 text-white">
                        
                        {{-- Header Modal --}}
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shadow-inner"
                                     :class="targetStudentId ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-sky-500/20 text-sky-400 border border-sky-500/30'">
                                    <i class="ph-bold" :class="targetStudentId ? 'ph-chat-centered-dots' : 'ph-megaphone'"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white" x-text="targetStudentId ? 'Teguran Pengawas' : 'Kirim Pesan Siaran'"></h3>
                                    <p class="text-xs text-slate-400 font-bold" x-text="targetStudentId ? 'Kirim peringatan ke layar siswa tertentu' : 'Siarkan pengumuman ke seluruh peserta'"></p>
                                </div>
                            </div>
                            <button @click="showMessageModal = false" class="w-10 h-10 rounded-xl bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white flex items-center justify-center transition border border-white/10">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        {{-- Target Banner --}}
                        <template x-if="targetStudentId">
                            <div class="p-3 bg-amber-500/15 border border-amber-400/30 rounded-2xl flex items-center justify-between gap-2.5 mb-5 text-xs font-bold text-amber-300">
                                <span class="flex items-center gap-2">
                                    <i class="ph-fill ph-user text-base"></i> Target: <span class="text-white font-black" x-text="targetStudentName"></span>
                                </span>
                                <button type="button" @click="targetStudentId = null; targetStudentName = ''; messageType = 'info'" class="text-[10px] text-slate-300 hover:text-white underline">Ubah ke Siaran Umum</button>
                            </div>
                        </template>
                        <template x-if="!targetStudentId">
                            <div class="p-3 bg-sky-500/15 border border-sky-400/30 rounded-2xl flex items-center gap-2.5 mb-5 text-xs font-bold text-sky-300">
                                <i class="ph-fill ph-broadcast text-base"></i> Target: <span class="text-white font-black">Semua Siswa Sedang Ujian (Broadcast)</span>
                            </div>
                        </template>

                        {{-- Tipe Pesan --}}
                        <div class="mb-5">
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Tingkat Pesan</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="messageType = 'info'" 
                                        class="py-2.5 px-3 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 transition"
                                        :class="messageType === 'info' ? 'bg-sky-500/20 border-sky-400 text-sky-300 shadow-sm' : 'bg-white/5 border-white/10 text-slate-400 hover:text-white'">
                                    <i class="ph-bold ph-info"></i> Info
                                </button>
                                <button type="button" @click="messageType = 'warning'" 
                                        class="py-2.5 px-3 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 transition"
                                        :class="messageType === 'warning' ? 'bg-amber-500/20 border-amber-400 text-amber-300 shadow-sm' : 'bg-white/5 border-white/10 text-slate-400 hover:text-white'">
                                    <i class="ph-bold ph-warning"></i> Peringatan
                                </button>
                                <button type="button" @click="messageType = 'urgent'" 
                                        class="py-2.5 px-3 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 transition"
                                        :class="messageType === 'urgent' ? 'bg-rose-500/20 border-rose-400 text-rose-300 shadow-sm' : 'bg-white/5 border-white/10 text-slate-400 hover:text-white'">
                                    <i class="ph-bold ph-warning-octagon"></i> Teguran Keras
                                </button>
                            </div>
                        </div>

                        {{-- Quick Phrases --}}
                        <div class="mb-4">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Template Cepat:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" @click="messageText = 'Waktu ujian tersisa 15 menit lagi, harap periksa kembali jawaban Anda!'" 
                                        class="text-[11px] px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition">
                                    ⏱️ Sisa 15 Menit
                                </button>
                                <button type="button" @click="messageText = 'Dilarang membuka tab browser lain, aplikasi lain, atau berpindah layar!'" 
                                        class="text-[11px] px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition">
                                    🚫 Dilarang Pindah Tab
                                </button>
                                <button type="button" @click="messageText = 'Harap tenang, fokus ke layar masing-masing dan jangan berbisik!'" 
                                        class="text-[11px] px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition">
                                    🤫 Jangan Berbisik
                                </button>
                            </div>
                        </div>

                        {{-- Input Textarea --}}
                        <div class="mb-6">
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Isi Pesan</label>
                            <textarea x-model="messageText" rows="3" 
                                      placeholder="Ketik pesan yang akan muncul langsung di layar siswa..." 
                                      class="w-full bg-slate-900/90 border-white/10 rounded-2xl p-4 text-white text-sm focus:border-sky-400 focus:ring-sky-500 placeholder:text-slate-500 shadow-inner"></textarea>
                        </div>

                        {{-- Tombol Kirim --}}
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="showMessageModal = false" 
                                    class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-white/5 border border-white/10 transition">
                                Batal
                            </button>
                            <button type="button" @click="sendMessageToServer()" :disabled="isSendingMessage"
                                    class="px-6 py-2.5 rounded-xl text-xs font-bold text-white shadow-lg transition flex items-center gap-2 active:scale-95 disabled:opacity-50"
                                    :class="targetStudentId ? 'bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 shadow-amber-500/25' : 'bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 shadow-sky-500/25'">
                                <i class="ph-bold" :class="isSendingMessage ? 'ph-spinner animate-spin' : 'ph-paper-plane-right'"></i>
                                <span x-text="isSendingMessage ? 'Mengirim...' : (targetStudentId ? 'Kirim Teguran' : 'Siarkan Pesan')"></span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>