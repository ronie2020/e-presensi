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
        <div class="absolute -top-[100px] -left-[100px] w-[300px] h-[300px] bg-blue-600/30 rounded-full blur-[80px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>

    <!-- TOMBOL PENGECIL SIDEBAR (TOGGLE BUTTON) - DESKTOP ONLY -->
    <button 
        @click="toggleSidebar()" 
        class="absolute -right-3 top-20 z-50 hidden md:flex h-6 w-6 items-center justify-center rounded-full bg-white text-blue-900 shadow-[0_0_10px_rgba(0,0,0,0.2)] border border-blue-100 hover:bg-blue-50 transition-transform duration-300 hover:scale-110 focus:outline-none"
        :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'">
        <i class="ph-bold ph-caret-left text-xs"></i>
    </button>
    
    <!-- TOMBOL CLOSE (X) - MOBILE ONLY -->
    <button 
        @click="sidebarOpen = false" 
        class="absolute right-4 top-4 z-50 md:hidden text-white/70 hover:text-white transition-colors">
        <i class="ph-bold ph-x text-2xl"></i>
    </button>

    <!-- HEADER LOGO -->
    <div class="relative z-10 shrink-0 flex items-center h-24 border-b border-white/10 transition-all duration-300"
         :class="sidebarExpanded ? 'px-6 justify-start' : 'px-0 justify-center'">
        
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 overflow-hidden whitespace-nowrap w-full"
           :class="sidebarExpanded ? 'justify-start' : 'justify-center'">
            <!-- Logo Icon -->
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 shrink-0 border border-white/10 transition-all duration-300 relative"
                 :class="sidebarExpanded ? 'mx-0' : 'mx-auto'">
                <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-6 h-6 text-white fill-current']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-white fill-current']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
            </div>
            
            <!-- Teks Logo -->
            <div class="flex flex-col transition-all duration-300 origin-left"
                 x-show="sidebarExpanded"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-90 translate-x-[-10px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-x-0">
                <span class="font-extrabold text-white text-lg tracking-tight leading-none">SMPN 3 LAKBOK</span>
                <span class="text-[10px] font-bold text-blue-300 uppercase tracking-[0.15em] mt-1">Unggul & Berkarakter</span>
            </div>
        </a>
    </div>

    <!-- MENU LIST -->
    <div class="relative z-10 flex-1 overflow-y-auto overflow-x-visible py-6 sidebar-scroll flex flex-col gap-6"
         :class="sidebarExpanded ? 'px-4' : 'px-2'">
        
        <?php $__currentLoopData = config('sidebar.menus'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupTitle => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            
            <?php
                $visibleItems = collect($items)->filter(function ($item) {
                    // 1. Jika menu tidak punya batasan role, tampilkan (PUBLIC)
                    if (!isset($item['roles'])) return true;
                    
                    // 2. Ambil Role User Saat Ini
                    $userRoles = Auth::user()->role;

                    // 3. Normalisasi Role User menjadi Array
                    // Jika tersimpan sebagai JSON string ["Guru", "Wali Kelas"], decode dulu
                    // Jika tersimpan sebagai string biasa "Guru", jadikan array ["Guru"]
                    if (is_string($userRoles)) {
                        $decoded = json_decode($userRoles, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $userRoles = $decoded;
                        } else {
                            // Coba explode koma jika formatnya "Guru,Wali Kelas"
                            $userRoles = explode(',', $userRoles); 
                        }
                    } elseif (!is_array($userRoles)) {
                        $userRoles = [$userRoles];
                    }

                    // 4. Cek Intersection (Irisan)
                    // Jika ada SATU SAJA role user yang cocok dengan role menu, IZINKAN.
                    // Contoh: User punya ["Guru", "Wali Kelas"], Menu butuh ["Admin", "Wali Kelas"] -> COCOK di "Wali Kelas"
                    $intersection = array_intersect($userRoles, $item['roles']);
                    
                    return count($intersection) > 0;
                });
            ?>

            
            <?php if($visibleItems->isNotEmpty()): ?>
                <div>
                    <!-- Group Title -->
                    <div class="mb-2 transition-all duration-300" 
                         :class="sidebarExpanded ? 'px-3' : 'px-0 text-center'">
                        
                        <h3 x-show="sidebarExpanded" 
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <?php echo e($groupTitle); ?>

                            <span class="h-px flex-1 bg-white/5"></span>
                        </h3>
                        
                        <!-- Divider saat kecil -->
                        <div x-show="!sidebarExpanded" class="h-0.5 w-4 bg-white/10 mx-auto rounded-full group-hover:bg-blue-500 transition-colors"></div>
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
                            ?>

                            <li class="relative">
                                <a href="<?php echo e(isset($item['route']) ? route($item['route']) : '#'); ?>" 
                                   class="group flex items-center py-3 rounded-xl transition-all duration-200 outline-none relative
                                          <?php echo e($isActive 
                                             ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-900/50' 
                                             : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>"
                                   :class="sidebarExpanded ? 'px-4 justify-start' : 'justify-center px-0 w-full'">
                                    
                                    <!-- Active Marker -->
                                    <?php if($isActive): ?>
                                        <div x-show="sidebarExpanded" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-yellow-400 rounded-r-full shadow-[0_0_10px_rgba(250,204,21,0.5)]"></div>
                                    <?php endif; ?>

                                    <!-- Icon -->
                                    <i class="ph-bold <?php echo e($item['icon'] ?? 'ph-circle'); ?> shrink-0 transition-all duration-200 relative z-10
                                              <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-blue-300 group-hover:scale-110'); ?>"
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
    <div class="relative z-10 p-4 border-t border-white/10 bg-[#0f172a] transition-all duration-300"
         :class="sidebarExpanded ? 'block' : 'flex justify-center p-2'">
        
        <div class="rounded-xl border border-white/5 bg-white/5 flex items-center gap-3 transition-all hover:border-blue-500/30 cursor-pointer group"
             :class="sidebarExpanded ? 'p-3' : 'justify-center p-2 h-10 w-10'"
             title="Status Sistem: Online">
            
            <div x-show="sidebarExpanded" class="flex-1 overflow-hidden">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-blue-300 transition-colors">Status Sistem</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </div>
                    <p class="text-xs font-bold text-white">Online v5.5</p>
                </div>
            </div>

            <i class="ph-bold ph-gear text-slate-400 text-xl group-hover:text-blue-400 group-hover:rotate-90 transition-all duration-500"></i>
        </div>
    </div>
</nav><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>