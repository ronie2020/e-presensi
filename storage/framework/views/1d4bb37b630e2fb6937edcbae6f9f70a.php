 <!-- QUICK ACCESS MENU -->
    <div class="bg-slate-50 py-20 relative z-20 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-10 right-10 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Akses Cepat Layanan</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Menu layanan digital terintegrasi untuk seluruh civitas akademika.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 md:gap-6">
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
                    <a href="<?php echo e($menu['link']); ?>" class="glass bg-white/60 p-6 rounded-2xl hover:bg-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group text-center md:text-left flex flex-col items-center md:items-start" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                        <div class="w-12 h-12 rounded-xl bg-<?php echo e($menu['color']); ?>-50 text-<?php echo e($menu['color']); ?>-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-<?php echo e($menu['color']); ?>-600 group-hover:text-white transition-all shadow-sm">
                            <i class="ph-duotone <?php echo e($menu['icon']); ?>"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 group-hover:text-<?php echo e($menu['color']); ?>-600 transition-colors"><?php echo e($menu['title']); ?></h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed hidden md:block"><?php echo e($menu['desc']); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>  <?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/quick-access.blade.php ENDPATH**/ ?>