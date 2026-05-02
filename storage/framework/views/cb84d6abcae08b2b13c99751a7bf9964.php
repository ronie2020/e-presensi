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
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <a href="<?php echo e(route('letters.spt.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative"
                 x-data="{ 
                    followers: <?php echo e(old('pengikut') ? Js::from(old('pengikut')) : '[]'); ?>,
                    users: <?php echo e(Js::from($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'nip' => $u->nip]))); ?>,
                    addFollower() {
                        this.followers.push({ nama: '', nip: '', keterangan: '' });
                    },
                    removeFollower(index) {
                        this.followers.splice(index, 1);
                    },
                    fillFollowerName(index, event) {
                        const selectedId = event.target.value;
                        const user = this.users.find(u => u.id == selectedId);
                        if (user) {
                            this.followers[index].nama = user.name;
                            this.followers[index].nip = user.nip;
                        } else {
                            this.followers[index].nama = '';
                            this.followers[index].nip = '';
                        }
                    }
                 }">
                
                
                <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-paper-plane-tilt text-elevate-accent"></i> Buat Surat Perintah Tugas
                    </h2>
                    <p class="text-elevate-accent text-sm font-medium relative z-10 mt-1">Isi formulir penugasan dinas pegawai.</p>
                </div>

                
                <div class="p-8">
                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold block mb-1">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside font-medium">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('letters.spt.store')); ?>" method="POST" class="space-y-8">
                        <?php echo csrf_field(); ?>

                        <!-- SECTION 1: DASAR & PEGAWAI -->
                        <div class="p-6 bg-elevate-accent/5 rounded-[2rem] border border-elevate-accent/20 relative group hover:border-elevate-accent/40 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Dasar Penugasan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPT</label>
                                    <input type="text" name="nomor_spt" value="<?php echo e(old('nomor_spt', $nomor_otomatis)); ?>" class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-mono font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Dasar Surat / Regulasi (Opsional)</label>
                                    <input type="text" name="dasar" value="<?php echo e(old('dasar')); ?>" placeholder="Surat Undangan No..." class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pegawai Yang Ditugaskan <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="pejabat_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark appearance-none transition-all cursor-pointer">
                                            <option value="">-- Pilih Pegawai --</option>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>" <?php echo e(old('pejabat_id') == $user->id ? 'selected' : ''); ?>>
                                                    <?php echo e($user->name); ?> (NIP. <?php echo e($user->nip ?? '-'); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL TUGAS -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Detail Kegiatan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Maksud Perjalanan / Perihal <span class="text-rose-500">*</span></label>
                                    <textarea name="perihal" rows="2" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-medium text-elevate-dark transition-all"><?php echo e(old('perihal')); ?></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan <span class="text-rose-500">*</span></label>
                                    <input type="text" name="tempat_tujuan" value="<?php echo e(old('tempat_tujuan')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Lama Hari <span class="text-rose-500">*</span></label>
                                    <input type="number" name="lama_hari" value="<?php echo e(old('lama_hari', 1)); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Berangkat <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tgl_berangkat" value="<?php echo e(old('tgl_berangkat')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Kembali <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tgl_kembali" value="<?php echo e(old('tgl_kembali')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: PENGIKUT -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider flex items-center gap-2">
                                    <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                    Peserta Tambahan (Pengikut)
                                </h3>
                                <button type="button" @click="addFollower()" class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors flex items-center gap-2 shadow-sm">
                                    <i class="ph-bold ph-plus"></i> Tambah
                                </button>
                            </div>

                            <div class="space-y-3 pl-9">
                                <template x-for="(follower, index) in followers" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-end bg-white p-4 rounded-2xl border border-slate-200 relative group/follower transition-all hover:border-elevate-accent/50 shadow-sm">
                                        <div class="flex-1 w-full">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama (Pilih/Ketik)</label>
                                            <select class="block w-full text-xs border-slate-300 rounded-lg mb-2 focus:border-elevate-primary focus:ring-elevate-primary bg-slate-50 cursor-pointer" @change="fillFollowerName(index, $event)">
                                                <option value="">-- Auto-fill (Opsional) --</option>
                                                <template x-for="u in users" :key="u.id">
                                                    <option :value="u.id" x-text="u.name"></option>
                                                </template>
                                            </select>
                                            <input type="text" :name="'pengikut['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm font-bold border-slate-300 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-elevate-dark" required>
                                        </div>
                                        <div class="w-full md:w-1/4">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIP / NIK</label>
                                            <input type="text" :name="'pengikut['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm font-mono border-slate-300 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-slate-600" placeholder="-">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Keterangan</label>
                                            <input type="text" :name="'pengikut['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Contoh: Pendamping" class="block w-full text-sm border-slate-300 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-slate-600">
                                        </div>
                                        <div class="absolute top-2 right-2 md:static">
                                            <button type="button" @click="removeFollower(index)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 hover:shadow-sm transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="followers.length === 0" class="text-center py-8 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 italic text-sm bg-white">
                                    Tidak ada peserta tambahan.
                                </div>
                                <?php $__errorArgs = ['pengikut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-rose-500 text-xs mt-2 font-bold"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        
                        <div class="pt-6 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="<?php echo e(route('letters.spt.index')); ?>" class="px-6 py-3.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-100 hover:text-slate-700 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Simpan SPT</span>
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/letters/spt/create.blade.php ENDPATH**/ ?>