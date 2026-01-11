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
    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2.5rem] bg-slate-900 overflow-hidden p-8 sm:p-10 mb-8 shadow-xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 text-[10px] font-bold uppercase tracking-widest mb-3">
                            <i class="ph-fill ph-users-three"></i> Database Alumni
                        </div>
                        <h1 class="text-3xl font-black text-white tracking-tight mb-2">Tracer Study SMP</h1>
                        <p class="text-slate-400 text-sm max-w-xl">
                            Pantau sebaran lulusan ke SMA, SMK, MA, atau Pesantren.
                        </p>
                    </div>

                    
                    <div class="flex gap-4">
                        <div class="text-center px-4 py-3 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-sm">
                            <span class="block text-2xl font-black text-blue-400">
                                <?php echo e(isset($stats['total']) ? $stats['total'] : $alumni->total()); ?>

                            </span>
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Total Alumni</span>
                        </div>
                        <div class="text-center px-4 py-3 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-sm hidden sm:block">
                            <span class="block text-2xl font-black text-emerald-400">
                                <?php echo e($stats['kuliah'] ?? 0); ?>

                            </span>
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Lanjut Sekolah</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm mb-6 p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                <form method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    
                    <select name="year" onchange="this.form.submit()" class="rounded-xl border-slate-200 text-sm font-bold text-slate-600 focus:ring-amber-500 py-2.5 bg-slate-50">
                        <option value="">-- Semua Angkatan --</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e(request('year') == $year ? 'selected' : ''); ?>>Lulusan <?php echo e($year); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    
                    <select name="activity" onchange="this.form.submit()" class="rounded-xl border-slate-200 text-sm font-bold text-slate-600 focus:ring-amber-500 py-2.5 bg-slate-50">
                        <option value="">-- Semua Jalur --</option>
                        <option value="SMA" <?php echo e(request('activity') == 'SMA' ? 'selected' : ''); ?>>SMA</option>
                        <option value="SMK" <?php echo e(request('activity') == 'SMK' ? 'selected' : ''); ?>>SMK</option>
                        <option value="MA" <?php echo e(request('activity') == 'MA' ? 'selected' : ''); ?>>MA</option>
                        <option value="Pesantren" <?php echo e(request('activity') == 'Pesantren' ? 'selected' : ''); ?>>Pesantren</option>
                        <option value="Tidak Lanjut" <?php echo e(request('activity') == 'Tidak Lanjut' ? 'selected' : ''); ?>>Tidak Lanjut</option>
                    </select>
                </form>

                <div class="flex flex-wrap gap-2 w-full md:w-auto justify-end">
                    
                    <form method="GET" class="relative w-full md:w-48 lg:w-56">
                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Nama / NISN..." 
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-amber-500 focus:border-amber-500">
                    </form>
                    
                    
                    <a href="<?php echo e(route('admin.alumni.testimonials')); ?>" class="px-4 py-2.5 bg-amber-100 text-amber-700 border border-amber-200 rounded-xl font-bold text-sm hover:bg-amber-200 flex items-center gap-2 transition-colors" title="Lihat Testimoni">
                        <i class="ph-bold ph-quotes"></i> <span class="hidden lg:inline">Testimoni</span>
                    </a>

                    
                    <a href="<?php echo e(route('admin.alumni.import')); ?>" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl font-bold text-sm hover:bg-emerald-100 flex items-center gap-2 transition-colors" title="Import Data Excel/CSV">
                        <i class="ph-bold ph-upload-simple"></i>
                    </a>

                    
                    <a href="<?php echo e(route('admin.alumni.export_pdf', request()->all())); ?>" target="_blank" class="px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl font-bold text-sm hover:bg-rose-100 flex items-center gap-2 transition-colors" title="Export Laporan PDF">
                        <i class="ph-bold ph-file-pdf"></i>
                    </a>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Identitas Alumni</th>
                                <th class="px-6 py-4">Angkatan</th>
                                <th class="px-6 py-4">Sekolah Lanjutan</th>
                                <th class="px-6 py-4">Kontak</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $alumni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-amber-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden">
                                            <?php if($student->photo_path): ?>
                                                <img src="<?php echo e(asset('storage/'.$student->photo_path)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold"><?php echo e(substr($student->name, 0, 2)); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800"><?php echo e($student->name); ?></div>
                                            <div class="text-xs text-slate-400 font-medium font-mono"><?php echo e($student->nisn ?? $student->student_id); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-500">
                                        <i class="ph-fill ph-graduation-cap"></i> 
                                        <?php echo e($student->graduation_year ?? (\Carbon\Carbon::parse($student->graduated_date)->year ?? '-')); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($student->alumniProfile): ?>
                                        <?php $status = $student->alumniProfile->activity_status; ?>
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border
                                            <?php echo e($status == 'SMA' ? 'bg-blue-50 text-blue-600 border-blue-100' : ''); ?>

                                            <?php echo e($status == 'SMK' ? 'bg-orange-50 text-orange-600 border-orange-100' : ''); ?>

                                            <?php echo e($status == 'MA' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ''); ?>

                                            <?php echo e($status == 'Pesantren' ? 'bg-teal-50 text-teal-600 border-teal-100' : ''); ?>

                                            <?php echo e($status == 'Bekerja' ? 'bg-slate-100 text-slate-600 border-slate-200' : ''); ?>">
                                            <?php echo e($status); ?>

                                        </span>
                                        <div class="text-xs text-slate-500 font-medium mt-1 truncate max-w-[200px]" title="<?php echo e($student->alumniProfile->campus_name ?? $student->alumniProfile->company_name); ?>">
                                            <?php echo e($student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-'); ?>

                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400 italic flex items-center gap-1">
                                            <i class="ph-bold ph-warning-circle"></i> Belum Mengisi
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-medium space-y-1">
                                        <?php if($student->alumniProfile && $student->alumniProfile->phone_number): ?>
                                            <div class="flex items-center gap-2 text-slate-600">
                                                <i class="ph-bold ph-whatsapp-logo text-emerald-500"></i> <?php echo e($student->alumniProfile->phone_number); ?>

                                            </div>
                                        <?php else: ?>
                                            <div class="text-slate-400">-</div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="<?php echo e(route('admin.alumni.show', $student->id)); ?>" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-blue-50 border border-blue-200 text-blue-600 hover:bg-blue-500 hover:text-white transition-colors shadow-sm" title="Lihat Detail">
                                            <i class="ph-bold ph-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.alumni.edit', $student->id)); ?>" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-amber-50 border border-amber-200 text-amber-600 hover:bg-amber-500 hover:text-white transition-colors shadow-sm" title="Edit Data">
                                            <i class="ph-bold ph-pencil-simple"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="ph-duotone ph-users-three text-4xl mb-2 text-slate-300"></i>
                                        <span>Belum ada data alumni yang ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100">
                    <?php echo e($alumni->withQueryString()->links()); ?>

                </div>
            </div>

        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek Session Success
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    confirmButtonColor: '#10b981', // Emerald-500
                    confirmButtonText: 'Mantap!',
                    background: '#ffffff',
                    iconColor: '#10b981',
                    customClass: {
                        popup: 'rounded-3xl shadow-xl border border-emerald-100'
                    }
                });
            <?php endif; ?>

            // Cek Session Error
            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo e(session('error')); ?>",
                    confirmButtonColor: '#f43f5e', // Rose-500
                    confirmButtonText: 'Coba Lagi',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl shadow-xl border border-rose-100'
                    }
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/admin/alumni/index.blade.php ENDPATH**/ ?>