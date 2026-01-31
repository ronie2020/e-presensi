<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500">
    
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-indigo-100 sticky top-24 h-fit">
            <h3 class="text-lg font-black text-slate-800 mb-1">Statistik Pustaka</h3>
            <p class="text-slate-400 text-xs mb-6">Ringkasan aktivitas literasimu.</p>
            
            <div class="space-y-3">
                 
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-read-cv-logo text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">E-Book</p>
                            <p class="text-[10px] text-slate-400">Total Dibaca</p>
                        </div>
                    </div>
                    <span class="text-xl font-black text-slate-800"><?php echo e(isset($ebookHistory) ? $ebookHistory->count() : 0); ?></span>
                </div>

                
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-book-open-text text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Peminjaman</p>
                            <p class="text-[10px] text-slate-400">Buku Fisik</p>
                        </div>
                    </div>
                    <span class="text-xl font-black text-slate-800"><?php echo e($library_visits ?? 0); ?></span>
                </div>

                
                <?php
                    $activeLoans = isset($library_history) ? $library_history->where('status', 'borrowed')->count() : 0;
                ?>
                <div class="flex items-center justify-between p-4 <?php echo e($activeLoans > 0 ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-100'); ?> rounded-2xl border transition-all hover:bg-white hover:shadow-md group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full <?php echo e($activeLoans > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-200 text-slate-500'); ?> flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-hand-holding text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold <?php echo e($activeLoans > 0 ? 'text-amber-700' : 'text-slate-500'); ?> uppercase tracking-wider">Sedang Dipinjam</p>
                            <p class="text-[10px] <?php echo e($activeLoans > 0 ? 'text-amber-600' : 'text-slate-400'); ?>">Harus dikembalikan</p>
                        </div>
                    </div>
                    <span class="text-xl font-black <?php echo e($activeLoans > 0 ? 'text-amber-600' : 'text-slate-800'); ?>"><?php echo e($activeLoans); ?></span>
                </div>
            </div>

            
            <div class="mt-6 p-5 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-[2rem] text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full blur-xl -mr-8 -mt-8"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-lightbulb text-yellow-300 text-lg"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-100">Tahukah Kamu?</span>
                    </div>
                    <p class="text-xs font-medium leading-relaxed opacity-90">
                        "Membaca 15 menit setiap hari dapat mengeksposmu pada lebih dari 1 juta kata dalam setahun!"
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="lg:col-span-2 space-y-8">

        
        <?php if(isset($ebookHistory) && $ebookHistory->count() > 0): ?>
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 sm:p-8 overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-clock-counter-clockwise text-violet-500"></i> Lanjutkan Membaca
                </h3>
            </div>
            
            <div class="flex gap-5 overflow-x-auto pb-6 custom-scrollbar snap-x">
                <?php $__currentLoopData = $ebookHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($history->book): ?>
                    <div class="flex-shrink-0 w-36 group relative snap-start">
                        <div class="aspect-[2/3] bg-slate-100 rounded-2xl overflow-hidden mb-3 relative shadow-md group-hover:shadow-xl transition-all duration-300 border border-slate-100">
                            <?php if($history->book->cover_path): ?>
                                <img src="<?php echo e(asset('storage/' . $history->book->cover_path)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                    <i class="ph-duotone ph-book-open text-3xl mb-1"></i>
                                </div>
                            <?php endif; ?>
                            
                            
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <?php if(Route::has('library.books.read')): ?>
                                    <a href="<?php echo e(route('library.books.read', ['book' => $history->book->id, 'origin' => 'portal'])); ?>" class="px-4 py-2 bg-white text-violet-700 rounded-full text-[10px] font-bold shadow-lg transform scale-90 group-hover:scale-100 transition-transform flex items-center gap-1 hover:bg-violet-50">
                                        <i class="ph-bold ph-play"></i> Lanjut
                                    </a>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-white/90 text-slate-600 rounded-full text-[10px] font-bold shadow-md cursor-default">
                                        Preview
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 text-xs line-clamp-2 leading-snug mb-1 group-hover:text-violet-600 transition-colors" title="<?php echo e($history->book->title); ?>">
                            <?php echo e($history->book->title); ?>

                        </h4>
                        <p class="text-[10px] text-slate-400 font-medium">
                            <?php echo e(\Carbon\Carbon::parse($history->created_at)->diffForHumans()); ?>

                        </p>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
        
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 sm:p-8" 
             x-data="{ search: '' }">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-books text-blue-500"></i> Pustaka Digital
                    </h3>
                    <p class="text-slate-400 text-sm font-medium mt-1">
                        Akses <?php echo e(isset($ebooks) ? $ebooks->count() : 0); ?> buku digital kapan saja.
                    </p>
                </div>
                
                
                <div class="relative w-full sm:w-72 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-magnifying-glass text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" x-model="search" placeholder="Cari judul atau penulis..." 
                        class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all placeholder:font-normal placeholder:text-slate-400">
                </div>
            </div>

            <?php if(isset($ebooks) && $ebooks->count() > 0): ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-4 gap-y-8">
                    <?php $__currentLoopData = $ebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <div class="group flex flex-col h-full"
                             x-show="'<?php echo e(strtolower($book->title)); ?>'.includes(search.toLowerCase()) || '<?php echo e(strtolower($book->author ?? '')); ?>'.includes(search.toLowerCase())"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            
                            
                            <div class="aspect-[2/3] bg-slate-100 rounded-2xl overflow-hidden mb-3 relative shadow-sm group-hover:shadow-xl group-hover:-translate-y-1 transition-all duration-300 border border-slate-100">
                                <?php if($book->cover_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" class="w-full h-full object-cover" alt="<?php echo e($book->title); ?>">
                                <?php else: ?>
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                        <i class="ph-duotone ph-book-open text-3xl mb-1"></i>
                                        <span class="text-[9px] uppercase font-black tracking-widest opacity-50">No Cover</span>
                                    </div>
                                <?php endif; ?>
                                
                                
                                <?php if($book->category): ?>
                                    <div class="absolute top-2 left-2 right-2 flex justify-end">
                                        <span class="bg-black/60 backdrop-blur-md text-white text-[9px] px-2 py-1 rounded-lg font-bold uppercase tracking-wider border border-white/10 shadow-sm truncate max-w-full">
                                            <?php echo e(Str::limit($book->category->name, 12)); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>

                                
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px]">
                                    <?php if(Route::has('library.books.read')): ?>
                                        <a href="<?php echo e(route('library.books.read', ['book' => $book->id, 'origin' => 'portal'])); ?>" class="px-5 py-2.5 bg-white text-blue-700 rounded-xl text-xs font-black shadow-xl transform scale-90 group-hover:scale-100 transition-transform flex items-center gap-2 hover:bg-blue-50">
                                            <span>BACA</span> <i class="ph-bold ph-book-open-text"></i>
                                        </a>
                                    <?php else: ?>
                                        <button type="button" class="px-4 py-2 bg-white/20 text-white rounded-xl text-xs font-bold backdrop-blur-md border border-white/30 cursor-not-allowed">
                                            Preview
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            
                            <div>
                                <h4 class="font-bold text-slate-800 text-xs line-clamp-2 leading-snug mb-1 group-hover:text-blue-600 transition-colors" title="<?php echo e($book->title); ?>">
                                    <?php echo e($book->title); ?>

                                </h4>
                                <p class="text-[10px] text-slate-400 font-medium line-clamp-1 flex items-center gap-1">
                                    <i class="ph-fill ph-pen-nib text-slate-300"></i> <?php echo e($book->author ?? 'Tanpa Pengarang'); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                
                <div x-show="search != '' && $el.previousElementSibling.querySelectorAll('div[x-show]:not([style*=\'display: none\'])').length === 0" 
                     class="text-center py-16" style="display: none;">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                        <i class="ph-duotone ph-magnifying-glass text-3xl"></i>
                    </div>
                    <p class="text-slate-600 font-bold text-sm">Buku tidak ditemukan.</p>
                    <p class="text-slate-400 text-xs">Coba kata kunci lain.</p>
                </div>

            <?php else: ?>
                
                <div class="text-center py-16 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-300">
                        <i class="ph-duotone ph-books text-4xl"></i>
                    </div>
                    <h3 class="text-slate-800 font-bold text-lg">Belum ada E-Book</h3>
                    <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto">Koleksi buku digital akan segera ditambahkan di sini.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-clock-counter-clockwise text-orange-500"></i> Riwayat Fisik
                </h3>
                <?php if(isset($library_history) && count($library_history) > 0): ?>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-bold text-slate-500 uppercase tracking-wide shadow-sm">
                        Terakhir
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="divide-y divide-slate-50">
                <?php if(isset($library_history) && count($library_history) > 0): ?>
                    <?php $__currentLoopData = $library_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-5 hover:bg-slate-50 transition-colors flex items-start sm:items-center gap-4 group">
                        
                        <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center shadow-sm border 
                            <?php echo e($loan->status == 'returned' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($loan->status == 'overdue' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-blue-50 text-blue-600 border-blue-100')); ?>">
                            <?php if($loan->status == 'returned'): ?>
                                <i class="ph-bold ph-check text-xl"></i>
                            <?php elseif($loan->status == 'overdue'): ?>
                                <i class="ph-bold ph-warning text-xl animate-pulse"></i>
                            <?php else: ?>
                                <i class="ph-bold ph-hourglass-medium text-xl"></i>
                            <?php endif; ?>
                        </div>

                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-slate-800 text-sm truncate pr-2 group-hover:text-blue-600 transition-colors" title="<?php echo e($loan->book->title ?? 'Judul Tidak Diketahui'); ?>">
                                    <?php echo e($loan->book->title ?? 'Buku Telah Dihapus'); ?>

                                </h4>
                                
                                <span class="sm:hidden text-[10px] font-black uppercase <?php echo e($loan->status == 'returned' ? 'text-emerald-500' : ($loan->status == 'overdue' ? 'text-rose-500' : 'text-blue-500')); ?>">
                                    <?php echo e($loan->status == 'returned' ? 'Selesai' : ($loan->status == 'overdue' ? 'Telat' : 'Pinjam')); ?>

                                </span>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5">
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                                    <i class="ph-bold ph-calendar-blank text-slate-300"></i>
                                    <?php echo e(\Carbon\Carbon::parse($loan->borrow_date)->format('d M Y')); ?>

                                </span>
                                
                                <span class="text-slate-300 text-[10px] hidden sm:inline">&bull;</span>

                                <?php if($loan->status != 'returned'): ?>
                                    <span class="text-xs <?php echo e(\Carbon\Carbon::now() > $loan->due_date ? 'text-rose-500 font-bold' : 'text-amber-600 font-bold'); ?> flex items-center gap-1.5">
                                        <i class="ph-bold ph-clock-countdown"></i>
                                        Tenggat: <?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-emerald-600 font-bold flex items-center gap-1.5">
                                        <i class="ph-bold ph-calendar-check"></i>
                                        Kembali: <?php echo e(\Carbon\Carbon::parse($loan->return_date)->format('d M Y')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="hidden sm:block flex-shrink-0">
                            <?php if($loan->status == 'returned'): ?>
                                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-wider rounded-xl border border-emerald-100 shadow-sm">Selesai</span>
                            <?php elseif($loan->status == 'overdue'): ?>
                                <span class="px-3 py-1.5 bg-rose-50 text-rose-700 text-[10px] font-black uppercase tracking-wider rounded-xl border border-rose-100 shadow-sm animate-pulse">Terlambat</span>
                            <?php else: ?>
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-wider rounded-xl border border-blue-100 shadow-sm">Dipinjam</span>
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
</div>


<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-perpustakaan.blade.php ENDPATH**/ ?>