<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Animasi Wiggle untuk ikon saat hover */
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }

        /* Utility Printing */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            [x-show] { display: block !important; }
        }
    </style>

    
    <div class="py-6 md:py-8 font-sans text-slate-800 pb-32" x-data="{ 
        activeTab: '<?php echo e(request('activeTab', 'hadir')); ?>',
        reportType: '<?php echo e(request('report_type', 'daily')); ?>',
        loading: false, // State untuk loading overlay
        
        // Fungsi untuk navigasi (switcher) atau submit filter dengan animasi loading
        navigate(url) {
            this.loading = true;
            setTimeout(() => { window.location.href = url; }, 200);
        },
        submitFilter() {
            this.loading = true;
            setTimeout(() => { this.$el.closest('form').submit(); }, 200);
        }
    }">

        
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;" 
             class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
            
            <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center transform transition-all scale-100">
                <div class="relative w-12 h-12 mb-4">
                    <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-blue-600 border-t-transparent animate-spin"></div>
                </div>
                <span class="text-xs font-bold text-slate-700 tracking-wider uppercase animate-pulse">Memuat Data...</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 no-print">
                
                
                <div class="animate-enter bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 rounded-[2rem] p-6 lg:p-8 text-white shadow-xl shadow-blue-900/30 relative overflow-hidden flex flex-col justify-between min-h-[180px] lg:min-h-[200px] border border-white/10 group">
                    
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    
                    
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all duration-700"></div>
                    <div class="absolute -left-10 bottom-0 w-32 h-32 bg-blue-400/10 rounded-full blur-2xl group-hover:bg-blue-400/20 transition-all duration-700"></div>
                    
                    <div class="relative z-10">
                        <h1 class="text-xl lg:text-2xl font-extrabold mb-1 tracking-tight text-white flex items-center gap-2">
                            Rekap Keagamaan
                        </h1>
                        <p class="text-blue-300 text-sm font-medium tracking-wide">Laporan ibadah siswa.</p>
                    </div>

                    
                    <div class="relative z-10 mt-6 bg-slate-900/50 p-1.5 rounded-2xl flex border border-white/10 backdrop-blur-sm">
                        <button @click="navigate('<?php echo e(route('reports.religious', array_merge(request()->all(), ['activity' => 'Dhuha']))); ?>')" 
                           class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 <?php echo e($selectedActivity == 'Dhuha' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-blue-300 hover:bg-white/5 hover:text-white'); ?>">
                            <i class="ph-bold ph-sun text-lg <?php echo e($selectedActivity == 'Dhuha' ? 'text-yellow-300' : ''); ?>"></i> 
                            Dhuha
                        </button>
                        <button @click="navigate('<?php echo e(route('reports.religious', array_merge(request()->all(), ['activity' => 'Dhuhur']))); ?>')" 
                           class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 <?php echo e($selectedActivity == 'Dhuhur' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-blue-300 hover:bg-white/5 hover:text-white'); ?>">
                            <i class="ph-fill ph-moon-stars text-lg <?php echo e($selectedActivity == 'Dhuhur' ? 'text-white' : ''); ?>"></i> 
                            Dhuhur
                        </button>
                    </div>
                </div>

                
                <div class="animate-enter lg:col-span-2 bg-white rounded-[2rem] p-6 lg:p-8 border border-slate-100 shadow-sm relative overflow-hidden" style="animation-delay: 100ms">
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                
                                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                                Filter Data
                            </h2>
                            
                            <div class="bg-slate-100 p-1 rounded-xl flex w-full md:w-auto">
                                <button @click="reportType = 'daily'" :class="reportType === 'daily' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all text-center">Harian</button>
                                <button @click="reportType = 'weekly'" :class="reportType === 'weekly' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all text-center">Mingguan</button>
                                <button @click="reportType = 'monthly'" :class="reportType === 'monthly' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all text-center">Bulanan</button>
                            </div>
                        </div>

                        <form action="<?php echo e(route('reports.religious')); ?>" method="GET" class="flex flex-col md:flex-row gap-3 w-full" @submit.prevent="submitFilter">
                            <input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                            <input type="hidden" name="activeTab" x-model="activeTab">
                            <input type="hidden" name="report_type" x-model="reportType">
                            
                            <div class="flex-1 w-full">
                                <div x-show="reportType === 'daily'">
                                    <input type="date" name="date" value="<?php echo e(request('date', $selectedDate_db->format('Y-m-d'))); ?>" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm">
                                </div>
                                <div x-show="reportType === 'weekly'" style="display: none;">
                                    <input type="week" name="week" value="<?php echo e(request('week')); ?>" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm">
                                </div>
                                <div x-show="reportType === 'monthly'" style="display: none;">
                                    <input type="month" name="month" value="<?php echo e(request('month')); ?>" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm">
                                </div>
                            </div>

                            <div class="flex gap-2 w-full md:w-auto">
                                
                                <button type="submit" class="flex-1 md:flex-none bg-blue-900 hover:bg-slate-900 text-white px-5 rounded-xl h-11 font-bold text-sm shadow-lg flex items-center justify-center gap-2 transition-all active:scale-95">
                                    <i class="ph-bold ph-magnifying-glass"></i> <span class="md:hidden">Cari</span>
                                </button>
                                
                                <div class="w-px h-11 bg-slate-200 hidden md:block"></div>

                                <a href="<?php echo e(route('reports.printReligious', request()->all())); ?>" target="_blank" class="flex-1 md:flex-none bg-white border border-slate-200 text-slate-600 hover:text-blue-900 hover:border-blue-900 px-5 rounded-xl h-11 font-bold text-sm flex items-center justify-center gap-2 transition-colors active:scale-95">
                                    <i class="ph-bold ph-printer text-lg"></i> <span class="md:hidden">Cetak</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-8">
                <div class="animate-enter bg-white p-5 lg:p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-all" style="animation-delay: 200ms">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sudah Absen</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-800 truncate"><?php echo e($hadirCount); ?></h3>
                    </div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl lg:text-3xl animate-wiggle shrink-0"><i class="ph-fill ph-check-circle"></i></div>
                </div>

                <div class="animate-enter bg-white p-5 lg:p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-all" style="animation-delay: 300ms">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Izin / Uzur</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-800 truncate"><?php echo e($izinUzurCount + $alfaCount); ?></h3>
                    </div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl lg:text-3xl animate-wiggle shrink-0"><i class="ph-fill ph-info"></i></div>
                </div>

                <div class="animate-enter bg-white p-5 lg:p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-all" style="animation-delay: 400ms">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Belum Absen</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-800 truncate"><?php echo e($belumAbsenCount); ?></h3>
                    </div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl lg:text-3xl animate-wiggle shrink-0"><i class="ph-fill ph-x-circle"></i></div>
                </div>
            </div>

            
            <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden min-h-[500px]" style="animation-delay: 500ms">
                
                
                <div class="flex flex-wrap md:flex-nowrap border-b border-slate-100 bg-slate-50/50 p-2 gap-2 sticky top-0 z-20 no-print">
                    <button @click="activeTab = 'hadir'" :class="activeTab === 'hadir' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-4 md:px-6 rounded-xl text-xs md:text-sm font-bold transition-all whitespace-nowrap">Sudah Absen</button>
                    <button @click="activeTab = 'belum'" :class="activeTab === 'belum' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-4 md:px-6 rounded-xl text-xs md:text-sm font-bold transition-all whitespace-nowrap">
                        Belum <span class="hidden sm:inline">Absen</span> <span class="ml-1 px-1.5 py-0.5 bg-rose-100 text-rose-600 rounded-md text-[10px]"><?php echo e($belumAbsenCount); ?></span>
                    </button>
                    <button @click="activeTab = 'uzur'" :class="activeTab === 'uzur' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-4 md:px-6 rounded-xl text-xs md:text-sm font-bold transition-all whitespace-nowrap">Ket. Lain</button>
                </div>

                <div class="p-0">
                    
                    
                    <div x-show="activeTab === 'hadir'" class="w-full">
                        
                        <div class="p-4 bg-slate-50 border-b border-slate-100 no-print flex justify-end">
                            <form id="reset-data-form" method="POST" action="<?php echo e(route('reports.destroyReligious')); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>">
                                <input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                                <button type="button" onclick="confirmResetData()" class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-4 py-2.5 rounded-xl transition-colors flex items-center gap-2 border border-rose-100 active:scale-95">
                                    <i class="ph-bold ph-trash"></i> Reset Data
                                </button>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 gap-0">
                            <?php $__empty_1 = true; $__currentLoopData = $attendancesHadir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 hidden group-hover:block"></div>
                                    <div class="flex items-center gap-3 md:gap-4 overflow-hidden">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                                            <?php echo e($loop->iteration); ?>

                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors truncate"><?php echo e($attendance->student->name); ?></h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded"><?php echo e($attendance->student->schoolClass->name); ?></span>
                                                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1"><i class="ph-bold ph-clock"></i> <?php echo e($attendance->created_at->format('H:i')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModalReligious(<?php echo e($attendance->id); ?>, '<?php echo e($attendance->student->name); ?>', '<?php echo e($attendance->status_final); ?>', `<?php echo e($attendance->notes_final); ?>`, '<?php echo e($attendance->activity); ?>')" 
                                        class="p-2 ml-2 md:ml-4 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all no-print shrink-0 active:scale-95">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-20">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300"><i class="ph-duotone ph-coffee text-4xl"></i></div>
                                    <p class="text-slate-400 font-bold">Belum ada data hadir.</p>
                                </div> 
                            <?php endif; ?>
                        </div>
                        <div class="p-4 border-t border-slate-100">
                            <?php echo e($attendancesHadir->appends(request()->query() + ['activeTab' => 'hadir'])->links()); ?>

                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'belum'" style="display: none;" class="w-full">
                         <?php if($belumAbsenList->count() > 0): ?>
                            <div class="p-5 bg-rose-50 border-b border-rose-100 flex flex-col md:flex-row items-center justify-between gap-4 no-print">
                                <div class="flex items-center gap-3 text-rose-700">
                                    <div class="p-2 bg-white rounded-lg shadow-sm shrink-0"><i class="ph-fill ph-warning-octagon text-xl"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm">Aksi Massal Diperlukan</h4>
                                        <p class="text-xs opacity-80"><?php echo e($belumAbsenList->count()); ?> siswa belum tercatat.</p>
                                    </div>
                                </div>
                                <form id="bulk-alpha-religious-form" action="<?php echo e(route('reports.bulkAlpha')); ?>" method="POST" class="w-full md:w-auto">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>">
                                    <input type="hidden" name="type" value="Keagamaan">
                                    <input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                                    <button type="button" onclick="confirmBulkAlphaReligious('<?php echo e($belumAbsenList->count()); ?>')" 
                                        class="w-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-5 py-3 rounded-xl shadow-lg shadow-rose-200 transition-all flex items-center justify-center gap-2 active:scale-95">
                                        <i class="ph-bold ph-check-circle"></i> Tandai Semua Alfa
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-1 gap-0">
                            <?php $__empty_1 = true; $__currentLoopData = $belumAbsenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500 hidden group-hover:block"></div>
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs shrink-0">!</div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-800 truncate"><?php echo e($student->name); ?></h4>
                                            <p class="text-xs text-slate-500"><?php echo e($student->schoolClass->name ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    <button onclick="openManualModalForStudent(<?php echo e($student->id); ?>, '<?php echo e($student->name); ?>')" 
                                        class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-50 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm no-print shrink-0 active:scale-95">
                                        Input <span class="hidden md:inline">Manual</span>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-20 text-emerald-600 font-bold">Semua Aman! Tidak ada siswa yang tertinggal.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'uzur'" style="display: none;" class="w-full">
                         <div class="grid grid-cols-1 gap-0">
                            <?php $__empty_1 = true; $__currentLoopData = $attendancesUzur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 <?php echo e($attendance->status_final == 'Alfa' ? 'bg-rose-500' : 'bg-blue-500'); ?> hidden group-hover:block"></div>
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="w-10 h-10 rounded-xl <?php echo e($attendance->status_final == 'Alfa' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600'); ?> flex items-center justify-center font-bold text-xs shrink-0">
                                            <?php echo e(substr($attendance->status_final, 0, 1)); ?>

                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-800 truncate"><?php echo e($attendance->student->name); ?></h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?php echo e($attendance->status_final == 'Alfa' ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700'); ?> uppercase"><?php echo e($attendance->status_final); ?></span>
                                                <?php if($attendance->notes_final): ?>
                                                    <span class="text-xs text-slate-400 italic max-w-[100px] md:max-w-none truncate">"<?php echo e($attendance->notes_final); ?>"</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModalReligious(<?php echo e($attendance->id); ?>, '<?php echo e($attendance->student->name); ?>', '<?php echo e($attendance->status_final); ?>', `<?php echo e($attendance->notes_final); ?>`, '<?php echo e($attendance->activity); ?>')" 
                                        class="p-2 ml-2 md:ml-4 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all no-print shrink-0 active:scale-95">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-20 text-slate-400 italic">Tidak ada data izin/uzur.</div> 
                            <?php endif; ?>
                        </div>
                        <div class="p-4 border-t border-slate-100">
                            <?php echo e($attendancesUzur->appends(request()->query() + ['activeTab' => 'uzur'])->links()); ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    
    
    <div id="manualInputModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
            <div class="bg-blue-900 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-bold ph-pencil-line"></i> Input Manual</h3>
                <button onclick="closeManualModal()" class="text-white/70 hover:text-white transition"><i class="ph-bold ph-x text-xl"></i></button>
            </div>
            <form action="<?php echo e(route('reports.storeManual')); ?>" method="POST" class="p-6 space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="attendance_type" value="Keagamaan">
                <input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>">
                
                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 text-center">
                    <span class="block text-xs font-bold text-blue-900 uppercase tracking-widest mb-1">Siswa</span>
                    <input type="text" id="manual-student-name-display" class="w-full bg-transparent border-none text-center text-xl font-black text-blue-900 focus:ring-0 p-0" readonly>
                    <input type="hidden" name="student_id" id="manual-student-id">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Status Kehadiran</label>
                    <select name="status" class="w-full border-slate-200 bg-slate-50 rounded-xl focus:ring-blue-900 font-bold text-slate-700 h-12">
                        <option value="Hadir">Hadir</option>
                        <option value="Uzur Syar'i">Uzur Syar'i</option>
                        <option value="Alfa">Alfa</option> 
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Catatan</label>
                    <input type="text" name="notes" class="w-full border-slate-200 bg-slate-50 rounded-xl focus:ring-blue-900 h-12" placeholder="Contoh: Sakit">
                </div>
                <button type="submit" class="w-full bg-blue-900 hover:bg-slate-900 text-white font-bold h-12 rounded-xl transition-colors shadow-lg shadow-blue-200 mt-2 active:scale-95">Simpan Data</button>
            </form>
        </div>
    </div>

    
    <div id="editReligiousModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
            <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-bold ph-pencil-simple"></i> Edit Data</h3>
                <button onclick="closeEditModalReligious()" class="text-white/70 hover:text-white transition"><i class="ph-bold ph-x text-xl"></i></button>
            </div>
            <form id="editReligiousForm" method="POST" class="p-6 space-y-4">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="text-center mb-4">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Mengedit Siswa</p>
                    <p id="modal-religious-student-name" class="text-xl font-black text-slate-800 truncate px-4"></p>
                </div>
                <input type="hidden" name="activity" id="modal-religious-activity">
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Status</label>
                    <select name="status" id="modal-religious-status" class="w-full border-slate-200 bg-slate-50 rounded-xl font-bold text-slate-700 h-12">
                        <option value="Hadir">Hadir</option>
                        <option value="Uzur Syar'i">Uzur Syar'i</option>
                        <option value="Alfa">Alfa</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Catatan</label>
                    <textarea name="notes" id="modal-religious-notes" class="w-full border-slate-200 bg-slate-50 rounded-xl" rows="2"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModalReligious()" class="flex-1 h-12 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="flex-1 h-12 bg-blue-900 text-white font-bold rounded-xl hover:bg-slate-900 shadow-md active:scale-95">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openManualModalForStudent(id, name) {
            document.getElementById('manual-student-id').value = id;
            document.getElementById('manual-student-name-display').value = name;
            document.getElementById('manualInputModal').classList.remove('hidden');
        }
        function closeManualModal() {
            document.getElementById('manualInputModal').classList.add('hidden');
        }
        function confirmResetData() {
            Swal.fire({
                title: 'Reset Data Hari Ini?',
                text: "Semua data kehadiran <?php echo e($selectedActivity); ?> akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('reset-data-form').submit();
            })
        }
        function confirmBulkAlphaReligious(count) {
            Swal.fire({
                title: 'Tandai ' + count + ' Siswa Alfa?',
                text: "Siswa akan ditandai Alfa untuk Shalat <?php echo e($selectedActivity); ?>.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('bulk-alpha-religious-form').submit();
            })
        }
        const religiousModal = document.getElementById('editReligiousModal');
        const religiousForm = document.getElementById('editReligiousForm');
        const religiousStudentNameDisplay = document.getElementById('modal-religious-student-name');
        const religiousActivitySelect = document.getElementById('modal-religious-activity');
        const religiousStatusSelect = document.getElementById('modal-religious-status');
        const religiousNotesInput = document.getElementById('modal-religious-notes');
        function openEditModalReligious(id, name, status, notes, activity) {
            const updateRoute = '<?php echo e(route('reports.update', ['attendance' => '__ID__'])); ?>'.replace('__ID__', id);
            religiousForm.action = updateRoute;
            religiousStudentNameDisplay.textContent = name; 
            religiousActivitySelect.value = activity;
            religiousStatusSelect.value = status;
            religiousNotesInput.value = notes;
            religiousModal.classList.remove('hidden');
        }
        function closeEditModalReligious() { religiousModal.classList.add('hidden'); }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/reports/religious.blade.php ENDPATH**/ ?>