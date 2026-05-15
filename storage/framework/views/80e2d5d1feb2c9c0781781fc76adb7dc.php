<div class="space-y-8 font-jakarta">
    
    <div class="flex items-center gap-5 border-b border-slate-100 pb-6">
        <div class="w-16 h-16 rounded-2xl bg-[#F3F9FD] flex items-center justify-center overflow-hidden border border-[#D0E7F8] shadow-sm">
            <?php if($habit->student->photo_path): ?>
                <img src="<?php echo e(asset('storage/' . $habit->student->photo_path)); ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <i class="ph-fill ph-user text-3xl text-[#5295FF]"></i>
            <?php endif; ?>
        </div>
        <div>
            <h3 class="font-black text-2xl text-[#2A3B52] tracking-tight"><?php echo e($habit->student->name); ?></h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-[#2A3B52] border border-slate-200 text-[10px] font-black uppercase tracking-widest">
                    Kelas <?php echo e($habit->student->schoolClass->name ?? '-'); ?>

                </span>
                <span class="text-slate-400 text-xs font-medium flex items-center gap-1.5">
                    <i class="ph-bold ph-calendar-blank"></i>
                    <?php echo e(\Carbon\Carbon::parse($habit->report_date)->translatedFormat('d F Y')); ?>

                </span>
            </div>
        </div>
    </div>

    
    <?php if($habit->photo_path): ?>
    <div class="space-y-3">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-image text-sm text-[#5295FF]"></i> Bukti Dokumentasi
        </label>
        <div class="rounded-3xl overflow-hidden border border-slate-100 shadow-sm bg-slate-50 relative group p-2">
            <img src="<?php echo e(asset('storage/' . $habit->photo_path)); ?>" 
                class="w-full max-h-[400px] object-contain mx-auto rounded-2xl transition-transform hover:scale-[1.01] duration-500"
                alt="Bukti Kebiasaan Siswa">
                
            <a href="<?php echo e(asset('storage/' . $habit->photo_path)); ?>" target="_blank" class="absolute bottom-6 right-6 bg-white/90 backdrop-blur-md text-[#2A3B52] px-4 py-2 rounded-xl text-xs font-bold shadow-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2 border border-slate-200">
                <i class="ph-bold ph-arrows-out-simple"></i> Lihat Penuh
            </a>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="space-y-4">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-list-checks text-sm text-[#5295FF]"></i> Laporan 7 Kebiasaan
        </label>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            
            
            <div class="flex flex-col p-4 rounded-2xl border transition-colors <?php echo e(($habit->habit_1 && $habit->habit_2) ? 'bg-[#F3F9FD] border-[#D0E7F8]' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-sun-horizon <?php echo e(($habit->habit_1 && $habit->habit_2) ? 'text-[#5295FF]' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-[#2A3B52]">1. Bangun & Mandi</span>
                    </div>
                    <i class="ph-fill <?php echo e(($habit->habit_1 && $habit->habit_2) ? 'ph-check-circle text-[#5295FF]' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">
                    Bangun jam <?php echo e($habit->habit_1_time ? \Carbon\Carbon::parse($habit->habit_1_time)->format('H:i') : '-'); ?>

                </p>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border bg-white border-slate-100 shadow-sm md:row-span-2">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-mosque text-[#107C10] text-lg"></i>
                            <span class="text-sm font-bold text-[#2A3B52]">2. Ibadah Harian</span>
                        </div>

                        
                        <?php if($habit->is_udzur_syar_i): ?>
                            <span class="px-2 py-1 bg-[#FDE7E9] text-[#D13438] rounded-md border border-[#F4C3C9] text-[9px] font-black uppercase tracking-wider flex items-center gap-1">
                                <i class="ph-fill ph-flower-lotus"></i> Sedang Udzur
                            </span>
                        <?php endif; ?>
                    </div>

                    
                    <?php if($habit->is_udzur_syar_i): ?>
                        <div class="p-4 bg-[#FDE7E9] rounded-xl border border-[#F4C3C9] text-center">
                            <p class="text-xs text-[#D13438] font-bold">Siswa sedang berhalangan (Udzur Syar'i).</p>
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
                                    <i class="ph-fill <?php echo e($p[1] ? 'ph-check-circle text-[#107C10]' : 'ph-circle text-slate-200'); ?> text-lg"></i>
                                    <span class="text-xs font-bold <?php echo e($p[1] ? 'text-[#2A3B52]' : 'text-slate-400'); ?>"><?php echo e($p[0]); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="mt-auto pt-3 border-t border-slate-100 border-dashed">
                    <p class="text-[10px] font-black text-slate-400 uppercase mb-2 flex items-center gap-2">
                        <i class="ph-bold ph-microphone-stage text-[#5295FF]"></i> Laporan Tadarus
                    </p>
                    
                    <?php if($habit->odoa_surah || $habit->odoa_ayat || $habit->odoa_audio_path): ?>
                        <div class="bg-[#F3F9FD] rounded-xl p-3 border border-[#D0E7F8] relative overflow-hidden group">
                             <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#5295FF] shrink-0 border border-[#D0E7F8] shadow-sm">
                                        <i class="ph-fill ph-book-open-text"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-[#2A3B52]">
                                            <?php echo e($habit->odoa_surah ? $habit->odoa_surah : 'Belum isi surat'); ?> 
                                            <span class="text-slate-500 font-medium">: Ayat <?php echo e($habit->odoa_ayat ?? '-'); ?></span>
                                        </p>
                                    </div>
                                </div>

                                
                                <?php if($habit->odoa_audio_path): ?>
                                    <div class="mt-2">
                                        <audio controls controlsList="nodownload" class="w-full h-8 rounded-lg">
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

            
            <div class="flex flex-col p-4 rounded-2xl border transition-colors <?php echo e($habit->habit_3 ? 'bg-[#FFEFD6] border-[#FFD8A8]' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-sneaker-move <?php echo e($habit->habit_3 ? 'text-[#D83B01]' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-[#2A3B52]">3. Berolahraga</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_3 ? 'ph-check-circle text-[#D83B01]' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_3_activity): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">"<?php echo e($habit->habit_3_activity); ?>"</p>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border transition-colors <?php echo e($habit->habit_5 ? 'bg-[#FDE7E9] border-[#F4C3C9]' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-carrot <?php echo e($habit->habit_5 ? 'text-[#D13438]' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-[#2A3B52]">4. Makan Bergizi</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_5 ? 'ph-check-circle text-[#D13438]' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_5_menu): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">Menu: <?php echo e($habit->habit_5_menu); ?></p>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border transition-colors <?php echo e($habit->habit_4 ? 'bg-slate-100 border-slate-200' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-book-open-text <?php echo e($habit->habit_4 ? 'text-[#2A3B52]' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-[#2A3B52]">5. Gemar Belajar</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_4 ? 'ph-check-circle text-[#2A3B52]' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_4_subject): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">Mapel: <?php echo e($habit->habit_4_subject); ?></p>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col p-4 rounded-2xl border transition-colors <?php echo e($habit->habit_6 ? 'bg-[#F3F9FD] border-[#D0E7F8]' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-users-three <?php echo e($habit->habit_6 ? 'text-[#5295FF]' : 'text-slate-400'); ?> text-lg"></i>
                        <span class="text-sm font-bold text-[#2A3B52]">6. Bantu Orang Tua</span>
                    </div>
                    <i class="ph-fill <?php echo e($habit->habit_6 ? 'ph-check-circle text-[#5295FF]' : 'ph-x-circle text-slate-300'); ?>"></i>
                </div>
                <?php if($habit->habit_6_activity): ?>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">"<?php echo e($habit->habit_6_activity); ?>"</p>
                <?php endif; ?>
            </div>

            
            <div class="md:col-span-2 flex items-center justify-between p-4 rounded-2xl border transition-colors <?php echo e($habit->habit_7 ? 'bg-[#2A3B52] border-slate-700 text-white' : 'bg-slate-50 border-slate-100 opacity-60'); ?>">
                <div class="flex items-center gap-3">
                    <i class="ph-bold ph-moon <?php echo e($habit->habit_7 ? 'text-white' : 'text-slate-400'); ?> text-lg"></i>
                    <div>
                        <span class="text-sm font-bold <?php echo e($habit->habit_7 ? 'text-white' : 'text-[#2A3B52]'); ?>">7. Tidur Cepat</span>
                        <?php if($habit->habit_7_time): ?>
                            <p class="text-[10px] font-bold <?php echo e($habit->habit_7 ? 'text-blue-200' : 'text-[#5295FF]'); ?>">Jam: <?php echo e(\Carbon\Carbon::parse($habit->habit_7_time)->format('H:i')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <i class="ph-fill <?php echo e($habit->habit_7 ? 'ph-check-circle text-white' : 'ph-x-circle text-slate-300'); ?>"></i>
            </div>
        </div>
    </div>

    
    
    
    <?php
        // Cek jika relasi ke jurnal literasi ada dan mengambil 1 entri terbaru
        // (Pastikan di model Student ada relasi: public function literacyJournals() { return $this->hasMany(LiteracyJournal::class); })
        $recentLiteracy = isset($habit->student) && method_exists($habit->student, 'literacyJournals') 
                          ? $habit->student->literacyJournals()->latest()->first() 
                          : null;
    ?>

    <?php if($recentLiteracy): ?>
    <div class="mt-4 bg-[#F8FAFC] border border-slate-200 p-5 rounded-[2rem] shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <i class="ph-bold ph-books text-sm text-[#5295FF]"></i> Integrasi Literasi Terakhir
            </label>
            <span class="text-[9px] font-bold text-slate-400 bg-white px-2 py-1 rounded-md border border-slate-100">
                <?php echo e($recentLiteracy->created_at->diffForHumans()); ?>

            </span>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 items-start">
            <div class="flex-1">
                <h4 class="font-black text-[#2A3B52] text-sm leading-tight mb-1"><?php echo e($recentLiteracy->title); ?></h4>
                <p class="text-[10px] font-bold text-slate-500 flex items-center gap-1.5 mb-2">
                    <i class="ph-fill ph-pen-nib text-slate-400"></i> <?php echo e($recentLiteracy->author ?? 'Tanpa Penulis'); ?>

                    <span class="mx-1">•</span>
                    <span class="text-[#107C10] bg-[#DFF6DD] px-1.5 py-0.5 rounded"><?php echo e($recentLiteracy->pages_read); ?> Hal</span>
                </p>
                <div class="bg-white p-3 rounded-xl border border-slate-100 text-[11px] text-slate-600 italic leading-relaxed shadow-sm">
                    "<?php echo e(Str::limit($recentLiteracy->summary, 120)); ?>"
                </div>
            </div>
            
            <?php if($recentLiteracy->proof_image): ?>
            <div class="shrink-0 w-20 h-24 rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-white">
                <img src="<?php echo e(asset('storage/' . $recentLiteracy->proof_image)); ?>" class="w-full h-full object-cover" alt="Cover/Bukti Baca">
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    


    
    <div class="pt-6 border-t border-slate-100">
        <form action="<?php echo e(route('teacher.habits.feedback', $habit->id)); ?>" method="POST" id="form-feedback-<?php echo e($habit->id); ?>" onsubmit="submitFeedbackAjax(event, this, <?php echo e($habit->id); ?>)" class="bg-[#F3F9FD] p-6 rounded-[2rem] border border-[#D0E7F8] shadow-sm relative overflow-hidden">
            <?php echo csrf_field(); ?>
            <div class="absolute top-0 right-0 p-4 opacity-[0.03]"><i class="ph-fill ph-chat-centered-text text-[6rem] text-[#2A3B52]"></i></div>
            
            <div class="flex items-center justify-between mb-4 relative z-10">
                <label class="text-[10px] font-black text-[#5295FF] uppercase tracking-[0.2em] block">
                    Berikan Apresiasi / Catatan
                </label>
                
                <?php if($habit->teacher_feedback): ?>
                    <span class="text-[9px] bg-[#DFF6DD] text-[#107C10] px-2 py-1 rounded-md border border-[#B7DFB9] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm">
                        <i class="ph-bold ph-check-circle"></i> Sudah dinilai
                    </span>
                <?php endif; ?>
            </div>

            
            <div class="mb-3 relative z-10">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1">
                    <i class="ph-bold ph-lightning"></i> Balasan Cepat
                </p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" 
                        onclick="const ta = this.closest('form').querySelector('textarea[name=feedback]'); ta.value = '👍 Masya Allah, pertahankan kebiasaan baikmu Nak!'; ta.focus();" 
                        class="px-3 py-1.5 bg-white border border-[#D0E7F8] rounded-xl text-[10px] font-bold text-[#5295FF] hover:bg-[#5295FF] hover:text-white transition-all shadow-sm active:scale-95">
                        👍 Pertahankan
                    </button>
                    <button type="button" 
                        onclick="const ta = this.closest('form').querySelector('textarea[name=feedback]'); ta.value = '📖 Bagus sekali. Jangan lupa tingkatkan lagi tilawah dan hafalannya ya.'; ta.focus();" 
                        class="px-3 py-1.5 bg-white border border-[#D0E7F8] rounded-xl text-[10px] font-bold text-[#5295FF] hover:bg-[#5295FF] hover:text-white transition-all shadow-sm active:scale-95">
                        📖 Tilawah
                    </button>
                    <button type="button" 
                        onclick="const ta = this.closest('form').querySelector('textarea[name=feedback]'); ta.value = '📚 Hebat! Bapak/Ibu lihat kamu juga sedang rajin baca buku. Teruskan semangat literasinya!'; ta.focus();" 
                        class="px-3 py-1.5 bg-white border border-[#D0E7F8] rounded-xl text-[10px] font-bold text-[#5295FF] hover:bg-[#5295FF] hover:text-white transition-all shadow-sm active:scale-95">
                        📚 Pujian Literasi
                    </button>
                    <button type="button" 
                        onclick="const ta = this.closest('form').querySelector('textarea[name=feedback]'); ta.value = '⭐ Jurnal yang sangat lengkap. Bapak/Ibu guru bangga padamu!'; ta.focus();" 
                        class="px-3 py-1.5 bg-white border border-[#D0E7F8] rounded-xl text-[10px] font-bold text-[#5295FF] hover:bg-[#5295FF] hover:text-white transition-all shadow-sm active:scale-95">
                        ⭐ Sangat Lengkap
                    </button>
                </div>
            </div>

            <textarea name="feedback" rows="3" class="w-full bg-white border-slate-200 rounded-xl text-[#2A3B52] text-sm font-medium focus:ring-[#5295FF] focus:border-[#5295FF] placeholder-slate-400 relative z-10 shadow-sm resize-none" placeholder="Tulis pesan motivasi atau apresiasi untuk siswa..."><?php echo e($habit->teacher_feedback); ?></textarea>
            
            <div class="flex justify-end mt-4 relative z-10">
                <button type="submit" id="btn-submit-feedback" class="bg-[#5295FF] hover:bg-[#3b7ee6] text-white font-black text-[10px] uppercase tracking-widest px-8 py-3 rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                    <i class="ph-bold ph-paper-plane-right text-sm"></i> 
                    <?php echo e($habit->teacher_feedback ? 'Perbarui Feedback' : 'Kirim Feedback'); ?>

                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/partials/detail_modal.blade.php ENDPATH**/ ?>