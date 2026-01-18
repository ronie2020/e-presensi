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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <a href="<?php echo e(route('letters.spt.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar
            </a>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10">Buat Surat Perintah Tugas</h2>
                    <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Isi formulir penugasan dinas pegawai.</p>
                </div>

                
                <div class="p-8">
                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm flex items-start gap-3">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold block mb-1">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('letters.spt.store')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        
                        
                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6">
                            <label class="block text-xs font-bold text-blue-800 uppercase mb-2 ml-1">Dasar Surat (Referensi)</label>
                            <div class="relative">
                                <i class="ph-bold ph-link absolute left-4 top-1/2 -translate-y-1/2 text-blue-400"></i>
                                <select name="letter_incoming_id" class="w-full pl-11 pr-10 rounded-xl border-blue-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all appearance-none">
                                    <option value="">-- Tanpa Dasar Surat (Langsung) --</option>
                                    <?php $__currentLoopData = $incoming_letters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($letter->id); ?>" <?php echo e((old('letter_incoming_id', $selected_letter_id) == $letter->id) ? 'selected' : ''); ?>>
                                            No: <?php echo e($letter->nomor_surat); ?> — <?php echo e(Str::limit($letter->perihal, 60)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-blue-400"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                            <p class="text-[10px] text-blue-500 mt-2 ml-1 font-medium">* Opsional: Pilih surat masuk yang mendasari tugas ini.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPT <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-hash absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="nomor_spt" value="<?php echo e(old('nomor_spt', $nomor_otomatis)); ?>" required
                                               class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-mono font-bold text-slate-700 transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Untuk (Maksud Tugas) <span class="text-rose-500">*</span></label>
                                    <textarea name="untuk" rows="4" required placeholder="Contoh: Menghadiri kegiatan Workshop Kurikulum Merdeka..."
                                              class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-medium text-slate-700 transition-all"><?php echo e(old('untuk')); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="tempat" value="<?php echo e(old('tempat')); ?>" required placeholder="Contoh: Aula Dinas Pendidikan"
                                               class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Berangkat</label>
                                        <input type="date" name="tgl_berangkat" value="<?php echo e(old('tgl_berangkat')); ?>" required
                                               class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Kembali</label>
                                        <input type="date" name="tgl_kembali" value="<?php echo e(old('tgl_kembali')); ?>" required
                                               class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-slate-50 p-5 rounded-[2rem] border border-slate-200 h-full flex flex-col">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-3 ml-1 flex items-center justify-between">
                                    <span>Pilih Pegawai</span>
                                    <span class="text-[10px] bg-slate-200 px-2 py-0.5 rounded text-slate-500">Multi-select</span>
                                </label>
                                
                                <div class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar pr-2 space-y-2">
                                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <label class="flex items-center p-3 bg-white rounded-2xl border border-slate-200 cursor-pointer hover:border-blue-400 hover:shadow-sm transition-all group">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" name="pegawai_ids[]" value="<?php echo e($user->id); ?>" 
                                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-blue-600 checked:bg-blue-600 hover:border-blue-400"
                                                    <?php echo e((is_array(old('pegawai_ids')) && in_array($user->id, old('pegawai_ids'))) ? 'checked' : ''); ?>>
                                                <div class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                                                    <i class="ph-bold ph-check text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors"><?php echo e($user->name); ?></span>
                                                <span class="block text-[10px] text-slate-400 font-mono"><?php echo e($user->pangkat ?? 'NIP. ' . ($user->nip ?? '-')); ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="p-6 text-center text-slate-400 italic text-sm border-2 border-dashed border-slate-200 rounded-2xl">
                                            Data pegawai tidak ditemukan.
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php $__errorArgs = ['pegawai_ids'];
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

                        
                        <div class="pt-8 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="<?php echo e(route('letters.spt.index')); ?>" class="px-6 py-3 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-50 hover:text-slate-700 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan SPT
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\letters\spt\create.blade.php ENDPATH**/ ?>