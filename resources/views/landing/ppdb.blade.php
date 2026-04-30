<!-- ========================================== -->
<!-- NEW SECTION: JALUR PENDAFTARAN PPDB        -->
<!-- ========================================== -->
<!-- PERBAIKAN TEMA ELEVATE (OMBAK MULUS):
     1. Menghapus 'border-t' agar tidak ada garis yang memotong gelombang.
     2. Memastikan 'bg-slate-50' identik dengan warna SVG di atasnya.
     3. Menghapus margin negatif karena gelombang sudah disediakan oleh SVG Hero.
-->
<section id="ppdb" class="relative z-10 py-20 md:py-24 bg-slate-50 dark:bg-slate-950 overflow-hidden transition-colors duration-300">
    
    {{-- Background Pattern Halus --}}
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] dark:opacity-10 pointer-events-none transition-opacity duration-300"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors duration-300">
                <i class="ph-fill ph-student text-sm"></i> Penerimaan Siswa Baru
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-elevate-dark dark:text-white leading-tight mb-4 transition-colors duration-300">Pilih Jalur Pendaftaran</h2>
            <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto text-sm md:text-lg font-medium transition-colors duration-300">
                Kami menyediakan berbagai metode pendaftaran untuk memudahkan calon siswa dan sekolah asal dalam proses administrasi digital.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            
            {{-- 1. PENDAFTARAN MANDIRI (SISWA) --}}
            <div class="group h-full" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('ppdb.create') }}" class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 hover:-translate-y-2 transition-all duration-500 h-full flex flex-col hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50 overflow-hidden">
                    {{-- Decorative Circle --}}
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-elevate-soft dark:bg-slate-800 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="w-16 h-16 rounded-2xl bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-elevate-accent flex items-center justify-center text-3xl mb-8 group-hover:bg-elevate-primary group-hover:text-white transition-all duration-300 border border-elevate-accent/20 dark:border-slate-700 shadow-sm">
                        <i class="ph-duotone ph-student"></i>
                    </div>
                    <h3 class="text-2xl font-black text-elevate-dark dark:text-white mb-3 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors leading-tight">Daftar Mandiri</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8 flex-1 transition-colors">
                        Untuk calon siswa atau orang tua yang ingin mengisi formulir pendaftaran secara langsung melalui website resmi sekolah.
                    </p>
                    <div class="flex items-center text-elevate-primary dark:text-elevate-accent font-black text-xs uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                        Isi Formulir <i class="ph-bold ph-arrow-right ml-2"></i>
                    </div>
                </a>
            </div>

            {{-- 2. PENDAFTARAN KOLEKTIF (GURU SD) - FEATURED CARD --}}
            <div class="group h-full" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('ppdb.collective') }}" class="relative bg-elevate-dark dark:bg-elevate-primary rounded-[2.5rem] p-8 shadow-2xl shadow-elevate-dark/20 dark:shadow-elevate-primary/20 border border-elevate-primary dark:border-transparent hover:-translate-y-2 transition-all duration-500 transform md:scale-105 md:-mt-4 ring-8 ring-white dark:ring-slate-950 h-full flex flex-col overflow-hidden group">
                    
                    {{-- Featured Badge --}}
                    <div class="absolute top-6 right-6 bg-elevate-peach text-elevate-dark text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg animate-bounce-subtle z-20">
                        Khusus Guru
                    </div>

                    {{-- Decorative Glow --}}
                    <div class="absolute top-0 right-0 w-48 h-48 bg-elevate-primary/50 dark:bg-elevate-accent/30 rounded-full blur-3xl -mr-20 -mt-20 group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="w-16 h-16 rounded-2xl bg-white/10 text-white flex items-center justify-center text-3xl mb-8 backdrop-blur-md group-hover:bg-white group-hover:text-elevate-primary transition-all duration-300 border border-white/20 shadow-inner relative z-10">
                        <i class="ph-duotone ph-microsoft-excel-logo"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3 relative z-10 leading-tight">Kolektif Guru SD/MI</h3>
                    <p class="text-sm text-white/80 dark:text-white/90 font-medium leading-relaxed mb-8 flex-1 relative z-10 transition-colors">
                        Fitur khusus bagi rekan-rekan Guru Sekolah Dasar untuk mendaftarkan siswanya secara massal melalui sistem upload data Excel.
                    </p>
                    <div class="flex items-center text-elevate-accent dark:text-white font-black text-xs uppercase tracking-widest mt-auto group-hover:gap-3 transition-all relative z-10">
                        Upload Data <i class="ph-bold ph-upload-simple ml-2"></i>
                    </div>
                </a>
            </div>

            {{-- 3. CEK STATUS / PENGUMUMAN --}}
            <div class="group h-full" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('ppdb.check') }}" class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 hover:-translate-y-2 transition-all duration-500 h-full flex flex-col hover:border-elevate-accent/30 dark:hover:border-elevate-accent/50 overflow-hidden">
                    {{-- Decorative Circle --}}
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-elevate-soft dark:bg-slate-800 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="w-16 h-16 rounded-2xl bg-elevate-soft dark:bg-slate-800 text-elevate-primary dark:text-elevate-accent flex items-center justify-center text-3xl mb-8 group-hover:bg-elevate-primary group-hover:text-white transition-all duration-300 border border-elevate-accent/20 dark:border-slate-700 shadow-sm">
                        <i class="ph-duotone ph-magnifying-glass"></i>
                    </div>
                    <h3 class="text-2xl font-black text-elevate-dark dark:text-white mb-3 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors leading-tight">Cek Kelulusan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8 flex-1 transition-colors">
                        Pantau status verifikasi berkas pendaftaran dan lihat pengumuman hasil seleksi PPDB secara real-time.
                    </p>
                    <div class="flex items-center text-elevate-primary dark:text-elevate-accent font-black text-xs uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                        Lihat Status <i class="ph-bold ph-arrow-right ml-2"></i>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>