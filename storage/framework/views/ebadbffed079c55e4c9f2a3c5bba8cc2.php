<div class="space-y-8 font-jakarta">
    
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
                <span class="px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">
                    Kelas <?php echo e($habit->student->schoolClass->name ?? '-'); ?>

                </span>
                <span class="text-slate-400 text-xs font-medium">
                    <i class="ph-bold ph-calendar-blank mr-1"></i>
                    <?php echo e(\Carbon\Carbon::parse($habit->report_date)->translatedFormat('d F Y')); ?>

                </span>
            </div>
        </div>
    </div>

    
    <?php if($habit->photo_path): ?>
    <div class="space-y-3">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-image text-sm text-emerald-500"></i> Bukti Dokumentasi
        </label>
        <div class="rounded-3xl overflow-hidden border-4 border-slate-50 shadow-lg bg-slate-100 relative group">
            <img src="<?php echo e(asset('storage/' . $habit->photo_path)); ?>" 
                class="w-full max-h-[400px] object-contain mx-auto transition-transform hover:scale-[1.02] duration-500"
                alt="Bukti Kebiasaan Siswa">
                
            <a href="<?php echo e(asset('storage/' . $habit->photo_path)); ?>" target="_blank" class="absolute bottom-4 right-4 bg-white/90 backdrop-blur text-slate-800 px-4 py-2 rounded-xl text-xs font-bold shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="ph-bold ph-arrows-out-simple"></i> Lihat Penuh
            </a>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="space-y-4">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-list-checks text-sm text-emerald-500"></i> Laporan 7 Kebiasaan
        </label>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            
            
            <div class="flex flex-col p-4 rounded-2xl border <?php echo e(($habit->habit_1 && $habit->habit_2) ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-sun-horizon <?php echo e($habit->habit_1 ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">1. Bangun & Mandi</span>
                    </div>
                    <i class="ph-fill <?php echo e(($habit->habit_1 && $habit->habit_2) ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">
                    Bangun jam <?php echo e($habit->habit_1_time ? \Carbon\Carbon::parse($habit->habit_1_time)->format('H:i') : '-'); ?>

                </p>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border bg-white border-slate-100 shadow-sm md:row-span-2">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-mosque text-emerald-600 text-lg"></i>
                            <span class="text-sm font-bold text-slate-700">2. Ibadah Harian</span>
                        </div>

                        
                        <?php if($habit->is_udzur_syar_i): ?>
                            <span class="px-2 py-1 bg-pink-50 text-pink-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-pink-100 flex items-center gap-1">
                                <i class="ph-fill ph-flower-lotus"></i> Sedang Udzur
                            </span>
                        <?php endif; ?>
                    </div>

                    
                    <?php if($habit->is_udzur_syar_i): ?>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-500 font-medium">Siswa sedang berhalangan (Udzur Syar'i).</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-2 gap-y-3 gap-x-2">
                            
                            <?php
                                $prayers = [
                                    ['Subuh', $habit->prayer_subuh],
                                    ['Dhuha', $habit->prayer_dhuha],
                                    ['Dzuhur', $habit->prayer_dzuhur],
                                    ['Ashar', $habit->prayer_ashar],
                                    ['Maghrib', $habit->prayer_maghrib],
                                    ['Isya', $habit->prayer_isya],
                                ];
                            ?>
                            <?php $__currentLoopData = $prayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-2">
                                    <i class="ph-fill <?php echo e($p[1] ? 'ph-check-circle text-emerald-500' : 'ph-circle text-slate-200'); ?> text-lg"></i>
                                    <span class="text-xs font-bold <?php echo e($p[1] ? 'text-slate-700' : 'text-slate-400'); ?>"><?php echo e($p[0]); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="mt-auto pt-3 border-t border-slate-100 border-dashed">
                    <p class="text-[10px] font-black text-slate-400 uppercase mb-2 flex items-center gap-2">
                        <i class="ph-bold ph-microphone-stage text-emerald-500"></i> Laporan Tadarus
                    </p>
                    
                    <?php if($habit->odoa_surah || $habit->odoa_ayat || $habit->odoa_audio_path): ?>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 relative overflow-hidden group">
                             <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                        <i class="ph-fill ph-book-open-text"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700">
                                            <?php echo e($habit->odoa_surah ? $habit->odoa_surah : 'Belum isi surat'); ?> 
                                            <span class="text-slate-400 font-normal">: Ayat <?php echo e($habit->odoa_ayat ?? '-'); ?></span>
                                        </p>
                                    </div>
                                </div>

                                
                                <?php if($habit->odoa_audio_path): ?>
                                    <div class="mt-2">
                                        <audio controls class="w-full h-8 rounded-lg">
                                            <source src="<?php echo e(asset('storage/'.$habit->odoa_audio_path)); ?>">
                                            Browser Anda tidak mendukung pemutar audio.
                                        </audio>
                                    </div>
                                <?php else: ?>
                                    <p class="text-[10px] text-slate-400 italic pl-11">Tidak ada rekaman suara.</p>
                                <?php endif; ?>
                             </div>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-slate-400 italic bg-slate-50 p-2 rounded-lg text-center">Siswa tidak mengisi laporan ODOA.</p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border <?php echo e($habit->habit_3 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-sneaker-move <?php echo e($habit->habit_3 ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">3. Berolahraga</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_3 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_3_activity): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">"<?php echo e($habit->habit_3_activity); ?>"</p>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border <?php echo e($habit->habit_5 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-carrot <?php echo e($habit->habit_5 ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">4. Makan Bergizi</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_5 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_5_menu): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">Menu: <?php echo e($habit->habit_5_menu); ?></p>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border <?php echo e($habit->habit_4 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-book-open-text <?php echo e($habit->habit_4 ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">5. Gemar Belajar</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_4 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_4_subject): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">Mapel: <?php echo e($habit->habit_4_subject); ?></p>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border <?php echo e($habit->habit_6 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-users-three <?php echo e($habit->habit_6 ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">6. Bantu Orang Tua</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_6 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_6_activity): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">"<?php echo e($habit->habit_6_activity); ?>"</p>
                <?php endif; ?>
            </div>

            
            <div class="md:col-span-2 flex items-center justify-between p-4 rounded-2xl border <?php echo e($habit->habit_7 ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center gap-3">
                    <i class="ph-bold ph-moon <?php echo e($habit->habit_7 ? 'text-emerald-600' : 'text-slate-400'); ?> text-lg"></i>
                    <div>
                        <span class="text-sm font-bold text-slate-700">7. Tidur Cepat</span>
                        <?php if($habit->habit_7_time): ?>
                            <p class="text-[10px] font-bold text-emerald-600">Jam: <?php echo e(\Carbon\Carbon::parse($habit->habit_7_time)->format('H:i')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <i class="ph-fill <?php echo e($habit->habit_7 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300'); ?>"></i>
            </div>
        </div>
    </div>

    
    <div class="pt-4 border-t border-slate-100">
        <form action="<?php echo e(route('teacher.habits.feedback', $habit->id)); ?>" method="POST" class="bg-slate-900 p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
            <?php echo csrf_field(); ?>
            <div class="absolute top-0 right-0 p-4 opacity-10"><i class="ph-fill ph-chat-centered-text text-6xl text-white"></i></div>
            <label class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-3 block">Berikan Apresiasi / Catatan</label>
            <textarea name="feedback" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white text-sm focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-500" placeholder="Tulis pesan motivasi..."><?php echo e($habit->teacher_feedback); ?></textarea>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-black text-xs uppercase tracking-widest px-8 py-3 rounded-xl transition-all shadow-lg active:scale-95">Kirim Feedback</button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/habits/partials/detail_modal.blade.php ENDPATH**/ ?>