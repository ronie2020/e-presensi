<?php $__env->startSection('content'); ?>
    
    <?php \Carbon\Carbon::setLocale('id'); ?>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        <div class="space-y-8">
            
            
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-blue-900/30 overflow-hidden border border-white/10">
               
               <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="inline-flex items-center gap-2 text-blue-300 hover:text-white transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em]">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Jurnal Misi Harian</h1>
                        <p class="text-blue-100/70 text-sm max-w-xl leading-relaxed">
                            Cicil misimu sepanjang hari. Jangan lupa simpan setiap perubahan!
                        </p>
                    </div>
                    
               </div>
            </div>

            <?php
                // Cek apakah semua habit inti (1-7) sudah terisi true
                $isFullyComplete = $todayEntry && 
                                   $todayEntry->habit_1 && 
                                   $todayEntry->habit_2 && 
                                   $todayEntry->habit_3 && 
                                   $todayEntry->habit_4 && 
                                   $todayEntry->habit_5 && 
                                   $todayEntry->habit_6 && 
                                   $todayEntry->habit_7;
            ?>

            <?php if($isFullyComplete): ?>
                
                <div class="space-y-6 animate-enter" style="animation-delay: 100ms">
                     
                     <div class="bg-white rounded-[3rem] p-12 text-center border border-slate-100 shadow-xl shadow-blue-900/5 relative overflow-hidden group">
                        
                        <h2 class="text-3xl font-black text-slate-800 mb-2">Misi Hari Ini Sempurna! ✨</h2>
                        <p class="text-slate-500 text-base max-w-md mx-auto mb-10">
                            Hebat! Kamu sudah melengkapi seluruh rangkaian kebiasaan baik hari ini.
                        </p>
                        <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="inline-flex items-center gap-3 px-10 py-4 bg-slate-900 hover:bg-blue-600 text-white font-black rounded-2xl shadow-xl transition-all">
                            <i class="ph-bold ph-house-line text-lg"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            <?php else: ?>
                
                
                
                <?php if($todayEntry): ?>
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3 text-amber-800 text-sm font-bold mb-4">
                    <i class="ph-fill ph-info"></i>
                    <p>Kamu sudah mengisi sebagian. Silakan lanjutkan mengisi sisa misimu!</p>
                </div>
                <?php endif; ?>

                 <form action="<?php echo e(route('student.habits.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-8 animate-enter" 
                        x-data="{
                        habit1: <?php echo e(($todayEntry->habit_1 ?? false) ? 'true' : 'false'); ?>, 
                        habit_shalat: <?php echo e(($todayEntry && ($todayEntry->prayer_subuh || $todayEntry->prayer_dzuhur)) ? 'true' : 'false'); ?>,
                        habit3: <?php echo e(($todayEntry->habit_3 ?? false) ? 'true' : 'false'); ?>, 
                        habit4: <?php echo e(($todayEntry->habit_4 ?? false) ? 'true' : 'false'); ?>, 
                        habit5: <?php echo e(($todayEntry->habit_5 ?? false) ? 'true' : 'false'); ?>, 
                        habit6: <?php echo e(($todayEntry->habit_6 ?? false) ? 'true' : 'false'); ?>, 
                        habit7: <?php echo e(($todayEntry->habit_7 ?? false) ? 'true' : 'false'); ?>,
                        previewUrl: '<?php echo e($todayEntry && $todayEntry->photo_path ? asset('storage/'.$todayEntry->photo_path) : null); ?>',
                        fileChosen(event) {
                            const file = event.target.files[0];
                            if (file) { this.previewUrl = URL.createObjectURL(file); }
                        }
                    }">
                    <?php echo csrf_field(); ?>
                    
                    
                    <?php if($todayEntry): ?>
                        <input type="hidden" name="existing_id" value="<?php echo e($todayEntry->id); ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                    <!-- 1. BANGUN TIDUR, MANDI DAN RAPI -->
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl"><i class="ph-duotone ph-sun-horizon"></i></div>
                                <h3 class="font-black text-slate-800 text-lg">1. Bangun & Rapi Diri</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Checklist Bangun -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                    <label class="text-sm font-bold text-slate-700">Bangun Pagi</label>
                                    <input type="checkbox" name="check_bangun" x-model="habit1" class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500" <?php echo e(($todayEntry->habit_1 ?? false) ? 'checked' : ''); ?>>
                                </div>
                                <!-- Input Jam Bangun -->
                                <div x-show="habit1" x-collapse>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Jam Berapa?</label>
                                    <input type="time" name="habit_1_time" value="<?php echo e($todayEntry->habit_1_time ?? old('habit_1_time')); ?>" class="w-full text-sm rounded-xl border-slate-200">
                                </div>
                                <!-- Checklist Mandi -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-cyan-50 border border-cyan-100">
                                    <label class="text-sm font-bold text-slate-700">Mandi & Berpakaian Rapi</label>
                                    <input type="checkbox" name="check_mandi" class="w-5 h-5 rounded text-cyan-600 focus:ring-cyan-500" <?php echo e(($todayEntry->habit_2 ?? false) ? 'checked' : ''); ?>>
                                </div>
                            </div>
                        </div>

                        <!-- 2. SHALAT TEPAT WAKTU (5 WAKTU + DHUHA) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group md:row-span-2">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl"><i class="ph-duotone ph-mosque"></i></div>
                                <h3 class="font-black text-slate-800 text-lg">2. Shalat Tepat Waktu</h3>
                            </div>
                            
                            <div class="space-y-3">
                                <?php 
                                    $prayers = [
                                        ['key' => 'prayer_subuh', 'label' => 'Subuh'],
                                        ['key' => 'prayer_dhuha', 'label' => 'Dhuha (Sunnah)', 'scan' => true],
                                        ['key' => 'prayer_dzuhur', 'label' => 'Dzuhur', 'scan' => true],
                                        ['key' => 'prayer_ashar', 'label' => 'Ashar'],
                                        ['key' => 'prayer_maghrib', 'label' => 'Maghrib'],
                                        ['key' => 'prayer_isya', 'label' => 'Isya'],
                                    ]; 
                                ?>

                                <?php $__currentLoopData = $prayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center justify-between p-3 rounded-2xl border transition-all cursor-pointer hover:bg-emerald-50 <?php echo e(($todayEntry->{$p['key']} ?? false) ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-slate-100'); ?>">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-bold <?php echo e(($todayEntry->{$p['key']} ?? false) ? 'text-emerald-700' : 'text-slate-600'); ?>"><?php echo e($p['label']); ?></span>
                                            <?php if(isset($p['scan'])): ?>
                                                <span class="text-[9px] px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-bold border border-slate-200">SCAN/MANUAL</span>
                                            <?php endif; ?>
                                        </div>
                                        <input type="checkbox" name="<?php echo e($p['key']); ?>" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" <?php echo e(($todayEntry->{$p['key']} ?? false) ? 'checked' : ''); ?>>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- 3. BEROLAHRAGA -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl"><i class="ph-duotone ph-sneaker-move"></i></div>
                                <div class="flex-1 flex justify-between items-center">
                                    <h3 class="font-black text-slate-800 text-lg">3. Berolahraga</h3>
                                    <input type="checkbox" name="check_olahraga" x-model="habit3" class="w-6 h-6 rounded-full text-indigo-600" <?php echo e(($todayEntry->habit_3 ?? false) ? 'checked' : ''); ?>>
                                </div>
                            </div>
                            <div x-show="habit3" x-collapse>
                                <input type="text" name="habit_3_activity" value="<?php echo e($todayEntry->habit_3_activity ?? ''); ?>" placeholder="Jenis Olahraga (Cth: Lari Pagi)" class="w-full text-sm rounded-xl border-slate-200">
                            </div>
                        </div>

                        <!-- 4. MAKAN BERGIZI (URUTAN NAIK KE NO 4) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl"><i class="ph-duotone ph-carrot"></i></div>
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <h3 class="font-black text-slate-800 text-lg">4. Makan Bergizi</h3>
                                        <?php if($todayEntry && $todayEntry->habit_5): ?>
                                            <span class="text-[9px] text-emerald-500 font-bold uppercase"><i class="ph-fill ph-check-circle"></i> Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="text-[9px] text-orange-500 font-bold uppercase"><i class="ph-bold ph-qr-code"></i> Scan Otamatis oleh TIM MBG</span>
                                        <?php endif; ?>
                                    </div>
                                    <input type="checkbox" name="check_makan" x-model="habit5" class="w-6 h-6 rounded-full text-orange-600" <?php echo e(($todayEntry->habit_5 ?? false) ? 'checked' : ''); ?>>
                                </div>
                            </div>
                            <div x-show="habit5" x-collapse>
                                <textarea name="habit_5_menu" rows="2" placeholder="Menu Makanan" class="w-full text-sm rounded-xl border-slate-200"><?php echo e($todayEntry->habit_5_menu ?? ''); ?></textarea>
                            </div>
                        </div>

                        <!-- 5. GEMAR BELAJAR (URUTAN TURUN KE NO 5) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl"><i class="ph-duotone ph-book-open-text"></i></div>
                                <div class="flex-1 flex justify-between items-center">
                                    <h3 class="font-black text-slate-800 text-lg">5. Gemar Belajar</h3>
                                    <input type="checkbox" name="check_belajar" x-model="habit4" class="w-6 h-6 rounded-full text-blue-600" <?php echo e(($todayEntry->habit_4 ?? false) ? 'checked' : ''); ?>>
                                </div>
                            </div>
                            <div x-show="habit4" x-collapse>
                                <input type="text" name="habit_4_subject" value="<?php echo e($todayEntry->habit_4_subject ?? ''); ?>" placeholder="Mata Pelajaran / Topik" class="w-full text-sm rounded-xl border-slate-200">
                            </div>
                        </div>

                        <!-- 6. BERMASYARAKAT / BANTU ORTU -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl"><i class="ph-duotone ph-users-three"></i></div>
                                <div class="flex-1 flex justify-between items-center">
                                    <h3 class="font-black text-slate-800 text-lg">6. Bermasyarakat/Bantu Orang Tua</h3>
                                    <input type="checkbox" name="check_sosial" x-model="habit6" class="w-6 h-6 rounded-full text-indigo-600" <?php echo e(($todayEntry->habit_6 ?? false) ? 'checked' : ''); ?>>
                                </div>
                            </div>
                            <div x-show="habit6" x-collapse>
                                <textarea name="habit_6_activity" rows="2" placeholder="Kegiatan Membantu" class="w-full text-sm rounded-xl border-slate-200"><?php echo e($todayEntry->habit_6_activity ?? ''); ?></textarea>
                            </div>
                        </div>

                        <!-- 7. TIDUR CEPAT -->
                        <div class="md:col-span-2 bg-slate-900 p-8 rounded-[2.5rem] shadow-xl text-white group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 text-blue-300 flex items-center justify-center text-2xl"><i class="ph-duotone ph-moon-stars"></i></div>
                                    <div>
                                        <h3 class="font-black text-white text-lg">7. Tidur Cepat</h3>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Istirahat Semalam</p>
                                    </div>
                                </div>
                                <input type="checkbox" name="check_tidur" x-model="habit7" class="w-6 h-6 rounded-full text-emerald-500 bg-white/10 border-none" <?php echo e(($todayEntry->habit_7 ?? false) ? 'checked' : ''); ?>>
                            </div>
                            <div x-show="habit7" x-collapse>
                                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl">
                                    <span class="text-sm text-slate-300">Jam Tidur:</span>
                                    <input type="time" name="habit_7_time" value="<?php echo e($todayEntry->habit_7_time ?? ''); ?>" class="bg-transparent border-none text-white focus:ring-0">
                                </div>
                            </div>
                        </div>
                    </div>
                        

                    
                    <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-sm border <?php echo e($errors->has('habit_photo') ? 'border-red-300 bg-red-50' : 'border-slate-100'); ?> text-center relative overflow-hidden group">
                        
                        <h3 class="font-black text-slate-800 text-xl mb-4">Lengkapi Dokumentasi Misi</h3>
                        
                        
                        <?php if($todayEntry && $todayEntry->photo_path): ?>
                             <p class="text-emerald-500 font-bold text-sm mb-4"><i class="ph-fill ph-check-circle"></i> Foto sudah diupload. Upload ulang untuk mengganti.</p>
                        <?php else: ?>
                             <p class="text-slate-400 text-sm mb-8 max-w-sm mx-auto">Upload foto kolase saat kamu melakukan berbagai kebiasaan baik hari ini. <span class="text-red-500">*</span></p>
                        <?php endif; ?>

                        <div class="flex justify-center w-full">
                            <label for="habit_photo" class="flex flex-col items-center justify-center w-full max-w-2xl h-72 border-2 border-dashed border-slate-200 rounded-[2.5rem] cursor-pointer bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-400 transition-all group relative overflow-hidden">
                                
                                <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-10 transition-transform duration-500 group-hover:scale-105">
                                
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-20" :class="previewUrl ? 'bg-white/80 p-6 rounded-[2rem] backdrop-blur-md shadow-lg border border-white/50' : ''">
                                    <i class="ph-duotone ph-camera-plus text-5xl text-slate-300 group-hover:text-blue-500 mb-4 transition-colors"></i>
                                    <p class="text-sm text-slate-600 font-bold"><span class="font-black text-blue-600">Pilih Foto</span> Kolase Kegiatan</p>
                                </div>
                                
                                <input id="habit_photo" name="habit_photo" type="file" class="hidden" accept="image/*" @change="fileChosen" <?php echo e(($todayEntry && $todayEntry->photo_path) ? '' : 'required'); ?>>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-end items-center gap-6 pt-6">
                        <button type="submit" class="w-full md:w-auto px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-3xl shadow-2xl shadow-blue-600/30 transition-all transform hover:scale-105 flex items-center justify-center gap-3">
                            <i class="ph-bold ph-floppy-disk text-xl"></i>
                            <?php echo e($todayEntry ? 'Simpan Perubahan' : 'Kirim Jurnal'); ?>

                        </button>
                    </div>
                </form>
            <?php endif; ?>
           
            <div class="pt-16 border-t border-slate-100 animate-enter" style="animation-delay: 200ms">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black text-slate-800 flex items-center gap-4">
                        <i class="ph-bold ph-clock-counter-clockwise text-blue-600"></i> Riwayat Jurnal
                    </h2>
                    <span class="text-xs font-bold text-slate-400 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 uppercase tracking-widest">
                        <?php echo e(\Carbon\Carbon::now()->translatedFormat('F Y')); ?>

                    </span>
                </div>

                <?php if(isset($history) && $history->count() > 0): ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php $__currentLoopData = $history->sortByDesc('report_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:border-blue-200 transition-all group overflow-hidden relative">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-full blur-2xl -mr-12 -mt-12 opacity-50"></div>
                                
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                                    <div class="flex items-center gap-6">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:border-blue-500 transition-all duration-300">
                                            <span class="text-[10px] font-bold text-slate-400 group-hover:text-blue-200 uppercase tracking-tighter"><?php echo e($item->report_date->translatedFormat('D')); ?></span>
                                            <span class="text-2xl font-black text-slate-700 group-hover:text-white leading-none mt-1"><?php echo e($item->report_date->format('d')); ?></span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-slate-800 text-base mb-1">
                                                <?php echo e($item->habit_1 ? 'Bangun Pukul ' . $item->habit_1_time : 'Jurnal Harian'); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2">
                                                <?php if($item->habit_3): ?> <span class="text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg border border-blue-100 font-black uppercase">Olahraga</span> <?php endif; ?>
                                                <?php if($item->habit_4): ?> <span class="text-[9px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-lg border border-indigo-100 font-black uppercase">Belajar</span> <?php endif; ?>
                                                <?php if($item->habit_7): ?> <span class="text-[9px] bg-slate-900 text-blue-300 px-2 py-0.5 rounded-lg font-black uppercase">Tidur</span> <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($item->teacher_feedback): ?>
                                        <div class="flex-1 md:max-w-md bg-blue-50/50 p-4 rounded-2xl border border-blue-100 relative group-hover:bg-white transition-colors shadow-inner">
                                            <p class="text-xs text-blue-700 italic leading-relaxed">
                                                <span class="font-black text-[9px] uppercase not-italic block mb-1 text-blue-400 tracking-wider">Pesan Guru:</span>
                                                "<?php echo e($item->teacher_feedback); ?>"
                                            </p>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-right">
                                            <span class="inline-flex items-center gap-2 text-[10px] font-bold text-slate-300 bg-slate-50 px-3 py-1.5 rounded-full uppercase italic border border-slate-100">
                                                <i class="ph-bold ph-hourglass"></i> Menunggu Feedback
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-[3rem] p-20 text-center border-2 border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ph-duotone ph-notebook text-4xl text-slate-300"></i>
                        </div>
                        <h4 class="font-black text-slate-700 mb-2">Belum Ada Jejak</h4>
                        <p class="text-sm text-slate-400 font-medium max-w-xs mx-auto">Ayo buat catatan kebiasaan pertamamu hari ini untuk memulai perjalanan hebat!</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        input[type="time"]::-webkit-calendar-picker-indicator { opacity: 0.3; }
        .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/habits/student_index.blade.php ENDPATH**/ ?>