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

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    
    <nav class="bg-slate-900 border-b border-rose-900/30 fixed w-full z-50 top-0 shadow-2xl shadow-rose-900/10">
        
        <div class="h-1 w-full bg-gradient-to-r from-rose-500 via-orange-500 to-rose-500"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center gap-6">
                    
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-9 h-9 bg-rose-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-rose-600/20 animate-pulse">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <div class="leading-tight">
                            <h1 class="font-bold text-white text-lg tracking-tight">Ujian Online</h1>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem Terhubung</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="hidden md:flex ml-8 border-l border-white/10 pl-8">
                        <a href="<?php echo e(route('student.exam.index')); ?>" 
                           class="text-rose-400 text-sm font-bold flex items-center gap-2">
                            <i class="ph-fill ph-list-checks"></i> Daftar Ujian
                        </a>
                    </div>
                </div>

                
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-300"><?php echo e(Auth::guard('student')->user()->name); ?></p>
                        <p class="text-[10px] text-slate-500"><?php echo e(Auth::guard('student')->user()->student_id); ?></p>
                    </div>
                    
                    
                    <a href="<?php echo e(route('portal.index')); ?>" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-slate-300 text-xs font-bold hover:bg-white/10 hover:text-white transition-colors flex items-center gap-2" title="Kembali ke Portal Utama">
                        <i class="ph-bold ph-door-open"></i>
                        <span class="hidden sm:inline">Keluar Mode Ujian</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-slate-900 border-t border-rose-900/30 z-50 px-6 py-3 flex justify-between items-center">
        
        <a href="<?php echo e(route('portal.index')); ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-white">
            <i class="ph-bold ph-door-open text-xl"></i>
            <span class="text-[10px] font-bold">Keluar</span>
        </a>
        
        <div class="px-6 py-2 bg-rose-900/20 border border-rose-500/30 rounded-full text-rose-400 flex items-center gap-2">
            <i class="ph-fill ph-desktop text-lg"></i>
            <span class="text-xs font-bold">Mode Ujian</span>
        </div>

        <div class="w-8"></div> 
    </div>

    <div class="pt-20 min-h-screen flex flex-col bg-slate-50">
        <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
            <?php echo e($slot ?? ''); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="text-center py-6 text-slate-400 text-[10px] font-medium pb-20 md:pb-6">
            <p>Computer Based Test (CBT) System &copy; <?php echo e(date('Y')); ?></p>
        </footer>
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\layouts\student.blade.php ENDPATH**/ ?>