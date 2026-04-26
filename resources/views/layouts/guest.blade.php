<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
        
         <!-- PWA META TAGS -->
        <link rel="manifest" href="{{ asset('manifest-guru.json') }}">
        <meta name="theme-color" content="#2c3f61"> <!-- Elevate Navy -->
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-guru-192x192.png') }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="SIMADU Lakbok">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])        
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            /* Efek Grid Halus untuk Background Login */
            .bg-grid-pattern {
                background-image: linear-gradient(to right, rgba(86, 187, 241, 0.05) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(86, 187, 241, 0.05) 1px, transparent 1px);
                background-size: 30px 30px;
            }
        </style>
    </head>
    
    <!-- PERBAIKAN: Mengganti overflow-hidden menjadi overflow-x-hidden agar bisa di-scroll vertikal di HP -->
    <body class="font-sans text-[#2c3f61] antialiased min-h-screen bg-[#f8fafc] bg-grid-pattern relative overflow-x-hidden flex flex-col md:flex-row">
        
        <!-- DEKORASI BACKGROUND (Elevate Ornaments) -->
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-[#56bbf1]/10 rounded-full blur-[80px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-[#0d52a1]/5 rounded-tl-[100%] pointer-events-none"></div>

        <!-- SPLIT LAYOUT UNTUK DESKTOP -->
        
        <!-- BAGIAN KIRI (Branding & Ilustrasi) -->
        <div class="hidden md:flex md:w-1/2 lg:w-3/5 bg-gradient-to-br from-[#2c3f61] to-[#0d52a1] p-12 text-white flex-col justify-between relative shadow-2xl z-10 rounded-br-[4rem] min-h-screen">
            <!-- Aksen Garis Elevate -->
            <div class="absolute top-0 right-0 w-full h-full overflow-hidden pointer-events-none rounded-br-[4rem]">
                <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-gradient-to-bl from-[#56bbf1]/30 to-transparent rounded-full blur-3xl"></div>
                <div class="absolute bottom-[10%] left-[10%] w-[30%] h-[30%] bg-gradient-to-tr from-[#f9a282]/20 to-transparent rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-16">
                    <!-- LOGO DESKTOP -->
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Netila" class="w-14 h-14 rounded-[1rem] object-cover border border-white/30 shadow-sm shrink-0">
                    <div>
                        <h1 class="font-black text-xl tracking-tight leading-none">SMP NEGERI 3 LAKBOK</h1>
                        <p class="text-xs text-[#56bbf1] font-bold uppercase tracking-widest">Sistem Informasi Terpadu</p>
                    </div>
                </div>

                <div class="max-w-lg">
                    <h2 class="text-4xl lg:text-5xl font-black mb-6 leading-tight">
                        Platform <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] to-white">E-Presensi & Ujian</span> <br>
                        Terintegrasi.
                    </h2>
                    <p class="text-[#e5eff5] text-lg font-medium leading-relaxed opacity-90">
                        Selamat datang di SIMADU (Sistem Manajemen Terpadu). Silakan masuk untuk mengelola absensi, jadwal pelajaran, dan bank soal ujian dalam satu pintu.
                    </p>
                </div>
            </div>

            <div class="relative z-10 mt-12 flex items-center gap-4 text-xs font-bold text-[#56bbf1] uppercase tracking-widest">
                <i class="ph-bold ph-shield-check text-xl"></i> Secure & Protected Environment
            </div>
        </div>

        <!-- BAGIAN KANAN (Form Login) -->
        <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center items-center p-6 sm:p-12 relative z-20 min-h-screen">
            
            <!-- Mobile Header Logo (Visible only on small screens) -->
            <div class="md:hidden text-center mb-8 pt-4">
                <!-- LOGO MOBILE -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo Netila" class="w-16 h-16 rounded-[1.25rem] object-cover mx-auto mb-4 shadow-sm border border-slate-200">
                <h1 class="font-black text-2xl text-[#2c3f61] tracking-tight">SIMADU LAKBOK</h1>
                <p class="text-xs text-[#0d52a1] font-bold uppercase tracking-widest">Portal Guru & Admin</p>
            </div>

            <!-- Login Box -->
            <div class="w-full max-w-md">
                <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-[#56bbf1]/10 border border-slate-100 p-8 sm:p-10 relative overflow-hidden">
                    
                    <!-- Decorative Corner -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-[#e5eff5] to-transparent rounded-bl-[3rem] pointer-events-none opacity-60"></div>

                    <div class="mb-8 relative z-10">
                        <h3 class="text-2xl font-black text-[#2c3f61] mb-1">Masuk ke Akun</h3>
                        <p class="text-sm font-bold text-slate-400">Masukkan kredensial Anda untuk melanjutkan.</p>
                    </div>

                    <div class="relative z-10">
                        {{ $slot }}
                    </div>

                </div>

                <!-- Footer / Back Link -->
                <div class="mt-8 text-center pb-6">
                    <div class="flex flex-col items-center gap-3">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#0d52a1] transition-colors group">
                            <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            Kembali ke Halaman Depan
                        </a>
                        
                        <p class="md:hidden text-[10px] text-slate-400 font-medium">
                            &copy; {{ date('Y') }} SMP Negeri 3 Lakbok.
                        </p>
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
</html>