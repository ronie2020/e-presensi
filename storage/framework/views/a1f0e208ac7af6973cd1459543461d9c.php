<!-- QUICK ACCESS MENU -->
<div id="layanan" class="bg-slate-50 dark:bg-slate-950 py-24 relative z-20 overflow-hidden border-y border-slate-100 dark:border-slate-900 transition-colors duration-300">
    
    <!-- Elevate Ambient Ornaments -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] dark:opacity-10 pointer-events-none transition-opacity duration-300"></div>
    <div class="absolute top-10 left-10 w-96 h-96 bg-elevate-accent/20 dark:bg-elevate-accent/10 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300 animate-blob"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-elevate-primary/15 dark:bg-elevate-primary/10 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-overlay pointer-events-none transition-colors duration-300 animate-blob" style="animation-delay: 2s;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header Section -->
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors duration-300">
                <i class="ph-fill ph-lightning text-sm"></i> Akses Instan
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-elevate-dark dark:text-white tracking-tight transition-colors duration-300">
                Akses Cepat <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-elevate-primary dark:from-elevate-accent dark:to-white">Layanan Digital</span>
            </h2>
            <p class="mt-4 text-sm sm:text-base md:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto font-medium transition-colors duration-300">
                Menu layanan digital terintegrasi untuk seluruh civitas akademika SMPN 3 Lakbok.
            </p>
        </div>
        
        <!-- Grid Menu -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            <?php
                // Kita pertahankan logika array menu Anda namun dengan mapping warna Elevate yang lebih pas
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
                <!-- Card Elevate Style -->
                <a href="<?php echo e($menu['link']); ?>" 
                   class="relative group bg-white dark:bg-slate-900 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 hover:-translate-y-2 transition-all duration-500 flex flex-col items-center md:items-start text-center md:text-left overflow-hidden" 
                   data-aos="fade-up" 
                   data-aos-delay="<?php echo e($loop->index * 50); ?>">
                    
                    <!-- Hover Glow Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-transparent to-<?php echo e($menu['color']); ?>-500/5 dark:to-<?php echo e($menu['color']); ?>-400/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <!-- Icon Container -->
                    <div class="w-14 h-14 rounded-2xl bg-<?php echo e($menu['color']); ?>-50 dark:bg-<?php echo e($menu['color']); ?>-900/20 text-<?php echo e($menu['color']); ?>-600 dark:text-<?php echo e($menu['color']); ?>-400 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500 shadow-sm border border-<?php echo e($menu['color']); ?>-100/50 dark:border-<?php echo e($menu['color']); ?>-800/50 relative z-10">
                        <i class="ph-duotone <?php echo e($menu['icon']); ?>"></i>
                    </div>

                    <!-- Title & Description -->
                    <div class="relative z-10 flex-1">
                        <h3 class="font-black text-elevate-dark dark:text-white text-sm md:text-base group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors leading-tight mb-1.5">
                            <?php echo e($menu['title']); ?>

                        </h3>
                        <p class="text-[10px] md:text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                            <?php echo e($menu['desc']); ?>

                        </p>
                    </div>

                    <!-- Subtle Indicator (Optional) -->
                    <div class="mt-4 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-500 relative z-10 hidden md:block">
                        <i class="ph-bold ph-arrow-right text-<?php echo e($menu['color']); ?>-500"></i>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/quick-access.blade.php ENDPATH**/ ?>