<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 selection:bg-blue-500 selection:text-white relative overflow-x-hidden">
        
        <!-- Background Decoration (Blobs) -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-400/10 blur-3xl animate-pulse"></div>
            <div class="absolute top-[40%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-400/10 blur-3xl animate-pulse" style="animation-delay: 2s"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- 
                PERBAIKAN TAMPILAN:
                1. Ubah 'max-w-5xl' menjadi 'max-w-4xl' agar kartu tidak terlalu lebar.
                2. Shadow diperhalus agar lebih elegan.
            --}}
            <div class="max-w-4xl w-full mx-auto bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.15)] rounded-3xl overflow-hidden flex flex-col md:flex-row border border-slate-100">

                <!-- KOLOM KIRI (BRANDING) - Lebar disesuaikan (md:w-5/12) agar seimbang -->
                <div class="w-full md:w-5/12 bg-blue-700 relative flex flex-col justify-between p-8 text-white overflow-hidden shrink-0">
                    
                    <!-- Background Gradients -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 z-0"></div>
                    
                    <!-- Pattern CSS -->
                    <div class="absolute inset-0 opacity-10 z-0" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
                    
                    <!-- Abstract Circles -->
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/5 blur-2xl z-0 border border-white/10"></div>
                    <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 rounded-full bg-indigo-500/30 blur-2xl z-0"></div>

                    <!-- Content -->
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        {{-- Header Logo --}}
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="bg-white/10 p-2 rounded-xl backdrop-blur-md border border-white/20 shadow-lg">
                                <img src="{{ asset('images/logo.png') }}" class="w-8 h-8 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <i class="ph-fill ph-graduation-cap text-2xl text-white" style="display: none;"></i>
                            </div>
                            <div>
                                <span class="block text-base font-extrabold tracking-tight leading-none">SMP NEGERI 3</span>
                                <span class="block text-[10px] font-bold tracking-widest text-blue-200 uppercase">LAKBOK</span>
                            </div>
                        </div>
                        
                        {{-- Middle Content --}}
                        <div class="my-auto py-6">
                            <h2 class="text-2xl md:text-3xl font-extrabold mb-3 leading-tight">
                                Dashboard <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Guru & Staff</span>
                            </h2>
                            
                            <p class="hidden sm:block text-blue-100/90 text-xs leading-relaxed mb-6">
                                Kelola presensi, nilai, dan administrasi sekolah dalam satu pintu.
                            </p>
                            
                            <!-- Features List (Simplified) -->
                            <div class="hidden md:flex flex-col gap-2">
                                <div class="flex items-center gap-2 text-xs text-blue-50 font-medium">
                                    <i class="ph-fill ph-check-circle text-green-300 text-lg"></i>
                                    <span>Monitoring Real-time</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-blue-50 font-medium">
                                    <i class="ph-fill ph-check-circle text-green-300 text-lg"></i>
                                    <span>Rekap Otomatis</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Footer Link --}}
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <a href="/" class="group inline-flex items-center text-xs font-bold text-blue-200 hover:text-white transition-colors">
                                <i class="ph-bold ph-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                                Kembali ke Website
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (LOGIN FORM) - Lebar diperbesar (md:w-7/12) -->
                <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-white relative">
                    <div class="w-full max-w-sm mx-auto">
                        <div class="mb-8">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 mb-4">
                                <i class="ph-duotone ph-sign-in text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">Selamat Datang!</h3>
                            <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk melanjutkan akses.</p>
                        </div>

                        {{-- Slot Form Login --}}
                        {{ $slot }}
                        
                        <div class="mt-8 text-center">
                             <p class="text-[10px] text-slate-400 font-medium">&copy; {{ date('Y') }} SMP Negeri 3 Lakbok.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>