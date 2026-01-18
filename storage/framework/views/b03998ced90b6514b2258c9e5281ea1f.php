
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
    <div class="py-12 font-sans text-slate-800">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none">Edit Kelas</h1>
                    <p class="text-sm font-medium text-slate-500 mt-1">Perbarui informasi rombongan belajar.</p>
                </div>
                <a href="<?php echo e(route('classes.index')); ?>" class="group flex items-center gap-2 bg-white px-4 py-2 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-200 hover:shadow-md transition-all">
                    <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <i class="ph-bold ph-arrow-left text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-600 group-hover:text-blue-600">Kembali</span>
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-blue-900/10 border border-slate-100 overflow-hidden relative">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                
                <div class="p-8 sm:p-10">
                    
                    <div class="flex items-start gap-4 mb-8 p-5 bg-blue-50/50 rounded-3xl border border-blue-100">
                        <div class="w-12 h-12 bg-white text-blue-600 rounded-2xl flex items-center justify-center shadow-sm border border-blue-50 text-2xl shrink-0">
                            <i class="ph-duotone ph-pencil-simple-line"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800">Formulir Perubahan</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed mt-1">Ubah nama atau wali kelas untuk rombel <strong class="text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded"><?php echo e($class->name); ?></strong>.</p>
                        </div>
                    </div>

                    
                    <?php if($errors->any()): ?>
                        <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <div class="p-1.5 bg-rose-100 rounded-lg shrink-0">
                                <i class="ph-bold ph-warning-circle text-lg"></i>
                            </div>
                            <div>
                                <p class="font-bold mb-1">Terjadi kesalahan input:</p>
                                <ul class="list-disc list-inside opacity-80 text-xs">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('classes.update', $class->id)); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        
                        
                        <div>
                            <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Kelas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="ph-bold ph-chalkboard text-lg"></i>
                                </div>
                                <input type="text" name="name" id="name" 
                                       value="<?php echo e(old('name', $class->name)); ?>" required 
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-all shadow-sm placeholder:font-normal">
                            </div>
                        </div>
                        
                        
                        <div>
                            <label for="homeroom_teacher_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Wali Kelas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="ph-bold ph-user-circle text-lg"></i>
                                </div>
                                <select name="homeroom_teacher_id" id="homeroom_teacher_id" 
                                        class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($teacher->id); ?>" 
                                            <?php echo e(old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : ''); ?>>
                                            <?php echo e($teacher->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50 mt-8">
                            <button type="submit" 
                                    class="w-full py-3.5 px-6 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\classes\edit.blade.php ENDPATH**/ ?>