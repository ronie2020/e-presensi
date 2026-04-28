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
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.05), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.15), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-elevate-surface min-h-screen relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-10 pointer-events-none -z-10 blur-3xl"></div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl group-hover:scale-105 transition-transform duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/40 border border-white/50 text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-users-three text-elevate-primary"></i> Akses & Keamanan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Manajemen Pengguna
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-bold leading-relaxed max-w-lg">
                            Kelola akun akses untuk Admin, Kepala Sekolah, TU, Guru, dan Staf lainnya secara terpusat.
                        </p>
                    </div>
                    
                    
                    <div class="flex gap-4 w-full md:w-auto">
                        <div class="bg-white/40 backdrop-blur-md px-6 py-5 rounded-[1.5rem] border border-white/50 flex-1 md:flex-none min-w-[150px] text-center md:text-left hover:bg-white/60 transition-colors shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-user-circle text-2xl"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider text-elevate-dark/70">Total Akun</span>
                            </div>
                            <span class="block text-4xl font-black text-elevate-dark tracking-tight mt-1"><?php echo e($users->total()); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center justify-between fluent-card">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-xl text-emerald-600 shadow-sm border border-emerald-100">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:bg-emerald-100 p-2 rounded-xl transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            
            <?php if(session('error')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center justify-between fluent-card">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-xl text-rose-600 shadow-sm border border-rose-100">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                    </div>
                    <button @click="show = false" class="text-rose-600 hover:bg-rose-100 p-2 rounded-xl transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-start gap-4 fluent-card">
                    <div class="p-2 bg-white rounded-xl text-rose-600 shrink-0 shadow-sm border border-rose-100 mt-0.5">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-black text-sm mb-1.5">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-bold space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group transition-all duration-300">
                        
                        
                        <div class="bg-gradient-to-r from-elevate-primary to-elevate-accent p-8 text-white relative overflow-hidden border-b border-white/20">
                            <div class="absolute -right-4 -bottom-4 text-white/20 text-8xl pointer-events-none group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-user-plus"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">User Baru</h3>
                            <p class="text-white/80 text-xs font-medium relative z-10 mt-1">Registrasi akun cepat.</p>
                        </div>

                        <div class="p-6 md:p-8 relative z-10">
                            <form action="<?php echo e(route('users.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                                <?php echo csrf_field(); ?>
                                
                                
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Contoh: Budi Santoso, S.Pd."
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors placeholder:font-medium">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Email Login</label>
                                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="email@sekolah.sch.id"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors placeholder:font-medium">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Peran (Role)</label>
                                    <div class="relative">
                                        <select name="role[]" required multiple class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-colors appearance-none cursor-pointer h-32 custom-scrollbar">
                                            <option value="Guru">Guru (Umum)</option>
                                            <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                                            <option value="Wali Kelas">Wali Kelas</option>
                                            <option value="TU">TU (Tata Usaha)</option>
                                            <option value="Guru Piket">Guru Piket</option>
                                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                                            <option value="Admin">Admin (IT)</option>
                                        </select>
                                        <p class="text-[10px] text-slate-400 mt-2 ml-1 font-medium">
                                            *Tahan tombol <kbd class="bg-slate-100 px-1 rounded border border-slate-200">CTRL</kbd> atau <kbd class="bg-slate-100 px-1 rounded border border-slate-200">CMD</kbd> untuk memilih > 1 role.
                                        </p>
                                    </div>
                                </div>

                                
                                <div class="grid grid-cols-2 gap-4 pt-5 border-t border-dashed border-slate-200">
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Password</label>
                                        <input type="password" name="password" required 
                                               class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Konfirmasi</label>
                                        <input type="password" name="password_confirmation" required 
                                               class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-4 px-6 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2 transform active:scale-95 mt-4">
                                    <i class="ph-bold ph-user-plus text-lg"></i>
                                    Daftarkan Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2" x-data="{ showImport: false }">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px] relative">
                        
                        
                        <div class="p-6 md:p-8 border-b border-slate-50 bg-elevate-peach-light/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <h3 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center border border-elevate-peach/50 shadow-sm">
                                    <i class="ph-bold ph-users text-xl"></i>
                                </div>
                                Daftar Pengguna
                                <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-full text-elevate-primary shadow-sm ml-1">
                                    <?php echo e($users->total()); ?>

                                </span>
                            </h3>
                            
                            
                            <div class="flex items-center gap-3">
                                
                                <a href="<?php echo e(route('users.export')); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-colors shadow-sm group">
                                    <i class="ph-bold ph-file-xls text-lg group-hover:-translate-y-0.5 transition-transform"></i>
                                    <span class="hidden sm:inline">Export</span>
                                </a>

                                
                                <button @click="showImport = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-elevate-peach-light text-elevate-primary text-xs font-bold border border-elevate-peach/50 hover:bg-elevate-primary hover:text-white transition-colors shadow-sm group">
                                    <i class="ph-bold ph-upload-simple text-lg group-hover:-translate-y-0.5 transition-transform"></i>
                                    <span class="hidden sm:inline">Import</span>
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
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-elevate-dark/70 backdrop-blur-sm">
                            
                            <div @click.away="showImport = false" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                                 class="bg-white w-full max-w-md p-8 rounded-[2rem] shadow-2xl shadow-elevate-dark/30 relative border border-slate-100">
                                
                                
                                <button @click="showImport = false" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                    <i class="ph-bold ph-x"></i>
                                </button>

                                <div class="text-center mb-8 mt-2">
                                    <div class="w-20 h-20 bg-elevate-peach-light rounded-[1.5rem] flex items-center justify-center mx-auto mb-5 text-elevate-primary border border-elevate-peach/50">
                                        <i class="ph-duotone ph-file-xls text-4xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-elevate-dark">Import Data User</h3>
                                    <p class="text-slate-500 text-sm font-medium mt-2 leading-relaxed px-4">
                                        Upload file Excel (.xlsx) untuk menambahkan user secara massal ke dalam sistem.
                                    </p>
                                </div>

                                <form action="<?php echo e(route('users.import')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    <?php echo csrf_field(); ?>
                                    
                                    
                                    <div class="relative group cursor-pointer">
                                        <input type="file" name="file" required accept=".xlsx, .xls"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                               onchange="document.getElementById('fileNameDisplay').innerText = this.files[0].name; document.getElementById('fileIcon').className='ph-duotone ph-check-circle text-4xl text-emerald-500 mb-3'; document.getElementById('fileContainer').classList.add('border-emerald-200', 'bg-emerald-50');">
                                        
                                        <div id="fileContainer" class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center group-hover:border-elevate-accent group-hover:bg-elevate-accent/5 transition-all duration-300">
                                            <i id="fileIcon" class="ph-duotone ph-cloud-arrow-up text-4xl text-slate-300 group-hover:text-elevate-primary mb-3 transition-colors"></i>
                                            <p class="text-sm font-bold text-slate-600 group-hover:text-elevate-dark transition-colors" id="fileNameDisplay">
                                                Klik atau seret file Excel
                                            </p>
                                            <p class="text-[10px] font-black text-elevate-primary mt-2 uppercase tracking-wider bg-elevate-peach-light px-2 py-1 rounded inline-block border border-elevate-peach/30">Maks. 5MB</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <button type="submit" class="w-full py-4 rounded-xl bg-elevate-dark text-white font-bold shadow-lg shadow-elevate-dark/20 hover:bg-elevate-primary transition-all transform active:scale-95 flex items-center justify-center gap-2 border border-transparent">
                                            <i class="ph-bold ph-upload-simple text-lg"></i> Proses Import File
                                        </button>
                                        
                                        
                                        <a href="<?php echo e(asset('template/template_users.xlsx')); ?>" class="w-full py-3.5 rounded-xl bg-white border border-slate-200 text-slate-500 font-bold hover:bg-elevate-peach-light hover:text-elevate-primary hover:border-elevate-peach/50 transition-colors text-center text-sm flex items-center justify-center gap-2">
                                            <i class="ph-bold ph-download-simple"></i> Download Template Excel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-[10px] font-black text-elevate-primary uppercase tracking-widest border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-5">Identitas Diri</th>
                                        <th class="px-6 py-5">Peran & Jabatan</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="group hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    
                                                    <div class="relative shrink-0">
                                                        <?php if($user->photo_path): ?>
                                                            <img src="<?php echo e(asset('storage/' . $user->photo_path)); ?>" class="w-12 h-12 rounded-[1rem] object-cover shadow-sm border border-slate-200">
                                                        <?php else: ?>
                                                            <div class="w-12 h-12 rounded-[1rem] bg-elevate-peach-light flex items-center justify-center text-elevate-primary font-black text-base border border-elevate-peach/50 shadow-sm">
                                                                <?php echo e(substr($user->name, 0, 2)); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        
                                                        <?php if($user->instagram || $user->facebook || $user->tiktok): ?>
                                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100" title="Data Sosmed Tersedia">
                                                                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors"><?php echo e($user->name); ?></div>
                                                        <div class="text-xs text-elevate-text/60 font-medium"><?php echo e($user->email); ?></div>
                                                        
                                                        <?php if($user->nip): ?>
                                                            <div class="text-[10px] text-elevate-text/50 font-mono mt-1 font-bold">NIP. <?php echo e($user->nip); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex flex-col items-start gap-2">
                                                    <div class="flex flex-wrap gap-1.5 max-w-[220px]">
                                                        <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $badgeClass = match($roleItem->name) {
                                                                    'Admin' => 'bg-rose-50 text-rose-600 border-rose-200',
                                                                    'Kepala Sekolah' => 'bg-elevate-primary/10 text-elevate-primary border-elevate-primary/30',
                                                                    'TU' => 'bg-slate-100 text-slate-600 border-slate-200',
                                                                    'Wali Kelas' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                                    'Guru Mata Pelajaran' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                                    'Guru Piket' => 'bg-elevate-accent/10 text-elevate-dark border-elevate-accent/30',
                                                                    default => 'bg-slate-50 text-slate-500 border-slate-200',
                                                                };
                                                            ?>
                                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border shadow-sm <?php echo e($badgeClass); ?>">
                                                                <?php echo e($roleItem->name); ?>

                                                            </span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>

                                                    
                                                    <?php if($user->position || $user->pangkat): ?>
                                                        <div class="text-[11px] text-elevate-text/70 font-bold flex flex-col gap-1 mt-1">
                                                            <?php if($user->position): ?>
                                                                <span class="flex items-center gap-1.5"><i class="ph-fill ph-briefcase text-elevate-primary"></i> <?php echo e($user->position); ?></span>
                                                            <?php endif; ?>
                                                            <?php if($user->pangkat): ?>
                                                                <span class="flex items-center gap-1 text-[10px] text-elevate-text/50 bg-slate-100 px-2 py-0.5 rounded-md w-fit border border-slate-200"><?php echo e($user->pangkat); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                                <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                                    <?php if(Auth::id() != $user->id): ?>
                                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-white hover:bg-elevate-primary hover:border-elevate-primary transition-all shadow-sm" title="Edit Data Lengkap">
                                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                        </a>

                                                        
                                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="inline" 
                                                              onsubmit="event.preventDefault(); 
                                                                        const form = this;
                                                                        Swal.fire({
                                                                            title: 'Hapus Pengguna?',
                                                                            text: 'Apakah Anda yakin ingin menghapus pengguna <?php echo e($user->name); ?>? Data tidak dapat dikembalikan.',
                                                                            icon: 'warning',
                                                                            showCancelButton: true,
                                                                            confirmButtonColor: '#e11d48',
                                                                            cancelButtonColor: '#94a3b8',
                                                                            confirmButtonText: 'Ya, Hapus!',
                                                                            cancelButtonText: 'Batal',
                                                                            reverseButtons: true,
                                                                            buttonsStyling: false,
                                                                            customClass: {
                                                                                popup: 'rounded-[2rem] border border-slate-100 shadow-2xl font-sans',
                                                                                confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg border border-transparent',
                                                                                cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2 border border-transparent'
                                                                            }
                                                                        }).then((result) => {
                                                                            if (result.isConfirmed) {
                                                                                form.submit();
                                                                            }
                                                                        });">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-white hover:border-rose-500 hover:bg-rose-500 transition-all shadow-sm" title="Hapus User">
                                                                <i class="ph-bold ph-trash text-lg"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-wider select-none cursor-not-allowed">
                                                            <i class="ph-bold ph-user"></i> Anda
                                                        </span>
                                                        <a href="<?php echo e(route('profile.edit')); ?>" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-elevate-primary hover:text-white hover:border-elevate-primary hover:bg-elevate-primary transition-all shadow-sm" title="Edit Profil Saya">
                                                            <i class="ph-bold ph-gear text-lg"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="px-6 py-24 text-center">
                                                <div class="w-20 h-20 bg-elevate-peach-light rounded-2xl flex items-center justify-center mx-auto mb-4 text-elevate-primary border border-elevate-peach/30">
                                                    <i class="ph-duotone ph-users-three text-5xl"></i>
                                                </div>
                                                <p class="text-base font-black text-elevate-dark">Belum ada data pengguna lain.</p>
                                                <p class="text-xs font-medium text-slate-400 mt-1">Tambahkan akun melalui form di samping.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        
                        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                            <?php echo e($users->links()); ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    <?php if($errors->has('file')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let alpineComponent = document.querySelector('[x-data="{ showImport: false }"]');
            if(alpineComponent) {
                alpineComponent.__x.$data.showImport = true;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal Upload',
                text: <?php echo json_encode($errors->first('file')); ?>,
                customClass: { popup: 'rounded-[2rem] border border-slate-100 font-sans shadow-2xl' }
            });
        });
    </script>
    <?php endif; ?>
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