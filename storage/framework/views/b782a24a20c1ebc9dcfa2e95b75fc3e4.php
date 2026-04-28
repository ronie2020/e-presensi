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
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen" x-data="promotionApp('<?php echo e(old('academic_year')); ?>', '<?php echo e(old('target_action')); ?>')">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest mb-3 shadow-sm backdrop-blur-sm">
                            <i class="ph-fill ph-arrows-left-right"></i> Mutasi Massal
                        </div>
                        <h1 class="text-3xl font-black text-elevate-dark tracking-tight mb-2">Kenaikan Kelas & Kelulusan</h1>
                        <p class="text-elevate-dark/80 text-sm max-w-xl font-medium">
                            Kelola pemindahan siswa antar kelas saat pergantian tahun ajaran baru atau luluskan siswa kelas akhir ke Database Alumni.
                        </p>
                    </div>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-[1.5rem] flex items-start gap-3 shadow-sm animate-enter">
                    <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold text-sm mb-1">Gagal memproses permintaan:</p>
                        <ul class="list-disc list-inside text-xs font-medium space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 sticky top-24">
                        <form method="GET" action="<?php echo e(route('promotions.index')); ?>" id="filterForm">
                            <h3 class="font-black text-elevate-dark mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-bold ph-funnel text-elevate-primary"></i> Pilih Kelas Asal
                            </h3>
                            
                            <div class="relative">
                                <select name="from_class_id" onchange="document.getElementById('filterForm').submit()" 
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary mb-4 py-3 px-4 appearance-none cursor-pointer">
                                    <option value="">-- Silakan Pilih --</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>" <?php echo e(request('from_class_id') == $class->id ? 'selected' : ''); ?>>
                                            Kelas <?php echo e($class->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 mb-4"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                            
                            <div class="p-4 bg-elevate-accent/10 rounded-xl border border-elevate-accent/20 text-[10px] text-elevate-dark font-medium leading-relaxed shadow-sm">
                                <i class="ph-fill ph-info block text-lg mb-1 text-elevate-primary"></i>
                                Pilih kelas di atas untuk memunculkan daftar siswa aktif. Hanya siswa yang dicentang yang akan diproses.
                            </div>
                        </form>
                    </div>
                </div>

                
                <div class="lg:col-span-3">
                    <?php if(request('from_class_id') && count($students ?? []) > 0): ?>
                        
                        <form action="<?php echo e(route('promotions.process')); ?>" method="POST" id="promotionForm">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="from_class_id" value="<?php echo e(request('from_class_id')); ?>">
                            
                            
                            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row items-end gap-4 relative overflow-hidden">
                                
                                
                                 <div class="w-full md:w-48 relative z-10 shrink-0">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Ajaran Lanjutan</label>
                                    <input type="text" name="academic_year" x-model="academicYear" placeholder="Cth: 2024/2025" required 
                                            pattern="\d{4}/\d{4}" title="Gunakan format YYYY/YYYY, contoh: 2024/2025"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary h-12 transition-all px-4 <?php echo e($errors->has('academic_year') ? 'border-rose-500 bg-rose-50' : ''); ?>">
                                </div>

                                
                                <div class="flex-1 w-full relative z-10">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tujuan Pemindahan</label>
                                    <div class="flex gap-3 relative">
                                        <select name="target_action" x-model="targetAction" required 
                                                class="flex-1 rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary h-12 transition-all px-4 cursor-pointer appearance-none <?php echo e($errors->has('target_action') ? 'border-rose-500 bg-rose-50' : ''); ?>">
                                            <option value="">-- Pilih Kelas Tujuan --</option>
                                            
                                            <optgroup label="Tujuan: Pindah / Naik Kelas">
                                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($class->id != request('from_class_id')): ?>
                                                        <option value="<?php echo e($class->id); ?>">Pindahkan ke <?php echo e($class->name); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>

                                            <optgroup label="Kelulusan">
                                                <option value="alumni">Luluskan Siswa (Jadikan Alumni)</option>
                                            </optgroup>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                
                                
                                <button type="button" @click="confirmProcess()" 
                                        class="w-full md:w-auto px-8 py-3 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-xl shadow-lg shadow-elevate-dark/20 transition-all flex items-center justify-center gap-2 h-12 shrink-0 relative z-10 active:scale-95 group">
                                    <i class="ph-bold ph-magic-wand group-hover:scale-110 transition-transform"></i> Eksekusi
                                </button>
                                
                                
                                <div x-show="targetAction === 'alumni'" x-transition.opacity 
                                     class="absolute inset-0 bg-gradient-to-r from-amber-50 to-orange-50/50 pointer-events-none z-0"></div>
                            </div>

                            
                            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col min-h-[600px]">
                                
                                
                                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4 justify-between items-center">
                                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2 shrink-0">
                                        <i class="ph-fill ph-users-three text-elevate-primary"></i> Daftar Siswa Terpilih
                                    </h3>

                                    <div class="flex items-center gap-2 w-full md:w-auto">
                                        <div class="relative flex-1 md:w-64">
                                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                            <input type="text" id="search-student" placeholder="Cari di tabel ini..." 
                                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-elevate-primary focus:ring-elevate-primary text-xs font-bold text-elevate-dark shadow-sm">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex-1 overflow-x-auto custom-scrollbar relative">
                                    <table class="w-full text-sm text-left text-slate-600" id="students-table">
                                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100 sticky top-0 z-20 shadow-sm">
                                            <tr>
                                                <th class="px-6 py-4 w-16 text-center">
                                                    
                                                    <input type="checkbox" x-model="checkAll" @change="toggleAll()" 
                                                           class="rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary w-5 h-5 cursor-pointer shadow-sm">
                                                </th>
                                                <th class="px-6 py-4">Nama Lengkap Siswa</th>
                                                <th class="px-6 py-4">NIS / NISN</th>
                                                <th class="px-6 py-4">Jenis Kelamin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="hover:bg-slate-50/80 transition-colors cursor-pointer student-row group" @click="toggleRow('<?php echo e($student->id); ?>')">
                                                <td class="px-6 py-4 text-center">
                                                    
                                                    <input type="checkbox" name="student_ids[]" value="<?php echo e($student->id); ?>" id="chk-<?php echo e($student->id); ?>"
                                                           class="student-checkbox rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary w-5 h-5 cursor-pointer shadow-sm"
                                                           <?php echo e((is_array(old('student_ids')) && in_array($student->id, old('student_ids'))) || !old('student_ids') ? 'checked' : ''); ?>

                                                           @click.stop="updateCheckAll()">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-elevate-accent/10 border border-elevate-accent/20 flex items-center justify-center text-xs text-elevate-primary font-bold overflow-hidden shrink-0 group-hover:bg-elevate-primary group-hover:text-white transition-colors">
                                                            <?php if($student->photo_path): ?>
                                                                <img src="<?php echo e(asset('storage/'.$student->photo_path)); ?>" class="w-full h-full object-cover">
                                                            <?php else: ?>
                                                                <?php echo e(substr($student->name, 0, 1)); ?>

                                                            <?php endif; ?>
                                                        </div>
                                                        <span class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors student-name"><?php echo e($student->name); ?></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                                    <?php echo e($student->nisn ?? $student->student_id); ?>

                                                </td>
                                                <td class="px-6 py-4">
                                                    <?php if($student->gender === 'L'): ?>
                                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold bg-elevate-accent/10 text-elevate-primary">
                                                            Laki-laki
                                                        </span>
                                                    <?php elseif($student->gender === 'P'): ?>
                                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-600">
                                                            Perempuan
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-500">
                                                            Belum Diisi
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                
                                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center text-xs font-bold text-slate-500">
                                    <span>Total: <?php echo e(count($students ?? [])); ?> Siswa</span>
                                    <span>Terpilih: <span id="selected-count" class="text-elevate-primary font-black">0</span></span>
                                </div>
                            </div>
                        </form>

                    <?php elseif(request('from_class_id')): ?>
                        
                        <div class="bg-white rounded-[2.5rem] p-16 text-center shadow-sm border border-slate-100 mt-6 lg:mt-0">
                            <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <i class="ph-duotone ph-users-three text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-black text-elevate-dark mb-2">Tidak Ada Siswa</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto">Tidak ada siswa aktif yang ditemukan di kelas ini. Mungkin sudah diluluskan atau dipindahkan semua.</p>
                        </div>
                    <?php else: ?>
                        
                        <div class="bg-slate-50/50 rounded-[2.5rem] p-16 text-center border-2 border-dashed border-slate-200 h-full min-h-[400px] flex flex-col items-center justify-center mt-6 lg:mt-0">
                            <i class="ph-duotone ph-arrow-left text-4xl text-elevate-accent mb-4 animate-bounce"></i>
                            <h3 class="text-base font-bold text-elevate-dark mb-1">Menunggu Pilihan Kelas</h3>
                            <p class="text-slate-500 text-sm font-medium">Pilih kelas asal di menu sebelah kiri untuk memulai.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('promotionApp', (oldAcademicYear = '', oldTargetAction = '') => ({
                checkAll: true,
                targetAction: oldTargetAction,
                academicYear: oldAcademicYear,
                
                init() {
                    this.updateCheckAll(); // Cek status saat load, berguna jika ada error validasi
                },
                
                toggleAll() {
                    const checkboxes = document.querySelectorAll('.student-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checkAll);
                },
                
                updateCheckAll() {
                    const checkboxes = document.querySelectorAll('.student-checkbox');
                    if(checkboxes.length === 0) return;
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    this.checkAll = allChecked;
                },

                toggleRow(id) {
                    const cb = document.getElementById('chk-' + id);
                    if(cb) {
                        cb.checked = !cb.checked;
                        this.updateCheckAll();
                    }
                },

                confirmProcess() {
                    const checkboxes = document.querySelectorAll('.student-checkbox:checked');
                    if (checkboxes.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Siswa!',
                            text: 'Anda belum memilih satupun siswa yang akan diproses.',
                            confirmButtonColor: '#3b5889',
                            customClass: { popup: 'rounded-[2.5rem]' }
                        });
                        return;
                    }

                    if (!this.targetAction || !this.academicYear) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap!',
                            text: 'Pastikan Anda telah memilih tujuan pemindahan dan mengisi Tahun Ajaran Lanjutan.',
                            confirmButtonColor: '#3b5889',
                            customClass: { popup: 'rounded-[2.5rem]' }
                        });
                        return;
                    }

                    // Validasi Format JS (Regex)
                    const yearPattern = /^\d{4}\/\d{4}$/;
                    if (this.academicYear.trim() === '' || !yearPattern.test(this.academicYear.trim())) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Format Tahun Ajaran Salah',
                            text: 'Silakan isi Tahun Ajaran dengan format YYYY/YYYY (Contoh: 2024/2025).',
                            confirmButtonColor: '#3b5889',
                            customClass: { popup: 'rounded-[2.5rem]' }
                        });
                        return;
                    }

                    let actionText = 'memproses data';
                    if (this.targetAction === 'alumni') {
                        actionText = 'Meluluskan (Menjadi Alumni)';
                    } else {
                        actionText = 'Memindahkan / Menaikkan Kelas';
                    }

                    Swal.fire({
                        title: 'Konfirmasi Tindakan',
                        html: `Anda akan <b>${actionText}</b> untuk <b>${checkboxes.length} siswa</b> di Tahun Ajaran <b>${this.academicYear}</b>. Lanjutkan?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b5889', // elevate-primary
                        cancelButtonColor: '#94a3b8', // slate-400
                        confirmButtonText: 'Ya, Proses Sekarang!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                            confirmButton: 'bg-elevate-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-elevate-dark transition-colors mx-2 shadow-lg shadow-elevate-primary/20',
                            cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses Data...',
                                text: 'Mohon tunggu sebentar, sedang memperbarui status siswa.',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); },
                                customClass: { popup: 'rounded-[2.5rem] font-sans' }
                            });
                            document.getElementById('promotionForm').submit();
                        }
                    });
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Flash Messages Success/Error
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    confirmButtonColor: '#3b5889',
                    customClass: { popup: 'rounded-[2.5rem] border border-slate-100 shadow-xl' }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo e(session('error')); ?>",
                    confirmButtonColor: '#e11d48',
                    customClass: { popup: 'rounded-[2.5rem] border border-slate-100 shadow-xl' }
                });
            <?php endif; ?>

            // Checkbox Select All Logic (Untuk menghitung jumlah saja)
            const selectAllBtn = document.getElementById('selectAll');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const selectedCountDisplay = document.getElementById('selected-count');

            function updateSelectedCount() {
                const count = document.querySelectorAll('.student-checkbox:checked').length;
                if(selectedCountDisplay) selectedCountDisplay.innerText = count;
            }

            if(selectAllBtn) {
                selectAllBtn.addEventListener('change', function() {
                    studentCheckboxes.forEach(cb => {
                        // Hanya centang row yang sedang terlihat (tidak ter-filter search)
                        if(cb.closest('tr').style.display !== 'none') {
                            cb.checked = this.checked;
                        }
                    });
                    updateSelectedCount();
                });
            }

            studentCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectedCount);
            });

            // Panggil sekali saat load
            updateSelectedCount();

            // Client-side Search Logic (Mencari di dalam tabel yang sudah dirender)
            const searchInput = document.getElementById('search-student');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const filter = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.student-row');
                    
                    rows.forEach(row => {
                        const name = row.querySelector('.student-name').textContent.toLowerCase();
                        if (name.includes(filter)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                            // Uncheck if hidden
                            const cb = row.querySelector('.student-checkbox');
                            if(cb) cb.checked = false;
                        }
                    });
                    updateSelectedCount();
                });
            }
        });
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/promotions/index.blade.php ENDPATH**/ ?>