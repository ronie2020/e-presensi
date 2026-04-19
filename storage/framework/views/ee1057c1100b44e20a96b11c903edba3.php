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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="<?php echo e(route('library.circulation.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors group">
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

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-stack"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/10 backdrop-blur-md">
                            Mode Eksemplar Fisik
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight">Distribusi Buku Paket</h2>
                    <p class="text-blue-200 text-sm font-medium relative z-10 mt-2 max-w-xl leading-relaxed">
                        Pindai barcode unik dari setiap fisik buku dan pasangkan dengan nama siswa di kelas untuk mencegah kecurangan tukar buku saat pengembalian.
                    </p>
                </div>

                <div class="p-8">
                    <form action="<?php echo e(route('library.circulation.storeBulk')); ?>" method="POST" id="bulkBorrowForm">
                        <?php echo csrf_field(); ?>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200">
                                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="ph-fill ph-sliders text-blue-500 text-lg"></i> Pengaturan
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Kelas <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-users-three absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                                <select name="class_id" id="class_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-slate-700 transition-all shadow-sm">
                                                    <option value="">-- Pilih Kelas --</option>
                                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Buku Paket <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-books absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                                <select name="book_id" id="book_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-slate-700 transition-all shadow-sm">
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
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tenggat Waktu <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                                <?php $defaultDueDate = \Carbon\Carbon::create(date('Y') + 1, 6, 15)->format('Y-m-d'); ?>
                                                <input type="date" name="due_date" value="<?php echo e($defaultDueDate); ?>" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-slate-700 transition-all shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="lg:col-span-2 flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-black text-slate-800">Daftar Penerima & Kode Fisik Buku</h3>
                                    <div class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg border border-blue-100 text-xs font-bold flex items-center gap-2">
                                        <i class="ph-bold ph-barcode"></i> Terisi: <span id="scannedCount">0</span> Siswa
                                    </div>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-[2rem] flex-1 overflow-hidden flex flex-col shadow-sm relative min-h-[400px]">
                                    
                                    <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 z-10 transition-opacity">
                                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 border border-slate-100 text-slate-300">
                                            <i class="ph-duotone ph-student text-4xl"></i>
                                        </div>
                                        <h4 class="font-black text-slate-600">Pilih Kelas Terlebih Dahulu</h4>
                                    </div>

                                    <div id="loadingState" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm z-20 hidden">
                                        <i class="ph-bold ph-spinner animate-spin text-4xl text-blue-500 mb-2"></i>
                                    </div>

                                    <div class="overflow-y-auto custom-scrollbar flex-1">
                                        <table class="w-full text-left border-collapse" id="studentTable">
                                            <thead class="sticky top-0 z-10">
                                                <tr class="bg-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                                                    <th class="px-6 py-4">Nama Siswa</th>
                                                    <th class="px-6 py-4 w-1/2">Scan Barcode Buku (Item Code)</th>
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
                            <p class="text-xs text-slate-400 font-medium">
                                <i class="ph-fill ph-info text-blue-500"></i> Hanya siswa dengan input barcode terisi yang akan tersimpan.
                            </p>
                            <button type="button" id="btnSubmit" onclick="confirmBulkSubmit()" disabled class="px-8 py-3.5 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-xl shadow-blue-600/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Distribusi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const studentContainer = document.getElementById('studentListContainer');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const scannedCountDisplay = document.getElementById('scannedCount');
            const btnSubmit = document.getElementById('btnSubmit');

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
                        studentContainer.innerHTML = `<tr><td colspan="2" class="px-6 py-10 text-center text-slate-400">Data siswa kosong.</td></tr>`;
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data.' });
                } finally {
                    loadingState.classList.add('hidden');
                    updateCounter();
                }
            });

            function renderStudents(students) {
                let html = '';
                students.forEach((student, index) => {
                    html += `
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700 text-sm group-hover:text-blue-700">${student.name}</div>
                                <div class="text-xs text-slate-400 font-mono">${student.nisn || student.student_id || '-'}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <i class="ph-bold ph-barcode absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="item_codes[${student.id}]" class="item-code-input w-full pl-9 pr-4 py-2 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-mono font-bold text-slate-700 text-sm" placeholder="Scan kode buku kesini..." oninput="updateCounter()" onkeydown="focusNext(event, ${index})">
                                </div>
                            </td>
                        </tr>
                    `;
                });
                studentContainer.innerHTML = html;
            }

            // Fungsi agar saat dienter pindah ke input bawahnya
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
                inputs.forEach(input => { if(input.value.trim() !== '') filledCount++; });
                
                scannedCountDisplay.innerText = filledCount;
                const bookSelected = document.getElementById('book_id').value !== '';
                btnSubmit.disabled = filledCount === 0 || !bookSelected;
            }
            document.getElementById('book_id').addEventListener('change', updateCounter);
        });

        window.confirmBulkSubmit = function() {
            const inputs = document.querySelectorAll('.item-code-input');
            let filledCount = 0;
            inputs.forEach(input => { if(input.value.trim() !== '') filledCount++; });

            const bookSelect = document.getElementById('book_id');
            const stockAvailable = parseInt(bookSelect.options[bookSelect.selectedIndex].getAttribute('data-stock'));

            if (filledCount > stockAvailable) {
                Swal.fire({ icon: 'error', title: 'Stok Kurang!', text: `Anda men-scan ${filledCount} buku, tapi stok tersisa ${stockAvailable}.` });
                return;
            }

            Swal.fire({
                title: 'Simpan Distribusi?',
                html: `Memproses peminjaman untuk <strong>${filledCount} Siswa</strong>.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                confirmButtonColor: '#2563eb',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/circulation/bulk-borrow.blade.php ENDPATH**/ ?>