<?php $__env->startSection('content'); ?>


<style>
    [x-cloak] { display: none !important; }
    
    /* Peningkatan Glass Effect: Lebih modern dengan border putih tipis (Elevate Style) */
    .glass-effect {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 10px 40px -10px rgba(2, 132, 199, 0.15); /* Bayangan biru halus */
    }
    
    /* Animasi Blob yang Disesuaikan (Jika belum ada di style global) */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    
    /* Mengurangi animasi jika user mengaktifkan "Reduced Motion" di OS */
    @media (prefers-reduced-motion: reduce) {
        .animate-blob { animation: none; }
    }
</style>


<div class="w-full max-w-6xl mx-auto min-h-[85vh] flex flex-col justify-center px-4 relative z-10" 
     x-data="{ mode: 'portal', isLoading: false }">

    <!-- Ornamen Latar Belakang Khusus Halaman Index (Tema Microsoft Elevate) -->
    <div class="absolute inset-0 pointer-events-none -z-10 overflow-hidden">
        <!-- Ambient Globs / Orbs -->
        <div class="absolute top-10 left-1/4 w-[400px] h-[400px] sm:w-[600px] sm:h-[600px] bg-cyan-400/20 rounded-full blur-[100px] sm:blur-[150px] animate-blob"></div>
        <div class="absolute bottom-10 right-1/4 w-[400px] h-[400px] sm:w-[600px] sm:h-[600px] bg-blue-600/20 rounded-full blur-[100px] sm:blur-[150px] animate-blob animation-delay-2000"></div>
        
        <!-- Texture Overlay -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    <!-- 1. HERO SECTION (DYNAMIC THEME) -->
    <?php echo $__env->make('students.portal.partials.home-hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/index.blade.php ENDPATH**/ ?>