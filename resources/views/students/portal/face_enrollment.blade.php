@extends('layouts.public')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/face-api.min.js') }}"></script>

    @php
        $faceStatus = $student->face_status ?? 'unregistered';
        $hasFaceDescriptor = !empty($student->face_descriptor);
        $isLocked = $hasFaceDescriptor && in_array($faceStatus, ['pending', 'verified']);
        $displayPhoto = $student->face_photo_path ?? $student->photo_path;
    @endphp

    <div class="py-8 sm:py-12 bg-[#020b18] text-slate-100 min-h-screen font-sans relative overflow-hidden">
        {{-- Ambient Glow --}}
        <div class="bg-gradient-to-b from-[#0d52a1]/25 via-[#031d3d]/15 to-transparent opacity-40 pointer-events-none -z-10 blur-3xl absolute inset-0"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- TOMBOL KEMBALI --}}
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('portal.show', $student->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/10 text-xs font-bold transition-all shadow-sm">
                    <i class="ph-bold ph-arrow-left text-sky-400"></i>
                    <span>Kembali ke Portal Siswa</span>
                </a>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-500/10 border border-sky-400/30 text-sky-300 text-[11px] font-bold">
                    <i class="ph-fill ph-scan-smiley"></i> Biometrik Kiosk & Scan
                </span>
            </div>

            {{-- HEADER --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#0d52a1]/20 border border-sky-400/30 text-sky-400 mb-4 shadow-lg backdrop-blur-md">
                    <i class="ph-duotone ph-user-focus text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Rekam Mandiri Face Recognition</h1>
                <p class="text-slate-400 text-sm mt-2 max-w-lg mx-auto font-medium">
                    Daftarkan biometrik wajah Anda agar dapat melakukan presensi cepat di Mesin Kiosk & Scanner Sekolah.
                </p>
            </div>

            {{-- ALERT JIKA DITOLAK / DIHAPUS ADMIN --}}
            @if($faceStatus === 'rejected')
                <div class="mb-6 p-5 bg-rose-500/10 border border-rose-500/30 text-rose-200 rounded-2xl flex items-start gap-3.5 shadow-xl backdrop-blur-md">
                    <i class="ph-fill ph-warning-octagon text-rose-400 text-2xl shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="font-black text-rose-300 text-sm uppercase tracking-wider">Data Wajah / Foto Sebelumnya Dihapus oleh Admin/Guru</h4>
                        <p class="text-xs text-rose-200/90 mt-1 leading-relaxed">
                            {{ $student->face_rejection_note ?: 'Foto atau rekaman wajah tidak sesuai ketentuan sekolah. Silakan lakukan perekaman ulang dengan wajah terlihat jelas dan berseragam rapi.' }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- CARD UTAMA --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-white/10 overflow-hidden relative backdrop-blur-xl">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-[#56bbf1] to-[#0d52a1]"></div>

                <div class="p-6 sm:p-10">

                    {{-- IDENTITAS SISWA --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-slate-900/60 rounded-2xl border border-white/10 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#0d52a1] text-white flex items-center justify-center font-bold text-xl shrink-0 border border-sky-400/30 overflow-hidden">
                                @if($displayPhoto)
                                    <img src="{{ asset('storage/' . $displayPhoto) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($student->name ?? 'S', 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Identitas Siswa:</p>
                                <h3 class="font-black text-white text-base sm:text-lg leading-tight">{{ $student->name }}</h3>
                                <p class="text-xs text-sky-400 font-bold mt-0.5">
                                    Kelas {{ $student->schoolClass->name ?? '-' }} &bull; NISN: <span class="font-mono">{{ $student->student_id }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- STATUS BADGE --}}
                        <div class="shrink-0">
                            @if($faceStatus === 'verified' && $hasFaceDescriptor)
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                    <i class="ph-fill ph-seal-check text-base"></i> Terverifikasi
                                </span>
                            @elseif($faceStatus === 'pending' && $hasFaceDescriptor)
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                    <i class="ph-fill ph-clock-countdown text-base"></i> Aktif (Menunggu Verifikasi)
                                </span>
                            @elseif($faceStatus === 'rejected')
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-rose-500/20 text-rose-300 border border-rose-400/30">
                                    <i class="ph-fill ph-x-circle text-base"></i> Perlu Rekam Ulang
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-slate-800 text-slate-300 border border-white/10">
                                    <i class="ph-fill ph-scan text-base text-sky-400"></i> Belum Terdaftar
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($isLocked)
                        {{-- TAMPILAN JIKA SUDAH DIREKAM (PENDING ATAU VERIFIED) --}}
                        <div class="text-center py-6 space-y-6">
                            <div class="relative w-40 h-40 mx-auto rounded-3xl overflow-hidden border-2 {{ $faceStatus === 'verified' ? 'border-emerald-400/50 shadow-[0_0_30px_rgba(16,185,129,0.25)]' : 'border-amber-400/50 shadow-[0_0_30px_rgba(245,158,11,0.25)]' }} bg-slate-900">
                                @if($displayPhoto)
                                    <img src="{{ asset('storage/' . $displayPhoto) }}" alt="Foto Wajah Terdaftar" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl text-sky-400">
                                        <i class="ph-duotone ph-user-focus"></i>
                                    </div>
                                @endif
                                <div class="absolute bottom-2 inset-x-2 px-2 py-1 rounded-lg bg-slate-950/80 backdrop-blur-md text-[10px] font-black uppercase tracking-wider {{ $faceStatus === 'verified' ? 'text-emerald-300' : 'text-amber-300' }}">
                                    {{ $faceStatus === 'verified' ? 'Resmi Terverifikasi' : 'Siap Digunakan' }}
                                </div>
                            </div>

                            @if($faceStatus === 'verified')
                                <div>
                                    <h3 class="text-xl font-black text-white">Face Recognition Telah Terverifikasi</h3>
                                    <p class="text-sm text-slate-400 mt-2 max-w-md mx-auto leading-relaxed">
                                        Data biometrik wajah Anda telah disetujui oleh Wali Kelas / Admin dan aktif digunakan pada mesin Kiosk maupun Scanner Sekolah.
                                    </p>
                                </div>
                            @else
                                <div class="max-w-lg mx-auto p-4 rounded-2xl bg-amber-500/10 border border-amber-400/30 text-left flex gap-3.5">
                                    <i class="ph-fill ph-info text-amber-400 text-2xl shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-black text-amber-300 text-sm">Face ID Anda Sudah Aktif & Bisa Digunakan!</h4>
                                        <p class="text-xs text-amber-100/80 mt-1 leading-relaxed">
                                            Walaupun statusnya masih <b>Menunggu Verifikasi Wali Kelas / Admin</b>, Anda <b>sudah dapat langsung menggunakan absensi wajah</b> di halaman Kiosk dan Scan sekarang.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/10 max-w-md mx-auto text-xs text-slate-400">
                                <i class="ph-bold ph-lock-key text-sky-400 mr-1"></i>
                                Demi keamanan presensi, perekaman ulang dikunci. Jika foto salah atau ingin mengganti data wajah, silakan minta <b>Wali Kelas atau Admin</b> untuk mereset Face ID Anda.
                            </div>
                        </div>
                    @else
                        {{-- FORM PEREKAMAN MANDIRI --}}
                        <div class="space-y-6">
                            {{-- Panduan Singkat --}}
                            <div class="bg-sky-500/10 border border-sky-400/30 rounded-2xl p-4 flex gap-3.5">
                                <i class="ph-fill ph-lightbulb text-sky-400 text-xl shrink-0 mt-0.5"></i>
                                <div class="text-xs text-sky-100/90 space-y-1">
                                    <p class="font-black text-sky-300 uppercase tracking-wider">Panduan Perekaman Wajah:</p>
                                    <ul class="list-disc list-inside space-y-0.5 text-slate-300">
                                        <li>Pastikan wajah terlihat jelas, pencahayaan cukup, serta tidak memakai masker/topi.</li>
                                        <li>Posisikan wajah di tengah bingkai kamera hingga sistem selesai memindai <b>3 sampel wajah</b>.</li>
                                        <li>Setelah disimpan, Face ID langsung aktif di Kiosk/Scan dan akan diverifikasi oleh Wali Kelas/Admin.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- STEP 1: KONFIRMASI IDENTITAS --}}
                            <div>
                                <label for="security_code" class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2">
                                    1. Konfirmasi NISN atau NIK Anda <span class="text-rose-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-shield-check text-lg"></i>
                                    </div>
                                    <input type="text" id="security_code" value="{{ $student->student_id }}"
                                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-white/15 bg-slate-900/90 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 font-mono font-bold text-white text-sm"
                                           placeholder="Masukkan NISN atau NIK Anda">
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1 ml-1">Digunakan untuk memastikan bahwa perekaman dilakukan oleh pemilik akun.</p>
                            </div>

                            {{-- STEP 2: AREA KAMERA & PEMINDAIAN WAJAH --}}
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                    <label class="block text-xs font-black text-sky-400 uppercase tracking-wider">
                                        2. Pemindaian Biometrik Wajah <span class="text-rose-400">*</span>
                                    </label>
                                    <span id="ai-model-status" class="text-[11px] font-bold text-amber-300 bg-amber-500/10 border border-amber-400/30 px-3 py-1 rounded-full flex items-center gap-1.5">
                                        <i class="ph-bold ph-circle-notch animate-spin"></i> Memuat Model AI Wajah...
                                    </span>
                                </div>

                                <div class="relative bg-slate-950 rounded-[2rem] border-2 border-white/15 overflow-hidden aspect-[4/3] max-h-[420px] w-full mx-auto flex items-center justify-center shadow-inner">
                                    <video id="face-video" autoplay muted playsinline class="w-full h-full object-cover hidden transform -scale-x-100"></video>
                                    <img id="captured-preview" class="w-full h-full object-cover hidden" alt="Hasil Capture Wajah">

                                    {{-- Panduan Oval Wajah --}}
                                    <div id="face-oval-guide" class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center hidden">
                                        <div id="oval-ring" class="w-48 h-60 sm:w-56 sm:h-72 rounded-full border-4 border-dashed border-sky-400/70 transition-all duration-300 shadow-[0_0_40px_rgba(56,189,248,0.2)]"></div>
                                        <p id="scan-instruction" class="mt-3 px-4 py-1.5 rounded-full bg-slate-950/85 border border-white/15 text-xs font-black text-sky-300 uppercase tracking-wider shadow-lg">
                                            Arahkan Wajah ke Dalam Bingkai
                                        </p>
                                    </div>

                                    {{-- Placeholder Saat Kamera Belum Aktif --}}
                                    <div id="camera-placeholder" class="text-center p-6 space-y-4">
                                        <div class="w-20 h-20 rounded-3xl bg-sky-500/10 border border-sky-400/30 flex items-center justify-center mx-auto text-sky-400 text-4xl">
                                            <i class="ph-duotone ph-camera"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white text-base">Kamera Siap Digunakan</h4>
                                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Klik tombol <b>Mulai Kamera Wajah</b> di bawah untuk memindai wajah secara otomatis.</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Progress Bar 3 Sampel --}}
                                <div class="mt-4 bg-slate-900/80 p-3.5 rounded-2xl border border-white/10">
                                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                        <span class="text-slate-300">Progres Akuisisi Sampel Wajah:</span>
                                        <span id="sample-counter" class="text-sky-400 font-mono">0 / 3 Sampel</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-slate-950 rounded-full overflow-hidden">
                                        <div id="sample-progress" class="h-full bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                </div>

                                {{-- Tombol Kontrol Kamera --}}
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
                                    <button type="button" id="btn-start-camera" onclick="startEnrollmentCamera()"
                                            class="py-3 px-4 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 border border-sky-400/30 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition-all active:scale-95">
                                        <i class="ph-bold ph-video-camera text-base"></i> Mulai Kamera Wajah
                                    </button>

                                    <button type="button" id="btn-switch-camera" onclick="flipEnrollmentCamera()"
                                            class="py-3 px-4 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition-all active:scale-95">
                                        <i class="ph-bold ph-camera-rotate text-base"></i> Putar Kamera
                                    </button>

                                    <button type="button" onclick="document.getElementById('fallback-photo-input').click()"
                                            class="py-3 px-4 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition-all active:scale-95">
                                        <i class="ph-bold ph-image text-base"></i> Pilih / Ambil Foto
                                    </button>
                                    <input type="file" id="fallback-photo-input" accept="image/*" capture="user" class="hidden" onchange="handlePhotoUpload(event)">
                                </div>
                            </div>

                            {{-- TOMBOL SIMPAN --}}
                            <div class="border-t border-white/10 pt-6">
                                <button type="button" id="btn-submit-face" onclick="submitFaceEnrollment()" disabled
                                        class="w-full py-4 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white font-black rounded-2xl shadow-xl shadow-sky-950/50 transition-all flex items-center justify-center gap-2 text-base border border-sky-400/40 opacity-50 cursor-not-allowed">
                                    <i class="ph-bold ph-check-circle text-xl"></i>
                                    <span>Simpan & Aktifkan Face ID Saya</span>
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if(!$isLocked)
    <script>
        const MODEL_URL = "{{ asset('models') }}";
        let modelsLoaded = false;
        let videoStream = null;
        let facingMode = 'user';
        let collectedDescriptors = [];
        let finalDescriptor = null;
        let capturedPhotoDataUrl = null;
        let scanInterval = null;

        async function loadFaceModels() {
            const statusEl = document.getElementById('ai-model-status');
            try {
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);
                modelsLoaded = true;
                if (statusEl) {
                    statusEl.className = 'text-[11px] font-bold text-emerald-300 bg-emerald-500/15 border border-emerald-400/30 px-3 py-1 rounded-full flex items-center gap-1.5';
                    statusEl.innerHTML = '<i class="ph-fill ph-check-circle"></i> AI Face Engine Siap';
                }
            } catch (err) {
                console.error('Gagal memuat model AI:', err);
                if (statusEl) {
                    statusEl.className = 'text-[11px] font-bold text-rose-300 bg-rose-500/15 border border-rose-400/30 px-3 py-1 rounded-full flex items-center gap-1.5';
                    statusEl.innerHTML = '<i class="ph-fill ph-warning"></i> Gagal Memuat AI (Cek Internet)';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadFaceModels();
        });

        async function startEnrollmentCamera() {
            if (!modelsLoaded) {
                await loadFaceModels();
                if (!modelsLoaded) return;
            }

            stopEnrollmentCamera();
            collectedDescriptors = [];
            finalDescriptor = null;
            capturedPhotoDataUrl = null;
            updateSampleProgress(0);
            setSubmitReady(false);

            const video = document.getElementById('face-video');
            const preview = document.getElementById('captured-preview');
            const placeholder = document.getElementById('camera-placeholder');
            const guide = document.getElementById('face-oval-guide');

            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: facingMode, width: { ideal: 640 }, height: { ideal: 480 } },
                    audio: false
                });
                video.srcObject = videoStream;
                video.classList.toggle('-scale-x-100', facingMode === 'user');
                await video.play();

                placeholder.classList.add('hidden');
                preview.classList.add('hidden');
                video.classList.remove('hidden');
                guide.classList.remove('hidden');

                startContinuousFaceScan();
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Dapat Diakses',
                    text: 'Pastikan izin kamera diberikan, atau gunakan tombol "Pilih / Ambil Foto".',
                    background: '#021124',
                    color: '#fff'
                });
            }
        }

        function stopEnrollmentCamera() {
            if (scanInterval) {
                clearInterval(scanInterval);
                scanInterval = null;
            }
            if (videoStream) {
                videoStream.getTracks().forEach(t => t.stop());
                videoStream = null;
            }
        }

        function flipEnrollmentCamera() {
            facingMode = facingMode === 'user' ? 'environment' : 'user';
            startEnrollmentCamera();
        }

        function updateSampleProgress(count) {
            const pct = Math.min(100, Math.round((count / 3) * 100));
            document.getElementById('sample-counter').textContent = `${count} / 3 Sampel`;
            document.getElementById('sample-progress').style.width = `${pct}%`;
        }

        function setSubmitReady(ready) {
            const btn = document.getElementById('btn-submit-face');
            if (!btn) return;
            btn.disabled = !ready;
            btn.classList.toggle('opacity-50', !ready);
            btn.classList.toggle('cursor-not-allowed', !ready);
            btn.classList.toggle('hover:scale-[1.01]', ready);
        }

        function startContinuousFaceScan() {
            const video = document.getElementById('face-video');
            const instruction = document.getElementById('scan-instruction');
            const ovalRing = document.getElementById('oval-ring');

            scanInterval = setInterval(async () => {
                if (!video || video.paused || video.ended || collectedDescriptors.length >= 3) return;

                try {
                    const detections = await faceapi
                        .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.6 }))
                        .withFaceLandmarks()
                        .withFaceDescriptors();

                    if (detections.length === 0) {
                        instruction.textContent = 'Wajah Belum Terdeteksi — Posisikan di Tengah';
                        ovalRing.className = 'w-48 h-60 sm:w-56 sm:h-72 rounded-full border-4 border-dashed border-amber-400/70 transition-all duration-300';
                        return;
                    }

                    if (detections.length > 1) {
                        instruction.textContent = 'Terdeteksi Lebih dari 1 Wajah! Harap Sendiri';
                        ovalRing.className = 'w-48 h-60 sm:w-56 sm:h-72 rounded-full border-4 border-dashed border-rose-500 transition-all duration-300';
                        return;
                    }

                    const det = detections[0];
                    if (det.detection.score < 0.72) {
                        instruction.textContent = 'Pencahayaan Kurang Jelas — Tahan Posisi';
                        return;
                    }

                    collectedDescriptors.push(Array.from(det.descriptor));
                    updateSampleProgress(collectedDescriptors.length);
                    ovalRing.className = 'w-48 h-60 sm:w-56 sm:h-72 rounded-full border-4 border-emerald-400 shadow-[0_0_40px_rgba(16,185,129,0.4)] transition-all duration-300';

                    if (collectedDescriptors.length < 3) {
                        instruction.textContent = `Sampel ${collectedDescriptors.length}/3 Berhasil! Tahan Sebentar...`;
                    } else {
                        instruction.textContent = 'Pemindaian Wajah Selesai (3/3)!';
                        finalizeCapturedSamples(video);
                    }
                } catch (e) {
                    console.warn('Scan loop error:', e);
                }
            }, 700);
        }

        function finalizeCapturedSamples(sourceEl) {
            // Rata-ratakan 3 sampel descriptor agar lebih akurat
            const averaged = new Array(128).fill(0);
            collectedDescriptors.forEach(desc => {
                for (let i = 0; i < 128; i++) {
                    averaged[i] += desc[i] / collectedDescriptors.length;
                }
            });
            finalDescriptor = averaged;

            // Ambil foto frame terbaik
            const canvas = document.createElement('canvas');
            canvas.width = 480;
            canvas.height = 360;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(sourceEl, 0, 0, canvas.width, canvas.height);
            capturedPhotoDataUrl = canvas.toDataURL('image/jpeg', 0.85);

            stopEnrollmentCamera();

            const video = document.getElementById('face-video');
            const preview = document.getElementById('captured-preview');
            video.classList.add('hidden');
            preview.src = capturedPhotoDataUrl;
            preview.classList.remove('hidden');

            setSubmitReady(true);
        }

        async function handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!modelsLoaded) {
                await loadFaceModels();
                if (!modelsLoaded) return;
            }

            stopEnrollmentCamera();

            Swal.fire({
                title: 'Memindai Wajah pada Foto...',
                allowOutsideClick: false,
                background: '#021124',
                color: '#fff',
                didOpen: () => Swal.showLoading()
            });

            const reader = new FileReader();
            reader.onload = async (e) => {
                const img = new Image();
                img.onload = async () => {
                    try {
                        const det = await faceapi
                            .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.5 }))
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        if (!det) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Wajah Tidak Terdeteksi',
                                text: 'Pastikan foto menampilkan 1 wajah menghadap depan dengan jelas.',
                                background: '#021124',
                                color: '#fff'
                            });
                            return;
                        }

                        finalDescriptor = Array.from(det.descriptor);

                        const canvas = document.createElement('canvas');
                        canvas.width = 480;
                        canvas.height = 360;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        capturedPhotoDataUrl = canvas.toDataURL('image/jpeg', 0.85);

                        document.getElementById('camera-placeholder').classList.add('hidden');
                        document.getElementById('face-video').classList.add('hidden');
                        const preview = document.getElementById('captured-preview');
                        preview.src = capturedPhotoDataUrl;
                        preview.classList.remove('hidden');

                        updateSampleProgress(3);
                        setSubmitReady(true);
                        Swal.close();
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memproses Foto',
                            text: 'Silakan coba foto lain yang lebih jelas.',
                            background: '#021124',
                            color: '#fff'
                        });
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        async function submitFaceEnrollment() {
            const securityCode = document.getElementById('security_code').value.trim();
            if (!securityCode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Identitas',
                    text: 'Masukkan NISN atau NIK Anda terlebih dahulu.',
                    background: '#021124',
                    color: '#fff'
                });
                return;
            }

            if (!finalDescriptor || !capturedPhotoDataUrl) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Ada Sampel Wajah',
                    text: 'Silakan lakukan pemindaian kamera terlebih dahulu.',
                    background: '#021124',
                    color: '#fff'
                });
                return;
            }

            Swal.fire({
                title: 'Menyimpan Data Biometrik...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                background: '#021124',
                color: '#fff',
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch("{{ route('student.face.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        security_code: securityCode,
                        descriptor: JSON.stringify(finalDescriptor),
                        photo: capturedPhotoDataUrl
                    })
                });

                const data = await res.json();
                if (res.ok && data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Face ID Aktif!',
                        text: data.message,
                        background: '#021124',
                        color: '#fff',
                        confirmButtonColor: '#0d52a1'
                    }).then(() => {
                        window.location.href = data.redirect_url || "{{ route('portal.show', $student->id) }}";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: data.message || 'Terjadi kesalahan saat menyimpan data wajah.',
                        background: '#021124',
                        color: '#fff'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Tidak dapat terhubung ke server.',
                    background: '#021124',
                    color: '#fff'
                });
            }
        }
    </script>
    @endif
@endsection
