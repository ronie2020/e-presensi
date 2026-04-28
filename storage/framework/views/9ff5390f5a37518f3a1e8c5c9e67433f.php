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
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest mb-3 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-users-three"></i> Database Alumni
                        </div>
                        <h1 class="text-3xl font-black text-elevate-dark tracking-tight mb-2">Tracer Study SMP</h1>
                        <p class="text-elevate-dark/80 text-sm max-w-xl font-medium">
                            Pantau sebaran lulusan ke SMA, SMK, MA, atau Pesantren.
                        </p>
                    </div>

                    
                    <div class="flex gap-4">
                        <div class="text-center px-6 py-4 bg-white/60 rounded-2xl border border-white backdrop-blur-md shadow-sm">
                            <span class="block text-3xl font-black text-elevate-dark mb-1">
                                <?php echo e(isset($stats['total']) ? $stats['total'] : $alumni->total()); ?>

                            </span>
                            <span class="text-[10px] text-elevate-primary uppercase font-bold tracking-wider">Total Alumni</span>
                        </div>
                        <div class="text-center px-6 py-4 bg-white/60 rounded-2xl border border-white backdrop-blur-md shadow-sm hidden sm:block">
                            <span class="block text-3xl font-black text-elevate-primary mb-1">
                                <?php echo e($stats['kuliah'] ?? 0); ?>

                            </span>
                            <span class="text-[10px] text-elevate-dark/70 uppercase font-bold tracking-wider">Lanjut Sekolah</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm mb-6 p-4 flex flex-col md:flex-row items-center justify-between gap-4 relative z-20">
                <form method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    
                    <div class="relative">
                        <select name="year" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary py-2.5 pl-4 pr-10 bg-slate-50 appearance-none cursor-pointer">
                            <option value="">-- Semua Angkatan --</option>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year); ?>" <?php echo e(request('year') == $year ? 'selected' : ''); ?>>Lulusan <?php echo e($year); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                    </div>

                    
                    <div class="relative">
                        <select name="activity" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary py-2.5 pl-4 pr-10 bg-slate-50 appearance-none cursor-pointer">
                            <option value="">-- Semua Jalur --</option>
                            <option value="SMA" <?php echo e(request('activity') == 'SMA' ? 'selected' : ''); ?>>SMA</option>
                            <option value="SMK" <?php echo e(request('activity') == 'SMK' ? 'selected' : ''); ?>>SMK</option>
                            <option value="MA" <?php echo e(request('activity') == 'MA' ? 'selected' : ''); ?>>MA</option>
                            <option value="Pesantren" <?php echo e(request('activity') == 'Pesantren' ? 'selected' : ''); ?>>Pesantren</option>
                            <option value="Tidak Lanjut" <?php echo e(request('activity') == 'Tidak Lanjut' ? 'selected' : ''); ?>>Tidak Lanjut</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                    </div>
                </form>

                <div class="flex flex-wrap gap-2 w-full md:w-auto justify-end">
                    
                    <form method="GET" class="relative w-full md:w-48 lg:w-56 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Nama / NISN..." 
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all shadow-sm">
                    </form>
                    
                    
                    <a href="<?php echo e(route('admin.alumni.testimonials')); ?>" class="px-4 py-2.5 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 rounded-xl font-bold text-sm hover:bg-elevate-primary hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Lihat Testimoni">
                        <i class="ph-bold ph-quotes"></i> <span class="hidden lg:inline">Testimoni</span>
                    </a>

                    
                    <a href="<?php echo e(route('admin.alumni.import')); ?>" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl font-bold text-sm hover:bg-emerald-600 hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Import Data Excel/CSV">
                        <i class="ph-bold ph-upload-simple"></i>
                    </a>

                    
                    <a href="<?php echo e(route('admin.alumni.export_pdf', request()->all())); ?>" target="_blank" class="px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl font-bold text-sm hover:bg-rose-600 hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Export Laporan PDF">
                        <i class="ph-bold ph-file-pdf"></i>
                    </a>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-slate-600 border-collapse">
                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50/80 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5">Identitas Alumni</th>
                                <th class="px-6 py-5">Angkatan</th>
                                <th class="px-6 py-5">Sekolah Lanjutan</th>
                                <th class="px-6 py-5">Kontak</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $alumni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                            <?php if($student->photo_path): ?>
                                                <img src="<?php echo e(asset('storage/'.$student->photo_path)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold bg-white"><?php echo e(substr($student->name, 0, 2)); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-1"><?php echo e($student->name); ?></div>
                                            <div class="text-xs text-slate-400 font-medium font-mono mt-0.5"><?php echo e($student->nisn ?? $student->student_id); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 tracking-wider">
                                        <i class="ph-fill ph-graduation-cap"></i> 
                                        <?php echo e($student->graduation_year ?? (\Carbon\Carbon::parse($student->graduated_date)->year ?? '-')); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($student->alumniProfile): ?>
                                        <?php $status = $student->alumniProfile->activity_status; ?>
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border
                                            <?php echo e($status == 'SMA' ? 'bg-elevate-primary/10 text-elevate-primary border-elevate-primary/20' : ''); ?>

                                            <?php echo e($status == 'SMK' ? 'bg-orange-50 text-orange-600 border-orange-100' : ''); ?>

                                            <?php echo e($status == 'MA' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ''); ?>

                                            <?php echo e($status == 'Pesantren' ? 'bg-teal-50 text-teal-600 border-teal-100' : ''); ?>

                                            <?php echo e($status == 'Bekerja' ? 'bg-slate-100 text-slate-600 border-slate-200' : ''); ?>">
                                            <?php echo e($status); ?>

                                        </span>
                                        <div class="text-xs text-elevate-dark font-bold mt-1.5 truncate max-w-[200px]" title="<?php echo e($student->alumniProfile->campus_name ?? $student->alumniProfile->company_name); ?>">
                                            <?php echo e($student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-'); ?>

                                        </div>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wider">
                                            <i class="ph-fill ph-warning-circle text-sm"></i> Belum Mengisi
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-medium space-y-1">
                                        <?php if($student->alumniProfile && $student->alumniProfile->phone_number): ?>
                                            <div class="flex items-center gap-2 font-bold text-emerald-600">
                                                <i class="ph-fill ph-whatsapp-logo text-emerald-500 text-sm"></i> <?php echo e($student->alumniProfile->phone_number); ?>

                                            </div>
                                        <?php else: ?>
                                            <div class="text-slate-400">-</div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?php echo e(route('admin.alumni.show', $student->id)); ?>" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-elevate-primary hover:border-elevate-primary hover:text-white transition-all shadow-sm" title="Lihat Detail">
                                            <i class="ph-bold ph-eye text-lg"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.alumni.edit', $student->id)); ?>" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-amber-500 hover:border-amber-500 hover:text-white transition-all shadow-sm" title="Edit Data">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                            <i class="ph-duotone ph-users-three text-4xl text-slate-300"></i>
                                        </div>
                                        <span class="font-bold text-slate-500 text-base">Belum ada data alumni yang ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
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
                    confirmButtonColor: '#3b5889', // elevate-primary
                    confirmButtonText: 'Tutup',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] shadow-xl border border-slate-100'
                    }
                });
            <?php endif; ?>

            // Cek Session Error
            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo e(session('error')); ?>",
                    confirmButtonColor: '#e11d48', // Rose-500
                    confirmButtonText: 'Coba Lagi',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] shadow-xl border border-slate-100'
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/alumni/index.blade.php ENDPATH**/ ?>