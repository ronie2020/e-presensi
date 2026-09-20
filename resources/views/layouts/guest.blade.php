<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'SIMADU Lakbok') }}</title>
        
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
            
            /* Efek Grid Halus untuk Background Gelap */
            .bg-grid-dark {
                background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
                background-size: 32px 32px;
            }

            /* Concept Mockup Portal & Floating Badge Animations */
            @keyframes portalPulse {
                0%, 100% {
                    box-shadow: 0 0 35px rgba(86, 187, 241, 0.4), 0 0 70px rgba(13, 82, 161, 0.3);
                    transform: scale(1);
                }
                50% {
                    box-shadow: 0 0 55px rgba(86, 187, 241, 0.7), 0 0 95px rgba(13, 82, 161, 0.45);
                    transform: scale(1.02);
                }
            }
            .animate-portal-pulse {
                animation: portalPulse 4s ease-in-out infinite;
            }

            @keyframes floatSlow {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-8px) rotate(1deg); }
            }
            .animate-float-badge {
                animation: floatSlow 4s ease-in-out infinite;
            }

            /* Styling Slot Breeze Form agar Menyatu dengan Tema Kaca */
            .auth-slot input[type="text"],
            .auth-slot input[type="email"],
            .auth-slot input[type="password"] {
                background-color: rgba(0, 0, 0, 0.25) !important;
                border-color: rgba(255, 255, 255, 0.2) !important;
                color: #ffffff !important;
                border-radius: 0.75rem !important;
                font-size: 0.8125rem !important;
            }
            .auth-slot input[type="text"]:focus,
            .auth-slot input[type="email"]:focus,
            .auth-slot input[type="password"]:focus {
                border-color: #56bbf1 !important;
                box-shadow: 0 0 0 2px rgba(86, 187, 241, 0.3) !important;
                outline: none !important;
            }
            .auth-slot label {
                color: #cbd5e1 !important;
                font-weight: 800 !important;
                font-size: 0.6875rem !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
            }
            .auth-slot button[type="submit"] {
                background-color: #56bbf1 !important;
                color: #021124 !important;
                font-weight: 900 !important;
                border-radius: 0.75rem !important;
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                font-size: 0.75rem !important;
                box-shadow: 0 4px 20px rgba(86, 187, 241, 0.3) !important;
                transition: all 0.2s ease !important;
            }
            .auth-slot button[type="submit"]:hover {
                background-color: #72cbfa !important;
                box-shadow: 0 6px 25px rgba(86, 187, 241, 0.45) !important;
            }
        </style>
    </head>
    
    <body class="text-white antialiased min-h-screen bg-[#021124] bg-grid-dark relative overflow-x-hidden flex flex-col md:flex-row selection:bg-elevate-accent selection:text-[#021124]">
        
        <!-- DEKORASI BACKGROUND KANAN & KIRI (Elevate Ambient Orbs) -->
        <div class="fixed -top-32 -left-32 w-[550px] h-[550px] bg-elevate-accent/15 rounded-full blur-[140px] pointer-events-none z-0"></div>
        <div class="fixed bottom-0 right-0 w-[600px] h-[600px] bg-elevate-primary/20 rounded-full blur-[150px] pointer-events-none z-0"></div>

        <!-- BACKGROUND KHUSUS MOBILE (Mode Gelap Transparan) -->
        <div class="md:hidden absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#021124]/95 via-[#021124]/90 to-[#021124] backdrop-blur-md"></div>
        </div>

        <main class="flex w-full min-h-screen relative z-10 flex-col md:flex-row">
            
            <!-- ================= BAGIAN KIRI (KONSEP MOCKUP: PORTAL & INFORMASI) ================= -->
            <section class="hidden md:flex md:w-1/2 lg:w-3/5 p-12 lg:p-16 text-white flex-col justify-between relative shadow-[20px_0_40px_rgba(0,0,0,0.4)] z-20 rounded-br-[4rem] min-h-screen overflow-hidden group border-r border-white/10">
                
                <!-- Background Image & Gelombang -->
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-105" 
                     style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
                
                <!-- Overlay Gradien -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#021124]/95 via-[#021124]/88 to-[#021124]/70 z-0"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#021124] via-transparent to-transparent opacity-80 z-0"></div>

                <!-- Konten Atas -->
                <div class="relative z-20">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-2 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-[0_0_20px_rgba(86,187,241,0.2)] shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Netila" class="w-12 h-12 object-contain" onerror="this.src='/images/logo.png'">
                        </div>
                        <div>
                            <h1 class="font-black text-2xl tracking-tight leading-none drop-shadow-md text-white">SMP NEGERI 3 LAKBOK</h1>
                            <p class="text-[11px] text-elevate-accent font-black uppercase tracking-widest drop-shadow-md mt-1">Sistem Informasi Terpadu</p>
                        </div>
                    </div>

                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-elevate-accent/20 border border-elevate-accent/30 text-elevate-accent text-[11px] font-black uppercase tracking-widest mb-6 backdrop-blur-sm">
                            <i class="ph-fill ph-sparkle text-sm"></i> Portal Presensi &amp; E-Learning
                        </div>
                        <h2 class="text-4xl lg:text-5xl font-black mb-4 leading-tight tracking-tight text-white">
                            SIMADU <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#85d1f8] to-white">TERINTEGRASI</span> <br>
                            Untuk Guru &amp; Siswa
                        </h2>
                        <p class="text-slate-300 text-sm lg:text-base font-normal leading-relaxed max-w-md mb-6">
                            Satu pintu masuk untuk absensi GPS, manajemen ruang belajar LMS, bank soal CBT, dan administrasi sekolah.
                        </p>

                        <!-- 4 CHECKLIST PILLS (Sesuai Konsep Mockup) -->
                        <div class="grid grid-cols-2 gap-2 max-w-md">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                                <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                                <span>Materi Modul LMS</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                                <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                                <span>Praktik &amp; Tugas</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                                <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                                <span>Ujian CBT Online</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-200 bg-white/5 px-3 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
                                <i class="ph-bold ph-check text-elevate-accent text-sm shrink-0"></i>
                                <span>Akses Terpadu Resmi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Bawah: Badges Info Keamanan -->
                <div class="relative z-20 mt-10 flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2.5 text-xs font-bold text-elevate-accent uppercase tracking-widest bg-white/5 px-4 py-2.5 rounded-2xl backdrop-blur-md border border-white/10 shadow-lg">
                        <i class="ph-fill ph-shield-check text-lg"></i> Autentikasi Resmi Terenkripsi
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-300 bg-white/5 px-4 py-2.5 rounded-2xl backdrop-blur-md border border-white/10 shadow-lg">
                        <i class="ph-fill ph-device-mobile text-elevate-peach text-lg"></i> Akses Cepat PWA
                    </div>
                </div>
            </section>

            <!-- ================= BAGIAN KANAN (FORM CONTAINER $slot) ================= -->
            <section class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center items-center p-6 sm:p-12 relative z-20 min-h-screen">
                
                <!-- Mobile Header Logo -->
                <div class="md:hidden text-center mb-8 pt-6">
                    <div class="w-20 h-20 mx-auto mb-4 p-2 bg-white/10 backdrop-blur-xl rounded-[1.5rem] border border-white/20 shadow-[0_0_25px_rgba(86,187,241,0.25)]">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                    </div>
                    <h1 class="font-black text-2xl text-white tracking-tight drop-shadow-md">SIMADU LAKBOK</h1>
                    <p class="text-[10px] text-elevate-accent font-black uppercase tracking-widest mt-1.5 drop-shadow-md bg-elevate-accent/15 w-fit mx-auto px-3.5 py-1 rounded-full border border-elevate-accent/30">Portal Siswa &amp; Guru</p>
                </div>

                <!-- Box Container -->
                <div class="w-full max-w-[440px] relative z-20">
                    
                    <!-- Kotak Glassmorphism Modern (Persis Konsep Mockup) -->
                    <div class="bg-[#031d3d]/90 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_12px_45px_rgba(0,0,0,0.6)] border border-white/20 p-8 sm:p-10 relative overflow-hidden group">
                        
                        <!-- Glowing Accent Line di atas kartu -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-elevate-accent to-transparent opacity-80"></div>

                        <!-- Decorative Top-Right gradient -->
                        <div class="absolute -top-16 -right-16 w-40 h-40 bg-elevate-accent/15 rounded-full blur-2xl pointer-events-none"></div>
                        
                        <!-- Decorative Bottom-Left gradient -->
                        <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-elevate-primary/20 rounded-full blur-2xl pointer-events-none"></div>

                        <!-- Header Form -->
                        <div class="mb-6 relative z-10 text-center md:text-left">
                            <h3 class="text-2xl font-black text-white tracking-tight mb-1">Akses Akun</h3>
                            <p class="text-xs sm:text-sm font-medium text-slate-300">Silakan lengkapi formulir di bawah ini.</p>
                        </div>

                        <!-- SLOT FORM -->
                        <div class="relative z-10 w-full auth-slot">
                            {{ $slot }}
                        </div>

                    </div>

                    <!-- Footer & 4-Dots Indicator (Persis Konsep Mockup) -->
                    <div class="mt-8 text-center pb-6 flex flex-col items-center gap-4">
                        <!-- 4 Dots Indicator -->
                        <div class="flex items-center gap-1.5">
                            <span class="w-6 h-2 rounded-full bg-elevate-accent shadow-[0_0_10px_rgba(86,187,241,0.7)]"></span>
                            <span class="w-2 h-2 rounded-full bg-white/20"></span>
                            <span class="w-2 h-2 rounded-full bg-white/20"></span>
                            <span class="w-2 h-2 rounded-full bg-white/20"></span>
                        </div>

                        <!-- Tombol Kembali -->
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-300 hover:text-white transition-all group px-5 py-2 rounded-full bg-white/5 hover:bg-white/10 border border-white/15 hover:border-elevate-accent/40 shadow-sm backdrop-blur-md">
                            <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform text-elevate-accent"></i>
                            Kembali ke Beranda
                        </a>
                        
                        <!-- Copyright -->
                        <p class="text-[11px] text-slate-400 font-medium tracking-wide">
                            &copy; {{ date('Y') }} SMP Negeri 3 Lakbok &bull; Terakreditasi A
                        </p>
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