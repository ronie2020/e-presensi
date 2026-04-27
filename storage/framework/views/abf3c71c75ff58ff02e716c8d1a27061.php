<!-- LIBRARY SECTION -->
    <div class="py-24 bg-slate-50/50 dark:bg-slate-900 border-y border-slate-100/50 dark:border-slate-800 relative overflow-hidden transition-colors duration-300">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30 dark:opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <!-- Badge -->
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-elevate-accent/10 dark:bg-elevate-accent/20 text-elevate-primary dark:text-elevate-accent text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-6 border border-elevate-accent/20 dark:border-elevate-accent/30">
                        <i class="ph-fill ph-books mr-2 shrink-0"></i> <span class="truncate">Pusat Literasi</span>
                    </span>
                    
                    <!-- Judul -->
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-6 break-words">
                        Budayakan Membaca, <br class="hidden sm:block">
                        <span class="text-elevate-primary dark:text-elevate-accent">Jelajahi Dunia</span>
                    </h2>
                    
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 mb-8 leading-relaxed break-words">
                        Perpustakaan digital kami memudahkan pemantauan aktivitas literasi siswa. Data kunjungan dan peminjaman buku tercatat secara real-time.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-3 sm:gap-6 w-full">
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-6 rounded-2xl shadow-lg shadow-elevate-primary/5 dark:shadow-none border border-slate-100 dark:border-slate-700 min-w-0 overflow-hidden">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                <div class="p-1.5 sm:p-2 bg-elevate-primary/10 dark:bg-elevate-primary/20 rounded-lg text-elevate-primary dark:text-elevate-accent shrink-0"><i class="ph-bold ph-users text-sm sm:text-base"></i></div>
                                <p class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-wide truncate w-full">Pengunjung</p>
                            </div>
                            <p class="text-2xl sm:text-4xl font-black text-slate-800 dark:text-white truncate"><?php echo e($libraryStats['visitors_today'] ?? 0); ?></p>
                            <p class="text-[10px] sm:text-xs text-elevate-primary dark:text-elevate-accent font-medium mt-1 truncate">Hari ini</p>
                        </div>
                        
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-6 rounded-2xl shadow-lg shadow-elevate-primary/5 dark:shadow-none border border-slate-100 dark:border-slate-700 min-w-0 overflow-hidden">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                <div class="p-1.5 sm:p-2 bg-elevate-accent/10 dark:bg-elevate-accent/20 rounded-lg text-elevate-primary dark:text-elevate-accent shrink-0"><i class="ph-bold ph-book-bookmark text-sm sm:text-base"></i></div>
                                <p class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-wide truncate w-full">Dipinjam</p>
                            </div>
                            <p class="text-2xl sm:text-4xl font-black text-slate-800 dark:text-white truncate"><?php echo e($libraryStats['books_borrowed'] ?? 0); ?></p>
                            <p class="text-[10px] sm:text-xs text-elevate-primary dark:text-elevate-accent font-medium mt-1 truncate">Buku Aktif</p>
                        </div>
                    </div>
                </div>
                
                <div class="w-full lg:w-7/12 mt-6 lg:mt-0" data-aos="fade-left">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-4 sm:p-6 md:p-8 border border-slate-100 dark:border-slate-700 min-w-0 w-full overflow-hidden">
                        <div class="flex items-center justify-between mb-4 sm:mb-6 shrink-0">
                            <h3 class="font-bold text-sm sm:text-lg text-slate-800 dark:text-white truncate">Tren Kunjungan Perpustakaan</h3>
                        </div>
                        
                        <div class="relative w-full h-48 sm:h-64 md:h-80 min-w-0">
                            <canvas id="publicLibraryChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/library.blade.php ENDPATH**/ ?>