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
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <a href="<?php echo e(route('users.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-user-gear"></i>
                    </div>
                    
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                        <div class="shrink-0">
                            <?php if($user->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $user->photo_path)); ?>" class="w-20 h-20 rounded-2xl object-cover border-4 border-white/20 shadow-lg">
                            <?php else: ?>
                                <div class="w-20 h-20 rounded-2xl bg-white/10 flex items-center justify-center text-white text-3xl font-black border-4 border-white/20 shadow-lg backdrop-blur-sm">
                                    <?php echo e(substr($user->name, 0, 2)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-center md:text-left">
                            <h2 class="text-2xl font-black tracking-tight"><?php echo e($user->name); ?></h2>
                            <p class="text-blue-200 text-sm font-medium mt-1"><?php echo e($user->email); ?></p>
                            <span class="inline-flex mt-3 px-3 py-1 rounded-lg bg-white/10 text-white text-[10px] font-black uppercase tracking-wider border border-white/20">
                                <?php echo e($user->role); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <form action="<?php echo e(route('users.update', $user->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ role: '<?php echo e($user->role); ?>' }">
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
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Peran (Role)</label>
                                    <div class="relative">
                                        <select name="role" x-model="role" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors appearance-none cursor-pointer">
                                            <option value="Guru">Guru</option>
                                            <option value="Wali Kelas">Wali Kelas</option>
                                            <option value="Guru Piket">Guru Piket</option>
                                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                                            <option value="Admin">Admin (IT)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. DATA PROFIL GURU (Hanya jika bukan admin) -->
                        <div x-show="role !== 'Admin'" x-transition>
                            <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <i class="ph-bold ph-chalkboard-teacher"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Data Profil Guru</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jabatan / Mapel</label>
                                    <input type="text" name="position" value="<?php echo e(old('position', $user->position)); ?>" placeholder="Contoh: Guru Matematika"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">NIP (Nomor Induk)</label>
                                    <input type="text" name="nip" value="<?php echo e(old('nip', $user->nip ?? '')); ?>" placeholder="Contoh: 19800101..."
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Bio Singkat</label>
                                    <textarea name="bio" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-medium text-slate-700 py-3 px-4 transition-colors placeholder:font-normal" placeholder="Tulis motto atau deskripsi singkat..."><?php echo e(old('bio', $user->bio)); ?></textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Update Foto</label>
                                    <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-dashed border-slate-300 rounded-2xl py-3 px-4 cursor-pointer bg-slate-50">
                                </div>
                            </div>
                        </div>

                        <!-- 3. KONTAK & MEDIA SOSIAL -->
                        <div x-show="role !== 'Admin'" x-transition>
                            <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                    <i class="ph-bold ph-share-network"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Kontak & Media Sosial</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-200">
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1 flex items-center gap-1">
                                        <i class="ph-fill ph-whatsapp-logo text-emerald-500 text-lg"></i> WhatsApp / HP
                                    </label>
                                    <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone ?? '')); ?>" placeholder="08xxxxxx"
                                           class="w-full rounded-2xl border-slate-200 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1 flex items-center gap-1">
                                        <i class="ph-fill ph-instagram-logo text-pink-500 text-lg"></i> Instagram (Username)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm font-bold">@</span>
                                        <input type="text" name="instagram" value="<?php echo e(old('instagram', $user->instagram ?? '')); ?>" placeholder="username"
                                               class="w-full rounded-2xl border-slate-200 pl-9 focus:bg-white focus:border-pink-500 focus:ring-pink-500 font-bold text-slate-700 py-3 px-4 transition-colors">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1 flex items-center gap-1">
                                        <i class="ph-fill ph-tiktok-logo text-black text-lg"></i> TikTok (Username)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm font-bold">@</span>
                                        <input type="text" name="tiktok" value="<?php echo e(old('tiktok', $user->tiktok ?? '')); ?>" placeholder="username"
                                               class="w-full rounded-2xl border-slate-200 pl-9 focus:bg-white focus:border-slate-800 focus:ring-slate-800 font-bold text-slate-700 py-3 px-4 transition-colors">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1 flex items-center gap-1">
                                        <i class="ph-fill ph-facebook-logo text-blue-600 text-lg"></i> Facebook (Nama)
                                    </label>
                                    <input type="text" name="facebook" value="<?php echo e(old('facebook', $user->facebook ?? '')); ?>" placeholder="Nama Profil"
                                           class="w-full rounded-2xl border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 px-4 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- 4. GANTI PASSWORD -->
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
                            
                            <div x-show="showPassword" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-200">
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

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                            <a href="<?php echo e(route('users.index')); ?>" class="px-6 py-3.5 rounded-2xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors text-sm">Batal</a>
                            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-blue-900 text-white font-bold hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/20 text-sm flex items-center gap-2 transform active:scale-[0.98]">
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\users\edit.blade.php ENDPATH**/ ?>