<nav 
    x-cloak
    class="fixed inset-y-0 left-0 w-72 h-screen flex-shrink-0 flex flex-col transition-transform duration-300 ease-in-out z-50 md:static md:translate-x-0 bg-gradient-to-b from-blue-900 via-slate-900 to-slate-900 text-white shadow-2xl border-r border-white/10"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    
    <!-- BACKGROUND DECORATION (Agar tidak terlalu polos) -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <!-- Aksen Cahaya di pojok atas -->
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500 rounded-full mix-blend-overlay filter blur-[80px] opacity-20"></div>
        <!-- Texture halus -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
    </div>

    <!-- HEADER LOGO -->
    <div class="relative z-10 shrink-0 flex items-center h-24 px-6 border-b border-white/10 bg-white/5 backdrop-blur-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group w-full transition-all duration-300">
            <!-- Logo Container -->
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-900/50 group-hover:scale-105 transition-transform overflow-hidden shrink-0 border border-white/20">
                <x-application-logo class="w-6 h-6 object-contain" />
            </div>
            
            <div class="flex flex-col leading-tight overflow-hidden">
                <span class="font-extrabold text-white text-lg tracking-tight group-hover:text-blue-200 transition-colors truncate drop-shadow-sm">SMPN 3 LAKBOK</span>
                <span class="text-[10px] font-bold text-blue-200/70 uppercase tracking-widest group-hover:text-yellow-400 transition-colors truncate">Unggul & Berkarakter</span>
            </div>
        </a>
    </div>

    {{-- SCROLLABLE MENU --}}
    <div class="relative z-10 flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
        
        @foreach(config('sidebar.menus') as $groupTitle => $items)
            <div>
                {{-- Judul Group Menu --}}
                <h3 class="px-4 text-[10px] font-black text-blue-300/60 uppercase tracking-widest mb-3 ml-1 flex items-center gap-2">
                    {{ $groupTitle }}
                    <span class="h-px flex-1 bg-blue-300/10"></span>
                </h3>
                
                <div class="space-y-1">
                    @foreach($items as $item)
                        @php
                            $isActive = false;
                            $checkRoute = $item['active_check'] ?? $item['route'];
                            if (is_array($checkRoute)) {
                                foreach ($checkRoute as $route) { if (request()->routeIs($route)) { $isActive = true; break; } }
                            } else {
                                $isActive = request()->routeIs($checkRoute);
                            }
                        @endphp

                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group relative overflow-hidden {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-white/20' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
                            
                            {{-- Active Indicator (Glow Effect) --}}
                            @if($isActive)
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 opacity-100 z-0"></div>
                                <div class="absolute left-0 h-full w-1 bg-yellow-400 z-10"></div>
                            @endif
                            
                            {{-- ICON --}}
                            <div class="relative z-10 mr-3 {{ $isActive ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors">
                                <i class="{{ $item['icon'] ?? 'ph-circle' }} text-xl"></i>
                            </div>
                            
                            <span class="relative z-10 text-sm font-bold tracking-wide">{{ $item['name'] }}</span>
                            
                            {{-- Arrow Right (Hover Effect) --}}
                            @if(!$isActive)
                                <i class="ph-bold ph-caret-right ml-auto text-xs opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 text-blue-300"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="h-12"></div>
    </div>
    
    <!-- FOOTER SIDEBAR -->
    <div class="p-4 border-t border-white/10 bg-slate-900/50 backdrop-blur-md relative z-10">
        <div class="bg-gradient-to-r from-blue-900/50 to-slate-800/50 rounded-xl p-3 border border-white/5 flex items-center justify-between group cursor-pointer hover:border-blue-500/30 transition-all">
             <div>
                 <p class="text-[10px] text-blue-300/70 font-bold uppercase tracking-widest group-hover:text-blue-300 transition-colors">Status Sistem</p>
                 <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <p class="text-xs text-white font-mono font-bold">Online v5.5</p>
                 </div>
             </div>
             <i class="ph-duotone ph-gear text-blue-400 text-xl group-hover:rotate-90 transition-transform duration-700"></i>
        </div>
    </div>
</nav>