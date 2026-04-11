<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Ujian Online')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    
    <script src="https://unpkg.com/@phosphor-icons/web" async></script>
    
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">
    
    <!-- NAVBAR (DARK BLUE THEME) -->
    <nav class="bg-gray-900 bg-gradient-to-r from-slate-900 to-blue-900 border-b border-white/10 fixed w-full z-50 top-0 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo & Judul -->
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/10 shadow-inner">
                        <i class="ph-bold ph-student text-2xl"></i>
                    </div>
                    <div class="leading-tight">
                        <h1 class="font-extrabold text-white text-lg tracking-tight">Portal Ujian</h1>
                        <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">CBT System</p>
                    </div>
                </div>

                <!-- Menu Kanan (User Info & Logout) -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right mr-2">
                        <?php if(Auth::guard('student')->check()): ?>
                            <p class="text-sm font-bold text-white"><?php echo e(Auth::guard('student')->user()->name); ?></p>
                            <p class="text-xs text-blue-300 font-mono"><?php echo e(Auth::guard('student')->user()->student_id); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Tombol Logout (Diubah menggunakan fungsi JS konfirmasi) -->
                    <form id="logoutForm" method="POST" action="<?php echo e(route('student.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="button" onclick="confirmLogout()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-lg shadow-rose-900/20 active:scale-95">
                            <i class="ph-bold ph-sign-out text-lg"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="pt-28 pb-12 min-h-screen relative overflow-hidden">
        
        <div class="absolute inset-0 z-0 opacity-30 pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-100/50 to-transparent"></div>
        </div>

        <?php if(isset($header)): ?>
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        <main class="relative z-10">
            <?php echo e($slot); ?>

        </main>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Setup SweetAlert Toast untuk Notifikasi Global (Mirip Notifikasi HP)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100 mt-20' // mt-20 agar tidak tertutup navbar fixed
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Tangkap Session Flash dari Laravel Controller lalu ubah jadi Toast Interaktif
            <?php if(session('success')): ?>
                Toast.fire({ icon: 'success', title: '<?php echo session('success'); ?>' });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Toast.fire({ icon: 'error', title: '<?php echo session('error'); ?>' });
            <?php endif; ?>
            
            <?php if(session('warning')): ?>
                Toast.fire({ icon: 'warning', title: '<?php echo session('warning'); ?>' });
            <?php endif; ?>
            
            <?php if(session('info')): ?>
                Toast.fire({ icon: 'info', title: '<?php echo session('info'); ?>' });
            <?php endif; ?>
        });

        // 2. Fungsi Konfirmasi Logout Fungsional
        function confirmLogout() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Anda akan keluar dari portal ujian. Pastikan semua ujian Anda telah selesai dikumpulkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', // Warna Rose/Merah
                cancelButtonColor: '#64748b', // Warna Slate/Abu-abu
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold px-6 py-3',
                    cancelButton: 'rounded-xl font-bold px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading kecil saat memproses logout
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang mengeluarkan akun Anda',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
                    });
                    
                    // Submit form logout
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\cbt\seb_landing.blade.php ENDPATH**/ ?>