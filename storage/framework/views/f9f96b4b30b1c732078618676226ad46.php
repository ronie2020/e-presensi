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
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-gear"></i> Konfigurasi Sistem
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Manajemen Jadwal
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pusat pengaturan jam operasional sekolah (reguler) dan kalender akademik untuk kegiatan khusus.
                        </p>
                    </div>
                    
                    
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white/15 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                <i class="ph-duotone ph-calendar-plus text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Agenda Khusus</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight"><?php echo e($specialSchedules->count()); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[2rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-2 rounded-xl hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0 ml-2">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Periksa inputan anda:</p>
                        <ul class="list-disc list-inside text-xs font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- BAGIAN 1: JADWAL REGULER (Full Width Card) -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden mb-10">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                        <i class="ph-duotone ph-clock"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Jadwal Sekolah Reguler</h2>
                        <p class="text-sm text-slate-500 font-medium">Pengaturan jam masuk dan pulang mingguan.</p>
                    </div>
                </div>

                <form action="<?php echo e(route('schedules.regular.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Jadwal Hari Biasa (Senin-Kamis) -->
                        <div class="bg-slate-50/80 rounded-[2rem] p-6 border border-slate-200 relative group hover:bg-blue-50/50 hover:border-blue-200 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-black text-slate-700 flex items-center gap-2 text-lg">
                                    <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                                    Senin - Kamis
                                </h4>
                                <span class="text-[10px] font-black bg-white text-blue-600 px-3 py-1.5 rounded-xl uppercase tracking-wider border border-slate-200 shadow-sm">Hari Biasa</span>
                            </div>
                            <input type="hidden" name="day_type[]" value="Biasa">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                
                                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-200 shadow-sm group-hover:border-blue-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-3 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-sun-horizon text-amber-500"></i> Jam Masuk
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_in[]" 
                                            value="<?php echo e(isset($regularSchedules['Biasa']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_in)->format('H:i') : '07:00'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-3 transition-all">
                                        <span class="text-slate-300 font-bold text-sm">-</span>
                                        <input type="time" name="end_in[]" 
                                            value="<?php echo e(isset($regularSchedules['Biasa']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_in)->format('H:i') : '07:30'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-3 transition-all">
                                    </div>
                                </div>

                                
                                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-200 shadow-sm group-hover:border-blue-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-3 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-moon-stars text-indigo-500"></i> Jam Pulang
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_out[]" 
                                            value="<?php echo e(isset($regularSchedules['Biasa']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_out)->format('H:i') : '14:00'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-3 transition-all">
                                        <span class="text-slate-300 font-bold text-sm">-</span>
                                        <input type="time" name="end_out[]" 
                                            value="<?php echo e(isset($regularSchedules['Biasa']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_out)->format('H:i') : '16:00'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-3 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Jadwal Hari Jum'at -->
                        <div class="bg-slate-50/80 rounded-[2rem] p-6 border border-slate-200 relative group hover:bg-purple-50/50 hover:border-purple-200 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-black text-slate-700 flex items-center gap-2 text-lg">
                                    <span class="w-2 h-8 bg-purple-600 rounded-full"></span>
                                    Hari Jum'at
                                </h4>
                                <span class="text-[10px] font-black bg-white text-purple-600 px-3 py-1.5 rounded-xl uppercase tracking-wide border border-slate-200 shadow-sm">Khusus</span>
                            </div>
                            <input type="hidden" name="day_type[]" value="Jumat">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                
                                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-200 shadow-sm group-hover:border-purple-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-3 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-sun-horizon text-amber-500"></i> Jam Masuk
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_in[]" 
                                            value="<?php echo e(isset($regularSchedules['Jumat']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_in)->format('H:i') : '07:00'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-3 transition-all">
                                        <span class="text-slate-300 font-bold text-sm">-</span>
                                        <input type="time" name="end_in[]" 
                                            value="<?php echo e(isset($regularSchedules['Jumat']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_in)->format('H:i') : '07:30'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-3 transition-all">
                                    </div>
                                </div>

                                
                                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-200 shadow-sm group-hover:border-purple-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-3 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-moon-stars text-indigo-500"></i> Jam Pulang
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_out[]" 
                                            value="<?php echo e(isset($regularSchedules['Jumat']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_out)->format('H:i') : '11:00'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-3 transition-all">
                                        <span class="text-slate-300 font-bold text-sm">-</span>
                                        <input type="time" name="end_out[]" 
                                            value="<?php echo e(isset($regularSchedules['Jumat']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_out)->format('H:i') : '12:00'); ?>" 
                                            class="flex-1 min-w-0 text-center font-black text-lg text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-3 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end border-t border-slate-100 pt-6">
                        <button type="submit" class="w-full sm:w-auto py-3.5 px-8 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                            <i class="ph-bold ph-floppy-disk text-xl"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- BAGIAN 2: JADWAL KHUSUS (GRID 1:3) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- KOLOM KIRI: FORM INPUT KHUSUS (Style 'Akses Cepat' Dashboard) -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden" x-data="{ isHoliday: false }">
                        
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-500 to-rose-500"></div>

                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-orange-100">
                                <i class="ph-duotone ph-calendar-plus"></i>
                            </div>
                            <h3 class="text-lg font-black text-slate-800">Agenda Baru</h3>
                        </div>

                        <form action="<?php echo e(route('schedules.special.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                        <input type="date" name="date" required 
                                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 transition-all shadow-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Keterangan</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                        <input type="text" name="description" placeholder="Contoh: Ujian Nasional" required
                                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 transition-all shadow-sm placeholder:font-normal">
                                    </div>
                                </div>
                                
                                <!-- Toggle Hari Libur Modern -->
                                <div class="bg-rose-50 p-3 rounded-2xl border border-rose-100 flex items-center gap-4 cursor-pointer hover:bg-rose-100 transition-colors select-none" @click="isHoliday = !isHoliday">
                                    <div class="relative flex items-center ml-1">
                                        <input type="checkbox" name="is_holiday" value="1" class="peer sr-only" x-model="isHoliday">
                                        <div class="w-10 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500 shadow-inner"></div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-xs font-black text-rose-800">Set Hari Libur</span>
                                    </div>
                                    <i class="ph-duotone ph-coffee text-rose-400 text-xl mr-1"></i>
                                </div>

                                <!-- Input Jam -->
                                <div x-show="!isHoliday" 
                                     x-transition:enter="transition ease-out duration-300" 
                                     x-transition:enter-start="opacity-0 -translate-y-2" 
                                     x-transition:enter-end="opacity-100 translate-y-0" 
                                     class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <p class="text-[10px] font-black text-slate-400 uppercase text-center mb-1 border-b border-slate-200 pb-2">Jam Operasional</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-[10px] text-slate-500 font-bold text-center block mb-1">Masuk</label>
                                            <div class="flex gap-1">
                                                 <input type="time" name="start_in" class="w-full text-[10px] text-center font-bold rounded-lg border-slate-200 bg-white px-0.5 py-2 focus:ring-blue-500 focus:border-blue-500">
                                                 <input type="time" name="end_in" class="w-full text-[10px] text-center font-bold rounded-lg border-slate-200 bg-white px-0.5 py-2 focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-slate-500 font-bold text-center block mb-1">Pulang</label>
                                            <div class="flex gap-1">
                                                <input type="time" name="start_out" class="w-full text-[10px] text-center font-bold rounded-lg border-slate-200 bg-white px-0.5 py-2 focus:ring-blue-500 focus:border-blue-500">
                                                <input type="time" name="end_out" class="w-full text-[10px] text-center font-bold rounded-lg border-slate-200 bg-white px-0.5 py-2 focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="mt-8 w-full py-3.5 px-6 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-plus-circle text-lg"></i>
                                Tambah Jadwal
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- KOLOM KANAN: DAFTAR JADWAL (Style 'Log Aktivitas' Dashboard) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col h-full min-h-[500px] relative">
                        
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-blue-900"></i> Riwayat Agenda
                            </h3>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1 rounded-full border border-blue-100 shadow-sm">
                                <?php echo e($specialSchedules->count()); ?> Data
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider sticky top-0">
                                    <tr>
                                        <th class="px-6 py-5">Tanggal</th>
                                        <th class="px-6 py-5 w-1/3">Keterangan</th>
                                        <th class="px-6 py-5 text-center">Status</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $specialSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-lg shadow-sm border border-slate-200">
                                                        <i class="ph-duotone ph-calendar-blank"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-slate-800 text-sm"><?php echo e(\Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y')); ?></p>
                                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wide"><?php echo e(\Carbon\Carbon::parse($schedule->date)->translatedFormat('l')); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <p class="text-sm font-bold text-slate-700 leading-snug"><?php echo e($schedule->description); ?></p>
                                                <?php if(!$schedule->is_holiday): ?>
                                                    <div class="inline-flex items-center gap-1.5 mt-1.5 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">
                                                        <i class="ph-bold ph-clock text-blue-400 text-xs"></i>
                                                        <span class="text-[10px] font-mono font-bold text-blue-600">
                                                            <?php echo e(\Carbon\Carbon::parse($schedule->start_in)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($schedule->end_out)->format('H:i')); ?>

                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <?php if($schedule->is_holiday): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase bg-rose-50 text-rose-600 border border-rose-100 shadow-sm">
                                                        <i class="ph-bold ph-coffee"></i> Libur
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm">
                                                        <i class="ph-bold ph-info"></i> Khusus
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-5 text-right">
                                                <form action="<?php echo e(route('schedules.special.destroy', $schedule->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm group-hover:border-slate-300" title="Hapus Jadwal">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-20 text-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-inner">
                                                    <i class="ph-duotone ph-calendar-slash text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-600">Tidak ada jadwal khusus.</p>
                                                <p class="text-xs text-slate-400 mt-1">Tambahkan hari libur atau kegiatan di formulir kiri.</p>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\schedules\index.blade.php ENDPATH**/ ?>