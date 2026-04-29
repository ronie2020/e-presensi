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
    <?php
        // Cek apakah user yang login adalah Admin
        $isAdmin = auth()->user()->hasRole('Admin');
        // Cek apakah user yang login sedang mengedit profilnya sendiri
        $isOwnProfile = auth()->id() == $user->id;
    ?>

    
    <style>
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.05), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.08), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.05); transform: translateY(-2px); }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-elevate-surface min-h-screen relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <?php if($isAdmin): ?>
                <a href="<?php echo e(route('users.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:bg-elevate-peach-light group-hover:border-elevate-peach/50 transition-all">
                        <i class="ph-bold ph-arrow-left group-hover:-translate-x-0.5 transition-transform"></i>
                    </div>
                    Kembali ke Daftar Pengguna
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:bg-elevate-peach-light group-hover:border-elevate-peach/50 transition-all">
                        <i class="ph-bold ph-arrow-left group-hover:-translate-x-0.5 transition-transform"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            <?php endif; ?>

            
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 text-elevate-dark relative overflow-hidden border-b border-white/60">
                    <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                    <div class="absolute -right-6 -top-6 text-white/40 text-9xl pointer-events-none">
                        <i class="ph-fill <?php echo e($isOwnProfile ? 'ph-user-circle' : 'ph-user-gear'); ?>"></i>
                    </div>
                    
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                        <div class="shrink-0 relative group">
                            <div class="w-24 h-24 rounded-[1.5rem] overflow-hidden border-4 border-white/60 shadow-lg bg-white relative">
                                <?php if($user->photo_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $user->photo_path)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-elevate-dark flex items-center justify-center text-white text-3xl font-black">
                                        <?php echo e(substr($user->name, 0, 2)); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-center md:text-left">
                            <h2 class="text-2xl md:text-3xl font-black tracking-tight text-elevate-dark"><?php echo e($isOwnProfile ? 'Profil Saya' : $user->name); ?></h2>
                            <p class="text-elevate-dark/80 text-sm font-bold mt-1"><?php echo e($user->email); ?></p>
                            
                            
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-3">
                                <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-flex px-3 py-1.5 rounded-lg bg-white/40 text-elevate-dark text-[10px] font-black uppercase tracking-wider border border-white/50 backdrop-blur-sm shadow-sm">
                                        <?php echo e($role->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($user->nip): ?>
                                    <span class="inline-flex px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-200 backdrop-blur-sm shadow-sm">
                                        NIP. <?php echo e($user->nip); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-10">
                    
                    <?php if(session('success')): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded-xl text-emerald-600 shadow-sm border border-emerald-100"><i class="ph-bold ph-check-circle text-xl"></i></div>
                                <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                            </div>
                            <button @click="show = false" class="text-emerald-600 hover:bg-emerald-100 p-2 rounded-xl transition-colors"><i class="ph-bold ph-x"></i></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded-xl text-rose-600 shadow-sm border border-rose-100"><i class="ph-bold ph-warning-circle text-xl"></i></div>
                                <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                            </div>
                            <button @click="show = false" class="text-rose-600 hover:bg-rose-100 p-2 rounded-xl transition-colors"><i class="ph-bold ph-x"></i></button>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-4 shadow-sm">
                            <div class="p-2 bg-white rounded-xl text-rose-600 shrink-0 border border-rose-100 mt-0.5">
                                <i class="ph-bold ph-warning-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-rose-700 text-sm mb-1.5">Gagal Menyimpan Perubahan</h4>
                                <ul class="list-disc list-inside text-xs font-bold text-rose-600 space-y-1">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('users.update', $user->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-10">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- 1. DATA AKUN UTAMA -->
                        <div>
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-elevate-peach-light text-elevate-primary border border-elevate-peach/50 flex items-center justify-center text-xl shadow-sm">
                                    <i class="ph-bold ph-identification-card"></i>
                                </div>
                                <h4 class="text-sm font-black text-elevate-dark uppercase tracking-wider">Data Akun & Login</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Email Login</label>
                                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                </div>
                                
                                
                                <?php if($isAdmin): ?>
                                    <div class="md:col-span-2 mt-2">
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-3 ml-1">Peran (Role) - Bisa pilih lebih dari satu</label>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                            <?php
                                                $availableRoles = ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket', 'Kepala Sekolah', 'TU', 'Admin'];
                                            ?>

                                            <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all hover:bg-elevate-peach-light/50
                                                    <?php echo e($user->hasRole($option) ? 'border-elevate-primary bg-elevate-primary/5 shadow-sm' : 'border-slate-200 bg-slate-50'); ?>">
                                                    <input type="checkbox" name="role[]" value="<?php echo e($option); ?>" 
                                                           class="w-5 h-5 rounded-lg text-elevate-primary border-slate-300 focus:ring-elevate-primary"
                                                           <?php echo e($user->hasRole($option) ? 'checked' : ''); ?>>
                                                    <div>
                                                        <span class="block text-xs font-bold <?php echo e($user->hasRole($option) ? 'text-elevate-primary' : 'text-slate-600'); ?>">
                                                            <?php echo e($option); ?>

                                                        </span>
                                                    </div>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs mt-2 font-bold"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- 2. DATA PROFIL GURU -->
                        <div x-data="{ isTeacher: <?php echo e($user->hasAnyRole(['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Kepala Sekolah']) ? 'true' : 'false'); ?> }">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-elevate-peach-light text-elevate-primary border border-elevate-peach/50 flex items-center justify-center text-xl shadow-sm">
                                    <i class="ph-bold ph-chalkboard-teacher"></i>
                                </div>
                                <h4 class="text-sm font-black text-elevate-dark uppercase tracking-wider">Data Kepegawaian</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">NIP (Opsional)</label>
                                    <input type="text" name="nip" value="<?php echo e(old('nip', $user->nip)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Pangkat/Golongan</label>
                                    <input type="text" name="pangkat" value="<?php echo e(old('pangkat', $user->pangkat)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Jabatan / Mapel Utama</label>
                                    <input type="text" name="position" value="<?php echo e(old('position', $user->position)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Bio Singkat (Slogan)</label>
                                    <textarea name="bio" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-medium text-elevate-dark py-3.5 px-4 transition-colors"><?php echo e(old('bio', $user->bio)); ?></textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Foto Profil Baru</label>
                                    <input type="file" name="photo" class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-elevate-peach-light file:text-elevate-primary hover:file:bg-elevate-peach/50 transition-all border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white p-2">
                                </div>
                            </div>
                        
                            <!-- 2.5 DATA PRIBADI & CV -->
                            <div x-show="isTeacher" style="display: none;">
                                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100 mt-10">
                                    <div class="w-10 h-10 rounded-xl bg-elevate-peach-light text-elevate-primary border border-elevate-peach/50 flex items-center justify-center text-xl shadow-sm">
                                        <i class="ph-bold ph-address-book"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-elevate-dark uppercase tracking-wider">Data Pribadi (Untuk CV)</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $user->tempat_lahir)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $user->tanggal_lahir)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Jenis Kelamin</label>
                                        <div class="relative">
                                            <select name="jenis_kelamin" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 pl-4 pr-10 appearance-none cursor-pointer transition-colors">
                                                <option value="">Pilih...</option>
                                                <option value="Laki-laki" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                                <option value="Perempuan" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Agama</label>
                                        <input type="text" name="agama" value="<?php echo e(old('agama', $user->agama)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Status Pernikahan</label>
                                        <input type="text" name="status_pernikahan" value="<?php echo e(old('status_pernikahan', $user->status_pernikahan)); ?>" placeholder="Belum / Sudah Menikah" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Alamat Lengkap</label>
                                        <textarea name="alamat" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-medium text-elevate-dark py-3.5 px-4 transition-colors"><?php echo e(old('alamat', $user->alamat)); ?></textarea>
                                    </div>
                                    
                                    
                                    <div class="md:col-span-2 bg-elevate-peach-light/30 p-6 rounded-2xl border border-elevate-peach/30 mt-2">
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Keahlian (Pisahkan dengan koma)</label>
                                        <input type="text" name="keahlian" value="<?php echo e(old('keahlian', $user->keahlian)); ?>" placeholder="Cth: Desain Grafis, Microsoft Office, Mengajar Matematika" class="w-full rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors mb-5 shadow-sm">
                                        
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Hobi (Pisahkan dengan koma)</label>
                                        <input type="text" name="hobi" value="<?php echo e(old('hobi', $user->hobi)); ?>" placeholder="Cth: Membaca Buku, Traveling, Menulis" class="w-full rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. KONTAK -->
                        <div>
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100 mt-10">
                                <div class="w-10 h-10 rounded-xl bg-elevate-peach-light text-elevate-primary border border-elevate-peach/50 flex items-center justify-center text-xl shadow-sm">
                                    <i class="ph-bold ph-share-network"></i>
                                </div>
                                <h4 class="text-sm font-black text-elevate-dark uppercase tracking-wider">Sosial Media & Kontak</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Nomor WhatsApp</label>
                                    <div class="relative">
                                        <i class="ph-fill ph-whatsapp-logo absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500 text-lg pointer-events-none"></i>
                                        <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="w-full pl-11 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 transition-colors shadow-sm" placeholder="081234567890">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instagram Link</label>
                                    <div class="relative">
                                        <i class="ph-fill ph-instagram-logo absolute left-4 top-1/2 -translate-y-1/2 text-rose-500 text-lg pointer-events-none"></i>
                                        <input type="text" name="instagram" value="<?php echo e(old('instagram', $user->instagram)); ?>" class="w-full pl-11 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 transition-colors shadow-sm" placeholder="https://instagram.com/username">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">TikTok Link</label>
                                    <div class="relative">
                                        <i class="ph-fill ph-tiktok-logo absolute left-4 top-1/2 -translate-y-1/2 text-slate-800 text-lg pointer-events-none"></i>
                                        <input type="text" name="tiktok" value="<?php echo e(old('tiktok', $user->tiktok)); ?>" class="w-full pl-11 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 transition-colors shadow-sm" placeholder="https://tiktok.com/@username">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Facebook Link</label>
                                    <div class="relative">
                                        <i class="ph-fill ph-facebook-logo absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 text-lg pointer-events-none"></i>
                                        <input type="text" name="facebook" value="<?php echo e(old('facebook', $user->facebook)); ?>" class="w-full pl-11 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 transition-colors shadow-sm" placeholder="https://facebook.com/username">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Password (Optional) -->
                        <div x-data="{ showPassword: false }" class="mt-8">
                             <div class="flex items-center justify-between p-5 bg-rose-50 border border-rose-200 rounded-2xl cursor-pointer hover:bg-rose-100 transition-colors shadow-sm group" @click="showPassword = !showPassword">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white text-rose-600 flex items-center justify-center shadow-sm border border-rose-100">
                                        <i class="ph-bold ph-lock-key text-lg"></i>
                                    </div>
                                    <span class="text-sm font-black text-rose-700">Ganti Password Akun?</span>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-rose-500 shadow-sm">
                                    <i class="ph-bold ph-caret-down transition-transform duration-300" :class="{'rotate-180': showPassword}"></i>
                                </div>
                            </div>
                            
                            <div x-show="showPassword" style="display: none;" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-200">
                                <div>
                                    <label class="block text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2 ml-1">Password Baru</label>
                                    <input type="password" name="password" class="w-full rounded-2xl border-slate-200 bg-white focus:border-rose-500 focus:ring-rose-500/30 font-bold text-slate-700 py-3.5 px-4 transition-colors shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2 ml-1">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="w-full rounded-2xl border-slate-200 bg-white focus:border-rose-500 focus:ring-rose-500/30 font-bold text-slate-700 py-3.5 px-4 transition-colors shadow-sm">
                                </div>
                            </div>
                        </div>

                         <!-- 5. KELOLA PORTOFOLIO GURU -->
                        <?php if($user->hasAnyRole(['Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Mata Pelajaran'])): ?>
                        <div class="mt-10 bg-elevate-peach-light/30 p-6 md:p-8 rounded-[2rem] border border-elevate-peach/50 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 bg-elevate-primary text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-elevate-primary/30">
                                    <i class="ph-fill ph-medal text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-elevate-dark">Portofolio & Karya Guru</h4>
                                    <p class="text-sm text-elevate-text/70 mt-1.5 font-medium leading-relaxed max-w-lg">
                                        <?php if($isOwnProfile): ?>
                                            Kelola riwayat pendidikan, pelatihan, materi ajar, dan galeri prestasi milik <b>Anda sendiri</b>.
                                        <?php else: ?>
                                            Kelola riwayat pendidikan, pelatihan, materi ajar, dan galeri prestasi milik <b class="text-elevate-primary"><?php echo e($user->name); ?></b>.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <a href="<?php echo e(route('portfolio.index', $isOwnProfile ? [] : ['user_id' => $user->id])); ?>" class="shrink-0 w-full md:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all flex items-center justify-center gap-2 group active:scale-95 text-sm">
                                Buka Panel Portofolio <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Buttons Actions -->
                        <div class="flex justify-end gap-3 pt-8 border-t border-slate-100">
                            <?php if($isAdmin): ?>
                                <a href="<?php echo e(route('users.index')); ?>" class="px-6 py-3.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors text-sm flex items-center justify-center">Batal</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('dashboard')); ?>" class="px-6 py-3.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors text-sm flex items-center justify-center">Batal</a>
                            <?php endif; ?>
                            
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-elevate-dark text-white font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 text-sm flex items-center gap-2 active:scale-95 transition-all">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/users/edit.blade.php ENDPATH**/ ?>