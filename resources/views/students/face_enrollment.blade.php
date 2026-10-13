<x-app-layout>
    {{-- Scripts Penunjang SweetAlert2 & Face-API.js Lokal --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/face-api.min.js') }}"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden" x-data="faceEnrollmentApp()">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[450px] bg-gradient-to-b from-[#0d52a1]/25 via-[#031d3d]/15 to-transparent opacity-40 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-6 md:p-8 mb-8 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden">
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('students.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                                <i class="ph-bold ph-arrow-left text-sky-400"></i>
                                <span>Kembali ke Data Siswa</span>
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                                </span>
                                AI Biometric Studio
                            </div>
                        </div>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white">
                            Perekaman Biometrik <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Wajah Siswa</span>
                        </h1>
                        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-medium leading-relaxed">
                            Daftarkan wajah siswa untuk diintegrasikan dengan Sistem Absensi Kiosk dan Kamera HP Guru. Data disimpan dalam bentuk 128-vektor matematis aman.
                        </p>
                    </div>

                    {{-- STATISTIK KARTU KECIL --}}
                    <div class="flex items-center gap-3 bg-slate-900/80 border border-white/10 rounded-2xl p-4 backdrop-blur-md shrink-0">
                        <div class="text-center px-3 border-r border-slate-700">
                            <span class="text-2xl font-black text-white">{{ $totalActive }}</span>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aktif</span>
                        </div>
                        <div class="text-center px-3 border-r border-slate-700">
                            <span class="text-2xl font-black text-emerald-400">{{ $totalRegistered }}</span>
                            <span class="block text-[10px] font-bold text-emerald-300 uppercase tracking-wider">Sudah Wajah</span>
                        </div>
                        <div class="text-center px-3">
                            <span class="text-2xl font-black text-amber-400">{{ $totalUnregistered }}</span>
                            <span class="block text-[10px] font-bold text-amber-300 uppercase tracking-wider">Belum</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONTEN UTAMA: 2 KOLOM --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- KOLOM KIRI (7 KANVAS): KAMERA LIVE & DETEKSI AI --}}
                <div class="lg:col-span-7 flex flex-col gap-6">
                    
                    {{-- BOX KAMERA --}}
                    <div class="bg-slate-900/80 border border-slate-800 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden flex flex-col">
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center text-xl">
                                    <i class="ph-bold ph-camera"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white">Live Kamera AI Enrolment</h3>
                                    <p class="text-xs text-slate-400">Posisikan wajah siswa di dalam bingkai</p>
                                </div>
                            </div>
                            
                            {{-- Status AI Badge --}}
                            <div id="ai-model-status" class="px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all flex items-center gap-2 bg-amber-500/10 border-amber-500/30 text-amber-300">
                                <i class="ph-bold ph-circle-notch animate-spin"></i>
                                <span>Memuat Model AI...</span>
                            </div>
                        </div>

                        {{-- AREA VIDEO STREAM --}}
                        <div class="relative w-full aspect-[4/3] bg-black rounded-2xl overflow-hidden border-2 border-slate-700 shadow-inner group">
                            
                            {{-- Elemen Video & Canvas --}}
                            <video id="webcam-video" autoplay muted playsinline class="w-full h-full object-cover transform -scale-x-100"></video>
                            <canvas id="overlay-canvas" class="absolute inset-0 w-full h-full pointer-events-none transform -scale-x-100"></canvas>

                            {{-- Bingkai Panduan Oval / Lingkaran Wajah --}}
                            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                                <div class="w-56 h-72 border-2 border-dashed border-sky-400/50 rounded-[4rem] flex flex-col items-center justify-between py-4 shadow-[0_0_30px_rgba(56,189,248,0.15)] transition-all group-hover:border-sky-400">
                                    <span class="text-[10px] font-bold text-sky-300 bg-black/60 px-2.5 py-1 rounded-full uppercase tracking-widest backdrop-blur-sm">Area Kepala</span>
                                    <span class="text-[10px] font-bold text-sky-300 bg-black/60 px-2.5 py-1 rounded-full uppercase tracking-widest backdrop-blur-sm">Area Dagu</span>
                                </div>
                            </div>

                            {{-- Banner Indikator Deteksi Realtime --}}
                            <div class="absolute bottom-4 inset-x-4 flex justify-center pointer-events-none">
                                <div id="detection-banner" class="bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-xs font-bold text-slate-300 flex items-center gap-2 shadow-lg transition-all">
                                    <i class="ph-bold ph-scan text-sky-400 animate-pulse text-base"></i>
                                    <span>Menunggu deteksi wajah...</span>
                                </div>
                            </div>

                            {{-- Countdown Overlay saat Capture --}}
                            <div id="countdown-overlay" class="hidden absolute inset-0 bg-sky-950/70 backdrop-blur-sm z-30 flex items-center justify-center">
                                <span id="countdown-number" class="text-8xl font-black text-white animate-ping">3</span>
                            </div>
                        </div>

                        {{-- TOMBOL KONTROL & AKSI DI BAWAH KAMERA --}}
                        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                            
                            {{-- Info Target Siswa Terpilih --}}
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <div class="w-12 h-12 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 overflow-hidden shrink-0">
                                    <template x-if="selectedStudent && selectedStudent.photo">
                                        <img :src="selectedStudent.photo" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!selectedStudent || !selectedStudent.photo">
                                        <i class="ph-bold ph-user text-2xl"></i>
                                    </template>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-sky-400 uppercase tracking-wider">Target Siswa:</p>
                                    <h4 class="text-sm font-black text-white truncate" x-text="selectedStudent ? selectedStudent.name : 'Pilih Siswa di Kolom Kanan'"></h4>
                                    <p class="text-[11px] text-slate-400" x-text="selectedStudent ? (selectedStudent.nisn || selectedStudent.student_id) + ' • ' + selectedStudent.class_name : 'Belum ada siswa terpilih'"></p>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                <button type="button" @click="toggleCamera()" class="px-4 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs flex items-center gap-2 border border-slate-700 transition-all">
                                    <i class="ph-bold ph-arrows-clockwise text-base"></i>
                                    <span x-text="cameraActive ? 'Restart Cam' : 'Buka Cam'"></span>
                                </button>

                                <button type="button" id="btn-capture" @click="startCaptureProcess()" :disabled="!canCapture" class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-lg shadow-sky-500/25 transition-all active:scale-95">
                                    <i class="ph-bold ph-camera-plus text-lg"></i>
                                    <span>Rekam Wajah</span>
                                </button>
                            </div>
                        </div>

                        {{-- FITUR EXTRA: Ekstrak dari Foto Profil Lama --}}
                        <div x-show="selectedStudent && selectedStudent.photo" class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-400">Siswa ini memiliki foto profil resmi di database.</span>
                            <button type="button" @click="extractFromProfilePhoto()" class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1.5 underline underline-offset-4">
                                <i class="ph-bold ph-magic-wand"></i> Ekstrak dari Foto Profil
                            </button>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (5 KANVAS): DAFTAR SISWA & FILTER --}}
                <div class="lg:col-span-5 flex flex-col gap-4">
                    
                    <div class="bg-slate-900/80 border border-slate-800 rounded-[2rem] p-6 shadow-2xl flex flex-col h-[700px]">
                        
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-800">
                            <div>
                                <h3 class="text-base font-black text-white">Daftar Antrean Siswa</h3>
                                <p class="text-xs text-slate-400">Pilih siswa untuk direkam wajahnya</p>
                            </div>
                            <div class="text-xs bg-slate-800 px-3 py-1 rounded-lg text-slate-300 font-bold border border-slate-700">
                                <span x-text="studentsList.length"></span> Siswa
                            </div>
                        </div>

                        {{-- FILTER KELAS & SEARCH --}}
                        <div class="space-y-3 mb-4">
                            <form method="GET" action="{{ route('face-recognition.index') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <select name="class_id" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:ring-sky-500 focus:border-sky-500 font-bold">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }}</option>
                                    @endforeach
                                </select>

                                <select name="status" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:ring-sky-500 focus:border-sky-500 font-bold">
                                    <option value="">Semua Status</option>
                                    <option value="unregistered" {{ $statusFilter == 'unregistered' ? 'selected' : '' }}>Belum Direkam</option>
                                    <option value="registered" {{ $statusFilter == 'registered' ? 'selected' : '' }}>Sudah Direkam</option>
                                </select>
                            </form>

                            <div class="relative">
                                <input type="text" x-model="searchQuery" placeholder="Cari nama siswa / NISN..." class="w-full bg-slate-950 border border-slate-700 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white placeholder-slate-500 focus:ring-sky-500 focus:border-sky-500">
                                <i class="ph-bold ph-magnifying-glass absolute left-3 top-3 text-slate-500 text-sm"></i>
                            </div>
                        </div>

                        {{-- DAFTAR ITEM SISWA (SCROLLABLE) --}}
                        <div class="flex-1 overflow-y-auto space-y-2 pr-1 custom-scrollbar">
                            <template x-for="student in filteredStudents" :key="student.id">
                                <div @click="selectStudent(student)" 
                                     :class="{
                                         'border-sky-500 bg-sky-500/10 shadow-lg shadow-sky-500/10': selectedStudent && selectedStudent.id === student.id,
                                         'border-slate-800 bg-slate-950/60 hover:bg-slate-800/60': !selectedStudent || selectedStudent.id !== student.id
                                     }" 
                                     class="p-3.5 rounded-2xl border transition-all cursor-pointer flex items-center justify-between group">
                                    
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-xl bg-slate-800 border border-slate-700 overflow-hidden shrink-0 flex items-center justify-center text-slate-400">
                                            <template x-if="student.photo">
                                                <img :src="student.photo" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!student.photo">
                                                <span class="text-sm font-black" x-text="student.name.charAt(0)"></span>
                                            </template>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-xs font-bold text-white group-hover:text-sky-300 transition-colors truncate" x-text="student.name"></h4>
                                            <p class="text-[10px] text-slate-400 font-mono mt-0.5" x-text="(student.nisn || student.student_id) + ' • ' + (student.class_name || '-')"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <template x-if="student.has_face">
                                            <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                                <i class="ph-fill ph-check-circle"></i> Terdaftar
                                            </span>
                                        </template>
                                        <template x-if="!student.has_face">
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                                                Belum
                                            </span>
                                        </template>

                                        {{-- Tombol Hapus Biometrik jika sudah ada --}}
                                        <template x-if="student.has_face">
                                            <button type="button" @click.stop="deleteFace(student)" class="w-7 h-7 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 flex items-center justify-center border border-rose-500/20 transition-all" title="Hapus Biometrik">
                                                <i class="ph-bold ph-trash text-xs"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="filteredStudents.length === 0">
                                <div class="text-center py-12 text-slate-500">
                                    <i class="ph-duotone ph-user-circle text-4xl mb-2"></i>
                                    <p class="text-xs font-bold">Tidak ada data siswa ditemukan.</p>
                                </div>
                            </template>
                        </div>

                        {{-- Pagination link jika ada --}}
                        <div class="mt-4 pt-3 border-t border-slate-800 text-xs">
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- LOGIKA ALPINE.JS & FACE-API.JS --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('faceEnrollmentApp', () => ({
                studentsList: @json($students->items()).map(s => ({
                    id: s.id,
                    student_id: s.student_id,
                    nisn: s.nisn,
                    name: s.name,
                    class_name: s.school_class ? s.school_class.name : '-',
                    photo: s.photo_path ? (s.photo_path.startsWith('http') ? s.photo_path : '/storage/' + s.photo_path) : null,
                    has_face: !!s.face_descriptor
                })),
                searchQuery: '',
                selectedStudent: null,
                cameraActive: false,
                modelsLoaded: false,
                isDetecting: false,
                currentDetection: null,
                canCapture: false,

                get filteredStudents() {
                    if (!this.searchQuery) return this.studentsList;
                    const q = this.searchQuery.toLowerCase();
                    return this.studentsList.filter(s => 
                        s.name.toLowerCase().includes(q) || 
                        (s.nisn && s.nisn.toLowerCase().includes(q)) || 
                        (s.student_id && s.student_id.toLowerCase().includes(q))
                    );
                },

                async init() {
                    // Otomatis pilih siswa pertama yang belum direkam
                    const firstUnregistered = this.studentsList.find(s => !s.has_face);
                    this.selectedStudent = firstUnregistered || this.studentsList[0] || null;

                    // Muat Model Face-API dari folder /models lokal
                    await this.loadFaceModels();

                    // Buka Kamera
                    await this.startCamera();
                },

                async loadFaceModels() {
                    const statusEl = document.getElementById('ai-model-status');
                    try {
                        const MODEL_URL = '{{ asset("models") }}';
                        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);

                        this.modelsLoaded = true;
                        statusEl.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold border flex items-center gap-2 bg-emerald-500/10 border-emerald-500/30 text-emerald-400';
                        statusEl.innerHTML = '<i class="ph-bold ph-check-circle"></i><span>Model AI Siap</span>';
                    } catch (err) {
                        console.error('Gagal memuat model:', err);
                        statusEl.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold border flex items-center gap-2 bg-rose-500/10 border-rose-500/30 text-rose-400';
                        statusEl.innerHTML = '<i class="ph-bold ph-warning-circle"></i><span>Gagal Muat AI</span>';
                    }
                },

                async startCamera() {
                    const video = document.getElementById('webcam-video');
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
                        });
                        video.srcObject = stream;
                        this.cameraActive = true;

                        video.onloadedmetadata = () => {
                            video.play();
                            this.startDetectionLoop();
                        };
                    } catch (err) {
                        console.error('Akses kamera gagal:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kamera Tidak Dapat Diakses',
                            text: 'Pastikan izin akses kamera telah diberikan di browser Anda.',
                            background: '#0f172a',
                            color: '#fff'
                        });
                    }
                },

                toggleCamera() {
                    const video = document.getElementById('webcam-video');
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                        video.srcObject = null;
                        this.cameraActive = false;
                    } else {
                        this.startCamera();
                    }
                },

                selectStudent(student) {
                    this.selectedStudent = student;
                    this.updateCaptureButtonState();
                },

                startDetectionLoop() {
                    const video = document.getElementById('webcam-video');
                    const canvas = document.getElementById('overlay-canvas');
                    const banner = document.getElementById('detection-banner');

                    setInterval(async () => {
                        if (!this.modelsLoaded || !this.cameraActive || video.paused || video.ended) return;

                        // Sesuaikan ukuran kanvas dengan video
                        const displaySize = { width: video.videoWidth || 640, height: video.videoHeight || 480 };
                        faceapi.matchDimensions(canvas, displaySize);

                        // Deteksi wajah tunggal dengan landmarks & descriptor
                        const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        const ctx = canvas.getContext('2d');
                        ctx.clearRect(0, 0, canvas.width, canvas.height);

                        if (detection) {
                            this.currentDetection = detection;
                            this.updateCaptureButtonState();

                            // Gambar kotak deteksi halus
                            const resized = faceapi.resizeResults(detection, displaySize);
                            const box = resized.detection.box;
                            
                            ctx.strokeStyle = '#38bdf8';
                            ctx.lineWidth = 3;
                            ctx.strokeRect(box.x, box.y, box.width, box.height);

                            banner.className = 'bg-emerald-950/90 backdrop-blur-md px-4 py-2 rounded-xl border border-emerald-500/40 text-xs font-black text-emerald-300 flex items-center gap-2 shadow-lg';
                            banner.innerHTML = '<i class="ph-fill ph-check-circle text-base text-emerald-400"></i> Wajah Terdeteksi Siap Direkam!';
                        } else {
                            this.currentDetection = null;
                            this.updateCaptureButtonState();

                            banner.className = 'bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-xs font-bold text-slate-400 flex items-center gap-2 shadow-lg';
                            banner.innerHTML = '<i class="ph-bold ph-scan text-sky-400 animate-pulse text-base"></i> Hadapkan wajah tegak ke kamera...';
                        }
                    }, 200);
                },

                updateCaptureButtonState() {
                    this.canCapture = !!(this.selectedStudent && this.currentDetection);
                },

                async startCaptureProcess() {
                    if (!this.canCapture) return;

                    const overlay = document.getElementById('countdown-overlay');
                    const numberEl = document.getElementById('countdown-number');
                    const video = document.getElementById('webcam-video');

                    overlay.classList.remove('hidden');

                    for (let i = 2; i >= 1; i--) {
                        numberEl.textContent = i;
                        await new Promise(r => setTimeout(r, 600));
                    }

                    overlay.classList.add('hidden');

                    // Ambil Frame Foto saat ini sebagai base64 preview
                    const snapCanvas = document.createElement('canvas');
                    snapCanvas.width = video.videoWidth || 640;
                    snapCanvas.height = video.videoHeight || 480;
                    const sCtx = snapCanvas.getContext('2d');
                    // Cermin balik agar orientasi normal
                    sCtx.translate(snapCanvas.width, 0);
                    sCtx.scale(-1, 1);
                    sCtx.drawImage(video, 0, 0, snapCanvas.width, snapCanvas.height);
                    const imageBase64 = snapCanvas.toDataURL('image/jpeg', 0.85);

                    // Kirim Descriptor ke Server
                    await this.saveDescriptorToServer(this.currentDetection.descriptor, imageBase64);
                },

                async extractFromProfilePhoto() {
                    if (!this.selectedStudent || !this.selectedStudent.photo) return;

                    Swal.fire({
                        title: 'Mengekstrak Wajah...',
                        text: 'Memproses foto profil siswa melalui model AI...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                        background: '#0f172a',
                        color: '#fff'
                    });

                    try {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.src = this.selectedStudent.photo;
                        await new Promise((resolve, reject) => {
                            img.onload = resolve;
                            img.onerror = () => reject('Gagal memuat foto profil');
                        });

                        const detection = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({ inputSize: 320 }))
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        if (!detection) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Wajah Tidak Terdeteksi',
                                text: 'Model AI tidak dapat mendeteksi wajah dengan jelas pada foto profil ini. Silakan gunakan webcam langsung.',
                                background: '#0f172a',
                                color: '#fff'
                            });
                            return;
                        }

                        // Simpan descriptor dari foto
                        await this.saveDescriptorToServer(detection.descriptor, null);

                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ekstraksi Gagal',
                            text: e.toString(),
                            background: '#0f172a',
                            color: '#fff'
                        });
                    }
                },

                async saveDescriptorToServer(descriptorArray, imageBase64) {
                    Swal.fire({
                        title: 'Menyimpan Biometrik...',
                        text: 'Menyimpan 128-vektor wajah ke database...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                        background: '#0f172a',
                        color: '#fff'
                    });

                    try {
                        const payload = {
                            student_id: this.selectedStudent.id,
                            descriptor: Array.from(descriptorArray),
                            image: imageBase64
                        };

                        const res = await fetch('{{ route("face-recognition.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await res.json();

                        if (res.ok) {
                            // Tandai siswa ini sebagai sudah memiliki wajah
                            this.selectedStudent.has_face = true;
                            if (data.student && data.student.photo_path) {
                                this.selectedStudent.photo = data.student.photo_path;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false,
                                background: '#0f172a',
                                color: '#fff'
                            });

                            // AUTO-ADVANCE: Alihkan ke siswa berikutnya yang belum terdaftar!
                            const nextStudent = this.studentsList.find(s => !s.has_face);
                            if (nextStudent) {
                                this.selectedStudent = nextStudent;
                            }

                        } else {
                            throw new Error(data.message || 'Gagal menyimpan.');
                        }
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: err.message,
                            background: '#0f172a',
                            color: '#fff'
                        });
                    }
                },

                async deleteFace(student) {
                    const result = await Swal.fire({
                        title: 'Hapus Biometrik?',
                        text: `Yakin ingin menghapus data biometrik wajah siswa ${student.name}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#475569',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#0f172a',
                        color: '#fff'
                    });

                    if (result.isConfirmed) {
                        try {
                            const res = await fetch(`/face-recognition/${student.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await res.json();
                            if (res.ok) {
                                student.has_face = false;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    background: '#0f172a',
                                    color: '#fff'
                                });
                            }
                        } catch (err) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                        }
                    }
                }
            }));
        });
    </script>
</x-app-layout>
