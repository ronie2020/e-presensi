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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('admin.alumni.index')); ?>" class="p-3 bg-white rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm text-slate-500 group">
                        <i class="ph-bold ph-arrow-left text-xl group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-elevate-dark tracking-tight">Detail Profil Alumni</h1>
                        <p class="text-slate-500 text-sm font-medium mt-1">Informasi lengkap data siswa dan tracer study.</p>
                    </div>
                </div>
                
                <div class="flex gap-2 w-full md:w-auto">
                    <a href="<?php echo e(route('admin.alumni.edit', $student->id)); ?>" class="w-full md:w-auto px-6 py-3 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 rounded-xl font-bold text-sm hover:bg-elevate-primary hover:text-white flex items-center justify-center gap-2 transition-all shadow-sm">
                        <i class="ph-bold ph-pencil-simple text-lg"></i> Edit Data
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden text-center flex flex-col items-center">
                        
                        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-elevate-dark to-elevate-primary"></div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-elevate-accent/20 rounded-full blur-2xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                        
                        <div class="relative z-10 mt-6 mb-5">
                            <div class="w-32 h-32 mx-auto rounded-[2rem] border-4 border-white shadow-xl bg-white overflow-hidden flex items-center justify-center text-4xl font-black text-elevate-primary shrink-0">
                                <?php if($student->photo_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo e(substr($student->name, 0, 1)); ?>

                                <?php endif; ?>
                            </div>
                        </div>

                        <h2 class="text-xl font-black text-elevate-dark mb-1 leading-tight"><?php echo e($student->name); ?></h2>
                        <p class="text-sm text-slate-400 font-mono font-bold mb-5"><?php echo e($student->nisn ?? $student->student_id); ?></p>

                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-full text-xs font-bold text-slate-600 uppercase tracking-widest mb-8 shadow-sm">
                            <i class="ph-fill ph-graduation-cap text-elevate-primary"></i>
                            Lulusan <?php echo e($student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year); ?>

                        </div>

                        <div class="space-y-3 w-full text-left bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-elevate-primary shadow-sm border border-slate-100"><i class="ph-bold ph-gender-intersex"></i></div>
                                <span class="font-bold text-slate-700"><?php echo e($student->gender == 'L' ? 'Laki-laki' : 'Perempuan'); ?></span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-elevate-primary shadow-sm border border-slate-100 shrink-0"><i class="ph-bold ph-map-pin"></i></div>
                                <span class="font-bold text-slate-700 line-clamp-2 leading-snug" title="<?php echo e($student->address); ?>"><?php echo e($student->address ?? 'Alamat tidak tersedia'); ?></span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 mt-6 relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 opacity-5 pointer-events-none">
                            <i class="ph-fill ph-clock-counter-clockwise text-9xl text-elevate-dark"></i>
                        </div>
                        
                        <h3 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2 relative z-10">
                            <i class="ph-fill ph-clock-counter-clockwise text-elevate-primary"></i> Riwayat Akademik
                        </h3>

                        <?php if($student->classHistories && $student->classHistories->count() > 0): ?>
                            <div class="relative border-l-2 border-slate-100 ml-3 space-y-6 z-10">
                                <?php $__currentLoopData = $student->classHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="relative pl-6 group">
                                        
                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-[3px] border-elevate-accent/50 group-hover:border-elevate-primary transition-colors shadow-sm"></div>
                                        
                                        
                                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 group-hover:bg-elevate-accent/5 group-hover:border-elevate-accent/20 transition-all">
                                            <div class="flex justify-between items-start mb-1.5">
                                                <h4 class="text-sm font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors leading-none">
                                                    <?php echo e($history->schoolClass->name ?? 'Kelas Dihapus'); ?>

                                                </h4>
                                                <span class="text-[9px] font-black text-slate-400 bg-white px-2 py-0.5 rounded-md shadow-sm border border-slate-100 uppercase tracking-wider">
                                                    <?php echo e($history->academic_year); ?>

                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 font-medium">Kenaikan / Mutasi Kelas</p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                
                                <div class="relative pl-6 opacity-60">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-200 border-[3px] border-white shadow-sm"></div>
                                    <h4 class="text-sm font-bold text-slate-600">Siswa Masuk / Terdaftar</h4>
                                    <p class="text-xs text-slate-400 font-medium mt-0.5">Awal mula pendataan</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-6 relative z-10 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300 shadow-sm border border-slate-100">
                                    <i class="ph-duotone ph-ghost text-2xl"></i>
                                </div>
                                <p class="text-xs text-slate-400 font-bold">Belum ada catatan riwayat mutasi/kenaikan kelas.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="lg:col-span-2 space-y-6">
                    
                    
                    <?php if($student->alumniProfile): ?>
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group hover:border-slate-200 transition-colors">
                            <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none">
                                <i class="ph-fill ph-briefcase text-9xl text-elevate-dark"></i>
                            </div>

                            <h3 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2 relative z-10">
                                <i class="ph-fill ph-chart-polar text-elevate-primary"></i> Laporan Aktivitas Saat Ini
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jalur Pilihan</p>
                                    <div class="text-xl font-black text-elevate-dark flex items-center gap-2.5">
                                        <?php $status = $student->alumniProfile->activity_status; ?>
                                        <span class="w-3.5 h-3.5 rounded-full shadow-inner
                                            <?php echo e($status == 'SMA' ? 'bg-elevate-primary' : ''); ?>

                                            <?php echo e($status == 'SMK' ? 'bg-orange-500' : ''); ?>

                                            <?php echo e($status == 'MA' ? 'bg-emerald-500' : ''); ?>

                                            <?php echo e($status == 'Pesantren' ? 'bg-teal-500' : ''); ?>

                                            <?php echo e($status == 'Bekerja' ? 'bg-slate-500' : ''); ?>

                                            <?php echo e($status == 'Lainnya' ? 'bg-purple-500' : ''); ?>">
                                        </span>
                                        <?php echo e($status); ?>

                                    </div>
                                </div>

                                <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Instansi / Tempat</p>
                                    <div class="text-lg font-black text-elevate-dark leading-tight line-clamp-1" title="<?php echo e($student->alumniProfile->campus_name ?? $student->alumniProfile->company_name); ?>">
                                        <?php echo e($student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-'); ?>

                                    </div>
                                    <?php if($student->alumniProfile->campus_major || $student->alumniProfile->position): ?>
                                        <div class="text-xs font-bold text-elevate-primary mt-1.5 uppercase tracking-wide">
                                            <?php echo e($student->alumniProfile->campus_major ?? $student->alumniProfile->position); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-emerald-50/50 border border-emerald-100/50">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i class="ph-fill ph-whatsapp-logo text-xl"></i></div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor WhatsApp</p>
                                        <?php if($student->alumniProfile->phone_number): ?>
                                            <a href="https://wa.me/<?php echo e($student->alumniProfile->phone_number); ?>" target="_blank" class="text-sm font-black text-emerald-700 hover:text-emerald-500 transition-colors">
                                                <?php echo e($student->alumniProfile->phone_number); ?>

                                            </a>
                                        <?php else: ?>
                                            <p class="text-sm font-bold text-slate-400">-</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50/50 border border-slate-100">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center shrink-0 shadow-sm"><i class="ph-bold ph-envelope-simple text-lg"></i></div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Email</p>
                                        <p class="text-sm font-bold text-elevate-dark truncate" title="<?php echo e($student->alumniProfile->email); ?>"><?php echo e($student->alumniProfile->email ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <?php if($student->alumniProfile->testimony): ?>
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                            <h3 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2">
                                <i class="ph-fill ph-quotes text-elevate-primary"></i> Kesan & Pesan
                            </h3>
                            <div class="bg-elevate-accent/5 p-6 rounded-2xl border border-elevate-accent/10 italic text-slate-600 font-medium leading-relaxed relative">
                                <i class="ph-fill ph-quotes text-4xl text-elevate-accent/20 absolute -top-3 -left-2"></i>
                                <span class="relative z-10">"<?php echo e($student->alumniProfile->testimony); ?>"</span>
                            </div>
                            <div class="mt-5 flex items-center gap-3 px-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rating Sekolah:</span>
                                <div class="flex text-amber-400 text-base drop-shadow-sm">
                                    <?php for($i=0; $i < ($student->alumniProfile->rating ?? 5); $i++): ?> <i class="ph-fill ph-star"></i> <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php else: ?>
                        
                        <div class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-slate-100 text-center flex flex-col items-center justify-center min-h-[400px]">
                            <div class="w-24 h-24 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 shadow-sm">
                                <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-elevate-dark mb-2">Belum Mengisi Tracer Study</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto mb-8 leading-relaxed">Alumni ini belum memperbarui data kelulusan atau rekam jejak sekolah lanjutan.</p>
                            
                            <a href="<?php echo e(route('admin.alumni.edit', $student->id)); ?>" class="inline-flex items-center gap-2 px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 transform active:scale-95">
                                <i class="ph-bold ph-pencil-simple text-lg"></i> Input Data Manual
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/alumni/show.blade.php ENDPATH**/ ?>