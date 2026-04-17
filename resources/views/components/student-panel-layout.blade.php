<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Siswa') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 4px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">

    <!-- 1. INCLUDE SIDEBAR SISWA -->
    @include('layouts.student-sidebar')

    <!-- 2. OVERLAY MOBILE -->
    <div x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="transition-opacity ease-linear duration-300" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 bg-blue-950/80 backdrop-blur-sm z-40 lg:hidden" 
            @click="sidebarOpen = false">
    </div>

    <!-- 3. KONTEN UTAMA -->
    <div class="lg:pl-72 flex flex-col min-h-screen transition-all duration-300 relative overflow-hidden">
        
        <!-- Background Decoration (Opsional untuk menyamakan nuansa cerah) -->
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-cyan-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

        <!-- HEADER MOBILE & TABLET (Sticky Top Tema Elevate) -->
        <header class="bg-gradient-to-r from-cyan-600 to-blue-700 sticky top-0 z-30 border-b border-blue-500/50 px-4 py-3 flex lg:hidden justify-between items-center shadow-md">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-cyan-200 focus:outline-none p-2 rounded-lg hover:bg-white/10 transition-colors">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <span class="font-bold text-white text-lg">Portal Siswa</span>
            </div>
            
            <!-- Avatar Kecil Mobile -->
            <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm overflow-hidden border border-white/30 shadow-inner">
                @if(Auth::guard('student')->user()->photo_path)
                    <img src="{{ asset('storage/' . Auth::guard('student')->user()->photo_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-xs font-bold text-white">
                        {{ substr(Auth::guard('student')->user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
        </header>

        <!-- MAIN CONTENT SLOT -->
        <main class="flex-1 p-4 md:p-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <!-- Slot untuk konten halaman -->
                {{ $slot ?? '' }}
            </div>
        </main>
        
        <!-- FOOTER SIMPLE -->
        <footer class="p-6 text-center text-xs text-slate-400 font-medium relative z-10">
            &copy; {{ date('Y') }} SMPN 3 Lakbok. <span class="text-blue-500">Portal Akademik Siswa.</span>
        </footer>
    </div>

</body>
</html>