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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Untuk Toast Notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> <!-- Untuk Pencarian/Filter Dinamis -->

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; } /* Scroll halus global */
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    
    <nav class="bg-slate-900 border-b border-slate-800 fixed w-full z-50 top-0 shadow-2xl shadow-slate-900/10">
        
        <div class="h-1 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center gap-6">
                    
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                            <i class="ph-bold ph-monitor-play text-xl"></i>
                        </div>
                        <div class="leading-tight">
                            <h1 class="font-bold text-white text-lg tracking-tight">Ujian Online</h1>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem Terhubung</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="hidden md:flex ml-8 border-l border-white/10 pl-8">
                        <a href="<?php echo e(route('student.exam.index')); ?>" 
                           class="text-blue-400 text-sm font-bold flex items-center gap-2 hover:text-blue-300 transition-colors">
                            <i class="ph-fill ph-list-checks"></i> Daftar Ujian
                        </a>
                    </div>
                </div>

                
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-300"><?php echo e(Auth::guard('student')->user()->name); ?></p>
                        <p class="text-[10px] text-slate-500"><?php echo e(Auth::guard('student')->user()->student_id); ?></p>
                    </div>
                    
                    <a href="<?php echo e(route('portal.index')); ?>" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-slate-300 text-xs font-bold hover:bg-rose-500/10 hover:border-rose-500/30 hover:text-rose-400 transition-all flex items-center gap-2 group" title="Kembali ke Portal Utama">
                        <i class="ph-bold ph-door-open group-hover:-translate-x-1 transition-transform"></i>
                        <span class="hidden sm:inline">Keluar Mode Ujian</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-slate-900 border-t border-slate-800 z-50 px-6 py-3 flex justify-between items-center">
        <a href="<?php echo e(route('portal.index')); ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-rose-400 transition-colors">
            <i class="ph-bold ph-door-open text-xl"></i>
            <span class="text-[10px] font-bold">Keluar</span>
        </a>
        
        <div class="px-6 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 flex items-center gap-2 backdrop-blur-sm">
            <i class="ph-fill ph-desktop text-lg"></i>
            <span class="text-xs font-bold">Mode Ujian</span>
        </div>

        <div class="w-8"></div> 
    </div>

    
    <div class="pt-16 min-h-screen flex flex-col bg-slate-50/50">
        <main class="flex-1 w-full">
            <?php echo e($slot ?? ''); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="text-center py-6 text-slate-400 text-[10px] font-medium pb-24 md:pb-6 flex items-center justify-center gap-1.5">
            <i class="ph-fill ph-student"></i>
            <p>Computer Based Test (CBT) System &copy; <?php echo e(date('Y')); ?></p>
        </footer>
    </div>
</body>
</html><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/layouts/student.blade.php ENDPATH**/ ?>