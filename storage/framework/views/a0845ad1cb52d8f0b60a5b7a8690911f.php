<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title>Login - <?php echo e(config('app.name', 'SMP Negeri 3 Lakbok')); ?></title>
        
         <!-- PWA META TAGS -->
        <link rel="manifest" href="<?php echo e(asset('manifest-guru.json')); ?>">
        <meta name="theme-color" content="#0284c7"> <!-- Sky 600 -->
        <link rel="apple-touch-icon" href="<?php echo e(asset('icons/icon-guru-192x192.png')); ?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="SIMADU Lakbok">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>        
        <script src="https://unpkg.com/@phosphor-icons/web" defer></script>

        <!-- Styles untuk Animasi Blob -->
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }            
            .animate-blob { animation: blob 7s infinite; }
            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }
            
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 relative overflow-x-hidden selection:bg-blue-500 selection:text-white">
        
        <!-- === BACKGROUND ANIMATION === -->
       <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-gradient-to-br from-cyan-500 via-blue-600 to-blue-900">
            <div class="absolute top-0 left-0 w-full md:w-[60%] h-full bg-cyan-300/30 rounded-full blur-[100px] -translate-x-1/4 -translate-y-1/4 animate-blob"></div>
            <div class="absolute bottom-0 right-0 w-full md:w-[50%] h-[80%] bg-indigo-900/40 rounded-full blur-[120px] translate-x-1/4 translate-y-1/4 animate-blob animation-delay-2000"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] mix-blend-overlay"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative z-10">
            <div class="w-full max-w-4xl bg-white rounded-3xl md:rounded-[2rem] shadow-xl md:shadow-2xl shadow-blue-900/10 overflow-hidden flex flex-col md:flex-row border border-white/50 ring-1 ring-slate-100 md:max-h-[90vh]">

                <!-- KOLOM KIRI (BRANDING) -->
                <div class="w-full md:w-5/12 bg-slate-900 relative flex flex-col justify-between p-6 md:p-10 text-white overflow-hidden shrink-0 transition-all">
                    
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-900 via-slate-900 to-slate-900 z-0"></div>
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 z-0"></div>
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500 rounded-full mix-blend-overlay filter blur-[60px] opacity-30 animate-pulse"></div>

                    <div class="relative z-10 h-full flex flex-row md:flex-col items-center md:items-start justify-between md:justify-start gap-4">
                        
                        <!-- Logo Section -->
                         <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-lg shadow-blue-900/20 shrink-0">
                                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-6 h-6 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                <i class="ph-bold ph-buildings text-xl hidden"></i>
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="font-extrabold text-white text-lg tracking-tight">SMPN 3 LAKBOK</span>
                                <span class="text-[10px] font-bold text-cyan-300 uppercase tracking-widest">Unggul & Berkarakter</span>
                            </div>
                        </div>

                        <!-- Hero Text & Description -->
                        <div class="hidden md:flex flex-col my-auto space-y-4">
                           <div class="inline-flex self-start items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-cyan-100 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                                <span class="relative flex h-1.5 w-1.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-300 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-cyan-400"></span>
                                </span>
                               Sistem Informasi Terpadu ( SIMADU )
                            </div>

                             <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white">
                                Kelola Akademik <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-200">Lebih Efisien.</span>
                            </h2>
                            
                              <p class="text-blue-100/80 text-sm leading-relaxed">
                                SIMADU: Platform terintegrasi untuk manajemen presensi, penilaian, dan administrasi kesiswaan.
                            </p>
                        </div>
                        
                       <div class="hidden md:flex mt-auto pt-6 border-t border-white/20 w-full items-center justify-between text-[10px] text-cyan-100/70 font-medium">
                            <span>&copy; <?php echo e(date('Y')); ?> Netila.</span>
                            <a href="/" class="hover:text-white transition-colors flex items-center gap-1 group">
                                <i class="ph-bold ph-globe"></i> Website
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (FORM) -->
                 <div class="w-full md:w-7/12 p-6 sm:p-8 md:p-12 flex flex-col justify-center bg-white relative overflow-y-auto">
                    <div class="w-full max-w-sm mx-auto">
                        
                        <div class="mb-6 md:mb-8">
                            <h3 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang!</h3>
                            <p class="text-slate-500 text-sm mt-1 leading-relaxed">
                                Silakan masuk untuk mengakses dashboard.
                            </p>
                        </div>

                        <!-- Slot Form Login -->
                        <div class="relative z-10">
                            <?php echo e($slot); ?>

                        </div>

                      <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col items-center gap-4">
                            <a href="/" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-cyan-600 transition-colors group">
                                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                                Kembali ke Halaman Depan
                            </a>
                            
                            <p class="md:hidden text-[10px] text-slate-400 font-medium">
                                &copy; <?php echo e(date('Y')); ?> SMP Negeri 3 Lakbok.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ============================================== -->
        <!-- PWA SERVICE WORKER REGISTRATION (KHUSUS GURU)  -->
        <!-- ============================================== -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {                 
                    navigator.serviceWorker.register('/sw-guru.js')
                        .then(registration => {
                            console.log('PWA Service Worker (Guru) berhasil didaftarkan di halaman Login.');
                        })
                        .catch(error => {
                            console.error('PWA Service Worker (Guru) gagal didaftarkan:', error);
                        });
                });
            }
        </script>
        <!-- ============================================== -->
    </body>
</html><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/layouts/guest.blade.php ENDPATH**/ ?>