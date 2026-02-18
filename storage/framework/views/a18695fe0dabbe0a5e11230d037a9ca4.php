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
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-users-three"></i> Akses & Keamanan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Manajemen Pengguna
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola akun akses untuk Admin, Kepala Sekolah, TU, Guru, dan Staf lainnya.
                        </p>
                    </div>
                    
                    
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white/15 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                <i class="ph-duotone ph-user-circle text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Akun</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight"><?php echo e($users->total()); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            
            <?php if(session('error')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600 p-1 rounded-md hover:bg-rose-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                        
                        
                        <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-user-plus"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">User Baru</h3>
                            <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Registrasi akun cepat.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="<?php echo e(route('users.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                                <?php echo csrf_field(); ?>
                                
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Contoh: Budi Santoso, S.Pd."
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors placeholder:font-normal">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Email Login</label>
                                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="email@sekolah.sch.id"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors placeholder:font-normal">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Peran (Role)</label>
                                    <div class="relative">
                                        
                                        <select name="role[]" required multiple class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors appearance-none cursor-pointer h-32">
                                            <option value="Guru">Guru (Umum)</option>
                                            <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                                            <option value="Wali Kelas">Wali Kelas</option>
                                            <option value="TU">TU (Tata Usaha)</option>
                                            <option value="Guru Piket">Guru Piket</option>
                                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                                            <option value="Admin">Admin (IT)</option>
                                        </select>
                                        <p class="text-[10px] text-slate-400 mt-2 ml-1 italic">
                                            *Tahan tombol CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu role.
                                        </p>
                                    </div>
                                </div>

                                
                                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-dashed border-slate-200">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Password</label>
                                        <input type="password" name="password" required 
                                               class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Konfirmasi</label>
                                        <input type="password" name="password_confirmation" required 
                                               class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-6 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98] mt-4">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2" x-data="{ showImport: false }">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px] relative">
                        
                        
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-blue-900"></i> Daftar Pengguna
                                <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-slate-500 shadow-sm ml-2">
                                    <?php echo e($users->total()); ?>

                                </span>
                            </h3>
                            
                            
                            <div class="flex items-center gap-2">
                                
                                <a href="<?php echo e(route('users.export')); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100 hover:bg-emerald-100 hover:border-emerald-200 transition-all shadow-sm group">
                                    <i class="ph-bold ph-file-xls text-lg group-hover:scale-110 transition-transform"></i>
                                    <span>Export Excel</span>
                                </a>

                                
                                <button @click="showImport = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100 hover:bg-blue-100 hover:border-blue-200 transition-all shadow-sm group">
                                    <i class="ph-bold ph-upload-simple text-lg group-hover:scale-110 transition-transform"></i>
                                    <span>Import</span>
                                </button>
                            </div>
                        </div>

                        
                        <div x-show="showImport" style="display: none;" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 backdrop-blur-none"
                             x-transition:enter-end="opacity-100 backdrop-blur-sm"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 backdrop-blur-sm"
                             x-transition:leave-end="opacity-0 backdrop-blur-none"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
                            
                            <div @click.away="showImport = false" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                                 class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl shadow-blue-900/20 border border-white relative">
                                
                                
                                <button @click="showImport = false" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors">
                                    <i class="ph-bold ph-x"></i>
                                </button>

                                <div class="text-center mb-8">
                                    <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-5 text-blue-600 shadow-inner shadow-blue-100">
                                        <i class="ph-duotone ph-microsoft-excel-logo text-4xl"></i>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800">Import Data User</h3>
                                    <p class="text-slate-500 text-sm font-medium mt-2 leading-relaxed">
                                        Upload file Excel (.xlsx) untuk menambahkan user secara massal ke dalam sistem.
                                    </p>
                                </div>

                                <form action="<?php echo e(route('users.import')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    <?php echo csrf_field(); ?>
                                    
                                    
                                    <div class="relative group cursor-pointer">
                                        <input type="file" name="file" required accept=".xlsx, .xls"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                               onchange="document.getElementById('fileNameDisplay').innerText = this.files[0].name; document.getElementById('fileIcon').classList.add('text-emerald-500'); document.getElementById('fileContainer').classList.add('border-emerald-400', 'bg-emerald-50');">
                                        
                                        <div id="fileContainer" class="border-2 border-dashed border-slate-200 rounded-3xl p-8 text-center group-hover:border-blue-400 group-hover:bg-blue-50/50 transition-all duration-300">
                                            <i id="fileIcon" class="ph-duotone ph-cloud-arrow-up text-4xl text-slate-300 group-hover:text-blue-500 mb-3 transition-colors"></i>
                                            <p class="text-sm font-bold text-slate-600 group-hover:text-blue-600 transition-colors" id="fileNameDisplay">
                                                Klik untuk pilih file Excel
                                            </p>
                                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Maksimal 5MB</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <button type="submit" class="w-full py-4 rounded-2xl bg-blue-900 text-white font-bold shadow-xl shadow-blue-900/20 hover:bg-blue-800 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                                            <i class="ph-bold ph-upload-simple text-lg"></i> Proses Import
                                        </button>
                                        
                                        
                                        <a href="<?php echo e(asset('template/template_users.xlsx')); ?>" class="w-full py-4 rounded-2xl bg-white border border-slate-200 text-slate-500 font-bold hover:bg-slate-50 hover:text-slate-700 transition-colors text-center text-sm flex items-center justify-center gap-2">
                                            <i class="ph-bold ph-download-simple"></i> Download Template
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-5">Identitas</th>
                                        <th class="px-6 py-5">Peran & Jabatan</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="group hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    
                                                    <div class="relative shrink-0">
                                                        <?php if($user->photo_path): ?>
                                                            <img src="<?php echo e(asset('storage/' . $user->photo_path)); ?>" class="w-10 h-10 rounded-2xl object-cover shadow-sm border border-slate-200">
                                                        <?php else: ?>
                                                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-100 to-slate-100 flex items-center justify-center text-blue-600 font-black text-sm border border-white shadow-sm">
                                                                <?php echo e(substr($user->name, 0, 2)); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        
                                                        <?php if($user->instagram || $user->facebook || $user->tiktok): ?>
                                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm" title="Data Sosmed Tersedia">
                                                                <div class="w-2.5 h-2.5 bg-blue-500 rounded-full"></div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors"><?php echo e($user->name); ?></div>
                                                        <div class="text-xs text-slate-400 font-medium"><?php echo e($user->email); ?></div>
                                                        
                                                        <?php if($user->nip): ?>
                                                            <div class="text-[10px] text-slate-500 font-mono mt-0.5 bg-slate-100 inline-block px-1.5 rounded">NIP. <?php echo e($user->nip); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                
                                                <?php
                                                    $userRoles = is_string($user->role) ? json_decode($user->role, true) : $user->role;
                                                    // Jika gagal decode atau format lama (string tunggal), ubah jadi array
                                                    if (!is_array($userRoles)) {
                                                        $userRoles = is_string($user->role) ? explode(',', $user->role) : [$user->role];
                                                    }
                                                ?>

                                                <div class="flex flex-col items-start gap-1.5">
                                                    <div class="flex flex-wrap gap-1 max-w-[200px]">
                                                        <?php $__currentLoopData = $userRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                // Validasi jika json_decode menghasilkan null/error
                                                                if(empty($roleItem)) continue;

                                                                $badgeClass = match($roleItem) {
                                                                    'Admin' => 'bg-rose-50 text-rose-600 border-rose-200',
                                                                    'Kepala Sekolah' => 'bg-purple-50 text-purple-600 border-purple-200',
                                                                    'TU' => 'bg-cyan-50 text-cyan-600 border-cyan-200',
                                                                    'Wali Kelas' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                                    'Guru Mata Pelajaran' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                                                    'Guru Piket' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                                    default => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                                };
                                                            ?>
                                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border <?php echo e($badgeClass); ?>">
                                                                <?php echo e($roleItem); ?>

                                                            </span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>

                                                    
                                                    <?php if($user->position || $user->pangkat): ?>
                                                        <div class="text-xs text-slate-500 font-bold flex flex-col gap-0.5 mt-1">
                                                            <?php if($user->position): ?>
                                                                <span class="flex items-center gap-1"><i class="ph-bold ph-briefcase text-slate-300"></i> <?php echo e($user->position); ?></span>
                                                            <?php endif; ?>
                                                            <?php if($user->pangkat): ?>
                                                                <span class="flex items-center gap-1 text-[10px] text-slate-400 bg-slate-50 px-1.5 rounded w-fit border border-slate-100"><?php echo e($user->pangkat); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                                <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                                    <?php if(Auth::id() != $user->id): ?>
                                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm" title="Edit Data Lengkap">
                                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                        </a>

                                                        
                                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="inline" 
                                                              onsubmit="event.preventDefault(); 
                                                                        const form = this;
                                                                        Swal.fire({
                                                                            title: 'Hapus Pengguna?',
                                                                            text: 'Apakah Anda yakin ingin menghapus pengguna <?php echo e($user->name); ?>? Data yang dihapus tidak dapat dikembalikan.',
                                                                            icon: 'warning',
                                                                            showCancelButton: true,
                                                                            confirmButtonColor: '#e11d48',
                                                                            cancelButtonColor: '#94a3b8',
                                                                            confirmButtonText: 'Ya, Hapus!',
                                                                            cancelButtonText: 'Batal',
                                                                            reverseButtons: true,
                                                                            buttonsStyling: false,
                                                                            customClass: {
                                                                                popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                                                                                confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                                                                                cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                                                            }
                                                                        }).then((result) => {
                                                                            if (result.isConfirmed) {
                                                                                form.submit();
                                                                            }
                                                                        });">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" title="Hapus User">
                                                                <i class="ph-bold ph-trash text-lg"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 text-xs font-bold select-none cursor-not-allowed">
                                                            <i class="ph-bold ph-user"></i> Anda
                                                        </span>
                                                        <a href="<?php echo e(route('profile.edit')); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-blue-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm" title="Edit Profil Saya">
                                                            <i class="ph-bold ph-gear text-lg"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="px-6 py-20 text-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                                    <i class="ph-duotone ph-users-three text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-600">Belum ada data pengguna lain.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        
                        <div class="p-6 border-t border-slate-50 bg-slate-50/30">
                            <?php echo e($users->links()); ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/users/index.blade.php ENDPATH**/ ?>