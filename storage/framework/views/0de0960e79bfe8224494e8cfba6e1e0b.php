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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-8 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <h1 class="text-3xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl"></span> Data Induk Siswa
                        </h1>
                        <p class="text-blue-200 text-sm font-medium leading-relaxed max-w-lg">
                            Kelola data siswa, registrasi peserta didik baru, dan cetak kartu identitas dalam satu panel terintegrasi.
                        </p>
                    </div>
                    
                    
                    <div class="flex gap-3">
                        <div class="bg-blue-950/40 backdrop-blur-md px-5 py-3 rounded-2xl border border-blue-400/20 text-center min-w-[100px] shadow-lg">
                            <span class="block text-2xl font-black text-white"><?php echo e($students->total()); ?></span>
                            <span class="text-[10px] uppercase font-bold text-blue-300 tracking-wider">Total Siswa</span>
                        </div>
                        <div class="bg-blue-950/40 backdrop-blur-md px-5 py-3 rounded-2xl border border-blue-400/20 text-center min-w-[100px] shadow-lg">
                            <span class="block text-2xl font-black text-emerald-300"><?php echo e($classes->count()); ?></span>
                            <span class="text-[10px] uppercase font-bold text-emerald-200 tracking-wider">Rombel</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                
                <div class="lg:col-span-1">
                    
                    
                    <div class="sticky top-24 space-y-6">

                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                            
                            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-900 to-blue-700"></div>
                            
                            <div class="p-6 md:p-8">
                                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                                    <div class="w-12 h-12 bg-blue-900 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-blue-900/20">
                                        <i class="ph-duotone ph-user-plus"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black text-slate-800 leading-none">Registrasi Cepat</h3>
                                        <p class="text-xs text-blue-600 font-bold mt-1 uppercase tracking-wider">Input Siswa Baru</p>
                                    </div>
                                </div>

                                <form action="<?php echo e(route('students.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ photoPreview: null }">
                                    <?php echo csrf_field(); ?>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">NIS / NISN <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-900/50">
                                                <i class="ph-bold ph-identification-card"></i>
                                            </div>
                                            <input type="text" name="student_id" value="<?php echo e(old('student_id')); ?>" required placeholder="Nomor Induk"
                                                class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all placeholder:font-normal">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-900/50">
                                                <i class="ph-bold ph-user"></i>
                                            </div>
                                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Nama Sesuai Ijazah"
                                                class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all placeholder:font-normal">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Kelas <span class="text-rose-500">*</span></label>
                                            <div class="relative">
                                                <select name="class_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 appearance-none px-4">
                                                    <option value="">Pilih</option>
                                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($class->id); ?>" <?php echo e(old('class_id') == $class->id ? 'selected' : ''); ?>>
                                                            <?php echo e($class->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Gender <span class="text-rose-500">*</span></label>
                                            <div class="relative">
                                                <select name="gender" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 appearance-none px-4">
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Foto (Opsional)</label>
                                        <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                                            <div class="shrink-0 w-12 h-12 rounded-xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shadow-sm">
                                                <template x-if="photoPreview">
                                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!photoPreview">
                                                    <i class="ph-duotone ph-camera text-slate-300 text-2xl"></i>
                                                </template>
                                            </div>
                                            <input type="file" name="photo" accept="image/*" 
                                                @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result }; reader.readAsDataURL(file)"
                                                class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer"/>
                                        </div>
                                    </div>

                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">RFID (Opsional)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-scan"></i></div>
                                                <input type="text" name="rfid_id" value="<?php echo e(old('rfid_id')); ?>" placeholder="Scan..."
                                                    class="w-full pl-9 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-mono font-bold text-slate-700">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">WA Ortu</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-500"><i class="ph-bold ph-whatsapp-logo"></i></div>
                                                <input type="text" name="parent_wa_number" value="<?php echo e(old('parent_wa_number')); ?>" placeholder="628..."
                                                    class="w-full pl-9 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-mono font-bold text-slate-700">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full py-3.5 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-95 mt-4 group">
                                        <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i>
                                        Simpan Data
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[2.5rem] border border-emerald-100 p-6 relative overflow-hidden group hover:shadow-lg transition-all">
                            <div class="relative z-10">
                                <h3 class="text-sm font-black text-emerald-900 mb-1 flex items-center gap-2">
                                    <i class="ph-bold ph-microsoft-excel-logo text-emerald-600 text-lg"></i> Import Massal
                                </h3>
                                <p class="text-[10px] text-emerald-700/70 mb-4 font-bold">Gunakan file Excel untuk input banyak data sekaligus.</p>
                                
                                <form action="<?php echo e(route('students.import')); ?>" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                                    <?php echo csrf_field(); ?>
                                    <label class="flex-1 cursor-pointer">
                                        <div class="bg-white border border-dashed border-emerald-300 rounded-xl py-3 px-4 text-center transition-all hover:border-emerald-500 hover:bg-emerald-50/50 truncate">
                                            <span class="text-xs font-bold text-emerald-600 truncate flex items-center justify-center gap-2">
                                                <i class="ph-bold ph-upload-simple"></i> Pilih File...
                                            </span>
                                        </div>
                                        <input type="file" name="file" id="file" required class="hidden">
                                    </label>
                                    <button type="submit" class="py-3 px-5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors text-xs shadow-md shadow-emerald-500/20">
                                        Upload
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col min-h-[800px]">
                        
                        
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2 w-full sm:w-auto">
                                <i class="ph-fill ph-users text-blue-900"></i> Daftar Siswa
                            </h3>

                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <form action="<?php echo e(route('students.index')); ?>" method="GET" class="flex gap-2 w-full sm:w-auto">
                                    <div class="relative flex-1 sm:w-48">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="search" placeholder="Cari nama / NISN..." value="<?php echo e(request('search')); ?>"
                                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-xs font-bold text-slate-700 shadow-sm">
                                    </div>
                                    
                                    <select name="filter_class_id" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-xs font-bold text-slate-700 py-2.5 px-3 shadow-sm cursor-pointer w-full sm:w-32">
                                        <option value="">Semua Kelas</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('filter_class_id') == $class->id ? 'selected' : ''); ?>>
                                                <?php echo e($class->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </form>

                                <a href="<?php echo e(route('students.export')); ?>" class="flex items-center justify-center px-4 py-2.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-100 transition-all shadow-sm font-bold text-xs gap-2 shrink-0">
                                    <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> Export
                                </a>
                            </div>
                        </div>
                        
                        
                        <div class="flex-1 overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-blue-900 text-blue-100 border-b border-blue-800">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/3">Identitas Siswa</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/6">Kelas</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/6 text-center">Status Data</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider w-1/6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-blue-50/50 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    <div class="relative shrink-0">
                                                        <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-white flex items-center justify-center group-hover:border-blue-200 transition-colors">
                                                            <?php if($student->photo_path): ?>
                                                                <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" alt="<?php echo e($student->name); ?>" class="w-full h-full object-cover">
                                                            <?php else: ?>
                                                                <div class="font-black text-sm <?php echo e($student->gender == 'L' ? 'text-blue-600' : 'text-pink-500'); ?>"><?php echo e(substr($student->name, 0, 2)); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php if($student->rfid_id): ?>
                                                            <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white rounded-full p-1 border-2 border-white shadow-sm" title="RFID Connected">
                                                                <i class="ph-bold ph-wifi-high text-[10px] block"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div>
                                                        <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors"><?php echo e($student->name); ?></div>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span class="text-[10px] text-slate-500 font-mono bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200"><?php echo e($student->student_id); ?></span>
                                                            <span class="text-[10px] font-bold <?php echo e($student->gender == 'L' ? 'text-blue-500' : 'text-pink-500'); ?>"><?php echo e($student->gender); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-50 text-slate-600 border border-slate-100 group-hover:bg-white group-hover:border-blue-100 transition-colors">
                                                    <?php echo e($student->schoolClass->name ?? 'Unassigned'); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <?php $isComplete = $student->pob && $student->dob && $student->address && $student->father_name; ?>
                                                <?php if($isComplete): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">
                                                        <i class="ph-fill ph-check-circle"></i> Lengkap
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-wide">
                                                        <i class="ph-fill ph-warning-circle"></i> Incomplete
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1">
                                                    <a href="<?php echo e(route('students.show', $student->id)); ?>" target="_blank" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 flex items-center justify-center transition-all shadow-sm" title="Cetak Buku Induk">
                                                        <i class="ph-bold ph-printer text-lg"></i>
                                                    </a>

                                                    <a href="<?php echo e(route('students.edit', $student->id)); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 flex items-center justify-center transition-all shadow-sm" title="Edit Data">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </a>
                                                    
                                                    <div x-data="{ open: false }" class="relative">
                                                        <button @click="open = !open" @click.outside="open = false" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-900 hover:border-blue-300 hover:bg-blue-50 flex items-center justify-center transition-all shadow-sm">
                                                            <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                                        </button>
                                                        
                                                        <div x-show="open" x-transition.origin.top.right class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden py-1 ring-1 ring-black/5" style="display: none;">
                                                            <button type="button" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 flex items-center gap-2 open-absen-modal transition-colors"
                                                                data-student-id="<?php echo e($student->id); ?>" data-student-name="<?php echo e($student->name); ?>">
                                                                <i class="ph-bold ph-user-check text-base"></i> Input Absen
                                                            </button>
                                                            
                                                            <button type="button" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-emerald-600 flex items-center gap-2 open-qr-modal transition-colors"
                                                                data-student-id="<?php echo e($student->student_id); ?>" data-student-name="<?php echo e($student->name); ?>">
                                                                <i class="ph-bold ph-qr-code text-base"></i> Lihat QR Code
                                                            </button>
                                                            
                                                            <a href="<?php echo e(route('students.card', $student->id)); ?>" target="_blank" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-purple-600 flex items-center gap-2 transition-colors">
                                                                <i class="ph-bold ph-identification-card text-base"></i> Cetak Kartu
                                                            </a>
                                                            
                                                            <div class="border-t border-slate-100 my-1"></div>
                                                            
                                                            
                                                            <form action="<?php echo e(route('students.destroy', $student->id)); ?>" method="POST">
                                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                                <button type="button" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-50 flex items-center gap-2 transition-colors btn-delete-confirm" data-name="<?php echo e($student->name); ?>">
                                                                    <i class="ph-bold ph-trash text-base"></i> Hapus Siswa
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-20 text-center">
                                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                                    <i class="ph-duotone ph-users-three text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-500">Belum ada data siswa ditemukan.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 border-t border-slate-100">
                            <?php echo e($students->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="absen-manual-modal" class="fixed inset-0 bg-blue-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md shadow-2xl rounded-[2rem] bg-white overflow-hidden">
            <div class="bg-blue-900 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white text-lg">Input Absensi Manual</h3>
                <button type="button" id="absen-modal-close" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form id="absen-manual-form" action="<?php echo e(route('reports.storeManual')); ?>" method="POST" class="p-6 space-y-5">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="student_id" id="absen-modal-student-id">
                <input type="hidden" name="attendance_type" value="Harian">
                
                <div class="text-center mb-2">
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wide">Siswa</p>
                    <h4 id="absen-modal-student-name" class="text-xl font-black text-slate-800">Nama Siswa</h4>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Tanggal</label>
                    
                    <input type="text" name="date" value="<?php echo e(date('Y-m-d')); ?>" class="datepicker w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 font-bold text-slate-700" placeholder="dd/mm/yyyy">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Status Kehadiran</label>
                    <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 font-bold text-slate-700">
                        <option value="Hadir">Hadir (Manual)</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option>
                        <option value="Terlambat">Terlambat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Waktu Masuk</label>
                        <input type="time" name="time_in" value="<?php echo e(now()->format('H:i')); ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 text-center font-mono font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Waktu Pulang</label>
                        <input type="time" name="time_out" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 text-center font-mono font-bold text-slate-700">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Keterangan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Datang terlambat karena ban bocor..." class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 text-sm font-medium"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/30 transition-transform active:scale-95">Simpan Data</button>
            </form>
        </div>
    </div>

    <div id="qr-code-modal" class="fixed inset-0 bg-blue-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-sm shadow-2xl rounded-[2rem] bg-white overflow-hidden text-center p-8">
            <h3 class="text-lg font-black text-slate-800 mb-1" id="qr-modal-student-name">QR Code</h3>
            <p class="text-xs text-slate-400 font-bold uppercase mb-6">Identitas Digital Siswa</p>
            <div class="bg-white p-4 border-2 border-dashed border-blue-200 rounded-2xl inline-block mb-6 relative group">
                <div class="absolute inset-0 bg-blue-50/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-2xl">
                    <i class="ph-bold ph-download-simple text-blue-600 text-2xl"></i>
                </div>
                <img id="qr-modal-image" src="" alt="QR Code" class="w-48 h-48 object-contain">
            </div>
            <div class="flex gap-3 justify-center">
                <button type="button" id="qr-modal-close" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 text-sm transition-colors">Tutup</button>
                <a id="qr-modal-download" href="#" download="qrcode.png" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 text-sm transition-transform active:scale-95 flex items-center gap-2">
                    <i class="ph-bold ph-download-simple"></i> Unduh
                </a>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 0. INISIALISASI FLATPICKR
            flatpickr(".datepicker", {
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                locale: "id",
                disableMobile: "true"
            });

            // 1. FLASH MESSAGES (SUCCESS / ERROR)
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?php echo e(session('success')); ?>',
                    confirmButtonColor: '#1e3a8a', // Blue 900
                    timer: 3000,
                    timerProgressBar: true
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?php echo e(session('error')); ?>',
                    confirmButtonColor: '#e11d48', // Rose 600
                });
            <?php endif; ?>

            // 2. KONFIRMASI HAPUS SISWA
            document.body.addEventListener('click', function(e) {
                if(e.target.closest('.btn-delete-confirm')) {
                    e.preventDefault();
                    const button = e.target.closest('.btn-delete-confirm');
                    const form = button.closest('form');
                    const studentName = button.getAttribute('data-name');

                    Swal.fire({
                        title: 'Hapus Siswa?',
                        text: `Data siswa "${studentName}" beserta riwayat absen akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // Rose 600
                        cancelButtonColor: '#64748b',  // Slate 500
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            // 3. LOGIKA MODAL LAINNYA (ABSEN & QR)
            const absenModal = document.getElementById('absen-manual-modal');
            const qrModal = document.getElementById('qr-code-modal');

            // Handle Open Absen
            document.addEventListener('click', function(e) {
                if (e.target.closest('.open-absen-modal')) {
                    const btn = e.target.closest('.open-absen-modal');
                    document.getElementById('absen-modal-student-name').innerText = btn.dataset.studentName;
                    document.getElementById('absen-modal-student-id').value = btn.dataset.studentId;
                    absenModal.classList.remove('hidden');
                }
                
                // Handle Open QR
                if (e.target.closest('.open-qr-modal')) {
                    const btn = e.target.closest('.open-qr-modal');
                    const id = btn.dataset.studentId;
                    const name = btn.dataset.studentName;
                    const url = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(id)}`;
                    document.getElementById('qr-modal-student-name').innerText = name;
                    document.getElementById('qr-modal-image').src = url;
                    document.getElementById('qr-modal-download').href = url;
                    qrModal.classList.remove('hidden');
                }
            });

            document.getElementById('absen-modal-close').onclick = () => absenModal.classList.add('hidden');
            document.getElementById('qr-modal-close').onclick = () => qrModal.classList.add('hidden');
            
            // Close on click outside
            window.onclick = function(event) {
                if (event.target == absenModal) absenModal.classList.add('hidden');
                if (event.target == qrModal) qrModal.classList.add('hidden');
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/index.blade.php ENDPATH**/ ?>