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

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <?php if($isAdmin): ?>
                <a href="<?php echo e(route('users.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Pengguna
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Dashboard
                </a>
            <?php endif; ?>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill <?php echo e($isOwnProfile ? 'ph-user-circle' : 'ph-user-gear'); ?>"></i>
                    </div>
                    
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                        <div class="shrink-0 relative group">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden border-4 border-white/20 shadow-lg bg-white relative">
                                <?php if($user->photo_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $user->photo_path)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-white text-3xl font-black">
                                        <?php echo e(substr($user->name, 0, 2)); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-center md:text-left">
                            <h2 class="text-2xl font-black tracking-tight"><?php echo e($isOwnProfile ? 'Profil Saya' : $user->name); ?></h2>
                            <p class="text-blue-200 text-sm font-medium mt-1"><?php echo e($user->email); ?></p>
                            
                            
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-3">
                                <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-flex px-3 py-1 rounded-lg bg-white/10 text-white text-[10px] font-black uppercase tracking-wider border border-white/20">
                                        <?php echo e($role->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($user->nip): ?>
                                    <span class="inline-flex px-3 py-1 rounded-lg bg-emerald-500/20 text-emerald-100 text-[10px] font-bold uppercase tracking-wider border border-emerald-400/20">
                                        NIP. <?php echo e($user->nip); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    
                    <?php if(session('success')): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-emerald-100 rounded-full text-emerald-600"><i class="ph-bold ph-check-circle text-xl"></i></div>
                                <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                            </div>
                            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><i class="ph-bold ph-x"></i></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-rose-100 rounded-full text-rose-600"><i class="ph-bold ph-warning-circle text-xl"></i></div>
                                <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                            </div>
                            <button @click="show = false" class="text-rose-400 hover:text-rose-600"><i class="ph-bold ph-x"></i></button>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3">
                            <i class="ph-bold ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                            <div>
                                <h4 class="font-bold text-rose-700 text-sm">Gagal Menyimpan Perubahan</h4>
                                <ul class="list-disc list-inside text-xs text-rose-600 mt-1">
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
                            <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="ph-bold ph-identification-card"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Data Akun & Login</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Email Login</label>
                                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>
                                
                                
                                <?php if($isAdmin): ?>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 ml-1">Peran (Role) - Bisa pilih lebih dari satu</label>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            <?php
                                                $availableRoles = ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket', 'Kepala Sekolah', 'TU', 'Admin'];
                                            ?>

                                            <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="relative flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all hover:bg-blue-50
                                                    <?php echo e($user->hasRole($option) ? 'border-blue-500 bg-blue-50/50' : 'border-slate-100 bg-slate-50'); ?>">
                                                    <input type="checkbox" name="role[]" value="<?php echo e($option); ?>" 
                                                           class="w-5 h-5 rounded-md text-blue-600 border-slate-300 focus:ring-blue-500 mt-0.5"
                                                           <?php echo e($user->hasRole($option) ? 'checked' : ''); ?>>
                                                    <div>
                                                        <span class="block text-sm font-bold <?php echo e($user->hasRole($option) ? 'text-blue-700' : 'text-slate-600'); ?>">
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
                            <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100 mt-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <i class="ph-bold ph-chalkboard-teacher"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Data Kepegawaian</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">NIP</label>
                                    <input type="text" name="nip" value="<?php echo e(old('nip', $user->nip)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pangkat/Golongan</label>
                                    <input type="text" name="pangkat" value="<?php echo e(old('pangkat', $user->pangkat)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jabatan / Mapel</label>
                                    <input type="text" name="position" value="<?php echo e(old('position', $user->position)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Bio Singkat</label>
                                    <textarea name="bio" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-medium text-slate-700 py-3 px-4 focus:ring-emerald-500 focus:border-emerald-500"><?php echo e(old('bio', $user->bio)); ?></textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Foto Profil</label>
                                    <input type="file" name="photo" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-bold">
                                </div>
                            </div>
                        
                            <!-- 2.5 DATA PRIBADI & CV -->
                            <div x-show="isTeacher">
                                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100 mt-6">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                        <i class="ph-bold ph-address-book"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Data Pribadi (Untuk CV)</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $user->tempat_lahir)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $user->tanggal_lahir)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500">
                                            <option value="">Pilih...</option>
                                            <option value="Laki-laki" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Agama</label>
                                        <input type="text" name="agama" value="<?php echo e(old('agama', $user->agama)); ?>" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Status Pernikahan</label>
                                        <input type="text" name="status_pernikahan" value="<?php echo e(old('status_pernikahan', $user->status_pernikahan)); ?>" placeholder="Belum Menikah / Menikah" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Alamat Lengkap</label>
                                        <textarea name="alamat" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-medium text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500"><?php echo e(old('alamat', $user->alamat)); ?></textarea>
                                    </div>
                                    <div class="md:col-span-2 bg-amber-50/50 p-4 rounded-2xl border border-amber-100">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Keahlian (Pisahkan dengan koma)</label>
                                        <input type="text" name="keahlian" value="<?php echo e(old('keahlian', $user->keahlian)); ?>" placeholder="Cth: Desain Grafis, Microsoft Office, Mengajar Matematika" class="w-full rounded-2xl border-slate-200 bg-white font-bold text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500 mb-4">
                                        
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Hobi (Pisahkan dengan koma)</label>
                                        <input type="text" name="hobi" value="<?php echo e(old('hobi', $user->hobi)); ?>" placeholder="Cth: Membaca Buku, Traveling, Menulis" class="w-full rounded-2xl border-slate-200 bg-white font-bold text-slate-700 py-3 px-4 focus:ring-amber-500 focus:border-amber-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. KONTAK -->
                        <div>
                            <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100 mt-6">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                    <i class="ph-bold ph-share-network"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Kontak</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">WhatsApp</label>
                                    <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="w-full rounded-2xl border-slate-200 bg-white font-bold text-slate-700 py-3 px-4">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instagram</label>
                                    <input type="text" name="instagram" value="<?php echo e(old('instagram', $user->instagram)); ?>" class="w-full rounded-2xl border-slate-200 bg-white font-bold text-slate-700 py-3 px-4">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">TikTok</label>
                                    <input type="text" name="tiktok" value="<?php echo e(old('tiktok', $user->tiktok)); ?>" class="w-full rounded-2xl border-slate-200 bg-white font-bold text-slate-700 py-3 px-4">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Facebook</label>
                                    <input type="text" name="facebook" value="<?php echo e(old('facebook', $user->facebook)); ?>" class="w-full rounded-2xl border-slate-200 bg-white font-bold text-slate-700 py-3 px-4">
                                </div>
                            </div>
                        </div>

                        <!-- 4. Password (Optional) -->
                        <div x-data="{ showPassword: false }">
                             <div class="flex items-center justify-between p-4 bg-rose-50 border border-rose-100 rounded-2xl cursor-pointer hover:bg-rose-100 transition-colors" @click="showPassword = !showPassword">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white text-rose-500 flex items-center justify-center shadow-sm">
                                        <i class="ph-bold ph-lock-key"></i>
                                    </div>
                                    <span class="text-sm font-bold text-rose-700">Ganti Password Akun?</span>
                                </div>
                                <i class="ph-bold ph-caret-down text-rose-400 transition-transform duration-300" :class="{'rotate-180': showPassword}"></i>
                            </div>
                            
                            <div x-show="showPassword" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Password Baru</label>
                                    <input type="password" name="password" class="w-full rounded-2xl border-slate-200 bg-white focus:border-rose-500 focus:ring-rose-500 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="w-full rounded-2xl border-slate-200 bg-white focus:border-rose-500 focus:ring-rose-500 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>
                            </div>
                        </div>

                         <!-- 5. KELOLA PORTOFOLIO GURU -->
                        <?php if($user->hasAnyRole(['Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Mata Pelajaran'])): ?>
                        <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-[2rem] border border-blue-100 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-blue-600/30">
                                    <i class="ph-fill ph-medal text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-base font-black text-slate-800">Portofolio & Karya Guru</h4>
                                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                                        <?php if($isOwnProfile): ?>
                                            Kelola riwayat pendidikan, pelatihan, materi ajar, dan galeri prestasi milik <b>Anda sendiri</b>.
                                        <?php else: ?>
                                            Kelola riwayat pendidikan, pelatihan, materi ajar, dan galeri prestasi milik <b class="text-blue-700"><?php echo e($user->name); ?></b>.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <a href="<?php echo e(route('portfolio.index', $isOwnProfile ? [] : ['user_id' => $user->id])); ?>" class="shrink-0 w-full md:w-auto px-6 py-3.5 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2 group">
                                Buka Panel Portofolio <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                            <?php if($isAdmin): ?>
                                <a href="<?php echo e(route('users.index')); ?>" class="px-6 py-3.5 rounded-2xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 text-sm">Batal</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('dashboard')); ?>" class="px-6 py-3.5 rounded-2xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 text-sm">Batal</a>
                            <?php endif; ?>
                            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-blue-900 text-white font-bold hover:bg-blue-800 shadow-lg shadow-blue-900/20 text-sm flex items-center gap-2">
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/users/edit.blade.php ENDPATH**/ ?>