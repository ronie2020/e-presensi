<?php $__env->startSection('title', 'Galeri Kegiatan - ' . config('app.name', 'SMP Negeri 3 Lakbok')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Shared Animations */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        @keyframes pulse-ring { 0% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); } 100% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); } }
        .animate-pulse-ring { animation: pulse-ring 2s infinite; }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <div x-data="{ 
            lightboxOpen: false, 
            imgSrc: '', 
            imgTitle: '',
            openLightbox(src, title) {
                this.imgSrc = src;
                this.imgTitle = title;
                this.lightboxOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeLightbox() {
                this.lightboxOpen = false;
                setTimeout(() => { this.imgSrc = '' }, 300);
                document.body.style.overflow = 'auto';
            }
        }">

        <!-- HEADER SECTION -->
        <div class="relative overflow-hidden bg-slate-900 py-20 sm:py-24 group -mt-24">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
            
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-500/20 rounded-full blur-[80px] animate-float"></div>
            <div class="absolute bottom-0 left-20 w-64 h-64 bg-purple-500/20 rounded-full blur-[60px] animate-float" style="animation-delay: 1.5s;"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center pt-12">
                <div class="animate-enter inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-sm">
                    <i class="ph-fill ph-camera"></i> Dokumentasi Sekolah
                </div>
                <h1 class="animate-enter text-4xl md:text-6xl font-black text-white mb-6 tracking-tight" style="animation-delay: 100ms;">
                    Galeri Kegiatan
                </h1>
                <p class="animate-enter text-blue-100/80 text-lg max-w-2xl mx-auto font-medium leading-relaxed" style="animation-delay: 200ms;">
                    Kumpulan momen berharga, aktivitas akademik, dan keceriaan siswa dalam lingkungan belajar SMP Negeri 3 Lakbok.
                </p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <main class="flex-grow py-16 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="animate-enter group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2 transition-all duration-500 flex flex-col h-full"
                             style="animation-delay: <?php echo e($index * 100); ?>ms;">
                            
                            <!-- Image Wrapper -->
                            <div class="relative h-64 overflow-hidden bg-slate-200 cursor-pointer"
                                 
                                 <?php if(!$activity->video_url): ?>
                                    @click="openLightbox('<?php echo e(asset('storage/' . $activity->image_path)); ?>', '<?php echo e(addslashes($activity->title)); ?>')"
                                 <?php endif; ?>
                                >
                                
                                <?php if($activity->image_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $activity->image_path)); ?>" 
                                         alt="<?php echo e($activity->title); ?>" 
                                         loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 hidden bg-slate-100">
                                        <i class="ph-duotone ph-image-broken text-4xl"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
                                        <i class="ph-duotone ph-image text-5xl"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                                <!-- Zoom Icon (Indikator bisa diklik) -->
                                <?php if(!$activity->video_url): ?>
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <span class="bg-black/50 backdrop-blur-sm text-white p-3 rounded-full">
                                            <i class="ph-bold ph-magnifying-glass-plus text-xl"></i>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Date Badge -->
                                <div class="absolute top-5 left-5 pointer-events-none">
                                    <span class="bg-white/95 backdrop-blur-md text-slate-800 text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                                        <i class="ph-bold ph-calendar-blank text-blue-600"></i> 
                                        <?php echo e($activity->created_at->format('d M Y')); ?>

                                    </span>
                                </div>

                                <!-- Video Play Button -->
                                <?php if($activity->video_url): ?>
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/50 group-hover:scale-110 transition-transform animate-pulse-ring">
                                            <i class="ph-fill ph-play text-2xl ml-1"></i>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <div class="p-8 flex-1 flex flex-col relative">
                                <div class="mb-3">
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md uppercase tracking-wider">
                                        <?php echo e($activity->video_url ? 'Video Dokumentasi' : 'Foto Galeri'); ?>

                                    </span>
                                </div>

                                <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors leading-tight line-clamp-2">
                                    <?php echo e($activity->title); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-6 font-medium">
                                    <?php echo e($activity->description); ?>

                                </p>

                                <?php if($activity->video_url): ?>
                                    <div class="mt-auto pt-5 border-t border-slate-50">
                                        <a href="<?php echo e($activity->video_url); ?>" target="_blank" class="flex items-center justify-center w-full px-5 py-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-600 hover:text-white transition-all duration-300 group/btn shadow-sm hover:shadow-red-500/20">
                                            <i class="ph-fill ph-youtube-logo text-xl mr-2 group-hover/btn:scale-110 transition-transform"></i> 
                                            Tonton Video
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="mt-auto pt-5 border-t border-slate-50 flex items-center text-slate-400 text-xs font-bold gap-1 group-hover:text-blue-600 transition-colors cursor-pointer"
                                         @click="openLightbox('<?php echo e(asset('storage/' . $activity->image_path)); ?>', '<?php echo e(addslashes($activity->title)); ?>')">
                                        <i class="ph-bold ph-images"></i> Lihat Fullscreen
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full py-24 text-center animate-enter">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 mb-6 text-slate-300 ring-8 ring-slate-50">
                                <i class="ph-duotone ph-aperture text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 mb-2">Belum Ada Dokumentasi</h3>
                            <p class="text-slate-500 font-medium max-w-md mx-auto">
                                Galeri kegiatan sekolah belum tersedia saat ini. Kunjungi lagi nanti!
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-16">
                    <?php echo e($activities->links()); ?>

                </div>
            </div>
        </main>

        
        <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop Gelap -->
            <div x-show="lightboxOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/95 backdrop-blur-sm transition-opacity" 
                 @click="closeLightbox()">
            </div>

            <!-- Gambar Fullscreen -->
            <div x-show="lightboxOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-90" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100" 
                 x-transition:leave-end="opacity-0 scale-90" 
                 class="relative z-10 w-full max-w-5xl p-4 flex flex-col items-center">
                
                <!-- Close Button -->
                <button @click="closeLightbox()" class="absolute -top-12 right-4 text-white hover:text-red-400 transition-colors">
                    <i class="ph-bold ph-x text-3xl"></i>
                </button>

                <img :src="imgSrc" class="max-h-[85vh] w-auto rounded-lg shadow-2xl border-4 border-slate-800" alt="Fullscreen Image">
                
                <p x-text="imgTitle" class="mt-4 text-white text-lg font-medium text-center bg-black/50 px-4 py-2 rounded-full backdrop-blur-md"></p>
            </div>
        </div>    
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/activities.blade.php ENDPATH**/ ?>