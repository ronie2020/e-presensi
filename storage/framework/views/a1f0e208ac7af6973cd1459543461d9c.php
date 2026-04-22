<!-- QUICK ACCESS MENU -->
    <div class="bg-slate-50 dark:bg-slate-900/50 py-20 lg:py-24 relative z-20 overflow-hidden border-y border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <!-- Pattern Overlay (Mesh Halus) -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] dark:opacity-10 mix-blend-darken dark:mix-blend-overlay pointer-events-none"></div>

        <div class="absolute inset-0 pointer-events-none">
            <!-- Blobs diubah ke warna Cyan dan Blue agar senada dengan Hero -->
            <div class="absolute top-10 left-10 w-96 h-96 bg-cyan-300 dark:bg-cyan-900/50 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[100px] opacity-30 animate-blob"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-400 dark:bg-blue-900/50 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[100px] opacity-30 animate-blob" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-4 border border-cyan-100 dark:border-cyan-500/20 shadow-sm">
                    <i class="ph-bold ph-lightning"></i> Akses Instan
                </span>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white sm:text-4xl tracking-tight">
                    Akses Cepat <br class="block sm:hidden">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-400">Layanan</span>
                </h2>
                <p class="mt-3 sm:mt-4 text-sm sm:text-base md:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto font-medium">Menu layanan digital terintegrasi untuk seluruh civitas akademika.</p>
            </div>
            
            <!-- PERBAIKAN: Menggunakan lg:grid-cols-5 agar 10 menu pas menjadi 2 baris di layar besar -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6">
                <?php
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
                ?>

                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($menu['link']); ?>" class="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-4 sm:p-6 rounded-[1.5rem] sm:rounded-[2rem] border border-slate-100 dark:border-slate-700 hover:border-cyan-300 dark:hover:border-cyan-500 hover:shadow-xl hover:shadow-cyan-500/10 dark:hover:shadow-cyan-500/20 hover:-translate-y-1.5 transition-all duration-300 group text-center md:text-left flex flex-col items-center md:items-start overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                        
                        <!-- Aksen gradien muncul saat di-hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-cyan-50/50 dark:to-cyan-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>

                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-[1rem] sm:rounded-2xl bg-<?php echo e($menu['color']); ?>-50 dark:bg-slate-700 text-<?php echo e($menu['color']); ?>-600 dark:text-<?php echo e($menu['color']); ?>-400 flex items-center justify-center text-2xl sm:text-3xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-sm border border-<?php echo e($menu['color']); ?>-100/50 dark:border-slate-600">
                            <i class="ph-duotone <?php echo e($menu['icon']); ?>"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-xs sm:text-sm md:text-base group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors leading-tight"><?php echo e($menu['title']); ?></h3>
                        <!-- PERBAIKAN: Deskripsi sekarang muncul di HP dengan ukuran teks yang proporsional -->
                        <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 sm:mt-1.5 leading-relaxed"><?php echo e($menu['desc']); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/quick-access.blade.php ENDPATH**/ ?>