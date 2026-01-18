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
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-trophy"></i> Modul Kesiswaan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Prestasi & Penghargaan
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Rekam jejak kejuaraan siswa dan guru. Kelola data prestasi akademik maupun non-akademik untuk arsip sekolah.
                        </p>
                    </div>
                    
                    
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white/15 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                <i class="ph-duotone ph-medal text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Prestasi</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight"><?php echo e($achievements->total()); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                        
                        
                        <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Input Prestasi</h3>
                            <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Catat pencapaian baru.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="<?php echo e(route('achievements.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6" 
                                  x-data="{ type: '<?php echo e(old('type', 'Siswa')); ?>', imgPreview: null }">
                                <?php echo csrf_field(); ?>
                                
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pemenang</label>
                                    <div class="grid grid-cols-3 gap-1 p-1 bg-slate-50 rounded-xl border border-slate-200">
                                        <button type="button" @click="type = 'Siswa'" :class="type === 'Siswa' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Siswa</button>
                                        <button type="button" @click="type = 'Guru'" :class="type === 'Guru' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Guru</button>
                                        <button type="button" @click="type = 'Sekolah'" :class="type === 'Sekolah' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Sekolah</button>
                                    </div>
                                    <input type="hidden" name="type" x-model="type">
                                </div>

                                
                                <div x-show="type === 'Siswa'" x-transition>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Siswa</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-student absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <select name="student_id" class="w-full pl-11 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 transition-colors appearance-none cursor-pointer">
                                            <option value="">-- Pilih Siswa --</option>
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($student->id); ?>" <?php echo e(old('student_id') == $student->id ? 'selected' : ''); ?>>
                                                    <?php echo e($student->name); ?> (<?php echo e($student->schoolClass->name ?? '-'); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1 font-bold"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                
                                <div x-show="type !== 'Siswa'" x-transition style="display: none;">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pemenang / Tim</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-users absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="name_manual" value="<?php echo e(old('name_manual')); ?>" placeholder="Contoh: Tim Futsal Guru" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 transition-colors">
                                    </div>
                                    <?php $__errorArgs = ['name_manual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1 font-bold"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Prestasi</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-medal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" value="<?php echo e(old('title')); ?>" required placeholder="Juara 1 Lomba..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 transition-colors">
                                    </div>
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1 font-bold"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat</label>
                                        <div class="relative">
                                            <select name="level" class="w-full pl-3 pr-8 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 text-xs appearance-none">
                                                <?php $__currentLoopData = ['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lvl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($lvl); ?>" <?php echo e(old('level') == $lvl ? 'selected' : ''); ?>><?php echo e($lvl); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                        <input type="date" name="date" value="<?php echo e(old('date', date('Y-m-d'))); ?>" class="w-full px-3 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 text-xs">
                                    </div>
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dokumentasi</label>
                                    <div class="relative group">
                                        <input type="file" name="photo" accept="image/*" @change="imgPreview = URL.createObjectURL($event.target.files[0])" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-4 text-center transition-all group-hover:border-blue-500 group-hover:bg-blue-50" :class="{'border-blue-500 bg-blue-50': imgPreview}">
                                            <div x-show="!imgPreview" class="space-y-2">
                                                <i class="ph-duotone ph-image text-3xl text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                                                <p class="text-[10px] text-slate-400 font-bold">Upload Foto</p>
                                            </div>
                                            <div x-show="imgPreview" style="display: none;">
                                                <img :src="imgPreview" class="h-24 w-full object-cover rounded-xl mx-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link Video (Opsional)</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-link absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="url" name="video_link" value="<?php echo e(old('video_link')); ?>" placeholder="https://..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 transition-colors text-xs">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-4 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px]">
                        
                        
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-blue-900"></i> Riwayat Prestasi
                                </h2>
                                <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-slate-500 shadow-sm">
                                    <?php echo e($achievements->count()); ?> Data
                                </span>
                            </div>

                            <div class="flex gap-3 w-full sm:w-auto">
                                <form method="GET" class="relative flex-1 sm:w-64 group">
                                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari prestasi..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold transition-colors shadow-sm">
                                </form>
                                <a href="<?php echo e(route('achievements.export', request()->all())); ?>" target="_blank" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                                    <span class="hidden sm:inline">Export</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-5">Info Juara</th>
                                        <th class="px-6 py-5">Prestasi</th>
                                        <th class="px-6 py-5">Tingkat</th>
                                        <th class="px-6 py-5 text-center">Tanggal</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="group hover:bg-blue-50/30 transition-colors duration-200">
                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-white
                                                        <?php echo e($item->type == 'Siswa' ? 'bg-blue-100 text-blue-600' : ($item->type == 'Guru' ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600')); ?>">
                                                        <?php if($item->type == 'Siswa'): ?>
                                                            <?php echo e(substr($item->achiever_name, 0, 2)); ?>

                                                        <?php elseif($item->type == 'Guru'): ?>
                                                            <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                                                        <?php else: ?>
                                                            <i class="ph-bold ph-buildings text-lg"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-black text-slate-800 text-sm line-clamp-1 group-hover:text-blue-700 transition-colors"><?php echo e($item->achiever_name); ?></div>
                                                        <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold border
                                                            <?php echo e($item->type == 'Siswa' ? 'bg-blue-50 text-blue-600 border-blue-100' : ($item->type == 'Guru' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-orange-50 text-orange-600 border-orange-100')); ?>">
                                                            <?php echo e(strtoupper($item->type)); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-slate-700 text-sm mb-1 line-clamp-2 leading-snug"><?php echo e($item->title); ?></div>
                                                <?php if($item->video_link): ?>
                                                    <a href="<?php echo e($item->video_link); ?>" target="_blank" class="flex items-center gap-1.5 text-[10px] font-bold text-blue-500 hover:text-blue-700 hover:underline transition">
                                                        <i class="ph-fill ph-video-camera"></i> Lihat Video
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">
                                                    <i class="ph-fill ph-map-pin text-slate-400"></i> <?php echo e($item->level); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <div class="text-xs font-bold text-slate-600"><?php echo e(\Carbon\Carbon::parse($item->date)->format('d M')); ?></div>
                                                <div class="text-[10px] font-bold text-slate-400"><?php echo e(\Carbon\Carbon::parse($item->date)->format('Y')); ?></div>
                                            </td>
                                            <td class="px-6 py-5 text-right">
                                                <div class="flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <form action="<?php echo e(route('achievements.destroy', $item->id)); ?>" method="POST" onsubmit="return confirm('Hapus data prestasi ini?');">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" title="Hapus Data">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="py-20 text-center">
                                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 shadow-sm border border-slate-100">
                                                    <i class="ph-duotone ph-trophy text-4xl text-slate-300"></i>
                                                </div>
                                                <h3 class="text-slate-700 font-bold text-lg">Belum ada data prestasi</h3>
                                                <p class="text-slate-400 text-sm mt-1">Silakan input prestasi pertama sekolah di formulir sebelah kiri.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        
                        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                            <?php echo e($achievements->links()); ?>

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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\achievements\index.blade.php ENDPATH**/ ?>