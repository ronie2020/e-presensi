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
<body class="font-sans antialiased bg-elevate-soft">
    
    
    <nav class="bg-gradient-to-r from-elevate-primary to-elevate-dark border-b border-elevate-accent/20 fixed w-full z-50 top-0 transition-all shadow-lg shadow-elevate-dark/10">
        <div class="h-1 w-full bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-accent"></div> 
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-8">
                    
                    <a href="<?php echo e(route('students.learning.index')); ?>" class="flex items-center gap-3 shrink-0 group">
                        <div class="w-10 h-10 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl flex items-center justify-center text-white shadow-inner group-hover:rotate-6 transition-transform duration-300">
                            <i class="ph-bold ph-books text-2xl"></i>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <h1 class="font-black text-white text-lg tracking-tight group-hover:text-elevate-accent transition-colors">Ruang Belajar</h1>
                            <p class="text-[10px] font-bold text-elevate-accent uppercase tracking-widest"><?php echo e(config('app.name')); ?></p>
                        </div>
                    </a>

                    
                    <div class="hidden md:flex space-x-1 border-l border-white/10 pl-8">
                        <a href="<?php echo e(route('students.learning.index')); ?>" 
                           class="px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all border
                           <?php echo e(request()->routeIs('students.learning.*') 
                                ? 'bg-white/20 text-white border-white/20 shadow-inner' 
                                : 'text-elevate-soft border-transparent hover:bg-white/10 hover:text-white'); ?>">
                            <i class="<?php echo e(request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold'); ?> ph-chalkboard-teacher text-lg"></i>
                            Materi & Tugas
                        </a>                        
                       
                    </div>
                </div>

                
                <div class="flex items-center gap-5">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-white leading-none"><?php echo e(Auth::guard('student')->user()->name ?? 'Siswa'); ?></p>
                        <span class="inline-flex mt-1.5 items-center px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-elevate-accent border border-white/10">
                            <?php echo e(Auth::guard('student')->user()->student_id ?? '-'); ?>

                        </span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-white/20"></div>

                    <form method="POST" action="<?php echo e(route('student.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-white hover:bg-elevate-peach hover:text-elevate-dark hover:border-elevate-peach-dark hover:shadow-lg hover:shadow-elevate-peach/20 transition-all group" title="Keluar">
                            <i class="ph-bold ph-sign-out text-lg group-hover:translate-x-0.5 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    
    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-elevate-dark/95 backdrop-blur-xl border border-elevate-primary/50 z-50 px-6 py-4 flex justify-between rounded-[2rem] shadow-2xl shadow-elevate-dark/40">
        <a href="<?php echo e(route('students.learning.index')); ?>" class="flex flex-col items-center gap-1 transition-all <?php echo e(request()->routeIs('students.learning.*') ? 'text-elevate-accent' : 'text-elevate-soft hover:text-elevate-accent'); ?>">
            <i class="<?php echo e(request()->routeIs('students.learning.*') ? 'ph-fill' : 'ph-bold'); ?> ph-books text-2xl"></i>
        </a>
        
        <a href="<?php echo e(route('student.exam.index')); ?>" class="flex flex-col items-center gap-1 transition-all text-elevate-soft hover:text-elevate-accent">
            <i class="ph-bold ph-desktop text-2xl"></i>
        </a>

        <div class="h-8 w-px bg-elevate-primary/50 mx-2"></div>

        <form method="POST" action="<?php echo e(route('student.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-elevate-peach hover:text-elevate-peach-light transition-colors flex items-center pt-1">
                <i class="ph-bold ph-sign-out text-2xl"></i>
            </button>
        </form>
    </div>

    <div class="pt-28 min-h-screen flex flex-col relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-elevate-soft to-transparent -z-10"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-elevate-accent/20 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-20 -left-20 w-72 h-72 bg-elevate-primary/10 rounded-full blur-3xl -z-10"></div>

        
        <?php if(isset($header)): ?>
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                <?php echo e($header); ?>

            </header>
        <?php endif; ?>

        <main class="flex-1 relative z-10">
            <?php echo e($slot ?? ''); ?>

        </main>

        <footer class="text-center py-12 text-slate-500 text-xs font-bold pb-28 md:pb-12">
            <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. <span class="text-elevate-primary">Ruang Belajar Siswa.</span></p>
        </footer>
    </div>
</body>
</html><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/components/student-learning-layout.blade.php ENDPATH**/ ?>