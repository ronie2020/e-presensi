<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

<!-- Styles & Scripts (Vite) -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!--
    Library CDN: versi DIKUNCI (bukan "latest") dan memakai `defer` supaya tidak memblokir render.
    Script defer selalu selesai dijalankan SEBELUM event DOMContentLoaded,
    jadi semua inisialisasi (AOS, Chart) di scripts.blade.php dibungkus DOMContentLoaded.
-->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://unpkg.com" crossorigin>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.js"></script>
<script defer src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
<script defer src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Twitter Card: cukup jenis kartunya saja. title/description/image otomatis mengikuti tag og:* di welcome.blade.php -->
<meta name="twitter:card" content="summary_large_image">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Anchor (#katalog-lms dst.) tidak tertutup navbar yang fixed */
    html { scroll-padding-top: 6rem; }

    /* Fokus keyboard yang selalu terlihat (banyak tombol memakai focus:outline-none) */
    :focus-visible { outline: 2px solid #56bbf1; outline-offset: 2px; }

    /* Scrollbar untuk Firefox (menggunakan elevate-accent #56bbf1) */
    * { scrollbar-width: thin; scrollbar-color: #56bbf1 #021124; }

    /* Custom Scrollbar Menggunakan Warna Elevate (tailwind.config.js) */
    ::-webkit-scrollbar { width: 10px; }
    ::-webkit-scrollbar-track { background: #021124; }
    ::-webkit-scrollbar-thumb { background: #56bbf1; border-radius: 5px; border: 2px solid #021124; }
    ::-webkit-scrollbar-thumb:hover { background: #0d52a1; }

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
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .glass-dark {
        background: rgba(44, 63, 97, 0.7); /* elevate-dark #2c3f61 */
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(86, 187, 241, 0.15); /* elevate-accent glow */
    }

    /* Book Cover 3D Effect */
    .book-card { perspective: 1000px; }
    .book-inner { transition: transform 0.5s; transform-style: preserve-3d; }
    .book-card:hover .book-inner { transform: rotateY(-10deg) scale(1.05); }
    .book-glass { background: rgba(255,255,255,0.05); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); }

    /* HP: kurangi blur agar scroll tetap mulus di perangkat menengah ke bawah */
    @media (max-width: 767px) {
        .glass, .glass-dark { backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); }
        .book-glass { backdrop-filter: blur(4px); }
    }

    /* Preloader */
    #preloader { position: fixed; inset: 0; z-index: 9999; background: #021124; display: flex; justify-content: center; align-items: center; transition: opacity 0.4s ease-out, visibility 0.4s ease-out; }
    .loader { width: 48px; height: 48px; border: 4px solid rgba(255, 255, 255, 0.1); border-bottom-color: #56bbf1; border-radius: 50%; display: inline-block; box-sizing: border-box; animation: rotation 1s linear infinite; }
    @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .hide-preloader { opacity: 0; visibility: hidden; pointer-events: none; }

    /* Cahaya lingkaran portal hero (GPU composite-only) */
    @keyframes portalGlow {
        0%, 100% { opacity: 0.55; transform: scale(1); }
        50%      { opacity: 0.95; transform: scale(1.05); }
    }
    .animate-portal-glow { animation: portalGlow 4s ease-in-out infinite; }

    @keyframes floatSlow {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }
    .animate-float-badge { animation: floatSlow 4s ease-in-out infinite; }

    @keyframes orbitSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-orbit-ring { animation: orbitSlow 45s linear infinite; }

    /* Hormati pengaturan "kurangi gerakan" milik pengunjung */
    @media (prefers-reduced-motion: reduce) {
        html { scroll-behavior: auto !important; }
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>