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

    <?php $__env->startPush('styles'); ?>
    <style>
        /* Animasi Garis Scanner */
        @keyframes scanMove { 
            0% { top: 0; opacity: 0; } 
            10% { opacity: 1; } 
            90% { opacity: 1; } 
            100% { top: 100%; opacity: 0; } 
        }

        /* Container Scanner mirip index.blade.php */
        #reader { 
            width: 100% !important; 
            border: none !important; 
            border-radius: 1.5rem; 
            overflow: hidden; 
            background: #0f172a; /* Slate 900 */
            position: relative;
        }

        #reader video { 
            width: 100% !important; 
            object-fit: cover !important; 
            border-radius: 1.5rem;
            display: block !important;
        }

        /* Garis Laser Biru (TEMA ELEVATE) */
        .scanner-line {
            position: absolute; 
            width: 100%; 
            height: 3px;
            background: #38bdf8; 
            box-shadow: 0 0 15px #38bdf8;
            top: 0; 
            animation: scanMove 2.5s infinite linear;
            z-index: 10; 
            opacity: 0.8;
            pointer-events: none;
        }

        /* Efek Flash Saat Berhasil */
        @keyframes flashSuccess {
            0% { box-shadow: inset 0 0 0 transparent; border-color: transparent; }
            50% { box-shadow: inset 0 0 40px rgba(16, 185, 129, 0.5); border-color: #10b981; }
            100% { box-shadow: inset 0 0 0 transparent; border-color: transparent; }
        }
        .scan-success-flash {
            animation: flashSuccess 1s ease-out;
            border: 2px solid transparent;
            border-radius: 1.5rem;
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 20;
        }

        /* Menyembunyikan elemen dashboard dari Html5QrcodeScanner (Jika ter-render) */
        #reader__dashboard_section_csr span, 
        #reader__dashboard_section_swaplink,
        #reader__dashboard_section_csr div { display: none !important; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <a href="<?php echo e(route('library.circulation.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-elevate-dark/60 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Sirkulasi
            </a>

            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-700">Gagal Memproses Distribusi</h3>
                        <ul class="list-disc list-inside text-xs text-rose-600 mt-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                    <p class="text-sm font-bold text-emerald-700"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in-down">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-700">Distribusi Terhenti</h3>
                        <p class="text-xs font-bold text-rose-600 mt-1"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="bg-elevate-gradient-main p-8 text-elevate-dark relative overflow-hidden border-b border-white/60">
                    <div class="absolute -right-6 -top-6 text-white/40 text-9xl pointer-events-none mix-blend-overlay">
                        <i class="ph-fill ph-barcode"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-white/50 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/60 backdrop-blur-md shadow-sm">
                            Mode Pindai Eksemplar
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight">Distribusi Buku Paket</h2>
                    <p class="text-elevate-dark/80 text-sm font-semibold relative z-10 mt-2 max-w-xl leading-relaxed">
                        Pindai barcode unik dari stiker masing-masing fisik buku dan pasangkan dengan nama siswa. Ini mencegah siswa menukar buku saat pengembalian.
                    </p>
                </div>

                <div class="p-8 bg-elevate-surface">
                    <form action="<?php echo e(route('library.circulation.storeBulk')); ?>" method="POST" id="bulkBorrowForm">
                        <?php echo csrf_field(); ?>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200">
                                    <h3 class="text-xs font-black text-elevate-dark/60 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="ph-fill ph-sliders text-elevate-primary text-lg"></i> Pengaturan
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Pilih Kelas <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-users-three absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <select name="class_id" id="class_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm">
                                                    <option value="">-- Pilih Kelas --</option>
                                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Buku Paket <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-books absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <select name="book_id" id="book_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm">
                                                    <option value="">-- Pilih Buku Paket --</option>
                                                    <?php $__currentLoopData = $textbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($book->id); ?>" data-stock="<?php echo e($book->stock); ?>">
                                                            <?php echo e($book->title); ?> (Stok: <?php echo e($book->stock); ?>)
                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Tenggat Waktu <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <?php $defaultDueDate = \Carbon\Carbon::create(date('Y') + 1, 6, 15)->format('Y-m-d'); ?>
                                                <input type="date" name="due_date" value="<?php echo e($defaultDueDate); ?>" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="lg:col-span-2 flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-black text-elevate-dark">Daftar Penerima</h3>
                                    
                                    
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="openScannerModal()" class="px-4 py-2 bg-elevate-dark hover:bg-elevate-primary text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2 border border-transparent transform active:scale-95">
                                            <i class="ph-bold ph-camera text-base"></i> Scan Kamera
                                        </button>

                                        <div class="px-3 py-2 bg-elevate-peach-light/40 text-elevate-primary rounded-xl border border-elevate-peach/30 text-xs font-bold flex items-center gap-2 shadow-sm">
                                            <i class="ph-bold ph-check-circle"></i> Terisi: <span id="scannedCount">0</span> Siswa
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-[2rem] flex-1 overflow-hidden flex flex-col shadow-sm relative min-h-[400px]">
                                    
                                    <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center bg-elevate-soft/50 z-10 transition-opacity">
                                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 border border-slate-200 text-elevate-primary">
                                            <i class="ph-duotone ph-student text-4xl"></i>
                                        </div>
                                        <h4 class="font-black text-elevate-dark/60">Pilih Kelas Terlebih Dahulu</h4>
                                    </div>

                                    <div id="loadingState" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm z-20 hidden">
                                        <i class="ph-bold ph-spinner animate-spin text-4xl text-elevate-primary mb-2"></i>
                                    </div>

                                    <div class="overflow-y-auto custom-scrollbar flex-1">
                                        <table class="w-full text-left border-collapse" id="studentTable">
                                            <thead class="sticky top-0 z-10">
                                                <tr class="bg-elevate-soft text-xs uppercase tracking-wider text-elevate-primary font-bold border-b border-slate-200">
                                                    <th class="px-6 py-4">Nama Siswa</th>
                                                    <th class="px-6 py-4 w-1/2">Scan Barcode (Stiker Buku)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100" id="studentListContainer">
                                                <!-- Via JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                            <p class="text-xs text-elevate-dark/50 font-medium max-w-sm">
                                <i class="ph-fill ph-info text-elevate-primary"></i> Pastikan tidak ada barcode merah (ganda). Tekan Enter setelah scan untuk lanjut ke baris berikutnya.
                            </p>
                            <button type="button" id="btnSubmit" onclick="confirmBulkSubmit()" disabled class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Distribusi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div id="scannerModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden flex flex-col relative">
            
            <div class="bg-elevate-soft p-5 border-b border-slate-200 flex justify-between items-center relative z-20">
                <div>
                    <h3 class="font-black text-elevate-dark text-lg">Kamera Pemindai</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Arahkan kamera ke QR / Barcode buku</p>
                </div>
                <button type="button" onclick="closeScannerModal()" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 bg-slate-100">
                
                <div class="relative w-full rounded-[1.5rem] border-4 border-slate-900 shadow-inner overflow-hidden bg-slate-900 min-h-[250px] flex items-center justify-center">
                    
                    <div id="reader-loader" class="text-slate-400 font-bold text-sm flex flex-col items-center gap-2 absolute z-10">
                        <i class="ph-bold ph-spinner animate-spin text-3xl"></i>
                        Membuka Kamera...
                    </div>
                    
                    
                    <div id="reader" class="w-full"></div>
                    
                    
                    <div id="scanner-laser" class="hidden">
                        <div class="scanner-line"></div>
                    </div>
                    
                    
                    <div id="scanner-flash"></div>
                </div>
                
                
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="switchCameraMode()" class="py-2.5 px-4 rounded-xl border border-slate-300 text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-elevate-peach-light hover:text-elevate-primary hover:border-elevate-peach/50 transition-all flex items-center justify-center gap-2 shadow-sm bg-white active:scale-95">
                        <i class="ph-bold ph-camera-rotate text-lg"></i> Ganti Kamera
                    </button>
                </div>
            </div>

            <div class="bg-white p-4 border-t border-slate-200 text-center text-xs text-slate-500 font-medium flex items-center justify-center gap-2 relative z-20">
                <i class="ph-fill ph-check-circle text-emerald-500 text-base"></i> Sistem mengisi nama siswa otomatis (dari atas).
            </div>
        </div>
    </div>

    <script>
        let html5QrCode = null;
        let currentFacingMode = "environment"; // Kamera belakang by default

        // Audio Beep (Mirip Absensi)
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type = 'success') {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = type === 'error' ? 'sawtooth' : 'sine';
            const freq = type === 'error' ? 150 : 880;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            if(type === 'success') osc.frequency.exponentialRampToValueAtTime(freq * 2, audioCtx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.3);
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 0.3);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const studentContainer = document.getElementById('studentListContainer');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const scannedCountDisplay = document.getElementById('scannedCount');
            const btnSubmit = document.getElementById('btnSubmit');

            // Buka Audio Context saat user interaksi pertama
            document.body.addEventListener('click', () => { if (audioCtx.state === 'suspended') audioCtx.resume(); }, { once: true });

            classSelect.addEventListener('change', async function() {
                const classId = this.value;
                if(!classId) {
                    emptyState.classList.remove('hidden');
                    studentContainer.innerHTML = '';
                    updateCounter();
                    return;
                }

                emptyState.classList.add('hidden');
                loadingState.classList.remove('hidden');

                try {
                    const response = await fetch(`<?php echo e(url('/library/tools/api/students-by-class')); ?>/${classId}`);
                    const data = await response.json();
                    
                    if(data.success && data.students.length > 0) {
                        renderStudents(data.students);
                    } else {
                        studentContainer.innerHTML = `<tr><td colspan="2" class="px-6 py-10 text-center text-elevate-dark/40">Data siswa kosong.</td></tr>`;
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data.', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem]' } });
                } finally {
                    loadingState.classList.add('hidden');
                    updateCounter();
                }
            });

            function renderStudents(students) {
                let html = '';
                students.forEach((student, index) => {
                    html += `
                        <tr class="hover:bg-elevate-soft/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary">${student.name}</div>
                                <div class="text-xs text-elevate-dark/50 font-mono">${student.nisn || student.student_id || '-'}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <i class="ph-bold ph-barcode absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="item_codes[${student.id}]" class="item-code-input w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-mono font-bold text-elevate-dark text-sm transition-all shadow-sm" placeholder="Scan barcode stiker..." oninput="updateCounter()" onkeydown="focusNext(event, ${index})">
                                </div>
                            </td>
                        </tr>
                    `;
                });
                studentContainer.innerHTML = html;
            }

            window.focusNext = function(event, currentIndex) {
                if(event.key === 'Enter') {
                    event.preventDefault();
                    const inputs = document.querySelectorAll('.item-code-input');
                    if(inputs[currentIndex + 1]) {
                        inputs[currentIndex + 1].focus();
                    }
                }
            }

            window.updateCounter = function() {
                const inputs = document.querySelectorAll('.item-code-input');
                let filledCount = 0;
                let scannedCodes = [];
                let hasDuplicate = false;

                inputs.forEach(input => { 
                    const val = input.value.trim();
                    if(val !== '') { 
                        filledCount++; 
                        
                        if(scannedCodes.includes(val)) {
                            hasDuplicate = true;
                            input.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-600', 'focus:border-rose-500', 'focus:ring-rose-500');
                            input.classList.remove('border-slate-200', 'bg-elevate-soft', 'text-elevate-dark', 'focus:border-elevate-accent', 'focus:ring-elevate-accent/20');
                        } else {
                            scannedCodes.push(val);
                            input.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-600', 'focus:border-rose-500', 'focus:ring-rose-500');
                            input.classList.add('border-slate-200', 'bg-elevate-soft', 'text-elevate-dark', 'focus:border-elevate-accent', 'focus:ring-elevate-accent/20');
                        }
                    } else {
                        input.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-600', 'focus:border-rose-500', 'focus:ring-rose-500');
                        input.classList.add('border-slate-200', 'bg-elevate-soft', 'text-elevate-dark', 'focus:border-elevate-accent', 'focus:ring-elevate-accent/20');
                    }
                });
                
                scannedCountDisplay.innerText = filledCount;
                const bookSelected = document.getElementById('book_id').value !== '';
                
                if (hasDuplicate) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<i class="ph-bold ph-warning text-lg"></i> Ada Barcode Ganda';
                    btnSubmit.classList.replace('bg-elevate-dark', 'bg-rose-600');
                    btnSubmit.classList.replace('hover:bg-elevate-primary', 'hover:bg-rose-700');
                } else {
                    btnSubmit.disabled = filledCount === 0 || !bookSelected;
                    btnSubmit.innerHTML = '<i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Distribusi';
                    btnSubmit.classList.replace('bg-rose-600', 'bg-elevate-dark');
                    btnSubmit.classList.replace('hover:bg-rose-700', 'hover:bg-elevate-primary');
                }
            }
            document.getElementById('book_id').addEventListener('change', updateCounter);
        });

        // ==========================================
        // LOGIC SCANNER KAMERA HP / WEBCAM
        // ==========================================
        
        function startCameraScanner() {
            document.getElementById('reader-loader').style.display = 'flex';
            document.getElementById('scanner-laser').classList.add('hidden'); // Sembunyikan laser saat loading
            
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            
            const config = { 
                fps: 15, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrCode.start(
                { facingMode: currentFacingMode }, 
                config, 
                onScanSuccess, 
                onScanFailure
            ).then(() => {
                document.getElementById('reader-loader').style.display = 'none';
                document.getElementById('scanner-laser').classList.remove('hidden'); // Munculkan laser biru
            }).catch((err) => {
                document.getElementById('reader-loader').style.display = 'none';
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Kamera Gagal Akses', 
                    text: 'Pastikan Anda memberikan izin akses kamera (Allow) pada browser Anda.', 
                    confirmButtonColor: '#2c3f61', 
                    customClass: { popup: 'rounded-[2rem]' } 
                });
                closeScannerModal();
            });
        }

        window.openScannerModal = function() {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            
            const classSelect = document.getElementById('class_id').value;
            if(!classSelect) {
                Swal.fire({ icon: 'warning', title: 'Pilih Kelas Dulu', text: 'Silakan pilih kelas sebelum mengaktifkan kamera.', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem]' } });
                return;
            }

            document.getElementById('scannerModal').classList.remove('hidden');
            startCameraScanner();
        }

        window.closeScannerModal = function() {
            document.getElementById('scannerModal').classList.add('hidden');
            document.getElementById('scanner-laser').classList.add('hidden');
            
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    // Berhasil stop kamera
                }).catch((err) => {
                    console.log("Kamera sudah berhenti", err);
                });
            }
        }

        window.switchCameraMode = function() {
            currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
            
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    startCameraScanner();
                }).catch(err => {
                    startCameraScanner();
                });
            }
        }

        function triggerFlashEffect(isDuplicate) {
            const flash = document.getElementById('scanner-flash');
            
            if(isDuplicate) {
                // Efek Error Merah
                flash.style.animation = 'none';
                flash.offsetHeight; /* trigger reflow */
                flash.style.animation = 'flashSuccess 1s ease-out';
                flash.style.borderColor = '#e11d48'; // Rose
                flash.style.boxShadow = 'inset 0 0 40px rgba(225, 29, 72, 0.5)';
            } else {
                // Efek Sukses Hijau
                flash.className = 'scan-success-flash';
                setTimeout(() => { flash.className = ''; }, 1000);
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Cek apakah kode ini sudah discan sebelumnya di input manapun
            const inputs = document.querySelectorAll('.item-code-input');
            let isDuplicate = false;
            let targetInput = null;

            for(let i=0; i < inputs.length; i++) {
                if(inputs[i].value.trim() === decodedText) {
                    isDuplicate = true;
                    break;
                }
                // Cari input kosong pertama jika belum ketemu duplicate
                if(inputs[i].value.trim() === '' && targetInput === null) {
                    targetInput = inputs[i];
                }
            }

            if (isDuplicate) {
                playBeep('error');
                triggerFlashEffect(true);
                
                Swal.fire({
                    toast: true, position: 'top', icon: 'error',
                    title: 'Barcode sudah di-scan!',
                    showConfirmButton: false, timer: 2000
                });
                return;
            }

            if (targetInput) {
                // Isi Value ke form kosong
                targetInput.value = decodedText;
                
                // Efek Sukses
                playBeep('success');
                triggerFlashEffect(false);
                
                // Animasi Flash Hijau pada baris tabel input
                targetInput.classList.add('ring-4', 'ring-emerald-400', 'bg-emerald-50');
                setTimeout(() => targetInput.classList.remove('ring-4', 'ring-emerald-400', 'bg-emerald-50'), 1000);

                // Scroll layar otomatis ke baris tabel yang baru terisi
                targetInput.scrollIntoView({ behavior: "smooth", block: "center" });

                // Update perhitungan
                updateCounter();

                // Toast Notifikasi
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: 'Stiker Ditambahkan',
                    text: decodedText,
                    showConfirmButton: false, timer: 1500
                });
            } else {
                // Semua siswa sudah mendapatkan buku
                closeScannerModal();
                Swal.fire({
                    icon: 'success', title: 'Selesai!',
                    text: 'Semua siswa di kelas ini telah mendapatkan buku.',
                    confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem]' }
                });
            }
        }

        function onScanFailure(error) {
            // Abaikan error saat kamera sedang mencari fokus/barcode
        }

        window.confirmBulkSubmit = function() {
            const inputs = document.querySelectorAll('.item-code-input');
            let filledCount = 0;
            inputs.forEach(input => { if(input.value.trim() !== '') filledCount++; });

            const bookSelect = document.getElementById('book_id');
            const stockAvailable = parseInt(bookSelect.options[bookSelect.selectedIndex].getAttribute('data-stock'));

            if (filledCount > stockAvailable) {
                Swal.fire({ icon: 'error', title: 'Stok Kurang!', text: `Anda men-scan ${filledCount} buku, tapi stok tersisa ${stockAvailable}.`, confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem]' } });
                return;
            }

            Swal.fire({
                title: 'Simpan Distribusi?',
                html: `Memproses peminjaman untuk <strong class="text-elevate-primary">${filledCount} Siswa</strong>.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                confirmButtonColor: '#2c3f61',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, customClass: { popup: 'rounded-[2rem]' }, didOpen: () => Swal.showLoading() });
                    document.getElementById('bulkBorrowForm').submit();
                }
            });
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/circulation/bulk-borrow.blade.php ENDPATH**/ ?>