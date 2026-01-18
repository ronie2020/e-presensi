<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO & Meta Tags -->
    <meta name="description" content="Daftar lengkap tenaga pendidik dan guru profesional di SMP Negeri 3 Lakbok.">
    <title>Direktori Pengajar - <?php echo e(config('app.name', 'SMP Negeri 3 Lakbok')); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts & Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        /* Animasi Konsisten (Tanpa AOS Library) */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }

        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        .animate-blob { animation: blob 7s infinite; }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 font-[Plus_Jakarta_Sans] flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="<?php echo e(route('landing')); ?>" class="flex items-center gap-3 group">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-10 h-10 object-contain group-hover:rotate-12 transition-transform">
                    <span class="text-lg font-extrabold text-slate-900 tracking-tight hidden sm:block">SMPN 3 LAKBOK</span>
                </a>
                <a href="<?php echo e(route('landing')); ?>" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full hover:bg-blue-50">
                    <i class="ph-bold ph-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <!-- HEADER SECTION -->
    <div class="bg-slate-900 pt-20 pb-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-600/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/95 to-slate-900"></div>

        <!-- Animated Blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-30 animate-blob" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="inline-block py-1.5 px-4 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm">
                SDM Berkualitas
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight">Direktori Tenaga Pendidik</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                Mengenal lebih dekat profil profesional guru dan staf yang berdedikasi membangun generasi masa depan.
            </p>

            <!-- FORM PENCARIAN (Modern Style) -->
            <form action="<?php echo e(route('teachers.index')); ?>" method="GET" class="max-w-lg mx-auto relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="<?php echo e(request('q')); ?>" 
                           placeholder="Cari nama guru atau mata pelajaran..." 
                           class="w-full pl-14 pr-14 py-4 rounded-full border-0 focus:ring-0 shadow-2xl text-sm font-bold placeholder-slate-400 bg-white/95 backdrop-blur-xl text-slate-800 transition-transform focus:scale-[1.02]">
                    
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="ph-bold ph-magnifying-glass text-xl"></i>
                    </div>
                    
                    <?php if(request('q')): ?>
                        <a href="<?php echo e(route('teachers.index')); ?>" class="absolute right-4 top-1/2 -translate-y-1/2 w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-100 hover:text-red-600 transition-colors" title="Hapus Pencarian">
                            <i class="ph-bold ph-x"></i>
                        </a>
                    <?php else: ?>
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 p-2.5 bg-blue-600 rounded-full text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 hover:scale-110 active:scale-95">
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-20">
        
        <!-- GRID GURU -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="animate-enter group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-blue-900/10 hover:-translate-y-2 transition-all duration-500 border border-slate-100 flex flex-col h-full relative"
                     style="animation-delay: <?php echo e(($index % 4) * 100); ?>ms">
                    
                    <!-- Foto -->
                    <div class="aspect-[4/5] sm:aspect-square bg-slate-200 relative overflow-hidden">
                        <?php if($teacher->photo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $teacher->photo_path)); ?>" 
                                 alt="<?php echo e($teacher->name); ?>" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale group-hover:grayscale-0"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <!-- Fallback -->
                            <div class="hidden w-full h-full flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-slate-200 text-slate-400">
                                <span class="text-4xl font-bold opacity-30"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-300">
                                <span class="text-6xl sm:text-7xl font-black opacity-30 select-none uppercase group-hover:scale-110 transition-transform duration-500">
                                    <?php echo e(substr($teacher->name, 0, 1)); ?>

                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Overlay Kontak -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end justify-center pb-6 gap-3 translate-y-4 group-hover:translate-y-0">
                            <?php if($teacher->phone): ?>
                                <?php
                                    $phoneRaw = preg_replace('/[^0-9]/', '', $teacher->phone);
                                    $waLink = Str::startsWith($phoneRaw, '0') ? '62' . substr($phoneRaw, 1) : $phoneRaw;
                                ?>
                                <a href="https://wa.me/<?php echo e($waLink); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center hover:bg-green-500 hover:border-green-500 hover:scale-110 transition shadow-lg">
                                    <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($teacher->email): ?>
                                <a href="mailto:<?php echo e($teacher->email); ?>" class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center hover:bg-blue-500 hover:border-blue-500 hover:scale-110 transition shadow-lg">
                                    <i class="ph-fill ph-envelope-simple text-xl"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-5 text-center flex-1 flex flex-col relative bg-white">
                        <!-- Badge Posisi -->
                        <div class="absolute -top-4 left-0 right-0 flex justify-center">
                            <span class="inline-block px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-wider rounded-full shadow-lg border-2 border-white transform group-hover:scale-105 transition-transform">
                                <?php echo e($teacher->position ?? $teacher->role); ?>

                            </span>
                        </div>

                        <div class="mt-4 mb-2">
                            <h3 class="text-base sm:text-lg font-bold text-slate-800 leading-tight group-hover:text-blue-600 transition-colors line-clamp-1">
                                <?php echo e($teacher->name); ?>

                            </h3>
                            <?php if($teacher->nip): ?>
                                <p class="text-[10px] sm:text-xs text-slate-400 font-mono mt-1 font-medium bg-slate-50 inline-block px-2 py-0.5 rounded">
                                    <?php echo e($teacher->nip); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($teacher->bio): ?>
                            <div class="mt-auto pt-4 border-t border-slate-50">
                                <p class="text-[11px] sm:text-xs text-slate-500 italic line-clamp-2 leading-relaxed">
                                    "<?php echo e($teacher->bio); ?>"
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-2 lg:col-span-4 py-24 text-center animate-enter">
                    <div class="inline-flex bg-slate-100 p-6 rounded-full mb-6 text-slate-300 ring-8 ring-slate-50">
                        <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                        Maaf, kami tidak dapat menemukan data guru dengan kata kunci tersebut.
                    </p>
                    <?php if(request('q')): ?>
                        <a href="<?php echo e(route('teachers.index')); ?>" class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-full hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 gap-2">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Pencarian
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-16 px-4 animate-enter">
            <?php echo e($teachers->withQueryString()->links()); ?>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="bg-slate-900 text-white pt-16 pb-8 border-t border-slate-800 relative overflow-hidden mt-auto">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-900/20 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <p class="text-slate-500 text-sm">&copy; <?php echo e(date('Y')); ?> SMP Negeri 3 Lakbok. Unggul & Berkarakter.</p>
        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\teachers.blade.php ENDPATH**/ ?>