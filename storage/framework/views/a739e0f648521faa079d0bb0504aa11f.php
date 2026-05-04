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
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');

        .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .animate-enter { 
            opacity: 0; 
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }
        
        /* Microsoft Fluent Elevation Shadows */
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

        .page-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="page-container p-4 md:p-8 space-y-8 min-h-screen bg-slate-50 font-jakarta text-elevate-dark">
        
        
        <div class="animate-enter relative rounded-[2rem] md:rounded-[3rem] bg-elevate-gradient-main p-8 md:p-12 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden group border border-white/40">
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none mix-blend-overlay z-0"></div>
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[400px] h-[400px] bg-white/30 rounded-full blur-[100px] group-hover:opacity-70 transition-opacity duration-1000 z-0"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[300px] h-[300px] bg-white/20 rounded-full blur-[80px] z-0"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="flex-1">
                    
                    
                    <div class="flex flex-wrap gap-3 mb-6 justify-center lg:justify-start">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/40 hover:bg-white/60 text-elevate-dark px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-md border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        
                        
                        <a href="<?php echo e(route('teacher.habits.leaderboard')); ?>" class="group bg-elevate-peach-dark hover:bg-elevate-peach text-white px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-md border border-elevate-peach-dark/50 transition-all flex items-center gap-2 shadow-lg shadow-elevate-peach-dark/20 w-fit">
                            <i class="ph-fill ph-trophy text-lg group-hover:scale-110 transition-transform"></i>
                            <span>Siswa Terajin</span>
                        </a>
                    </div>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/40 border border-white/50 text-elevate-dark text-[10px] font-black uppercase tracking-[0.2em] mb-6 backdrop-blur-md shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-primary opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-primary"></span>
                        </span>
                        Sistem Monitoring Karakter
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-elevate-dark tracking-tighter mb-4 leading-none">
                        Pantau <span class="text-elevate-dark">Kebiasaan</span> Siswa
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Kelola dan tinjau perkembangan karakter siswa secara real-time. Berikan apresiasi terbaik untuk setiap langkah kecil mereka.
                    </p>
                </div>

                
                <div class="w-full lg:w-[480px] xl:w-auto shrink-0 flex flex-col gap-4">
                    
                     <form id="filterForm" action="<?php echo e(route('teacher.habits.index')); ?>" method="GET" class="bg-white/30 backdrop-blur-md p-6 rounded-[2rem] border border-white/40 shadow-sm flex flex-col gap-5 relative">
                        <div id="formLoading" class="hidden absolute inset-0 bg-white/50 backdrop-blur-[2px] z-10 rounded-[2rem] flex items-center justify-center">
                            <i class="ph-bold ph-circle-notch animate-spin text-elevate-primary text-3xl"></i>
                        </div>

                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-elevate-dark uppercase tracking-widest ml-1 block">Tipe Periode</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-elevate-primary"></i>
                                    <select id="filterPeriodType" name="period_type" 
                                        class="block w-full pl-11 pr-8 py-3 bg-white/60 border-white/50 rounded-xl text-xs font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all appearance-none shadow-sm cursor-pointer" 
                                        onchange="changePeriodInput()">
                                        <option value="daily" <?php echo e(request('period_type', 'daily') == 'daily' ? 'selected' : ''); ?>>Harian</option>
                                        <option value="weekly" <?php echo e(request('period_type') == 'weekly' ? 'selected' : ''); ?>>Mingguan</option>
                                        <option value="monthly" <?php echo e(request('period_type') == 'monthly' ? 'selected' : ''); ?>>Bulanan</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-elevate-dark uppercase tracking-widest ml-1 block">Waktu</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-elevate-primary"></i>
                                    <input type="date" id="filterDateValue" name="date" 
                                        value="<?php echo e(request('period_value', $date)); ?>" 
                                        class="block w-full pl-11 pr-3 py-3 bg-white/60 border-white/50 rounded-xl text-xs font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all shadow-sm cursor-pointer uppercase"
                                        onchange="submitFilter()">
                                </div>
                            </div>

                            
                            <div class="space-y-2 sm:col-span-2">
                                <label class="text-[10px] font-black text-elevate-dark uppercase tracking-widest ml-1 block">Kelas</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-users-three absolute left-4 top-1/2 -translate-y-1/2 text-elevate-primary"></i>
                                    <select id="filterClass" name="class_id" 
                                        class="block w-full pl-11 pr-10 py-3 bg-white/60 border-white/50 rounded-xl text-xs font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all appearance-none shadow-sm cursor-pointer" 
                                        onchange="submitFilter()">
                                        <option value="">Pilih Kelas</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>" <?php echo e($classId == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                    </form>

                      
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        
                        <button onclick="openRecap()" 
                            class="group w-full py-4 bg-white hover:bg-slate-50 text-elevate-dark hover:text-emerald-600 rounded-xl font-bold shadow-sm flex flex-col items-center justify-center gap-2 transition-all border border-slate-200">
                            <i class="ph-duotone ph-whatsapp-logo text-2xl mb-1 group-hover:scale-110 transition-transform text-emerald-600"></i>
                            <span class="uppercase tracking-wider text-[9px] font-black">Lihat Rekap</span>
                        </button>

                        
                        <button onclick="printReport()" 
                            class="group w-full py-4 bg-elevate-primary hover:bg-elevate-dark text-white rounded-xl font-bold shadow-sm flex flex-col items-center justify-center gap-2 transition-all">
                            <i class="ph-bold ph-printer text-2xl mb-1 group-hover:rotate-12 transition-transform"></i>
                            <span class="uppercase tracking-wider text-[9px] font-black">Cetak PDF</span>
                        </button>
                    </div>
                </div>    
            </div>
        </div>

        
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-enter" style="animation-delay: 100ms">
            
            <div class="bg-white p-8 rounded-2xl fluent-card flex items-center gap-6 group hover:border-emerald-500 transition-all duration-300">
                <div class="w-16 h-16 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-sm border border-emerald-200">
                    <i class="ph-fill ph-shield-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sudah Melapor</p>
                    <p class="text-4xl font-black text-elevate-dark tracking-tighter">
                        <?php echo e($stats['submitted'] ?? 0); ?> <span class="text-sm font-bold text-slate-400">SISWA</span>
                    </p>
                    <?php if(!$classId): ?>
                        <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md mt-1 inline-block">Total Sekolah</span>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white p-8 rounded-2xl fluent-card flex items-center gap-6 group hover:border-rose-500 transition-all duration-300">
                <div class="w-16 h-16 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-sm border border-rose-200">
                    <i class="ph-fill ph-clock-countdown"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Belum Melapor</p>
                    <p class="text-4xl font-black text-elevate-dark tracking-tighter">
                        <?php echo e($stats['missing'] ?? 0); ?> <span class="text-sm font-bold text-slate-400">SISWA</span>
                    </p>
                </div>
            </div>

            
            <div class="bg-elevate-soft p-8 rounded-2xl fluent-card flex items-center gap-6 group hover:bg-slate-100 hover:border-elevate-primary transition-all duration-300 border border-elevate-accent/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/40 rounded-full blur-2xl"></div>
                <div class="w-16 h-16 rounded-xl bg-white text-elevate-primary border border-elevate-accent/30 flex items-center justify-center text-3xl group-hover:rotate-12 transition-transform shadow-sm">
                    <i class="ph-fill ph-chart-line-up"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-1">Tingkat Partisipasi</p>
                    <p class="text-4xl font-black text-elevate-dark tracking-tighter"><?php echo e($stats['percentage'] ?? 0); ?>%</p>
                </div>
            </div>
        </div>


        
        
        <?php if($classId): ?>
            
            <div class="animate-enter bg-white rounded-[2rem] fluent-card overflow-hidden mb-12" style="animation-delay: 200ms">
                <div class="px-6 md:px-8 py-6 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                    <h2 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                        <i class="ph-bold ph-list-checks text-elevate-primary"></i> 
                        Status Monitoring Harian
                    </h2>
                    
                    <div class="flex flex-col md:flex-row items-center gap-3 w-full xl:w-auto">
                        
                        
                        <div class="relative w-full md:w-48 shrink-0">
                            <select id="statusFilter" onchange="searchTable()" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all appearance-none cursor-pointer">
                                <option value="all">Semua Status</option>
                                <option value="pending">⏳ Menunggu Dinilai</option>
                                <option value="graded">✅ Sudah Dinilai</option>
                                <option value="missing">❌ Belum Lapor</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>

                        
                        <div class="relative w-full md:w-64 shrink-0">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama siswa..." 
                                class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all placeholder:text-slate-400 placeholder:font-medium">
                        </div>

                         <div class="flex gap-2 w-full md:w-auto shrink-0">
                             <span class="px-4 py-2.5 rounded-xl bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-black uppercase tracking-wider flex-1 text-center whitespace-nowrap shadow-sm">
                                Kelas: <?php echo e($classes->find($classId)->name ?? '-'); ?>

                             </span>
                         </div>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left" id="studentsTable">
                        <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                            <tr>
                                <th class="px-6 md:px-8 py-5">Profil Siswa</th>
                                <th class="px-6 py-5 text-center">Status Jurnal</th>
                                <th class="px-6 py-5 text-center">Waktu Masuk</th>
                                <th class="px-6 py-5 text-center">Makan (MBG)</th>
                                <th class="px-6 md:px-8 py-5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    // Menentukan Status Row untuk filter JavaScript
                                    $rowStatus = 'missing';
                                    if ($student->habit_status == 'submitted') {
                                        $rowStatus = ($student->habit_data && !empty($student->habit_data->teacher_feedback)) ? 'graded' : 'pending';
                                    }
                                ?>

                                <tr class="hover:bg-slate-50/80 transition-colors group student-row" data-status="<?php echo e($rowStatus); ?>">
                                    <td class="px-6 md:px-8 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-elevate-soft border border-elevate-accent/30 flex items-center justify-center text-elevate-primary font-black text-lg group-hover:bg-elevate-primary group-hover:text-white transition-all duration-300 shadow-sm">
                                                <?php echo e(substr($student->name, 0, 1)); ?>

                                            </div>
                                            <div>
                                                <div class="student-name font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors text-sm"><?php echo e($student->name); ?></div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 tracking-wider"><?php echo e($student->student_id); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if($student->habit_status == 'submitted'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold uppercase tracking-widest shadow-sm">
                                                <i class="ph-fill ph-check-circle text-sm"></i> Sudah Lapor
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 text-[10px] font-bold uppercase tracking-widest border border-slate-200">
                                                <i class="ph-bold ph-minus text-sm"></i> Belum Ada
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if($student->habit_data): ?>
                                            <div class="flex items-center justify-center gap-1.5 text-slate-500 font-bold text-xs bg-white px-2 py-1 rounded-md border border-slate-200 w-fit mx-auto shadow-sm">
                                                <i class="ph-bold ph-clock text-elevate-primary"></i>
                                                <?php echo e($student->habit_data->created_at->format('H:i')); ?>

                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-[10px] font-bold uppercase tracking-widest italic">MENUNGGU...</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if($student->habit_data && $student->habit_data->habit_5): ?> 
                                            <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 shadow-sm" title="Sudah Mengambil Makan">
                                                <i class="ph-bold ph-check text-lg"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-50 text-slate-300 border border-slate-200" title="Belum Mengambil">
                                                <i class="ph-bold ph-minus text-lg"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 md:px-8 py-4 text-right">
                                        <?php if($student->habit_data): ?>
                                            <div class="flex items-center justify-end gap-3">
                                                <div id="badge-feedback-<?php echo e($student->habit_data->id); ?>" class="mr-1 hidden lg:block">
                                                    <?php if($student->habit_data->teacher_feedback): ?>
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                                            <i class="ph-fill ph-check-circle"></i> Dinilai
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-600 text-[10px] font-bold uppercase tracking-wider animate-pulse shadow-sm">
                                                            <i class="ph-bold ph-clock"></i> Menunggu
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <button onclick="openDetail(<?php echo e($student->habit_data->id); ?>)" 
                                                    class="inline-flex items-center gap-2 text-white font-bold text-[10px] uppercase tracking-widest bg-elevate-dark hover:bg-elevate-primary px-5 py-2.5 rounded-xl transition-all shadow-sm active:scale-95 group/btn">
                                                    Tinjau Laporan <i class="ph-bold ph-caret-right text-sm group-hover/btn:translate-x-1 transition-transform"></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-[10px] font-bold uppercase tracking-widest italic opacity-80">Laporan Kosong</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 shadow-sm">
                                            <i class="ph-duotone ph-users-three text-3xl"></i>
                                        </div>
                                        <p class="text-elevate-dark font-bold text-sm">Tidak ada data siswa yang ditemukan</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr id="noResultsRow" class="hidden">
                                <td colspan="5" class="px-8 py-16 text-center text-slate-500 font-medium text-sm">
                                    <i class="ph-duotone ph-magnifying-glass mb-2 text-3xl text-slate-300"></i><br>
                                    Data siswa tidak ditemukan dengan filter tersebut.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: ?>
            
            <div class="animate-enter space-y-6" style="animation-delay: 100ms">
                
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 px-2">
                    <div>
                        <h3 class="text-2xl font-black text-elevate-dark tracking-tight flex items-center gap-2">
                            <i class="ph-fill ph-lightning text-amber-600"></i>
                            Aktivitas Masuk Terbaru
                        </h3>
                        <p class="text-slate-500 text-sm font-medium mt-1">Daftar siswa yang baru saja mengirimkan laporan kebiasaan hari ini.</p>
                    </div>

                    
                    <div class="flex flex-wrap items-center gap-2 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => null, 'page' => 1])); ?>" 
                           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all <?php echo e(!request('status') ? 'bg-elevate-primary text-white shadow-sm' : 'text-slate-500 hover:text-elevate-dark hover:bg-slate-50'); ?>">
                            <i class="ph-bold ph-list-dashes"></i> Semua
                        </a>
                        
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'pending', 'page' => 1])); ?>" 
                           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 <?php echo e(request('status') == 'pending' ? 'bg-amber-50 text-amber-600 shadow-sm border border-amber-200' : 'text-slate-500 hover:text-elevate-dark hover:bg-slate-50'); ?>">
                            <i class="ph-bold ph-clock-countdown"></i> Antrean Penilaian
                            <?php if(isset($stats['pending_feedback']) && $stats['pending_feedback'] > 0): ?>
                                <span class="bg-amber-600 text-white px-2 py-0.5 rounded-md text-[9px] shadow-sm"><?php echo e($stats['pending_feedback']); ?></span>
                            <?php endif; ?>
                        </a>
                        
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'graded', 'page' => 1])); ?>" 
                           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 <?php echo e(request('status') == 'graded' ? 'bg-emerald-50 text-emerald-600 shadow-sm border border-emerald-200' : 'text-slate-500 hover:text-elevate-dark hover:bg-slate-50'); ?>">
                            <i class="ph-bold ph-check-circle"></i> Sudah Dinilai
                        </a>
                    </div>
                </div>

                
                <?php if($latestSubmissions->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <?php $__currentLoopData = $latestSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group bg-white rounded-2xl p-6 fluent-card hover:border-elevate-primary transition-all duration-300 relative overflow-hidden">
                            
                            
                            <div class="absolute top-0 right-0 w-32 h-32 bg-elevate-soft rounded-bl-[4rem] -mr-6 -mt-6 transition-transform group-hover:scale-110 opacity-50"></div>

                            <div class="relative z-10">
                                
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 font-bold text-lg shadow-sm group-hover:bg-elevate-primary group-hover:text-white group-hover:border-elevate-primary transition-all duration-300">
                                        <?php echo e(substr($submission->student->name, 0, 1)); ?>

                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-elevate-dark text-sm truncate group-hover:text-elevate-primary transition-colors">
                                            <?php echo e($submission->student->name); ?>

                                        </h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-[9px] font-bold text-slate-500 uppercase truncate max-w-[80px]">
                                                <?php echo e($submission->student->schoolClass->name ?? 'N/A'); ?>

                                            </span>
                                            <span class="text-[9px] text-slate-400 font-bold flex items-center gap-1 shrink-0">
                                                <i class="ph-bold ph-clock"></i> <?php echo e($submission->updated_at->format('H:i')); ?> WIB
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="flex gap-2 mb-6">
                                    <div class="flex-1 py-2 px-1 rounded-xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="Status Shalat">
                                        <?php $isPrayerDone = $submission->prayer_subuh || $submission->prayer_dzuhur || $submission->prayer_ashar || $submission->prayer_maghrib || $submission->prayer_isya || $submission->is_udzur_syar_i; ?>
                                        <i class="ph-fill ph-mosque text-lg <?php echo e($isPrayerDone ? 'text-emerald-600' : 'text-slate-300'); ?> mb-1 block"></i>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Ibadah</span>
                                    </div>
                                    <div class="flex-1 py-2 px-1 rounded-xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="One Day One Ayat">
                                        <i class="ph-fill ph-microphone-stage text-lg <?php echo e($submission->odoa_audio_path ? 'text-elevate-primary' : 'text-slate-300'); ?> mb-1 block"></i>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Odoa</span>
                                    </div>
                                    <div class="flex-1 py-2 px-1 rounded-xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="Makan Bergizi">
                                        <i class="ph-fill ph-carrot text-lg <?php echo e($submission->habit_5 ? 'text-elevate-peach-dark' : 'text-slate-300'); ?> mb-1 block"></i>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Gizi</span>
                                    </div>
                                </div>

                                
                                <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                                    
                                    <!-- WRAPPER BADGE FEEDBACK -->
                                    <div id="badge-feedback-<?php echo e($submission->id); ?>">
                                        <?php if($submission->teacher_feedback): ?>
                                            <div class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-bold uppercase tracking-wide bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-md shadow-sm">
                                                <i class="ph-bold ph-check-circle"></i> Dinilai
                                            </div>
                                        <?php else: ?>
                                            <div class="flex items-center gap-1.5 text-amber-600 text-[10px] font-bold uppercase tracking-wide bg-amber-50 border border-amber-200 px-2 py-1 rounded-md shadow-sm animate-pulse">
                                                <i class="ph-bold ph-clock"></i> Menunggu
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <button onclick="openDetail(<?php echo e($submission->id); ?>)" 
                                        class="pl-4 pr-3 py-2 bg-elevate-dark hover:bg-elevate-primary text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm transition-all active:scale-95 flex items-center gap-2 group-hover:translate-x-1">
                                        Tinjau <i class="ph-bold ph-caret-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="mt-8">
                        <?php echo e($latestSubmissions->links()); ?>

                    </div>
                <?php else: ?>
                    
                    <div class="text-center py-24 bg-white fluent-card rounded-[2rem]">
                        <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-slate-300 rotate-3 transition-transform hover:rotate-0 shadow-sm">
                            <i class="ph-duotone ph-coffee text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-elevate-dark tracking-tight">
                            <?php echo e(request('status') == 'pending' ? 'Hore! Antrean Kosong' : 'Belum Ada Aktivitas'); ?>

                        </h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                            <?php echo e(request('status') == 'pending' ? 'Semua jurnal kebiasaan untuk hari ini sudah kamu nilai.' : 'Tampaknya belum ada siswa yang mengisi jurnal dengan filter ini.'); ?>

                        </p>
                        <?php if(request('status')): ?>
                            <a href="<?php echo e(route('teacher.habits.index')); ?>" class="inline-block mt-6 px-5 py-2.5 bg-slate-100 border border-slate-200 text-elevate-dark rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-colors shadow-sm">Reset Filter</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    
    
    <div id="detailModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeDetail()"></div>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6 relative z-10">
            <div class="bg-white rounded-[2rem] w-full max-w-3xl shadow-2xl relative transform transition-all border border-white fluent-modal">
                <div class="h-2 bg-elevate-gradient-main rounded-t-[2rem]"></div>
                <button onclick="closeDetail()" class="absolute top-6 right-6 z-20 text-slate-400 hover:text-rose-500 p-2 rounded-xl bg-slate-50 hover:bg-rose-50 transition-all border border-slate-200 hover:border-rose-200"><i class="ph-bold ph-x text-lg"></i></button>
                <div id="modalContent" class="p-6 md:p-10 font-jakarta max-h-[85vh] overflow-y-auto custom-scrollbar"></div>
            </div>
        </div>
    </div>
    
    
    
    <div id="recapModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeRecap()"></div>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6 relative z-10">
            <div class="bg-slate-50 rounded-[2rem] w-full max-w-4xl shadow-2xl relative transform transition-all overflow-hidden border border-white flex flex-col max-h-[90vh] fluent-modal">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between bg-white z-20">
                    <div>
                        <h3 class="text-xl font-bold text-elevate-dark flex items-center gap-2"><i class="ph-fill ph-whatsapp-logo text-emerald-600"></i> Rekap WhatsApp</h3>
                        <p class="text-xs text-slate-500 font-bold mt-1">Data rekap siswa per <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d F Y')); ?></p>
                    </div>
                    <div class="flex gap-2">
                         <button onclick="copyRecap()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-sm flex items-center gap-2 transition-all"><i class="ph-bold ph-copy"></i> Salin</button>
                        <button onclick="closeRecap()" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-colors border border-slate-200"><i class="ph-bold ph-x text-lg"></i></button>
                    </div>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar bg-slate-50 flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-emerald-200 shadow-sm">
                            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                                <h4 class="font-bold text-emerald-600 uppercase tracking-widest text-xs flex items-center gap-2"><i class="ph-fill ph-check-circle text-lg"></i> Sudah Lapor</h4>
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg text-xs font-bold border border-emerald-200"><?php echo e($stats['submitted'] ?? 0); ?> Siswa</span>
                            </div>
                            <?php if($classId): ?>
                                <ol class="list-decimal list-inside space-y-2 text-sm font-bold text-elevate-dark marker:text-emerald-600">
                                    <?php $__empty_1 = true; $__currentLoopData = $students->where('habit_status', 'submitted'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> <li class="pl-1"><?php echo e($s->name); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <p class="text-slate-400 italic text-center py-4 text-xs font-medium">Belum ada siswa yang melapor.</p> <?php endif; ?>
                                </ol>
                            <?php else: ?>
                                <div class="text-center py-8 bg-slate-50 rounded-xl border border-slate-100">
                                    <i class="ph-duotone ph-users-three text-4xl text-slate-300 mb-2"></i>
                                    <p class="text-slate-500 text-xs font-bold px-4">Tampilan Global</p>
                                    <p class="text-slate-400 text-[10px] mt-1 px-4">Pilih kelas spesifik untuk melihat daftar nama siswa.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-rose-200 shadow-sm">
                            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                                <h4 class="font-bold text-rose-600 uppercase tracking-widest text-xs flex items-center gap-2"><i class="ph-fill ph-x-circle text-lg"></i> Belum Lapor</h4>
                                <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-lg text-xs font-bold border border-rose-200"><?php echo e($stats['missing'] ?? 0); ?> Siswa</span>
                            </div>
                            <?php if($classId): ?>
                                <ol class="list-decimal list-inside space-y-2 text-sm font-bold text-elevate-dark marker:text-rose-600">
                                    <?php $__empty_1 = true; $__currentLoopData = $students->where('habit_status', 'missing'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> <li class="pl-1"><?php echo e($s->name); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <p class="text-slate-400 italic text-center py-4 text-xs font-medium">Semua siswa sudah melapor!</p> <?php endif; ?>
                                </ol>
                            <?php else: ?>
                                <div class="text-center py-8 bg-slate-50 rounded-xl border border-slate-100">
                                    <i class="ph-duotone ph-list-magnifying-glass text-4xl text-slate-300 mb-2"></i>
                                    <p class="text-slate-500 text-xs font-bold px-4">Tampilan Global</p>
                                    <p class="text-slate-400 text-[10px] mt-1 px-4">Pilih kelas spesifik untuk melihat daftar nama siswa.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php if($classId): ?>
 <textarea id="recapText" class="hidden">
*LAPORAN MONITORING KEBIASAAN BAIK*
Kelas: <?php echo e($classId ? ($classes->find($classId)->name ?? '-') : 'SEMUA KELAS (GLOBAL)'); ?>

Tanggal: <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('l, d F Y')); ?>


✅ *SUDAH LAPOR (<?php echo e($stats['submitted'] ?? 0); ?> Siswa)*
<?php if($classId): ?>
<?php $__currentLoopData = $students->where('habit_status', 'submitted'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php echo e($loop->iteration); ?>. <?php echo e($s->name); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
_Tampilan Global: Daftar nama disembunyikan._
<?php endif; ?>
❌ *BELUM LAPOR (<?php echo e($stats['missing'] ?? 0); ?> Siswa)*
<?php if($classId): ?>
<?php $__currentLoopData = $students->where('habit_status', 'missing'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php echo e($loop->iteration); ?>. <?php echo e($s->name); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
_Tampilan Global: Daftar nama disembunyikan._
<?php endif; ?>
_Mohon segera mengisi jurnal kebiasaan baik._
Terima kasih. 🙏
    </textarea>
    <?php endif; ?>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // =========================================================================
        // INISIALISASI SAAT HALAMAN DIMUAT
        // =========================================================================
        document.addEventListener('DOMContentLoaded', function() {
            // FIX: Pindahkan elemen modal langsung ke dalam <body> agar terlepas dari Stacking Context layout utama
            const detailModal = document.getElementById('detailModal');
            const recapModal = document.getElementById('recapModal');
            
            // "Teleport" modal ke paling akhir dokumen HTML
            if (detailModal) document.body.appendChild(detailModal);
            if (recapModal) document.body.appendChild(recapModal);

            // Jalankan fungsi agar tipe input tanggal sesuai saat pertama kali diload
            changePeriodInput();
        });

        // =========================================================================
        // 1. FUNGSI PENCARIAN DI TABEL & FILTER STATUS
        // =========================================================================
        function searchTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const statusFilter = document.getElementById("statusFilter").value;
            const rows = document.querySelectorAll(".student-row");
            let hasResults = false;

            rows.forEach(row => {
                const nameText = row.querySelector(".student-name").innerText.toLowerCase();
                const rowStatus = row.getAttribute("data-status");

                const matchName = nameText.includes(input);
                const matchStatus = (statusFilter === 'all' || rowStatus === statusFilter);

                if (matchName && matchStatus) {
                    row.style.display = "";
                    hasResults = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Menampilkan atau menyembunyikan tulisan "Tidak ada data"
            const noResultsRow = document.getElementById("noResultsRow");
            if(noResultsRow) {
                noResultsRow.style.display = hasResults ? "none" : "";
            }
        }

        // =========================================================================
        // 2. FUNGSI FILTER & KALENDER DINAMIS
        // =========================================================================
        function submitFilter() {
            document.getElementById('formLoading').classList.remove('hidden');
            document.getElementById('filterForm').submit();
        }

        function changePeriodInput() {
            const periodType = document.getElementById('filterPeriodType').value;
            const dateInput = document.getElementById('filterDateValue');
            
            if (!dateInput) return; // Mencegah error jika elemen tidak ditemukan
            
            // Ubah tipe input kalender (Harian/Mingguan/Bulanan)
            if (periodType === 'daily') {
                dateInput.type = 'date';
                dateInput.name = 'period_value'; 
            } else if (periodType === 'weekly') {
                dateInput.type = 'week';
                dateInput.name = 'period_value';
            } else if (periodType === 'monthly') {
                dateInput.type = 'month';
                dateInput.name = 'period_value';
            }
        }

          // =========================================================================
        // 3. FUNGSI CETAK PDF
        // =========================================================================
        function printReport() {
            // Sesuaikan ID dengan input filter terbaru
            const periodValue = document.getElementById('filterDateValue').value;
            const periodType = document.getElementById('filterPeriodType').value;
            const classId = document.getElementById('filterClass').value;
            
            // Validasi dihapus agar bisa cetak global (classId kosong)
            
            // Membuka tab baru untuk print report dengan parameter dinamis
            window.open(`<?php echo e(route('teacher.habits.print')); ?>?period_type=${periodType}&period_value=${periodValue}&class_id=${classId}`, '_blank');
        }

        // =========================================================================
        // 4. FUNGSI MODAL DETAIL JURNAL SISWA
        // =========================================================================
        function openDetail(id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            content.innerHTML = `<div class="flex flex-col items-center justify-center py-20"><div class="relative"><div class="w-16 h-16 border-4 border-slate-200 rounded-full"></div><div class="w-16 h-16 border-4 border-elevate-primary border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div></div><p class="mt-4 text-elevate-primary text-[10px] font-black uppercase tracking-widest">Mengambil Jurnal...</p></div>`;
            
            fetch(`<?php echo e(url('/teacher/habits/detail')); ?>/${id}`)
                .then(response => { if (!response.ok) throw new Error('Network error'); return response.text(); })
                .then(html => { content.innerHTML = html; })
                .catch(err => { content.innerHTML = `<div class="text-center py-20"><div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-rose-200"><i class="ph-bold ph-warning-circle text-3xl"></i></div><h3 class="text-lg font-bold text-elevate-dark">Gagal Memuat Jurnal</h3><p class="text-slate-500 text-xs mb-6 font-medium">Koneksi bermasalah.</p><button onclick="openDetail(${id})" class="bg-elevate-primary text-white px-8 py-3 rounded-xl font-bold text-xs shadow-sm hover:bg-elevate-dark transition-colors">Muat Ulang</button></div>`; });
        }

        function closeDetail() { 
            document.getElementById('detailModal').classList.add('hidden'); 
            document.body.style.overflow = 'auto'; 
            
            // Hentikan pemutaran audio jika modal ditutup (Mencegah bug suara)
            const content = document.getElementById('modalContent');
            if(content) {
                const audios = content.getElementsByTagName('audio');
                for(let i=0; i<audios.length; i++) {
                    audios[i].pause();
                }
            }
        }

        // =========================================================================
        // 5. FUNGSI REKAP WHATSAPP
        // =========================================================================
        function openRecap() { 
            document.getElementById('recapModal').classList.remove('hidden'); 
            document.body.style.overflow = 'hidden'; 
        }
        
        function closeRecap() { 
            document.getElementById('recapModal').classList.add('hidden'); 
            document.body.style.overflow = 'auto'; 
        }
        
        function copyRecap() {
            const text = document.getElementById('recapText').value;
            navigator.clipboard.writeText(text).then(() => { 
                Swal.fire({
                    icon:'success', title:'Tersalin', text:'Rekap berhasil disalin ke Clipboard!',
                    toast:true, position:'top-end', showConfirmButton:false, timer:3000,
                    customClass: { popup: 'fluent-modal rounded-xl border border-emerald-200' }
                }); 
            }).catch(err => { 
                console.error(err); 
                alert('Gagal menyalin.'); 
            });
        }

        // =========================================================================
        // 6. FUNGSI AJAX SUBMIT FEEDBACK (Real-Time Badge Update)
        // =========================================================================
        function submitFeedbackAjax(event, formElement, passedHabitId = null) {
            event.preventDefault(); // Mencegah browser me-reload halaman
            
            const url = formElement.action;
            const formData = new FormData(formElement);
            const btnSubmit = formElement.querySelector('#btn-submit-feedback');
            const originalText = btnSubmit.innerHTML;
            
            // Ekstrak ID habit dengan benar
            let habitId = passedHabitId;
            if (!habitId) {
                const urlParts = url.split('/').filter(Boolean);
                habitId = urlParts[urlParts.length - 1]; 
            }

            // Ubah status tombol jadi "Menyimpan..."
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
            btnSubmit.classList.add('opacity-70');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json(); 
            })
            .then(data => {
                // Munculkan notifikasi sukses
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil!', 
                    text: 'Feedback/Apresiasi berhasil tersimpan.',
                    toast: true, 
                    position: 'top-end', 
                    showConfirmButton: false, 
                    timer: 3000,
                    customClass: { popup: 'fluent-modal rounded-xl border border-emerald-200 bg-white' }
                });

                // Ganti nama tombol di modal
                btnSubmit.innerHTML = '<i class="ph-bold ph-check"></i> Perbarui Feedback';

                // ==========================================
                // MENCARI & MENGUBAH BADGE TANPA RELOAD
                // ==========================================
                const badgeElement = document.getElementById('badge-feedback-' + habitId);
                
                if (badgeElement) {
                    // Update tampilan badge menjadi Hijau ("Dinilai") 
                    badgeElement.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 text-[10px] font-black uppercase tracking-wider shadow-sm">
                            <i class="ph-fill ph-check-circle"></i> Dinilai
                        </span>
                    `;
                    
                    // UPDATE ATRIBUT BARIS (Agar Filter Status di Tabel tetap Akurat)
                    const tableRow = badgeElement.closest('tr.student-row');
                    if(tableRow) {
                        tableRow.setAttribute('data-status', 'graded');
                    }
                } else {
                    console.log("Badge untuk ID " + habitId + " tidak terdeteksi");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error', 
                    title: 'Oops...', 
                    text: 'Terjadi kesalahan saat menyimpan feedback.',
                    customClass: { popup: 'fluent-modal rounded-2xl border border-rose-200 bg-white' }
                });
                btnSubmit.innerHTML = originalText;
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-70');
            });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/teacher_index.blade.php ENDPATH**/ ?>