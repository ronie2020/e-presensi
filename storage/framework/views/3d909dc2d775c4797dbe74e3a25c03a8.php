<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Netila E-Presensi')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            
            .sidebar-scroll::-webkit-scrollbar { width: 4px; }
            .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); }
        </style>
        
        <?php echo $__env->yieldPushContent('styles'); ?>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        
        <!-- INITIALIZE STATE HERE -->
        <!-- sidebarExpanded: Ambil dari localStorage agar browser 'ingat' posisi terakhir -->
        <div x-data="{ 
                sidebarOpen: false, 
                sidebarExpanded: localStorage.getItem('sidebarExpanded') === null ? true : localStorage.getItem('sidebarExpanded') === 'true',
                toggleSidebar() {
                    this.sidebarExpanded = !this.sidebarExpanded;
                    localStorage.setItem('sidebarExpanded', this.sidebarExpanded);
                }
            }" 
            class="h-screen flex overflow-hidden bg-slate-50">
            
            <!-- ====== SIDEBAR NAVIGASI ====== -->
            <!-- Navigasi ini sekarang akan merespon variabel sidebarExpanded -->
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Overlay Mobile -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-40 md:hidden" 
                 @click="sidebarOpen = false">
            </div>

            <!-- ====== KONTEN UTAMA ====== -->
            <!-- Flex-1 memastikan konten ini mengisi sisa ruang di sebelah sidebar -->
            <div class="flex-1 flex flex-col h-screen relative z-10 overflow-hidden transition-all duration-300">
                
                <!-- Header -->
                <header class="bg-white/90 backdrop-blur-md sticky top-0 z-30 border-b border-slate-100 px-6 py-4 flex justify-between items-center shadow-sm">
                    
                    <div class="flex items-center gap-4">
                        <!-- Tombol Hamburger Mobile -->
                        <button @click="sidebarOpen = true" class="md:hidden text-slate-500 hover:text-blue-600 focus:outline-none transition-colors p-1 rounded-lg hover:bg-blue-50">
                            <i class="ph-bold ph-list text-2xl"></i>
                        </button>

                        <?php if(isset($header)): ?>
                            <div class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">
                                <?php echo e($header); ?>

                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- User Info -->
                    <div class="flex items-center gap-6">
                        <div class="hidden md:block text-right border-r border-slate-100 pr-6">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Tanggal</p>
                            <p class="text-sm font-bold text-slate-700"><?php echo e(\Carbon\Carbon::now()->translatedFormat('d M Y')); ?></p>
                        </div>

                        <!-- Dropdown User -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors"><?php echo e(Auth::user()->name); ?></p>
                                    
                                    
                                    <?php
                                        $rawRole = Auth::user()->role;
                                        $displayRoles = [];
                                        
                                        // 1. Cek tipe data role (bisa string JSON, string biasa, atau array)
                                        if (is_string($rawRole)) {
                                            $decoded = json_decode($rawRole, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $displayRoles = $decoded; // Format baru JSON ["Guru", "Admin"]
                                            } else {
                                                $displayRoles = explode(',', $rawRole); // Format lama string "Guru,Admin"
                                            }
                                        } elseif (is_array($rawRole)) {
                                            $displayRoles = $rawRole;
                                        } else {
                                            $displayRoles = [$rawRole ?? 'User'];
                                        }

                                        // 2. Bersihkan nilai kosong
                                        $displayRoles = array_filter($displayRoles);

                                        // 3. Ambil role utama (pertama) dan hitung sisanya
                                        $mainRole = $displayRoles[0] ?? '-';
                                        $extraRolesCount = count($displayRoles) - 1;
                                    ?>

                                    <div class="flex items-center justify-end gap-1 mt-0.5">
                                        <p class="text-xs text-slate-500 font-medium"><?php echo e($mainRole); ?></p>
                                        
                                        <?php if($extraRolesCount > 0): ?>
                                            <span class="inline-flex items-center justify-center bg-blue-100 text-blue-600 text-[9px] font-bold px-1.5 py-0.5 rounded leading-none" 
                                                  title="<?php echo e(implode(', ', array_slice($displayRoles, 1))); ?>">
                                                +<?php echo e($extraRolesCount); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    

                                </div>
                                <div class="h-10 w-10 rounded-full bg-blue-50 border-2 border-white shadow-md flex items-center justify-center text-blue-600 font-bold text-lg overflow-hidden shrink-0 ring-2 ring-transparent group-hover:ring-blue-100 transition-all">
                                    <?php if(Auth::user()->photo_path): ?>
                                        <img class="h-full w-full object-cover" src="<?php echo e(asset('storage/' . Auth::user()->photo_path)); ?>" alt="Avatar">
                                    <?php else: ?>
                                        <span><?php echo e(substr(Auth::user()->name, 0, 1)); ?></span>
                                    <?php endif; ?>
                                </div>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 border border-slate-100 z-50 origin-top-right ring-1 ring-black/5"
                                 style="display: none;">
                                
                                <div class="px-4 py-2 border-b border-slate-50 mb-1">
                                    <p class="text-xs text-slate-400 font-bold uppercase">Akun Saya</p>
                                </div>

                                <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600 font-medium transition-colors">
                                    <i class="ph-bold ph-user-circle mr-2"></i> <?php echo e(__('Profile')); ?>

                                </a>
                                
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <a href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-medium transition-colors">
                                        <i class="ph-bold ph-sign-out mr-2"></i> <?php echo e(__('Log Out')); ?>

                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth bg-slate-50/50">
                    <div class="max-w-7xl mx-auto">
                        <?php echo e($slot); ?>

                    </div>
                    <div class="h-10"></div>
                </main>
            </div>
        </div>
        
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\layouts\app.blade.php ENDPATH**/ ?>