<nav 
    x-cloak
    class="fixed inset-y-0 left-0 z-50 h-screen w-72 flex flex-col transition-all duration-300 ease-in-out shadow-2xl bg-[#0f172a] text-white border-r border-white/5 md:relative"
    :class="{
        '-translate-x-full': !sidebarOpen,   
        'translate-x-0': sidebarOpen,        
        'md:translate-x-0': true,            
        'md:w-72': sidebarExpanded,          
        'md:w-20': !sidebarExpanded          
    }">
    
    <!-- BACKGROUND ART -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-[100px] -left-[100px] w-[300px] h-[300px] bg-cyan-600/20 rounded-full blur-[80px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>

    <!-- TOMBOL PENGECIL SIDEBAR (TOGGLE BUTTON) - DESKTOP ONLY -->
    <button 
        @click="toggleSidebar()" 
        class="absolute -right-3 top-20 z-50 hidden md:flex h-6 w-6 items-center justify-center rounded-full bg-white text-blue-600 shadow-md border border-slate-200 hover:bg-cyan-50 transition-transform duration-300 hover:scale-110 focus:outline-none"
        :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'">
        <i class="ph-bold ph-caret-left text-xs"></i>
    </button>
    
    <!-- TOMBOL CLOSE (X) - MOBILE ONLY -->
     <button 
        @click="sidebarOpen = false" 
        class="absolute right-4 top-4 z-50 md:hidden text-blue-200 hover:text-white transition-colors">
        <i class="ph-bold ph-x text-2xl"></i>
    </button>

    <!-- HEADER LOGO -->
    <div class="relative z-10 shrink-0 flex items-center h-20 border-b border-blue-900/50 transition-all duration-300"
         :class="sidebarExpanded ? 'px-6 justify-start' : 'px-0 justify-center'">
        
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 overflow-hidden whitespace-nowrap w-full group"
           :class="sidebarExpanded ? 'justify-start' : 'justify-center'">
            <!-- Logo Icon -->
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-lg shadow-cyan-900/20 shrink-0 relative overflow-hidden transition-all duration-300"
                 :class="sidebarExpanded ? 'mx-0' : 'mx-auto group-hover:scale-105'">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-6 h-6 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                <i class="ph-bold ph-buildings text-xl hidden z-10"></i>
            </div>
            
            <!-- Teks Logo -->
            <div class="flex flex-col transition-all duration-300 origin-left"
                 x-show="sidebarExpanded"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-90 translate-x-[-10px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-x-0">
                <span class="font-bold text-white text-lg tracking-tight leading-none group-hover:text-cyan-200 transition-colors">SMPN 3 LAKBOK</span>
                <span class="text-[10px] font-bold text-cyan-300 uppercase tracking-widest mt-1">Unggul & Berkarakter</span>
            </div>
        </a>
    </div>

    <!-- MENU LIST -->
    <div class="relative z-10 flex-1 overflow-y-auto overflow-x-visible py-6 sidebar-scroll flex flex-col gap-6"
         :class="sidebarExpanded ? 'px-4' : 'px-2'">
        
        <?php $__currentLoopData = config('sidebar.menus'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupTitle => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            
            <?php
                $visibleItems = collect($items)->filter(function ($item) {
                    // 1. Jika menu tidak punya batasan role atau diatur publik ('*'), tampilkan
                    if (!isset($item['roles']) || in_array('*', $item['roles'])) {
                        return true;
                    }
                    
                    // 2. Fungsi bawaan Spatie: Cek apakah user punya SALAH SATU role yang diminta menu
                    return auth()->user()->hasAnyRole($item['roles']);
                });
            ?>

            
            <?php if($visibleItems->isNotEmpty()): ?>
                <div>
                    <!-- Group Title -->
                    <div class="mb-2 transition-all duration-300" 
                         :class="sidebarExpanded ? 'px-3' : 'px-0 text-center'">
                        
                        <h3 x-show="sidebarExpanded" 
                            class="text-[10px] font-black text-blue-300/70 uppercase tracking-widest flex items-center gap-2">
                            <?php echo e($groupTitle); ?>

                            <span class="h-px flex-1 bg-blue-800/50"></span>
                        </h3>
                        
                        <!-- Divider saat kecil -->
                       <div x-show="!sidebarExpanded" class="h-0.5 w-4 bg-blue-800 mx-auto rounded-full group-hover:bg-cyan-500 transition-colors"></div>
                    </div>

                    <!-- Items (Looping $visibleItems yang sudah disaring) -->
                    <ul class="space-y-1">
                        <?php $__currentLoopData = $visibleItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isActive = false;
                                // Cek Active State
                                $checkRoute = $item['active_check'] ?? $item['route'];
                                if (is_array($checkRoute)) {
                                    foreach ($checkRoute as $route) { 
                                        if (request()->routeIs($route)) { 
                                            $isActive = true; 
                                            break; 
                                        } 
                                    }
                                } else {
                                    $isActive = request()->routeIs($checkRoute);
                                }
                                
                                // -------------------------------------------------------------
                                // LOGIKA KHUSUS UNTUK "PROFIL SAYA" (KARENA REDIRECT DARI WEB.PHP)
                                // -------------------------------------------------------------
                                $editedUserId = request()->route('user');
                                if(is_object($editedUserId)) {
                                    $editedUserId = $editedUserId->id;
                                }
                                
                                $isEditingMyProfile = request()->routeIs('users.edit', 'users.update') && $editedUserId == auth()->id();

                                if ($item['name'] === 'Profil Saya' && $isEditingMyProfile) {
                                    $isActive = true; // Paksa nyala untuk Profil Saya
                                }
                                
                                if ($item['name'] === 'Data Pengguna' && $isEditingMyProfile) {
                                    $isActive = false; // Paksa mati untuk Data Pengguna
                                }
                                // -------------------------------------------------------------
                            ?>

                             <li class="relative">
                                <a href="<?php echo e(isset($item['route']) ? route($item['route']) : '#'); ?>" 
                                   class="group flex items-center py-3 rounded-xl transition-all duration-200 outline-none relative
                                          <?php echo e($isActive 
                                             ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-lg shadow-cyan-900/50' 
                                             : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>"
                                   :class="sidebarExpanded ? 'px-4 justify-start' : 'justify-center px-0 w-full'">
                                    
                                    <!-- Active Marker -->
                                    <?php if($isActive): ?>
                                        <div x-show="sidebarExpanded" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-cyan-300 rounded-r-full shadow-[0_0_10px_rgba(103,232,249,0.5)]"></div>
                                    <?php endif; ?>

                                    <!-- Icon -->
                                    <i class="ph-bold <?php echo e($item['icon'] ?? 'ph-circle'); ?> shrink-0 transition-all duration-200 relative z-10
                                              <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-cyan-300 group-hover:scale-110'); ?>"
                                       :class="sidebarExpanded ? 'text-xl mr-3' : 'text-2xl mr-0'"></i>
                                    
                                    <!-- Text -->
                                    <span x-show="sidebarExpanded" 
                                          class="text-sm font-bold tracking-wide whitespace-nowrap overflow-hidden relative z-10">
                                        <?php echo e($item['name']); ?>

                                    </span>

                                    <!-- TOOLTIP (Desktop Only) -->
                                    <div x-show="!sidebarExpanded"
                                         class="hidden md:block absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-2 bg-slate-800 text-white text-xs font-bold rounded-lg shadow-xl border border-slate-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-[100]">
                                        <?php echo e($item['name']); ?>

                                        <div class="absolute top-1/2 -left-1 -mt-1 w-2 h-2 bg-slate-800 border-l border-b border-slate-600 transform rotate-45"></div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <div class="h-20"></div>
    </div>

    <!-- FOOTER STATUS -->
    <div class="relative z-10 p-4 border-t border-blue-900/50 bg-blue-950 transition-all duration-300"
         :class="sidebarExpanded ? 'block' : 'flex justify-center p-2'">
        
        <div class="rounded-xl border border-white/10 bg-white/5 flex items-center gap-3 transition-all hover:border-cyan-500/50 cursor-pointer group"
             :class="sidebarExpanded ? 'p-3' : 'justify-center p-2 h-10 w-10'"
             title="Status Sistem: Online">
            
            <div x-show="sidebarExpanded" class="flex-1 overflow-hidden">
                <p class="text-[10px] font-bold text-cyan-200/60 uppercase tracking-widest group-hover:text-cyan-200 transition-colors">Status Sistem</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                    </div>
                    <p class="text-xs font-bold text-white">Online v6.5</p>
                </div>
            </div>

            <i class="ph-bold ph-gear text-cyan-400/60 text-xl group-hover:text-cyan-300 group-hover:rotate-90 transition-all duration-500"></i>
        </div>
    </div>
</nav><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>