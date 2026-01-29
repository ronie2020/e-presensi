<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-indigo-100 sticky top-24 h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Statistik Saya</h3>
            <p class="text-slate-400 text-xs mb-6">Ringkasan aktivitas perpustakaanmu.</p>
            
            <div class="space-y-3">
                 
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center">
                            <i class="ph-bold ph-read-cv-logo text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">E-Book Dibaca</p>
                            <p class="text-[10px] text-slate-400">Literasi Digital</p>
                        </div>
                    </div>
                    <span class="text-xl font-black text-slate-800"><?php echo e(isset($ebookHistory) ? $ebookHistory->count() : 0); ?></span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="ph-bold ph-book-open-text text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Riwayat</p>
                            <p class="text-[10px] text-slate-400">Peminjaman Fisik</p>
                        </div>
                    </div>
                    <span class="text-xl font-black text-slate-800"><?php echo e($library_visits ?? 0); ?></span>
                </div>

                
                <?php
                    $activeLoans = isset($library_history) ? $library_history->where('status', 'borrowed')->count() : 0;
                ?>
                <div class="flex items-center justify-between p-4 <?php echo e($activeLoans > 0 ? 'bg-amber-50 border-amber-100' : 'bg-slate-50 border-slate-100'); ?> rounded-2xl border transition-all hover:bg-white hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full <?php echo e($activeLoans > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-200 text-slate-500'); ?> flex items-center justify-center">
                            <i class="ph-bold ph-hand-holding text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold <?php echo e($activeLoans > 0 ? 'text-amber-600' : 'text-slate-500'); ?> uppercase tracking-wider">Sedang Dipinjam</p>
                            <p class="text-[10px] text-slate-400">Harus dikembalikan</p>
                        </div>
                    </div>
                    <span class="text-xl font-black <?php echo e($activeLoans > 0 ? 'text-amber-600' : 'text-slate-800'); ?>"><?php echo e($activeLoans); ?></span>
                </div>
            </div>

            
            <div class="mt-6 p-4 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-2xl text-white shadow-lg shadow-blue-500/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full blur-xl -mr-4 -mt-4"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-lightbulb text-yellow-300"></i>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-100">Tips Literasi</span>
                    </div>
                    <p class="text-xs leading-relaxed opacity-90">
                        "Membaca satu buku E-Book per minggu dapat meningkatkan wawasanmu secara signifikan."
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="lg:col-span-2 space-y-8">

        
        <?php if(isset($ebookHistory) && $ebookHistory->count() > 0): ?>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 overflow-hidden">
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mb-4">
                <i class="ph-fill ph-clock-counter-clockwise text-violet-500"></i> Terakhir Dibaca
            </h3>
            <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar">
                <?php $__currentLoopData = $ebookHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($history->book): ?>
                    <div class="flex-shrink-0 w-32 group relative">
                        <div class="aspect-[2/3] bg-slate-200 rounded-xl overflow-hidden mb-2 relative shadow-md">
                            <?php if($history->book->cover_path): ?>
                                <img src="<?php echo e(asset('storage/' . $history->book->cover_path)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                                    <i class="ph-duotone ph-book-open text-2xl mb-1"></i>
                                </div>
                            <?php endif; ?>
                            
                            
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px]">
                                <a href="<?php echo e(route('library.books.read', ['book' => $history->book->id, 'origin' => 'portal'])); ?>" class="px-3 py-1.5 bg-white text-blue-900 rounded-full text-[10px] font-bold shadow-xl transform scale-90 group-hover:scale-100 transition-transform flex items-center gap-1">
                                    <i class="ph-bold ph-arrow-right"></i> Lanjut
                                </a>
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 text-[10px] line-clamp-2 leading-snug mb-0.5" title="<?php echo e($history->book->title); ?>">
                            <?php echo e($history->book->title); ?>

                        </h4>
                        <p class="text-[9px] text-slate-400">
                            <?php echo e(\Carbon\Carbon::parse($history->created_at)->diffForHumans()); ?>

                        </p>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
        
        
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 sm:p-8" 
             x-data="{ search: '' }">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-books text-blue-500"></i> Perpustakaan Digital
                    </h3>
                    <p class="text-slate-400 text-sm font-medium mt-1">
                        <?php echo e(isset($ebooks) ? $ebooks->count() : 0); ?> buku digital tersedia untuk dibaca.
                    </p>
                </div>
                
                
                <div class="relative w-full sm:w-64 group">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="text" x-model="search" placeholder="Cari judul buku..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none">
                </div>
            </div>

            <?php if(isset($ebooks) && $ebooks->count() > 0): ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <?php $__currentLoopData = $ebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <div class="group relative bg-slate-50 rounded-2xl p-2.5 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col h-full"
                             x-show="'<?php echo e(strtolower($book->title)); ?>'.includes(search.toLowerCase()) || '<?php echo e(strtolower($book->author ?? '')); ?>'.includes(search.toLowerCase())"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100">
                            
                            
                            <div class="aspect-[2/3] bg-slate-200 rounded-xl overflow-hidden mb-3 relative shadow-inner">
                                <?php if($book->cover_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="<?php echo e($book->title); ?>">
                                <?php else: ?>
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                                        <i class="ph-duotone ph-book-open text-3xl mb-1"></i>
                                        <span class="text-[9px] uppercase font-bold">No Cover</span>
                                    </div>
                                <?php endif; ?>
                                
                                
                                <?php if($book->category): ?>
                                    <span class="absolute top-2 right-2 bg-black/50 backdrop-blur-md text-white text-[9px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider border border-white/10">
                                        <?php echo e(Str::limit($book->category->name, 10)); ?>

                                    </span>
                                <?php endif; ?>

                                
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px]">
                                    <a href="<?php echo e(route('library.books.read', ['book' => $book->id, 'origin' => 'portal'])); ?>" class="px-4 py-2 bg-white text-blue-900 rounded-full text-xs font-bold shadow-xl transform scale-90 group-hover:scale-100 transition-transform flex items-center gap-1.5 hover:bg-blue-50">
                                        <i class="ph-bold ph-read-cv-logo text-lg"></i> BACA
                                    </a>
                                </div>
                            </div>
                            
                            
                            <div class="px-1 flex flex-col flex-1">
                                <h4 class="font-bold text-slate-800 text-xs line-clamp-2 leading-snug mb-1 group-hover:text-blue-600 transition-colors" title="<?php echo e($book->title); ?>">
                                    <?php echo e($book->title); ?>

                                </h4>
                                <p class="text-[10px] text-slate-400 line-clamp-1 mt-auto flex items-center gap-1">
                                    <i class="ph-fill ph-pen-nib"></i> <?php echo e($book->author ?? 'Tanpa Pengarang'); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                
                <div x-show="search != '' && $el.previousElementSibling.querySelectorAll('div[x-show]:not([style*=\'display: none\'])').length === 0" 
                     class="text-center py-10" style="display: none;">
                    <div class="inline-flex p-3 bg-slate-100 rounded-full text-slate-400 mb-2">
                        <i class="ph-duotone ph-magnifying-glass text-2xl"></i>
                    </div>
                    <p class="text-slate-500 text-sm font-bold">Buku tidak ditemukan.</p>
                </div>

            <?php else: ?>
                <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-300">
                        <i class="ph-duotone ph-books text-3xl"></i>
                    </div>
                    <h3 class="text-slate-800 font-bold text-sm">Belum ada koleksi E-Book</h3>
                    <p class="text-slate-400 text-xs mt-1">Nantikan buku-buku digital seru di sini.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8 pb-4 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-clock-counter-clockwise text-orange-500"></i> Riwayat Peminjaman
                </h3>
                <?php if(isset($library_history) && count($library_history) > 0): ?>
                    <span class="text-xs font-bold text-slate-400"><?php echo e(count($library_history)); ?> Transaksi Terakhir</span>
                <?php endif; ?>
            </div>
            
            <div class="divide-y divide-slate-50">
                <?php if(isset($library_history) && count($library_history) > 0): ?>
                    <?php $__currentLoopData = $library_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-5 hover:bg-slate-50/80 transition-colors flex items-center gap-4 group">
                        
                        <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center shadow-sm border 
                            <?php echo e($loan->status == 'returned' ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : ($loan->status == 'overdue' ? 'bg-rose-100 text-rose-600 border-rose-200' : 'bg-blue-100 text-blue-600 border-blue-200')); ?>">
                            <?php if($loan->status == 'returned'): ?>
                                <i class="ph-bold ph-check text-xl"></i>
                            <?php elseif($loan->status == 'overdue'): ?>
                                <i class="ph-bold ph-warning text-xl"></i>
                            <?php else: ?>
                                <i class="ph-bold ph-hourglass-medium text-xl"></i>
                            <?php endif; ?>
                        </div>

                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-slate-800 text-sm truncate group-hover:text-blue-700 transition-colors" title="<?php echo e($loan->book->title ?? 'Judul Tidak Diketahui'); ?>">
                                <?php echo e($loan->book->title ?? 'Buku Dihapus'); ?>

                            </h4>
                            
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <i class="ph-bold ph-calendar-blank text-slate-400"></i>
                                    Pinjam: <?php echo e(\Carbon\Carbon::parse($loan->borrow_date)->format('d M Y')); ?>

                                </span>
                                
                                <?php if($loan->status != 'returned'): ?>
                                    <span class="text-xs <?php echo e(\Carbon\Carbon::now() > $loan->due_date ? 'text-rose-500' : 'text-amber-600'); ?> font-bold flex items-center gap-1">
                                        <i class="ph-bold ph-clock-countdown"></i>
                                        Tenggat: <?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-emerald-600 font-bold flex items-center gap-1">
                                        <i class="ph-bold ph-calendar-check"></i>
                                        Kembali: <?php echo e(\Carbon\Carbon::parse($loan->return_date)->format('d M Y')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="hidden sm:block">
                            <?php if($loan->status == 'returned'): ?>
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-emerald-100">Selesai</span>
                            <?php elseif($loan->status == 'overdue'): ?>
                                <span class="px-3 py-1 bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-rose-100 animate-pulse">Terlambat</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-blue-100">Dipinjam</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="p-12 text-center">
                        <div class="inline-flex p-4 bg-slate-50 rounded-full text-slate-300 mb-3 border border-slate-100">
                            <i class="ph-duotone ph-receipt text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm">Belum ada riwayat peminjaman</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Kunjungi perpustakaan fisik sekolah untuk meminjam buku dan riwayat akan muncul di sini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-perpustakaan.blade.php ENDPATH**/ ?>