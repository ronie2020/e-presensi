<?php $__env->startSection('title', 'Arsip Prestasi - ' . config('app.name', 'SMP Negeri 3 Lakbok')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Mencegah elemen berkedip saat AlpineJS belum siap */
        [x-cloak] { display: none !important; }

        /* Animasi Custom Khusus Halaman Ini */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        .animate-blob { animation: blob 7s infinite; }
        @keyframes blob { 
            0% { transform: translate(0px, 0px) scale(1); } 
            33% { transform: translate(30px, -50px) scale(1.1); } 
            66% { transform: translate(-20px, 20px) scale(0.9); } 
            100% { transform: translate(0px, 0px) scale(1); } 
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- HEADER SECTION -->
    <div class="bg-slate-900 pt-32 pb-32 relative overflow-hidden -mt-24">
        <div class="absolute inset-0 bg-yellow-600/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/95 to-slate-900"></div>

        <!-- Animated Blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-30 animate-blob" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-300 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm">
                <i class="ph-fill ph-trophy"></i> Hall of Fame
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight">Arsip Prestasi Sekolah</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                Kumpulan jejak juara dan penghargaan yang diraih oleh siswa, guru, dan institusi SMP Negeri 3 Lakbok di berbagai tingkatan.
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-20">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $achievements ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $prestasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-yellow-900/10 hover:-translate-y-2 transition-all duration-500 border border-slate-100 flex flex-col h-full relative animate-enter" style="animation-delay: <?php echo e(($index % 6) * 100); ?>ms">

                    <!-- Foto -->
                    <div class="aspect-video bg-slate-100 relative overflow-hidden group">
                        <?php if(!empty($prestasi->photo_path)): ?>
                            <img src="<?php echo e(asset('storage/' . $prestasi->photo_path)); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="<?php echo e($prestasi->title); ?>">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-500 text-white">
                                <i class="ph-bold ph-trophy text-6xl opacity-50 group-hover:scale-110 transition-transform duration-500"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Badge Tingkat -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 rounded-full bg-white/90 backdrop-blur text-[10px] font-black uppercase text-yellow-700 tracking-wider shadow-sm border border-white/50">
                                <?php echo e($prestasi->level ?? 'Sekolah'); ?>

                            </span>
                        </div>
                    </div>

                    <!-- Info Konten -->
                    <div class="p-6 flex-1 flex flex-col relative bg-white">
                        <div class="text-xs text-slate-400 font-bold mb-3 flex items-center gap-1.5">
                            <i class="ph-fill ph-calendar-blank"></i>
                            <?php echo e(isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->translatedFormat('d F Y') : '-'); ?>

                        </div>

                        <h3 class="text-xl font-black text-slate-800 leading-tight mb-4 group-hover:text-yellow-600 transition-colors">
                            <?php echo e($prestasi->title); ?>

                        </h3>

                        <!-- Info Juara -->
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0
                                    <?php echo e($prestasi->type === 'Siswa' ? 'text-blue-500' : ($prestasi->type === 'Guru' ? 'text-emerald-500' : 'text-orange-500')); ?>">
                                    <?php if($prestasi->type === 'Siswa'): ?>
                                        <i class="ph-bold ph-student text-xl"></i>
                                    <?php elseif($prestasi->type === 'Guru'): ?>
                                        <i class="ph-bold ph-chalkboard-teacher text-xl"></i>
                                    <?php else: ?>
                                        <i class="ph-bold ph-buildings text-xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700 line-clamp-1"><?php echo e($prestasi->achiever_name); ?></p>
                                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-wider"><?php echo e($prestasi->type); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL TAMBAHAN: Foto, Sertifikat & Video -->
                        <?php if(!empty($prestasi->photo_path) || !empty($prestasi->certificate_path) || !empty($prestasi->video_link)): ?>
                            <div class="mt-5 flex flex-wrap gap-2">
                                
                                <?php if(!empty($prestasi->photo_path)): ?>
                                    <a href="<?php echo e(asset('storage/' . $prestasi->photo_path)); ?>" target="_blank" class="flex-1 min-w-[30%] flex items-center justify-center gap-1.5 px-3 py-2.5 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl text-xs font-bold transition-colors">
                                        <i class="ph-bold ph-image text-sm"></i> Foto
                                    </a>
                                <?php endif; ?>

                                
                                <?php if(!empty($prestasi->certificate_path)): ?>
                                    <a href="<?php echo e(asset('storage/' . $prestasi->certificate_path)); ?>" target="_blank" class="flex-1 min-w-[30%] flex items-center justify-center gap-1.5 px-3 py-2.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl text-xs font-bold transition-colors">
                                        <i class="ph-bold ph-certificate text-sm"></i> Sertifikat
                                    </a>
                                <?php endif; ?>
                                
                                
                                <?php if(!empty($prestasi->video_link)): ?>
                                    <a href="<?php echo e($prestasi->video_link); ?>" target="_blank" class="flex-1 min-w-[30%] flex items-center justify-center gap-1.5 px-3 py-2.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl text-xs font-bold transition-colors">
                                        <i class="ph-bold ph-youtube-logo text-sm"></i> Video
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-24 text-center animate-enter">
                    <div class="inline-flex bg-slate-100 p-6 rounded-full mb-6 text-slate-300 ring-8 ring-slate-50">
                        <i class="ph-duotone ph-trophy text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Arsip Prestasi</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto">Saat ini belum ada data prestasi yang tercatat dalam sistem arsip sekolah.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-16 px-4 animate-enter">
            <?php if(isset($achievements) && method_exists($achievements, 'links')): ?>
                <?php echo e($achievements->links()); ?>

            <?php endif; ?>
        </div>
        
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/achievements.blade.php ENDPATH**/ ?>