<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Ruang Belajar')); ?></title>

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
<body class="font-sans antialiased bg-slate-50 text-slate-800">
    
    
    <nav class="bg-white/90 backdrop-blur-md border-b border-slate-200/60 fixed w-full z-50 top-0 transition-all shadow-sm">
        <div class="h-1 w-full bg-gradient-to-r from-rose-500 via-orange-400 to-rose-500"></div> 
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-8">
                    
                    <a href="<?php echo e(route('students.learning.index')); ?>" class="flex items-center gap-3 shrink-0 group">
                        <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-lg shadow-slate-900/20 group-hover:bg-rose-600 group-hover:shadow-rose-600/30 transition-all duration-300">
                            <i class="ph-bold ph-books text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-extrabold text-slate-800 text-lg tracking-tight group-hover:text-rose-600 transition-colors">Ruang Belajar</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SMPN 3 Lakbok</p>
                        </div>
                    </a>

                    
                    <div class="hidden md:flex space-x-1">
                        <a href="<?php echo e(route('students.learning.index')); ?>" 
                           class="px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all border
                           <?php echo e(request()->routeIs('students.learning.*') 
                                ? 'bg-slate-100 text-slate-900 border-slate-200' 
                                : 'text-slate-500 border-transparent hover:bg-slate-50 hover:text-slate-700'); ?>">
                            <i class="<?php echo e(request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold'); ?> ph-chalkboard-teacher text-lg"></i>
                            Materi & Tugas
                        </a>                        
                      
                    </div>
                </div>

                
                <div class="flex items-center gap-5">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-800 leading-none"><?php echo e(Auth::guard('student')->user()->name ?? 'Siswa'); ?></p>
                        <span class="inline-flex mt-1.5 items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                            <?php echo e(Auth::guard('student')->user()->student_id ?? '-'); ?>

                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-slate-200"></div>

                    <form method="POST" action="<?php echo e(route('student.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-rose-600 hover:text-white hover:border-rose-600 hover:shadow-lg hover:shadow-rose-600/20 transition-all group" title="Keluar">
                            <i class="ph-bold ph-sign-out text-lg group-hover:translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    
    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-slate-900/90 backdrop-blur-xl border border-white/10 z-50 px-6 py-4 flex justify-between items-center rounded-2xl shadow-2xl shadow-slate-900/30">
        <a href="<?php echo e(route('students.learning.index')); ?>" class="flex flex-col items-center gap-1 transition-all <?php echo e(request()->routeIs('students.learning.*') ? 'text-white' : 'text-slate-500'); ?>">
            <i class="<?php echo e(request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold'); ?> ph-books text-2xl"></i>
            <span class="text-[10px] font-bold">Belajar</span>
        </a>
        
        <a href="<?php echo e(route('student.exam.index')); ?>" class="flex flex-col items-center gap-1 transition-all text-slate-500 hover:text-rose-400">
            <i class="ph-bold ph-desktop text-2xl"></i>
            <span class="text-[10px] font-bold">Ujian</span>
        </a>

        <div class="h-8 w-px bg-white/10 mx-2"></div>

        <form method="POST" action="<?php echo e(route('student.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex flex-col items-center gap-1 text-rose-500 hover:text-rose-400 transition-colors">
                <i class="ph-bold ph-sign-out text-2xl"></i>
                <span class="text-[10px] font-bold">Keluar</span>
            </button>
        </form>
    </div>

    <div class="pt-28 min-h-screen flex flex-col relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-white to-transparent -z-10"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-rose-500/5 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-20 -left-20 w-72 h-72 bg-blue-500/5 rounded-full blur-3xl -z-10"></div>

        
        <?php if(isset($header)): ?>
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                <?php echo e($header); ?>

            </header>
        <?php endif; ?>

        <main class="flex-1 relative z-10">
            <?php echo e($slot ?? ''); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="text-center py-12 text-slate-400 text-xs font-bold pb-28 md:pb-12">
            <p>&copy; <?php echo e(date('Y')); ?> SMPN 3 Lakbok. <span class="text-rose-500">Learning Management System.</span></p>
        </footer>
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\layouts\student_learning.blade.php ENDPATH**/ ?>