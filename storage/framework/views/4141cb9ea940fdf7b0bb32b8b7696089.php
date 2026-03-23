<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    
    <meta name="description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <meta property="og:title" content="<?php echo e(config('app.name', 'SMP Negeri 3 Lakbok')); ?>">
    <meta property="og:description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    
    <meta property="og:image" content="<?php echo e(asset('images/netila.jpg')); ?>"> 
    
    <title><?php echo e(config('app.name', 'SMP Negeri 3 Lakbok')); ?></title>
    
    
    <?php echo $__env->make('landing.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    
    <?php
        $infoPopup = [
            'active' => true, 
            'id' => 'Idul_Fitri_2026', 
            'image' => asset('images/netila.jpg'),
            'title' => 'Idul Fitri 1447 H 2026',
            'message' => 'SMP Negeri 3 Lakbok Mengucapkan " Selamat Hari Raya Idul Fitri 1 Syawal 1447 H 2026". Mohon Maaf lahir bathin, ',
            'cta_text' => 'info jurnal siswa',
            'cta_link' => 'https://e-presensi.smpn3lakbok.sch.id/portal', 
            'color' => 'emerald' 
        ];

        // Mapping warna agar Tailwind JIT/Purge bisa membaca class secara utuh
        $colorTheme = match($infoPopup['color']) {
            'blue' => [
                'badge_bg' => 'bg-blue-50', 'badge_text' => 'text-blue-700', 'badge_ring' => 'ring-blue-600/20',
                'btn_bg' => 'bg-blue-600', 'btn_hover' => 'hover:bg-blue-500', 'btn_ring' => 'focus-visible:outline-blue-600'
            ],
            'amber' => [
                'badge_bg' => 'bg-amber-50', 'badge_text' => 'text-amber-700', 'badge_ring' => 'ring-amber-600/20',
                'btn_bg' => 'bg-amber-600', 'btn_hover' => 'hover:bg-amber-500', 'btn_ring' => 'focus-visible:outline-amber-600'
            ],
            'rose' => [
                'badge_bg' => 'bg-rose-50', 'badge_text' => 'text-rose-700', 'badge_ring' => 'ring-rose-600/20',
                'btn_bg' => 'bg-rose-600', 'btn_hover' => 'hover:bg-rose-500', 'btn_ring' => 'focus-visible:outline-rose-600'
            ],
            default => [ 
                'badge_bg' => 'bg-emerald-50', 'badge_text' => 'text-emerald-700', 'badge_ring' => 'ring-emerald-600/20',
                'btn_bg' => 'bg-emerald-600', 'btn_hover' => 'hover:bg-emerald-500', 'btn_ring' => 'focus-visible:outline-emerald-600'
            ],
        };
    ?>
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden selection:bg-blue-500 selection:text-white" 
    x-data="{ 
        mobileMenuOpen: false,
        modalOpen: false, 
        guestBookModalOpen: false,
        guestListModalOpen: false,
        
        infoPopupOpen: false,
        
        initPopup() {
            const isActive = <?php echo e($infoPopup['active'] ? 'true' : 'false'); ?>;
            const popupId = '<?php echo e($infoPopup['id']); ?>';
            
            if (isActive) {
                const hasSeen = localStorage.getItem('seen_' + popupId);
                
                if (!hasSeen) {
                    // PERBAIKAN: setTimeout 2 detik dihapus agar pop-up langsung tampil
                    this.infoPopupOpen = true;
                    document.body.style.overflow = 'hidden'; 
                }
            }
        },

        closeInfoPopup(dontShowAgain) {
            this.infoPopupOpen = false;
            // PERBAIKAN: Gunakan string kosong ('') agar class 'overflow-x-hidden' pada body bekerja kembali
            document.body.style.overflow = ''; 
            
            if (dontShowAgain) {
                localStorage.setItem('seen_<?php echo e($infoPopup['id']); ?>', 'true');
            }
        },

        activeAnnouncement: null,
        scrolled: false,
        showBackToTop: false,
        activeSection: 'home',
        
        openAnnouncementByIndex(index) {
            if (window.announcementsData && window.announcementsData[index]) {
                this.activeAnnouncement = window.announcementsData[index];
                this.modalOpen = true;
                document.body.style.overflow = 'hidden';
            }
        },
        
        closeAnnouncement() {
            this.modalOpen = false;
            setTimeout(() => { this.activeAnnouncement = null }, 300);
            // PERBAIKAN: Gunakan string kosong ('') juga di sini
            document.body.style.overflow = '';
        },

        init() {
            window.addEventListener('load', () => {
                // PENGEMBANGAN: Preloader dihapus langsung tanpa paksaan menunggu 800ms
                // Jika koneksi internet cepat, pengunjung tidak perlu menunggu lama
                const preloader = document.getElementById('preloader');
                if(preloader) preloader.classList.add('hide-preloader');
                
                this.initPopup();
            });
        }
    }" 
    @scroll.window="
        scrolled = (window.pageYOffset > 20) ? true : false;
        showBackToTop = (window.pageYOffset > 500) ? true : false;
        const sections = ['home', 'profil', 'kegiatan', 'prestasi', 'kontak'];
        for (const section of sections) {
            const el = document.getElementById(section);
            if (el && window.scrollY >= (el.offsetTop - 150)) {
                activeSection = section;
            }
        }
    ">

    <!-- PRELOADER -->
    <div id="preloader">
        <div class="flex flex-col items-center gap-4">
            <span class="loader"></span>
            <p class="text-white text-xs font-bold tracking-widest uppercase animate-pulse">Memuat Netila Berjaya...</p>
        </div>
    </div>

    <!-- 
        === INFO POPUP MODAL (DYNAMIC) === 
        PENGEMBANGAN: Ditambahkan x-cloak dan @keydown.escape.window
    -->
    <div x-cloak 
         x-show="infoPopupOpen" 
         @keydown.escape.window="if(infoPopupOpen) closeInfoPopup(false)"
         class="fixed inset-0 z-[100] overflow-y-auto" 
         role="dialog" aria-modal="true" aria-labelledby="modal-title">
        
        
        <div x-show="infoPopupOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" 
             @click="closeInfoPopup(false)"></div>

        
        <div class="flex min-h-full p-4 sm:p-6">

            
            <!-- SOLUSI: Menggunakan class 'm-auto' untuk memusatkan secara otomatis. 
                 Jika konten lebih panjang dari layar, scroll luar akan bekerja secara alami 
                 tanpa memotong bagian atas atau bawah pop-up. -->
            <div x-show="infoPopupOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="m-auto relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full sm:max-w-2xl border border-white/20">
                
                <!-- Tombol Close -->
                <button @click="closeInfoPopup(false)" class="absolute top-3 right-3 z-20 text-slate-500 hover:text-slate-800 transition-colors bg-white/90 backdrop-blur shadow-sm rounded-full p-1.5" aria-label="Tutup Pop-up">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>

                <!-- Container Utama Pop-up -->
                <div class="flex flex-col md:flex-row w-full">
                    
                    <div class="md:w-5/12 h-48 sm:h-56 md:h-auto shrink-0 relative bg-slate-200">
                        <img src="<?php echo e($infoPopup['image']); ?>" alt="Info Sekolah" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent md:bg-gradient-to-r md:from-transparent md:to-black/10"></div>
                    </div>

                    
                    <!-- Diberikan padding yang lega agar konten tidak bertabrakan -->
                    <div class="md:w-7/12 p-6 md:p-8 flex flex-col justify-center bg-white relative">
                        <div class="mb-4">
                            <span class="inline-flex items-center rounded-md <?php echo e($colorTheme['badge_bg']); ?> px-2 py-1 text-xs font-medium <?php echo e($colorTheme['badge_text']); ?> ring-1 ring-inset <?php echo e($colorTheme['badge_ring']); ?> mb-3">
                                Informasi Terbaru
                            </span>
                            <h3 id="modal-title" class="text-xl font-black text-slate-900 leading-tight">
                                <?php echo e($infoPopup['title']); ?>

                            </h3>
                        </div>
                        
                        <div class="prose prose-sm text-slate-500 mb-6 leading-relaxed">
                            <p><?php echo e($infoPopup['message']); ?></p>
                        </div>

                        <!-- mt-auto dihapus, diganti mt-6 agar alur padding natural -->
                        <div class="flex flex-col sm:flex-row gap-3 items-center mt-6">
                            <a href="<?php echo e($infoPopup['cta_link']); ?>" @click="closeInfoPopup(false)" class="w-full sm:w-auto text-center inline-flex justify-center items-center gap-2 rounded-xl <?php echo e($colorTheme['btn_bg']); ?> px-5 py-2.5 text-sm font-semibold text-white shadow-sm <?php echo e($colorTheme['btn_hover']); ?> focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 <?php echo e($colorTheme['btn_ring']); ?> transition-all">
                                <?php echo e($infoPopup['cta_text']); ?>

                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                            
                            <button @click="closeInfoPopup(true)" class="text-xs font-semibold text-slate-400 hover:text-slate-600 underline decoration-slate-300 underline-offset-4 transition-colors">
                                Jangan tampilkan lagi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- NAVBAR -->
    <?php echo $__env->make('landing.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- SOLUSI: Bungkus semua konten dengan div anti-bocor (overflow-x-hidden) -->
    <div class="w-full overflow-x-hidden relative">

        <!-- HERO SECTION -->
        <?php echo $__env->make('landing.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- PPDB TRACKS -->
        <?php echo $__env->make('landing.ppdb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- 7 HABITS (KARAKTER) -->
        <?php echo $__env->make('landing.character', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- QUICK ACCESS MENU -->
        <?php echo $__env->make('landing.quick-access', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- DOWNLOAD AREA -->
        <?php echo $__env->make('landing.downloads', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- KEPALA SEKOLAH -->
        <?php echo $__env->make('landing.headmaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- PROFIL SEKOLAH -->
        <?php echo $__env->make('landing.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- VIDEO PROFIL -->
        <?php echo $__env->make('landing.video', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- GURU & STAFF -->
        <?php echo $__env->make('landing.teachers', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- KEGIATAN -->
        <?php echo $__env->make('landing.activities', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- ARTIKEL --> 
        <?php echo $__env->make('landing.articles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- PRESTASI -->
        <?php echo $__env->make('landing.achievements', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- EKSTRAKURIKULER -->
        <?php echo $__env->make('landing.extracurricular', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- ALUMNI -->
        <?php echo $__env->make('landing.alumni', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- KATA MEREKA / GUESTBOOK -->
        <?php echo $__env->make('landing.guestbook', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- LIBRARY -->
        <?php echo $__env->make('landing.library', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- E-BOOKS -->
        <?php echo $__env->make('landing.ebooks', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- ANNOUNCEMENTS, AGENDA & FOOTER -->
        <?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
    <!-- Penutup Solusi -->

    <!-- MODALS -->
    <?php echo $__env->make('landing.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- BACK TO TOP -->
    
    <button 
        x-cloak
        x-show="showBackToTop" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        aria-label="Kembali ke atas"
        class="fixed bottom-6 right-6 z-40 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 focus:outline-none"
    >
        <i class="ph-bold ph-arrow-up text-xl"></i>
    </button>

    
    <?php echo $__env->make('landing.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/welcome.blade.php ENDPATH**/ ?>