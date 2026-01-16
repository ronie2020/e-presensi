<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <?php $__env->startPush('styles'); ?>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .animate-enter { animation: fadeUp 0.3s ease-out; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Style untuk Kamera */
        #reader { width: 100%; border-radius: 1rem; overflow: hidden; }
        #reader video { object-fit: cover; border-radius: 1rem; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-6 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="relative rounded-[2rem] bg-gradient-to-r from-indigo-900 to-slate-900 p-6 mb-8 text-white shadow-xl overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight mb-1 flex items-center gap-3">
                            <i class="ph-duotone ph-shield-check text-indigo-400"></i>
                            Pos Guru Piket
                        </h2>
                        <p class="text-indigo-200 text-sm">Monitoring perizinan siswa keluar kelas real-time.</p>
                    </div>
                    <div class="hidden md:block text-right">
                        <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Petugas Jaga</div>
                        <div class="font-bold text-lg"><?php echo e(Auth::user()->name ?? 'Administrator'); ?></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-700 flex items-center gap-2">
                                <i class="ph-bold ph-qr-code text-indigo-600"></i> Scan Kartu
                            </h3>
                            
                            
                            <button onclick="toggleCamera()" id="btnCamera" class="text-xs font-bold px-3 py-1.5 bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 rounded-lg transition flex items-center gap-1">
                                <i class="ph-bold ph-camera"></i> <span id="cameraText">Buka Kamera</span>
                            </button>
                        </div>

                        
                        <div id="cameraContainer" class="hidden mb-4 relative bg-slate-900 rounded-2xl overflow-hidden shadow-inner">
                            <div id="reader" class="w-full"></div>
                            <div class="absolute bottom-4 left-0 right-0 text-center">
                                <span class="bg-black/50 text-white text-[10px] px-3 py-1 rounded-full backdrop-blur-sm">Arahkan QR Code ke kamera</span>
                            </div>
                        </div>
                        
                        
                        <div class="relative group">
                            <input type="text" id="scannerInput" 
                                class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-0 font-mono text-lg font-bold text-slate-700 transition-all placeholder:text-slate-300" 
                                placeholder="Scan Kartu / Ketik NIS..." autofocus autocomplete="off">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <i class="ph-bold ph-scan text-xl"></i>
                            </div>
                            <button id="btnSearch" class="absolute right-3 top-1/2 -translate-y-1/2 bg-indigo-100 text-indigo-700 p-2 rounded-lg hover:bg-indigo-200 transition cursor-pointer">
                                <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 ml-1">*Pastikan kursor aktif di kolom ini jika pakai scanner tembak.</p>
                        
                        <!-- Feedback Status -->
                        <div id="scanFeedback" class="hidden mt-3 p-3 rounded-xl text-center text-sm font-bold animate-pulse"></div>
                    </div>

                    <!-- Riwayat Singkat -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100 h-fit">
                        <h3 class="font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <i class="ph-duotone ph-clock-counter-clockwise text-indigo-600"></i> Baru Saja Kembali
                        </h3>
                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $todayHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                        <i class="ph-bold ph-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700"><?php echo e($history->student->name); ?></div>
                                        <div class="text-[10px] text-slate-500"><?php echo e($history->reason_category); ?> • <?php echo e($history->duration_minutes); ?> menit</div>
                                    </div>
                                </div>
                                <div class="text-xs font-mono text-slate-400">
                                    <?php echo e(\Carbon\Carbon::parse($history->time_in)->format('H:i')); ?>

                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4 text-slate-400 text-sm">Belum ada riwayat hari ini.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden min-h-[500px] flex flex-col">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                    <i class="ph-duotone ph-timer text-orange-500 text-xl"></i> Sedang Di Luar
                                </h3>
                                <p class="text-xs text-slate-500">Siswa yang belum kembali ke kelas.</p>
                            </div>
                            <span class="bg-orange-100 text-orange-600 py-1 px-3 rounded-full text-xs font-bold">
                                <?php echo e($activePermits->count()); ?> Siswa
                            </span>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $activePermits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="group relative bg-white p-4 rounded-2xl border-2 <?php echo e($permit->is_overdue ? 'border-rose-100 bg-rose-50/30' : 'border-slate-100'); ?> hover:shadow-md transition-all animate-enter">
                                <div class="flex justify-between items-start">
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 rounded-xl <?php echo e($permit->is_overdue ? 'bg-rose-100 text-rose-600' : 'bg-indigo-50 text-indigo-600'); ?> flex items-center justify-center text-xl font-bold">
                                            <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-base"><?php echo e($permit->student->name); ?></h4>
                                            <p class="text-xs text-slate-500 font-medium mb-1">
                                                <?php echo e($permit->student->schoolClass->name ?? 'Kelas -'); ?> • <?php echo e($permit->student->student_id); ?>

                                            </p>
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                                <?php echo e($permit->reason_category); ?>

                                            </div>
                                            <?php if($permit->notes): ?>
                                                <span class="text-[10px] text-slate-400 italic ml-2">"<?php echo e($permit->notes); ?>"</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-slate-400 font-medium mb-1">Durasi</div>
                                        <div class="text-2xl font-black font-mono <?php echo e($permit->is_overdue ? 'text-rose-600 animate-pulse' : 'text-slate-700'); ?>">
                                            <?php echo e($permit->minutes_elapsed); ?><span class="text-sm text-slate-400 font-normal">m</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-1">
                                            Keluar: <?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="flex flex-col items-center justify-center h-64 text-slate-400">
                                <i class="ph-duotone ph-student text-6xl mb-4 opacity-50"></i>
                                <p>Semua siswa ada di kelas.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="permitModal" class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl p-6 md:p-8 animate-enter relative transform scale-100 transition-transform duration-300">
            <button type="button" onclick="closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition cursor-pointer z-10">
                <i class="ph-bold ph-x text-2xl"></i>
            </button>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                    <i class="ph-duotone ph-door-open"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Izin Keluar Kelas</h3>
                <p id="modalStudentName" class="text-indigo-600 font-bold text-lg mt-1">Nama Siswa</p>
                <p id="modalStudentClass" class="text-sm text-slate-500">Kelas Siswa</p>
            </div>

            <form id="permitForm">
                <input type="hidden" id="modalStudentId" name="student_id">
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <?php $__currentLoopData = ['Toilet', 'UKS', 'Barang Tertinggal', 'Panggilan Guru', 'Dispensasi', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="cursor-pointer relative group">
                        <input type="radio" name="reason_category" value="<?php echo e($reason); ?>" class="peer sr-only">
                        <div class="p-3 rounded-xl border-2 border-slate-100 text-center text-sm font-bold text-slate-600 
                                    group-hover:bg-slate-50 group-hover:border-slate-300
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 
                                    transition-all duration-200">
                            <?php echo e($reason); ?>

                        </div>
                        <div class="absolute top-2 right-2 text-indigo-500 opacity-0 peer-checked:opacity-100 transition-opacity">
                            <i class="ph-fill ph-check-circle"></i>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Catatan Tambahan (Opsional)</label>
                    <input type="text" name="notes" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm" placeholder="Contoh: Sakit perut, dipanggil Bu Siti...">
                </div>

                <button type="button" onclick="submitPermitManual()" class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
                    <span>Izinkan Keluar</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    
    <script>
        const scannerInput = document.getElementById('scannerInput');
        const modal = document.getElementById('permitModal');
        const scanFeedback = document.getElementById('scanFeedback');
        const csrfToken = '<?php echo e(csrf_token()); ?>';
        
        // --- 1. LOGIKA SCANNER KAMERA ---
        let html5QrCode;
        let isCameraRunning = false;
        
        function toggleCamera() {
            const container = document.getElementById('cameraContainer');
            const btnText = document.getElementById('cameraText');
            
            if (isCameraRunning) {
                // Matikan Kamera
                html5QrCode.stop().then(() => {
                    container.classList.add('hidden');
                    btnText.textContent = "Buka Kamera";
                    isCameraRunning = false;
                    html5QrCode = null;
                }).catch(err => console.error(err));
            } else {
                // Nyalakan Kamera
                container.classList.remove('hidden');
                btnText.textContent = "Tutup Kamera";
                
                html5QrCode = new Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
                
                // Gunakan kamera belakang (environment)
                html5QrCode.start({ facingMode: "environment" }, config, onCameraSuccess)
                .then(() => {
                    isCameraRunning = true;
                })
                .catch(err => {
                    console.error("Error Camera:", err);
                    Swal.fire("Error", "Gagal mengakses kamera: " + err, "error");
                    container.classList.add('hidden');
                    btnText.textContent = "Buka Kamera";
                });
            }
        }
        
        // Callback saat QR Code terdeteksi kamera
        const onCameraSuccess = (decodedText, decodedResult) => {
            // Pause sebentar agar tidak double scan
            if(isCameraRunning) {
                html5QrCode.pause();
                handleScan(decodedText).then(() => {
                    // Resume setelah proses selesai (jika kamera masih aktif)
                    setTimeout(() => {
                        if(isCameraRunning) html5QrCode.resume();
                    }, 2000);
                });
            }
        };

        // --- 2. LOGIKA UMUM ---
        document.addEventListener('click', (e) => {
            const isNoteInput = e.target.closest('input[name="notes"]');
            const isModal = e.target.closest('#permitModal');
            const isBtnCamera = e.target.closest('#btnCamera');
            
            // Auto focus ke input manual jika tidak sedang di modal atau klik tombol kamera
            if (!isNoteInput && !isBtnCamera && modal.classList.contains('hidden')) {
                scannerInput.focus();
            }
        });

        scannerInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                handleScan(this.value);
                this.value = '';
            }
        });

        document.getElementById('btnSearch').addEventListener('click', () => {
            handleScan(scannerInput.value);
            scannerInput.value = '';
        });

        function showFeedback(msg, type = 'info') {
            scanFeedback.classList.remove('hidden', 'bg-emerald-100', 'text-emerald-700', 'bg-rose-100', 'text-rose-700', 'bg-blue-100', 'text-blue-700');
            if(type === 'success') scanFeedback.classList.add('bg-emerald-100', 'text-emerald-700');
            else if(type === 'error') scanFeedback.classList.add('bg-rose-100', 'text-rose-700');
            else scanFeedback.classList.add('bg-blue-100', 'text-blue-700');
            
            scanFeedback.innerHTML = msg;
            scanFeedback.classList.remove('hidden');
            setTimeout(() => { scanFeedback.classList.add('hidden'); }, 3000);
        }

        async function handleScan(code) {
            if(!code) return;
            showFeedback('<i class="ph-bold ph-spinner animate-spin"></i> Memproses...', 'info');

            try {
                const res = await fetch('<?php echo e(route("permit.scan")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ identifier: code })
                });
                const data = await res.json();

                if(!res.ok) throw new Error(data.message || 'Data tidak ditemukan');

                if(data.mode === 'CHECK_IN') {
                    showFeedback(`✅ ${data.data.student.name} kembali (Durasi: ${data.data.duration}m)`, 'success');
                    playAudio('success');
                    Swal.fire({
                        icon: 'success',
                        title: 'Selamat Datang Kembali!',
                        html: `<span class="font-bold text-lg">${data.data.student.name}</span><br>Kembali setelah ${data.data.duration} menit.`,
                        timer: 2500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' }
                    }).then(() => location.reload());
                } else {
                    showFeedback('Silakan pilih alasan izin...', 'info');
                    openModal(data.data.student);
                    playAudio('notification');
                }

            } catch (err) {
                showFeedback(`❌ ${err.message}`, 'error');
                playAudio('error');
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Gagal', 
                    text: err.message, 
                    timer: 1500, 
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        }

        function openModal(student) {
            document.getElementById('modalStudentName').textContent = student.name;
            document.getElementById('modalStudentClass').textContent = student.school_class?.name || '';
            document.getElementById('modalStudentId').value = student.id;
            
            document.querySelectorAll('input[name="reason_category"]').forEach(el => el.checked = false);
            document.querySelector('input[name="notes"]').value = '';

            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.getElementById('permitForm').reset();
            scannerInput.focus();
        }

        async function submitPermitManual() {
            const form = document.getElementById('permitForm');
            const formData = new FormData(form);
            const reason = formData.get('reason_category');
            
            if (!reason) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Alasan!',
                    text: 'Anda belum memilih alasan izin keluar.',
                    confirmButtonColor: '#4f46e5',
                    customClass: { popup: 'rounded-[2rem]' }
                });
                return;
            }

            const payload = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('<?php echo e(route("permit.store")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if(!res.ok) throw new Error(data.message);

                closeModal();
                playAudio('success');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Izin Tercatat!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                }).then(() => location.reload());

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: err.message,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        }

        function playAudio(type) {}
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/permit/index.blade.php ENDPATH**/ ?>