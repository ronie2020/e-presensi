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
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">                
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-3 mb-2">
                            <a href="<?php echo e(route('library.dashboard')); ?>" class="px-3 py-1 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold text-blue-100 transition flex items-center gap-2">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-blue-300 text-xs font-bold uppercase tracking-wider">Koleksi</span>
                        </div>                      
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-books"></i> Katalog Perpustakaan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Koleksi Buku
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola inventaris buku perpustakaan, pantau ketersediaan stok, dan baca koleksi E-Book digital.
                        </p>
                    </div>
                    
                    
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white/15 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                <i class="ph-duotone ph-book-open-text text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Judul</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight"><?php echo e($books->total()); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 flex flex-col lg:flex-row gap-6 justify-between items-center relative overflow-hidden">
                
                
                <form method="GET" class="w-full lg:w-2/3 flex flex-col sm:flex-row gap-4 relative z-10">
                    <div class="relative flex-1 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul, pengarang, atau kode buku..." 
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm">
                    </div>
                    <div class="relative sm:w-64">
                        <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="category_id" onchange="this.form.submit()" 
                            class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="">Semua Kategori</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </form>

                
                <div class="flex gap-3 w-full lg:w-auto relative z-10">
                    <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="flex-1 lg:flex-none px-6 py-3.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-200 rounded-2xl font-bold text-sm transition-all shadow-sm flex items-center justify-center gap-2 group">
                        <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                        <span>Import</span>
                    </button>
                    <a href="<?php echo e(route('library.books.create')); ?>" class="flex-1 lg:flex-none px-6 py-3.5 bg-blue-900 text-white hover:bg-blue-800 rounded-2xl font-bold text-sm transition-all shadow-lg shadow-blue-900/20 hover:shadow-blue-900/40 flex items-center justify-center gap-2 transform active:scale-95">
                        <i class="ph-bold ph-plus-circle text-lg"></i>
                        <span>Tambah Buku</span>
                    </a>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group bg-white rounded-[2rem] border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 flex flex-col h-full overflow-hidden relative">
                        
                        
                        <div class="h-64 bg-slate-100 relative overflow-hidden">
                            <?php if($book->cover_path): ?>
                                <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="<?php echo e($book->title); ?>">
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                    <i class="ph-duotone ph-book-open text-5xl mb-2 opacity-50"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-50">No Cover</span>
                                </div>
                            <?php endif; ?>
                            
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                            
                            <?php if($book->ebook_path): ?>
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="px-2 py-1 bg-rose-500/90 backdrop-blur-md text-[10px] font-bold text-white rounded-lg shadow-sm border border-white/20 flex items-center gap-1">
                                        <i class="ph-bold ph-file-pdf"></i> E-Book
                                    </span>
                                </div>
                            <?php endif; ?>

                            
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1.5 bg-white/90 backdrop-blur-md text-[10px] font-black uppercase tracking-wider rounded-xl text-blue-900 shadow-sm border border-white/20">
                                    <?php echo e($book->category->name ?? 'Umum'); ?>

                                </span>
                            </div>

                            
                            <div class="absolute bottom-4 left-4 flex items-center gap-2">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-white flex items-center gap-1.5 backdrop-blur-md border border-white/10 shadow-lg <?php echo e($book->stock > 0 ? 'bg-emerald-500/80' : 'bg-rose-500/80'); ?>">
                                    <i class="<?php echo e($book->stock > 0 ? 'ph-bold ph-check-circle' : 'ph-bold ph-x-circle'); ?>"></i>
                                    Stok: <?php echo e($book->stock); ?>

                                </span>
                            </div>
                        </div>

                        
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-4">
                                <h3 class="font-black text-slate-800 text-lg leading-snug line-clamp-2 mb-1 group-hover:text-blue-700 transition-colors" title="<?php echo e($book->title); ?>">
                                    <?php echo e($book->title); ?>

                                </h3>
                                <p class="text-xs text-slate-500 font-bold flex items-center gap-1">
                                    <i class="ph-fill ph-pen-nib text-blue-400"></i> <?php echo e($book->author ?? 'Tanpa Pengarang'); ?>

                                </p>
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                                
                                <?php if($book->ebook_path): ?>
                                    <a href="<?php echo e(route('library.books.read', $book->id)); ?>" 
                                       class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-105 transition-all flex items-center gap-2 group/btn">
                                        <i class="ph-bold ph-read-cv-logo text-lg group-hover/btn:animate-pulse"></i>
                                        <span>Baca</span>
                                    </a>
                                <?php else: ?>
                                    <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                        <span class="text-[10px] font-mono font-bold text-slate-500"><?php echo e($book->book_code); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0 duration-300">
                                    <a href="<?php echo e(route('library.books.edit', $book->id)); ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Edit">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </a>
                                    <form action="<?php echo e(route('library.books.destroy', $book->id)); ?>" method="POST" class="delete-form">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors" title="Hapus">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 shadow-inner">
                            <i class="ph-duotone ph-books text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-700 mb-2">Koleksi Masih Kosong</h3>
                        <p class="text-slate-400 text-sm max-w-xs mx-auto mb-6">Belum ada data buku yang ditemukan. Mulai dengan menambahkan buku baru.</p>
                        <a href="<?php echo e(route('library.books.create')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/20">
                            <i class="ph-bold ph-plus"></i> Tambah Buku Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-8">
                <?php echo e($books->links()); ?>

            </div>
        </div>
    </div>

    
    <div id="importModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-white/20 relative z-10">
                
                
                <div class="bg-emerald-600 p-6 flex justify-between items-center">
                    <h3 class="text-lg font-black text-white flex items-center gap-2">
                        <i class="ph-bold ph-microsoft-excel-logo text-emerald-200"></i> Import Data Buku
                    </h3>
                    <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-emerald-200 hover:text-white transition-colors">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <div class="p-8">
                    <div class="text-center mb-6">
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">
                            Upload file Excel (.xlsx / .csv) untuk menambahkan banyak buku sekaligus.
                        </p>
                    </div>
                    
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 mb-6">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Format Header Wajib:</p>
                        <div class="font-mono text-xs text-slate-600 bg-white p-3 rounded-xl border border-slate-200 overflow-x-auto whitespace-nowrap">
                            kode_buku, judul, pengarang, penerbit, tahun, stok, rak, kategori
                        </div>
                    </div>

                    <form action="<?php echo e(route('library.books.import')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="relative group mb-6">
                            <input type="file" name="file" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-dashed border-slate-300 rounded-2xl py-3 px-4 hover:border-emerald-400 transition-all cursor-pointer bg-white">
                            
                            
                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="mt-2 p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2 text-rose-600 text-xs font-bold animate-pulse">
                                    <i class="ph-bold ph-warning-circle text-lg"></i>
                                    <span><?php echo e($message); ?></span>
                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="flex-1 py-3.5 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-3.5 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-upload-simple"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Hapus Buku Ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // rose-500
                cancelButtonColor: '#64748b',  // slate-500
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
        }
    </script>
    
    
    <?php if($errors->has('file')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Buka Modal
            document.getElementById('importModal').classList.remove('hidden');
            
            // 2. Tampilkan SweetAlert (Opsional, tapi bagus untuk UX)
            // PERBAIKAN: Menggunakan fungsi bawaan blade json_encode atau addslashes agar JavaScript tidak error jika pesan error mengandung tanda kutip
            Swal.fire({
                icon: 'error',
                title: 'Gagal Upload',
                text: <?php echo json_encode($errors->first('file')); ?>,
                customClass: { popup: 'rounded-[2rem]' }
            });
        });
    </script>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/books/index.blade.php ENDPATH**/ ?>