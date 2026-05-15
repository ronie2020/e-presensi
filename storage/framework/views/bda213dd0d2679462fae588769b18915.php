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
        
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        
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
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

         @media print {
            @page { size: landscape; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
            .no-print, header, nav, footer, form, .shadow-sm, .modal-backdrop, .hero-decoration { display: none !important; }
            .print-container { padding: 0 !important; margin: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; background: white !important; }
            .print-table th, .print-table td { border: 1px solid #cbd5e1 !important; font-size: 10pt !important; padding: 8px !important; }
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; }
            .page-container { padding: 0 !important; background: white !important; }
        }
        .print-header { display: none; }
        [x-cloak] { display: none !important; }
    </style>

    
    <div x-data="{
        isModalOpen: false,
        studentName: '',
        details: {
            fasting: false,
            prayers: {}, 
            sunnahs: {}, 
            khotib: '',
            summary: '',
            tadarus_surah: '',
            tadarus_ayah: '',
            murojaah: '',
            kultum_penceramah: '',
            kultum_summary: ''
        }, 
        formAction: '',
        currentScore: '',
        currentNote: '',
        
        openFeedback(id, logData, sName) {
            this.studentName = sName;
            this.currentScore = logData.teacher_score !== null ? logData.teacher_score : 100;
            this.currentNote = logData.teacher_note || '';
            
            this.details = {
                fasting: logData.is_fasting || false,
                prayers: logData.prayers || {},
                sunnahs: logData.sunnah_deeds || {},
                khotib: logData.friday_khotib || '',
                summary: logData.friday_summary || '',
                tadarus_surah: logData.tadarus_surah || '',
                tadarus_ayah: logData.tadarus_ayah || '',
                murojaah: logData.murojaah_surah || '',
                kultum_penceramah: logData.kultum_penceramah || '',
                kultum_summary: logData.kultum_summary || ''
            };

            this.formAction = '<?php echo e(route('admin.ramadan.verify', ':id')); ?>'.replace(':id', id); 
            this.isModalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isModalOpen = false;
            document.body.style.overflow = 'auto';
        },
        setScore(value) { this.currentScore = value; }
    }" class="page-container p-4 md:p-8 space-y-6 md:space-y-8 min-h-screen bg-slate-50 font-jakarta print-container">
        
       
       <div class="animate-enter relative rounded-[2rem] md:rounded-[3rem] bg-elevate-gradient-main p-8 md:p-12 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden group border border-white/40 no-print">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none no-print z-0"></div>
            
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[500px] h-[500px] bg-white/30 rounded-full blur-[100px] group-hover:opacity-60 transition-opacity duration-1000 hero-decoration z-0"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[400px] h-[400px] bg-white/20 rounded-full blur-[80px] hero-decoration z-0"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="flex-1 min-w-0">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/40 border border-white/50 text-elevate-dark text-[10px] font-black uppercase tracking-[0.2em] mb-6 backdrop-blur-md shadow-sm">
                        <i class="ph-fill ph-moon-stars"></i> Program Ramadhan
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-elevate-dark tracking-tighter mb-4 leading-none">
                        Rekap <span class="text-elevate-dark">Mutabaah</span> Siswa
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Monitoring ibadah harian dan kegiatan Ramadhan.
                        <br><span class="text-elevate-dark font-black"><?php echo e(\Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM Y')); ?></span>
                    </p>
                </div>

                
                <div class="w-full lg:w-[450px] xl:w-[500px] shrink-0 flex flex-col gap-4">
                    <form action="<?php echo e(route('admin.ramadan.reports')); ?>" method="GET" class="bg-white/30 backdrop-blur-md p-6 rounded-[2rem] border border-white/40 shadow-sm flex flex-col gap-5">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-elevate-dark uppercase tracking-widest ml-1 block">Tanggal</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-elevate-primary"></i>
                                    <input type="date" name="date" value="<?php echo e($date); ?>" onchange="this.form.submit()" 
                                        class="block w-full pl-11 pr-4 py-3 bg-white/60 border-white/50 rounded-2xl text-xs font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all uppercase shadow-sm">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-elevate-dark uppercase tracking-widest ml-1 block">Kelas</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-chalkboard absolute left-4 top-1/2 -translate-y-1/2 text-elevate-primary"></i>
                                    <select name="class_id" onchange="this.form.submit()" 
                                        class="block w-full pl-11 pr-10 py-3 bg-white/60 border-white/50 rounded-2xl text-xs font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all appearance-none shadow-sm cursor-pointer">
                                        <option value="">Semua Kelas</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c->id); ?>" <?php echo e($selectedClass == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2 sm:col-span-2">
                                <label class="text-[10px] font-black text-elevate-dark uppercase tracking-widest ml-1 block">Cari Siswa</label>
                                <div class="relative group flex items-center gap-2">
                                    <div class="relative w-full">
                                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-elevate-primary"></i>
                                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Ketik nama siswa..." 
                                            class="block w-full pl-11 pr-4 py-3 bg-white/60 border-white/50 rounded-2xl text-xs font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all shadow-sm placeholder:font-medium placeholder-elevate-dark/50">
                                    </div>
                                    <button type="submit" class="p-3 rounded-2xl bg-elevate-primary text-white hover:bg-elevate-dark transition shadow-md"><i class="ph-bold ph-caret-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    
                    <?php if($selectedClass): ?>
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="window.print()" class="flex-1 py-3 bg-white hover:bg-slate-50 text-elevate-dark rounded-xl font-bold shadow-sm flex flex-col items-center justify-center gap-1 transition-all active:scale-95 border border-slate-200">
                                <i class="ph-bold ph-printer text-xl"></i>
                                <span class="uppercase tracking-wider text-[9px]">Cetak Harian</span>
                            </button>
                            <a href="<?php echo e(route('admin.ramadan.exportPdf', ['class_id' => $selectedClass])); ?>" target="_blank" class="flex-1 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-sm flex flex-col items-center justify-center gap-1 transition-all active:scale-95 border border-transparent">
                                <i class="ph-bold ph-file-pdf text-xl"></i>
                                <span class="uppercase tracking-wider text-[9px]">Rekap Full PDF</span>
                            </a>
                            <a href="<?php echo e(route('admin.ramadan.exportExcel', ['class_id' => $selectedClass])); ?>" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-sm flex flex-col items-center justify-center gap-1 transition-all active:scale-95 border border-transparent">
                                <i class="ph-bold ph-file-csv text-xl"></i>
                                <span class="uppercase tracking-wider text-[9px]">Export Excel</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-5 animate-enter no-print" style="animation-delay: 100ms">
            <div class="bg-white fluent-card p-6 rounded-[1.5rem] flex items-center gap-4 group hover:border-elevate-primary">
                <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary border border-elevate-accent/30 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 group-hover:bg-elevate-primary group-hover:text-white transition-all"><i class="ph-fill ph-student"></i></div>
                <div>
                    <div class="text-2xl font-black text-elevate-dark tracking-tight"><?php echo e($stats['total_students']); ?></div>
                    <div class="text-[9px] uppercase font-bold text-slate-400 tracking-widest">
                        <?php echo e($selectedClass ? 'Siswa Kelas' : 'Total Siswa'); ?>

                    </div>
                </div>
            </div>
            
             <div class="bg-white fluent-card p-6 rounded-[1.5rem] flex items-center gap-4 group hover:border-emerald-500">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all shrink-0"><i class="ph-fill ph-bowl-food"></i></div>
                <div class="flex-1 w-full">
                    <div class="text-2xl font-black text-elevate-dark tracking-tight"><?php echo e($stats['fasting_count']); ?> <span class="text-xs text-emerald-600 font-bold">(<?php echo e($stats['percentage_fasting']); ?>%)</span></div>
                    <div class="text-[9px] uppercase font-bold text-slate-400 tracking-widest mb-1.5">Berpuasa</div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-600 h-1.5 rounded-full transition-all duration-1000" style="width: <?php echo e($stats['percentage_fasting']); ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white fluent-card p-6 rounded-[1.5rem] flex items-center gap-4 group hover:border-amber-500">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all"><i class="ph-fill ph-hands-praying"></i></div>
                <div>
                    <div class="text-2xl font-black text-elevate-dark tracking-tight"><?php echo e($stats['prayer_complete_count']); ?></div>
                    <div class="text-[9px] uppercase font-bold text-slate-400 tracking-widest">Shalat Lengkap (5W)</div>
                </div>
            </div>
  <?php if($isFriday): ?>
            <div class="bg-white fluent-card p-6 rounded-[1.5rem] flex items-center gap-4 group hover:border-elevate-primary relative overflow-hidden">
                <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary border border-elevate-accent/30 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 group-hover:bg-elevate-primary group-hover:text-white transition-all"><i class="ph-fill ph-mosque"></i></div>
                <div class="relative z-10">
                    <div class="text-2xl font-black text-elevate-dark tracking-tight"><?php echo e($stats['friday_log_count']); ?></div>
                    <div class="text-[9px] uppercase font-bold text-slate-400 tracking-widest">Jurnal Jumat</div>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-slate-50 p-6 rounded-[1.5rem] flex items-center gap-4 group border border-slate-200">
                <div class="w-12 h-12 rounded-xl bg-white text-slate-400 border border-slate-200 flex items-center justify-center text-xl"><i class="ph-bold ph-calendar-x"></i></div>
                <div>
                    <div class="text-sm font-bold text-slate-500 mb-0.5">Bukan Hari Jumat</div>
                    <div class="text-[9px] uppercase font-bold text-slate-400 tracking-widest">Fitur Non-aktif</div>
                </div>
            </div>
            <?php endif; ?>
        </div>

         
        
        
        <?php if(isset($topStudents) && $topStudents->count() > 0): ?>
        <div class="animate-enter bg-white rounded-[2rem] fluent-card p-6 md:p-8 no-print" style="animation-delay: 150ms">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-black text-elevate-dark flex items-center gap-2">
                        <i class="ph-fill ph-crown text-elevate-peach-dark text-2xl"></i> Siswa Paling Rajin
                    </h3>
                    <p class="text-slate-500 text-xs font-medium mt-1">
                        Berdasarkan konsistensi pengisian mutabaah terbanyak selama bulan Ramadhan ini.
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-3">
                    <span class="px-4 py-2 rounded-xl bg-elevate-peach-light/30 text-elevate-peach-dark text-[10px] font-bold uppercase tracking-wider border border-elevate-peach/50 hidden sm:inline-block">
                        Top <?php echo e($topStudents->count()); ?> Leaderboard
                    </span>
                    <a href="<?php echo e(route('admin.ramadan.leaderboard')); ?>" class="px-5 py-2 rounded-xl bg-elevate-dark hover:bg-elevate-primary text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-sm flex items-center gap-2">
                        Lihat Semua <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <?php $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $topStudent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-4 p-5 rounded-[1.5rem] bg-white border border-slate-100 hover:border-elevate-primary transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-elevate-primary/10 fluent-card">
                        
                        
                        <div class="relative shrink-0">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center font-black text-xl shadow-sm border border-slate-200 overflow-hidden bg-slate-50">
                                <?php
                                    // Menggunakan warna semantik Elevate untuk background avatar
                                    $avatarBg = $index == 0 ? 'c86845' : ($index == 1 ? '0d52a1' : '059669');
                                ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topStudent->name)); ?>&background=<?php echo e($avatarBg); ?>&color=fff&bold=true" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <?php
                                $badgeColor = $index == 0 ? 'bg-elevate-peach-dark border-elevate-peach' : ($index == 1 ? 'bg-elevate-primary border-elevate-accent/50' : 'bg-emerald-600 border-emerald-300');
                            ?>
                            <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-sm border border-white <?php echo e($badgeColor); ?>">
                                #<?php echo e($index + 1); ?>

                            </div>
                        </div>

                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-elevate-dark text-sm truncate" title="<?php echo e($topStudent->name); ?>">
                                <?php echo e($topStudent->name); ?>

                            </h4>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-1 flex items-center gap-1.5">
                                <i class="ph-fill ph-chalkboard text-slate-400"></i> <?php echo e($topStudent->schoolClass->name ?? 'N/A'); ?>

                            </div>
                        </div>

                        
                        <div class="text-right shrink-0 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                            <div class="text-lg font-black leading-none text-elevate-dark">
                                <?php echo e($topStudent->total_logs_count ?? $topStudent->total_logs ?? 0); ?>

                            </div>
                            <div class="text-[8px] uppercase font-bold text-slate-400 tracking-widest mt-1">Hari</div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
        

           
        <div class="print-header">
            <h2 class="text-xl font-bold uppercase">Laporan Harian Mutabaah Ramadhan</h2>
            <p class="text-sm"><?php echo e($classes->find($selectedClass)->name ?? 'Semua Kelas'); ?> &bull; <?php echo e(\Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y')); ?></p>
        </div>
        
        
        <?php if($selectedClass): ?>
            
            <div class="animate-enter bg-white rounded-[2rem] fluent-card overflow-hidden mb-12 print-container" style="animation-delay: 200ms">
                <div class="px-6 md:px-8 py-6 border-b border-slate-100 flex items-center justify-between no-print bg-white">
                    <h2 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                        <i class="ph-bold ph-list-checks text-elevate-primary"></i> Data Mutabaah Kelas
                    </h2>
                    <span class="px-4 py-2 rounded-xl bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                        <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d F Y')); ?>

                    </span>
                </div>

                <div class="overflow-x-auto overflow-y-auto custom-scrollbar max-h-[600px] relative">
                    <table class="w-full text-left print-table text-elevate-dark">
                        <thead class="bg-slate-50/90 backdrop-blur-md text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200 sticky top-0 z-20 shadow-sm">
                            <tr>
                                <th class="px-6 py-5 text-center w-16">No</th>
                                <th class="px-6 py-5">Profil Siswa</th>
                                <th class="px-6 py-5 text-center">Puasa</th>
                                <th class="px-6 py-5 text-center">Shalat 5W</th>
                                <th class="px-6 py-5 text-center no-print">Detail</th>
                                
                                <?php if($isFriday): ?>
                                <th class="px-6 py-5 text-center text-elevate-primary bg-elevate-soft">Jumat</th>
                                <?php endif; ?>

                                <th class="px-6 py-5">Tilawah</th>
                                <th class="px-6 py-5 text-center bg-white border-l border-slate-100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php 
                                $log = $student->ramadanLogs->first();
                                $prayerCount = $log ? count(array_filter($log->prayers ?? [])) : 0;
                            ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 text-center text-xs font-bold text-slate-400"><?php echo e($index + 1); ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors"><?php echo e($student->name); ?></div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-0.5 font-mono"><?php echo e($student->student_id); ?></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($log && $log->is_fasting): ?>
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto shadow-sm"><i class="ph-fill ph-check"></i></div>
                                    <?php elseif($log): ?>
                                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center mx-auto shadow-sm"><i class="ph-bold ph-x"></i></div>
                                    <?php else: ?>
                                        <span class="text-slate-300 font-bold">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($log): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border <?php echo e($prayerCount == 5 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-amber-50 text-amber-600 border-amber-200'); ?>">
                                            <?php echo e($prayerCount); ?>/5
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-300 font-bold">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center no-print">
                                    <?php if($log && is_array($log->prayers)): ?>
                                        <div class="flex justify-center gap-1.5">
                                            <?php $__currentLoopData = ['subuh','dzuhur','ashar','maghrib','isya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div title="<?php echo e(ucfirst($p)); ?>" class="w-2.5 h-2.5 rounded-full border border-white <?php echo e(($log->prayers[$p] ?? false) ? 'bg-elevate-primary' : 'bg-slate-200'); ?>"></div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <?php if($isFriday): ?>
                                <td class="px-6 py-4 text-center bg-elevate-soft/30">
                                    <?php if($log && $log->friday_khotib): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-elevate-soft text-elevate-primary text-[10px] font-bold border border-elevate-accent/30 shadow-sm" title="<?php echo e(Str::limit($log->friday_summary, 50)); ?>">
                                            <i class="ph-bold ph-check-circle"></i> Ada
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-[10px] font-medium">Kosong</span>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>

                                <td class="px-6 py-4">
                                    <?php if($log && $log->tadarus_surah): ?>
                                        <div class="flex items-center gap-2">
                                            <i class="ph-bold ph-book-open-text text-elevate-primary"></i>
                                            <span class="text-xs font-bold text-elevate-dark"><?php echo e(Str::limit($log->tadarus_surah, 12)); ?> <span class="text-slate-400 font-normal ml-1">Ayat <?php echo e($log->tadarus_ayah); ?></span></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px] font-bold">-</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-6 py-4 text-center bg-white border-l border-slate-100">
                                    <?php if($log): ?>
                                        <button type="button" 
                                            @click="openFeedback(<?php echo e($log->id); ?>, <?php echo e(json_encode($log)); ?>, <?php echo e(json_encode($student->name)); ?>)"
                                            class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm active:scale-95 border
                                            <?php echo e($log->teacher_verified_at ? 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100' : 'bg-white border-slate-200 text-slate-500 hover:bg-elevate-primary hover:text-white hover:border-elevate-primary'); ?>">
                                            <?php if($log->teacher_verified_at): ?>
                                                <i class="ph-bold ph-check-circle text-sm"></i> <?php echo e($log->teacher_score); ?>

                                            <?php else: ?>
                                                <i class="ph-bold ph-pencil-simple text-sm"></i> Nilai
                                            <?php endif; ?>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-[10px] font-bold bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">Belum Ada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="<?php echo e($isFriday ? 8 : 7); ?>" class="text-center py-20 text-slate-400 font-medium">Data siswa tidak ditemukan untuk kelas ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            
            <div class="animate-enter space-y-6 no-print" style="animation-delay: 100ms">
                
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 px-2">
                    <div>
                        <h3 class="text-2xl font-black text-elevate-dark tracking-tight flex items-center gap-2">
                            <i class="ph-duotone ph-lightning text-amber-600"></i>
                            Aktivitas Ramadhan Global
                        </h3>
                        <p class="text-slate-500 text-sm font-medium mt-1">Pantau seluruh aktivitas mutabaah siswa hari ini.</p>
                    </div>

                    
                    <div class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-xl border border-slate-200 shadow-sm">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => null, 'page' => 1])); ?>" 
                           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all <?php echo e(!request('status') ? 'bg-white text-elevate-primary shadow-sm border border-slate-200' : 'text-slate-500 hover:text-elevate-dark hover:bg-slate-100'); ?>">
                            <i class="ph-bold ph-list-dashes"></i> Semua
                        </a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'pending', 'page' => 1])); ?>" 
                           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 <?php echo e(request('status') == 'pending' ? 'bg-amber-50 text-amber-600 shadow-sm border border-amber-200' : 'text-slate-500 hover:text-elevate-dark hover:bg-slate-100'); ?>">
                            <i class="ph-bold ph-clock-countdown"></i> Menunggu Penilaian
                        </a>
                    </div>
                </div>

                
                <?php if(isset($latestLogs) && $latestLogs->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <?php $__currentLoopData = $latestLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $prayerCount = count(array_filter($log->prayers ?? [])); ?>
                        <div class="group bg-white rounded-[2rem] p-6 fluent-card hover:border-elevate-primary transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-elevate-soft rounded-bl-[4rem] -mr-6 -mt-6 transition-transform group-hover:scale-110 opacity-50"></div>
                            <div class="relative z-10">
                                
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 font-black text-lg group-hover:bg-elevate-primary group-hover:text-white group-hover:border-elevate-primary transition-all duration-300 shadow-sm">
                                        <?php echo e(substr($log->student->name, 0, 1)); ?>

                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-elevate-dark text-sm truncate group-hover:text-elevate-primary transition-colors"><?php echo e($log->student->name); ?></h4>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-[9px] font-bold text-slate-500 uppercase truncate max-w-[80px]">
                                                <?php echo e($log->student->schoolClass->name ?? 'N/A'); ?>

                                            </span>
                                            <span class="text-[9px] text-slate-400 font-bold flex items-center gap-1 shrink-0"><i class="ph-bold ph-clock"></i> <?php echo e($log->updated_at->format('H:i')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 mb-6">
                                    <div class="flex-1 py-3 px-1 rounded-xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm">
                                        <i class="ph-fill ph-bowl-food text-xl <?php echo e($log->is_fasting ? 'text-emerald-600' : 'text-rose-600'); ?> mb-1.5 block"></i>
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide">Puasa</span>
                                    </div>
                                    <div class="flex-1 py-3 px-1 rounded-xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm">
                                        <div class="flex items-center justify-center gap-0.5 text-xl font-black <?php echo e($prayerCount == 5 ? 'text-emerald-600' : 'text-amber-600'); ?> mb-1.5">
                                            <?php echo e($prayerCount); ?><span class="text-[10px] text-slate-400">/5</span>
                                        </div>
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide">Wajib</span>
                                    </div>
                                    <div class="flex-1 py-3 px-1 rounded-xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm">
                                        <i class="ph-fill ph-book-open-text text-xl <?php echo e($log->tadarus_surah ? 'text-elevate-primary' : 'text-slate-300'); ?> mb-1.5 block"></i>
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide">Qur'an</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between gap-3 pt-5 border-t border-slate-100">
                                    <?php if($log->teacher_verified_at): ?>
                                        <div class="flex items-center gap-1.5 text-emerald-700 text-[10px] font-bold uppercase tracking-wide bg-emerald-50 border border-emerald-200 px-2.5 py-1.5 rounded-lg shadow-sm">
                                            <i class="ph-bold ph-check-circle"></i> Nilai: <?php echo e($log->teacher_score); ?>

                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center gap-1.5 text-amber-600 text-[10px] font-bold uppercase tracking-wide bg-amber-50 border border-amber-200 px-2.5 py-1.5 rounded-lg shadow-sm">
                                            <i class="ph-bold ph-clock"></i> Menunggu
                                        </div>
                                    <?php endif; ?>

                                    <button @click="openFeedback(<?php echo e($log->id); ?>, <?php echo e(json_encode($log)); ?>, <?php echo e(json_encode($log->student->name)); ?>)" 
                                        class="pl-4 pr-3 py-2 bg-elevate-dark hover:bg-elevate-primary text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm transition-all active:scale-95 flex items-center gap-2 group-hover:translate-x-1">
                                        Tinjau <i class="ph-bold ph-caret-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    
                    <div class="mt-8">
                        <?php echo e($latestLogs->links()); ?>

                    </div>
                <?php else: ?>
                    
                    <div class="text-center py-24 bg-white fluent-card rounded-[2.5rem]">
                        <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-slate-400 rotate-3 transition-transform hover:rotate-0 shadow-sm">
                            <i class="ph-duotone ph-moon-stars text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-elevate-dark tracking-tight">
                            <?php echo e(request('status') == 'pending' ? 'Semua Jurnal Telah Dinilai' : 'Belum Ada Aktivitas'); ?>

                        </h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                            <?php echo e(request('status') == 'pending' ? 'Bagus! Antrean mutabaah yang perlu verifikasi sudah kosong.' : 'Belum ada data jurnal siswa yang cocok dengan filter saat ini.'); ?>

                        </p>
                        <?php if(request('status') || request('search')): ?>
                            <a href="<?php echo e(route('admin.ramadan.reports')); ?>" class="inline-block mt-6 px-5 py-2.5 bg-slate-100 border border-slate-200 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-200 hover:text-elevate-dark transition-colors shadow-sm">Reset Filter</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <div x-cloak x-show="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center px-4 modal-backdrop">
            
            <div x-show="isModalOpen" 
                x-transition.opacity.duration.300ms
                @click="closeModal()"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            
            <div x-show="isModalOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative bg-slate-50 rounded-[2rem] fluent-modal w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh] border border-white">
                
                
                <div class="relative bg-elevate-gradient-main p-8 text-elevate-dark shrink-0 overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/30 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <h3 class="font-black text-2xl tracking-tight">Detail Mutabaah Siswa</h3>
                        <p class="text-elevate-dark/80 text-sm font-bold mt-1 flex items-center gap-2">
                            <i class="ph-bold ph-student"></i> <span x-text="studentName"></span>
                        </p>
                    </div>
                    <button @click="closeModal()" class="absolute top-6 right-6 w-10 h-10 bg-white/30 hover:bg-white/50 border border-white/40 flex items-center justify-center rounded-xl transition text-elevate-dark backdrop-blur-md shadow-sm"><i class="ph-bold ph-x text-lg"></i></button>
                </div>

                
                <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar space-y-6 bg-slate-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                        
                        <div class="space-y-5 md:space-y-6">
                            
                            <div class="bg-white p-5 rounded-2xl border border-slate-100 fluent-card flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-sm border" :class="details.fasting ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200'">
                                        <i class="ph-fill" :class="details.fasting ? 'ph-check-circle' : 'ph-x-circle'"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-elevate-dark text-sm">Puasa Hari Ini</h4>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-0.5" x-text="details.fasting ? 'Melaksanakan' : 'Tidak / Berhalangan'"></p>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-white p-6 rounded-2xl fluent-card border border-slate-100">
                                <h4 class="font-bold text-elevate-dark text-sm mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-clock text-elevate-primary"></i> Shalat Wajib
                                </h4>
                                <div class="grid grid-cols-5 gap-2">
                                    <template x-for="p in ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center border shadow-sm transition-all"
                                                :class="details.prayers[p] ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-slate-50 border-slate-200 text-slate-300'">
                                                <i class="ph-fill text-xl" :class="details.prayers[p] ? 'ph-check-circle' : 'ph-circle'"></i>
                                            </div>
                                            <span class="text-[9px] font-bold uppercase text-slate-500 tracking-wider" x-text="p"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            
                            <div class="bg-white p-6 rounded-2xl fluent-card border border-slate-100">
                                <h4 class="font-bold text-elevate-dark text-sm mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-book-open text-elevate-primary"></i> Tilawah & Murojaah
                                </h4>
                                <div class="space-y-3">
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Tadarus</p>
                                        <p class="text-sm font-bold text-elevate-dark">
                                            <span x-text="details.tadarus_surah || '-'"></span> 
                                            <span x-show="details.tadarus_ayah" class="text-elevate-primary ml-1">Ayat <span x-text="details.tadarus_ayah"></span></span>
                                        </p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Murojaah</p>
                                        <p class="text-sm font-bold text-elevate-dark" x-text="details.murojaah || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="space-y-5 md:space-y-6">
                            
                            <div class="bg-white p-6 rounded-2xl fluent-card border border-slate-100 h-full">
                                <h4 class="font-bold text-elevate-dark text-sm mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-star text-amber-600"></i> Amalan Sunnah
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="s in ['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah']">
                                        <div class="flex items-center justify-between p-3.5 rounded-xl border shadow-sm transition-all"
                                            :class="details.sunnahs[s] ? 'bg-elevate-soft border-elevate-accent/30' : 'bg-slate-50 border-slate-200 opacity-70'">
                                            <span class="text-xs font-bold text-elevate-dark capitalize" x-text="s"></span>
                                            <i class="ph-fill text-xl" :class="details.sunnahs[s] ? 'ph-check-circle text-elevate-primary' : 'ph-circle text-slate-300'"></i>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <template x-if="details.khotib">
                        <div class="bg-white p-6 rounded-2xl border border-elevate-accent/30 fluent-card">
                            <h4 class="font-bold text-elevate-primary text-sm mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-mosque text-elevate-primary"></i> Laporan Jumat
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Khotib</p>
                                    <p class="text-sm font-bold text-elevate-dark bg-slate-50 p-3 rounded-xl border border-slate-200" x-text="details.khotib"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Ringkasan</p>
                                    <p class="text-sm text-elevate-dark font-medium bg-slate-50 p-3 rounded-xl border border-slate-200 leading-relaxed" x-text="details.summary"></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    
                    <template x-if="details.kultum_summary || details.kultum_penceramah">
                        <div class="bg-white p-6 rounded-2xl border border-elevate-peach/30 fluent-card">
                            <h4 class="font-bold text-elevate-peach-dark text-sm mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-microphone-stage text-elevate-peach-dark"></i> Laporan Kultum
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Penceramah</p>
                                    <p class="text-sm font-bold text-elevate-dark bg-slate-50 p-3 rounded-xl border border-slate-200" x-text="details.kultum_penceramah || '-'"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Ringkasan Materi</p>
                                    <p class="text-sm text-elevate-dark font-medium bg-slate-50 p-3 rounded-xl border border-slate-200 leading-relaxed" x-text="details.kultum_summary || '-'"></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    
                    <form :action="formAction" method="POST" id="gradingForm" class="space-y-5 pt-6 border-t border-slate-200">
                        <?php echo csrf_field(); ?>
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/3">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block">Nilai (0-100)</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="teacher_score" x-model="currentScore" min="0" max="100" class="w-full pl-4 pr-2 py-3.5 rounded-xl border-slate-200 font-black focus:ring-elevate-primary focus:border-elevate-primary text-2xl text-elevate-primary shadow-sm text-center bg-white" placeholder="0">
                                    <div class="flex flex-col gap-1.5 shrink-0">
                                        <button type="button" @click="setScore(100)" class="px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold hover:bg-emerald-100 transition-colors shadow-sm">100</button>
                                        <button type="button" @click="setScore(80)" class="px-3 py-1.5 rounded-lg bg-slate-100 border border-slate-200 text-elevate-dark text-[10px] font-bold hover:bg-slate-200 transition-colors shadow-sm">80</button>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['teacher_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-rose-600 text-[10px] mt-1.5 font-bold"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="w-full md:w-2/3">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block">Catatan / Motivasi Guru</label>
                                <textarea name="teacher_note" x-model="currentNote" rows="2" class="w-full p-4 rounded-xl border-slate-200 text-sm font-medium text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary leading-relaxed shadow-sm placeholder:text-slate-400 placeholder:font-normal resize-none bg-white" placeholder="Berikan ucapan penyemangat..."></textarea>
                                <?php $__errorArgs = ['teacher_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-rose-600 text-[10px] mt-1.5 font-bold"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="p-6 border-t border-slate-200 bg-white flex flex-col-reverse md:flex-row justify-end gap-3 shrink-0">
                    <button @click="closeModal()" class="w-full md:w-auto px-6 py-3 rounded-lg font-bold text-slate-600 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition text-xs uppercase tracking-widest shadow-sm">Batal</button>
                    <button type="submit" form="gradingForm" class="w-full md:w-auto px-8 py-3 rounded-lg font-bold text-white bg-elevate-primary hover:bg-elevate-dark transition text-xs uppercase tracking-widest shadow-md flex items-center justify-center gap-2 transform active:scale-95">
                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Penilaian
                    </button>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ramadan/admin_report.blade.php ENDPATH**/ ?>