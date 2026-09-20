<!-- LIBRARY SECTION -->
<div class="py-24 relative overflow-hidden transition-colors duration-300">
    <!-- Ambient Backgrounds -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-elevate-primary/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            <div class="w-full lg:w-5/12" data-aos="fade-right">
                <!-- Badge -->
                <span class="inline-flex items-center py-1.5 px-3.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-6 border border-elevate-accent/30 shadow-sm">
                    <i class="ph-fill ph-books mr-2 shrink-0"></i> <span class="truncate">Pusat Literasi</span>
                </span>
                
                <!-- Judul -->
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-6 break-words">
                    Budayakan Membaca, <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-blue-300">Jelajahi Dunia</span>
                </h2>
                
                <p class="text-sm sm:text-base text-slate-300 mb-8 leading-relaxed break-words font-medium">
                    Perpustakaan digital kami memudahkan pemantauan aktivitas literasi siswa. Data kunjungan dan peminjaman buku tercatat secara real-time.
                </p>
                
                <div class="grid grid-cols-2 gap-3 sm:gap-6 w-full">
                    <div class="bg-white/5 backdrop-blur-xl p-3 sm:p-6 rounded-2xl shadow-[0_8px_32px_rgba(0,0,0,0.3)] border border-white/10 hover:border-elevate-accent/30 hover:bg-white/10 transition-all min-w-0 overflow-hidden">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                            <div class="p-1.5 sm:p-2 bg-elevate-accent/20 border border-elevate-accent/30 rounded-lg text-elevate-accent shrink-0"><i class="ph-bold ph-users text-sm sm:text-base"></i></div>
                            <p class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-wide truncate w-full">Pengunjung</p>
                        </div>
                        <p class="text-2xl sm:text-4xl font-black text-white truncate">{{ $libraryStats['visitors_today'] ?? 0 }}</p>
                        <p class="text-[10px] sm:text-xs text-elevate-accent font-medium mt-1 truncate">Hari ini</p>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-xl p-3 sm:p-6 rounded-2xl shadow-[0_8px_32px_rgba(0,0,0,0.3)] border border-white/10 hover:border-elevate-peach/30 hover:bg-white/10 transition-all min-w-0 overflow-hidden">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                            <div class="p-1.5 sm:p-2 bg-elevate-peach/20 border border-elevate-peach/30 rounded-lg text-elevate-peach shrink-0"><i class="ph-bold ph-book-bookmark text-sm sm:text-base"></i></div>
                            <p class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-wide truncate w-full">Dipinjam</p>
                        </div>
                        <p class="text-2xl sm:text-4xl font-black text-white truncate">{{ $libraryStats['books_borrowed'] ?? 0 }}</p>
                        <p class="text-[10px] sm:text-xs text-elevate-peach font-medium mt-1 truncate">Buku Aktif</p>
                    </div>
                </div>
            </div>
            
            <div class="w-full lg:w-7/12 mt-6 lg:mt-0" data-aos="fade-left">
                <div class="bg-white/5 backdrop-blur-xl rounded-3xl shadow-[0_8px_32px_rgba(0,0,0,0.3)] p-4 sm:p-6 md:p-8 border border-white/10 min-w-0 w-full overflow-hidden">
                    <div class="flex items-center justify-between mb-4 sm:mb-6 shrink-0">
                        <h3 class="font-bold text-sm sm:text-lg text-white truncate">Tren Kunjungan Perpustakaan</h3>
                    </div>
                    
                    <div class="relative w-full h-48 sm:h-64 md:h-80 min-w-0">
                        <canvas id="publicLibraryChart" class="absolute inset-0 w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>