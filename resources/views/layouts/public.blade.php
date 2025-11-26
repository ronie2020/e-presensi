<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token dari file lama Anda -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }} - Portal Siswa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Stack Styles (Jika ada CSS tambahan dari view) -->
    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 min-h-screen flex flex-col">

    <!-- NAVBAR ATAS (Pengganti Sidebar) -->
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Logo & Judul -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="h-9 w-9 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                            <x-application-logo class="h-6 w-6 fill-current" />
                        </div>
                        <div class="flex flex-col">
                            <span class="font-black text-gray-800 leading-tight tracking-tight">PORTAL SISWA</span>
                            <span class="text-[10px] font-bold text-gray-500 tracking-widest">SMPN 3 LAKBOK</span>
                        </div>
                    </a>
                </div>

                <!-- Menu Kanan -->
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50">
                        <i class="ph-bold ph-house text-lg"></i>
                        <span class="hidden sm:inline">Beranda</span>
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition px-4 py-2 rounded-lg shadow-sm shadow-blue-200">
                            Dashboard Guru
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT (Tengah) -->
    <main class="flex-grow flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-white to-transparent -z-10"></div>
        
        <div class="w-full max-w-6xl relative z-10">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xs text-gray-400 font-medium">
                &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Sistem Informasi Sekolah Terpadu.
            </p>
        </div>
    </footer>

    <!-- Stack Scripts (Jika ada JS tambahan dari view) -->
    @stack('scripts')
</body>
</html>