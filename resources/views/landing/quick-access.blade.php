<!-- QUICK ACCESS MENU -->
    <div class="bg-slate-50 dark:bg-slate-900/50 py-20 lg:py-24 relative z-20 overflow-hidden border-y border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <!-- Pattern Overlay (Mesh Halus) -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] dark:opacity-10 mix-blend-darken dark:mix-blend-overlay pointer-events-none"></div>

        <div class="absolute inset-0 pointer-events-none">            
            <div class="absolute top-10 left-10 w-96 h-96 bg-elevate-accent/40 dark:bg-elevate-accent/20 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[100px] opacity-30 animate-blob"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-elevate-primary/30 dark:bg-elevate-primary/20 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[100px] opacity-30 animate-blob" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-accent/10 dark:bg-elevate-accent/20 text-elevate-primary dark:text-elevate-accent text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-4 border border-elevate-accent/20 shadow-sm">
                    <i class="ph-bold ph-lightning"></i> Akses Instan
                </span>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white sm:text-4xl tracking-tight">
                    Akses Cepat <br class="block sm:hidden">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-elevate-primary dark:from-elevate-accent dark:to-elevate-primary">Layanan</span>
                </h2>
                <p class="mt-3 sm:mt-4 text-sm sm:text-base md:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto font-medium">Menu layanan digital terintegrasi untuk seluruh civitas akademika.</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6">
                @php
                    $menus = [
                        ['icon' => 'ph-student', 'color' => 'blue', 'title' => 'PPDB Online', 'desc' => 'Pendaftaran Siswa Baru', 'link' => route('ppdb.create')],
                        ['icon' => 'ph-graduation-cap', 'color' => 'purple', 'title' => 'Cek Kelulusan', 'desc' => 'Pengumuman Kelas IX', 'link' => route('graduation.index')],
                        ['icon' => 'ph-desktop', 'color' => 'indigo', 'title' => 'Portal Siswa', 'desc' => 'Dashboard Akademik', 'link' => route('portal.index')],
                        ['icon' => 'ph-megaphone', 'color' => 'rose', 'title' => 'Pengaduan', 'desc' => 'Layanan Suara Siswa', 'link' => route('student.complaints.index')],
                        ['icon' => 'ph-chalkboard-simple', 'color' => 'teal', 'title' => 'E-Learning', 'desc' => 'LMS & Tugas Online', 'link' => route('student.login')],
                        ['icon' => 'ph-monitor-play', 'color' => 'amber', 'title' => 'Ujian CBT', 'desc' => 'Portal Ujian Online', 'link' => route('student.login')],
                        ['icon' => 'ph-qr-code', 'color' => 'emerald', 'title' => 'Mesin Absensi', 'desc' => 'Mode Kiosk Sekolah', 'link' => route('kiosk.show')],
                        ['icon' => 'ph-books', 'color' => 'purple', 'title' => 'E-Library', 'desc' => 'Perpustakaan Digital', 'link' => route('library.kiosk.index')],
                        ['icon' => 'ph-chalkboard-teacher', 'color' => 'slate', 'title' => 'Login Staff', 'desc' => 'Admin & Guru', 'link' => route('login')],
                        ['icon' => 'ph-presentation-chart', 'color' => 'cyan', 'title' => 'Jurnal Mengajar', 'desc' => 'Pembelajaran Guru', 'link' => route('teaching.index')],                       
                    ];  
                @endphp

                @foreach($menus as $menu)
                    <!-- hover:border dirubah ke Elevate Theme agar seragam walau iconnya berwarna warni -->
                    <a href="{{ $menu['link'] }}" class="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-4 sm:p-6 rounded-[1.5rem] sm:rounded-[2rem] border border-slate-100 dark:border-slate-700 hover:border-elevate-accent/50 hover:shadow-xl hover:shadow-elevate-accent/10 dark:hover:shadow-elevate-accent/20 hover:-translate-y-1.5 transition-all duration-300 group text-center md:text-left flex flex-col items-center md:items-start overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-elevate-accent/10 dark:to-elevate-accent/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>

                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-[1rem] sm:rounded-2xl bg-{{ $menu['color'] }}-50 dark:bg-slate-700 text-{{ $menu['color'] }}-600 dark:text-{{ $menu['color'] }}-400 flex items-center justify-center text-2xl sm:text-3xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-sm border border-{{ $menu['color'] }}-100/50 dark:border-slate-600">
                            <i class="ph-duotone {{ $menu['icon'] }}"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-xs sm:text-sm md:text-base group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors leading-tight">{{ $menu['title'] }}</h3>
                        <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 sm:mt-1.5 leading-relaxed">{{ $menu['desc'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>