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
            <?php echo e(__('Upload Materi Baru')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight text-elevate-dark">Upload Materi Baru</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">Lengkapi formulir di bawah ini untuk membagikan materi ke siswa.</p>
                    </div>
                    <a href="<?php echo e(route('lms.materials.index')); ?>" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-colors text-elevate-dark border border-white/60 shadow-sm btn-cancel-confirm active:scale-95 shrink-0">
                        <i class="ph-bold ph-arrow-left text-lg"></i> Kembali
                    </a>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="animate-enter mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm">
                    <div class="w-10 h-10 bg-white text-[#D13438] rounded-xl shrink-0 shadow-sm border border-[#F4C3C9] flex items-center justify-center">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-[#D13438] uppercase tracking-wider mb-1 mt-1">Gagal Mengupload</h3>
                        <ul class="list-disc list-inside text-sm text-[#D13438] space-y-1 font-bold">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="animate-enter mb-8 bg-blue-50 border border-blue-200 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm" style="animation-delay: 50ms">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl shrink-0 flex items-center justify-center text-2xl">
                    <i class="ph-duotone ph-info"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-blue-900 mb-1">Pro-Tip: Urutan Belajar Siswa</h3>
                    <p class="text-xs font-medium text-blue-700 leading-relaxed">
                        Materi yang Anda unggah akan otomatis masuk ke <b>Alur Belajar Siswa (Learning Player)</b>. Pastikan Anda mengunggah materi ini sebelum memberikan Tugas/Latihan yang berkaitan agar alurnya runtut.
                    </p>
                </div>
            </div>

            
            <?php               
                $oldAttachments = old('attachments', [['id' => time(), 'type' => 'file', 'link' => '', 'name' => '']]);   
                foreach($oldAttachments as $k => &$att) {
                    if(!isset($att['id'])) $att['id'] = time() + $k;
                }
            ?>

            
            <div class="animate-enter bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden" style="animation-delay: 100ms">
                <form action="<?php echo e(route('lms.materials.store')); ?>" method="POST" enctype="multipart/form-data" 
                      x-data="{ 
                          targetType: '<?php echo e(old('target_type', 'class')); ?>', 
                          attachments: <?php echo e(json_encode($oldAttachments)); ?> 
                      }"
                      id="uploadForm">
                    <?php echo csrf_field(); ?>

                    <div class="p-6 md:p-10 space-y-8">
                        
                        <!-- BAGIAN 1: INFORMASI UTAMA -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-2xl border border-slate-100 shadow-sm"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-xl font-black text-elevate-dark">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Materi <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" required 
                                           class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-black text-elevate-dark focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 placeholder:font-bold placeholder:text-slate-400 transition-colors focus:bg-white shadow-sm" 
                                           placeholder="Contoh: Bab 1 - Ekosistem">
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none transition-colors focus:bg-white cursor-pointer shadow-sm">
                                            <option value="">-- Pilih Mapel --</option>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id') == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                
                                <div class="bg-elevate-soft/50 p-5 rounded-2xl border border-slate-100">
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <i class="ph-fill ph-users-three text-lg"></i> Bagikan Kepada:
                                    </label>
                                    <div class="space-y-4">
                                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                            <label class="flex-1 inline-flex items-center cursor-pointer group bg-white px-4 py-3 border border-slate-200 rounded-xl hover:border-elevate-accent shadow-sm transition-all">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="target_type" value="class" x-model="targetType" class="peer sr-only">
                                                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-elevate-primary peer-checked:bg-elevate-primary transition-colors"></div>
                                                </div>
                                                <span class="ml-3 text-sm font-black text-elevate-dark group-hover:text-elevate-primary transition-colors">Satu Kelas</span>
                                            </label>
                                            <label class="flex-1 inline-flex items-center cursor-pointer group bg-white px-4 py-3 border border-slate-200 rounded-xl hover:border-elevate-accent shadow-sm transition-all">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="target_type" value="grade" x-model="targetType" class="peer sr-only">
                                                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-elevate-primary peer-checked:bg-elevate-primary transition-colors"></div>
                                                </div>
                                                <span class="ml-3 text-sm font-black text-elevate-dark group-hover:text-elevate-primary transition-colors">Satu Jenjang</span>
                                            </label>
                                        </div>
                                        
                                        <!-- SELECT KELAS -->
                                        <div x-show="targetType === 'class'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                            <div class="relative group">
                                                <select name="class_id" 
                                                        :required="targetType === 'class'"
                                                        class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-12 px-4 appearance-none shadow-sm cursor-pointer text-elevate-dark transition-colors">
                                                    <option value="">-- Pilih Kelas Spesifik --</option>
                                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($class->id); ?>" <?php echo e(old('class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>

                                        <!-- SELECT JENJANG -->
                                        <div x-cloak x-show="targetType === 'grade'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                            <div class="relative group">
                                                <select name="target_grade" 
                                                        :required="targetType === 'grade'"
                                                        class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-12 px-4 appearance-none shadow-sm cursor-pointer text-elevate-dark transition-colors">
                                                    <option value="7" <?php echo e(old('target_grade') == '7' ? 'selected' : ''); ?>>Semua Kelas 7</option>
                                                    <option value="8" <?php echo e(old('target_grade') == '8' ? 'selected' : ''); ?>>Semua Kelas 8</option>
                                                    <option value="9" <?php echo e(old('target_grade') == '9' ? 'selected' : ''); ?>>Semua Kelas 9</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                            <p class="text-[10px] text-slate-500 font-bold mt-2 flex items-center gap-1.5"><i class="ph-bold ph-info text-elevate-primary"></i> Sistem akan membagikan ke seluruh kelas dengan awalan angka tersebut.</p>
                                        </div>
                                    </div>
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
                                <textarea name="resume" rows="6" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:ring-elevate-accent/30 focus:border-elevate-accent shadow-sm p-5 text-elevate-dark leading-relaxed font-medium placeholder:font-normal placeholder:text-slate-400 transition-colors focus:bg-white" placeholder="Tuliskan rangkuman materi, tujuan pembelajaran, atau instruksi untuk siswa..."><?php echo e(old('resume')); ?></textarea>
                                <div class="absolute bottom-4 right-4 text-slate-300 pointer-events-none"><i class="ph-bold ph-text-aa text-xl"></i></div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                         <!-- BAGIAN 3: LAMPIRAN (DYNAMIC) -->
                        <div class="bg-elevate-soft/30 rounded-[2rem] border border-slate-100 p-6 md:p-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <label class="block text-sm font-black text-elevate-dark flex items-center gap-2">
                                        <i class="ph-fill ph-paperclip text-elevate-primary text-xl"></i> Referensi & Lampiran
                                    </label>
                                    <p class="text-[11px] font-bold text-slate-400 mt-1">Upload file dokumen atau tautkan video pembelajaran.</p>
                                </div>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file', link: '', name: ''})" 
                                        class="w-full sm:w-auto text-xs bg-white border border-slate-200 text-elevate-primary px-5 py-3 sm:py-2.5 rounded-xl font-bold hover:bg-elevate-soft hover:border-elevate-accent transition-colors shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-4 p-5 bg-white rounded-2xl border border-slate-200 relative group transition-all hover:border-elevate-accent/50 hover:shadow-md animate-enter" style="animation-duration: 0.3s">
                                        
                                        <!-- Nomor -->
                                        <div class="hidden md:flex w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary text-sm font-black items-center justify-center border border-slate-100 shrink-0 shadow-sm" x-text="index + 1"></div>

                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <!-- Pilihan Tipe -->
                                            <div class="md:col-span-3">
                                                <div class="relative group/sel">
                                                    <select :name="'attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-xl border-slate-200 focus:ring-elevate-accent/30 focus:border-elevate-accent bg-elevate-soft cursor-pointer h-12 px-4 appearance-none shadow-sm text-elevate-dark focus:bg-white transition-colors">
                                                        <option value="file">📄 Dokumen (File)</option>
                                                        <option value="video">📺 Video (YouTube)</option>
                                                        <option value="link">🔗 Link Website</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 group-focus-within/sel:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>

                                            <!-- Input File / Link -->
                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'attachments['+index+'][file]'" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/20 transition-colors cursor-pointer h-12 border border-slate-200 rounded-xl bg-white shadow-sm">
                                                <input x-show="att.type !== 'file'" type="text" :name="'attachments['+index+'][link]'" x-model="att.link" class="w-full text-sm font-medium rounded-xl border-slate-200 h-12 placeholder:text-slate-400 focus:ring-elevate-accent/30 focus:border-elevate-accent text-elevate-dark shadow-sm px-4 bg-elevate-soft focus:bg-white transition-colors" placeholder="https://...">
                                            </div>

                                            <!-- Nama Label -->
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'attachments['+index+'][name]'" x-model="att.name" class="w-full text-sm font-medium rounded-xl border-slate-200 h-12 placeholder:text-slate-400 focus:ring-elevate-accent/30 focus:border-elevate-accent text-elevate-dark shadow-sm px-4 bg-elevate-soft focus:bg-white transition-colors" placeholder="Label (Opsional)">
                                            </div>
                                        </div>

                                        <!-- Hapus Baris -->
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="absolute top-3 right-3 md:static md:mt-1 w-10 h-10 flex items-center justify-center rounded-xl bg-white text-[#D13438] hover:bg-[#FDE7E9] border border-[#F4C3C9] transition-colors shadow-sm" title="Hapus Baris">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>             

                     <!-- FOOTER ACTIONS -->
                    <div class="bg-elevate-soft/30 px-6 md:px-10 py-6 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                        <a href="<?php echo e(route('lms.materials.index')); ?>" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors text-center text-sm btn-cancel-confirm active:scale-95 shadow-sm">Batal</a>
                        
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm active:scale-95 border border-transparent">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                            <span>Simpan & Terbitkan</span>
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
            // 1. Proteksi Tombol Batal
             const cancelButtons = document.querySelectorAll('.btn-cancel-confirm');
            cancelButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    Swal.fire({
                        title: 'Batalkan Upload?',
                        text: "Data yang sudah diisi akan hilang!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2c3f61', 
                        cancelButtonColor: '#e5eff5', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: '<span class="text-elevate-dark">Lanjut Mengisi</span>',
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

            // 2. Logic Submit Form yang Lebih Aman
             const uploadForm = document.getElementById('uploadForm');
            if(uploadForm) {
                uploadForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }
                    Swal.fire({
                        title: 'Sedang Mengupload...',
                        html: 'Mohon jangan tutup halaman ini.<br>Proses upload file sedang berjalan.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl', title: 'text-xl font-black text-elevate-dark' }
                    });
                    setTimeout(() => { this.submit(); }, 300);
                });
            }

            // 3. Notifikasi Error PHP (Jika validasi server gagal)
           <?php if($errors->any()): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengupload',
                    text: 'Silakan periksa kembali isian formulir Anda.',
                    confirmButtonText: 'Oke, Saya Perbaiki',
                    confirmButtonColor: '#D13438',
                    customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl', confirmButton: 'rounded-xl px-6 py-3 font-bold shadow-sm' }
                });
            <?php endif; ?>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/materials/create.blade.php ENDPATH**/ ?>