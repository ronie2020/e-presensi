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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <div class="py-8 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 md:p-10 mb-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/20 overflow-hidden border border-white/60">
                
                
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/60 backdrop-blur-md text-xs font-bold uppercase tracking-widest text-[#0d52a1] border border-white/50 mb-4 shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher text-lg"></i> Dashboard Wali Kelas
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 text-[#0d52a1]">Kelas <?php echo e($class->name); ?></h1>
                        <p class="font-medium text-sm md:text-base max-w-2xl text-[#2c3f61]/80">Pantau statistik kedisiplinan, literasi, pembiasaan, dan kehadiran anak didik Anda secara komprehensif.</p>
                    </div>
                                        
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        
                        <form action="<?php echo e(route('homeroom.dashboard')); ?>" method="GET" class="relative w-full sm:w-auto">
                            <?php if(request('class_id')): ?>
                                <input type="hidden" name="class_id" value="<?php echo e(request('class_id')); ?>">
                            <?php endif; ?>
                            <select name="period" onchange="this.form.submit()" class="w-full pl-5 pr-12 py-3 bg-white/60 hover:bg-white/80 backdrop-blur-md text-[#0d52a1] border border-white/50 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-[#56bbf1] cursor-pointer font-bold shadow-sm transition-all">
                                <option value="this_month" <?php echo e(request('period', 'this_month') == 'this_month' ? 'selected' : ''); ?> class="text-slate-800 font-semibold">Bulan Ini</option>
                                <option value="last_month" <?php echo e(request('period') == 'last_month' ? 'selected' : ''); ?> class="text-slate-800 font-semibold">Bulan Lalu</option>
                                <option value="semester_1" <?php echo e(request('period') == 'semester_1' ? 'selected' : ''); ?> class="text-slate-800 font-semibold">Semester Ganjil</option>
                                <option value="semester_2" <?php echo e(request('period') == 'semester_2' ? 'selected' : ''); ?> class="text-slate-800 font-semibold">Semester Genap</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#0d52a1]">
                                <i class="ph-bold ph-calendar-blank text-lg"></i>
                            </div>
                        </form>

                         <?php if(isset($isAdminOrKepsek) && $isAdminOrKepsek && isset($allClasses)): ?>
                            <form action="<?php echo e(route('homeroom.dashboard')); ?>" method="GET" class="relative w-full sm:w-auto">
                                <?php if(request('period')): ?>
                                    <input type="hidden" name="period" value="<?php echo e(request('period')); ?>">
                                <?php endif; ?>
                                <select name="class_id" onchange="this.form.submit()" class="w-full pl-5 pr-12 py-3 bg-white/60 hover:bg-white/80 backdrop-blur-md text-[#0d52a1] border border-white/50 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-[#56bbf1] cursor-pointer font-bold shadow-sm transition-all">
                                    <?php $__currentLoopData = $allClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c->id); ?>" <?php echo e($class->id == $c->id ? 'selected' : ''); ?> class="text-slate-800 font-semibold">
                                            Pantau Kelas <?php echo e($c->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#0d52a1]">
                                    <i class="ph-bold ph-caret-down text-lg"></i>
                                </div>
                            </form>
                        <?php endif; ?>

                        <a href="<?php echo e(route('homeroom.print', ['class_id' => $class->id, 'period' => request('period', 'this_month')])); ?>" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-[#0d52a1] text-white font-bold rounded-xl hover:bg-[#0a4282] transition-all shadow-lg shadow-[#0d52a1]/20 flex items-center justify-center gap-2 group active:scale-95 border border-[#0d52a1]/20">
                            <i class="ph-bold ph-printer group-hover:scale-110 transition-transform"></i> Rekap PDF
                        </a>
                    </div>
                </div>
            </div>

             
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-blue-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="ph-fill ph-users-three"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                        <p class="text-xl font-black text-slate-800"><?php echo e($stats['total_students']); ?></p>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-emerald-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl"><i class="ph-fill ph-star"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Poin Karakter</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-emerald-600">+<?php echo e($stats['total_merits']); ?></p>
                            <?php if(isset($trends['merits'])): ?>
                                <?php $isUp = $trends['merits'] >= 0; ?>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border <?php echo e($isUp ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'); ?>" title="<?php echo e($isUp ? 'Naik' : 'Turun'); ?> dari periode sebelumnya">
                                    <i class="ph-bold <?php echo e($isUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right'); ?> mr-0.5"></i> <?php echo e(abs($trends['merits'])); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-purple-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl"><i class="ph-fill ph-books"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Literasi</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-purple-600"><?php echo e($stats['total_literacy']); ?></p>
                            <?php if(isset($trends['literacy'])): ?>
                                <?php $isUp = $trends['literacy'] >= 0; ?>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border <?php echo e($isUp ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'); ?>" title="<?php echo e($isUp ? 'Naik' : 'Turun'); ?> dari periode sebelumnya">
                                    <i class="ph-bold <?php echo e($isUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right'); ?> mr-0.5"></i> <?php echo e(abs($trends['literacy'])); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-teal-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl"><i class="ph-fill ph-list-checks"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jurnal Habit</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-teal-600"><?php echo e($stats['total_habits']); ?></p>
                            <?php if(isset($trends['habits'])): ?>
                                <?php $isUp = $trends['habits'] >= 0; ?>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border <?php echo e($isUp ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'); ?>" title="<?php echo e($isUp ? 'Naik' : 'Turun'); ?> dari periode sebelumnya">
                                    <i class="ph-bold <?php echo e($isUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right'); ?> mr-0.5"></i> <?php echo e(abs($trends['habits'])); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-amber-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl"><i class="ph-fill ph-calendar-x"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Alpa</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-amber-600"><?php echo e($stats['alfa_count']); ?></p>
                            <?php if(isset($trends['alfa'])): ?>
                                
                                <?php $isGood = $trends['alfa'] <= 0; ?>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border <?php echo e($isGood ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'); ?>" title="<?php echo e($isGood ? 'Menurun (Meningkatnya Kehadiran)' : 'Meningkat (Banyak Alpa)'); ?>">
                                    <i class="ph-bold <?php echo e($isGood ? 'ph-arrow-down-right' : 'ph-arrow-up-right'); ?> mr-0.5"></i> <?php echo e(abs($trends['alfa'])); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-rose-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl"><i class="ph-fill ph-warning-circle"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelanggaran</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-rose-600">-<?php echo e($stats['total_violations']); ?></p>
                            <?php if(isset($trends['violations'])): ?>
                                
                                <?php $isGood = $trends['violations'] <= 0; ?>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border <?php echo e($isGood ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'); ?>" title="<?php echo e($isGood ? 'Menurun (Lebih Disiplin)' : 'Meningkat (Banyak Pelanggaran)'); ?>">
                                    <i class="ph-bold <?php echo e($isGood ? 'ph-arrow-down-right' : 'ph-arrow-up-right'); ?> mr-0.5"></i> <?php echo e(abs($trends['violations'])); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-rose-100 overflow-hidden h-full relative">
                        <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                            <i class="ph-fill ph-siren text-9xl text-rose-500"></i>
                        </div>
                        
                        <div class="p-6 border-b border-rose-50 bg-rose-50/50 flex justify-between items-center relative z-10">
                            <h3 class="font-black text-rose-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-siren text-rose-500 animate-pulse"></i> Perlu Perhatian Khusus
                            </h3>
                            <span class="bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1 rounded-full"><?php echo e($warningStudents->count()); ?> Terdeteksi</span>
                        </div>
                        
                        <div class="p-6 relative z-10">
                            <?php if($warningStudents->count() > 0): ?>
                                <div class="space-y-4">
                                    <?php $__currentLoopData = $warningStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ws): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-rose-200 hover:bg-rose-50/30 transition-colors bg-white shadow-sm">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-full bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                                    <?php if($ws->photo): ?>
                                                        <img src="<?php echo e(asset('storage/' . $ws->photo)); ?>" alt="Foto profil <?php echo e($ws->name); ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="w-full h-full flex items-center justify-center font-bold text-slate-400"><?php echo e(substr($ws->name, 0, 1)); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800"><?php echo e($ws->name); ?></h4>
                                                    <div class="flex flex-wrap gap-2 mt-1.5">
                                                        <?php if($ws->violation_points >= 50): ?>
                                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-100 text-rose-700 border border-rose-200"><i class="ph-bold ph-minus"></i> <?php echo e($ws->violation_points); ?> Poin</span>
                                                        <?php endif; ?>
                                                        <?php if($ws->alfa_count >= 3): ?>
                                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700 border border-amber-200"><i class="ph-bold ph-calendar-x"></i> <?php echo e($ws->alfa_count); ?>x Alpa</span>
                                                        <?php endif; ?>
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200"><?php echo e($ws->issue); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="<?php echo e(route('students.show', $ws->id)); ?>" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all shrink-0" title="Buku Induk & Detail">
                                                <i class="ph-bold ph-arrow-right"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-50 rounded-full mb-4 ring-8 ring-emerald-50/50">
                                        <i class="ph-fill ph-check-circle text-5xl text-emerald-500"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-lg">Aman Terkendali!</p>
                                    <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1">Sistem tidak mendeteksi adanya siswa dengan tingkat pelanggaran tinggi atau absensi kritis di kelas Anda.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-crown text-amber-500"></i> Bintang Karakter
                            </h3>
                        </div>
                        <div class="p-6">
                            <?php if($topStudents->count() > 0): ?>
                                <div class="space-y-4">
                                    <?php $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-3 group">
                                            <div class="w-6 font-black <?php echo e($index == 0 ? 'text-amber-500 text-xl' : ($index == 1 ? 'text-slate-400 text-lg' : ($index == 2 ? 'text-amber-700 text-lg' : 'text-slate-300'))); ?> text-center">
                                                <?php echo e($index + 1); ?>

                                            </div>
                                            <div class="flex-1 flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-white group-hover:border-emerald-200 transition-colors shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                                        <?php if($ts->photo): ?>
                                                            <img src="<?php echo e(asset('storage/' . $ts->photo)); ?>" class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <div class="w-full h-full flex items-center justify-center font-bold text-slate-400 text-xs"><?php echo e(substr($ts->name, 0, 1)); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h4 class="font-bold text-sm text-slate-700 truncate max-w-[110px]" title="<?php echo e($ts->name); ?>"><?php echo e($ts->name); ?></h4>
                                                </div>
                                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-100 px-2 py-1.5 rounded-lg border border-emerald-200 shadow-sm">+<?php echo e($ts->merit_points); ?> Poin</span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-10">
                                    <i class="ph-duotone ph-star text-4xl text-slate-300 mb-2 block"></i>
                                    <p class="text-sm font-bold text-slate-500">Belum ada data prestasi tercatat bulan ini.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-purple-100 overflow-hidden">
                    <div class="p-6 border-b border-purple-50 bg-purple-50/50 flex justify-between items-center">
                        <h3 class="font-black text-purple-800 text-lg flex items-center gap-2">
                            <i class="ph-fill ph-books text-purple-500"></i> Duta Literasi Kelas (Top 5)
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if($topLiteracy->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $topLiteracy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-purple-200 transition-colors shadow-sm">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 font-black text-slate-300 text-center"><?php echo e($index + 1); ?></div>
                                            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                                <?php if($tl->photo): ?>
                                                    <img src="<?php echo e(asset('storage/' . $tl->photo)); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="w-full h-full flex items-center justify-center font-bold text-slate-400"><?php echo e(substr($tl->name, 0, 1)); ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <h4 class="font-bold text-slate-700"><?php echo e($tl->name); ?></h4>
                                        </div>
                                        <span class="text-[10px] font-black text-purple-700 bg-purple-100 px-3 py-1.5 rounded-xl border border-purple-200"><?php echo e($tl->count); ?> Buku</span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="ph-duotone ph-book-open-text text-4xl text-slate-300 mb-2 block"></i>
                                <p class="text-sm font-bold text-slate-500">Belum ada siswa yang mengisi Jurnal Literasi bulan ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-teal-100 overflow-hidden">
                    <div class="p-6 border-b border-teal-50 bg-teal-50/50 flex justify-between items-center">
                        <h3 class="font-black text-teal-800 text-lg flex items-center gap-2">
                            <i class="ph-fill ph-list-checks text-teal-500"></i> Terajin Lapor Pembiasaan
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if($topHabits->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $topHabits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-teal-200 transition-colors shadow-sm">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 font-black text-slate-300 text-center"><?php echo e($index + 1); ?></div>
                                            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                                <?php if($th->photo): ?>
                                                    <img src="<?php echo e(asset('storage/' . $th->photo)); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="w-full h-full flex items-center justify-center font-bold text-slate-400"><?php echo e(substr($th->name, 0, 1)); ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <h4 class="font-bold text-slate-700"><?php echo e($th->name); ?></h4>
                                        </div>
                                        <span class="text-[10px] font-black text-teal-700 bg-teal-100 px-3 py-1.5 rounded-xl border border-teal-200"><?php echo e($th->count); ?> Hari</span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="ph-duotone ph-mosque text-4xl text-slate-300 mb-2 block"></i>
                                <p class="text-sm font-bold text-slate-500">Belum ada siswa yang mengisi Jurnal Pembiasaan Keagamaan bulan ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            
            
            
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-[2.5rem] shadow-lg border border-amber-200 overflow-hidden relative group">
                <!-- Ornamen Latar -->
                <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none group-hover:scale-110 transition-transform duration-700">
                    <i class="ph-fill ph-trophy text-9xl text-amber-500"></i>
                </div>
                
                <div class="p-8 border-b border-amber-200/50 bg-white/50 backdrop-blur-sm relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="font-black text-amber-900 text-2xl flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-lg">
                                    <i class="ph-fill ph-trophy text-xl"></i>
                                </div>
                                Nominasi Siswa Teladan (Top 10)
                            </h2>
                            <p class="text-amber-700/80 text-sm mt-2 font-medium">Sistem merekomendasikan 10 siswa berdasarkan perhitungan gabungan: <strong>Kelengkapan Tugas (Habit & Literasi), Sikap Positif, Tingkat Kehadiran, dan Akademik</strong>.</p>
                        </div>
                        <div class="shrink-0 bg-white px-4 py-2 rounded-xl border border-amber-200 shadow-sm text-xs font-bold text-amber-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Smart Evaluation System
                        </div>
                    </div>
                </div>

                <div class="p-8 relative z-10">
                    <?php if(isset($awardNominees) && $awardNominees->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $awardNominees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nominee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-amber-100 shadow-sm hover:shadow-md hover:border-amber-300 transition-all">
                                    
                                    
                                    <div class="shrink-0 w-12 h-12 rounded-full <?php echo e($index == 0 ? 'bg-gradient-to-br from-yellow-300 to-yellow-500 text-yellow-900 ring-4 ring-yellow-100' : ($index == 1 ? 'bg-gradient-to-br from-slate-200 to-slate-400 text-slate-800' : ($index == 2 ? 'bg-gradient-to-br from-orange-300 to-orange-500 text-orange-950' : 'bg-amber-100 text-amber-700'))); ?> flex items-center justify-center font-black text-xl shadow-inner">
                                        #<?php echo e($index + 1); ?>

                                    </div>
                                    
                                    
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-slate-800 truncate text-lg"><?php echo e($nominee->name); ?></h4>
                                        
                                        <div class="flex flex-wrap gap-2 mt-1.5">
                                            
                                            <?php if($nominee->alfa_count == 0 && $nominee->violation_points == 0): ?>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center gap-1" title="Tidak pernah Alpa dan tidak ada Pelanggaran">
                                                    <i class="ph-bold ph-shield-check"></i> Catatan Sempurna
                                                </span>
                                            <?php else: ?>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700 border border-blue-200 flex items-center gap-1" title="Sikap Bersih = Poin Prestasi - Poin Pelanggaran">
                                                    <i class="ph-bold ph-star"></i> Sikap: +<?php echo e($nominee->net_discipline); ?> Pts
                                                </span>
                                            <?php endif; ?>
                                            
                                            
                                            <?php if($nominee->task_score > 0): ?>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-100 text-purple-700 border border-purple-200 flex items-center gap-1" title="Total akumulasi poin dari Habit & Literasi">
                                                    <i class="ph-bold ph-check-square-offset"></i> Tugas: <?php echo e($nominee->task_score); ?> Pts
                                                </span>
                                            <?php endif; ?>

                                            
                                            <?php if($nominee->academic_score > 0): ?>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 flex items-center gap-1">
                                                    <i class="ph-bold ph-graduation-cap"></i> Akad: <?php echo e($nominee->academic_score); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="shrink-0 text-right">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5" title="Gabungan Skor Kehadiran, Sikap, Tugas, dan Akademik">Total Skor</p>
                                        <p class="text-2xl font-black text-amber-600"><?php echo e(number_format($nominee->total_score, 0)); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10 bg-white/50 rounded-2xl border border-amber-200/50 border-dashed">
                            <i class="ph-duotone ph-magnifying-glass text-5xl text-amber-300 mb-3 block"></i>
                            <p class="font-bold text-amber-800 text-lg">Belum Ada Kandidat Valid</p>
                            <p class="text-sm text-amber-700/70 max-w-md mx-auto mt-1">Sistem belum menemukan siswa dengan nilai dan partisipasi yang cukup untuk direkomendasikan.</p>
                        </div>
                    <?php endif; ?>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/homeroom/dashboard.blade.php ENDPATH**/ ?>