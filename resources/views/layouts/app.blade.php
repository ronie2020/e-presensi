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
            
            .bg-grid-dark {
                background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
                background-size: 32px 32px;
            }

            /* Custom Scrollbar Elevate Dark */
            ::-webkit-scrollbar { width: 8px; height: 8px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #56bbf1; border-radius: 10px; border: 2px solid transparent; background-clip: padding-box; }
            ::-webkit-scrollbar-thumb:hover { background: #0d52a1; border: 2px solid transparent; background-clip: padding-box; }
            
            .sidebar-scroll::-webkit-scrollbar { width: 4px; }
            .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
            .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(86,187,241,0.6); }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-[#021124] text-white min-h-screen relative overflow-hidden selection:bg-elevate-accent selection:text-[#021124]">
        
        <!-- GLOBAL FIXED BACKGROUND (Elegan, Tenang & Tidak Mengganggu) -->
        <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-[#021124]"></div>
            <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
                 style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
            <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
            <div class="absolute inset-0 bg-grid-dark opacity-35"></div>
            <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-elevate-accent/15 rounded-full blur-[160px] pointer-events-none"></div>
            <div class="absolute top-1/3 right-1/4 w-[550px] h-[550px] bg-elevate-primary/25 rounded-full blur-[180px] pointer-events-none"></div>
            <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[150px] pointer-events-none"></div>
        </div>

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
                 class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md z-40 md:hidden" 
                 @click="sidebarOpen = false"
                 x-cloak>
            </div>

            <!-- ====== KONTEN UTAMA ====== -->
            <div class="flex-1 flex flex-col h-screen relative z-0 overflow-hidden transition-all duration-300">
                
                <!-- Header (ELEVATE DARK GLASSMORPHISM) -->
                <header class="bg-[#031d3d]/90 backdrop-blur-2xl sticky top-0 z-30 border-b border-white/10 px-6 py-4 flex justify-between items-center shadow-[0_4px_30px_rgba(0,0,0,0.4)]">
                    
                     <div class="flex items-center gap-4">
                        <!-- Tombol Hamburger Mobile -->
                         <button @click="sidebarOpen = true" class="md:hidden text-slate-300 hover:text-elevate-accent focus:outline-none transition-colors p-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10">
                            <i class="ph-bold ph-list text-2xl"></i>
                        </button>

                        @if (isset($header))
                            <div class="text-xl md:text-2xl font-black text-white tracking-tight hidden sm:block">
                                {{ $header }}
                            </div>
                        @endif
                    </div>

                    <!-- User Info & Quick Tools -->
                    <div class="flex items-center gap-4 sm:gap-6">
                       <div class="hidden md:block text-right border-r border-white/15 pr-6">
                            <p class="text-[10px] font-bold text-elevate-accent uppercase tracking-widest">Hari Ini</p>
                            <p class="text-sm font-bold text-slate-200">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        </div>
                        
                        <!-- Link Cepat ke Portal Siswa -->
                        <a href="{{ route('portal.index') }}" target="_blank" class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/10 text-xs font-bold transition-all" title="Buka Portal Publik Siswa">
                            <i class="ph-bold ph-arrow-square-out text-elevate-accent"></i>
                            <span>Portal</span>
                        </a>

                        <!-- Dropdown User -->
                       <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-3 p-1.5 pr-4 rounded-[1.25rem] bg-white/5 border border-white/15 hover:border-elevate-accent/50 hover:bg-white/10 hover:shadow-lg transition-all focus:outline-none group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-bold text-white group-hover:text-elevate-accent transition-colors leading-tight">{{ Auth::user()->name }}</p>
                                    
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

                                   <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <p class="text-[10px] text-slate-400 font-medium">{{ $mainRole }}</p>
                                        
                                        @if($extraRolesCount > 0)
                                            <span class="inline-flex items-center justify-center bg-elevate-accent/20 text-elevate-accent text-[9px] font-bold px-1.5 py-0.5 rounded leading-none border border-elevate-accent/30" 
                                                  title="{{ implode(', ', array_slice($displayRoles, 1)) }}">
                                                +{{ $extraRolesCount }}
                                            </span>
                                        @endif
                                   </div>
                                </div>

                               <div class="h-9 w-9 rounded-full bg-gradient-to-br from-elevate-accent via-elevate-primary to-[#0d52a1] p-0.5 shadow-inner flex items-center justify-center text-white font-bold text-sm overflow-hidden shrink-0 ring-2 ring-white/10 group-hover:ring-elevate-accent transition-all">
                                    @if(Auth::user()->photo_path)
                                        <img class="h-full w-full object-cover rounded-full" src="{{ asset('storage/' . Auth::user()->photo_path) }}" alt="Avatar">
                                    @else
                                        <span class="w-full h-full bg-[#021124] rounded-full flex items-center justify-center text-elevate-accent font-black">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <i class="ph-bold ph-caret-down text-slate-400 group-hover:text-white transition-colors hidden sm:block text-xs"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                 class="absolute right-0 mt-3 w-60 bg-[#031d3d]/95 backdrop-blur-2xl rounded-2xl shadow-2xl shadow-black/80 py-2 border border-white/15 z-50 origin-top-right"
                                 x-cloak>
                                
                                <div class="px-4 py-3 border-b border-white/10 mb-1">
                                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-200 hover:bg-white/10 hover:text-elevate-accent font-bold transition-colors">
                                    <i class="ph-bold ph-user-circle mr-3 text-lg text-sky-400"></i> {{ __('Profil Saya') }}
                                </a>
                                
                                <div class="border-t border-white/10 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="flex items-center px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 font-bold transition-colors">
                                        <i class="ph-bold ph-sign-out mr-3 text-lg"></i> {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth relative z-0">
                    <div class="max-w-7xl mx-auto relative z-10">
                        {{ $slot }}
                    </div>
                    <div class="h-10"></div>
                </main>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const activeMenu = document.querySelector('nav .active'); 
                if (activeMenu) {
                    activeMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        </script>
        
        @stack('scripts')
    </body>
</html>