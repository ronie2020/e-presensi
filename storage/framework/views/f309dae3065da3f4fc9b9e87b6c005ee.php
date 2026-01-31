

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-50 pb-20 pt-10 px-4" x-data="{ isSaving: false }">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER & NAVIGASI KEMBALI -->
        <div class="mb-6 flex items-center gap-3">
            <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>"class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-sm">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-lg font-black text-slate-800">Kembali ke Portal</h2>
                <p class="text-xs text-slate-400 font-medium"><?php echo e(\Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y')); ?></p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-emerald-800 to-teal-600 rounded-[2.5rem] p-8 text-white shadow-xl mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="ph-fill ph-moon text-[120px]"></i>
            </div>
            <div class="relative z-10">
                <h1 class="text-2xl font-black mb-2">Bismillah,</h1>
                <p class="text-emerald-50/80">"Allah menyukai amalan yang dilakukan secara istiqomah (terus-menerus) walaupun sedikit."</p>
            </div>
        </div>

        <form action="<?php echo e(route('student.ramadan.save')); ?>" method="POST" @submit="isSaving = true">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($today); ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- KOLOM KIRI: WAJIB -->
                <div class="md:col-span-2 space-y-6">
                    
                    <!-- STATUS PUASA -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i class="ph-bold ph-check-circle text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Status Puasa</h3>
                                <p class="text-xs text-slate-400">Apakah kamu berpuasa hari ini?</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_fasting" class="sr-only peer" <?php echo e(($todayRamadanLog->is_fasting ?? true) ? 'checked' : ''); ?>>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <!-- SHALAT 5 WAKTU -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib 5 Waktu
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <?php $__currentLoopData = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $checked = $todayRamadanLog->prayers[$p] ?? false; ?>
                            <label class="cursor-pointer group">
                                <input type="checkbox" name="prayer_<?php echo e($p); ?>" class="hidden peer" <?php echo e($checked ? 'checked' : ''); ?>>
                                <div class="p-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-slate-400 transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-200 peer-checked:text-emerald-700 flex flex-col items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase"><?php echo e($p); ?></span>
                                    <i class="ph-bold ph-check-circle text-xl"></i>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- KHUSUS HARI JUMAT: LAPORAN SHALAT JUMAT -->
                    <?php if(\Carbon\Carbon::parse($today)->isFriday()): ?>
                    <div class="bg-white p-8 rounded-[2rem] border border-emerald-100 shadow-sm relative overflow-hidden ring-1 ring-emerald-50">
                        <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                            <i class="ph-fill ph-mosque text-9xl text-emerald-800"></i>
                        </div>

                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 relative z-10">
                            <i class="ph-fill ph-mosque text-emerald-600"></i> Laporan Shalat Jumat
                        </h3>

                        <div class="grid grid-cols-1 gap-5 relative z-10">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Khotib</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="ph-bold ph-user"></i></span>
                                    <input type="text" name="friday_khotib" 
                                        value="<?php echo e($todayRamadanLog->friday_khotib ?? ''); ?>" 
                                        class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-emerald-500 pl-10 placeholder:font-normal placeholder:text-slate-300 disabled:opacity-70 disabled:cursor-not-allowed disabled:bg-slate-100" 
                                        placeholder="Nama Ustadz / Khotib..."
                                        <?php echo e(($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : ''); ?>>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ringkasan / Resume Khutbah</label>
                                <textarea name="friday_summary" rows="4" 
                                    class="w-full bg-slate-50 border-none rounded-xl text-sm font-medium focus:ring-emerald-500 placeholder:font-normal placeholder:text-slate-300 leading-relaxed disabled:opacity-70 disabled:cursor-not-allowed disabled:bg-slate-100" 
                                    placeholder="Tuliskan poin-poin penting dari khutbah Jumat yang kamu dengarkan hari ini..."
                                    <?php echo e(($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : ''); ?>><?php echo e($todayRamadanLog->friday_summary ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- TILAWAH -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-book-open text-blue-500"></i> Tilawah & Murojaah
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase">Surah Tadarus</label>
                                <div class="flex gap-2">
                                    <input type="text" name="tadarus_surah" value="<?php echo e($todayRamadanLog->tadarus_surah ?? ''); ?>" class="flex-1 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Surah">
                                    <input type="number" name="tadarus_ayah" value="<?php echo e($todayRamadanLog->tadarus_ayah ?? ''); ?>" class="w-20 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Ayat">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase">Surah Murojaah</label>
                                <input type="text" name="murojaah_surah" value="<?php echo e($todayRamadanLog->murojaah_surah ?? ''); ?>" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Contoh: An-Naba">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: SUNNAH -->
                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 h-full flex flex-col">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-star text-amber-500"></i> Amalan Sunnah
                        </h3>
                        <div class="space-y-3 flex-1">
                            <?php $__currentLoopData = ['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $checked = $todayRamadanLog->sunnah_deeds[$s] ?? false; ?>
                            <label class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-50 cursor-pointer hover:border-emerald-200 transition-all">
                                <span class="text-sm font-bold text-slate-600 capitalize"><?php echo e($s); ?></span>
                                <input type="checkbox" name="sunnah_<?php echo e($s); ?>" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" <?php echo e($checked ? 'checked' : ''); ?>>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 group" :disabled="isSaving">
                            <template x-if="!isSaving">
                                <div class="flex items-center gap-2 group-hover:scale-105 transition-transform">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal
                                </div>
                            </template>
                            <template x-if="isSaving">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-spinner animate-spin"></i> Memproses...
                                </div>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/ramadan/student_index.blade.php ENDPATH**/ ?>