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
    
    <div x-data="{ activeTab: 'mapel' }" class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-purple-500/30 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-calendar-plus"></i> Akademik & KBM
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Atur Jadwal
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola jadwal pelajaran (KBM) per kelas dengan format Jam Pelajaran (JP).
                        </p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-md p-1.5 rounded-2xl border border-white/10 flex flex-col sm:flex-row gap-1">
                        <button @click="activeTab = 'mapel'" 
                            :class="activeTab === 'mapel' ? 'bg-white text-blue-900 shadow-lg' : 'text-blue-100 hover:bg-white/10'"
                            class="px-6 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-book-open"></i> Jadwal Mapel
                        </button>
                        <button @click="activeTab = 'jam'" 
                            :class="activeTab === 'jam' ? 'bg-white text-blue-900 shadow-lg' : 'text-blue-100 hover:bg-white/10'"
                            class="px-6 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-clock"></i> Jam Sekolah
                        </button>
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
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-center gap-3 shadow-sm relative">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0 ml-2">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Terjadi kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <button @click="show = false" class="absolute top-4 right-4 text-rose-400 hover:text-rose-600"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            
            <div x-show="activeTab === 'mapel'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    
                    <div class="lg:col-span-1">
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden sticky top-24">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                            
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-blue-100">
                                    <i class="ph-duotone ph-plus-square"></i>
                                </div>
                                <h2 class="text-lg font-black text-slate-800">Tambah Jadwal</h2>
                            </div>

                            <form action="<?php echo e(route('schedules.store')); ?>" method="POST" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelas</label>
                                    <div class="relative">
                                        <select name="school_class_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-300 bg-rose-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value="">-- Pilih Kelas --</option>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($c->id); ?>" <?php echo e(old('school_class_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[10px] text-rose-500 font-bold ml-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran</label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">-- Pilih Mapel --</option>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($s->id); ?>" <?php echo e(old('subject_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Guru Pengampu</label>
                                    <div class="relative">
                                        <select name="teacher_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-300 bg-rose-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value="">-- Pilih Guru --</option>
                                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($t->id); ?>" <?php echo e(old('teacher_id') == $t->id ? 'selected' : ''); ?>><?php echo e($t->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[10px] text-rose-500 font-bold ml-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Hari</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="day" value="<?php echo e($day); ?>" class="peer sr-only" <?php echo e(old('day') == $day ? 'checked' : ''); ?> required>
                                                <div class="text-center py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-md transition-all hover:bg-slate-100">
                                                    <?php echo e(substr($day, 0, 3)); ?>

                                                </div>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mulai Jam Ke-</label>
                                        <div class="relative">
                                            <select name="start_time" required class="w-full text-center py-3 rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 appearance-none cursor-pointer">
                                                <?php for($i = 1; $i <= 12; $i++): ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e(old('start_time') == $i ? 'selected' : ''); ?>>Jam <?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Selesai Jam Ke-</label>
                                        <div class="relative">
                                            <select name="end_time" required class="w-full text-center py-3 rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 appearance-none cursor-pointer">
                                                <?php for($i = 1; $i <= 12; $i++): ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e(old('end_time') == $i ? 'selected' : ''); ?>>Jam <?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full mt-4 py-3.5 px-6 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Jadwal
                                </button>
                            </form>
                        </div>
                    </div>

                    
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col relative min-h-[600px]">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                            <div class="p-8 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-indigo-100">
                                        <i class="ph-duotone ph-list-dashes"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black text-slate-800">Daftar Jadwal</h3>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total <?php echo e($schedules->count()); ?> Sesi</p>
                                    </div>
                                </div>
                                
                                <form method="GET" class="w-full sm:w-auto">
                                    <div class="relative">
                                        <select name="class_id" onchange="this.form.submit()" class="w-full sm:w-48 pl-4 pr-10 py-2.5 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">Semua Kelas</option>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($c->id); ?>" <?php echo e(request('class_id') == $c->id ? 'selected' : ''); ?>>Kelas <?php echo e($c->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-funnel absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="overflow-x-auto flex-1 custom-scrollbar">
                                <table class="min-w-full text-left text-sm text-slate-600">
                                    <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider sticky top-0 z-10">
                                        <tr>
                                            <th class="px-6 py-5">Hari & Jam Pelajaran</th>
                                            <th class="px-6 py-5">Kelas</th>
                                            <th class="px-6 py-5">Mata Pelajaran</th>
                                            <th class="px-6 py-5">Guru</th>
                                            <th class="px-6 py-5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-slate-800 text-sm mb-1"><?php echo e($item->day); ?></span>
                                                    
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-mono font-bold border border-blue-100 w-fit">
                                                        <i class="ph-bold ph-clock"></i>
                                                        JP <?php echo e($item->clean_start_time); ?> - <?php echo e($item->clean_end_time); ?>

                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                                    <?php echo e($item->schoolClass->name); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="font-bold text-slate-700"><?php echo e($item->subject->name); ?></div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                        <?php echo e(substr($item->teacher->name, 0, 1)); ?>

                                                    </div>
                                                    <span class="font-bold text-slate-600 text-xs"><?php echo e($item->teacher->name); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                                <div class="flex justify-end items-center">
                                                    <form action="<?php echo e(route('schedules.destroy', $item->id)); ?>" 
                                                          method="POST" 
                                                          id="delete-schedule-<?php echo e($item->id); ?>"
                                                          class="shrink-0 block">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        
                                                        <button type="button" 
                                                                onclick="confirmDelete('delete-schedule-<?php echo e($item->id); ?>', 'Hapus jadwal mapel <?php echo e($item->subject->name); ?> di kelas <?php echo e($item->schoolClass->name); ?>?')"
                                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm">
                                                            <i class="ph-bold ph-trash text-lg leading-none"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="px-6 py-20 text-center text-slate-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300 shadow-inner">
                                                        <i class="ph-duotone ph-calendar-slash text-4xl"></i>
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-500">Belum ada jadwal pelajaran.</p>
                                                    <p class="text-xs text-slate-400 mt-1">Gunakan formulir di kiri untuk menambah.</p>
                                                </div>
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

            
            <div x-show="activeTab === 'jam'" x-cloak x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    
                    
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-500 to-fuchsia-600"></div>
                        
                        <div class="p-8 border-b border-slate-50 flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-purple-100">
                                <i class="ph-duotone ph-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Jam Sekolah Reguler</h3>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Setting Bel Masuk & Pulang</p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('schedules.regular.store')); ?>" method="POST" class="p-8 space-y-8">
                            <?php echo csrf_field(); ?>
                            
                            
                            <div class="bg-slate-50/80 p-6 rounded-[2rem] border border-slate-200 relative group hover:border-blue-200 transition-colors">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-black text-slate-700 flex items-center gap-2">
                                        <i class="ph-fill ph-sun text-amber-500"></i> Senin - Kamis
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Biasa">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-2 text-center">Masuk</label>
                                        <div class="flex gap-1.5">
                                            
                                            <input type="time" name="start_in[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->start_in ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->start_in)->format('H:i') : ''); ?>" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                            <input type="time" name="end_in[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->end_in ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->end_in)->format('H:i') : ''); ?>" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-2 text-center">Pulang</label>
                                        <div class="flex gap-1.5">
                                            <input type="time" name="start_out[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->start_out ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->start_out)->format('H:i') : ''); ?>" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                            <input type="time" name="end_out[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->end_out ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->end_out)->format('H:i') : ''); ?>" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-purple-50/50 p-6 rounded-[2rem] border border-purple-100 relative group hover:border-purple-200 transition-colors">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-black text-purple-900 flex items-center gap-2">
                                        <i class="ph-fill ph-moon-stars text-purple-500"></i> Hari Jum'at
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Jumat">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-purple-400 mb-2 text-center">Masuk</label>
                                        <div class="flex gap-1.5">
                                            
                                            <input type="time" name="start_in[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->start_in ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->start_in)->format('H:i') : ''); ?>" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                            <input type="time" name="end_in[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->end_in ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->end_in)->format('H:i') : ''); ?>" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-purple-400 mb-2 text-center">Pulang</label>
                                        <div class="flex gap-1.5">
                                            <input type="time" name="start_out[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->start_out ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->start_out)->format('H:i') : ''); ?>" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                            <input type="time" name="end_out[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->end_out ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->end_out)->format('H:i') : ''); ?>" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-6 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Jam Reguler
                            </button>
                        </form>
                    </div>

                    
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative" x-data="{ isHoliday: false }">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-500 to-rose-500"></div>
                        
                        <div class="p-8 border-b border-slate-50 flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-orange-100">
                                <i class="ph-duotone ph-calendar-blank"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Jadwal Khusus / Libur</h3>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Tanggal Merah & Acara</p>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            <form action="<?php echo e(route('schedules.special.store')); ?>" method="POST" class="space-y-5">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <input type="date" name="date" required class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Keterangan</label>
                                    <input type="text" name="description" placeholder="Contoh: Rapat Guru" class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm shadow-sm placeholder:font-normal">
                                </div>
                                
                                <div class="bg-rose-50 p-3 rounded-2xl border border-rose-100 flex items-center gap-4 cursor-pointer hover:bg-rose-100 transition-colors select-none" @click="isHoliday = !isHoliday">
                                    <div class="relative flex items-center ml-1">
                                        <input type="checkbox" name="is_holiday" value="1" class="peer sr-only" x-model="isHoliday">
                                        <div class="w-10 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500 shadow-inner"></div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-xs font-black text-rose-800">Set Sebagai Hari Libur</span>
                                    </div>
                                </div>

                                <div x-show="!isHoliday" x-transition class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                                    <p class="text-[10px] font-black text-center text-slate-400 uppercase tracking-wide border-b border-slate-200 pb-2">Jam Operasional (Opsional)</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="time" name="start_in" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                        <input type="time" name="end_in" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                        <input type="time" name="start_out" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                        <input type="time" name="end_out" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-6 bg-orange-600 text-white font-bold rounded-2xl hover:bg-orange-700 transition-all shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-plus-circle text-lg"></i>
                                    Tambah Jadwal Khusus
                                </button>
                            </form>

                            <div>
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-3 ml-1">Terbaru Ditambahkan</h4>
                                <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                                    <?php $__empty_1 = true; $__currentLoopData = $specialSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ss): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="flex items-center justify-between p-4 rounded-2xl border <?php echo e($ss->is_holiday ? 'bg-rose-50 border-rose-100' : 'bg-blue-50 border-blue-100'); ?>">
                                            <div class="overflow-hidden mr-3"> 
                                                <p class="text-xs font-black <?php echo e($ss->is_holiday ? 'text-rose-700' : 'text-blue-700'); ?>">
                                                    <?php echo e(\Carbon\Carbon::parse($ss->date)->format('d M Y')); ?>

                                                </p>
                                                <p class="text-[10px] font-bold text-slate-500 truncate max-w-[150px]"><?php echo e($ss->description); ?></p>
                                            </div>
                                            
                                            <form action="<?php echo e(route('schedules.special.destroy', $ss->id)); ?>" 
                                                  method="POST"
                                                  id="delete-special-<?php echo e($ss->id); ?>"
                                                  class="shrink-0 block">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                
                                                <button type="button"
                                                        onclick="confirmDelete('delete-special-<?php echo e($ss->id); ?>', 'Hapus agenda <?php echo e($ss->description); ?> pada tanggal <?php echo e(\Carbon\Carbon::parse($ss->date)->format('d M Y')); ?>?')"
                                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/50 hover:bg-white text-slate-400 hover:text-rose-600 transition-all shadow-sm">
                                                    <i class="ph-bold ph-trash text-lg leading-none"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="text-center py-4 text-xs font-bold text-slate-400 italic">Belum ada data.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Fungsi Generic untuk menghapus data di halaman ini
        function confirmDelete(formId, message) {
            Swal.fire({
                title: 'Hapus Data?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form not found:', formId);
                        Swal.fire('Error', 'Form tidak ditemukan. Silakan refresh halaman.', 'error');
                    }
                }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/schedules/index.blade.php ENDPATH**/ ?>