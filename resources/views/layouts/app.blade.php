<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMADU-Netila') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
            /* Custom Scrollbar Elevate */
            ::-webkit-scrollbar { width: 8px; height: 8px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid transparent; background-clip: padding-box; }
            /* Menggunakan warna elevate-accent (#38bdf8) untuk hover scrollbar */
            ::-webkit-scrollbar-thumb:hover { background: #38bdf8; border: 2px solid transparent; background-clip: padding-box; }
            
            .sidebar-scroll::-webkit-scrollbar { width: 4px; }
            .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
            .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(56,189,248,0.5); }
        </style>
        
        @stack('styles')
    </head>
     <!-- Background Gradient Elevate dipasang langsung di body -->
    <body class="font-sans antialiased bg-elevate-gradient-main text-elevate-text min-h-screen">
        
       <!-- INITIALIZE STATE -->        
        <div x-data="{ 
                sidebarOpen: false, 
                sidebarExpanded: localStorage.getItem('sidebarExpanded') === null ? true : localStorage.getItem('sidebarExpanded') === 'true',
                toggleSidebar() {
                    this.sidebarExpanded = !this.sidebarExpanded;
                    localStorage.setItem('sidebarExpanded', this.sidebarExpanded);
                }
            }" 
            class="h-screen flex overflow-hidden bg-transparent">
            
            <!-- ====== SIDEBAR NAVIGASI ====== -->            
            @include('layouts.navigation')

            <!-- Overlay Mobile -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-40 md:hidden" 
                 @click="sidebarOpen = false"
                 x-cloak>
            </div>

            <!-- ====== KONTEN UTAMA ====== -->
            <!-- Flex-1 memastikan konten ini mengisi sisa ruang di sebelah sidebar -->
            <div class="flex-1 flex flex-col h-screen relative z-10 overflow-hidden transition-all duration-300">
                
                <!-- Header (ELEVATE GLASSMORPHISM) -->
                <header class="bg-white/70 backdrop-blur-xl sticky top-0 z-30 border-b border-slate-200/60 px-6 py-4 flex justify-between items-center shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)]">
                    
                     <div class="flex items-center gap-4">
                        <!-- Tombol Hamburger Mobile -->
                         <button @click="sidebarOpen = true" class="md:hidden text-slate-500 hover:text-elevate-primary focus:outline-none transition-colors p-1.5 rounded-xl hover:bg-white/50 border border-transparent hover:border-slate-200">
                            <i class="ph-bold ph-list text-2xl"></i>
                        </button>

                        @if (isset($header))
                            <div class="text-xl md:text-2xl font-bold text-elevate-dark tracking-tight hidden sm:block">
                                {{ $header }}
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="flex items-center gap-6">
                       <div class="hidden md:block text-right border-r border-slate-200 pr-6">
                            <p class="text-[10px] font-bold text-elevate-accent uppercase tracking-wider">Tanggal</p>
                            <p class="text-sm font-bold text-elevate-dark">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        </div>
                        
                        <!-- Tombol Lonceng Notifikasi (Placeholder) -->
                        <button class="w-10 h-10 rounded-full bg-white/50 text-slate-400 hover:text-elevate-accent flex items-center justify-center transition-colors border border-slate-100 shadow-sm hidden sm:flex">
                            <i class="ph-bold ph-bell"></i>
                        </button>

                        <!-- Dropdown User -->
                       <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-3 p-1.5 pr-4 rounded-[1.25rem] bg-white border border-slate-200 hover:border-elevate-accent/50 hover:shadow-sm transition-all focus:outline-none group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors leading-tight">{{ Auth::user()->name }}</p>
                                    
                                    {{-- Logic untuk merapikan tampilan Role (JSON -> Text) --}}
                                    @php
                                        $rawRole = Auth::user()->role;
                                        $displayRoles = [];
                                        
                                        if (is_string($rawRole)) {
                                            $decoded = json_decode($rawRole, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $displayRoles = $decoded; 
                                            } else {
                                                $displayRoles = explode(',', $rawRole); 
                                            }
                                        } elseif (is_array($rawRole)) {
                                            $displayRoles = $rawRole;
                                        } else {
                                            $displayRoles = [$rawRole ?? 'User'];
                                        }

                                        $displayRoles = array_filter($displayRoles);
                                        $mainRole = $displayRoles[0] ?? '-';
                                        $extraRolesCount = count($displayRoles) - 1;
                                    @endphp

                                   <div class="flex items-center justify-end gap-1 mt-0.5">
                                        <p class="text-[10px] text-slate-400 font-medium">{{ $mainRole }}</p>
                                        
                                        @if($extraRolesCount > 0)
                                            <span class="inline-flex items-center justify-center bg-elevate-accent/10 text-elevate-primary text-[9px] font-bold px-1.5 py-0.5 rounded leading-none border border-elevate-accent/30" 
                                                  title="{{ implode(', ', array_slice($displayRoles, 1)) }}">
                                                +{{ $extraRolesCount }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                               <div class="h-9 w-9 rounded-full bg-gradient-to-br from-elevate-accent to-elevate-primary shadow-inner flex items-center justify-center text-white font-bold text-sm overflow-hidden shrink-0 ring-2 ring-transparent group-hover:ring-elevate-accent/50 transition-all">
                                    @if(Auth::user()->photo_path)
                                        <img class="h-full w-full object-cover" src="{{ asset('storage/' . Auth::user()->photo_path) }}" alt="Avatar">
                                    @else
                                        <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <i class="ph-bold ph-caret-down text-slate-400 hidden sm:block"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                 class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl shadow-elevate-accent/10 py-2 border border-slate-100 z-50 origin-top-right"
                                 x-cloak>
                                
                                <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                    <p class="text-sm font-bold text-elevate-dark truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-elevate-dark/80 hover:bg-elevate-accent/10 hover:text-elevate-primary font-bold transition-colors">
                                    <i class="ph-bold ph-user-circle mr-3 text-lg"></i> {{ __('Profile Saya') }}
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="flex items-center px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 hover:text-rose-700 font-bold transition-colors mt-1">
                                        <i class="ph-bold ph-sign-out mr-3 text-lg"></i> {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth relative z-0">
                    {{-- Decoration Accent in Main App --}}
                    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-white/40 to-transparent rounded-bl-full opacity-50 pointer-events-none -z-10"></div>
                    
                    <div class="max-w-7xl mx-auto relative z-10">
                        {{ $slot }}
                    </div>
                    <div class="h-10"></div>
                </main>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>