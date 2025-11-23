<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Netila E-Presensi') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Kustomisasi Scrollbar agar lebih tipis dan rapi */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>
    <body class="font-sans antialiased bg-blue-700 selection:bg-yellow-300 selection:text-blue-900">
        
        <div x-data="{ sidebarOpen: false }" class="h-screen flex overflow-hidden">
            
            <!-- ====== SIDEBAR NAVIGASI (Background Biru) ====== -->
            @include('layouts.navigation')

            <!-- Overlay Mobile -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-blue-900/80 backdrop-blur-sm z-40 md:hidden" 
                 @click="sidebarOpen = false">
            </div>

            <!-- ====== KONTEN UTAMA (Kertas Putih) ====== -->
            <div class="flex-1 flex flex-col h-screen relative z-10 transition-all duration-300">
                
                <!-- Container Melengkung (Hanya di Desktop) -->
                <div class="flex-1 bg-gray-50 md:rounded-l-[2.5rem] md:my-2 md:mr-2 overflow-hidden flex flex-col shadow-[0_0_40px_-10px_rgba(0,0,0,0.2)] relative">

                    <!-- Header (Sticky & Blur) -->
                    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                        
                        <!-- Tombol Hamburger & Judul -->
                        <div class="flex items-center gap-4">
                            <button @click="sidebarOpen = true" class="md:hidden text-gray-500 hover:text-blue-600 focus:outline-none transition-colors p-1 rounded-lg hover:bg-blue-50">
                                <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <!-- Header Title (Dari Slot) -->
                            @if (isset($header))
                                <div class="text-2xl font-bold text-gray-800 tracking-tight">
                                    {{ $header }}
                                </div>
                            @endif
                        </div>

                        <!-- User Dropdown & Info -->
                        <div class="flex items-center gap-4">
                            <!-- Jam/Tanggal (Desktop Only) -->
                            <div class="hidden md:block text-right mr-2">
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Hari Ini</p>
                                <p class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                            </div>

                            <!-- Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition-colors">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->role ?? 'User' }}</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center text-blue-600 font-bold text-lg overflow-hidden">
                                        <!-- Avatar Initials -->
                                        <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=2563eb&background=dbeafe&bold=true" alt="Avatar">
                                    </div>
                                </button>

                                <!-- Dropdown Content -->
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50 origin-top-right"
                                     style="display: none;">
                                    
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                                        {{ __('Profile') }}
                                    </a>
                                    
                                    <div class="border-t border-gray-100 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); this.closest('form').submit();"
                                           class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-medium">
                                            {{ __('Log Out') }}
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Main Scrollable Content -->
                    <main class="flex-1 overflow-y-auto p-6 md:p-8 scroll-smooth">
                        <div class="max-w-7xl mx-auto">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>