<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{-- Latar belakang halaman --}}
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-100">
            
            {{-- Kontainer Kartu Utama (Dua Kolom) --}}
            <div class="max-w-4xl w-full mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row">

                <!-- KOLOM KIRI (BIRU) -->
                <div class="w-full md:w-2/5 bg-blue-600 p-8 text-white flex flex-col justify-between">
                    <div>
                        <!-- Logo dan Judul Sekolah -->
                        <div class="flex items-center space-x-3 mb-4">
                            <x-application-logo class="w-10 h-10 text-white" />
                            <span class="text-xl font-bold">SMP NEGERI 3 LAKBOK</span>
                        </div>
                        
                        <p class="text-sm font-light text-blue-100">
                            Sistem manajemen kehadiran siswa terintegrasi untuk efisiensi administrasi sekolah.
                        </p>
                    </div>

                    <!-- Akses Publik -->
                    <div class="mt-8">
                        <h3 class="text-xs font-semibold uppercase text-blue-200 mb-3">Akses publik tersedia:</h3>
                        <div class="space-y-3">
                            {{-- Tombol Portal Siswa --}}
                            <a href="{{ route('portal.index') }}" class="flex items-center px-4 py-3 bg-blue-500 hover:bg-blue-400 rounded-lg text-sm font-medium transition-colors">
                                <x-icon-user-portal class="w-5 h-5 mr-3"/>
                                Portal Siswa
                            </a>
                            {{-- Tombol Mode Kiosk --}}
                            <a href="{{ route('kiosk.show') }}" class="flex items-center px-4 py-3 bg-blue-500 hover:bg-blue-400 rounded-lg text-sm font-medium transition-colors">
                                <x-icon-kiosk-mode class="w-5 h-5 mr-3"/>
                                Mode KiosK
                            </a>
                            {{-- Tombol Halaman Utama --}}
                            <a href="#" class="flex items-center px-4 py-3 bg-blue-500 hover:bg-blue-400 rounded-lg text-sm font-medium transition-colors">
                                <x-icon-home-page class="w-5 h-5 mr-3"/>
                                Halaman Utama
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (PUTIH) -->
                <div class="w-full md:w-3/5 p-8 md:p-12">
                    {{-- Di sinilah file login.blade.php akan dimuat --}}
                    {{ $slot }}
                </div>

            </div>
        </div>
    </body>
</html>