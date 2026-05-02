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
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            <?php echo e(__('Buat Bank Soal Mapel')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-10 font-sans text-elevate-dark relative overflow-hidden min-h-screen">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-elevate-surface rounded-[2rem] shadow-xl shadow-elevate-accent/5 p-8 md:p-10 border border-slate-100">
                
                
                <div class="mb-8 border-b border-elevate-soft pb-6 flex items-center gap-4">
                    <div class="w-14 h-14 bg-elevate-peach-light text-elevate-primary rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-elevate-peach/30">
                        <i class="ph-bold ph-file-plus"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-elevate-dark">Bank Soal Baru</h2>
                        <p class="text-elevate-dark/70 text-sm font-medium">Memasukkan mapel ke dalam folder: <strong class="text-elevate-primary"><?php echo e($folder->name); ?></strong></p>
                    </div>
                </div>

                <form action="<?php echo e(route('bank.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <input type="hidden" name="folder_id" value="<?php echo e($folder_id); ?>">

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-elevate-dark mb-2 ml-1">Nama Topik / Modul Bank Soal <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required class="w-full rounded-2xl border-transparent bg-elevate-soft py-3.5 px-5 font-bold text-elevate-dark focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all placeholder-elevate-dark/40" placeholder="Misal: Ulangan Harian Bab 1 Algoritma">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-elevate-dark mb-2 ml-1">Pilih Mata Pelajaran <span class="text-rose-500">*</span></label>
                                <select name="subject_name" required class="w-full rounded-2xl border-transparent bg-elevate-soft py-3.5 px-5 font-bold text-elevate-dark focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all">
                                    <option value="" disabled selected class="text-elevate-dark/50">-- Pilih Mapel --</option>
                                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($subject->name); ?>"><?php echo e($subject->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <option value="Lainnya">Mapel Lainnya (Ketik Manual)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-elevate-dark mb-2 ml-1">Tingkat Kelas <span class="text-rose-500">*</span></label>
                                <select name="class_level" required class="w-full rounded-2xl border-transparent bg-elevate-soft py-3.5 px-5 font-bold text-elevate-dark focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all">
                                    <option value="" disabled selected class="text-elevate-dark/50">-- Pilih Kelas --</option>
                                    <option value="7">Kelas 7</option>
                                    <option value="8">Kelas 8</option>
                                    <option value="9">Kelas 9</option>
                                    <option value="Umum">Umum / Semua Kelas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-10 flex gap-4 pt-6 border-t border-elevate-soft">
                        <a href="<?php echo e(route('bank.show', $folder_id)); ?>" class="flex-1 text-center py-4 bg-white border-2 border-elevate-soft text-elevate-dark rounded-2xl font-bold hover:bg-elevate-soft transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="flex-[2] py-4 bg-elevate-dark text-white rounded-2xl font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-colors active:scale-[0.98]">
                            Buat Bank Soal
                        </button>
                    </div>
                </form>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/bank/create.blade.php ENDPATH**/ ?>