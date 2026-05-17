<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Ujian Online')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        /* Custom scrollbar khusus untuk siswa */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #38bdf8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #3b5889; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    
    <nav class="bg-white/80 backdrop-blur-xl border-b border-slate-200/60 sticky top-0 z-40 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-[72px] items-center">
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-[1rem] bg-gradient-to-br from-elevate-accent to-elevate-primary text-white flex items-center justify-center text-xl shadow-md shadow-elevate-accent/30">
                        <i class="ph-bold ph-student"></i>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg font-black text-elevate-dark leading-none">Netila</h1>
                        <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest">Portal Siswa</p>
                    </div>
                </div>

                
                <div class="flex items-center gap-3 sm:gap-5">
                    <?php if(Auth::guard('student')->check()): ?>
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-bold text-elevate-dark"><?php echo e(Auth::guard('student')->user()->name); ?></p>
                            <p class="text-xs text-slate-400 font-mono"><?php echo e(Auth::guard('student')->user()->student_id); ?></p>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo e(route('portal.index')); ?>" class="px-4 py-2 bg-slate-100 text-elevate-primary font-bold text-xs rounded-xl hover:bg-elevate-accent hover:text-white transition flex items-center gap-2 border border-elevate-accent/20 active:scale-95 hidden md:flex">
                        <i class="ph-bold ph-door-open text-base"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-lg border-t border-slate-200 z-50 px-6 py-3 flex justify-between items-center shadow-[0_-10px_20px_rgba(0,0,0,0.05)] pb-safe">
        <a href="<?php echo e(route('portal.index')); ?>" class="flex flex-col items-center gap-1 text-slate-400 hover:text-rose-500 transition-colors">
            <i class="ph-bold ph-door-open text-2xl"></i>
            <span class="text-[10px] font-bold">Keluar</span>
        </a>
        
        <div class="px-6 py-2 bg-gradient-to-r from-elevate-accent to-elevate-primary shadow-lg shadow-elevate-accent/30 rounded-[1rem] text-white flex items-center gap-2 transform -translate-y-2">
            <i class="ph-fill ph-desktop text-xl"></i>
            <span class="text-xs font-black tracking-wide">Ujian</span>
        </div>

        <div class="w-8"></div> 
    </div>

    
    <div class="min-h-screen flex flex-col relative z-0">
        <main class="flex-1 w-full pb-20 md:pb-0">
            <?php echo e($slot ?? ''); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="text-center py-6 text-xs font-bold text-slate-400 hidden md:block">
            &copy; <?php echo e(date('Y')); ?> Sistem Ujian Terpadu (CBT). All rights reserved.
        </footer>
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/layouts/student.blade.php ENDPATH**/ ?>