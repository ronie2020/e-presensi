<!DOCTYPE html>
<!-- Kunci lebar maksimal tepat pada 100% viewport (layar) -->
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth overflow-x-hidden w-full max-w-[100vw]">
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
        /* Style Preloader Elevate */
        #preloader {
            position: fixed; inset: 0; z-index: 9999;
            background: #2c3f61; /* elevate-dark */
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        #preloader.hide-preloader { opacity: 0; visibility: hidden; }
        .loader {
            width: 48px; height: 48px;
            border: 5px solid #e5eff5; /* elevate-soft */
            border-bottom-color: #56bbf1; /* elevate-accent */
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }
        @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <?php
        // Logika Pop-up Dinamis
        $hasPopup = isset($popupAnnouncement) && !empty($popupAnnouncement);

        if ($hasPopup) {
            $popupId = 'pengumuman_' . $popupAnnouncement->id;
            $popupImage = !empty($popupAnnouncement->image) ? asset('storage/' . $popupAnnouncement->image) : asset('images/logo-sekolah.png');
            $popupTitle = $popupAnnouncement->title;
            $popupMessage = Str::limit(strip_tags($popupAnnouncement->content), 200);
        }

        // Tema warna default disesuaikan dengan Microsoft Elevate
        $colorTheme = [
            'badge_bg' => 'bg-elevate-accent/10', 
            'badge_text' => 'text-elevate-primary', 
            'badge_ring' => 'ring-elevate-accent/30', 
            'btn_bg' => 'bg-elevate-primary', 
            'btn_hover' => 'hover:bg-elevate-dark', 
            'btn_ring' => 'focus-visible:outline-elevate-primary'
        ];
    ?>
</head>

