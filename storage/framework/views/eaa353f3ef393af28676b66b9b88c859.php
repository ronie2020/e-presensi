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

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <a href="<?php echo e(route('letters.incoming.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-envelope-simple-open"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-envelope-simple-open text-elevate-accent"></i> Registrasi Surat Masuk
                    </h2>
                    <p class="text-elevate-accent text-sm font-medium relative z-10 mt-1">Isi detail surat dinas yang diterima sekolah.</p>
                </div>

                
                <div class="p-8">
                    
                    <form id="form-create-surat" action="<?php echo e(route('letters.incoming.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <?php echo csrf_field(); ?>

                        <!-- SECTION 1: IDENTITAS SURAT -->
                        <div class="p-6 bg-elevate-accent/5 rounded-[2rem] border border-elevate-accent/20 relative group hover:border-elevate-accent/40 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Identitas Surat
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor Agenda</label>
                                    <input type="text" name="nomor_agenda" value="<?php echo e(old('nomor_agenda', $nextAgenda)); ?>" readonly class="w-full px-4 rounded-2xl border-slate-200 bg-slate-100 shadow-inner focus:outline-none text-sm py-3 font-bold text-slate-500 cursor-not-allowed transition-all" title="Nomor Agenda terisi otomatis">
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 flex items-center gap-1 font-medium"><i class="ph-fill ph-lock-key"></i> Terisi Otomatis</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Sifat Surat</label>
                                    <div class="relative">
                                        <select name="sifat_surat" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark appearance-none transition-all cursor-pointer">
                                            <option value="Biasa" <?php echo e(old('sifat_surat') == 'Biasa' ? 'selected' : ''); ?>>Biasa</option>
                                            <option value="Penting" <?php echo e(old('sifat_surat') == 'Penting' ? 'selected' : ''); ?>>Penting</option>
                                            <option value="Segera" <?php echo e(old('sifat_surat') == 'Segera' ? 'selected' : ''); ?>>Segera</option>
                                            <option value="Rahasia" <?php echo e(old('sifat_surat') == 'Rahasia' ? 'selected' : ''); ?>>Rahasia</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor Surat Asli</label>
                                    <input type="text" name="nomor_surat" value="<?php echo e(old('nomor_surat')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Asal / Pengirim Surat</label>
                                    <input type="text" name="asal_surat" value="<?php echo e(old('asal_surat')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL WAKTU & ISI -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Detail Waktu & Isi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" value="<?php echo e(old('tgl_surat')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Diterima</label>
                                    <input type="date" name="tgl_diterima" value="<?php echo e(old('tgl_diterima', date('Y-m-d'))); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Perihal / Maksud Surat</label>
                                    <textarea name="perihal" rows="3" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-medium text-elevate-dark transition-all"><?php echo e(old('perihal')); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECTION 3: LAMPIRAN -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                Lampiran (Opsional)
                            </h3>
                            <div class="pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Upload File Surat</label>
                                    <div class="relative">
                                        <input type="file" name="file_surat" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-accent/10 file:text-elevate-primary hover:file:bg-elevate-accent/20 transition-all cursor-pointer border border-dashed border-slate-300 bg-white shadow-sm rounded-2xl py-3 px-4 hover:border-elevate-primary">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 flex items-center gap-1 font-medium">
                                        <i class="ph-fill ph-info text-elevate-primary"></i> PDF / JPG / PNG (Max 2MB)
                                    </p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-6 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="<?php echo e(route('letters.incoming.index')); ?>" class="px-6 py-3.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-100 hover:text-slate-700 transition-colors">
                                Batal
                            </a>
                            
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Simpan Data</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        // 1. Tampilkan SweetAlert jika ada error validasi dari Controller (menggantikan alert merah HTML)
        <?php if($errors->any()): ?>
            let errorMessages = '<div class="text-left"><ul class="list-disc list-inside text-sm font-medium space-y-1 text-slate-600">';
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                errorMessages += '<li><?php echo e($error); ?></li>';
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            errorMessages += '</ul></div>';

            Swal.fire({
                icon: 'error',
                title: 'Periksa Kembali Inputan Anda!',
                html: errorMessages,
                confirmButtonColor: '#e11d48', // Tailwind rose-600
                confirmButtonText: 'Baik, Saya Perbaiki',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors shadow-lg shadow-rose-900/20'
                },
                buttonsStyling: false
            });
        <?php endif; ?>

        // 2. Fungsi Konfirmasi Simpan Data
        function confirmSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('form-create-surat');
            
            // Trigger validasi bawaan HTML5 (seperti 'required' popups) sebelum menjalankan SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: 'Simpan Data Surat?',
                text: "Pastikan seluruh identitas dan file lampiran surat sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Periksa Lagi',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-elevate-dark text-white px-6 py-3 rounded-xl font-bold hover:bg-elevate-primary transition-colors mx-2 shadow-lg shadow-elevate-dark/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan indikator loading saat form dikirim
                    Swal.fire({
                        title: 'Menyimpan Data...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    form.submit();
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/letters/incoming/create.blade.php ENDPATH**/ ?>