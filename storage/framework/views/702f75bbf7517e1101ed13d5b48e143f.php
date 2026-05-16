

<?php $__env->startSection('content'); ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-12 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-100 text-blue-900 mb-4 shadow-sm">
                    <i class="ph-duotone ph-shield-check text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black text-blue-900 tracking-tight">Verifikasi Data Induk</h1>
                <p class="text-slate-500 text-sm mt-2 max-w-lg mx-auto">
                    Untuk keperluan sinkronisasi Dapodik, mohon pastikan Nomor Induk Kependudukan (NIK) dan NISN Anda sudah benar sesuai dokumen resmi.
                </p>
            </div>

            
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-emerald-600 text-xl shrink-0"></i>
                    <div>
                        <h4 class="font-bold text-emerald-800">Verifikasi Berhasil!</h4>
                        <p class="text-sm text-emerald-700 mt-0.5"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-600 text-xl shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="font-bold text-rose-800">Gagal Memverifikasi Data:</h4>
                        <ul class="list-disc list-inside text-sm text-rose-700 mt-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-400 to-blue-900"></div>

                <div class="p-8 sm:p-10">
                    
                    
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-8">
                        <div class="w-14 h-14 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xl shrink-0">
                            <?php echo e(substr(auth()->user()->name ?? 'S', 0, 1)); ?>

                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Login Sebagai:</p>
                            <h3 class="font-bold text-slate-800 text-lg leading-tight"><?php echo e(auth()->user()->name ?? $student->name); ?></h3>
                            <p class="text-sm text-blue-600 font-medium"><?php echo e($student->schoolClass->name ?? 'Kelas Tidak Ditemukan'); ?></p>
                        </div>
                    </div>

                    
                    <?php
                        // Asumsi Anda menambahkan kolom 'is_validated' boolean di tabel students
                        $isValidated = $student->is_validated ?? false; 
                    ?>

                    <?php if($isValidated): ?>
                        
                        <div class="text-center py-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 mb-4 ring-4 ring-emerald-50">
                                <i class="ph-fill ph-seal-check text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">Data Telah Terverifikasi</h3>
                            <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">Terima kasih, data NIK dan NISN Anda telah dikunci ke dalam sistem. Jika terdapat kesalahan, silakan hubungi Operator Sekolah/Wali Kelas.</p>
                            
                            <div class="mt-8 grid grid-cols-2 gap-4 text-left max-w-sm mx-auto bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">NISN</p>
                                    <p class="font-mono font-bold text-slate-700"><?php echo e($student->student_id); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">NIK</p>
                                    <p class="font-mono font-bold text-slate-700"><?php echo e($student->nik); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        
                        <form id="verify-form" action="<?php echo e(route('students.verify.process')); ?>" method="POST" class="space-y-6">
                            <?php echo csrf_field(); ?>
                            
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
                                <i class="ph-fill ph-warning-circle text-amber-600 text-xl shrink-0"></i>
                                <p class="text-xs text-amber-800 font-medium">
                                    Pastikan data yang dimasukkan <strong>sama persis</strong> dengan dokumen Kartu Keluarga (KK) dan Ijazah terakhir. Data yang tersimpan tidak dapat diubah sendiri nantinya.
                                </p>
                            </div>

                            <div class="space-y-5 mt-6">
                                
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <label for="nisn" class="block text-sm font-bold text-slate-700">Nomor Induk Siswa Nasional (NISN) <span class="text-rose-500">*</span></label>
                                        <a href="https://nisn.data.kemdikbud.go.id" target="_blank" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 bg-blue-50 px-2 py-1 rounded">
                                            Cek NISN Online <i class="ph-bold ph-arrow-up-right"></i>
                                        </a>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-identification-card text-lg"></i>
                                        </div>
                                        <input type="text" name="nisn" id="nisn" value="<?php echo e(old('nisn', $student->student_id)); ?>" 
                                            required inputmode="numeric" pattern="[0-9]*" maxlength="10" minlength="10"
                                            class="w-full pl-11 rounded-2xl border-slate-300 bg-white focus:border-blue-900 focus:ring-blue-900 text-lg py-3 font-mono font-bold text-slate-800 transition-all placeholder:font-normal placeholder:text-sm" 
                                            placeholder="Masukkan 10 digit NISN">
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1.5 ml-1">Terdiri dari tepat 10 digit angka.</p>
                                </div>

                                
                                <div>
                                    <label for="nik" class="block text-sm font-bold text-slate-700 mb-2">Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-credit-card text-lg"></i>
                                        </div>
                                        <input type="text" name="nik" id="nik" value="<?php echo e(old('nik', $student->nik)); ?>" 
                                            required inputmode="numeric" pattern="[0-9]*" maxlength="16" minlength="16"
                                            class="w-full pl-11 rounded-2xl border-slate-300 bg-white focus:border-blue-900 focus:ring-blue-900 text-lg py-3 font-mono font-bold text-slate-800 transition-all placeholder:font-normal placeholder:text-sm" 
                                            placeholder="Masukkan 16 digit NIK">
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1.5 ml-1">Lihat pada Kartu Keluarga (KK). Terdiri dari 16 digit angka.</p>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 pt-6 mt-8">
                                <button type="button" onclick="confirmVerification()" class="w-full py-4 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 shadow-xl shadow-blue-900/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 text-lg">
                                    <i class="ph-bold ph-check-circle"></i>
                                    Validasi & Simpan Data
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
            
            
            <div class="text-center mt-8">
                <p class="text-xs font-medium text-slate-400">
                    Mengalami kendala? Silakan lapor ke Tata Usaha atau Wali Kelas Anda.
                </p>
            </div>

        </div>
    </div>

    
    <script>
        // Mencegah input selain angka
        document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
            input.addEventListener('input', function (e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        // Konfirmasi sebelum submit
        function confirmVerification() {
            const form = document.getElementById('verify-form');
            const nisn = document.getElementById('nisn').value;
            const nik = document.getElementById('nik').value;

            // Validasi client-side sederhana
            if (nisn.length !== 10) {
                Swal.fire({ icon: 'error', title: 'NISN Tidak Valid', text: 'NISN harus berisi tepat 10 digit angka.', customClass: { popup: 'rounded-[2rem]' } });
                return;
            }
            if (nik.length !== 16) {
                Swal.fire({ icon: 'error', title: 'NIK Tidak Valid', text: 'NIK harus berisi tepat 16 digit angka.', customClass: { popup: 'rounded-[2rem]' } });
                return;
            }

            Swal.fire({
                title: 'Apakah Data Sudah Benar?',
                html: `
                    <div class="text-left mt-4 text-sm">
                        <p class="mb-2"><strong>NISN:</strong> <span class="font-mono">${nisn}</span></p>
                        <p><strong>NIK:</strong> <span class="font-mono">${nik}</span></p>
                    </div>
                    <p class="text-xs text-rose-500 mt-4 font-bold">Data yang dikirim akan dikunci permanen!</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e3a8a', // blue-900
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Data Sudah Benar',
                cancelButtonText: 'Cek Kembali',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                    form.submit();
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/verify.blade.php ENDPATH**/ ?>