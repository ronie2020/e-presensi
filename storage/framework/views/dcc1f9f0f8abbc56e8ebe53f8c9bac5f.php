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
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    
    <nav class="bg-white/90 backdrop-blur-md border-b border-rose-100 fixed w-full z-50 top-0 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-4">
                    
                    <a href="<?php echo e(route('student.exam.index')); ?>" class="flex items-center gap-3 shrink-0 group cursor-default">
                        <div class="w-10 h-10 bg-rose-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rose-600/30">
                            <i class="ph-bold ph-exam text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-black text-slate-800 text-lg tracking-tight">Computer Based Test</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Secure Exam Mode</p>
                        </div>
                    </a>
                </div>

                
                <div class="flex items-center gap-5">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-700 leading-none"><?php echo e(Auth::guard('student')->user()->name ?? 'Siswa'); ?></p>
                        <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-100">
                            <?php echo e(Auth::guard('student')->user()->student_id ?? '-'); ?>

                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-slate-200"></div>

                    <form method="POST" action="<?php echo e(route('student.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all group" title="Keluar Aplikasi">
                            <i class="ph-bold ph-sign-out text-lg group-hover:-translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    
    

    <div class="pt-24 min-h-screen flex flex-col">
        
        <?php if(isset($header)): ?>
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <?php echo e($header); ?>

            </header>
        <?php endif; ?>

        <main class="flex-1">
            <?php echo e($slot ?? ''); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="text-center py-8 text-slate-400 text-xs font-medium pb-8">
            <p>&copy; <?php echo e(date('Y')); ?> Sistem Ujian Sekolah. <span class="text-rose-500">Mode Ujian Aman.</span></p>
        </footer>
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\components\student-exam-layout.blade.php ENDPATH**/ ?>