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
    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex items-center gap-4 mb-8">
                <a href="<?php echo e(route('admin.alumni.index')); ?>" class="p-3 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm">
                    <i class="ph-bold ph-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-800">Import Data Alumni</h1>
                    <p class="text-slate-500 text-sm">Upload data alumni lama (legacy) secara massal.</p>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    <span class="font-bold"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-3">
                    <i class="ph-fill ph-warning-circle text-xl"></i>
                    <span class="font-bold"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl">
                    <ul class="list-disc list-inside font-bold text-sm">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                
                <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-xl shadow-gray-200/50">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        <i class="ph-duotone ph-file-csv"></i>
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Upload File CSV</h3>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                        Pastikan file Anda berformat <strong>.CSV</strong>. Sistem akan otomatis membuatkan akun untuk setiap alumni dengan password default NISN.
                    </p>

                    <form action="<?php echo e(route('admin.alumni.import.process')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File</label>
                            <input type="file" name="file" accept=".csv" required
                                   class="block w-full text-sm text-slate-500
                                          file:mr-4 file:py-3 file:px-6
                                          file:rounded-xl file:border-0
                                          file:text-sm file:font-bold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100
                                          border border-gray-200 rounded-xl cursor-pointer">
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-upload-simple"></i> Mulai Proses Import
                        </button>
                    </form>
                </div>

                
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 h-fit">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-info text-blue-500"></i> Petunjuk Format
                    </h3>
                    
                    <ol class="list-decimal list-inside space-y-3 text-sm text-slate-600 mb-8 font-medium">
                        <li>Download template CSV di bawah ini.</li>
                        <li>Jangan ubah urutan kolom di template.</li>
                        <li><strong>PENTING:</strong> Pastikan format file saat Save As di Excel adalah <strong>CSV (Comma Delimited)</strong>.</li>
                        <li>Jika menggunakan Excel Indonesia, sistem akan otomatis mendeteksi pemisah titik koma (;).</li>
                    </ol>

                    <a href="<?php echo e(route('admin.alumni.template')); ?>" class="w-full py-3 bg-white border border-slate-300 hover:border-slate-400 text-slate-700 font-bold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 group">
                        <i class="ph-bold ph-download-simple group-hover:text-blue-600"></i> Download Template CSV
                    </a>
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/admin/alumni/import.blade.php ENDPATH**/ ?>