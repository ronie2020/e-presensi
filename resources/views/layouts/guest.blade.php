<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            
            {{-- Card Login --}}
            <div class="max-w-4xl w-full mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row min-h-[500px]">

                <!-- KOLOM KIRI (BIRU) - Hanya Identitas Sekolah -->
                <div class="w-full md:w-2/5 bg-blue-600 p-8 text-white flex flex-col justify-between relative overflow-hidden">
                    <!-- Dekorasi Latar Belakang -->
                    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                        <svg width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="0" cy="0" r="200" fill="white"/></svg>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center space-x-3 mb-6">
                            <x-application-logo class="w-12 h-12 text-white" />
                            <div>
                                <span class="block text-xl font-bold leading-tight">SMP NEGERI 3</span>
                                <span class="block text-xl font-bold leading-tight">LAKBOK</span>
                            </div>
                        </div>
                        
                        <p class="text-blue-100 text-sm leading-relaxed mb-6">
                            Halaman ini adalah akses khusus untuk Guru dan Staf Administrasi.
                        </p>
                        
                        <div class="border-t border-blue-400/50 pt-6">
                            <p class="text-blue-200 text-xs mb-2">Ingin mengakses portal siswa atau mesin absensi?</p>
                            <a href="{{ route('landing') }}" class="inline-flex items-center font-semibold text-white hover:text-blue-200 transition group">
                                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                Kembali ke Halaman Utama
                            </a>
                        </div>
                    </div>
                    
                    <div class="relative z-10 mt-auto text-xs text-blue-300">
                        &copy; {{ date('Y') }} Tim IT SMPN 3 Lakbok @Ri..
                    </div>
                </div>

                <!-- KOLOM KANAN (LOGIN FORM) -->
                <div class="w-full md:w-3/5 p-8 md:p-12 flex items-center justify-center bg-white">
                    <div class="w-full max-w-md">
                        {{-- Slot ini akan diisi oleh konten login.blade.php --}}
                        {{ $slot }}
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>