<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Area Siswa')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Custom Scrollbar untuk menu mobile jika perlu */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    
    <nav class="bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 border-b border-slate-800 fixed w-full z-50 top-0 shadow-xl shadow-blue-900/10 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center gap-8">
                    <a href="<?php echo e(route('students.learning.index')); ?>" class="flex items-center gap-3 shrink-0 group">
                        <div class="w-10 h-10 bg-blue-950 border border-blue-800 rounded-xl flex items-center justify-center text-yellow-400 shadow-lg shadow-blue-900/50 group-hover:scale-105 transition-transform">
                            <i class="ph-bold ph-graduation-cap text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-bold text-white text-lg tracking-tight group-hover:text-blue-200 transition-colors">Area Siswa</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-yellow-400 transition-colors">SMPN 3 Lakbok</p>
                        </div>
                    </a>

                    <div class="hidden md:flex space-x-2">
                        <a href="<?php echo e(route('students.learning.index')); ?>" 
                           class="px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all border border-transparent
                           <?php echo e(request()->routeIs('students.learning.*') 
                                ? 'bg-blue-800/50 text-yellow-400 border-blue-700/50 shadow-inner' 
                                : 'text-slate-300 hover:bg-white/5 hover:text-white'); ?>">
                            <i class="ph-fill ph-books text-lg <?php echo e(request()->routeIs('students.learning.*') ? 'text-yellow-400' : 'text-slate-400'); ?>"></i>
                            Ruang Belajar
                        </a>

                        <a href="<?php echo e(route('student.exam.index')); ?>" 
                           class="px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all border border-transparent
                           <?php echo e(request()->routeIs('student.exam.*') 
                                ? 'bg-rose-900/30 text-rose-400 border-rose-800/50 shadow-inner' 
                                : 'text-slate-300 hover:bg-white/5 hover:text-white'); ?>">
                            <i class="ph-fill ph-desktop text-lg <?php echo e(request()->routeIs('student.exam.*') ? 'text-rose-400' : 'text-slate-400'); ?>"></i>
                            Ujian Online
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-5">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-200 leading-none"><?php echo e(Auth::guard('student')->user()->name ?? 'Siswa'); ?></p>
                        <p class="text-[10px] text-blue-300 font-mono mt-1 tracking-wider"><?php echo e(Auth::guard('student')->user()->student_id ?? '-'); ?></p>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-slate-700"></div>

                    <form method="POST" action="<?php echo e(route('student.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-rose-900/50 hover:text-rose-400 hover:border-rose-800 transition-all group" title="Keluar">
                            <i class="ph-bold ph-sign-out text-lg group-hover:-translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="md:hidden fixed bottom-0 left-0 w-full bg-slate-900 border-t border-slate-800 z-50 px-6 py-2 flex justify-around shadow-[0_-4px_20px_-1px_rgba(0,0,0,0.3)] backdrop-blur-lg bg-opacity-95">
        
        <a href="<?php echo e(route('students.learning.index')); ?>" class="flex flex-col items-center gap-1 p-2 rounded-xl transition-all <?php echo e(request()->routeIs('students.learning.*') ? 'text-yellow-400' : 'text-slate-500 hover:text-slate-300'); ?>">
            <div class="<?php echo e(request()->routeIs('students.learning.*') ? 'bg-blue-900/50 px-4 py-1 rounded-full mb-0.5' : ''); ?>">
                <i class="ph-fill ph-books text-2xl"></i>
            </div>
            <span class="text-[10px] font-bold">Belajar</span>
        </a>

        <a href="<?php echo e(route('student.exam.index')); ?>" class="flex flex-col items-center gap-1 p-2 rounded-xl transition-all <?php echo e(request()->routeIs('student.exam.*') ? 'text-rose-400' : 'text-slate-500 hover:text-slate-300'); ?>">
             <div class="<?php echo e(request()->routeIs('student.exam.*') ? 'bg-rose-900/20 px-4 py-1 rounded-full mb-0.5' : ''); ?>">
                <i class="ph-fill ph-desktop text-2xl"></i>
             </div>
            <span class="text-[10px] font-bold">Ujian</span>
        </a>

        <form method="POST" action="<?php echo e(route('student.logout')); ?>" class="flex flex-col items-center gap-1 p-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex flex-col items-center text-slate-500 hover:text-red-400">
                <i class="ph-bold ph-sign-out text-2xl"></i>
                <span class="text-[10px] font-bold mt-1">Keluar</span>
            </button>
        </form>
    </div>

    <div class="pt-16 min-h-screen flex flex-col">
        <?php if(isset($header)): ?>
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-4">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        <main class="flex-1">
            <?php echo $__env->yieldContent('content'); ?> 
            <?php echo e($slot ?? ''); ?> 
        </main>

        <footer class="text-center py-8 text-slate-400 text-xs font-medium pb-24 md:pb-8">
            <p>&copy; <?php echo e(date('Y')); ?> SMPN 3 Lakbok. Learning Management System.</p>
        </footer>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\layouts\student-old.blade.php ENDPATH**/ ?>