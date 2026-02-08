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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Akademik & E-Rapor')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="{ 
            inputMode: 'subject', 
            importMode: false,
            students: [],
            isLoadingStudents: false,

            fetchStudents(classId) {
                if(!classId) { this.students = []; return; }
                this.isLoadingStudents = true;
                fetch(`<?php echo e(url('/grades/students')); ?>/${classId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.students = data;
                        this.isLoadingStudents = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.isLoadingStudents = false;
                    });
            }
         }"> 
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl"></span> Akademik & E-Rapor
                        </h1>
                        <p class="text-blue-300 text-sm font-medium leading-relaxed max-w-lg">
                            Pusat pengelolaan nilai siswa, rapor semester, dan arsip akademik sekolah secara terpadu.
                        </p>
                    </div>
                    
                    
                    <div class="flex gap-3">
                        <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center">
                            <span class="block text-2xl font-black"><?php echo e(count($classes)); ?></span>
                            <span class="text-[10px] uppercase font-bold text-blue-300 tracking-wider">Kelas</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center">
                            <span class="block text-2xl font-black"><?php echo e(count($subjects)); ?></span>
                            <span class="text-[10px] uppercase font-bold text-blue-300 tracking-wider">Mapel</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 border border-slate-100 overflow-hidden relative group transition-all duration-300 flex flex-col h-full hover:border-blue-200">
                    
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-sm">
                                    <i class="ph-duotone ph-pencil-simple-line"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-slate-800">Input Nilai</h2>
                                    <p class="text-sm font-medium text-slate-400">Entri data nilai harian & ujian.</p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-slate-100 p-1.5 rounded-xl flex mb-6 relative">
                            <button @click="inputMode = 'subject'; importMode = false" 
                                    :class="inputMode === 'subject' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10">
                                <i class="ph-bold ph-books"></i> Per Mapel
                            </button>
                            <button @click="inputMode = 'student'; importMode = false" 
                                    :class="inputMode === 'student' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10">
                                <i class="ph-bold ph-student"></i> Per Siswa
                            </button>
                        </div>

                        
                        <div x-show="inputMode === 'subject'" x-transition>
                            
                            
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Metode Input</span>
                                <div class="flex bg-slate-50 rounded-lg p-1 border border-slate-100">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-white text-slate-800 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            
                            <form x-show="!importMode" action="<?php echo e(route('grades.create')); ?>" method="GET" class="flex-1 flex flex-col gap-4">
                                <div class="bg-slate-50 p-1.5 rounded-2xl border border-slate-100 space-y-2">
                                    <select name="class_id" class="w-full rounded-xl border-transparent bg-white text-sm font-bold text-slate-700 focus:ring-blue-500 py-3" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <select name="subject_id" class="w-full rounded-xl border-transparent bg-white text-sm font-bold text-slate-700 focus:ring-blue-500 py-3" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="academic_year" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3">
                                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($y->name); ?>" <?php echo e(($activeYear && $activeYear->name == $y->name) ? 'selected' : ''); ?>><?php echo e($y->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3">
                                            <option value="1" <?php echo e(($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : ''); ?>>Ganjil</option>
                                            <option value="2" <?php echo e(($activeYear && $activeYear->semester == 'Genap') ? 'selected' : ''); ?>>Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-2 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20 group">
                                    <span>Mulai Input</span> <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </form>

                            
                            <form x-show="importMode" action="<?php echo e(route('grades.import')); ?>" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4" style="display: none;">
                                <?php echo csrf_field(); ?>
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg"><i class="ph-fill ph-file-xls text-xl"></i></div>
                                    <div>
                                        <p class="text-xs text-emerald-800 font-bold">Import Nilai (Per Mapel)</p>
                                        <a href="<?php echo e(route('grades.template')); ?>" class="text-[10px] font-bold text-emerald-600 underline hover:text-emerald-800">Download Template Excel</a>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="class_id" class="w-full rounded-xl border-slate-200 text-xs font-bold" required>
                                            <option value="">- Kelas -</option>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <select name="subject_id" class="w-full rounded-xl border-slate-200 text-xs font-bold" required>
                                            <option value="">- Mapel -</option>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-50/10 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-300 group-hover:text-emerald-500 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-500 group-hover:text-emerald-600">Klik untuk upload file Excel</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data
                                </button>
                            </form>
                        </div>

                        
                        <div x-show="inputMode === 'student'" x-transition style="display: none;">
                            
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Metode Input</span>
                                <div class="flex bg-slate-50 rounded-lg p-1 border border-slate-100">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-white text-slate-800 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            
                            <form x-show="!importMode" action="<?php echo e(route('grades.create_by_student')); ?>" method="GET" class="flex-1 flex flex-col gap-4">
                                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-3 flex gap-3 items-start">
                                    <i class="ph-fill ph-info text-blue-500 mt-0.5"></i>
                                    <p class="text-xs text-blue-800 leading-relaxed font-medium">
                                        Pilih <strong>Kelas</strong> terlebih dahulu. Daftar Siswa akan muncul di halaman berikutnya.
                                    </p>
                                </div>

                                <div class="bg-slate-50 p-1.5 rounded-2xl border border-slate-100 space-y-2">
                                    <select name="class_id" class="w-full rounded-xl border-transparent bg-white text-sm font-bold text-slate-700 focus:ring-blue-500 py-3" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="academic_year" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3">
                                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($y->name); ?>" <?php echo e(($activeYear && $activeYear->name == $y->name) ? 'selected' : ''); ?>><?php echo e($y->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3">
                                            <option value="1" <?php echo e(($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : ''); ?>>Ganjil</option>
                                            <option value="2" <?php echo e(($activeYear && $activeYear->semester == 'Genap') ? 'selected' : ''); ?>>Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-2 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/20">
                                    <span>Lanjut Pilih Siswa</span> <i class="ph-bold ph-user-list"></i>
                                </button>
                            </form>

                            
                            <form x-show="importMode" action="<?php echo e(route('grades.import_student')); ?>" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4" style="display: none;">
                                <?php echo csrf_field(); ?>
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg"><i class="ph-fill ph-file-xls text-xl"></i></div>
                                    <div>
                                        <p class="text-xs text-emerald-800 font-bold">Import Nilai (Per Siswa)</p>
                                        <a href="<?php echo e(route('grades.template_student')); ?>" class="text-[10px] font-bold text-emerald-600 underline hover:text-emerald-800">Download Template Siswa</a>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    
                                    <select name="class_id" @change="fetchStudents($el.value)" class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700" required>
                                        <option value="">- Pilih Kelas Dulu -</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    
                                    <div class="relative">
                                        <select name="student_id" :disabled="students.length === 0" class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 disabled:bg-slate-100 disabled:text-slate-400" required>
                                            <option value="">- Pilih Siswa -</option>
                                            <template x-for="student in students" :key="student.id">
                                                <option :value="student.id" x-text="student.name + ' (' + student.student_id + ')'"></option>
                                            </template>
                                        </select>
                                        <div x-show="isLoadingStudents" class="absolute right-8 top-1/2 -translate-y-1/2 text-emerald-500"><i class="ph-bold ph-spinner animate-spin"></i></div>
                                    </div>

                                    
                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-50/10 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-300 group-hover:text-emerald-500 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-500 group-hover:text-emerald-600">Pilih File Excel Siswa</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 border border-slate-100 overflow-hidden relative group transition-all duration-300 flex flex-col h-full hover:border-cyan-200">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-cyan-500 to-blue-500"></div>
                    
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shadow-sm">
                                    <i class="ph-duotone ph-printer"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-slate-800">Cetak E-Rapor</h2>
                                    <p class="text-sm font-medium text-slate-400">Hasil belajar siswa.</p>
                                </div>
                            </div>
                        </div>

                        <form action="<?php echo e(route('grades.list')); ?>" method="GET" class="flex-1 flex flex-col gap-4">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex-1 flex flex-col justify-center">
                                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Pilih Kelas</label>
                                <select name="class_id" class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 focus:ring-cyan-500 h-12" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                
                                <div class="mt-4 flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-100">
                                    <i class="ph-fill ph-info text-cyan-500 mt-0.5"></i>
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        Sistem akan menampilkan daftar siswa dari kelas yang dipilih. Pastikan nilai sudah lengkap sebelum mencetak rapor.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Tahun</label>
                                    <select name="academic_year" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white">
                                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($y->name); ?>" <?php echo e(($activeYear && $activeYear->name == $y->name) ? 'selected' : ''); ?>><?php echo e($y->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Semester</label>
                                    <select name="semester" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white">
                                        <option value="1" <?php echo e(($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : ''); ?>>Ganjil</option>
                                        <option value="2" <?php echo e(($activeYear && $activeYear->semester == 'Genap') ? 'selected' : ''); ?>>Genap</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-2 py-3.5 bg-cyan-600 text-white font-bold rounded-xl hover:bg-cyan-700 transition flex items-center justify-center gap-2 shadow-lg shadow-cyan-500/20 group">
                                <i class="ph-bold ph-list-magnifying-glass text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Lihat Daftar Siswa</span>
                            </button>
                        </form>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/grades/index.blade.php ENDPATH**/ ?>