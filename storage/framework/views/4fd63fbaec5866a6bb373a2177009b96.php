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
            <?php echo e(__('Upload Materi Baru')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-6 md:py-8 font-sans text-slate-800 pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-6 md:p-8 mb-8 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/30 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold mb-1 tracking-tight">Upload Materi Baru</h1>
                        <p class="text-[#2A3B52]/80 text-sm font-medium">Lengkapi formulir di bawah ini untuk membagikan materi.</p>
                    </div>
                    <a href="<?php echo e(route('lms.materials.index')); ?>" class="flex items-center justify-center gap-2 px-4 py-2 bg-white/40 hover:bg-white/60 rounded-xl text-sm font-bold backdrop-blur-sm transition text-[#2A3B52] border border-white/50 btn-cancel-confirm active:scale-95 w-full md:w-auto shadow-sm">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="animate-enter mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-xl flex items-start gap-4 shadow-sm fluent-card">
                    <div class="p-2 bg-white text-[#D13438] rounded-lg shrink-0 shadow-sm border border-[#F4C3C9]">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-[#D13438] uppercase tracking-wide mb-1">Gagal Mengupload</h3>
                        <ul class="list-disc list-inside text-sm text-[#D13438] space-y-1 font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php               
                $oldAttachments = old('attachments', [['id' => time(), 'type' => 'file', 'link' => '', 'name' => '']]);   
                foreach($oldAttachments as $k => &$att) {
                    if(!isset($att['id'])) $att['id'] = time() + $k;
                }
            ?>
            
            <div class="animate-enter bg-white rounded-xl fluent-card overflow-hidden" style="animation-delay: 100ms">
                <form action="<?php echo e(route('lms.materials.store')); ?>" method="POST" enctype="multipart/form-data" 
                      x-data="{ 
                          targetType: '<?php echo e(old('target_type', 'class')); ?>', 
                          attachments: <?php echo e(json_encode($oldAttachments)); ?> 
                      }"
                      id="uploadForm">
                    <?php echo csrf_field(); ?>

                    <div class="p-6 md:p-8 space-y-8">
                        
                        <!-- BAGIAN 1: INFORMASI UTAMA -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center text-xl border border-[#D0E7F8] shadow-sm"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-lg font-black text-[#2A3B52]">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Materi <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" required 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 placeholder:font-normal placeholder:text-slate-400 transition-colors focus:bg-white" 
                                           placeholder="Contoh: Bab 1 - Ekosistem">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 appearance-none transition-colors focus:bg-white cursor-pointer">
                                            <option value="">-- Pilih Mapel --</option>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id') == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                
                                <div class="bg-[#F3F9FD] p-5 rounded-xl border border-[#D0E7F8]">
                                    <label class="block text-xs font-black text-[#5295FF] uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="ph-fill ph-users-three"></i> Bagikan Kepada:
                                    </label>
                                    <div class="space-y-4">
                                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                            <label class="inline-flex items-center cursor-pointer group p-2 rounded-lg hover:bg-white hover:shadow-sm transition border border-transparent hover:border-[#D0E7F8]">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="target_type" value="class" x-model="targetType" class="peer sr-only">
                                                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-[#5295FF] peer-checked:bg-[#5295FF] transition shadow-sm"></div>
                                                </div>
                                                <span class="ml-2 text-sm font-bold text-slate-600 group-hover:text-[#5295FF] transition">Satu Kelas</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer group p-2 rounded-lg hover:bg-white hover:shadow-sm transition border border-transparent hover:border-[#D0E7F8]">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="target_type" value="grade" x-model="targetType" class="peer sr-only">
                                                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-[#5295FF] peer-checked:bg-[#5295FF] transition shadow-sm"></div>
                                                </div>
                                                <span class="ml-2 text-sm font-bold text-slate-600 group-hover:text-[#5295FF] transition">Satu Jenjang</span>
                                            </label>
                                        </div>
                                        
                                        <!-- SELECT KELAS -->
                                        <div x-show="targetType === 'class'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                            <div class="relative">
                                                <select name="class_id" 
                                                        :required="targetType === 'class'"
                                                        class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-[#5295FF] h-11 px-3 appearance-none shadow-sm cursor-pointer text-[#2A3B52]">
                                                    <option value="">-- Pilih Kelas Spesifik --</option>
                                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($class->id); ?>" <?php echo e(old('class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>

                                        <!-- SELECT JENJANG -->
                                        <div x-cloak x-show="targetType === 'grade'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                            <div class="relative">
                                                <select name="target_grade" 
                                                        :required="targetType === 'grade'"
                                                        class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-[#5295FF] h-11 px-3 appearance-none shadow-sm cursor-pointer text-[#2A3B52]">
                                                    <option value="7" <?php echo e(old('target_grade') == '7' ? 'selected' : ''); ?>>Semua Kelas 7</option>
                                                    <option value="8" <?php echo e(old('target_grade') == '8' ? 'selected' : ''); ?>>Semua Kelas 8</option>
                                                    <option value="9" <?php echo e(old('target_grade') == '9' ? 'selected' : ''); ?>>Semua Kelas 9</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                            <p class="text-[10px] text-slate-400 font-bold mt-1.5 flex items-center gap-1"><i class="ph-bold ph-info"></i> Sistem akan membagikan ke seluruh kelas dengan awalan angka tersebut.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- BAGIAN 2: DESKRIPSI -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
                                Pengantar & Resume Materi
                            </label>
                            <div class="relative">
                                <textarea name="resume" rows="6" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-[#5295FF] focus:border-[#5295FF] shadow-sm p-4 text-[#2A3B52] leading-relaxed font-medium placeholder:font-normal placeholder:text-slate-400 transition-colors focus:bg-white" placeholder="Tuliskan rangkuman materi, tujuan pembelajaran, atau instruksi untuk siswa..."><?php echo e(old('resume')); ?></textarea>
                                <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none"><i class="ph-bold ph-text-aa text-xl"></i></div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                         <!-- BAGIAN 3: LAMPIRAN (DYNAMIC) -->
                        <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 md:p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <label class="block text-sm font-black text-[#2A3B52] flex items-center gap-2">
                                        <i class="ph-fill ph-paperclip text-[#5295FF]"></i> Referensi & Lampiran
                                    </label>
                                    <p class="text-xs text-slate-400 mt-1">Upload file dokumen atau tautkan video pembelajaran.</p>
                                </div>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file', link: '', name: ''})" 
                                        class="w-full sm:w-auto text-xs bg-white border border-slate-200 text-[#5295FF] px-4 py-3 sm:py-2 rounded-lg font-bold hover:bg-[#F3F9FD] hover:border-[#D0E7F8] transition shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-3 p-4 bg-white rounded-xl border border-slate-200 relative group transition-all hover:border-[#5295FF] hover:shadow-md animate-enter" style="animation-duration: 0.3s">
                                        
                                        <!-- Nomor -->
                                        <div class="hidden md:flex w-8 h-8 rounded-lg bg-slate-100 text-slate-500 text-xs font-bold items-center justify-center border border-slate-200 shrink-0 mt-1" x-text="index + 1"></div>

                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <!-- Pilihan Tipe -->
                                            <div class="md:col-span-3">
                                                <div class="relative">
                                                    <select :name="'attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-lg border-slate-200 focus:ring-[#5295FF] bg-slate-50 cursor-pointer h-10 px-3 appearance-none shadow-sm text-[#2A3B52]">
                                                        <option value="file">📄 Dokumen (File)</option>
                                                        <option value="video">📺 Video (YouTube)</option>
                                                        <option value="link">🔗 Link Website</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>

                                            <!-- Input File / Link -->
                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'attachments['+index+'][file]'" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#F3F9FD] file:text-[#5295FF] hover:file:bg-[#E0F0FC] transition cursor-pointer h-10 border border-slate-100 rounded-lg bg-white">
                                                <input x-show="att.type !== 'file'" type="text" :name="'attachments['+index+'][link]'" x-model="att.link" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400 focus:ring-[#5295FF] text-[#2A3B52]" placeholder="https://...">
                                            </div>

                                            <!-- Nama Label -->
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'attachments['+index+'][name]'" x-model="att.name" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400 focus:ring-[#5295FF] text-[#2A3B52]" placeholder="Label (Opsional)">
                                            </div>
                                        </div>

                                        <!-- Hapus Baris -->
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="absolute top-2 right-2 md:static md:mt-1 w-8 h-8 flex items-center justify-center rounded-lg bg-white text-[#D13438] hover:bg-[#FDE7E9] border border-[#F4C3C9] transition shadow-sm" title="Hapus Baris">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>             

                     <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-50 px-6 md:px-8 py-6 flex flex-col md:flex-row justify-end gap-3 border-t border-slate-100">
                        <a href="<?php echo e(route('lms.materials.index')); ?>" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition text-center text-sm btn-cancel-confirm active:scale-95">Batal</a>
                        
                        <button type="submit" class="px-8 py-3 bg-[#2A3B52] text-white font-bold rounded-xl shadow-md hover:bg-[#182436] transition flex items-center justify-center gap-2 text-sm active:scale-95 border border-transparent">
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
                        confirmButtonColor: '#64748b', 
                        cancelButtonColor: '#cbd5e1', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Mengisi',
                        customClass: {
                            popup: 'rounded-xl fluent-modal border-0 font-sans',
                            confirmButton: 'rounded-lg px-4 py-2.5 font-bold',
                            cancelButton: 'rounded-lg px-4 py-2.5 font-bold text-slate-600'
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
                        customClass: { popup: 'rounded-xl fluent-modal border-0 font-sans', title: 'text-xl font-bold text-[#2A3B52]' }
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
                    customClass: { popup: 'rounded-xl fluent-modal border-0 font-sans' }
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/materials/create.blade.php ENDPATH**/ ?>