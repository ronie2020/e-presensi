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

    
    <div class="py-8 font-sans text-slate-800" x-data="promotionApp('<?php echo e(old('academic_year')); ?>', '<?php echo e(old('target_action')); ?>')">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2.5rem] bg-slate-900 overflow-hidden p-8 sm:p-10 mb-8 shadow-xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-[10px] font-bold uppercase tracking-widest mb-3">
                            <i class="ph-fill ph-arrows-left-right"></i> Mutasi Massal
                        </div>
                        <h1 class="text-3xl font-black text-white tracking-tight mb-2">Kenaikan Kelas & Kelulusan</h1>
                        <p class="text-slate-400 text-sm max-w-xl">
                            Kelola pemindahan siswa antar kelas saat pergantian tahun ajaran baru atau luluskan siswa kelas akhir ke Database Alumni.
                        </p>
                    </div>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-start gap-3 shadow-sm">
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
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-8">
                        <form method="GET" action="<?php echo e(route('promotions.index')); ?>" id="filterForm">
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-bold ph-funnel text-blue-500"></i> Pilih Kelas Asal
                            </h3>
                            
                            <select name="from_class_id" onchange="document.getElementById('filterForm').submit()" 
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-slate-600 focus:ring-blue-500 focus:border-blue-500 mb-4 h-11">
                                <option value="">-- Silakan Pilih --</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>" <?php echo e(request('from_class_id') == $class->id ? 'selected' : ''); ?>>
                                        Kelas <?php echo e($class->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 text-[10px] text-blue-600 font-medium leading-relaxed">
                                <i class="ph-fill ph-info block text-lg mb-1"></i>
                                Pilih kelas di atas untuk memunculkan daftar siswa aktif. Hanya siswa yang dicentang yang akan diproses.
                            </div>
                        </form>
                    </div>
                </div>

                
                <div class="lg:col-span-3">
                    <?php if(request('from_class_id') && count($students) > 0): ?>
                        
                        <form action="<?php echo e(route('promotions.process')); ?>" method="POST" id="promotionForm">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="from_class_id" value="<?php echo e(request('from_class_id')); ?>">
                            
                            
                            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 mb-6 flex flex-col md:flex-row items-end gap-4 relative overflow-hidden">
                                
                                
                                 <div class="w-full md:w-48 relative z-10 shrink-0">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Ajaran</label>
                                    <input type="text" name="academic_year" x-model="academicYear" placeholder="Cth: 2024/2025" required 
                                           pattern="\d{4}/\d{4}" title="Gunakan format YYYY/YYYY, contoh: 2024/2025"
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-slate-700 focus:ring-blue-500 h-12 transition-all <?php echo e($errors->has('academic_year') ? 'border-rose-500 bg-rose-50' : ''); ?>">
                                </div>

                                
                                <div class="flex-1 w-full relative z-10">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tujuan Pemindahan</label>
                                    <div class="flex gap-3">
                                        <select name="target_action" x-model="targetAction" required 
                                                class="flex-1 rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-slate-700 focus:ring-blue-500 h-12 transition-all cursor-pointer <?php echo e($errors->has('target_action') ? 'border-rose-500 bg-rose-50' : ''); ?>">
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
                                    </div>
                                </div>
                                
                                
                                <button type="button" @click="confirmProcess()" 
                                        class="w-full md:w-auto px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 h-12 shrink-0 relative z-10">
                                    <i class="ph-bold ph-magic-wand"></i> Eksekusi
                                </button>
                                
                                
                                <div x-show="targetAction === 'alumni'" x-transition.opacity 
                                     class="absolute inset-0 bg-gradient-to-r from-amber-50 to-orange-50/50 pointer-events-none z-0"></div>
                            </div>

                            
                            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-slate-600">
                                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                                            <tr>
                                                <th class="px-6 py-4 w-16 text-center">
                                                    
                                                    <input type="checkbox" x-model="checkAll" @change="toggleAll()" 
                                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer shadow-sm">
                                                </th>
                                                <th class="px-6 py-4">Nama Lengkap Siswa</th>
                                                <th class="px-6 py-4">NIS / NISN</th>
                                                <th class="px-6 py-4">Jenis Kelamin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" @click="toggleRow('<?php echo e($student->id); ?>')">
                                                <td class="px-6 py-4 text-center">
                                                    
                                                    <input type="checkbox" name="student_ids[]" value="<?php echo e($student->id); ?>" id="chk-<?php echo e($student->id); ?>"
                                                           class="student-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer shadow-sm"
                                                           <?php echo e((is_array(old('student_ids')) && in_array($student->id, old('student_ids'))) || !old('student_ids') ? 'checked' : ''); ?>

                                                           @click.stop="updateCheckAll()">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs text-slate-500 font-bold overflow-hidden shrink-0">
                                                            <?php if($student->photo_path): ?>
                                                                <img src="<?php echo e(asset('storage/'.$student->photo_path)); ?>" class="w-full h-full object-cover">
                                                            <?php else: ?>
                                                                <?php echo e(substr($student->name, 0, 1)); ?>

                                                            <?php endif; ?>
                                                        </div>
                                                        <span class="font-bold text-slate-800"><?php echo e($student->name); ?></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                                    <?php echo e($student->nisn ?? $student->student_id); ?>

                                                </td>
                                                <td class="px-6 py-4">
                                                    <?php if($student->gender === 'L'): ?>
                                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-600">
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
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-xs text-slate-400 font-medium">
                                    Total: <span class="font-bold text-slate-700"><?php echo e(count($students)); ?></span> siswa ditampilkan.
                                </div>
                            </div>
                        </form>

                    <?php elseif(request('from_class_id')): ?>
                        
                        <div class="bg-white rounded-[2.5rem] p-16 text-center shadow-xl shadow-slate-200/50 border border-slate-100">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <i class="ph-duotone ph-users-three text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-black text-slate-800 mb-2">Tidak Ada Siswa</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto">Tidak ada siswa aktif yang ditemukan di kelas ini. Mungkin sudah diluluskan atau dipindahkan semua.</p>
                        </div>
                    <?php else: ?>
                        
                        <div class="bg-slate-50/50 rounded-[2.5rem] p-16 text-center border-2 border-dashed border-slate-200 h-full flex flex-col items-center justify-center">
                            <i class="ph-duotone ph-arrow-left text-4xl text-slate-300 mb-4 animate-bounce"></i>
                            <h3 class="text-base font-bold text-slate-700 mb-1">Menunggu Pilihan Kelas</h3>
                            <p class="text-slate-500 text-sm font-medium">Pilih kelas asal di menu sebelah kiri untuk memulai.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('alpine:init', () => {
            // Menerima parameter old values dari Blade
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
                            title: 'Pilih Siswa',
                            text: 'Silakan centang minimal satu siswa untuk diproses.',
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'rounded-3xl' }
                        });
                        return;
                    }

                    // Tambahan Validasi Format JS (Regex)
                    const yearPattern = /^\d{4}\/\d{4}$/;
                    if (this.academicYear.trim() === '' || !yearPattern.test(this.academicYear.trim())) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Format Tahun Ajaran Salah',
                            text: 'Silakan isi Tahun Ajaran dengan format YYYY/YYYY (Contoh: 2024/2025).',
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'rounded-3xl' }
                        });
                        return;
                    }

                    if (this.targetAction === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tujuan Belum Dipilih',
                            text: 'Pilih kelas tujuan terlebih dahulu.',
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'rounded-3xl' }
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Pemindahan',
                        html: `Anda akan memproses <b>${checkboxes.length} siswa</b> untuk Tahun Ajaran <b>${this.academicYear}</b>. Yakin ingin melanjutkan?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: { popup: 'rounded-3xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses Data...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); },
                                customClass: { popup: 'rounded-3xl' }
                            });
                            document.getElementById('promotionForm').submit();
                        }
                    });
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    confirmButtonColor: '#10b981',
                    customClass: { popup: 'rounded-3xl border border-emerald-100' }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo e(session('error')); ?>",
                    confirmButtonColor: '#f43f5e',
                    customClass: { popup: 'rounded-3xl border border-rose-100' }
                });
            <?php endif; ?>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/promotions/index.blade.php ENDPATH**/ ?>