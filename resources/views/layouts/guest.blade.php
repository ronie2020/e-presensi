<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <!-- PERBAIKAN 1: Hapus 'overflow-hidden', ganti 'overflow-x-hidden' agar bisa scroll vertikal -->
    <body class="font-sans text-gray-900 antialiased bg-gray-50 selection:bg-blue-500 selection:text-white relative overflow-x-hidden">
        
        <!-- Background Decoration (Blobs) -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-200/20 blur-3xl"></div>
            <div class="absolute top-[40%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-200/20 blur-3xl"></div>
        </div>

        <!-- PERBAIKAN 2: Gunakan 'min-h-screen' pada wrapper dan padding yang pas -->
        <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Card Container --}}
            <!-- PERBAIKAN 3: 'min-h-[600px]' tetap ada, tapi biarkan height auto agar fleksibel di mobile -->
            <div class="max-w-5xl w-full mx-auto bg-white shadow-2xl shadow-blue-900/10 rounded-3xl overflow-hidden flex flex-col md:flex-row border border-white/50">

                <!-- KOLOM KIRI (BRANDING) -->
                <!-- PERBAIKAN 4: Padding dikurangi di mobile (p-6) agar tidak terlalu tinggi -->
                <div class="w-full md:w-1/2 bg-blue-800 relative flex flex-col justify-between p-6 md:p-12 text-white overflow-hidden shrink-0">
                    
                    <!-- Background Gradients & Patterns -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 z-0"></div>
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] z-0"></div>
                    
                    <!-- Abstract Shapes Decoration -->
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/5 blur-2xl z-0"></div>
                    <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 rounded-full bg-indigo-500/30 blur-2xl z-0"></div>

                    <!-- Content -->
                    <div class="relative z-10 h-full flex flex-col">
                        {{-- Header Logo --}}
                        <div class="flex items-center space-x-3 mb-6 md:mb-8">
                            <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm border border-white/10 shadow-lg">
                                <x-application-logo class="w-8 h-8 md:w-10 md:h-10 text-white fill-current" />
                            </div>
                            <div>
                                <span class="block text-base md:text-lg font-extrabold tracking-tight leading-none">SMP NEGERI 3</span>
                                <span class="block text-base md:text-lg font-extrabold tracking-tight leading-none text-blue-200">LAKBOK</span>
                            </div>
                        </div>
                        
                        {{-- Middle Content --}}
                        <!-- PERBAIKAN 5: Sembunyikan teks deskripsi panjang di mobile agar user fokus login -->
                        <div class="my-auto">
                            <h2 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4 leading-tight">
                                Selamat Datang di <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Dashboard Guru</span>
                            </h2>
                            
                            {{-- Deskripsi ini hanya muncul di layar Tablet ke atas (md:block) --}}
                            <p class="hidden md:block text-blue-100/90 text-sm leading-relaxed mb-8 max-w-xs">
                                Silakan masuk untuk mengelola data kehadiran, rekapitulasi nilai, dan administrasi siswa secara terpadu.
                            </p>
                            
                            <!-- Mini Feature List (Visual Only - Hidden on small mobile) -->
                            <div class="hidden sm:block space-y-3 mt-4 md:mt-0">
                                <div class="flex items-center gap-3 text-sm text-blue-100 font-medium bg-blue-900/30 p-2.5 rounded-lg border border-blue-500/20 backdrop-blur-sm">
                                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Monitoring Real-time</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-blue-100 font-medium bg-blue-900/30 p-2.5 rounded-lg border border-blue-500/20 backdrop-blur-sm">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    <span>Keamanan Data Terjamin</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Footer Link --}}
                        <!-- PERBAIKAN 6: Di mobile, link ini dibuat lebih ringkas -->
                        <div class="mt-6 md:mt-8 pt-6 border-t border-blue-500/30">
                            <a href="/" class="group inline-flex items-center text-sm font-semibold text-white hover:text-blue-200 transition-colors">
                                <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center mr-3 group-hover:bg-white/20 transition-all">
                                    <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                </span>
                                <span class="hidden md:inline">Kembali ke Halaman Utama</span>
                                <span class="md:hidden">Ke Halaman Utama</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (LOGIN FORM) -->
                <div class="w-full md:w-1/2 p-6 md:p-14 flex flex-col justify-center bg-white relative">
                    
                    {{-- Form Wrapper --}}
                    <div class="w-full max-w-sm mx-auto">
                        <div class="mb-6 md:mb-8">
                            <h3 class="text-2xl font-bold text-gray-900">Sign In</h3>
                            <p class="text-gray-500 text-sm mt-1">Masukkan kredensial akun Anda.</p>
                        </div>

                        {{-- Slot (Form Login dari login.blade.php akan masuk sini) --}}
                        {{ $slot }}
                        
                        <div class="mt-8 text-center">
                             <p class="text-xs text-gray-400">&copy; {{ date('Y') }} SMP Negeri 3 Lakbok.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </body>
</html>