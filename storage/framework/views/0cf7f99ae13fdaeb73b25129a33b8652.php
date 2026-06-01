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
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
        
        table td, table th { white-space: normal !important; word-wrap: break-word !important; vertical-align: top !important; line-height: 1.6 !important; }
        table .truncate, table .whitespace-nowrap { overflow: visible !important; text-overflow: clip !important; white-space: normal !important; }

        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            [x-show] { display: block !important; }
            .overflow-x-auto, .overflow-y-auto, .max-h-screen { overflow: visible !important; max-height: none !important; height: auto !important; }
        }
    </style>

     <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-32" x-data="{ 
        activeTab: '<?php echo e(request('activeTab', 'hadir')); ?>',
        reportType: '<?php echo e(request('report_type', 'daily')); ?>',
        loading: false, 
        submitFilter() {
            this.loading = true;
            setTimeout(() => { this.$el.closest('form').submit(); }, 200);
        }
    }">

     <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>
    
        
          <template x-teleport="body">
            <div x-show="loading" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;" 
                 class="fixed inset-0 z-[100] bg-elevate-dark/40 backdrop-blur-sm flex items-center justify-center">
                
                <div class="bg-white p-8 rounded-[2.5rem] flex flex-col items-center transform transition-all scale-100 shadow-2xl">
                    <div class="relative w-14 h-14 mb-5">
                        <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-elevate-primary border-t-transparent animate-spin"></div>
                    </div>
                    <span class="text-sm font-black text-elevate-dark tracking-wider uppercase animate-pulse">Memuat Data...</span>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
             <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 no-print">
                <div class="animate-enter rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-6 lg:p-8 text-elevate-dark relative overflow-hidden flex flex-col justify-between min-h-[180px] lg:min-h-[200px] border border-white/60 shadow-xl shadow-elevate-accent/20 group">
                    <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/30 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <h1 class="text-2xl lg:text-3xl font-black mb-1 tracking-tight flex items-center gap-2">
                            Rekap Absensi
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold tracking-wide">Kehadiran siswa harian.</p>
                    </div>
                    <div class="relative z-10 mt-6">
                        <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md border border-white/50 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm text-elevate-dark">
                            <i class="ph-bold ph-calendar-blank"></i>
                            <span><?php echo e($selectedDate_db->translatedFormat('d F Y')); ?></span>
                        </div>
                    </div>
                </div>

                 <div class="animate-enter lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 p-6 lg:p-8 shadow-xl shadow-slate-200/40 relative overflow-hidden" style="animation-delay: 100ms">
                    <div class="relative z-10">
                         <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5 gap-4">
                            <h2 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                                <span class="w-1.5 h-6 bg-elevate-accent rounded-full"></span>
                                Filter & Laporan
                            </h2>
                              <div class="bg-elevate-soft p-1.5 rounded-2xl flex w-full md:w-auto">
                                <button @click="reportType = 'daily'" :class="reportType === 'daily' ? 'bg-white text-elevate-dark shadow-sm' : 'text-slate-500 hover:text-elevate-dark'" class="flex-1 px-5 py-2.5 rounded-xl text-xs font-bold transition-all">Harian</button>
                                <button @click="reportType = 'weekly'" :class="reportType === 'weekly' ? 'bg-white text-elevate-dark shadow-sm' : 'text-slate-500 hover:text-elevate-dark'" class="flex-1 px-5 py-2.5 rounded-xl text-xs font-bold transition-all">Mingguan</button>
                                <button @click="reportType = 'monthly'" :class="reportType === 'monthly' ? 'bg-white text-elevate-dark shadow-sm' : 'text-slate-500 hover:text-elevate-dark'" class="flex-1 px-5 py-2.5 rounded-xl text-xs font-bold transition-all">Bulanan</button>
                            </div>
                        </div>

                       <form action="<?php echo e(route('reports.daily')); ?>" method="GET" class="flex flex-col md:flex-row gap-4 w-full" @submit.prevent="submitFilter">
                            <input type="hidden" name="report_type" x-model="reportType">
                            <input type="hidden" name="activeTab" x-model="activeTab">
                            <div class="flex-1 w-full">
                                <div x-show="reportType === 'daily'">
                                    <input type="date" name="date" value="<?php echo e(request('date', $selectedDate_db->format('Y-m-d'))); ?>" 
                                           class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold h-14 text-sm px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent shadow-sm text-elevate-dark transition-colors">
                                </div>
                                <div x-show="reportType === 'weekly'" style="display: none;">
                                    <input type="week" name="week" value="<?php echo e(request('week')); ?>" 
                                           class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold h-14 text-sm px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent shadow-sm text-elevate-dark transition-colors">
                                </div>
                                <div x-show="reportType === 'monthly'" style="display: none;">
                                    <input type="month" name="month" value="<?php echo e(request('month')); ?>" 
                                           class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold h-14 text-sm px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent shadow-sm text-elevate-dark transition-colors">
                                </div>
                            </div>
                            <div class="flex gap-3 w-full md:w-auto">
                                <button type="submit" class="flex-1 md:flex-none bg-elevate-dark hover:bg-elevate-primary text-white px-6 rounded-2xl h-14 font-bold text-sm shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transition-all active:scale-95">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i> <span class="md:hidden">Tampilkan</span>
                                </button>
                                <a href="<?php echo e(route('reports.printDaily', request()->all())); ?>" target="_blank" class="flex-1 md:flex-none bg-white border-2 border-slate-100 text-elevate-dark hover:bg-elevate-soft px-6 rounded-2xl h-14 font-bold text-sm flex items-center justify-center gap-2 transition-colors active:scale-95 shadow-sm">
                                    <i class="ph-bold ph-printer text-xl"></i> <span class="md:hidden">Cetak</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

             <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="animate-enter mb-6 p-5 bg-[#DFF6DD] border border-[#B7DFB9] text-[#107C10] rounded-[1.5rem] font-bold text-sm flex justify-between items-center no-print">
                    <div class="flex items-center gap-3"><i class="ph-fill ph-check-circle text-xl"></i> <span><?php echo e(session('success')); ?></span></div>
                    <button @click="show = false" class="text-[#107C10] hover:opacity-70 p-1"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="animate-enter mb-6 p-5 bg-[#FDE7E9] border border-[#F4C3C9] text-[#D13438] rounded-[1.5rem] font-bold text-sm flex justify-between items-center no-print">
                    <div class="flex items-center gap-3"><i class="ph-fill ph-warning-circle text-xl"></i> <span><?php echo e(session('error')); ?></span></div>
                    <button @click="show = false" class="text-[#D13438] hover:opacity-70 p-1"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6 mb-8">
                <div class="animate-enter bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex items-center justify-between group hover:-translate-y-1 transition-transform" style="animation-delay: 200ms">
                    <div class="min-w-0">
                         <p class="text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Total Hadir</p>
                        <h3 class="text-4xl lg:text-5xl font-black text-elevate-dark truncate"><?php echo e($hadirCount); ?></h3>
                        <?php if($terlambatCount > 0): ?>
                            <div class="mt-3 inline-block px-3 py-1 bg-[#FFEFD6] text-[#D83B01] rounded-lg text-xs font-bold border border-[#FFD8A8]">
                                <?php echo e($terlambatCount); ?> Terlambat
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="w-16 h-16 lg:w-20 lg:h-20 bg-[#DFF6DD] text-[#107C10] rounded-[1.5rem] flex items-center justify-center text-3xl lg:text-4xl animate-wiggle shrink-0 shadow-sm"><i class="ph-fill ph-check-circle"></i></div>
                </div>
                 <div class="animate-enter bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex items-center justify-between group hover:-translate-y-1 transition-transform" style="animation-delay: 300ms">
                     <div class="min-w-0">
                         <p class="text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Izin / Sakit / Alfa</p>
                        <h3 class="text-4xl lg:text-5xl font-black text-elevate-dark truncate"><?php echo e($sakitCount + $izinCount + $alfaCount); ?></h3>
                    </div>
                    <div class="w-16 h-16 lg:w-20 lg:h-20 bg-[#FFEFD6] text-[#D83B01] rounded-[1.5rem] flex items-center justify-center text-3xl lg:text-4xl animate-wiggle shrink-0 shadow-sm"><i class="ph-fill ph-warning-circle"></i></div>
                </div>
                <div class="animate-enter bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex items-center justify-between group hover:-translate-y-1 transition-transform" style="animation-delay: 400ms">
                     <div class="min-w-0">
                         <p class="text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Belum Absen</p>
                        <h3 class="text-4xl lg:text-5xl font-black text-elevate-dark truncate"><?php echo e($belumAbsenList->total()); ?></h3>
                    </div>
                    <div class="w-16 h-16 lg:w-20 lg:h-20 bg-elevate-soft text-elevate-primary rounded-[1.5rem] flex items-center justify-center text-3xl lg:text-4xl animate-wiggle shrink-0 shadow-sm"><i class="ph-fill ph-x-circle"></i></div>
                </div>
            </div>

             
            <div class="animate-enter bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden min-h-[500px]" style="animation-delay: 500ms">
                
                
                <div class="flex flex-wrap md:flex-nowrap border-b border-slate-100 bg-elevate-soft/50 p-3 gap-3 sticky top-0 z-20 no-print backdrop-blur-md">
                    <button @click="activeTab = 'hadir'" :class="activeTab === 'hadir' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60 hover:text-elevate-dark'" class="flex-1 md:flex-none py-3.5 px-6 rounded-2xl text-sm font-bold whitespace-nowrap transition-all">Hadir / Terlambat</button>
                    <button @click="activeTab = 'belum'" :class="activeTab === 'belum' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60 hover:text-elevate-dark'" class="flex-1 md:flex-none py-3.5 px-6 rounded-2xl text-sm font-bold whitespace-nowrap transition-all">
                        Belum <span class="hidden sm:inline">Absen</span> <span class="ml-2 px-2 py-1 bg-[#FDE7E9] text-[#D13438] rounded-lg text-xs"><?php echo e($belumAbsenList->total()); ?></span>
                    </button>
                    <button @click="activeTab = 'lain'" :class="activeTab === 'lain' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60 hover:text-elevate-dark'" class="flex-1 md:flex-none py-3.5 px-6 rounded-2xl text-sm font-bold whitespace-nowrap transition-all">Sakit / Izin / Alfa</button>
                    
                    
                    <button @click="activeTab = 'rekapSemester'" :class="activeTab === 'rekapSemester' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60 hover:text-elevate-dark'" class="flex-1 md:flex-none py-3.5 px-6 rounded-2xl text-sm font-bold whitespace-nowrap transition-all text-elevate-primary">Rekap Semester</button>

                    
                    <button onclick="openChecklistModal()" class="ml-auto bg-elevate-dark hover:bg-elevate-primary text-white px-6 py-3.5 rounded-2xl text-sm font-bold shadow-lg shadow-elevate-dark/20 transition-all flex items-center gap-2 active:scale-95 border border-transparent shrink-0">
                        <i class="ph-bold ph-checks text-lg"></i> <span class="hidden sm:inline">Input Per Kelas</span>
                    </button>
                </div>

                <div class="w-full bg-white">
                    
                     
                    <div x-show="activeTab === 'hadir'" class="w-full">
                        <div class="grid grid-cols-1 gap-0">
                            <?php $__empty_1 = true; $__currentLoopData = $attendancesHadir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative p-5 border-b border-slate-50 hover:bg-elevate-soft/30 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($att->status == 'Terlambat' ? 'bg-[#D83B01]' : 'bg-[#107C10]'); ?> hidden group-hover:block rounded-r-md"></div>
                                    <div class="flex items-center gap-5 overflow-hidden w-full">
                                        <div class="w-14 h-14 rounded-2xl <?php echo e($att->status == 'Terlambat' ? 'bg-[#FFEFD6] text-[#D83B01]' : 'bg-[#DFF6DD] text-[#107C10]'); ?> flex items-center justify-center font-black text-sm shrink-0 border border-transparent">
                                             <?php echo e($attendancesHadir->firstItem() + $index); ?>

                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-black text-lg text-elevate-dark truncate group-hover:text-elevate-primary transition-colors"><?php echo e($att->student->name); ?></h4>
                                            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs font-bold">
                                                <span class="text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1 rounded-lg"><?php echo e($att->student->schoolClass->name ?? '-'); ?></span>
                                                <span class="flex items-center gap-1.5 text-slate-600">
                                                    <i class="ph-bold ph-arrow-right-circle text-[#107C10] text-base"></i> <?php echo e($att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('H:i') : '-'); ?>

                                                </span>
                                                <span class="flex items-center gap-1.5 text-slate-600">
                                                    <i class="ph-bold ph-arrow-left-circle text-elevate-primary text-base"></i> <?php echo e($att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('H:i') : '-'); ?>

                                                </span>
                                                
                                                <?php if($att->status == 'Terlambat'): ?>
                                                    <span class="text-[#D83B01] uppercase tracking-wider text-[10px] bg-[#FFEFD6] px-3 py-1 rounded-full border border-[#FFD8A8]">
                                                        Terlambat
                                                    </span>
                                                    <?php if($att->notes): ?>
                                                        <span class="text-[#D13438] text-[10px] bg-[#FDE7E9] px-3 py-1 rounded-full border border-[#F4C3C9] animate-pulse">
                                                            <?php echo e($att->notes); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModal(<?php echo e($att->id); ?>, '<?php echo e(addslashes($att->student->name)); ?>', '<?php echo e($att->status); ?>', `<?php echo e(addslashes($att->notes ?? '')); ?>`, '<?php echo e($att->time_in); ?>', '<?php echo e($att->time_out); ?>')" 
                                        class="p-3 ml-4 text-slate-400 hover:text-white hover:bg-elevate-peach-dark rounded-xl transition-all no-print shrink-0 active:scale-95">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-24">
                                    <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary"><i class="ph-duotone ph-coffee text-5xl"></i></div>
                                    <p class="text-elevate-dark/60 font-semibold text-lg">Belum ada data kehadiran.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($attendancesHadir->hasPages()): ?>
                            <div class="p-6 border-t border-slate-100 bg-white">
                                <?php echo e($attendancesHadir->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div x-show="activeTab === 'belum'" style="display: none;" class="w-full">
                         <?php if($belumAbsenList->total() > 0): ?>
                            <div class="p-6 bg-[#FDE7E9] border-b border-[#F4C3C9] flex flex-col md:flex-row items-center justify-between gap-5 no-print">
                                <div class="flex items-center gap-4 text-[#D13438]">
                                    <div class="p-3 bg-white rounded-xl shadow-sm shrink-0"><i class="ph-fill ph-warning-octagon text-3xl"></i></div>
                                    <div>
                                        <h4 class="font-black text-lg">Absensi Massal</h4>
                                        <p class="text-sm font-semibold opacity-90"><?php echo e($belumAbsenList->total()); ?> siswa akan ditandai Alfa.</p>
                                    </div>
                                </div>
                                <form id="bulk-alpha-form" action="<?php echo e(route('reports.bulkAlpha')); ?>" method="POST" class="w-full md:w-auto">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>">
                                    <input type="hidden" name="type" value="Harian">
                                    <button type="button" onclick="confirmBulkAlpha('<?php echo e($belumAbsenList->total()); ?>')" 
                                        class="w-full bg-[#D13438] hover:bg-[#A4262C] text-white text-sm font-bold px-6 py-3.5 rounded-2xl shadow-lg shadow-[#D13438]/30 transition-all flex items-center justify-center gap-2 active:scale-95 border border-transparent">
                                        <i class="ph-bold ph-check-circle text-lg"></i> Proses Alfa
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-1 gap-0">
                            <?php $__empty_1 = true; $__currentLoopData = $belumAbsenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative p-5 border-b border-slate-50 hover:bg-elevate-soft/30 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#D13438] hidden group-hover:block rounded-r-md"></div>
                                    <div class="flex items-center gap-5 overflow-hidden">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center font-black text-lg shrink-0">!</div>
                                        <div class="min-w-0">
                                            <h4 class="font-black text-lg text-elevate-dark truncate"><?php echo e($student->name); ?></h4>
                                            <p class="text-sm font-bold text-slate-500 mt-0.5"><?php echo e($student->schoolClass->name ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    <button onclick="openManualModalDaily(<?php echo e($student->id); ?>, '<?php echo e(addslashes($student->name)); ?>')" 
                                        class="inline-flex items-center gap-2 bg-white border-2 border-slate-100 text-elevate-dark px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-elevate-soft hover:border-elevate-soft transition-all shadow-sm no-print shrink-0 active:scale-95">
                                        Input <span class="hidden md:inline">Manual</span>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-24 text-[#107C10] font-black text-xl">Semua Aman!</div>
                            <?php endif; ?>
                        </div>
                        
                       <?php if($belumAbsenList->hasPages()): ?>
                            <div class="p-6 border-t border-slate-100 bg-white">
                                <?php echo e($belumAbsenList->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>

                
                    
                    <div x-show="activeTab === 'lain'" style="display: none;" class="w-full">
                         <div class="grid grid-cols-1 gap-0">
                            <?php $__empty_1 = true; $__currentLoopData = $attendancesLain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative p-5 border-b border-slate-50 hover:bg-elevate-soft/30 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($att->status == 'Alfa' ? 'bg-[#D13438]' : ($att->status == 'Izin' ? 'bg-[#D83B01]' : 'bg-elevate-primary')); ?> hidden group-hover:block rounded-r-md"></div>
                                    <div class="flex items-center gap-5 overflow-hidden">
                                        <div class="w-14 h-14 rounded-2xl <?php echo e($att->status == 'Alfa' ? 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]' : ($att->status == 'Izin' ? 'bg-[#FFEFD6] text-[#D83B01] border-[#FFD8A8]' : 'bg-elevate-soft text-elevate-primary border-slate-200')); ?> border flex items-center justify-center font-black text-lg shrink-0">
                                             <?php echo e(substr($att->status, 0, 1)); ?>

                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-black text-lg text-elevate-dark truncate"><?php echo e($att->student->name); ?></h4>
                                            <div class="flex flex-wrap items-center gap-3 mt-1.5">
                                                <span class="px-3 py-1 rounded-lg text-xs font-bold <?php echo e($att->status == 'Alfa' ? 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]' : ($att->status == 'Izin' ? 'bg-[#FFEFD6] text-[#D83B01] border-[#FFD8A8]' : 'bg-elevate-soft text-elevate-primary border-slate-200')); ?> border uppercase"><?php echo e($att->status); ?></span>
                                                <?php if($att->notes): ?>
                                                    <span class="text-sm text-slate-500 font-semibold italic max-w-[150px] md:max-w-none truncate">"<?php echo e($att->notes); ?>"</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModal(<?php echo e($att->id); ?>, '<?php echo e(addslashes($att->student->name)); ?>', '<?php echo e($att->status); ?>', `<?php echo e(addslashes($att->notes ?? '')); ?>`, '', '')" 
                                        class="p-3 ml-4 text-slate-400 hover:text-white hover:bg-elevate-peach-dark rounded-xl transition-all no-print shrink-0 active:scale-95">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-24 text-elevate-dark/60 font-semibold text-lg italic">Tidak ada data lain.</div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($attendancesLain->hasPages()): ?>
                            <div class="p-6 border-t border-slate-100 bg-white">
                                <?php echo e($attendancesLain->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div x-show="activeTab === 'rekapSemester'" style="display: none;" class="w-full p-6 animate-enter">
                        <div class="mb-5 border-b border-slate-100 pb-5">
                            <h3 class="font-black text-xl text-elevate-dark flex items-center gap-2">
                                <i class="ph-bold ph-chart-bar text-elevate-primary"></i> Rekapitulasi Ketidakhadiran
                            </h3>
                            <p class="text-sm font-semibold text-slate-500 mt-1">
                                Akumulasi (Sakit, Izin, Alfa) per kelas berdasarkan data semester berjalan 
                                <span class="font-bold text-elevate-dark bg-slate-100 px-2 py-0.5 rounded-md ml-1">
                                    <?php echo e(\Carbon\Carbon::parse($semesterStart)->translatedFormat('d M Y')); ?> s/d <?php echo e(\Carbon\Carbon::parse($semesterEnd)->translatedFormat('d M Y')); ?>

                                </span>.
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse min-w-[600px]">
                                    <thead class="bg-elevate-soft/80 text-elevate-primary text-xs uppercase font-black border-b border-slate-200">
                                        <tr>
                                            <th class="p-4 w-16 text-center">No</th>
                                            <th class="p-4">Nama Kelas</th>
                                            <th class="p-4 text-center bg-[#FFEFD6]/30 text-[#D83B01]">Sakit</th>
                                            <th class="p-4 text-center bg-[#FFEFD6]/30 text-[#D83B01]">Izin</th>
                                            <th class="p-4 text-center bg-[#FDE7E9]/30 text-[#D13438]">Alfa</th>
                                            <th class="p-4 text-center bg-slate-100 text-elevate-dark">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-sm">
                                        <?php $__empty_1 = true; $__currentLoopData = $rekapSemester ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $rekap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="p-4 text-center font-bold text-slate-400"><?php echo e($index + 1); ?></td>
                                                <td class="p-4 font-black text-elevate-dark"><?php echo e($rekap->name); ?></td>
                                                
                                                <td class="p-4 text-center font-bold text-slate-600 bg-[#FFEFD6]/10"><?php echo e($rekap->total_sakit ?: '-'); ?></td>
                                                <td class="p-4 text-center font-bold text-slate-600 bg-[#FFEFD6]/10"><?php echo e($rekap->total_izin ?: '-'); ?></td>
                                                <td class="p-4 text-center font-bold <?php echo e($rekap->total_alfa > 0 ? 'text-[#D13438]' : 'text-slate-600'); ?> bg-[#FDE7E9]/10">
                                                    <?php echo e($rekap->total_alfa ?: '-'); ?>

                                                </td>
                                                
                                                <td class="p-4 text-center font-black text-elevate-dark bg-slate-50">
                                                    <?php $totalTidakHadir = $rekap->total_sakit + $rekap->total_izin + $rekap->total_alfa; ?>
                                                    <span class="<?php echo e($totalTidakHadir > 0 ? 'text-elevate-dark' : 'text-slate-300'); ?>">
                                                        <?php echo e($totalTidakHadir); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="py-16 text-center">
                                                    <div class="w-16 h-16 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-primary"><i class="ph-duotone ph-folder-open text-3xl"></i></div>
                                                    <p class="text-slate-500 font-bold text-base">Belum ada data rekap semester.</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
      
    <template x-teleport="body">
        <div id="manualModalDaily" class="hidden fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity no-print">
            <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
                <div class="bg-elevate-peach-light/30 px-6 py-5 flex justify-between items-center border-b border-elevate-peach/30">
                    <h3 class="font-black text-elevate-dark flex items-center gap-3 text-lg"><i class="ph-bold ph-pencil-line text-elevate-peach-dark"></i> Input Manual</h3>
                    <button onclick="closeManualModalDaily()" class="text-slate-400 hover:text-elevate-dark bg-white hover:bg-elevate-soft p-2 rounded-full transition-colors"><i class="ph-bold ph-x text-lg"></i></button>
                </div>
                <form action="<?php echo e(route('reports.storeManual')); ?>" method="POST" class="p-6 md:p-8 space-y-5">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="attendance_type" value="Harian">
                    <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>">
                    <input type="hidden" name="student_id" id="daily-manual-id">
                    
                    <div class="bg-elevate-soft p-4 rounded-2xl border border-slate-100 text-center">
                        <span class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Siswa</span>
                        <p id="daily-manual-name-display" class="text-xl font-black text-elevate-dark truncate px-2"></p>
                    </div>

                    <div x-data="{ status: 'Hadir' }">
                        <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Status</label>
                        <select name="status" id="daily-manual-status" x-model="status" onchange="toggleTimeInput()" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:ring-elevate-accent/30 focus:bg-white focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                            <option value="Hadir">Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alfa">Alfa</option> 
                        </select>
                        
                        <div x-show="status === 'Alfa'" class="mt-3 text-xs font-bold text-[#D13438] bg-[#FDE7E9] p-4 rounded-2xl border border-[#F4C3C9] flex items-start gap-3">
                            <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i> 
                            <div>
                                <span>Hati-hati!</span>
                                <span class="block font-medium opacity-80 mt-0.5 leading-relaxed">Siswa akan otomatis mendapatkan Poin Pelanggaran.</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div id="manual-time-wrapper">
                            <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Masuk</label>
                            <input type="time" name="time_in" id="daily-manual-time-in" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:ring-elevate-accent/30 focus:bg-white focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                        </div>
                        
                        <div id="manual-notes-wrapper" class="col-span-1">
                            <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Catatan</label>
                            <input type="text" name="notes" placeholder="Opsional" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:ring-elevate-accent/30 focus:bg-white focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-elevate-dark hover:bg-elevate-primary text-white font-bold h-14 rounded-2xl transition-colors shadow-lg shadow-elevate-dark/20 active:scale-95">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    
    <template x-teleport="body">
        <div id="editAttendanceModal" class="hidden fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity no-print">
            <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
                <div class="bg-elevate-soft px-6 py-5 flex justify-between items-center border-b border-slate-100">
                    <h3 class="font-black text-elevate-dark flex items-center gap-3 text-lg"><i class="ph-bold ph-pencil-simple text-elevate-primary"></i> Edit Kehadiran</h3>
                    <button onclick="closeEditModal()" class="text-slate-400 hover:text-elevate-dark bg-white hover:bg-slate-100 p-2 rounded-full transition-colors"><i class="ph-bold ph-x text-lg"></i></button>
                </div>
                <form id="editForm" method="POST" class="p-6 md:p-8 space-y-5">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="text-center mb-6">
                        <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Mengedit Siswa</p>
                        <p id="modal-student-name" class="text-2xl font-black text-elevate-dark truncate px-2"></p>
                    </div>

                    <div x-data="{ status: '' }">
                        <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Status</label>
                        <select name="status" id="modal-status" onchange="checkEditStatus(this.value)" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:ring-elevate-accent/30 focus:bg-white focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                            <option value="Hadir">Hadir</option>
                            <option value="Terlambat">Terlambat (Otomatis)</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alfa">Alfa</option> 
                        </select>

                        <div id="edit-alfa-alert" class="hidden mt-3 text-xs font-bold text-[#D13438] bg-[#FDE7E9] p-4 rounded-2xl border border-[#F4C3C9] flex items-start gap-3">
                            <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i> 
                            <div>
                                <span>Hati-hati!</span>
                                <span class="block font-medium opacity-80 mt-0.5 leading-relaxed">Mengubah menjadi Alfa akan menambah Poin Pelanggaran.</span>
                            </div>
                        </div>
                    </div>

                    <div id="edit-time-container" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Masuk</label>
                            <input type="time" name="time_in" id="modal-time_in" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:ring-elevate-accent/30 focus:bg-white focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Pulang</label>
                            <input type="time" name="time_out" id="modal-time_out" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:ring-elevate-accent/30 focus:bg-white focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Catatan</label>
                        <textarea name="notes" id="modal-notes" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:ring-elevate-accent/30 focus:bg-white focus:border-elevate-accent font-bold text-elevate-dark p-4 transition-colors" rows="2"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEditModal()" class="flex-1 h-14 bg-white border-2 border-slate-100 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors">Batal</button>
                        <button type="submit" class="flex-1 h-14 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 active:scale-95 transition-colors">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- MODAL CHECKLIST PER KELAS -->
    <template x-teleport="body">
        <div id="checklistModal" class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
            <div class="bg-white rounded-[2rem] w-full max-w-4xl shadow-2xl overflow-hidden animate-enter flex flex-col max-h-[90vh]" x-data="checklistHandlerDaily()">
                
                <div class="bg-elevate-dark px-8 py-5 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="font-black text-white flex items-center gap-3 text-xl">
                            <i class="ph-bold ph-list-checks text-elevate-accent"></i> Input Massal Per Kelas
                        </h3>
                        <p class="text-elevate-soft/80 font-medium text-xs mt-1">
                            Absensi Harian • <?php echo e($selectedDate_db->translatedFormat('d F Y')); ?>

                        </p>
                    </div>
                    <button onclick="closeChecklistModal()" class="text-white/70 hover:text-white transition bg-white/10 p-2.5 rounded-full hover:bg-white/20">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                <div class="bg-white grow flex flex-col overflow-hidden">
                    <div class="flex items-center gap-4 bg-white p-6 shrink-0 border-b border-slate-100">
                        <div class="w-full">
                            <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Pilih Kelas</label>
                            <select x-model="selectedClass" @change="fetchStudents()" class="w-full border-slate-200 bg-elevate-soft rounded-2xl font-bold text-elevate-dark h-14 px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent transition-colors">
                                <option value="">-- Pilih Kelas --</option>
                                <?php $__currentLoopData = $allClasses ?? \App\Models\SchoolClass::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="shrink-0 pt-6" x-show="loading">
                            <div class="w-8 h-8 border-4 border-elevate-primary border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>

                    <form id="checklistForm" action="<?php echo e(route('reports.storeClass')); ?>" method="POST" @submit.prevent="submitChecklist" x-show="students.length > 0" style="display: none;" class="p-6">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="class_id" :value="selectedClass">
                        <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>">
                        <input type="hidden" name="type" value="Harian">
                        <button type="submit" x-ref="submitBtn" class="hidden"></button>

                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden w-full">
                            <div class="overflow-x-auto h-[45vh] custom-scrollbar"> 
                                <table class="w-full text-left border-collapse min-w-[600px]">
                                    <thead class="bg-elevate-soft/50 text-elevate-primary text-xs uppercase font-black sticky top-0 z-10 border-b border-slate-100 backdrop-blur-sm">
                                        <tr>
                                            <th class="p-5 w-12 text-center bg-elevate-soft/90">No</th>
                                            <th class="p-5 bg-elevate-soft/90">Nama Siswa</th>
                                            <th class="p-5 text-center bg-elevate-soft/90 w-48">Status</th>
                                            <th class="p-5 w-1/4 bg-elevate-soft/90">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 text-sm">
                                        <template x-for="(student, index) in students" :key="student.id">
                                            <tr class="hover:bg-elevate-soft/30 transition-colors">
                                                <td class="p-5 text-center font-bold text-slate-400" x-text="index + 1"></td>
                                                <td class="p-5 font-black text-elevate-dark" x-text="student.name"></td>
                                                
                                                <td class="p-4 text-center">
                                                    <div class="inline-flex bg-white rounded-xl p-1.5 border border-slate-200 shadow-sm gap-1.5">
                                                        <label class="cursor-pointer group relative">
                                                            <input type="radio" :name="'students['+index+'][status]'" value="Hadir" x-model="student.status" class="peer sr-only">
                                                            <div class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-black text-slate-400 peer-checked:bg-[#107C10] peer-checked:text-white transition-all hover:bg-slate-100">H</div>
                                                        </label>
                                                        <label class="cursor-pointer group relative">
                                                            <input type="radio" :name="'students['+index+'][status]'" value="Sakit" x-model="student.status" class="peer sr-only">
                                                            <div class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-black text-slate-400 peer-checked:bg-elevate-primary peer-checked:text-white transition-all hover:bg-slate-100">S</div>
                                                        </label>
                                                        <label class="cursor-pointer group relative">
                                                            <input type="radio" :name="'students['+index+'][status]'" value="Izin" x-model="student.status" class="peer sr-only">
                                                            <div class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-black text-slate-400 peer-checked:bg-[#D83B01] peer-checked:text-white transition-all hover:bg-slate-100">I</div>
                                                        </label>
                                                        <label class="cursor-pointer group relative">
                                                            <input type="radio" :name="'students['+index+'][status]'" value="Alfa" x-model="student.status" class="peer sr-only">
                                                            <div class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-black text-slate-400 peer-checked:bg-[#D13438] peer-checked:text-white transition-all hover:bg-slate-100">A</div>
                                                        </label>
                                                    </div>
                                                </td>

                                                <td class="p-4">
                                                    <input type="hidden" :name="'students['+index+'][id]'" :value="student.id">
                                                    <input type="text" :name="'students['+index+'][notes]'" x-model="student.notes" class="w-full text-sm font-semibold border-slate-200 focus:ring-elevate-accent/30 focus:border-elevate-accent focus:bg-white rounded-xl h-11 bg-elevate-soft transition-colors px-4" placeholder="Keterangan...">
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    
                    <div x-show="!loading && students.length === 0 && selectedClass" class="text-center py-12 text-slate-400 flex-1 flex flex-col justify-center items-center" style="display: none;">
                        <i class="ph-duotone ph-users text-5xl mb-4 text-elevate-soft"></i>
                        <p class="font-bold">Tidak ada siswa di kelas ini.</p>
                    </div>
                </div>

                <div class="p-6 bg-white border-t border-slate-100 shrink-0 flex justify-end gap-3 z-20" x-show="students.length > 0" style="display: none;">
                    <button type="button" onclick="closeChecklistModal()" class="px-6 py-3.5 rounded-2xl font-bold text-elevate-dark bg-white border-2 border-slate-100 hover:bg-elevate-soft transition-colors">Batal</button>
                    <button type="button" @click="$refs.submitBtn.click()" class="px-8 py-3.5 bg-elevate-dark hover:bg-elevate-primary text-white rounded-2xl font-bold shadow-lg shadow-elevate-dark/20 transition-all active:scale-95 flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Absensi
                    </button>
                </div>
            </div>
        </div>
    </template>

   <script>
        function confirmBulkAlpha(count) {
            Swal.fire({
                title: 'Tandai ' + count + ' Siswa Alfa?',
                html: "Siswa yang belum absen akan dicatat <b>Alfa</b>.<br>" +
                      "<div class='mt-4 text-[#D13438] font-semibold bg-[#FDE7E9] p-3 rounded-xl border border-[#F4C3C9] text-sm'>Siswa akan otomatis mendapatkan Poin Pelanggaran!</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D13438',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Proses & Kurangi Poin!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulk-alpha-form').submit();
                }
            })
        }
        
        function toggleTimeInput() {
            const status = document.getElementById('daily-manual-status').value;
            const timeWrapper = document.getElementById('manual-time-wrapper');
            const notesWrapper = document.getElementById('manual-notes-wrapper');
            
            if (status === 'Hadir' || status === 'Terlambat') {
                timeWrapper.classList.remove('hidden');
                notesWrapper.classList.remove('col-span-2');
                notesWrapper.classList.add('col-span-1');
            } else {
                timeWrapper.classList.add('hidden');
                notesWrapper.classList.remove('col-span-1');
                notesWrapper.classList.add('col-span-2');
            }
        }

        function checkEditStatus(val) {
            const alertBox = document.getElementById('edit-alfa-alert');
            const timeContainer = document.getElementById('edit-time-container');
            
            if(val === 'Alfa') {
                alertBox.classList.remove('hidden');
            } else {
                alertBox.classList.add('hidden');
            }

            if (val === 'Hadir' || val === 'Terlambat') {
                timeContainer.classList.remove('hidden');
            } else {
                timeContainer.classList.add('hidden');
            }
        }

        function openManualModalDaily(id, name) { 
            document.getElementById('daily-manual-id').value = id; 
            document.getElementById('daily-manual-name-display').textContent = name; 
            
            const statusSelect = document.getElementById('daily-manual-status');
            statusSelect.value = 'Hadir';
            statusSelect.dispatchEvent(new Event('change')); 
            
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('daily-manual-time-in').value = `${hours}:${minutes}`;
            
            toggleTimeInput(); 
            
            document.getElementById('manualModalDaily').classList.remove('hidden'); 
        }
        function closeManualModalDaily() { 
            document.getElementById('manualModalDaily').classList.add('hidden'); 
        }
        
       function openEditModal(id, name, status, notes, timeIn, timeOut) {
            // Pindahkan ke sini (ke dalam fungsi)
            const modal = document.getElementById('editAttendanceModal');
            const form = document.getElementById('editForm');

            form.action = '<?php echo e(route('reports.update', ['attendance' => '__ID__'])); ?>'.replace('__ID__', id);
            document.getElementById('modal-student-name').textContent = name;
            
            const statusSelect = document.getElementById('modal-status');
            statusSelect.value = status;
            
            document.getElementById('modal-notes').value = notes;
            document.getElementById('modal-time_in').value = timeIn ? timeIn.substring(0,5) : '';
            document.getElementById('modal-time_out').value = timeOut ? timeOut.substring(0,5) : '';
            
            checkEditStatus(status); 
            
            modal.classList.remove('hidden');
        } 
            function closeEditModal() { 
            // Cari elemennya di dalam fungsi juga
            document.getElementById('editAttendanceModal').classList.add('hidden'); 
        }      

        function checklistHandlerDaily() {
            return {
                selectedClass: '',
                students: [],
                loading: false,

                async fetchStudents() {
                    if (!this.selectedClass) {
                        this.students = [];
                        return;
                    }
                    
                    this.loading = true;
                    this.students = [];

                    try {
                        const url = `<?php echo e(route('reports.getStudentsByClass')); ?>?class_id=${this.selectedClass}&date=<?php echo e($selectedDate_db->format('Y-m-d')); ?>&type=Harian`;
                        const response = await fetch(url);
                        const data = await response.json();
                        this.students = data;
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({icon: 'error', title: 'Error', text: 'Gagal memuat data siswa', customClass: { popup: 'rounded-[2rem]' }});
                    } finally {
                        this.loading = false;
                    }
                },

                async submitChecklist(e) {
                    e.preventDefault(); 
                    const form = e.target.closest('form') || e.target;
                    const alfaCount = this.students.filter(s => s.status === 'Alfa').length;
                    
                    if (alfaCount > 0) {
                        const result = await Swal.fire({
                            title: 'Konfirmasi Simpan',
                            html: `Anda menandai <b>${alfaCount} siswa Alfa</b>.<br>Poin pelanggaran akan otomatis ditambahkan.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#2c3f61', 
                            confirmButtonText: 'Ya, Simpan',
                            cancelButtonText: 'Batal',
                            customClass: { popup: 'rounded-[2rem]' }
                        });

                        if (!result.isConfirmed) return;
                    }

                    Swal.fire({
                        title: 'Menyimpan Data...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        customClass: { popup: 'rounded-[2rem]' },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    HTMLFormElement.prototype.submit.call(form);
                }
            }
        }

        function openChecklistModal() {
            document.getElementById('checklistModal').classList.remove('hidden');
        }

        function closeChecklistModal() {
            document.getElementById('checklistModal').classList.add('hidden');
        }
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/reports/daily.blade.php ENDPATH**/ ?>