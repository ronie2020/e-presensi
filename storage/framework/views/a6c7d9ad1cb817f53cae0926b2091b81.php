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
    
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Fix style agar kamera scanner mengisi area dengan pas */
        #reader video {
            object-fit: cover;
            width: 100% !important;
            height: 100% !important;
            border-radius: 1rem;
        }
        #reader { width: 100%; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl"></span> Catatan Kedisiplinan
                        </h1>
                        <p class="text-blue-300 text-sm font-medium leading-relaxed max-w-lg">
                            Kelola poin pelanggaran dan prestasi siswa untuk membangun karakter positif di lingkungan sekolah.
                        </p>
                    </div>
                    
                    
                    <a href="<?php echo e(route('discipline-types.index')); ?>" class="group bg-white/10 backdrop-blur-md hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm border border-white/10 transition-all flex items-center gap-2 shadow-lg">
                        <i class="ph-bold ph-gear text-xl group-hover:rotate-90 transition-transform duration-500"></i>
                        <span>Atur Jenis Pelanggaran</span>
                    </a>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[1.5rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ph-bold ph-check"></i></div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="p-2 hover:bg-emerald-100 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <!-- BAGIAN 1: INPUT FORM (GRID 2 KOLOM MODERN) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                
                <!-- Form Pelanggaran (Rose Theme) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-rose-900/5 border border-slate-100 overflow-hidden relative group hover:border-rose-100 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-rose-500"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-rose-100 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-warning-octagon"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-800">Input Pelanggaran</h3>
                                <p class="text-sm font-bold text-rose-400">Pengurangan Poin (-)</p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('discipline.store')); ?>" method="POST" class="space-y-5">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="date" value="<?php echo e(\Carbon\Carbon::today()->toDateString()); ?>">
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <select name="student_id" id="student_select_violation" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                            <option value="">-- Cari Nama Siswa --</option>
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($student->id); ?>" 
                                                        data-nis="<?php echo e($student->nis ?? ''); ?>" 
                                                        data-nisn="<?php echo e($student->nisn ?? ''); ?>"
                                                        data-student-id="<?php echo e($student->student_id ?? ''); ?>">
                                                    <?php echo e($student->name); ?> (<?php echo e($student->schoolClass->name ?? 'N/A'); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    
                                    <button type="button" onclick="startScanner('student_select_violation')" class="shrink-0 bg-slate-800 text-white w-12 rounded-2xl hover:bg-slate-700 transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Pelanggaran</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php $__currentLoopData = $violationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?> (-<?php echo e($type->point_value); ?> Poin)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kronologi / Catatan</label>
                                <textarea name="notes" rows="3" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-medium p-4" placeholder="Jelaskan singkat kejadiannya..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 flex items-center justify-center gap-2 mt-2 transform active:scale-95">
                                <i class="ph-bold ph-warning-circle text-lg"></i>
                                Simpan Pelanggaran
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Form Kebaikan (Emerald Theme) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-emerald-900/5 border border-slate-100 overflow-hidden relative group hover:border-emerald-100 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-medal"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-800">Input Prestasi</h3>
                                <p class="text-sm font-bold text-emerald-500">Penambahan Poin (+)</p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('discipline.store')); ?>" method="POST" class="space-y-5">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="date" value="<?php echo e(\Carbon\Carbon::today()->toDateString()); ?>">
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <select name="student_id" id="student_select_merit" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                            <option value="">-- Cari Nama Siswa --</option>
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($student->id); ?>" 
                                                        data-nis="<?php echo e($student->nis ?? ''); ?>" 
                                                        data-nisn="<?php echo e($student->nisn ?? ''); ?>"
                                                        data-student-id="<?php echo e($student->student_id ?? ''); ?>">
                                                    <?php echo e($student->name); ?> (<?php echo e($student->schoolClass->name ?? 'N/A'); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    
                                    <button type="button" onclick="startScanner('student_select_merit')" class="shrink-0 bg-slate-800 text-white w-12 rounded-2xl hover:bg-slate-700 transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Kebaikan</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php $__currentLoopData = $meritTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?> (+<?php echo e($type->point_value); ?> Poin)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Detail Tambahan</label>
                                <textarea name="notes" rows="3" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-medium p-4" placeholder="Keterangan prestasi..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 mt-2 transform active:scale-95">
                                <i class="ph-bold ph-star text-lg"></i>
                                Simpan Kebaikan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: RINGKASAN POIN (Leaderboard Style) -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mb-10">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 text-2xl border border-amber-100 shadow-sm">
                            <i class="ph-fill ph-trophy"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Aktivitas Terkini</h3>
                            <p class="text-sm font-medium text-slate-400">Daftar siswa dengan catatan disiplin terbaru.</p>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider w-20 text-center">Rank</th>
                                <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggaran</th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Kebaikan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $studentSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/20 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if($index == 0): ?>
                                            <div class="w-8 h-8 mx-auto rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center font-black text-sm ring-2 ring-amber-50">1</div>
                                        <?php elseif($index == 1): ?>
                                            <div class="w-8 h-8 mx-auto rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm ring-2 ring-slate-50">2</div>
                                        <?php elseif($index == 2): ?>
                                            <div class="w-8 h-8 mx-auto rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm ring-2 ring-orange-50">3</div>
                                        <?php else: ?>
                                            <span class="text-sm font-bold text-slate-400">#<?php echo e($index + 1); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors"><?php echo e($summary->name); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-xs font-bold text-slate-500 border border-slate-200"><?php echo e($summary->class); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if($summary->total_violation > 0): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black text-rose-700 bg-rose-100 border border-rose-200">
                                                - <?php echo e($summary->total_violation); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-xs font-medium">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if($summary->total_merit > 0): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black text-emerald-700 bg-emerald-100 border border-emerald-200">
                                                + <?php echo e($summary->total_merit); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-xs font-medium">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                                <i class="ph-duotone ph-scroll text-4xl"></i>
                                            </div>
                                            <p class="text-slate-500 font-bold">Belum ada catatan disiplin.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                
                <div class="p-6 border-t border-slate-50">
                    <?php echo e($historyRecords->links()); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeScanner()"></div>

            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:align-middle sm:max-w-md w-full relative">
                <div class="bg-white p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                            <i class="ph-duotone ph-qr-code text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2">Scan Kartu Siswa</h3>
                        
                        <div class="relative w-full rounded-2xl overflow-hidden aspect-square bg-black border-4 border-slate-900 shadow-inner">
                            <div id="reader" class="w-full h-full object-cover"></div>
                            <div id="scanner-status" class="absolute inset-0 flex items-center justify-center text-white text-xs font-bold z-10 pointer-events-none bg-black/50">
                                Menunggu Kamera...
                            </div>
                        </div>

                        <div id="error-message" class="text-rose-500 text-xs font-bold mt-4 hidden bg-rose-50 p-2 rounded-lg"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-2">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-3 bg-white text-base font-bold text-slate-700 hover:bg-slate-100 focus:outline-none sm:w-auto sm:text-sm" onclick="closeScanner()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        let html5QrcodeScanner = null;
        let currentTargetInput = null;

        function updateStatus(message) {
            const statusEl = document.getElementById('scanner-status');
            if(statusEl) statusEl.innerText = message;
        }

        function startScanner(targetInputId) {
            if (typeof Html5Qrcode === 'undefined') {
                Swal.fire('Error', 'Library Scanner belum siap.', 'error');
                return;
            }

            currentTargetInput = targetInputId;
            const modal = document.getElementById('qrModal');
            const errorMsg = document.getElementById('error-message');
            const statusEl = document.getElementById('scanner-status');

            errorMsg.classList.add('hidden');
            modal.classList.remove('hidden');
            
            if(statusEl) {
                statusEl.style.display = 'flex';
                statusEl.innerText = "Memulai kamera...";
            }

            setTimeout(() => {
                if (html5QrcodeScanner === null) {
                    html5QrcodeScanner = new Html5Qrcode("reader");
                }

                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        let cameraId = devices.length > 1 ? devices[devices.length - 1].id : devices[0].id;
                        updateStatus("Kamera aktif...");

                        html5QrcodeScanner.start(cameraId, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess)
                        .then(() => {
                            if(statusEl) statusEl.style.display = 'none';
                        }).catch(err => {
                            showError("Gagal membuka kamera: " + err);
                        });
                    } else {
                        showError("Tidak ada kamera ditemukan.");
                    }
                }).catch(err => {
                    showError("Izin kamera ditolak.");
                });
            }, 500);
        }

        function showError(msg) {
            const errorMsg = document.getElementById('error-message');
            if(errorMsg) {
                errorMsg.innerText = msg;
                errorMsg.classList.remove('hidden');
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            const scannedText = decodedText.trim();
            let selectElement = document.getElementById(currentTargetInput);
            let found = false;
            let foundName = "";

            for (let i = 0; i < selectElement.options.length; i++) {
                const option = selectElement.options[i];
                if (option.value == scannedText || 
                   option.getAttribute('data-nis') == scannedText || 
                   option.getAttribute('data-nisn') == scannedText || 
                   option.getAttribute('data-student-id') == scannedText) {
                    
                    selectElement.selectedIndex = i;
                    found = true;
                    foundName = option.text;
                    break;
                }
            }

            if (found) {
                // Play beep sound
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.frequency.value = 880; 
                gainNode.gain.value = 0.1;
                oscillator.start();
                setTimeout(() => oscillator.stop(), 100);

                Swal.fire({
                    icon: 'success', title: 'Siswa Ditemukan!',
                    text: foundName, timer: 1500, showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                }).then(() => closeScanner());
            } else {
                Swal.fire({
                    icon: 'error', title: 'Tidak Ditemukan',
                    text: `Kode "${scannedText}" tidak terdaftar.`,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        }

        function closeScanner() {
            const modal = document.getElementById('qrModal');
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    if(modal) modal.classList.add('hidden');
                }).catch(() => {
                    if(modal) modal.classList.add('hidden');
                });
            } else {
                if(modal) modal.classList.add('hidden');
            }
        }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\discipline\index.blade.php ENDPATH**/ ?>