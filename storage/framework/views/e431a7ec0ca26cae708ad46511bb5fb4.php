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
            <?php echo e(__('Riwayat Mengajar')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <?php
        // Pastikan format tanggal menggunakan Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');
    ?>

    <div class="py-6 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-6 sm:p-8 mb-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left w-full md:w-auto">
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-1 flex items-center justify-center md:justify-start gap-3">
                            <i class="ph-duotone ph-clock-counter-clockwise hidden md:block"></i> Jejak Aktivitas
                        </h2>
                        <p class="text-blue-300 text-xs sm:text-sm font-medium">
                            Rekapitulasi kegiatan mengajar Anda per bulan.
                        </p>
                    </div>

                    
                    <form method="GET" action="<?php echo e(route('teaching.history')); ?>" class="w-full md:w-auto relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-calendar-blank text-blue-300"></i>
                        </div>
                        <input type="month" name="month" value="<?php echo e($month); ?>" onchange="this.form.submit()" 
                            class="pl-11 pr-5 py-3 w-full md:w-auto bg-white/10 border border-white/20 rounded-2xl text-sm font-bold text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 cursor-pointer hover:bg-white/20 transition-all shadow-lg backdrop-blur-sm">
                    </form>
                </div>
            </div>

            
            
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                
                <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        
                        <!-- Icon Dot di Tengah -->
                        <div class="flex items-center justify-center w-12 h-12 rounded-full border-[6px] border-slate-50 bg-white shadow-md group-hover:bg-blue-600 group-hover:text-white text-slate-300 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 transition-all duration-500 z-10 group-hover:scale-110 group-hover:shadow-blue-200 relative left-0 md:left-auto">
                            <i class="ph-bold ph-check text-lg"></i>
                        </div>
                        
                        <!-- Konten Card (Responsive Width) -->
                        <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white rounded-[2rem] p-5 sm:p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 group-hover:ring-1 group-hover:ring-blue-100">
                            
                            
                            <div class="flex justify-between items-start mb-4 border-b border-slate-50 pb-3">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-wider border border-blue-100">
                                        <i class="ph-bold ph-users-three"></i> <?php echo e($history->schedule->schoolClass->name ?? '-'); ?>

                                    </span>
                                    <h3 class="font-bold text-base sm:text-lg text-slate-800 leading-tight group-hover:text-blue-700 transition-colors">
                                        <?php echo e($history->schedule->subject->name ?? 'Mapel Dihapus'); ?>

                                    </h3>
                                </div>
                                <div class="text-right bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 min-w-[60px]">
                                    <div class="text-lg sm:text-xl font-black text-slate-800 leading-none"><?php echo e(\Carbon\Carbon::parse($history->date)->format('d')); ?></div>
                                    <div class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wide"><?php echo e(\Carbon\Carbon::parse($history->date)->translatedFormat('M')); ?></div>
                                </div>
                            </div>

                            
                            <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl p-4 mb-4 border border-slate-100 group-hover:border-blue-50 transition-colors">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1 flex items-center gap-1">
                                    <i class="ph-bold ph-notebook text-blue-400"></i> Topik Bahasan
                                </p>
                                <p class="text-xs sm:text-sm font-medium text-slate-700 line-clamp-2 leading-relaxed">
                                    <?php echo e($history->topic ?? 'Tidak ada judul topik.'); ?>

                                </p>
                            </div>

                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 sm:gap-3 bg-slate-50 rounded-xl px-2 sm:px-3 py-1.5 border border-slate-100">
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs font-bold text-emerald-600" title="Hadir">
                                        <i class="ph-fill ph-check-circle"></i> <?php echo e($history->hadir); ?>

                                    </div>
                                    <div class="w-px h-3 bg-slate-200"></div>
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs font-bold text-rose-500" title="Alpha/Absen">
                                        <i class="ph-fill ph-x-circle"></i> <?php echo e($history->alpha); ?>

                                    </div>
                                </div>
                                
                                <a href="<?php echo e(route('teaching.show', $history->id)); ?>" class="group/link flex items-center gap-1 text-[10px] sm:text-xs font-bold text-white bg-slate-800 hover:bg-blue-600 px-3 sm:px-4 py-2 rounded-xl transition-all shadow-md hover:shadow-lg">
                                    Detail <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="relative flex flex-col items-center justify-center py-20 text-center z-10">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-slate-50 rounded-full flex items-center justify-center shadow-sm border border-slate-100 mb-6 animate-pulse">
                            <i class="ph-duotone ph-notebook text-4xl sm:text-5xl text-slate-300"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-lg sm:text-xl">Belum Ada Riwayat</h3>
                        <p class="text-slate-500 text-xs sm:text-sm mt-2 max-w-xs mx-auto leading-relaxed">
                            Aktivitas mengajar Anda di bulan <span class="font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($month)->translatedFormat('F Y')); ?></span> belum terekam.
                        </p>
                    </div>
                <?php endif; ?>

            </div>

            
            <div class="mt-12 flex justify-center">
                <?php echo e($histories->links()); ?>

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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/teaching/history.blade.php ENDPATH**/ ?>