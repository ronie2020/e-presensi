<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login CBT - Portal Siswa</title>
    
    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* CSS Glass Effect & Utilities */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        /* Style Tombol Kembali (Glass Button) */
        .glass-button {
             background: rgba(255, 255, 255, 0.1);
             backdrop-filter: blur(10px);
             border: 1px solid rgba(255, 255, 255, 0.1);
             transition: all 0.3s ease;
        }
        .glass-button:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateX(-3px); /* Efek geser sedikit saat hover */
        }
        .glass-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(96, 165, 250, 0.5); /* blue-400 */
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="h-full bg-slate-900 text-white overflow-hidden selection:bg-blue-500 selection:text-white">

    <!-- BACKGROUND ANIMATION -->
    <div class="fixed inset-0 z-0">
        <!-- Background Image Overlay -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        <!-- Gradient Background: Dominan Biru Gelap -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
        
        <!-- Animated Blobs (Semua nuansa Biru) -->
        <!-- Blob 1: Biru Utama -->
        <div class="absolute top-0 -left-4 w-96 h-96 bg-blue-600 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob"></div>
        <!-- Blob 2: Cyan/Langit -->
        <div class="absolute top-0 -right-4 w-96 h-96 bg-cyan-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <!-- Blob 3: Indigo/Deep Blue -->
        <div class="absolute -bottom-32 left-20 w-96 h-96 bg-indigo-600 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <!-- TOMBOL KEMBALI (FIXED TOP LEFT) -->
    
    <!-- MAIN CONTENT -->
    <div class="relative z-10 min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
        
        <!-- Floating Icons Decoration -->
        <div class="absolute top-1/4 left-[10%] animate-float hidden lg:block opacity-30 text-cyan-300">
            <i class="ph-duotone ph-student text-8xl"></i>
        </div>
        <div class="absolute bottom-1/4 right-[10%] animate-float hidden lg:block opacity-30 text-blue-300 animation-delay-2000">
            <i class="ph-duotone ph-desktop text-8xl"></i>
        </div>

        <!-- LOGO & BRAND -->
        <div class="text-center mb-8 transform hover:scale-105 transition-transform duration-300">
            <div class="inline-flex p-4 glass-card rounded-2xl mb-6 shadow-2xl shadow-blue-500/20">
                <img src="<?php echo e(asset('images/logo.png')); ?>" class="h-16 w-auto drop-shadow-lg" alt="Logo" onerror="this.src='https://ui-avatars.com/api/?name=S+L&background=0ea5e9&color=fff&size=128'; this.onerror=null;">
            </div>
            <h2 class="text-4xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-cyan-200 via-white to-blue-300 mb-2">
                CBT System
            </h2>
            <p class="text-blue-200/60 font-medium">Ujian Berbasis Komputer & Portal Siswa</p>
        </div>

        <!-- LOGIN CARD -->
        <div class="w-full max-w-md">
            <div class="glass-card rounded-3xl p-1 shadow-2xl shadow-blue-900/20">
                <div class="bg-slate-900/40 rounded-[1.3rem] p-8 backdrop-blur-sm">
                    
                    <div class="mb-8 text-center">
                        <h3 class="text-xl font-bold text-white mb-1">Login Peserta</h3>
                        <p class="text-sm text-slate-400">Masukkan NISN untuk mengakses ujian.</p>
                    </div>

                    <form class="space-y-6" action="<?php echo e(route('student.login.post')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Input NISN -->
                        <div class="space-y-2">
                            <label for="student_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 ml-1">
                                NISN / ID Siswa
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="ph-bold ph-identification-card text-slate-400 group-focus-within:text-cyan-400 transition-colors text-xl"></i>
                                </div>
                                <input id="student_id" name="student_id" type="text" autocomplete="off" required autofocus 
                                    class="glass-input block w-full rounded-xl py-4 pl-12 pr-4 text-white placeholder:text-slate-500 focus:ring-0 focus:outline-none transition-all duration-300 group-focus-within:bg-white/10"
                                    placeholder="Contoh: 0056789012">
                            </div>
                            <!-- Error Message Tetap Merah agar Waspada, tapi disesuaikan -->
                            <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-300 flex items-center gap-1 mt-2 animate-pulse font-bold bg-red-500/10 p-2 rounded-lg border border-red-500/20">
                                    <i class="ph-fill ph-warning-circle"></i> <?php echo e($message); ?>

                                </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Button Blue Gradient -->
                        <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-cyan-500 transition-all duration-300 shadow-lg shadow-blue-600/30 overflow-hidden">
                            <!-- Shine Effect -->
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            
                            <span class="relative flex items-center gap-2">
                                Masuk Ruang Ujian <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>
                    </form>

                    <!-- Footer Help -->
                    <div class="mt-8 text-center">
                        <p class="text-xs text-slate-500">
                            Mengalami kendala login? 
                            <a href="#" class="text-cyan-400 hover:text-cyan-300 font-bold transition-colors">Hubungi Proktor</a>
                        </p>
                        <!-- TOMBOL KEMBALI -->
                        <p class="text-xs text-slate-500">                            
                            <a href="<?php echo e(url('/')); ?>" class="text-cyan-400 hover:text-cyan-300 font-bold transition-colors">Kembali ke Menu Utama</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\auth\student_login.blade.php ENDPATH**/ ?>