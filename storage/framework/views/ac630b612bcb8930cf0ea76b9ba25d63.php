<?php $__env->startSection('content'); ?>


<style>
    [x-cloak] { display: none !important; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
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


<div class="w-full max-w-6xl mx-auto min-h-[85vh] flex flex-col justify-center px-4" 
     x-data="{ mode: 'portal', isLoading: false }">

    <!-- 1. HERO SECTION (DYNAMIC THEME) -->
    <?php echo $__env->make('students.portal.partials.home-hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/students/portal/index.blade.php ENDPATH**/ ?>