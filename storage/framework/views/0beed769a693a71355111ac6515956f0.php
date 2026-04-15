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
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center gap-4">
                <a href="<?php echo e(route('achievements.index')); ?>" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm">
                    <i class="ph-bold ph-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-800">Edit Prestasi</h1>
                    <p class="text-slate-500 text-sm font-medium">Perbarui data penghargaan.</p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8">
                    <form action="<?php echo e(route('achievements.update', $achievement->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-6" 
                          x-data="{ type: '<?php echo e(old('type', $achievement->type)); ?>', imgPreview: '<?php echo e($achievement->photo_path ? asset('storage/'.$achievement->photo_path) : ''); ?>' }">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
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
                                        <option value="<?php echo e($student->id); ?>" <?php echo e((old('student_id') ?? $achievement->student_id) == $student->id ? 'selected' : ''); ?>>
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
                                <input type="text" name="name_manual" value="<?php echo e(old('name_manual', $achievement->name_manual)); ?>" placeholder="Contoh: Tim Futsal Guru" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 transition-colors">
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
                                <input type="text" name="title" value="<?php echo e(old('title', $achievement->title)); ?>" required placeholder="Juara 1 Lomba..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 transition-colors">
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
                                            <option value="<?php echo e($lvl); ?>" <?php echo e((old('level') ?? $achievement->level) == $lvl ? 'selected' : ''); ?>><?php echo e($lvl); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                <input type="date" name="date" value="<?php echo e(old('date', \Carbon\Carbon::parse($achievement->date)->format('Y-m-d'))); ?>" class="w-full px-3 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dokumentasi Foto</label>
                            <div class="relative group">
                                <input type="file" name="photo" accept="image/*" @change="if($event.target.files.length > 0) { imgPreview = URL.createObjectURL($event.target.files[0]) }" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-4 text-center transition-all group-hover:border-blue-500 group-hover:bg-blue-50" :class="{'border-blue-500 bg-blue-50': imgPreview}">
                                    <div x-show="!imgPreview" class="space-y-2">
                                        <i class="ph-duotone ph-image text-3xl text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                                        <p class="text-[10px] text-slate-400 font-bold">Upload Foto Baru</p>
                                    </div>
                                    <div x-show="imgPreview" style="display: none;">
                                        <img :src="imgPreview" class="h-32 w-full object-contain rounded-xl mx-auto">
                                        <p class="text-[10px] text-slate-500 font-bold mt-2">Klik area ini untuk mengganti foto</p>
                                    </div>
                                </div>
                            </div>
                            <?php if($achievement->photo_path): ?>
                                <p class="text-xs text-slate-400 mt-2 ml-1"><i class="ph-fill ph-info"></i> Kosongkan jika tidak ingin mengganti foto saat ini.</p>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link Video (Opsional)</label>
                                <div class="relative">
                                    <i class="ph-bold ph-youtube-logo absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="url" name="video_link" value="<?php echo e(old('video_link', $achievement->video_link)); ?>" placeholder="https://..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3 transition-colors text-xs">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Sertifikat (Opsional)</label>
                                <div class="relative">
                                    <input type="file" name="certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-3 file:rounded-xl file:border-0 file:font-bold file:bg-blue-50 file:text-blue-700 bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white hover:bg-white transition-colors cursor-pointer">
                                </div>
                                <?php if($achievement->certificate_path): ?>
                                    <p class="text-[10px] text-blue-500 mt-2 ml-1 font-bold"><a href="<?php echo e(asset('storage/'.$achievement->certificate_path)); ?>" target="_blank" class="hover:underline">Lihat Sertifikat Saat Ini</a></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <a href="<?php echo e(route('achievements.index')); ?>" class="flex-1 py-3.5 px-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all flex items-center justify-center gap-2 text-center">
                                Batal
                            </a>
                            <button type="submit" class="flex-[2] py-3.5 px-4 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Perubahan
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/achievements/edit.blade.php ENDPATH**/ ?>