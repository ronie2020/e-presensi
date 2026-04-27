<!-- ========================================== -->
    <!-- NEW SECTION: JALUR PENDAFTARAN PPDB        -->
    <!-- ========================================== -->
    <section class="py-20 bg-slate-50 dark:bg-slate-900/50 relative overflow-hidden border-b border-slate-100 dark:border-slate-800 transition-colors duration-300">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-5 dark:opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center mb-12">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-elevate-accent/10 dark:bg-elevate-accent/20 text-elevate-primary dark:text-elevate-accent text-xs font-bold uppercase tracking-wider mb-3 border border-elevate-accent/20 dark:border-elevate-accent/30">
                    <i class="ph-bold ph-student mr-2"></i> Penerimaan Siswa Baru
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4">Pilih Jalur Pendaftaran</h2>
                <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto text-lg">
                    Kami menyediakan berbagai metode pendaftaran untuk memudahkan calon siswa dan sekolah asal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- 1. PENDAFTARAN MANDIRI (SISWA) --}}
                <a href="{{ route('ppdb.create') }}" class="group relative bg-white dark:bg-slate-800 rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 hover:-translate-y-2 hover:shadow-elevate-primary/10 transition-all duration-300 h-full flex flex-col">
                    <div class="w-16 h-16 rounded-2xl bg-elevate-accent/10 dark:bg-slate-700 text-elevate-primary dark:text-elevate-accent flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-elevate-primary group-hover:text-white transition-all duration-300">
                        <i class="ph-duotone ph-student"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors">Daftar Mandiri</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-6 flex-1">
                        Untuk siswa atau orang tua yang ingin mengisi formulir pendaftaran secara langsung melalui website.
                    </p>
                    <div class="flex items-center text-elevate-primary dark:text-elevate-accent font-bold text-sm mt-auto">
                        Isi Formulir <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                {{-- 2. PENDAFTARAN KOLEKTIF (GURU SD) --}}
                <a href="{{ route('ppdb.collective') }}" class="group relative bg-gradient-to-br from-elevate-primary to-elevate-dark rounded-[2rem] p-8 shadow-xl shadow-elevate-dark/30 border border-elevate-primary dark:border-slate-700 hover:-translate-y-2 hover:shadow-elevate-dark/40 transition-all duration-300 transform md:scale-105 md:-mt-4 ring-4 ring-elevate-accent/20 h-full flex flex-col">
                    <div class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-wider">
                        Khusus Guru
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-white/10 text-white flex items-center justify-center text-3xl mb-6 backdrop-blur-sm group-hover:scale-110 transition-all duration-300">
                        <i class="ph-duotone ph-microsoft-excel-logo"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">Kolektif Guru SD/MI</h3>
                    <p class="text-sm text-slate-100/80 dark:text-slate-200/80 font-medium leading-relaxed mb-6 flex-1">
                        Fitur khusus bagi Guru Sekolah Dasar untuk mendaftarkan siswanya secara massal menggunakan upload Excel.
                    </p>
                    <div class="flex items-center text-white font-bold text-sm mt-auto">
                        Upload Data <i class="ph-bold ph-upload-simple ml-2 group-hover:-translate-y-1 transition-transform"></i>
                    </div>
                </a>

                {{-- 3. CEK STATUS / PENGUMUMAN --}}
                <a href="{{ route('ppdb.check') }}" class="group relative bg-white dark:bg-slate-800 rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 hover:-translate-y-2 hover:shadow-elevate-accent/10 transition-all duration-300 h-full flex flex-col">
                    <div class="w-16 h-16 rounded-2xl bg-elevate-primary/10 dark:bg-slate-700 text-elevate-primary dark:text-elevate-accent flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-elevate-primary group-hover:text-white transition-all duration-300">
                        <i class="ph-duotone ph-magnifying-glass"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors">Cek Status</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-6 flex-1">
                        Pantau status verifikasi berkas pendaftaran dan lihat pengumuman hasil seleksi PPDB secara real-time.
                    </p>
                    <div class="flex items-center text-elevate-primary dark:text-elevate-accent font-bold text-sm mt-auto">
                        Cek Sekarang <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

            </div>
        </div>
    </section>