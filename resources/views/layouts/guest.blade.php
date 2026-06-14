<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
        
         <!-- PWA META TAGS -->
        <link rel="manifest" href="{{ asset('manifest-guru.json') }}">
        <meta name="theme-color" content="#032b5b"> <!-- Elevate Dark -->
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
            
            /* Efek Grid Halus untuk Background Area Kanan/Mobile */
            .bg-grid-pattern {
                background-image: linear-gradient(to right, rgba(56, 189, 248, 0.05) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(56, 189, 248, 0.05) 1px, transparent 1px);
                background-size: 30px 30px;
            }

            /* TAMBAHAN: Animasi Fade-in Up yang elegan */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0; /* Mulai dari tidak terlihat */
            }
            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
        </style>
    </head>
    
    <body class="font-sans text-elevate-text antialiased min-h-screen bg-slate-50 bg-grid-pattern relative overflow-x-hidden flex flex-col md:flex-row">
        
        <!-- DEKORASI BACKGROUND KANAN (Desktop) -->
        <div class="hidden md:block absolute -top-32 -left-32 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[80px] pointer-events-none z-0"></div>
        <div class="hidden md:block absolute bottom-0 right-0 w-[600px] h-[600px] bg-elevate-primary/5 rounded-tl-[100%] pointer-events-none z-0"></div>

        <!-- ================= PERBAIKAN: BACKGROUND KHUSUS MOBILE ================= -->
        <!-- Memunculkan gambar sekolah sebagai background penuh di HP dengan efek gelap blur -->
        <div class="md:hidden absolute inset-0 z-0 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
            <div class="absolute inset-0 bg-elevate-dark/85 backdrop-blur-sm"></div>
        </div>

        <!-- ================= BAGIAN KIRI (FOTO SEKOLAH & TEKS DESKTOP) ================= -->
        <div class="hidden md:flex md:w-1/2 lg:w-3/5 p-12 text-white flex-col justify-between relative shadow-[10px_0_30px_rgba(0,0,0,0.1)] z-10 rounded-br-[4rem] min-h-screen"
             style="background-image: url('{{ asset('images/netila.jpg') }}');
                    background-size: cover;
                    background-position: center;">
            
            <!-- OVERLAY REVISI: Gradien gelap ke transparan -->
            <div class="absolute inset-0 bg-gradient-to-r from-elevate-dark/95 via-elevate-dark/70 to-elevate-dark/20 rounded-br-[4rem] z-0"></div>

            <!-- Konten Teks Kiri -->
            <div class="relative z-20 animate-fade-in-up delay-100">
                <div class="flex items-center gap-4 mb-16">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Netila" class="w-14 h-14 rounded-[1rem] object-cover border-2 border-white/20 shadow-lg shrink-0">
                    <div>
                        <h1 class="font-black text-xl tracking-tight leading-none drop-shadow-md">SMP NEGERI 3 LAKBOK</h1>
                        <p class="text-xs text-elevate-accent font-bold uppercase tracking-widest drop-shadow-md">Sistem Informasi Terpadu</p>
                    </div>
                </div>

                <div class="max-w-lg">
                    <h2 class="text-4xl lg:text-5xl font-black mb-6 leading-tight">
                        SIMADU <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-white">TERINTEGRASI</span> <br>
                        Untuk Siswa & Guru
                    </h2>
                    <p class="text-slate-100 text-lg font-normal leading-relaxed opacity-90">
                       Silakan masuk untuk mengelola absensi, jadwal pelajaran, dan bank soal ujian dalam satu pintu.
                    </p>
                </div>
            </div>

            <div class="relative z-20 mt-12 flex items-center gap-3 text-xs font-bold text-elevate-accent uppercase tracking-widest animate-fade-in-up delay-200 bg-black/20 w-max px-4 py-2 rounded-full backdrop-blur-sm border border-white/10">
                <i class="ph-fill ph-shield-check text-lg"></i> Akses Terlindungi Sistem
            </div>
        </div>

        <!-- ================= BAGIAN KANAN (FORM LOGIN) ================= -->
        <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center items-center p-6 sm:p-12 relative z-20 min-h-screen">
            
            <!-- Mobile Header Logo -->
            <div class="md:hidden text-center mb-8 pt-4 animate-fade-in-up">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="w-20 h-20 rounded-[1.25rem] object-cover mx-auto mb-4 shadow-lg border border-white/30">
                <!-- PERBAIKAN: Teks diubah menjadi putih agar terlihat jelas di atas background gelap -->
                <h1 class="font-black text-2xl text-white tracking-tight drop-shadow-md">SIMADU LAKBOK</h1>
                <p class="text-xs text-elevate-accent font-bold uppercase tracking-widest mt-1 drop-shadow-md">Portal Guru & Admin</p>
            </div>

            <!-- Login Box -->
            <div class="w-full max-w-md animate-fade-in-up delay-200">
                <div class="bg-white/80 backdrop-blur-2xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-white p-8 sm:p-10 relative overflow-hidden">
                    
                    <!-- Decorative Corner -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-elevate-accent/10 to-transparent rounded-bl-[4rem] pointer-events-none"></div>

                    <div class="mb-8 relative z-10">
                        <h3 class="text-2xl font-black text-elevate-dark mb-1">Masuk ke Akun</h3>
                        <p class="text-sm font-medium text-slate-500">Masukkan Akses Login Anda untuk melanjutkan.</p>
                    </div>

                    <div class="relative z-10">
                        {{ $slot }}
                    </div>

                </div>

                <!-- Footer / Back Link -->
                <div class="mt-8 text-center pb-6 animate-fade-in-up delay-300">
                    <div class="flex flex-col items-center gap-3">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 md:text-slate-400 text-white/70 hover:text-white md:hover:text-elevate-primary transition-all group px-4 py-2 rounded-full md:hover:bg-slate-100/50 hover:bg-white/10">
                            <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            Kembali ke Beranda
                        </a>
                        
                        <!-- PERBAIKAN: Teks copyright mobile diubah lebih terang -->
                        <p class="md:hidden text-[11px] text-white/60 font-medium mt-4">
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