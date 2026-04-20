
    <section class="py-24 bg-slate-900 relative overflow-hidden">
        
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-cyan-500/20 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/20 border border-cyan-400/30 text-cyan-300 text-xs font-bold uppercase tracking-widest mb-4">
                        <i class="ph-fill ph-books"></i> E-Library
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white leading-tight">
                        Jelajahi Dunia Pengetahuan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-300">Tanpa Batas Ruang</span>
                    </h2>
                    <p class="text-slate-400 mt-4 text-lg leading-relaxed">
                        Akses koleksi buku digital terbaru SMPN 3 Lakbok kapan saja dan di mana saja.
                    </p>
                </div>
                
                
                <a href="<?php echo e(route('library.catalogue')); ?>" class="group flex items-center gap-2 px-6 py-3 bg-white text-slate-900 font-bold rounded-full hover:bg-cyan-50 transition-all shadow-xl shadow-cyan-900/20">
                    <span>Lihat Katalog Lengkap</span>
                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $latestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group relative" data-aos="fade-up" data-aos-delay="<?php echo e($loop->iteration * 100); ?>">
                        <div class="book-glass rounded-2xl p-3 h-full flex flex-col hover:-translate-y-2 transition-transform duration-300 shadow-2xl">
                            
                            
                            <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-4 bg-slate-800 shadow-inner">
                                <?php if($book->cover_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" 
                                         alt="<?php echo e($book->title); ?>" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <?php else: ?>
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-600">
                                        <i class="ph-duotone ph-book-open text-4xl mb-2"></i>
                                        <span class="text-[10px] font-bold uppercase">No Cover</span>
                                    </div>
                                <?php endif; ?>
                                
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                    <a href="<?php echo e(route('library.books.read', $book->id)); ?>" class="w-full py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold rounded-lg flex items-center justify-center gap-2 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                        <i class="ph-bold ph-read-cv-logo"></i> Baca Sekarang
                                    </a>
                                </div>
                            </div>

                            
                            <div class="mt-auto">
                                <h3 class="text-white font-bold text-sm line-clamp-2 leading-snug mb-1 group-hover:text-cyan-400 transition-colors" title="<?php echo e($book->title); ?>">
                                    <?php echo e($book->title); ?>

                                </h3>
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="ph-fill ph-pen-nib"></i> <?php echo e($book->author ?? 'Anonim'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-12 text-center book-glass rounded-3xl">
                        <i class="ph-duotone ph-books text-5xl text-slate-600 mb-4"></i>
                        <p class="text-slate-400 font-bold">Belum ada koleksi E-Book terbaru.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/ebooks.blade.php ENDPATH**/ ?>