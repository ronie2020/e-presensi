<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100 sticky top-24">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Catatan Prestasi</h3>
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100 text-center mt-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-200/50 rounded-full blur-2xl -mr-10 -mt-10"></div>
                
                
                <p class="text-5xl font-black text-emerald-600 relative z-10">+<?php echo e($total_merit_points ?? 0); ?></p>
                <p class="text-xs text-emerald-500 mt-2 font-bold uppercase tracking-widest relative z-10">Total Poin</p>
            </div>
            <div class="mt-6 text-sm text-slate-500 leading-relaxed text-center">
                "Terus tingkatkan kebaikanmu untuk menjadi inspirasi bagi teman-teman!"
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
            <?php if(isset($achievements) && count($achievements) > 0): ?>
                <div class="relative border-l-2 border-slate-200 ml-3 space-y-8">
                    <?php $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative pl-8 group">
                            <!-- Dot Timeline (Green) -->
                            <div class="absolute -left-[9px] top-0 w-5 h-5 bg-emerald-100 border-2 border-emerald-500 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
                            
                            <!-- Content Header: Nama Kebaikan & Poin -->
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-1">
                                <h4 class="font-bold text-slate-800 text-lg group-hover:text-emerald-600 transition-colors">
                                    <?php echo e($record->disciplineType->name ?? 'Jenis Prestasi Dihapus'); ?>

                                </h4>
                                
                                
                                <span class="text-xs font-bold px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-100 whitespace-nowrap">
                                    +<?php echo e($record->disciplineType->point_value ?? 0); ?> Poin
                                </span>
                            </div>
                            
                            <!-- Tanggal Kejadian -->
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-calendar-blank"></i>
                                <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y')); ?>

                            </p>
                            
                            <!-- Catatan Detail -->
                            <?php if($record->notes): ?>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-600 italic relative">
                                    <i class="ph-fill ph-quotes text-emerald-200 text-2xl absolute top-2 right-2"></i>
                                    "<?php echo e($record->notes); ?>"
                                </div>
                            <?php else: ?>
                                <div class="text-xs text-slate-300 italic">Tidak ada catatan tambahan.</div>
                            <?php endif; ?>

                             <!-- (Opsional) Menampilkan Guru Pencatat -->
                             <?php if($record->recorder): ?>
                                <div class="mt-2 flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                    <i class="ph-fill ph-user-circle"></i> Dicatat oleh: <?php echo e($record->recorder->name ?? 'Guru'); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4 animate-bounce">
                        <i class="ph-duotone ph-trophy text-4xl text-emerald-300"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Belum Ada Prestasi</h3>
                    <p class="text-slate-500 text-sm mt-2 max-w-xs">Ayo tunjukkan bakatmu dan kumpulkan poin kebaikan!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-prestasi.blade.php ENDPATH**/ ?>