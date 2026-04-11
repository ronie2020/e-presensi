 <!-- LIBRARY SECTION -->
    <div class="py-24 bg-emerald-50/50 border-y border-emerald-100/50 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-5/12" data-aos="fade-right">
                    <span class="inline-flex items-center py-1.5 px-3 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-6 border border-emerald-200">
                        <i class="ph-fill ph-books mr-2"></i> Pusat Literasi
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-6">
                        Budayakan Membaca, <br>
                        <span class="text-emerald-600">Jelajahi Dunia</span>
                    </h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Perpustakaan digital kami memudahkan pemantauan aktivitas literasi siswa. Data kunjungan dan peminjaman buku tercatat secara real-time.
                    </p>
                    
                    <div class="flex gap-4 sm:gap-6">
                        <div class="flex-1 bg-white p-6 rounded-2xl shadow-lg shadow-emerald-100 border border-emerald-50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600"><i class="ph-bold ph-users"></i></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pengunjung</p>
                            </div>
                            <p class="text-4xl font-black text-slate-800"><?php echo e($libraryStats['visitors_today'] ?? 0); ?></p>
                            <p class="text-xs text-emerald-600 font-medium mt-1">Hari ini</p>
                        </div>
                        <div class="flex-1 bg-white p-6 rounded-2xl shadow-lg shadow-emerald-100 border border-emerald-50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 rounded-lg text-blue-600"><i class="ph-bold ph-book-bookmark"></i></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Dipinjam</p>
                            </div>
                            <p class="text-4xl font-black text-slate-800"><?php echo e($libraryStats['books_borrowed'] ?? 0); ?></p>
                            <p class="text-xs text-blue-600 font-medium mt-1">Buku Aktif</p>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-7/12" data-aos="fade-left">
                    <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-lg text-slate-800">Tren Kunjungan Perpustakaan</h3>
                        </div>
                        <div class="h-64 md:h-80">
                            <canvas id="publicLibraryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/library.blade.php ENDPATH**/ ?>