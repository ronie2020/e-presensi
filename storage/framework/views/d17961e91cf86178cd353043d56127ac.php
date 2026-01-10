<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title>Login - <?php echo e(config('app.name', 'SMP Negeri 3 Lakbok')); ?></title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <!-- 
             OPTIMASI 1: Tambahkan 'defer'
             Agar script icon tidak menunda tampilan halaman muncul
        -->
        <script src="https://unpkg.com/@phosphor-icons/web" defer></script>

        <!-- 
             OPTIMASI 2 (Opsional): Alpine.js CDN 
             Aktifkan baris di bawah ini HANYA jika animasi loading di tombol login tidak jalan.
             Biasanya Laravel Breeze sudah menyertakan ini di app.js.
        -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->

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
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <!-- Gradient Base -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/50 to-slate-100"></div>
            
            <!-- Animated Blobs -->
            <div class="absolute top-0 -left-20 w-72 md:w-96 h-72 md:h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-[60px] md:blur-[80px] opacity-40 animate-blob"></div>
            <div class="absolute bottom-0 -right-20 w-72 md:w-96 h-72 md:h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-[60px] md:blur-[80px] opacity-40 animate-blob animation-delay-2000"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative z-10">
            
            <!-- === MAIN CARD CONTAINER === -->
            <!-- 
                OPTIMASI 3: max-h-[90vh] untuk Desktop
                Agar card tidak terlalu tinggi di layar monitor kecil
            -->
            <div class="w-full max-w-4xl bg-white rounded-3xl md:rounded-[2rem] shadow-xl md:shadow-2xl shadow-blue-900/10 overflow-hidden flex flex-col md:flex-row border border-white/50 ring-1 ring-slate-100 md:max-h-[90vh]">

                <!-- KOLOM KIRI (BRANDING) -->
                <div class="w-full md:w-5/12 bg-slate-900 relative flex flex-col justify-between p-6 md:p-10 text-white overflow-hidden shrink-0 transition-all">
                    
                    <!-- Background Decoration -->
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-900 via-slate-900 to-slate-900 z-0"></div>
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 z-0"></div>
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500 rounded-full mix-blend-overlay filter blur-[60px] opacity-30 animate-pulse"></div>

                    <!-- Content Wrapper -->
                    <div class="relative z-10 h-full flex flex-row md:flex-col items-center md:items-start justify-between md:justify-start gap-4">
                        
                        <!-- Logo Section -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-900/50 border border-white/20 shrink-0">
                                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-6 h-6 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                <!-- Fallback Icon jika gambar gagal load -->
                                <i class="ph-bold ph-buildings text-xl hidden"></i>
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="font-extrabold text-white text-lg tracking-tight">SMPN 3 LAKBOK</span>
                                <span class="text-[10px] font-bold text-blue-200/70 uppercase tracking-widest">Unggul & Berkarakter</span>
                            </div>
                        </div>

                        <!-- Hero Text & Description -->
                        <div class="hidden md:flex flex-col my-auto space-y-4">
                            <div class="inline-flex self-start items-center gap-2 px-3 py-1 rounded-full bg-blue-900/50 border border-blue-700/50 text-blue-300 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                                <span class="relative flex h-1.5 w-1.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-blue-500"></span>
                                </span>
                                Sistem Informasi Sekolah
                            </div>

                            <h2 class="text-3xl font-extrabold leading-tight tracking-tight">
                                Kelola Akademik <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">Lebih Efisien.</span>
                            </h2>
                            
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Platform terintegrasi untuk manajemen presensi, penilaian, dan administrasi kesiswaan.
                            </p>
                        </div>
                        
                        <!-- Copyright Desktop Only -->
                        <div class="hidden md:flex mt-auto pt-6 border-t border-white/10 w-full items-center justify-between text-[10px] text-blue-300/60 font-medium">
                            <span>&copy; <?php echo e(date('Y')); ?> Netila.</span>
                            <a href="/" class="hover:text-white transition-colors flex items-center gap-1 group">
                                <i class="ph-bold ph-globe"></i> Website
                            </a>
                        </div>

                    </div>
                </div>

                <!-- 
                    KOLOM KANAN (FORM)
                    OPTIMASI 4: overflow-y-auto
                    Agar jika form sangat panjang, user tetap bisa scroll di dalam card
                -->
                <div class="w-full md:w-7/12 p-6 sm:p-8 md:p-12 flex flex-col justify-center bg-white relative overflow-y-auto">
                    <div class="w-full max-w-sm mx-auto">
                        
                        <!-- Greeting Header -->
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

                        <!-- Footer Links (Mobile & Desktop) -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col items-center gap-4">
                            <a href="/" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors group">
                                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                                Kembali ke Halaman Depan
                            </a>
                            
                            <!-- Copyright Mobile Only -->
                            <p class="md:hidden text-[10px] text-slate-300 font-medium">
                                &copy; <?php echo e(date('Y')); ?> SMP Negeri 3 Lakbok.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/layouts/guest.blade.php ENDPATH**/ ?>