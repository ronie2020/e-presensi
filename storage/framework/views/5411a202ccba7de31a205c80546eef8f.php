<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

<!-- Styles & Scripts -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<!-- Animation Library (AOS) -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Open Graph / SEO -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo e(url('/')); ?>">
<meta property="og:title" content="SMP Negeri 3 Lakbok - Berjaya, Jujur, Berkarakter">
<meta property="og:description" content="Platform digital resmi SMPN 3 Lakbok. Informasi akademik, PPDB, dan prestasi siswa terkini.">
<meta property="og:image" content="<?php echo e(asset('images/og-image.jpg')); ?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo e(url('/')); ?>">
<meta property="twitter:title" content="SMP Negeri 3 Lakbok">
<meta property="twitter:description" content="Platform digital resmi SMPN 3 Lakbok.">
<meta property="twitter:image" content="<?php echo e(asset('images/og-image.jpg')); ?>">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    /* Custom Scrollbar Diselaraskan Ke Tema Biru/Cyan */
    ::-webkit-scrollbar { width: 10px; }
    ::-webkit-scrollbar-track { background: #f8fafc; }
    ::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 5px; border: 2px solid #f8fafc; }
    ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
    
    /* Utility Animations */
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
    
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }

    /* Glassmorphism Utilities */
    .glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    .glass-dark {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    /* Book Cover 3D Effect */
    .book-card { perspective: 1000px; }
    .book-inner { transition: transform 0.5s; transform-style: preserve-3d; }
    .book-card:hover .book-inner { transform: rotateY(-10deg) scale(1.05); }
    .book-glass { background: rgba(255,255,255,0.05); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.1); }

    /* Preloader */
    #preloader { position: fixed; inset: 0; z-index: 9999; background: #0f172a; display: flex; justify-content: center; align-items: center; transition: opacity 0.5s ease-out, visibility 0.5s ease-out; }
    .loader { width: 48px; height: 48px; border: 5px solid #FFF; border-bottom-color: #0ea5e9; border-radius: 50%; display: inline-block; box-sizing: border-box; animation: rotation 1s linear infinite; }
    @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .hide-preloader { opacity: 0; visibility: hidden; }
</style><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/styles.blade.php ENDPATH**/ ?>