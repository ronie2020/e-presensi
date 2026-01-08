<?php $__env->startSection('content'); ?>
    
    <?php \Carbon\Carbon::setLocale('id'); ?>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        <div class="space-y-8">
            
            
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-blue-900/20 overflow-hidden border border-white/10">
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="inline-flex items-center gap-2 text-blue-200 hover:text-white transition-colors mb-4 text-xs font-bold uppercase tracking-widest">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2">Jurnal Kebiasaan Baik</h1>
                        <p class="text-blue-100/90 text-sm max-w-xl leading-relaxed">
                            "Karaktermu ditentukan oleh apa yang kamu lakukan setiap hari." <br>
                            Isi misi harianmu hari ini untuk mengumpulkan poin!
                        </p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20 flex items-center gap-3 shrink-0">
                        <div class="text-right">
                            <p class="text-xs text-blue-200 font-bold uppercase">Tanggal Hari Ini</p>
                            <p class="text-xl font-black"><?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-white text-blue-600 rounded-xl flex items-center justify-center text-2xl font-bold shadow-lg">
                            <i class="ph-fill ph-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($todayEntry): ?>
                
                <div class="space-y-6 animate-enter" style="animation-delay: 100ms">
                    <div class="bg-white rounded-[2.5rem] p-10 text-center border border-blue-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute inset-0 bg-blue-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10">
                            <div class="w-24 h-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-200 animate-bounce">
                                <i class="ph-fill ph-check-square-offset text-5xl"></i>
                            </div>
                            <h2 class="text-2xl font-black text-slate-800 mb-2">Hebat, Kamu Juara!</h2>
                            <p class="text-slate-500 text-base max-w-md mx-auto mb-8">
                                Laporanmu hari ini sudah tersimpan di markas. Tetap konsisten ya!
                            </p>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                                <div class="bg-white px-8 py-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                                    <div class="text-left">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Bangun Pagi</p>
                                        <p class="text-xl font-black text-slate-800"><?php echo e($todayEntry->habit_1_time ?? '-'); ?></p>
                                    </div>
                                    <div class="w-px h-10 bg-slate-100"></div>
                                    <div class="text-left">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Kategori</p>
                                        <p class="text-xl font-black text-blue-600">Lengkap</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all hover:scale-105">
                                    <i class="ph-fill ph-house"></i>
                                    Ke Halaman Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($todayEntry->teacher_feedback): ?>
                        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[2rem] p-8 text-white shadow-xl shadow-blue-200 relative overflow-hidden group">
                            <div class="relative z-10">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl text-blue-100">
                                        <i class="ph-fill ph-user-circle-gear"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-lg">Pesan Motivasi Guru</h3>
                                        <p class="text-xs text-blue-100 font-medium opacity-70">Khusus untukmu hari ini</p>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-md p-5 rounded-2xl border border-white/20 shadow-inner">
                                    <p class="italic text-base leading-relaxed font-medium">"<?php echo e($todayEntry->teacher_feedback); ?>"</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                
                <form action="<?php echo e(route('student.habits.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-8 animate-enter" x-data="{
                    habit1: <?php echo e(old('check_1') || old('habit_1_time') ? 'true' : 'false'); ?>, 
                    habit3: <?php echo e(old('check_3') || old('habit_3_activity') ? 'true' : 'false'); ?>, 
                    habit4: <?php echo e(old('check_4') || old('habit_4_subject') ? 'true' : 'false'); ?>, 
                    habit5: <?php echo e(old('check_5') || old('habit_5_menu') ? 'true' : 'false'); ?>, 
                    habit6: <?php echo e(old('check_6') || old('habit_6_activity') ? 'true' : 'false'); ?>, 
                    habit7: <?php echo e(old('check_7') || old('habit_7_time') ? 'true' : 'false'); ?>,
                    previewUrl: null,
                    fileChosen(event) {
                        const file = event.target.files[0];
                        if (file) { this.previewUrl = URL.createObjectURL(file); }
                    }
                }">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. BANGUN PAGI -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border <?php echo e($errors->has('habit_1_time') ? 'border-red-300 bg-red-50' : 'border-slate-100'); ?> hover:border-blue-400 transition-all hover:-translate-y-1 group">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-blue-100">
                                    <i class="ph-duotone ph-sun-horizon"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800 text-lg">1. Bangun Pagi & Ibadah</h3>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="check_1" x-model="habit1" class="sr-only peer" <?php echo e(old('check_1') ? 'checked' : ''); ?>>
                                            <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div x-show="habit1" x-collapse>
                                        <div class="grid grid-cols-2 gap-3 mt-4">
                                            <div>
                                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Jam Bangun <span class="text-red-500">*</span></label>
                                                <input type="time" name="habit_1_time" value="<?php echo e(old('habit_1_time')); ?>" class="w-full text-sm rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500">
                                                <?php $__errorArgs = ['habit_1_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-[10px] text-red-500 font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Ibadah</label>
                                                <input type="text" name="habit_1_note" value="<?php echo e(old('habit_1_note')); ?>" placeholder="Sholat Subuh" class="w-full text-sm rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. KEBERSIHAN -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all hover:-translate-y-1">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-cyan-100">
                                    <i class="ph-duotone ph-drop"></i>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <h3 class="font-bold text-slate-800 text-lg">2. Mandi & Rapi</h3>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="check_2" class="sr-only peer" <?php echo e(old('check_2') ? 'checked' : ''); ?>>
                                        <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 3. OLAHRAGA -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all hover:-translate-y-1 group">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-indigo-100">
                                    <i class="ph-duotone ph-sneaker-move"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800 text-lg">3. Olahraga</h3>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="check_3" x-model="habit3" class="sr-only peer" <?php echo e(old('check_3') ? 'checked' : ''); ?>>
                                            <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div x-show="habit3" x-collapse>
                                        <input type="text" name="habit_3_activity" value="<?php echo e(old('habit_3_activity')); ?>" placeholder="Jenis Olahraga" class="mt-2 w-full text-sm rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. BELAJAR -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all hover:-translate-y-1 group">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-blue-100">
                                    <i class="ph-duotone ph-book-open-text"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800 text-lg">4. Belajar Mandiri</h3>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="check_4" x-model="habit4" class="sr-only peer" <?php echo e(old('check_4') ? 'checked' : ''); ?>>
                                            <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div x-show="habit4" x-collapse>
                                        <input type="text" name="habit_4_subject" value="<?php echo e(old('habit_4_subject')); ?>" placeholder="Materi yang dipelajari" class="mt-2 w-full text-sm rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. MAKAN SEHAT -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all hover:-translate-y-1 group">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-cyan-100">
                                    <i class="ph-duotone ph-carrot"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800 text-lg">5. Makan Sehat</h3>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="check_5" x-model="habit5" class="sr-only peer" <?php echo e(old('check_5') ? 'checked' : ''); ?>>
                                            <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div x-show="habit5" x-collapse>
                                        <textarea name="habit_5_menu" rows="2" placeholder="Menu makanan" class="mt-2 w-full text-sm rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500"><?php echo e(old('habit_5_menu')); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. BERMASYARAKAT -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all hover:-translate-y-1 group">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-indigo-100">
                                    <i class="ph-duotone ph-users-three"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800 text-lg">6. Bermasyarakat</h3>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="check_6" x-model="habit6" class="sr-only peer" <?php echo e(old('check_6') ? 'checked' : ''); ?>>
                                            <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div x-show="habit6" x-collapse>
                                        <textarea name="habit_6_activity" rows="2" placeholder="Membantu orang tua" class="mt-2 w-full text-sm rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500"><?php echo e(old('habit_6_activity')); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 7. TIDUR CUKUP -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all hover:-translate-y-1 group md:col-span-2">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-slate-900 text-blue-400 flex items-center justify-center text-3xl shrink-0 shadow-sm">
                                    <i class="ph-duotone ph-moon-stars"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800 text-lg">7. Tidur Cukup</h3>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="check_7" x-model="habit7" class="sr-only peer" <?php echo e(old('check_7') ? 'checked' : ''); ?>>
                                            <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div x-show="habit7" x-collapse>
                                        <div class="flex items-center gap-4 mt-2">
                                            <span class="text-sm text-slate-500 font-medium">Saya tidur pukul:</span>
                                            <input type="time" name="habit_7_time" value="<?php echo e(old('habit_7_time')); ?>" class="w-32 text-sm rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white p-8 rounded-3xl shadow-sm border <?php echo e($errors->has('habit_photo') ? 'border-red-300 bg-red-50' : 'border-slate-100'); ?> text-center">
                        <h3 class="font-bold text-slate-800 text-lg mb-4">Bukti Dokumentasi Hari Ini <span class="text-red-500">*</span></h3>
                        
                        <?php $__errorArgs = ['habit_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 font-bold text-sm mb-3"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <div class="flex justify-center w-full">
                            <label for="habit_photo" class="flex flex-col items-center justify-center w-full h-64 border-2 <?php echo e($errors->has('habit_photo') ? 'border-red-300' : 'border-slate-300'); ?> border-dashed rounded-3xl cursor-pointer bg-slate-50 hover:bg-blue-50 hover:border-blue-400 transition-all group relative overflow-hidden">
                                
                                <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-10">
                                
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-20" :class="previewUrl ? 'bg-white/80 p-4 rounded-xl backdrop-blur-sm shadow-sm' : ''">
                                    <i class="ph-duotone ph-camera-plus text-4xl text-slate-400 group-hover:text-blue-500 mb-3 transition-colors"></i>
                                    <p class="text-sm text-slate-500 font-bold"><span class="font-black text-blue-600">Upload Foto</span> Kolase Kegiatan</p>
                                    <p class="text-xs text-slate-400 mt-1">JPG, PNG (Maks. 3MB)</p>
                                </div>
                                <input id="habit_photo" name="habit_photo" type="file" class="hidden" accept="image/*" @change="fileChosen">
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="w-full md:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-600/20 transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-paper-plane-right text-xl"></i>
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            
            <div class="pt-8 border-t border-slate-100 animate-enter" style="animation-delay: 200ms">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        <i class="ph-fill ph-clock-counter-clockwise text-blue-600"></i> Riwayat Jurnal Bulan Ini
                    </h2>
                    <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full uppercase tracking-wider">
                        <?php echo e(\Carbon\Carbon::now()->translatedFormat('F Y')); ?>

                    </span>
                </div>

                <?php if(isset($history) && $history->count() > 0): ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php $__currentLoopData = $history->sortByDesc('report_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:border-blue-200 transition-all group">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center shrink-0 group-hover:bg-blue-50 group-hover:border-blue-100 transition-colors">
                                            <span class="text-[10px] font-bold text-slate-400 group-hover:text-blue-400 uppercase"><?php echo e($item->report_date->translatedFormat('D')); ?></span>
                                            <span class="text-lg font-black text-slate-700 group-hover:text-blue-600"><?php echo e($item->report_date->format('d')); ?></span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm">
                                                <?php echo e($item->habit_1 ? 'Bangun Pukul ' . $item->habit_1_time : 'Jurnal Kebiasaan'); ?>

                                            </h4>
                                            <div class="flex gap-2 mt-1">
                                                <?php if($item->habit_3): ?> <span class="text-[9px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded border border-blue-100 font-bold uppercase">Olahraga</span> <?php endif; ?>
                                                <?php if($item->habit_4): ?> <span class="text-[9px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded border border-indigo-100 font-bold uppercase">Belajar</span> <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($item->teacher_feedback): ?>
                                        <div class="flex-1 md:max-w-md bg-blue-50 p-3 rounded-xl border border-blue-100 relative">
                                            <i class="ph-fill ph-chat-circle-dots absolute -top-2 -left-2 text-xl text-blue-500 bg-white rounded-full"></i>
                                            <p class="text-xs text-blue-700 italic leading-relaxed">
                                                <span class="font-bold text-[10px] uppercase not-italic block mb-0.5 text-blue-400">Pesan Guru:</span>
                                                "<?php echo e($item->teacher_feedback); ?>"
                                            </p>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-right">
                                            <span class="text-[10px] font-bold text-slate-300 uppercase italic">Belum ada feedback</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="bg-slate-50 rounded-3xl p-16 text-center border border-dashed border-slate-200">
                        <i class="ph-duotone ph-notebook text-4xl text-slate-300 mb-2"></i>
                        <p class="text-sm text-slate-500 font-medium">Belum ada riwayat jurnal bulan ini.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/student_index.blade.php ENDPATH**/ ?>