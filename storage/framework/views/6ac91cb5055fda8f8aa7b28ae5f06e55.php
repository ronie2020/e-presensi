<div class="space-y-8">
    
    <div class="flex items-center gap-5 border-b border-slate-100 pb-6">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center overflow-hidden border-2 border-emerald-100 shadow-inner">
            <?php if($habit->student->photo_path): ?>
                <img src="<?php echo e(asset('storage/' . $habit->student->photo_path)); ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <i class="ph-fill ph-user text-3xl text-emerald-300"></i>
            <?php endif; ?>
        </div>
        <div>
            <h3 class="font-black text-2xl text-slate-800 tracking-tight"><?php echo e($habit->student->name); ?></h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Kelas <?php echo e($habit->student->schoolClass->name ?? '-'); ?></span>
                <span class="text-slate-400 text-xs font-medium"><i class="ph-bold ph-calendar-blank mr-1"></i><?php echo e($habit->report_date->translatedFormat('d F Y')); ?></span>
            </div>
        </div>
    </div>

    
    <?php if($habit->photo_path): ?>
    <div class="space-y-3">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-image text-sm text-emerald-500"></i> Bukti Dokumentasi
        </label>
        <div class="rounded-3xl overflow-hidden border-4 border-slate-50 shadow-lg bg-slate-100">
            
            <img src="<?php echo e(asset('storage/' . $habit->photo_path)); ?>" 
                class="w-full max-h-[400px] object-contain mx-auto transition-transform hover:scale-[1.05] duration-500 cursor-zoom-in"
                alt="Bukti Kebiasaan Siswa">
        </div>
        <p class="text-center text-[10px] text-slate-400 italic">Klik gambar untuk melihat lebih jelas</p>
    </div>
    <?php endif; ?>

    
    <div class="space-y-4">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-list-checks text-sm text-emerald-500"></i> Check-list 7 Kebiasaan
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <?php
                $habits = [
                    ['val' => $habit->habit_1, 'label' => 'Shalat Subuh', 'icon' => 'ph-sun-horizon'],
                    ['val' => $habit->habit_2, 'label' => 'Membaca Al-Quran', 'icon' => 'ph-book-open'],
                    ['val' => $habit->habit_3, 'label' => 'Membantu Orang Tua', 'icon' => 'ph-heart-straight'],
                    ['val' => $habit->habit_4, 'label' => 'Belajar Mandiri', 'icon' => 'ph-brain'],
                    ['val' => $habit->habit_5, 'label' => 'Olahraga/Gerak Fisik', 'icon' => 'ph-barbell'],
                    ['val' => $habit->habit_6, 'label' => 'Makan Bergizi', 'icon' => 'ph-bowl-food'],
                ];
            ?>

            <?php $__currentLoopData = $habits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-4 rounded-2xl border <?php echo e($h['val'] ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60'); ?> transition-all">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold <?php echo e($h['icon']); ?> <?php echo e($h['val'] ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-slate-700"><?php echo e($h['label']); ?></span>
                    </div>
                    <i class="ph-fill <?php echo e($h['val'] ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?> text-xl"></i>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <div class="md:col-span-2 flex items-center justify-between p-4 rounded-2xl border <?php echo e($habit->habit_7 ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center gap-3">
                    <i class="ph-bold ph-moon <?php echo e($habit->habit_7 ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                    <div>
                        <span class="text-sm font-bold text-slate-700">Tidur Cukup (Maks Pukul 22.00)</span>
                        <?php if($habit->habit_7_time): ?>
                            <p class="text-[10px] font-bold text-emerald-600">Jam Tidur: <?php echo e($habit->habit_7_time); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <i class="ph-fill <?php echo e($habit->habit_7 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?> text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="pt-4 border-t border-slate-100">
        <form action="<?php echo e(route('teacher.habits.feedback', $habit->id)); ?>" method="POST" class="bg-slate-900 p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
            <?php echo csrf_field(); ?>
            
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="ph-fill ph-chat-centered-text text-6xl text-white"></i>
            </div>

            <label class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-3 block">Berikan Apresiasi / Catatan</label>
            <textarea name="feedback" rows="3" 
                class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white text-sm focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-500" 
                placeholder="Tulis pesan motivasi untuk siswa di sini..."><?php echo e($habit->feedback); ?></textarea>
            
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-black text-xs uppercase tracking-widest px-8 py-3 rounded-xl transition-all active:scale-95 shadow-lg shadow-emerald-500/20">
                    Kirim Feedback
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/partials/detail_modal.blade.php ENDPATH**/ ?>