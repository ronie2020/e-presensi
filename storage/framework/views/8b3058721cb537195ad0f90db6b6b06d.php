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
    
    <div x-data="{ activeTab: 'mapel' }" class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/50 hover:bg-white/80 text-elevate-primary px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-calendar-plus"></i> Akademik & KBM
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Atur Jadwal
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Kelola jadwal pelajaran (KBM) per kelas dengan format Jam Pelajaran (JP) serta jam operasional scanner.
                        </p>
                    </div>
                    
                    
                    <div class="bg-white/40 backdrop-blur-md p-1.5 rounded-2xl border border-white/50 flex flex-col sm:flex-row gap-1 shadow-sm w-full md:w-auto">
                        <button @click="activeTab = 'mapel'" 
                            :class="activeTab === 'mapel' ? 'bg-elevate-dark text-white shadow-lg shadow-elevate-dark/30' : 'text-elevate-dark/70 hover:bg-white/60 hover:text-elevate-dark'"
                            class="px-6 py-3.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap border border-transparent">
                            <i class="ph-bold ph-book-open text-lg"></i> Jadwal Mapel
                        </button>
                        <button @click="activeTab = 'jam'" 
                            :class="activeTab === 'jam' ? 'bg-elevate-dark text-white shadow-lg shadow-elevate-dark/30' : 'text-elevate-dark/70 hover:bg-white/60 hover:text-elevate-dark'"
                            class="px-6 py-3.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap border border-transparent">
                            <i class="ph-bold ph-clock text-lg"></i> Jam Sekolah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            
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
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-start gap-3 shadow-sm relative">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0 ml-2 mt-0.5">
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
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden sticky top-24 group/form hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                            
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20 group-hover/form:scale-110 transition-transform">
                                    <i class="ph-duotone ph-plus-square"></i>
                                </div>
                                <h2 class="text-xl font-black text-elevate-dark">Tambah Jadwal</h2>
                            </div>

                            <form action="<?php echo e(route('schedules.store')); ?>" method="POST" class="space-y-5">
                                <?php echo csrf_field(); ?>
                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kelas</label>
                                    <div class="relative group">
                                        <select name="school_class_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer <?php $__errorArgs = ['school_class_id'];
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
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Mata Pelajaran</label>
                                    <div class="relative group">
                                        <select name="subject_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">-- Pilih Mapel --</option>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($s->id); ?>" <?php echo e(old('subject_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Guru Pengampu</label>
                                    <div class="relative group">
                                        <select name="teacher_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer <?php $__errorArgs = ['teacher_id'];
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
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Hari</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="day" value="<?php echo e($day); ?>" class="peer sr-only" <?php echo e(old('day') == $day ? 'checked' : ''); ?> required>
                                                <div class="text-center py-2.5 rounded-xl bg-elevate-soft border border-slate-200 text-xs font-bold text-elevate-dark/60 peer-checked:bg-elevate-primary peer-checked:text-white peer-checked:border-elevate-primary peer-checked:shadow-md transition-all hover:bg-white hover:border-elevate-accent">
                                                    <?php echo e(substr($day, 0, 3)); ?>

                                                </div>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Mulai Jam Ke-</label>
                                        <div class="relative">
                                            <select name="start_time" required class="w-full text-center py-3.5 rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 appearance-none cursor-pointer transition-all shadow-sm">
                                                <?php for($i = 1; $i <= 12; $i++): ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e(old('start_time') == $i ? 'selected' : ''); ?>>Jam <?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Selesai Jam Ke-</label>
                                        <div class="relative">
                                            <select name="end_time" required class="w-full text-center py-3.5 rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 appearance-none cursor-pointer transition-all shadow-sm">
                                                <?php for($i = 1; $i <= 12; $i++): ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e(old('end_time') == $i ? 'selected' : ''); ?>>Jam <?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full mt-6 py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Jadwal
                                </button>
                            </form>
                        </div>
                    </div>

                    
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col relative min-h-[600px]">
                            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20">
                                        <i class="ph-duotone ph-list-dashes"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-elevate-dark">Daftar Jadwal</h3>
                                        <p class="text-xs text-elevate-dark/60 font-bold uppercase tracking-wider mt-1">Total <?php echo e($schedules->count()); ?> Sesi</p>
                                    </div>
                                </div>
                                
                                <form method="GET" class="w-full sm:w-auto">
                                    <div class="relative group">
                                        <select name="class_id" onchange="this.form.submit()" class="w-full sm:w-56 pl-11 pr-10 py-3 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">Semua Kelas</option>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($c->id); ?>" <?php echo e(request('class_id') == $c->id ? 'selected' : ''); ?>>Kelas <?php echo e($c->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-funnel absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="overflow-x-auto flex-1 custom-scrollbar">
                                <table class="min-w-full text-left text-sm text-elevate-dark">
                                    <thead class="bg-elevate-soft/50 text-xs font-bold text-elevate-primary uppercase tracking-wider sticky top-0 z-10 border-b border-slate-100">
                                        <tr>
                                            <th class="px-8 py-5">Hari & Jam Pelajaran</th>
                                            <th class="px-6 py-5">Kelas</th>
                                            <th class="px-6 py-5">Mata Pelajaran</th>
                                            <th class="px-6 py-5">Guru</th>
                                            <th class="px-8 py-5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-elevate-dark text-base mb-1"><?php echo e($item->day); ?></span>
                                                    
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-elevate-soft text-elevate-primary text-[10px] font-mono font-bold border border-elevate-accent/20 w-fit shadow-sm">
                                                        <i class="ph-bold ph-clock"></i>
                                                        JP <?php echo e($item->clean_start_time); ?> - <?php echo e($item->clean_end_time); ?>

                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-elevate-dark text-xs font-bold shadow-sm">
                                                    <?php echo e($item->schoolClass->name); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="font-bold text-elevate-dark text-sm"><?php echo e($item->subject->name); ?></div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-xl bg-elevate-peach-light/40 flex items-center justify-center text-[10px] font-black text-elevate-peach-dark border border-elevate-peach/30 shadow-sm">
                                                        <?php echo e(substr($item->teacher->name, 0, 1)); ?>

                                                    </div>
                                                    <span class="font-bold text-elevate-dark/80 text-xs"><?php echo e($item->teacher->name); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                                <div class="flex justify-end items-center opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                                    <form action="<?php echo e(route('schedules.destroy', $item->id)); ?>" 
                                                          method="POST" 
                                                          id="delete-schedule-<?php echo e($item->id); ?>"
                                                          class="shrink-0 block">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        
                                                        <button type="button" 
                                                                onclick="confirmDelete('delete-schedule-<?php echo e($item->id); ?>', 'Hapus jadwal mapel <?php echo e($item->subject->name); ?> di kelas <?php echo e($item->schoolClass->name); ?>?')"
                                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm">
                                                            <i class="ph-bold ph-trash text-lg leading-none"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="px-8 py-20 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mb-4 text-elevate-primary shadow-inner border border-elevate-accent/20">
                                                        <i class="ph-duotone ph-calendar-slash text-5xl"></i>
                                                    </div>
                                                    <p class="text-base font-black text-elevate-dark mb-1">Belum ada jadwal pelajaran.</p>
                                                    <p class="text-sm text-elevate-dark/60 font-medium">Gunakan formulir di kiri untuk menambah jadwal.</p>
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
                    
                    
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group/card hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                        
                        <div class="p-8 border-b border-slate-100 flex items-center gap-4">
                            <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-elevate-accent/20 group-hover/card:scale-110 transition-transform">
                                <i class="ph-duotone ph-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-elevate-dark">Jam Sekolah Reguler</h3>
                                <p class="text-xs text-elevate-dark/60 font-bold uppercase tracking-wider mt-1">Setting Bel Masuk & Pulang</p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('schedules.regular.store')); ?>" method="POST" class="p-6 sm:p-8 space-y-8">
                            <?php echo csrf_field(); ?>
                            
                            
                            <div class="bg-elevate-soft/50 p-6 rounded-[2rem] border border-slate-100 relative transition-colors hover:bg-elevate-soft hover:border-elevate-accent/30">
                                <div class="flex items-center justify-between mb-6">
                                    <h4 class="font-black text-elevate-dark flex items-center gap-2 text-lg">
                                        <span class="w-2 h-8 bg-elevate-primary rounded-full"></span>
                                        Senin - Kamis
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Biasa">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-primary/70 mb-3 sm:text-center tracking-wider"><i class="ph-bold ph-sun-horizon"></i> Masuk</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_in[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->start_in ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->start_in)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Jam Buka Scanner Masuk">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Buka Scan</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_in[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->end_in ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->end_in)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Batas Terlambat">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Batas Telat</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-primary/70 mb-3 mt-2 sm:mt-0 sm:text-center tracking-wider"><i class="ph-bold ph-moon-stars text-elevate-dark"></i> Pulang</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_out[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->start_out ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->start_out)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Jam Boleh Pulang">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Boleh Plg</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_out[]" value="<?php echo e(optional($regularSchedules->get('Biasa'))->end_out ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->end_out)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Tutup Scanner Pulang">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Tutup Scan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-elevate-peach-light/20 p-6 rounded-[2rem] border border-elevate-peach/30 relative transition-colors hover:bg-elevate-peach-light/40 hover:border-elevate-peach/50">
                                <div class="flex items-center justify-between mb-6">
                                    <h4 class="font-black text-elevate-dark flex items-center gap-2 text-lg">
                                        <span class="w-2 h-8 bg-elevate-peach-dark rounded-full"></span>
                                        Hari Jum'at
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Jumat">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-peach-dark/80 mb-3 sm:text-center tracking-wider"><i class="ph-bold ph-sun-horizon"></i> Masuk</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_in[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->start_in ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->start_in)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Buka Scan</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_in[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->end_in ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->end_in)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Batas Telat</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-peach-dark/80 mb-3 mt-2 sm:mt-0 sm:text-center tracking-wider"><i class="ph-bold ph-moon-stars text-elevate-dark"></i> Pulang</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_out[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->start_out ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->start_out)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Boleh Plg</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_out[]" value="<?php echo e(optional($regularSchedules->get('Jumat'))->end_out ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->end_out)->format('H:i') : ''); ?>" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Tutup Scan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                <i class="ph-bold ph-floppy-disk text-xl"></i>
                                Simpan Jam Reguler
                            </button>
                        </form>
                    </div>

                    
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group/card2 hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300" x-data="{ isHoliday: false }">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-peach to-elevate-peach-dark"></div>
                        
                        <div class="p-8 border-b border-slate-100 flex items-center gap-4">
                            <div class="w-14 h-14 bg-elevate-peach-light/40 text-elevate-peach-dark rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-elevate-peach/30 group-hover/card2:scale-110 transition-transform">
                                <i class="ph-duotone ph-calendar-blank"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-elevate-dark">Jadwal Khusus / Libur</h3>
                                <p class="text-xs text-elevate-dark/60 font-bold uppercase tracking-wider mt-1">Tanggal Merah & Acara</p>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            <form action="<?php echo e(route('schedules.special.store')); ?>" method="POST" class="space-y-5">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="date" name="date" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark text-sm shadow-sm transition-all outline-none cursor-pointer">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Keterangan</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="description" placeholder="Contoh: Rapat Guru" class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark text-sm shadow-sm placeholder:font-medium placeholder:text-slate-400 transition-all outline-none">
                                    </div>
                                </div>
                                
                                <div class="bg-rose-50 p-4 rounded-2xl border border-rose-100 flex items-center gap-4 cursor-pointer hover:bg-rose-100 transition-colors select-none shadow-sm" @click="isHoliday = !isHoliday">
                                    <div class="relative flex items-center ml-1">
                                        <input type="checkbox" name="is_holiday" value="1" class="peer sr-only" x-model="isHoliday">
                                        <div class="w-10 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500 shadow-inner"></div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-black text-rose-800">Set Sebagai Hari Libur</span>
                                    </div>
                                    <i class="ph-duotone ph-coffee text-rose-500 text-2xl mr-1"></i>
                                </div>

                                <div x-show="!isHoliday" x-transition class="bg-elevate-soft/50 p-5 rounded-2xl border border-elevate-accent/20 space-y-4">
                                    <p class="text-[10px] font-black text-center text-elevate-primary/70 uppercase tracking-wider border-b border-elevate-accent/20 pb-3">Jam Operasional (Opsional)</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="text-center">
                                            <input type="time" name="start_in" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Buka Masuk</p>
                                        </div>
                                        <div class="text-center">
                                            <input type="time" name="end_in" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Batas Telat</p>
                                        </div>
                                        <div class="text-center">
                                            <input type="time" name="start_out" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Boleh Plg</p>
                                        </div>
                                        <div class="text-center">
                                            <input type="time" name="end_out" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Tutup Plg</p>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-plus-circle text-lg"></i>
                                    Tambah Jadwal Khusus
                                </button>
                            </form>

                            <div>
                                <h4 class="text-[10px] font-black text-elevate-dark/50 uppercase tracking-wider mb-3 ml-1">Terbaru Ditambahkan</h4>
                                <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                                    <?php $__empty_1 = true; $__currentLoopData = $specialSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ss): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="flex items-center justify-between p-4 rounded-2xl border shadow-sm transition-colors hover:shadow-md <?php echo e($ss->is_holiday ? 'bg-rose-50 border-rose-100 hover:border-rose-200' : 'bg-elevate-soft border-elevate-accent/20 hover:border-elevate-accent/40'); ?>">
                                            <div class="overflow-hidden mr-3"> 
                                                <p class="text-xs font-black <?php echo e($ss->is_holiday ? 'text-rose-700' : 'text-elevate-primary'); ?>">
                                                    <?php echo e(\Carbon\Carbon::parse($ss->date)->format('d M Y')); ?>

                                                </p>
                                                <p class="text-xs font-bold text-elevate-dark truncate max-w-[180px] mt-0.5"><?php echo e($ss->description); ?></p>
                                            </div>
                                            
                                            <form action="<?php echo e(route('schedules.special.destroy', $ss->id)); ?>" 
                                                  method="POST"
                                                  id="delete-special-<?php echo e($ss->id); ?>"
                                                  class="shrink-0 block">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                
                                                <button type="button"
                                                        onclick="confirmDelete('delete-special-<?php echo e($ss->id); ?>', 'Hapus agenda <?php echo e(addslashes($ss->description)); ?> pada tanggal <?php echo e(\Carbon\Carbon::parse($ss->date)->format('d M Y')); ?>?')"
                                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 hover:border-rose-200 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all shadow-sm">
                                                    <i class="ph-bold ph-trash text-lg leading-none"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="text-center py-6 text-xs font-bold text-elevate-dark/40 italic bg-slate-50 rounded-xl border border-slate-100">Belum ada data jadwal khusus.</div>
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
                    confirmButton: 'bg-rose-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-600/30',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
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