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
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            <?php echo e(__('Edit Materi')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group animate-enter">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight text-elevate-dark">Edit Materi Pelajaran</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">Perbarui informasi, file, atau lampiran materi ini.</p>
                    </div>
                    <a href="<?php echo e(route('lms.materials.index')); ?>" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-colors text-elevate-dark border border-white/60 btn-cancel-confirm shadow-sm active:scale-95 shrink-0">
                        <i class="ph-bold ph-arrow-left text-lg"></i> Batal
                    </a>
                </div>
            </div>

            
            <div class="animate-enter mb-8 bg-blue-50 border border-blue-200 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm" style="animation-delay: 50ms">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl shrink-0 flex items-center justify-center text-2xl">
                    <i class="ph-duotone ph-info"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-blue-900 mb-1">Pro-Tip: Urutan Belajar Siswa</h3>
                    <p class="text-xs font-medium text-blue-700 leading-relaxed">
                        Materi yang sedang Anda ubah ini merupakan bagian dari <b>Alur Belajar Siswa (Learning Player)</b>. Pastikan urutan materinya tetap sesuai dengan yang diharapkan siswa.
                    </p>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-pulse animate-enter">
                    <div class="w-10 h-10 bg-white text-[#D13438] rounded-xl shrink-0 border border-[#F4C3C9] shadow-sm flex items-center justify-center">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-[#D13438] uppercase tracking-wider mb-1 mt-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-[#D13438] space-y-1 font-bold">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php
                $oldNewAttachments = old('new_attachments', []);
                foreach($oldNewAttachments as $k => &$att) {
                    if(!isset($att['id'])) $att['id'] = time() + $k;
                }
            ?>
            
            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-enter" style="animation-delay: 100ms">
                <form action="<?php echo e(route('lms.materials.update', $material->id)); ?>" method="POST" enctype="multipart/form-data" 
                      x-data="{ attachments: <?php echo e(json_encode($oldNewAttachments)); ?> }"
                      id="updateForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="p-6 md:p-10 space-y-8">
                        
                        <!-- BAGIAN 1: INFORMASI UTAMA -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary border border-slate-100 flex items-center justify-center text-2xl shadow-sm"><i class="ph-bold ph-pencil-simple"></i></div>
                                <h3 class="text-xl font-black text-elevate-dark">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Materi <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="<?php echo e(old('title', $material->title)); ?>" required 
                                           class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-black text-elevate-dark focus:ring-elevate-accent/30 focus:border-elevate-accent focus:bg-white h-14 px-5 transition-colors shadow-sm">
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none cursor-pointer focus:bg-white transition-colors shadow-sm">
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id', $material->subject_id) == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Kelas <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <select name="class_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none cursor-pointer focus:bg-white transition-colors shadow-sm">
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($class->id); ?>" <?php echo e(old('class_id', $material->class_id) == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                    <p class="text-[10px] text-slate-500 mt-2 flex items-center gap-1 font-bold"><i class="ph-bold ph-info text-elevate-primary"></i> *Jika ini materi jenjang, pengeditan ini hanya mengubah untuk kelas ini saja.</p>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- BAGIAN 2: DESKRIPSI -->
                        <div>
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">
                                Pengantar & Resume Materi
                            </label>
                            <div class="relative">
                                <textarea name="resume" rows="6" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:ring-elevate-accent/30 focus:border-elevate-accent shadow-sm p-5 text-elevate-dark leading-relaxed font-medium transition-colors focus:bg-white"><?php echo e(old('resume', $material->resume)); ?></textarea>
                                <div class="absolute bottom-4 right-4 text-slate-300 pointer-events-none"><i class="ph-bold ph-text-aa text-xl"></i></div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- BAGIAN 3: LAMPIRAN (EXISTING & NEW) -->
                        <div class="bg-elevate-soft/30 rounded-[2rem] border border-slate-100 p-6 md:p-8">
                            <label class="block text-sm font-black text-elevate-dark flex items-center gap-2 mb-5">
                                <i class="ph-fill ph-paperclip text-elevate-primary text-xl"></i> Kelola Lampiran
                            </label>

                            <!-- Lampiran Lama -->
                            <?php if($material->attachments->count() > 0): ?>
                                <div class="space-y-4 mb-8">
                                    <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest">Lampiran Tersimpan:</p>
                                    <?php $__currentLoopData = $material->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white border border-slate-200 rounded-2xl shadow-sm gap-4">
                                            <div class="flex items-center gap-4 overflow-hidden">
                                                <div class="w-12 h-12 rounded-xl bg-elevate-soft flex items-center justify-center text-elevate-primary shrink-0">
                                                    <i class="ph-bold <?php echo e($att->file_type == 'file' ? 'ph-file-pdf text-xl' : 'ph-link text-xl'); ?>"></i>
                                                </div>
                                                <div class="truncate">
                                                    <p class="text-sm font-black text-elevate-dark truncate leading-tight"><?php echo e($att->file_name); ?></p>
                                                    <a href="<?php echo e($att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path); ?>" target="_blank" class="text-[10px] text-elevate-primary hover:underline font-bold uppercase tracking-wider mt-1 inline-block">Lihat File</a>
                                                </div>
                                            </div>
                                            <label class="flex items-center gap-2.5 cursor-pointer bg-white px-4 py-2 rounded-xl border border-slate-200 hover:bg-[#FDE7E9] hover:border-[#F4C3C9] transition-colors group shrink-0 shadow-sm w-full sm:w-auto justify-center">
                                                <input type="checkbox" name="delete_attachments[]" value="<?php echo e($att->id); ?>" class="rounded text-[#D13438] focus:ring-[#D13438] border-slate-300 w-4 h-4 cursor-pointer">
                                                <span class="text-xs font-bold text-slate-500 group-hover:text-[#D13438] transition-colors">Hapus</span>
                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            <div class="h-px bg-slate-200 mb-6"></div>

                            <!-- Lampiran Baru -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
                                <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest">Tambah Lampiran Baru:</p>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file', link: '', name: ''})" 
                                        class="w-full sm:w-auto text-xs bg-white border border-slate-200 text-elevate-primary px-5 py-3 sm:py-2.5 rounded-xl font-bold hover:bg-elevate-soft hover:border-elevate-accent transition-colors shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-4 p-5 bg-white rounded-2xl border border-slate-200 relative group animate-enter hover:border-elevate-accent/50 transition-colors shadow-sm">
                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <div class="md:col-span-3">
                                                <div class="relative group/sel">
                                                    <select :name="'new_attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-xl border-slate-200 bg-elevate-soft focus:ring-elevate-accent/30 focus:border-elevate-accent cursor-pointer h-12 px-4 appearance-none shadow-sm text-elevate-dark focus:bg-white transition-colors">
                                                        <option value="file">📄 Dokumen</option>
                                                        <option value="video">📺 Video</option>
                                                        <option value="link">🔗 Link</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 group-focus-within/sel:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>
                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'new_attachments['+index+'][file]'" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-soft file:text-elevate-primary h-12 border border-slate-200 rounded-xl bg-white cursor-pointer hover:file:bg-elevate-primary/20 shadow-sm transition-colors">
                                                <input x-show="att.type !== 'file'" type="text" :name="'new_attachments['+index+'][link]'" x-model="att.link" class="w-full text-sm font-medium rounded-xl border-slate-200 h-12 placeholder:text-slate-400 focus:ring-elevate-accent/30 focus:border-elevate-accent text-elevate-dark px-4 shadow-sm bg-elevate-soft focus:bg-white transition-colors" placeholder="https://...">
                                            </div>
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'new_attachments['+index+'][name]'" x-model="att.name" class="w-full text-sm font-medium rounded-xl border-slate-200 h-12 placeholder:text-slate-400 focus:ring-elevate-accent/30 focus:border-elevate-accent text-elevate-dark px-4 shadow-sm bg-elevate-soft focus:bg-white transition-colors" placeholder="Label (Opsional)">
                                            </div>
                                        </div>
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="absolute top-3 right-3 md:static md:mt-1 w-10 h-10 flex items-center justify-center rounded-xl bg-white text-[#D13438] hover:bg-[#FDE7E9] border border-[#F4C3C9] transition-colors shadow-sm" title="Hapus Baris">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-elevate-soft/30 px-6 md:px-10 py-6 border-t border-slate-100 flex flex-col md:flex-row justify-end gap-4">
                        <a href="<?php echo e(route('lms.materials.index')); ?>" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors text-center text-sm btn-cancel-confirm active:scale-95 shadow-sm">Batal</a>
                        
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm active:scale-95 border border-transparent">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const cancelButtons = document.querySelectorAll('.btn-cancel-confirm');
            cancelButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    Swal.fire({
                        title: 'Batalkan Edit?',
                        text: "Perubahan tidak akan disimpan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2c3f61', 
                        cancelButtonColor: '#e5eff5', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: '<span class="text-elevate-dark">Lanjut Mengedit</span>',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                            cancelButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm text-elevate-dark'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });

            const updateForm = document.getElementById('updateForm');
            if(updateForm) {
                updateForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }
                    Swal.fire({
                        title: 'Menyimpan Perubahan...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl', title: 'text-xl font-black text-elevate-dark' }
                    });
                    setTimeout(() => { this.submit(); }, 300);
                });
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/materials/edit.blade.php ENDPATH**/ ?>