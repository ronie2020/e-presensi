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
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .count-up { font-variant-numeric: tabular-nums; }
        
        /* Animasi Wiggle */
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }

        /* ==========================================================
           MICROSOFT FLUENT ELEVATION SHADOWS & DESIGN
           ========================================================== */
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
        .fluent-modal {
            box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Utility */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* PRINT STYLES */
        @media print {
            body { background-color: white !important; color: black !important; }
            .no-print, nav, header, .filter-group, button, .quick-actions { display: none !important; }
            .card-print { break-inside: avoid; border: 1px solid #ddd; box-shadow: none !important; background: white !important; color: black !important; transform: none !important;}
            .text-white { color: black !important; }
            .bg-gradient-to-br, .bg-gradient-to-r { background: none !important; border-bottom: 2px solid #000; color: black !important; }
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
            canvas { max-width: 100% !important; max-height: 300px !important; }
            .fluent-card, .shadow-2xl, .shadow-xl, .shadow-lg, .shadow-md, .shadow-sm { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
        .print-header { display: none; }
    </style>

    
    <div x-data="{ 
            period: new URLSearchParams(window.location.search).get('period') || 'today',
            date: new URLSearchParams(window.location.search).get('date') || new Date().toISOString().split('T')[0],
            loading: false,
            loadingTarget: '',
            
            updateFilter(newPeriod) {
                this.loading = true;
                this.loadingTarget = newPeriod;
                this.period = newPeriod;
                setTimeout(() => {
                    window.location.href = '?period=' + this.period + '&date=' + this.date;
                }, 300); 
            },
            changeDate(days) {
                this.loading = true;
                this.loadingTarget = 'date';
                let d = new Date(this.date);
                d.setDate(d.getDate() + days);
                this.date = d.toISOString().split('T')[0];
                window.location.href = '?period=' + this.period + '&date=' + this.date;
            },
            printDashboard() { window.print(); },
            navigate(url) {
                this.loading = true;
                this.loadingTarget = 'page';
                window.location.href = url;
            }
        }" class="relative space-y-6 md:space-y-8 min-h-screen pb-10 font-sans text-slate-800">
        
        
        <div class="print-header">
            <h1 class="text-2xl font-bold uppercase tracking-wide">Laporan Monitoring Harian</h1>
            <p class="text-sm">SMP NEGERI 3 LAKBOK</p>
            <p class="text-xs mt-2">Dicetak pada: <?php echo e(now()->format('d F Y H:i')); ?> oleh <?php echo e(Auth::user()->name); ?></p>
        </div>

        
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;" 
             class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
            
            <div class="bg-white p-6 rounded-xl fluent-modal flex flex-col items-center transform transition-all scale-100">
                <div class="relative w-12 h-12 mb-4">
                    <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-[#2A3B52] border-t-transparent animate-spin"></div>
                </div>
                <span class="text-xs font-bold text-[#2A3B52] tracking-wider uppercase animate-pulse">Memproses Data...</span>
            </div>
        </div>

         
        <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-6 md:p-10 mb-6 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden group border border-white/40 card-print">
            
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none no-print mix-blend-overlay"></div>
            
            
            <div class="absolute top-0 left-0 w-[300px] sm:w-[400px] h-[300px] sm:h-[400px] bg-white/30 rounded-full blur-[100px] group-hover:opacity-70 transition-opacity duration-1000 no-print animate-blob -ml-20 -mt-20"></div>
            <div class="absolute bottom-0 right-0 w-[200px] sm:w-[300px] h-[200px] sm:h-[300px] bg-white/20 rounded-full blur-[120px] no-print animate-blob" style="animation-delay: 2s;"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm no-print">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#107C10] opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-[#107C10]"></span>
                        </span>
                        System Online                        
                    </div>
                    
                    <h1 class="text-2xl md:text-5xl font-extrabold text-[#2A3B52] tracking-tight mb-3">
                        Halo, <span><?php echo e(Auth::user()->name ?? 'Administrator'); ?></span> 
                    </h1>
                    <p class="text-[#2A3B52]/80 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                        Berikut adalah ringkasan aktivitas akademik dan kehadiran siswa untuk periode 
                        <span class="text-[#5295FF] font-bold bg-white/70 px-2 py-0.5 rounded shadow-sm border border-white/50" x-text="period === 'today' ? 'Hari Ini' : (period === 'week' ? 'Minggu Ini' : 'Bulan Ini')"></span>.
                    </p>
                </div>
                
                
                <div class="flex flex-col gap-3 w-full md:w-auto md:min-w-[320px] filter-group no-print">
                    <div class="flex items-center justify-between bg-white/30 backdrop-blur-md rounded-xl p-1 border border-white/40 mb-1 relative shadow-sm">
                        <div x-show="loadingTarget === 'date'" class="absolute inset-0 bg-white/50 rounded-xl flex items-center justify-center z-10">
                            <i class="ph-bold ph-spinner animate-spin text-[#2A3B52]"></i>
                        </div>

                        <button @click="changeDate(-1)" :disabled="loading" class="p-2 hover:bg-white/40 rounded-lg text-[#2A3B52] transition disabled:opacity-50" title="Sebelumnya">
                            <i class="ph-bold ph-caret-left"></i>
                        </button>
                        <div class="relative group/date flex-1 mx-2">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph-bold ph-calendar text-[#2A3B52]/70 group-hover/date:text-[#2A3B52] transition-colors"></i>
                            </div>
                            <input type="date" x-model="date" @change="loading = true; loadingTarget = 'date'; updateFilter(period)" 
                                class="w-full bg-transparent border-none text-[#2A3B52] text-xs font-bold text-center focus:ring-0 cursor-pointer placeholder-[#2A3B52]/70">
                        </div>
                        <button @click="changeDate(1)" :disabled="loading" class="p-2 hover:bg-white/40 rounded-lg text-[#2A3B52] transition disabled:opacity-50" title="Berikutnya">
                            <i class="ph-bold ph-caret-right"></i>
                        </button>
                    </div>

                    <div class="bg-white/30 backdrop-blur-md p-1.5 rounded-xl flex border border-white/40 shadow-sm overflow-x-auto">
                        <button @click="updateFilter('today')" :disabled="loading"
                            :class="period === 'today' ? 'bg-white text-[#5295FF] shadow-sm' : 'text-[#2A3B52] hover:bg-white/40'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'today'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'today') ? '' : 'Harian'"></span>
                        </button>

                        <button @click="updateFilter('week')" :disabled="loading"
                            :class="period === 'week' ? 'bg-white text-[#5295FF] shadow-sm' : 'text-[#2A3B52] hover:bg-white/40'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'week'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'week') ? '' : 'Mingguan'"></span>
                        </button>

                        <button @click="updateFilter('month')" :disabled="loading"
                            :class="period === 'month' ? 'bg-white text-[#5295FF] shadow-sm' : 'text-[#2A3B52] hover:bg-white/40'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'month'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'month') ? '' : 'Bulanan'"></span>
                        </button>
                        
                        
                        <a href="<?php echo e(route('reports.printDaily', ['date' => request('date')])); ?>" target="_blank" class="ml-2 bg-[#2A3B52] text-white p-2.5 rounded-lg hover:bg-[#182436] hover:scale-105 active:scale-95 transition-all shadow-sm border border-transparent shrink-0 flex items-center gap-2" title="Cetak Laporan Harian">
                            <i class="ph-bold ph-printer text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="animate-enter mb-8 no-print" style="animation-delay: 50ms">
            <?php if($countOut > 0): ?>
                
                <div class="bg-[#FFEFD6] rounded-xl border border-[#FFD8A8] p-6 relative overflow-hidden fluent-card">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="ph-duotone ph-door-open text-8xl text-[#D83B01]"></i>
                    </div>
                    
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D83B01] opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-[#D83B01]"></span>
                                </span>
                                <h3 class="text-lg font-bold text-[#D83B01]">Peringatan: Siswa Sedang Di Luar</h3>
                            </div>
                            <p class="text-sm text-[#D83B01]/80">Terdapat <span class="font-bold"><?php echo e($countOut); ?> siswa</span> yang belum kembali ke kelas saat ini.</p>
                        </div>
                        
                        <a href="<?php echo e(route('permit.index')); ?>" class="px-5 py-2.5 bg-white text-[#D83B01] text-sm font-bold rounded-lg shadow-sm border border-[#FFD8A8] hover:bg-[#FFE3B8] transition-colors flex items-center gap-2">
                            <i class="ph-bold ph-eye"></i> Lihat Detail
                        </a>
                    </div>

                    <div class="mt-6 flex gap-3 overflow-x-auto pb-2 custom-scrollbar">
                        <?php $__currentLoopData = $studentsOut; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $duration = \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                $isOverdue = $duration > 15;
                            ?>
                            <div class="flex-shrink-0 w-64 bg-white p-3 rounded-xl border <?php echo e($isOverdue ? 'border-[#F4C3C9] bg-[#FDE7E9]' : 'border-[#FFD8A8]'); ?> shadow-sm flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg <?php echo e($isOverdue ? 'bg-[#FDE7E9] text-[#D13438]' : 'bg-[#FFEFD6] text-[#D83B01]'); ?> flex items-center justify-center font-bold text-sm">
                                    <?php echo e(substr($permit->student->name, 0, 1)); ?>

                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-700 truncate"><?php echo e($permit->student->name); ?></p>
                                    <p class="text-[10px] text-slate-500 truncate"><?php echo e($permit->reason_category); ?> • <span class="<?php echo e($isOverdue ? 'text-[#D13438] font-bold animate-pulse' : 'text-slate-500'); ?>"><?php echo e($duration); ?> m</span></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php else: ?>
                
                <div class="bg-[#DFF6DD] rounded-xl border border-[#B7DFB9] p-6 relative overflow-hidden flex items-center justify-between fluent-card">
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-[#107C10] text-2xl shadow-sm border border-[#B7DFB9]">
                            <i class="ph-fill ph-shield-check"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#107C10]">Status Monitoring: Aman</h3>
                            <p class="text-sm text-[#107C10]/80">Semua siswa berada di dalam kelas. Tidak ada izin keluar aktif.</p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('permit.index')); ?>" class="px-5 py-2.5 bg-white text-[#107C10] text-sm font-bold rounded-lg shadow-sm border border-[#B7DFB9] hover:bg-[#D0F0C0] transition-colors hidden md:flex items-center gap-2">
                        <i class="ph-bold ph-list-magnifying-glass"></i> Cek Log
                    </a>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 mb-8 no-print animate-enter quick-actions" style="animation-delay: 100ms">
            <a href="<?php echo e(route('students.index')); ?>" @click.prevent="navigate('<?php echo e(route('students.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#5295FF] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#D0E7F8]">
                    <i class="ph-bold ph-student text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#5295FF]">Data Siswa</div>
            </a>
            
             <a href="<?php echo e(route('teacher.habits.index')); ?>" @click.prevent="navigate('<?php echo e(route('teacher.habits.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#107C10] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#DFF6DD] text-[#107C10] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#B7DFB9]">
                    <i class="ph-bold ph-calendar-check text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#107C10]">7 Kebiasaan</div>
            </a>
            
            <a href="<?php echo e(route('cbt.index')); ?>" @click.prevent="navigate('<?php echo e(route('cbt.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#2A3B52] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-slate-100 text-[#2A3B52] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-slate-200">
                    <i class="ph-bold ph-monitor-play text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#2A3B52]">Ujian CBT</div>
            </a>

            <a href="<?php echo e(route('lms.assignments.index')); ?>" @click.prevent="navigate('<?php echo e(route('lms.assignments.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#D13438] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#FDE7E9] text-[#D13438] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#F4C3C9]">
                    <i class="ph-bold ph-pencil-simple text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#D13438]">Tugas & PR</div>
            </a>

            <a href="<?php echo e(route('lms.grades.index')); ?>" @click.prevent="navigate('<?php echo e(route('lms.grades.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#107C10] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#DFF6DD] text-[#107C10] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#B7DFB9]">
                    <i class="ph-bold ph-chart-bar text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#107C10]">Rekap Nilai</div>
            </a>

            <a href="<?php echo e(route('reports.class')); ?>" @click.prevent="navigate('<?php echo e(route('reports.class')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#D83B01] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#FFEFD6] text-[#D83B01] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#FFD8A8]">
                    <i class="ph-bold ph-files text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#D83B01]">Laporan Kelas</div>
            </a>

            <a href="<?php echo e(route('admin.graduation.index')); ?>" @click.prevent="navigate('<?php echo e(route('admin.graduation.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#D13438] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#FDE7E9] text-[#D13438] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#F4C3C9]">
                    <i class="ph-bold ph-envelope-open text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#D13438]">Kelulusan</div>
            </a>

            <a href="<?php echo e(route('admin.ppdb.index')); ?>" @click.prevent="navigate('<?php echo e(route('admin.ppdb.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#D83B01] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#FFEFD6] text-[#D83B01] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#FFD8A8]">
                    <i class="ph-bold ph-users text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#D83B01]">SPMB/PPDB</div>
            </a>

            <a href="<?php echo e(route('letters.spt.index')); ?>" @click.prevent="navigate('<?php echo e(route('letters.spt.index')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#5295FF] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-[#D0E7F8]">
                    <i class="ph-bold ph-car-profile text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#5295FF]">SPPD</div>
            </a>

            <a href="<?php echo e(route('library.dashboard')); ?>" @click.prevent="navigate('<?php echo e(route('library.dashboard')); ?>')" class="group bg-white p-4 rounded-xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-[#2A3B52] cursor-pointer text-center">
                <div class="w-12 h-12 rounded-lg bg-slate-100 text-[#2A3B52] flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-slate-200">
                    <i class="ph-bold ph-book-open-text text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-[#2A3B52]">Perpustakaan</div>
            </a>
        </div>

        
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-5">
            <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $titleLower = strtolower($card['title']);
                $rawIcon = $card['icon'] ?? ''; 
                
                if (str_contains($titleLower, 'alpha') || str_contains($titleLower, 'alpa') || str_contains($titleLower, 'absen') || str_contains($titleLower, 'tidak hadir')) { 
                    $iconClass = 'ph-x-circle'; 
                    $colorKey = 'danger'; 
                } 
                elseif (str_contains($titleLower, 'telat') || str_contains($titleLower, 'lambat')) { 
                    $iconClass = 'ph-clock'; 
                    $colorKey = 'warning'; 
                } 
                elseif (str_contains($titleLower, 'izin') || str_contains($titleLower, 'sakit')) { 
                    $iconClass = 'ph-envelope-open'; 
                    $colorKey = 'info'; 
                } 
                elseif (str_contains($titleLower, 'hadir') && !str_contains($titleLower, 'belum') && !str_contains($titleLower, 'tidak')) { 
                    $iconClass = 'ph-check-circle'; 
                    $colorKey = 'success'; 
                } 
                elseif (str_contains($titleLower, 'belum')) { 
                    $iconClass = 'ph-minus-circle'; 
                    $colorKey = 'neutral'; 
                } 
                elseif (str_contains($titleLower, 'pulang')) { 
                    $iconClass = 'ph-person-simple-run'; 
                    $colorKey = 'warning'; 
                } 
                elseif (str_contains($titleLower, 'total') || str_contains($titleLower, 'siswa')) { 
                    $iconClass = 'ph-student'; 
                    $colorKey = 'primary'; 
                } 
                else { 
                    $iconClass = (!empty($rawIcon) && !str_starts_with($rawIcon, 'M') && $rawIcon !== 'ph-hash') ? $rawIcon : 'ph-chart-bar'; 
                    $colorKey = 'primary'; 
                }

                $theme = match($colorKey) {
                    'success' => ['bg' => 'bg-[#DFF6DD]', 'text' => 'text-[#107C10]', 'hover_bg' => 'group-hover:bg-[#107C10]', 'hover_border' => 'hover:border-[#B7DFB9]', 'border' => 'border-[#B7DFB9]'],
                    'warning' => ['bg' => 'bg-[#FFEFD6]', 'text' => 'text-[#D83B01]', 'hover_bg' => 'group-hover:bg-[#D83B01]', 'hover_border' => 'hover:border-[#FFD8A8]', 'border' => 'border-[#FFD8A8]'],
                    'danger' => ['bg' => 'bg-[#FDE7E9]', 'text' => 'text-[#D13438]', 'hover_bg' => 'group-hover:bg-[#D13438]', 'hover_border' => 'hover:border-[#F4C3C9]', 'border' => 'border-[#F4C3C9]'],
                    'info' => ['bg' => 'bg-slate-100', 'text' => 'text-[#2A3B52]', 'hover_bg' => 'group-hover:bg-[#2A3B52]', 'hover_border' => 'hover:border-slate-300', 'border' => 'border-slate-200'],
                    'neutral' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'hover_bg' => 'group-hover:bg-slate-600', 'hover_border' => 'hover:border-slate-300', 'border' => 'border-slate-200'],
                    default => ['bg' => 'bg-[#F3F9FD]', 'text' => 'text-[#5295FF]', 'hover_bg' => 'group-hover:bg-[#5295FF]', 'hover_border' => 'hover:border-[#D0E7F8]', 'border' => 'border-[#D0E7F8]'],
                };
            ?>

            <div onclick="showCardInfo('<?php echo e($card['title']); ?>', '<?php echo e($card['value']); ?>', '<?php echo e($colorKey); ?>')" 
               class="cursor-pointer animate-enter group bg-white rounded-xl p-5 fluent-card <?php echo e($theme['hover_border']); ?> relative overflow-hidden flex flex-col justify-between h-full card-print"
               style="animation-delay: <?php echo e(($index + 1) * 100); ?>ms">
                
                <i class="ph-duotone <?php echo e($iconClass); ?> absolute -right-4 -bottom-4 text-[5rem] opacity-[0.03] text-slate-900 group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 no-print"></i>
                
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center shadow-sm border <?php echo e($theme['border']); ?> transition-all duration-300 <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> <?php echo e($theme['hover_bg']); ?> group-hover:text-white group-hover:scale-110">
                        <i class="ph-duotone <?php echo e($iconClass); ?> text-2xl animate-wiggle"></i>
                    </div>
                    
                    <?php if(isset($card['percentage'])): ?>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-md border <?php echo e($card['percentage'] > 0 ? 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]' : 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]'); ?>">
                        <?php echo e($card['percentage'] > 0 ? '+' : ''); ?><?php echo e($card['percentage']); ?>%
                    </span>
                    <?php endif; ?>
                    
                    <?php if(isset($card['trend']) && $card['trend'] !== null): ?>
                        <div class="text-[10px] font-bold px-2 py-1 rounded-md border flex items-center gap-1 <?php echo e($card['trend'] >= 0 ? 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]' : 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]'); ?>">
                            <i class="<?php echo e($card['trend'] >= 0 ? 'ph-bold ph-trend-up' : 'ph-bold ph-trend-down'); ?>"></i>
                            <span><?php echo e(abs($card['trend'])); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="relative z-10 mt-auto">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 truncate <?php echo e(str_replace('text-', 'group-hover:text-', $theme['text'])); ?> transition-colors"><?php echo e($card['title']); ?></p>
                    <h3 class="text-2xl md:text-3xl font-extrabold text-[#2A3B52] tracking-tight count-up" data-target="<?php echo e($card['value']); ?>">0</h3>
                    <?php if(isset($card['trend']) && $card['trend'] !== null): ?>
                        <p class="text-[9px] text-slate-400 font-bold mt-1">vs Kemarin</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <div class="animate-enter xl:col-span-2 bg-white p-5 md:p-8 rounded-xl fluent-card card-print" style="animation-delay: 600ms">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-[#2A3B52] flex items-center gap-2">
                            <i class="ph-fill ph-chart-bar text-[#5295FF]"></i> Analisis Tren Kehadiran
                        </h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wide mt-1">
                            Statistik <span x-text="period === 'month' ? 'Bulanan' : 'Mingguan'"></span>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 no-print">
                        <div class="px-3 py-1 rounded-lg bg-[#F3F9FD] border border-[#D0E7F8] flex items-center gap-2 text-[10px] font-bold text-[#5295FF] uppercase"><span class="w-2 h-2 rounded-full bg-[#5295FF]"></span> Hadir</div>
                        <div class="px-3 py-1 rounded-lg bg-[#FFEFD6] border border-[#FFD8A8] flex items-center gap-2 text-[10px] font-bold text-[#D83B01] uppercase"><span class="w-2 h-2 rounded-full bg-[#D83B01]"></span> Telat</div>
                        <div class="px-3 py-1 rounded-lg bg-[#FDE7E9] border border-[#F4C3C9] flex items-center gap-2 text-[10px] font-bold text-[#D13438] uppercase"><span class="w-2 h-2 rounded-full bg-[#D13438]"></span> Absen</div>
                    </div>
                </div>
                <div class="relative h-64 md:h-72 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            
            <div class="animate-enter bg-white p-5 md:p-8 rounded-xl fluent-card flex flex-col h-full card-print" style="animation-delay: 700ms">
                <h3 class="text-lg font-bold text-[#2A3B52] mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-[#2A3B52]"></i> Komposisi Hari Ini
                </h3>
                <div class="relative h-56 w-full flex items-center justify-center mb-6">
                    <canvas id="dailyDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl md:text-4xl font-black text-[#2A3B52] count-up" data-target="<?php echo e($totalStudents ?? 0); ?>">0</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total Siswa</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <div class="bg-[#F3F9FD] p-3 rounded-lg border border-[#D0E7F8]"><span class="block text-[10px] font-bold text-[#5295FF] uppercase mb-1">Hadir Tepat</span><span class="text-lg font-black text-[#2A3B52]"><?php echo e($presentOnTimeCount ?? 0); ?></span></div>
                    <div class="bg-[#FFEFD6] p-3 rounded-lg border border-[#FFD8A8]"><span class="block text-[10px] font-bold text-[#D83B01] uppercase mb-1">Terlambat</span><span class="text-lg font-black text-[#2A3B52]"><?php echo e($lateCount ?? 0); ?></span></div>
                    <div class="bg-[#FDE7E9] p-3 rounded-lg border border-[#F4C3C9]"><span class="block text-[10px] font-bold text-[#D13438] uppercase mb-1">Alfa</span><span class="text-lg font-black text-[#2A3B52]"><?php echo e($absentCount ?? 0); ?></span></div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-200"><span class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Belum Hadir</span><span class="text-lg font-black text-[#2A3B52]"><?php echo e($notYetScannedCount ?? 0); ?></span></div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 page-break-inside-avoid">
            
            <div class="animate-enter bg-white p-6 md:p-8 rounded-xl fluent-card flex flex-col h-full card-print" style="animation-delay: 800ms" x-data="{ tab: 'activity' }">
                <div class="flex items-center justify-between mb-6 no-print">
                    <div class="flex gap-6 border-b border-slate-100 w-full">
                        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'text-[#5295FF] border-[#5295FF]' : 'text-slate-400 border-transparent hover:text-slate-600'" class="text-sm font-bold pb-3 border-b-2 transition-all px-1">Aktivitas Terbaru</button>
                        <button @click="tab = 'late_recap'" :class="tab === 'late_recap' ? 'text-[#D83B01] border-[#D83B01]' : 'text-slate-400 border-transparent hover:text-slate-600'" class="text-sm font-bold pb-3 border-b-2 transition-all px-1">Top Terlambat</button>
                    </div>
                </div>

                <div x-show="tab === 'activity'" class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar pr-2">
                    <?php if(isset($recentActivities) && count($recentActivities) > 0): ?>
                        <div class="relative pl-6 border-l-2 border-slate-100 space-y-6 py-2 ml-2">
                            <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $type = $log->type;
                                    $statusText = $log->status;
                                    $subText = 'Absensi Sekolah';
                                    $theme = ['bg_icon' => 'bg-[#F3F9FD]', 'border_icon' => 'border-[#D0E7F8]', 'text_icon' => 'text-[#5295FF]', 'dot' => 'bg-[#5295FF]', 'bg_badge' => 'bg-[#F3F9FD]', 'text_badge' => 'text-[#5295FF]'];
                                    $icon = 'ph-check-circle';

                                    if ($type === 'Keagamaan') {
                                        $icon = 'ph-moon-stars'; $statusText = $log->activity; $subText = 'Ibadah';
                                        $theme = ['bg_icon' => 'bg-slate-100', 'border_icon' => 'border-slate-200', 'text_icon' => 'text-[#2A3B52]', 'dot' => 'bg-[#2A3B52]', 'bg_badge' => 'bg-slate-100', 'text_badge' => 'text-[#2A3B52]'];
                                    } elseif ($type === 'Ekstrakurikuler') {
                                        $icon = 'ph-trophy'; $statusText = $log->activity; $subText = 'Ekstrakurikuler';
                                        $theme = ['bg_icon' => 'bg-[#FFEFD6]', 'border_icon' => 'border-[#FFD8A8]', 'text_icon' => 'text-[#D83B01]', 'dot' => 'bg-[#D83B01]', 'bg_badge' => 'bg-[#FFEFD6]', 'text_badge' => 'text-[#D83B01]'];
                                    } else {
                                        if ($log->status == 'Terlambat') {
                                            $icon = 'ph-clock-warning';
                                            $theme = ['bg_icon' => 'bg-[#FFEFD6]', 'border_icon' => 'border-[#FFD8A8]', 'text_icon' => 'text-[#D83B01]', 'dot' => 'bg-[#D83B01]', 'bg_badge' => 'bg-[#FFEFD6]', 'text_badge' => 'text-[#D83B01]'];
                                        } elseif ($type == 'Pulang') {
                                            $icon = 'ph-person-simple-walk'; $statusText = 'Pulang'; $subText = 'Selesai KBM';
                                            $theme = ['bg_icon' => 'bg-[#F3F9FD]', 'border_icon' => 'border-[#D0E7F8]', 'text_icon' => 'text-[#5295FF]', 'dot' => 'bg-[#5295FF]', 'bg_badge' => 'bg-[#F3F9FD]', 'text_badge' => 'text-[#5295FF]'];
                                        } else {
                                            $subText = $log->student->schoolClass->name ?? '-';
                                        }
                                    }
                                ?>
                            <div class="relative group">
                                <div class="absolute -left-[31px] top-1.5 h-4 w-4 rounded-full border-[3px] border-white ring-1 ring-slate-200 <?php echo e($theme['dot']); ?>"></div>
                                <div class="flex items-start justify-between gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors -mt-2 -ml-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg <?php echo e($theme['bg_icon']); ?> border <?php echo e($theme['border_icon']); ?> flex items-center justify-center <?php echo e($theme['text_icon']); ?> font-bold text-xs shrink-0">
                                            <i class="ph-bold <?php echo e($icon); ?> text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[#2A3B52] line-clamp-1 group-hover:text-[#5295FF] transition-colors"><?php echo e($log->student->name ?? 'Siswa'); ?></p>
                                            <p class="text-[10px] text-slate-500 font-bold px-2 py-0.5 rounded-md inline-block mt-1 border border-slate-100 bg-white"><?php echo e($subText); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold font-mono text-slate-600 mb-1"><?php echo e($log->created_at->format('H:i')); ?></p>
                                        <span class="text-[10px] font-bold px-2 py-1 rounded-md <?php echo e($theme['bg_badge']); ?> <?php echo e($theme['text_badge']); ?>"><?php echo e($statusText); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center h-40 text-center text-slate-400">
                            <i class="ph-duotone ph-coffee text-4xl mb-3 opacity-30"></i>
                            <p class="text-xs font-bold">Belum ada aktivitas hari ini.</p>
                        </div>
                    <?php endif; ?>
                </div> 

                <div x-show="tab === 'late_recap'" style="display: none;" class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar">
                    <?php if(isset($topLateStudents) && count($topLateStudents) > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $topLateStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-50 hover:bg-[#FDE7E9] transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold text-xs flex items-center justify-center border border-slate-200">#<?php echo e($index + 1); ?></div>
                                    <div>
                                        <div class="text-xs font-bold text-[#2A3B52] line-clamp-1"><?php echo e($student->student->name ?? 'Siswa'); ?></div>
                                        <div class="text-[10px] text-slate-400"><?php echo e($student->student->schoolClass->name ?? '-'); ?></div>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-[#D13438] bg-[#FDE7E9] px-3 py-1 rounded-md border border-[#F4C3C9]"><?php echo e($student->total_late); ?>x</span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10 text-slate-400 text-xs font-bold">Tidak ada siswa terlambat signifikan.</div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="animate-enter bg-white p-6 md:p-8 rounded-xl fluent-card h-full card-print" style="animation-delay: 900ms" x-data="{ rankTab: 'best' }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-[#2A3B52] flex items-center gap-2">
                        <i class="ph-fill ph-trophy text-[#D83B01] text-xl drop-shadow-sm"></i> Peringkat Kelas
                    </h3>
                    
                    
                    <div class="bg-slate-100 p-1 rounded-lg flex no-print border border-slate-200">
                        <button @click="rankTab = 'best'" :class="rankTab === 'best' ? 'bg-white text-[#5295FF] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-2 py-1 rounded-md text-[10px] font-bold transition-all">Rajin</button>
                        <button @click="rankTab = 'worst'" :class="rankTab === 'worst' ? 'bg-white text-[#D13438] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-2 py-1 rounded-md text-[10px] font-bold transition-all">Perlu Atensi</button>
                    </div>
                </div>

                
                <div x-show="rankTab === 'best'">
                    <?php if(isset($classRanks) && count($classRanks) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-slate-50">
                                <?php $__currentLoopData = $classRanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $rank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="group hover:bg-slate-50 transition-colors">
                                    <td class="py-4 pl-1 w-10">
                                        <?php if($index == 0): ?> <i class="ph-fill ph-medal text-[#D83B01] text-2xl drop-shadow-sm"></i>
                                        <?php elseif($index == 1): ?> <i class="ph-fill ph-medal text-slate-400 text-xl"></i>
                                        <?php elseif($index == 2): ?> <i class="ph-fill ph-medal text-amber-700 text-xl"></i>
                                        <?php else: ?> <span class="font-bold text-slate-400 ml-1.5">#<?php echo e($index + 1); ?></span> <?php endif; ?>
                                    </td>
                                    <td class="py-4">
                                        <div class="font-bold text-[#2A3B52] mb-1"><?php echo e($rank->class_name); ?></div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden max-w-[150px]">
                                            <?php $percent = min(100, ($rank->present_count / 40) * 100); ?>
                                            <div class="h-1.5 rounded-full <?php echo e($index == 0 ? 'bg-[#D83B01]' : 'bg-[#5295FF]'); ?>" style="width: <?php echo e($percent); ?>%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <div class="font-black text-[#2A3B52]"><?php echo e(number_format($percent, 0)); ?>%</div>
                                        <div class="text-[10px] text-slate-400 font-bold whitespace-nowrap"><?php echo e($rank->present_count); ?> Hadir</div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                     <div class="flex flex-col items-center justify-center h-40 text-center text-slate-400"><p class="text-xs font-bold">Belum ada data peringkat.</p></div>
                    <?php endif; ?>
                </div>

                
                <div x-show="rankTab === 'worst'" style="display: none;">
                    <?php if(isset($lowestClassRanks) && count($lowestClassRanks) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-slate-50">
                                <?php $__currentLoopData = $lowestClassRanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $rank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="group hover:bg-[#FDE7E9]/50 transition-colors">
                                    <td class="py-4 pl-1 w-10 text-center font-bold text-slate-400">
                                        <?php echo e($index + 1); ?>

                                    </td>
                                    <td class="py-4">
                                        <div class="font-bold text-[#2A3B52] mb-1"><?php echo e($rank->class_name); ?></div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden max-w-[150px]">
                                            <?php $percent = min(100, ($rank->absent_count / 40) * 100); ?>
                                            <div class="h-1.5 rounded-full bg-[#D13438]" style="width: <?php echo e($percent); ?>%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <div class="font-black text-[#D13438]"><?php echo e($rank->absent_count); ?></div>
                                        <div class="text-[10px] text-slate-400 font-bold whitespace-nowrap">Tidak Masuk</div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                     <div class="flex flex-col items-center justify-center h-40 text-center text-[#107C10]">
                        <i class="ph-duotone ph-check-circle text-4xl mb-2 opacity-50"></i>
                        <p class="text-xs font-bold">Semua kelas hadir lengkap!</p>
                     </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    <script>
        // Fungsi Popup Info dengan warna semantik Elevate
        function showCardInfo(title, value, colorKey) {
            let colorHex = '#5295FF'; // default primary (Elevate Blue)
            if(colorKey === 'success') colorHex = '#107C10'; // Microsoft Green
            if(colorKey === 'warning') colorHex = '#D83B01'; // Microsoft Orange
            if(colorKey === 'danger') colorHex = '#D13438'; // Microsoft Red
            if(colorKey === 'info') colorHex = '#2A3B52'; // Navy
            if(colorKey === 'neutral') colorHex = '#64748b'; // Slate

            Swal.fire({
                title: `<span style="color: ${colorHex}; font-weight: 900;">${title}</span>`,
                html: `
                    <div class="mt-2 mb-6">
                        <span class="text-5xl font-black text-[#2A3B52]">${value}</span>
                        <span class="text-sm font-bold text-slate-400 ml-1">Siswa</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed mb-4">
                        Untuk melihat daftar nama siswa secara spesifik, silakan buka menu <b>Data Siswa</b> atau menu <b>Laporan Kelas</b> di pintasan atas.
                    </p>
                    <div class="inline-block bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400">
                        Halaman tabel detail sedang dalam tahap persiapan.
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: colorHex,
                confirmButtonText: 'Tutup Info',
                customClass: {
                    popup: 'fluent-modal rounded-xl',
                    confirmButton: 'rounded-lg font-bold px-6 py-2.5 transition-shadow fluent-card'
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Animasi Angka
            const counters = document.querySelectorAll('.count-up');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let count = 0; const inc = Math.max(1, target / 50);
                const updateCount = () => {
                    count += inc;
                    if (count < target) { counter.innerText = Math.ceil(count).toLocaleString('id-ID'); requestAnimationFrame(updateCount); } 
                    else { counter.innerText = target.toLocaleString('id-ID'); }
                };
                updateCount();
            });

            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#94a3b8';

            // Data
            const rawPresent = <?php echo json_encode($weeklyPresentData ?? [], 15, 512) ?>;
            const rawLate = <?php echo json_encode($weeklyLateData ?? [], 15, 512) ?>;
            const rawAbsent = <?php echo json_encode($weeklyAbsentData ?? [], 15, 512) ?>;
            const labels = <?php echo json_encode($chartLabels ?? [], 15, 512) ?>;
            
            // Bar Chart (Warna Microsoft Elevate)
            const ctxBar = document.getElementById('weeklyChart');
            if(ctxBar) {
                const hasData = rawPresent.some(x => x > 0) || rawLate.some(x => x > 0) || rawAbsent.some(x => x > 0);
                if (hasData) {
                    new Chart(ctxBar.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'Hadir', data: rawPresent, backgroundColor: '#5295FF', borderRadius: 4, barThickness: 12 }, // Elevate Blue
                                { label: 'Telat', data: rawLate, backgroundColor: '#D83B01', borderRadius: 4, barThickness: 12 }, // Elevate Orange
                                { label: 'Absen', data: rawAbsent, backgroundColor: '#D13438', borderRadius: 4, barThickness: 12 } // Elevate Red
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { 
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (context.parsed.y !== null) label += context.parsed.y + ' Siswa';
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: { grid: { color: '#f1f5f9', borderDash: [4, 4] }, border: { display: false } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                } else {
                    ctxBar.parentElement.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-slate-300"><i class="ph-duotone ph-chart-bar text-5xl mb-2"></i><p class="text-xs font-bold">Belum ada data grafik minggu ini.</p></div>`;
                }
            }

            // Donut Chart (Warna Semantik Microsoft)
            const ctxDonut = document.getElementById('dailyDonutChart');
            if(ctxDonut) {
                new Chart(ctxDonut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Hadir Tepat', 'Telat', 'Alfa', 'Izin/Sakit', 'Belum Hadir'],
                        datasets: [{ 
                            data: [
                                <?php echo e($presentOnTimeCount ?? 0); ?>, 
                                <?php echo e($lateCount ?? 0); ?>, 
                                <?php echo e($absentCount ?? 0); ?>, 
                                <?php echo e($sickPermitCount ?? 0); ?>,
                                <?php echo e($notYetScannedCount ?? 0); ?>

                            ], 
                            backgroundColor: ['#5295FF', '#D83B01', '#D13438', '#2A3B52', '#cbd5e1'], // Blue, Orange, Red, Navy, Slate
                            borderWidth: 0 
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, cutout: '85%', 
                        plugins: { legend: { display: false } } 
                    }
                });
            }
        });
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/dashboard.blade.php ENDPATH**/ ?>