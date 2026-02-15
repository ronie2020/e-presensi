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
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
    </style>

    <div class="py-8 sm:py-12 font-sans text-slate-800 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="animate-enter flex items-center justify-between mb-8">
                <a href="<?php echo e(route('admin.ppdb.index')); ?>" class="group inline-flex items-center gap-2 text-slate-500 font-bold text-sm hover:text-blue-600 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:border-blue-200 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="ph-bold ph-arrow-left"></i>
                    </div>
                    Kembali ke Daftar
                </a>
                
                <div class="flex gap-3">
                    <a href="<?php echo e(route('admin.ppdb.print', $registrant->id)); ?>" target="_blank" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-50 hover:border-slate-300 transition shadow-sm flex items-center gap-2">
                        <i class="ph-bold ph-printer"></i> Cetak Bukti
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="space-y-6 animate-enter delay-100">
                    
                    
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group">
                        <div class="h-28 bg-gradient-to-r from-blue-900 to-slate-900 relative">
                             <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                        </div>
                        <div class="px-6 pb-8 text-center relative">
                            <div class="w-32 h-32 mx-auto rounded-full bg-white p-1.5 shadow-xl -mt-16 mb-4 relative z-10">
                                <div class="w-full h-full rounded-full bg-slate-100 overflow-hidden flex items-center justify-center relative border border-slate-100">
                                    <?php if($registrant->file_photo): ?>
                                        <img src="<?php echo e(asset('storage/' . $registrant->file_photo)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="bg-gradient-to-br from-slate-100 to-slate-300 w-full h-full flex items-center justify-center">
                                            <span class="text-4xl font-black text-slate-400"><?php echo e(substr($registrant->full_name, 0, 1)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h2 class="text-xl font-black text-slate-800 leading-tight mb-1"><?php echo e($registrant->full_name); ?></h2>
                            <p class="text-sm font-bold text-slate-500 mb-4"><?php echo e($registrant->school_origin); ?></p>
                            
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-600">
                                <i class="ph-bold ph-barcode"></i> <?php echo e($registrant->registration_number); ?>

                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-8">
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                             <i class="ph-fill ph-sliders-horizontal"></i> Panel Kelulusan
                        </h3>
                        
                        <form action="<?php echo e(route('admin.ppdb.update_status', $registrant->id)); ?>" method="POST" class="space-y-5">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            
                            <div>
                                <label class="text-xs font-bold text-slate-700 mb-2 block ml-1">Status Seleksi</label>
                                <div class="relative">
                                    <select name="status" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer hover:bg-white hover:shadow-sm">
                                        <option value="pending" <?php echo e($registrant->status == 'pending' ? 'selected' : ''); ?>>⏳ Menunggu (Pending)</option>
                                        <option value="verified" <?php echo e($registrant->status == 'verified' ? 'selected' : ''); ?>>✅ Terverifikasi</option>
                                        <option value="accepted" <?php echo e($registrant->status == 'accepted' ? 'selected' : ''); ?>>🏆 DITERIMA</option>
                                        <option value="rejected" <?php echo e($registrant->status == 'rejected' ? 'selected' : ''); ?>>❌ Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-xs font-bold text-slate-700 mb-2 block ml-1">Catatan Panitia</label>
                                <textarea name="admin_note" rows="3" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white text-sm focus:ring-blue-500 focus:border-blue-500 placeholder:text-slate-400 placeholder:font-normal font-bold shadow-sm" placeholder="Contoh: Lulus jalur prestasi..."><?php echo e($registrant->admin_note); ?></textarea>
                            </div>

                            <button type="submit" class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-slate-800 transition shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </form>

                        <?php if($registrant->status === 'accepted'): ?>
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                <?php if(!$isPromoted): ?>
                                    <form id="promoteForm" action="<?php echo e(route('admin.ppdb.promote', $registrant->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="button" onclick="confirmPromote()" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 group">
                                            <i class="ph-bold ph-user-plus text-lg group-hover:scale-110 transition-transform"></i> Pindahkan ke Siswa Aktif
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="w-full py-3 bg-emerald-50 text-emerald-600 font-bold rounded-xl text-sm border border-emerald-100 flex items-center justify-center gap-2">
                                        <i class="ph-fill ph-check-circle text-lg"></i> Data Sudah Dipindahkan
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="bg-blue-50 rounded-[2.5rem] p-8 border border-blue-100">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Jalur Masuk</p>
                                <p class="text-2xl font-black text-blue-900 capitalize tracking-tight"><?php echo e($registrant->track); ?></p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-white text-blue-600 flex items-center justify-center shadow-sm">
                                <i class="ph-fill ph-path text-2xl"></i>
                            </div>
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-blue-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nilai Rapor</span>
                            <span class="text-2xl font-black text-slate-800"><?php echo e($registrant->average_grade); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2 space-y-6 animate-enter delay-200">
                    
                    
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-8 sm:p-10 relative overflow-hidden group">
                         <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i class="ph-fill ph-identification-card text-9xl text-slate-900"></i>
                        </div>

                        <h3 class="text-lg font-black text-slate-800 mb-8 flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                                <i class="ph-duotone ph-identification-card"></i>
                            </div>
                            Identitas Siswa
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-12 relative z-10">
                            <?php $__currentLoopData = [
                                'NISN' => $registrant->nisn,
                                'NIK' => $registrant->nik,
                                'Tempat Lahir' => $registrant->birth_place,
                                'Tanggal Lahir' => \Carbon\Carbon::parse($registrant->birth_date)->translatedFormat('d F Y'),
                                'Jenis Kelamin' => $registrant->gender == 'L' ? 'Laki-laki' : 'Perempuan',
                                'Agama' => $registrant->religion,
                                'No. HP' => $registrant->student_phone ?? '-'
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-l-2 border-slate-100 pl-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?php echo e($label); ?></p>
                                <p class="font-bold text-slate-700 text-base"><?php echo e($val); ?></p>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="sm:col-span-2 border-l-2 border-slate-100 pl-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                                <p class="font-bold text-slate-700 leading-relaxed"><?php echo e($registrant->address); ?></p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-8 sm:p-10 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i class="ph-fill ph-users-three text-9xl text-purple-900"></i>
                        </div>

                        <h3 class="text-lg font-black text-slate-800 mb-8 flex items-center gap-3 relative z-10">
                             <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                                <i class="ph-duotone ph-users-three"></i>
                            </div>
                            Data Orang Tua
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-12 relative z-10">
                            <?php $__currentLoopData = [
                                'Ayah' => $registrant->father_name,
                                'Ibu' => $registrant->mother_name,
                                'Pekerjaan' => $registrant->parent_job ?? '-',
                                'Penghasilan' => $registrant->parent_income ?? '-',
                                'No. WA Ortu' => $registrant->parent_phone
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-l-2 border-slate-100 pl-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?php echo e($label); ?></p>
                                <p class="font-bold text-slate-700 text-base"><?php echo e($val); ?></p>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-8 sm:p-10">
                        <h3 class="text-lg font-black text-slate-800 mb-8 flex items-center gap-3">
                             <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                                <i class="ph-duotone ph-files"></i>
                            </div>
                            Berkas Lampiran
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php $__currentLoopData = [
                                'file_kk' => 'Kartu Keluarga',
                                'file_akta' => 'Akta Kelahiran',
                                'file_grades' => 'Scan Rapor',
                                'file_kip' => 'Kartu KIP/PKH',
                                'file_achievement' => 'Sertifikat Prestasi'
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($registrant->$field): ?>
                                    <a href="<?php echo e(asset('storage/' . $registrant->$field)); ?>" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300 group">
                                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                                            <i class="ph-fill ph-file-text text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700 group-hover:text-blue-700 transition-colors"><?php echo e($label); ?></p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1 flex items-center gap-1 group-hover:text-blue-400">
                                                <i class="ph-bold ph-eye"></i> Lihat File
                                            </p>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmPromote() {
            Swal.fire({
                title: 'Pindahkan ke Siswa Aktif?',
                text: "Pastikan data sudah benar. Data akan masuk ke database utama sekolah.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pindahkan',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                customClass: { popup: 'rounded-[2rem] font-sans' }
            }).then((res) => { if(res.isConfirmed) document.getElementById('promoteForm').submit(); });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/ppdb/show.blade.php ENDPATH**/ ?>