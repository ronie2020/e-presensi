<!-- ========================================== -->
    <!-- NEW SECTION: JALUR PENDAFTARAN PPDB        -->
    <!-- ========================================== -->
    <section class="py-20 bg-slate-50 relative overflow-hidden border-b border-slate-100">
        
        <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center mb-12">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-100 text-cyan-700 text-xs font-bold uppercase tracking-wider mb-3 border border-cyan-200">
                    <i class="ph-bold ph-student mr-2"></i> Penerimaan Siswa Baru
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Pilih Jalur Pendaftaran</h2>
                <p class="text-slate-500 max-w-2xl mx-auto text-lg">
                    Kami menyediakan berbagai metode pendaftaran untuk memudahkan calon siswa dan sekolah asal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                
                <a href="<?php echo e(route('ppdb.create')); ?>" class="group relative bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 hover:shadow-cyan-900/10 transition-all duration-300 h-full flex flex-col">
                    <div class="w-16 h-16 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-cyan-600 group-hover:text-white transition-all duration-300">
                        <i class="ph-duotone ph-student"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-cyan-600 transition-colors">Daftar Mandiri</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6 flex-1">
                        Untuk siswa atau orang tua yang ingin mengisi formulir pendaftaran secara langsung melalui website.
                    </p>
                    <div class="flex items-center text-cyan-600 font-bold text-sm mt-auto">
                        Isi Formulir <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                
                <a href="<?php echo e(route('ppdb.collective')); ?>" class="group relative bg-gradient-to-br from-cyan-700 to-blue-900 rounded-[2rem] p-8 shadow-xl shadow-cyan-900/30 border border-blue-800 hover:-translate-y-2 hover:shadow-cyan-900/40 transition-all duration-300 transform md:scale-105 md:-mt-4 ring-4 ring-cyan-500/20 h-full flex flex-col">
                    <div class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-wider">
                        Khusus Guru
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-white/10 text-white flex items-center justify-center text-3xl mb-6 backdrop-blur-sm group-hover:scale-110 transition-all duration-300">
                        <i class="ph-duotone ph-microsoft-excel-logo"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">Kolektif Guru SD/MI</h3>
                    <p class="text-sm text-blue-100/80 font-medium leading-relaxed mb-6 flex-1">
                        Fitur khusus bagi Guru Sekolah Dasar untuk mendaftarkan siswanya secara massal menggunakan upload Excel.
                    </p>
                    <div class="flex items-center text-white font-bold text-sm mt-auto">
                        Upload Data <i class="ph-bold ph-upload-simple ml-2 group-hover:-translate-y-1 transition-transform"></i>
                    </div>
                </a>

                
                <a href="<?php echo e(route('ppdb.check')); ?>" class="group relative bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 hover:shadow-blue-900/10 transition-all duration-300 h-full flex flex-col">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <i class="ph-duotone ph-magnifying-glass"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">Cek Status</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6 flex-1">
                        Pantau status verifikasi berkas pendaftaran dan lihat pengumuman hasil seleksi PPDB secara real-time.
                    </p>
                    <div class="flex items-center text-blue-600 font-bold text-sm mt-auto">
                        Cek Sekarang <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

            </div>
        </div>
    </section><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/ppdb.blade.php ENDPATH**/ ?>