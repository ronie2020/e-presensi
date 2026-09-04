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
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="SIMADU Lakbok">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])        
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            
            /* Efek Grid Halus untuk Background Area Kanan/Mobile */
            .bg-grid-pattern {
                background-image: linear-gradient(to right, rgba(13, 82, 161, 0.03) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(13, 82, 161, 0.03) 1px, transparent 1px);
                background-size: 32px 32px;
            }

            /* Animasi Masuk Halus */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0; 
            }
            
            /* Delay Class Utils */
            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
            .delay-400 { animation-delay: 400ms; }
        </style>
    </head>
    
    <body class="text-elevate-dark antialiased min-h-screen bg-slate-50 bg-grid-pattern relative overflow-x-hidden flex flex-col md:flex-row selection:bg-elevate-primary selection:text-white">
        
        <!-- DEKORASI BACKGROUND KANAN (Desktop) -->
        <div class="hidden md:block absolute -top-32 -left-32 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[100px] pointer-events-none z-0"></div>
        <div class="hidden md:block absolute bottom-0 right-0 w-[600px] h-[600px] bg-elevate-primary/5 rounded-tl-[100%] pointer-events-none z-0"></div>

        <!-- BACKGROUND KHUSUS MOBILE (Mode Gelap Transparan) -->
        <div class="md:hidden absolute inset-0 z-0 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-elevate-dark/90 via-elevate-dark/80 to-slate-900/95 backdrop-blur-sm"></div>
        </div>

        <main class="flex w-full min-h-screen relative z-10 flex-col md:flex-row">
            
            <!-- ================= BAGIAN KIRI (FOTO SEKOLAH & TEKS DESKTOP) ================= -->
            <section class="hidden md:flex md:w-1/2 lg:w-3/5 p-12 lg:p-16 text-white flex-col justify-between relative shadow-[20px_0_40px_rgba(0,0,0,0.15)] z-20 rounded-br-[4rem] min-h-screen overflow-hidden group">
                
                <!-- Background Image -->
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-105" 
                     style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
                
                <!-- Overlay Gradien yang lebih solid agar teks mudah dibaca -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#032b5b]/95 via-[#032b5b]/80 to-[#032b5b]/30 z-0"></div>

                <!-- Konten Teks Kiri -->
                <div class="relative z-20 animate-fade-in-up delay-100">
                    <div class="flex items-center gap-4 mb-16">
                        <div class="p-1.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Netila" class="w-12 h-12 object-contain">
                        </div>
                        <div>
                            <h1 class="font-black text-2xl tracking-tight leading-none drop-shadow-md">SMP NEGERI 3 LAKBOK</h1>
                            <p class="text-[11px] text-elevate-accent font-black uppercase tracking-widest drop-shadow-md mt-1">Sistem Informasi Terpadu</p>
                        </div>
                    </div>

                    <div class="max-w-xl">
                        <h2 class="text-4xl lg:text-6xl font-black mb-6 leading-tight tracking-tight">
                            SIMADU <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#85d1f8] to-white">TERINTEGRASI</span> <br>
                            Untuk Siswa & Guru
                        </h2>
                        <p class="text-slate-200 text-lg lg:text-xl font-medium leading-relaxed max-w-md">
                           Satu pintu masuk untuk absensi, manajemen jadwal pelajaran, bank soal, dan administrasi sekolah.
                        </p>
                    </div>
                </div>

                <!-- Lencana Keamanan Bawah -->
                <div class="relative z-20 mt-12 flex items-center gap-3 text-xs font-bold text-elevate-accent uppercase tracking-widest animate-fade-in-up delay-200 bg-white/5 w-max px-5 py-3 rounded-2xl backdrop-blur-md border border-white/10 shadow-lg">
                    <i class="ph-duotone ph-shield-check text-xl"></i> Akses Terlindungi Sistem
                </div>
            </section>

            <!-- ================= BAGIAN KANAN (FORM LOGIN) ================= -->
            <section class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center items-center p-6 sm:p-12 relative z-20 min-h-screen">
                
                <!-- Glowing effect khusus desktop di belakang form -->
                <div class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-white/50 rounded-full blur-[100px] pointer-events-none -z-10"></div>

                <!-- Mobile Header Logo -->
                <div class="md:hidden text-center mb-10 pt-8 animate-fade-in-up">
                    <div class="w-24 h-24 mx-auto mb-5 p-2 bg-white/10 backdrop-blur-md rounded-[1.5rem] border border-white/20 shadow-xl">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                    </div>
                    <h1 class="font-black text-3xl text-white tracking-tight drop-shadow-md">SIMADU LAKBOK</h1>
                    <p class="text-[11px] text-elevate-accent font-black uppercase tracking-widest mt-2 drop-shadow-md bg-white/10 w-fit mx-auto px-4 py-1.5 rounded-full border border-white/10">Portal Guru & Admin</p>
                </div>

                <!-- Login Box -->
                <div class="w-full max-w-[420px] animate-fade-in-up delay-200 relative">
                    
                    <!-- Kotak Glassmorphism -->
                    <div class="bg-white/90 md:bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(13,82,161,0.1)] border border-white p-8 sm:p-10 relative overflow-hidden group">
                        
                        <!-- Decorative Top-Right gradient -->
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-gradient-to-br from-elevate-accent/20 to-transparent rounded-full blur-xl pointer-events-none transition-all duration-500 group-hover:scale-150 group-hover:from-elevate-accent/30"></div>

                        <!-- Header Form -->
                        <div class="mb-8 relative z-10 text-center md:text-left">
                            <h3 class="text-2xl lg:text-3xl font-black text-elevate-dark mb-2">Masuk ke Akun</h3>
                            <p class="text-sm font-semibold text-slate-500">Masukkan kredensial Anda untuk melanjutkan.</p>
                        </div>

                        <!-- SLOT UNTUK FORM (Input Email & Password) -->
                        <div class="relative z-10 w-full">
                            {{ $slot }}
                        </div>

                    </div>

                    <!-- Footer / Back Link -->
                    <div class="mt-8 text-center pb-6 animate-fade-in-up delay-300">
                        <div class="flex flex-col items-center gap-4">
                            <!-- Tombol Kembali -->
                            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white/80 md:text-slate-500 hover:text-white md:hover:text-elevate-primary transition-all group px-5 py-2.5 rounded-full md:hover:bg-slate-200/50 hover:bg-white/10 md:bg-transparent bg-white/5 border border-transparent md:hover:border-slate-200 hover:border-white/10">
                                <div class="w-6 h-6 rounded-full bg-white/10 md:bg-slate-100 flex items-center justify-center group-hover:bg-white md:group-hover:bg-elevate-soft md:group-hover:text-elevate-primary transition-colors text-current">
                                    <i class="ph-bold ph-arrow-left"></i>
                                </div>
                                Kembali ke Beranda
                            </a>
                            
                            <!-- Copyright (Hanya Muncul di Mobile) -->
                            <p class="md:hidden text-[11px] text-white/50 font-semibold mt-2 tracking-wide">
                                &copy; {{ date('Y') }} SMP Negeri 3 Lakbok.
                            </p>
                        </div>
                    </div>
                </div>

            </section>
        </main>

        <!-- ============================================== -->
        <!-- PWA SERVICE WORKER REGISTRATION (KHUSUS GURU)  -->
        <!-- ============================================== -->
        <script>
            // Pastikan dijalankan di environment yang aman (HTTPS atau localhost)
            if ('serviceWorker' in navigator && window.isSecureContext) {
                window.addEventListener('load', () => {                 
                    navigator.serviceWorker.register('/sw-guru.js')
                        .then(registration => {
                            console.log('PWA Service Worker (Guru) berhasil didaftarkan.');
                        })
                        .catch(error => {
                            console.warn('PWA Service Worker (Guru) gagal didaftarkan:', error);
                        });
                });
            }
        </script>
    </body>
</html>