<nav 
    x-cloak
    class="fixed inset-y-0 left-0 z-50 h-screen w-72 flex flex-col transition-all duration-300 ease-in-out shadow-2xl bg-elevate-dark text-white border-r border-white/5 md:relative"
    :class="{
        '-translate-x-full': !sidebarOpen,   
        'translate-x-0': sidebarOpen,        
        'md:translate-x-0': true,            
        'md:w-72': sidebarExpanded,          
        'md:w-20': !sidebarExpanded          
    }">
    
    <!-- BACKGROUND AMBIENT GLOW (Dibuat lebih cerah & bersih, tidak buram) -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-[100px] -left-[100px] w-[350px] h-[350px] bg-elevate-accent/20 rounded-full blur-[90px]"></div>
        <div class="absolute bottom-[-50px] right-[-50px] w-[250px] h-[250px] bg-elevate-primary/40 rounded-full blur-[80px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
    </div>

    <!-- TOMBOL PENGECIL SIDEBAR (TOGGLE BUTTON) - DESKTOP ONLY -->
    <button 
        @click="toggleSidebar()" 
        class="absolute -right-3 top-20 z-50 hidden md:flex h-6 w-6 items-center justify-center rounded-full bg-white text-elevate-dark shadow-[0_0_15px_rgba(56,189,248,0.3)] border border-slate-100 hover:scale-110 hover:text-elevate-primary transition-all duration-300 focus:outline-none"
        :title="sidebarExpanded ? 'Perkecil Sidebar' : 'Perbesar Sidebar'">
        <i class="ph-bold transition-transform duration-300" :class="sidebarExpanded ? 'ph-caret-left' : 'ph-caret-right'"></i>
    </button>

    <!-- TOMBOL CLOSE (X) - MOBILE ONLY -->
    <button 
        @click="sidebarOpen = false" 
        class="absolute right-4 top-4 z-50 md:hidden text-slate-400 hover:text-white transition-colors">
        <i class="ph-bold ph-x text-2xl"></i>
    </button>

    <!-- HEADER LOGO -->
    <div class="relative z-10 shrink-0 flex items-center h-20 border-b border-white/10 transition-all duration-300 bg-white/5 backdrop-blur-sm"
         :class="sidebarExpanded ? 'px-6 justify-start' : 'px-0 justify-center'">
        
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 overflow-hidden whitespace-nowrap w-full group"
           :class="sidebarExpanded ? 'justify-start' : 'justify-center'">
            
            <!-- Logo Icon (Glow Elevate Style) -->
            <div class="w-10 h-10 rounded-[1rem] bg-gradient-to-br from-elevate-accent to-elevate-primary flex items-center justify-center shadow-[0_0_15px_rgba(56,189,248,0.4)] border border-white/20 shrink-0 relative overflow-hidden transition-all duration-300"
                 :class="sidebarExpanded ? 'mx-0' : 'mx-auto group-hover:scale-105 group-hover:shadow-[0_0_25px_rgba(56,189,248,0.6)]'">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-6 h-6 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                <i class="ph-bold ph-graduation-cap text-xl hidden z-10 text-white"></i>
            </div>
            
            <!-- Teks Logo -->
            <div class="flex flex-col transition-all duration-300 origin-left"
                 x-show="sidebarExpanded"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-90 translate-x-[-10px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-x-0">
                <span class="font-black text-white text-lg tracking-tight leading-none group-hover:text-elevate-accent transition-colors drop-shadow-sm">SMPN 3 LAKBOK</span>
                <span class="text-[10px] font-bold text-elevate-accent uppercase tracking-widest mt-1">Unggul & Berkarakter</span>
            </div>
        </a>
    </div>

    <!-- MENU LIST (DYNAMIC) -->
    <div class="relative z-10 flex-1 overflow-y-auto overflow-x-visible py-6 sidebar-scroll flex flex-col gap-6"
         :class="sidebarExpanded ? 'px-3' : 'px-2'">
        
        <?php $__currentLoopData = config('sidebar.menus'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupTitle => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            
            <?php
                $visibleItems = collect($items)->filter(function ($item) {
                    if (!isset($item['roles']) || in_array('*', $item['roles'])) {
                        return true;
                    }
                    return auth()->user()->hasAnyRole($item['roles']);
                });
            ?>

            <?php if($visibleItems->isNotEmpty()): ?>
                <div>
                    <!-- Group Title -->
                    <div class="mb-2 transition-all duration-300" 
                         :class="sidebarExpanded ? 'px-3' : 'px-0 text-center'">
                        
                        <h3 x-show="sidebarExpanded" 
                            class="text-[10px] font-black text-elevate-accent/80 uppercase tracking-widest flex items-center gap-3">
                            <?php echo e($groupTitle); ?>

                            <span class="h-px flex-1 bg-gradient-to-r from-elevate-accent/30 to-transparent"></span>
                        </h3>
                        
                        <!-- Divider saat kecil -->
                       <div x-show="!sidebarExpanded" class="h-0.5 w-4 bg-white/10 mx-auto rounded-full group-hover:bg-elevate-accent transition-colors"></div>
                    </div>

                    <!-- Items -->
                    <ul class="space-y-1">
                        <?php $__currentLoopData = $visibleItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
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
                            ?>

                             <li class="relative">
                                <a href="<?php echo e(isset($item['route']) ? route($item['route']) : '#'); ?>" 
                                   class="group flex items-center gap-3 py-3 rounded-2xl transition-all duration-300 outline-none relative overflow-hidden
                                          <?php echo e($isActive 
                                             ? 'bg-gradient-to-r from-elevate-primary/50 to-transparent text-white shadow-inner' 
                                             : 'text-slate-300 hover:text-white hover:bg-elevate-primary/30'); ?>"
                                   :class="sidebarExpanded ? 'px-4 justify-start' : 'justify-center px-0 w-full'">
                                    
                                    <!-- Active Marker (Neon Crisp Style) -->
                                    <div class="absolute left-0 top-1.5 bottom-1.5 w-1.5 rounded-r-full transition-all duration-300 <?php echo e($isActive ? 'bg-elevate-accent shadow-[0_0_12px_rgba(56,189,248,0.8)]' : 'bg-transparent'); ?>"></div>

                                    <!-- Icon -->
                                    <i class="ph-duotone <?php echo e($item['icon'] ?? 'ph-circle'); ?> shrink-0 transition-all duration-300 relative z-10
                                              <?php echo e($isActive ? 'text-elevate-accent drop-shadow-[0_0_10px_rgba(56,189,248,0.6)]' : 'text-slate-400 group-hover:text-elevate-accent group-hover:scale-110'); ?>"
                                       :class="sidebarExpanded ? 'text-[1.35rem] mr-0' : 'text-2xl mx-auto'"></i>
                                    
                                    <!-- Text -->
                                    <span x-show="sidebarExpanded" 
                                          class="text-sm font-bold tracking-wide whitespace-nowrap overflow-hidden relative z-10 transition-transform duration-300 <?php echo e($isActive ? 'translate-x-0' : 'group-hover:translate-x-1'); ?>">
                                        <?php echo e($item['name']); ?>

                                    </span>

                                    <!-- TOOLTIP (Desktop Only) -->
                                    <div x-show="!sidebarExpanded"
                                         class="hidden md:block absolute left-full top-1/2 -translate-y-1/2 ml-4 px-3 py-2 bg-white text-elevate-dark text-xs font-bold rounded-xl shadow-xl shadow-elevate-accent/20 border border-slate-100 opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-[100] translate-x-[-10px] group-hover:translate-x-0">
                                        <?php echo e($item['name']); ?>

                                        <div class="absolute top-1/2 -left-1 -mt-1 w-2 h-2 bg-white border-l border-b border-slate-100 transform rotate-45"></div>
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
    <div class="relative z-10 p-4 border-t border-white/10 bg-black/10 backdrop-blur-md transition-all duration-300"
         :class="sidebarExpanded ? 'block' : 'flex justify-center p-2'">
        
        <div class="rounded-2xl border border-white/5 bg-white/5 flex items-center gap-3 transition-all hover:bg-elevate-primary/30 hover:border-elevate-accent/30 cursor-pointer group"
             :class="sidebarExpanded ? 'p-4' : 'justify-center p-2 h-10 w-10'"
             title="Status Sistem: Online">
            
            <div x-show="sidebarExpanded" class="flex-1 overflow-hidden">
                <p class="text-[10px] font-bold text-elevate-accent/70 uppercase tracking-widest group-hover:text-elevate-accent transition-colors">Status Sistem</p>
                <div class="flex items-center gap-2 mt-1">
                    <div class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                    </div>
                    <p class="text-xs font-black text-white">Online v7.0</p>
                </div>
            </div>

            <i class="ph-bold ph-plugs text-xl text-slate-400 group-hover:text-elevate-accent transition-colors" :class="!sidebarExpanded ? 'block' : 'hidden'"></i>
        </div>
    </div>
</nav><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>