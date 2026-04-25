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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gradient-to-br from-cyan-500 via-blue-600 to-blue-900 p-8 mb-10 text-white shadow-xl shadow-cyan-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-300/30 rounded-full blur-[60px] translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="<?php echo e(route('library.dashboard')); ?>" class="px-3 py-1 bg-white/10 hover:bg-white/20 rounded-full text-xs font-bold text-cyan-100 transition flex items-center gap-2 border border-white/20 backdrop-blur-sm">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-white/30 text-xs">•</span>
                            <span class="text-cyan-100 bg-white/10 border border-white/10 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">Modul Transaksi</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight flex items-center gap-3 mt-2">
                            <span class="text-4xl">🔄</span> Sirkulasi Buku
                        </h1>
                        <p class="text-cyan-50 text-sm font-medium mt-2 max-w-lg">
                            Proses peminjaman dan pengembalian buku secara cepat menggunakan pemindai barcode atau input manual.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                            <i class="ph-duotone ph-barcode text-4xl text-white opacity-80"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                
                <!-- PANEL PEMINJAMAN (KIRI - TEMA CYAN) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-cyan-900/10 transition-all duration-300">
                    
                    
                    <div class="p-8 pb-0">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-cyan-50 text-cyan-600 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-cyan-100">
                                <i class="ph-bold ph-export"></i> <!-- PERBAIKAN IKON DISINI -->
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Mode Peminjaman</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Transaksi Keluar</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 pt-2 space-y-8 flex-1">
                        <!-- Step 1: Anggota -->
                        <div class="relative group/step">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-3 ml-1 flex justify-between">
                                <span>1. Identitas Peminjam</span>
                                <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-[10px]">Wajib</span>
                            </label>
                            <div class="flex gap-3">
                                <div id="member-scan-wrapper" class="flex-1 flex items-center px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl focus-within:border-cyan-500 focus-within:bg-white focus-within:shadow-lg transition-all group-hover/step:border-slate-300">
                                    <i class="ph-bold ph-identification-card text-slate-400 mr-3 text-xl"></i>
                                    <input type="text" id="memberInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold placeholder-slate-400 text-sm" placeholder="Scan Kartu / Ketik NISN + Enter" autofocus>
                                </div>
                                <button type="button" onclick="openScanner('memberInput')" class="p-4 bg-cyan-50 hover:bg-cyan-600 hover:text-white text-cyan-600 rounded-2xl transition-all shadow-sm border border-cyan-100 hover:border-cyan-600 hover:shadow-lg hover:shadow-cyan-500/30" title="Buka Kamera">
                                    <i class="ph-bold ph-qr-code text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Result Card: Anggota -->
                        <div id="memberInfo" class="hidden animate-fade-in-down space-y-4">
                            <!-- Diisi via JS: Profil Siswa -->
                        </div>

                        <!-- Step 2: Buku -->
                        <div id="bookInputSection" class="opacity-50 pointer-events-none transition-all duration-300">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-3 ml-1">2. Data Buku</label>
                            <div class="flex gap-3">
                                <div id="book-borrow-scan-wrapper" class="flex-1 flex items-center px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl focus-within:border-cyan-500 focus-within:bg-white focus-within:shadow-lg transition-all">
                                    <i class="ph-bold ph-book-open text-slate-400 mr-3 text-xl"></i>
                                    <input type="text" id="bookBorrowInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold placeholder-slate-400 text-sm" placeholder="Scan Barcode Buku + Enter">
                                </div>
                                <button type="button" onclick="openScanner('bookBorrowInput')" class="p-4 bg-cyan-50 hover:bg-cyan-600 hover:text-white text-cyan-600 rounded-2xl transition-all shadow-sm border border-cyan-100 hover:border-cyan-600 hover:shadow-lg hover:shadow-cyan-500/30">
                                    <i class="ph-bold ph-barcode text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-6 mt-auto flex gap-4 border-t border-slate-50">
                            <button type="button" onclick="resetBorrow()" class="px-6 py-4 rounded-2xl border border-slate-200 text-slate-500 font-bold text-sm hover:bg-slate-50 hover:text-slate-700 transition-colors">Reset</button>
                            <button type="button" id="btnProcessBorrow" onclick="processBorrow()" disabled class="flex-1 py-4 bg-cyan-600 text-white font-bold rounded-2xl hover:bg-cyan-700 shadow-xl shadow-cyan-500/30 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2">
                                <i class="ph-bold ph-check-circle text-lg"></i> Konfirmasi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL PENGEMBALIAN (KANAN - TETAP EMERALD UNTUK UX) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-emerald-900/10 transition-all duration-300">
                    
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-emerald-100">
                                <i class="ph-bold ph-arrow-u-down-left"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Pengembalian Cepat</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Transaksi Masuk</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-[2rem] p-8 border border-slate-200 text-center mb-8 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl"></div>
                            
                            <p class="text-sm text-slate-500 font-medium mb-6 relative z-10">Scan barcode buku untuk memproses pengembalian secara instan.</p>
                            
                            <div class="relative max-w-sm mx-auto z-10">
                                <div id="return-scan-wrapper" class="flex items-center px-5 py-4 bg-white border-2 border-emerald-400 rounded-2xl shadow-lg shadow-emerald-500/10 focus-within:ring-4 focus-within:ring-emerald-100 transition-all">
                                    <i class="ph-bold ph-barcode text-emerald-500 mr-3 text-2xl"></i>
                                    <input type="text" id="returnInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-black text-lg placeholder-slate-300" placeholder="Scan Buku + Enter" autofocus>
                                </div>
                                <button onclick="openScanner('returnInput')" class="absolute right-3 top-3 p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors">
                                    <i class="ph-bold ph-camera text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Area Info Pengembalian -->
                        <div id="returnInfo" class="hidden">
                            <!-- Diisi via JS -->
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABEL REKAP PEMINJAMAN TERKINI -->
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-cyan-100">
                            <i class="ph-fill ph-clock-counter-clockwise"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-800">Aktivitas Peminjaman Terkini</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Real-time Log</p>
                        </div>
                    </div>
                    <!-- Legend Keterangan -->
                    <div class="hidden sm:flex gap-3">
                        <span class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aman
                        </span>
                        <span class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Terlambat
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-xs uppercase tracking-wider text-slate-400 font-bold border-b border-slate-100">
                                <th class="px-8 py-5">Siswa</th>
                                <th class="px-8 py-5">Buku</th>
                                <th class="px-8 py-5">Tanggal Pinjam</th>
                                <th class="px-8 py-5">Tenggat Kembali</th>
                                <th class="px-8 py-5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $recentActiveLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500">
                                            <?php echo e(substr(optional($loan->student)->name ?? '?', 0, 1)); ?>

                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-700"><?php echo e(optional($loan->student)->name ?? 'Siswa Terhapus'); ?></p>
                                            <p class="text-xs text-slate-400 font-mono"><?php echo e(optional($loan->student)->student_id ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="ph-fill ph-book text-slate-300"></i>
                                        <span class="text-sm font-medium text-slate-600 truncate max-w-[200px] block" title="<?php echo e(optional($loan->book)->title); ?>">
                                            <?php echo e(optional($loan->book)->title ?? 'Buku Terhapus'); ?>

                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-sm font-bold text-slate-500">
                                    <?php echo e(\Carbon\Carbon::parse($loan->borrow_date)->format('d M Y')); ?>

                                </td>
                                <td class="px-8 py-4 text-sm font-bold text-slate-500">
                                    <?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?>

                                </td>
                                <td class="px-8 py-4 text-center">
                                    <?php
                                        $isOverdue = \Carbon\Carbon::now()->gt($loan->due_date);
                                    ?>
                                    <?php if($isOverdue): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-wide">
                                            Late
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">
                                            Active
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-8 py-10 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="ph-duotone ph-books text-4xl mb-1 opacity-50"></i>
                                        <p class="text-sm font-medium">Belum ada peminjaman aktif saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    
    <div id="scannerModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" onclick="stopScanner()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl w-full max-w-md relative z-10">
                <div class="p-8">
                    <h3 class="text-xl font-black text-slate-800 text-center mb-6">Pindai Kode</h3>
                    <div class="relative bg-black rounded-3xl overflow-hidden aspect-square border-4 border-slate-100 shadow-inner">
                        <div id="reader" class="w-full h-full"></div>
                        
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                            <div class="w-64 h-64 border-2 border-white/30 rounded-2xl relative">
                                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-cyan-500 rounded-tl-xl -mt-1 -ml-1"></div>
                                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-cyan-500 rounded-tr-xl -mt-1 -mr-1"></div>
                                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-cyan-500 rounded-bl-xl -mb-1 -ml-1"></div>
                                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-cyan-500 rounded-br-xl -mb-1 -mr-1"></div>
                            </div>
                        </div>
                    </div>
                    <button onclick="stopScanner()" class="mt-8 w-full py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition text-sm">Batalkan Scan</button>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        // --- SETUP AUDIO ---
        const audioSuccess = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        const audioError = new Audio('https://assets.mixkit.co/active_storage/sfx/950/950-preview.mp3');
        const audioBeep = new Audio('https://assets.mixkit.co/active_storage/sfx/2578/2578-preview.mp3'); 

        let html5QrcodeScanner = null;
        let activeInputId = null;
        let currentMember = null;
        let currentBook = null;

        function openScanner(inputId) {
            activeInputId = inputId;
            document.getElementById('scannerModal').classList.remove('hidden');
            
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            
            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    document.getElementById(activeInputId).value = decodedText;
                    document.getElementById(activeInputId).dispatchEvent(new Event('change'));
                    stopScanner();
                },
                (errorMessage) => {}
            );
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    document.getElementById('scannerModal').classList.add('hidden');
                });
            } else {
                document.getElementById('scannerModal').classList.add('hidden');
            }
        }

        // --- PERBAIKAN: HARDWARE SCANNER COMPATIBILITY ---
        function addScannerEnterEvent(elementId) {
            const el = document.getElementById(elementId);
            el.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); 
                    this.dispatchEvent(new Event('change')); 
                }
            });
        }
        
        addScannerEnterEvent('memberInput');
        addScannerEnterEvent('bookBorrowInput');
        addScannerEnterEvent('returnInput');

        // --- 1. LOGIC PENCARIAN ANGGOTA ---
        document.getElementById('memberInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            if(!query) return;
            
            const wrapper = document.getElementById('member-scan-wrapper');
            const infoBox = document.getElementById('memberInfo');

            try {
                wrapper.classList.add('opacity-50');

                const res = await fetch('<?php echo e(route("library.circulation.searchStudent")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ q: query })
                });
                
                if (!res.ok) throw new Error('Network response was not ok');
                
                const data = await res.json();

                if(data.success) {
                    audioSuccess.play(); 
                    currentMember = data.student;
                    wrapper.classList.add('border-emerald-500', 'bg-emerald-50');
                    wrapper.classList.remove('border-slate-200', 'bg-slate-50', 'border-rose-500', 'bg-rose-50', 'focus-within:border-cyan-500');
                    
                    infoBox.classList.remove('hidden');
                    
                    let activeLoansHtml = '';
                    if(data.active_loan_details && data.active_loan_details.length > 0) {
                        activeLoansHtml = `
                        <div class="mt-4 bg-white/10 rounded-xl p-3 border border-white/10">
                            <p class="text-[10px] uppercase font-bold text-cyan-100 mb-2">Sedang Dipinjam (${data.active_loan_details.length} Buku):</p>
                            <ul class="space-y-1">
                                ${data.active_loan_details.map(loan => `
                                    <li class="flex items-center justify-between text-xs text-white">
                                        <span class="truncate max-w-[150px]"><i class="ph-bold ph-book text-cyan-200 mr-1"></i> ${loan.title}</span>
                                        <span class="${loan.is_overdue ? 'text-rose-300 font-bold' : 'text-emerald-300'}">${loan.due_date}</span>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>`;
                    } else {
                        activeLoansHtml = `<div class="mt-4 text-xs text-cyan-100 bg-white/10 rounded-xl p-3 border border-white/10"><i class="ph-bold ph-check-circle text-emerald-400 mr-1"></i> Tidak ada tanggungan buku.</div>`;
                    }

                    infoBox.innerHTML = `
                        <div class="bg-gradient-to-br from-cyan-600 to-blue-800 rounded-3xl p-6 text-white relative overflow-hidden shadow-lg shadow-cyan-900/30">
                            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="flex items-start gap-4 relative z-10">
                                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-xl font-black text-white border border-white/20 shrink-0">
                                    ${data.student.name.charAt(0)}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-black leading-tight">${data.student.name}</h3>
                                    <p class="text-cyan-100 text-sm font-mono mt-1">${data.student.student_id}</p>
                                    <div class="mt-2">
                                        ${data.has_overdue 
                                            ? '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-rose-500 text-white text-[10px] font-bold uppercase shadow-sm"><i class="ph-bold ph-warning"></i> Ada Tunggakan</span>' 
                                            : '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-500 text-white text-[10px] font-bold uppercase shadow-sm"><i class="ph-bold ph-check"></i> Status Aman</span>'}
                                    </div>
                                    ${activeLoansHtml}
                                </div>
                            </div>
                        </div>
                    `;

                    if(!data.has_overdue) {
                        const bookSection = document.getElementById('bookInputSection');
                        bookSection.classList.remove('opacity-50', 'pointer-events-none');
                        setTimeout(() => { document.getElementById('bookBorrowInput').focus(); }, 500);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Terblokir', text: 'Siswa memiliki buku yang belum dikembalikan melewati tenggat.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
                    }
                } else {
                    audioError.play(); 
                    wrapper.classList.add('border-rose-500', 'bg-rose-50');
                    wrapper.classList.remove('focus-within:border-cyan-500');
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Siswa tidak ditemukan.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
                }
            } catch(err) {
                console.error(err);
                audioError.play();
                Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Gagal menghubungi server. Periksa koneksi internet.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
            } finally {
                wrapper.classList.remove('opacity-50');
            }
        });

        // --- 2. LOGIC PENCARIAN BUKU (PINJAM) ---
        document.getElementById('bookBorrowInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            const wrapper = document.getElementById('book-borrow-scan-wrapper');
            
            if(!query) return;

            try {
                wrapper.classList.add('opacity-50');

                const res = await fetch('<?php echo e(route("library.circulation.searchBook")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ q: query })
                });

                if (!res.ok) throw new Error('Network error');
                const data = await res.json();

                if(data.success && data.is_available) {
                    audioBeep.play(); 
                    currentBook = data.book;
                    wrapper.classList.add('border-emerald-500', 'bg-emerald-50');
                    wrapper.classList.remove('border-slate-200', 'bg-slate-50', 'border-rose-500', 'bg-rose-50', 'focus-within:border-cyan-500');
                    
                    document.getElementById('btnProcessBorrow').disabled = false;
                    document.getElementById('btnProcessBorrow').focus();

                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: 'Buku siap dipinjam: ' + data.book.title, showConfirmButton: false, timer: 2000,
                        customClass: { popup: 'rounded-2xl' }
                    });
                } else {
                    audioError.play();
                    wrapper.classList.add('border-rose-500', 'bg-rose-50');
                    wrapper.classList.remove('focus-within:border-cyan-500');
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.success ? 'Stok buku habis' : 'Buku tidak ditemukan', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
                }
            } catch(err) {
                console.error(err);
                audioError.play();
                Swal.fire({ icon: 'error', title: 'Kesalahan Koneksi', text: 'Tidak dapat memverifikasi buku. Coba lagi.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
                wrapper.classList.remove('border-emerald-500', 'bg-emerald-50');
            } finally {
                wrapper.classList.remove('opacity-50');
            }
        });

        // --- 3. LOGIC PROSES PEMINJAMAN ---
        async function processBorrow() {
            if(!currentMember || !currentBook) return;
             
             try {
                const res = await fetch('<?php echo e(route("library.circulation.store")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ student_id: currentMember.id, book_id: currentBook.id })
                });
                
                if (!res.ok) throw new Error('Server Error');
                const data = await res.json();
                
                if(data.success) {
                    audioSuccess.play();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Transaksi peminjaman sukses!', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }}).then(() => {
                        resetBorrow();
                        window.location.reload(); 
                    });
                } else {
                    audioError.play();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
                }
            } catch (err) {
                console.error(err);
                audioError.play();
                Swal.fire({ icon: 'error', title: 'Gagal Memproses', text: 'Terjadi kesalahan saat menyimpan data peminjaman. Silakan coba lagi.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
            }
        }

        function resetBorrow() {
            currentMember = null;
            currentBook = null;
            document.getElementById('memberInput').value = '';
            document.getElementById('bookBorrowInput').value = '';
            
            document.getElementById('memberInfo').classList.add('hidden');
            document.getElementById('memberInfo').innerHTML = '';
            
            const bookSection = document.getElementById('bookInputSection');
            bookSection.classList.add('opacity-50', 'pointer-events-none');
            
            document.getElementById('member-scan-wrapper').className = "flex-1 flex items-center px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl focus-within:border-cyan-500 focus-within:bg-white focus-within:shadow-lg transition-all group-hover/step:border-slate-300";
            document.getElementById('book-borrow-scan-wrapper').className = "flex-1 flex items-center px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl focus-within:border-cyan-500 focus-within:bg-white focus-within:shadow-lg transition-all";
            
            document.getElementById('btnProcessBorrow').disabled = true;
            document.getElementById('memberInput').focus();
        }

        // --- 4. LOGIC CEK PENGEMBALIAN ---
        document.getElementById('returnInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            e.target.value = '';
            const infoBox = document.getElementById('returnInfo');
            
            infoBox.classList.remove('hidden');
            infoBox.innerHTML = '<div class="text-center py-8"><div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto"></div></div>';

            try {
                const res = await fetch('<?php echo e(route("library.circulation.return")); ?>?check_only=1', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ book_code: query })
                });
                
                if (!res.ok) throw new Error('Network error');
                const result = await res.json();

                if(result.success) {
                    audioBeep.play();
                    const data = result.data;
                    let dendaHtml = data.fine > 0 
                        ? `<div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl mb-4"><p class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Denda Keterlambatan</p><p class="text-2xl font-black text-rose-600 mt-1">Rp ${new Intl.NumberFormat('id-ID').format(data.fine)}</p></div>`
                        : `<div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl mb-4"><p class="text-sm font-bold text-emerald-600 flex items-center justify-center gap-2"><i class="ph-bold ph-check-circle text-xl"></i> Pengembalian Tepat Waktu</p></div>`;

                    infoBox.innerHTML = `
                        <div class="bg-white rounded-[2rem] border-2 border-slate-100 p-6 text-center mt-6 shadow-xl shadow-slate-200/50 animate-fade-in-up">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500 font-black border-4 border-white shadow-lg text-2xl">
                                ${data.student_name.charAt(0)}
                            </div>
                            <h3 class="font-black text-slate-800 text-lg leading-tight mb-1">${data.student_name}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-6">Mengembalikan Buku</p>
                            ${dendaHtml}
                            <button onclick="confirmReturn('${query}')" id="btnConfirmReturn" class="w-full py-4 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30 transform active:scale-95">
                                Konfirmasi Pengembalian
                            </button>
                        </div>
                    `;
                    
                    document.getElementById('btnConfirmReturn').focus();
                } else {
                    audioError.play();
                    infoBox.innerHTML = `<div class="p-5 bg-rose-50 text-rose-600 font-bold text-center rounded-[1.5rem] border border-rose-100 mt-6 shadow-sm"><i class="ph-bold ph-warning-circle text-2xl mb-2 block"></i> ${result.message}</div>`;
                }
            } catch (err) {
                console.error(err);
                audioError.play();
                infoBox.innerHTML = ''; 
                infoBox.classList.add('hidden'); 
                Swal.fire({ icon: 'error', title: 'Terputus', text: 'Gagal mengecek data buku. Pastikan server aktif.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
            }
        });

        // --- 5. LOGIC KONFIRMASI PENGEMBALIAN ---
        async function confirmReturn(bookCode) {
            try {
                const res = await fetch('<?php echo e(route("library.circulation.return")); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ book_code: bookCode })
                });
                
                if (!res.ok) throw new Error('Network error');
                const data = await res.json();
                
                if(data.success) {
                    audioSuccess.play();
                    Swal.fire({ icon: 'success', title: 'Sukses', text: 'Buku berhasil dikembalikan.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }}).then(() => {
                        window.location.reload(); 
                    });
                    document.getElementById('returnInfo').innerHTML = '';
                    document.getElementById('returnInfo').classList.add('hidden');
                } else {
                     audioError.play();
                     Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal menyimpan data.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
                }
            } catch(err) {
                console.error(err);
                audioError.play();
                Swal.fire({ icon: 'error', title: 'Kesalahan Jaringan', text: 'Buku gagal dikembalikan karena masalah koneksi.', confirmButtonColor: '#0891b2', customClass: { popup: 'rounded-[2rem]' }});
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/circulation.blade.php ENDPATH**/ ?>