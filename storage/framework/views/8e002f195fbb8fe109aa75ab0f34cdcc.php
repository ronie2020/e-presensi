<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Portal Siswa')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts & Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">

    <!-- 1. INCLUDE SIDEBAR SISWA -->
    <?php echo $__env->make('layouts.student-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- 2. OVERLAY MOBILE -->
    <div x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="transition-opacity ease-linear duration-300" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-40 lg:hidden" 
            @click="sidebarOpen = false">
    </div>

    <!-- 3. KONTEN UTAMA -->
    <div class="lg:pl-72 flex flex-col min-h-screen transition-all duration-300">
        
        <!-- HEADER MOBILE & TABLET (Sticky Top) -->
        <header class="bg-white/90 backdrop-blur-md sticky top-0 z-30 border-b border-slate-200 px-4 py-3 flex lg:hidden justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-blue-600 focus:outline-none p-2 rounded-lg hover:bg-slate-100">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <span class="font-bold text-slate-800 text-lg">Portal Siswa</span>
            </div>
            
            <!-- Avatar Kecil Mobile -->
            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden border border-slate-300">
                <?php if(Auth::guard('student')->user()->photo_path): ?>
                    <img src="<?php echo e(asset('storage/' . Auth::guard('student')->user()->photo_path)); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-xs font-bold text-slate-500">
                        <?php echo e(substr(Auth::guard('student')->user()->name, 0, 1)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- MAIN CONTENT SLOT -->
        <main class="flex-1 p-4 md:p-8">
            <div class="max-w-6xl mx-auto">
                <!-- Slot untuk konten halaman -->
                <?php echo e($slot ?? ''); ?>

            </div>
        </main>
        
        <!-- FOOTER SIMPLE -->
        <footer class="p-6 text-center text-xs text-slate-400 font-medium">
            &copy; <?php echo e(date('Y')); ?> SMPN 3 Lakbok. Portal Akademik Siswa.
        </footer>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\components\student-panel-layout.blade.php ENDPATH**/ ?>