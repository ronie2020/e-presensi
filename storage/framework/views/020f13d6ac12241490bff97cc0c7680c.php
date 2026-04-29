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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        table td, table th { white-space: normal !important; word-wrap: break-word !important; vertical-align: top !important; line-height: 1.6 !important; }
        @media print { .no-print { display: none !important; } }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-32" x-data="{ activeTab: '<?php echo e(request('activeTab', 'hadir')); ?>', reportType: '<?php echo e(request('report_type', 'daily')); ?>', viewMode: 'list', loading: false, navigate(url) { this.loading = true; setTimeout(() => { window.location.href = url; }, 200); }, submitFilter() { this.loading = true; setTimeout(() => { this.$el.closest('form').submit(); }, 200); } }">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <template x-teleport="body">
            <div x-show="loading" class="fixed inset-0 z-[100] bg-elevate-dark/40 backdrop-blur-sm flex items-center justify-center">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl flex flex-col items-center">
                    <div class="w-12 h-12 border-4 border-elevate-primary border-t-transparent rounded-full animate-spin mb-4"></div>
                    <span class="text-sm font-black text-elevate-dark tracking-wider uppercase animate-pulse">Memuat Data...</span>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 no-print">
                <div class="animate-enter bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach rounded-[2rem] p-6 lg:p-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 relative overflow-hidden flex flex-col justify-between min-h-[180px] lg:min-h-[200px] border border-white/60">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/30 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <h1 class="text-2xl lg:text-3xl font-black mb-1 tracking-tight flex items-center gap-2">Rekap Keagamaan</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold">Laporan ibadah siswa.</p>
                    </div>
                    <div class="relative z-10 mt-6 bg-white/40 backdrop-blur-md p-1.5 rounded-2xl flex border border-white/50 shadow-sm">
                        <button @click="navigate('<?php echo e(route('reports.religious', array_merge(request()->all(), ['activity' => 'Dhuha']))); ?>')" class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 <?php echo e($selectedActivity == 'Dhuha' ? 'bg-white text-elevate-peach-dark shadow-sm' : 'text-elevate-dark hover:bg-white/60'); ?>">
                            <i class="ph-bold ph-sun text-lg"></i> Dhuha
                        </button>
                        <button @click="navigate('<?php echo e(route('reports.religious', array_merge(request()->all(), ['activity' => 'Dhuhur']))); ?>')" class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 <?php echo e($selectedActivity == 'Dhuhur' ? 'bg-white text-elevate-primary shadow-sm' : 'text-elevate-dark hover:bg-white/60'); ?>">
                            <i class="ph-fill ph-moon-stars text-lg"></i> Dhuhur
                        </button>
                    </div>
                </div>

                <div class="animate-enter lg:col-span-2 bg-white rounded-[2rem] p-6 lg:p-8 border border-slate-100 shadow-xl shadow-slate-200/40 relative" style="animation-delay: 100ms">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5 gap-4">
                        <h2 class="text-xl font-black text-elevate-dark flex items-center gap-3"><span class="w-1.5 h-6 bg-elevate-accent rounded-full"></span> Filter Data</h2>
                        <div class="bg-elevate-soft p-1.5 rounded-2xl flex w-full md:w-auto">
                            <button @click="reportType = 'daily'" :class="reportType === 'daily' ? 'bg-white text-elevate-dark shadow-sm' : 'text-slate-500'" class="flex-1 px-5 py-2.5 rounded-xl text-xs font-bold transition-all">Harian</button>
                            <button @click="reportType = 'weekly'" :class="reportType === 'weekly' ? 'bg-white text-elevate-dark shadow-sm' : 'text-slate-500'" class="flex-1 px-5 py-2.5 rounded-xl text-xs font-bold transition-all">Mingguan</button>
                            <button @click="reportType = 'monthly'" :class="reportType === 'monthly' ? 'bg-white text-elevate-dark shadow-sm' : 'text-slate-500'" class="flex-1 px-5 py-2.5 rounded-xl text-xs font-bold transition-all">Bulanan</button>
                        </div>
                    </div>
                    <form action="<?php echo e(route('reports.religious')); ?>" method="GET" class="flex flex-col md:flex-row gap-4 w-full" @submit.prevent="submitFilter">
                        <input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                        <input type="hidden" name="activeTab" x-model="activeTab">
                        <input type="hidden" name="report_type" x-model="reportType">
                        <div class="flex-1 w-full">
                            <div x-show="reportType === 'daily'"><input type="date" name="date" value="<?php echo e(request('date', $selectedDate_db->format('Y-m-d'))); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold h-14 text-sm px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent transition-colors"></div>
                            <div x-show="reportType === 'weekly'" style="display: none;"><input type="week" name="week" value="<?php echo e(request('week', date('Y-\WW'))); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold h-14 text-sm px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent transition-colors"></div>
                            <div x-show="reportType === 'monthly'" style="display: none;"><input type="month" name="month" value="<?php echo e(request('month', date('Y-m'))); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold h-14 text-sm px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent transition-colors"></div>
                        </div>
                        <div class="flex gap-3 w-full md:w-auto">
                            <button type="submit" class="flex-1 md:flex-none bg-elevate-dark hover:bg-elevate-primary text-white px-6 rounded-2xl h-14 font-bold text-sm shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 active:scale-95 transition-colors"><i class="ph-bold ph-magnifying-glass text-lg"></i> <span class="md:hidden">Cari</span></button>
                            <a href="<?php echo e(route('reports.printReligious', request()->all())); ?>" target="_blank" class="flex-1 md:flex-none bg-white border-2 border-slate-100 text-elevate-dark hover:bg-elevate-soft px-6 rounded-2xl h-14 font-bold text-sm flex items-center justify-center gap-2 active:scale-95 transition-colors"><i class="ph-bold ph-printer text-xl"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="mb-8 no-print">
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/30"><p class="text-[10px] md:text-xs font-bold text-elevate-primary uppercase mb-2">Total Siswa</p><h3 class="text-3xl md:text-4xl font-black text-elevate-dark"><?php echo e($hadirCount + $izinUzurCount + $alfaCount + $belumAbsenCount); ?></h3></div>
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/30"><p class="text-[10px] md:text-xs font-bold text-[#107C10] uppercase mb-2">Total Hadir</p><h3 class="text-3xl md:text-4xl font-black text-elevate-dark"><?php echo e($hadirCount); ?></h3></div>
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/30"><p class="text-[10px] md:text-xs font-bold text-[#D13438] uppercase mb-2">Belum Hadir</p><h3 class="text-3xl md:text-4xl font-black text-elevate-dark"><?php echo e($belumAbsenCount + $alfaCount); ?></h3></div>
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/30"><p class="text-[10px] md:text-xs font-bold text-elevate-primary uppercase mb-2">Sakit / Izin</p><h3 class="text-3xl md:text-4xl font-black text-elevate-dark"><?php echo e($izinUzurCount); ?></h3></div>
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/30 col-span-2 lg:col-span-1 flex items-center justify-between">
                         <div>
                            <p class="text-[10px] md:text-xs font-bold text-elevate-primary uppercase mb-1">Kehadiran</p>
                             <?php $totalAll = $hadirCount + $izinUzurCount + $alfaCount + $belumAbsenCount; $percentage = $totalAll > 0 ? round(($hadirCount / $totalAll) * 100) : 0; ?>
                            <h3 class="text-3xl md:text-4xl font-black text-elevate-dark"><?php echo e($percentage); ?>%</h3>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 lg:col-span-2">
                        <h3 class="text-lg font-black text-elevate-dark flex items-center gap-3 mb-6"><i class="ph-fill ph-chart-bar text-elevate-accent text-xl"></i> Analisis Tren Kehadiran</h3>
                        <div id="chartTrend" class="w-full min-h-[300px]"></div>
                    </div>
                    <div class="bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40">
                        <h3 class="text-lg font-black text-elevate-dark flex items-center gap-3 mb-6"><i class="ph-fill ph-pie-chart text-elevate-peach-dark text-xl"></i> Komposisi Hari Ini</h3>
                        <div id="chartDonut" class="w-full relative flex items-center justify-center"></div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mb-8 no-print">
                <div class="bg-white p-1.5 rounded-2xl inline-flex shadow-sm border border-slate-100">
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-elevate-dark text-white shadow-md' : 'text-slate-500 hover:text-elevate-dark hover:bg-elevate-soft/50'" class="px-8 py-3 rounded-xl text-sm font-bold transition-all"><i class="ph-bold ph-list-dashes mr-2"></i> Detail Siswa</button>
                    <button @click="viewMode = 'rekap'" :class="viewMode === 'rekap' ? 'bg-elevate-dark text-white shadow-md' : 'text-slate-500 hover:text-elevate-dark hover:bg-elevate-soft/50'" class="px-8 py-3 rounded-xl text-sm font-bold transition-all"><i class="ph-bold ph-chart-bar mr-2"></i> Rekap Per Kelas</button>
                </div>
            </div>

            
            <div x-show="viewMode === 'list'" class="animate-enter bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden min-h-[500px]">
                <div class="flex flex-wrap md:flex-nowrap border-b border-slate-100 bg-elevate-soft/50 p-3 gap-3 sticky top-0 z-20 no-print backdrop-blur-md">
                    <button @click="activeTab = 'hadir'" :class="activeTab === 'hadir' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60 hover:text-elevate-dark'" class="flex-1 md:flex-none py-3.5 px-6 rounded-2xl text-sm font-bold transition-all">Sudah Absen</button>
                    <button @click="activeTab = 'belum'" :class="activeTab === 'belum' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60 hover:text-elevate-dark'" class="flex-1 md:flex-none py-3.5 px-6 rounded-2xl text-sm font-bold transition-all">Belum Absen <span class="bg-[#FDE7E9] text-[#D13438] px-2 py-1 ml-1 rounded-lg text-xs"><?php echo e($belumAbsenList->total()); ?></span></button>
                    <button @click="activeTab = 'uzur'" :class="activeTab === 'uzur' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60 hover:text-elevate-dark'" class="flex-1 md:flex-none py-3.5 px-6 rounded-2xl text-sm font-bold transition-all">Ket. Lain</button>
                    <button onclick="openChecklistModal()" class="ml-auto bg-elevate-dark text-white hover:bg-elevate-primary px-6 py-3.5 rounded-2xl text-sm font-bold shadow-lg shadow-elevate-dark/20 transition-colors"><i class="ph-bold ph-checks text-lg mr-2"></i> Input Per Kelas</button>
                </div>
                <div class="p-0">
                    <div x-show="activeTab === 'hadir'" class="w-full">
                        <?php $__empty_1 = true; $__currentLoopData = $attendancesHadir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                             <div class="p-5 border-b border-slate-50 flex items-center justify-between hover:bg-elevate-soft/30 transition-colors">
                                <div class="flex items-center gap-5 w-full">
                                    <div class="w-14 h-14 rounded-2xl bg-[#DFF6DD] text-[#107C10] flex items-center justify-center font-black text-sm"><?php echo e($attendancesHadir->firstItem() + $index); ?></div>
                                    <div class="flex-1">
                                        <button type="button" class="font-black text-lg text-elevate-dark hover:text-elevate-primary transition-colors truncate"><?php echo e($attendance->student->name); ?></button>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="text-[10px] bg-[#DFF6DD] text-[#107C10] px-3 py-1 rounded-md font-black uppercase tracking-wider"><?php echo e($attendance->status); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <button onclick="openEditModalReligious(<?php echo e($attendance->id); ?>, '<?php echo e(addslashes($attendance->student->name)); ?>', '<?php echo e($attendance->status); ?>', `<?php echo e(addslashes($attendance->notes ?? '')); ?>`, '<?php echo e($attendance->activity); ?>')" class="p-3 text-slate-400 hover:text-white hover:bg-elevate-peach-dark rounded-xl transition-all"><i class="ph-bold ph-pencil-simple text-xl"></i></button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-24 text-elevate-dark/60 font-semibold text-lg italic">Belum ada data hadir.</div>
                        <?php endif; ?>
                    </div>

                    <div x-show="activeTab === 'belum'" style="display: none;" class="w-full">
                         <?php if($belumAbsenList->total() > 0): ?>
                            <div class="p-6 bg-[#FDE7E9] border-b border-[#F4C3C9] flex justify-between items-center no-print">
                                <div class="flex items-center gap-4 text-[#D13438]">
                                    <div class="p-3 bg-white rounded-xl shadow-sm shrink-0"><i class="ph-fill ph-warning-octagon text-3xl"></i></div> 
                                    <h4 class="font-black text-lg">Aksi Massal Diperlukan</h4>
                                </div>
                                <form id="bulk-alpha-religious-form" action="<?php echo e(route('reports.bulkAlpha')); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>"><input type="hidden" name="type" value="Keagamaan"><input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                                    <button type="button" onclick="confirmBulkAlphaReligious('<?php echo e($belumAbsenList->total()); ?>')" class="bg-[#D13438] text-white hover:bg-[#A4262C] transition-colors text-sm font-bold px-6 py-3.5 rounded-2xl shadow-lg shadow-[#D13438]/30"><i class="ph-bold ph-check-circle text-lg mr-2"></i> Tandai Semua Alfa</button>
                                </form>
                            </div>
                        <?php endif; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $belumAbsenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-5 border-b border-slate-50 flex items-center justify-between hover:bg-elevate-soft/30 transition-colors">
                                <div class="flex items-center gap-5"><div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-black text-lg">!</div><h4 class="font-black text-lg text-elevate-dark"><?php echo e($student->name); ?></h4></div>
                                <button onclick="openManualModalForStudent(<?php echo e($student->id); ?>, '<?php echo e(addslashes($student->name)); ?>')" class="bg-white border-2 border-slate-100 text-elevate-dark px-6 py-3 rounded-xl text-xs font-bold hover:bg-elevate-soft transition-colors">Input Manual</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-24 text-[#107C10] font-black text-xl">Semua Aman!</div>
                        <?php endif; ?>
                    </div>

                    <div x-show="activeTab === 'uzur'" style="display: none;" class="w-full">
                        <?php $__empty_1 = true; $__currentLoopData = $attendancesUzur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-5 border-b border-slate-50 flex items-center justify-between hover:bg-elevate-soft/30 transition-colors">
                                <div class="flex items-center gap-5 w-full">
                                    <div class="w-14 h-14 rounded-2xl <?php echo e($attendance->status == 'Alfa' ? 'bg-[#FDE7E9] text-[#D13438]' : 'bg-elevate-soft text-elevate-primary'); ?> flex items-center justify-center font-black text-lg"><?php echo e(substr($attendance->status, 0, 1)); ?></div>
                                    <h4 class="font-black text-lg text-elevate-dark"><?php echo e($attendance->student->name); ?></h4>
                                </div>
                                <button onclick="openEditModalReligious(<?php echo e($attendance->id); ?>, '<?php echo e(addslashes($attendance->student->name)); ?>', '<?php echo e($attendance->status); ?>', `<?php echo e(addslashes($attendance->notes ?? '')); ?>`, '<?php echo e($attendance->activity); ?>')" class="p-3 text-slate-400 hover:text-white hover:bg-elevate-peach-dark rounded-xl transition-all"><i class="ph-bold ph-pencil-simple text-xl"></i></button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-24 text-elevate-dark/60 font-semibold text-lg italic">Tidak ada data.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div x-show="viewMode === 'rekap'" style="display: none;" class="animate-enter bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead><tr class="bg-elevate-soft/50 text-elevate-primary text-xs uppercase font-black border-b border-slate-100"><th class="p-5">Kelas</th><th class="p-5 text-center">Total Siswa</th><th class="p-5 text-center text-elevate-peach-dark"><i class="ph-fill ph-sun"></i> Dhuha</th><th class="p-5 text-center text-elevate-primary"><i class="ph-fill ph-moon-stars"></i> Dhuhur</th></tr></thead>
                        <tbody class="text-sm font-bold text-elevate-dark divide-y divide-slate-50">
                            <?php $__currentLoopData = $classRecap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rekap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-elevate-soft/20 transition-colors">
                                <td class="p-5 font-black"><?php echo e($rekap->className); ?></td>
                                <td class="p-5 text-center text-lg"><?php echo e($rekap->total_siswa); ?></td>
                                <td class="p-5 bg-elevate-peach-light/10">
                                    <div class="w-full bg-slate-100 rounded-full h-3 mb-2 overflow-hidden"><div class="bg-elevate-peach-dark h-3 rounded-full" style="width: <?php echo e($rekap->dhuha['percent']); ?>%"></div></div>
                                    <div class="flex justify-between text-[10px] font-bold"><span class="text-[#D13438]">Alfa: <?php echo e($rekap->dhuha['alfa']); ?></span><span><?php echo e($rekap->dhuha['percent']); ?>%</span></div>
                                </td>
                                <td class="p-5 bg-elevate-soft/10">
                                    <div class="w-full bg-slate-100 rounded-full h-3 mb-2 overflow-hidden"><div class="bg-elevate-primary h-3 rounded-full" style="width: <?php echo e($rekap->dhuhur['percent']); ?>%"></div></div>
                                    <div class="flex justify-between text-[10px] font-bold"><span class="text-[#D13438]">Alfa: <?php echo e($rekap->dhuhur['alfa']); ?></span><span><?php echo e($rekap->dhuhur['percent']); ?>%</span></div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div id="manualInputModal" class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
            <div class="bg-elevate-peach-light/30 px-6 py-5 flex justify-between items-center border-b border-elevate-peach/30">
                <h3 class="font-black text-elevate-dark flex items-center gap-3 text-lg"><i class="ph-bold ph-pencil-line text-elevate-peach-dark"></i> Input Manual</h3>
                <button onclick="closeManualModal()" class="text-slate-400 hover:text-elevate-dark bg-white hover:bg-elevate-soft p-2 rounded-full transition-colors"><i class="ph-bold ph-x text-lg"></i></button>
            </div>
            <form action="<?php echo e(route('reports.storeManual')); ?>" method="POST" class="p-6 md:p-8 space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Menyimpan...';">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="attendance_type" value="Keagamaan">
                <input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                <input type="hidden" name="date" value="<?php echo e($selectedDate_db->format('Y-m-d')); ?>">
                
                <div class="bg-elevate-soft p-4 rounded-2xl border border-slate-100 text-center">
                    <span class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Siswa</span>
                    <input type="text" id="manual-student-name-display" class="w-full bg-transparent border-none text-center text-xl font-black text-elevate-dark focus:ring-0 p-0" readonly>
                    <input type="hidden" name="student_id" id="manual-student-id">
                </div>

                <div x-data="{ status: 'Hadir' }">
                    <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Status Kehadiran</label>
                    <select name="status" x-model="status" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Uzur Syar'i">Uzur Syar'i</option>
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

                <div>
                    <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Catatan</label>
                    <input type="text" name="notes" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-4 font-bold text-elevate-dark transition-colors" placeholder="Contoh: Sakit">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-elevate-dark hover:bg-elevate-primary text-white font-bold h-14 rounded-2xl transition-colors shadow-lg shadow-elevate-dark/20 active:scale-95">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CHECKLIST PER KELAS -->
    <template x-teleport="body">
        <div id="checklistModal" class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
            <div class="bg-white rounded-[2rem] w-full max-w-4xl shadow-2xl overflow-hidden animate-enter flex flex-col max-h-[90vh]" x-data="checklistHandler()">
                
                <div class="bg-elevate-dark px-8 py-5 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="font-black text-white flex items-center gap-3 text-xl">
                            <i class="ph-bold ph-list-checks text-elevate-accent"></i> Input Massal Per Kelas
                        </h3>
                        <p class="text-elevate-soft/80 font-medium text-xs mt-1">
                            <?php echo e($selectedActivity); ?> • <?php echo e($selectedDate_db->translatedFormat('d F Y')); ?>

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
                        <input type="hidden" name="activity" value="<?php echo e($selectedActivity); ?>">
                        <input type="hidden" name="type" value="Keagamaan">
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

    <div id="editReligiousModal" class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
            <div class="bg-elevate-soft px-6 py-5 flex justify-between items-center border-b border-slate-100">
                <h3 class="font-black text-elevate-dark flex items-center gap-3 text-lg"><i class="ph-bold ph-pencil-simple text-elevate-primary"></i> Edit Data</h3>
                <button onclick="closeEditModalReligious()" class="text-slate-400 hover:text-elevate-dark bg-white hover:bg-slate-100 p-2 rounded-full transition-colors"><i class="ph-bold ph-x text-lg"></i></button>
            </div>
            <form id="editReligiousForm" method="POST" class="p-6 md:p-8 space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Menyimpan...';">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="text-center mb-6">
                    <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Mengedit Siswa</p>
                    <p id="modal-religious-student-name" class="text-2xl font-black text-elevate-dark truncate px-2"></p>
                </div>
                <input type="hidden" name="activity" id="modal-religious-activity">
                
                <div x-data="{ status: '' }" x-init="$watch('status', value => {})">
                    <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Status</label>
                    <select name="status" id="modal-religious-status" onchange="checkEditReligiousStatus(this.value)" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent font-bold text-elevate-dark h-14 px-4 transition-colors">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Uzur Syar'i">Uzur Syar'i</option>
                        <option value="Alfa">Alfa</option>
                    </select>

                     <div id="edit-religious-alert" class="hidden mt-3 text-xs font-bold text-[#D13438] bg-[#FDE7E9] p-4 rounded-2xl border border-[#F4C3C9] flex items-start gap-3">
                        <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i> 
                        <div>
                            <span>Hati-hati!</span>
                            <span class="block font-medium opacity-80 mt-0.5 leading-relaxed">Mengubah menjadi Alfa akan menambah Poin Pelanggaran.</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-elevate-primary uppercase mb-2 block ml-1">Catatan</label>
                    <textarea name="notes" id="modal-religious-notes" class="w-full border-slate-200 bg-elevate-soft rounded-2xl focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent font-bold text-elevate-dark p-4 transition-colors" rows="2"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeEditModalReligious()" class="flex-1 h-14 bg-white border-2 border-slate-100 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors">Batal</button>
                    <button type="submit" class="flex-1 h-14 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 active:scale-95 transition-colors">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden border border-slate-100 animate-enter flex flex-col max-h-[80vh]">
            <div class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center shrink-0">
                <div>
                    <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Riwayat Keagamaan</p>
                    <h3 id="history-student-name" class="font-black text-xl text-elevate-dark">Nama Siswa</h3>
                </div>
                <button onclick="document.getElementById('historyModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-elevate-soft hover:bg-slate-200 flex items-center justify-center text-elevate-dark transition-colors"><i class="ph-bold ph-x"></i></button>
            </div>
            
            <div id="history-content" class="p-0 overflow-y-auto grow custom-scrollbar">
                <div class="flex flex-col items-center justify-center h-40">
                    <div class="w-10 h-10 border-4 border-elevate-primary border-t-transparent rounded-full animate-spin mb-3"></div>
                    <span class="text-sm font-black text-elevate-dark">Memuat riwayat...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if(isset($chartData)): ?>
                const trendOptions = {
                    series: <?php echo json_encode($chartData['series'], 15, 512) ?>,
                    chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
                    colors: ['#107C10', '#D13438'],
                    plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 6, borderRadiusApplication: 'end' } },
                    dataLabels: { enabled: false },
                    xaxis: { categories: <?php echo json_encode($chartData['labels'], 15, 512) ?> },
                    legend: { show: false }
                };
                new ApexCharts(document.querySelector("#chartTrend"), trendOptions).render();

                const donutOptions = {
                    series: [<?php echo e($chartData['composition']['hadir']); ?>, <?php echo e($chartData['composition']['uzur']); ?>, <?php echo e($chartData['composition']['alfa'] + $chartData['composition']['belum']); ?>],
                    labels: ['Hadir', 'Sakit/Izin', 'Tidak Hadir'],
                    chart: { type: 'donut', height: 250, fontFamily: 'Figtree, sans-serif' },
                    colors: ['#107C10', '#0d52a1', '#D13438'], 
                    plotOptions: { pie: { donut: { size: '75%', labels: { show: false } } } },
                    dataLabels: { enabled: false },
                    legend: { show: false }
                };
                new ApexCharts(document.querySelector("#chartDonut"), donutOptions).render();
            <?php endif; ?>
        });

        function openStudentHistory(studentId, studentName) {
            document.getElementById('history-student-name').innerText = studentName;
            document.getElementById('historyModal').classList.remove('hidden');
            const contentDiv = document.getElementById('history-content');
            contentDiv.innerHTML = `<div class="flex flex-col items-center justify-center h-40"><div class="w-10 h-10 border-4 border-elevate-primary border-t-transparent rounded-full animate-spin mb-3"></div><span class="text-sm font-black text-elevate-dark">Memuat riwayat...</span></div>`;
            fetch(`<?php echo e(url('reports/religious/history')); ?>?student_id=${studentId}&activity=<?php echo e($selectedActivity); ?>`).then(r=>r.text()).then(h=>{contentDiv.innerHTML=h;}).catch(e=>{console.error(e);contentDiv.innerHTML=`<div class="p-8 text-center text-rose-500 font-bold text-sm">Gagal memuat data.</div>`;});
        }
        function openManualModalForStudent(id, name) {
            document.getElementById('manual-student-id').value = id;
            document.getElementById('manual-student-name-display').value = name;
            document.getElementById('manualInputModal').classList.remove('hidden');
        }
        function closeManualModal() { document.getElementById('manualInputModal').classList.add('hidden'); }
        function confirmResetData() {
            Swal.fire({ title: 'Reset Data Hari Ini?', text: "Semua data kehadiran <?php echo e($selectedActivity); ?> akan dihapus!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#D13438', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' } }).then((result) => { if (result.isConfirmed) document.getElementById('reset-data-form').submit(); })
        }
        function confirmBulkAlphaReligious(count) {
            Swal.fire({ title: 'Tandai ' + count + ' Siswa Alfa?', html: "Siswa akan ditandai <b>Alfa</b> untuk Shalat <?php echo e($selectedActivity); ?>.<br><div class='mt-4 text-[#D13438] font-semibold bg-[#FDE7E9] p-3 rounded-xl border border-[#F4C3C9] text-sm'>Poin Pelanggaran akan ditambahkan otomatis!</div>", icon: 'warning', showCancelButton: true, confirmButtonColor: '#D13438', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Proses!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' } }).then((result) => { if (result.isConfirmed) document.getElementById('bulk-alpha-religious-form').submit(); })
        }
        const religiousModal = document.getElementById('editReligiousModal');
        const religiousForm = document.getElementById('editReligiousForm');
        const religiousStudentNameDisplay = document.getElementById('modal-religious-student-name');
        const religiousActivitySelect = document.getElementById('modal-religious-activity');
        const religiousStatusSelect = document.getElementById('modal-religious-status');
        const religiousNotesInput = document.getElementById('modal-religious-notes');
        function checkEditReligiousStatus(val) { const alertBox = document.getElementById('edit-religious-alert'); if(val === 'Alfa') { alertBox.classList.remove('hidden'); } else { alertBox.classList.add('hidden'); } }
        function openEditModalReligious(id, name, status, notes, activity) {
            const submitBtn = religiousForm.querySelector('button[type=submit]');
            submitBtn.disabled = false; submitBtn.innerText = 'Update';
            const updateRoute = '<?php echo e(route('reports.update', ['attendance' => '__ID__'])); ?>'.replace('__ID__', id);
            religiousForm.action = updateRoute;
            religiousStudentNameDisplay.textContent = name; religiousActivitySelect.value = activity;
            religiousStatusSelect.value = status; religiousNotesInput.value = notes;
            checkEditReligiousStatus(status); religiousModal.classList.remove('hidden');
        }
        function closeEditModalReligious() { religiousModal.classList.add('hidden'); }

        function checklistHandler() {
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
                        const url = `<?php echo e(route('reports.getStudentsByClass')); ?>?class_id=${this.selectedClass}&date=<?php echo e($selectedDate_db->format('Y-m-d')); ?>&activity=<?php echo e($selectedActivity); ?>`;
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/reports/religious.blade.php ENDPATH**/ ?>