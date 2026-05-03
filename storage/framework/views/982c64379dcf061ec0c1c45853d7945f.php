<?php $__env->startSection('content'); ?>
    <?php \Carbon\Carbon::setLocale('id'); ?>

    <style>
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
    </style>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24 font-sans text-[#2A3B52] bg-[#f8fafc] min-h-screen">
        
        <div class="space-y-6 md:space-y-8">
            
            
            <div class="relative rounded-2xl md:rounded-[2.5rem] bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 md:p-10 mb-6 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40">
               <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
               <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/30 rounded-full blur-[80px] pointer-events-none"></div>

               <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="inline-flex items-center gap-2 bg-white/40 hover:bg-white/60 text-[#2A3B52] px-4 py-2 rounded-xl transition-colors text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm border border-white/50 shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 text-[#2A3B52]">Jurnal Misi Harian</h1>
                        <p class="text-[#2A3B52]/80 text-sm max-w-xl leading-relaxed font-medium">
                            "Amalan yang paling dicintai Allah adalah amalan yang rutin dilakukan meskipun sedikit."
                        </p>
                    </div>
                    <div class="hidden md:block">
                         <div class="w-16 h-16 bg-white/40 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/50 shadow-sm">
                            <i class="ph-duotone ph-pencil-simple-line text-3xl text-[#5295FF]"></i>
                        </div>
                    </div>
               </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="bg-[#FDE7E9] border border-[#F4C3C9] rounded-xl p-5 mb-6 fluent-card">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-warning-circle text-[#D13438] text-lg"></i>
                        <h3 class="font-bold text-[#D13438]">Periksa Kembali Isian Anda</h3>
                    </div>
                    <ul class="list-disc list-inside text-sm text-[#D13438]/80 space-y-1 ml-1 font-medium">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('student.habits.store')); ?>" method="POST" enctype="multipart/form-data" id="habitForm" 
                  x-data="{ isSubmitting: false }" 
                  @submit="isSubmitting = true">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">

                    <!-- 1. BANGUN PAGI -->
                    <label class="bg-white p-6 rounded-2xl fluent-card relative group cursor-pointer hover:border-[#5295FF] md:col-span-2">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="ph-duotone ph-sun-horizon"></i></div>
                            <div class="flex-1">
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">1. Bangun Pagi</h3>
                                <p class="text-sm text-slate-500 mb-4 font-medium">Apakah kamu bangun sebelum adzan subuh hari ini?</p>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="habit_1" class="w-5 h-5 rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF]" 
                                            <?php echo e(old('habit_1', $todayEntry->habit_1 ?? false) ? 'checked' : ''); ?>>
                                        <span class="ml-2 font-bold text-[#2A3B52] text-sm">Ya, bangun pagi</span>
                                    </div>
                                    <input type="time" name="habit_1_time" value="<?php echo e(old('habit_1_time', $todayEntry->habit_1_time ?? '')); ?>" class="rounded-lg border-slate-200 text-sm font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] shadow-sm bg-slate-50 focus:bg-white">
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- 2. IBADAH HARIAN -->
                    <div class="bg-white p-6 md:p-8 rounded-2xl fluent-card md:row-span-2"
                        x-data="{ 
                            isUdzur: <?php echo e(old('is_udzur_syar_i', $todayEntry->is_udzur_syar_i ?? false) ? 'true' : 'false'); ?>,
                            init() {
                                this.$watch('isUdzur', value => {
                                    if(value) {
                                        const prayerIds = ['prayer_subuh', 'prayer_dhuha', 'prayer_dzuhur', 'prayer_ashar', 'prayer_maghrib', 'prayer_isya'];
                                        prayerIds.forEach(id => {
                                            let el = document.getElementById(id);
                                            if(el && !el.disabled) el.checked = false; 
                                        });
                                    }
                                });
                            }
                        }">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] flex items-center justify-center text-2xl shadow-sm"><i class="ph-duotone ph-mosque"></i></div>
                                <div>
                                    <h3 class="font-bold text-[#2A3B52] text-lg">2. Ibadah Harian</h3>
                                    <p class="text-xs text-slate-500 font-medium">Shalat & Tadarus</p>
                                </div>
                            </div>

                            <label class="flex items-center gap-2 cursor-pointer bg-[#FDE7E9] px-3 py-1.5 rounded-lg border border-[#F4C3C9] hover:bg-[#FCE0E3] transition-colors shadow-sm">
                                <input type="checkbox" name="is_udzur_syar_i" value="1" x-model="isUdzur" class="w-4 h-4 rounded text-[#D13438] focus:ring-[#D13438] border-[#F4C3C9]">
                                <span class="text-xs font-bold text-[#D13438] select-none">Sedang Udzur</span>
                            </label>
                        </div>
                        
                        
                        <div class="space-y-2 mb-6 transition-all duration-300" :class="isUdzur ? 'opacity-50 grayscale pointer-events-none' : ''">
                            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <i class="ph-bold ph-hands-praying"></i> A. Shalat Wajib & Sunnah
                            </h4>

                            <div x-show="isUdzur" class="mb-3 p-3 bg-[#FDE7E9] border border-[#F4C3C9] rounded-lg text-[#D13438] text-xs font-bold" style="display: none;">
                                Alhamdulillah, istirahat adalah ibadah bagi yang udzur.
                            </div>

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
                                <?php
                                    $isVerifiedSchool = false;
                                    if($p['key'] == 'prayer_dhuha') $isVerifiedSchool = $schoolDhuha ?? false;
                                    if($p['key'] == 'prayer_dzuhur') $isVerifiedSchool = $schoolDzuhur ?? false;

                                    $isChecked = old($p['key'], $todayEntry->{$p['key']} ?? false) || $isVerifiedSchool;
                                ?>

                                <label class="flex items-center justify-between p-3 rounded-lg border transition-all 
                                    <?php echo e($isVerifiedSchool 
                                        ? 'bg-[#F3F9FD] border-[#D0E7F8] cursor-not-allowed opacity-90' 
                                        : ($isChecked ? 'bg-[#DFF6DD] border-[#B7DFB9] cursor-pointer' : 'bg-slate-50 border-slate-200 cursor-pointer hover:bg-white hover:border-[#5295FF]')); ?>">
                                    
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold <?php echo e($isChecked ? ($isVerifiedSchool ? 'text-[#5295FF]' : 'text-[#107C10]') : 'text-slate-600'); ?>">
                                            <?php echo e($p['label']); ?>

                                        </span>
                                        <?php if($isVerifiedSchool): ?>
                                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-white text-[#5295FF] font-bold border border-[#D0E7F8]">TERVERIFIKASI</span>
                                        <?php endif; ?>
                                    </div>

                                    <input type="checkbox" name="<?php echo e($p['key']); ?>" id="<?php echo e($p['key']); ?>" value="1"
                                        class="w-5 h-5 rounded focus:ring-[#107C10] <?php echo e($isVerifiedSchool ? 'text-[#5295FF] border-[#D0E7F8] bg-white' : 'text-[#107C10] border-slate-300'); ?>" 
                                        <?php echo e($isChecked ? 'checked' : ''); ?> <?php echo e($isVerifiedSchool ? 'disabled' : ''); ?>>
                                    
                                    <?php if($isVerifiedSchool): ?> <input type="hidden" name="<?php echo e($p['key']); ?>" value="1"> <?php endif; ?>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        
                        <div class="pt-6 border-t border-slate-100">
                            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <i class="ph-bold ph-microphone-stage text-[#5295FF]"></i> B. One Day One Ayat (ODOA)
                            </h4>

                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="col-span-2">
                                    <input type="text" name="odoa_surah" value="<?php echo e(old('odoa_surah', $todayEntry->odoa_surah ?? '')); ?>" placeholder="Nama Surat" class="w-full text-sm rounded-lg border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] font-medium bg-slate-50 focus:bg-white shadow-sm text-[#2A3B52]">
                                </div>
                                <div class="col-span-1">
                                    <input type="text" name="odoa_ayat" value="<?php echo e(old('odoa_ayat', $todayEntry->odoa_ayat ?? '')); ?>" placeholder="Ayat" class="w-full text-sm rounded-lg border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] text-center font-medium bg-slate-50 focus:bg-white shadow-sm text-[#2A3B52]">
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 text-center relative" x-data="audioRecorder">
                                <?php if($todayEntry && $todayEntry->odoa_audio_path): ?>
                                    <div class="mb-4 bg-white p-3 rounded-lg border border-[#D0E7F8] flex items-center gap-3 shadow-sm" x-show="!isRecording && !audioBlob">
                                        <div class="w-8 h-8 rounded-md bg-[#5295FF] text-white flex items-center justify-center shrink-0">
                                            <i class="ph-fill ph-play"></i>
                                        </div>
                                        <div class="text-left flex-1 overflow-hidden">
                                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wide">Rekaman Tersimpan</p>
                                            <audio controls class="w-full h-8 mt-1"><source src="<?php echo e(asset('storage/'.$todayEntry->odoa_audio_path)); ?>" type="audio/mpeg"></audio>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="flex flex-col items-center gap-3">
                                    <button type="button" @click="toggleRecording" 
                                        class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-sm border-2"
                                        :class="isRecording ? 'bg-[#D13438] border-[#F4C3C9] animate-pulse text-white' : 'bg-white border-slate-200 text-[#107C10] hover:border-[#B7DFB9] hover:bg-[#DFF6DD]'">
                                        <i class="text-2xl" :class="isRecording ? 'ph-fill ph-stop' : 'ph-fill ph-microphone'"></i>
                                    </button>

                                    <div class="text-center">
                                        <p class="text-xs font-bold text-[#2A3B52]" x-text="isRecording ? 'Merekam...' : (audioBlob ? 'Selesai' : 'Mulai Rekam')"></p>
                                    </div>
                                </div>

                                <div x-show="audioBlob" class="mt-4 pt-4 border-t border-slate-200" style="display: none;">
                                    <div class="bg-[#DFF6DD] p-3 rounded-lg border border-[#B7DFB9]">
                                        <p class="text-[10px] font-bold text-[#107C10] mb-2 uppercase tracking-wide"><i class="ph-fill ph-check-circle"></i> Siap Diupload</p>
                                        <audio x-ref="audioPlayer" controls class="w-full h-8 mb-2"></audio>
                                        <button type="button" @click="resetRecording" class="text-[10px] text-[#D13438] font-bold hover:underline w-full text-center">Hapus & Rekam Ulang</button>
                                    </div>
                                </div>
                                <input type="file" name="odoa_audio" x-ref="audioInput" class="hidden" accept="audio/*">
                            </div>
                        </div>
                    </div>

                    <!-- 3. KEBERSIHAN -->
                    <label class="bg-white p-6 rounded-2xl fluent-card relative group cursor-pointer hover:border-[#5295FF] transition-all">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="ph-duotone ph-drop"></i></div>
                            <div>
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">3. Mandi & Gosok Gigi</h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <input type="checkbox" name="habit_2" class="w-5 h-5 rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF]" 
                                        <?php echo e(old('habit_2', $todayEntry->habit_2 ?? false) ? 'checked' : ''); ?>>
                                    <span class="font-bold text-slate-600 text-sm">Sudah Mandi</span>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- 4. OLAHRAGA -->
                    <div class="bg-white p-6 rounded-2xl fluent-card relative hover:border-[#5295FF] transition-all">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-lg bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8] flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="ph-duotone ph-sneaker-move"></i></div>
                            <div>
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">4. Olahraga</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="habit_3" class="w-5 h-5 rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF]" 
                                        <?php echo e(old('habit_3', $todayEntry->habit_3 ?? false) ? 'checked' : ''); ?>>
                                    <span class="font-bold text-slate-600 text-sm">Aktivitas fisik</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="habit_3_activity" value="<?php echo e(old('habit_3_activity', $todayEntry->habit_3_activity ?? '')); ?>" placeholder="Contoh: Lari pagi" class="w-full text-sm rounded-lg border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] bg-slate-50 focus:bg-white font-medium text-[#2A3B52] shadow-sm">
                    </div>

                    <!-- 5. MAKAN SEHAT -->
                    <?php
                        $schoolMenu = $schoolMbgMenu ?? null; 
                        $displayValue = $schoolMenu ? $schoolMenu : old('habit_5_menu', $todayEntry->habit_5_menu ?? '');
                        $isLockedMBG = !empty($schoolMenu);
                    ?>
                    <div class="bg-white p-6 rounded-2xl fluent-card relative transition-all <?php echo e($isLockedMBG ? 'border-[#B7DFB9] bg-[#DFF6DD]/30' : 'hover:border-[#5295FF]'); ?>">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-lg bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9] flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="ph-duotone ph-carrot"></i></div>
                            <div>
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">5. Makan Bergizi</h3>
                                <?php if($isLockedMBG): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-[#107C10] text-white text-[9px] font-bold uppercase"><i class="ph-fill ph-qr-code"></i> AUTO DATA</span>
                                    <input type="hidden" name="habit_5" value="1">
                                <?php else: ?>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="habit_5" class="w-5 h-5 rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF]" <?php echo e(old('habit_5', $todayEntry->habit_5 ?? false) ? 'checked' : ''); ?>>
                                        <span class="font-bold text-slate-600 text-sm">Makan sehat</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <input type="text" name="habit_5_menu" value="<?php echo e($displayValue); ?>" placeholder="Menu..." class="w-full text-sm rounded-lg border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] font-medium text-[#2A3B52] shadow-sm <?php echo e($isLockedMBG ? 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9] font-bold cursor-not-allowed' : 'bg-slate-50 focus:bg-white'); ?>" <?php echo e($isLockedMBG ? 'readonly' : ''); ?>>
                    </div>

                    <!-- 6. BELAJAR -->
                    <div class="bg-white p-6 rounded-2xl fluent-card relative hover:border-[#5295FF] transition-all">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 text-[#2A3B52] border border-slate-200 flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="ph-duotone ph-book-open-text"></i></div>
                            <div>
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">6. Gemar Belajar</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="habit_4" class="w-5 h-5 rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF]" <?php echo e(old('habit_4', $todayEntry->habit_4 ?? false) ? 'checked' : ''); ?>>
                                    <span class="font-bold text-slate-600 text-sm">Belajar di rumah</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="habit_4_subject" value="<?php echo e(old('habit_4_subject', $todayEntry->habit_4_subject ?? '')); ?>" placeholder="Materi yang dipelajari..." class="w-full text-sm rounded-lg border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] bg-slate-50 focus:bg-white font-medium text-[#2A3B52] shadow-sm">
                    </div>

                    <!-- 7. SOSIAL -->
                    <div class="bg-white p-6 rounded-2xl fluent-card relative hover:border-[#5295FF] transition-all">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="ph-duotone ph-users-three"></i></div>
                            <div>
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">7. Bantu Orang Tua</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="habit_6" class="w-5 h-5 rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF]" <?php echo e(old('habit_6', $todayEntry->habit_6 ?? false) ? 'checked' : ''); ?>>
                                    <span class="font-bold text-slate-600 text-sm">Melakukan kebaikan</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="habit_6_activity" value="<?php echo e(old('habit_6_activity', $todayEntry->habit_6_activity ?? '')); ?>" placeholder="Contoh: Menyapu" class="w-full text-sm rounded-lg border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] bg-slate-50 focus:bg-white font-medium text-[#2A3B52] shadow-sm">
                    </div>

                    <!-- 8. TIDUR -->
                    <label class="bg-white p-6 rounded-2xl fluent-card relative group cursor-pointer hover:border-[#5295FF] transition-all">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-lg bg-[#2A3B52] text-white border border-slate-700 flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="ph-duotone ph-moon-stars"></i></div>
                            <div class="flex-1">
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">8. Tidur Teratur</h3>
                                <p class="text-xs text-slate-500 mb-3 font-medium">Maksimal jam 22.00 malam.</p>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="habit_7" class="w-5 h-5 rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF]" <?php echo e(old('habit_7', $todayEntry->habit_7 ?? false) ? 'checked' : ''); ?>>
                                        <span class="ml-2 font-bold text-[#2A3B52] text-sm">Ya, teratur</span>
                                    </div>
                                    <input type="time" name="habit_7_time" value="<?php echo e(old('habit_7_time', $todayEntry->habit_7_time ?? '')); ?>" class="rounded-lg border-slate-200 text-sm font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] bg-slate-50 focus:bg-white shadow-sm">
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- FOTO -->
                    <div class="bg-white p-6 md:p-8 rounded-2xl fluent-card md:col-span-2" x-data="{ photoPreview: null }">
                        <div class="flex flex-col md:flex-row items-center gap-5">
                            <div class="w-14 h-14 rounded-lg bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8] flex items-center justify-center text-3xl shrink-0 shadow-sm"><i class="ph-duotone ph-camera"></i></div>
                            <div class="flex-1 text-center md:text-left w-full">
                                <h3 class="font-bold text-[#2A3B52] text-lg mb-1">Bukti Foto Kegiatan</h3>
                                <p class="text-slate-500 text-xs font-medium mb-3">Upload foto kegiatan (Max 5MB).</p>
                                <input type="file" name="habit_photo" accept="image/png, image/jpeg, image/jpg, image/webp"
                                       @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => photoPreview = e.target.result; reader.readAsDataURL(file); } else { photoPreview = null; }"
                                       class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#F3F9FD] file:text-[#5295FF] hover:file:bg-[#D0E7F8] transition cursor-pointer border border-slate-200 rounded-lg bg-slate-50">
                            </div>
                        </div>
                        
                        <div x-show="photoPreview" class="mt-5 p-4 border border-[#B7DFB9] rounded-xl bg-[#DFF6DD]/50" style="display: none;">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[10px] font-bold text-[#107C10] uppercase"><i class="ph-fill ph-check-circle"></i> Pratinjau Foto</p>
                                <button type="button" @click="photoPreview = null; $el.closest('div[x-data]').querySelector('input[type=file]').value = ''" class="text-[10px] text-[#D13438] font-bold hover:underline">Hapus Foto</button>
                            </div>
                            <img :src="photoPreview" class="h-48 w-full object-contain bg-white rounded-lg border border-slate-200 shadow-sm">
                        </div>

                        <?php if($todayEntry && $todayEntry->photo_path): ?>
                            <div x-show="!photoPreview" class="mt-5 p-4 border border-slate-200 rounded-xl bg-slate-50">
                                <p class="text-[10px] font-bold text-slate-500 uppercase mb-2">Foto Tersimpan:</p>
                                <img src="<?php echo e(asset('storage/' . $todayEntry->photo_path)); ?>" class="h-48 w-full object-contain bg-white rounded-lg border border-slate-200 shadow-sm">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="mt-8 mb-12">
                    <button type="submit" 
                            :disabled="isSubmitting" 
                            :class="isSubmitting ? 'opacity-70 cursor-not-allowed' : 'hover:bg-[#3b7ee6]'"
                            class="w-full py-4 bg-[#5295FF] text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold" :class="isSubmitting ? 'ph-spinner animate-spin text-xl' : 'ph-paper-plane-right text-lg'"></i>
                        <span x-text="isSubmitting ? 'MENYIMPAN JURNAL...' : 'SIMPAN JURNAL SAYA'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('audioRecorder', () => ({
                isRecording: false, isSupported: true, mediaRecorder: null, audioChunks: [], audioBlob: null, recordingTime: 0, timerInterval: null, mimeType: '',
                init() {
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) { this.isSupported = false; } 
                    else { const types = ['audio/webm', 'audio/mp4', 'audio/ogg', 'audio/aac']; for (let type of types) { if (MediaRecorder.isTypeSupported(type)) { this.mimeType = type; break; } } if(!this.mimeType) this.mimeType = ''; }
                },
                async toggleRecording() { if (this.isRecording) { this.stopRecording(); } else { await this.startRecording(); } },
                async startRecording() {
                    if (!this.isSupported) { alert("Browser tidak mendukung perekaman audio."); return; }
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        const options = this.mimeType ? { mimeType: this.mimeType } : {};
                        this.mediaRecorder = new MediaRecorder(stream, options);
                        this.audioChunks = [];
                        this.mediaRecorder.ondataavailable = e => { if (e.data.size > 0) this.audioChunks.push(e.data); };
                        this.mediaRecorder.onstop = () => {
                            const actualMimeType = this.mediaRecorder.mimeType || 'audio/webm';
                            this.audioBlob = new Blob(this.audioChunks, { type: actualMimeType });
                            this.$refs.audioPlayer.src = URL.createObjectURL(this.audioBlob);
                            const ext = actualMimeType.includes('mp4') ? 'mp4' : 'webm';
                            const file = new File([this.audioBlob], "rec_" + Date.now() + "." + ext, { type: actualMimeType });
                            const dataTransfer = new DataTransfer(); dataTransfer.items.add(file); this.$refs.audioInput.files = dataTransfer.files;
                        };
                        this.mediaRecorder.start(); this.isRecording = true; this.recordingTime = 0;
                        this.timerInterval = setInterval(() => { this.recordingTime++; if(this.recordingTime >= 300) this.stopRecording(); }, 1000);
                    } catch (err) { alert("Gagal akses mikrofon."); }
                },
                stopRecording() { if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') { this.mediaRecorder.stop(); this.mediaRecorder.stream.getTracks().forEach(t => t.stop()); } this.isRecording = false; clearInterval(this.timerInterval); },
                resetRecording() { this.audioBlob = null; this.audioChunks = []; this.$refs.audioPlayer.src = ''; this.$refs.audioInput.value = ''; },
                formatTime(s) { const m = Math.floor(s/60); const sec = s%60; return `${m}:${sec<10?'0':''}${sec}`; }
            }));
        });
    </script>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: "<?php echo e(session('success')); ?>",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                    customClass: { popup: 'rounded-xl shadow-sm border border-[#B7DFB9] bg-white' }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "<?php echo e(session('error')); ?>",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                    customClass: { popup: 'rounded-xl shadow-sm border border-[#F4C3C9] bg-white' }
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/student_index.blade.php ENDPATH**/ ?>