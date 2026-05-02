
<section id="ebooks" class="py-24 bg-slate-50 dark:bg-slate-950 relative overflow-hidden border-t border-slate-100 dark:border-slate-900 transition-colors duration-300">
    
    
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] dark:opacity-10 pointer-events-none transition-opacity duration-300"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-primary/10 dark:bg-elevate-primary/20 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-accent/10 dark:bg-elevate-accent/15 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors duration-300">
                    <i class="ph-fill ph-books text-sm"></i> E-Library
                </span>
                <h2 class="text-3xl md:text-5xl font-black text-elevate-dark dark:text-white leading-tight transition-colors duration-300">
                    Jelajahi Dunia Pengetahuan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-elevate-primary dark:from-elevate-accent dark:to-white">Tanpa Batas Ruang</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 mt-4 text-sm md:text-base font-medium transition-colors duration-300">
                    Akses koleksi buku digital terbaru SMPN 3 Lakbok kapan saja dan di mana saja.
                </p>
            </div>
            
            
            <a href="<?php echo e(route('library.catalogue')); ?>" class="hidden md:inline-flex items-center px-6 py-3 rounded-full text-xs font-bold text-elevate-dark dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent transition-all shadow-sm group">
                Lihat Katalog Lengkap
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $latestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group bg-white dark:bg-slate-900 rounded-[2rem] p-3 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 hover:-translate-y-2 transition-all duration-500 hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50 flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                    
                    
                    <div class="relative aspect-[2/3] rounded-[1.5rem] overflow-hidden mb-4 bg-slate-100 dark:bg-slate-800 shadow-inner group-hover:shadow-md transition-shadow">
                        <?php if($book->cover_path): ?>
                            <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" 
                                 alt="<?php echo e($book->title); ?>" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-elevate-primary/40 dark:text-slate-600 bg-elevate-soft/50 dark:bg-slate-800">
                                <i class="ph-duotone ph-book-open text-5xl mb-2"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">No Cover</span>
                            </div>
                        <?php endif; ?>
                        
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-elevate-dark/95 via-elevate-dark/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                            <a href="<?php echo e(route('library.books.read', $book->id)); ?>" class="w-full py-2.5 bg-elevate-accent hover:bg-white text-elevate-dark text-[10px] font-black uppercase tracking-widest rounded-xl flex items-center justify-center gap-2 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                <i class="ph-bold ph-read-cv-logo text-sm"></i> Baca Sekarang
                            </a>
                        </div>
                    </div>

                    
                    <div class="px-1 flex flex-col flex-1">
                        <h3 class="text-elevate-dark dark:text-white font-black text-sm md:text-base line-clamp-2 leading-snug mb-2 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors" title="<?php echo e($book->title); ?>">
                            <?php echo e($book->title); ?>

                        </h3>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold flex items-center gap-1.5 uppercase tracking-wide mt-auto transition-colors">
                            <i class="ph-bold ph-pen-nib text-elevate-accent"></i> <?php echo e($book->author ?? 'Anonim'); ?>

                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-16 text-center animate-enter bg-white dark:bg-slate-800/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-700 transition-colors" data-aos="fade-up">
                    <div class="inline-flex p-5 bg-elevate-soft dark:bg-slate-800 rounded-full mb-4 text-elevate-primary dark:text-slate-500 shadow-sm border border-elevate-accent/20 dark:border-slate-700 transition-colors">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-elevate-dark dark:text-slate-200 mb-1 transition-colors">Belum Ada Koleksi</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium transition-colors">Koleksi E-Book digital terbaru akan segera ditambahkan di sini.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="mt-10 text-center md:hidden" data-aos="fade-up">
            <a href="<?php echo e(route('library.catalogue')); ?>" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-elevate-primary dark:text-white bg-elevate-soft dark:bg-elevate-primary border border-elevate-accent/30 dark:border-transparent rounded-full hover:bg-white dark:hover:bg-elevate-dark transition-all shadow-sm group">
                Lihat Katalog Lengkap
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

    </div>
</section><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/ebooks.blade.php ENDPATH**/ ?>