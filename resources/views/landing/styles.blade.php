<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

<!-- Styles & Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<!-- Animation Library (AOS) -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Twitter Card (tag og:* sudah ada di <head> welcome.blade.php — tidak diulang di sini agar tidak dobel/tabrakan saat link dibagikan) -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="SMP Negeri 3 Lakbok">
<meta property="twitter:description" content="Platform digital resmi SMPN 3 Lakbok.">
<meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }

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

    /* Preloader */
    #preloader { position: fixed; inset: 0; z-index: 9999; background: #021124; display: flex; justify-content: center; align-items: center; transition: opacity 0.5s ease-out, visibility 0.5s ease-out; }
    
    /* Preloader Spinner dengan Elevate Accent (#56bbf1) */
    .loader { width: 48px; height: 48px; border: 4px solid rgba(255, 255, 255, 0.1); border-bottom-color: #56bbf1; border-radius: 50%; display: inline-block; box-sizing: border-box; animation: rotation 1s linear infinite; }
    @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .hide-preloader { opacity: 0; visibility: hidden; }

    /* Concept Mockup Portal & Floating Badge Animations */
    @keyframes portalPulse {
        0%, 100% {
            box-shadow: 0 0 35px rgba(86, 187, 241, 0.4), 0 0 70px rgba(13, 82, 161, 0.3);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 55px rgba(86, 187, 241, 0.7), 0 0 95px rgba(13, 82, 161, 0.45);
            transform: scale(1.02);
        }
    }
    .animate-portal-pulse {
        animation: portalPulse 4s ease-in-out infinite;
    }

    @keyframes floatSlow {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
    }
    .animate-float-badge {
        animation: floatSlow 4s ease-in-out infinite;
    }

    @keyframes orbitSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-orbit-ring {
        animation: orbitSlow 45s linear infinite;
    }
</style>