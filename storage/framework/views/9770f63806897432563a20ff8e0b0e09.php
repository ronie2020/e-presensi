<!-- LIBRARY SECTION -->
    <div class="py-24 bg-blue-50/50 border-y border-blue-100/50 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
        <!-- PERBAIKAN: Tambahkan w-full agar kontainer tetap pada jalurnya -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <!-- Badge -->
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-blue-100 text-blue-700 text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-6 border border-blue-200">
                        <i class="ph-fill ph-books mr-2 shrink-0"></i> <span class="truncate">Pusat Literasi</span>
                    </span>
                    
                    <!-- Judul (PERBAIKAN: Tambahkan break-words) -->
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-6 break-words">
                        Budayakan Membaca, <br class="hidden sm:block">
                        <span class="text-blue-600">Jelajahi Dunia</span>
                    </h2>
                    
                    <p class="text-sm sm:text-base text-slate-600 mb-8 leading-relaxed break-words">
                        Perpustakaan digital kami memudahkan pemantauan aktivitas literasi siswa. Data kunjungan dan peminjaman buku tercatat secara real-time.
                    </p>
                    
                    <!-- PERBAIKAN: Menggunakan grid-cols-2 dengan padding diperkecil (p-3) dan teks disesuaikan untuk layar HP -->
                    <div class="grid grid-cols-2 gap-3 sm:gap-6 w-full">
                        <div class="bg-white p-3 sm:p-6 rounded-2xl shadow-lg shadow-blue-100 border border-blue-50 min-w-0 overflow-hidden">
                            <!-- Di HP, ikon dan teks dibuat atas-bawah. Di layar besar menyamping. -->
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                <div class="p-1.5 sm:p-2 bg-blue-100 rounded-lg text-blue-600 shrink-0"><i class="ph-bold ph-users text-sm sm:text-base"></i></div>
                                <p class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-wide truncate w-full">Pengunjung</p>
                            </div>
                            <p class="text-2xl sm:text-4xl font-black text-slate-800 truncate"><?php echo e($libraryStats['visitors_today'] ?? 0); ?></p>
                            <p class="text-[10px] sm:text-xs text-blue-600 font-medium mt-1 truncate">Hari ini</p>
                        </div>
                        
                        <div class="bg-white p-3 sm:p-6 rounded-2xl shadow-lg shadow-blue-100 border border-blue-50 min-w-0 overflow-hidden">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                <div class="p-1.5 sm:p-2 bg-cyan-100 rounded-lg text-cyan-600 shrink-0"><i class="ph-bold ph-book-bookmark text-sm sm:text-base"></i></div>
                                <p class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-wide truncate w-full">Dipinjam</p>
                            </div>
                            <p class="text-2xl sm:text-4xl font-black text-slate-800 truncate"><?php echo e($libraryStats['books_borrowed'] ?? 0); ?></p>
                            <p class="text-[10px] sm:text-xs text-cyan-600 font-medium mt-1 truncate">Buku Aktif</p>
                        </div>
                    </div>
                </div>
                
                <div class="w-full lg:w-7/12 mt-6 lg:mt-0" data-aos="fade-left">
                    <!-- PERBAIKAN: Tambahkan min-w-0 dan kurangi padding luar di HP (p-4) -->
                    <div class="bg-white rounded-3xl shadow-xl p-4 sm:p-6 md:p-8 border border-slate-100 min-w-0 w-full overflow-hidden">
                        <div class="flex items-center justify-between mb-4 sm:mb-6 shrink-0">
                            <h3 class="font-bold text-sm sm:text-lg text-slate-800 truncate">Tren Kunjungan Perpustakaan</h3>
                        </div>
                        
                        <!-- PERBAIKAN: Chart.js Canvas dikunci menggunakan layout absolute agar tidak memaksa lebar -->
                        <div class="relative w-full h-48 sm:h-64 md:h-80 min-w-0">
                            <canvas id="publicLibraryChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/library.blade.php ENDPATH**/ ?>