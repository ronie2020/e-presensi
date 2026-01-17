

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl mx-auto pb-20 px-4 sm:px-6 pt-6 md:pt-10">
    
    <!-- Tombol Kembali -->
    <a href="<?php echo e(route('student.bk.index')); ?>" class="inline-flex items-center text-sm text-slate-500 hover:text-blue-600 font-bold mb-6 transition-colors">
        <i class="ph-bold ph-arrow-left mr-2"></i> Kembali ke Riwayat
    </a>

    <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-slate-100 overflow-hidden relative">
        <!-- Header Dekorasi -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-pink-500 to-purple-600"></div>
        
        <div class="p-6 md:p-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-3xl shadow-inner">
                    <i class="ph-duotone ph-heart-beat"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Mulai Konsultasi</h2>
                    <p class="text-slate-500 text-sm">Ceritakan masalahmu, privasi dijamin aman 100%.</p>
                </div>
            </div>
            
            <form action="<?php echo e(route('student.bk.store')); ?>" method="POST" class="space-y-8">
                <?php echo csrf_field(); ?>
                
                <!-- 1. Kategori Masalah -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Topik Permasalahan <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="bk_category_id" value="<?php echo e($cat->id); ?>" class="peer sr-only" required>
                            <div class="rounded-xl border-2 border-slate-200 p-4 hover:border-pink-300 hover:bg-pink-50/50 transition-all peer-checked:border-pink-500 peer-checked:bg-pink-50 peer-checked:shadow-md flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-pink-500 peer-checked:bg-pink-500 flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <span class="text-sm font-bold text-slate-600 peer-checked:text-pink-700"><?php echo e($cat->name); ?></span>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['bk_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-2 font-bold"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 2. Pesan Awal -->
                <div>
                    <label for="initial_message" class="block text-sm font-bold text-slate-700 mb-3">Apa yang sedang kamu rasakan? <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <textarea name="initial_message" id="initial_message" rows="6" 
                            class="w-full rounded-2xl border-slate-300 bg-slate-50 focus:bg-white shadow-sm focus:border-pink-500 focus:ring-pink-500 p-4 text-slate-700 leading-relaxed resize-none transition-all" 
                            placeholder="Contoh: Saya merasa kesulitan membagi waktu antara belajar dan ekskul, nilai saya jadi turun..." required><?php echo e(old('initial_message')); ?></textarea>
                        <i class="ph-duotone ph-pencil-simple text-slate-400 absolute top-4 right-4 text-xl"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 text-right">Minimal 10 karakter</p>
                    <?php $__errorArgs = ['initial_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-2 font-bold"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 3. Metode Konseling -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Metode Konseling <span class="text-red-500">*</span></label>
                    <div class="flex flex-col md:flex-row gap-4">
                        <label class="flex-1 relative cursor-pointer">
                            <input type="radio" name="method" value="offline" class="peer sr-only" checked>
                            <div class="p-4 rounded-xl border-2 border-slate-200 hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-start gap-4">
                                <div class="p-2 bg-white rounded-lg border border-slate-200 peer-checked:border-blue-200 text-blue-600 text-2xl">
                                    <i class="ph-duotone ph-users-three"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800 peer-checked:text-blue-700">Tatap Muka</span>
                                    <span class="text-xs text-slate-500">Bertemu langsung dengan Guru BK di ruangan.</span>
                                </div>
                            </div>
                        </label>
                        <label class="flex-1 relative cursor-pointer">
                            <input type="radio" name="method" value="online" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 hover:bg-slate-50 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all flex items-start gap-4">
                                <div class="p-2 bg-white rounded-lg border border-slate-200 peer-checked:border-purple-200 text-purple-600 text-2xl">
                                    <i class="ph-duotone ph-chat-circle-text"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800 peer-checked:text-purple-700">Online (Chat/WA)</span>
                                    <span class="text-xs text-slate-500">Konseling jarak jauh melalui media komunikasi.</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Footer & Button -->
                <div class="pt-6 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-slate-500 text-xs bg-slate-100 px-3 py-2 rounded-lg">
                        <i class="ph-fill ph-lock-key text-slate-400"></i>
                        <span>Data ini bersifat <strong>RAHASIA</strong>.</span>
                    </div>
                    
                    <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 shadow-lg shadow-pink-500/30 hover:-translate-y-1 transition-all duration-300">
                        <span>Kirim Pengajuan</span>
                        <i class="ph-bold ph-paper-plane-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/bk/create.blade.php ENDPATH**/ ?>