<!-- TEMA ELEVATE: Background terang (slate-50), text navy (elevate-dark), tanpa dark mode class -->
<body class="antialiased text-elevate-dark bg-slate-50 overflow-x-hidden w-full selection:bg-elevate-accent selection:text-white" 
    x-data="{ 
        mobileMenuOpen: false,
        modalOpen: false, 
        guestBookModalOpen: false,
        guestListModalOpen: false,
        infoPopupOpen: false,
        
        initPopup() {
            <?php if($hasPopup): ?>
                const popupId = '<?php echo e($popupId); ?>';
                const hasSeen = localStorage.getItem('seen_' + popupId);
                
                if (!hasSeen) {
                    setTimeout(() => {
                        this.infoPopupOpen = true;
                        document.body.style.overflow = 'hidden'; 
                    }, 1000);
                }
            <?php endif; ?>
        },

        closeInfoPopup(dontShowAgain) {
            this.infoPopupOpen = false;
            document.body.style.overflow = ''; 
            
            if (dontShowAgain) {
                <?php if($hasPopup): ?>
                    localStorage.setItem('seen_<?php echo e($popupId); ?>', 'true');
                <?php endif; ?>
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
            document.body.style.overflow = '';
        },

        init() {
            window.addEventListener('load', () => {
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

    <!-- PRELOADER (Elevate Navy) -->
    <div id="preloader">
        <div class="flex flex-col items-center gap-4">
            <span class="loader"></span>
            <p class="text-white text-xs font-bold tracking-widest uppercase animate-pulse">Memuat Netila Berjaya...</p>
        </div>
    </div>

    <!-- INFO POPUP MODAL (Elevate Style) -->
    <?php if($hasPopup): ?>
    <div x-cloak x-show="infoPopupOpen" @keydown.escape.window="if(infoPopupOpen) closeInfoPopup(false)" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div x-show="infoPopupOpen" x-transition.opacity class="fixed inset-0 bg-elevate-dark/70 backdrop-blur-sm transition-opacity" @click="closeInfoPopup(false)"></div>
        <div class="flex min-h-full p-4 sm:p-6">
            <div x-show="infoPopupOpen" x-transition class="m-auto relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full sm:max-w-2xl border border-slate-100">
                <button @click="closeInfoPopup(false)" class="absolute top-4 right-4 z-20 w-10 h-10 bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm rounded-full flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
                <div class="flex flex-col md:flex-row w-full">
                    <div class="md:w-5/12 h-48 sm:h-56 md:h-auto shrink-0 relative bg-slate-100 p-6 flex items-center justify-center">
                        <img src="<?php echo e($popupImage); ?>" alt="Info Sekolah" class="w-full h-full object-contain drop-shadow-lg">
                    </div>
                    <div class="md:w-7/12 p-6 md:p-8 flex flex-col justify-center bg-white relative">
                        <div class="mb-4">
                            <span class="inline-flex items-center rounded-lg <?php echo e($colorTheme['badge_bg']); ?> px-3 py-1.5 text-[10px] font-black uppercase tracking-widest <?php echo e($colorTheme['badge_text']); ?> ring-1 ring-inset <?php echo e($colorTheme['badge_ring']); ?> mb-3"><i class="ph-fill ph-megaphone mr-1.5"></i> Pengumuman</span>
                            <h3 id="modal-title" class="text-2xl font-black text-elevate-dark leading-tight"><?php echo e($popupTitle); ?></h3>
                        </div>
                        <div class="prose prose-sm text-slate-500 mb-6 font-medium leading-relaxed"><p><?php echo e($popupMessage); ?></p></div>
                        <div class="flex flex-col gap-3 mt-auto">
                            <button @click="closeInfoPopup(false)" class="w-full text-center justify-center items-center rounded-xl <?php echo e($colorTheme['btn_bg']); ?> px-5 py-3.5 text-xs font-black text-white shadow-lg shadow-elevate-primary/20 <?php echo e($colorTheme['btn_hover']); ?> transition-all">SAYA MENGERTI</button>
                            <button @click="closeInfoPopup(true)" class="text-xs font-bold text-slate-400 hover:text-elevate-peach transition-colors text-center py-2">Jangan tampilkan pengumuman ini lagi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- NAVBAR -->
    <?php echo $__env->make('landing.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- KONTEN UTAMA (STRUKTUR ASLI ANDA) -->
    <div class="w-full overflow-x-hidden relative">
        <?php echo $__env->make('landing.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.ppdb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.character', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.quick-access', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.downloads', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.headmaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.video', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.teachers', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.exams', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.activities', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.articles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.achievements', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.extracurricular', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.alumni', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.guestbook', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.library', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.ebooks', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

   <!-- MODALS -->
    <?php echo $__env->make('landing.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- VISITOR COUNTER -->
    <div class="fixed bottom-4 left-4 sm:bottom-6 sm:left-6 z-40 bg-white/90 backdrop-blur-md border border-slate-200 shadow-xl p-1.5 sm:px-4 sm:py-2 rounded-full flex items-center gap-2 sm:gap-3 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 group cursor-default max-w-[calc(100vw-40px)] overflow-hidden" title="Total pengunjung website">
        <div class="bg-elevate-accent/10 text-elevate-primary p-1.5 sm:p-2 rounded-full shrink-0 group-hover:bg-elevate-primary group-hover:text-white transition-colors duration-300">
            <i class="ph-fill ph-users text-sm sm:text-lg"></i>
        </div>
        
        <!-- Teks Detail (Hanya muncul di Desktop/Tablet) -->
        <div class="hidden sm:flex flex-col truncate">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">Pengunjung</span>
            <span class="text-sm font-black text-elevate-dark leading-none"><?php echo e(number_format($visitorCount ?? 0, 0, ',', '.')); ?></span>
        </div>
        
        <!-- Angka saja (Muncul di Mobile HP) -->
        <div class="flex sm:hidden pr-2 shrink-0">
            <span class="text-xs font-black text-elevate-dark leading-none"><?php echo e(number_format($visitorCount ?? 0, 0, ',', '.')); ?></span>
        </div>
    </div>

    <!-- BACK TO TOP -->
    <button x-cloak x-show="showBackToTop" x-transition @click="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Kembali ke atas" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-40 bg-elevate-dark text-white w-12 h-12 rounded-2xl shadow-xl shadow-elevate-dark/20 flex items-center justify-center hover:bg-elevate-primary hover:-translate-y-1 transition-all duration-300 focus:outline-none border border-elevate-accent/30">
        <i class="ph-bold ph-arrow-up text-lg sm:text-xl"></i>
    </button>

    
    <?php echo $__env->make('landing.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/welcome.blade.php ENDPATH**/ ?>