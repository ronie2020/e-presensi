<?php $__env->startSection('content'); ?>

<?php
    use Carbon\Carbon;
    $startDate = $startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d'); 
    $today = $today ?? Carbon::now()->format('Y-m-d');
    $canFill = $canFill ?? false;
    $todayRamadanLog = $todayRamadanLog ?? null;
    $calendarLogs = $calendarLogs ?? [];
?>

<div class="min-h-screen bg-slate-50 pb-20 pt-10 px-4" x-data="{ isSaving: false }">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER -->
        <div class="mb-6 flex items-center gap-3">
            <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>"class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-sm">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-lg font-black text-slate-800">Kembali ke Portal</h2>
                <p class="text-xs text-slate-400 font-medium"><?php echo e(\Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y')); ?></p>
            </div>
        </div>

        
        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 mb-8">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-black text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-calendar-check text-emerald-500"></i> Kalender Ramadhan
                </h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mulai: <?php echo e(\Carbon\Carbon::parse($startDate)->format('d M')); ?></span>
            </div>

            <div class="grid grid-cols-7 sm:grid-cols-8 md:grid-cols-10 gap-2 sm:gap-3">
                <?php for($i = 0; $i < 30; $i++): ?>
                    <?php
                        $dateCheck = \Carbon\Carbon::parse($startDate)->addDays($i);
                        $dateString = $dateCheck->format('Y-m-d');
                        $isToday = $dateString === $today;
                        $isPast = $dateCheck->lt(\Carbon\Carbon::parse($today));
                        $logExists = isset($calendarLogs[$dateString]);
                        
                        $bgClass = 'bg-slate-50 border-slate-100 text-slate-400'; 
                        if ($isToday) {
                            $bgClass = 'bg-white border-emerald-500 text-emerald-600 ring-2 ring-emerald-100 ring-offset-2';
                        } elseif ($logExists) {
                            $bgClass = 'bg-emerald-500 border-emerald-600 text-white'; 
                        } elseif ($isPast) {
                            $bgClass = 'bg-slate-200 border-slate-300 text-slate-400 opacity-60'; 
                        }
                    ?>
                    
                    <div class="aspect-square rounded-2xl border flex flex-col items-center justify-center relative group <?php echo e($bgClass); ?>">
                        <span class="text-[10px] font-black uppercase mb-0.5">H-<?php echo e($i + 1); ?></span>
                        <span class="text-xs font-bold"><?php echo e($dateCheck->format('d')); ?></span>
                        
                        <?php if($logExists): ?>
                            <div class="absolute -top-1 -right-1 bg-white text-emerald-600 rounded-full p-0.5 shadow-sm border border-emerald-100"><i class="ph-fill ph-check-circle text-[10px]"></i></div>
                        <?php elseif($isPast): ?>
                            <div class="absolute -top-1 -right-1 bg-white text-rose-400 rounded-full p-0.5 shadow-sm border border-rose-100"><i class="ph-bold ph-x text-[10px]"></i></div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        
        <?php if(!$canFill): ?>
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-[2rem] text-center mb-8">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3"><i class="ph-bold ph-lock-key text-2xl"></i></div>
            <h3 class="font-bold text-amber-800">Waktu Pengisian Ditutup</h3>
            <p class="text-sm text-amber-600 mt-1 max-w-md mx-auto">Formulir ini hanya terbuka selama <b>1x24 jam</b> pada tanggal <?php echo e(\Carbon\Carbon::parse($today)->format('d F Y')); ?>.</p>
        </div>
        <?php endif; ?>
        
        <?php if($todayRamadanLog): ?>
        <div class="bg-emerald-50 border border-emerald-200 p-6 rounded-[2rem] text-center mb-8">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3"><i class="ph-fill ph-check-fat text-2xl"></i></div>
            <h3 class="font-bold text-emerald-800">Alhamdulillah!</h3>
            <p class="text-sm text-emerald-600 mt-1">Kamu sudah mengisi jurnal hari ini. Data tersimpan aman.</p>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('student.ramadan.save')); ?>" method="POST" @submit="isSaving = true">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($today); ?>">

            <fieldset <?php echo e(!$canFill ? 'disabled' : ''); ?> class="contents group-disabled:opacity-50 group-disabled:pointer-events-none">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    
                    <div class="md:col-span-2 space-y-6">
                        
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-check-circle text-2xl"></i></div>
                                <div><h3 class="font-bold text-slate-800">Status Puasa</h3><p class="text-xs text-slate-400">Apakah kamu berpuasa hari ini?</p></div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_fasting" class="sr-only peer" <?php echo e(($todayRamadanLog->is_fasting ?? true) ? 'checked' : ''); ?>>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib 5 Waktu</h3>
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

                        
                        <?php if(\Carbon\Carbon::parse($today)->isFriday()): ?>
                        <div class="bg-white p-8 rounded-[2rem] border border-emerald-100 shadow-sm relative overflow-hidden ring-1 ring-emerald-50">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 relative z-10"><i class="ph-fill ph-mosque text-emerald-600"></i> Laporan Shalat Jumat</h3>
                            <div class="grid grid-cols-1 gap-5 relative z-10">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Khotib</label>
                                    <input type="text" name="friday_khotib" value="<?php echo e($todayRamadanLog->friday_khotib ?? ''); ?>" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-emerald-500 pl-4" placeholder="Nama Ustadz..." <?php echo e(($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : ''); ?>>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ringkasan</label>
                                    <textarea name="friday_summary" rows="4" class="w-full bg-slate-50 border-none rounded-xl text-sm font-medium focus:ring-emerald-500" placeholder="Ringkasan khutbah..." <?php echo e(($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : ''); ?>><?php echo e($todayRamadanLog->friday_summary ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-book-open text-blue-500"></i> Tilawah & Murojaah</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Surah Tadarus</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="tadarus_surah" value="<?php echo e($todayRamadanLog->tadarus_surah ?? ''); ?>" class="flex-1 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Surah">
                                        <input type="number" name="tadarus_ayah" value="<?php echo e($todayRamadanLog->tadarus_ayah ?? ''); ?>" class="w-20 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Ayat">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Murojaah</label>
                                    <input type="text" name="murojaah_surah" value="<?php echo e($todayRamadanLog->murojaah_surah ?? ''); ?>" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Contoh: An-Naba">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="space-y-6">
                        
                        
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-microphone-stage text-purple-500"></i> Laporan Kultum
                            </h3>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Penceramah</label>
                                    <input type="text" name="kultum_penceramah" 
                                        value="<?php echo e($todayRamadanLog->kultum_penceramah ?? ''); ?>" 
                                        class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-purple-500 text-slate-700 placeholder-slate-300" 
                                        placeholder="Nama Penceramah...">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ringkasan Materi</label>
                                    <textarea name="kultum_summary" rows="4" 
                                        class="w-full bg-slate-50 border-none rounded-xl text-sm font-medium focus:ring-purple-500 text-slate-600 placeholder-slate-300 leading-relaxed resize-none" 
                                        placeholder="Apa isi ceramahnya?"><?php echo e($todayRamadanLog->kultum_summary ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-star text-amber-500"></i> Amalan Sunnah</h3>
                            <div class="space-y-3 flex-1">
                                <?php $__currentLoopData = ['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $checked = $todayRamadanLog->sunnah_deeds[$s] ?? false; ?>
                                <label class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-50 cursor-pointer hover:border-emerald-200 transition-all">
                                    <span class="text-sm font-bold text-slate-600 capitalize"><?php echo e($s); ?></span>
                                    <input type="checkbox" name="sunnah_<?php echo e($s); ?>" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" <?php echo e($checked ? 'checked' : ''); ?>>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <?php if($canFill): ?>
                            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 group" :disabled="isSaving">
                                <template x-if="!isSaving"><div class="flex items-center gap-2 group-hover:scale-105 transition-transform"><i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal</div></template>
                                <template x-if="isSaving"><div class="flex items-center gap-2"><i class="ph-bold ph-spinner animate-spin"></i> Memproses...</div></template>
                            </button>
                            <?php else: ?>
                             <div class="w-full mt-10 bg-slate-200 text-slate-400 font-bold py-4 rounded-2xl text-center cursor-not-allowed">
                                <i class="ph-bold ph-lock-key"></i> Form Terkunci
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ramadan/student_index.blade.php ENDPATH**/ ?>