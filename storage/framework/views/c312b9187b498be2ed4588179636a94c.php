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

        /* Container Scanner HTML5 QRCode */
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
            background: #56bbf1; /* elevate-accent */
            box-shadow: 0 0 15px #56bbf1;
            top: 0; 
            animation: scanMove 2.5s infinite linear;
            z-index: 10; 
            opacity: 0.8;
            pointer-events: none;
        }

        /* Efek Flash Saat Berhasil Scan via Kamera */
        @keyframes flashSuccess {
            0% { box-shadow: inset 0 0 0 transparent; border-color: transparent; }
            50% { box-shadow: inset 0 0 40px rgba(13, 82, 161, 0.5); border-color: #0d52a1; } /* elevate-primary */
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

        /* Menyembunyikan elemen dashboard dari Html5QrcodeScanner bawaan */
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

            
            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in-down">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-700">Peminjaman Gagal</h3>
                        <p class="text-xs font-bold text-rose-600 mt-1"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                    <p class="text-sm font-bold text-emerald-700"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="bg-elevate-gradient-main p-8 text-elevate-dark relative overflow-hidden border-b border-white/60">
                    <div class="absolute -right-6 -top-6 text-white/40 text-9xl pointer-events-none mix-blend-overlay">
                        <i class="ph-fill ph-student"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-white/50 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/60 backdrop-blur-md shadow-sm">
                            Mode Individu
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight">Peminjaman Paket Siswa</h2>
                    <p class="text-elevate-dark/80 text-sm font-semibold relative z-10 mt-2 max-w-xl leading-relaxed">
                        Pilih satu siswa dan pindai beberapa buku sekaligus. Maksimal 11 Buku per Siswa.
                    </p>
                </div>

                <div class="p-8 bg-elevate-surface">
                    <form action="<?php echo e(route('library.circulation.storeStudentBulk')); ?>" method="POST" id="studentBorrowForm">
                        <?php echo csrf_field(); ?>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200">
                                    <h3 class="text-xs font-black text-elevate-dark/60 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="ph-fill ph-user-focus text-elevate-primary text-lg"></i> Data Peminjam
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Pilih Siswa <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <select name="student_id" id="student_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm">
                                                    <option value="">-- Cari / Pilih Siswa --</option>
                                                    <?php
                                                        // Mengurutkan berdasarkan Kelas (asc) lalu Nama Abjad (asc), dan mengelompokkannya
                                                        $groupedStudents = collect($students)->sortBy([
                                                            ['class_name', 'asc'],
                                                            ['name', 'asc']
                                                        ])->groupBy('class_name');
                                                    ?>
                                                    
                                                    <?php $__currentLoopData = $groupedStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className => $classStudents): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <optgroup label="=== Kelas <?php echo e($className); ?> ===">
                                                            <?php $__currentLoopData = $classStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (NISN: <?php echo e($student->id); ?>)</option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </optgroup>
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

                                
                                <div class="bg-white p-6 rounded-[2rem] border-2 border-elevate-accent/30 shadow-lg shadow-elevate-accent/5 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-4 opacity-5 text-elevate-primary text-6xl pointer-events-none"><i class="ph-fill ph-barcode"></i></div>
                                    <label class="block text-sm font-black text-elevate-primary mb-3 relative z-10">Scan Barcode Buku Disini</label>
                                    <div class="relative z-10">
                                        <input type="text" id="mainScannerInput" class="w-full px-4 py-4 rounded-xl border-2 border-slate-200 bg-elevate-soft/50 focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-mono font-bold text-elevate-dark text-lg transition-all shadow-sm" placeholder="Arahkan kursor & Scan..." autofocus>
                                        
                                        <div class="mt-3 flex gap-2">
                                            <button type="button" onclick="processManualInput()" class="flex-1 bg-elevate-dark text-white py-2 rounded-lg text-xs font-bold hover:bg-elevate-primary transition shadow-md">Tambahkan</button>
                                            <button type="button" onclick="openScannerModal()" class="flex-none px-4 bg-elevate-peach-light text-elevate-primary border border-elevate-peach/50 py-2 rounded-lg text-xs font-bold hover:bg-elevate-peach transition shadow-sm" title="Pakai Kamera HP">
                                                <i class="ph-bold ph-camera text-base"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-500 mt-3 relative z-10">Gunakan alat Scanner Barcode. Tekan enter untuk memasukkan buku ke dalam daftar.</p>
                                </div>
                            </div>

                            
                            <div class="lg:col-span-2 flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-black text-elevate-dark">Keranjang Peminjaman</h3>
                                    
                                    <div class="px-4 py-2 bg-elevate-peach-light/40 text-elevate-primary rounded-xl border border-elevate-peach/30 text-sm font-black flex items-center gap-2 shadow-sm">
                                        <i class="ph-bold ph-books"></i> Total: <span id="totalBooks">0</span>/11 Buku
                                    </div>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-[2rem] flex-1 overflow-hidden flex flex-col shadow-sm relative min-h-[400px]">
                                    
                                    <div id="emptyCart" class="absolute inset-0 flex flex-col items-center justify-center bg-elevate-soft/50 z-10 transition-opacity">
                                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 border border-slate-200 text-elevate-primary">
                                            <i class="ph-duotone ph-shopping-cart text-4xl"></i>
                                        </div>
                                        <h4 class="font-black text-elevate-dark/60">Keranjang Masih Kosong</h4>
                                        <p class="text-xs text-elevate-dark/40 mt-1">Scan buku untuk menambahkannya ke daftar</p>
                                    </div>

                                    <div class="overflow-y-auto custom-scrollbar flex-1 relative z-20">
                                        <table class="w-full text-left border-collapse" id="scannedTable">
                                            <thead class="sticky top-0 z-30 bg-white">
                                                <tr class="bg-elevate-soft text-xs uppercase tracking-wider text-elevate-primary font-bold border-b border-slate-200">
                                                    <th class="px-6 py-4 w-16 text-center">No</th>
                                                    <th class="px-6 py-4">Informasi Buku</th>
                                                    <th class="px-6 py-4">Kode / Barcode</th>
                                                    <th class="px-6 py-4 w-20 text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100" id="cartContainer">
                                                <!-- Baris buku akan ditambahkan via JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                            <p class="text-xs text-elevate-dark/50 font-medium max-w-sm">
                                <i class="ph-fill ph-info text-elevate-primary"></i> Pastikan siswa dan buku sudah benar sebelum menyimpan. Maksimal 11 Buku.
                            </p>
                            <button type="button" id="btnSubmit" onclick="confirmCheckout()" disabled class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent disabled:transform-none">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Proses Peminjaman
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
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Arahkan kamera ke Barcode buku</p>
                </div>
                <button type="button" onclick="closeScannerModal()" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 bg-slate-100">
                <div class="relative w-full rounded-[1.5rem] border-4 border-slate-900 shadow-inner overflow-hidden bg-slate-900 min-h-[250px] flex items-center justify-center">
                    
                    <div id="reader-loader" class="text-slate-400 font-bold text-sm flex flex-col items-center gap-2 absolute z-10">
                        <i class="ph-bold ph-spinner animate-spin text-3xl text-elevate-accent"></i>
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
                <i class="ph-fill ph-magic-wand text-elevate-accent text-base"></i> Sistem otomatis menambahkan ke keranjang jika sukses.
            </div>
        </div>
    </div>

    
    <script>
        let scannedCodesList = []; 
        const MAX_BOOKS = 11; // Batas Maksimal
        const cartContainer = document.getElementById('cartContainer');
        const emptyCart = document.getElementById('emptyCart');
        const totalDisplay = document.getElementById('totalBooks');
        const btnSubmit = document.getElementById('btnSubmit');
        const mainInput = document.getElementById('mainScannerInput');

        // BEEP SOUND
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type = 'success') {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.type = type === 'error' ? 'sawtooth' : 'sine';
            const freq = type === 'error' ? 150 : 880;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            if(type === 'success') osc.frequency.exponentialRampToValueAtTime(freq * 2, audioCtx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.3);
            osc.start(audioCtx.currentTime); osc.stop(audioCtx.currentTime + 0.3);
        }

        // DETEKSI ENTER PADA INPUT SCANNER FISIK
        mainInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                processScannedCode(this.value.trim());
            }
        });

        function processManualInput() {
            processScannedCode(mainInput.value.trim());
        }

        // ==========================================
        // FUNGSI UTAMA: MEMPROSES KODE (DARI FISIK / KAMERA)
        // ==========================================
        async function processScannedCode(code, fromCamera = false) {
            if (!code) return;
            
            if(!fromCamera) {
                mainInput.value = '';
                mainInput.focus();
                mainInput.placeholder = "Mencari buku...";
                mainInput.disabled = true;
            }

            // 1. Validasi Maksimal
            if (scannedCodesList.length >= MAX_BOOKS) {
                playBeep('error');
                if(fromCamera) triggerFlashEffect(true);
                Swal.fire({ toast: true, position: 'top', icon: 'error', title: `Maksimal ${MAX_BOOKS} Buku!`, showConfirmButton: false, timer: 2500 });
                resetInputState(fromCamera);
                return;
            }

            // 2. Validasi Duplikat di Keranjang
            if (scannedCodesList.includes(code)) {
                playBeep('error');
                if(fromCamera) triggerFlashEffect(true);
                Swal.fire({ toast: true, position: 'top', icon: 'error', title: 'Buku sudah ada di daftar!', showConfirmButton: false, timer: 2000 });
                resetInputState(fromCamera);
                return;
            }

            try {
                // 3. FETCH DATA BUKU DARI BACKEND
                const response = await fetch(`<?php echo e(url('/library/tools/api/book-by-code')); ?>?code=${code}`);
                const data = await response.json();

                if (data.success) {
                    playBeep('success');
                    if(fromCamera) triggerFlashEffect(false);
                    addToCartUI(data.book, code);
                } else {
                    playBeep('error');
                    if(fromCamera) triggerFlashEffect(true);
                    Swal.fire({ toast: true, position: 'top', icon: 'warning', title: data.message || 'Buku tidak ditemukan', showConfirmButton: false, timer: 3000 });
                }
            } catch (error) {
                playBeep('error');
                console.error("Error fetching book:", error);
            } finally {
                resetInputState(fromCamera);
            }
        }

        function resetInputState(fromCamera) {
            if(!fromCamera) {
                mainInput.disabled = false;
                mainInput.placeholder = "Arahkan kursor & Scan...";
                mainInput.focus();
            }
        }

        // FUNGSI MENAMBAH BARIS KE TABEL KERANJANG
        function addToCartUI(book, code) {
            scannedCodesList.push(code);
            emptyCart.style.opacity = '0';
            setTimeout(() => emptyCart.classList.add('hidden'), 300);

            let index = scannedCodesList.length;
            
            const tr = document.createElement('tr');
            tr.id = `row-${code}`;
            tr.className = "hover:bg-elevate-soft transition-colors animate-fade-in-down group";
            tr.innerHTML = `
                <td class="px-6 py-4 text-center font-bold text-slate-400 number-cell">${index}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors">${book.title}</div>
                    <div class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mt-1">Kategori: ${book.category || '-'}</div>
                    <input type="hidden" name="item_codes[]" value="${code}">
                </td>
                <td class="px-6 py-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200 font-mono text-xs font-bold text-elevate-primary shadow-sm">
                        <i class="ph-bold ph-barcode text-slate-400"></i> ${code}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <button type="button" onclick="removeBook('${code}')" class="w-8 h-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all mx-auto shadow-sm">
                        <i class="ph-bold ph-trash"></i>
                    </button>
                </td>
            `;
            
            cartContainer.appendChild(tr);
            updateCartCounter();
            
            // Scroll otomatis ke bawah jika buku banyak
            cartContainer.parentElement.scrollTop = cartContainer.parentElement.scrollHeight;
        }

        window.removeBook = function(code) {
            const index = scannedCodesList.indexOf(code);
            if (index > -1) {
                scannedCodesList.splice(index, 1);
                document.getElementById(`row-${code}`).remove();
                
                const numberCells = document.querySelectorAll('.number-cell');
                numberCells.forEach((cell, i) => { cell.innerText = i + 1; });
                updateCartCounter();
            }
        }

        function updateCartCounter() {
            const count = scannedCodesList.length;
            totalDisplay.innerText = count;
            
            if (count === 0) {
                emptyCart.classList.remove('hidden');
                setTimeout(() => emptyCart.style.opacity = '1', 10);
                btnSubmit.disabled = true;
            } else {
                btnSubmit.disabled = false;
            }

            // Ganti warna indikator jika sudah menyentuh batas (11)
            if (count >= MAX_BOOKS) {
                totalDisplay.parentElement.classList.replace('bg-elevate-peach-light/40', 'bg-rose-100');
                totalDisplay.parentElement.classList.replace('text-elevate-primary', 'text-rose-600');
                totalDisplay.parentElement.classList.replace('border-elevate-peach/30', 'border-rose-300');
            } else {
                totalDisplay.parentElement.classList.replace('bg-rose-100', 'bg-elevate-peach-light/40');
                totalDisplay.parentElement.classList.replace('text-rose-600', 'text-elevate-primary');
                totalDisplay.parentElement.classList.replace('border-rose-300', 'border-elevate-peach/30');
            }
        }

        window.confirmCheckout = function() {
            const studentId = document.getElementById('student_id').value;
            if(!studentId) {
                Swal.fire({ icon: 'warning', title: 'Siswa Belum Dipilih', text: 'Mohon pilih peminjam terlebih dahulu.', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem]' } });
                return;
            }

            Swal.fire({
                title: 'Proses Peminjaman?',
                html: `Meminjamkan <strong class="text-elevate-primary">${scannedCodesList.length} Buku Paket</strong> ke siswa yang dipilih.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses!',
                confirmButtonColor: '#2c3f61',
                cancelButtonColor: '#e11d48',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, customClass: { popup: 'rounded-[2rem]' }, didOpen: () => Swal.showLoading() });
                    document.getElementById('studentBorrowForm').submit();
                }
            });
        }


        // ==========================================
        // LOGIC KAMERA HTML5 QR CODE SCANNER
        // ==========================================
        let html5QrCode = null;
        let currentFacingMode = "environment";
        let lastScannedCode = "";
        let lastScanTime = 0;

        function startCameraScanner() {
            document.getElementById('reader-loader').style.display = 'flex';
            document.getElementById('scanner-laser').classList.add('hidden');
            
            if (!html5QrCode) { html5QrCode = new Html5Qrcode("reader"); }
            
            const config = { fps: 15, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

            html5QrCode.start({ facingMode: currentFacingMode }, config, onScanSuccess, onScanFailure)
            .then(() => {
                document.getElementById('reader-loader').style.display = 'none';
                document.getElementById('scanner-laser').classList.remove('hidden');
            }).catch((err) => {
                document.getElementById('reader-loader').style.display = 'none';
                Swal.fire({ icon: 'error', title: 'Kamera Gagal Akses', text: 'Pastikan browser diizinkan mengakses kamera.', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem]' } });
                closeScannerModal();
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            const now = Date.now();
            // Mencegah kamera melakukan multi-scan gila-gilaan pada 1 barcode yang sama dalam 2 detik
            if (decodedText === lastScannedCode && (now - lastScanTime) < 2000) {
                return; 
            }
            lastScannedCode = decodedText;
            lastScanTime = now;

            // Lempar ke fungsi utama keranjang
            processScannedCode(decodedText, true);
        }

        function onScanFailure(error) { /* Abaikan error cari fokus */ }

        function triggerFlashEffect(isError) {
            const flash = document.getElementById('scanner-flash');
            flash.style.animation = 'none';
            flash.offsetHeight; /* trigger reflow */
            flash.style.animation = 'flashSuccess 1s ease-out';
            
            if(isError) {
                flash.style.borderColor = '#e11d48'; // Rose
                flash.style.boxShadow = 'inset 0 0 40px rgba(225, 29, 72, 0.5)';
            } else {
                flash.style.borderColor = '#0d52a1'; // Elevate Primary
                flash.style.boxShadow = 'inset 0 0 40px rgba(13, 82, 161, 0.5)';
            }
        }

        window.openScannerModal = function() {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            document.getElementById('scannerModal').classList.remove('hidden');
            startCameraScanner();
        }

        window.closeScannerModal = function() {
            document.getElementById('scannerModal').classList.add('hidden');
            document.getElementById('scanner-laser').classList.add('hidden');
            if (html5QrCode) {
                html5QrCode.stop().catch(err => console.log("Kamera sudah berhenti", err));
            }
        }

        window.switchCameraMode = function() {
            currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
            if (html5QrCode) {
                html5QrCode.stop().then(() => startCameraScanner()).catch(() => startCameraScanner());
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/circulation/student-borrow.blade.php ENDPATH**/ ?>