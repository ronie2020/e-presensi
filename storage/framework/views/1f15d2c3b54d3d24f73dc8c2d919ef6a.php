<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Perpustakaan</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-slate-50 font-sans text-slate-800">

    
    <div class="bg-gray-900 bg-gradient-to-b from-cyan-600 via-blue-800 to-slate-900 pt-16 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
        <div class="absolute top-0 left-1/2 w-96 h-96 bg-cyan-400/20 rounded-full blur-[80px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        
        
        <div class="absolute top-6 left-6 z-20">
            <?php if(Auth::guard('student')->check()): ?>
                
                <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>?tab=perpustakaan" class="text-cyan-100 hover:text-white flex items-center gap-2 text-sm font-bold transition-colors bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Portal
                </a>
            <?php else: ?>
                
                <a href="<?php echo e(url('/')); ?>" class="text-cyan-100 hover:text-white flex items-center gap-2 text-sm font-bold transition-colors">
                    <i class="ph-bold ph-house"></i> Beranda
                </a>
            <?php endif; ?>
        </div>

        <div class="max-w-7xl mx-auto relative z-10 text-center mt-8">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-900/50 border border-cyan-400/30 text-cyan-100 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-md">
                <i class="ph-fill ph-student"></i> Perpustakaan Digital
            </div>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-4 leading-tight drop-shadow-md">
                Jelajahi Dunia Lewat <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-200">Buku</span>
            </h1>
            <p class="text-cyan-50/80 text-lg font-medium max-w-2xl mx-auto">
                Cari buku favoritmu, cek ketersediaan stok, atau baca E-Book langsung dari perangkatmu.
            </p>

            
            <div class="mt-10 max-w-3xl mx-auto">
                <form method="GET" class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative flex bg-white rounded-2xl shadow-2xl overflow-hidden p-1.5 border border-white/50">
                        <div class="flex-1 flex items-center px-4">
                            <i class="ph-bold ph-magnifying-glass text-slate-400 text-xl group-focus-within:text-cyan-500 transition-colors"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul buku, penulis, atau topik..." 
                                class="w-full border-none focus:ring-0 text-slate-700 font-bold placeholder-slate-400 h-12 bg-transparent outline-none">
                        </div>
                        <div class="w-px h-8 bg-slate-100 my-auto"></div>
                        <div class="w-1/3 max-w-[200px] hidden sm:block">
                            <select name="category_id" onchange="this.form.submit()" 
                                class="w-full border-none focus:ring-0 text-slate-600 font-bold text-sm bg-transparent h-12 cursor-pointer outline-none">
                                <option value="">Semua Kategori</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 rounded-xl font-bold transition-colors shadow-lg shadow-cyan-600/20 active:scale-95">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20 pb-20">
        
        <?php if(request('search') || request('category_id')): ?>
            <div class="mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-600 uppercase text-xs tracking-wider">
                    Hasil Pencarian: "<?php echo e(request('search')); ?>"
                </h3>
                <a href="<?php echo e(route('library.catalogue')); ?>" class="text-rose-500 text-xs font-bold hover:underline flex items-center gap-1">
                    <i class="ph-bold ph-x-circle"></i> Reset Filter
                </a>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group bg-white rounded-[2rem] border border-slate-100 hover:border-cyan-300 hover:shadow-2xl hover:shadow-cyan-900/10 transition-all duration-300 flex flex-col h-full overflow-hidden relative transform hover:-translate-y-1">
                    
                    
                    <div class="h-72 bg-slate-100 relative overflow-hidden">
                        <?php if($book->cover_path): ?>
                            <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="<?php echo e($book->title); ?>">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                <i class="ph-duotone ph-book-open text-5xl mb-2 opacity-50"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-50">No Cover</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                        
                        <?php if($book->ebook_path): ?>
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-3 py-1.5 bg-rose-500 text-[10px] font-black text-white uppercase tracking-wider rounded-lg shadow-lg shadow-rose-500/30 flex items-center gap-1.5 animate-pulse">
                                    <i class="ph-fill ph-file-pdf"></i> E-Book
                                </span>
                            </div>
                        <?php endif; ?>

                        
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 bg-white/20 backdrop-blur-md text-[10px] font-black uppercase tracking-wider rounded-lg text-white border border-white/20">
                                <?php echo e($book->category->name ?? 'Umum'); ?>

                            </span>
                        </div>

                        
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold <?php echo e($book->stock > 0 ? 'bg-emerald-500/80 text-white' : 'bg-rose-500/80 text-white'); ?> backdrop-blur-md">
                                    <?php echo e($book->stock > 0 ? 'Stok: '.$book->stock : 'Habis'); ?>

                                </span>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold bg-white/20 backdrop-blur-md border border-white/10 font-mono">
                                    <?php echo e($book->book_code); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h3 class="font-black text-slate-800 text-lg leading-snug line-clamp-2 mb-1 group-hover:text-cyan-700 transition-colors" title="<?php echo e($book->title); ?>">
                                <?php echo e($book->title); ?>

                            </h3>
                            <p class="text-xs text-slate-500 font-bold flex items-center gap-1">
                                <i class="ph-fill ph-pen-nib text-cyan-400"></i> <?php echo e($book->author ?? 'Tanpa Pengarang'); ?>

                            </p>
                        </div>
                        
                        <p class="text-xs text-slate-400 line-clamp-2 mb-4 leading-relaxed">
                            <?php echo e($book->description ?? 'Tidak ada sinopsis tersedia.'); ?>

                        </p>

                        <div class="mt-auto pt-4 border-t border-slate-50">
                            <?php if($book->ebook_path): ?>
                                
                                <a href="<?php echo e(route('library.books.read', $book->id)); ?>" 
                                   class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white text-xs font-bold shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50 transition-all flex items-center justify-center gap-2 group/btn transform active:scale-95">
                                    <i class="ph-bold ph-book-open-text text-lg group-hover/btn:animate-pulse"></i>
                                    <span>Baca E-Book</span>
                                </a>
                            <?php else: ?>
                                <button disabled class="w-full py-3 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold flex items-center justify-center gap-2 cursor-not-allowed">
                                    <i class="ph-bold ph-prohibit text-lg"></i>
                                    <span>Fisik Only</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-cyan-50 mb-4 shadow-inner">
                        <i class="ph-duotone ph-magnifying-glass text-5xl text-cyan-400"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-700">Buku tidak ditemukan</h3>
                    <p class="text-slate-400 text-sm">Coba cari dengan kata kunci lain.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-10">
            <?php echo e($books->links()); ?>

        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/books/catalogue.blade.php ENDPATH**/ ?>