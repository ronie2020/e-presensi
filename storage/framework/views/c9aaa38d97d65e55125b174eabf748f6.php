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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Edit Tugas')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-amber-600 via-orange-600 to-rose-600 p-8 mb-8 text-white shadow-xl shadow-orange-900/20 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-yellow-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-extrabold mb-1 tracking-tight">Edit Penugasan</h1>
                        <p class="text-white/80 text-sm font-medium">Perbarui informasi, deadline, atau instruksi tugas.</p>
                    </div>
                    <a href="<?php echo e(route('lms.assignments.index')); ?>" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-bold backdrop-blur-sm transition text-white border border-white/10 btn-cancel-confirm">
                        <i class="ph-bold ph-arrow-left"></i> Batal
                    </a>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-8 bg-rose-50 border border-rose-100 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-pulse">
                    <div class="p-2 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-rose-800 uppercase tracking-wide mb-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-rose-700 space-y-1 font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2rem] border border-slate-100 overflow-hidden">
                <form action="<?php echo e(route('lms.assignments.update', $assignment->id)); ?>" method="POST" id="editAssignmentForm" 
                      x-data="{ 
                          assignmentType: '<?php echo e($assignment->assignment_type); ?>', 
                          questions: <?php echo e($assignment->assignment_type == 'quiz' ? json_encode($assignment->questions) : '[]'); ?> 
                      }">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="p-8 space-y-8">
                        
                        <!-- 1. IDENTITAS TUGAS -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl"><i class="ph-bold ph-pencil-simple"></i></div>
                                <h3 class="text-lg font-black text-slate-800">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Tugas <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" value="<?php echo e(old('title', $assignment->title)); ?>" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-orange-500 focus:border-orange-500 h-12 px-4 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-orange-500 focus:border-orange-500 h-12 px-4 appearance-none transition-colors">
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($subject->id); ?>" <?php echo e($assignment->subject_id == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deadline <span class="text-rose-500">*</span></label>
                                    <input type="datetime-local" name="deadline" value="<?php echo e(old('deadline', $assignment->deadline->format('Y-m-d\TH:i'))); ?>" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-orange-500 focus:border-orange-500 h-12 px-4 transition-colors">
                                </div>

                                <div class="col-span-2">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" <?php echo e($assignment->allow_late_submission ? 'checked' : ''); ?>>
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-600 group-hover:text-orange-700 transition">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. INFO TARGET & TIPE (READ ONLY) -->
                        <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100/50 flex flex-col md:flex-row gap-6">
                            <div class="flex-1">
                                <label class="block text-xs font-black text-blue-800 uppercase tracking-wider mb-2">Target Penerima (Terkunci)</label>
                                <div class="flex items-center gap-2 text-slate-600 font-bold bg-white px-4 py-3 rounded-xl border border-slate-200">
                                    <i class="ph-fill ph-users-three text-blue-500 text-lg"></i>
                                    <?php if($isBulk): ?>
                                        Semua Kelas <?php echo e($assignment->schoolClass ? substr($assignment->schoolClass->name, 0, 1) : 'Jenjang'); ?> (Mode Massal)
                                    <?php else: ?>
                                        Kelas <?php echo e($assignment->schoolClass->name ?? '-'); ?>

                                    <?php endif; ?>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 italic">*Target kelas tidak dapat diubah saat mengedit.</p>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-black text-blue-800 uppercase tracking-wider mb-2">Jenis Tugas (Terkunci)</label>
                                <div class="flex items-center gap-2 text-slate-600 font-bold bg-white px-4 py-3 rounded-xl border border-slate-200">
                                    <?php if($assignment->assignment_type == 'file_upload'): ?>
                                        <i class="ph-duotone ph-upload-simple text-blue-500 text-lg"></i> Upload File
                                    <?php elseif($assignment->assignment_type == 'quiz'): ?>
                                        <i class="ph-duotone ph-brain text-purple-500 text-lg"></i> Kuis Online
                                    <?php else: ?>
                                        <i class="ph-duotone ph-link text-sky-500 text-lg"></i> Link Eksternal
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 3. KONTEN DINAMIS -->
                        <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100">
                            
                            <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi / Soal</label>
                                <textarea name="description" rows="5" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-orange-500 focus:border-orange-500 p-4 text-slate-700 font-medium transition-colors"><?php echo e(old('description', $assignment->description)); ?></textarea>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'">
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">URL Link Tugas <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-link"></i></div>
                                        <input type="url" name="link_url" value="<?php echo e(old('link_url', $assignment->link_url)); ?>" class="w-full rounded-xl border-slate-200 bg-white pl-10 font-bold text-blue-600 focus:ring-orange-500 h-12 transition-colors">
                                    </div>
                                </div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi Tambahan</label>
                                <textarea name="description" rows="3" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-orange-500 p-4 font-medium transition-colors"><?php echo e(old('description', $assignment->description)); ?></textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE -->
                            <div x-show="assignmentType === 'quiz'">
                                <div class="mb-6 flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi Kuis</label>
                                        <textarea name="description" rows="2" class="w-full rounded-xl border-slate-200 bg-white focus:ring-purple-500 p-3 transition-colors"><?php echo e(old('description', $assignment->description)); ?></textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Durasi (Menit) <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="duration_minutes" value="<?php echo e(old('duration_minutes', $assignment->duration_minutes)); ?>" class="w-full rounded-xl border-slate-200 bg-white font-bold text-slate-800 focus:ring-purple-500 h-11 pl-4 pr-10 transition-colors">
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-xs font-bold">MIN</div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-start gap-3 mb-4">
                                    <div class="p-2 bg-amber-100 text-amber-600 rounded-lg shrink-0"><i class="ph-bold ph-warning"></i></div>
                                    <div>
                                        <h4 class="font-bold text-amber-800 text-sm">Peringatan Edit Soal</h4>
                                        <p class="text-xs text-amber-700 mt-1">
                                            Saat ini fitur edit <b>detail butir soal</b> belum tersedia di halaman ini. Jika ada kesalahan fatal pada soal, disarankan untuk membuat tugas baru agar nilai siswa tidak rusak. Anda hanya dapat mengubah instruksi dan durasi waktu di sini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t border-slate-100">
                        <a href="<?php echo e(route('lms.assignments.index')); ?>" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition text-center text-sm btn-cancel-confirm">Batal</a>
                        
                        <button type="submit" class="px-8 py-3 bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-900/20 hover:bg-orange-700 hover:-translate-y-0.5 transition transform flex items-center justify-center gap-2 text-sm">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Proteksi Tombol Batal/Kembali
            const cancelButtons = document.querySelectorAll('.btn-cancel-confirm');
            cancelButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    Swal.fire({
                        title: 'Batalkan Edit?',
                        text: "Perubahan yang belum disimpan akan hilang.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#64748b', // Slate-500
                        cancelButtonColor: '#cbd5e1', // Slate-300
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Edit',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-4 py-2 font-bold',
                            cancelButton: 'rounded-xl px-4 py-2 font-bold text-slate-600'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });

            // 2. Loading saat Submit
            const form = document.getElementById('editAssignmentForm');
            if(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: 'Menyimpan Perubahan...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
                    });

                    setTimeout(() => {
                        this.submit();
                    }, 500);
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/assignments/edit.blade.php ENDPATH**/ ?>