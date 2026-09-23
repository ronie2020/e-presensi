@php
    $rawRole = Auth::user()->role ?? null;
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
        $displayRoles = [$rawRole ?? 'Pengguna'];
    }
    $displayRoles = array_filter($displayRoles);
    $mainRole = $displayRoles[0] ?? (Auth::user()->roles->first()?->name ?? 'Pengguna');
@endphp

<nav 
    x-cloak
    class="fixed inset-y-0 left-0 z-50 h-screen w-72 flex flex-col transition-all duration-300 ease-in-out shadow-2xl bg-[#021124]/95 backdrop-blur-2xl text-white border-r border-white/10 shadow-[8px_0_32px_rgba(0,0,0,0.5)] md:relative"
    :class="{
        '-translate-x-full': !sidebarOpen,   
        'translate-x-0': sidebarOpen,        
        'md:translate-x-0': true,            
        'md:w-72': sidebarExpanded,          
        'md:w-20': !sidebarExpanded          
    }">
    
    <!-- BACKGROUND AMBIENT GLOW -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-[100px] -left-[100px] w-[350px] h-[350px] bg-elevate-accent/15 rounded-full blur-[90px]"></div>
        <div class="absolute bottom-[-50px] right-[-50px] w-[250px] h-[250px] bg-elevate-primary/30 rounded-full blur-[80px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
    </div>

    <!-- TOMBOL PENGECIL SIDEBAR (TOGGLE BUTTON) - DESKTOP ONLY -->
    <button 
        @click="toggleSidebar()" 
        class="absolute -right-3 top-20 z-50 hidden md:flex h-6 w-6 items-center justify-center rounded-full bg-[#031d3d] text-elevate-accent shadow-[0_0_15px_rgba(86,187,241,0.4)] border border-elevate-accent/40 hover:scale-110 hover:bg-gradient-to-r hover:from-elevate-accent hover:to-elevate-primary hover:text-white transition-all duration-300 focus:outline-none"
        :title="sidebarExpanded ? 'Perkecil Sidebar' : 'Perbesar Sidebar'">
        <i class="ph-bold transition-transform duration-300" :class="sidebarExpanded ? 'ph-caret-left' : 'ph-caret-right'"></i>
    </button>

    <!-- TOMBOL CLOSE (X) - MOBILE ONLY -->
    <button 
        @click="sidebarOpen = false" 
        class="absolute right-4 top-4 z-50 md:hidden text-slate-400 hover:text-white transition-colors">
        <i class="ph-bold ph-x text-2xl"></i>
    </button>

    <!-- HEADER LOGO & ACADEMIC YEAR PILL -->
    <div class="relative z-10 shrink-0 flex flex-col justify-center border-b border-white/10 transition-all duration-300 bg-[#031d3d]/50 backdrop-blur-md"
         :class="sidebarExpanded ? 'px-5 py-4' : 'px-0 py-4 items-center'">
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden whitespace-nowrap w-full group"
           :class="sidebarExpanded ? 'justify-start' : 'justify-center'">
            
            <!-- Logo Icon (Glow Elevate Style) -->
            <div class="w-10 h-10 rounded-[1rem] bg-gradient-to-br from-elevate-accent to-elevate-primary flex items-center justify-center shadow-[0_0_20px_rgba(86,187,241,0.4)] border border-white/20 shrink-0 relative overflow-hidden transition-all duration-300"
                 :class="sidebarExpanded ? 'mx-0' : 'mx-auto group-hover:scale-105 group-hover:shadow-[0_0_25px_rgba(86,187,241,0.6)]'">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                <i class="ph-bold ph-graduation-cap text-xl hidden z-10 text-white"></i>
            </div>
            
            <!-- Teks Logo & Academic Pill -->
            <div class="flex flex-col transition-all duration-300 origin-left"
                 x-show="sidebarExpanded"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-90 translate-x-[-10px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-x-0">
                <span class="font-black text-white text-base tracking-tight leading-none group-hover:text-elevate-accent transition-colors drop-shadow-sm">SMPN 3 LAKBOK</span>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[9px] font-bold text-slate-300 tracking-wider">TA 2024/2025</span>
                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-elevate-accent/20 text-elevate-accent border border-elevate-accent/30 uppercase leading-none">Ganjil</span>
                </div>
            </div>
        </a>
    </div>

    <!-- QUICK MENU FILTER (PENCARIAN MENU CEPAT DENGAN CTRL+K) -->
    <div class="relative z-10 px-3 pt-3 pb-1 transition-all duration-300">
        <!-- Input Search saat Expanded -->
        <div x-show="sidebarExpanded" class="relative flex items-center">
            <i class="ph-bold ph-magnifying-glass absolute left-3 text-slate-400 text-sm pointer-events-none"></i>
            <input x-ref="sidebarSearchInput" 
                   x-model="menuSearch" 
                   type="text" 
                   placeholder="Cari menu... (Ctrl+K)" 
                   class="w-full pl-8 pr-7 py-2 text-xs rounded-xl bg-white/5 border border-white/10 placeholder-slate-400 text-white focus:outline-none focus:border-elevate-accent/60 focus:bg-white/10 focus:ring-1 focus:ring-elevate-accent/30 transition-all">
            <button x-show="menuSearch && menuSearch.length > 0" 
                    @click="menuSearch = ''" 
                    class="absolute right-2 text-slate-400 hover:text-white p-0.5 transition-colors"
                    title="Hapus pencarian">
                <i class="ph-bold ph-x text-xs"></i>
            </button>
            <kbd x-show="!menuSearch || menuSearch.length === 0" class="absolute right-2 px-1.5 py-0.5 rounded bg-white/10 border border-white/15 text-[9px] font-mono text-slate-400 pointer-events-none">
                ⌘K
            </kbd>
        </div>

        <!-- Tombol Icon Search saat Collapsed -->
        <div x-show="!sidebarExpanded" class="flex justify-center">
            <button @click="toggleSidebar(); setTimeout(() => $refs.sidebarSearchInput?.focus(), 200)" 
                    class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-elevate-accent/40 text-slate-400 hover:text-elevate-accent flex items-center justify-center transition-all shadow-sm group"
                    title="Cari Menu (Ctrl + K)">
                <i class="ph-bold ph-magnifying-glass text-base group-hover:scale-110 transition-transform"></i>
            </button>
        </div>
    </div>

    <!-- MENU LIST (DYNAMIC FILTERABLE) -->
    <div class="relative z-10 flex-1 overflow-y-auto overflow-x-visible py-4 sidebar-scroll flex flex-col gap-5"
         :class="sidebarExpanded ? 'px-3' : 'px-2'">
        
        @foreach(config('sidebar.menus') as $groupTitle => $items)
            
            {{-- LOGIKA FILTER MULTI-ROLE (MENGGUNAKAN SPATIE) --}}
            @php
                $visibleItems = collect($items)->filter(function ($item) {
                    if (!isset($item['roles']) || in_array('*', $item['roles'])) {
                        return true;
                    }
                    return auth()->user()->hasAnyRole($item['roles']);
                });
                $groupItemKeywords = strtolower($groupTitle . ' ' . $visibleItems->pluck('name')->implode(' '));
            @endphp

            @if($visibleItems->isNotEmpty())
                <div x-show="!menuSearch || '{{ addslashes($groupItemKeywords) }}'.includes(menuSearch.toLowerCase().trim())"
                     x-transition:enter="transition ease-out duration-150">
                    <!-- Group Title -->
                    <div class="mb-2 transition-all duration-300" 
                         :class="sidebarExpanded ? 'px-3' : 'px-0 text-center'">
                        
                        <h3 x-show="sidebarExpanded" 
                            class="text-[10px] font-black text-elevate-accent/80 uppercase tracking-widest flex items-center gap-3">
                            {{ $groupTitle }}
                            <span class="h-px flex-1 bg-gradient-to-r from-elevate-accent/30 to-transparent"></span>
                        </h3>
                        
                        <!-- Divider saat kecil -->
                       <div x-show="!sidebarExpanded" class="h-0.5 w-4 bg-white/10 mx-auto rounded-full group-hover:bg-elevate-accent transition-colors"></div>
                    </div>

                    <!-- Items -->
                    <ul class="space-y-1">
                        @foreach($visibleItems as $item)
                            @php
                                $isActive = false;
                                $checkRoute = $item['active_check'] ?? $item['route'];
                                if (is_array($checkRoute)) {
                                    foreach ($checkRoute as $route) { 
                                        if (request()->routeIs($route)) { 
                                            $isActive = true; break; 
                                        } 
                                    }
                                } else {
                                    $isActive = request()->routeIs($checkRoute);
                                }
                                
                                $editedUserId = request()->route('user');
                                if(is_object($editedUserId)) { $editedUserId = $editedUserId->id; }
                                $isEditingMyProfile = request()->routeIs('users.edit', 'users.update') && $editedUserId == auth()->id();
                                if ($item['name'] === 'Profil Saya' && $isEditingMyProfile) { $isActive = true; }
                                if ($item['name'] === 'Data Pengguna' && $isEditingMyProfile) { $isActive = false; }
                                $itemNameLower = strtolower($item['name']);
                            @endphp

                             <li class="relative" 
                                 x-show="!menuSearch || '{{ addslashes($itemNameLower) }}'.includes(menuSearch.toLowerCase().trim())"
                                 x-transition:enter="transition ease-out duration-150">
                                
                                <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" 
                                   class="group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300 outline-none relative overflow-hidden
                                          {{ $isActive 
                                             ? 'active bg-gradient-to-r from-elevate-accent/20 via-elevate-primary/30 to-transparent text-white font-bold border-l-2 border-elevate-accent shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)]' 
                                             : 'text-slate-300 hover:text-white hover:bg-white/5 hover:translate-x-0.5' 
                                          }}"
                                   :class="sidebarExpanded ? 'px-3.5 justify-start' : 'justify-center px-0 w-full'">
                                    
                                    <!-- Active Marker (Neon Crisp Style) -->
                                    <div class="absolute left-0 top-1.5 bottom-1.5 w-1.5 rounded-r-full transition-all duration-300 {{ $isActive ? 'bg-elevate-accent shadow-[0_0_15px_rgba(86,187,241,0.9)]' : 'bg-transparent' }}"></div>

                                    <!-- Icon -->
                                    <i class="ph-duotone {{ $item['icon'] ?? 'ph-circle' }} shrink-0 transition-all duration-300 relative z-10
                                              {{ $isActive ? 'text-elevate-accent drop-shadow-[0_0_12px_rgba(86,187,241,0.7)]' : 'text-slate-400 group-hover:text-elevate-accent group-hover:scale-110' }}"
                                       :class="sidebarExpanded ? 'text-[1.25rem] mr-0' : 'text-2xl mx-auto'"></i>
                                    
                                    <!-- Text -->
                                    <span x-show="sidebarExpanded" 
                                          class="text-xs font-bold tracking-wide whitespace-nowrap overflow-hidden relative z-10 transition-transform duration-300 {{ $isActive ? 'translate-x-0' : 'group-hover:translate-x-0.5' }}">
                                        {{ $item['name'] }}
                                    </span>

                                    <!-- TOOLTIP (Desktop Only) -->
                                    <div x-show="!sidebarExpanded"
                                         class="hidden md:block absolute left-full top-1/2 -translate-y-1/2 ml-4 px-3 py-2 bg-[#031d3d]/95 backdrop-blur-xl text-white text-xs font-bold rounded-xl shadow-2xl shadow-black/80 border border-elevate-accent/30 opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-[100] translate-x-[-10px] group-hover:translate-x-0">
                                        {{ $item['name'] }}
                                        <div class="absolute top-1/2 -left-1 -mt-1 w-2 h-2 bg-[#031d3d] border-l border-b border-elevate-accent/30 transform rotate-45"></div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
        
        <div class="h-6"></div>
    </div>

    <!-- USER PROFILE MINI-CARD -->
    <div class="relative z-10 p-2.5 border-t border-white/10 bg-[#031d3d]/50 backdrop-blur-md transition-all duration-300">
        <!-- Expanded Profile View -->
        <div x-show="sidebarExpanded" class="p-2 rounded-2xl bg-white/5 border border-white/10 hover:border-elevate-accent/30 hover:bg-white/10 transition-all flex items-center gap-2.5 group">
            <div class="relative w-9 h-9 rounded-xl bg-gradient-to-br from-elevate-accent to-elevate-primary p-0.5 shrink-0 shadow-sm">
                @if(Auth::user()->photo_path)
                    <img class="h-full w-full object-cover rounded-[10px]" src="{{ asset('storage/' . Auth::user()->photo_path) }}" alt="Avatar">
                @else
                    <span class="w-full h-full bg-[#021124] rounded-[10px] flex items-center justify-center text-elevate-accent font-black text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
                @endif
                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-[#021124]"></span>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate leading-tight group-hover:text-elevate-accent transition-colors">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $mainRole }}</p>
            </div>

            <!-- Quick Profile Link -->
            <a href="{{ route('profile.edit') }}" 
               class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/15 border border-white/10 hover:border-elevate-accent/40 text-slate-400 hover:text-elevate-accent flex items-center justify-center transition-all shrink-0" 
               title="Pengaturan Profil">
                <i class="ph-bold ph-gear text-sm"></i>
            </a>
        </div>

        <!-- Collapsed Profile View (Avatar Icon Only) -->
        <div x-show="!sidebarExpanded" class="flex justify-center">
            <a href="{{ route('profile.edit') }}" 
               class="relative w-9 h-9 rounded-xl bg-gradient-to-br from-elevate-accent to-elevate-primary p-0.5 shrink-0 shadow-sm hover:scale-105 hover:shadow-[0_0_15px_rgba(86,187,241,0.5)] transition-all group"
               title="{{ Auth::user()->name }} ({{ $mainRole }}) - Klik untuk profil">
                @if(Auth::user()->photo_path)
                    <img class="h-full w-full object-cover rounded-[10px]" src="{{ asset('storage/' . Auth::user()->photo_path) }}" alt="Avatar">
                @else
                    <span class="w-full h-full bg-[#021124] rounded-[10px] flex items-center justify-center text-elevate-accent font-black text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
                @endif
                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-[#021124]"></span>
            </a>
        </div>
    </div>

    <!-- FOOTER STATUS SISTEM -->
    <div class="relative z-10 px-3 py-2 border-t border-white/5 bg-[#021124] transition-all duration-300"
         :class="sidebarExpanded ? 'block' : 'flex justify-center p-2'">
        
        <div class="flex items-center justify-between text-[10px] text-slate-400"
             :class="sidebarExpanded ? 'px-1' : 'justify-center'">
            <div x-show="sidebarExpanded" class="flex items-center gap-1.5">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                </span>
                <span class="font-medium text-slate-300">SIMADU Online</span>
            </div>
            <span x-show="sidebarExpanded" class="font-mono text-slate-500 font-bold">v7.0</span>
            <i class="ph-bold ph-shield-check text-emerald-400 text-base" :class="!sidebarExpanded ? 'block' : 'hidden'" title="SIMADU Online v7.0"></i>
        </div>
    </div>
</nav>
<!-- SCRIPT MEMPERTAHANKAN POSISI SCROLL SIDEBAR (MENGGUNAKAN MEMORI BROWSER) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.querySelector('.sidebar-scroll');
        
        if (sidebar) {
            // 1. Ambil posisi scroll terakhir dari memori browser saat halaman dimuat
            const savedScrollPosition = sessionStorage.getItem('sidebarScrollPos');
            
            if (savedScrollPosition !== null) {
                // Kembalikan ke posisi scroll terakhir SECARA INSTAN
                sidebar.scrollTop = parseInt(savedScrollPosition, 10);
            }
            
            // 2. Simpan posisi scroll ke memori sesaat sebelum pindah halaman (saat loading)
            window.addEventListener('beforeunload', function() {
                sessionStorage.setItem('sidebarScrollPos', sidebar.scrollTop);
            });
            
            // 3. (Opsional/Backup) Simpan posisi setiap kali user melakukan scroll di sidebar
            sidebar.addEventListener('scroll', function() {
                sessionStorage.setItem('sidebarScrollPos', sidebar.scrollTop);
            }, { passive: true });
        }
    });
</script>